<?php
/**
 * Clase: ComunicacionR
 *
 *
 * @author ArgenCode
 */

class ComunicacionR {
    
    // Método para listar las comunicacionRes
    public static function listar($reintegro_id){
        
        DB::query("SELECT comunicacionR_id,
                          comunicacionR,
                          comunicacionR_historial_id,
                          comunicacionR_fechaIngreso,
                          usuarios.usuario_nombre,
                          usuarios.usuario_apellido,
                          comunicacionR_usuario_id
                   FROM comunicaciones_reintegro
                        LEFT JOIN usuarios ON usuario_id = comunicacionR_usuario_id
                   WHERE comunicacionR_reintegro_id = :reintegro_id
                   AND comunicacionR_modificada <> 1
                   ORDER BY comunicacionR_fechaIngreso DESC");
        
        DB::bind(':reintegro_id', "$reintegro_id");
        
        $resultados = DB::resultados();
        
        return $resultados; 
    }
    
    // Método para listar las comunicacionRes
    public static function listar_cantidad($reintegro_id){
        
        DB::query("SELECT count(comunicacionR_id) as comunicacionesR_cantidad
                   FROM comunicaciones_reintegro
                   WHERE comunicacionR_reintegro_id = :reintegro_id
                   AND comunicacionR_modificada <> 1
                  ");
        
        DB::bind(':reintegro_id', $reintegro_id);
        
        return DB::resultado();
    }
    
    // Método para listar las comunicacionRes
    public static function listar_historial($reintegro_id, $comunicacionR_id){
        
        DB::query("SELECT comunicacionR_id,
                          comunicacionR,
                          comunicacionR_historial_id,
                          comunicacionR_fechaIngreso,
                          comunicacionR_fechaModificacion,
                          usuarios.usuario_nombre,
                          usuarios.usuario_apellido
                    FROM comunicaciones_reintegro
                         LEFT JOIN usuarios ON usuario_id = comunicacionR_usuario_id
                    WHERE comunicacionR_reintegro_id = :reintegro_id
                    AND (comunicacionR_historial_id = (select comunicacionR_historial_id from comunicaciones_reintegro where comunicacionR_id = :comunicacionR_id)
				OR comunicacionR_id = (select comunicacionR_historial_id from comunicaciones_reintegro where comunicacionR_id = :comunicacionR_id))
                    ORDER BY comunicacionR_id DESC");
        
        DB::bind(':reintegro_id', "$reintegro_id");
        DB::bind(':comunicacionR_id', "$comunicacionR_id");
        
        $resultados = DB::resultados();
        
        return $resultados; 
    }
    
    // Método para insertar una comunicacionR
    public static function insertar($comunicacionR_n, 
                                    $reintegro_id_n,
                                    $sesion_usuario_id){
        try {
            
            DB::conecta_t();
  
            DB::beginTransaction_t();  // start Transaction
            
            // Formateo de Fechas para el INSERT
            $fecha_ingreso = date("Y-m-d H:i:s");

            DB::query_t("INSERT INTO comunicaciones_reintegro (comunicacionR,
                                                               comunicacionR_reintegro_id,
                                                               comunicacionR_usuario_id,
                                                               comunicacionR_fechaIngreso)
                                                       VALUES (:comunicacionR,
                                                               :comunicacionR_reintegro_id,
                                                               :comunicacionR_usuario_id,
                                                               :comunicacionR_fechaIngreso)");
        
            DB::bind(':comunicacionR', "$comunicacionR_n");
            DB::bind(':comunicacionR_reintegro_id', "$reintegro_id_n");
            DB::bind(':comunicacionR_usuario_id', "$sesion_usuario_id");
            DB::bind(':comunicacionR_fechaIngreso', "$fecha_ingreso");

            DB::execute();

            //DB::query_t("UPDATE casos SET caso_casoEstado_id = :comunicacionR_casoEstado_id
            //           WHERE caso_id = :caso_id");

            //DB::bind(':comunicacionR_casoEstado_id', "$comunicacionR_casoEstado_id_n");
            //DB::bind(':caso_id', "$caso_id_n");
        
            //DB::execute();

            DB::endTransaction_t(); // commit

        }
    
        // Bloque para capturar errores en la transaccion
        catch (Exception $e) {

            // Mensaje en caso de errores en la transaccion
            echo '<p>Existió un error en la transaccion con la base de datos, intente nuevamente. Error:</p>' . $e;

            // Hace el rollback de todo 
            DB::cancelTransaction_t();  // rollBack

        }
        
    }
    
    // Método para insertar una modificacion de comunicacionR con historial
    public static function modificar($comunicacionR, 
                                     $comunicacionR_id,
                                     $comunicacionR_fechaIngreso,
                                     $reintegro_id,
                                     $sesion_usuario_id){        
        try {
  
            DB::conecta_t();
  
            DB::beginTransaction_t();  // start Transaction

            //Marca la comunicacionR original como comunicacionR modificada
            DB::query_t("UPDATE comunicaciones_reintegro SET comunicacionR_modificada = :comunicacionR_modificada
                        WHERE comunicacionR_id = :comunicacionR_id");

            DB::bind(':comunicacionR_id', "$comunicacionR_id");
            DB::bind(':comunicacionR_modificada', true);

            DB::execute();

            //Lee el id de la comunicacionR de cabecera para insertarla en la comunicacionR modificada
            DB::query_t("SELECT comunicacionR_historial_id FROM comunicaciones_reintegro WHERE comunicacionR_id = :comunicacionR_id");
            DB::bind(':comunicacionR_id', "$comunicacionR_id");

            $resultado = DB::resultado();
            if ($resultado["comunicacionR_historial_id"] !== null){       
                $comunicacionR_historial_id = $resultado["comunicacionR_historial_id"];
            }else{
                $comunicacionR_historial_id = $comunicacionR_id;            
            }
        
            //Insert de la comunicacionR modificada
            DB::query_t("INSERT INTO comunicaciones_reintegro (comunicacionR,
                                                               comunicacionR_reintegro_id,
                                                               comunicacionR_usuario_id,
                                                               comunicacionR_historial_id,
                                                               comunicacionR_fechaIngreso)
                                                       VALUES (:comunicacionR,
                                                               :comunicacionR_reintegro_id,
                                                               :comunicacionR_usuario_id,
                                                               :comunicacionR_historial_id,
                                                               :comunicacionR_fechaIngreso)");

            DB::bind(':comunicacionR', "$comunicacionR");
            DB::bind(':comunicacionR_reintegro_id', "$reintegro_id");
            DB::bind(':comunicacionR_usuario_id', "$sesion_usuario_id");
            DB::bind(':comunicacionR_historial_id', $comunicacionR_historial_id);
            DB::bind(':comunicacionR_fechaIngreso', "$comunicacionR_fechaIngreso");

            DB::execute();
            
            //DB::query_t("UPDATE casos SET caso_casoEstado_id = :comunicacionR_casoEstado_id
            //           WHERE caso_id = :caso_id");

            //DB::bind(':comunicacionR_casoEstado_id', "$comunicacionR_casoEstado_id");
            //DB::bind(':caso_id', "$caso_id");
        
            //DB::execute();
        
            DB::endTransaction_t(); // commit

        }
       
        // Bloque para capturar errores en la transaccion
        catch (Exception $e) {

            // Mensaje en caso de errores en la transaccion
            echo '<p>Existió un error en la transaccion con la base de datos, intente nuevamente. Error:</p>' . $e;

            // Hace el rollback de todo 
            DB::cancelTransaction_t();  // rollBack

        }
        
    }
        
    // Método para buscar por ID la comunicacionR
    public static function buscarPorId($comunicacionR_id){
        DB::query("SELECT comunicacionR_id,
                          comunicacionR,
                          comunicacionR_fechaIngreso
                   FROM comunicaciones_reintegro
                   WHERE comunicacionR_id = :comunicacionR_id");
        
        DB::bind(':comunicacionR_id', "$comunicacionR_id");
        
        return DB::resultado();        
    }
    
    // Método para modificar la comunicacionR con historial
    public static function actualizar($comunicacionR, 
                                      $comunicacionR_id){
        
            DB::query("UPDATE comunicaciones_reintegro SET
                                comunicacionR = :comunicacionR
                       WHERE comunicacionR_id = :comunicacionR_id");
          
            DB::bind(':comunicacionR', "$comunicacionR");
            DB::bind(':comunicacionR_id', "$comunicacionR_id");
            
            DB::execute();
            
    }
 
}