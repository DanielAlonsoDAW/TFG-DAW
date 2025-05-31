<?php
require_once RUTA_APP . '/librerias/Funciones.php';

class Resenas extends Controlador
{
    private $resenaModelo;
    private $reservaModelo;
    private $cuidadorModelo;
    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['usuario']) || $_SESSION['grupo'] !== 'dueno') {
            redireccionar('/autenticacion');
        }

        $this->resenaModelo = $this->modelo('Resenas_Model');
        $this->reservaModelo = $this->modelo('Reservas_Model');
        $this->cuidadorModelo = $this->modelo('Cuidadores_Model');
    }

    /**
     * Muestra el formulario para crear una nueva reseña.
     * @param int $reservaId
     */
    public function crear($reservaId)
    {
        // Verifica que la reserva exista y pertenezca al dueño autenticado
        $reserva = $this->reservaModelo->obtenerReservaPorId($reservaId);


        if (!$reserva || $reserva->duenio_id !== $_SESSION['usuario_id']) {
            redireccionar('/duenos/misReservas');
        }

        // Comprobar si ya existe reseña para esta reserva
        $resenaExistente = $this->resenaModelo->obtenerResenaPorReservaId($reservaId);
        if ($resenaExistente) {
            redireccionar('/resenas/editar/' . $resenaExistente->id);
        }

        // Comprobar que la reserva está finalizada
        if ($reserva->estado !== 'completada') {
            redireccionar('/duenos/misReservas');
        }

        $cuidador = $this->cuidadorModelo->obtenerCuidadorPorReservaId($reservaId);
        $reserva->cuidador_nombre = $cuidador->nombre ?? 'Desconocido';

        // Inicializar datos y errores
        $datos = [
            'reserva'     => $reserva,
            'calificacion' => '',
            'comentario'   => '',
            'errores'      => [
                'calificacion' => '',
                'comentario'   => ''
            ]
        ];

        // Si se ha enviado el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanear entradas
            $datos['calificacion'] = test_input($_POST['calificacion'] ?? '');
            $datos['comentario']   = test_input($_POST['comentario']   ?? '');

            // Validar calificación (número entre 1 y 5)
            if (!comprobarNumero($datos['calificacion']) || (int)$datos['calificacion'] < 1 || (int)$datos['calificacion'] > 5) {
                $datos['errores']['calificacion'] = 'La calificación debe ser un número entre 1 y 5.';
            }

            // Validar comentario no vacío
            if (!comprobarDatos($datos['comentario'])) {
                $datos['errores']['comentario'] = 'El comentario no puede estar vacío.';
            }

            // Si todo es válido
            if (formularioErrores(...array_values($datos['errores']))) {
                $nuevaResena = [
                    'reserva_id'   => $reservaId,
                    'duenio_id'    => $_SESSION['usuario_id'],
                    'cuidador_id'  => $reserva->cuidador_id,
                    'calificacion' => $datos['calificacion'],
                    'comentario'   => $datos['comentario']
                ];

                if ($this->resenaModelo->crearResena($nuevaResena)) {
                    redireccionar('/duenos/misReservas');
                } else {
                    // Todo-> Añadir erores a un log o envio de correo con errores registrarError('ERROR', 'Error al guardar reseña para reserva ' . $reservaId);
                    $datos['errores']['comentario'] = 'Hubo un error al guardar la reseña. Inténtalo más tarde.';
                }
            }
        }

        $this->vista('resenas/crear', $datos);
    }

    public function editar($resenaId)
    {
        // Obtener reseña y validar que exista
        $resena = $this->resenaModelo->obtenerResenaPorId($resenaId);
        if (!$resena || $resena->duenio_id !== $_SESSION['usuario_id']) {
            redireccionar('/duenos/misReservas');
        }

        // Obtener reserva asociada
        $reserva = $this->reservaModelo->obtenerReservaPorId($resena->reserva_id);
        if (!$reserva) {
            redireccionar('/duenos/misReservas');
        }

        // Obtener cuidador asociado a la reserva
        $cuidador = $this->cuidadorModelo->obtenerCuidadorPorReservaId($resena->reserva_id);
        $reserva->cuidador_nombre = $cuidador->nombre ?? 'Desconocido';

        // Inicializar datos y errores
        $datos = [
            'reserva'      => $reserva,
            'resena'       => $resena,
            'calificacion' => $resena->calificacion,
            'comentario'   => $resena->comentario,
            'errores'      => [
                'calificacion' => '',
                'comentario'   => ''
            ]
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanear entradas
            $datos['calificacion'] = test_input($_POST['calificacion'] ?? '');
            $datos['comentario']   = test_input($_POST['comentario'] ?? '');

            // Validar calificación (1 a 5)
            if (!comprobarNumero($datos['calificacion']) || (int)$datos['calificacion'] < 1 || (int)$datos['calificacion'] > 5) {
                $datos['errores']['calificacion'] = 'La calificación debe estar entre 1 y 5.';
            }

            // Validar comentario
            if (!comprobarDatos($datos['comentario'])) {
                $datos['errores']['comentario'] = 'El comentario no puede estar vacío.';
            }

            // Si todo está correcto
            if (formularioErrores(...array_values($datos['errores']))) {
                $nuevaData = [
                    'id'           => $resenaId,
                    'calificacion' => $datos['calificacion'],
                    'comentario'   => $datos['comentario']
                ];

                if ($this->resenaModelo->actualizarResena($nuevaData)) {
                    redireccionar('/duenos/misReservas');
                } else {
                    // Todo-> Añadir erores a un log o envio de correo con errores registrarError('ERROR', 'Error al actualizar reseña ID: ' . $resenaId);
                    $datos['errores']['comentario'] = 'Hubo un error al actualizar la reseña.';
                }
            }
        }

        // Mostrar vista de edición con datos actuales o errores
        $this->vista('resenas/editar', $datos);
    }
}
