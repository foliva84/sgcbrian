<?php
/**
 * Clase: Comunicacion
 *
 *
 * @author ArgenCode
 */

class Comunicacion {
    
    // Método para listar las comunicaciones
    public static function listar($caso_id, $asunto_tipo_id){
        
        DB::query("SELECT
                        comunicacion_id,
                        comunicacion_casoEstado_id,
                        comunicacion_asunto_id,
                        comunicacionAsunto_nombre,
                        comunicacionAsunto_color,
                        comunicacion,
                        comunicacion_historial_id,
                        casoEstado_nombre,
                        comunicacion_fechaIngreso,
                        usuarios.usuario_nombre,
                        usuarios.usuario_apellido,
                        comunicacion_usuario_id
                   FROM comunicaciones
                        LEFT JOIN casos_estados ON casoEstado_id = comunicacion_casoEstado_id
                        LEFT JOIN comunicaciones_asuntos ON comunicacionAsunto_id = comunicacion_asunto_id
                        LEFT JOIN usuarios ON usuario_id = comunicacion_usuario_id
                   WHERE comunicacion_caso_id = :caso_id
                   AND comunicacion_modificada <> 1
                   AND (CASE WHEN :asunto_tipo_id = 1 THEN comunicacion_asunto_id IN (select comunicacionAsunto_id from comunicaciones_asuntos where comunicacionAsunto_tipo_id = 1)
                             WHEN :asunto_tipo_id = 2 THEN comunicacion_asunto_id IN (select comunicacionAsunto_id from comunicaciones_asuntos where comunicacionAsunto_tipo_id = 2)
                             WHEN :asunto_tipo_id = 3 THEN comunicacion_asunto_id IN (select comunicacionAsunto_id from comunicaciones_asuntos where comunicacionAsunto_tipo_id = 3)
                             ELSE comunicacion_id = comunicacion_id
                        END)
                   ORDER BY comunicacion_fechaIngreso DESC");
        
        DB::bind(':caso_id', "$caso_id");
        DB::bind(':asunto_tipo_id', $asunto_tipo_id);
        
        $resultados = DB::resultados();
        
        return $resultados; 
    }
    
    // Método para listar las comunicaciones
    public static function listar_cantidad($caso_id, $asunto_tipo_id){
        
        DB::query("SELECT count(comunicacion_id) as comunicaciones_cantidad
                   FROM comunicaciones
                   WHERE comunicacion_caso_id = :caso_id
                   AND comunicacion_modificada <> 1
                   AND (CASE WHEN :asunto_tipo_id = 1 THEN comunicacion_asunto_id IN (select comunicacionAsunto_id from comunicaciones_asuntos where comunicacionAsunto_tipo_id = 1)
                             WHEN :asunto_tipo_id = 2 THEN comunicacion_asunto_id IN (select comunicacionAsunto_id from comunicaciones_asuntos where comunicacionAsunto_tipo_id = 2)
                             WHEN :asunto_tipo_id = 3 THEN comunicacion_asunto_id IN (select comunicacionAsunto_id from comunicaciones_asuntos where comunicacionAsunto_tipo_id = 3)
                             ELSE comunicacion_id = comunicacion_id
                        END)
                  ");
        
        DB::bind(':caso_id', $caso_id);
        DB::bind(':asunto_tipo_id', $asunto_tipo_id);
        
        return DB::resultado();
    }
    
    // Método para listar las comunicaciones
    public static function listar_historial($caso_id, $comunicacion_id){
        
        DB::query("SELECT comunicacion_id,
                          comunicacion_casoEstado_id,
                          comunicacion_asunto_id,
                          comunicacionAsunto_nombre,
                          comunicacionAsunto_color,
                          comunicacion,
                          comunicacion_historial_id,
                          casoEstado_nombre,
                          comunicacion_fechaIngreso,
                          comunicacion_fechaModificacion,
                          usuarios.usuario_nombre,
                          usuarios.usuario_apellido
                    FROM comunicaciones
                         LEFT JOIN casos_estados ON casoEstado_id = comunicacion_casoEstado_id
                         LEFT JOIN comunicaciones_asuntos ON comunicacionAsunto_id = comunicacion_asunto_id
                         LEFT JOIN usuarios ON usuario_id = comunicacion_usuario_id
                    WHERE comunicacion_caso_id = :caso_id
                    AND (comunicacion_historial_id = (select comunicacion_historial_id from comunicaciones where comunicacion_id = :comunicacion_id)
				OR comunicacion_id = (select comunicacion_historial_id from comunicaciones where comunicacion_id = :comunicacion_id))
                    ORDER BY comunicacion_id DESC");
        
        DB::bind(':caso_id', "$caso_id");
        DB::bind(':comunicacion_id', "$comunicacion_id");
        
        $resultados = DB::resultados();
        
        return $resultados; 
    }
    
    // Método para insertar una comunicacion
    public static function insertar($comunicacion_asunto_id_n,
                                    $comunicacion_casoEstado_id_n, 
                                    $comunicacion_n, 
                                    $caso_id_n,
                                    $sesion_usuario_id,
                                    $medical_cost){
        try {
            
            DB::conecta_t();
  
            DB::beginTransaction_t();  // start Transaction
            
            // Formateo de Fechas para el INSERT
            $fecha_ingreso = date("Y-m-d H:i:s");

            DB::query_t("INSERT INTO comunicaciones (comunicacion_asunto_id, 
                                                    comunicacion_casoEstado_id, 
                                                    comunicacion,
                                                    comunicacion_caso_id,
                                                    comunicacion_usuario_id,
                                                    comunicacion_fechaIngreso)
                                            VALUES (:comunicacion_asunto_id,
                                                    :comunicacion_casoEstado_id,
                                                    :comunicacion,
                                                    :comunicacion_caso_id,
                                                    :comunicacion_usuario_id,
                                                    :comunicacion_fechaIngreso)");
        
            DB::bind(':comunicacion_asunto_id', "$comunicacion_asunto_id_n");
            DB::bind(':comunicacion_casoEstado_id', "$comunicacion_casoEstado_id_n");
            DB::bind(':comunicacion', "$comunicacion_n");
            DB::bind(':comunicacion_caso_id', "$caso_id_n");
            DB::bind(':comunicacion_usuario_id', "$sesion_usuario_id");
            DB::bind(':comunicacion_fechaIngreso', "$fecha_ingreso");

            DB::execute();

            $update = ($medical_cost=='1') ? ", no_medical_cost = 1 " : "" ;
                
            DB::query_t("UPDATE casos SET caso_casoEstado_id = :comunicacion_casoEstado_id" . $update . "
                       WHERE caso_id = :caso_id");

            DB::bind(':comunicacion_casoEstado_id', "$comunicacion_casoEstado_id_n");
            DB::bind(':caso_id', "$caso_id_n");
        
            DB::execute();

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
    
    // Método para insertar una modificacion de comunicacion con historial
    public static function modificar($comunicacion_asunto_id, 
                                     $comunicacion_casoEstado_id, 
                                     $comunicacion, 
                                     $comunicacion_id,
                                     $comunicacion_fechaIngreso,
                                     $caso_id,
                                     $sesion_usuario_id){        
        try {
  
            DB::conecta_t();
  
            DB::beginTransaction_t();  // start Transaction

            //Marca la comunicacion original como comunicacion modificada
            DB::query_t("UPDATE comunicaciones SET comunicacion_modificada = :comunicacion_modificada
                        WHERE comunicacion_id = :comunicacion_id");

            DB::bind(':comunicacion_id', "$comunicacion_id");
            DB::bind(':comunicacion_modificada', true);

            DB::execute();

            //Lee el id de la comunicacion de cabecera para insertarla en la comunicacion modificada
            DB::query_t("SELECT comunicacion_historial_id FROM comunicaciones WHERE comunicacion_id = :comunicacion_id");
            DB::bind(':comunicacion_id', "$comunicacion_id");

            $resultado = DB::resultado();
            if ($resultado["comunicacion_historial_id"] !== null){       
                $comunicacion_historial_id = $resultado["comunicacion_historial_id"];
            }else{
                $comunicacion_historial_id = $comunicacion_id;            
            }
        
            //Insert de la comunicacion modificada
            DB::query_t("INSERT INTO comunicaciones  (comunicacion_asunto_id, 
                                                    comunicacion_casoEstado_id, 
                                                    comunicacion,
                                                    comunicacion_caso_id,
                                                    comunicacion_usuario_id,
                                                    comunicacion_historial_id,
                                                    comunicacion_fechaIngreso)
                                            VALUES (:comunicacion_asunto_id,
                                                    :comunicacion_casoEstado_id,
                                                    :comunicacion,
                                                    :comunicacion_caso_id,
                                                    :comunicacion_usuario_id,
                                                    :comunicacion_historial_id,
                                                    :comunicacion_fechaIngreso)");

            DB::bind(':comunicacion_asunto_id', "$comunicacion_asunto_id");
            DB::bind(':comunicacion_casoEstado_id', "$comunicacion_casoEstado_id");
            DB::bind(':comunicacion', "$comunicacion");
            DB::bind(':comunicacion_caso_id', "$caso_id");
            DB::bind(':comunicacion_usuario_id', "$sesion_usuario_id");
            DB::bind(':comunicacion_historial_id', $comunicacion_historial_id);
            DB::bind(':comunicacion_fechaIngreso', "$comunicacion_fechaIngreso");

            DB::execute();
            
            DB::query_t("UPDATE casos SET caso_casoEstado_id = :comunicacion_casoEstado_id
                       WHERE caso_id = :caso_id");

            DB::bind(':comunicacion_casoEstado_id', "$comunicacion_casoEstado_id");
            DB::bind(':caso_id', "$caso_id");
        
            DB::execute();
        
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
        
    // Método para buscar por ID la comunicacion
    public static function buscarPorId($comunicacion_id){
        DB::query("SELECT 
                        comunicacion_id,
                        comunicacion_casoEstado_id,
                        comunicacion_asunto_id,
                        comunicacion,
                        comunicacion_fechaIngreso,
                        casoEstado_nombre
                   FROM comunicaciones
                   INNER JOIN casos_estados ON casoEstado_id = comunicacion_casoEstado_id
                   WHERE comunicacion_id = :comunicacion_id");
        
        DB::bind(':comunicacion_id', "$comunicacion_id");
        
        return DB::resultado();        
    }
    
    // Método para modificar la comunicacion con historial
    public static function actualizar($comunicacion_asunto_id, 
                                      $comunicacion_casoEstado_id, 
                                      $comunicacion, 
                                      $comunicacion_id){
        
            DB::query("UPDATE comunicaciones SET
                            comunicacion_asunto_id = :comunicacion_asunto_id,
                            comunicacion_casoEstado_id = :comunicacion_casoEstado_id,
                            comunicacion = :comunicacion
                        WHERE comunicacion_id = :comunicacion_id");
          
            DB::bind(':comunicacion_asunto_id', "$comunicacion_asunto_id");
            DB::bind(':comunicacion_casoEstado_id', "$comunicacion_casoEstado_id");
            DB::bind(':comunicacion', "$comunicacion");
            DB::bind(':comunicacion_id', "$comunicacion_id");
            
            DB::execute();
            
    }
       
    // Método para el Select - Lista los asuntos en insertar comunicacion
    public static function listarAsunto_altaComunicacion() {
        
        DB::query("SELECT comunicacionAsunto_id, 
                          comunicacionAsunto_nombre 
                   FROM comunicaciones_asuntos WHERE comunicacionAsunto_activo = 1");

        return DB::resultados();
    }
    
    // Método para el Select - Lista los estados de caso en insertar comunicacion
    public static function listarCasoEstado_altaComunicacion($caso_id, $permisos, $validacion) {
        
        // Validacion de permisos - Verifica si puede o no cerrar casos
        $comunicaciones_cerrar_casos = array_search('comunicaciones_cerrar_casos', array_column($permisos, 'permiso_variable'));
        if (!empty($comunicaciones_cerrar_casos) || ($comunicaciones_cerrar_casos === 0)) {
            
            // Valida si el caso es del tipo asistencia = MEDICO y SIN diagnostico
            // En caso de validarse esto como verdadero, el select de estados no muestra el estado 'Cerrado'
            if ($validacion == 'TRUE') {
                $WHERE = "(casoEstado_id = 6 OR casoEstado_id = 7)";
            } else {
                $WHERE = "casoEstado_id = 7";
            }
            
            DB::query("SELECT casoEstado_id, casoEstado_nombre
                        FROM casos_estados
                        INNER JOIN comunicaciones ON comunicacion_casoEstado_id = casoEstado_id
                        WHERE comunicacion_caso_id = :caso_id
                        AND comunicacion_id = (SELECT max(comunicacion_id) FROM comunicaciones WHERE comunicacion_caso_id = :caso_id)
                        UNION 
                        SELECT casoEstado_id, casoEstado_nombre 
                        FROM casos_estados
                        WHERE NOT " . $WHERE . "");
        } else {
            DB::query("SELECT casoEstado_id, casoEstado_nombre
                        FROM casos_estados
                        INNER JOIN comunicaciones ON comunicacion_casoEstado_id = casoEstado_id
                        WHERE comunicacion_caso_id = :caso_id
                        AND comunicacion_id = (SELECT max(comunicacion_id) FROM comunicaciones WHERE comunicacion_caso_id = :caso_id)
                        UNION 
                        SELECT casoEstado_id, casoEstado_nombre 
                        FROM casos_estados
                        WHERE NOT (casoEstado_id = 6 OR casoEstado_id = 7)");
        }  
        
        DB::bind(':caso_id', "$caso_id");
        return DB::resultados();
    }
    
    // Método para el Select - Lista los asuntos en modificar comunicacion
    public static function listarAsunto_modificarComunicacion($comunicacion_id) {
        
        DB::query("SELECT comunicacionAsunto_id, 
                          comunicacionAsunto_nombre
                   FROM comunicaciones_asuntos
                   INNER JOIN comunicaciones ON comunicacion_asunto_id = comunicacionAsunto_id
                   WHERE comunicacion_id = :comunicacion_id
                   UNION
                   SELECT comunicacionAsunto_id, 
                          comunicacionAsunto_nombre
                   FROM comunicaciones_asuntos");

        DB::bind(':comunicacion_id', "$comunicacion_id");

        return DB::resultados();
    }
    
    // Método para el Select - Lista los estados de casos en modificar comunicacion
    public static function listarCasoEstado_modificarComunicacion($comunicacion_id) {
        
        DB::query("SELECT casoEstado_id, 
                          casoEstado_nombre 
                   FROM casos_estados
                   INNER JOIN comunicaciones ON comunicacion_casoEstado_id = casoEstado_id
                   WHERE comunicacion_id = :comunicacion_id
                   UNION
                   SELECT casoEstado_id, 
                          casoEstado_nombre 
                   FROM casos_estados
                   WHERE NOT casoEstado_id = 7");

        DB::bind(':comunicacion_id', "$comunicacion_id");

        return DB::resultados();
    }
    
    // Método para el Select - Lista los tipos de asunto para filtrar grilla de comunicaciones
    public static function listarTiposAsunto_grillaComunicaciones() {
        
        DB::query("SELECT asunto_tipo_id, 
                          asunto_tipo_nombre 
                   FROM comunicaciones_asunto_tipos");

        return DB::resultados();
    }

    public static function listarComunicacionesPorAsunto($caso_id, $asunto_id){
        DB::query("SELECT comunicacion_caso_id
        FROM comunicaciones
        WHERE comunicacion_caso_id = :caso_id 
        AND comunicacion_asunto_id = :asunto_id");

        DB::bind(':caso_id', "$caso_id");
        DB::bind(':asunto_id', "$asunto_id");

        return DB::resultados();
    }
    
}