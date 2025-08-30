<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Product
 *
 * @author ArgenCode
 */
class Product {
 
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
    
    
    
    
    
// Metodos de SISTEMA DE EMISION    
    // Lista los productos de un Sistema De Emision
    public static function buscar($product_id,$se_id) {

        DB::query(" SELECT 
                        product_interno_id, 
                        product_name,
                        product_se_id
                    FROM product 
                    WHERE product_id = :product_id
                    AND 
                    product_se_id = :product_se_id 
                ");
        
        DB::bind(':product_interno_id', "$product_id");
        DB::bind(':product_se_id', $se_id);
        
        return DB::resultado();
    }
    
    
    // Para buscar el producto por su nombre
     public static function existe($product_name,$se_id) {

        DB::query("
                    SELECT 
                        product_id_interno            
                    FROM product 
                    WHERE product_name = :product_name
                    AND 
                    product_se_id = :product_se_id 
                ");
        
        DB::bind(':product_name', "$product_name");
        DB::bind(':product_se_id', $se_id);
        
        $productos_existentes = DB::resultado();
        
        $product_id_interno = $productos_existentes["product_id_interno"];
        
        
        return $product_id_interno;
        
        
    }
    
    
    
    // Metodo para insertar un producto
    public static function insertar($product_id, $product_nombre, $se_id){   
        DB::query("INSERT INTO product
                            (product_id, 
                            product_name,
                            product_se_id,
                            product_activo)
                    VALUES (:product_id,
                            :product_name,
                            :product_se_id,
                            :product_activo)");
        
        DB::bind(':product_id', "$product_id");
        DB::bind(':product_name', "$product_nombre");
        DB::bind(':product_se_id', $se_id);
        DB::bind(':product_activo', 1);
                
        DB::execute();

        $product_id_interno = DB::lastInsertId();
        
        return $product_id_interno;
    }
    
// Metodos Varios
    // Método para el Select - Lista los Productos Activos
    public static function listar_activos() {

        DB::query("SELECT product_id_interno, product_name
                    FROM product ORDER BY product_name");

        return DB::resultados();
    }
    
    
    // Método para el Select - Lista los Productos segun el cliente elegido
    public static function listar_segunCliente($cliente_id) {

        DB::query("SELECT product.product_id_interno, product.product_name
                    FROM clientes_products
                        LEFT JOIN product ON product_id_interno = clienteProduct_product_id
                    WHERE clienteProduct_cliente_id = :clienteProduct_cliente_id");

        DB::bind(':clienteProduct_cliente_id', "$cliente_id");
        
        return DB::resultados();
    }
    
    
    // Método para el Select - Lista los productos en Modificar Caso
    public static function listar_modificacion_casos($caso_id, $cliente_id) {
    
        DB::query("SELECT product_id_interno, product_name
                        FROM product 
                        INNER JOIN casos ON caso_producto_id = product_id_interno 
                        WHERE caso_id = :caso_id
                   UNION
                   SELECT product.product_id_interno, product.product_name
                   FROM clientes_products
                    LEFT JOIN product ON product_id_interno = clienteProduct_product_id
                    LEFT JOIN clientes ON cliente_id = clienteProduct_cliente_id
                   WHERE product_id_interno NOT IN (SELECT caso_producto_id FROM casos WHERE caso_id = :caso_id) AND
                         clienteProduct_cliente_id = :clienteProduct_cliente_id");

        DB::bind(':caso_id', "$caso_id");
        DB::bind(':clienteProduct_cliente_id', "$cliente_id");  

        return DB::resultados();
    }    
    
}
