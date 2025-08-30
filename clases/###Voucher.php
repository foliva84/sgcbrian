<?php
/**
 * Clase: Voucher
 *
 *
 * @author ArgenCode
 * 
 * Esta es la clase que se debe utilizar para el webservice y el sistema
 * 
 */

class Voucherererwr {
    
    
    public static function is_valid_xml ( $xml ) {
            libxml_use_internal_errors( true );

            $doc = new DOMDocument('1.0', 'utf-8');

            $doc->loadXML( $xml );

            $errors = libxml_get_errors();

            return empty( $errors );
        }
    
    
    
    //Métodos para leer el webservice
    // Password del web service
    private static $password = "ds987sdg8s8dfg";
    // private static $password = "ds987sdg8s8dfs";  // falsa password para descomponer el ws para pruebas
    // URL del web service   
    
    
    //  Método get_vouchers_for_coris
    //  Trae la información de un voucher
    
    public static function buscar_ws($numero_voucher, $buscar_documento, $buscar_nombre, $buscar_apellido, $sistema_emision) {
        
        
        // Trae la url del sistema de emision
        $se = SistemaEmision::getURLvoucher($sistema_emision);
        
        $url = $se['se_url_voucher'];
        
        
        //Lectura del Webservice
        $WS = file_get_contents($url . "?password=" . self::$password . "&voucher_number=" . $numero_voucher . "&passenger_document_number=" . $buscar_documento . "&passenger_first_name=" . $buscar_nombre . "&passenger_last_name=" . $buscar_apellido);
        
        if(self::is_valid_xml($WS)){
        
        // Parseado del webservice
        $xml = new SimpleXMLElement($WS);
        } else {
        
            $xml = "Error";
        }
        return $xml;
        
    }
    
    
       
public static function buscar_por_numero($numero_voucher, $sistema_emision) {
        
        
        // Trae la url del sistema de emision
        $se = SistemaEmision::getURLvoucher($sistema_emision);
        
        $url = $se['se_url_voucher'];
        
        
        //Lectura del Webservice
        $WS = file_get_contents($url . "?password=" . self::$password . "&voucher_number=" . $numero_voucher);
        
        if(self::is_valid_xml($WS)){
        
        // Parseado del webservice
        $xml = new SimpleXMLElement($WS);
        } else {
        
            $xml = "Error";
        }
        return $xml;
        
    }
    
    
    //  Método get_product_for_coris
    //  Trae la información de un producto
    
