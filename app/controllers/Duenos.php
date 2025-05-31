<?php
require RUTA_APP . "/librerias/Funciones.php";

/**
 * Controlador para la gestión de dueños.
 */
class Duenos extends Controlador
{
    private $duenoModelo;
    private $reservaModelo;
    private $resenaModelo;
    private $mascotaModelo;

    /**
     * Constructor del controlador.
     * Inicia la sesión y carga los modelos necesarios.
     */

    public function __construct()
    {
        session_start();
        $this->duenoModelo = $this->modelo('Duenos_Model');
        $this->reservaModelo = $this->modelo('Reservas_Model');
        $this->resenaModelo = $this->modelo('Resenas_Model');
        $this->mascotaModelo = $this->modelo('Mascotas_Model');
    }

    /**
     * Muestra el perfil privado del dueño autenticado.
     */
    public function index()
    {
        // Verifica que exista una sesión activa y que el usuario pertenezca al grupo 'dueno'
        if (!isset($_SESSION['usuario']) || $_SESSION['grupo'] !== 'dueno') {
            redireccionar('/autenticacion');
        }

        // Obtiene el ID del dueño desde la sesión
        $duenoId = $_SESSION['usuario_id'];

        // Recupera los datos del perfil del dueño desde el modelo
        $perfil = $this->duenoModelo->obtenerPerfilDueno($duenoId);

        // Renderiza la vista con los datos del perfil
        $this->vista('duenos/perfilPriv', $perfil);
    }
    /**
     * Muestra y procesa el formulario para editar los datos del dueño.
     */
    public function editarDatos()
    {
        // Verifica que exista una sesión activa y que el usuario pertenezca al grupo 'dueno'
        if (!isset($_SESSION['usuario']) || $_SESSION['grupo'] !== 'dueno') {
            redireccionar('/autenticacion');
        }

        $errores = [];
        $entrada = [];
        $duenoId = $_SESSION['usuario_id'];
        // Obtiene los datos actuales del dueño
        $dueno = $this->duenoModelo->obtenerPerfilDueno($duenoId);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Recoge y limpia los datos enviados por el formulario
            $entrada = [
                'nombre' => trim($_POST['nombre']),
            ];

            // Validación del campo nombre
            if (empty($entrada['nombre'])) {
                $errores['nombre'] = 'El nombre es obligatorio.';
            }

            // Procesamiento de la imagen de perfil si se ha subido una nueva
            $imagenFinal = $dueno->imagen;
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $temp = $_FILES['imagen']['tmp_name'];
                $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
                $nuevaRuta = "img/duenos/{$duenoId}.webp";
                $rutaCompleta = RUTA_APP . '/../' . $nuevaRuta;

                // Verifica que el formato de imagen sea válido
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                    // Crea el recurso de imagen según el tipo
                    switch ($ext) {
                        case 'jpg':
                        case 'jpeg':
                            $imagen = imagecreatefromjpeg($temp);
                            break;
                        case 'png':
                            $imagen = imagecreatefrompng($temp);
                            break;
                        case 'webp':
                            $imagen = imagecreatefromwebp($temp);
                            break;
                        default:
                            $imagen = false;
                    }

                    if ($imagen) {
                        // Convierte y guarda la imagen en formato webp
                        imagewebp($imagen, $rutaCompleta, 80);
                        imagedestroy($imagen);

                        // Si la imagen supera 1MB, la redimensiona a la mitad
                        if (filesize($rutaCompleta) > 1024 * 1024) {
                            list($ancho, $alto) = getimagesize($rutaCompleta);
                            $nuevoAncho = intval($ancho / 2);
                            $nuevoAlto = intval($alto / 2);

                            $tmp = imagecreatefromwebp($rutaCompleta);
                            $rec = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                            imagecopyresampled($rec, $tmp, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                            imagewebp($rec, $rutaCompleta, 80);
                            imagedestroy($tmp);
                            imagedestroy($rec);

                            // Verifica nuevamente el tamaño tras la compresión
                            if (filesize($rutaCompleta) > 1024 * 1024) {
                                $errores['imagen'] = 'La imagen pesa demasiado.';
                            } else {
                                $imagenFinal = $nuevaRuta;
                                $_SESSION['imagen_usuario'] = $imagenFinal;
                            }
                        } else {
                            $imagenFinal = $nuevaRuta;
                            $_SESSION['imagen_usuario'] = $imagenFinal;
                        }
                    } else {
                        $errores['imagen'] = 'No se pudo procesar la imagen.';
                    }
                } else {
                    $errores['imagen'] = 'Formato de imagen no válido.';
                }
            }

