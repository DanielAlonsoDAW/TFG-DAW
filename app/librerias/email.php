<?php
require "PHPMailer/src/PHPMailer.php";
require "PHPMailer/src/SMTP.php";
require "PHPMailer/src/Exception.php";

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

$correo = "";
$asunto = "";
$cuerpo = "";

/**
 * Funcion que crea una instancia de PHPMailer con los parametros definidos por el usuario en el fichero .env y los propios de Gmail, como el tipo de encriptado y el host del servidor SMTP
 * @return PHPMailer Configurado totalmente para poder mandar correos
 */
function configurarCorreo()
{
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "danielalonsodaw@gmail.com";
    $mail->Password = "widy xuwm ayzq qurb";
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;
    $mail->CharSet = "UTF-8";
    $mail->isHTML(true);

    return $mail;
}

/**
 * Función que establece el destinatario introducido por el usuario en el mail
 * @param PHPMailer $mail El correo previamente configurado en la función @configurarCorreo
 * @param string $correo El correo al que quieres mandar el mensaje
 * @return void muestra un mensaje si el correo no se ha podido establecer
 */
function establecerDestinatario($mail, $correo)
{
    if (!empty($correo)) {
        $mail->addAddress($correo);
    } else {
        echo "La dirección de correo del destinatario no está definida. ";
    }
}

/**
 * Función que envia un correo 
 * @param mixed $mail El mail configurado que has creado previamente
 * @param mixed $asunto El asunto del mensaje
 * @param mixed $cuerpo El cuerpo del mensaje
 * @return void
 */
function enviarCorreo($mail, $asunto, $cuerpo)
{
    $mail->Subject = $asunto;
    $mail->Body = $cuerpo;

    try {
        $mail->send();
    } catch (Exception $e) {
        registrarError("Crítica", "El mensaje no se pudo enviar. Mailer Error: {$mail->ErrorInfo}");
    }
}

/**
 * Función para mandar el correo, que ejecuta las funciones anteriores para configurar el correo y establecer los distintos parametros que necesita
 * @param mixed $correo El correo
 * @param mixed $cc El/los cc
 * @param mixed $bcc El/los cco
 * @param mixed $asunto El asunto del correo
 * @param mixed $cuerpo El cuerpo del correo
 * @param mixed $adjunto El fichero adjunto del correo
 * @return void
 */
function mandarCorreo($correo, $asunto, $cuerpo)
{
    $mail = configurarCorreo();
    establecerDestinatario($mail, $correo);
    enviarCorreo($mail, $asunto, $cuerpo);
}
