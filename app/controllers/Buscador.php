<?php
class Buscador extends Controlador
{
    private $cuidadoresModelo;

    public function __construct()
    {
        session_start();
        $this->cuidadoresModelo = $this->modelo('Cuidadores_Model');
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
        $tamanoInput = $_GET['tamano'] ?? '';
        // $fechaInput = $_GET['fecha'] ?? ''; // para implementar disponibilidad por fecha)
        $modelo = $this->cuidadoresModelo;
        $todos =  $this->cuidadoresModelo->obtenerCuidadoresConMedia();

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
            if (!empty($tipoMascotaInput) || !empty($tamanoInput)) {
                $tipos = $modelo->obtenerTiposMascotas($cuidador->id);
                $coincide = false;
                foreach ($tipos as $tipo) {
                    if (
                        (empty($tipoMascotaInput) || strtolower($tipo->tipo_mascota) === strtolower($tipoMascotaInput)) &&
                        (empty($tamanoInput) || strtolower($tipo->tamano) === strtolower($tamanoInput))
                    ) {
                        $coincide = true;
                        break;
                    }
                }
                if (!$coincide) return false;
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

        usort($filtrados, fn($a, $b) => $b->media_valoracion <=> $a->media_valoracion);

        echo json_encode(array_slice($filtrados, 0, 10));
    }
}
