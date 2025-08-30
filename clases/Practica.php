<?php
/**
 * Clase: Practica
 *
 *
 * @author ArgenCode
 */

class Practica {
     
    // Un tipo de búsqueda, se debería definir el resto.
    public static function buscar($nombre){
        DB::query("SELECT * FROM practicas 
                                WHERE practica_nombre LIKE :practica_nombre");
        DB::bind(':practica_nombre', "%$nombre%");
        return DB::resultado();
    }
    
    // Para listar.
    public static function listar(){
        DB::query("SELECT 
                    practica_id,
                    practica_nombre,
                    practica_activa
                    FROM practicas");
        return DB::resultados();  
    }
    
     
     
     
    // Un tipo de búsqueda, se debería definir el resto.
    public static function buscarPorId($practica_id){
        DB::query("SELECT 
                    practicas.practica_id,
                    practicas.practica_nombre,
                    practicas.practica_activa
                    FROM practicas
                    WHERE practica_id = :practica_id");
        DB::bind(':practica_id', "$practica_id");
        return DB::resultado();        
    }
    
    // Metodo para re activar una practica
    public static function reActivar($practica_id){  
        DB::query("UPDATE practicas SET
                           practica_activa = :practica_activa
                   WHERE practica_id = :practica_id");            
          
        DB::bind(':practica_activa', true);
        DB::bind(':practica_id', "$practica_id");

        DB::execute();
        $mensaje = "ok";
        return $mensaje;
    }
    
    // Métodos ABM
    public static function actualizar($practica_nombre, $practica_id){
        
        $existe = self::existeUpdate($practica_nombre, $practica_id);   
        
        If ($existe == 1){
            $mensaje = "La práctica ya existe en la base";
            return $mensaje;
        } else {
                DB::query("UPDATE practicas SET
                             practica_nombre = :practica_nombre
                      WHERE practica_id = :practica_id");
          
            DB::bind(':practica_nombre', "$practica_nombre");
            DB::bind(':practica_id', "$practica_id");
            
            DB::execute();
            $mensaje = "La practica fue actualizada con éxito";
            return $mensaje;
        }
    } 
    
    // Metodo para insertar una practica
    public static function insertar($practica_nombre){   
        DB::query("INSERT INTO practicas (practica_nombre,
                                          practica_activa)
                                VALUES (:practica_nombre,
                                        :practica_activa)");
        
        DB::bind(':practica_nombre', "$practica_nombre");
        DB::bind(':practica_activa', true);
        
        DB::execute();     
        
        $mensaje = "La práctica fue creada con éxito";
        return $mensaje;
    }
    
    // Metodo para el borrado logico de una practica
    public static function borradoLogico($practica_id){
        
        DB::query("UPDATE practicas SET
                          practica_activa = :practica_activa
                    WHERE practica_id = :practica_id");
        
        DB::bind(':practica_activa', false);
        DB::bind(':practica_id',$practica_id);
        DB::execute();
        $mensaje = "ok";
        return $mensaje;
    }
     
    //Verificar si existe la practica antes de hacer un insert   
    public static function existe($practica_nombre){
        DB::query("SELECT * FROM practicas 
                  WHERE practica_nombre = :practica_nombre");
        
        DB::bind(':practica_nombre', "$practica_nombre");
        
        $practica = DB::resultado(); 
        
        if(!empty($practica) && is_array($practica)){       
            $existe = true;
        }else{
            $existe= false;            
        }
        return $existe;      
    }
     
    //Verificar si existe la practica antes de hacer un update
    public static function existeUpdate($practica_nombre, $practica_id)
    { 
        DB::query("SELECT * FROM practicas 
                                WHERE practica_nombre = :practica_nombre
                                AND practica_id <> :practica_id");
        DB::bind(':practica_nombre', "$practica_nombre");
        DB::bind(':practica_id', "$practica_id");
       
        $practica = DB::resultado(); 
        
        if(!empty($practica) && is_array($practica)){     
            $existe = true;
        }else{
            $existe= false;            
        }
        return $existe;
    }
}
