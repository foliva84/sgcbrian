<?php
/**
 * Clase: Permiso
 *
 *
 * @author ArgenCode
 */

class Permiso { 
   

   
      public static function listar(){
        DB::query("SELECT
                        permiso_id,
                        permiso_nombre,
                        permiso_variable
                 FROM permisos 
                 ORDER BY permiso_nombre");
        return DB::resultados();
      }
      
      public static function buscarPorId($permiso_id){
        DB::query("
                 SELECT
                        permiso_id,
                        permiso_nombre,
                        permiso_variable
                 FROM permisos
                 WHERE permiso_id = :permiso_id
                    ");
        DB::bind(':permiso_id', $permiso_id); 
        return DB::resultado();
      }
      
          
      
      
      public static function listar_por_rolid($rol_id){
        DB::query("
                 SELECT
                        permisos.permiso_id,
                        permisos.permiso_nombre,
                        permisos.permiso_variable,
                        roles_permisos.rol_id as rol_id
                 FROM permisos
                 LEFT JOIN roles_permisos
                 ON permisos.permiso_id = roles_permisos.permiso_id
                 WHERE roles_permisos.rol_id = :rol_id
                    ");
        
        DB::bind(':rol_id', "$rol_id");   
        return DB::resultados();
      }
    
    
    // Metodo para insertar un permiso
    public static function insertar($permiso_nombre,$permiso_variable){   
        DB::query("
                    INSERT INTO permisos
                        (permiso_nombre,
                        permiso_variable) 
                    VALUES (:permiso_nombre,
                            :permiso_variable)
                    ");
        DB::bind(':permiso_nombre', "$permiso_nombre");
        DB::bind(':permiso_variable', "$permiso_variable");
        return DB::execute();     
    }
    
    
    
     public static function actualizar($permiso_nombre,$permiso_variable,$permiso_id){
          
            DB::query("
                        UPDATE permisos SET
                               permiso_nombre = :permiso_nombre,
                               permiso_variable = :permiso_variable
                        WHERE permiso_id = :permiso_id
                    ");
            DB::bind(':permiso_nombre', "$permiso_nombre");
            DB::bind(':permiso_variable', "$permiso_variable");
            DB::bind(':permiso_id', "$permiso_id");
      
            DB::execute();
    }
    
    
    
    
    
    
    
    
    public static function nombre_existe($permiso_nombre)
    {
        DB::query("
                  SELECT * FROM permisos 
                  WHERE permiso_nombre = :permiso_nombre 
                  ");
        DB::bind(':permiso_nombre', "$permiso_nombre");
        $permiso = DB::resultado(); 
        
        if(!empty($permiso) && is_array($permiso)){       
            $existe = true;
        }else{
            $existe= false;            
        }
        return $existe;      
    }
    
    
    public static function nombre_existe_modificacion($permiso_nombre, $permiso_id)
    {
        DB::query("
                  SELECT * FROM permisos 
                  WHERE permiso_nombre = :permiso_nombre 
                  AND permiso_id <> :permiso_id
                    ");
        DB::bind(':permiso_nombre', "$permiso_nombre");
        DB::bind(':permiso_id', $permiso_id);
        $permiso = DB::resultado(); 
        
        if(!empty($permiso) && is_array($permiso)){       
            $existe = true;
        }else{
            $existe= false;            
        }
        return $existe;      
    }
    
    public static function variable_existe($permiso_variable)
    {
        DB::query("
                    SELECT * FROM permisos 
                    WHERE permiso_variable = :permiso_variable 

                    ");
        DB::bind(':permiso_variable', "$permiso_variable");
        
        $permiso = DB::resultado(); 
        
        if(!empty($permiso) && is_array($permiso)){       
            $existe = true;
        }else{
            $existe= false;            
        }
        return $existe;      
    }
    
    
    public static function variable_existe_modificacion($permiso_variable, $permiso_id)
    {
        DB::query("
                    SELECT * FROM permisos 
                    WHERE permiso_variable = :permiso_variable 
                    AND permiso_id <> :permiso_id
                    ");
        DB::bind(':permiso_variable', "$permiso_variable");
        DB::bind(':permiso_id', $permiso_id);
        $permiso = DB::resultado(); 
        
        if(!empty($permiso) && is_array($permiso)){       
            $existe = true;
        }else{
            $existe= false;            
        }
        return $existe;      
    }
    
    
    
    public static function listar_permisos_rol($rol_id){
        DB::query("SELECT lp.permiso_id, lp.permiso_nombre, tienerol.rol_id 
                    FROM permisos AS lp
                    LEFT OUTER JOIN 
                    (SELECT * FROM roles_permisos as rp WHERE rp.rol_id = :rol_id) AS tienerol 
                    ON lp.permiso_id = tienerol.permiso_id ORDER BY lp.permiso_nombre");
        DB::bind(':rol_id', $rol_id);
        return DB::resultados();        
    }
    
    
    public static function asignar_permiso_rol($permiso_id, $rol_id){
        DB::query("
                    INSERT INTO roles_permisos
                    (rol_id, 
                    permiso_id) 
                    VALUES (:rol_id,
                    :permiso_id)
                  ");
                
        DB::bind(':rol_id', $rol_id);
        DB::bind(':permiso_id', $permiso_id);
        
        $resultado = DB::execute();     
        
        if($resultado){
            return true;
        } else {
            return false;
        }
      
    }
    
    
    public static function quitar_permiso_rol($permiso_id, $rol_id){
        DB::query("
                    DELETE FROM roles_permisos
                    WHERE rol_id = :rol_id
                    AND
                    permiso_id = :permiso_id
                  ");
                
        DB::bind(':rol_id', $rol_id);
        DB::bind(':permiso_id', $permiso_id);
        
        $resultado = DB::execute();     
        
        if($resultado){
            return true;
        } else {
            return false;
        }
      
    }
    
}
