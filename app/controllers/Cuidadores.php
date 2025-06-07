<?php
require RUTA_APP . "/librerias/Funciones.php";

/**
 * Controlador para la gestión de cuidadores.
 */
class Cuidadores extends Controlador
{
    private $cuidadorModelo;
    private $reservaModelo;
    private $mascotaModelo;
    private $resenaModelo;

    /**
     * Constructor del controlador.
     * Inicia la sesión y carga los modelos necesarios.
     */
    public function __construct()
    {
        session_start();
        $this->cuidadorModelo = $this->modelo('Cuidadores_Model');
        $this->reservaModelo = $this->modelo('Reservas_Model');
        $this->mascotaModelo = $this->modelo('Mascotas_Model');
        $this->resenaModelo = $this->modelo('Resenas_Model');
    }

    /**
     * Muestra el perfil público de un cuidador.
     * @param int $id ID del cuidador a mostrar.
     */
    public function perfil($id)
    {
        // Obtiene los datos del cuidador por su ID
        $datosCuidador = $this->cuidadorModelo->obtenerPerfilCuidador($id);
        if ($datosCuidador) {
            // Obtiene los servicios, tipos de mascotas admitidas, reseñas, mascotas y reservas confirmadas
            $servicios = $this->cuidadorModelo->obtenerServicios($id);
            $admite = $this->cuidadorModelo->obtenerTiposMascotas($id);
            $resenas = $this->resenaModelo->obtenerResenasCuidador($id);
            $mascotas = $this->cuidadorModelo->obtenerMascotasCuidador($id);
            $reservas = $this->reservaModelo->obtenerReservasConfirmadas($id);

            // Formatea los precios de los servicios según el tipo
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

            // Renderiza la vista del perfil del cuidador con todos los datos obtenidos
            $this->vista('cuidadores/perfil', [
                'cuidador' => $datosCuidador,
                'servicios' => $servicios,
                'admite' => $admite,
                'resenas' => $resenas,
                'mascotas' => $mascotas,
                'reservas' => $reservas
            ]);
        } else {
            // Si no existe el cuidador, redirige al home
            redireccionar('/home');
        }
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

        // Switch para mejorar como se muestra el precio de los servicios
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

    /**
     * Permite al cuidador editar sus datos de acceso (email y contraseña).
     */
    public function editarAccesos()
    {
        // Verifica que el usuario esté autenticado y sea cuidador
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

            // Valida el email
            if (!comprobarEmail($_POST['email'])) {
                $errores['email'] = "El email no es válido.";
            }

            $cuidadorContrasena = $obj->contrasena;

            // Verifica la contraseña actual
            if (!password_verify($_POST['contrasena_actual'], $cuidadorContrasena)) {
                $errores['contrasena_actual'] = "La contraseña actual no es correcta.";
            }

            // Valida la nueva contraseña
            $contrasenaOK = comprobarContrasena($_POST['contrasena']);
            if ($contrasenaOK !== true) {
                $errores['contrasena'] = $contrasenaOK;
            }

            // Si no hay errores, actualiza los accesos
            if (formularioErrores(...array_values($errores))) {
                $contrasenaHash = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

                $this->cuidadorModelo->actualizarAccesos([
                    'id' => $id,
                    'email' => test_input($_POST['email']),
                    'contrasena' => $contrasenaHash
                ]);

                redireccionar('/cuidadores/perfilPriv');
            }

            // Si hay errores, vuelve a mostrar el formulario con los errores
            $this->vista('cuidadores/editarAccesos', [
                'datos' => $datos,
                'errores' => $errores,
                'entrada' => $_POST
            ]);
        } else {
            // Muestra el formulario por primera vez
            $this->vista('cuidadores/editarAccesos', ['datos' => $datos]);
        }
    }

