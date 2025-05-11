<?php
require_once RUTA_APP . '/librerias/Funciones.php';
class Mascotas extends Controlador
{
    private $mascotaModelo;
    public function __construct()
    {
        session_start();
        $this->mascotaModelo = $this->modelo('Mascotas_Model');

        if (!isset($_SESSION['usuario'])) {
            redireccionar('/autenticacion');
        }
    }

    public function index()
    {
        $tipo  = $_SESSION['grupo'];
        $id    = $_SESSION['usuario_id'];
        $mascotas = $this->mascotaModelo->obtenerMascotas($tipo, $id);
        foreach ($mascotas as $m) {
            $m->imagenes = $this->mascotaModelo->obtenerImagenes($m->id);
        }
        $this->vista('mascotas/inicio', ['mascotas' => $mascotas]);
    }

    public function editar($id_mascota)
    {
        $mascota = $this->mascotaModelo->obtenerMascotaPorId($id_mascota);
        $imagenes = $this->mascotaModelo->obtenerImagenes($id_mascota);
        $this->vista('mascotas/editarMascotas', ['mascota' => $mascota, 'imagenes' => $imagenes]);
    }

    public function agregarMascota()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1) Sanear entrada con test_input()
            $entrada = [
                'nombre'        => test_input($_POST['nombre']        ?? ''),
                'tipo'          => $_POST['tipo']                     ?? '',
                'raza'          => test_input($_POST['raza']          ?? ''),
                'edad'          => $_POST['edad']                     ?? '',
                'tamano'        => $_POST['tamano']                   ?? '',
                'observaciones' => test_input($_POST['observaciones'] ?? ''),
            ];

            // 2) Inicializar array de errores
            $errores = [
                'nombre'   => '',
                'tipo'     => '',
                'raza'     => '',
                'edad'     => '',
                'tamano'   => '',
                'imagenes' => '',
            ];

            // 3) Validaciones usando tus funciones
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
            if (empty($_FILES['imagenes']['name'][0])) {
                $errores['imagenes'] = 'Sube al menos una imagen.';
            }
            if (!comprobarImagenesSubidas($_FILES['imagenes'])) {
                $errores['imagenes'] = 'Solo se permiten JPG, PNG o WEBP.';
            }

            // 4) Si no hay errores, procesar alta
            if (formularioErrores(...array_values($errores))) {
                $entrada['propietario_tipo'] = $_SESSION['grupo'];
                $entrada['propietario_id']   = $_SESSION['usuario_id'];

                // Insertar mascota y obtener su ID
                $idMascota = $this->mascotaModelo->insertarMascota($entrada);

                // Directorio de subida
                $uploadsDir = __DIR__ . '/../../public/img/mascotas/';
                if (!is_dir($uploadsDir)) {
                    mkdir($uploadsDir, 0755, true);
                }

                // Procesar cada imagen
                $index = 1;
                foreach ($_FILES['imagenes']['tmp_name'] as $i => $tmpName) {
                    if ($_FILES['imagenes']['error'][$i] !== UPLOAD_ERR_OK) {
                        continue;
                    }

                    $ext = strtolower(pathinfo($_FILES['imagenes']['name'][$i], PATHINFO_EXTENSION));
                    // Ruta final .webp con índice
                    $fileName     = "{$idMascota}_{$index}.webp";
                    $rutaCompleta = $uploadsDir . $fileName;

                    // 1) Carga GD según extensión
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

                    // 2) Exporta a WebP calidad 80
                    imagewebp($img, $rutaCompleta, 80);
                    imagedestroy($img);

                    // 3) Si >1MB, redimensiona al 50%
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

                        // 4) Si sigue >1MB, error definitivo
                        if (filesize($rutaCompleta) > 1024 * 1024) {
                            $errores['imagenes'] = 'Imagen demasiado pesada (>1MB tras redimensionar).';
                            break;
                        }
                    }

                    // 5) Insertar URL en BD
                    $url = "public/img/mascotas/{$fileName}";
                    $this->mascotaModelo->insertarImagen($idMascota, $url);
                    $index++;
                }

                // Si hubo error de imágenes, mostraremos formulario de nuevo
                if ($errores['imagenes'] !== '') {
                    // borrar registro de BD y archivos parciales, opcional
                    $this->vista('mascotas/agregarMascota', [
                        'entrada' => $entrada,
                        'errores' => $errores
                    ]);
                    return;
                }

                redireccionar('/mascotas');
            }

            // 5) Si hay errores de validación, volvemos al formulario
            $this->vista('mascotas/agregarMascota', [
                'entrada' => $entrada,
                'errores' => $errores
            ]);
        } else {
            // Cargar razas desde los CSV
            $razasPerro = array_map('str_getcsv', file(RUTA_APP . '/models/api-dog.csv'));
            $razasGato  = array_map('str_getcsv', file(RUTA_APP . '/models/api-cat.csv'));

            // Quitar encabezado y reestructurar
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

            $this->vista('mascotas/agregarMascota', [
                'entrada' => [],
                'errores' => [],
                'razasPerro' => $perros,
                'razasGato'  => $gatos
            ]);
        }
    }
}
