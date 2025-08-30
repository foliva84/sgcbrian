<?php
/**
 * Clase: TipoTelefono
 *
 *
 * @author ArgenCode
 */

class TipoTelefono {
    
    // Metodo para listar los tipos de telefonos segun la entidad que llama al metodo
    public static function listarTipoTelefonos($entidad_id) {

        DB::query("SELECT tipoTelefono_id, tipoTelefono_nombre
                   FROM tipos_telefonos
                   WHERE tipoTelefono_entidad = :tipoTelefono_entidad");
        
        DB::bind(':tipoTelefono_entidad', $entidad_id);
        
        return DB::resultados();
    }
}