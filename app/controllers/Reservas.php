<?php

require RUTA_APP . "/librerias/Funciones.php";
class Reservas extends Controlador
{
    private $reservaModelo;
    private $cuidadorModelo;
    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['usuario']) || $_SESSION['grupo'] != 'dueno') {
            redireccionar('/autenticacion');
        }
        $this->reservaModelo = $this->modelo('Reservas_Model');
        $this->cuidadorModelo = $this->modelo('Cuidadores_Model');
    }

    public function crear($cuidador_id)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = [
                'duenio_id' => $_SESSION['usuario_id'],
                'cuidador_id' => $cuidador_id,
                'servicio' => $_POST['servicio'],
                'fecha_inicio' => $_POST['fecha_inicio'],
                'fecha_fin' => $_POST['fecha_fin'],
                'mascotas' => $_POST['mascotas'],
                'origen' => $_POST['direccion_origen'] ?? null,
                'destino' => $_POST['direccion_destino'] ?? null
            ];

            // Validar disponibilidad
            $totalConfirmadas = $this->reservaModelo->contarMascotasConfirmadas($cuidador_id, $datos['fecha_inicio'], $datos['fecha_fin']);
            $mascotasExtra = count($datos['mascotas']);
            $max = $this->reservaModelo->obtenerMaxMascotasCuidador($cuidador_id);

            if (($totalConfirmadas + $mascotasExtra) > $max) {
                die("Este cuidador no tiene disponibilidad en esas fechas.");
            }

            // Verificar si el cuidador ya tiene una reserva de "Cuidado a domicilio" en ese rango
            $tieneReservaDomicilio = $this->reservaModelo->cuidadorOcupadoPorDomicilio($cuidador_id, $datos['fecha_inicio'], $datos['fecha_fin']);
            if ($tieneReservaDomicilio) {
                die("El cuidador ya tiene una reserva de 'Cuidado a domicilio' en esas fechas y no está disponible.");
            }

            // Calcular precio
            $precioBase = $this->reservaModelo->obtenerPrecioPorServicio($cuidador_id, $datos['servicio']);
            $total = $precioBase * $mascotasExtra;

            if ($datos['servicio'] == 'Taxi') {
                $total += 10.00;
            }

            // Guardar reserva
            $reservaId = $this->reservaModelo->crearReserva($datos, $total);

            // Guardar mascotas
            foreach ($datos['mascotas'] as $mascotaId) {
                $this->reservaModelo->asociarMascota($reservaId, $mascotaId);
            }

            redireccionar('/duenos/misReservas');
        } else {
            // Mostrar formulario
            $cuidador = $this->cuidadorModelo->obtenerPerfilCuidador($cuidador_id);
            $servicios = $this->cuidadorModelo->obtenerServicios($cuidador_id);
            $mascotas = $this->reservaModelo->obtenerMascotas($_SESSION['usuario_id']);
            $reservas = $this->reservaModelo->obtenerReservasConfirmadas($cuidador_id);

            $precios = [];
            foreach ($servicios as $serv) {
                $precios[$serv->servicio] = (float) $serv->precio;
            }

            $datos = ['cuidador' => $cuidador, 'mascotas' => $mascotas, 'precios' => $precios, 'reservas' => $reservas];
            $this->vista('reservas/crear', $datos);
        }
    }
}
