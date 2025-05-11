<?php

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function test_rutas_locales($data)
{
    $data = trim($data);
    $data = htmlspecialchars($data);
    return $data;
}

function comprobarDatos($datos)
{
    if (!isset($datos)) return false;
    if (empty($datos)) return false;

    return true;
}

function comprobarDatosNoRequeridos($datos)
{
    if (!isset($datos)) return false;
    return true;
}

function comprobarNumero($num)
{
    if (!isset($num)) return false;
    if (empty($num)) return false;
    if (is_numeric($num)) return true;
}

function comprobarNumeroNoRequerido($num)
{
    if (!isset($num)) return false;
    if (!empty($num)) {
        if (is_numeric($num)) {
            return true;
        } else {
            return false;
        }
    } else {
        return true;
    }
}

function formularioErrores(...$error)
{

    foreach ($error as $value) {
        if (strlen($value) > 0) {
            return false;
        }
    }
    return true;
}

// Función para registrar errores en el archivo log/error.log 
function registrarError($criticidad, $mensaje)
{
    $fechaHora = new DateTime("now", new DateTimeZone("Europe/Madrid"));
    $fechaHoraStr = $fechaHora->format("d-m-Y H:i:s");
    $logMensaje = $fechaHoraStr . " - " . $criticidad . " - " . $mensaje . "\n";
    error_log($logMensaje, 3, "log/error.log");
}

function comprobarEmail($email)
{
    // Validar formato de email con expresión regular
    $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    if (!preg_match($pattern, $email)) {
        return false;
    }
    return true;
}

function comprobarContrasena($contrasena)
{
    // Verificar que la contraseña tenga al menos 8 caracteres
    if (strlen($contrasena) < 8) {
        return "La contraseña debe tener al menos 8 caracteres, una mayúscula, una minuscula, un número y un símbolo.";
    }

    // Verificar que contenga al menos una letra mayúscula
    if (!preg_match('/[A-Z]/', $contrasena)) {
        return "La contraseña debe tener al menos 8 caracteres, una mayúscula, una minuscula, un número y un símbolo.";
    }

    // Verificar que contenga al menos una letra minúscula
    if (!preg_match('/[a-z]/', $contrasena)) {
        return "La contraseña debe tener al menos 8 caracteres, una mayúscula, una minuscula, un número y un símbolo.";
    }

    // Verificar que contenga al menos un número
    if (!preg_match('/[0-9]/', $contrasena)) {
        return "La contraseña debe tener al menos 8 caracteres, una mayúscula, una minuscula, un número y un símbolo.";
    }

    // Verificar que contenga al menos un símbolo
    if (!preg_match('/[\W_]/', $contrasena)) {
        return "La contraseña debe tener al menos 8 caracteres, una mayúscula, una minuscula, un número y un símbolo.";
    }

    // Si pasa todas las validaciones
    return true;
}
function comprobarImagenesSubidas(array $files): bool
{
    $tiposPermitidos = ['image/jpeg', 'image/png', 'image/webp'];
    foreach ($files['type'] as $tipo) {
        if (!in_array($tipo, $tiposPermitidos, true)) {
            return false;
        }
    }
    return true;
}

function comprobarDNI($dni)
{
    $pattern = '/^[0-9]{8}[A-Z]$/i';
    if (!preg_match($pattern, $dni)) {
        return false;
    }
    return true;
}

function comprobarTelefono($telefono)
{
    $pattern = '/^(6|7|9)[0-9]{8}$/';
    if (!preg_match($pattern, $telefono)) {
        return false;
    }
    return true;
}

