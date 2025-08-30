<?php
/**
 * Clase: TipoAsistencia
 *
 *
 * @author ArgenCode
 */

class TipoAsistencia {
    
    // Método para listar los tipos de asistencia ACTIVOS
    public static function listar(){
        DB::query("SELECT 
                        tipoAsistencia_id,
                        tipoAsistencia_clasificacion_id,
                        tipoAsistencia_nombre,
                        tipoAsistencia_activa
                   FROM tipos_asistencias
                   WHERE tipoAsistencia_activa = 1
                   ORDER BY tipoAsistencia_nombre");
        return DB::resultados();  
    }
    
    // Método para insertar un tipo de asistencia
    public static function insertar($tipoAsistencia_clasificacion_id_n, $tipoAsistencia_nombre_n){   
        DB::query("INSERT INTO tipos_asistencias (tipoAsistencia_clasificacion_id, tipoAsistencia_nombre, tipoAsistencia_activa)
                                VALUES (:tipoAsistencia_clasificacion_id,
                                        :tipoAsistencia_nombre,
                                        :tipoAsistencia_activa)");
        
        DB::bind(':tipoAsistencia_clasificacion_id', "$tipoAsistencia_clasificacion_id_n");
        DB::bind(':tipoAsistencia_nombre', "$tipoAsistencia_nombre_n");
        DB::bind(':tipoAsistencia_activa', true);
        
        DB::execute();
        
        $mensaje = "El Tipo de Asistencia fue creado con éxito";
        return $mensaje;
    }
    
    
    // Método para el borrado lógico de los tipos de asistencia
    public static function borradoLogico($tipoAsistencia_id_b){
        
        DB::query("UPDATE tipos_asistencias SET
                          tipoAsistencia_activa = :tipoAsistencia_activa
                    WHERE tipoAsistencia_id = :tipoAsistencia_id");
        
        DB::bind(':tipoAsistencia_activa', false);
        DB::bind(':tipoAsistencia_id',$tipoAsistencia_id_b);
        DB::execute();
        $mensaje = "ok";
        return $mensaje;
    }
     
     
    // Método para re activar un tipo de asistencia
    public static function reActivar($tipoAsistencia_id){  
        DB::query("UPDATE tipos_asistencias SET
                           tipoAsistencia_activa = :tipoAsistencia_activa
                    WHERE tipoAsistencia_id = :tipoAsistencia_id");
            
          
            DB::bind(':tipoAsistencia_activa', true);
            DB::bind(':tipoAsistencia_id', "$tipoAsistencia_id");
            
            DB::execute();
            $mensaje = "ok";
            return $mensaje;
    }
    
    
    // Método para modificar un tipo de asistencia
    public static function actualizar($tipoAsistencia_clasificacion_id, $tipoAsistencia_nombre, $tipoAsistencia_id){
        
        $existe = self::existeUpdate($tipoAsistencia_nombre, $tipoAsistencia_id);   
        
        If ($existe == 1){
            $mensaje = "El Tipo de Asistencia ya existe en la base";
            return $mensaje;
        } else {
                DB::query("UPDATE tipos_asistencias SET
                            tipoAsistencia_clasificacion_id = :tipoAsistencia_clasificacion_id,
                            tipoAsistencia_nombre = :tipoAsistencia_nombre
                        WHERE tipoAsistencia_id = :tipoAsistencia_id");
          
            DB::bind(':tipoAsistencia_clasificacion_id', "$tipoAsistencia_clasificacion_id");
            DB::bind(':tipoAsistencia_nombre', "$tipoAsistencia_nombre");
            DB::bind(':tipoAsistencia_id', "$tipoAsistencia_id");
            
            DB::execute();
            $mensaje = "El Tipo de Asistencia fue actualizado con éxito";
            return $mensaje;
        }
    }
    
    
    // Método para verificar si existe el tipo de asistencia antes de hacer un update
    public static function existeUpdate($tipoAsistencia_nombre, $tipoAsistencia_id)
    { 
        DB::query("SELECT * FROM tipos_asistencias 
                                WHERE tipoAsistencia_nombre = :tipoAsistencia_nombre
                                AND tipoAsistencia_id <> :tipoAsistencia_id");
        DB::bind(':tipoAsistencia_nombre', "$tipoAsistencia_nombre");
        DB::bind(':tipoAsistencia_id', "$tipoAsistencia_id");
       
        $tipoAsistencia = DB::resultado(); 
        
        if(!empty($tipoAsistencia) && is_array($tipoAsistencia)){     
            $existe = true;
        }else{
            $existe= false;            
        }
        return $existe;
    }
    
    
    // Método para buscar por ID los tipos de asistencia
    public static function buscarPorId($tipoAsistencia_id){
        DB::query("SELECT 
                    tipos_asistencias.tipoAsistencia_id,
                    tipoAsistencia_clasificacion_id,
                    tipoAsistencia_clasificacion_id,
                    tipos_asistencias.tipoAsistencia_nombre,
                    tipos_asistencias.tipoAsistencia_activa
                    FROM tipos_asistencias
                    WHERE tipoAsistencia_id = :tipoAsistencia_id");
        
        DB::bind(':tipoAsistencia_id', "$tipoAsistencia_id");
        
        return DB::resultado();        
    }
    
       
    // Método para buscar la Clasificacion del Tipo de Asistencia
    public static function buscarClasificacion($caso_tipoAsistencia_id_n) {
        DB::query("SELECT tipoAsistencia_clasificacion_id
                    FROM tipos_asistencias
                    WHERE tipoAsistencia_id = :tipoAsistencia_id");
        
        DB::bind(':tipoAsistencia_id', $caso_tipoAsistencia_id_n);
        
        return DB::resultado();
    }
    
    
    // Método para verificar si existe el tipo de asistencia antes de hacer un insert   
    public static function existe($tipoAsistencia_nombre)
    {
        DB::query("SELECT * FROM tipos_asistencias 
                  WHERE tipoAsistencia_nombre = :tipoAsistencia_nombre
                  AND tipoAsistencia_activa = :tipoAsistencia_activa");
        
        DB::bind(':tipoAsistencia_nombre', "$tipoAsistencia_nombre");
        DB::bind(':tipoAsistencia_activa', true);
        
        $tipoAsistencia = DB::resultado(); 
        
        if(!empty($tipoAsistencia) && is_array($tipoAsistencia)){       
            $existe = true;
        }else{
            $existe= false;            
        }
        return $existe;      
    }
    
    // Método para el Select - Lista los tipos de asistencia en Modificar Caso
    public static function listar_modificacion_casos($caso_id) {
        
        DB::query("SELECT tipoAsistencia_id, tipoAsistencia_nombre 
                        FROM tipos_asistencias 
                        INNER JOIN casos ON caso_tipoAsistencia_id = tipoAsistencia_id 
                        WHERE caso_id = :caso_id
                   UNION
                   SELECT tipoAsistencia_id, tipoAsistencia_nombre 
                   FROM tipos_asistencias
                   WHERE tipoAsistencia_activa = 1");

        DB::bind(':caso_id', "$caso_id");

        return DB::resultados();
    }
}