    public static function buscar_producto($producto_id, $sistema_emision){
        
        
        // Trae la url del producto según el sistema sistema de emision
        $se = SistemaEmision::getURLproducto($sistema_emision);
        
        $url_producto = $se['se_url_producto'];
        
        
        //Lectura del Webservice
        $WS_producto = file_get_contents($url_producto . "?password=" . self::$password . "&product_id=" . $producto_id);
        
        
         if(self::is_valid_xml($WS_producto)){
        
            // Parseado del webservice
            $xml_producto = new SimpleXMLElement($WS_producto);
            
            
         } else {
             
                 $xml_producto = "Error";
         }
        
        
        return $xml_producto;
        
    }
    
    
    private static function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
    
    
    // Un tipo de búsqueda, se debería definir el resto.
    public static function buscar($voucher_numero, $sistema_emision){
        DB::query("SELECT 
                        voucher.voucher_number,
                        voucher.voucher_number_am,
                        voucher.voucher_date,
                        voucher.voucher_date_from,
                        voucher.voucher_date_to,
                        voucher.voucher_int_ref,
                        voucher.voucher_total_cost,
                        voucher.voucher_taxes,
                        voucher.currency_id,
                        voucher.currency_name,
                        voucher.currency_abre,
                        voucher.currency_tc,
                        voucher.product_id,
                        voucher.product_name,
                        voucher.multitrip,
                        voucher.vip,
                        voucher.destination_country_id,
                        voucher.destination_country_name,
                        voucher.passenger_document_type_id,
                        voucher.passenger_document_type_name,
                        voucher.passenger_document_number,
                        voucher.passenger_birth_date,
                        voucher.passenger_gender,
                        voucher.passenger_first_name,
                        voucher.passenger_last_name,
                        voucher.passenger_second_name,
                        voucher.passenger_city,
                        voucher.passenger_address,
                        voucher.passenger_phone,
                        voucher.passenger_email,
                        voucher.passenger_emergency_first_name,
                        voucher.passenger_emergency_last_name,
                        voucher.passenger_emergency_phone_1,
                        voucher.passenger_emergency_phone_2,
                        voucher.pais,
                        voucher.representante,
                        voucher.asesor,
                        voucher.agencia,
                        voucher.se_id,
                        cobertura.coverage_val
                    FROM voucher
                    LEFT JOIN (SELECT coverage.product_id, coverage.coverage_name, coverage.coverage_val
                                FROM coverage 
                                WHERE coverage_name LIKE '%25. Deducible%') 
                                AS cobertura ON voucher.product_id = cobertura.product_id
                    WHERE voucher_number = :voucher_number AND se_id = :se_id");
       
        DB::bind(':voucher_number', "$voucher_numero");
        DB::bind(':se_id', $sistema_emision);
        
        $resultado = DB::resultado();
        
        //Conversiones de la fecha de ANSI a normal 
        if (self::validateDate($resultado['voucher_date'])) {
            $voucher_date = date("d-m-Y", strtotime($resultado['voucher_date']));
            $resultado['voucher_date'] = $voucher_date;
        }
        
        if (self::validateDate($resultado['voucher_date_from'])) {
            $voucher_dateFrom = date("d-m-Y", strtotime($resultado['voucher_date_from']));
            $resultado['voucher_date_from'] = $voucher_dateFrom;
        }
        
        if (self::validateDate($resultado['voucher_date_to'])) {
            $voucher_dateTo = date("d-m-Y", strtotime($resultado['voucher_date_to']));
            $resultado['voucher_date_to'] = $voucher_dateTo;
        }
        
        if (self::validateDate($resultado['passenger_birth_date'])) {
            $passenger_birth_date = date("d-m-Y", strtotime($resultado['passenger_birth_date']));
            $resultado['passenger_birth_date'] = $passenger_birth_date;
        }
        
        
        return $resultado;
    }
    
    
    // Insertar voucher
    public static function insertar(
                                    $voucher_number,
                                    $voucher_number_am,
                                    $voucher_date,
                                    $voucher_date_from,
                                    $voucher_date_to,
                                    $voucher_int_ref,
                                    $voucher_total_cost,
                                    $voucher_taxes,
                                    $currency_id,
                                    $currency_name,
                                    $currency_abre,
                                    $currency_tc,
                                    $product_id,
                                    $product_name,
                                    $multitrip,
                                    $vip,
                                    $destination_country_id,
                                    $destination_country_name,
                                    $passenger_document_type_id,
                                    $passenger_document_type_name,
                                    $passenger_document_number,
                                    $passenger_birth_date,
                                    $passenger_gender,
                                    $passenger_first_name,
                                    $passenger_last_name,
                                    $passenger_second_name,
                                    $passenger_city,
                                    $passenger_address,
                                    $passenger_phone,
                                    $passenger_email,
                                    $passenger_emergency_first_name,
                                    $passenger_emergency_last_name,
                                    $passenger_emergency_phone_1,
                                    $passenger_emergency_phone_2,
                                    $pais,
                                    $representante,
                                    $asesor,
                                    $agencia,
                                    $sistema_emision
                                )

            
    {   
        
            
        function fecha_arreglo($fecha)
        {
            $fecha_arreglo = str_replace('/', '-', $fecha);
            $fecha_ANSI= date('Y-m-d',strtotime($fecha_arreglo));
            return $fecha_ANSI;
        }
        
        $fecha_alta = date("Y-m-d H:i:s");
        
        $voucher_date = fecha_arreglo($voucher_date);
        $voucher_date_from = fecha_arreglo($voucher_date_from);
        $voucher_date_to = fecha_arreglo($voucher_date_to);
        $passenger_birth_date = fecha_arreglo($passenger_birth_date);
        
        
        DB::query("
                    INSERT INTO VOUCHER (voucher_number,
                                        voucher_number_am,
                                        voucher_date,
                                        voucher_date_from,
                                        voucher_date_to,
                                        voucher_int_ref,
                                        voucher_total_cost,
                                        voucher_taxes,
                                        currency_id,
                                        currency_name,
                                        currency_abre,
                                        currency_tc,
                                        product_id,
                                        product_name,
                                        multitrip,
                                        vip,
                                        destination_country_id,
                                        destination_country_name,
                                        passenger_document_type_id,
                                        passenger_document_type_name,
                                        passenger_document_number,
                                        passenger_birth_date,
                                        passenger_gender,
                                        passenger_first_name,
                                        passenger_last_name,
                                        passenger_second_name,
                                        passenger_city,
                                        passenger_address,
                                        passenger_phone,
                                        passenger_email,
                                        passenger_emergency_first_name,
                                        passenger_emergency_last_name,
                                        passenger_emergency_phone_1,
                                        passenger_emergency_phone_2,
                                        pais,
                                        representante,
                                        asesor,
                                        agencia,
                                        fecha_alta,
                                        se_id
                                        )
                                VALUES (:voucher_number,
                                        :voucher_number_am,
                                        :voucher_date,
                                        :voucher_date_from,
                                        :voucher_date_to,
                                        :voucher_int_ref,
                                        :voucher_total_cost,
                                        :voucher_taxes,
                                        :currency_id,
                                        :currency_name,
                                        :currency_abre,
                                        :currency_tc,
                                        :product_id,
                                        :product_name,
                                        :multitrip,
                                        :vip,
                                        :destination_country_id,
                                        :destination_country_name,
                                        :passenger_document_type_id,
                                        :passenger_document_type_name,
                                        :passenger_document_number,
                                        :passenger_birth_date,
                                        :passenger_gender,
                                        :passenger_first_name,
                                        :passenger_last_name,
                                        :passenger_second_name,
                                        :passenger_city,
                                        :passenger_address,
                                        :passenger_phone,
                                        :passenger_email,
                                        :passenger_emergency_first_name,
                                        :passenger_emergency_last_name,
                                        :passenger_emergency_phone_1,
                                        :passenger_emergency_phone_2,
                                        :pais,
                                        :representante,
                                        :asesor,
                                        :agencia,
                                        :fecha_alta,
                                        :se_id)       
                    ");
        
        DB::bind(':voucher_number', "$voucher_number");
        DB::bind(':voucher_number_am', "$voucher_number_am");
        DB::bind(':voucher_date', "$voucher_date");
        DB::bind(':voucher_date_from', "$voucher_date_from");
        DB::bind(':voucher_date_to', "$voucher_date_to");
        DB::bind(':voucher_int_ref', "$voucher_int_ref");
        DB::bind(':voucher_total_cost', "$voucher_total_cost");
        DB::bind(':voucher_taxes', "$voucher_taxes");
        DB::bind(':currency_id', "$currency_id");
        DB::bind(':currency_name', "$currency_name");
        DB::bind(':currency_abre', "$currency_abre");
        DB::bind(':currency_tc', "$currency_tc");
        DB::bind(':product_id', "$product_id");
        DB::bind(':product_name', "$product_name");
        DB::bind(':multitrip', "$multitrip");
        DB::bind(':vip', "$vip");
        DB::bind(':destination_country_id', "$destination_country_id");
        DB::bind(':destination_country_name', "$destination_country_name");
        DB::bind(':passenger_document_type_id', "$passenger_document_type_id");
        DB::bind(':passenger_document_type_name', "$passenger_document_type_name");
        DB::bind(':passenger_document_number', "$passenger_document_number");
        DB::bind(':passenger_birth_date', "$passenger_birth_date");
        DB::bind(':passenger_gender', "$passenger_gender");
        DB::bind(':passenger_first_name', "$passenger_first_name");
        DB::bind(':passenger_last_name', "$passenger_last_name");
        DB::bind(':passenger_second_name', "$passenger_second_name");
        DB::bind(':passenger_city', "$passenger_city");
        DB::bind(':passenger_address', "$passenger_address");
        DB::bind(':passenger_phone', "$passenger_phone");
        DB::bind(':passenger_email', "$passenger_email");
        DB::bind(':passenger_emergency_first_name', "$passenger_emergency_first_name");
        DB::bind(':passenger_emergency_last_name', "$passenger_emergency_last_name");
        DB::bind(':passenger_emergency_phone_1', "$passenger_emergency_phone_1");
        DB::bind(':passenger_emergency_phone_2', "$passenger_emergency_phone_2");
        DB::bind(':pais', "$pais");
        DB::bind(':representante', "$representante");
        DB::bind(':asesor', "$asesor");
        DB::bind(':agencia', "$agencia");
        DB::bind(':agencia', "$agencia");
        DB::bind(':fecha_alta', "$fecha_alta");
        DB::bind(':se_id', "$sistema_emision");
        DB::execute();     
        
    }
    
    
    
}