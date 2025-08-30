 <?php
/**
 * Clase: Agenda
 *
 *
 * @author ArgenCode
 */

class Agenda {
    
    /*
     *
     * AGENDA DE CASOS
     *  
    */

    // Método para asignar un caso a un usuario especifico
    public static function asignar_caso($agenda_caso_id,
                                        $agenda_usuarioAsignado_a,
                                        $sesion_usuario_id) {
        
        // Formateo de Fechas para el INSERT
        $agenda_fechaAsignacion_a = date("Y-m-d H:i:s"); 
        
        
        DB::query("INSERT INTO casos_agenda
                    (casoAgenda_caso_id,
                    casoAgenda_usuarioAsigna_id,
                    casoAgenda_usuarioAsignado_id,
                    casoAgenda_fechaAsignacion)
                VALUES 
                    (:casoAgenda_caso_id,
                    :casoAgenda_usuarioAsigna_id,
                    :casoAgenda_usuarioAsignado_id,
                    :casoAgenda_fechaAsignacion)");
        
        DB::bind(':casoAgenda_caso_id', "$agenda_caso_id");
        DB::bind(':casoAgenda_usuarioAsigna_id', "$sesion_usuario_id");
        DB::bind(':casoAgenda_usuarioAsignado_id', "$agenda_usuarioAsignado_a");
        DB::bind(':casoAgenda_fechaAsignacion', "$agenda_fechaAsignacion_a");
        
        DB::execute();     
        
        $mensaje = "OK";
        return $mensaje;
    }
    
    
    // Método para re-asignar un caso a un usuario especifico
    public static function reasignar_caso($agenda_id_r,
                                          $agenda_usuarioAsignado_r,
                                          $sesion_usuario_id) {
        
        // Formateo de Fechas para el INSERT
        $agenda_fechaAsignacion_r = date("Y-m-d H:i:s"); 
        
        DB::query("UPDATE casos_agenda SET
                    casoAgenda_usuarioAsigna_id = :casoAgenda_usuarioAsigna_id,
                    casoAgenda_usuarioAsignado_id = :casoAgenda_usuarioAsignado_id,
                    casoAgenda_fechaAsignacion = :casoAgenda_fechaAsignacion
                WHERE casoAgenda_id = :casoAgenda_id");
        
        DB::bind(':casoAgenda_usuarioAsigna_id', $sesion_usuario_id);
        DB::bind(':casoAgenda_usuarioAsignado_id', $agenda_usuarioAsignado_r);
        DB::bind(':casoAgenda_fechaAsignacion', "$agenda_fechaAsignacion_r");
        DB::bind(':casoAgenda_id', $agenda_id_r);
        

        DB::execute();
        $mensaje = "OK";
        return $mensaje;
    }
    
    
    // Método para buscar por ID un registro en Agenda
    public static function buscarPorId($casoAgenda_id){
        DB::query("SELECT casoAgenda_id,
                          casoAgenda_caso_id,
                          casoAgenda_usuarioAsignado_id,
                          casos.caso_numero
                    FROM casos_agenda
                        LEFT JOIN casos ON caso_id = casoAgenda_caso_id
                    WHERE casoAgenda_id = :casoAgenda_id");
        
        DB::bind(':casoAgenda_id', "$casoAgenda_id");
        
        $resultado = DB::resultado();
        
        return $resultado;       
    }


    /*
     *
     * AGENDA DE REINTEGROS
     *  
    */
    // Método para asignar un caso a un usuario especifico
    public static function asignar_reintegro($agenda_reintegro_id,
                                             $agenda_usuarioAsignado_a,
                                             $sesion_usuario_id) {
        
        // Formateo de Fechas para el INSERT
        $agenda_fechaAsignacion_a = date("Y-m-d H:i:s"); 
        
        
        DB::query("INSERT INTO reintegros_agenda
                               (reintegroAgenda_reintegro_id,
                                reintegroAgenda_usuarioAsigna_id,
                                reintegroAgenda_usuarioAsignado_id,
                                reintegroAgenda_fechaAsignacion)
                        VALUES 
                                (:reintegroAgenda_reintegro_id,
                                :reintegroAgenda_usuarioAsigna_id,
                                :reintegroAgenda_usuarioAsignado_id,
                                :reintegroAgenda_fechaAsignacion)");
        
        DB::bind(':reintegroAgenda_reintegro_id', "$agenda_reintegro_id");
        DB::bind(':reintegroAgenda_usuarioAsigna_id', "$sesion_usuario_id");
        DB::bind(':reintegroAgenda_usuarioAsignado_id', "$agenda_usuarioAsignado_a");
        DB::bind(':reintegroAgenda_fechaAsignacion', "$agenda_fechaAsignacion_a");
        
        DB::execute();     
        
        $mensaje = "OK";
        return $mensaje;
    }
    
    
    // Método para re-asignar un caso a un usuario especifico
    public static function reasignar_reintegro($agenda_id_r,
                                               $agenda_usuarioAsignado_r,
                                               $sesion_usuario_id) {
        
        // Formateo de Fechas para el INSERT
        $agenda_fechaAsignacion_r = date("Y-m-d H:i:s"); 
        
        DB::query("UPDATE reintegros_agenda SET
                    reintegroAgenda_usuarioAsigna_id = :reintegroAgenda_usuarioAsigna_id,
                    reintegroAgenda_usuarioAsignado_id = :reintegroAgenda_usuarioAsignado_id,
                    reintegroAgenda_fechaAsignacion = :reintegroAgenda_fechaAsignacion
                WHERE reintegroAgenda_id = :reintegroAgenda_id");
        
        DB::bind(':reintegroAgenda_usuarioAsigna_id', $sesion_usuario_id);
        DB::bind(':reintegroAgenda_usuarioAsignado_id', $agenda_usuarioAsignado_r);
        DB::bind(':reintegroAgenda_fechaAsignacion', "$agenda_fechaAsignacion_r");
        DB::bind(':reintegroAgenda_id', $agenda_id_r);
        

        DB::execute();
        $mensaje = "OK";
        return $mensaje;
    }
    
    
    // Método para buscar por ID un registro en Agenda
    public static function buscarRPorId($reintegroAgenda_id){
        DB::query("SELECT reintegroAgenda_id,
                          reintegroAgenda_reintegro_id,
                          reintegroAgenda_usuarioAsignado_id,
                          casos.caso_numero
                    FROM reintegros_agenda
                        LEFT JOIN reintegros ON reintegro_id = reintegroAgenda_reintegro_id
                        LEFT JOIN casos ON caso_id = reintegro_caso_id
                    WHERE reintegroAgenda_id = :reintegroAgenda_id");
        
        DB::bind(':reintegroAgenda_id', $reintegroAgenda_id);
        
        $resultado = DB::resultado();
        
        return $resultado;       
    }
    
}

