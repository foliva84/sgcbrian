<?php
/**
 * Clase: FormaPago
 *
 *
 * @author ArgenCode
 */
    
class FormaPago {

    // Lista las formas de pago
    public static function listar(){
    
        DB::query("SELECT formaPago_id, formaPago_nombre
                    FROM formas_pagos 
                    WHERE formaPago_activa = 1");
        
        return DB::resultados();
    }


    // Lista las formas de pago para Reintegros
    public static function listar_reint(){
    
        DB::query("SELECT formaPago_id, formaPago_nombre
                    FROM formas_pagos 
                    WHERE formaPago_activa = 1 AND formaPago_id != 2");
        
        return DB::resultados();
    }
}