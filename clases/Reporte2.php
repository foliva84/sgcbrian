<?php
/**
 * Clase: Reporte
 *
 *
 * @author ArgenCode
 */

class Reporte {
     
    // Reporte avanzado de Casos
    // Método para listar los casos aplicando el filtro del buscador
    public static function listar_casosAvanzado($caso_numero_desde, 
                                                $caso_numero_hasta, 
                                                $caso_fechaSiniestro_desde,
                                                $caso_fechaSiniestro_hasta,
                                                $caso_cliente_id,
                                                $caso_producto_id,
                                                $caso_pais_id,
                                                $caso_ciudad_id,
                                                $caso_tipoAsistencia_id,
                                                $caso_abiertoPor_id,
                                                $caso_agencia,
                                                $caso_prestador_id,
                                                $caso_estado_id,
                                                $caso_fee_id,
                                                $caso_anulado) {
        
        If ($caso_numero_desde == '') {
            $caso_numero_desde = NULL;
        }
        If ($caso_numero_hasta == '') {
            $caso_numero_hasta = NULL;
        }
        if (!empty($caso_fechaSiniestro_desde)) {
            $caso_fechaSiniestro_desde = date('Y-m-d', strtotime($caso_fechaSiniestro_desde));
        } else {
            $caso_fechaSiniestro_desde = NULL;
        }
        if (!empty($caso_fechaSiniestro_hasta)) {
            $caso_fechaSiniestro_hasta = date('Y-m-d 23:59:59', strtotime($caso_fechaSiniestro_hasta));
        } else {
            $caso_fechaSiniestro_hasta = NULL;
        }
        If ($caso_cliente_id == '') {
            $caso_cliente_id = NULL;
        }
        If ($caso_producto_id == '') {
            $caso_producto_id = NULL;
        }
        If ($caso_pais_id == '') {
            $caso_pais_id = NULL;
        }
        If ($caso_ciudad_id == '') {
            $caso_ciudad_id = NULL;
        }
        If ($caso_tipoAsistencia_id == '') {
            $caso_tipoAsistencia_id = NULL;
        }
        If ($caso_abiertoPor_id == '') {
            $caso_abiertoPor_id = NULL;
        }
        If ($caso_prestador_id == '') {
            $caso_prestador_id = NULL;
        }
        If ($caso_estado_id == '') {
            $caso_estado_id = NULL;
        }
        If ($caso_fee_id == '') {
            $caso_fee_id = NULL;
        }
                 
        DB::query("select caso_numero,
                          DATE_FORMAT(caso_fechaSiniestro, '%d-%m-%Y') as caso_fechaSiniestro,
                          tipoAsistencia_nombre as tipoAsistencia, 
                          casoEstado_nombre,
                          pais_nombreEspanol, 
                          ciudad_nombre,
                          DATE_FORMAT(caso_fechaAperturaCaso, '%d-%m-%Y') as caso_fechaAperturaCaso, 
                          cliente_nombre, 
                          caso_numeroVoucher, 
                          product_name, 
                          DATE_FORMAT(caso_fechaEmisionVoucher, '%d-%m-%Y') as caso_fechaEmisionVoucher,
                          DATE_FORMAT(caso_vigenciaVoucherDesde, '%d-%m-%Y')as caso_vigenciaVoucherDesde, 
                          DATE_FORMAT(caso_vigenciaVoucherHasta, '%d-%m-%Y') as caso_vigenciaVoucherHasta,
                          caso_beneficiarioNombre, 
                          caso_beneficiarioEdad, 
                          caso_sintomas, 
                          diagnostico_nombre,
                          fee_nombre, 
                          caso_agencia, 
                          usuario_usuario, 
                          CostoTotalUSD, 
                          caso_observaciones
                   from casos left join casos_estados on casoEstado_id = caso_casoEstado_id
                              left join paises on pais_id = caso_pais_id
                              left join ciudades on ciudad_id = caso_ciudad_id
			      left join clientes on cliente_id = caso_cliente_id
                              left join product on product_id_interno = caso_producto_id
                              left join diagnosticos on diagnostico_id = caso_diagnostico_id
                              left join tipos_asistencias on tipoAsistencia_id = caso_tipoAsistencia_id
                              left join fees on fee_id = caso_fee_id
                              left join usuarios on usuario_id = caso_abiertoPor_id
                              left join servicios ON servicios.servicio_caso_id = casos.caso_id
                              left join 
                                        (SELECT servicio_caso_id, SUM(servicio_presuntoUSD) as CostoTotalUSD
                                         FROM servicios 
                                         WHERE servicio_cancelado <> 1
                                         GROUP BY servicio_caso_id) AS 
					 CostoServicio ON CostoServicio.servicio_caso_id = Casos.caso_id
                   where caso_numero between COALESCE(:caso_numero_desde,caso_numero) and COALESCE(:caso_numero_hasta,caso_numero)
                     and caso_fechaSiniestro between COALESCE(:caso_fechaSiniestro_desde,caso_fechaSiniestro) and COALESCE(:caso_fechaSiniestro_hasta,caso_fechaSiniestro)
                     and caso_cliente_id = COALESCE(:caso_cliente_id,caso_cliente_id)
                     and caso_producto_id = COALESCE(:caso_producto_id,caso_producto_id)
                     and caso_pais_id = COALESCE(:caso_pais_id,caso_pais_id)
                     and caso_ciudad_id = COALESCE(:caso_ciudad_id,caso_ciudad_id)
                     and caso_tipoAsistencia_id = COALESCE(:caso_tipoAsistencia_id,caso_tipoAsistencia_id)
                     and caso_abiertoPor_id = COALESCE(:caso_abiertoPor_id,caso_abiertoPor_id)
                     and caso_casoEstado_id = COALESCE(:caso_estado_id,caso_casoEstado_id)
                     and caso_fee_id = COALESCE(:caso_fee_id,caso_fee_id)
                     and caso_agencia LIKE :caso_agencia
                     and 
                        (CASE WHEN :caso_prestador_id is not null 
                            THEN (servicio_prestador_id = :caso_prestador_id)
                            ELSE (caso_id = caso_id)
                         END)
                     and caso_anulado = :caso_anulado
                     group by caso_id
                     limit 36000");
        
        DB::bind(':caso_numero_desde', $caso_numero_desde);
        DB::bind(':caso_numero_hasta', $caso_numero_hasta);
        DB::bind(':caso_fechaSiniestro_desde', $caso_fechaSiniestro_desde);
        DB::bind(':caso_fechaSiniestro_hasta', $caso_fechaSiniestro_hasta);
        DB::bind(':caso_cliente_id', $caso_cliente_id);
        DB::bind(':caso_producto_id', $caso_producto_id);
        DB::bind(':caso_pais_id', $caso_pais_id);
        DB::bind(':caso_ciudad_id', $caso_ciudad_id);
        DB::bind(':caso_tipoAsistencia_id', $caso_tipoAsistencia_id);
        DB::bind(':caso_abiertoPor_id', $caso_abiertoPor_id);
        DB::bind(':caso_agencia', "%$caso_agencia%");
        DB::bind(':caso_prestador_id', $caso_prestador_id);
        DB::bind(':caso_estado_id', $caso_estado_id);
        DB::bind(':caso_fee_id', $caso_fee_id);
        DB::bind(':caso_anulado', "$caso_anulado");
        
        
        return DB::resultados();
    }
    
    public static function listar_casosAvanzado_contar($caso_numero_desde, 
                                                       $caso_numero_hasta, 
                                                       $caso_fechaSiniestro_desde,
                                                       $caso_fechaSiniestro_hasta,
                                                       $caso_cliente_id,
                                                       $caso_producto_id,
                                                       $caso_pais_id,
                                                       $caso_ciudad_id,
                                                       $caso_tipoAsistencia_id,
                                                       $caso_abiertoPor_id,
                                                       $caso_agencia,
                                                       $caso_prestador_id,
                                                       $caso_estado_id,
                                                       $caso_fee_id,
                                                       $caso_anulado) {
        
        If ($caso_numero_desde == '') {
            $caso_numero_desde = NULL;
        }
        If ($caso_numero_hasta == '') {
            $caso_numero_hasta = NULL;
        }
        if (!empty($caso_fechaSiniestro_desde)) {
            $caso_fechaSiniestro_desde = date('Y-m-d', strtotime($caso_fechaSiniestro_desde));
        } else {
            $caso_fechaSiniestro_desde = NULL;
        }
        if (!empty($caso_fechaSiniestro_hasta)) {
            $caso_fechaSiniestro_hasta = date('Y-m-d 23:59:59', strtotime($caso_fechaSiniestro_hasta));
        } else {
            $caso_fechaSiniestro_hasta = NULL;
        }
        If ($caso_cliente_id == '') {
            $caso_cliente_id = NULL;
        }
        If ($caso_producto_id == '') {
            $caso_producto_id = NULL;
        }
        If ($caso_pais_id == '') {
            $caso_pais_id = NULL;
        }
        If ($caso_ciudad_id == '') {
            $caso_ciudad_id = NULL;
        }
        If ($caso_tipoAsistencia_id == '') {
            $caso_tipoAsistencia_id = NULL;
        }
        If ($caso_abiertoPor_id == '') {
            $caso_abiertoPor_id = NULL;
        }
        If ($caso_prestador_id == '') {
            $caso_prestador_id = NULL;
        }
        If ($caso_estado_id == '') {
            $caso_estado_id = NULL;
        }
        If ($caso_fee_id == '') {
            $caso_fee_id = NULL;
        }
                   
        DB::query("SELECT COUNT(*) AS registros FROM
                        (select caso_numero
                        from casos left join servicios ON servicios.servicio_caso_id = casos.caso_id
                        where caso_numero between COALESCE(:caso_numero_desde,caso_numero) and COALESCE(:caso_numero_hasta,caso_numero)
                          and caso_fechaSiniestro between COALESCE(:caso_fechaSiniestro_desde,caso_fechaSiniestro) and COALESCE(:caso_fechaSiniestro_hasta,caso_fechaSiniestro)
                          and caso_cliente_id = COALESCE(:caso_cliente_id,caso_cliente_id)
                          and caso_producto_id = COALESCE(:caso_producto_id,caso_producto_id)
                          and caso_pais_id = COALESCE(:caso_pais_id,caso_pais_id)
                          and caso_ciudad_id = COALESCE(:caso_ciudad_id,caso_ciudad_id)
                          and caso_tipoAsistencia_id = COALESCE(:caso_tipoAsistencia_id,caso_tipoAsistencia_id)
                          and caso_abiertoPor_id = COALESCE(:caso_abiertoPor_id,caso_abiertoPor_id)
                          and caso_casoEstado_id = COALESCE(:caso_estado_id,caso_casoEstado_id)
                          and caso_fee_id = COALESCE(:caso_fee_id,caso_fee_id)
                          and caso_agencia LIKE :caso_agencia
                          and 
                            (CASE WHEN :caso_prestador_id is not null 
                                THEN (servicio_prestador_id = :caso_prestador_id)
                                ELSE (caso_id = caso_id)
                             END)
                          and caso_anulado = :caso_anulado
                          group by caso_id) AS casos_encontrados
                  ");
        
        DB::bind(':caso_numero_desde', $caso_numero_desde);
        DB::bind(':caso_numero_hasta', $caso_numero_hasta);
        DB::bind(':caso_fechaSiniestro_desde', $caso_fechaSiniestro_desde);
        DB::bind(':caso_fechaSiniestro_hasta', $caso_fechaSiniestro_hasta);
        DB::bind(':caso_cliente_id', $caso_cliente_id);
        DB::bind(':caso_producto_id', $caso_producto_id);
        DB::bind(':caso_pais_id', $caso_pais_id);
        DB::bind(':caso_ciudad_id', $caso_ciudad_id);
        DB::bind(':caso_tipoAsistencia_id', $caso_tipoAsistencia_id);
        DB::bind(':caso_abiertoPor_id', $caso_abiertoPor_id);
        DB::bind(':caso_agencia', "%$caso_agencia%");
        DB::bind(':caso_prestador_id', $caso_prestador_id);
        DB::bind(':caso_estado_id', $caso_estado_id);
        DB::bind(':caso_fee_id', $caso_fee_id);
        DB::bind(':caso_anulado', "$caso_anulado");
        
        return DB::resultado();
    }
    
    
    
    // Reporte Re Asegurador
    // Método para listar los casos aplicando el filtro del buscador
    public static function listar_casosReAsegurador($caso_numero_desde, 
                                                    $caso_numero_hasta, 
                                                    $caso_fechaSiniestro_desde,
                                                    $caso_fechaSiniestro_hasta) {
        
        If ($caso_numero_desde == '') {
            $caso_numero_desde = NULL;
        }
        If ($caso_numero_hasta == '') {
            $caso_numero_hasta = NULL;
        }
        if (!empty($caso_fechaSiniestro_desde)) {
            $caso_fechaSiniestro_desde = date('Y-m-d', strtotime($caso_fechaSiniestro_desde));
        } else {
            $caso_fechaSiniestro_desde = NULL;
        }
        if (!empty($caso_fechaSiniestro_hasta)) {
            $caso_fechaSiniestro_hasta = date('Y-m-d 23:59:59', strtotime($caso_fechaSiniestro_hasta));
        } else {
            $caso_fechaSiniestro_hasta = NULL;
        }
        
        DB::query("SELECT cliente_nombre, 
                          caso_numero,
                          DATE_FORMAT(caso_fechaSiniestro, '%d-%m-%Y') as caso_fechaSiniestro,
                          costoReintegro,
                          costoFactura,
                          pais_nombreEspanol,
                          caso_numeroVoucher, 
                          tipoAsistencia_nombre,
                          DATE_FORMAT(caso_fechaEmisionVoucher, '%d-%m-%Y') as caso_fechaEmisionVoucher,                          
                          caso_beneficiarioNombre,
                          product_name
                    FROM casos  LEFT JOIN clientes on cliente_id = caso_cliente_id
                    		/* REINTEGROS */                                
                                LEFT JOIN 
                                    (SELECT casos.caso_id, SUM(ri_movimientos.riMov_importeAprobadoUSD) as costoReintegro
                                    FROM ri_movimientos
                                    LEFT JOIN reintegros_items ON reintegros_items.reintegroItem_id = ri_movimientos.riMov_reintegroItem_id
                                    LEFT JOIN reintegros ON reintegros.reintegro_id = reintegros_items.reintegroItem_reintegro_id
                                    LEFT JOIN casos ON casos.caso_id = reintegros.reintegro_caso_id
                                    GROUP BY reintegros.reintegro_caso_id) AS 
                                    costoReintegros ON costoReintegros.caso_id = casos.caso_id
                                /* FACTURAS */                                
                                LEFT JOIN 
                                    (SELECT fc_items.fcItem_caso_id , SUM(fciMov_importeAprobadoUSD) as costoFactura
                                    FROM fci_movimientos
                                    LEFT JOIN fc_items ON fc_items.fcItem_id = fci_movimientos.fciMov_fcItem_id
                                    LEFT JOIN casos ON casos.caso_id = fc_items.fcItem_caso_id
                                    GROUP BY fcItem_caso_id) AS
                                    costoFacturas ON costoFacturas.fcItem_caso_id = casos.caso_id
                                /* PAISES, TIPO ASISTENCIA, PRODUCTO */
                                LEFT JOIN paises on pais_id = caso_pais_id
                                LEFT JOIN tipos_asistencias on tipoAsistencia_id = caso_tipoAsistencia_id
                                LEFT JOIN product on product.product_id_interno = caso_producto_id
                    WHERE caso_numero between COALESCE(:caso_numero_desde,caso_numero) and COALESCE(:caso_numero_hasta,caso_numero)
                        and caso_fechaSiniestro between COALESCE(:caso_fechaSiniestro_desde,caso_fechaSiniestro) and COALESCE(:caso_fechaSiniestro_hasta,caso_fechaSiniestro)
                        and caso_anulado = 0
                        group by casos.caso_id");
        
        DB::bind(':caso_numero_desde', $caso_numero_desde);
        DB::bind(':caso_numero_hasta', $caso_numero_hasta);
        DB::bind(':caso_fechaSiniestro_desde', $caso_fechaSiniestro_desde);
        DB::bind(':caso_fechaSiniestro_hasta', $caso_fechaSiniestro_hasta);
               
        return DB::resultados();
    }
    
    public static function listar_casosReAsegurador_contar($caso_numero_desde, 
                                                           $caso_numero_hasta, 
                                                           $caso_fechaSiniestro_desde,
                                                           $caso_fechaSiniestro_hasta) {
        
        If ($caso_numero_desde == '') {
            $caso_numero_desde = NULL;
        }
        If ($caso_numero_hasta == '') {
            $caso_numero_hasta = NULL;
        }
        if (!empty($caso_fechaSiniestro_desde)) {
            $caso_fechaSiniestro_desde = date('Y-m-d', strtotime($caso_fechaSiniestro_desde));
        } else {
            $caso_fechaSiniestro_desde = NULL;
        }
        if (!empty($caso_fechaSiniestro_hasta)) {
            $caso_fechaSiniestro_hasta = date('Y-m-d 23:59:59', strtotime($caso_fechaSiniestro_hasta));
        } else {
            $caso_fechaSiniestro_hasta = NULL;
        }
        
        DB::query("SELECT COUNT(*) AS registros FROM
                        (select caso_numero
                         from casos
                         where caso_numero between COALESCE(:caso_numero_desde,caso_numero) and COALESCE(:caso_numero_hasta,caso_numero)
                           and caso_fechaSiniestro between COALESCE(:caso_fechaSiniestro_desde,caso_fechaSiniestro) and COALESCE(:caso_fechaSiniestro_hasta,caso_fechaSiniestro)
                           and caso_anulado = 0
                           group by caso_id) AS casos_encontrados
                  ");        
        
        DB::bind(':caso_numero_desde', $caso_numero_desde);
        DB::bind(':caso_numero_hasta', $caso_numero_hasta);
        DB::bind(':caso_fechaSiniestro_desde', $caso_fechaSiniestro_desde);
        DB::bind(':caso_fechaSiniestro_hasta', $caso_fechaSiniestro_hasta);
               
        return DB::resultado();
    }
    
    
    // Reportes de facturacion
    // 
    // Método para listar las facturas aplicando el filtro del buscador
    public static function listar_facturacion($factura_fechaIngresoSistema_desde,
                                               $factura_fechaIngresoSistema_hasta,
                                               $caso_numero_desde, 
                                               $caso_numero_hasta, 
                                               $caso_cliente_id,
                                               $caso_prestador_id,
                                               $facturaEstado_id,
                                               $factura_numero) {
        
        if (!empty($factura_fechaIngresoSistema_desde)) {
            $factura_fechaIngresoSistema_desde = date('Y-m-d', strtotime($factura_fechaIngresoSistema_desde));
        } else {
            $factura_fechaIngresoSistema_desde = NULL;
        }
        if (!empty($factura_fechaIngresoSistema_hasta)) {
            $factura_fechaIngresoSistema_hasta = date('Y-m-d 23:59:59', strtotime($factura_fechaIngresoSistema_hasta));
        } else {
            $factura_fechaIngresoSistema_hasta = NULL;
        }        
        If ($caso_numero_desde == '') {
            $caso_numero_desde = NULL;
        }
        If ($caso_numero_hasta == '') {
            $caso_numero_hasta = NULL;
        }
        If ($caso_cliente_id == '') {
            $caso_cliente_id = NULL;
        }
        If ($caso_prestador_id == '') {
            $caso_prestador_id = NULL;
        }
        If ($facturaEstado_id == '') {
            $facturaEstado_id = NULL;
        }
                         
        DB::query("SELECT 				    
                        casos.caso_numero,
                        casos.caso_beneficiarioNombre,
                        DATE_FORMAT(casos.caso_fechaSiniestro, '%d-%m-%Y') as caso_fechaSiniestro,
                        paises.pais_nombreEspanol,
                        casos.caso_numeroVoucher,
                        DATE_FORMAT(casos.caso_fechaEmisionVoucher, '%d-%m-%Y') as caso_fechaEmisionVoucher,
                        product.product_name,
                        casos.caso_agencia,
                        facturas.factura_numero,
                        DATE_FORMAT(facturas.factura_fechaEmision, '%d-%m-%Y') as factura_fechaEmision,
                        DATE_FORMAT(facturas.factura_fechaIngresoSistema, '%d-%m-%Y') as factura_fechaIngresoSistema,
                        CONCAT(fci_movimientos_estados.fciMovEstado_nombre, '-', fci_movimientos_estados.fciMovEstado_sector) as estado,
                        prestadores.prestador_nombre,
                        prestadores.prestador_razonSocial,
                        clientes.cliente_nombre,
                        fc_items.fcItem_importeMedicoOrigen,
                        fc_items.fcItem_importeFeeOrigen,
                        fc_items.fcItem_descuento,                        
                        monedas.moneda_nombre,
                        fc_items.fcItem_tipoCambio,
                        fc_items.fcItem_importeUSD,
                        casos.caso_deducible,
                        (CASE WHEN (fcimov.fciMov_fciMovEstado_id = 6 or fcimov.fciMov_fciMovEstado_id = 8)
                              THEN fcimov.fciMov_importeAprobadoUSD 
                              ELSE fciimportesaprobados.impAprobadoUSD END) AS aprobadoUSD,
                        (CASE WHEN (fcimov.fciMov_fciMovEstado_id = 6 or fcimov.fciMov_fciMovEstado_id = 8)
				THEN (fc_items.fcItem_importeUSD - fcimov.fciMov_importeAprobadoUSD ) 
				ELSE (fc_items.fcItem_importeUSD - fciimportesaprobados.impAprobadoUSD) END) AS rechazadoUSD,
                        fcimov.fciMov_observaciones
                    FROM fc_items
                        LEFT JOIN facturas on factura_Id = fcItem_factura_id
                        LEFT JOIN casos on caso_id = fcItem_caso_id
                        LEFT JOIN clientes on cliente_id = fcItem_clientePagador_id
                        LEFT JOIN monedas on moneda_id = fcItem_monedaOrigen_id
                        LEFT JOIN paises on pais_id = caso_pais_id
                        LEFT JOIN product on product.product_id_interno = casos.caso_producto_id
                        LEFT JOIN fci_servicios on fci_servicios.fciServicio_fcItem_id = fc_items.fcItem_id
                             LEFT JOIN servicios on servicio_id = fciServicio_servicio_id
                             LEFT JOIN prestadores on prestador_id = servicio_prestador_id
                        LEFT JOIN (select fciMov_id, fciMov_fcItem_id, fciMov_fciMovEstado_id, fciMov_importeAprobadoUSD, fciMov_observaciones
                                   from fci_movimientos
			           where fciMov_id IN (SELECT max(fciMov_id) FROM fci_movimientos GROUP BY fciMov_fcItem_id)) 
                          AS fcimov on fcimov.fciMov_fcItem_id = fc_items.fcItem_id
                             LEFT JOIN fci_movimientos_estados ON fci_movimientos_estados.fciMovEstado_id = fcimov.fciMov_fciMovEstado_id
                        LEFT JOIN (select fci_movimientos.fciMov_fcItem_id, 
        			          fci_movimientos.fciMov_importeAprobadoUSD as impAprobadoUSD,
        				  fci_movimientos.fciMov_importeAprobadoOrigen as impAprobadoOrigen
				     from fci_movimientos
				    where (fci_movimientos.fciMov_fciMovEstado_id = 2 OR fci_movimientos.fciMov_fciMovEstado_id = 3))
                          AS fciimportesaprobados on fciimportesaprobados.fciMov_fcItem_id = fc_items.fcItem_id
                    WHERE caso_numero BETWEEN COALESCE(:caso_numero_desde,caso_numero) AND COALESCE(:caso_numero_hasta,caso_numero)
                      AND factura_fechaIngresoSistema between COALESCE(:factura_fechaIngresoSistema_desde,factura_fechaIngresoSistema) and COALESCE(:factura_fechaIngresoSistema_hasta,factura_fechaIngresoSistema)
                      AND fcItem_clientePagador_id = COALESCE(:caso_cliente_id,fcItem_clientePagador_id)
                      AND fciMov_fciMovEstado_id = COALESCE(:facturaEstado_id,fciMov_fciMovEstado_id)
                      AND factura_numero LIKE :factura_numero
                      AND (CASE WHEN :caso_prestador_id is not null 
                                THEN (servicio_prestador_id = :caso_prestador_id)
                                ELSE (factura_id = factura_id) 
                           END)
                    GROUP BY fcItem_id");
        
        DB::bind(':factura_fechaIngresoSistema_desde', $factura_fechaIngresoSistema_desde);
        DB::bind(':factura_fechaIngresoSistema_hasta', $factura_fechaIngresoSistema_hasta);
        DB::bind(':caso_numero_desde', $caso_numero_desde);
        DB::bind(':caso_numero_hasta', $caso_numero_hasta);
        DB::bind(':caso_cliente_id', $caso_cliente_id);
        DB::bind(':caso_prestador_id', $caso_prestador_id);
        DB::bind(':facturaEstado_id', $facturaEstado_id); 
        DB::bind(':factura_numero', "%$factura_numero%");
        
        return DB::resultados();
    }
    
    // Método para listar las facturas aplicando el filtro del buscador
    public static function listar_facturacion_contar($factura_fechaIngresoSistema_desde,
                                                      $factura_fechaIngresoSistema_hasta,
                                                      $caso_numero_desde, 
                                                      $caso_numero_hasta, 
                                                      $caso_cliente_id,
                                                      $caso_prestador_id,
                                                      $facturaEstado_id,
                                                      $factura_numero) {
        
        if (!empty($factura_fechaIngresoSistema_desde)) {
            $factura_fechaIngresoSistema_desde = date('Y-m-d', strtotime($factura_fechaIngresoSistema_desde));
        } else {
            $factura_fechaIngresoSistema_desde = NULL;
        }
        if (!empty($factura_fechaIngresoSistema_hasta)) {
            $factura_fechaIngresoSistema_hasta = date('Y-m-d 23:59:59', strtotime($factura_fechaIngresoSistema_hasta));
        } else {
            $factura_fechaIngresoSistema_hasta = NULL;
        }
        If ($caso_numero_desde == '') {
            $caso_numero_desde = NULL;
        }
        If ($caso_numero_hasta == '') {
            $caso_numero_hasta = NULL;
        }
        If ($caso_cliente_id == '') {
            $caso_cliente_id = NULL;
        }
        If ($caso_prestador_id == '') {
            $caso_prestador_id = NULL;
        }
        If ($facturaEstado_id == '') {
            $facturaEstado_id = NULL;
        }
                    
        DB::query("SELECT COUNT(*) AS registros FROM
                    (SELECT factura_numero        
                    FROM fc_items 
                        LEFT JOIN facturas on factura_Id = fcItem_factura_id
                        LEFT JOIN casos on caso_id = fcItem_caso_id
                        LEFT JOIN clientes on cliente_id = fcItem_clientePagador_id
                        LEFT JOIN monedas on moneda_id = fcItem_monedaOrigen_id
                        LEFT JOIN fci_servicios on fci_servicios.fciServicio_fcItem_id = fc_items.fcItem_id
                             LEFT JOIN servicios on servicio_id = fciServicio_servicio_id
                             LEFT JOIN prestadores on prestador_id = servicio_prestador_id
                        LEFT JOIN (select fciMov_id, fciMov_fcItem_id, fciMov_fciMovEstado_id, fciMov_importeAprobadoUSD, fciMov_observaciones
                                   from fci_movimientos
			           where fciMov_id IN (SELECT max(fciMov_id) FROM fci_movimientos GROUP BY fciMov_fcItem_id)) 
			  AS fcimov on fcimov.fciMov_fcItem_id = fc_items.fcItem_id
                             LEFT JOIN fci_movimientos_estados ON fci_movimientos_estados.fciMovEstado_id = fcimov.fciMov_fciMovEstado_id
                    WHERE caso_numero BETWEEN COALESCE(:caso_numero_desde,caso_numero) AND COALESCE(:caso_numero_hasta,caso_numero)
                      AND factura_fechaIngresoSistema between COALESCE(:factura_fechaIngresoSistema_desde,factura_fechaIngresoSistema) and COALESCE(:factura_fechaIngresoSistema_hasta,factura_fechaIngresoSistema)
                      AND fcItem_clientePagador_id = COALESCE(:caso_cliente_id,fcItem_clientePagador_id)
                      AND fciMov_fciMovEstado_id = COALESCE(:facturaEstado_id,fciMov_fciMovEstado_id)
                      AND factura_numero LIKE :factura_numero
                      AND (CASE WHEN :caso_prestador_id is not null 
                                THEN (servicio_prestador_id = :caso_prestador_id)
                                ELSE (factura_id = factura_id) 
                           END)
                    GROUP BY fcItem_id) AS facturas_encontradas");
        
        DB::bind(':factura_fechaIngresoSistema_desde', $factura_fechaIngresoSistema_desde);
        DB::bind(':factura_fechaIngresoSistema_hasta', $factura_fechaIngresoSistema_hasta);
        DB::bind(':caso_numero_desde', $caso_numero_desde);
        DB::bind(':caso_numero_hasta', $caso_numero_hasta);
        DB::bind(':caso_cliente_id', $caso_cliente_id);
        DB::bind(':caso_prestador_id', $caso_prestador_id);
        DB::bind(':facturaEstado_id', $facturaEstado_id); 
        DB::bind(':factura_numero', "%$factura_numero%");
        
        return DB::resultado();
    }
    

    // Método para listar las facturas con items en estado ingresado
    public static function listar_fci_ingresados() {
                                
        DB::query("SELECT 				    
                        casos.caso_numero,
                        facturas.factura_numero,                       
                        fc_items.fcItem_fechaIngresoSistema,
                        casos.caso_beneficiarioNombre,
                        casos.caso_numeroVoucher,
                        prestadores.prestador_nombre
                  FROM fc_items
                        LEFT JOIN facturas on factura_Id = fcItem_factura_id
                        LEFT JOIN casos on caso_id = fcItem_caso_id
                        LEFT JOIN prestadores on prestador_id = factura_prestador_id
                        LEFT JOIN (select fciMov_id, fciMov_fcItem_id, fciMov_fciMovEstado_id, fciMov_importeAprobadoUSD, fciMov_observaciones
                           from fci_movimientos
                           where fciMov_id IN (SELECT max(fciMov_id) FROM fci_movimientos GROUP BY fciMov_fcItem_id)) 
                           as fcimov on fcimov.fciMov_fcItem_id = fc_items.fcItem_id
                  WHERE fciMov_fciMovEstado_id = 1
                  ORDER BY fcItem_fechaIngresoSistema");

        return DB::resultados();
    }
    
    
    public static function listar_fci_ingresados_contar() {
                         
        DB::query("SELECT COUNT(*) AS registros FROM
                    (SELECT factura_numero        
                       FROM fc_items
                            LEFT JOIN facturas on factura_Id = fcItem_factura_id
                            LEFT JOIN casos on caso_id = fcItem_caso_id
                            LEFT JOIN prestadores on prestador_id = factura_prestador_id
                            LEFT JOIN (select fciMov_id, fciMov_fcItem_id, fciMov_fciMovEstado_id, fciMov_importeAprobadoUSD, fciMov_observaciones
                               from fci_movimientos
                               where fciMov_id IN (SELECT max(fciMov_id) FROM fci_movimientos GROUP BY fciMov_fcItem_id)) 
                               as fcimov on fcimov.fciMov_fcItem_id = fc_items.fcItem_id
                      WHERE fciMov_fciMovEstado_id = 1) AS facturas_encontradas");
        
        return DB::resultado();
    }
    
    
    // Método para listar las facturas con items en estado aprobado por facturacion
    public static function listar_fci_ordenAuditoria($cliente_id,
                                                     $prestador_id) {
        
        If ($cliente_id == '') {
            $cliente_id = NULL;
        }
        If ($prestador_id == '') {
            $prestador_id = NULL;
        }
                                
        DB::query("select
                        fc_items.fcItem_id,
                        facturas.factura_numero,
                        casos.caso_numero,
                        casos.caso_beneficiarioNombre,
                        casos.caso_numeroVoucher,
                        importeAprobadoOrigen,
                        importeAprobadoUSD
                   from fc_items left join 
                                        (select fciMov_fcItem_id,
                                                          max(fciMov_fciMovEstado_id) as ultimoEstado,
                                                          max(fciMov_importeAprobadoOrigen) as importeAprobadoOrigen,
                                                          max(fciMov_importeAprobadoUSD) as importeAprobadoUSD
                                        from fci_movimientos
                                        group by fciMov_fcItem_id) as 
                                        ultimoEstadoItem on ultimoEstadoItem.fciMov_fcItem_id = fc_items.fcItem_id
                                left join facturas on facturas.factura_Id = fc_items.fcItem_factura_id
                                left join casos on casos.caso_id = fc_items.fcItem_caso_id
                   where (ultimoEstado = 2 or ultimoEstado = 3)
                     and fc_items.fcItem_clientePagador_id = COALESCE(:cliente_id,fcItem_clientePagador_id)
                     and facturas.factura_prestador_id = COALESCE(:prestador_id,factura_prestador_id)                  
                   ");

        DB::bind(':cliente_id', $cliente_id);
        DB::bind(':prestador_id', $prestador_id);
        
        return DB::resultados();
    }
  
    
    public static function listar_fci_ordenAuditoria_contar($cliente_id,
                                                            $prestador_id) {
        
        If ($cliente_id == '') {
            $cliente_id = NULL;
        }
        If ($prestador_id == '') {
            $prestador_id = NULL;
        }
                         
        DB::query("SELECT COUNT(*) AS registros FROM
                    (select
                        facturas.factura_numero,
                        casos.caso_numero,
                        casos.caso_beneficiarioNombre,
                        casos.caso_numeroVoucher,
                        importeAprobadoOrigen,
                        importeAprobadoUSD
                    from fc_items left join 
                                        (select fciMov_fcItem_id,
                                                          max(fciMov_fciMovEstado_id) as ultimoEstado,
                                                          max(fciMov_importeAprobadoOrigen) as importeAprobadoOrigen,
                                                          max(fciMov_importeAprobadoUSD) as importeAprobadoUSD
                                        from fci_movimientos
                                        group by fciMov_fcItem_id) as 
                                        ultimoEstadoItem on ultimoEstadoItem.fciMov_fcItem_id = fc_items.fcItem_id
                                left join facturas on facturas.factura_Id = fc_items.fcItem_factura_id
                                left join casos on casos.caso_id = fc_items.fcItem_caso_id
                   where (ultimoEstado = 2 or ultimoEstado = 3)
                    and fc_items.fcItem_clientePagador_id = COALESCE(:cliente_id,fcItem_clientePagador_id)
                    and facturas.factura_prestador_id = COALESCE(:prestador_id,factura_prestador_id)	
                    ) AS facturas_encontradas");
        
        DB::bind(':cliente_id', $cliente_id);
        DB::bind(':prestador_id', $prestador_id);
        
        return DB::resultado();
    }
    
    // Método para listar las facturas con items en estado aprobado facturacion que van a orden de pago en excel
    public static function listar_fci_ordenAuditoria_op($cliente_id,
                                                        $prestador_id,
                                                        $fci_seleccionados) {
        
        If ($cliente_id == '') {
            $cliente_id = NULL;
        }
        If ($prestador_id == '') {
            $prestador_id = NULL;
        }
        If ($fci_seleccionados == '') {
            $fci_seleccionados = 0;
        }
                                
        DB::query("select
                        facturas.factura_numero,
                        casos.caso_numero,
                        casos.caso_beneficiarioNombre,
                        casos.caso_numeroVoucher,
                        pais_nombreEspanol,
                        CONCAT(prestadores.prestador_nombre, ' - ', prestadores.prestador_razonSocial) as prestador_nombre,
                        fci_movimientos.fciMov_importeAprobadoOrigen,
                        fci_movimientos.fciMov_importeAprobadoUSD
                   from fc_items left join fci_movimientos on fci_movimientos.fciMov_fcItem_id = fc_items.fcItem_id
                                 left join facturas on facturas.factura_Id = fc_items.fcItem_factura_id
                                    left join prestadores on prestador_id = facturas.factura_prestador_id
                                 left join casos on casos.caso_id = fc_items.fcItem_caso_id
                                    left join paises on pais_id = casos.caso_pais_id
                   where (fci_movimientos.fciMov_fciMovEstado_id = 2 or fci_movimientos.fciMov_fciMovEstado_id = 3)
                     and fc_items.fcItem_clientePagador_id = COALESCE(:cliente_id,fcItem_clientePagador_id)
                     and facturas.factura_prestador_id = COALESCE(:prestador_id,factura_prestador_id)
                     and fc_items.fcItem_id IN (" .$fci_seleccionados. ")
                   ");

        DB::bind(':cliente_id', $cliente_id);
        DB::bind(':prestador_id', $prestador_id);        
        
        return DB::resultados();
    }
    
    // 
    public static function totales_fci_ordenAuditoria_op($cliente_id,
                                                        $prestador_id,
                                                        $fci_seleccionados) {
        
        If ($cliente_id == '') {
            $cliente_id = NULL;
        }
        If ($prestador_id == '') {
            $prestador_id = NULL;
        }
        If ($fci_seleccionados == '') {
            $fci_seleccionados = 0;
        }
                                
        DB::query("select
                        sum(fci_movimientos.fciMov_importeAprobadoOrigen),
                        sum(fci_movimientos.fciMov_importeAprobadoUSD)
                   from fc_items left join fci_movimientos on fci_movimientos.fciMov_fcItem_id = fc_items.fcItem_id
                                 left join facturas on facturas.factura_Id = fc_items.fcItem_factura_id
                                    left join prestadores on prestador_id = facturas.factura_prestador_id
                                 left join casos on casos.caso_id = fc_items.fcItem_caso_id
                                    left join paises on pais_id = casos.caso_pais_id
                   where (fci_movimientos.fciMov_fciMovEstado_id = 2 or fci_movimientos.fciMov_fciMovEstado_id = 3)
                     and fc_items.fcItem_clientePagador_id = COALESCE(:cliente_id,fcItem_clientePagador_id)
                     and facturas.factura_prestador_id = COALESCE(:prestador_id,factura_prestador_id)
                     and fc_items.fcItem_id IN (" .$fci_seleccionados. ")
                   ");

        DB::bind(':cliente_id', $cliente_id);
        DB::bind(':prestador_id', $prestador_id);        
        
        return DB::resultados();
    }
    
    
    
    public static function importeAprobadoUSD_total($fci_seleccionados) {
                                
        If ($fci_seleccionados == '') {
            $fci_seleccionados = 0;
        }
        
        DB::query("select SUM(fci_movimientos.fciMov_importeAprobadoUSD) as importeAprobadoUSD_total
                   from fci_movimientos
                   where fciMov_fcItem_id IN (" .$fci_seleccionados. ")
                   ");
        
        return DB::resultado();
    }
    
    
    // Reporte GOP
    // Método para listar las gop enviadas por caso
    public static function listar_gopEnviadas($caso_numero) {
        
        If ($caso_numero == '') {
            $caso_numero = NULL;
        }
                
        DB::query("SELECT gop_id,
                          gop_casoNumero,
                          gop_voucher,
                          prestador_nombre, 
                          gop_nombreBeneficiario,
                          DATE_FORMAT(gop_fecha, '%d-%m-%Y %H:%i:%s') AS gop_fecha
                   FROM gop LEFT JOIN prestadores ON prestador_id = gop_prestador_id
                   WHERE gop_casoNumero = COALESCE(:caso_numero,gop_casoNumero)
                   ORDER BY gop_fecha desc, gop_id desc");
        
        DB::bind(':caso_numero', $caso_numero);
                       
        return DB::resultados();
    }
    
    public static function listar_gopEnviadas_contar($caso_numero) {
        
        If ($caso_numero == '') {
            $caso_numero = NULL;
        }
        
        DB::query("SELECT COUNT(*) AS registros FROM
                        (select gop_id
                        from gop left join prestadores on prestador_id = gop_prestador_id
                        where gop_casoNumero = COALESCE(:caso_numero,gop_casoNumero)) AS casos_encontrados
                  ");        
        
        DB::bind(':caso_numero', $caso_numero);
               
        return DB::resultado();
    }
    
    
    // Reporte de estadisticas por operador
    // Método para listar las esadisticas por operador aplicando el filtro del buscador
    public static function listar_estadisticaPorOperador($caso_fechaAperturaCaso_desde,
                                                         $caso_fechaAperturaCaso_hasta,
                                                         $incluir_usuariosDeshabilitados) {
        
        if (!empty($caso_fechaAperturaCaso_desde)) {
            if (!empty($caso_fechaAperturaCaso_hasta)) {
                $caso_fechaAperturaCaso_desde = date('Y-m-d', strtotime($caso_fechaAperturaCaso_desde));
                $caso_fechaAperturaCaso_hasta = date('Y-m-d 23:59:59', strtotime($caso_fechaAperturaCaso_hasta));
            } else {
                $caso_fechaAperturaCaso_desde = date('Y-m-d', strtotime($caso_fechaAperturaCaso_desde));
                $caso_fechaAperturaCaso_hasta = date('Y-m-d 23:59:59', strtotime($caso_fechaAperturaCaso_desde));
            }    
        } else {
            $caso_fechaAperturaCaso_desde = NULL;
            $caso_fechaAperturaCaso_hasta = NULL;
        }

        // Valida si se quieren agregar los usuarios deshabilitados o no
        if ($incluir_usuariosDeshabilitados == 0) {
            $where = "usuarios.usuario_activo = 1 AND
                        (usuarios.usuario_rol_id IN (select roles.rol_id 
                        from roles 
                        left join roles_permisos on roles_permisos.rol_id = roles.rol_id
                        left join permisos on permisos.permiso_id = roles_permisos.permiso_id
                        where permiso_variable = 'estadisticasPorOperador_incluir'))";
        } else if ($incluir_usuariosDeshabilitados == 1) {
            $where = "(usuarios.usuario_rol_id IN (select roles.rol_id 
                        from roles 
                        left join roles_permisos on roles_permisos.rol_id = roles.rol_id
                        left join permisos on permisos.permiso_id = roles_permisos.permiso_id
                        where permiso_variable = 'estadisticasPorOperador_incluir'))";
        }

        DB::query("SELECT usuarios.usuario_usuario, 
                            CantidadCasos.cantcasos, 
                            CantidadCOM.cantcomunic, 
                            CantidadCOMReintegros.cantcomunicR,
                            IF(usuarios.usuario_activo = 1, 'Habilitado', 'Deshabilitado') AS usuarioEstado
                    FROM usuarios 
                        LEFT JOIN
                            (select usuarios.usuario_id, count(caso_numero) as cantcasos
                            from usuarios left join casos on casos.caso_abiertoPor_id = usuarios.usuario_id 
                            where (casos.caso_fechaAperturaCaso between :caso_fechaAperturaCaso_desde and :caso_fechaAperturaCaso_hasta)
                            group by usuarios.usuario_id) as CantidadCasos ON CantidadCasos.usuario_id = usuarios.usuario_id
                        LEFT JOIN
                            (select usuarios.usuario_id, count(comunicacion_id) as cantcomunic
                            from usuarios left join comunicaciones on comunicaciones.comunicacion_usuario_id = usuarios.usuario_id 
                            where (comunicacion_fechaIngreso between :caso_fechaAperturaCaso_desde and :caso_fechaAperturaCaso_hasta)
                            group by usuarios.usuario_id) as CantidadCOM ON CantidadCOM.usuario_id = usuarios.usuario_id
                        LEFT JOIN
                            (select usuarios.usuario_id, count(comunicacionR_id) as cantcomunicR
                            from usuarios left join comunicaciones_reintegro on comunicaciones_reintegro.comunicacionR_usuario_id = usuarios.usuario_id 
                            where (comunicacionR_fechaIngreso between :caso_fechaAperturaCaso_desde and :caso_fechaAperturaCaso_hasta)
                            group by usuarios.usuario_id) as CantidadCOMReintegros ON CantidadCOMReintegros.usuario_id = usuarios.usuario_id
                    WHERE " . $where . "
                    ORDER BY usuarios.usuario_id");
        
        DB::bind(':caso_fechaAperturaCaso_desde', $caso_fechaAperturaCaso_desde);
        DB::bind(':caso_fechaAperturaCaso_hasta', $caso_fechaAperturaCaso_hasta);
               
        return DB::resultados();
    }
    
    
    // Reporte Datos de Contacto
    // Método para listar los casos aplicando el filtro del buscador
    public static function listar_datosContacto($caso_numero_desde, 
                                                $caso_numero_hasta, 
                                                $caso_fechaSiniestro_desde,
                                                $caso_fechaSiniestro_hasta) {
        
        If ($caso_numero_desde == '') {
            $caso_numero_desde = NULL;
        }
        If ($caso_numero_hasta == '') {
            $caso_numero_hasta = NULL;
        }
        if (!empty($caso_fechaSiniestro_desde)) {
            $caso_fechaSiniestro_desde = date('Y-m-d', strtotime($caso_fechaSiniestro_desde));
        } else {
            $caso_fechaSiniestro_desde = NULL;
        }
        if (!empty($caso_fechaSiniestro_hasta)) {
            $caso_fechaSiniestro_hasta = date('Y-m-d 23:59:59', strtotime($caso_fechaSiniestro_hasta));
        } else {
            $caso_fechaSiniestro_hasta = NULL;
        }
        
        DB::query("SELECT casos.caso_numero,
                          casos.caso_beneficiarioNombre, 
                          casos.caso_numeroVoucher,
                          casos.caso_fechaAperturaCaso,
                          paises.pais_nombreEspanol,
                          casos.caso_beneficiarioEdad,
                          tipos_asistencias.tipoAsistencia_nombre,
                          emailContacto.email_email
                     FROM casos
                          left join paises on paises.pais_id = casos.caso_pais_id
                          left join tipos_asistencias on tipos_asistencias.tipoAsistencia_id = casos.caso_tipoAsistencia_id
                          left join (select email_entidad_id, email_email from emails where emails.email_entidad_tipo = 2) 
                                 as emailContacto on emailContacto.email_entidad_id = casos.caso_numero
                    WHERE caso_numero between COALESCE(:caso_numero_desde,caso_numero) and COALESCE(:caso_numero_hasta,caso_numero)
                      AND caso_fechaSiniestro between COALESCE(:caso_fechaSiniestro_desde,caso_fechaSiniestro) and COALESCE(:caso_fechaSiniestro_hasta,caso_fechaSiniestro)
                    LIMIT 10000
                 ");
        
        DB::bind(':caso_numero_desde', $caso_numero_desde);
        DB::bind(':caso_numero_hasta', $caso_numero_hasta);
        DB::bind(':caso_fechaSiniestro_desde', $caso_fechaSiniestro_desde);
        DB::bind(':caso_fechaSiniestro_hasta', $caso_fechaSiniestro_hasta);
               
        return DB::resultados();
    }
    
    public static function listar_datosContacto_contar($caso_numero_desde, 
                                                       $caso_numero_hasta, 
                                                       $caso_fechaSiniestro_desde,
                                                       $caso_fechaSiniestro_hasta) {
        
        If ($caso_numero_desde == '') {
            $caso_numero_desde = NULL;
        }
        If ($caso_numero_hasta == '') {
            $caso_numero_hasta = NULL;
        }
        if (!empty($caso_fechaSiniestro_desde)) {
            $caso_fechaSiniestro_desde = date('Y-m-d', strtotime($caso_fechaSiniestro_desde));
        } else {
            $caso_fechaSiniestro_desde = NULL;
        }
        if (!empty($caso_fechaSiniestro_hasta)) {
            $caso_fechaSiniestro_hasta = date('Y-m-d 23:59:59', strtotime($caso_fechaSiniestro_hasta));
        } else {
            $caso_fechaSiniestro_hasta = NULL;
        }
        
        DB::query("SELECT COUNT(*) AS registros FROM
                        (select caso_numero
                         from casos
                         where caso_numero between COALESCE(:caso_numero_desde,caso_numero) and COALESCE(:caso_numero_hasta,caso_numero)
                           and caso_fechaSiniestro between COALESCE(:caso_fechaSiniestro_desde,caso_fechaSiniestro) and COALESCE(:caso_fechaSiniestro_hasta,caso_fechaSiniestro)) 
                       AS casos_encontrados
                  ");        
        
        DB::bind(':caso_numero_desde', $caso_numero_desde);
        DB::bind(':caso_numero_hasta', $caso_numero_hasta);
        DB::bind(':caso_fechaSiniestro_desde', $caso_fechaSiniestro_desde);
        DB::bind(':caso_fechaSiniestro_hasta', $caso_fechaSiniestro_hasta);
               
        return DB::resultado();
    }


    // Reintegros
    //
    //
    // Método para listar los reintegros aplicando el filtro del buscador
    public static function listar_reintegros($caso_numero_desde,
                                             $caso_numero_hasta,
                                             $caso_fechaSiniestro_desde,
                                             $caso_fechaSiniestro_hasta,
                                             $caso_agencia,
                                             $caso_pais_id,
                                             $caso_producto_id,
                                             $reintegroEstado_id,
                                             $reintegro_fechaIngresoSistema_desde,
                                             $reintegro_fechaIngresoSistema_hasta) {
        
        If ($caso_numero_desde == '') {
            $caso_numero_desde = NULL;
        }
        If ($caso_numero_hasta == '') {
            $caso_numero_hasta = NULL;
        }
        if (!empty($caso_fechaSiniestro_desde)) {
            $caso_fechaSiniestro_desde = date('Y-m-d', strtotime($caso_fechaSiniestro_desde));
        } else {
            $caso_fechaSiniestro_desde = NULL;
        }
        if (!empty($caso_fechaSiniestro_hasta)) {
            $caso_fechaSiniestro_hasta = date('Y-m-d 23:59:59', strtotime($caso_fechaSiniestro_hasta));
        } else {
            $caso_fechaSiniestro_hasta = NULL;
        }
        If ($caso_pais_id == '') {
            $caso_pais_id = NULL;
        }
        If ($caso_producto_id == '') {
            $caso_producto_id = NULL;
        }
        If ($reintegroEstado_id == '') {
            $reintegroEstado_id = NULL;
        }
        if (!empty($reintegro_fechaIngresoSistema_desde)) {
            $reintegro_fechaIngresoSistema_desde = date('Y-m-d', strtotime($reintegro_fechaIngresoSistema_desde));
        } else {
            $reintegro_fechaIngresoSistema_desde = NULL;
        }
        if (!empty($reintegro_fechaIngresoSistema_hasta)) {
            $reintegro_fechaIngresoSistema_hasta = date('Y-m-d 23:59:59', strtotime($reintegro_fechaIngresoSistema_hasta));
        } else {
            $reintegro_fechaIngresoSistema_hasta = NULL;
        }
                         
        DB::query("SELECT caso_id,				    
                          caso_numero,
                          reintegro_id,
                          reintegroEstado_nombre,
                          DATE_FORMAT(reintegro_fechaIngresoSistema, '%d-%m-%Y') as reintegro_fechaIngreso,                      
                          DATE_FORMAT(reintegro_fechaPresentacion, '%d-%m-%Y') as reintegro_fechaPresentacion,
                          DATE_FORMAT(reintegro_fechaAuditado, '%d-%m-%Y') as reintegro_fechaAuditado,
                          DATE_FORMAT(reintegro_fechaPago, '%d-%m-%Y') as reintegro_fechaPago,
                          SUM(reintegroItem_importeUSD) as importeUSD,
                          SUM(reintegroItem_importeAprobadoUSD) as importeAprobadoUSD,
                          formas_pagos.formaPago_nombre
                     FROM reintegros
                        INNER JOIN reintegros_items on reintegros_items.reintegroItem_reintegro_id = reintegros.reintegro_id
                        LEFT JOIN reintegros_estados on reintegroEstado_id = reintegro_reintegroEstado_id
                        LEFT JOIN formas_pagos on formas_pagos.formaPago_id = reintegros.reintegro_formaPago_id 
                        LEFT JOIN casos on caso_id = reintegro_caso_id
                    WHERE caso_numero BETWEEN COALESCE(:caso_numero_desde,caso_numero) AND COALESCE(:caso_numero_hasta,caso_numero)
                      AND caso_fechaSiniestro between COALESCE(:caso_fechaSiniestro_desde,caso_fechaSiniestro) and COALESCE(:caso_fechaSiniestro_hasta,caso_fechaSiniestro)
                      AND caso_agencia LIKE :caso_agencia
                      AND caso_pais_id = COALESCE(:caso_pais_id,caso_pais_id)
                      AND caso_producto_id = COALESCE(:caso_producto_id,caso_producto_id)
                      AND reintegro_reintegroEstado_id = COALESCE(:reintegroEstado_id,reintegro_reintegroEstado_id)
                      AND reintegro_fechaIngresoSistema between COALESCE(:reintegro_fechaIngresoSistema_desde,reintegro_fechaIngresoSistema) and COALESCE(:reintegro_fechaIngresoSistema_hasta,reintegro_fechaIngresoSistema)
                 GROUP BY reintegroItem_reintegro_id");
        
        DB::bind(':caso_numero_desde', $caso_numero_desde);
        DB::bind(':caso_numero_hasta', $caso_numero_hasta);
        DB::bind(':caso_fechaSiniestro_desde', $caso_fechaSiniestro_desde);
        DB::bind(':caso_fechaSiniestro_hasta', $caso_fechaSiniestro_hasta);
        DB::bind(':caso_agencia', "%$caso_agencia%");
        DB::bind(':caso_pais_id', $caso_pais_id);
        DB::bind(':caso_producto_id', $caso_producto_id);
        DB::bind(':reintegroEstado_id', $reintegroEstado_id);
        DB::bind(':reintegro_fechaIngresoSistema_desde', $reintegro_fechaIngresoSistema_desde);
        DB::bind(':reintegro_fechaIngresoSistema_hasta', $reintegro_fechaIngresoSistema_hasta);
        
        return DB::resultados();
    }
    // Método para contar la cantidad de Reintegros
    public static function listar_reintegros_contar($caso_numero_desde, 
                                                    $caso_numero_hasta,
                                                    $caso_fechaSiniestro_desde,
                                                    $caso_fechaSiniestro_hasta,
                                                    $caso_agencia,
                                                    $caso_pais_id,
                                                    $caso_producto_id,
                                                    $reintegroEstado_id,
                                                    $reintegro_fechaIngresoSistema_desde,
                                                    $reintegro_fechaIngresoSistema_hasta) {
        
        If ($caso_numero_desde == '') {
            $caso_numero_desde = NULL;
        }
        If ($caso_numero_hasta == '') {
            $caso_numero_hasta = NULL;
        }
        if (!empty($caso_fechaSiniestro_desde)) {
            $caso_fechaSiniestro_desde = date('Y-m-d', strtotime($caso_fechaSiniestro_desde));
        } else {
            $caso_fechaSiniestro_desde = NULL;
        }
        if (!empty($caso_fechaSiniestro_hasta)) {
            $caso_fechaSiniestro_hasta = date('Y-m-d 23:59:59', strtotime($caso_fechaSiniestro_hasta));
        } else {
            $caso_fechaSiniestro_hasta = NULL;
        }
        If ($caso_pais_id == '') {
            $caso_pais_id = NULL;
        }
        If ($caso_producto_id == '') {
            $caso_producto_id = NULL;
        }
        If ($reintegroEstado_id == '') {
            $reintegroEstado_id = NULL;
        }
        if (!empty($reintegro_fechaIngresoSistema_desde)) {
            $reintegro_fechaIngresoSistema_desde = date('Y-m-d', strtotime($reintegro_fechaIngresoSistema_desde));
        } else {
            $reintegro_fechaIngresoSistema_desde = NULL;
        }
        if (!empty($reintegro_fechaIngresoSistema_hasta)) {
            $reintegro_fechaIngresoSistema_hasta = date('Y-m-d 23:59:59', strtotime($reintegro_fechaIngresoSistema_hasta));
        } else {
            $reintegro_fechaIngresoSistema_hasta = NULL;
        }
                    
        DB::query("SELECT COUNT(*) AS registros FROM
                    (SELECT caso_numero
                    FROM reintegros
                        INNER JOIN reintegros_items on reintegros_items.reintegroItem_reintegro_id = reintegros.reintegro_id
                        LEFT JOIN reintegros_estados on reintegroEstado_id = reintegro_reintegroEstado_id
                        LEFT JOIN formas_pagos on formas_pagos.formaPago_id = reintegros.reintegro_formaPago_id 
                        LEFT JOIN casos on caso_id = reintegro_caso_id
                    WHERE caso_numero BETWEEN COALESCE(:caso_numero_desde,caso_numero) AND COALESCE(:caso_numero_hasta,caso_numero)
                      AND caso_fechaSiniestro between COALESCE(:caso_fechaSiniestro_desde,caso_fechaSiniestro) and COALESCE(:caso_fechaSiniestro_hasta,caso_fechaSiniestro)
                      AND caso_agencia LIKE :caso_agencia
                      AND caso_pais_id = COALESCE(:caso_pais_id,caso_pais_id)
                      AND caso_producto_id = COALESCE(:caso_producto_id,caso_producto_id)
                      AND reintegro_reintegroEstado_id = COALESCE(:reintegroEstado_id,reintegro_reintegroEstado_id)
                      AND reintegro_fechaIngresoSistema between COALESCE(:reintegro_fechaIngresoSistema_desde,reintegro_fechaIngresoSistema) and COALESCE(:reintegro_fechaIngresoSistema_hasta,reintegro_fechaIngresoSistema)
                 GROUP BY reintegroItem_reintegro_id) 
                 AS casos_encontrados");
        
        DB::bind(':caso_numero_desde', $caso_numero_desde);
        DB::bind(':caso_numero_hasta', $caso_numero_hasta);
        DB::bind(':caso_fechaSiniestro_desde', $caso_fechaSiniestro_desde);
        DB::bind(':caso_fechaSiniestro_hasta', $caso_fechaSiniestro_hasta);
        DB::bind(':caso_agencia', "%$caso_agencia%");
        DB::bind(':caso_pais_id', $caso_pais_id);
        DB::bind(':caso_producto_id', $caso_producto_id);
        DB::bind(':reintegroEstado_id', $reintegroEstado_id);
        DB::bind(':reintegro_fechaIngresoSistema_desde', $reintegro_fechaIngresoSistema_desde);
        DB::bind(':reintegro_fechaIngresoSistema_hasta', $reintegro_fechaIngresoSistema_hasta);
        
        return DB::resultado();
    }
    // Método para listar los reintegros aplicando el filtro del buscador para EXCEL
    public static function listar_reintegros_excel($caso_numero_desde,
                                                   $caso_numero_hasta,
                                                   $caso_fechaSiniestro_desde,
                                                   $caso_fechaSiniestro_hasta,
                                                   $caso_agencia,
                                                   $caso_pais_id,
                                                   $caso_producto_id,
                                                   $reintegroEstado_id,
                                                   $reintegro_fechaIngresoSistema_desde,
                                                   $reintegro_fechaIngresoSistema_hasta) {
        
        If ($caso_numero_desde == '') {
            $caso_numero_desde = NULL;
        }
        If ($caso_numero_hasta == '') {
            $caso_numero_hasta = NULL;
        }
        if (!empty($caso_fechaSiniestro_desde)) {
            $caso_fechaSiniestro_desde = date('Y-m-d', strtotime($caso_fechaSiniestro_desde));
        } else {
            $caso_fechaSiniestro_desde = NULL;
        }
        if (!empty($caso_fechaSiniestro_hasta)) {
            $caso_fechaSiniestro_hasta = date('Y-m-d 23:59:59', strtotime($caso_fechaSiniestro_hasta));
        } else {
            $caso_fechaSiniestro_hasta = NULL;
        }
        If ($caso_pais_id == '') {
            $caso_pais_id = NULL;
        }
        If ($caso_producto_id == '') {
            $caso_producto_id = NULL;
        }
        If ($reintegroEstado_id == '') {
            $reintegroEstado_id = NULL;
        }
        if (!empty($reintegro_fechaIngresoSistema_desde)) {
            $reintegro_fechaIngresoSistema_desde = date('Y-m-d', strtotime($reintegro_fechaIngresoSistema_desde));
        } else {
            $reintegro_fechaIngresoSistema_desde = NULL;
        }
        if (!empty($reintegro_fechaIngresoSistema_hasta)) {
            $reintegro_fechaIngresoSistema_hasta = date('Y-m-d 23:59:59', strtotime($reintegro_fechaIngresoSistema_hasta));
        } else {
            $reintegro_fechaIngresoSistema_hasta = NULL;
        }
                         
        DB::query("SELECT caso_numero,
                          caso_fechaSiniestro,
                          cliente_abreviatura,
                          tipoAsistencia_nombre,
                          CONCAT(usuario_nombre, ' ',usuario_apellido) AS usuario,
                          reintegroEstado_nombre,
                          DATE_FORMAT(reintegro_fechaIngresoSistema, '%d-%m-%Y') as reintegro_fechaIngreso,
                          DATE_FORMAT(reintegro_fechaAuditado, '%d-%m-%Y') as reintegro_fechaAuditado,
                          DATE_FORMAT(reintegro_fechaPago, '%d-%m-%Y') as reintegro_fechaPago,
                          paises.pais_nombreEspanol,
                          product.product_name,
                          casos.caso_agencia,
                          reintegros.reintegro_importeARS,
                          SUM(reintegroItem_importeAprobadoUSD) as importeAprobadoUSD,
                         (SUM(reintegroItem_importeUSD) - SUM(reintegroItem_importeAprobadoUSD)) as importeRechazadoUSD,
                          formas_pagos.formaPago_nombre
                     FROM reintegros
                        INNER JOIN reintegros_items on reintegros_items.reintegroItem_reintegro_id = reintegros.reintegro_id
                        LEFT JOIN reintegros_estados on reintegroEstado_id = reintegro_reintegroEstado_id
                        LEFT JOIN formas_pagos on formas_pagos.formaPago_id = reintegros.reintegro_formaPago_id 
                        LEFT JOIN casos on caso_id = reintegro_caso_id
                        LEFT JOIN paises on paises.pais_id = casos.caso_pais_id
                        LEFT JOIN product on product.product_id_interno = casos.caso_producto_id
                        LEFT JOIN clientes on clientes.cliente_id = casos.caso_cliente_id
                        LEFT JOIN tipos_asistencias on tipos_asistencias.tipoAsistencia_id = casos.caso_tipoAsistencia_id
                        LEFT JOIN usuarios on usuarios.usuario_id = casos.caso_coordinadorCaso_id
                    WHERE caso_numero BETWEEN COALESCE(:caso_numero_desde,caso_numero) AND COALESCE(:caso_numero_hasta,caso_numero)
                      AND caso_fechaSiniestro between COALESCE(:caso_fechaSiniestro_desde,caso_fechaSiniestro) and COALESCE(:caso_fechaSiniestro_hasta,caso_fechaSiniestro)
                      AND caso_agencia LIKE :caso_agencia
                      AND caso_pais_id = COALESCE(:caso_pais_id,caso_pais_id)
                      AND caso_producto_id = COALESCE(:caso_producto_id,caso_producto_id)
                      AND reintegro_reintegroEstado_id = COALESCE(:reintegroEstado_id,reintegro_reintegroEstado_id)
                      AND reintegro_fechaIngresoSistema between COALESCE(:reintegro_fechaIngresoSistema_desde,reintegro_fechaIngresoSistema) and COALESCE(:reintegro_fechaIngresoSistema_hasta,reintegro_fechaIngresoSistema)
                 GROUP BY reintegroItem_reintegro_id");
        
        DB::bind(':caso_numero_desde', $caso_numero_desde);
        DB::bind(':caso_numero_hasta', $caso_numero_hasta);
        DB::bind(':caso_fechaSiniestro_desde', $caso_fechaSiniestro_desde);
        DB::bind(':caso_fechaSiniestro_hasta', $caso_fechaSiniestro_hasta);
        DB::bind(':caso_agencia', "%$caso_agencia%");
        DB::bind(':caso_pais_id', $caso_pais_id);
        DB::bind(':caso_producto_id', $caso_producto_id);
        DB::bind(':reintegroEstado_id', $reintegroEstado_id);
        DB::bind(':reintegro_fechaIngresoSistema_desde', $reintegro_fechaIngresoSistema_desde);
        DB::bind(':reintegro_fechaIngresoSistema_hasta', $reintegro_fechaIngresoSistema_hasta);
        
        return DB::resultados();
    }
    

    // Metodo para listar los reintegros op300
    public static function listar_reintegros_op300($reintegroEstado_id) {
        
        If ($reintegroEstado_id == '') {
            $reintegroEstado_id = NULL;
        }
        
        DB::query("SELECT
                        caso_id,
                        caso_numero,
                        cliente_nombre,
                        product_name,
                        caso_numeroVoucher,
                        totalP as importeAprobadoUSD,
                        caso_agencia
                   FROM reintegros
                       LEFT JOIN casos on caso_id = reintegro_caso_id
                       LEFT JOIN clientes on clientes.cliente_id = casos.caso_cliente_id
                       LEFT JOIN product on product.product_id_interno = casos.caso_producto_id
                       LEFT JOIN (SELECT reintegroItem_id, reintegroItem_reintegro_id, SUM(reintegroItem_importeAprobadoUSD) totalP
                                                     FROM reintegros_items                       
                                GROUP BY reintegroItem_reintegro_id) 
                             AS re_total ON re_total.reintegroItem_reintegro_id = reintegros.reintegro_id
                   WHERE totalP > 300
                     AND reintegro_reintegroEstado_id = COALESCE(:reintegroEstado_id,reintegro_reintegroEstado_id)");
        
        DB::bind(':reintegroEstado_id', $reintegroEstado_id);
        
        return DB::resultados();
    }


    // Metodo para contar la cantidad de Reintegros op300
    public static function listar_reintegros_op300_contar($reintegroEstado_id) {
        
        If ($reintegroEstado_id == '') {
            $reintegroEstado_id = NULL;
        }
        
        DB::query("SELECT COUNT(*) AS registros FROM
                    (SELECT
                        reintegro_id,                        
                        totalP as importeAprobadoUSD                        
                    FROM reintegros                       
                       LEFT JOIN (SELECT reintegroItem_id, reintegroItem_reintegro_id, SUM(reintegroItem_importeAprobadoUSD) totalP
                                  FROM reintegros_items                       
                                  GROUP BY reintegroItem_reintegro_id) 
                             AS re_total ON re_total.reintegroItem_reintegro_id = reintegros.reintegro_id
                    WHERE totalP > 300
                      AND reintegro_reintegroEstado_id = COALESCE(:reintegroEstado_id,reintegro_reintegroEstado_id)
                    ) AS reintegros_encontrados");
        
        DB::bind(':reintegroEstado_id', $reintegroEstado_id);
        
        return DB::resultado();
    }


    // Metodo para listar los reintegros op300 excel
    public static function listar_reintegros_op300_excel($reintegroEstado_id) {
        
        If ($reintegroEstado_id == '') {
            $reintegroEstado_id = NULL;
        }
        
        DB::query("SELECT
                        caso_numero,
                        cliente_nombre,
                        product_name,
                        caso_numeroVoucher,
                        totalP as importeAprobadoUSD,
                        caso_agencia
                   FROM reintegros
                       LEFT JOIN casos on caso_id = reintegro_caso_id
                       LEFT JOIN clientes on clientes.cliente_id = casos.caso_cliente_id
                       LEFT JOIN product on product.product_id_interno = casos.caso_producto_id
                       LEFT JOIN (SELECT reintegroItem_id, reintegroItem_reintegro_id, SUM(reintegroItem_importeAprobadoUSD) totalP
                                                     FROM reintegros_items                       
                                GROUP BY reintegroItem_reintegro_id) 
                             AS re_total ON re_total.reintegroItem_reintegro_id = reintegros.reintegro_id
                   WHERE totalP > 300
                     AND reintegro_reintegroEstado_id = COALESCE(:reintegroEstado_id,reintegro_reintegroEstado_id)");
        
        DB::bind(':reintegroEstado_id', $reintegroEstado_id);
        
        return DB::resultados();
    }

    
    // Método para listar los reintegros aplicando el filtro del buscador MODELO1
    public static function listar_reintegros_modelo1($caso_numero_desde,
                                                     $caso_numero_hasta,
                                                     $reintegro_fechaPresentacion_desde,
                                                     $reintegro_fechaPresentacion_hasta,
                                                     $reintegro_fechaAuditado_desde,
                                                     $reintegro_fechaAuditado_hasta,
                                                     $reintegro_fechaPago_desde,
                                                     $reintegro_fechaPago_hasta) {
        
        if ($caso_numero_desde == '') {
            $caso_numero_desde = NULL;
        }

        if ($caso_numero_hasta == '') {
            $caso_numero_hasta = NULL;
        }
        
        // Seteo automatico del filtro Reintegro Estado en Pendiente Pago
        $reintegroEstado_id = 5;
        
        // Seteo automatico del filtro Forma de Pago en Transferencia Bancaria
        $formaPago_id = 1;
        
        if (!empty($reintegro_fechaPresentacion_desde)) {
            $reintegro_fechaPresentacion_desde = date('Y-m-d', strtotime($reintegro_fechaPresentacion_desde));
        } else {
            $reintegro_fechaPresentacion_desde = NULL;
        }
        if (!empty($reintegro_fechaPresentacion_hasta)) {
            $reintegro_fechaPresentacion_hasta = date('Y-m-d 23:59:59', strtotime($reintegro_fechaPresentacion_hasta));
        } else {
            $reintegro_fechaPresentacion_hasta = NULL;
        }
        if (!empty($reintegro_fechaAuditado_desde)) {
            $reintegro_fechaAuditado_desde = date('Y-m-d', strtotime($reintegro_fechaAuditado_desde));
        } else {
            $reintegro_fechaAuditado_desde = NULL;
        }
        if (!empty($reintegro_fechaAuditado_hasta)) {
            $reintegro_fechaAuditado_hasta = date('Y-m-d 23:59:59', strtotime($reintegro_fechaAuditado_hasta));
        } else {
            $reintegro_fechaAuditado_hasta = NULL;
        }
        if (!empty($reintegro_fechaPago_desde)) {
            $reintegro_fechaPago_desde = date('Y-m-d', strtotime($reintegro_fechaPago_desde));
        } else {
            $reintegro_fechaPago_desde = NULL;
        }
        if (!empty($reintegro_fechaPago_hasta)) {
            $reintegro_fechaPago_hasta = date('Y-m-d 23:59:59', strtotime($reintegro_fechaPago_hasta));
        } else {
            $reintegro_fechaPago_hasta = NULL;
        }
                         
        DB::query("SELECT 				    
                        caso_numero,
                        reintegroEstado_nombre,                      
                        DATE_FORMAT(reintegro_fechaPresentacion, '%d-%m-%Y') as reintegro_fechaPresentacion,
                        usuario_nombre
                    FROM reintegros
                        LEFT JOIN reintegros_estados on reintegroEstado_id = reintegro_reintegroEstado_id
                        LEFT JOIN casos on caso_id = reintegro_caso_id
                        LEFT JOIN usuarios ON usuario_id = reintegro_usuario_id
                        LEFT JOIN formas_pagos ON formaPago_id = reintegro_formaPago_id
                    WHERE caso_numero BETWEEN COALESCE(:caso_numero_desde,caso_numero) AND COALESCE(:caso_numero_hasta,caso_numero)
                      AND reintegro_reintegroEstado_id = :reintegroEstado_id
                      AND reintegro_formaPago_id = :formaPago_id
                      AND reintegro_fechaPresentacion between COALESCE(:reintegro_fechaPresentacion_desde,reintegro_fechaPresentacion) and COALESCE(:reintegro_fechaPresentacion_hasta,reintegro_fechaPresentacion)
                      AND (CASE WHEN (:reintegro_fechaAuditado_desde is not null) and (:reintegro_fechaAuditado_hasta is not null) 
                                    THEN (reintegro_fechaAuditado between :reintegro_fechaAuditado_desde and :reintegro_fechaAuditado_hasta)
				WHEN (:reintegro_fechaAuditado_desde is not null) and (:reintegro_fechaAuditado_hasta is null)
                                    THEN (reintegro_fechaAuditado between :reintegro_fechaAuditado_desde and reintegro_fechaAuditado) 
				ELSE (reintegro_id = reintegro_id) 
                           END)
                      AND (CASE WHEN (:reintegro_fechaPago_desde is not null) and (:reintegro_fechaPago_hasta is not null) 
                                    THEN (reintegro_fechaPago between :reintegro_fechaPago_desde and :reintegro_fechaPago_hasta)
				WHEN (:reintegro_fechaPago_desde is not null) and (:reintegro_fechaPago_hasta is null)
                                    THEN (reintegro_fechaPago between :reintegro_fechaPago_desde and reintegro_fechaPago) 
				ELSE (reintegro_id = reintegro_id) 
                           END)
                 ");
        
        DB::bind(':caso_numero_desde', $caso_numero_desde);
        DB::bind(':caso_numero_hasta', $caso_numero_hasta);
        DB::bind(':reintegroEstado_id', $reintegroEstado_id);
        DB::bind(':formaPago_id', $formaPago_id);
        DB::bind(':reintegro_fechaPresentacion_desde', $reintegro_fechaPresentacion_desde);
        DB::bind(':reintegro_fechaPresentacion_hasta', $reintegro_fechaPresentacion_hasta);
        DB::bind(':reintegro_fechaAuditado_desde', $reintegro_fechaAuditado_desde);
        DB::bind(':reintegro_fechaAuditado_hasta', $reintegro_fechaAuditado_hasta);
        DB::bind(':reintegro_fechaPago_desde', $reintegro_fechaPago_desde);
        DB::bind(':reintegro_fechaPago_hasta', $reintegro_fechaPago_hasta);
        
        return DB::resultados();
    }   
    
    
    // Método para contar la cantidad de Reintegros MODELO1
    public static function listar_reintegros_modelo1_contar($caso_numero_desde, 
                                                            $caso_numero_hasta, 
                                                            $reintegro_fechaPresentacion_desde,
                                                            $reintegro_fechaPresentacion_hasta,
                                                            $reintegro_fechaAuditado_desde,
                                                            $reintegro_fechaAuditado_hasta,
                                                            $reintegro_fechaPago_desde,
                                                            $reintegro_fechaPago_hasta) {
        
        if ($caso_numero_desde == '') {
            $caso_numero_desde = NULL;
        }

        if ($caso_numero_hasta == '') {
            $caso_numero_hasta = NULL;
        }

        // Seteo automatico del filtro Reintegro Estado en Pendiente Pago
        $reintegroEstado_id = 5;
       
        // Seteo automatico del filtro Forma de Pago en Transferencia Bancaria
        $formaPago_id = 1;      
         
        if (!empty($reintegro_fechaPresentacion_desde)) {
            $reintegro_fechaPresentacion_desde = date('Y-m-d', strtotime($reintegro_fechaPresentacion_desde));
        } else {
            $reintegro_fechaPresentacion_desde = NULL;
        }
        if (!empty($reintegro_fechaPresentacion_hasta)) {
            $reintegro_fechaPresentacion_hasta = date('Y-m-d 23:59:59', strtotime($reintegro_fechaPresentacion_hasta));
        } else {
            $reintegro_fechaPresentacion_hasta = NULL;
        }
        if (!empty($reintegro_fechaAuditado_desde)) {
            $reintegro_fechaAuditado_desde = date('Y-m-d', strtotime($reintegro_fechaAuditado_desde));
        } else {
            $reintegro_fechaAuditado_desde = NULL;
        }
        if (!empty($reintegro_fechaAuditado_hasta)) {
            $reintegro_fechaAuditado_hasta = date('Y-m-d 23:59:59', strtotime($reintegro_fechaAuditado_hasta));
        } else {
            $reintegro_fechaAuditado_hasta = NULL;
        }
        if (!empty($reintegro_fechaPago_desde)) {
            $reintegro_fechaPago_desde = date('Y-m-d', strtotime($reintegro_fechaPago_desde));
        } else {
            $reintegro_fechaPago_desde = NULL;
        }
        if (!empty($reintegro_fechaPago_hasta)) {
            $reintegro_fechaPago_hasta = date('Y-m-d 23:59:59', strtotime($reintegro_fechaPago_hasta));
        } else {
            $reintegro_fechaPago_hasta = NULL;
        }
                    
        DB::query("SELECT COUNT(*) AS registros FROM
                    (SELECT caso_numero
                    FROM reintegros
                        LEFT JOIN reintegros_estados on reintegroEstado_id = reintegro_reintegroEstado_id
                        LEFT JOIN casos on caso_id = reintegro_caso_id
                        LEFT JOIN usuarios ON usuario_id = reintegro_usuario_id
                        LEFT JOIN formas_pagos ON formaPago_id = reintegro_formaPago_id
                    WHERE caso_numero BETWEEN COALESCE(:caso_numero_desde,caso_numero) AND COALESCE(:caso_numero_hasta,caso_numero)
                      AND reintegro_reintegroEstado_id = :reintegroEstado_id
                      AND reintegro_formaPago_id = :formaPago_id
                      AND reintegro_fechaPresentacion between COALESCE(:reintegro_fechaPresentacion_desde,reintegro_fechaPresentacion) and COALESCE(:reintegro_fechaPresentacion_hasta,reintegro_fechaPresentacion)
                      AND (CASE WHEN (:reintegro_fechaAuditado_desde is not null) and (:reintegro_fechaAuditado_hasta is not null) 
                                    THEN (reintegro_fechaAuditado between :reintegro_fechaAuditado_desde and :reintegro_fechaAuditado_hasta)
				WHEN (:reintegro_fechaAuditado_desde is not null) and (:reintegro_fechaAuditado_hasta is null)
                                    THEN (reintegro_fechaAuditado between :reintegro_fechaAuditado_desde and reintegro_fechaAuditado) 
				ELSE (reintegro_id = reintegro_id) 
                           END)
                      AND (CASE WHEN (:reintegro_fechaPago_desde is not null) and (:reintegro_fechaPago_hasta is not null) 
                                    THEN (reintegro_fechaPago between :reintegro_fechaPago_desde and :reintegro_fechaPago_hasta)
				WHEN (:reintegro_fechaPago_desde is not null) and (:reintegro_fechaPago_hasta is null)
                                    THEN (reintegro_fechaPago between :reintegro_fechaPago_desde and reintegro_fechaPago) 
				ELSE (reintegro_id = reintegro_id) 
                           END)
                      ) AS reintegros_encontradas");
        
        DB::bind(':caso_numero_desde', $caso_numero_desde);
        DB::bind(':caso_numero_hasta', $caso_numero_hasta);
        DB::bind(':reintegroEstado_id', $reintegroEstado_id);
        DB::bind(':formaPago_id', $formaPago_id);
        DB::bind(':reintegro_fechaPresentacion_desde', $reintegro_fechaPresentacion_desde);
        DB::bind(':reintegro_fechaPresentacion_hasta', $reintegro_fechaPresentacion_hasta);
        DB::bind(':reintegro_fechaAuditado_desde', $reintegro_fechaAuditado_desde);
        DB::bind(':reintegro_fechaAuditado_hasta', $reintegro_fechaAuditado_hasta);
        DB::bind(':reintegro_fechaPago_desde', $reintegro_fechaPago_desde);
        DB::bind(':reintegro_fechaPago_hasta', $reintegro_fechaPago_hasta);
        
        return DB::resultado();
    }

    
    // Método para listar los reintegros aplicando el filtro del buscador MODELO1
    public static function listar_reintegros_modelo1excel($caso_numero_desde,
                                                          $caso_numero_hasta,
                                                          $reintegro_fechaPresentacion_desde,
                                                          $reintegro_fechaPresentacion_hasta,
                                                          $reintegro_fechaAuditado_desde,
                                                          $reintegro_fechaAuditado_hasta,
                                                          $reintegro_fechaPago_desde,
                                                          $reintegro_fechaPago_hasta) {
        
        if ($caso_numero_desde == '') {
            $caso_numero_desde = NULL;
        }

        if ($caso_numero_hasta == '') {
            $caso_numero_hasta = NULL;
        }

        // Seteo automatico del filtro Reintegro Estado en Pendiente Pago
        $reintegroEstado_id = 5;

        // Seteo automatico del filtro Forma de Pago en Transferencia Bancaria
        $formaPago_id = 1;     

        if (!empty($reintegro_fechaPresentacion_desde)) {
            $reintegro_fechaPresentacion_desde = date('Y-m-d', strtotime($reintegro_fechaPresentacion_desde));
        } else {
            $reintegro_fechaPresentacion_desde = NULL;
        }
        if (!empty($reintegro_fechaPresentacion_hasta)) {
            $reintegro_fechaPresentacion_hasta = date('Y-m-d 23:59:59', strtotime($reintegro_fechaPresentacion_hasta));
        } else {
            $reintegro_fechaPresentacion_hasta = NULL;
        }
        if (!empty($reintegro_fechaAuditado_desde)) {
            $reintegro_fechaAuditado_desde = date('Y-m-d', strtotime($reintegro_fechaAuditado_desde));
        } else {
            $reintegro_fechaAuditado_desde = NULL;
        }
        if (!empty($reintegro_fechaAuditado_hasta)) {
            $reintegro_fechaAuditado_hasta = date('Y-m-d 23:59:59', strtotime($reintegro_fechaAuditado_hasta));
        } else {
            $reintegro_fechaAuditado_hasta = NULL;
        }
        if (!empty($reintegro_fechaPago_desde)) {
            $reintegro_fechaPago_desde = date('Y-m-d', strtotime($reintegro_fechaPago_desde));
        } else {
            $reintegro_fechaPago_desde = NULL;
        }
        if (!empty($reintegro_fechaPago_hasta)) {
            $reintegro_fechaPago_hasta = date('Y-m-d 23:59:59', strtotime($reintegro_fechaPago_hasta));
        } else {
            $reintegro_fechaPago_hasta = NULL;
        }
                         
        DB::query("SELECT
                        reintegro_id,
                        reintegro_CBUcuenta,
                        reintegro_importeARS,
                        caso_beneficiarioNombre,
                        reintegros.reintegro_beneficiarioDocumento,
                        reintegro_emailTexto,
                        totalP as importeAprobadoUSD,
                        tipoAsistencia_nombre,
                        CONCAT(usuario_nombre, ' ',usuario_apellido) AS usuario,
                        caso_agencia,
                        caso_numero
                    FROM reintegros
                        LEFT JOIN reintegros_estados on reintegroEstado_id = reintegro_reintegroEstado_id
                        LEFT JOIN casos on caso_id = reintegro_caso_id
                        LEFT JOIN tipos_asistencias on tipos_asistencias.tipoAsistencia_id = casos.caso_tipoAsistencia_id
                        LEFT JOIN usuarios on usuarios.usuario_id = casos.caso_coordinadorCaso_id
                        LEFT JOIN formas_pagos ON formaPago_id = reintegro_formaPago_id
                        LEFT JOIN (SELECT reintegroItem_id, reintegroItem_reintegro_id, SUM(ri_movimientos.riMov_importeAprobadoUSD) totalP
				   FROM reintegros_items
                                        LEFT JOIN ri_movimientos ON ri_movimientos.riMov_reintegroItem_id = reintegros_items.reintegroItem_id
                                            INNER JOIN (select max(riMov_id) as ultimoMov
                                                        from ri_movimientos 
                                                        group by riMov_reintegroItem_id) 
                                                    AS ultimoMov on ultimoMov.ultimoMov = ri_movimientos.riMov_id
                                   GROUP BY reintegroItem_reintegro_id) 
                               AS re_total ON re_total.reintegroItem_reintegro_id = reintegros.reintegro_id
                    WHERE caso_numero BETWEEN COALESCE(:caso_numero_desde,caso_numero) AND COALESCE(:caso_numero_hasta,caso_numero)
                      AND reintegro_reintegroEstado_id = :reintegroEstado_id
                      AND reintegro_formaPago_id = :formaPago_id
                      AND reintegro_fechaPresentacion between COALESCE(:reintegro_fechaPresentacion_desde,reintegro_fechaPresentacion) and COALESCE(:reintegro_fechaPresentacion_hasta,reintegro_fechaPresentacion)
                      AND (CASE WHEN (:reintegro_fechaAuditado_desde is not null) and (:reintegro_fechaAuditado_hasta is not null) 
                                    THEN (reintegro_fechaAuditado between :reintegro_fechaAuditado_desde and :reintegro_fechaAuditado_hasta)
				WHEN (:reintegro_fechaAuditado_desde is not null) and (:reintegro_fechaAuditado_hasta is null)
                                    THEN (reintegro_fechaAuditado between :reintegro_fechaAuditado_desde and reintegro_fechaAuditado) 
				ELSE (reintegro_id = reintegro_id) 
                           END)
                      AND (CASE WHEN (:reintegro_fechaPago_desde is not null) and (:reintegro_fechaPago_hasta is not null) 
                                    THEN (reintegro_fechaPago between :reintegro_fechaPago_desde and :reintegro_fechaPago_hasta)
				WHEN (:reintegro_fechaPago_desde is not null) and (:reintegro_fechaPago_hasta is null)
                                    THEN (reintegro_fechaPago between :reintegro_fechaPago_desde and reintegro_fechaPago) 
				ELSE (reintegro_id = reintegro_id) 
                           END)
                 ");
        
        DB::bind(':caso_numero_desde', $caso_numero_desde);
        DB::bind(':caso_numero_hasta', $caso_numero_hasta);
        DB::bind(':reintegroEstado_id', $reintegroEstado_id);
        DB::bind(':formaPago_id', $formaPago_id);
        DB::bind(':reintegro_fechaPresentacion_desde', $reintegro_fechaPresentacion_desde);
        DB::bind(':reintegro_fechaPresentacion_hasta', $reintegro_fechaPresentacion_hasta);
        DB::bind(':reintegro_fechaAuditado_desde', $reintegro_fechaAuditado_desde);
        DB::bind(':reintegro_fechaAuditado_hasta', $reintegro_fechaAuditado_hasta);
        DB::bind(':reintegro_fechaPago_desde', $reintegro_fechaPago_desde);
        DB::bind(':reintegro_fechaPago_hasta', $reintegro_fechaPago_hasta);
        
        return DB::resultados();
    }


    // Método para calcular totales de los reintegros aplicando el filtro del buscador MODELO1
    public static function totales_reintegros_modelo1excel($caso_numero_desde,
                                                           $caso_numero_hasta,
                                                           $reintegro_fechaPresentacion_desde,
                                                           $reintegro_fechaPresentacion_hasta,
                                                           $reintegro_fechaAuditado_desde,
                                                           $reintegro_fechaAuditado_hasta,
                                                           $reintegro_fechaPago_desde,
                                                           $reintegro_fechaPago_hasta) {
        
        if ($caso_numero_desde == '') {
            $caso_numero_desde = NULL;
        }
        if ($caso_numero_hasta == '') {
            $caso_numero_hasta = NULL;
        }
        
        // Seteo automatico del filtro Reintegro Estado en Pendiente Pago
        $reintegroEstado_id = 5;

        // Seteo automatico del filtro Forma de Pago en Transferencia Bancaria
        $formaPago_id = 1;  

        if (!empty($reintegro_fechaPresentacion_desde)) {
            $reintegro_fechaPresentacion_desde = date('Y-m-d', strtotime($reintegro_fechaPresentacion_desde));
        } else {
            $reintegro_fechaPresentacion_desde = NULL;
        }
        if (!empty($reintegro_fechaPresentacion_hasta)) {
            $reintegro_fechaPresentacion_hasta = date('Y-m-d 23:59:59', strtotime($reintegro_fechaPresentacion_hasta));
        } else {
            $reintegro_fechaPresentacion_hasta = NULL;
        }
        if (!empty($reintegro_fechaAuditado_desde)) {
            $reintegro_fechaAuditado_desde = date('Y-m-d', strtotime($reintegro_fechaAuditado_desde));
        } else {
            $reintegro_fechaAuditado_desde = NULL;
        }
        if (!empty($reintegro_fechaAuditado_hasta)) {
            $reintegro_fechaAuditado_hasta = date('Y-m-d 23:59:59', strtotime($reintegro_fechaAuditado_hasta));
        } else {
            $reintegro_fechaAuditado_hasta = NULL;
        }
        if (!empty($reintegro_fechaPago_desde)) {
            $reintegro_fechaPago_desde = date('Y-m-d', strtotime($reintegro_fechaPago_desde));
        } else {
            $reintegro_fechaPago_desde = NULL;
        }
        if (!empty($reintegro_fechaPago_hasta)) {
            $reintegro_fechaPago_hasta = date('Y-m-d 23:59:59', strtotime($reintegro_fechaPago_hasta));
        } else {
            $reintegro_fechaPago_hasta = NULL;
        }
                         
        DB::query("SELECT
                        'TOTAL ARS',
                        SUM(reintegro_importeARS) as totalImporteARS,
                        NULL,
                        '',
                        'TOTAL USD',
                        SUM(totalP) as totalAprobadoUSD
                    FROM reintegros
                        LEFT JOIN reintegros_estados on reintegroEstado_id = reintegro_reintegroEstado_id
                        LEFT JOIN casos on caso_id = reintegro_caso_id
                        LEFT JOIN usuarios ON usuario_id = reintegro_usuario_id
                        LEFT JOIN formas_pagos ON formaPago_id = reintegro_formaPago_id
                        LEFT JOIN (SELECT reintegroItem_id, reintegroItem_reintegro_id, SUM(ri_movimientos.riMov_importeAprobadoUSD) totalP
				   FROM reintegros_items
                                        LEFT JOIN ri_movimientos ON ri_movimientos.riMov_reintegroItem_id = reintegros_items.reintegroItem_id
                                            INNER JOIN (select max(riMov_id) as ultimoMov
                                                        from ri_movimientos 
                                                        group by riMov_reintegroItem_id) 
                                                    AS ultimoMov on ultimoMov.ultimoMov = ri_movimientos.riMov_id
                                   GROUP BY reintegroItem_reintegro_id) 
                               AS re_total ON re_total.reintegroItem_reintegro_id = reintegros.reintegro_id
                    WHERE caso_numero BETWEEN COALESCE(:caso_numero_desde,caso_numero) AND COALESCE(:caso_numero_hasta,caso_numero)
                      AND reintegro_reintegroEstado_id = :reintegroEstado_id
                      AND reintegro_formaPago_id = :formaPago_id
                      AND reintegro_fechaPresentacion between COALESCE(:reintegro_fechaPresentacion_desde,reintegro_fechaPresentacion) and COALESCE(:reintegro_fechaPresentacion_hasta,reintegro_fechaPresentacion)
                      AND (CASE WHEN (:reintegro_fechaAuditado_desde is not null) and (:reintegro_fechaAuditado_hasta is not null) 
                                    THEN (reintegro_fechaAuditado between :reintegro_fechaAuditado_desde and :reintegro_fechaAuditado_hasta)
				WHEN (:reintegro_fechaAuditado_desde is not null) and (:reintegro_fechaAuditado_hasta is null)
                                    THEN (reintegro_fechaAuditado between :reintegro_fechaAuditado_desde and reintegro_fechaAuditado) 
				ELSE (reintegro_id = reintegro_id) 
                           END)
                      AND (CASE WHEN (:reintegro_fechaPago_desde is not null) and (:reintegro_fechaPago_hasta is not null) 
                                    THEN (reintegro_fechaPago between :reintegro_fechaPago_desde and :reintegro_fechaPago_hasta)
				WHEN (:reintegro_fechaPago_desde is not null) and (:reintegro_fechaPago_hasta is null)
                                    THEN (reintegro_fechaPago between :reintegro_fechaPago_desde and reintegro_fechaPago) 
				ELSE (reintegro_id = reintegro_id) 
                           END)
                 ");
        
        DB::bind(':caso_numero_desde', $caso_numero_desde);
        DB::bind(':caso_numero_hasta', $caso_numero_hasta);
        DB::bind(':reintegroEstado_id', $reintegroEstado_id);
        DB::bind(':formaPago_id', $formaPago_id);
        DB::bind(':reintegro_fechaPresentacion_desde', $reintegro_fechaPresentacion_desde);
        DB::bind(':reintegro_fechaPresentacion_hasta', $reintegro_fechaPresentacion_hasta);
        DB::bind(':reintegro_fechaAuditado_desde', $reintegro_fechaAuditado_desde);
        DB::bind(':reintegro_fechaAuditado_hasta', $reintegro_fechaAuditado_hasta);
        DB::bind(':reintegro_fechaPago_desde', $reintegro_fechaPago_desde);
        DB::bind(':reintegro_fechaPago_hasta', $reintegro_fechaPago_hasta);
        
        return DB::resultado();
    }


    // Método para listar los reintegros para generar la Orden de Pago
    public static function listar_reintegro_ordenPago() {

        DB::query("SELECT reint.reintegro_id, 
                            casos.caso_numero, 
                            reint.reintegro_fechaIngresoSistema, 
                            reint.reintegro_fechaPresentacion, 
                            reintegro_importeARS AS total,
                            reint.reintegro_CBUcuenta as cbu_cuenta
                    FROM reintegros AS reint
                        LEFT JOIN reintegros_items ON reintegros_items.reintegroItem_reintegro_id = reint.reintegro_id
                        LEFT JOIN ri_movimientos ON ri_movimientos.riMov_reintegroItem_id = reintegros_items.reintegroItem_id
                        LEFT JOIN casos ON casos.caso_id = reint.reintegro_caso_id
                    WHERE (reint.reintegro_reintegroEstado_id = 4)
                    GROUP BY reintegro_id");

        return DB::resultados();
    }


    // Método para contar en la lista de los reintegros para generar la Orden de Pago
    public static function listar_reintegro_ordenPago_contar() {

        DB::query("SELECT COUNT(reint.reintegro_id) AS registros
                     FROM reintegros AS reint
                    WHERE reint.reintegro_reintegroEstado_id = 4");

        return DB::resultado();
    }


    // Método para mostrar el total de la lista de Reintegros en Orden de Pago
    public static function reintegros_importeAprobadoARS_total($rim_seleccionados) {
                                
        If ($rim_seleccionados == '') {
            $rim_seleccionados = 0;
        }
        
        DB::query("SELECT SUM(importeAprobadoARS.total) AS importeAprobadoARS_total
                     FROM(
                            SELECT reintegros.reintegro_id, 
                                    reintegro_importeARS AS total
                            FROM reintegros
                               LEFT JOIN reintegros_items ON reintegros_items.reintegroItem_reintegro_id = reintegros.reintegro_id
                               LEFT JOIN ri_movimientos ON ri_movimientos.riMov_reintegroItem_id = reintegros_items.reintegroItem_id
                            WHERE reintegros.reintegro_reintegroEstado_id = 4
                            GROUP BY reintegro_id ) as importeAprobadoARS
                    WHERE importeAprobadoARS.reintegro_id IN (" .$rim_seleccionados. ")");
        
        return DB::resultado();
    }
    
    // Método para listar los reintegros seleccionados para generar la Orden de Pago en excel
    public static function listar_reintegro_ordenPago_op($rim_seleccionados) {

        If ($rim_seleccionados == '') {
            $rim_seleccionados = 0;
        }
        
        DB::query("SELECT reintegros.reintegro_CBUcuenta,
                            reintegros.reintegro_CBUalias,
                            reintegro_importeARS,
                            reintegros.reintegro_denominacion,
                            tipos_documentos.tipoDocumento_nombre,
                            reintegros.reintegro_beneficiarioDocumento,
                            re_tipos_referencia.re_tipoReferencia_nombre,
                            reintegros.reintegro_referencia,
                            re_tipos_avisotransferencia.re_tipoAvisoTrans_nombre,
                            reintegros.reintegro_emailDestinatario,
                            reintegros.reintegro_emailTexto,
                            case when reintegros.reintegro_compania = 0 then ''
		 		when reintegros.reintegro_compania = 1 then 'CLA'
		 		when reintegros.reintegro_compania = 2 then 'MOV'
		 		when reintegros.reintegro_compania = 3 then 'PER' end as reintegro_compania,
                            reintegros.reintegro_codigoArea,
                            reintegros.reintegro_telefono       
                     FROM reintegros
                            LEFT JOIN tipos_documentos ON tipos_documentos.tipoDocumento_id = reintegros.reintegro_documentoTipo_id
                            LEFT JOIN re_tipos_referencia ON re_tipos_referencia.re_tipoReferencia_id = reintegros.reintegro_referenciaTipo_id
                            LEFT JOIN re_tipos_avisotransferencia ON re_tipos_avisotransferencia.re_tipoAvisoTrans_id = reintegros.reintegro_avisoTransTipo_id
                    WHERE reintegros.reintegro_reintegroEstado_id = 4 AND (reintegros.reintegro_CBUcuenta IS NOT NULL AND reintegros.reintegro_CBUalias IS NOT NULL)
                      AND reintegros.reintegro_id IN (" .$rim_seleccionados. ")
                 GROUP BY reintegro_id");

        return DB::resultados();
    }
    
    // Método para sumar totales de los reintegros seleccionados para generar la Orden de Pago en excel
    public static function totales_reintegro_ordenPago_op($rim_seleccionados) {

        If ($rim_seleccionados == '') {
            $rim_seleccionados = 0;
        }
        
        DB::query("SELECT reintegro_importeARS
                    FROM reintegros AS reint
                    WHERE reint.reintegro_reintegroEstado_id = 4 AND (reint.reintegro_CBUcuenta IS NOT NULL AND reint.reintegro_CBUalias IS NOT NULL)
                      AND reint.reintegro_id IN (" .$rim_seleccionados. ")");

        return DB::resultado();
    }
    
    // Método para modificar el estado de los reintegros seleccionados
    public static function update_reintegros_pendientePago($rim_seleccionados) {

        If ($rim_seleccionados == '') {
            $rim_seleccionados = 0;
        }
        
        DB::query("UPDATE reintegros SET reintegros.reintegro_reintegroEstado_id = 5
                                   WHERE reintegros.reintegro_id IN (" .$rim_seleccionados. ")
                   ");

        DB::execute();
    }


    // Método para contar en la lista de los reintegros para el pago
    public static function listar_reintegro_pago_contar() {

        DB::query("SELECT COUNT(reint.reintegro_id) AS registros
                    FROM reintegros AS reint
                    WHERE reint.reintegro_reintegroEstado_id = 5");

        return DB::resultado();
    }


    // Método para listar los reintegros para el pago
    public static function listar_reintegro_pago() {

        DB::query("SELECT reint.reintegro_id, 
                            casos.caso_numero, 
                            reint.reintegro_fechaIngresoSistema, 
                            reint.reintegro_fechaPresentacion, 
                            reintegro_importeARS AS total
                    FROM reintegros AS reint
                        LEFT JOIN reintegros_items ON reintegros_items.reintegroItem_reintegro_id = reint.reintegro_id
                        LEFT JOIN ri_movimientos ON ri_movimientos.riMov_reintegroItem_id = reintegros_items.reintegroItem_id
                        LEFT JOIN casos ON casos.caso_id = reint.reintegro_caso_id
                    WHERE reint.reintegro_reintegroEstado_id = 5
                    GROUP BY reintegro_id");

        return DB::resultados();
    }
    
    // Método para mostrar el total de la lista de Reintegros en Pago de Reintegros
    public static function reintegro_pago_importeAprobadoARS_total($rim_seleccionados) {
                                
        If ($rim_seleccionados == '') {
            $rim_seleccionados = 0;
        }
        
        DB::query("SELECT SUM(importeAprobadoARS.total) AS importeAprobadoARS_total
                     FROM(
                            SELECT reintegros.reintegro_id, 
                                    reintegro_importeARS AS total
                            FROM reintegros
                               LEFT JOIN reintegros_items ON reintegros_items.reintegroItem_reintegro_id = reintegros.reintegro_id
                               LEFT JOIN ri_movimientos ON ri_movimientos.riMov_reintegroItem_id = reintegros_items.reintegroItem_id
                            WHERE reintegros.reintegro_reintegroEstado_id = 5
                            GROUP BY reintegro_id ) as importeAprobadoARS
                    WHERE importeAprobadoARS.reintegro_id IN (" .$rim_seleccionados. ")
                 ");
        
        return DB::resultado();
    }
    
        
    // Método para modificar el estado de los reintegros seleccionados
    public static function update_reintegros_abonados($rim_seleccionados, $reintegro_fechaPago) {

        If ($rim_seleccionados == '') {
            $rim_seleccionados = 0;
        }
        // Formateo de Fechas para el INSERT
        $reintegro_fechaPago = Herramientas::fecha_formateo($reintegro_fechaPago);
        
        DB::query("UPDATE reintegros SET reintegros.reintegro_reintegroEstado_id = 6,
                                         reintegros.reintegro_fechaPago = :reintegro_fechaPago            
                                   WHERE reintegros.reintegro_id IN (" .$rim_seleccionados. ")
                   ");
        
        DB::bind(':reintegro_fechaPago', "$reintegro_fechaPago");

        DB::execute();
    }
    
}
