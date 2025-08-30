<?php
/**
 * Clase: WS
 *
 *
 * @author ArgenCode
 * 
 * API  Versión 1.03
 * vouchers_json
 * 
 * Parámetros:
 *   voucher_number : para una búsqueda por número de voucher
 *   passenger_first_name : para una búsqueda por Nombre del pasajero
 *   passenger_last_name : para una búsqueda por Apellido del pasajero
 *   passenger_document_number : para una búsqueda por número de documento del pasajero.
 * 
 * - En caso de no enviar parámetros, el resultado devuelto serán los últimos 15 vouchers emitidos en el SEC
 * - Si se combinan 2 o 3 de los parámetros las búsquedas son “AND”.
 * - Las búsquedas pueden ser parciales, es decir: por parte del Apellido del pasajero o parte del número de documento, etc.
 * 
 * 
 */
// error_reporting(0);
class WS {


    //Métodos para leer el webservice
    // Password del web service
    private static $password = "98erihlkdsh";
    private static $password_assist1 = "krfvdfgqp4866%";
    private static $password_coris = "zmskqp1793%";
    private static $password_sec_old = "tvflmsdkcn9765$%";


    //  Trae la información de un voucher desde el WS
    public static function buscar_ws($voucher_number, 
                                    $passenger_first_name,
                                    $passenger_last_name,  
                                    $passenger_document_number,
                                    $sistema_emision
                                    )
        {
        // Trae la url del sistema de emision
        $url = SistemaEmision::getURLvoucher($sistema_emision);

        if($sistema_emision == "1"){
            try{
                //Lectura del Webservice
                $WS = file_get_contents($url . "?pwd=" . self::$password . "&voucher_number=" . $voucher_number . "&passenger_document_number=" . $passenger_document_number . "&passenger_first_name=" . $passenger_first_name . "&passenger_last_name=" . $passenger_last_name);
                return $WS;
            } catch (Exception $ex) {
                return "Error en la conexión:";
            }
        }

        if($sistema_emision == "2"){
            try{
                //Lectura del Webservice
                $WS = file_get_contents($url . "?accesskey=" . self::$password_assist1 . "&voucher_number=" . $voucher_number . "&passenger_document_number=" . $passenger_document_number . "&passenger_first_name=" . $passenger_first_name . "&passenger_last_name=" . $passenger_last_name);
                return $WS;
            } catch (Exception $ex) {
                return "Error en la conexión:";
            }
        }

        if ($sistema_emision == "3"){
            try{
                //Lectura del Webservice
                $WS = file_get_contents($url . "?accesskey=" . self::$password_coris . "&voucher_number=" . $voucher_number . "&passenger_document_number=" . $passenger_document_number . "&passenger_first_name=" . $passenger_first_name . "&passenger_last_name=" . $passenger_last_name);
                return $WS;
            } catch (Exception $ex) {
                return "Error en la conexión:";
            }
        }

        if ($sistema_emision == "4"){
            try{
                //Lectura del Webservice
                $WS = file_get_contents($url . "?accesskey=" . self::$password_sec_old . "&voucher_number=" . $voucher_number . "&passenger_document_number=" . $passenger_document_number . "&passenger_first_name=" . $passenger_first_name . "&passenger_last_name=" . $passenger_last_name);
                return $WS;
            } catch (Exception $ex) {
                return "Error en la conexión:";
            }
        }

    }