function comprobarFecha($fecha)
{
    $pattern = '/^\d{4}-\d{2}-\d{2}$/';
    if (!preg_match($pattern, $fecha)) {
        return false;
    }
    $partes = explode('-', $fecha);
    $anio = (int)$partes[0];
    $mes = (int)$partes[1];
    $dia = (int)$partes[2];
    if (!checkdate($mes, $dia, $anio)) {
        return false;
    }
    return true;
}
function comprobarFecha_Nacimiento($fecha_nacimiento)
{
    $fecha = date('Y-m-d', strtotime($fecha_nacimiento));
    if (!comprobarFecha($fecha)) return false;
    $fecha_actual = date('Y-m-d');
    if ($fecha >= $fecha_actual) {
        return false;
    }
    return true;
}
function comprobarFecha_Recogida($fecha_recogida)
{
    $fecha = date('Y-m-d', strtotime($fecha_recogida));
    if (!comprobarFecha($fecha)) return false;
    $fecha_actual = date('Y-m-d');
    if ($fecha < $fecha_actual) {
        return false;
    }
    return true;
}
function comprobarFecha_Devolucion($fecha_recogida, $fecha_devolucion)
{
    $fecha_inicial = date('Y-m-d', strtotime($fecha_recogida));
    $fecha_final = date('Y-m-d', strtotime($fecha_devolucion));
    if (!comprobarFecha($fecha_inicial)) return false;
    if (!comprobarFecha($fecha_final)) return false;
    $fecha_actual = date('Y-m-d');
    if ($fecha_final < $fecha_actual) {
        return false;
    }
    if ($fecha_inicial >= $fecha_final) {
        return false;
    }
    return true;
}

function compararFechas($fecha1, $fecha2)
{
    $f1 = date_timestamp_get(new DateTime($fecha1));
    $f2 = date_timestamp_get(new DateTime($fecha2));

    return $f2 - $f1;
}

// Calcular diferencias entre fechas: calcula y muestra la cantidad de días entre dos fechas ingresadas por el usuario.
function calcularDiferenciaFechas($fecha1, $fecha2)
{
    $diffFecha = compararFechas($fecha1, $fecha2);

    return  $diffFecha / 86400;
}

//Comprobar idioma
function comprobarIdioma($idioma)
{
    switch ($idioma) {
        case 'espanol':
            return true;
        case 'ingles':
            return true;
        case 'catalan':
            return true;
        case 'euskera':
            return true;
        default:
            return false;
    }
}

function estrellasBootstrap($valoracion)
{
    $html = '';
    $estrellas = floor($valoracion);
    $decimal = $valoracion - $estrellas;
    $media = 0;

    // Media estrella si el decimal es entre 0.25 y 0.75
    if ($decimal >= 0.25 && $decimal <= 0.75) {
        $media = 1;
    } elseif ($decimal > 0.75) {
        // Si es mayor que 0.75, redondeamos hacia arriba
        $estrellas += 1;
    }

    $vacias = 5 - $estrellas - $media;

    $html .= str_repeat('<i class="bi bi-star-fill text-warning"></i>', $estrellas);
    if ($media) {
        $html .= '<i class="bi bi-star-half text-warning"></i>';
    }
    $html .= str_repeat('<i class="bi bi-star text-warning"></i>', $vacias);

    return $html;
}

function getClasificacionTamano($tipo, $tamano)
{
    $tamano = strtolower($tamano);
    $tipo = strtolower($tipo);

    if ($tipo === 'perro') {
        return match ($tamano) {
            'pequeño' => '(< 35 cm)',
            'mediano' => '(35–49 cm)',
            'grande'  => '≥ 50 cm',
            default   => ''
        };
    }

    if ($tipo === 'gato') {
        return match ($tamano) {
            'pequeño' => '(< 3 kg)',
            'mediano' => '(3–5 kg)',
            'grande'  => '> 5 kg',
            default   => ''
        };
    }

    return '';
}

function getIcono($tipo)
{
    switch ($tipo) {
        case 'perro':
            return '<i class="fa-solid fa-dog"></i> ';
        case 'gato':
            return '<i class="fa-solid fa-cat"></i> ';
        case 'phone':
            return '<i class="bi bi-telephone-fill text-info"></i>';
        case 'location':
            return '<i class="bi bi-geo-alt-fill text-primary"></i>';
        case 'email':
            return '<i class="bi bi-envelope-fill text-secondary"></i>';
        case 'star':
            return '<i class="bi bi-star-fill text-warning"></i>';
        case 'calendar':
            return '<i class="bi bi-calendar-check text-muted"></i>';
        default:
            return '';
    }
}
