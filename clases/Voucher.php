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

class Voucher {
    

    public static function buscar($caso_numeroVoucher_n) {


        DB::query("SELECT
            voucher_number, 
            passenger_first_name,
            passenger_last_name,
            passenger_second_name,
            deducible,
            passenger_birth_date,
            passenger_gender, 
            passenger_document_number,
            agency_name,
            issuing_user_name,
            voucher_id_int,
            voucher_date,
            voucher_date_from,
            voucher_date_to,
            product_name
        FROM voucher
        WHERE voucher_number = :voucher_number");

        DB::bind(':voucher_number', "$caso_numeroVoucher_n");

        return DB::resultado();
   
    }
    
    
}