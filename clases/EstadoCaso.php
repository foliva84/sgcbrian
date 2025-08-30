<?php
/**
 * Clase: Estado del Caso
 *
 *
 * @author ArgenCode
 */

class EstadoCaso {  
    
    // Método para el Input de Modificacion/Ver Caso - Muestra el estado elegido
    public static function mostrar($caso_id) {
        
        DB::query("SELECT casoEstado_id, casoEstado_nombre
                    FROM casos_estados 
                    INNER JOIN casos ON caso_casoEstado_id = casoEstado_id 
                    WHERE caso_id = :caso_id");

        DB::bind(':caso_id', "$caso_id");

        return DB::resultados();
    }
    
    // Método para listar en un select los estados de un caso
    public static function listar_casoEstados() {

        DB::query("SELECT casoEstado_id, casoEstado_nombre
                   FROM casos_estados 
                   ORDER BY casoEstado_orden");

        return DB::resultados();
    }
}
