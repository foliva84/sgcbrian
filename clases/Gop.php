<?php
/**
 * Clase: Gop
 *
 *
 * @author ArgenCode
 */

class Gop {  
    
    // Método para guardar copia del GOP
    public static function insertar($casoNumero, 
                                    $voucher, 
                                    $nombreBeneficiario, 
                                    $nacimientoBeneficiario, 
                                    $edad, 
                                    $sintomas, 
                                    $telefono, 
                                    $pais_id, 
                                    $ciudad_id,
                                    $direccion,
                                    $cp, 
                                    $hotel, 
                                    $habitacion, 
                                    $prestador_id, 
                                    $email, 
                                    $observaciones,
                                    $sesion_usuario_id) {
        
        DB::query("INSERT INTO gop
                                    (gop_casoNumero,
                                    gop_voucher,
                                    gop_nombreBeneficiario,
                                    gop_nacimientoBeneficiario,
                                    gop_edad,
                                    gop_sintomas,
                                    gop_telefono,
                                    gop_pais_id,
                                    gop_ciudad_id,
                                    gop_direccion,
                                    gop_cp,
                                    gop_hotel,
                                    gop_habitacion,
                                    gop_prestador_id,
                                    gop_prestadorEmail,
                                    gop_observaciones,
                                    gop_usuario_id)
                                VALUES 
                                    (:gop_casoNumero,
                                    :gop_voucher,
                                    :gop_nombreBeneficiario,
                                    :gop_nacimientoBeneficiario,
                                    :gop_edad,
                                    :gop_sintomas,
                                    :gop_telefono,
                                    :gop_pais_id,
                                    :gop_ciudad_id,
                                    :gop_direccion,
                                    :gop_cp,
                                    :gop_hotel,
                                    :gop_habitacion,
                                    :gop_prestador_id,
                                    :gop_prestadorEmail,
                                    :gop_observaciones,
                                    :gop_usuario_id)
                    ");

        DB::bind(':gop_casoNumero', "$casoNumero");
        DB::bind(':gop_voucher', "$voucher");
        DB::bind(':gop_nombreBeneficiario', "$nombreBeneficiario");
        DB::bind(':gop_nacimientoBeneficiario', "$nacimientoBeneficiario");
        DB::bind(':gop_edad', "$edad");
        DB::bind(':gop_sintomas', "$sintomas");
        DB::bind(':gop_telefono', "$telefono");
        DB::bind(':gop_pais_id', $pais_id);
        DB::bind(':gop_ciudad_id', $ciudad_id);
        DB::bind(':gop_direccion', "$direccion");
        DB::bind(':gop_cp', "$cp");
        DB::bind(':gop_hotel', "$hotel");
        DB::bind(':gop_habitacion', "$habitacion");
        DB::bind(':gop_prestador_id', $prestador_id);
        DB::bind(':gop_prestadorEmail', "$email");
        DB::bind(':gop_observaciones', "$observaciones");
        DB::bind(':gop_usuario_id', $sesion_usuario_id);
        
        DB::execute();
    }
    
    // Método para ver la GOP enviada
    public static function buscarPorId($gop_id){
        DB::query("SELECT gop_casoNumero,
                          gop_voucher,
                          gop_nombreBeneficiario,
                          DATE_FORMAT(gop_nacimientoBeneficiario, '%d-%m-%Y') as gop_nacimientoBeneficiario,
                          gop_edad,
                          gop_sintomas,
                          gop_telefono,
                          gop_pais_id,
                          pais_nombreEspanol,
                          gop_ciudad_id,
                          ciudad_nombre,
                          gop_direccion,
                          gop_cp,
                          gop_hotel,
                          gop_habitacion,
                          gop_prestador_id,
                          prestador_nombre,
                          gop_prestadorEmail,
                          gop_observaciones,
                          gop_usuario_id,
                          usuario_usuario,
                          DATE_FORMAT(gop_fecha, '%d-%m-%Y %H:%i:%s') as gop_fecha
                    FROM gop 
                            LEFT JOIN paises ON pais_id = gop_pais_id
                            LEFT JOIN ciudades ON ciudad_id = gop_ciudad_id
                            LEFT JOIN prestadores ON prestador_id = gop_prestador_id
                            LEFT JOIN usuarios ON usuario_id = gop_usuario_id
                    WHERE gop_id = :gop_id");
        
        DB::bind(':gop_id', $gop_id);
        
        return DB::resultado();
        
    }
    
}
