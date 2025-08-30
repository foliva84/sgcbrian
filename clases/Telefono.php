<?php
/**
 * Clase: Telefono
 * 
 *
 * @author ArgenCode
 */

class Telefono {
   
    // Metodo para listar Telefonos por Id de la Entidad y TIPO
    public static function listarPorEntidadId($entidad_id, $entidad_tipo) {
        
        DB::query("SELECT telefono_id,
                          tipoTelefono_nombre,
                          telefono_numero,
                          telefono_principal
                    FROM telefonos 
                        LEFT JOIN tipos_telefonos ON tipoTelefono_id = telefono_tipoTelefono_id
                    WHERE telefono_entidad_id = :telefono_entidad_id
                    AND telefono_entidad_tipo = :telefono_entidad_tipo
                    ORDER BY telefono_principal desc");
        
        DB::bind(':telefono_entidad_id', "$entidad_id");
        DB::bind(':telefono_entidad_tipo', "$entidad_tipo");
        
        return DB::resultados();
    }
       
    // Metodo para listar Telefonos por Id del TELEFONO
    public static function listarPorId($telefono_id) {
        
        DB::query("SELECT telefono_id,
                          telefono_tipoTelefono_id,
                          telefono_numero,
                          telefono_principal
                    FROM telefonos 
                    WHERE telefono_id = :telefono_id
                    ORDER BY telefono_principal desc");
        
        DB::bind(':telefono_id', "$telefono_id");
        
        return DB::resultado();   
    }
    
    // Metodo para listar los tipos de telefonos segun la entidad que llama al metodo
    public static function listarTipoTelefonos($entidad_id) {

        DB::query("SELECT tipoTelefono_id, tipoTelefono_nombre
                   FROM tipos_telefonos
                   WHERE tipoTelefono_entidad = :tipoTelefono_entidad");
        
        DB::bind(':tipoTelefono_entidad', $entidad_id);
        
        return DB::resultados();
    }
    
    // Metodo para listar los tipos de telefonos segun la entidad que llama al metodo
    public static function listarTipoTelefonos_modificacion($telefono_id_e, $entidad_id) {

        DB::query("SELECT tipoTelefono_id, tipoTelefono_nombre
                   FROM tipos_telefonos
                        LEFT JOIN telefonos ON tipoTelefono_id = telefono_tipoTelefono_id
                   WHERE telefono_id = :telefono_id
                   UNION
                   SELECT tipoTelefono_id, tipoTelefono_nombre
                   FROM tipos_telefonos
                   WHERE tipoTelefono_entidad = :tipoTelefono_entidad");
        
        DB::bind(':telefono_id', $telefono_id_e);
        DB::bind(':tipoTelefono_entidad', $entidad_id);
        
        return DB::resultados();
    }
    
    // Metodo para insertar telefonos
    public static function insertar($caso_telefonoTipo_id, 
                                    $telefono_numero,
                                    $telefono_principal,
                                    $telefono_entidad_id,
                                    $telefono_entidad_tipo) {  
        
        $error = 0;
        
        //Comprobar si se va a guardar un telefono principal
        if($telefono_principal == 1){
            DB::query("SELECT COUNT(telefono_principal) AS NumerodePrincipales FROM telefonos
                        WHERE telefono_principal = 1
                        AND telefono_entidad_id = :telefono_entidad_id
                        AND telefono_entidad_tipo = :telefono_entidad_tipo");

            // Preparar los parámetros
            DB::bind(':telefono_entidad_id', "$telefono_entidad_id");
            DB::bind(':telefono_entidad_tipo', "$telefono_entidad_tipo");
            
            $principales = DB::resultado();
            
            $cant_principales = $principales["NumerodePrincipales"];
            if($cant_principales > 0)
            {
                return $error = 13;
            }
        }
            // Comprobar si hay más de 5 Teléfonos
            DB::query("SELECT COUNT(telefono_id) AS NumerodeTelefonos FROM telefonos
                        WHERE telefono_entidad_id = :telefono_entidad_id
                        AND telefono_entidad_tipo = :telefono_entidad_tipo");
            
            // Preparar los parámetros
            DB::bind(':telefono_entidad_id', "$telefono_entidad_id");
            DB::bind(':telefono_entidad_tipo', "$telefono_entidad_tipo");
            
            $cantidad_telefono = DB::resultado();
            
            $cant_telefono = $cantidad_telefono["NumerodeTelefonos"];
            if($cant_telefono > 4)
            {
                return $error = 14;
            }
        
        // Preparar el sql a ser ejecutado
        DB::query("INSERT INTO telefonos (telefono_tipoTelefono_id,
                                        telefono_numero,
                                        telefono_principal,
                                        telefono_entidad_id,
                                        telefono_entidad_tipo)
                    VALUES (:telefono_tipoTelefono_id,
                            :telefono_numero,
                            :telefono_principal,
                            :telefono_entidad_id,
                            :telefono_entidad_tipo)");
        
        DB::bind(':telefono_tipoTelefono_id', "$caso_telefonoTipo_id");
        DB::bind(':telefono_numero', "$telefono_numero");
        DB::bind(':telefono_principal', "$telefono_principal");
        DB::bind(':telefono_entidad_id', "$telefono_entidad_id");
        DB::bind(':telefono_entidad_tipo', "$telefono_entidad_tipo");
             
        $resultado = DB::execute();
        
        If ($resultado) {
            $error = 0;
        } else {
            $error = 1;
        }
        
        return $error;
        
    }
    
    // Metodo para elimitar telefonos
    public static function eliminar($telefono_id) {
        DB::query("DELETE FROM telefonos WHERE telefono_id = :telefono_id");
        DB::bind(':telefono_id', "$telefono_id");
        $resultado = DB::execute();
        
        If ($resultado) {
            $error = 0;
        } else {
            $error = 4;
        }
        
        return $error;
    }

    // Metodo para modificar telefonos
    public static function modificar($caso_telefonoTipo_id, 
                                $telefono_numero,
                                $telefono_principal,
                                $telefono_entidad_id,  
                                $telefono_entidad_tipo,            
                                $telefono_id) {
        
        if($telefono_principal == 1){
            
            DB::query("SELECT COUNT(telefono_principal) AS NumerodePrincipales FROM telefonos
                        WHERE telefono_principal = 1
                        AND telefono_entidad_id = :telefono_entidad_id
                        AND telefono_entidad_tipo = :telefono_entidad_tipo
                        AND NOT telefono_id = :telefono_id");
            
            DB::bind(':telefono_entidad_id', "$telefono_entidad_id");
            DB::bind(':telefono_entidad_tipo', "$telefono_entidad_tipo");
            DB::bind(':telefono_id', "$telefono_id");
            
            $principales = DB::resultado();
            $cant_principales = $principales["NumerodePrincipales"];
                
            if($cant_principales >= 1)
            {
                return $error = 13;
            }
        }
        // Preparar el sql a ser ejecutado
        DB::query("UPDATE telefonos SET
                    telefono_tipoTelefono_id = :telefono_tipoTelefono_id,
                    telefono_numero = :telefono_numero,
                    telefono_principal = :telefono_principal
                  WHERE telefono_id = :telefono_id");
        
        DB::bind('telefono_tipoTelefono_id', "$caso_telefonoTipo_id");
        DB::bind('telefono_numero', "$telefono_numero");
        DB::bind('telefono_principal', "$telefono_principal");
        DB::bind('telefono_id', "$telefono_id");
    
        $resultado = DB::Execute();
            
        If ($resultado) {
            $error = 0;
        } else {
            $error = 1;
        }
        
        return $error;
    }     
}