    // Guardar un voucher y el producto en la BD - Recibe el numero de voucher y el sistema de emisión.
    public static function insertar_voucher_producto($voucher_number, $sistema_emision) {

        $vouchers = self::buscar_ws($voucher_number,'','','', $sistema_emision);
        $vouchers = json_decode($vouchers, true);

        // Si lo que viene como vouchers es un array procede

        if(is_array($vouchers)) {

            foreach($vouchers as $voucher) {
                if (is_array($voucher)) {
                    foreach($voucher as $atributo) {
                        $voucher_id = $atributo['voucher_id'];
                        $voucher_number = $atributo['voucher_number'];
                        $voucher_date = $atributo['voucher_date'];
                        $voucher_date_from =$atributo['voucher_date_from'];
                        $voucher_date_to = $atributo['voucher_date_to'];
                        $voucher_int_ref = $atributo['voucher_int_ref'];
                        $voucher_notes = $atributo['voucher_notes'];
                        $voucher_total_cost = $atributo['voucher_total_cost'];
                        $voucher_taxes = $atributo['voucher_taxes'];
                        $currency_id = $atributo['currency_id'];
                        $currency_name = $atributo['currency_name'];
                        $currency_tc = $atributo['currency_tc'];
                        $product_id = $atributo['product_id'];
                        $product_name = $atributo['product_name'];
                        $country_id = $atributo['country_id'];
                        $country_name = $atributo['country_name'];
                        $agency_id = $atributo['agency_id'];
                        $agency_name = $atributo['agency_name'];
                        $issuing_user_id = $atributo['issuing_user_id'];
                        $issuing_user_name = $atributo['issuing_user_name'];
                        $passenger_document_type_id = $atributo['passenger_document_type_id'];
                        $passenger_document_type_name = $atributo['passenger_document_type_name'];
                        $passenger_document_number = $atributo['passenger_document_number'];
                        $passenger_birth_date = $atributo['passenger_birth_date'];
                        $passenger_gender = $atributo['passenger_gender'];
                        $passenger_first_name = $atributo['passenger_first_name'];
                        $passenger_last_name = $atributo['passenger_last_name'];
                        $passenger_second_name = $atributo['passenger_second_name'];
                        $passenger_city =  $atributo['passenger_city'];
                        $passenger_address =  $atributo['passenger_address'];
                        $passenger_phone =  $atributo['passenger_phone'];
                        $passenger_email =  $atributo['passenger_email'];
                        $passenger_emergency_first_name = $atributo['passenger_emergency_first_name'];
                        $passenger_emergency_last_name = $atributo['passenger_emergency_last_name'];
                        $passenger_emergency_phone_1 = $atributo['passenger_emergency_phone_1'];
                        $passenger_emergency_phone_2 = $atributo['passenger_emergency_phone_2'];
                        // Trae todas las coberturas del producto
                        $coberturas = $atributo['coverages'];
                    }
                }
            }
        }

        // Busca si dentro de las coberturas hay deducible
        // El valor por defecto si no lo encuentra es no

        $deducible = "No";
        foreach ($coberturas as $cobertura){

            $coverage_name = $cobertura["coverage_name"];
            $coverage_val = $cobertura["coverage_value"];


            // Busca dentro del texto convirtiendo todo en mayúsculas

            if (strpos(strtoupper($coverage_name),'DEDUCIBLE') !==FALSE ){

                $deducible = $coverage_val;
                break;
            }

        }




        // Función para arreglar las fechas
        function fecha_arreglo($fecha) {
            $fecha_arreglo = str_replace('/', '-', $fecha);
            $fecha_ANSI= date('Y-m-d',strtotime($fecha_arreglo));
            return $fecha_ANSI;
        }

        // Fecha de alta en la base de datos del voucher actual
        $fecha_alta = date("Y-m-d H:i:s");

        // Arregla las fechas para insertarlas a la base de datos
        $voucher_date = fecha_arreglo($voucher_date);
        $voucher_date_from = fecha_arreglo($voucher_date_from);
        $voucher_date_to = fecha_arreglo($voucher_date_to);
        $passenger_birth_date = fecha_arreglo($passenger_birth_date);

        // Arregla passenger_gender
        if($passenger_gender == 'm'){
            $passenger_gender = 1;
        }elseif($passenger_gender == 'f'){
            $passenger_gender = 2;
        }else{
            $passenger_gender = null;
        }

        // Verifica que el voucher no se encuentre en la base de datos
        DB::query("SELECT COUNT(voucher_number) AS CantVouchers
                       FROM voucher
                       WHERE voucher_number = :voucher_number");
        DB::bind(':voucher_number', "$voucher_number");

        $vouchers_existentes = DB::resultado();

        $cant_vouchers = $vouchers_existentes["CantVouchers"];

        // Si no se encuentra en la BD lo agrega
        if($cant_vouchers < 1) {
            // Inserta el voucher en la base de datos
            DB::query("
                        INSERT INTO VOUCHER (
                                            voucher_fecha_int,	
                                            voucher_id,	
                                            voucher_number,
                                            voucher_date,
                                            voucher_date_from,
                                            voucher_date_to,
                                            voucher_int_ref,
                                            voucher_notes,                
                                            voucher_total_cost,
                                            voucher_taxes,
                                            currency_id,
                                            currency_name,
                                            currency_tc,
                                            product_id,
                                            product_name,
                                            country_id,
                                            country_name,
                                            agency_id,
                                            agency_name,
                                            issuing_user_id,
                                            issuing_user_name,
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
                                            deducible,
                                            se_id
                                            )
                                    VALUES (:voucher_fecha_int,
                                            :voucher_id,	
                                            :voucher_number,
                                            :voucher_date,
                                            :voucher_date_from,
                                            :voucher_date_to,
                                            :voucher_int_ref,
                                            :voucher_notes,                
                                            :voucher_total_cost,
                                            :voucher_taxes,
                                            :currency_id,
                                            :currency_name,
                                            :currency_tc,
                                            :product_id,
                                            :product_name,
                                            :country_id,
                                            :country_name,
                                            :agency_id,
                                            :agency_name,
                                            :issuing_user_id,
                                            :issuing_user_name,
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
                                            :deducible,
                                            :se_id)       
                    ");
            DB::bind(':voucher_fecha_int', "$fecha_alta");
            DB::bind(':voucher_id', "$voucher_id");
            DB::bind(':voucher_number', "$voucher_number");
            DB::bind(':voucher_date', "$voucher_date");
            DB::bind(':voucher_date_from', "$voucher_date_from");
            DB::bind(':voucher_date_to', "$voucher_date_to");
            DB::bind(':voucher_int_ref', "$voucher_int_ref");
            DB::bind(':voucher_notes', "$voucher_notes");
            DB::bind(':voucher_total_cost', "$voucher_total_cost");
            DB::bind(':voucher_taxes', "$voucher_taxes");
            DB::bind(':currency_id', "$currency_id");
            DB::bind(':currency_name', "$currency_name");
            DB::bind(':currency_tc', "$currency_tc");
            DB::bind(':product_id', "$product_id");
            DB::bind(':product_name', "$product_name");
            DB::bind(':country_id', "$country_id");
            DB::bind(':country_name', "$country_name");
            DB::bind(':agency_id', "$agency_id");
            DB::bind(':agency_name', "$agency_name");
            DB::bind(':issuing_user_id', "$issuing_user_id");
            DB::bind(':issuing_user_name', "$issuing_user_name");
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
            DB::bind(':deducible', "$deducible");
            DB::bind(':se_id', "$sistema_emision");
            DB::execute();

            // Toma el id del voucher para pasárselo a las coberturas
            $voucher_id = DB::lastInsertId();

            // Inserta las coberturas del voucher en la tabla
            Coverage::insertar($voucher_id, $coberturas, $sistema_emision);

        }

        //
        // Ver si el producto existe, de ser así no hace nada, sino lo agrega a la lista
        //
        $existe_producto = Product::existe($product_name, $sistema_emision);

        // Si el producto no existe, lo agrega a la lista y trae su id_interno para ponerlo luego en el caso
        if ($existe_producto < 1) {

            // Agrega el producto
            $product_id_interno = Product::insertar($product_id, $product_name, $sistema_emision);

            // Agrega la relación cliente-producto
            $cliente_id = Cliente::obtener_cliente_coris_id($voucher_number);

            Cliente::insertar_producto($cliente_id, $product_id_interno);


        } else {

            $product_id_interno = $existe_producto;

            // Agrega la relación cliente-producto por más que el producto ya esté, porque puede ser de otro país
            $cliente_id = Cliente::obtener_cliente_coris_id($voucher_number);

            Cliente::insertar_producto($cliente_id, $product_id_interno);

        }


        array_unshift ( $vouchers , $product_id_interno );

        return $vouchers;
    }



    private static function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }


    // Un tipo de búsqueda, se debería definir el resto.
    public static function buscar($voucher_numero, $sistema_emision){
        DB::query("SELECT 
                        
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




}