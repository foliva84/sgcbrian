<?php
/**
 * Clase: Alerta
 *
 *
 * @author ArgenCode
 */
    
class Alerta {

    
    // CASOS > Método para consultar distintos datos del sistema y completar la Alerta
    public static function datos_alerta_servicios($motivo, $servicio_id) {

        DB::query("SELECT caso_id,
                            caso_numero, 
                            caso_fechaSiniestro,
                            caso_beneficiarioNombre, 
                            caso_numeroVoucher, 
                            caso_vigenciaVoucherDesde,
                            caso_vigenciaVoucherHasta,                            
                            product.product_name,
                            caso_agencia,
                            caso_usuario.usuario_nombre AS caso_usuario_nombre,
                            caso_usuario.usuario_apellido AS caso_usuario_apellido,
                            servicios.servicio_fecha,
                            prestadores.prestador_nombre,
                            servicios.servicio_presuntoUSD,
                            servicios.servicio_justificacion,
                            servicio_usuario.usuario_nombre AS servicio_usuario_nombre,
                            servicio_usuario.usuario_apellido AS servicio_usuario_apellido
                    FROM casos 
                            LEFT JOIN product ON product_id_interno = casos.caso_producto_id
                            LEFT JOIN servicios ON servicio_caso_id = casos.caso_id
                            LEFT JOIN prestadores ON prestador_id = servicios.servicio_prestador_id                       
                            LEFT JOIN usuarios AS caso_usuario ON caso_usuario.usuario_id = casos.caso_abiertoPor_id
                            LEFT JOIN usuarios AS servicio_usuario ON servicio_usuario.usuario_id = servicios.servicio_usuario_id
                    WHERE servicios.servicio_id = :servicio_id");

        DB::bind(':servicio_id', "$servicio_id");

        $resultado = DB::resultado();

        $caso_id                    = $resultado['caso_id'];
        $entidad                    = 'Servicio';
        $caso_numero                = $resultado['caso_numero'];
        $siniestro_fecha            = Herramientas::fechaANSI_formateo($resultado['caso_fechaSiniestro']);
        $beneficiario               = $resultado['caso_beneficiarioNombre'];
        $voucher                    = $resultado['caso_numeroVoucher'];
        $vigencia_desde             = Herramientas::fechaANSI_formateo($resultado['caso_vigenciaVoucherDesde']);
        $vigencia_hasta             = Herramientas::fechaANSI_formateo($resultado['caso_vigenciaVoucherHasta']);
        $plan                       = $resultado['product_name'];
        $agencia                    = $resultado['caso_agencia'];
        $caso_creado_usuario        = $resultado['caso_usuario_nombre'] . ' ' . $resultado['caso_usuario_apellido'];
        $servicio_fecha             = Herramientas::fechaANSI_formateo($resultado['servicio_fecha']);
        $prestador                  = $resultado['prestador_nombre'];
        $presuntoUSD                = $resultado['servicio_presuntoUSD'];
        $servicio_justificacion     = $resultado['servicio_justificacion'];
        $servicio_creado_usuario    = $resultado['servicio_usuario_nombre'] . ' ' . $resultado['servicio_usuario_apellido'];

        Self::alerta_1($motivo, 
                        $caso_id, 
                        $entidad,
                        $caso_numero,
                        $siniestro_fecha,
                        $beneficiario,
                        $voucher,
                        $vigencia_desde,
                        $vigencia_hasta,
                        $plan,
                        $agencia,
                        $caso_creado_usuario,
                        $servicio_fecha,
                        $prestador,
                        $presuntoUSD,
                        $servicio_justificacion,
                        $servicio_creado_usuario);
    }


    /*
    |    Método para enviar un correo cuando:
    |    1 - El caso fue creado fuera de cobertura.
    |    2 - La fecha actual este fuera de los rangos de cobertura
    |    3 - El usuario tiene los permisos necesarios para crear el servicio bajo las condiciones 1 o 2
    */
    public static function alerta_1($motivo,
                                    $caso_id, 
                                    $entidad,
                                    $caso_numero,
                                    $siniestro_fecha,
                                    $beneficiario,
                                    $voucher,
                                    $vigencia_desde,
                                    $vigencia_hasta,
                                    $plan,
                                    $agencia,
                                    $caso_creado_usuario,
                                    $servicio_fecha,
                                    $prestador,
                                    $presuntoUSD,
                                    $servicio_justificacion,
                                    $servicio_creado_usuario) {

        $to = 'german.prado@coris.com.ar; asistencia@coris.com.ar; paola.lozano@coris.com.ar';
        $subject = 'SGC Alerta | ' . $motivo . ' - Nro. ' . $caso_id;
        $body = '<img src="cid:logo_coris" align="center">' .
                '<h4 align="center">AVISO por <u>' . $entidad . '</u> creado en un <u>' . $motivo . '</u></h4>' .
                '<br>' .
                '<p><u><b>Datos del Caso:</b></u></p>' .
                '<p><b>Caso: </b><a target="_blank" href="http://200.32.52.50/sgcp/caso/caso.php?vcase=' . $caso_id . '">' . $caso_numero . '</a></p>' .
                '<p><b>Fecha del Siniestro: </b>' . $siniestro_fecha . '<p>' .
                '<p><b>Beneficiario: </b>' . $beneficiario . '<p>' .
                '<p><b>Voucher: </b>' . $voucher . '<p>' .
                '<p><b>Vigencia desde: </b>' . $vigencia_desde . '<b> - Hasta: </b>' . $vigencia_hasta . '<p>' .
                '<p><b>Plan: </b>' . $plan . '<p>' .
                '<p><b>Agencia: </b>' . $agencia . '<p>' .
                '<p><b>Caso creado por: </b>' . $caso_creado_usuario . '<p>' .
                '<br>' .
                '<p><u><b>Datos del Servicio:</b></u></p>' .
                '<p><b>Fecha del Servicio: </b>' . $servicio_fecha . '<p>' .
                '<p><b>Prestador: </b>' . $prestador . '<p>' .
                '<p><b>Presunto (USD): </b>' . $presuntoUSD . '<p>' .
                '<p><b>Justificación de la Creación: </b>' . $servicio_justificacion . '<p>' .
                '<p><b>Servicio creado por: </b>' . $servicio_creado_usuario . '<p>';

        include ('../mail/alertas_mail.php');

    }


    // REINTEGROS > Método para enviar un correo se seleccionen forma de pago específica en el proceso de pago del reintegro
    public static function alerta_reintegros_pago($tipo) {
        
        // Cuando sea: Efectivo o Cheque
        if ($tipo == 1) {

            $to = 'nahuel.frega@gmail.com';
            $subject = 'Reintegro Forma de Pago 1';
            $body = 'Reintegro Forma de Pago 1';
                    
            include ('../mail/alertas_mail.php');
        
        // Cuando sea: Nota de Crédito
        } else if ($tipo == 2) {

            $to = 'nahuel.frega@gmail.com';
            $subject = 'Reintegro Forma de Pago 2';
            $body = 'Reintegro Forma de Pago 2';
                    
            include ('../mail/alertas_mail.php');

        }
    }
}