    /**
     * Permite al cuidador editar sus datos personales (nombre, dirección, ciudad, país e imagen).
     */
    public function editarDatos()
    {
        // Verifica que el usuario esté autenticado y sea cuidador
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

            // Validación de campos obligatorios
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

            // Validación de imagen (debe subirse al menos una)
            if (empty($_FILES['imagen']['name'][0])) {
                $errores['imagen'] = 'Sube al menos una imagen.';
            }

            $imagenFinal = $datos->imagen;
            // Procesamiento de la imagen si se ha subido correctamente
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $temp = $_FILES['imagen']['tmp_name'];
                $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
                // Construimos la ruta de destino
                $nuevaRuta = "img/cuidadores/{$id}.webp";
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                    $rutaCompleta = RUTA_APP . '/../public/' . $nuevaRuta;

                    // Carga la imagen original en GD según el tipo
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
                        // Exporta a WebP calidad 80
                        imagewebp($imagen, $rutaCompleta, 80);
                        imagedestroy($imagen);

                        // Si la imagen pesa más de 1MB, redimensiona al 50%
                        if (filesize($rutaCompleta) > 1024 * 1024) {
                            list($ancho, $alto) = getimagesize($rutaCompleta);
                            $nuevoAncho = intval($ancho / 2);
                            $nuevoAlto  = intval($alto  / 2);

                            $tmp = imagecreatefromwebp($rutaCompleta);
                            $rec = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                            imagecopyresampled($rec, $tmp, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                            imagewebp($rec, $rutaCompleta, 80);
                            imagedestroy($tmp);
                            imagedestroy($rec);

                            // Si sigue pesando > 1MB, error
                            if (filesize($rutaCompleta) > 1024 * 1024) {
                                $errores['imagen'] = 'La imagen pesa demasiado.';
                            } else {
                                $imagenFinal = $nuevaRuta;
                                $_SESSION['imagen_usuario'] = $imagenFinal;
                            }
                        } else {
                            // Imagen válida a la primera
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

            // Si no hay errores, actualiza los datos del cuidador
            if (formularioErrores(...array_values($errores))) {

                $direccionNueva = test_input($_POST['direccion']);
                $ciudadNueva = test_input($_POST['ciudad']);
                $paisNuevo = test_input($_POST['pais']);

                $lat = $datos->lat;
                $lng = $datos->lng;

                // Si la dirección ha cambiado, actualiza coordenadas
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

                // Actualiza los datos en la base de datos
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

            // Si hay errores, vuelve a mostrar el formulario con los errores
            $this->vista('cuidadores/editarDatos', [
                'datos' => $datos,
                'errores' => $errores,
                'entrada' => $_POST
            ]);
        } else {
            // Muestra el formulario por primera vez
            $this->vista('cuidadores/editarDatos', ['datos' => $datos]);
        }
    }

    /**
     * Permite al cuidador editar los servicios que ofrece y los tipos de mascotas que admite.
     */
    public function editarServicios()
    {
        // Verifica que el usuario esté autenticado y sea cuidador
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

            // Valida el número máximo de mascotas por día
            if (!comprobarNumero($_POST['max_mascotas_dia']) || $_POST['max_mascotas_dia'] < 1) {
                $errores['max_mascotas_dia'] = "Número inválido.";
            } else {
                $maxMascotas = test_input((int)$_POST['max_mascotas_dia']);
            }

            // Si no hay errores, actualiza los servicios y tipos de mascotas admitidas
            if (formularioErrores(...array_values($errores))) {

                // Guarda el máximo de mascotas por día
                $this->cuidadorModelo->actualizarMaxMascotas($id, $maxMascotas);

                // Elimina los tipos de mascotas admitidas anteriores
                $this->cuidadorModelo->eliminarAdmiteMascotas($id);

                // Inserta los nuevos tipos de perros admitidos
                if (isset($_POST['acepta_perro']) && isset($_POST['tamanos_perro'])) {
                    foreach ($_POST['tamanos_perro'] as $tam) {
                        $this->cuidadorModelo->insertarTipoMascota($id, 'perro', $tam);
                    }
                }

                // Inserta los nuevos tipos de gatos admitidos
                if (isset($_POST['acepta_gato']) && isset($_POST['tamanos_gato'])) {
                    foreach ($_POST['tamanos_gato'] as $tam) {
                        $this->cuidadorModelo->insertarTipoMascota($id, 'gato', $tam);
                    }
                }

                // Elimina los servicios anteriores
                $this->cuidadorModelo->eliminarServicios($id);

                // Inserta los servicios seleccionados con sus precios
                if (!empty($_POST['servicios'])) {
                    foreach ($_POST['servicios'] as $servicio) {
                        // Obtiene el nombre del campo de precio correspondiente
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
                // Redirige al perfil privado del cuidador tras guardar los cambios
                redireccionar('/cuidadores/perfilPriv');
            }

            // Si hay errores, vuelve a mostrar el formulario con los datos enviados
            $servicios = $this->cuidadorModelo->obtenerServicios($id);
            $admite = [];

            // Reconstruye la selección de tipos de mascotas admitidas para mostrar en el formulario
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
            // Muestra el formulario por primera vez con los datos actuales
            $this->vista('cuidadores/editarServicios', [
                'datos' => $datos,
                'servicios' => $servicios,
                'admite' => $admite
            ]);
        }
    }

    /**
     * Obtiene las coordenadas (latitud y longitud) de una dirección usando Nominatim (OpenStreetMap).
     * @param string $direccionCompleta Dirección completa a buscar.
     * @return array|null Array asociativo con 'lat' y 'lng' si se encuentra, o null si no.
     */
    function obtenerCoordenadasConNominatim($direccionCompleta)
    {
        // Construye la URL de la petición a Nominatim
        $url = 'https://nominatim.openstreetmap.org/search?' . http_build_query([
            'q' => $direccionCompleta,
            'format' => 'json',
            'limit' => 1,
        ]);

        // Define el User-Agent requerido por Nominatim
        $opts = [
            "http" => [
                "header" => "User-Agent: PatitasApp/1.0\r\n"
            ]
        ];
        $context = stream_context_create($opts);

        // Realiza la petición HTTP
        $respuesta = file_get_contents($url, false, $context);

        // Decodifica la respuesta JSON
        $datos = json_decode($respuesta, true);

        // Si se encuentra al menos un resultado, devuelve latitud y longitud
        if (!empty($datos[0])) {
            return [
                'lat' => $datos[0]['lat'],
                'lng' => $datos[0]['lon']
            ];
        }

        // Si no se encuentra, devuelve null
        return null;
    }

    /**
     * Muestra las reservas del cuidador autenticado.
     */
    public function misReservas()
    {
        // Verifica que exista una sesión activa y que el usuario pertenezca al grupo 'cuidador'
        if (!isset($_SESSION['usuario']) || $_SESSION['grupo'] !== 'cuidador') {
            redireccionar('/autenticacion');
        }

        // Obtiene las reservas del cuidador autenticado
        $cuidador_id = $_SESSION['usuario_id'];
        $reservas = $this->reservaModelo->obtenerReservasPorCuidador($cuidador_id);
        $resenas = $this->resenaModelo->obtenerResenasCuidador($cuidador_id);

        // Añade los datos completos de las mascotas a cada reserva
        foreach ($reservas as $reserva) {
            $mascotasReserva = $this->reservaModelo->obtenerMascotasDeReserva($reserva->id);
            $mascotasConDatos = [];

            foreach ($mascotasReserva as $mascotaRel) {
                $mascota = $this->mascotaModelo->obtenerMascotaPorId($mascotaRel->mascota_id);

                if ($mascota) {
                    // Añadir imágenes a la mascota
                    $imagenes = $this->mascotaModelo->obtenerImagenes($mascota->id);
                    $mascota->imagenes = $imagenes;

                    $mascotasConDatos[] = $mascota;
                }
            }

            // Asignar mascotas a la reserva
            $reserva->mascotas = $mascotasConDatos;
        }

        // Renderiza la vista con las reservas
        $this->vista('cuidadores/misReservas', ['reservas' => $reservas, 'resenas' => $resenas]);
    }
}
