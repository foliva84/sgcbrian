<?php
require '../seguridad/seguridad.php';
include_once '../includes/herramientas.php';

date_default_timezone_set('Etc/UTC');
require 'clases/PHPMailerAutoload.php';

require '../vendor/autoload.php';

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $account = $_ENV['NEWCASE_ACCOUNT'];
    $pass = $_ENV['NEWCASE_PASS'];

    //Create a new PHPMailer instance
    $mail = new PHPMailer;

    //Tell PHPMailer to use SMTP
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPDebug = 0; //Enable SMTP debugging - 0 = off (for production use)

    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->Username = $account;
    $mail->Password = $pass;
    //$mail->Debugoutput = 'html'; //Ask for HTML-friendly debug output

    $mail->setFrom('sgc@coris.com.ar', 'CORIS - SGC Alertas'); // Muestra quien envia

    // A quien le envia el mail
    $destinatarios = $to;
    $addr = explode(';', $destinatarios);

    foreach ($addr as $ad) {
        $mail->AddAddress(trim($ad));
    }

    //Set the subject line
    $mail->Subject = $subject;

    // Cuerpo del mail
    $mail->Body = $body;

    //Replace the plain text body with one created manually
    $mail->AltBody = 'pbody';

    // Send the message, check for errors
    if (!$mail->send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo '200';
    }
} catch (Dotenv\Exception\InvalidPathException $e) {
    echo 'Error' . $e->getMessage();
}