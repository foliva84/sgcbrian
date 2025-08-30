<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Coberturas de un voucher especifico
 *
 * @author ArgenCode
 */
class Coverage {
 
    // Metodo para insertar las coberturas de un voucher
    // Recibe el id del voucher, las coberturas y el sistema de emisión.
    public static function insertar($voucher_id, $coberturas, $sistema_emision){
        
         foreach ($coberturas as $cobertura){
            
            $coverage_name = $cobertura["coverage_name"];
            $coverage_val = $cobertura["coverage_value"];
            
        
            DB::query("INSERT INTO coverage
                                (voucher_id, 
                                se_id,
                                coverage_name,
                                coverage_val)
                        VALUES (:voucher_id, 
                                :se_id,
                                :coverage_name,
                                :coverage_val)
                      ");

            DB::bind(':voucher_id', "$voucher_id");
            DB::bind(':se_id', $sistema_emision);
            DB::bind(':coverage_name', "$coverage_name");
            DB::bind(':coverage_val', "$coverage_val");

            DB::execute();    
            
            
        }
         
        $ultimo_id = DB::lastInsertId();
        return $ultimo_id;
    }
    
    
    
    //  ArgenTODO:  Ver este método para listar las coberturas si está funcionando y las modificaciones que hay que hacerle
    public static function listar($product_id, $se_id){
        
        DB::query(" SELECT 
                        coverage_id,
                        coverage_name,
                        coverage_val
                    FROM coverage 
                    WHERE product_id = :product_id
                    AND 
                    se_id = :se_id 
                ");
        
        DB::bind(':product_id', "$product_id");
        DB::bind(':se_id', $se_id);
        
        return DB::resultados();
        
        
    }
    
}
