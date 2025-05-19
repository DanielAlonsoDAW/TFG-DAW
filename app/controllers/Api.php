<?php

class Api extends Controlador
{
    public function imagenesPortada()
    {
        header('Content-Type: application/json');

        $claveGatos = getenv('CAT_API_KEY');
        $clavePerros = getenv('DOG_API_KEY');


        $urls = [
            ["tipo" => "perro", "url" => "https://api.thedogapi.com/v1/images/search?limit=6", "clave" => $clavePerros],
            ["tipo" => "gato", "url" => "https://api.thecatapi.com/v1/images/search?limit=6", "clave" => $claveGatos]
        ];

        $imagenes = [];

        foreach ($urls as $peticion) {
            $opciones = [
                'http' => [
                    'header' => "x-api-key: {$peticion['clave']}\r\n"
                ]
            ];

            $contexto = stream_context_create($opciones);
            $respuesta = file_get_contents($peticion["url"], false, $contexto);

            if ($respuesta !== false) {
                $datos = json_decode($respuesta, true);
                foreach ($datos as $imagen) {
                    $imagenes[] = [
                        "tipo" => $peticion["tipo"],
                        "url" => $imagen["url"]
                    ];
                }
            }
        }

        // Alternar entre perro y gato para UX más amigable
        $perros = array_values(array_filter($imagenes, fn($i) => $i["tipo"] === "perro"));
        $gatos  = array_values(array_filter($imagenes, fn($i) => $i["tipo"] === "gato"));


        $intercaladas = [];
        for ($i = 0; $i < count($perros); $i++) {
            if (isset($perros[$i])) $intercaladas[] = $perros[$i];
            if (isset($gatos[$i]))  $intercaladas[] = $gatos[$i];
        }

        echo json_encode($intercaladas);
    }

    public function calcularDistancia()
    {
        header('Content-Type: application/json');

        // Leer JSON del body
        $raw = file_get_contents("php://input");

        // Convertir a UTF-8 si no lo está
        $rawUtf8 = mb_convert_encoding($raw, 'UTF-8', mb_detect_encoding($raw, 'UTF-8, ISO-8859-1, Windows-1252', true));

        $datos = json_decode($rawUtf8, true);

        if (!is_array($datos)) {
            echo json_encode(['error' => 'El cuerpo no es JSON válido', 'raw' => $raw]);
            exit;
        }

        $origen = trim($datos['origen'] ?? '');
        $destino = trim($datos['destino'] ?? '');

        if (!$origen || !$destino) {
            echo json_encode(['error' => 'Direcciones requeridas']);
            exit;
        }

        // Función segura para geocodificar usando Nominatim
        function geocodificar($direccion)
        {
            $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($direccion);

            $opts = [
                "http" => [
                    "header" => "User-Agent: MiAppPHP/1.0\r\n"
                ]
            ];
            $context = stream_context_create($opts);
            $json = @file_get_contents($url, false, $context);

            if (!$json) return null;

            $res = json_decode($json, true);
            return $res[0] ?? null;
        }

        $coorOrigen = geocodificar($origen);
        $coorDestino = geocodificar($destino);

        if (!$coorOrigen || !$coorDestino) {
            echo json_encode([
                'error' => 'No se pudo geocodificar',
                'origen' => $coorOrigen,
                'destino' => $coorDestino
            ]);
            exit;
        }

        // Preparar datos para OpenRouteService
        $apiKey = "5b3ce3597851110001cf624890f393bd77d040f7a519dcf050146271"; //getenv('ORS_API_KEY');
        if (!$apiKey) {
            echo json_encode(['error' => 'API Key no configurada']);
            exit;
        }

        $body = [
            "coordinates" => [
                [(float)$coorOrigen['lon'], (float)$coorOrigen['lat']],
                [(float)$coorDestino['lon'], (float)$coorDestino['lat']]
            ]
        ];

        $ch = curl_init("https://api.openrouteservice.org/v2/directions/driving-car/geojson");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . $apiKey,
                'Content-Type: application/json'
            ],
            CURLOPT_POSTFIELDS => json_encode($body)
        ]);

        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            echo json_encode([
                'error' => 'Error en la solicitud a OpenRouteService',
                'detalle' => $curlError // 👈 Añade esto para saber qué falla exactamente
            ]);
            exit;
        }

        $data = json_decode($response, true);
        if (isset($data['features'][0]['properties']['summary']['distance'])) {
            $distanciaKm = $data['features'][0]['properties']['summary']['distance'] / 1000;
            echo json_encode(['distancia_km' => round($distanciaKm, 2)]);
        } else {
            echo json_encode([
                'error' => 'No se pudo calcular la ruta',
                'respuesta_ors' => $data
            ]);
        }
    }
}
