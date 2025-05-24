<?php
require RUTA_APP . "/librerias/Funciones.php";
class Cuidadores extends Controlador
{
    private $cuidadorModelo;
    private $reservaModelo;
    public function __construct()
    {
        session_start();
        $this->cuidadorModelo = $this->modelo('Cuidadores_Model');
        $this->reservaModelo = $this->modelo('Reservas_Model');
    }

    public function perfil($id)
    {
        $datosCuidador = $this->cuidadorModelo->obtenerPerfilCuidador($id);
        $servicios = $this->cuidadorModelo->obtenerServicios($id);
        $admite = $this->cuidadorModelo->obtenerTiposMascotas($id);
        $resenas = $this->cuidadorModelo->obtenerResenas($id);
        $mascotas = $this->cuidadorModelo->obtenerMascotasCuidador($id);
        $reservas = $this->reservaModelo->obtenerReservasConfirmadas($id);

        foreach ($servicios as $s) {
            $precio = number_format($s->precio, 2);

            switch (strtolower($s->servicio)) {
                case 'taxi':
                    $s->precio = "10€ + {$precio}€/km";
                    break;
                case 'alojamiento':
                case 'cuidado a domicilio':
                    $s->precio = "{$precio}€/noche";
                    break;
                case 'guardería de día':
                    $s->precio = "{$precio}€/día";
                    break;
                case 'paseos':
                    $s->precio = "{$precio}€/paseo";
                    break;
                case 'visitas a domicilio':
                    $s->precio = "{$precio}€/visita";
                    break;
            }
        }

        $this->vista('cuidadores/perfil', [
            'cuidador' => $datosCuidador,
            'servicios' => $servicios,
            'admite' => $admite,
            'resenas' => $resenas,
            'mascotas' => $mascotas,
            'reservas' => $reservas
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

        foreach ($servicios as $s) {
            $precio = number_format($s->precio, 2);

            switch (strtolower($s->servicio)) {
                case 'taxi':
                    $s->precio = "10€ + {$precio}€/km";
                    break;
                case 'alojamiento':
                case 'cuidado a domicilio':
                    $s->precio = "{$precio}€/noche";
                    break;
                case 'guardería de día':
                    $s->precio = "{$precio}€/día";
                    break;
                case 'paseos':
                    $s->precio = "{$precio}€/paseo";
                    break;
                case 'visitas a domicilio':
                    $s->precio = "{$precio}€/visita";
                    break;
            }
        }

        // Switch para tamaños de perro
        foreach ($admite as $a) {
            if ($a->tipo_mascota === 'perro') {
                switch (strtolower($a->tamano)) {
                    case 'pequeño':
                        $a->tamano = 'Pequeño < 35 cm';
                        break;
                    case 'mediano':
                        $a->tamano = 'Mediano 35-49 cm';
                        break;
                    case 'grande':
                        $a->tamano = 'Grande ≥ 50 cm';
                        break;
                }
            }
        }

        // Switch para tamaños de gato
        foreach ($admite as $a) {
            if ($a->tipo_mascota === 'gato') {
                switch (strtolower($a->tamano)) {
                    case 'pequeño':
                        $a->tamano = 'Pequeño <3 kg';
                        break;
                    case 'mediano':
                        $a->tamano = 'Mediano 3-5 kg';
                        break;
                    case 'grande':
                        $a->tamano = 'Grande ≥ 5 kg';
                        break;
                }
            }
        }

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
                'email' => '',
                'contrasena_actual' => '',
                'contrasena' => ''
            ];

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

    public function editarDatos()
    {
        if (!isset($_SESSION['usuario']) || $_SESSION['grupo'] !== 'cuidador') {
            redireccionar('/autenticacion');
        }

        $id = $_SESSION['usuario_id'];
        $datos = $this->cuidadorModelo->obtenerPerfilCuidador($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errores = [
                'nombre' => '',
                'direccion' => '',
                'ciudad' => '',
                'pais' => '',
                'imagen' => ''
            ];

            if (!comprobarDatos($_POST['nombre'])) {
                $errores['nombre'] = "El nombre no puede estar vacío.";
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

            if (empty($_FILES['imagenes']['name'][0])) {
                $errores['imagenes'] = 'Sube al menos una imagen.';
            }

            $imagenFinal = $datos->imagen;
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $temp = $_FILES['imagen']['tmp_name'];
                $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
                // Construimos la ruta 
                $nuevaRuta = "img/cuidadores/{$id}.webp";

                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                    $rutaCompleta = RUTA_APP . '/../' . $nuevaRuta;

                    // 1) Carga la imagen original en GD
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
                    }

                    if ($imagen) {
                        // 2) Primera exportación a WebP calidad 80
                        imagewebp($imagen, $rutaCompleta, 80);
                        imagedestroy($imagen);

                        // 3) Si sigue pesando > 1 MB, redimensionamos al 50%
                        if (filesize($rutaCompleta) > 1024 * 1024) {
                            // Obtenemos dimensiones actuales
                            list($ancho, $alto) = getimagesize($rutaCompleta);
                            $nuevoAncho = intval($ancho / 2);
                            $nuevoAlto  = intval($alto  / 2);

                            // Cargamos de nuevo como WebP
                            $tmp = imagecreatefromwebp($rutaCompleta);
                            $rec = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                            imagecopyresampled($rec, $tmp, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                            imagewebp($rec, $rutaCompleta, 80);
                            imagedestroy($tmp);
                            imagedestroy($rec);

                            // 4) Si después de redimensionar sigue > 1 MB, error
                            if (filesize($rutaCompleta) > 1024 * 1024) {
                                $errores['imagen'] = 'La imagen pesa demasiado.';
                            } else {
                                $imagenFinal = $nuevaRuta;
                                $_SESSION['imagen_usuario'] = $imagenFinal;
                            }
                        } else {
                            // ok a la primera
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
                    'nombre' => test_input($_POST['nombre']),
                    'direccion' => test_input($_POST['direccion']),
                    'ciudad' => test_input($_POST['ciudad']),
                    'pais' => test_input($_POST['pais']),
                    'imagen' => $imagenFinal,
                    'lat' => $lat,
                    'lng' => $lng
                ]);

                redireccionar('/cuidadores/perfilPriv');
            }

            $this->vista('cuidadores/editarDatos', [
                'datos' => $datos,
                'errores' => $errores,
                'entrada' => $_POST
            ]);
        } else {
            $this->vista('cuidadores/editarDatos', ['datos' => $datos]);
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
                        // Consolida la misma transformación que en la vista:
                        $clave = str_replace(' ', '_', $servicio);
                        if (
                            isset($_POST['precio'][$clave]) &&
                            is_numeric($_POST['precio'][$clave])
                        ) {
                            $precio = floatval($_POST['precio'][$clave]);
                            $this->cuidadorModelo->insertarServicio(
                                $id,
                                $servicio,   // nombre real “Cuidado a domicilio”
                                $precio
                            );
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
