<?php
require RUTA_APP . "/librerias/FuncionesFormulario.php";
class Cuidadores extends Controlador
{
    private $cuidadorModelo;
    public function __construct()
    {
        session_start();
        $this->cuidadorModelo = $this->modelo('Cuidadores_Model');
    }

    public function perfil($id)
    {
        $datosCuidador = $this->cuidadorModelo->obtenerPerfilCuidador($id);
        $servicios = $this->cuidadorModelo->obtenerServicios($id);
        $admite = $this->cuidadorModelo->obtenerTiposMascotas($id);
        $resenas = $this->cuidadorModelo->obtenerResenas($id);
        $mascotas = $this->cuidadorModelo->obtenerMascotasCuidador($id);


        $this->vista('cuidadores/perfil', [
            'cuidador' => $datosCuidador,
            'servicios' => $servicios,
            'admite' => $admite,
            'resenas' => $resenas,
            'mascotas' => $mascotas
        ]);
    }
    public function perfilPriv()
    {
        if (!isset($_SESSION['usuario']) || $_SESSION['grupo'] !== 'cuidador') {
            redireccionar('/autenticacion');
        }

        $id = $_SESSION['usuario_id'];
        $datos = $this->cuidadorModelo->obtenerPerfilCuidador($id);
        $servicios = $this->cuidadorModelo->obtenerServicios($id);
        $admite = $this->cuidadorModelo->obtenerTiposMascotas($id);

        $this->vista('cuidadores/perfilPriv', [
            'datos' => $datos,
            'servicios' => $servicios,
            'admite' => $admite
        ]);
    }

    public function editarAccesos()
    {
        if (!isset($_SESSION['usuario']) || $_SESSION['grupo'] !== 'cuidador') {
            redireccionar('/autenticacion');
        }

        $id = $_SESSION['usuario_id'];
        $datos = $this->cuidadorModelo->obtenerPerfilCuidador($id);
        $obj = $this->cuidadorModelo->obtenerContrasenaPorId($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errores = [
                'nombre' => '',
                'email' => '',
                'contrasena_actual' => '',
                'contrasena' => ''
            ];

            if (!comprobarDatos($_POST['nombre'])) {
                $errores['nombre'] = "El nombre no puede estar vacío.";
            }

            if (!comprobarEmail($_POST['email'])) {
                $errores['email'] = "El email no es válido.";
            }

            $cuidadorContrasena = $obj->contrasena;

            if (!password_verify($_POST['contrasena_actual'], $cuidadorContrasena)) {
                $errores['contrasena_actual'] = "La contraseña actual no es correcta.";
            }

            $contrasenaOK = comprobarContrasena($_POST['contrasena']);
            if ($contrasenaOK !== true) {
                $errores['contrasena'] = $contrasenaOK;
            }

            if (formularioErrores(...array_values($errores))) {
                $contrasenaHash = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

                $this->cuidadorModelo->actualizarAccesos([
                    'id' => $id,
                    'nombre' => test_input($_POST['nombre']),
                    'email' => test_input($_POST['email']),
                    'contrasena' => $contrasenaHash
                ]);

                redireccionar('/cuidadores/perfilPriv');
            }

            $this->vista('cuidadores/editarAccesos', [
                'datos' => $datos,
                'errores' => $errores,
                'entrada' => $_POST
            ]);
        } else {
            $this->vista('cuidadores/editarAccesos', ['datos' => $datos]);
        }
    }

    public function editarDatosCuidador()
    {
        if (!isset($_SESSION['usuario']) || $_SESSION['grupo'] !== 'cuidador') {
            redireccionar('/autenticacion');
        }

        $id = $_SESSION['usuario_id'];
        $datos = $this->cuidadorModelo->obtenerPerfilCuidador($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errores = [
                'telefono' => '',
                'direccion' => '',
                'ciudad' => '',
                'pais' => '',
                'imagen' => ''
            ];

            if (!comprobarTelefono($_POST['telefono'])) {
                $errores['telefono'] = "Teléfono inválido.";
            }

            if (!comprobarDatos($_POST['direccion'])) {
                $errores['direccion'] = "Dirección obligatoria.";
            }

            if (!comprobarDatos($_POST['ciudad'])) {
                $errores['ciudad'] = "Ciudad obligatoria.";
            }

            if (!comprobarDatos($_POST['pais'])) {
                $errores['pais'] = "País obligatorio.";
            }

            $imagenFinal = $datos->imagen;
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
                $temp = $_FILES['imagen']['tmp_name'];
                $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
                $nuevaRuta = 'public/img/avatar/cuidador_' . $id . '.webp';

                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                    $rutaCompleta = RUTA_APP . '/../' . $nuevaRuta;
                    $imagen = null;

                    if ($ext === 'jpg' || $ext === 'jpeg') $imagen = imagecreatefromjpeg($temp);
                    if ($ext === 'png') $imagen = imagecreatefrompng($temp);
                    if ($ext === 'webp') $imagen = imagecreatefromwebp($temp);

                    if ($imagen) {
                        imagewebp($imagen, $rutaCompleta, 80);
                        imagedestroy($imagen);
                        $imagenFinal = $nuevaRuta;
                        $_SESSION['imagen_usuario'] = $imagenFinal;
                    }
                } else {
                    $errores['imagen'] = "Formato de imagen no válido.";
                }
            }

