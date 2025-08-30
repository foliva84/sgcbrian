<?php
/**
 * Clase: Reintegro
 *
 *
 * @author ArgenCode
 */

class Reintegro {
    



    # Métodos de Búsqueda / Select #


    // Método para listar los reintegros por numero de caso
    public static function listarPorCaso($caso_id) {
        
        DB::query("SELECT reintegro_id,
                            reintegroEstado_nombre,
                            reintegro_fechaPresentacion,
                            reintegro_fechaAuditado,
                            reintegro_fechaPago,
                            reintegros_agenda.reintegroAgenda_id,
                            usuarios.usuario_nombre,
                            usuarios.usuario_apellido                    
                    FROM reintegros
                        LEFT JOIN reintegros_estados ON reintegroEstado_id = reintegro_reintegroEstado_id
                        LEFT JOIN reintegros_agenda ON reintegroAgenda_reintegro_id = reintegro_id
                        LEFT JOIN usuarios ON usuario_id = reintegros_agenda.reintegroAgenda_usuarioAsignado_id           
                   WHERE reintegro_caso_id = :caso_id");
                        
        DB::bind(':caso_id', $caso_id);

        return DB::resultados();
    }    
    
    // Método para mostrar la información de un reintegro
    public static function buscarPorId($reintegro_id) {
        
        DB::query("SELECT reintegro_id,
                        reintegro_reintegroEstado_id,
                        reintegroEstado_nombre,
                        reintegro_fechaIngresoSistema,
                        reintegro_fechaPresentacion,
                        reintegro_fechaAuditado,
                        reintegro_fechaPago,
                        formas_pagos.formaPago_nombre,
                        reintegro_observaciones,
                        reintegro_CBUcuenta,
                        reintegro_CBUalias,
                        reintegro_denominacion,
                        reintegro_documentoTipo_id,
                        tipoDocumento_nombre,
                        reintegro_beneficiarioDocumento,
                        reintegro_referenciaTipo_id,
                        re_tipoReferencia_nombre,
                        reintegro_referencia,
                        reintegro_avisoTransTipo_id,
                        re_tipoAvisoTrans_nombre,
                        reintegro_emailDestinatario,
                        reintegro_emailTexto,
                        reintegro_compania,
                        reintegro_codigoArea,
                        reintegro_telefono,
                        SUM(reintegros_items.reintegroItem_importeAprobadoUSD) AS total_usd,
                        reintegro_importeARS as total_ars,
                        clientes.cliente_pais_id as pais,
                        reintegro_banco,
                        reintegro_digito_verificacion_titular,
                        reintegro_mail_titular,
                        reintegro_tipo_cuenta,
                        reintegro_direccion_titular,
                        reintegro_ciudad
                    FROM reintegros 
                        LEFT JOIN reintegros_estados ON reintegroEstado_id = reintegro_reintegroEstado_id
                        LEFT JOIN formas_pagos ON formaPago_id = reintegro_formaPago_id
                        LEFT JOIN tipos_documentos ON tipoDocumento_id = reintegro_documentoTipo_id
                        LEFT JOIN re_tipos_referencia ON re_tipoReferencia_id = reintegro_referenciaTipo_id
                        LEFT JOIN re_tipos_avisotransferencia ON re_tipoAvisoTrans_id = reintegro_avisoTransTipo_id
		                LEFT JOIN reintegros_items ON reintegroItem_reintegro_id = reintegro_id
                        LEFT JOIN casos ON reintegros.reintegro_caso_id = casos.caso_id
                        LEFT JOIN clientes ON clientes.cliente_id = casos.caso_cliente_id
                    WHERE reintegro_id = :reintegro_id");
        
        DB::bind(':reintegro_id', $reintegro_id);
        
        $resultado = DB::resultado();
        
        //Conversiones de la fecha de ANSI a normal para Datepicker
        $reintegro_fechaIngresoSistema = $resultado['reintegro_fechaIngresoSistema'];
        $reintegro_fechaIngresoSistema = date("d-m-Y H:i", strtotime($reintegro_fechaIngresoSistema));
        $resultado['reintegro_fechaIngresoSistema'] = $reintegro_fechaIngresoSistema;
        
        if ($resultado['reintegro_fechaPresentacion'] !== NULL) {
            $reintegro_fechaPresentacion = date("d-m-Y", strtotime($resultado['reintegro_fechaPresentacion']));
            $resultado['reintegro_fechaPresentacion'] = $reintegro_fechaPresentacion;
        } else {
            $resultado['reintegro_fechaPresentacion'] = NULL;
        }

        if ($resultado['reintegro_fechaAuditado'] !== NULL) {
            $reintegro_fechaAuditado = date("d-m-Y", strtotime($resultado['reintegro_fechaAuditado']));
            $resultado['reintegro_fechaAuditado'] = $reintegro_fechaAuditado;
        } else {
            $resultado['reintegro_fechaAuditado'] = NULL;
        }
        
        if ($resultado['reintegro_fechaPago'] !== NULL) {
            $reintegro_fechaPago = date("d-m-Y", strtotime($resultado['reintegro_fechaPago']));
            $resultado['reintegro_fechaPago'] = $reintegro_fechaPago;
        } else {
            $resultado['reintegro_fechaPago'] = NULL;
        }
        
        return $resultado;       
    }

    // Método para mostrar la informacion general del Caso que aparece en la cabecera
    public static function buscarDatosGeneralesCaso($caso_id){
        
        DB::query("SELECT caso_id,
                          caso_numero,
                          caso_fechaSiniestro,
                          caso_beneficiarioNombre,
                          caso_numeroVoucher,
                          product_name as caso_productoNombre,
                          caso_agencia,
                          pais_nombreEspanol as caso_paisSiniestro                       
                    FROM casos
                        LEFT JOIN product ON product_id_interno = caso_producto_id
                        LEFT JOIN paises ON pais_id = caso_pais_id
                    WHERE caso_id = :caso_id");
        
        DB::bind(':caso_id', "$caso_id");
        
        $resultado = DB::resultado();

        //Conversiones de la fecha de ANSI a normal para Datepicker
        if ($resultado['caso_fechaSiniestro'] !== NULL) {
            $caso_fechaSiniestro = date("d-m-Y", strtotime($resultado['caso_fechaSiniestro']));
            $resultado['caso_fechaSiniestro'] = $caso_fechaSiniestro;
        } else {
            $resultado['caso_fechaSiniestro'] = NULL;
        }

        return $resultado;        
    }

    // Método para listar los items de un reintegro
    public static function listarItemsPorReintegro($reintegro_id) {
        
        DB::query("SELECT reintegroItem_id,
                          riMov_riEstado_id,
                          riEstado_nombre,
                          riEstado_sector,
                          reintegroItem_fecha, 
                          riConcepto_nombre,
                          reintegroItem_importeOrigen,
                          moneda_nombre,
                          reintegroItem_monedaTC,
                          reintegroItem_importeUSD,
                          riMov_importeAprobadoUSD,
                          usuario_usuario
                   FROM reintegros_items
                        LEFT JOIN (select riMov_id, riMov_reintegroItem_id, riMov_riEstado_id, riMov_importeAprobadoUSD
                                   from ri_movimientos
                                   where riMov_id IN (SELECT max(riMov_id) FROM ri_movimientos GROUP BY riMov_reintegroItem_id)) 
                                  AS riMov ON riMov.riMov_reintegroItem_id = reintegroItem_id
                        LEFT JOIN ri_estados ON riEstado_id = riMov.riMov_riEstado_id
                        LEFT JOIN ri_conceptos ON riConcepto_id = reintegroItem_concepto_id
                        LEFT JOIN monedas ON moneda_id = reintegroItem_moneda_id
                        LEFT JOIN usuarios ON usuario_id = reintegroItem_usuario_id                       
                   WHERE reintegroItem_reintegro_id = :reintegro_id
                   ORDER BY reintegroItem_fecha");
                        
        DB::bind(':reintegro_id', $reintegro_id);

        return DB::resultados();
    }
    
    // Método para mostrar la información de un item
    public static function buscarItemPorId($reintegroItem_id) {
        
        DB::query("SELECT reintegroItem_id,
                          reintegroItem_concepto_id,
                          reintegroItem_importeOrigen,
                          reintegroItem_moneda_id,
                          reintegroItem_importeUSD,
                          reintegroItem_monedaTC,
                          reintegroItem_importeAprobadoUSD,
                          reintegroItem_observaciones,
                          reintegroItem_estado_id
                    FROM reintegros_items 
                    WHERE reintegroItem_id = :reintegroItem_id");
        
        DB::bind(':reintegroItem_id', $reintegroItem_id);
        
        $resultado = DB::resultado();       
        return $resultado;  
        
    }

    // Método para mostrar la informacion de ITEM de Reintegro pendiente de autorizacion
    public static function info_ri_pendiente_autorizar($reintegroItem_id) {
        
        DB::query("SELECT reintegroItem_id,
                          riMov_riEstado_id,
                          riEstado_nombre,
                          reintegroItem_importeUSD,
                          riMov_importeAprobadoUSD
                   FROM reintegros_items
                        LEFT JOIN (select riMov_id, riMov_reintegroItem_id, riMov_riEstado_id, riMov_importeAprobadoUSD
                                   from ri_movimientos
                                   where riMov_id IN (SELECT max(riMov_id) FROM ri_movimientos GROUP BY riMov_reintegroItem_id)) 
                                  AS riMov ON riMov.riMov_reintegroItem_id = reintegroItem_id
                        LEFT JOIN ri_estados ON riEstado_id = riMov.riMov_riEstado_id
                   WHERE reintegroItem_id = :reintegroItem_id");
                        
        DB::bind(':reintegroItem_id', $reintegroItem_id);

        return DB::resultado();
    }

    // Método para listar los tipos de documento para los datos bancarios de reintegro
    public static function listar_documentoTipos_alta(){
        
        DB::query("SELECT tipoDocumento_id, tipoDocumento_nombre
                   FROM tipos_documentos");
        
        return DB::resultados();
    }

    // Método para listar los tipos de documento en formulario de modificación
    public static function listar_documentoTipos_modificacion($reintegro_id){
        
        DB::query("SELECT tipoDocumento_id, tipoDocumento_nombre
                   FROM tipos_documentos
                        INNER JOIN reintegros ON reintegro_documentoTipo_id = tipoDocumento_id
                   WHERE reintegro_id = :reintegro_id
                   UNION
                   SELECT tipoDocumento_id, tipoDocumento_nombre
                   FROM tipos_documentos");
        
        DB::bind(':reintegro_id', $reintegro_id);
        
        return DB::resultados();
    }
    
    // Método para el Select - Lista los tipos de referencia para datos bancarios de reintegro
    public static function listar_referenciaTipos_alta(){
        
        DB::query("SELECT re_tipoReferencia_id, re_tipoReferencia_nombre
                   FROM re_tipos_referencia");
        
        return DB::resultados();
    }

    // 
    public static function listar_referenciaTipos_modificacion($reintegro_id){
        
        DB::query("SELECT re_tipoReferencia_id, re_tipoReferencia_nombre
                   FROM re_tipos_referencia
                        LEFT JOIN reintegros ON reintegro_referenciaTipo_id = re_tipoReferencia_id
                   WHERE reintegro_id = :reintegro_id
                   UNION         
                   SELECT re_tipoReferencia_id, re_tipoReferencia_nombre
                   FROM re_tipos_referencia
                   WHERE re_tipoReferencia_id <> 0");
        
        DB::bind(':reintegro_id', $reintegro_id);
        
        return DB::resultados();
    }

    // Método para el Select - Lista los tipos de referencia para datos bancarios de reintegro
    public static function listar_avisoTransTipos_alta(){
        
        DB::query("SELECT re_tipoAvisoTrans_id, re_tipoAvisoTrans_nombre
                   FROM re_tipos_avisotransferencia");
        
        return DB::resultados();
    }

    //
    public static function listar_avisoTransTipos_modificacion($reintegro_id){
        
        DB::query("SELECT re_tipoAvisoTrans_id, re_tipoAvisoTrans_nombre
                   FROM re_tipos_avisotransferencia
                        LEFT JOIN reintegros ON reintegro_avisoTransTipo_id = re_tipoAvisoTrans_id
                   WHERE reintegro_id = :reintegro_id
                   UNION
                   SELECT re_tipoAvisoTrans_id, re_tipoAvisoTrans_nombre
                   FROM re_tipos_avisotransferencia
                   WHERE re_tipoAvisoTrans_id <> 0");
        
        DB::bind(':reintegro_id', $reintegro_id);
        
        return DB::resultados();
    }
    
    // Método para el Select - Lista los conceptos de items de reintegro en formulario ALTA ITEM
    public static function listar_riConceptos_alta(){
        
        DB::query("SELECT riConcepto_id, riConcepto_nombre
                   FROM ri_conceptos
                   ORDER BY riConcepto_id");
        
        return DB::resultados();
    }

    // Método para el Select - Lista los conceptos de items de reintegro en formulario MODIFICAR ITEM
    public static function listar_riConceptos_modificacion($reintegroItem_id){
        
        DB::query("SELECT riConcepto_id, riConcepto_nombre
                   FROM ri_conceptos
                        INNER JOIN reintegros_items ON reintegroItem_concepto_id = riConcepto_id
                   WHERE reintegroItem_id = :reintegroItem_id
                   UNION
                   SELECT riConcepto_id, riConcepto_nombre
                   FROM ri_conceptos");
        
        DB::bind(':reintegroItem_id', $reintegroItem_id);
        
        return DB::resultados();
    }

    // Método para el Select - Lista las monedas en formulario ALTA ITEM
    public static function listar_riMonedas_alta() {
        
        DB::query("SELECT moneda_id, 
                          moneda_nombre 
                   FROM monedas
                   ORDER BY moneda_nombre");

        return DB::resultados();
    }

    // Método para el Select - Lista las monedas en formulario MODIFICAR ITEM
    public static function listar_riMonedas_modificacion($reintegroItem_id) {
        
        DB::query("SELECT moneda_id, moneda_nombre 
                   FROM monedas 
                        INNER JOIN reintegros_items ON reintegroItem_moneda_id = moneda_id
                   WHERE reintegroItem_id = :reintegroItem_id
                   UNION
                   SELECT moneda_id, moneda_nombre 
                   FROM monedas");
        
        DB::bind(':reintegroItem_id', $reintegroItem_id);

        return DB::resultados();
    }
    
    // Método para el Select - Lista los ESTADOS de LOG para el modal de autorizacion de ITEMS de Reintegro
    // Logica segun workflow
    public static function listar_movEstados_alta($ri_estado_id_au){
        
        if ($ri_estado_id_au == 1) { 
            // 1- Item Ingresado 
            // 2- Item Aprobado Autorizacion 
            // 3- Item Aprobado Parcial Autorizacion
            // 4- Item Rechazado Autorizacion
            $where = 'riEstado_id = 2 OR riEstado_id = 3 OR riEstado_id = 4';
        } else if ($ri_estado_id_au == 2 || $ri_estado_id_au == 3) { 
            // 5- Item Aprobado Auditoria
            // 6- Item Rechazado Auditoria
            $where = 'riEstado_id = 5 OR riEstado_id = 6';
        } else if ($ri_estado_id_au == 5) { 
            // 7- Item Pagado
            $where = 'riEstado_id = 7'; 
        }
        
        DB::query("SELECT riEstado_id, riEstado_nombre
                   FROM ri_estados
                   WHERE " . $where . "
                   ORDER BY riEstado_id");
        
        return DB::resultados();
    }
    
    // Lista los estados de reintegro para Reporte de Reintegros. 
    public static function listar_estadosReintegro(){
        
        DB::query("SELECT reintegroEstado_id, reintegroEstado_nombre
                   FROM reintegros_estados");
        
        return DB::resultados();
    }

    // Lista las formas de pago para Reporte de Reintegros. 
    public static function listar_formasPago(){
        
        DB::query("SELECT formaPago_id, formaPago_nombre
                   FROM formas_pagos");
        
        return DB::resultados();
    }
    
    // Método para el Select - Lista los tipos de reintegros en formulario MODIFICACION
    public static function chequeoItemsPendientes($reintegro_id){
        
        //query para chequear que los items del reintegro estan todos aprobados y pagados o rechazados
        DB::query("SELECT count(reintegroItem_id) as cantItemsPendientes
                   FROM reintegros_items
                        INNER JOIN (select riMov_id, riMov_reintegroItem_id, riMov_riEstado_id
                                    from ri_movimientos
                                    where riMov_id IN (SELECT max(riMov_id) FROM ri_movimientos GROUP BY riMov_reintegroItem_id))
                           AS riMov ON riMov.riMov_reintegroItem_id = reintegroItem_id
                   WHERE reintegroItem_reintegro_id = :reintegro_id
                   AND (riMov_riEstado_id <> 7 and riMov_riEstado_id <> 4 and riMov_riEstado_id <> 6)");
        
        DB::bind(':reintegro_id', $reintegro_id);
        
        return DB::resultado();
    }
    
    // Método para listar los movimientos del item de reintegro
    public static function listar_mov_ri($reintegroItem_id) {
        
        DB::query("SELECT riMov_id,
                          riMov_fechaEvento as fechaEvento,
                          usuario_nombre as usuarioNombreMov, 
                          usuario_apellido as usuarioApellidoMov,
                          riMov_riEstado_id as estadoMovId,
                          riEstado_descripcion as estadoMovDesc, 
                          riMov_observaciones as observacionesMov,
                          riMov_importeAprobadoUSD as importeAprobadoUSD,
                          DATE_FORMAT(riMov_fechaPago, '%d-%m-%Y') as fechaPago,
                          formaPago_nombre as formaPago
                   FROM ri_movimientos
                        LEFT JOIN usuarios on usuario_id = riMov_usuario_id
                        LEFT JOIN ri_estados on riEstado_id = riMov_riEstado_id
                        LEFT JOIN formas_pagos on formaPago_id = riMov_formaPago_id
                   WHERE riMov_reintegroItem_id = :reintegroItem_id
                   ORDER BY riMov_fechaEvento");
                        
        DB::bind(':reintegroItem_id', $reintegroItem_id);
        
        return DB::resultados();;
    }

    // Método para contar la cantidad de registros en el log consultados
    public static function contar_mov_ri($reintegroItem_id){
        
        DB::query("SELECT count(riMov_reintegroItem_id) as riMov_cantidad
                    FROM ri_movimientos
                    WHERE riMov_reintegroItem_id = :reintegroItem_id");
        
        DB::bind(':reintegroItem_id', $reintegroItem_id);
        
        return DB::resultado();
    }
    
    // Método para validar los estos de los REI
    // Comprueba en el Array si hay REI con estado distinto a: Aprobado (Id. 2), Aprobado Parcial (Id. 3) o Rechazado (Id. 4)
    public static function valida_estado_items($reintegro_id) {
        
        DB::query("SELECT reintegroItem_estado_id 
                    FROM reintegros_items
                    WHERE reintegroItem_reintegro_id = :reintegro_id");
                    
        DB::bind(':reintegro_id', $reintegro_id);
        
        $resultados = DB::resultados();

        foreach ($resultados as $resultado) {

            if ($resultado['reintegroItem_estado_id'] != 2 && $resultado['reintegroItem_estado_id'] != 3 && $resultado['reintegroItem_estado_id'] != 4) {

                return false;

            }

        }

    }

    // Método para validar estados Rechazado de REI
    // Comprueba en el Array si hay REI con estado distinto a: Rechazado (Id. 4)
    public static function valida_estado_rechazado($reintegro_id) {

        DB::query("SELECT reintegroItem_estado_id
                    FROM reintegros_items
                    WHERE reintegroItem_reintegro_id = :reintegro_id");

        DB::bind(':reintegro_id', $reintegro_id);

        $resultados = DB::resultados();

        foreach ($resultados as $resultado) {

            if ($resultado['reintegroItem_estado_id'] != 4) {

                return false;

            }

        }
        
        return true;

    }

    // Método para listar los casos aplicando el filtro de la grilla Agenda Reintegros
    public static function listar_filtrado_agenda($caso_numero_desde_b,
                                                  $caso_numero_hasta_b,
                                                  $reintegro_estado_id_b,
                                                  $caso_usuarioAsignado_id_b) {
        
        If ($caso_numero_desde_b == '') {
            $caso_numero_desde_b = NULL;
        }
        If ($caso_numero_hasta_b == '') {
            $caso_numero_hasta_b = NULL;
        }
        If ($reintegro_estado_id_b == '') {
            $reintegro_estado_id_b = NULL;
        }
        If ($caso_usuarioAsignado_id_b == '') {
            $caso_usuarioAsignado_id_b = NULL;
        }        
        
                    
        DB::query("SELECT reintegro_id, 
                          casos.caso_id,
                          casos.caso_numero,
                          reintegro_fechaIngresoSistema,
                          casos.caso_beneficiarioNombre, 
                          reintegros_estados.reintegroEstado_nombre,
                          usuarios.usuario_nombre AS asignado_nombre,
                          usuarios.usuario_apellido AS asignado_apellido,
                          reintegros_agenda.reintegroAgenda_id
                    FROM reintegros
                        LEFT JOIN casos ON caso_id = reintegro_caso_id
                        LEFT JOIN reintegros_estados ON reintegroEstado_id = reintegro_reintegroEstado_id
                        LEFT JOIN reintegros_agenda ON reintegroAgenda_reintegro_id = reintegro_id
                        LEFT JOIN usuarios ON usuario_id = reintegroAgenda_usuarioAsignado_id
                    WHERE caso_numero between COALESCE(:caso_numero_desde,caso_numero) and COALESCE(:caso_numero_hasta,caso_numero)
                      AND reintegro_reintegroEstado_id = COALESCE(:reintegro_reintegroEstado_id,reintegro_reintegroEstado_id)
                      AND (CASE WHEN :reintegro_usuarioAsignado_id is not null 
                                THEN (reintegroAgenda_usuarioAsignado_id = :reintegro_usuarioAsignado_id)
                                ELSE (caso_id = caso_id) 
                           END)
                    ORDER BY reintegro_fechaIngresoSistema ASC
                    LIMIT 600");
                    
        DB::bind(':caso_numero_desde', $caso_numero_desde_b);
        DB::bind(':caso_numero_hasta', $caso_numero_hasta_b);
        DB::bind(':reintegro_reintegroEstado_id', $reintegro_estado_id_b);
        DB::bind(':reintegro_usuarioAsignado_id', $caso_usuarioAsignado_id_b);

        return DB::resultados();
    }
    
    // Método para listar los casos aplicando el filtro de la grilla Agenda Reintegros
    public static function listar_filtrado_contar_agenda($caso_numero_desde_b,
                                                        $caso_numero_hasta_b,
                                                        $reintegro_estado_id_b,
                                                        $caso_usuarioAsignado_id_b) {
        
        If ($caso_numero_desde_b == '') {
            $caso_numero_desde_b = NULL;
        }
        If ($caso_numero_hasta_b == '') {
            $caso_numero_hasta_b = NULL;
        }
        If ($reintegro_estado_id_b == '') {
            $reintegro_estado_id_b = NULL;
        }
        If ($caso_usuarioAsignado_id_b == '') {
            $caso_usuarioAsignado_id_b = NULL;
        }
                    
        DB::query("SELECT COUNT(*) AS registros
                    FROM reintegros
                        LEFT JOIN casos ON caso_id = reintegro_caso_id
                        LEFT JOIN reintegros_estados ON reintegroEstado_id = reintegro_reintegroEstado_id
                        LEFT JOIN reintegros_agenda ON reintegroAgenda_reintegro_id = reintegro_id
                        LEFT JOIN usuarios ON usuario_id = reintegroAgenda_usuarioAsignado_id
                    WHERE caso_numero between COALESCE(:caso_numero_desde,caso_numero) AND COALESCE(:caso_numero_hasta,caso_numero)
                      AND reintegro_reintegroEstado_id = COALESCE(:reintegro_reintegroEstado_id,reintegro_reintegroEstado_id)
                      AND (CASE WHEN :reintegro_usuarioAsignado_id IS NOT NULL
                                THEN (reintegroAgenda_usuarioAsignado_id = :reintegro_usuarioAsignado_id)
                                ELSE (caso_id = caso_id) 
                           END)");
                    
        DB::bind(':caso_numero_desde', $caso_numero_desde_b);
        DB::bind(':caso_numero_hasta', $caso_numero_hasta_b);
        DB::bind(':reintegro_reintegroEstado_id', $reintegro_estado_id_b);
        DB::bind(':reintegro_usuarioAsignado_id', $caso_usuarioAsignado_id_b);

        return DB::resultado();
    }
    
    // Devuelve el 'estado_id' de un Reintegro
    public static function obtener_estado_id($reintegro_id) {

        DB::query("SELECT reintegro_reintegroEstado_id AS estado
                    FROM reintegros
                    WHERE reintegro_id = :reintegro_id");
                    
        DB::bind(':reintegro_id', $reintegro_id);
        
        $resultado = DB::resultado();

        return $resultado['estado'];

    }

    // Método para validar si un Reintegro tiene Items asociados
    public static function tiene_rei($reintegro_id) {

        DB::query("SELECT COUNT(reintegroItem_id) AS cantidad
                    FROM reintegros_items
                    WHERE reintegroItem_reintegro_id = :reintegro_id");
                    
        DB::bind(':reintegro_id', $reintegro_id);
        
        $resultado = DB::resultado();

        return $resultado['cantidad'];

    }

    // Devuelve el 'aprobado total' de todo el Reintegro
    public static function total_aprobado($reintegro_id) {

        DB::query("SELECT reintegro_id, SUM(reintegros_items.reintegroItem_importeAprobadoUSD) as total_aprobado
                    FROM reintegros
                        LEFT JOIN reintegros_items ON reintegros_items.reintegroItem_reintegro_id = reintegros.reintegro_id
                    WHERE reintegro_id = :reintegro_id
                    GROUP BY reintegro_id");

        DB::bind(':reintegro_id', $reintegro_id);

        $resultado = DB::resultado();

        return $resultado['total_aprobado'];

    }




    # Métodos ABM / Acciones #


    // Método para insertar un reintegro
    public static function insertar($reintegro_fechaPresentacion_n,
                                    $reintegro_observaciones_n,
                                    $caso_id,
                                    $sesion_usuario_id) {   
        
        // Estado automatico de reintegro al momento del ingreso
        $reintegro_reintegroEstado_id_n = 1; //En proceso

        // Formateo de Fechas para el Insert
        if (!empty($reintegro_fechaPresentacion_n)) {
            $reintegro_fechaPresentacion_n = date('Y-m-d', strtotime($reintegro_fechaPresentacion_n));
        } else {
            $reintegro_fechaPresentacion_n = NULL;
        }

        // Select para info del campo 'email_texto'
        DB::query("SELECT caso_beneficiarioNombre,
                          caso_numeroVoucher,
                          caso_numero                      
                    FROM casos
                    WHERE caso_id = :caso_id");
        
        DB::bind(':caso_id', "$caso_id");
        
        $resultado = DB::resultado();
        
        // Arma el campo 'email_texto' para completar en datos bancarios
        $nombre_pax = $resultado['caso_beneficiarioNombre'];
        $voucher = $resultado['caso_numeroVoucher'];
        $caso = $resultado['caso_numero'];

        $email_texto = $nombre_pax;
        $email_texto .= ' - ' . $voucher;
        $email_texto .= ' - #' . $caso;
        
        // Insert en Reintegros
        DB::query("INSERT INTO reintegros (reintegro_caso_id,
                                           reintegro_reintegroEstado_id,
                                           reintegro_fechaPresentacion,           
                                           reintegro_observaciones,
                                           reintegro_usuario_id,
                                           reintegro_emailTexto)
                                    VALUES (:reintegro_caso_id,
                                            :reintegro_reintegroEstado_id_n,
                                            :reintegro_fechaPresentacion_n,
                                            :reintegro_observaciones_n,
                                            :sesion_usuario_id,
                                            :reintegro_emailTexto)");
        
        
        DB::bind(':reintegro_caso_id', $caso_id);
        DB::bind(':reintegro_reintegroEstado_id_n', $reintegro_reintegroEstado_id_n);
        
        if (!empty($reintegro_fechaPresentacion_n)) {
            DB::bind(':reintegro_fechaPresentacion_n', "$reintegro_fechaPresentacion_n");
        } else {
            DB::bind(':reintegro_fechaPresentacion_n', $reintegro_fechaPresentacion_n);
        }
        
        DB::bind(':reintegro_observaciones_n', "$reintegro_observaciones_n");
        DB::bind(':sesion_usuario_id', $sesion_usuario_id);
        DB::bind(':reintegro_emailTexto', $email_texto);
        
        DB::execute();
        
        return DB::lastInsertId();
    }
    
    // Método para actualizar un reintegro
    public static function actualizar($reintegro_fechaPresentacion_m,
                                      $reintegro_observaciones_m,
                                      $reintegro_id_m,
                                      $sesion_usuario_id) {
        
        // Formateo de Fechas para el Insert        
        if (!empty($reintegro_fechaPresentacion_m)) {
            $reintegro_fechaPresentacion_m = date('Y-m-d', strtotime($reintegro_fechaPresentacion_m));
        } else {
            $reintegro_fechaPresentacion_m = NULL;
        }
        
        
        DB::query("UPDATE reintegros SET
                        reintegro_fechaPresentacion = :reintegro_fechaPresentacion_m,
                        reintegro_observaciones = :reintegro_observaciones_m
                   WHERE reintegro_id = :reintegro_id");
        
        if (!empty($reintegro_fechaPresentacion_m)) {
            DB::bind(':reintegro_fechaPresentacion_m', "$reintegro_fechaPresentacion_m");
        } else {
            DB::bind(':reintegro_fechaPresentacion_m', $reintegro_fechaPresentacion_m);
        }
        DB::bind(':reintegro_observaciones_m', "$reintegro_observaciones_m");
        DB::bind(':reintegro_id', $reintegro_id_m);

        DB::execute();
        
        $mensaje = "El reintegro fue actualizado con éxito";
        return $mensaje;
    }

    // Método para actualizar datos bancarios de un reintegro
    public static function actualizar_datosBancarios($reintegro_CBUcuenta_m,
                                                    $reintegro_CBUalias_m,
                                                    $reintegro_denominacion_m,
                                                    $reintegro_documentoTipo_id_m,
                                                    $reintegro_beneficiarioDocumento_m,
                                                    $reintegro_referenciaTipo_id_m,
                                                    $reintegro_referencia_m,
                                                    $reintegro_avisoTransTipo_id_m,
                                                    $reintegro_emailDestinatario_m,
                                                    $reintegro_emailTexto_m,
                                                    $reintegro_compania_m,
                                                    $reintegro_codigoArea_m,
                                                    $reintegro_telefono_m,
                                                    $reintegro_id_db,
                                                    $reintegro_banco_m,
                                                    $reintegro_digito_verificacion_titular_m,
                                                    $reintegro_mail_titular_m,
                                                    $reintegro_tipo_cuenta_m,
                                                    $reintegro_direccion_titular_m,
                                                    $reintegro_ciudad_m) {
        
        // Hardcode del TIPO DE DOCUMENTO = 4 (CUIT)
        $reintegro_documentoTipo_id_m = 4;
        // Hardcode del TIPO DE REFERENCIA = 1 (VARIOS)
        $reintegro_referenciaTipo_id_m = 1;
        // Hardcode del REFERENCIA = REEMBOLSO
        $reintegro_referencia_m = 'REEMBOLSO';
        // Hardcode del TIPO AVISO TRANSFERENCIA = 1 (EMAIL)
        $reintegro_avisoTransTipo_id_m = 1;
        // Hardcode del EMAIL DESTINATARIO
        $reintegro_emailDestinatario_m = 'pilar.lozano@coris.com.ar; angeles.sosa@coris.com.ar';

        DB::query("UPDATE reintegros SET
                            reintegro_CBUcuenta = :reintegro_CBUcuenta_m,
                            reintegro_CBUalias = :reintegro_CBUalias_m,
                            reintegro_denominacion = :reintegro_denominacion_m,
                            reintegro_documentoTipo_id = :reintegro_documentoTipo_id_m,
                            reintegro_beneficiarioDocumento = :reintegro_beneficiarioDocumento_m,
                            reintegro_referenciaTipo_id = :reintegro_referenciaTipo_id_m,
                            reintegro_referencia = :reintegro_referencia_m,
                            reintegro_avisoTransTipo_id = :reintegro_avisoTransTipo_id_m,
                            reintegro_emailDestinatario = :reintegro_emailDestinatario_m,
                            reintegro_emailTexto = :reintegro_emailTexto_m,
                            reintegro_compania = :reintegro_compania_m,
                            reintegro_codigoArea = :reintegro_codigoArea_m,
                            reintegro_telefono = :reintegro_telefono_m,
                            reintegro_banco  = :reintegro_banco_m,
                            reintegro_digito_verificacion_titular = :reintegro_digito_verificacion_titular_m,
                            reintegro_mail_titular = :reintegro_mail_titular_m,
                            reintegro_tipo_cuenta = :reintegro_tipo_cuenta_m,
                            reintegro_direccion_titular = :reintegro_direccion_titular_m,
                            reintegro_ciudad = :reintegro_ciudad_m
                   WHERE reintegro_id = :reintegro_id");

        // Datos bancarios
        DB::bind(':reintegro_CBUcuenta_m', "$reintegro_CBUcuenta_m");
        DB::bind(':reintegro_CBUalias_m', "$reintegro_CBUalias_m");
        DB::bind(':reintegro_denominacion_m', "$reintegro_denominacion_m");
        DB::bind(':reintegro_documentoTipo_id_m', $reintegro_documentoTipo_id_m);
        DB::bind(':reintegro_beneficiarioDocumento_m', "$reintegro_beneficiarioDocumento_m");
        DB::bind(':reintegro_referenciaTipo_id_m', $reintegro_referenciaTipo_id_m);
        DB::bind(':reintegro_referencia_m', "$reintegro_referencia_m");
        DB::bind(':reintegro_avisoTransTipo_id_m', $reintegro_avisoTransTipo_id_m);
        DB::bind(':reintegro_emailDestinatario_m', "$reintegro_emailDestinatario_m");
        DB::bind(':reintegro_emailTexto_m', "$reintegro_emailTexto_m");
        DB::bind(':reintegro_compania_m', "$reintegro_compania_m");
        DB::bind(':reintegro_codigoArea_m', "$reintegro_codigoArea_m");
        DB::bind(':reintegro_telefono_m', "$reintegro_telefono_m");

        DB::bind(':reintegro_banco_m', "$reintegro_banco_m");
        DB::bind(':reintegro_digito_verificacion_titular_m', "$reintegro_digito_verificacion_titular_m");
        DB::bind(':reintegro_mail_titular_m', "$reintegro_mail_titular_m");
        DB::bind(':reintegro_tipo_cuenta_m', "$reintegro_tipo_cuenta_m");
        DB::bind(':reintegro_direccion_titular_m', "$reintegro_direccion_titular_m");
        DB::bind(':reintegro_ciudad_m', "$reintegro_ciudad_m"); 
              
        DB::bind(':reintegro_id', $reintegro_id_db);

        DB::execute();
        
        $mensaje = "El reintegro fue actualizado con éxito";
        return $mensaje;
    }
    
    // Método para insertar el ITEM de REINTEGRO
    public static function insertar_ri($reintegroItem_reintegro_id_n,
                                       $reintegroItem_concepto_id_n,
                                       $reintegroItem_importeOrigen_n,
                                       $reintegroItem_moneda_id_n,
                                       $reintegroItem_monedaTC_n,
                                       $reintegroItem_importeUSD_n,
                                       $reintegroItem_observaciones_n,
                                       $sesion_usuario_id) {
        
        // Consulta el Tipo de Cambio
        $reintegroItem_monedaTC_n = Moneda::calculo_tc_live($reintegroItem_moneda_id_n);
        
        // Calcula el Servicio Presunto USD
        $reintegroItem_importeUSD_n = $reintegroItem_importeOrigen_n / $reintegroItem_monedaTC_n;

        // Primer estado del Item (id. 1)
        $reintegroItem_estado_id_n = 1;
                                        
        // INSERT INTO reintegros_items
        DB::query("INSERT INTO reintegros_items 
                                (reintegroItem_reintegro_id,
                                reintegroItem_concepto_id,       
                                reintegroItem_importeOrigen,
                                reintegroItem_moneda_id,
                                reintegroItem_monedaTC,
                                reintegroItem_importeUSD,
                                reintegroItem_observaciones,
                                reintegroItem_estado_id,
                                reintegroItem_usuario_id)
                        VALUES (:reintegroItem_reintegro_id_n,
                                :reintegroItem_concepto_id_n,
                                :reintegroItem_importeOrigen_n,
                                :reintegroItem_moneda_id_n,
                                :reintegroItem_monedaTC_n,
                                :reintegroItem_importeUSD_n,
                                :reintegroItem_observaciones_n,
                                :reintegroItem_estado_id_n,
                                :sesion_usuario_id)");
                
        
        DB::bind(':reintegroItem_reintegro_id_n', $reintegroItem_reintegro_id_n);
        DB::bind(':reintegroItem_concepto_id_n', $reintegroItem_concepto_id_n);
        DB::bind(':reintegroItem_importeOrigen_n', "$reintegroItem_importeOrigen_n");
        DB::bind(':reintegroItem_moneda_id_n', $reintegroItem_moneda_id_n);
        DB::bind(':reintegroItem_monedaTC_n', "$reintegroItem_monedaTC_n");
        DB::bind(':reintegroItem_importeUSD_n', "$reintegroItem_importeUSD_n");
        DB::bind(':reintegroItem_observaciones_n', "$reintegroItem_observaciones_n");
        DB::bind(':reintegroItem_estado_id_n', "$reintegroItem_estado_id_n");
        DB::bind(':sesion_usuario_id', $sesion_usuario_id);
                    
        DB::execute();
        
        $riMov_reintegroItem_id_n = DB::lastInsertId();
        

        // UPDATE reintegros
        // 
        // Al cargar el primer Item al Reintegro, cambia el estado de este a 'En Proceso' (Id. 2)
        $reintegro_estado_id = 2;    
        
                DB::query("UPDATE reintegros SET 
                                reintegro_reintegroEstado_id = :reintegro_reintegroEstado_id
                            WHERE reintegro_id = :reintegro_id");
                        
        DB::bind(':reintegro_reintegroEstado_id', $reintegro_estado_id);
        DB::bind(':reintegro_id', $reintegroItem_reintegro_id_n);

        DB::execute();

        // INSERT INTO insertar_ri_movimientos
        //         
        // Insert para cargar un nuevo registro en los movimientos del item
        $riMov_riEstado_id_n = 1; //Ingresado
        
        DB::query("INSERT INTO ri_movimientos (riMov_reintegroItem_id,
                                               riMov_usuario_id,
                                               riMov_riEstado_id)
                                       VALUES (:riMov_reintegroItem_id_n,
                                               :riMov_usuario_id_n,
                                               :riMov_riEstado_id_n)");

        DB::bind(':riMov_reintegroItem_id_n', $riMov_reintegroItem_id_n);
        DB::bind(':riMov_usuario_id_n', $sesion_usuario_id);
        DB::bind(':riMov_riEstado_id_n', $riMov_riEstado_id_n);
        
        DB::execute();
    }
    
    // Método para modificar el ITEM de REINTEGRO
    public static function actualizar_ri($reintegroItem_id,
                                         $reintegroItem_concepto_id,
                                         $reintegroItem_importeOrigen,
                                         $reintegroItem_moneda_id,
                                         $reintegroItem_monedaTC,
                                         $reintegroItem_importeUSD_n,
                                         $reintegroItem_observaciones,
                                         $sesion_usuario_id) {
    
        
        // Consulta el Tipo de Cambio
        $reintegroItem_monedaTC = Moneda::calculo_tc_live($reintegroItem_moneda_id);
        
        // Calcula el Servicio Presunto USD
        $reintegroItem_importeUSD = $reintegroItem_importeOrigen / $reintegroItem_monedaTC;
        

        // Update reintegros_items
        DB::query("UPDATE reintegros_items SET
                        reintegroItem_concepto_id = :reintegroItem_concepto_id,
                        reintegroItem_importeOrigen = :reintegroItem_importeOrigen,
                        reintegroItem_moneda_id = :reintegroItem_moneda_id,
                        reintegroItem_monedaTC = :reintegroItem_monedaTC,
                        reintegroItem_importeUSD = :reintegroItem_importeUSD,
                        reintegroItem_observaciones = :reintegroItem_observaciones,
                        reintegroItem_usuario_id = :sesion_usuario_id
                    WHERE reintegroItem_id = :reintegroItem_id");

                        
        DB::bind(':reintegroItem_id', $reintegroItem_id);
        DB::bind(':reintegroItem_concepto_id', $reintegroItem_concepto_id);
        DB::bind(':reintegroItem_importeOrigen', "$reintegroItem_importeOrigen");
        DB::bind(':reintegroItem_moneda_id', $reintegroItem_moneda_id);
        DB::bind(':reintegroItem_monedaTC', "$reintegroItem_monedaTC");
        DB::bind(':reintegroItem_importeUSD', "$reintegroItem_importeUSD");
        DB::bind(':reintegroItem_observaciones', "$reintegroItem_observaciones");
        DB::bind(':sesion_usuario_id', $sesion_usuario_id);
                    
        DB::execute();
        
        //return DB::lastInsertId();
    }

    // LOGUEO: Método para insertar un registro en los movimientos de Reintegros
    public static function insertar_riMovimiento($riMov_ri_id,
                                                 $riMov_riEstado_id,
                                                 $riMov_fechaPago,
                                                 $riMov_formaPago_id,
                                                 $riMov_observaciones,
                                                 $riMov_usuario_id,
                                                 $riMov_importeAprobadoUSD) {
        
        try {
            DB::conecta_t();
            DB::beginTransaction_t();  // start Transaction
        
            // Formateo de Fechas para el Insert
            if (!empty($riMov_fechaPago)) {
                $riMov_fechaPago = date('Y-m-d', strtotime($riMov_fechaPago));
            } else {
                $riMov_fechaPago = NULL;
            }
            if (empty($riMov_formaPago_id)) {
                $riMov_formaPago_id = NULL;            
            }
            
            DB::query_t("INSERT INTO ri_movimientos (riMov_reintegroItem_id,
                                                riMov_riEstado_id,           
                                                riMov_fechaPago,
                                                riMov_formaPago_id,
                                                riMov_observaciones,
                                                riMov_usuario_id,
                                                riMov_importeAprobadoUSD)
                                        VALUES (:riMov_reintegroItem_id,
                                                :riMov_riEstado_id,
                                                :riMov_fechaPago,
                                                :riMov_formaPago_id,
                                                :riMov_observaciones,
                                                :riMov_usuario_id,
                                                :riMov_importeAprobadoUSD)");
            
            DB::bind(':riMov_reintegroItem_id', $riMov_ri_id);
            DB::bind(':riMov_riEstado_id', $riMov_riEstado_id);
            if (!empty($riMov_fechaPago)) {
                DB::bind(':riMov_fechaPago', "$riMov_fechaPago");
            } else {
                DB::bind(':riMov_fechaPago', $riMov_fechaPago);
            }
            DB::bind(':riMov_formaPago_id', $riMov_formaPago_id);
            DB::bind(':riMov_observaciones', "$riMov_observaciones");
            DB::bind(':riMov_usuario_id', $riMov_usuario_id);
            DB::bind(':riMov_importeAprobadoUSD', "$riMov_importeAprobadoUSD");
            
            DB::execute();


            /*
            |   ESTADOS RECHAZADO - ID: 4
            */
            if (($riMov_riEstado_id == 4)) {

                /* 
                |   UPDATE en reintegros_items:
                |   Como se rechaza el item de reintegro, se toma solo el estado del movimiento, los importes se ponen en 0         
                */

                $reintegroItem_importeAprobadoUSD = 0;

                DB::query_t("UPDATE reintegros_items SET 
                                    reintegroItem_estado_id = :reintegroItem_estado_id,
                                    reintegroItem_importeAprobadoUSD = :reintegroItem_importeAprobadoUSD
                            WHERE reintegroItem_id = :reintegroItem_id");

                DB::bind(':reintegroItem_estado_id', $riMov_riEstado_id);
                DB::bind(':reintegroItem_importeAprobadoUSD', "$reintegroItem_importeAprobadoUSD");
                DB::bind(':reintegroItem_id', $riMov_ri_id);

                DB::execute();

            /*
            |   ESTADOS APROBADO - ID: 2 - 3
            */  
            } else if (($riMov_riEstado_id == 2) || ($riMov_riEstado_id == 3)) {

                /* 
                |   UPDATE en reintegros_items:
                |   Como se aprueba el item de reintegro, se toman los importes + estado del movimiento      
                */

                DB::query_t("UPDATE reintegros_items SET 
                                    reintegroItem_estado_id = :reintegroItem_estado_id,
                                    reintegroItem_importeAprobadoUSD = :reintegroItem_importeAprobadoUSD
                            WHERE reintegroItem_id = :reintegroItem_id");

                DB::bind(':reintegroItem_estado_id', $riMov_riEstado_id);
                DB::bind(':reintegroItem_importeAprobadoUSD', "$riMov_importeAprobadoUSD");
                DB::bind(':reintegroItem_id', $riMov_ri_id);

                DB::execute();
            
            }

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
    
    // Método para Auditar el Reintegro
    public static function auditar_reintegro($reintegro_id, $auditoria_tipo) {
        
        $reint_estado           = Self::obtener_estado_id($reintegro_id);
        $rei_rechazados = Self::valida_estado_rechazado($reintegro_id);

        /* 
            Tipo = Aprobado (Id. 3) -> Realiza el Update si se cumple:
                1- El estado del reintegro es En Proceso (Id. 2)
                2- Al menos uno de sus REI debe estar Aprobado (Id. 2 o 3)
                3- El total de aprobado del reintegro es menor a USD300 y el usuario tiene permiso reintegro_auditoria
                4- Si es superior a USD300, el usuario debe tener permiso reintegro_superior_auditoria
        */
        if ($auditoria_tipo == 1) {

            $reint_importe_aprobado = Self::total_aprobado($reintegro_id);

            if ($reint_estado == 2 && $rei_rechazados == false && (($reint_importe_aprobado < 300 && Usuario::puede("reintegro_auditoria") == 1) || Usuario::puede("reintegro_superior_auditoria") == 1)) {
            
                $validado               = true;
                $reintegro_estado_id_m  = 3;

            } else {

                $validado               = false;

            }

        /* 
            Tipo = Rechazado (Id. 8) -> Realiza el Update si se cumple:
                1- El estado del reintegro es Pend. Documentacion (Id. 1) o En Proceso (Id. 2)
                2- Puede no tener REI
                3- Si tiene REI, todos deben estar Rechazados
        */
        } else if ($auditoria_tipo == 2) {

            $cant_items     = Self::tiene_rei($reintegro_id);

            if (($reint_estado == 1) || ($reint_estado == 2 && ($cant_items > 0 && $rei_rechazados == true))) {
            
                $validado               = true;
                $reintegro_estado_id_m  = 8;

            } else {

                $validado               = false;

            }

        }

        // Arriba se realizan las validaciones correspondientes a cada tipo de Auditoria (Aprobado o Rechazado) y eso define '$validado'         
        if ($validado) {
            
            $reintegro_fechaAuditado_m = date("Y-m-d H:i:s");

            DB::query("UPDATE reintegros SET
                            reintegro_reintegroEstado_id = :reintegro_estado_id_m,
                            reintegro_fechaAuditado = :reintegro_fechaAuditado_m
                        WHERE reintegro_id = :reintegro_id");

            DB::bind(':reintegro_estado_id_m', $reintegro_estado_id_m);
            DB::bind(':reintegro_fechaAuditado_m', $reintegro_fechaAuditado_m);
            DB::bind(':reintegro_id', $reintegro_id);

            DB::execute();

            return true;

        } else {

            return false;

        }

    }

    // Método para pasar un Reintegro a estado Retenido (Id. 7)
    public static function retener_reintegro($reintegro_id) {
        
        // Consulta el estado e importe total del reintegro
        DB::query("SELECT reintegro_id, reintegro_reintegroEstado_id as estado
                    FROM reintegros
                    WHERE reintegro_id = :reintegro_id");
                    
        DB::bind(':reintegro_id', $reintegro_id);
        
        $resultado = DB::resultado();

        // Update en tabla reintegros, lo hace solo si se cumple:
        // 1- El estado del reintegro es En Proceso (Id. 2)
        // 2- El usuario tiene permiso reintegros_retener
        if ((($resultado['estado']) == 2) && (Usuario::puede("reintegros_retener") == 1)) {
            
            $reintegro_estado_id_m = 7; // Retenido (Id. 7)

            DB::query("UPDATE reintegros SET
                            reintegro_reintegroEstado_id = :reintegro_estado_id_m
                    WHERE reintegro_id = :reintegro_id");

            DB::bind(':reintegro_estado_id_m', $reintegro_estado_id_m);
            DB::bind(':reintegro_id', $reintegro_id);

            DB::execute();

            return true;
        } else {
            return false;
        }
    }

    // Método para pasar un Reintegro Retenido a estado En Proceso (Id. 2)
    public static function liberar_reintegro($reintegro_id) {
        
        // Consulta el estado e importe total del reintegro
        DB::query("SELECT reintegro_id, reintegro_reintegroEstado_id as estado
                    FROM reintegros
                    WHERE reintegro_id = :reintegro_id");
                    
        DB::bind(':reintegro_id', $reintegro_id);
        
        $resultado = DB::resultado();

        // Update en tabla reintegros, lo hace solo si se cumple:
        // 1- El estado del reintegro es En Proceso (Id. 2)
        // 2- El usuario tiene permiso reintegros_retener
        if ((($resultado['estado']) == 7) && (Usuario::puede("reintegros_retener") == 1)) {
            
            $reintegro_estado_id_m = 2; // En Proceso (Id. 2)

            DB::query("UPDATE reintegros SET
                            reintegro_reintegroEstado_id = :reintegro_estado_id_m
                    WHERE reintegro_id = :reintegro_id");

            DB::bind(':reintegro_estado_id_m', $reintegro_estado_id_m);
            DB::bind(':reintegro_id', $reintegro_id);

            DB::execute();

            return true;
        } else {
            return false;
        }
    }

    // Método para pasar un Reintegro a estado Auditado (Id. 3) 
    public static function forma_pago($reintegro_formaPago_id, 
                                        $reintegro_importe_usd_v,                                    
                                        $reintegro_monedaTC_m,
                                        $importe_total_ars_m,
                                        $reintegro_id) {
        
        // Calculo en Back-End para pasar de USD a ARS
        if ($reintegro_monedaTC_m > 0) {
            $importe_total_ars_m = $reintegro_importe_usd_v * $reintegro_monedaTC_m;
        }
        
        // Consulta el Estado del reintegro
        DB::query("SELECT reintegros.reintegro_reintegroEstado_id AS estado
                    FROM reintegros
                    WHERE reintegros.reintegro_id = :reintegro_id");
                    
        DB::bind(':reintegro_id', $reintegro_id);
        
        $estado_reint = DB::resultado();


        // Consulta el Cliente del Caso
        DB::query("SELECT casos.caso_cliente_id AS cliente
                    FROM reintegros
                        LEFT JOIN casos ON caso_id = reintegro_caso_id
                    WHERE reintegros.reintegro_id = :reintegro_id");
                    
        DB::bind(':reintegro_id', $reintegro_id);
        
        $cliente_caso = DB::resultado();

        /*  
        |   Si la forma de pago es: Tarjeta de Crédito - Nota de Crédito - Efectivo - Cheque
        |    1- Cambia el estado del Reintegro a Pendiente Pago (Id. 5)
        |    2- Manda un mail de aviso
        */
        if (($estado_reint['estado']) == 3 && (($reintegro_formaPago_id == 2) || ($reintegro_formaPago_id == 3) || ($reintegro_formaPago_id == 4) || ($reintegro_formaPago_id == 5))) {
            
            $reintegro_estado_id_m = 5;

            // Envio de correo
            if ($reintegro_formaPago_id == 4 || $reintegro_formaPago_id == 5) {
                Alerta::alerta_reintegros_pago(1);
            } else if ($reintegro_formaPago_id == 3) {
                Alerta::alerta_reintegros_pago(2);
            }
        /*
        |   Si la forma de pago es: transferencia bancaria y el Cliente del caso es AR
        |    1- Cambia el estado del Reintegro a Pend. Orden de Pago (Id. 4)
        */
        } else if ((($estado_reint['estado']) == 3) && ($reintegro_formaPago_id == 1)) {

            $reintegro_estado_id_m = 4;
        /*
        |   Si la forma de pago es: transferencia bancaria y el Cliente del Caso NO es AR
        |    1- Cambia el estado del Reintegro a Pendiente Pago (Id. 5)
        */
        } 
        // else if ((($estado_reint['estado']) == 3) && ($reintegro_formaPago_id == 1) && (($cliente_caso['cliente']) != 1)) {
        //     $reintegro_estado_id_m = 5;
        // }

        // Update en tabla reintegros 
        DB::query("UPDATE reintegros SET
                        reintegro_formaPago_id = :reintegro_formaPago_id,
                        reintegro_reintegroEstado_id = :reintegro_estado_id_m,
                        reintegro_monedaTC = :reintegro_monedaTC_m,
                        reintegro_importeARS = :importe_total_ars_m
                WHERE reintegro_id = :reintegro_id");

        DB::bind(':reintegro_formaPago_id', $reintegro_formaPago_id);
        DB::bind(':reintegro_estado_id_m', $reintegro_estado_id_m);
        DB::bind(':reintegro_monedaTC_m', $reintegro_monedaTC_m);
        DB::bind(':importe_total_ars_m', $importe_total_ars_m);
        DB::bind(':reintegro_id', $reintegro_id);

        DB::execute();
    }




    # Rollbacks #


    // Método para realizar el Rollback del Reintegro a Estado: Auditado (Id. 3)
    public static function rollback_reintegro($reintegro_id) {
        
        // Consulta el Estado ID del Reintegro
        $estado = Self::obtener_estado_id($reintegro_id);

        // Update en tabla reintegros, lo hace si cumple:
        // 1- Que el reintegro esté en estado Pendiente Pago (Id. 5)
        // 2- Que el usuario tenga permisos para hacerlo
        if ($estado == 5 && Usuario::puede("reintegro_rollback_auditado") == 1) {
            
            // Parámetros para el Update
            $reintegro_estado_id_m      = 3;
            $reintegro_formaPago_id_m   = 0;
            $reintegro_importeARS_m     = NULL;

            DB::query("UPDATE reintegros SET
                            reintegro_reintegroEstado_id = :reintegro_estado_id_m,
                            reintegro_formaPago_id = :reintegro_formaPago_id_m,
                            reintegro_importeARS = :reintegro_importeARS_m
                    WHERE reintegro_id = :reintegro_id");

            DB::bind(':reintegro_estado_id_m', $reintegro_estado_id_m);
            DB::bind(':reintegro_formaPago_id_m', $reintegro_formaPago_id_m);
            DB::bind(':reintegro_importeARS_m', $reintegro_importeARS_m);
            DB::bind(':reintegro_id', $reintegro_id);

            DB::execute();

            return true;

        } else {

            return false;

        }
    }

    // Método para realizar el Rollback del Reintegro a Estado: Pend. Documentacion (Id. 1)
    public static function rollback_reintegro_pendDoc($reintegro_id) {
        
        // Consulta el Estado ID del Reintegro
        $estado = Self::obtener_estado_id($reintegro_id);

        // Update en tabla reintegros, lo hace si cumple:
        // 1- Que el reintegro esté en estado En Proceso (Id. 2)
        // 2- Que el usuario tenga permisos para hacerlo
        if ($estado == 2 && Usuario::puede("reintegro_rollback_pendDocumentacion") == 1) {
            
            // Parámetros para el Update
            $reintegro_estado_id_m  = 1;

            DB::query("UPDATE reintegros SET
                            reintegro_reintegroEstado_id = :reintegro_estado_id_m
                    WHERE reintegro_id = :reintegro_id");

            DB::bind(':reintegro_estado_id_m', $reintegro_estado_id_m);
            DB::bind(':reintegro_id', $reintegro_id);

            DB::execute();

            return true;

        } else {

            return false;

        }
    }

    // Método para realizar el Rollback del Reintegro a Estado: En Proceso (Id. 2)
    public static function rollback_reintegro_enProceso($reintegro_id) {
        
        // Consulta el Estado ID del Reintegro
        $estado = Self::obtener_estado_id($reintegro_id);

        // Update en tabla reintegros, lo hace si cumple:
        // 1- Que el reintegro esté en estado Pend. Documentación (Id. 1)
        // 2- Que el usuario tenga permisos para hacerlo
        if ($estado == 1 && Usuario::puede("reintegro_rollback_pendDocumentacion") == 1) {
            
            // Parámetros para el Update
            $reintegro_estado_id_m  = 2;

            DB::query("UPDATE reintegros SET
                            reintegro_reintegroEstado_id = :reintegro_estado_id_m
                    WHERE reintegro_id = :reintegro_id");

            DB::bind(':reintegro_estado_id_m', $reintegro_estado_id_m);
            DB::bind(':reintegro_id', $reintegro_id);

            DB::execute();

            return true;

        } else {

            return false;

        }
    }

}
