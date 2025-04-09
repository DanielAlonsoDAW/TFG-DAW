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

// Validar matrícula con expresión regular
function validarFormatoMatricula($matricula)
{
    if (!preg_match("/^[0-9]{4}[A-Z]{3}$/", $matricula)) {
        return false;
    }

    return true;
}
function validarRutaImagen($ruta)
{
    // Asegurarse de que la ruta contiene solo caracteres permitidos
    $patron = "/^[a-zA-Z0-9\/\-_\.\\\:\%\ ]+$/";
    if (!preg_match($patron, $ruta)) {
        return false;
    }

    // Comprobar que el archivo tiene una extensión de imagen válida
    $extensionesPermitidas = array("jpg", "jpeg", "png", "webp", "gif");
    $posicionPunto = strrpos($ruta, '.');
    if ($posicionPunto === false) {
        return false;
    }
    $extension = substr($ruta, $posicionPunto + 1);
    if (!in_array(strtolower($extension), $extensionesPermitidas)) {
        return false;
    }

    return true;
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
