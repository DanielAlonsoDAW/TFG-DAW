<?php
require_once RUTA_APP . '/librerias/Funciones.php';

// Controlador para la gestión de mascotas
class Mascotas extends Controlador
{
    private $mascotaModelo;

    // Constructor: inicia sesión, carga modelo y verifica autenticación
    public function __construct()
    {
        session_start();
        $this->mascotaModelo = $this->modelo('Mascotas_Model');

        // Redirige si el usuario no está autenticado
        if (!isset($_SESSION['usuario'])) {
            redireccionar('/autenticacion');
        }
    }

    // Muestra la lista de mascotas del usuario autenticado
    public function index()
    {
        $tipo  = $_SESSION['grupo'];
        $id    = $_SESSION['usuario_id'];
        $mascotas = $this->mascotaModelo->obtenerMascotas($tipo, $id);

        // Añade las imágenes de cada mascota
        foreach ($mascotas as $m) {
            $m->imagenes = $this->mascotaModelo->obtenerImagenes($m->id);
        }

        // Carga la vista con los datos
        $this->vista('mascotas/inicio', ['mascotas' => $mascotas]);
    }

    // Agrega una nueva mascota al sistema
    public function agregarMascota()
    {
        // Cargar razas desde los archivos CSV
        $razasPerro = array_map('str_getcsv', file(RUTA_APP . '/models/api-dog.csv'));
        $razasGato  = array_map('str_getcsv', file(RUTA_APP . '/models/api-cat.csv'));

        // Quitar encabezado y reestructurar arrays
        array_shift($razasPerro);
        array_shift($razasGato);

        // Formatear como ['raza' => 'tamaño']
        $perros = [];
        foreach ($razasPerro as $r) {
            $perros[$r[1]] = $r[2];
        }

        $gatos = [];
        foreach ($razasGato as $r) {
            $gatos[$r[1]] = $r[2];
        }

        // Si el formulario fue enviado
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanear entrada del usuario
            $entrada = [
                'nombre'        => test_input($_POST['nombre']        ?? ''),
                'tipo'          => $_POST['tipo']                     ?? '',
                'raza'          => test_input($_POST['raza']          ?? ''),
                'edad'          => $_POST['edad']                     ?? '',
                'tamano'        => $_POST['tamano']                   ?? '',
                'observaciones' => test_input($_POST['observaciones'] ?? ''),
            ];

            // Inicializar array de errores
            $errores = [
                'nombre'   => '',
                'tipo'     => '',
                'raza'     => '',
                'edad'     => '',
                'tamano'   => '',
                'imagenes' => '',
            ];

            // Validaciones de los campos
            if (!comprobarDatos($entrada['nombre'])) {
                $errores['nombre'] = 'El nombre es obligatorio.';
            }
            if (!in_array($entrada['tipo'], ['perro', 'gato'], true)) {
                $errores['tipo'] = 'Selecciona perro o gato.';
            }
            if (!comprobarDatos($entrada['raza'])) {
                $errores['raza'] = 'La raza es obligatoria.';
            }
            if (!comprobarNumero($entrada['edad']) || (int)$entrada['edad'] < 0) {
                $errores['edad'] = 'La edad debe ser un número ≥ 0.';
            }
            if (!in_array($entrada['tamano'], ['pequeño', 'mediano', 'grande'], true)) {
                $errores['tamano'] = 'El tamano indicado no es correcto.';
            }
            if (empty($_FILES['imagenes']['name'][0])) {
                $errores['imagenes'] = 'Sube al menos una imagen.';
            }
            if (!comprobarImagenesSubidas($_FILES['imagenes'])) {
                $errores['imagenes'] = 'Solo se permiten JPG, PNG o WEBP.';
            }

            // Comprobar si la raza coincide con el tamaño según CSV
            $raza = $entrada['raza'];
            $tamanoIngresado = $entrada['tamano'];

            if ($entrada['tipo'] === 'perro' && isset($perros[$raza])) {
                if ($perros[$raza] !== $tamanoIngresado) {
                    $errores['tamano'] = 'El tamaño no coincide con la raza seleccionada.';
                }
            } elseif ($entrada['tipo'] === 'gato' && isset($gatos[$raza])) {
                if ($gatos[$raza] !== $tamanoIngresado) {
                    $errores['tamano'] = 'El tamaño no coincide con la raza seleccionada.';
                }
            }

            // Si no hay errores, procesar el alta de la mascota
            if (formularioErrores(...array_values($errores))) {
                $entrada['propietario_tipo'] = $_SESSION['grupo'];
                $entrada['propietario_id']   = $_SESSION['usuario_id'];

                // Insertar mascota y obtener su ID
                $idMascota = $this->mascotaModelo->insertarMascota($entrada);

                // Directorio de subida de imágenes
                $uploadsDir = __DIR__ . '/../../public/img/mascotas/';
                if (!is_dir($uploadsDir)) {
                    mkdir($uploadsDir, 0755, true);
                }

                // Procesar cada imagen subida
                $index = 1;
                foreach ($_FILES['imagenes']['tmp_name'] as $i => $tmpName) {
                    if ($_FILES['imagenes']['error'][$i] !== UPLOAD_ERR_OK) {
                        continue;
                    }

                    $ext = strtolower(pathinfo($_FILES['imagenes']['name'][$i], PATHINFO_EXTENSION));
                    // Ruta final .webp con índice
                    $fileName     = "{$idMascota}_{$index}.webp";
                    $rutaCompleta = $uploadsDir . $fileName;

                    // Cargar imagen según extensión usando GD
                    switch ($ext) {
                        case 'jpg':
                        case 'jpeg':
                            $img = imagecreatefromjpeg($tmpName);
                            break;
                        case 'png':
                            $img = imagecreatefrompng($tmpName);
                            break;
                        case 'webp':
                            $img = imagecreatefromwebp($tmpName);
                            break;
                        default:
                            $errores['imagenes'] = 'Formato de imagen no válido.';
                            break 2; // sale del foreach
                    }
                    if (!$img) {
                        $errores['imagenes'] = 'No se pudo procesar la imagen.';
                        break;
                    }

                    // Exportar a WebP calidad 80
                    imagewebp($img, $rutaCompleta, 80);
                    imagedestroy($img);

                    // Si la imagen es mayor a 1MB, redimensionar al 50%
                    if (filesize($rutaCompleta) > 1024 * 1024) {
                        list($w, $h) = getimagesize($rutaCompleta);
                        $nw = (int)($w / 2);
                        $nh = (int)($h / 2);

                        $tmp2 = imagecreatefromwebp($rutaCompleta);
                        $res  = imagecreatetruecolor($nw, $nh);
                        imagecopyresampled($res, $tmp2, 0, 0, 0, 0, $nw, $nh, $w, $h);
                        imagewebp($res, $rutaCompleta, 80);
                        imagedestroy($tmp2);
                        imagedestroy($res);

                        // Si sigue siendo mayor a 1MB, mostrar error
                        if (filesize($rutaCompleta) > 1024 * 1024) {
                            $errores['imagenes'] = 'Imagen demasiado pesada (>1MB tras redimensionar).';
                            break;
                        }
                    }

                    // Insertar URL de la imagen en la base de datos
                    $url = "img/mascotas/{$fileName}";
                    $this->mascotaModelo->insertarImagen($idMascota, $url);
                    $index++;
                }

                // Si hubo error de imágenes, mostrar formulario de nuevo
                if ($errores['imagenes'] !== '') {
                    // Opcional: borrar registro de BD y archivos parciales
                    $this->vista('mascotas/agregarMascota', [
                        'entrada' => $entrada,
                        'errores' => $errores,
                        'razasPerro' => $perros,
                        'razasGato'  => $gatos
                    ]);
                    return;
                }

                // Redirigir a la lista de mascotas tras el alta
                redireccionar('/mascotas');
            }

            // Si hay errores de validación, volver al formulario
            $this->vista('mascotas/agregarMascota', [
                'entrada' => $entrada,
                'errores' => $errores,
                'razasPerro' => $perros,
                'razasGato'  => $gatos
            ]);
        } else {
            // Vista inicial del formulario de alta
            $this->vista('mascotas/agregarMascota', [
                'entrada' => [],
                'errores' => [],
                'razasPerro' => $perros,
                'razasGato'  => $gatos
            ]);
        }
    }

    // Edita los datos de una mascota existente
    public function editarMascotas($id_mascota)
    {
        // Cargar razas desde los archivos CSV
        $razasPerro = array_map('str_getcsv', file(RUTA_APP . '/models/api-dog.csv'));
        $razasGato  = array_map('str_getcsv', file(RUTA_APP . '/models/api-cat.csv'));

        // Quitar encabezado y reestructurar arrays
        array_shift($razasPerro);
        array_shift($razasGato);

        // Formatear como ['raza' => 'tamaño']
        $perros = [];
        foreach ($razasPerro as $r) {
            $perros[$r[1]] = $r[2];
        }

        $gatos = [];
        foreach ($razasGato as $r) {
            $gatos[$r[1]] = $r[2];
        }

        // Si el formulario fue enviado (POST)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Recoger y sanear la entrada del usuario
            $entrada = [
                'id'             => $_POST['id'],
                'nombre'         => test_input($_POST['nombre'] ?? ''),
                'tipo'           => $_POST['tipo'] ?? '',
                'raza'           => test_input($_POST['raza'] ?? ''),
                'edad'           => $_POST['edad'] ?? '',
                'tamano'         => $_POST['tamano'] ?? '',
                'observaciones'  => test_input($_POST['observaciones'] ?? ''),
                'propietario_tipo' => $_POST['propietario_tipo'] ?? '',
                'propietario_id'   => $_POST['propietario_id'] ?? ''
            ];

            // Inicializar array de errores
            $errores = [
                'nombre' => '',
                'tipo' => '',
                'raza' => '',
                'edad' => '',
                'tamano' => '',
                'imagenes' => ''
            ];

            // Validaciones de los campos
            if (!comprobarDatos($entrada['nombre'])) {
                $errores['nombre'] = 'El nombre es obligatorio.';
            }
            if (!in_array($entrada['tipo'], ['perro', 'gato'], true)) {
                $errores['tipo'] = 'Selecciona perro o gato.';
            }
            if (!comprobarDatos($entrada['raza'])) {
                $errores['raza'] = 'La raza es obligatoria.';
            }
            if (!comprobarNumero($entrada['edad']) || (int)$entrada['edad'] < 0) {
                $errores['edad'] = 'La edad debe ser un número ≥ 0.';
            }
            if (!in_array($entrada['tamano'], ['pequeño', 'mediano', 'grande'], true)) {
                $errores['tamano'] = 'Selecciona un tamaño.';
            }
            // Validar imágenes solo si se suben nuevas
            if (!empty($_FILES['nuevas_imagenes']['name'][0]) && !comprobarImagenesSubidas($_FILES['nuevas_imagenes'])) {
                $errores['imagenes'] = 'Solo se permiten imágenes JPG, PNG o WEBP.';
            }

            // Comprobar si la raza coincide con el tamaño según CSV
            $raza = $entrada['raza'];
            $tamanoIngresado = $entrada['tamano'];

            if ($entrada['tipo'] === 'perro' && isset($perros[$raza])) {
                if ($perros[$raza] !== $tamanoIngresado) {
                    $errores['tamano'] = 'El tamaño no coincide con la raza seleccionada.';
                }
            } elseif ($entrada['tipo'] === 'gato' && isset($gatos[$raza])) {
                if ($gatos[$raza] !== $tamanoIngresado) {
                    $errores['tamano'] = 'El tamaño no coincide con la raza seleccionada.';
                }
            }

            // Si no hay errores, actualizar mascota y procesar imágenes
            if (formularioErrores(...array_values($errores))) {
                // Actualizar datos de la mascota en la base de datos
                $this->mascotaModelo->actualizarMascota($entrada);

                // Procesar nuevas imágenes si se han subido
                if (!empty($_FILES['nuevas_imagenes']['name'][0])) {
                    $uploadsDir = __DIR__ . '/../../public/img/mascotas/';
                    if (!is_dir($uploadsDir)) {
                        mkdir($uploadsDir, 0755, true);
                    }

                    // Calcular el índice para el nombre de la imagen
                    $index = count($this->mascotaModelo->obtenerImagenes($entrada['id'])) + 1;
                    foreach ($_FILES['nuevas_imagenes']['tmp_name'] as $i => $tmpName) {
                        if ($_FILES['nuevas_imagenes']['error'][$i] !== UPLOAD_ERR_OK) continue;

                        $ext = strtolower(pathinfo($_FILES['nuevas_imagenes']['name'][$i], PATHINFO_EXTENSION));
                        $fileName = "{$entrada['id']}_{$index}.webp";
                        $rutaCompleta = $uploadsDir . $fileName;

                        // Cargar imagen según extensión usando GD
                        switch ($ext) {
                            case 'jpg':
                            case 'jpeg':
                                $img = imagecreatefromjpeg($tmpName);
                                break;
                            case 'png':
                                $img = imagecreatefrompng($tmpName);
                                break;
                            case 'webp':
                                $img = imagecreatefromwebp($tmpName);
                                break;
                            default:
                                continue 2;
                        }

                        if (!$img) continue;

                        // Exportar a WebP calidad 80
                        imagewebp($img, $rutaCompleta, 80);
                        imagedestroy($img);

                        // Si la imagen es mayor a 1MB, redimensionar al 50%
                        if (filesize($rutaCompleta) > 1024 * 1024) {
                            list($w, $h) = getimagesize($rutaCompleta);
                            $nw = (int)($w / 2);
                            $nh = (int)($h / 2);

                            $tmp2 = imagecreatefromwebp($rutaCompleta);
                            $res  = imagecreatetruecolor($nw, $nh);
                            imagecopyresampled($res, $tmp2, 0, 0, 0, 0, $nw, $nh, $w, $h);
                            imagewebp($res, $rutaCompleta, 80);
                            imagedestroy($tmp2);
                            imagedestroy($res);

                            // Si sigue siendo mayor a 1MB, eliminar la imagen
                            if (filesize($rutaCompleta) > 1024 * 1024) {
                                unlink($rutaCompleta);
                                continue;
                            }
                        }

                        // Insertar URL de la imagen en la base de datos
                        $url = "public/img/mascotas/{$fileName}";
                        $this->mascotaModelo->insertarImagen($entrada['id'], $url);
                        $index++;
                    }
                }

                // Redirigir a la lista de mascotas tras la edición
                redireccionar('/mascotas');
            }

            // Si hay errores, recargar la vista con los datos actuales y errores
            $mascota = (object) $entrada;
            $imagenes = $this->mascotaModelo->obtenerImagenes($mascota->id);
            $this->vista('mascotas/editarMascotas', [
                'mascota' => $mascota,
                'imagenes' => $imagenes,
                'razasPerro' => $perros,
                'razasGato'  => $gatos,
                'errores' => $errores
            ]);
        } else {
            // Vista inicial: cargar datos actuales de la mascota
            $mascota = $this->mascotaModelo->obtenerMascotaPorId($id_mascota);
            $imagenes = $this->mascotaModelo->obtenerImagenes($id_mascota);

            $this->vista('mascotas/editarMascotas', [
                'mascota' => $mascota,
                'imagenes' => $imagenes,
                'razasPerro' => $perros,
                'razasGato'  => $gatos,
                'errores' => []
            ]);
        }
    }

    // Elimina una mascota y sus imágenes asociadas
    public function eliminarMascota($id_mascota)
    {
        // Obtener la mascota por su ID
        $mascota = $this->mascotaModelo->obtenerMascotaPorId($id_mascota);

        // Si no existe, redirigir a la lista
        if (!$mascota) {
            redireccionar('/mascotas');
        }

        if ($mascota) {
            // Obtener imágenes asociadas a la mascota
            $imagenes = $this->mascotaModelo->obtenerImagenes($id_mascota);

            // Eliminar la mascota de la base de datos
            $resultado = $this->mascotaModelo->eliminarMascota($id_mascota);

            if ($resultado) {
                // Eliminar archivos de imagen del sistema de archivos
                foreach ($imagenes as $imagen) {
                    $rutaImagen = $imagen->imagen;
                    $rutaAbsoluta = RUTA_APP . '/../public/' . $rutaImagen;

                    if (file_exists($rutaAbsoluta)) {
                        unlink($rutaAbsoluta);
                    }
                }
                // Redirigir tras eliminar correctamente
                redireccionar('/mascotas/inicio');
            } else {
                // Manejar error de eliminación
                $errores = 'Error al eliminar la mascota.';
                $this->vista('mascotas/inicio', $errores);
            }
        }

        // Redirigir en caso de cualquier otro escenario
        redireccionar('/mascotas');
    }
}