            if (formularioErrores(...array_values($errores))) {

                $direccionNueva = trim($_POST['direccion']);
                $ciudadNueva = trim($_POST['ciudad']);
                $paisNuevo = trim($_POST['pais']);

                $lat = $datos->lat;
                $lng = $datos->lng;

                if (
                    $direccionNueva !== $datos->direccion ||
                    $ciudadNueva !== $datos->ciudad ||
                    $paisNuevo !== $datos->pais
                ) {
                    $coordenadas = $this->obtenerCoordenadasConNominatim("$direccionNueva, $ciudadNueva, $paisNuevo");

                    if ($coordenadas) {
                        $lat = $coordenadas['lat'];
                        $lng = $coordenadas['lng'];
                    }
                }

                $this->cuidadorModelo->actualizarDatosCuidador([
                    'id' => $id,
                    'telefono' => test_input($_POST['telefono']),
                    'direccion' => test_input($_POST['direccion']),
                    'ciudad' => test_input($_POST['ciudad']),
                    'pais' => test_input($_POST['pais']),
                    'imagen' => $imagenFinal,
                    'lat' => $lat,
                    'lng' => $lng
                ]);

                redireccionar('/cuidadores/perfilPriv');
            }

            $this->vista('cuidadores/editarDatosCuidador', [
                'datos' => $datos,
                'errores' => $errores,
                'entrada' => $_POST
            ]);
        } else {
            $this->vista('cuidadores/editarDatosCuidador', ['datos' => $datos]);
        }
    }

    public function editarServicios()
    {
        if (!isset($_SESSION['usuario']) || $_SESSION['grupo'] !== 'cuidador') {
            redireccionar('/autenticacion');
        }

        $id = $_SESSION['usuario_id'];
        $datos = $this->cuidadorModelo->obtenerPerfilCuidador($id);
        $servicios = $this->cuidadorModelo->obtenerServicios($id);
        $admite = $this->cuidadorModelo->obtenerTiposMascotas($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errores = [
                'max_mascotas_dia' => ''
            ];

            if (!comprobarNumero($_POST['max_mascotas_dia']) || $_POST['max_mascotas_dia'] < 1) {
                $errores['max_mascotas_dia'] = "Número inválido.";
            } else {
                $maxMascotas = test_input((int)$_POST['max_mascotas_dia']);
            }

            if (formularioErrores(...array_values($errores))) {

                // Guardar máximo de mascotas
                $this->cuidadorModelo->actualizarMaxMascotas($id, $maxMascotas);

                // Reemplazar admisiones
                $this->cuidadorModelo->eliminarAdmiteMascotas($id);

                if (isset($_POST['acepta_perro']) && isset($_POST['tamanos_perro'])) {
                    foreach ($_POST['tamanos_perro'] as $tam) {
                        $this->cuidadorModelo->insertarTipoMascota($id, 'perro', $tam);
                    }
                }

                if (isset($_POST['acepta_gato']) && isset($_POST['tamanos_gato'])) {
                    foreach ($_POST['tamanos_gato'] as $tam) {
                        $this->cuidadorModelo->insertarTipoMascota($id, 'gato', $tam);
                    }
                }

                // Eliminar servicios anteriores
                $this->cuidadorModelo->eliminarServicios($id);

                // Insertar los servicios seleccionados con sus precios
                if (!empty($_POST['servicios'])) {
                    foreach ($_POST['servicios'] as $servicio) {
                        $precioCampo = 'precio_' . $servicio;
                        if (isset($_POST[$precioCampo]) && is_numeric($_POST[$precioCampo])) {
                            $precio = floatval($_POST[$precioCampo]);
                            $this->cuidadorModelo->insertarServicio($id, $servicio, $precio);
                        }
                    }
                }

                // Redirigir al perfil privado del cuidador
                redireccionar('/cuidadores/perfilPriv');
            }

            // 💡 Volvemos a cargar lo que se va a mostrar con los datos enviados (en caso de error)
            $servicios = $this->cuidadorModelo->obtenerServicios($id);
            $admite = [];

            if (isset($_POST['acepta_perro']) && isset($_POST['tamanos_perro'])) {
                foreach ($_POST['tamanos_perro'] as $tam) {
                    $admite[] = (object)['tipo_mascota' => 'perro', 'tamano' => $tam];
                }
            }

            if (isset($_POST['acepta_gato']) && isset($_POST['tamanos_gato'])) {
                foreach ($_POST['tamanos_gato'] as $tam) {
                    $admite[] = (object)['tipo_mascota' => 'gato', 'tamano' => $tam];
                }
            }

            $this->vista('cuidadores/editarServicios', [
                'datos' => $datos,
                'errores' => $errores,
                'entrada' => $_POST,
                'servicios' => $servicios,
                'admite' => $admite
            ]);
        } else {
            $this->vista('cuidadores/editarServicios', [
                'datos' => $datos,
                'servicios' => $servicios,
                'admite' => $admite
            ]);
        }
    }

    function obtenerCoordenadasConNominatim($direccionCompleta)
    {
        $url = 'https://nominatim.openstreetmap.org/search?' . http_build_query([
            'q' => $direccionCompleta,
            'format' => 'json',
            'limit' => 1,
        ]);

        $opts = [
            "http" => [
                "header" => "User-Agent: PatitasApp/1.0\r\n"
            ]
        ];
        $context = stream_context_create($opts);
        $respuesta = file_get_contents($url, false, $context);
        $datos = json_decode($respuesta, true);

        if (!empty($datos[0])) {
            return [
                'lat' => $datos[0]['lat'],
                'lng' => $datos[0]['lon']
            ];
        }

        return null;
    }
}
