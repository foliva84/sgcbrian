<?php
/**
 * Clase: ComunicacionF
 *
 *
 * @author ArgenCode
 */

class ComunicacionF {
    
    // Método para listar las comunicaciones
    public static function listar($factura_id){
        
        DB::query("SELECT comunicacionF_id,
                          comunicacionF,
                          comunicacionF_historial_id,
                          comunicacionF_fechaIngreso,
                          usuarios.usuario_nombre,
                          usuarios.usuario_apellido,
                          comunicacionF_usuario_id
                   FROM comunicaciones_factura
                        LEFT JOIN usuarios ON usuario_id = comunicacionF_usuario_id
                   WHERE comunicacionF_factura_id = :factura_id
                   AND comunicacionF_modificada <> 1
                   ORDER BY comunicacionF_fechaIngreso DESC");
        
        DB::bind(':factura_id', "$factura_id");
        
        $resultados = DB::resultados();
        
        return $resultados; 
    }
    
    // Método para listar las comunicaciones
    public static function listar_cantidad($factura_id){
        
        DB::query("SELECT count(comunicacionF_id) as comunicacionesF_cantidad
                   FROM comunicaciones_factura
                   WHERE comunicacionF_factura_id = :factura_id
                   AND comunicacionF_modificada <> 1
                  ");
        
        DB::bind(':factura_id', $factura_id);
        
        return DB::resultado();
    }
    
    // Método para listar las comunicaciones
    public static function listar_historial($factura_id, $comunicacionF_id){
        
        DB::query("SELECT comunicacionF_id,
                          comunicacionF,
                          comunicacionF_historial_id,
                          comunicacionF_fechaIngreso,
                          comunicacionF_fechaModificacion,
                          usuarios.usuario_nombre,
                          usuarios.usuario_apellido
                    FROM comunicaciones_factura
                         LEFT JOIN usuarios ON usuario_id = comunicacionF_usuario_id
                    WHERE comunicacionF_factura_id = :factura_id
                    AND (comunicacionF_historial_id = (select comunicacionF_historial_id from comunicaciones_factura where comunicacionF_id = :comunicacionF_id)
				OR comunicacionF_id = (select comunicacionF_historial_id from comunicaciones_factura where comunicacionF_id = :comunicacionF_id))
                    ORDER BY comunicacionF_id DESC");
        
        DB::bind(':factura_id', "$factura_id");
        DB::bind(':comunicacionF_id', "$comunicacionF_id");
        
        $resultados = DB::resultados();
        
        return $resultados; 
    }
    
    // Método para insertar una comunicacionF
    public static function insertar($comunicacionF_n, 
                                    $factura_id_n,
                                    $sesion_usuario_id){
        try {
            
            DB::conecta_t();
  
            DB::beginTransaction_t();  // start Transaction
            
            // Formateo de Fechas para el INSERT
            $fecha_ingreso = date("Y-m-d H:i:s");

            DB::query_t("INSERT INTO comunicaciones_factura (comunicacionF,
                                                             comunicacionF_factura_id,
                                                             comunicacionF_usuario_id,
                                                             comunicacionF_fechaIngreso)
                                                    VALUES (:comunicacionF,
                                                            :comunicacionF_factura_id,
                                                            :comunicacionF_usuario_id,
                                                            :comunicacionF_fechaIngreso)");
        
            DB::bind(':comunicacionF', "$comunicacionF_n");
            DB::bind(':comunicacionF_factura_id', "$factura_id_n");
            DB::bind(':comunicacionF_usuario_id', "$sesion_usuario_id");
            DB::bind(':comunicacionF_fechaIngreso', "$fecha_ingreso");

            DB::execute();

            //DB::query_t("UPDATE casos SET caso_casoEstado_id = :comunicacionF_casoEstado_id
            //           WHERE caso_id = :caso_id");

            //DB::bind(':comunicacionF_casoEstado_id', "$comunicacionF_casoEstado_id_n");
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
    
    // Método para insertar una modificacion de comunicacionF con historial
    public static function modificar($comunicacionF, 
                                     $comunicacionF_id,
                                     $comunicacionF_fechaIngreso,
                                     $factura_id,
                                     $sesion_usuario_id){        
        try {
  
            DB::conecta_t();
  
            DB::beginTransaction_t();  // start Transaction

            //Marca la comunicacionF original como comunicacionF modificada
            DB::query_t("UPDATE comunicaciones_factura SET comunicacionF_modificada = :comunicacionF_modificada
                        WHERE comunicacionF_id = :comunicacionF_id");

            DB::bind(':comunicacionF_id', "$comunicacionF_id");
            DB::bind(':comunicacionF_modificada', true);

            DB::execute();

            //Lee el id de la comunicacionF de cabecera para insertarla en la comunicacionF modificada
            DB::query_t("SELECT comunicacionF_historial_id FROM comunicaciones_factura WHERE comunicacionF_id = :comunicacionF_id");
            DB::bind(':comunicacionF_id', "$comunicacionF_id");

            $resultado = DB::resultado();
            if ($resultado["comunicacionF_historial_id"] !== null){       
                $comunicacionF_historial_id = $resultado["comunicacionF_historial_id"];
            }else{
                $comunicacionF_historial_id = $comunicacionF_id;            
            }
        
            //Insert de la comunicacionF modificada
            DB::query_t("INSERT INTO comunicaciones_factura (comunicacionF,
                                                             comunicacionF_factura_id,
                                                             comunicacionF_usuario_id,
                                                             comunicacionF_historial_id,
                                                             comunicacionF_fechaIngreso)
                                                    VALUES (:comunicacionF,
                                                            :comunicacionF_factura_id,
                                                            :comunicacionF_usuario_id,
                                                            :comunicacionF_historial_id,
                                                            :comunicacionF_fechaIngreso)");

            DB::bind(':comunicacionF', "$comunicacionF");
            DB::bind(':comunicacionF_factura_id', "$factura_id");
            DB::bind(':comunicacionF_usuario_id', "$sesion_usuario_id");
            DB::bind(':comunicacionF_historial_id', $comunicacionF_historial_id);
            DB::bind(':comunicacionF_fechaIngreso', "$comunicacionF_fechaIngreso");

            DB::execute();
            
            //DB::query_t("UPDATE casos SET caso_casoEstado_id = :comunicacionF_casoEstado_id
            //           WHERE caso_id = :caso_id");

            //DB::bind(':comunicacionF_casoEstado_id', "$comunicacionF_casoEstado_id");
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
        
    // Método para buscar por ID la comunicacionF
    public static function buscarPorId($comunicacionF_id){
        DB::query("SELECT comunicacionF_id,
                          comunicacionF,
                          comunicacionF_fechaIngreso
                   FROM comunicaciones_factura
                   WHERE comunicacionF_id = :comunicacionF_id");
        
        DB::bind(':comunicacionF_id', "$comunicacionF_id");
        
        return DB::resultado();        
    }
    
    // Método para modificar la comunicacionF con historial
    public static function actualizar($comunicacionF, 
                                      $comunicacionF_id){
        
            DB::query("UPDATE comunicaciones_factura SET
                                comunicacionF = :comunicacionF
                       WHERE comunicacionF_id = :comunicacionF_id");
          
            DB::bind(':comunicacionF', "$comunicacionF");
            DB::bind(':comunicacionF_id', "$comunicacionF_id");
            
            DB::execute();
            
    }
 
}