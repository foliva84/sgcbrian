<?php
/**
 * Clase: Rol
 *
 *
 * @author ArgenCode
 */

class Rol {  
    // Para listar.
    public static function listar(){
        DB::query("SELECT 
                    rol_id,
                    rol_nombre,
                    rol_orden,
                    rol_jerarquia
                    FROM roles ORDER BY rol_orden");
        return DB::resultados();
    }
    
    public static function formulario_alta_roles(){
        DB::query("SELECT 
                    rol_id,
                    rol_nombre
                    FROM roles ORDER BY rol_orden");
        return DB::resultados();
    }
   
    public static function alta($rol_nombre,$rol_orden,$rol_jerarquia){
        DB::query("
                    INSERT INTO roles (rol_nombre,
                                       rol_orden,
                                       rol_jerarquia)
                                VALUES (:rol_nombre,
                                        :rol_orden,
                                        :rol_jerarquia)       
                    ");
        
        
        DB::bind(':rol_nombre', "$rol_nombre");
        DB::bind(':rol_orden', "$rol_orden");
        DB::bind(':rol_jerarquia', "$rol_jerarquia");
        
        DB::execute();     
        
        $mensaje = "OK";
        return $mensaje;
    }
    
    
    // Funcion para completar el SELECT de roles en la modificacion del usuario
    public static function formulario_modificacion_roles($usuario_id){
        DB::query(" 
                    SELECT rol_id, rol_nombre 
                    FROM roles WHERE rol_id 
                    IN (SELECT usuario_rol_id FROM `usuarios` WHERE usuario_id = :usuario_id) 
                    UNION
                    SELECT rol_id, rol_nombre 
                    FROM roles WHERE NOT rol_id 
                    IN (SELECT usuario_rol_id FROM `usuarios` WHERE usuario_id = :usuario_id) 
                    ");
        DB::bind(':usuario_id', "$usuario_id");
    
        return DB::resultados();
        $mensaje = true;
        return $mensaje;
        
    }
    
    
    
    
    
    public static function modificar($rol_id,$rol_nombre,$rol_orden,$rol_jerarquia){
         DB::query(" 
                    UPDATE roles SET
                        rol_nombre = :rol_nombre,
                        rol_orden = :rol_orden,
                        rol_jerarquia = :rol_jerarquia
                    WHERE rol_id = :rol_id
                    ");
        DB::bind(':rol_id', "$rol_id");
        DB::bind(':rol_nombre', "$rol_nombre");
        DB::bind(':rol_orden', "$rol_orden");
        DB::bind(':rol_jerarquia', "$rol_jerarquia");
        
        DB::execute();     
        
        $mensaje = true;
        return $mensaje;
        
    }
    
    
    public static function listarMenosSeleccionado($usuario_rol_id){
        DB::query("SELECT 
                    rol_id,
                    rol_nombre
                    FROM roles 
                    WHERE NOT rol_id = :rol_id
                    ORDER BY rol_orden");
        DB::bind(":rol_id", $usuario_rol_id);
        return DB::resultados();
    }
    
    
    // Acá hay que hacer la función para traer los permisos activos de un rol.
    
    
    
    
    public static function existe($rol_nombre)
    {
        DB::query("
                  SELECT * FROM roles 
                  WHERE rol_nombre = :rol_nombre
                    ");
        DB::bind(':rol_nombre', "$rol_nombre");
      
        $rol = DB::resultado(); 
        
        if(!empty($rol) && is_array($rol)){       
            $existe = true;
        }else{
            $existe= false;            
        }
        return $existe;      
    }
    
    
    
    // Un tipo de búsqueda por id.
    public static function buscarPorid($rol_id){        
        DB::query("SELECT * FROM roles 
                                WHERE rol_id = :rol_id
                ");
        DB::bind(":rol_id", $rol_id);
        return DB::resultado(); 
    }
    
    
    
    
}
