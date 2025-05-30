<?php

require RUTA_APP . "/librerias/Funciones.php";
class Reservas extends Controlador
{
    private $reservaModelo;
    private $cuidadorModelo;
    private $mascotaModelo;
    public function __construct()
    {
        session_start();

        $this->reservaModelo = $this->modelo('Reservas_Model');
        $this->cuidadorModelo = $this->modelo('Cuidadores_Model');
        $this->mascotaModelo = $this->modelo('Mascotas_Model');
    }

    public function crear($cuidador_id)
    {
        if (!isset($_SESSION['usuario']) || $_SESSION['grupo'] != 'dueno') {
            redireccionar('/autenticacion');
        }
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = [
                'duenio_id'      => $_SESSION['usuario_id'],
                'cuidador_id'    => $cuidador_id,
                'servicio'       => test_input($_POST['servicio']),
                'fecha_inicio'   => test_input($_POST['fecha_inicio']),
                'fecha_fin'      => test_input($_POST['fecha_fin']),
                'mascotas'       => $_POST['mascotas'] ?? [],
                'origen'         => test_rutas_locales($_POST['direccion_origen'] ?? ''),
                'destino'        => test_rutas_locales($_POST['direccion_destino'] ?? '')
            ];

            $esTaxi = $datos['servicio'] === 'Taxi';

            // Validaciones base
            if (
                !comprobarDatos($datos['servicio']) ||
                !comprobarFecha($datos['fecha_inicio']) ||
                (!$esTaxi && !comprobarFecha($datos['fecha_fin'])) ||
                !is_array($datos['mascotas']) ||
                empty($datos['mascotas'])
            ) {
                $errores[] = "Todos los campos obligatorios deben estar correctamente completados.";
            }

            // Validación de fecha de inicio
            if (!comprobarFecha_Inicio($datos['fecha_inicio'])) {
                $errores[] = "La fecha de inicio debe ser posterior a hoy.";
            }

            // Validación de fecha de fin
            if (!$esTaxi && !comprobarFecha_Fin($datos['fecha_inicio'], $datos['fecha_fin'])) {
                $errores[] = "La fecha de fin debe ser posterior a la de inicio.";
            }

            // Validación de mascotas
            if (!$this->mascotaModelo->mascotasValidas($datos['mascotas'], $_SESSION['usuario_id'])) {
                $errores[] = "Una o más mascotas seleccionadas no son válidas.";
            }

            // Validación de cuidador
            $totalConfirmadas = $this->reservaModelo->contarMascotasConfirmadas($cuidador_id, $datos['fecha_inicio'], $datos['fecha_fin']);
            $mascotasExtra    = count($datos['mascotas']);
            $max              = $this->reservaModelo->obtenerMaxMascotasCuidador($cuidador_id);

            if (($totalConfirmadas + $mascotasExtra) > $max) {
                $errores[] = "El cuidador no dispone de plazas suficientes para las fechas indicadas.";
            }

            // Validación de servicio Taxi
            if ($this->reservaModelo->cuidadorOcupadoPorDomicilio($cuidador_id, $datos['fecha_inicio'], $datos['fecha_fin'])) {
                $errores[] = "El cuidador ya tiene una reserva de 'Cuidado a domicilio' en esas fechas.";
            }

            // Validación de direcciones para servicio Taxi
            if (empty($errores)) {
                $precioBase = $this->reservaModelo->obtenerPrecioPorServicio($cuidador_id, $datos['servicio']);
                $total = 0;


                if ($esTaxi) {
                    $distanciaKm = calcularDistanciaKm($datos['origen'], $datos['destino']);
                    if ($distanciaKm === null) {
                        $errores[] = "No se pudo calcular la distancia para el servicio Taxi.";
                    } else {
                        $tarifaPorKm     = $precioBase;
                        $suplementoFijo  = 10.00;
                        $total           = $suplementoFijo + ($tarifaPorKm * $distanciaKm * $mascotasExtra);
                    }
                } else {
                    $dias = calcularDiferenciaFechas($datos['fecha_inicio'], $datos['fecha_fin']);
                    if (!in_array($datos['servicio'], ['Alojamiento', 'Cuidado a domicilio']) && $dias > 0) {
                        $dias++;
                    }
                    $total = $precioBase * $mascotasExtra * $dias;
                }
            }

            if (empty($errores)) {
                $reservaId = $this->reservaModelo->crearReserva($datos, $total);

                foreach ($datos['mascotas'] as $mascotaId) {
                    $this->reservaModelo->asociarMascota($reservaId, $mascotaId);
                }

                redireccionar('/duenos/misReservas');
            }

            // Si hay errores, volver a mostrar la vista con los errores
            $cuidador = $this->cuidadorModelo->obtenerPerfilCuidador($cuidador_id);
            $servicios = $this->cuidadorModelo->obtenerServicios($cuidador_id);
            $mascotas = $this->reservaModelo->obtenerMascotas($_SESSION['usuario_id']);
            $reservas = $this->reservaModelo->obtenerReservasConfirmadas($cuidador_id);

            $precios = [];
            foreach ($servicios as $serv) {
                $precios[$serv->servicio] = (float) $serv->precio;
            }

            $datos = [
                'cuidador' => $cuidador,
                'mascotas' => $mascotas,
                'precios' => $precios,
                'reservas' => $reservas,
                'errores' => $errores
            ];

            $this->vista('reservas/crear', $datos);
        } else {
            // Mostrar formulario inicial
            $cuidador = $this->cuidadorModelo->obtenerPerfilCuidador($cuidador_id);
            $servicios = $this->cuidadorModelo->obtenerServicios($cuidador_id);
            $mascotas = $this->reservaModelo->obtenerMascotas($_SESSION['usuario_id']);
            $reservas = $this->reservaModelo->obtenerReservasConfirmadas($cuidador_id);

            $precios = [];
            foreach ($servicios as $serv) {
                $precios[$serv->servicio] = (float) $serv->precio;
            }

            $datos = [
                'cuidador' => $cuidador,
                'mascotas' => $mascotas,
                'precios' => $precios,
                'reservas' => $reservas,
                'errores' => []
            ];

            $this->vista('reservas/crear', $datos);
        }
    }

    public function cancelar($id_reserva)
    {
        $reserva = $this->reservaModelo->obtenerReservaPorId($id_reserva);

        if ($_SESSION['grupo'] == 'dueno') {
            // Si el usuario es dueño, comprobar que la reserva pertenece al dueño
            if (!$reserva || $reserva->duenio_id != $_SESSION['usuario_id'] || comprobarFecha_Cancelacion($reserva->fecha_inicio)) {
                // Si la reserva no existe o no pertenece al dueño, redireccionar
                redireccionar('/duenos/misReservas');
            }
            // Cancelar la reserva
            $this->reservaModelo->cancelarReserva($id_reserva);

            redireccionar('/duenos/misReservas');
        } else {
            // Si el usuario no es dueño, redireccionar
            redireccionar('/autenticacion');
        }
    }

    public function rechazar($id_reserva)
    {
        $reserva = $this->reservaModelo->obtenerReservaPorId($id_reserva);

        if ($_SESSION['grupo'] == 'cuidador') {
            if (!$reserva || $reserva->cuidador_id != $_SESSION['usuario_id'] || comprobarFecha_Cancelacion($reserva->fecha_inicio)) {
                // Si la reserva no existe o no pertenece al cuidador, redireccionar
                redireccionar('/cuidadores/misReservas');
            }

            // Rechazar la reserva
            $this->reservaModelo->rechazarReserva($id_reserva);

            redireccionar('/cuidadores/misReservas');
        } else {
            // Si el usuario no es cuidador, redireccionar
            redireccionar('/autenticacion');
        }
    }
}
