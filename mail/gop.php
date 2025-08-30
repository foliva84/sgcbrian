<?php
require '../seguridad/seguridad.php';
include_once '../includes/herramientas.php';

date_default_timezone_set('Etc/UTC');
require 'clases/PHPMailerAutoload.php';
require '../vendor/autoload.php';

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $account = $_ENV['COORDINATION_ACCOUNT'];
    $pass = $_ENV['COORDINATION_PASS'];


    // Toma las variables de la funcion enviar_gop
    $email                  = isset($_POST["email"]) ? $_POST["email"] : '';
    $prestador              = isset($_POST["prestador"]) ? $_POST["prestador"] : '';
    $prestador_id           = isset($_POST["prestador_id"]) ? $_POST["prestador_id"] : '';
    $nombreBeneficiario     = isset($_POST["nombreBeneficiario"]) ? $_POST["nombreBeneficiario"] : '';
    $nacimientoBeneficiario = isset($_POST["nacimientoBeneficiario"]) ? $_POST["nacimientoBeneficiario"] : '';
    $nacimientoBeneficiario_ansi = isset($_POST["nacimientoBeneficiario_ansi"]) ? $_POST["nacimientoBeneficiario_ansi"] : '';
    $edad                   = isset($_POST["edad"]) ? $_POST["edad"] : '';
    $voucher                = isset($_POST["voucher"]) ? $_POST["voucher"] : '';
    $casoNumero             = isset($_POST["casoNumero"]) ? $_POST["casoNumero"] : '';
    $sintomas               = isset($_POST["sintomas"]) ? $_POST["sintomas"] : '';
    $telefono               = isset($_POST["telefono"]) ? $_POST["telefono"] : '';
    $telefonosSec           = isset($_POST["telefonosSec"]) ? $_POST["telefonosSec"] : '';
    $pais                   = isset($_POST["pais"]) ? $_POST["pais"] : '';
    $pais_id                = isset($_POST["pais_id"]) ? $_POST["pais_id"] : '';
    $ciudad                 = isset($_POST["ciudad"]) ? $_POST["ciudad"] : '';
    $ciudad_id              = isset($_POST["ciudad_id"]) ? $_POST["ciudad_id"] : '';
    $direccion              = isset($_POST["direccion"]) ? $_POST["direccion"] : '';
    $cp                     = isset($_POST["cp"]) ? $_POST["cp"] : '';
    $hotel                  = isset($_POST["hotel"]) ? $_POST["hotel"] : '';
    $habitacion             = isset($_POST["habitacion"]) ? $_POST["habitacion"] : '';
    $observaciones          = isset($_POST["observaciones"]) ? $_POST["observaciones"] : '';
    $idioma1                = isset($_POST["idioma1"]) ? $_POST["idioma1"] : '';



    if ($idioma1 == 1) {
        // Titulos
        $t_asunto = 'Nuestra REF';
        $cabeceraMail = 'POR FAVOR CONFIRMAR RECEPCION';
        $titulo_datosCaso = 'Datos del Caso: ';
        $t_paciente = 'Paciente: ';
        $t_fechaNacimiento = 'Fecha de Nacimiento: ';
        $t_edad = 'Edad: ';
        $t_nVoucher = 'Numero de Voucher: ';
        $t_nCaso = 'Numero de Caso: ';
        $t_sintomas = 'Sintomas: ';
        $titulo_datosContacto = 'Datos de Contacto: ';
        $t_telefono = 'Telefono principal: ';
        $t_telefonosSec = 'Telefonos secundarios: ';
        $t_pais = 'Pais: ';
        $t_ciudad = 'Ciudad: ';
        $t_direccion = 'Direccion: ';
        $t_cp = 'CP: ';
        $titulo_datosHotel = 'Datos del Hotel: ';
        $t_hotel = 'Hotel: ';
        $t_habitacion = 'Habitacion: ';
        $t_observaciones = 'Observaciones: ';
        $t_datosFacturacion = '<h3>Facturacion:</h3>' .
        '<p><u>Compa&#241;ia de Asistencia Integral S.A</u><p>' .
        '<p><b>CUIT:</b> 33-71450792-9<p>' .
        '<p><b>Direccion:</b> Av. Alicia Moreau de Justo 270 | Piso 1 | Buenos Aires, Argentina (C1107AAF)<p>';
    } else if ($idioma1 == 2) {
        $t_asunto = 'OUR REF';
        $cabeceraMail = 'PLEASE CONFIRM RECEPTION';
        $titulo_datosCaso = 'Case information: ';
        $t_paciente = 'Patient: ';
        $t_fechaNacimiento = 'Date of Birth: ';
        $t_edad = 'Age: ';
        $t_nVoucher = 'Voucher Number: ';
        $t_nCaso = 'Case Number: ';
        $t_sintomas = 'Symptoms: ';
        $titulo_datosContacto = 'Contact Information: ';
        $t_telefono = 'Telephone (main): ';
        $t_telefonosSec = 'Telephone (secondary): ';
        $t_pais = 'Country: ';
        $t_ciudad = 'City: ';
        $t_direccion = 'Address: ';
        $t_cp = 'Zip Code: ';
        $titulo_datosHotel = 'Hotel Information: ';
        $t_hotel = 'Hotel: ';
        $t_habitacion = 'Room: ';
        $t_observaciones = 'Observations: ';
        $t_datosFacturacion = '<h3>Accounting:</h3>' .
        '<p><u>Compa&#241;ia de Asistencia Integral S.A</u><p>' .
        '<p><b>CUIT:</b> 33-71450792-9<p>' .
        '<p><b>Address:</b> Av. Alicia Moreau de Justo 270 | Piso 1 | Buenos Aires, Argentina (C1107AAF)<p>';
    }

    //Create a new PHPMailer instance
    $mail = new PHPMailer();
    $mail->CharSet = 'UTF-8';


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

    $mail->setFrom('asistencia@coris.com.ar', 'CORIS Asistencia al Viajero'); // Muestra quien envia
    $mail->addReplyTo('asistencia@coris.com.ar', 'CORIS Asistencia al Viajero'); // Mail de respuesta
    //$mail->addAddress($email, $prestador); // A quien le envia el mail
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;

    $addr = explode(',', $email);
    foreach ($addr as $ad) {
        $mail->AddAddress(trim($ad));
    }

    //Set the subject line
    $mail->Subject = $t_asunto . ': ' . $casoNumero . ' | ' . $nombreBeneficiario;

    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
    //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));

    // Logo de CORIS
    $mail->AddEmbeddedImage('../assets/images/logo_coris.png', 'logo_coris');

    // Cuerpo del mail
    $mail->Body = '<img src="cid:logo_coris" align="center">' .
    '<h3 align="center"><u>' . $cabeceraMail . '</h3></u>' .
    '<p><u><b>' . $titulo_datosCaso . '</b></u></p>' .
    '<p><b>' . $t_paciente . '</b>' . $nombreBeneficiario . '<p>' .
    '<p><b>' . $t_fechaNacimiento . '</b>' . $nacimientoBeneficiario . ' - <b>' . $t_edad . '</b>' . $edad . '<p>' .
    '<p><b>' . $t_nVoucher . '</b>' . $voucher . '<p>' .
    '<p><b>' . $t_nCaso . '</b>' . $casoNumero . '<p>' .
    '<p><b>' . $t_sintomas . '</b>' . $sintomas . '<p>' .
    // Datos de contacto
    '<p><u><b>' . $titulo_datosContacto . '</b></u></p>' .
    '<p><b>' . $t_telefono . '</b>' . $telefono . '<p>' .
    '<p><b>' . $t_telefonosSec . '</b>' . $telefonosSec . '<p>' .
    '<p><b>' . $t_pais . '</b>' . $pais . ' - <b>' . $t_ciudad . '</b>' . $ciudad . '<p>' .
    '<p><b>' . $t_direccion . '</b>' . $direccion . ' - <b>' . $t_cp . '</b>' . $cp . '<p>' .
    // Datos del hotel
    '<p><u><b>' . $titulo_datosHotel . '</b></u></p>' .
    '<p><b>' . $t_hotel . '</b>' . $hotel . ' - <b>' . $t_habitacion . '</b>' . $habitacion . '<p>' .
    // Datos varios
    '<p><b>' . $t_observaciones . '</b></p><p style="color:red">' . $observaciones . '<p>' .
    $t_datosFacturacion;

    //Replace the plain text body with one created manually
    $mail->AltBody = 'pbody';

    //Attach an image file
    //$mail->addAttachment('images/phpmailer_mini.png');

    //send the message, check for errors
    if (!$mail->send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        Gop::insertar(
            $casoNumero,
            $voucher,
            $nombreBeneficiario,
            $nacimientoBeneficiario_ansi,
            $edad,
            $sintomas,
            $telefono,
            $telefonosSec,
            $pais_id,
            $ciudad_id,
            $direccion,
            $cp,
            $hotel,
            $habitacion,
            $prestador_id,
            $email,
            $observaciones,
            $sesion_usuario_id
        );

        echo '200';
    }
} catch (Dotenv\Exception\InvalidPathException $e) {
    echo 'Error' . $e->getMessage();
}