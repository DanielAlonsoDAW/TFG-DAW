<?php
class Buscador extends Controlador
{
    private $buscadorModelo;

    public function __construct()
    {
        session_start();
        $this->buscadorModelo = $this->modelo('Cuidadores_Model');
    }

    // Vista principal
    public function index()
    {
        $this->vista('buscador/inicio');
    }

    // API para obtener cuidadores filtrados por ciudad y ordenados por media de valoración
    public function api_filtrar()
    {
        header('Content-Type: application/json');

        $ciudadInput = $_GET['ciudad'] ?? '';
        $tipoMascotaInput = $_GET['tipo_mascota'] ?? '';
        $servicioInput = $_GET['servicio'] ?? '';
        $tamanoInput = $_GET['tamano'] ?? '[]';
        $tamanoArray = json_decode(urldecode($tamanoInput), true);

        // $fechaInput = $_GET['fecha'] ?? ''; // para implementar disponibilidad por fecha)
        $modelo = $this->buscadorModelo;
        $todos =  $this->buscadorModelo->obtenerCuidadoresConMedia();

        $filtrados = array_filter($todos, function ($cuidador) use (
            $ciudadInput,
            $tipoMascotaInput,
            $servicioInput,
            $tamanoInput,
            $modelo
        ) {
            // Filtro ciudad
            if (!empty($ciudadInput) && stripos($cuidador->ciudad, $ciudadInput) === false) {
                return false;
            }

            // Filtro tipo mascota y tamaño
            if (!empty($tamanoArray)) {
                $tipos = $modelo->obtenerTiposMascotas($cuidador->id);
                $cuidadorCombinaciones = array_map(fn($t) => [
                    'tipo' => strtolower($t->tipo_mascota),
                    'tamano' => strtolower($t->tamano)
                ], $tipos);

                $match = false;
                foreach ($tamanoArray as $entrada) {
                    $entradaTipo = strtolower($entrada['tipo']);
                    $entradaTamano = strtolower($entrada['tamano']);

                    foreach ($cuidadorCombinaciones as $c) {
                        if ($c['tipo'] === $entradaTipo && $c['tamano'] === $entradaTamano) {
                            $match = true;
                            break 2;
                        }
                    }
                }
                if (!$match) return false;
            }

            // Filtro por servicio
            if (!empty($servicioInput)) {
                $servicios = $modelo->obtenerServicios($cuidador->id);
                $coincide = false;
                foreach ($servicios as $s) {
                    if (strtolower($s->servicio) === strtolower($servicioInput)) {
                        $coincide = true;
                        break;
                    }
                }
                if (!$coincide) return false;
            }

            return true;
        });

        foreach ($filtrados as $cuidador) {
            // Servicios del cuidador
            $cuidador->servicios = $modelo->obtenerServicios($cuidador->id);

            // Reseñas del cuidador
            $cuidador->resenas = $modelo->obtenerResenas($cuidador->id);
            $cuidador->total_resenas = count($cuidador->resenas);

            // Mejor reseña
            $cuidador->mejor_resena = '';
            if (!empty($cuidador->resenas)) {
                usort($cuidador->resenas, fn($a, $b) => $b->calificacion <=> $a->calificacion);
                $cuidador->mejor_resena = $cuidador->resenas[0]->comentario;
            }

            // Precio del servicio seleccionado
            $cuidador->precio_servicio = '';
            if (!empty($servicioInput)) {
                foreach ($cuidador->servicios as $s) {
                    if (strtolower($s->servicio) === strtolower($servicioInput)) {
                        $precio = number_format($s->precio, 2);

                        switch (strtolower($s->servicio)) {
                            case 'taxi':
                                $cuidador->precio_servicio = "10€ + {$precio}€/km";
                                break;
                            case 'alojamiento':
                                $cuidador->precio_servicio = "{$precio}€/noche";
                                break;
                            case 'guardería de día':
                                $cuidador->precio_servicio = "{$precio}€/día";
                                break;
                            case 'paseos':
                            case 'paseo de perros':
                                $cuidador->precio_servicio = "{$precio}€/paseo";
                                break;
                            case 'cuidado a domicilio':
                            case 'visitas a domicilio':
                                $cuidador->precio_servicio = "{$precio}€/visita";
                                break;
                        }
                        break; 
                    }
                }
            }        }

        // Ordenar por media de valoración

        usort($filtrados, fn($a, $b) => $b->media_valoracion <=> $a->media_valoracion);

        echo json_encode(array_slice($filtrados, 0, 10));
    }
}
