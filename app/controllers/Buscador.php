<?php
class Buscador extends Controlador
{
    private $buscadorModelo;

    public function __construct()
    {
        session_start();
        $this->buscadorModelo = $this->modelo('Buscador_Model');
    }

    public function index()
    {
        $ciudad   = $_POST['ciudad']        ?? $_GET['ciudad']        ?? '';
        $fecha    = $_POST['fecha']         ?? $_GET['fecha']         ?? '';
        $servicio = $_POST['servicio']      ?? $_GET['servicio']      ?? '';

        if (isset($_POST['tipo_mascota'])) {
            $tipos = $_POST['tipo_mascota'];
        } elseif (!empty($_GET['tipo_mascota'])) {
            $tipos = explode(',', $_GET['tipo_mascota']);
        } else {
            $tipos = [];
        }

        $rawTam = $_POST['tamano'] ?? $_GET['tamano'] ?? '[]';
        $tamano = json_decode($rawTam, true);
        if (!is_array($tamano)) {
            $tamano = [];
        }

        $datos = [
            'ciudad'        => $ciudad,
            'fecha'         => $fecha,
            'servicio'      => $servicio,
            'tipo_mascota'  => $tipos,
            'tamano'        => $tamano,
            'api_filtrar'   => RUTA_URL . '/buscador/api_filtrar'
        ];

        $this->vista('buscador/inicio', $datos);
    }

    public function api_filtrar()
    {
        header('Content-Type: application/json');

        $ciudadInput      = $_POST['ciudad']       ?? $_GET['ciudad']       ?? '';
        $servicioInput    = $_POST['servicio']     ?? $_GET['servicio']     ?? '';
        $tamanoInput      = $_POST['tamano']       ?? $_GET['tamano']       ?? '[]';

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

        $tamanoArray = json_decode(urldecode($tamanoInput), true);
        if (!is_array($tamanoArray)) {
            $tamanoArray = [];
        }

        $modelo = $this->buscadorModelo;
        $todos  = $modelo->obtenerCuidadoresConMedia();

        $filtrados = array_filter($todos, function ($c) use (
            $ciudadInput,
            $tiposSeleccionados,
            $servicioInput,
            $tamanoArray,
            $modelo
        ) {
            if (
                $ciudadInput !== '' &&
                stripos($c->ciudad, $ciudadInput) === false
            ) {
                return false;
            }

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

        foreach ($filtrados as $c) {
            $c->servicios     = $modelo->obtenerServicios($c->id);
            $c->resenas       = $modelo->obtenerResenas($c->id);
            $c->total_resenas = count($c->resenas);

            if (!empty($c->resenas)) {
                usort($c->resenas, fn($a, $b) => $b->calificacion <=> $a->calificacion);
                $c->mejor_resena = $c->resenas[0]->comentario;
            } else {
                $c->mejor_resena = '';
            }

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
                            case 'paseo de perros':
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

        usort($filtrados, fn($a, $b) => $b->media_valoracion <=> $a->media_valoracion);
        $resultado = array_slice($filtrados, 0, 10);

        echo json_encode($resultado);
    }
}