            // Si no hay errores, actualiza los datos en la base de datos
            if (empty($errores)) {
                $entrada = [
                    'id' => $duenoId,
                    'nombre' => trim($_POST['nombre']),
                    'imagen' => $imagenFinal
                ];

                if ($this->duenoModelo->actualizarDatos($entrada)) {
                    redireccionar('/duenos');
                } else {
                    $errores['general'] = 'Error al guardar los cambios.';
                }
            }
        }

        $datos = [
            'dueno' => $dueno,
            'entrada' => $entrada,
            'errores' => $errores
        ];
        // Renderiza la vista de edición con los datos y posibles errores
        $this->vista('duenos/editarDatos', $datos);
    }

    /**
     * Muestra y procesa el formulario para editar los accesos del dueño.
     */
    public function editarAccesos()
    {
        if (!isset($_SESSION['usuario']) || $_SESSION['grupo'] !== 'dueno') {
            redireccionar('/autenticacion');
        }
        $errores = [];

        $id = $_SESSION['usuario_id'];
        $dueno = $this->duenoModelo->obtenerPerfilDueno($id);
        $obj = $this->duenoModelo->obtenerContrasenaPorId($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errores = [
                'email' => '',
                'contrasena_actual' => '',
                'contrasena' => ''
            ];

            if (!comprobarEmail($_POST['email'])) {
                $errores['email'] = "El email no es válido.";
            }

            $duenoContrasena = $obj->contrasena;

            if (!password_verify($_POST['contrasena_actual'], $duenoContrasena)) {
                $errores['contrasena_actual'] = "La contraseña actual no es correcta.";
            }

            $contrasenaOK = comprobarContrasena($_POST['contrasena']);
            if ($contrasenaOK !== true) {
                $errores['contrasena'] = $contrasenaOK;
            }

            if (formularioErrores(...array_values($errores))) {
                $contrasenaHash = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

                $this->duenoModelo->actualizarAccesos([
                    'id' => $id,
                    'email' => test_input($_POST['email']),
                    'contrasena' => $contrasenaHash
                ]);

                redireccionar('/duenos/perfilPriv');
            }

            $datos = [
                'datos' => $dueno,
                'errores' => $errores
            ];

            $this->vista('duenos/editarAccesos', $datos);
        } else {
            $datos = [
                'datos' => $dueno,
            ];
            $this->vista('duenos/editarAccesos', $datos);
        }
    }

    /**
     * Muestra las reservas del dueño autenticado.
     */
    public function misReservas()
    {
        // Verifica que exista una sesión activa y que el usuario pertenezca al grupo 'dueno'
        if (!isset($_SESSION['usuario']) || $_SESSION['grupo'] !== 'dueno') {
            redireccionar('/autenticacion');
        }
        // Obtiene las reservas del dueño autenticado
        $dueno_id = $_SESSION['usuario_id'];
        $reservas = $this->reservaModelo->obtenerReservasPorDueno($dueno_id);
        $resenas = $this->resenaModelo->obtenerResenasDueno($dueno_id);

        // Añade los datos completos de las mascotas a cada reserva
        foreach ($reservas as $reserva) {
            $mascotasReserva = $this->reservaModelo->obtenerMascotasDeReserva($reserva->id);
            $nombresMascotas = [];
            foreach ($mascotasReserva as $mascotaRel) {
                $mascota = $this->mascotaModelo->obtenerMascotaPorId($mascotaRel->mascota_id);
                $nombresMascotas[] = $mascota->nombre;
            }

            // Asignar mascotas a la reserva
            $reserva->mascotas = $nombresMascotas;
        }

        $this->vista('duenos/misReservas', ['reservas' => $reservas, 'resenas' => $resenas]);
    }
}
