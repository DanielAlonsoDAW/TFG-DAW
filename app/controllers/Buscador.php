<?php
class Buscador extends Controlador
{
    private $buscadorModelo;

    public function __construct()
    {
        session_start();
        // Carga el modelo Buscador_Model
        $this->buscadorModelo = $this->modelo('Buscador_Model');
    }

    // Muestra la vista principal del buscador
    public function index()
    {
        // Obtiene los filtros desde POST o GET
        $ciudad   = $_POST['ciudad']        ?? $_GET['ciudad']        ?? '';
        $servicio = $_POST['servicio']      ?? $_GET['servicio']      ?? '';

        // Obtiene los tipos de mascota seleccionados
        if (isset($_POST['tipo_mascota'])) {
            $tipos = $_POST['tipo_mascota'];
        } elseif (!empty($_GET['tipo_mascota'])) {
            $tipos = explode(',', $_GET['tipo_mascota']);
        } else {
            $tipos = [];
        }

        // Obtiene los tamaños seleccionados (array)
        $rawTam = $_POST['tamano'] ?? $_GET['tamano'] ?? '[]';
        $tamano = json_decode($rawTam, true);
        if (!is_array($tamano)) {
            $tamano = [];
        }

        // Prepara los datos para la vista
        $datos = [
            'ciudad'        => $ciudad,
            'servicio'      => $servicio,
            'tipo_mascota'  => $tipos,
            'tamano'        => $tamano,
            'api_filtrar'   => RUTA_URL . '/buscador/api_filtrar'
        ];

        // Carga la vista buscador/inicio con los datos
        $this->vista('buscador/inicio', $datos);
    }

    // API para filtrar cuidadores según los filtros recibidos
    public function api_filtrar()
    {
        header('Content-Type: application/json');

        // Obtiene los filtros desde POST o GET
        $ciudadInput      = $_POST['ciudad']       ?? $_GET['ciudad']       ?? '';
        $servicioInput    = $_POST['servicio']     ?? $_GET['servicio']     ?? '';
        $tamanoInput      = $_POST['tamano']       ?? $_GET['tamano']       ?? '[]';

        // Procesa los tipos de mascota seleccionados
        $tipoRaw = $_POST['tipo_mascota'] ?? $_GET['tipo_mascota'] ?? [];
        if (is_array($tipoRaw)) {
            $tiposSeleccionados = array_map('strtolower', $tipoRaw);
        } elseif (is_string($tipoRaw)) {
            $tiposSeleccionados = array_map(
                'strtolower',
                array_filter(explode(',', $tipoRaw))
            );
        } else {
            $tiposSeleccionados = [];
        }

        // Procesa los tamaños seleccionados
        $tamanoArray = json_decode(urldecode($tamanoInput), true);
        if (!is_array($tamanoArray)) {
            $tamanoArray = [];
        }

        $modelo = $this->buscadorModelo;
        // Obtiene todos los cuidadores con su media de valoración
        $todos  = $modelo->obtenerCuidadoresConMedia();

        // Filtra los cuidadores según los filtros
        $filtrados = array_filter($todos, function ($c) use (
            $ciudadInput,
            $tiposSeleccionados,
            $servicioInput,
            $tamanoArray,
            $modelo
        ) {
            // Filtra por ciudad
            if (
                $ciudadInput !== '' &&
                stripos($c->ciudad, $ciudadInput) === false
            ) {
                return false;
            }

            // Filtra por tipo de mascota
            if (!empty($tiposSeleccionados)) {
                $tiposCuidador = array_map(
                    fn($t) => strtolower($t->tipo_mascota),
                    $modelo->obtenerTiposMascotas($c->id)
                );
                $coincide = false;
                foreach ($tiposSeleccionados as $t) {
                    if (in_array($t, $tiposCuidador)) {
                        $coincide = true;
                        break;
                    }
                }
                if (!$coincide) return false;
            }

            // Filtra por tamaño de mascota
            if (!empty($tamanoArray)) {
                $combosCuidador = array_map(
                    fn($t) => [
                        'tipo'   => strtolower($t->tipo_mascota),
                        'tamano' => strtolower($t->tamano)
                    ],
                    $modelo->obtenerTiposMascotas($c->id)
                );
                $match = false;
                foreach ($tamanoArray as $e) {
                    $eTipo   = strtolower($e['tipo']   ?? '');
                    $eTamaño = strtolower($e['tamano'] ?? '');
                    foreach ($combosCuidador as $cu) {
                        if ($cu['tipo'] === $eTipo && $cu['tamano'] === $eTamaño) {
                            $match = true;
                            break 2;
                        }
                    }
                }
                if (!$match) return false;
            }

            // Filtra por servicio
            if ($servicioInput !== '') {
                $servs = $modelo->obtenerServicios($c->id);
                $ok    = false;
                foreach ($servs as $s) {
                    if (strtolower($s->servicio) === strtolower($servicioInput)) {
                        $ok = true;
                        break;
                    }
                }
                if (!$ok) return false;
            }

            return true;
        });

        // Añade información adicional a cada cuidador filtrado
        foreach ($filtrados as $c) {
            $c->servicios     = $modelo->obtenerServicios($c->id);
            $c->resenas       = $modelo->obtenerResenas($c->id);
            $c->total_resenas = count($c->resenas);

            // Obtiene la mejor reseña (mayor calificación)
            if (!empty($c->resenas)) {
                usort($c->resenas, fn($a, $b) => $b->calificacion <=> $a->calificacion);
                $c->mejor_resena = $c->resenas[0]->comentario;
            } else {
                $c->mejor_resena = '';
            }

            // Formatea el precio del servicio seleccionado
            $c->precio_servicio = '';
            if ($servicioInput !== '') {
                foreach ($c->servicios as $s) {
                    if (strtolower($s->servicio) === strtolower($servicioInput)) {
                        $precio = number_format($s->precio, 2);
                        switch (strtolower($s->servicio)) {
                            case 'taxi':
                                $c->precio_servicio = "10€ + {$precio}€/km";
                                break;
                            case 'alojamiento':
                                $c->precio_servicio = "{$precio}€/noche";
                                break;
                            case 'guardería de día':
                                $c->precio_servicio = "{$precio}€/día";
                                break;
                            case 'paseos':
                                $c->precio_servicio = "{$precio}€/paseo";
                                break;
                            case 'cuidado a domicilio':
                            case 'visitas a domicilio':
                                $c->precio_servicio = "{$precio}€/visita";
                                break;
                        }
                        break;
                    }
                }
            }
        }

        // Ordena los cuidadores por media de valoración descendente y limita a 10 resultados
        usort($filtrados, fn($a, $b) => $b->media_valoracion <=> $a->media_valoracion);
        $resultado = array_slice($filtrados, 0, 10);

        // Devuelve el resultado en formato JSON
        echo json_encode($resultado);
    }
}
