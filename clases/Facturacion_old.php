<?php
/**
 * Clase: Facturacion_old
 *
 * Esta clase corresponde a la versión vieja de Facturación. 
 * Sirve para adaptar las facturas del SGC1.
 *
 * @author ArgenCode
 */

class Facturacion_old {
    
    // Método para listar las facturas buscando por numero de caso
    public static function listar($caso_id) {

        DB::query("SELECT facturas.factura_id AS idFactura,
                          tipos_facturas.tipoFactura_descripcion AS tipoFactura, 
                          facturas.factura_numero AS numeroFactura,
                          facturas.factura_facturaEstado_id AS estadoFacturaId,
                          facturas_estados.facturaEstado_nombre AS estadoFactura,
                          facturas_prioridades.facturaPrioridad_nombre AS prioridadFactura, 
                          clientes.cliente_nombre AS cliente,
                          facturas.factura_importeMedicoOrigen AS costoMedico,
                          facturas.factura_importeFeeOrigen AS fee,
                          facturas.factura_importeUSD AS importeUSD,
                          facturas.factura_importeAprobadoUSD AS importeAprobadoUSD
                          
                    FROM old_facturas AS facturas
                            LEFT JOIN tipos_facturas ON tipos_facturas.tipoFactura_id = facturas.factura_tipoFactura_id
                            LEFT JOIN old_facturas_estados AS Facturas_estados ON facturas_estados.facturaEstado_id = facturas.factura_facturaEstado_id
                            LEFT JOIN facturas_prioridades ON facturas_prioridades.facturaPrioridad_id = facturas.factura_prioridad_id
                            LEFT JOIN clientes ON clientes.cliente_id = facturas.factura_pagador_id
                            
                    WHERE facturas.factura_caso_id = :caso_id
                
                    ORDER BY facturas.factura_id");
                        
        DB::bind(':caso_id', $caso_id);

        return DB::resultados();
    }
    
    
    // Método para listar las facturas pendientes autorizar buscando por numero de caso
    public static function listar_pendientes_autorizar($caso_id) {
        
        DB::query("SELECT facturas.factura_id AS idFactura,
                          tipos_facturas.tipoFactura_descripcion AS tipoFactura, 
                          facturas.factura_numero AS numeroFactura,
                          facturas.factura_facturaEstado_id AS estadoFacturaId,
                          facturas_estados.facturaEstado_nombre AS estadoFactura,
                          facturas_prioridades.facturaPrioridad_nombre AS prioridadFactura, 
                          clientes.cliente_nombre AS cliente, 
                          facturas.factura_importeUSD AS presuntoUSD
                          
                    FROM old_facturas AS facturas
                            LEFT JOIN tipos_facturas ON tipoFactura_id = facturas.factura_tipoFactura_id
                            LEFT JOIN old_facturas_estados AS facturas_estados ON facturaEstado_id = facturas.factura_facturaEstado_id
                            LEFT JOIN facturas_prioridades ON facturaPrioridad_id = facturas.factura_prioridad_id
                            LEFT JOIN clientes ON clientes.cliente_id = facturas.factura_pagador_id
                            
                    WHERE facturas.factura_caso_id = :caso_id AND 
                          (factura_facturaEstado_id = 1 OR factura_facturaEstado_id = 2 OR 
                           factura_facturaEstado_id = 3 OR factura_facturaEstado_id = 4)
                
                    ORDER BY facturas.factura_id");
                        
        DB::bind(':caso_id', $caso_id);

        return DB::resultados();
    }
    
    
    // Método para PAGAR una factura
    public static function pagar($factura_id_au,
                                 $factura_fechaPago_auto,
                                 $factura_formaPago_auto,
                                 $factura_observaciones_auto) {
        
        // Formateo de fechas para guardar en la DB
        if (!empty($factura_fechaPago_auto)) {
            $factura_fechaPago_auto = date('Y-m-d', strtotime($factura_fechaPago_auto));
        } else {
            $factura_fechaPago_auto = NULL;
        }

        // Hardcode de Estado para ponerlo como Factura Pagada
        $factura_estado = 4;
        
        DB::query("UPDATE old_facturas SET 
                        factura_facturaEstado_id = :factura_estado,
                        factura_fechaPago = :factura_fechaPago_auto,
                        factura_formaPago_id = :factura_formaPago_auto,
                        facturas_pagoObservaciones = :factura_observaciones_auto
                    WHERE factura_id = :factura_id_au");
         
        DB::bind(':factura_estado', $factura_estado); 
        if (!empty($factura_fechaPago_auto)) {
            DB::bind(':factura_fechaPago_auto', "$factura_fechaPago_auto");
        } else {
            DB::bind(':factura_fechaPago_auto', $factura_fechaPago_auto);
        }
        DB::bind(':factura_formaPago_auto', $factura_formaPago_auto);
        DB::bind(':factura_observaciones_auto', "$factura_observaciones_auto");
        DB::bind(':factura_id_au', $factura_id_au);

        DB::execute();
        
        $mensaje = "La factura fue pagada con éxito";
        return $mensaje;
    }
    
    
    // Método para el Select - Lista los tipos de facturas en formulario ALTA
    public static function listar_tiposFacturas_alta(){
        
        DB::query("SELECT tipoFactura_id, tipoFactura_nombre
                    FROM tipos_facturas
                    WHERE tipoFactura_activo = 1
                    ORDER BY tipoFactura_nombre");
        
        return DB::resultados();
    }
    
    
    // Método para el Select - Lista las prioridades de facturas en formulario ALTA
    public static function listar_facturasPrioridades_alta(){
        
        DB::query("SELECT facturaPrioridad_id, facturaPrioridad_nombre
                    FROM facturas_prioridades
                    WHERE facturaPrioridad_activa = 1 
                    ORDER BY facturaPrioridad_nombre");
        
        return DB::resultados();
    }
    
  
    // Método para el Select - Lista las prioridades de facturas en formulario ALTA
    public static function listar_facturasMonedas_alta(){
        
        DB::query("SELECT moneda_id, moneda_nombre
                    FROM monedas
                    WHERE moneda_activa = 1 
                    ORDER BY moneda_nombre");
        
        return DB::resultados();
    }
    

    // Método para el Select - Lista los tipos de facturas en formulario ALTA
    public static function listar_facturaEstados(){
        
        DB::query("SELECT facturaEstado_id, facturaEstado_nombre
                    FROM old_facturas_estados AS facturas_estados");
        
        return DB::resultados();
    }
    
    
    // Método para el Select - Lista los motivos de rechazo de una factura para el modal de autorizacion de facturas. 
    public static function listar_motivosRechazo_alta(){
        
        DB::query("SELECT facturaMotivoRechazo_id, facturaMotivoRechazo_descripcion
                    FROM old_facturas_motivos_rechazos AS facturas_motivos_rechazos
                    ORDER BY facturaMotivoRechazo_descripcion");
        
        return DB::resultados();
    }
    
    
    // Método para el Select - Lista los tipos de facturas en formulario MODIFICACION
    public static function listar_tiposFacturas_modificacion($factura_id){
        
        DB::query("SELECT tipoFactura_id, tipoFactura_nombre
                    FROM old_facturas AS facturas
                        INNER JOIN tipos_facturas ON tipos_facturas.tipoFactura_id = facturas.factura_tipoFactura_id 
                    WHERE factura_id = :factura_id
                    UNION
                    SELECT tipoFactura_id, tipoFactura_nombre
                    FROM tipos_facturas
                    WHERE tipoFactura_activo = 1");
        
        DB::bind(':factura_id', $factura_id);
        
        return DB::resultados();
    }
    
    
    // Método para el Select - Lista las prioridades de facturas en formulario MODIFICACION
    public static function listar_facturasPrioridades_modificacion($factura_id){
        
        DB::query("SELECT facturaPrioridad_id, facturaPrioridad_nombre
                    FROM old_facturas AS facturas
                        INNER JOIN facturas_prioridades ON facturas_prioridades.facturaPrioridad_id = facturas.factura_prioridad_id 
                    WHERE factura_id = :factura_id
                    UNION
                    SELECT facturaPrioridad_id, facturaPrioridad_nombre
                    FROM facturas_prioridades
                    WHERE facturaPrioridad_activa = 1");
        
        DB::bind(':factura_id', $factura_id);
        
        return DB::resultados();
    }
    
  
    // Método para el Select - Lista las prioridades de facturas en formulario MODIFICACION
    public static function listar_facturasMonedas_modificacion($factura_id){
        
        DB::query("SELECT moneda_id, moneda_nombre
                    FROM old_facturas AS facturas
                        INNER JOIN monedas ON monedas.moneda_id = facturas.factura_monedaOrigen_id 
                    WHERE factura_id = :factura_id
                    UNION
                    SELECT moneda_id, moneda_nombre
                    FROM monedas
                    WHERE moneda_activa = 1");
        
        DB::bind(':factura_id', $factura_id);
        
        return DB::resultados();
    }
    
  
    // Método para el Select - Lista las formas de pagos habilitadas para el formulario AUTORIZACION
    public static function listar_formasPagos_autorizacion(){
        
        DB::query("SELECT formaPago_id, formaPago_nombre
                    FROM formas_pagos 
                    WHERE formaPago_activa = 1");
        
        return DB::resultados();
    }
    
    
    // Método para mostrar la información de un caso (según caso_id)
    public static function buscarPorId($factura_id) {
        
        DB::query("SELECT facturas.factura_id,
                          facturas.factura_numero as numeroFactura,
                          facturas.factura_facturaEstado_id as estadoFactura,
                          facturas.factura_fechaIngresoSistema as fechaIngresoSistemaFactura,                           
                          facturas.factura_fechaEmision as fechaEmisionFactura, 
                          facturas.factura_fechaRecepcion as fechaRecepcionFactura, 
                          facturas.factura_fechaVencimiento as fechaVencimientoFactura,
                          facturas.factura_fechaPago as fechaPagoFactura, 
                          facturas.factura_importeMedicoOrigen as importeMedicoFactura, 
                          facturas.factura_importeFeeOrigen as feeOrigenFactura, 
                          facturas.factura_tipoCambio as tipoCambioFactura,
                          facturas.factura_importeUSD as importeUSDFactura,
                          facturas.factura_importeAprobadoUSD as importeAprobadoUSDFactura,
                          facturas.factura_importeRechazadoUSD as importeRechazadoUSDFactura,
                          facturas.factura_observaciones as observacionesFactura,
                          tipos_facturas.tipoFactura_nombre as tipoFactura, 
                          clientes.cliente_nombre as clienteFactura, 
                          facturas_prioridades.facturaPrioridad_nombre as prioridadFactura, 
                          monedas.moneda_nombre as monedaFactura

                    FROM old_facturas AS facturas
                        LEFT JOIN tipos_facturas ON tipos_facturas.tipoFactura_id = facturas.factura_tipoFactura_id
                        LEFT JOIN clientes ON clientes.cliente_id = facturas.factura_pagador_id
                        LEFT JOIN facturas_prioridades ON facturas_prioridades.facturaPrioridad_id = facturas.factura_prioridad_id
                        LEFT JOIN monedas ON monedas.moneda_id = facturas.factura_monedaOrigen_id    

                    WHERE factura_id = :factura_id");
        
        DB::bind(':factura_id', $factura_id);
        
        $resultado = DB::resultado();
        
        //Conversiones de la fecha de ANSI a normal para Datepicker
        $fechaIngresoSistemaFactura = $resultado['fechaIngresoSistemaFactura'];
        $fechaIngresoSistemaFactura = date("d-m-Y H:i", strtotime($fechaIngresoSistemaFactura));
        $resultado['fechaIngresoSistemaFactura'] = $fechaIngresoSistemaFactura;
        
        if ($resultado['fechaEmisionFactura'] !== NULL) {
            $fechaEmisionFactura = date("d-m-Y", strtotime($resultado['fechaEmisionFactura']));
            $resultado['fechaEmisionFactura'] = $fechaEmisionFactura;
        } else {
            $resultado['fechaEmisionFactura'] = NULL;
        }
        
        if ($resultado['fechaRecepcionFactura'] !== NULL) {
            $fechaRecepcionFactura = date("d-m-Y", strtotime($resultado['fechaRecepcionFactura']));
            $resultado['fechaRecepcionFactura'] = $fechaRecepcionFactura;
        } else {
            $resultado['fechaRecepcionFactura'] = NULL;
        }
        
        if ($resultado['fechaVencimientoFactura'] !== NULL) {
            $fechaVencimientoFactura = date("d-m-Y", strtotime($resultado['fechaVencimientoFactura']));
            $resultado['fechaVencimientoFactura'] = $fechaVencimientoFactura;
        } else {
            $resultado['fechaVencimientoFactura'] = NULL;
        }
          
        if ($resultado['fechaPagoFactura'] !== NULL) {
            $fechaPagoFactura = date("d-m-Y", strtotime($resultado['fechaPagoFactura']));
            $resultado['fechaPagoFactura'] = $fechaPagoFactura;
        } else {
            $resultado['fechaPagoFactura'] = NULL;
        }
        
        return $resultado;       
    }
    
    
    // Método para listar el log de facturas
    public static function listar_log_factura($factura_id) {
        
        DB::query("SELECT facturas_logs.facturaLog_factura_id as idLogFactura,
                          facturas_logs.facturaLog_fechaEvento as fechaEventoLogFactura,
                          usuarios.usuario_nombre as usuarioNombreLogFactura, 
                          usuarios.usuario_apellido as usuarioApellidoLogFactura,
                          facturas_logs.facturaLog_facturaLogEstado_id as estadoLogFacturaId,
                          facturas_logs_estados.facturaLogEstado_descripcion as estadoLogFacturaDescripcion, 
                          facturas_motivos_rechazos.facturaMotivoRechazo_descripcion as motivoRechazoLogFactura, 
                          facturas_logs.facturaLog_observaciones as observacionesLogFactura,
                          facturas_logs.facturaLog_fechaPago as fechaPagoLogFactura,
                          facturas_logs.facturaLog_importeAprobadoUSD as importeAprobadoUSDFactura,
                          formas_pagos.formaPago_nombre as formaPagoLogFactura
                    FROM old_facturas_logs AS facturas_logs
                        LEFT JOIN usuarios on usuarios.usuario_id = facturas_logs.facturaLog_usuario_id
                        LEFT JOIN old_facturas_logs_estados AS facturas_logs_estados on facturas_logs_estados.facturaLogEstado_id = facturas_logs.facturaLog_facturaLogEstado_id
                        LEFT JOIN old_facturas_motivos_rechazos AS facturas_motivos_rechazos on facturas_motivos_rechazos.facturaMotivoRechazo_id = facturas_logs.facturaLog_facturaMotivoRechazo_id
                        LEFT JOIN formas_pagos on formas_pagos.formaPago_id = facturas_logs.facturaLog_formaPago_id

                    WHERE facturas_logs.facturaLog_factura_id = :factura_id
                
                    ORDER BY facturas_logs.facturaLog_fechaEvento");
                        
        DB::bind(':factura_id', $factura_id);
        
        return DB::resultados();;
    }
    
    
    // Método para contar la cantidad de registros en el log consultados
    public static function contar_log_factura($factura_id){
        
        DB::query("SELECT count(facturas_logs.facturaLog_factura_id) as facturaLog_cantidad
                    FROM old_facturas_logs AS facturas_logs
                    WHERE facturas_logs.facturaLog_factura_id = :factura_id");
        
        DB::bind(':factura_id', $factura_id);
        
        return DB::resultado();
    }
    
    
    // Método para mostrar la informacion de las facturas pendientes autorizar
    public static function info_pendientes_autorizar($factura_id) {
        
        DB::query("SELECT facturas.factura_id as idFactura, 
                          facturas.factura_facturaEstado_id as estadoFacturaId,
                          facturas.factura_numero as numeroFactura, 
                          facturas_estados.facturaEstado_nombre as estadoFactura, 
                          facturas.factura_importeUSD as importeUSDFactura,
                          facturas.factura_importeAprobadoUSD importeAprobadoUSD
                    FROM old_facturas AS facturas
                        LEFT JOIN old_facturas_estados AS facturas_estados on facturas_estados.facturaEstado_id = facturas.factura_facturaEstado_id
                    WHERE facturas.factura_id = :factura_id");
                        
        DB::bind(':factura_id', $factura_id);

        return DB::resultado();
    }
}
