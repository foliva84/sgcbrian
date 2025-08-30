<?php
/**
 * Clase: Herramientas
 *
 *
 * @author ArgenCode
 */

class Herramientas {

    // Convierte una fecha en formato ANSI para guardar en la base de datos
    public static function fecha_hora_formateo($fecha = null)
    {
        $fecha = str_replace('/', '-', $fecha);
        $time = strtotime($fecha);
        $fecha = ($time === false) ? null : date('Y-m-d H:i:s', $time);
        return $fecha;
    }

    // Convierte una fecha en formato ANSI para guardar en la base de datos
    public static function fecha_formateo($fecha = null)
    {
        $fecha = str_replace('/', '-', $fecha);
        $time = strtotime($fecha);
        $fecha = ($time === false) ? null : date('Y-m-d', $time);
        return $fecha;

    }

    //Conversiones de la fecha de ANSI a normal para Datepicker
    public static function fechaANSI_formateo($fecha_ansi) {
        
        if ($fecha_ansi !== NULL) {
            $fecha = date("d-m-Y", strtotime($fecha_ansi));
            return $fecha;
        } else {
            $fecha = NULL;
            return $fecha;
        }
    }

    // Calcula la  edad en base a la fecha de nacimiento
    public static function calcula_edad($fecha_nacimiento = null){
        if($fecha_nacimiento = date('Y-m-d', strtotime($fecha_nacimiento))){
            $cumpleanos = new DateTime($fecha_nacimiento);
            $hoy = new DateTime();
            $annos = $hoy->diff($cumpleanos);

            $resultado = $annos->y;

        } else {
            $resultado =  0;
        }
      
        return $resultado;
        
    }


    public static function genero($genero){
        if($genero == 'm' || $genero = 1 ) {
            $genero = 1;
        } elseif ($genero == 'f' || $genero = 2 ) {
            $genero = 2;
        } else {
            $genero = null;
        } 
        return $genero;
    } 

    
    public static function es_booleano($dato){
        if ($dato == 1) {
            $resultado = 1;
        } else {
            $resultado = 0;
        }
        return $resultado;
    }


    // ESTA FUNCION ESTABA CUANDO SE MODIFICABA UN PRODUCTO

    public static function verifica_producto(){ 

        //Verifica si ya existe el producto_id con su cliente_id correspondiente
        DB::query("SELECT clienteProduct_id FROM clientes_products
        WHERE clienteProduct_product_id = :caso_producto_id AND clienteProduct_cliente_id = :caso_cliente_id");

        DB::bind(':caso_producto_id', "$caso_producto_id");
        DB::bind(':caso_cliente_id', "$caso_cliente_id");

        $resultado = DB::resultado();
        if ($resultado["clienteProduct_id"] == null){       

            //Guarda el producto_id con su cliente_id correspondiente
            DB::query("INSERT INTO clientes_products 
                            (clienteProduct_product_id,
                            clienteProduct_cliente_id)
                    VALUES (:caso_producto_id,
                            :caso_cliente_id)
            ");

            DB::bind(':caso_producto_id', "$caso_producto_id");
            DB::bind(':caso_cliente_id', "$caso_cliente_id");

            DB::execute();                

        }

    }




}