<?php
/**
 * Clase: Pais
 *
 *
 * @author ArgenCode
 */

class Pais {
    
    // Método para el borrado logico de una practica
    public static function borradoLogico($pais_id){
        DB::query("UPDATE paises SET
                        pais_activo = :pais_activo
                    WHERE pais_id = :pais_id");
        
        DB::bind(':pais_activo', false);
        DB::bind(':pais_id',$pais_id);
        DB::execute();
        
        $mensaje = "El Pais fue dado de baja con éxito.";
        return $mensaje;
    }
    
    // Método para el Select - Lista los Paises ---- POR FAVOR CAMBIAR EL NOMBRE DE ESTE METODO COMO EL DE ABAJO Y UNIFICAR
    public static function formulario_alta_paises() {

        DB::query("SELECT
                        pais_id, 
                        pais_nombreEspanol
                    FROM paises WHERE pais_activo = 1 ORDER BY pais_nombreEspanol");

        return DB::resultados();
    }
    
    
    // Lista Paises
    public static function listar() {

        DB::query("SELECT
                        pais_id, 
                        pais_nombreEspanol
                    FROM paises WHERE pais_activo = 1 ORDER BY pais_nombreEspanol");

        return DB::resultados();
    }
    
    
    // Lista Paises
    public static function listar_filtrado($pais_nombreEspanol) {

        DB::query("SELECT
                        pais_id, 
                        pais_nombreEspanol
                    FROM paises 
                    WHERE pais_activo = 1 
                    AND pais_nombreEspanol LIKE :pais_nombreEspanol
                    ORDER BY pais_nombreEspanol");
        
         DB::bind(':pais_nombreEspanol', "%$pais_nombreEspanol%");
        
        return DB::resultados();
    }
    
    

// ********************************
// Métodos de Formularios Modificar
// ********************************  
  
    // Método para el Select - Lista los paises en Modificar Prestador
    public static function formulario_modificacion_prestador_paises($prestador_id) {
        
        DB::query("
                   SELECT pais_id, pais_nombreEspanol 
                   FROM paises 
                   INNER JOIN prestadores ON prestador_pais_id = pais_id 
                   WHERE prestador_id = :prestador_id
                   UNION
                   SELECT pais_id, pais_nombreEspanol 
                   FROM paises
                 ");

        DB::bind(':prestador_id', "$prestador_id");
        
        return DB::resultados();
    }
    
    // Método para el Select - Lista los paises en Modificar Cliente
    public static function formulario_modificacion_cliente_paises($cliente_id) {
        
        DB::query("
                   SELECT pais_id, pais_nombreEspanol 
                   FROM paises 
                   INNER JOIN clientes ON cliente_pais_id = pais_id 
                   WHERE cliente_id = :cliente_id 
                   UNION
                   SELECT pais_id, pais_nombreEspanol 
                   FROM paises
                 ");

        DB::bind(':cliente_id', "$cliente_id");
        
        return DB::resultados();
    }
    
    // Método para el Select - Lista los paises en Modificar Ciudad
    public static function formulario_modificacion_ciudad_paises($ciudad_id) {
        
        DB::query("
                   SELECT pais_id, pais_nombreEspanol 
                   FROM paises 
                   INNER JOIN ciudades ON ciudad_pais_id = pais_id 
                   WHERE ciudad_id = :ciudad_id 
                   UNION
                   SELECT pais_id, pais_nombreEspanol 
                   FROM paises
                 ");

        DB::bind(':ciudad_id', "$ciudad_id");
        
        return DB::resultados();
    }
    

    // Método para el Select - Lista los paises en Modificar Caso
    public static function listar_modificacion_casos($caso_id) {
        
        DB::query("SELECT pais_id, pais_nombreEspanol 
                        FROM paises 
                        INNER JOIN casos ON caso_pais_id = pais_id 
                        WHERE caso_id = :caso_id
                   UNION
                   SELECT pais_id, pais_nombreEspanol 
                   FROM paises");

        DB::bind(':caso_id', "$caso_id");

        return DB::resultados();
    }


    //  Método para mostrar el prefijo del pais elegido en Alta Caso
    public static function buscar_prefijo($pais_id) {
        
        DB::query("SELECT paises.pais_codigoTel as prefijo 
                    FROM paises 
                    WHERE paises.pais_id = :pais_id");

        DB::bind(':pais_id', $pais_id);

        $prefijo = DB::resultado();
        $prefijo = '+' . $prefijo['prefijo'];
        return $prefijo;
    }
}