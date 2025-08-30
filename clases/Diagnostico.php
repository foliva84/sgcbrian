<?php
/**
 * Clase: Diagnostico
 *
 *
 * @author ArgenCode
 */

class Diagnostico {
    
    
    // Método para listar todos los diagnosticos
    public static function listar() {
        DB::query("SELECT 
                        diagnostico_id,
                        diagnostico_codigoICD,
                        diagnostico_nombre,
                        diagnostico_activo
                   FROM diagnosticos");
        
        return DB::resultados();
    }
    
    // Método para buscar un diagnostico por nombre, es el resultado del autocomplete
    public static function buscar_select($nombre) {
        DB::query("SELECT diagnostico_id, diagnostico_nombre
                   FROM diagnosticos
                   WHERE diagnostico_nombre LIKE :diagnostico_nombre 
                   AND diagnostico_activo = 1
                   LIMIT 15");
        
        DB::bind(':diagnostico_nombre', "%$nombre%");
        
        return DB::resultados();
    }
    
     // Método para listar diagnosticos
    public static function listar_filtrado($diagnostico_nombre_buscar, $diagnostico_codigoICD_buscar) {
        
        DB::query("SELECT diagnostico_id,
                          diagnostico_codigoICD,
                          diagnostico_nombre, 
                          diagnostico_activo
                    FROM diagnosticos
                    WHERE diagnostico_nombre LIKE :diagnostico_nombre
                    AND diagnostico_codigoICD LIKE :diagnostico_codigoICD
                    LIMIT 50");
        
        DB::bind(':diagnostico_nombre', "%$diagnostico_nombre_buscar%");
        DB::bind(':diagnostico_codigoICD', "%$diagnostico_codigoICD_buscar%");       
                
        return DB::resultados();
    }
    
    // Método para listar diagnosticoes
    public static function listar_filtrado_contar($diagnostico_nombre_buscar, $diagnostico_codigoICD_buscar) {
        
        DB::query("SELECT COUNT(*) AS registros
                    FROM diagnosticos
                    WHERE diagnostico_nombre LIKE :diagnostico_nombre
                    AND diagnostico_codigoICD LIKE :diagnostico_codigoICD");
        
        DB::bind(':diagnostico_nombre', "%$diagnostico_nombre_buscar%");
        DB::bind(':diagnostico_codigoICD', "%$diagnostico_codigoICD_buscar%");
                
        return DB::resultado();
    }
    
    
    // Un tipo de búsqueda, se debería definir el resto.
    public static function buscar($nombre)
    {
        DB::query("SELECT 
                        diagnostico_id,
                        diagnostico_codigoICD,
                        diagnostico_nombre,
                        diagnostico_activo
                   FROM diagnosticos
                   WHERE diagnostico_nombre LIKE :diagnostico_nombre");
        
        DB::bind(':diagnostico_nombre', "%$nombre%");
        return DB::resultado();
    }
    
    
    // Un tipo de búsqueda, se debería definir el resto.
    public static function buscarPorId($diagnostico_id)
    {
        DB::query("SELECT 
                        diagnostico_id,
                        diagnostico_codigoICD,
                        diagnostico_nombre,
                        diagnostico_activo
                   FROM diagnosticos
                   WHERE diagnostico_id = :diagnostico_id");
        
        DB::bind(':diagnostico_id', "$diagnostico_id");
        return DB::resultado();        
    }
    
    // Funcion para desactivar un diagnostico.
    public static function borradoLogico($diagnostico_id)
    {
        DB::query("UPDATE diagnosticos SET
                        diagnostico_activo = :diagnostico_activo                               
                   WHERE diagnostico_id = :diagnostico_id");
        
        DB::bind(':diagnostico_activo', false);
        DB::bind(':diagnostico_id', "$diagnostico_id");
        
        DB::execute();
        $mensaje = "ok";
        return $mensaje;
    }
    
    // Funcion para activar un diagnostico.
    public static function reActivar($diagnostico_id)
    {
        DB::query("UPDATE diagnosticos SET
                        diagnostico_activo = :diagnostico_activo                               
                   WHERE diagnostico_id = :diagnostico_id");
        
        DB::bind(':diagnostico_activo', true);
        DB::bind(':diagnostico_id', "$diagnostico_id");
        
        DB::execute();
        $mensaje = "ok";
        return $mensaje;
    }
          
    // Métodos ABM
   
    // Funcion para insertar un diagnostico
    public static function insertar($diagnostico_codigoICD, 
                                    $diagnostico_nombre)        
    {   
        DB::query("INSERT INTO diagnosticos (diagnostico_codigoICD,
                                            diagnostico_nombre,
                                            diagnostico_activo)
                                     VALUES (:diagnostico_codigoICD,
                                             :diagnostico_nombre,
                                             :diagnostico_activo)");
        
        DB::bind(':diagnostico_codigoICD', "$diagnostico_codigoICD");
        DB::bind(':diagnostico_nombre', "$diagnostico_nombre");
        DB::bind(':diagnostico_activo', true);
                
        DB::execute();     
        
        $diagnostico_id = DB::lastInsertId();
        
        return $diagnostico_id;
    }
    
    
    public static function actualizar($diagnostico_codigoICD,
                                      $diagnostico_nombre, 
                                      $diagnostico_id)
    {                     
        $existe = self::existeUpdate($diagnostico_nombre, $diagnostico_id);
        
        If ($existe == 1){
            $mensaje = "El diagnostico ya existe en la base";
            return $mensaje;
        } else {
        
                DB::query("UPDATE diagnosticos SET
                                diagnostico_codigoICD = :diagnostico_codigoICD,
                                diagnostico_nombre = :diagnostico_nombre
                           WHERE diagnostico_id = :diagnostico_id");

        DB::bind(':diagnostico_codigoICD', "$diagnostico_codigoICD");
        DB::bind(':diagnostico_nombre', "$diagnostico_nombre");
        DB::bind(':diagnostico_id', "$diagnostico_id");

        DB::execute();
        $mensaje = "El diagnostico fue actualizado con éxito";
        return $mensaje;
        }
    }
    
    
    //Verifica si existe el codigoICD antes de hacer un insert   
    public static function existe_codigoICD($diagnostico_codigoICD)
    {
        DB::query("SELECT 
                        diagnostico_id
                   FROM diagnosticos
                   WHERE diagnostico_codigoICD = :diagnostico_codigoICD");
        
        DB::bind(':diagnostico_codigoICD', "$diagnostico_codigoICD");
                
        $diagnostico = DB::resultado(); 
        
        if(!empty($diagnostico) && is_array($diagnostico)){       
            $existe = true;
        }else{
            $existe= false;            
        }
        return $existe;      
    }
    
    //Verifica si existe el diagnostico antes de hacer un insert   
    public static function existe($diagnostico_nombre)
    {
        DB::query("SELECT 
                        diagnostico_id
                   FROM diagnosticos
                   WHERE diagnostico_nombre = :diagnostico_nombre");
        
        DB::bind(':diagnostico_nombre', "$diagnostico_nombre");
                
        $diagnostico = DB::resultado(); 
        
        if(!empty($diagnostico) && is_array($diagnostico)){       
            $existe = true;
        }else{
            $existe= false;            
        }
        return $existe;      
    }
     
    //Verifica si existe el codigoICD antes de hacer un update
    public static function existeUpdate_codigoICD($diagnostico_codigoICD, $diagnostico_id)
    { 
        DB::query("SELECT 
                        diagnostico_id
                   FROM diagnosticos 
                   WHERE diagnostico_codigoICD = :diagnostico_codigoICD
                   AND diagnostico_id <> :diagnostico_id");
        
        DB::bind(':diagnostico_codigoICD', "$diagnostico_codigoICD");
        DB::bind(':diagnostico_id', "$diagnostico_id");
       
        $diagnostico = DB::resultado(); 
        
        if(!empty($diagnostico) && is_array($diagnostico)){       
            $existe = true;
        }else{
            $existe= false;            
        }
        return $existe;      
    }
    
    //Verifica si existe el diagnostico antes de hacer un update
    public static function existeUpdate($diagnostico_nombre, $diagnostico_id)
    { 
        DB::query("SELECT 
                        diagnostico_id
                   FROM diagnosticos 
                   WHERE diagnostico_nombre = :diagnostico_nombre
                   AND diagnostico_id <> :diagnostico_id");
        
        DB::bind(':diagnostico_nombre', "$diagnostico_nombre");
        DB::bind(':diagnostico_id', "$diagnostico_id");
       
        $diagnostico = DB::resultado(); 
        
        if(!empty($diagnostico) && is_array($diagnostico)){       
            $existe = true;
        }else{
            $existe= false;            
        }
        return $existe;      
    }
}
 
           
    
     
    

