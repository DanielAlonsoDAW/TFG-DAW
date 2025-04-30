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
}
