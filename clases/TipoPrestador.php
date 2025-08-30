<?php
/**
 * Clase: TipoPrestador
 *
 *
 * @author ArgenCode
 */

class TipoPrestador {
    
    
// Para listar.
    public static function listar()
    {
        DB::query("SELECT 
                        tipoPrestador_id,
                        tipoPrestador_nombre,
                        tipoPrestador_activo
                   FROM tipos_prestadores");
        return DB::resultados();
    }
    
    
    // Un tipo de búsqueda, se debería definir el resto.
    public static function buscar($nombre)
    {
        DB::query("SELECT 
                        tipoPrestador_id,
                        tipoPrestador_nombre
                   FROM tipos_prestadores
                   WHERE tipoPrestador_nombre LIKE :tipoPrestador_nombre");
        DB::bind(':tipoPrestador_nombre', "%$nombre%");
        return DB::resultado();
    }
    
    
    // Un tipo de búsqueda, se debería definir el resto.
    public static function buscarPorId($tipoPrestador_id)
    {
        DB::query("SELECT 
                        tipoPrestador_id,
                        tipoPrestador_nombre,
                        tipoPrestador_activo
                   FROM tipos_prestadores
                   WHERE tipoPrestador_id = :tipoPrestador_id");
        
        DB::bind(':tipoPrestador_id', "$tipoPrestador_id");
        return DB::resultado();        
    }
    
    // Funcion para desactivar un tipo de prestador.
    public static function borradoLogico($tipoPrestador_id)
    {
        DB::query("
                    UPDATE tipos_prestadores SET
                        tipoPrestador_activo = :tipoPrestador_activo                               
                    WHERE tipoPrestador_id = :tipoPrestador_id
                  ");
        
        DB::bind(':tipoPrestador_activo', false);
        DB::bind(':tipoPrestador_id', "$tipoPrestador_id");
        
        DB::execute();
        $mensaje = "ok";
        return $mensaje;
    }
    
    // Funcion para activar un tipo de prestador.
    public static function reActivar($tipoPrestador_id)
    {
        DB::query("
                    UPDATE tipos_prestadores SET
                               tipoPrestador_activo = :tipoPrestador_activo                               
                    WHERE tipoPrestador_id = :tipoPrestador_id
                  ");
        
        DB::bind(':tipoPrestador_activo', true);
        DB::bind(':tipoPrestador_id', "$tipoPrestador_id");
        
        DB::execute();
        $mensaje = "ok";
        return $mensaje;
    }
          
    // Métodos ABM
   
    // Funcion para insertar un tipo de prestador
    public static function insertar($tipoPrestador_nombre)        
    {   
        DB::query("
                    INSERT INTO tipos_prestadores (tipoPrestador_nombre,
                                                   tipoPrestador_activo)
                                           VALUES (:tipoPrestador_nombre,
                                                   :tipoPrestador_activo)       
                 ");
        
        DB::bind(':tipoPrestador_nombre', "$tipoPrestador_nombre");
        DB::bind(':tipoPrestador_activo', true);
                
        DB::execute();     
        
        $tipoPrestador_id = DB::lastInsertId();
        
        return $tipoPrestador_id;
    }
    
    
    public static function actualizar($tipoPrestador_nombre, 
                                      $tipoPrestador_id)
    {                     
        $existe = self::existeUpdate($tipoPrestador_nombre, $tipoPrestador_id);
        
        If ($existe == 1){
            $mensaje = "El tipo de prestador ya existe en la base";
            return $mensaje;
        } else {
        
                DB::query("
                            UPDATE tipos_prestadores SET
                               tipoPrestador_nombre = :tipoPrestador_nombre
                            WHERE tipoPrestador_id = :tipoPrestador_id
                          ");

        DB::bind(':tipoPrestador_nombre', "$tipoPrestador_nombre");
        DB::bind(':tipoPrestador_id', "$tipoPrestador_id");

        DB::execute();
        $mensaje = "El tipo de prestador fue actualizado con éxito";
        return $mensaje;
        }
    }
    
    
    //Verificar si existe el tipo de prestador antes de hacer un insert   
    public static function existe($tipoPrestador_nombre)
    {
        DB::query("SELECT 
                        tipoPrestador_id,
                        tipoPrestador_nombre,
                        tipoPrestador_activo
                   FROM tipos_prestadores
                   WHERE tipoPrestador_nombre = :tipoPrestador_nombre");
        
        DB::bind(':tipoPrestador_nombre', "$tipoPrestador_nombre");
                
        $tipoPrestador = DB::resultado(); 
        
        if(!empty($tipoPrestador) && is_array($tipoPrestador)){       
            $existe = true;
        }else{
            $existe = false;            
        }
        return $existe;      
    }
     
    //Verificar si existe el tipo de prestador antes de hacer un update
    public static function existeUpdate($tipoPrestador_nombre, $tipoPrestador_id)
    { 
        DB::query("SELECT 
                        tipoPrestador_id,
                        tipoPrestador_nombre,
                        tipoPrestador_activo
                   FROM tipos_prestadores 
                   WHERE tipoPrestador_nombre = :tipoPrestador_nombre
                   AND tipoPrestador_id <> :tipoPrestador_id");
        
        DB::bind(':tipoPrestador_nombre', "$tipoPrestador_nombre");
        DB::bind(':tipoPrestador_id', "$tipoPrestador_id");
       
        $tipoPrestador = DB::resultado(); 
        
        if(!empty($tipoPrestador) && is_array($tipoPrestador)){       
            $existe = true;
        }else{
            $existe= false;            
        }
        return $existe;      
    }
    
    // Funcion para el Select - Lista los tipos de prestador
    public static function formulario_alta_tipoPrestador() {

        DB::query("SELECT
                    tipoPrestador_id, 
                    tipoPrestador_nombre
                   FROM tipos_prestadores WHERE tipoPrestador_activo = 1 ORDER BY tipoPrestador_nombre");

        return DB::resultados();
    }
    
    // Funcion para completar el SELECT de roles en la modificacion del usuario
    public static function formulario_modificacion_tipoPrestador($prestador_id){
        DB::query(" 
                    SELECT tipoPrestador_id, tipoPrestador_nombre 
                    FROM tipos_prestadores WHERE tipoPrestador_id 
                    IN (SELECT prestador_tipoPrestador_id FROM `prestadores` WHERE prestador_id = :prestador_id) 
                    UNION
                    SELECT tipoPrestador_id, tipoPrestador_nombre 
                    FROM tipos_prestadores 
                    ");
        DB::bind(':prestador_id', "$prestador_id");
    
        return DB::resultados();
       
        
    }
}
 
           
    
     
    

