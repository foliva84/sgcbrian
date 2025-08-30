<?php



/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Producto
 *
 * @author ArgenCode
 */
class Producto {
    
    
     // Método para buscar por ID del caso la cobertura del voucher
    public static function buscar_por_caso($caso_id){
        
        DB::query("SELECT 
                        caso_numeroVoucher
                   FROM casos
                   WHERE caso_id = :caso_id");
        
        DB::bind(':caso_id', $caso_id);
        
        $resultado = DB::resultado();
        $caso_numeroVoucher = $resultado["caso_numeroVoucher"];
        
        
        DB::query("SELECT 
                        voucher_id_int
                   FROM voucher
                   WHERE voucher_number = :caso_numeroVoucher");
        
        DB::bind(':caso_numeroVoucher', "$caso_numeroVoucher");
        
        $resultado = DB::resultado();
        $voucher_id = $resultado["voucher_id_int"];
        
        
        DB::query("SELECT 
                            coverage_name,
                            coverage_val
                   FROM coverage
                   WHERE voucher_id = :voucher_id");
        
        DB::bind(':voucher_id', "$voucher_id");
        
        $resultados = DB::resultados();
      
        
        return $resultados; 
    
        
    }
    
    
   
}
