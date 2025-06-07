<?php
require_once RUTA_APP . '/librerias/Funciones.php';

/**
 * Controlador para gestionar las reseñas de los dueños sobre los cuidadores.
 */
class Resenas extends Controlador
{
    private $resenaModelo;
    private $reservaModelo;
    private $cuidadorModelo;

    /**
     * Constructor: inicializa modelos y verifica autenticación de dueño.
     */
    public function __construct()
    {
        session_start();
        // Verifica que el usuario esté autenticado y sea del grupo 'dueno'
        if (!isset($_SESSION['usuario']) || $_SESSION['grupo'] !== 'dueno') {
            redireccionar('/autenticacion');
        }

        // Carga los modelos necesarios
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
                    $datos['errores']['comentario'] = 'Hubo un error al guardar la reseña. Inténtalo más tarde.';
                }
            }
        }

        $this->vista('resenas/crear', $datos);
    }

    /**
     * Muestra y procesa el formulario para editar una reseña existente.
     * @param int $resenaId
     */
    public function editar($resenaId)
    {
        // Obtener reseña y validar que exista y pertenezca al dueño autenticado
        $resena = $this->resenaModelo->obtenerResenaPorId($resenaId);
        if (!$resena || $resena->duenio_id !== $_SESSION['usuario_id']) {
            redireccionar('/duenos/misReservas');
        }

        // Obtener reserva asociada a la reseña
        $reserva = $this->reservaModelo->obtenerReservaPorId($resena->reserva_id);
        if (!$reserva) {
            redireccionar('/duenos/misReservas');
        }

        // Obtener cuidador asociado a la reserva
        $cuidador = $this->cuidadorModelo->obtenerCuidadorPorReservaId($resena->reserva_id);
        $reserva->cuidador_nombre = $cuidador->nombre ?? 'Desconocido';

        // Inicializar datos y errores para la vista
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

        // Si se envía el formulario (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanear entradas del usuario
            $datos['calificacion'] = test_input($_POST['calificacion'] ?? '');
            $datos['comentario']   = test_input($_POST['comentario'] ?? '');

            // Validar calificación (debe ser número entre 1 y 5)
            if (!comprobarNumero($datos['calificacion']) || (int)$datos['calificacion'] < 1 || (int)$datos['calificacion'] > 5) {
                $datos['errores']['calificacion'] = 'La calificación debe estar entre 1 y 5.';
            }

            // Validar comentario (no vacío)
            if (!comprobarDatos($datos['comentario'])) {
                $datos['errores']['comentario'] = 'El comentario no puede estar vacío.';
            }

            // Si no hay errores, actualizar la reseña
            if (formularioErrores(...array_values($datos['errores']))) {
                $nuevaData = [
                    'id'           => $resenaId,
                    'calificacion' => $datos['calificacion'],
                    'comentario'   => $datos['comentario']
                ];

                if ($this->resenaModelo->actualizarResena($nuevaData)) {
                    redireccionar('/duenos/misReservas');
                } else {
                    $datos['errores']['comentario'] = 'Hubo un error al actualizar la reseña.';
                }
            }
        }

        // Mostrar vista de edición con los datos actuales o errores
        $this->vista('resenas/editar', $datos);
    }
}
