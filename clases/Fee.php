<?php
/**
 * Clase: Fee
 *
 *
 * @author ArgenCode
 */

class Fee {  
    
    // Funcion para el Select - Lista los Fees
    public static function listar() {
        
        DB::query("SELECT
                    fee_id, 
                    fee_nombre
                    FROM fees WHERE fee_activo = 1 
                    ORDER BY fee_nombre DESC");
        
        return DB::resultados();
    }
    
    // Funcion para el Select - Lista los Fees en modificar caso
    public static function listar_modificacion_casos($caso_id) {
        
        DB::query("SELECT fee_id, fee_nombre
                  FROM fees 
                    LEFT JOIN casos ON caso_fee_id = fee_id
                  WHERE caso_id = :caso_id
                  UNION
                  SELECT fee_id, fee_nombre
                  FROM fees
                  ");
        
        DB::bind(':caso_id', "$caso_id");

        return DB::resultados();
        
    }
}
