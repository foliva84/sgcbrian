<?php
/**
 * Clase: Prestador
 *
 *
 * @author ArgenCode
 */

class Prestador {
    
    const ENTIDAD =  Entidad::PRESTADOR;
    
    // Método para listar los prestadores
    public static function listar() {
        DB::query("SELECT prestador_id,
                          prestador_nombre,
                          tipoPrestador_nombre,
                          prestador_direccion,
                          pais_nombreEspanol,
                          ciudad_nombre,
                          prestadorPrioridad_nombre,
                          prestador_activo
                    FROM prestadores 
                        LEFT JOIN tipos_prestadores on tipoPrestador_id = prestador_tipoPrestador_id
                        LEFT JOIN paises on pais_id = prestador_pais_id
                        LEFT JOIN ciudades on ciudad_id = prestador_ciudad_id
                        LEFT JOIN prestadores_prioridades on prestadorPrioridad_id = prestador_prestadorPrioridad_id");
        
        return DB::resultados();
    }
    
    
    // Método para listar los prestadores aplicando el filtro de la grilla
    public static function listar_filtrado($prestador_nombre_buscar, 
                                           $prestador_tipoPrestador_id_buscar, 
                                           $prestador_pais_id_buscar,
                                           $prestador_ciudad_id_buscar) {
        
        If ($prestador_tipoPrestador_id_buscar == '') {
            $prestador_tipoPrestador_id_buscar = NULL;
        }
        
        If ($prestador_pais_id_buscar == '') {
            $prestador_pais_id_buscar = NULL;
        }
        
        If ($prestador_ciudad_id_buscar == '') {
            $prestador_ciudad_id_buscar = NULL;
        }
        
        DB::query("SELECT prestador_id,
                          prestador_nombre,
                          tipoPrestador_nombre,
                          prestador_direccion,
                          pais_nombreEspanol,
                          ciudad_nombre,
                          prestadorPrioridad_id,
                          prestadorPrioridad_nombre,
                          prestador_activo
                    FROM prestadores 
                        LEFT JOIN tipos_prestadores on tipoPrestador_id = prestador_tipoPrestador_id
                        LEFT JOIN paises on pais_id = prestador_pais_id
                        LEFT JOIN ciudades on ciudad_id = prestador_ciudad_id
                        LEFT JOIN prestadores_prioridades on prestadorPrioridad_id = prestador_prestadorPrioridad_id
                        LEFT JOIN prestadores_paises ON prestadorPais_prestador_id = prestador_id
                        LEFT JOIN prestadores_ciudades ON prestadorCiudad_prestador_id = prestador_id
                    WHERE prestador_nombre LIKE :prestador_nombre 
                      AND prestador_tipoPrestador_id = COALESCE(:prestador_tipoPrestador_id,prestador_tipoPrestador_id) 
                      AND 
                         (CASE WHEN :prestadorPais_pais_id is not null 
                            THEN (prestadorPais_pais_id = :prestadorPais_pais_id OR prestadorCiudad_ciudad_id = :prestadorCiudad_ciudad_id)
                            ELSE (prestador_id = prestador_id) 
                            END)
                    GROUP BY prestador_id
                    ORDER BY prestadorPrioridad_nombre
                    LIMIT 200");
        
        DB::bind(':prestador_nombre', "%$prestador_nombre_buscar%");
        DB::bind(':prestador_tipoPrestador_id', $prestador_tipoPrestador_id_buscar);
        DB::bind(':prestadorPais_pais_id', $prestador_pais_id_buscar);
        DB::bind(':prestadorCiudad_ciudad_id', $prestador_ciudad_id_buscar);
        
        return DB::resultados();
    }
    
    public static function listar_filtrado_contar($prestador_nombre_buscar, 
                                                  $prestador_tipoPrestador_id_buscar, 
                                                  $prestador_pais_id_buscar,
                                                  $prestador_ciudad_id_buscar) {
        
        If ($prestador_tipoPrestador_id_buscar == '') {
            $prestador_tipoPrestador_id_buscar = NULL;
        }
        
        If ($prestador_pais_id_buscar == '') {
            $prestador_pais_id_buscar = NULL;
        }
        
        If ($prestador_ciudad_id_buscar == '') {
            $prestador_ciudad_id_buscar = NULL;
        }
        
        DB::query("SELECT COUNT(*) AS registros FROM
                    (SELECT prestador_id,
                            prestador_activo
                    FROM prestadores 
                       LEFT JOIN prestadores_paises ON prestadorPais_prestador_id = prestador_id
                       LEFT JOIN prestadores_ciudades ON prestadorCiudad_prestador_id = prestador_id
                    WHERE prestador_nombre LIKE :prestador_nombre 
                      AND prestador_tipoPrestador_id = COALESCE(:prestador_tipoPrestador_id,prestador_tipoPrestador_id) 
                      AND 
                         (CASE WHEN :prestadorPais_pais_id is not null 
                                THEN (prestadorPais_pais_id = :prestadorPais_pais_id OR prestadorCiudad_ciudad_id = :prestadorCiudad_ciudad_id)
                                ELSE (prestador_id = prestador_id) 
                               END)     
                    GROUP BY prestador_id) AS prestadores
                  ");
        
        DB::bind(':prestador_nombre', "%$prestador_nombre_buscar%");
        DB::bind(':prestador_tipoPrestador_id', $prestador_tipoPrestador_id_buscar);
        DB::bind(':prestadorPais_pais_id', $prestador_pais_id_buscar);
        DB::bind(':prestadorCiudad_ciudad_id', $prestador_ciudad_id_buscar);
        
        return DB::resultado();
    }
    
    
    // Método para listar los prestadores aplicando el filtro de la grilla en SERVICIOS
    public static function listar_filtrado_enServicios($prestador_nombre_buscar, 
                                           $prestador_tipoPrestador_id_buscar, 
                                           $prestador_pais_id_buscar,
                                           $prestador_ciudad_id_buscar) {
        
        If ($prestador_tipoPrestador_id_buscar == '') {
            $prestador_tipoPrestador_id_buscar = NULL;
        }
        
        If ($prestador_pais_id_buscar == '') {
            $prestador_pais_id_buscar = NULL;
        }
        
        If ($prestador_ciudad_id_buscar == '') {
            $prestador_ciudad_id_buscar = NULL;
        }
        
        DB::query("SELECT prestador_id,
                          prestador_nombre,
                          tipoPrestador_nombre,
                          prestador_direccion,
                          pais_nombreEspanol,
                          ciudad_nombre,
                          prestadorPrioridad_id,
                          prestadorPrioridad_nombre,
                          prestador_activo
                    FROM prestadores 
                        LEFT JOIN tipos_prestadores on tipoPrestador_id = prestador_tipoPrestador_id
                        LEFT JOIN paises on pais_id = prestador_pais_id
                        LEFT JOIN ciudades on ciudad_id = prestador_ciudad_id
                        LEFT JOIN prestadores_prioridades on prestadorPrioridad_id = prestador_prestadorPrioridad_id
                        LEFT JOIN prestadores_paises ON prestadorPais_prestador_id = prestador_id
                        LEFT JOIN prestadores_ciudades ON prestadorCiudad_prestador_id = prestador_id
                    WHERE prestador_nombre LIKE :prestador_nombre 
                      AND prestador_tipoPrestador_id = COALESCE(:prestador_tipoPrestador_id,prestador_tipoPrestador_id) 
                      AND 
                         (CASE WHEN :prestadorPais_pais_id is not null 
                            THEN (prestadorPais_pais_id = :prestadorPais_pais_id OR prestadorCiudad_ciudad_id = :prestadorCiudad_ciudad_id)
                            ELSE (prestador_id = prestador_id) 
                            END)
                      AND NOT (prestador_prestadorPrioridad_id = 5 or prestador_prestadorPrioridad_id = 6)      
                    GROUP BY prestador_id
                    ORDER BY prestadorPrioridad_nombre
                    LIMIT 50");
        
        DB::bind(':prestador_nombre', "%$prestador_nombre_buscar%");
        DB::bind(':prestador_tipoPrestador_id', $prestador_tipoPrestador_id_buscar);
        DB::bind(':prestadorPais_pais_id', $prestador_pais_id_buscar);
        DB::bind(':prestadorCiudad_ciudad_id', $prestador_ciudad_id_buscar);
        
        return DB::resultados();
    }
    
    public static function listar_filtrado_contar_enServicios($prestador_nombre_buscar, 
                                                  $prestador_tipoPrestador_id_buscar, 
                                                  $prestador_pais_id_buscar,
                                                  $prestador_ciudad_id_buscar) {
        
        If ($prestador_tipoPrestador_id_buscar == '') {
            $prestador_tipoPrestador_id_buscar = NULL;
        }
        
        If ($prestador_pais_id_buscar == '') {
            $prestador_pais_id_buscar = NULL;
        }
        
        If ($prestador_ciudad_id_buscar == '') {
            $prestador_ciudad_id_buscar = NULL;
        }
        
        DB::query("SELECT COUNT(*) AS registros FROM
                    (SELECT prestador_id,
                            prestador_activo
                    FROM prestadores 
                       LEFT JOIN prestadores_paises ON prestadorPais_prestador_id = prestador_id
                       LEFT JOIN prestadores_ciudades ON prestadorCiudad_prestador_id = prestador_id
                    WHERE prestador_nombre LIKE :prestador_nombre 
                      AND prestador_tipoPrestador_id = COALESCE(:prestador_tipoPrestador_id,prestador_tipoPrestador_id) 
                      AND 
                         (CASE WHEN :prestadorPais_pais_id is not null 
                                THEN (prestadorPais_pais_id = :prestadorPais_pais_id OR prestadorCiudad_ciudad_id = :prestadorCiudad_ciudad_id)
                                ELSE (prestador_id = prestador_id) 
                               END)
                      AND NOT (prestador_prestadorPrioridad_id = 5 or prestador_prestadorPrioridad_id = 6)           
                    GROUP BY prestador_id) AS prestadores
                  ");
        
        DB::bind(':prestador_nombre', "%$prestador_nombre_buscar%");
        DB::bind(':prestador_tipoPrestador_id', $prestador_tipoPrestador_id_buscar);
        DB::bind(':prestadorPais_pais_id', $prestador_pais_id_buscar);
        DB::bind(':prestadorCiudad_ciudad_id', $prestador_ciudad_id_buscar);
        
        return DB::resultado();
    }
    
    
    // Método para listar los prestadores
    public static function listar_prestadores() {
        DB::query("SELECT prestador_id,
                          prestador_nombre
                     FROM prestadores
                     ORDER BY prestador_nombre");
        
        return DB::resultados();
    }
        
    // Operaciones con prácticas
    
    // Listado de prácticas
    public static function listar_practicas($prestador_id) {
        
        DB::query("SELECT 
                        prestadores_practicas.prestadorPractica_id,
                        practicas.practica_nombre,
                        prestadores_practicas.presuntoOrigen
                    FROM prestadores_practicas 
                        LEFT JOIN practicas on practicas.practica_id = prestadores_practicas.prestadorPractica_practica_id
                    WHERE 
                        prestadores_practicas.prestadorPractica_prestador_id = :prestadorPractica_prestador_id"
                );
        
        DB::bind(':prestadorPractica_prestador_id', $prestador_id);
        
        return DB::resultados();
    
    }
    
    
    // Método para insertar una práctica en el prestador
    public static function insertar_practica($prestador_id, $practica_id, $presuntoOrigen)       
    {   
        
        $existe = self::existe_practica($prestador_id, $practica_id);
        
        If ($existe == 1){
          
            return $existe;
            
        } else {
         
        DB::query("
                    INSERT INTO prestadores_practicas 
                        (
                            prestadorPractica_practica_id,
                            prestadorPractica_prestador_id,
                            presuntoOrigen
                        )
                    VALUES 
                        (
                            :prestadorPractica_practica_id,
                            :prestadorPractica_prestador_id,
                            :presuntoOrigen
                        )       
                 ");
        
        DB::bind(':prestadorPractica_practica_id', "$practica_id");
        DB::bind(':prestadorPractica_prestador_id', "$prestador_id");
        DB::bind(':presuntoOrigen', "$presuntoOrigen");

        DB::execute(); 
        
        }
        
    }
    
     
    // Método para eliminar una práctica del prestador
    public static function eliminar_practica($prestadorPractica_id)       
    {   
        DB::query("
                    DELETE FROM prestadores_practicas
                    WHERE
                        prestadorPractica_id = :prestadorPractica_id    
                 ");
        
        DB::bind(':prestadorPractica_id', $prestadorPractica_id);

        DB::execute();     
    }
    
    
    // Método para evaluar si el cliente ya posee el producto ingresado
    public static function existe_practica($prestador_id, $practica_id)
    {
        DB::query("
                    SELECT COUNT(*) as cantidad
                    FROM prestadores_practicas
                    WHERE prestadorPractica_practica_id = :prestadorPractica_practica_id
                    AND prestadorPractica_prestador_id = :prestadorPractica_prestador_id 
                 ");
        
        DB::bind(':prestadorPractica_practica_id', "$practica_id");
        DB::bind(':prestadorPractica_prestador_id', "$prestador_id");
        
        $resultado = DB::resultado(); 
        $cantidad = $resultado['cantidad'];
        
        if($cantidad >=1){
            $existe = true;
        } else {
            $existe = false;
        }
                
        return $existe;      
    }

    
    
    // Operaciones con paises
    
    // Listado de paises
    public static function listar_paises($prestador_id) {
        
        DB::query("
                    SELECT 
                        prestadores_paises.prestadorPais_id, 
                        paises.pais_nombreEspanol 
                    FROM prestadores_paises 
                    LEFT JOIN paises ON paises.pais_id = prestadores_paises.prestadorPais_pais_id 
                    WHERE prestadores_paises.prestadorPais_prestador_id = :prestadorPais_prestador_id
                  ");
        
        DB::bind(':prestadorPais_prestador_id', $prestador_id);
        
        return DB::resultados();
    
    }
    
    // Método para insertar una país en el prestador
    public static function insertar_pais($prestador_id, $pais_id)       
    {   
        
        $existe = self::existe_pais($prestador_id, $pais_id);
        
        If ($existe == 1){
            
          
            return $existe;
            
        } else {
         
        DB::query("
                    INSERT INTO prestadores_paises
                        (prestadorPais_pais_id,
                        prestadorPais_prestador_id)
                    VALUES (:prestadorPais_pais_id,
                            :prestadorPais_prestador_id)       
                 ");
        
        DB::bind(':prestadorPais_pais_id', "$pais_id");
        DB::bind(':prestadorPais_prestador_id', "$prestador_id");

        DB::execute(); 
        
        }
    }
     
    // Método para eleminar una país del prestador
    public static function eliminar_pais($prestadorPais_id)       
    {   
        
        DB::query("
                    DELETE FROM prestadores_paises
                    WHERE
                        prestadorPais_id = :prestadorPais_id    
                 ");
        
        DB::bind(':prestadorPais_id', $prestadorPais_id);

        DB::execute();     
    }
    
    public static function existe_pais($prestador_id, $pais_id)
    {
        DB::query("
                    SELECT COUNT(*) as cantidad
                    FROM prestadores_paises
                    WHERE prestadorPais_pais_id = :prestadorPais_pais_id
                    AND prestadorPais_prestador_id = :prestadorPais_prestador_id 
                 ");
        
        DB::bind(':prestadorPais_pais_id', $pais_id);
        DB::bind(':prestadorPais_prestador_id', $prestador_id);
        
        $resultado = DB::resultado(); 
        $cantidad = $resultado['cantidad'];
        
        if($cantidad >=1){
            $existe = true;
        } else {
            $existe = false;
        }
                
        return $existe;      
    }
    
    
    // Operaciones con ciudades
    
    // Listado de ciudades
    public static function listar_ciudades($prestador_id) {
        
        DB::query("
                    SELECT 
                        prestadores_ciudades.prestadorCiudad_id, 
                        ciudades.ciudad_nombre 
                    FROM prestadores_ciudades 
                    LEFT JOIN ciudades ON ciudades.ciudad_id = prestadores_ciudades.prestadorCiudad_ciudad_id 
                    WHERE prestadores_ciudades.prestadorCiudad_prestador_id = :prestadorCiudad_prestador_id
                  ");
        
        DB::bind(':prestadorCiudad_prestador_id', $prestador_id);
        
        return DB::resultados();
    
    }
    
    // Método para insertar una ciudad en el prestador
    public static function insertar_ciudad($prestador_id, $ciudad_id)       
    {   
        
        $existe = self::existe_ciudad($prestador_id, $ciudad_id);
        
        If ($existe == 1){
          
            return $existe;
            
        } else {
         
        DB::query("
                    INSERT INTO prestadores_ciudades
                        (prestadorCiudad_ciudad_id,
                        prestadorCiudad_prestador_id)
                    VALUES (:prestadorCiudad_ciudad_id,
                            :prestadorCiudad_prestador_id)       
                 ");
        
        DB::bind(':prestadorCiudad_ciudad_id', "$ciudad_id");
        DB::bind(':prestadorCiudad_prestador_id', "$prestador_id");

        DB::execute(); 
        
        }
        
    }
     
    // Método para eleminar una ciudad del prestador
    public static function eliminar_ciudad($prestadorCiudad_id)       
    {   
        
        DB::query("
                    DELETE FROM prestadores_ciudades
                    WHERE
                        prestadorCiudad_id = :prestadorCiudad_id    
                 ");
        
        DB::bind(':prestadorCiudad_id', $prestadorCiudad_id);

        DB::execute();     
    }
    
    
    public static function existe_ciudad($prestador_id, $ciudad_id)
    {
        DB::query("
                    SELECT COUNT(*) as cantidad
                    FROM prestadores_ciudades
                    WHERE prestadorCiudad_ciudad_id = :prestadorCiudad_ciudad_id
                    AND prestadorCiudad_prestador_id = :prestadorCiudad_prestador_id 
                 ");
        
        DB::bind(':prestadorCiudad_ciudad_id', $ciudad_id);
        DB::bind(':prestadorCiudad_prestador_id', $prestador_id);
        
        $resultado = DB::resultado(); 
        $cantidad = $resultado['cantidad'];
        
        if($cantidad >=1){
            $existe = true;
        } else {
            $existe = false;
        }
                
        return $existe;      
    }
    
    
    // Método para buscar un prestador por nombre
    public static function buscar_por_nombre($prestador_nombre) {
        DB::query("SELECT prestador_id, CONCAT(prestadores.prestador_nombre, ' - ', prestadores.prestador_razonSocial) AS prestador_nombre
                   FROM prestadores
                   WHERE (CONCAT(prestadores.prestador_nombre, ' ', prestadores.prestador_razonSocial)) LIKE :prestador_nombre AND prestador_activo = 1
                   LIMIT 15");
        
        DB::bind(':prestador_nombre', "%$prestador_nombre%");
        
        return DB::resultados();
    }
    
    
    // Un tipo de búsqueda, se debería definir el resto.
    public static function buscar_por_id($prestador_id) {
        DB::query("SELECT 
                        prestador_id,
                        prestador_nombre,
                        prestador_razonSocial,
                        prestador_tipoPrestador_id,
                        tipoPrestador_nombre,
                        prestador_paginaWeb,
                        prestador_prestadorPrioridad_id,
                        prestadorPrioridad_nombre,
                        prestador_direccion,
                        prestador_codigoPostal,
                        prestador_pais_id,
                        pais_nombreEspanol,
                        prestador_ciudad_id,
                        ciudad_nombre,
                        prestador_contrato_id,
                        prestadorContrato_nombre,
                        prestador_contratoObservaciones,
                        prestador_bancoIntermediario,
                        prestador_bancoBeneficiario,
                        prestador_taxID,
                        prestador_inicioActividades,
                        prestador_observaciones,
                        prestador_activo
                   FROM prestadores INNER JOIN ciudades ON ciudad_id = prestador_ciudad_id
                                    LEFT JOIN tipos_prestadores ON tipoPrestador_id = prestador_tipoPrestador_id
                                    LEFT JOIN prestadores_prioridades ON prestadorPrioridad_id = prestador_prestadorPrioridad_id
                                    LEFT JOIN paises ON pais_id = prestador_pais_id
                                    LEFT JOIN prestadores_contratos ON prestadorContrato_id = prestador_contrato_id
                   WHERE prestador_id = :prestador_id");
        
        DB::bind(':prestador_id', "$prestador_id");
        
        $resultado = DB::resultado();

        if ($resultado['prestador_inicioActividades'] !== NULL) {
            //Conversiones de la fecha de ANSI a normal para Datepicker
            $inicio_actividades = date("d-m-Y", strtotime($resultado['prestador_inicioActividades']));
            $resultado['prestador_inicioActividades'] = $inicio_actividades;
        } else {
            $resultado['prestador_inicioActividades'] = NULL;
        }
        
        return $resultado;
    }
   
    
    // Busqueda de prestador por nombre
    public static function buscar($nombre)
    {
        DB::query("SELECT 
                        prestador_id,
                        prestador_nombre
                   FROM prestadores
                   WHERE prestador_nombre LIKE :prestador_nombre");
        
        DB::bind(':prestador_nombre', "%$nombre%");
        return DB::resultado();
    }
    
    
    // Un tipo de búsqueda, se debería definir el resto.
    public static function buscarPorId($prestador_id)
    {
        DB::query("SELECT 
                        prestador_id,
                        prestador_nombre,
                        prestador_razonSocial,
                        prestador_tipoPrestador_id,
                        tipoPrestador_nombre,
                        prestador_paginaWeb,
                        prestador_prestadorPrioridad_id,
                        prestadorPrioridad_nombre,
                        prestador_direccion,
                        prestador_codigoPostal,
                        prestador_pais_id,
                        pais_nombreEspanol,
                        prestador_ciudad_id,
                        ciudad_nombre,
                        prestador_contrato_id,
                        prestadorContrato_nombre,
                        prestador_contratoObservaciones,
                        prestador_bancoIntermediario,
                        prestador_bancoBeneficiario,
                        prestador_taxID,
                        prestador_inicioActividades,
                        prestador_observaciones,
                        prestador_activo
                   FROM prestadores INNER JOIN ciudades ON ciudad_id = prestador_ciudad_id
                                    LEFT JOIN tipos_prestadores ON tipoPrestador_id = prestador_tipoPrestador_id
                                    LEFT JOIN prestadores_prioridades ON prestadorPrioridad_id = prestador_prestadorPrioridad_id
                                    LEFT JOIN paises ON pais_id = prestador_pais_id
                                    LEFT JOIN prestadores_contratos ON prestadorContrato_id = prestador_contrato_id
                   WHERE prestador_id = :prestador_id");
        
        DB::bind(':prestador_id', "$prestador_id");
        
        $resultado = DB::resultado();

        if ($resultado['prestador_inicioActividades'] !== NULL) {
            //Conversiones de la fecha de ANSI a normal para Datepicker
            $inicio_actividades = date("d-m-Y", strtotime($resultado['prestador_inicioActividades']));
            $resultado['prestador_inicioActividades'] = $inicio_actividades;
        } else {
            $resultado['prestador_inicioActividades'] = NULL;
        }
        
        return $resultado;
    }
    
    // Método para desactivar un prestador.
    public static function borradoLogico($prestador_id)
    {
        DB::query("
                    UPDATE prestadores SET
                        prestador_activo = :prestador_activo                               
                    WHERE prestador_id = :prestador_id
                  ");
        
        DB::bind(':prestador_activo', false);
        DB::bind(':prestador_id', "$prestador_id");
        
        DB::execute();
        $mensaje = "ok";
        return $mensaje;
    }
    
    // Método para activar un prestador.
    public static function reActivar($prestador_id)
    {
        DB::query("UPDATE prestadores SET
                               prestador_activo = :prestador_activo                               
                    WHERE prestador_id = :prestador_id");
        
        DB::bind(':prestador_activo', true);
        DB::bind(':prestador_id', "$prestador_id");
        
        DB::execute();
        $mensaje = "ok";
        return $mensaje;
    }
          
    // Métodos ABM
    // Método para insertar un prestador
    public static function insertar($prestador_nombre_n, 
                                    $prestador_razonSocial_n, 
                                    $prestador_tipoPrestador_id_n, 
                                    $prestador_paginaWeb_n, 
                                    $prestador_prestadorPrioridad_id_n, 
                                    $prestador_direccion_n,
                                    $prestador_codigoPostal_n,
                                    $prestador_pais_id_n, 
                                    $prestador_ciudad_id_n, 
                                    $prestador_contrato_id_n, 
                                    $prestador_contratoObservaciones_n, 
                                    $prestador_bancoIntermediario_n, 
                                    $prestador_bancoBeneficiario_n, 
                                    $prestador_taxID_n, 
                                    $prestador_inicioActividades_n,
                                    $prestador_observaciones_n) {   
        
        if (!empty($prestador_inicioActividades_n)) {
            $prestador_inicioActividades_n = date('Y-m-d H:i:s', strtotime($prestador_inicioActividades_n));
        } else {
            $prestador_inicioActividades_n = NULL;
        }
        if (empty($prestador_contrato_id_n)) {
            $prestador_contrato_id_n = 0;
        }
        
        DB::query("INSERT INTO prestadores (prestador_nombre,
                                             prestador_razonSocial,
                                             prestador_tipoPrestador_id,
                                             prestador_paginaWeb,
                                             prestador_prestadorPrioridad_id,
                                             prestador_direccion,
                                             prestador_codigoPostal,
                                             prestador_pais_id,
                                             prestador_ciudad_id,
                                             prestador_contrato_id,
                                             prestador_contratoObservaciones,
                                             prestador_bancoIntermediario,
                                             prestador_bancoBeneficiario,
                                             prestador_taxID,
                                             prestador_inicioActividades,
                                             prestador_observaciones,
                                             prestador_activo)
                                     VALUES (:prestador_nombre_n,
                                             :prestador_razonSocial_n,
                                             :prestador_tipoPrestador_id_n,
                                             :prestador_paginaWeb_n,
                                             :prestador_prestadorPrioridad_id_n,
                                             :prestador_direccion_n,
                                             :prestador_codigoPostal_n,
                                             :prestador_pais_id_n,
                                             :prestador_ciudad_id_n,
                                             :prestador_contrato_id_n,
                                             :prestador_contratoObservaciones_n,
                                             :prestador_bancoIntermediario_n,
                                             :prestador_bancoBeneficiario_n,
                                             :prestador_taxID_n,
                                             :prestador_inicioActividades_n,
                                             :prestador_observaciones_n,
                                             :prestador_activo_n)");
        
        DB::bind(':prestador_nombre_n', "$prestador_nombre_n");
        DB::bind(':prestador_razonSocial_n', "$prestador_razonSocial_n");
        DB::bind(':prestador_tipoPrestador_id_n', $prestador_tipoPrestador_id_n);
        DB::bind(':prestador_paginaWeb_n', "$prestador_paginaWeb_n");
        DB::bind(':prestador_prestadorPrioridad_id_n', $prestador_prestadorPrioridad_id_n);
        DB::bind(':prestador_direccion_n', "$prestador_direccion_n");
        DB::bind(':prestador_codigoPostal_n', "$prestador_codigoPostal_n");
        DB::bind(':prestador_pais_id_n', $prestador_pais_id_n);
        DB::bind(':prestador_ciudad_id_n', $prestador_ciudad_id_n);
        DB::bind(':prestador_contrato_id_n', $prestador_contrato_id_n);
        DB::bind(':prestador_contratoObservaciones_n', "$prestador_contratoObservaciones_n");
        DB::bind(':prestador_bancoIntermediario_n', "$prestador_bancoIntermediario_n");
        DB::bind(':prestador_bancoBeneficiario_n', "$prestador_bancoBeneficiario_n");
        DB::bind(':prestador_taxID_n', "$prestador_taxID_n");
        if (!empty($prestador_inicioActividades_n)) {
            DB::bind(':prestador_inicioActividades_n', "$prestador_inicioActividades_n");
        } else {
            DB::bind(':prestador_inicioActividades_n', $prestador_inicioActividades_n);
        }
        DB::bind(':prestador_observaciones_n', "$prestador_observaciones_n");
        DB::bind(':prestador_activo_n', true);
                
        DB::execute();     
        
        $prestador_id = DB::lastInsertId();
        
        return $prestador_id;
    }
    
    // Método para actualizar un prestador
    public static function actualizar($prestador_nombre, 
                                      $prestador_razonSocial, 
                                      $prestador_tipoPrestador_id, 
                                      $prestador_paginaWeb, 
                                      $prestador_prestadorPrioridad_id, 
                                      $prestador_direccion,
                                      $prestador_codigoPostal,
                                      $prestador_pais_id, 
                                      $prestador_ciudad_id, 
                                      $prestador_contrato_id, 
                                      $prestador_contratoObservaciones, 
                                      $prestador_bancoIntermediario, 
                                      $prestador_bancoBeneficiario, 
                                      $prestador_taxID, 
                                      $prestador_inicioActividades,
                                      $prestador_observaciones,
                                      $prestador_id) {                     

        $existe = self::existeUpdate($prestador_nombre, $prestador_id);

        If ($existe == 1){
            $mensaje = "El prestador ya existe en la base";
            return $mensaje;
        } else {

        if (!empty($prestador_inicioActividades)) {
            $prestador_inicioActividades = date('Y-m-d H:i:s', strtotime($prestador_inicioActividades));
        } else {
            $prestador_inicioActividades = NULL;
        }    
     
                DB::query("UPDATE prestadores SET
                                prestador_nombre = :prestador_nombre,
                                prestador_razonSocial = :prestador_razonSocial,
                                prestador_tipoPrestador_id = :prestador_tipoPrestador_id,
                                prestador_paginaWeb = :prestador_paginaWeb,
                                prestador_prestadorPrioridad_id = :prestador_prestadorPrioridad_id,
                                prestador_direccion = :prestador_direccion,
                                prestador_codigoPostal = :prestador_codigoPostal,
                                prestador_pais_id = :prestador_pais_id,
                                prestador_ciudad_id = :prestador_ciudad_id,
                                prestador_contrato_id = :prestador_contrato_id,
                                prestador_contratoObservaciones = :prestador_contratoObservaciones,
                                prestador_bancoIntermediario = :prestador_bancoIntermediario,
                                prestador_bancoBeneficiario = :prestador_bancoBeneficiario,
                                prestador_taxID = :prestador_taxID,
                                prestador_inicioActividades = :prestador_inicioActividades,
                                prestador_observaciones = :prestador_observaciones
                            WHERE prestador_id = :prestador_id");

        DB::bind(':prestador_nombre', "$prestador_nombre");
        DB::bind(':prestador_razonSocial', "$prestador_razonSocial");
        DB::bind(':prestador_tipoPrestador_id', $prestador_tipoPrestador_id);
        DB::bind(':prestador_paginaWeb', "$prestador_paginaWeb");
        DB::bind(':prestador_prestadorPrioridad_id', $prestador_prestadorPrioridad_id);
        DB::bind(':prestador_direccion', "$prestador_direccion");
        DB::bind(':prestador_codigoPostal', "$prestador_codigoPostal");
        DB::bind(':prestador_pais_id', $prestador_pais_id);
        DB::bind(':prestador_ciudad_id', $prestador_ciudad_id);
        DB::bind(':prestador_contrato_id', $prestador_contrato_id);
        DB::bind(':prestador_contratoObservaciones', "$prestador_contratoObservaciones");
        DB::bind(':prestador_bancoIntermediario', "$prestador_bancoIntermediario");
        DB::bind(':prestador_bancoBeneficiario', "$prestador_bancoBeneficiario");
        DB::bind(':prestador_taxID', "$prestador_taxID");
        if (!empty($prestador_inicioActividades)) {
            DB::bind(':prestador_inicioActividades', "$prestador_inicioActividades");
        } else {
            DB::bind(':prestador_inicioActividades', $prestador_inicioActividades);
        }
        DB::bind(':prestador_observaciones', "$prestador_observaciones");
        DB::bind(':prestador_id', $prestador_id);

        DB::execute();
        
        $mensaje = "El prestador fue actualizado con éxito";
        return $mensaje;
        }
    }
    
    
    //Verificar si existe el prestador antes de hacer un insert   
    public static function existe($prestador_nombre)
    {
        DB::query("SELECT 
                        prestador_id,
                        prestador_nombre,
                        prestador_activo
                   FROM prestadores
                   WHERE prestador_nombre = :prestador_nombre");
        
        DB::bind(':prestador_nombre', "$prestador_nombre");
                
        $prestador = DB::resultado(); 
        
        if(!empty($prestador) && is_array($prestador)){       
            $existe = true;
        }else{
            $existe = false;            
        }
        return $existe;      
    }
     
    //Verificar si existe el prestador antes de hacer un update
    public static function existeUpdate($prestador_nombre, $prestador_id)
    { 
        DB::query("SELECT 
                        prestador_id,
                        prestador_nombre,
                        prestador_activo
                   FROM prestadores 
                   WHERE prestador_nombre = :prestador_nombre
                   AND prestador_id <> :prestador_id");
        
        DB::bind(':prestador_nombre', "$prestador_nombre");
        DB::bind(':prestador_id', "$prestador_id");
       
        $prestador = DB::resultado(); 
        
        if(!empty($prestador) && is_array($prestador)){       
            $existe = true;
        }else{
            $existe= false;            
        }
        return $existe;      
    }
    
    
    // Método para el Select - Lista las prioridades de prestador en Alta del prestador
    public static function formulario_alta_prestadorPrioridad() {

        DB::query("SELECT
                    prestadorPrioridad_id, 
                    prestadorPrioridad_nombre
                   FROM prestadores_prioridades 
                   WHERE prestadorPrioridad_activa = 1 
                   ORDER BY prestadorPrioridad_nombre");

        return DB::resultados();
    }
    // Método para el Select - Lista las prioridades de prestador en Modificacion del prestador
    public static function formulario_modificacion_prestadorPrioridad($prestador_id) {

        DB::query("
                    SELECT prestadorPrioridad_id, prestadorPrioridad_nombre 
                    FROM prestadores_prioridades WHERE prestadorPrioridad_id 
                    IN (SELECT prestador_prestadorPrioridad_id FROM `prestadores` WHERE prestador_id = :prestador_id) 
                    UNION
                    SELECT prestadorPrioridad_id, prestadorPrioridad_nombre 
                    FROM prestadores_prioridades
                  ");
        
        DB::bind(':prestador_id', "$prestador_id");
    
        return DB::resultados();
    }
    
    
    // Método para el Select - Lista los contratos de prestador en Alta de Prestador
    public static function formulario_alta_prestadorContrato() {

        DB::query("SELECT
                    prestadorContrato_id, 
                    prestadorContrato_nombre
                   FROM prestadores_contratos WHERE prestadorContrato_activo = 1 ORDER BY prestadorContrato_nombre");

        return DB::resultados();
    }
    
    
    // Método para el Select - Lista los contratos de prestador en Modificacion de Prestador
    public static function formulario_modificacion_prestadorContrato($prestador_id) {

        DB::query("
                    SELECT prestadorContrato_id, prestadorContrato_nombre
                    FROM prestadores_contratos 
                    INNER JOIN prestadores ON prestador_contrato_id = prestadorContrato_id 
                    WHERE prestador_id = :prestador_id 
                    UNION
                    SELECT prestadorContrato_id, prestadorContrato_nombre
                    FROM prestadores_contratos
                    WHERE prestadorContrato_activo = 1
                  ");
        
        DB::bind(':prestador_id', "$prestador_id");

        return DB::resultados();
    }
    
    
    // Select - Metodo para listar los Prestadores
    public static function listar_prestadores_select() {
        
        DB::query("SELECT prestador_id, CONCAT(prestadores.prestador_nombre, ' - ', prestadores.prestador_razonSocial) as prestador_nombre
                   FROM prestadores
                   WHERE prestador_activo = 1
                   ORDER BY prestador_nombre, prestador_razonSocial");
        
        return DB::resultados();    
    }
    
    
    // Select - Metodo para listar los Prestadores
    public static function listar_prestadores_conRazonSocial_select() {
        
        DB::query("SELECT prestador_id, prestador_nombre, prestador_razonSocial
                   FROM prestadores
                   WHERE prestador_activo = 1
                   ORDER BY prestador_nombre");
        
        return DB::resultados();    
    }
}