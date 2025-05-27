<?php

// Controlador API
class Api extends Controlador
{
    // Método para obtener imágenes de portada de perros y gatos
    public function imagenesPortada()
    {
        header('Content-Type: application/json'); // Respuesta en JSON

        // Obtener claves de las APIs desde variables de entorno
        $claveGatos = getenv('CAT_API_KEY');
        $clavePerros = getenv('DOG_API_KEY');

        // URLs y claves para las APIs de perros y gatos
        $urls = [
            ["tipo" => "perro", "url" => "https://api.thedogapi.com/v1/images/search?limit=6", "clave" => $clavePerros],
            ["tipo" => "gato", "url" => "https://api.thecatapi.com/v1/images/search?limit=6", "clave" => $claveGatos]
        ];

        $imagenes = [];

        // Recorrer cada API (perros y gatos)
        foreach ($urls as $peticion) {
            // Opciones de cabecera para la petición HTTP
            $opciones = [
                'http' => [
                    'header' => "x-api-key: {$peticion['clave']}\r\n"
                ]
            ];

            $contexto = stream_context_create($opciones);
            $respuesta = file_get_contents($peticion["url"], false, $contexto);

            // Si la respuesta es válida, decodificar y guardar las imágenes
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

        // Separar imágenes por tipo
        $perros = array_values(array_filter($imagenes, fn($i) => $i["tipo"] === "perro"));
        $gatos  = array_values(array_filter($imagenes, fn($i) => $i["tipo"] === "gato"));

        // Intercalar imágenes de perros y gatos
        $intercaladas = [];
        for ($i = 0; $i < count($perros); $i++) {
            if (isset($perros[$i])) $intercaladas[] = $perros[$i];
            if (isset($gatos[$i]))  $intercaladas[] = $gatos[$i];
        }

        // Devolver el resultado en JSON
        echo json_encode($intercaladas);
    }

    // Método para calcular la distancia entre dos direcciones usando OpenRoute Service
    public function calcularDistancia()
    {
        header('Content-Type: application/json'); // Respuesta en JSON

        // Leer el cuerpo de la petición (JSON)
        $raw = file_get_contents("php://input");

        // Convertir a UTF-8 si es necesario
        $rawUtf8 = mb_convert_encoding($raw, 'UTF-8', mb_detect_encoding($raw, 'UTF-8, ISO-8859-1, Windows-1252', true));

        $datos = json_decode($rawUtf8, true);

        // Validar que el cuerpo sea JSON válido
        if (!is_array($datos)) {
            echo json_encode(['error' => 'El cuerpo no es JSON válido', 'raw' => $raw]);
            exit;
        }

        // Obtener direcciones de origen y destino
        $origen = trim($datos['origen'] ?? '');
        $destino = trim($datos['destino'] ?? '');

        // Validar que ambas direcciones estén presentes
        if (!$origen || !$destino) {
            echo json_encode(['error' => 'Direcciones requeridas']);
            exit;
        }

        // Función interna para geocodificar una dirección usando Nominatim
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

        // Geocodificar origen y destino
        $coorOrigen = geocodificar($origen);
        $coorDestino = geocodificar($destino);

        // Validar que ambas coordenadas sean válidas
        if (!$coorOrigen || !$coorDestino) {
            echo json_encode([
                'error' => 'No se pudo geocodificar',
                'origen' => $coorOrigen,
                'destino' => $coorDestino
            ]);
            exit;
        }

        // Preparar datos para la API de OpenRouteService
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

        // Realizar la petición a OpenRouteService usando cURL
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

        // Validar respuesta de la API
        if ($response === false) {
            echo json_encode([
                'error' => 'Error en la solicitud a OpenRouteService',
                'detalle' => $curlError // Mostrar detalle del error
            ]);
            exit;
        }

        $data = json_decode($response, true);
        // Si la respuesta contiene la distancia, devolverla
        if (isset($data['features'][0]['properties']['summary']['distance'])) {
            $distanciaKm = $data['features'][0]['properties']['summary']['distance'] / 1000;
            echo json_encode(['distancia_km' => round($distanciaKm, 2)]);
        } else {
            // Si no, mostrar el error y la respuesta de la API
            echo json_encode([
                'error' => 'No se pudo calcular la ruta',
                'respuesta_ors' => $data
            ]);
        }
    }
}
