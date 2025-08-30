<?php
/**
 * Clase: Moneda
 *
 *
 * @author ArgenCode
 */

class Moneda {
    
    // Método para buscar un prestador por nombre, es el resultado del autocomplete en alta de factura
    public static function buscar($moneda) {

        Self::test($moneda);

        DB::query("SELECT moneda_id, moneda_nombre
                   FROM monedas
                   WHERE moneda_nombre LIKE :moneda
                   AND moneda_activa = 1
                   LIMIT 10");
        
        DB::bind(':moneda', "%$moneda%");
        
        return DB::resultados();        

    }


    // Método para calcular el tipo de cambio y moneda usando la API
    public static function calculo_tc_live($moneda_id) {

        // SELECT para buscar moneda_nombre en base a moneda_id
        DB::query("SELECT moneda_nombre
                   FROM monedas
                   WHERE moneda_id = :moneda_id");

        DB::bind(':moneda_id', $moneda_id);
        
        $resultado = DB::resultado();

        $moneda_nombre = $resultado['moneda_nombre'];

        // Set API Endpoint and access key (and any options of your choice)
        $endpoint = 'live';
        $access_key = '2NgMGSgT3Ea2UNJZZEHVL7vEYgniu1oq';


        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.apilayer.com/currency_data/" . $endpoint . "?source=USD&currencies=",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: text/plain",
                "apikey: " .  $access_key
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET"
        ));
        $json = curl_exec($curl);
        curl_close($curl);


        // Decode JSON response
        $exchangeRates = json_decode($json, true);

        // Access the exchange rate values
        if ($moneda_nombre === 'USD'){
            $tipo_cambio = 1;
        } else {
            $tipo_cambio = $exchangeRates['quotes']['USD' . $moneda_nombre];
        }

        return $tipo_cambio;
    }


    // Método para calcular el tipo de cambio y moneda usando la API
    public static function calculo_tc_history($moneda_id, $fecha_emision) {

        // SELECT para buscar moneda_nombre en base a moneda_id
        DB::query_t("SELECT moneda_nombre
                   FROM monedas
                   WHERE moneda_id = :moneda_id");

        DB::bind(':moneda_id', $moneda_id);
        
        $resultado = DB::resultado();

        $moneda_nombre = $resultado['moneda_nombre'];

        // Set API Endpoint and access key (and any options of your choice)
        $endpoint = 'historical';
        $access_key = '2NgMGSgT3Ea2UNJZZEHVL7vEYgniu1oq';


        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.apilayer.com/currency_data/" . $endpoint . "?date=" . $fecha_emision,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: text/plain",
                "apikey: " . $access_key
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET"
        ));
        $json = curl_exec($curl);
        curl_close($curl);


        // Decode JSON response
        $exchangeRates = json_decode($json, true);

        // Access the exchange rate values
        if ($moneda_nombre === 'USD'){
            $tipo_cambio = 1;
        } else {
            $tipo_cambio = $exchangeRates['quotes']['USD' . $moneda_nombre];
        }

        return $tipo_cambio;
    }


    // Método para el Select - Lista las Monedas en los formularios de ALTA
    public static function listar_form_alta() {
        DB::query("SELECT moneda_id, moneda_nombre
                   FROM monedas
                   WHERE moneda_activa = 1 
                   ORDER BY moneda_nombre");
        
        return DB::resultados();
    }
    
    
    // Método para el Select - Lista las Monedas en los formularios de MODIFICACION de Facturas Items (FCI)
    public static function fci_listar_form_modificacion($moneda_id){
        
        DB::query("SELECT moneda_id, moneda_nombre
                   FROM monedas
                        INNER JOIN fc_items ON fcItem_monedaOrigen_id = moneda_id
                   WHERE fcItem_monedaOrigen_id = :moneda_id
                   UNION
                   SELECT moneda_id, moneda_nombre
                   FROM monedas
                   WHERE moneda_activa = 1");
        
        DB::bind(':moneda_id', $moneda_id);
        
        return DB::resultados();
    }
}