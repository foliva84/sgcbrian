<?php
/*
 * Clase: SistemaEmision
 *
 * @author ArgenCode
 * Aquí se setean los diferentes sistemas de emisión.
 * NUNCA MODIFICAR EL ARRAY.
 */

class SistemaEmision {
    
    private static $URL_1 = "http://sec.coris.com.ar/vouchers_json.php";
    
    private static $URL_2 = "https://sistema.assist1.com.co/ws/assistance.aspx";
    
    private static $URL_3 = "https://sistemacoris.com/ws/assistance.aspx";
    
    private static $URL_4 = "https://sistemacoris.com/ws/assistance.aspx";
    
    private static $sistema_emision = array(
                                            array("se_id"=>"1", "se_nombre"=>"Coris-SEC"),
                                            array("se_id"=>"2", "se_nombre"=>"Assist1"),
                                            array("se_id"=>"3", "se_nombre"=>"Coris"),
                                            array("se_id"=>"4", "se_nombre"=>"Coris-SEC-OLD"),
                                            );  
        
    public static function listar(){
        $resultado = self::$sistema_emision;
        return $resultado;
    } 
    
    
    // Devuelve la URL del voucher.
    public static function getURLvoucher($se_id){
        
        if ($se_id == "1") {
            $resultado = self::$URL_1;
        } 

        if ($se_id == "2") {
            $resultado = self::$URL_2;
        }

        if ($se_id == "3") {
            $resultado = self::$URL_3;
        }

        if ($se_id == "4") {
            $resultado = self::$URL_4;
        }
        
        return $resultado;
    }
        
}