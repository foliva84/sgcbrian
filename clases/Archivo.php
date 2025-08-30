<?php
/**
 * Clase: Archivo
 *
 *
 * @author ArgenCode
 */

class Archivo {
    
    
    
    // Método para guardar un archivo
    public static function guardar( 
                                    $archivo_nombre,
                                    $archivo_extension,
                                    $archivo_encriptado,
                                    $caso_id
                                  )        
    {   
        $archivo_fecha = date("Y-m-d H:i:s");
        
        $usuario_id = $_SESSION['usuario_id'];
        
        
        DB::query("
                    INSERT INTO archivos (archivo_nombre,
                                          archivo_extension,
                                          archivo_encriptado,
                                          caso_id,
                                          usuario_id,
                                          archivo_fecha)
                                VALUES (:archivo_nombre,
                                        :archivo_extension,
                                        :archivo_encriptado,
                                        :caso_id,
                                        :usuario_id,
                                        :archivo_fecha)       
                    ");
        
        DB::bind(':archivo_nombre', "$archivo_nombre");
        DB::bind(':archivo_extension', "$archivo_extension");
        DB::bind(':archivo_encriptado', "$archivo_encriptado");
        DB::bind(':caso_id', "$caso_id");
        DB::bind(':usuario_id', "$usuario_id");
        DB::bind(':archivo_fecha', "$archivo_fecha");
               
        DB::execute();     
        
    }
    
    
    
    
    
    
    // Método para guardar un archivo
    public static function guardar_facturas( 
                                    $archivo_nombre,
                                    $archivo_extension,
                                    $archivo_encriptado,
                                    $factura_id
                                  )        
    {   
        $archivo_fecha = date("Y-m-d H:i:s");
        
        $usuario_id = $_SESSION['usuario_id'];
        
        
        DB::query("
                    INSERT INTO archivos_facturas (archivo_nombre,
                                          archivo_extension,
                                          archivo_encriptado,
                                          factura_id,
                                          usuario_id,
                                          archivo_fecha)
                                VALUES (:archivo_nombre,
                                        :archivo_extension,
                                        :archivo_encriptado,
                                        :factura_id,
                                        :usuario_id,
                                        :archivo_fecha)       
                    ");
        
        DB::bind(':archivo_nombre', "$archivo_nombre");
        DB::bind(':archivo_extension', "$archivo_extension");
        DB::bind(':archivo_encriptado', "$archivo_encriptado");
        DB::bind(':factura_id', "$factura_id");
        DB::bind(':usuario_id', "$usuario_id");
        DB::bind(':archivo_fecha', "$archivo_fecha");
               
        DB::execute();     
        
    }
    
    
       // Método para guardar un archivo
    public static function guardar_reintegros( 
                                    $archivo_nombre,
                                    $archivo_extension,
                                    $archivo_encriptado,
                                    $reintegro_id
                                  )        
    {   
        $archivo_fecha = date("Y-m-d H:i:s");
        
        $usuario_id = $_SESSION['usuario_id'];
        
        
        DB::query("
                    INSERT INTO archivos_reintegros (archivo_nombre,
                                          archivo_extension,
                                          archivo_encriptado,
                                          reintegro_id,
                                          usuario_id,
                                          archivo_fecha)
                                VALUES (:archivo_nombre,
                                        :archivo_extension,
                                        :archivo_encriptado,
                                        :reintegro_id,
                                        :usuario_id,
                                        :archivo_fecha)       
                    ");
        
        DB::bind(':archivo_nombre', "$archivo_nombre");
        DB::bind(':archivo_extension', "$archivo_extension");
        DB::bind(':archivo_encriptado', "$archivo_encriptado");
        DB::bind(':reintegro_id', "$reintegro_id");
        DB::bind(':usuario_id', "$usuario_id");
        DB::bind(':archivo_fecha', "$archivo_fecha");
               
        DB::execute();     
        
    }
    
    
    // Método para guardar un archivo
    public static function guardar_prestador( 
                                    $archivo_nombre,
                                    $archivo_extension,
                                    $archivo_encriptado,
                                    $prestador_id
                                  )        
    {   
        $archivo_fecha = date("Y-m-d H:i:s");
        
        $usuario_id = $_SESSION['usuario_id'];
        
        
        DB::query("
                    INSERT INTO archivos_prestador (archivo_nombre,
                                          archivo_extension,
                                          archivo_encriptado,
                                          prestador_id,
                                          usuario_id,
                                          archivo_fecha)
                                VALUES (:archivo_nombre,
                                        :archivo_extension,
                                        :archivo_encriptado,
                                        :prestador_id,
                                        :usuario_id,
                                        :archivo_fecha)       
                    ");
        
        DB::bind(':archivo_nombre', "$archivo_nombre");
        DB::bind(':archivo_extension', "$archivo_extension");
        DB::bind(':archivo_encriptado', "$archivo_encriptado");
        DB::bind(':prestador_id', "$prestador_id");
        DB::bind(':usuario_id', "$usuario_id");
        DB::bind(':archivo_fecha', "$archivo_fecha");
               
        DB::execute();     
        
    }
    
    
    
    // Listar archivos sin filto
    public static function listar($caso_id_archivo){
        DB::query("SELECT archivo_id,
                          archivo_nombre,
                          archivo_extension,
                          archivo_encriptado,
                          DATE_FORMAT(archivo_fecha, '%d-%m-%Y') as archivo_fecha
                   FROM archivos
                   WHERE caso_id = :caso_id");
        
        DB::bind(':caso_id', "$caso_id_archivo");
        
        $resultados = DB::resultados();
        
        /*foreach($resultados as $resultado) {
            if ($resultado['archivo_fecha'] !== NULL) {         
                $archivo_fecha = date("d-m-Y", strtotime($resultado['archivo_fecha']));
                $resultado['archivo_fecha'] = $archivo_fecha;
            } else {
                $resultado['archivo_fecha'] = NULL;
            }
        }*/
        
        return $resultados;
    }
    
   
    // Listar archivos sin filto
    public static function listar_facturas($factura_id_archivo){
        DB::query("SELECT archivo_id,
                          archivo_nombre,
                          archivo_extension,
                          archivo_encriptado,
                          DATE_FORMAT(archivo_fecha, '%d-%m-%Y') as archivo_fecha
                   FROM archivos_facturas
                   WHERE factura_id = :factura_id");
        
        DB::bind(':factura_id', "$factura_id_archivo");
        
        $resultados = DB::resultados();
        
        /*foreach($resultados as $resultado) {
            if ($resultado['archivo_fecha'] !== NULL) {         
                $archivo_fecha = date("d-m-Y", strtotime($resultado['archivo_fecha']));
                $resultado['archivo_fecha'] = $archivo_fecha;
            } else {
                $resultado['archivo_fecha'] = NULL;
            }
        }*/
        
        return $resultados;
    }
    
    
    // Listar archivos sin filto
    public static function listar_reintegros($reintegro_id_archivo){
        DB::query("SELECT archivo_id,
                          archivo_nombre,
                          archivo_extension,
                          archivo_encriptado,
                          DATE_FORMAT(archivo_fecha, '%d-%m-%Y') as archivo_fecha
                   FROM archivos_reintegros
                   WHERE reintegro_id = :reintegro_id");
        
        DB::bind(':reintegro_id', "$reintegro_id_archivo");
        
        $resultados = DB::resultados();
        
        /*foreach($resultados as $resultado) {
            if ($resultado['archivo_fecha'] !== NULL) {         
                $archivo_fecha = date("d-m-Y", strtotime($resultado['archivo_fecha']));
                $resultado['archivo_fecha'] = $archivo_fecha;
            } else {
                $resultado['archivo_fecha'] = NULL;
            }
        }*/
        
        return $resultados;
    }
    
    // Listar archivos sin filto
    public static function listar_prestador($prestador_id_archivo){
        DB::query("SELECT archivo_id,
                          archivo_nombre,
                          archivo_extension,
                          archivo_encriptado,
                          DATE_FORMAT(archivo_fecha, '%d-%m-%Y') as archivo_fecha
                   FROM archivos_prestador
                   WHERE prestador_id = :prestador_id");
        
        DB::bind(':prestador_id', "$prestador_id_archivo");
        
        $resultados = DB::resultados();
        
        /*foreach($resultados as $resultado) {
            if ($resultado['archivo_fecha'] !== NULL) {         
                $archivo_fecha = date("d-m-Y", strtotime($resultado['archivo_fecha']));
                $resultado['archivo_fecha'] = $archivo_fecha;
            } else {
                $resultado['archivo_fecha'] = NULL;
            }
        }*/
        
        return $resultados;
    }
    
    
    
    // Eliminar archivo
    public static function eliminar($archivo_id){
        
        DB::query("DELETE FROM archivos WHERE archivo_id = :archivo_id");
        
        DB::bind(':archivo_id', $archivo_id);
        
        DB::execute(); 
        
    }    
    
    // Eliminar archivo
    public static function eliminar_facturas($archivo_id){
        
        DB::query("DELETE FROM archivos_facturas WHERE archivo_id = :archivo_id");
        
        DB::bind(':archivo_id', $archivo_id);
        
        DB::execute(); 
        
    }
    
    // Eliminar archivo
    public static function eliminar_reintegros($archivo_id){
        
        DB::query("DELETE FROM archivos_reintegros WHERE archivo_id = :archivo_id");
        
        DB::bind(':archivo_id', $archivo_id);
        
        DB::execute(); 
        
    }
    
    // Eliminar archivo
    public static function eliminar_prestador($archivo_id){
        
        DB::query("DELETE FROM archivos_prestador WHERE archivo_id = :archivo_id");
        
        DB::bind(':archivo_id', $archivo_id);
        
        DB::execute(); 
        
    }
    
    // Obtener un nombre de archivo por su id
    
     public static function obtener_nombre($archivo_id){
        
        DB::query("SELECT archivo_encriptado, archivo_extension FROM archivos WHERE archivo_id = :archivo_id");
                
        DB::bind(':archivo_id', $archivo_id);
        
        $resultado = DB::resultado();
        
        return $resultado;
        
    } 
    
    
    // Obtener un nombre de archivo por su id
    
     public static function obtener_nombre_facturas($archivo_id){
        
        DB::query("SELECT archivo_encriptado, archivo_extension FROM archivos_facturas WHERE archivo_id = :archivo_id");
                
        DB::bind(':archivo_id', $archivo_id);
        
        $resultado = DB::resultado();
        
        return $resultado;
        
    } 
    
    // Obtener un nombre de archivo por su id
    
     public static function obtener_nombre_reintegros($archivo_id){
        
        DB::query("SELECT archivo_encriptado, archivo_extension FROM archivos_reintegros WHERE archivo_id = :archivo_id");
                
        DB::bind(':archivo_id', $archivo_id);
        
        $resultado = DB::resultado();
        
        return $resultado;
        
    } 
    
}

    
