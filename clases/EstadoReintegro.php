<?php
/**
 * Clase: Estado del Reintegro
 *
 *
 * @author ArgenCode
 */

class EstadoReintegro {  
    
    // Método para listar en un select los estados de un reintegro
    public static function listar_reintegroEstados() {

        DB::query("SELECT reintegroEstado_id, reintegroEstado_nombre
                    FROM reintegros_estados
                    ORDER BY reintegroEstado_orden");

        return DB::resultados();
    }
}