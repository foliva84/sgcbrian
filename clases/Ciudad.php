<?php
/**
 * Clase: Ciudad
 *
 *
 * @author ArgenCode
 */

class Ciudad {
    
    // Método para listar ciudades
    public static function listar() {
        DB::query("SELECT ciudad_id,pais_nombreEspanol, ciudad_nombre, ciudad_activa
                    FROM ciudades
                    INNER JOIN paises ON pais_id = ciudad_pais_id
                    LIMIT 50
                ");
        
        return DB::resultados();
    }
    
    
    
    // Método para listar ciudades
    public static function listar_filtrado($ciudad_nombre_buscar, $ciudad_pais_buscar) {
    
        DB::query("SELECT ciudad_id,pais_nombreEspanol, ciudad_nombre, ciudad_activa
                    FROM ciudades
                    INNER JOIN paises ON pais_id = ciudad_pais_id
                    WHERE ciudad_nombre LIKE :ciudad_nombre
                    AND ciudad_pais_id = :ciudad_pais_id
                    LIMIT 50
                ");
        
        DB::bind(':ciudad_nombre', "%$ciudad_nombre_buscar%");
        DB::bind(':ciudad_pais_id', $ciudad_pais_buscar);
        
        return DB::resultados();
    }
    
    
    
    // Método para listar ciudades
    public static function listar_filtrado_contar($ciudad_nombre_buscar, $ciudad_pais_buscar) {
    
        DB::query("SELECT COUNT(*) AS registros
                    FROM ciudades
                    INNER JOIN paises ON pais_id = ciudad_pais_id
                    WHERE ciudad_nombre LIKE :ciudad_nombre
                    AND ciudad_pais_id = :ciudad_pais_id
                ");
        
        DB::bind(':ciudad_nombre', "%$ciudad_nombre_buscar%");
        DB::bind(':ciudad_pais_id', $ciudad_pais_buscar);
        
        return DB::resultado();
    }
    
    
    
    // Método para buscar una ciudad por nombre, es el resultado del autocomplete
    public static function buscar_select($nombre, $pais_id) {
        DB::query("SELECT ciudad_id, ciudad_nombre
                   FROM ciudades
                   WHERE ciudad_nombre LIKE :ciudad_nombre 
                   AND ciudad_activa = 1 
                   AND ciudad_pais_id = :ciudad_pais_id
                   LIMIT 30");
        
        DB::bind(':ciudad_nombre', "%$nombre%");
        DB::bind(':ciudad_pais_id', "$pais_id");
        
        return DB::resultados();
    }
    
    // Método para buscar una ciudad por id
    public static function buscarPorId($ciudad_id) {
        DB::query("SELECT ciudad_id, ciudad_pais_id, ciudad_nombre, ciudad_activa
                   FROM ciudades
                   WHERE ciudad_id = :ciudad_id");
        
        DB::bind(':ciudad_id', "$ciudad_id");
        
        return DB::resultado();        
    }
    
    // Método para buscar una ciudad por id
    public static function listarPorPaisId($pais_id) {
        DB::query("SELECT ciudad_id, ciudad_nombre
                   FROM ciudades
                   WHERE ciudad_pais_id = :pais_id AND ciudad_activa = 1");
        
        DB::bind(':pais_id', "$pais_id");
        
        return DB::resultados();        
    }
    
    // Método para desactivar un ciudad.
    public static function borradoLogico($ciudad_id) {
        DB::query("UPDATE ciudades SET
                        ciudad_activa = :ciudad_activa                               
                   WHERE ciudad_id = :ciudad_id");
        
        DB::bind(':ciudad_activa', false);
        DB::bind(':ciudad_id', "$ciudad_id");
        
        DB::execute();
        $mensaje = "ok";
        return $mensaje;
    }
    
    // Método para activar una ciudad.
    public static function reActivar($ciudad_id) {
        DB::query("UPDATE ciudades SET
                        ciudad_activa = :ciudad_activa                               
                   WHERE ciudad_id = :ciudad_id");
        
        DB::bind(':ciudad_activa', true);
        DB::bind(':ciudad_id', "$ciudad_id");
        
        DB::execute();
        $mensaje = "ok";
        return $mensaje;
    }
    
    // Métodos ABM
        // Método para insertar una ciudad
        public static function insertar($ciudad_pais_id_n, $ciudad_nombre_n) {   
            DB::query("INSERT INTO ciudades (ciudad_pais_id,
                                                ciudad_nombre,
                                                ciudad_activa)
                                         VALUES (:ciudad_pais_id,
                                                :ciudad_nombre,
                                                :ciudad_activa)");

            DB::bind(':ciudad_pais_id', "$ciudad_pais_id_n");
            DB::bind(':ciudad_nombre', "$ciudad_nombre_n");
            DB::bind(':ciudad_activa', true);

            DB::execute();     

            $ciudad_id = DB::lastInsertId();

            return $ciudad_id;
        }

        public static function actualizar($ciudad_pais_id, $ciudad_nombre, $ciudad_id) {
            
            $existe = self::existeUpdate($ciudad_nombre, $ciudad_id, $ciudad_pais_id);

            If ($existe == 1){
                $mensaje = "La ciudad ya existe en la base";
                return $mensaje;
            } else {

                DB::query("UPDATE ciudades SET
                            ciudad_pais_id = :ciudad_pais_id,
                            ciudad_nombre = :ciudad_nombre
                            WHERE ciudad_id = :ciudad_id");

                DB::bind(':ciudad_pais_id', "$ciudad_pais_id");
                DB::bind(':ciudad_nombre', "$ciudad_nombre");
                DB::bind(':ciudad_id', "$ciudad_id");

                DB::execute();
                $mensaje = "La ciudad fue actualizada con éxito";
                return $mensaje;
            }
        }
        
    // Métodos de validaciones en formulario
        // Método para verificar si existe la ciudad antes de hacer un insert   
        public static function existe($ciudad_nombre_n, $ciudad_pais_id_n) { 

            DB::query("SELECT ciudad_id, ciudad_pais_id, ciudad_nombre 
                        FROM ciudades 
                        WHERE ciudad_nombre = :ciudad_nombre
                        AND ciudad_pais_id = :ciudad_pais_id");

            DB::bind(':ciudad_nombre', "$ciudad_nombre_n");
            DB::bind(':ciudad_pais_id', "$ciudad_pais_id_n");

            $ciudad = DB::resultado(); 

            if(!empty($ciudad) && is_array($ciudad)){     
                $existe = true;
            }else{
                $existe= false;            
            }
            return $existe;
        }

        // Método para verificar si existe la practica antes de hacer un update
        public static function existeUpdate($ciudad_nombre, $ciudad_id, $ciudad_pais_id) {

            DB::query("SELECT ciudad_id, ciudad_pais_id, ciudad_nombre
                        FROM ciudades 
                        WHERE ciudad_nombre = :ciudad_nombre
                        AND ciudad_pais_id = :ciudad_pais_id
                        AND ciudad_id <> :ciudad_id");

            DB::bind(':ciudad_nombre', "$ciudad_nombre");
            DB::bind(':ciudad_pais_id', "$ciudad_pais_id");
            DB::bind(':ciudad_id', "$ciudad_id");

            $ciudad = DB::resultado(); 

            if(!empty($ciudad) && is_array($ciudad)){       
                $existe = true;
            }else{
                $existe= false;            
            }
            return $existe;      
        }
    
    // Métodos de formularios
        // Método para el Select - Lista las Ciudades
        public static function formulario_alta_ciudades() {

            DB::query("SELECT ciudad_id, ciudad_nombre
                        FROM ciudades 
                        WHERE ciudad_activa = 1 
                        ORDER BY ciudad_nombre");

            return DB::resultados();
        }

        // Método para el Select - Lista las Ciudades en modificar Prestador
        public static function formulario_modificacion_prestador_ciudades($prestador_id) {

             DB::query("SELECT ciudad_id, ciudad_nombre 
                        FROM ciudades 
                        INNER JOIN prestadores ON prestador_ciudad_id = ciudad_id 
                        WHERE prestador_id = :prestador_id 
                        UNION
                        SELECT ciudad_id, ciudad_nombre 
                        FROM ciudades 
                        WHERE ciudad_id NOT IN (SELECT prestador_ciudad_id FROM `prestadores` WHERE prestador_id = :prestador_id)
                        AND ciudad_pais_id IN (SELECT prestador_pais_id FROM `prestadores` WHERE prestador_id = :prestador_id)");

             DB::bind(':prestador_id', "$prestador_id");

            return DB::resultados();
        }

        // Método para el Select - Lista las Ciudades en modificar Cliente
        public static function formulario_modificacion_cliente_ciudades($cliente_id) {

             DB::query("SELECT ciudad_id, ciudad_nombre 
                        FROM ciudades 
                        INNER JOIN clientes ON cliente_ciudad_id = ciudad_id 
                        WHERE cliente_id = :cliente_id 
                        UNION
                        SELECT ciudad_id, ciudad_nombre 
                        FROM ciudades 
                        WHERE ciudad_id NOT IN (SELECT cliente_ciudad_id FROM `clientes` WHERE cliente_id = :cliente_id)
                        AND ciudad_pais_id IN (SELECT cliente_pais_id FROM `clientes` WHERE cliente_id = :cliente_id)");

             DB::bind(':cliente_id', "$cliente_id");

            return DB::resultados();
        }
}
