<?php
/**
 * Clase: Facturacion
 *
 *
 * @author ArgenCode
 */

class Facturacion {
    
    // Metodos de busqueda y listados
    // 
    // Método para listar las facturas
    public static function listar($factura_numero_buscar,
        $factura_final_buscar,
                                  $fc_caso_numero_buscar,
                                  $factura_prestador_buscar) {

        if ($fc_caso_numero_buscar == '') {
            $fc_caso_numero_buscar = NULL;
        }
        if ($factura_prestador_buscar == '') {
          $factura_prestador_buscar = NULL;
        }

        $where = '';
        if ($factura_final_buscar != '') {
            $where =  " AND fc_items.fcItem_facturaNuevo_id LIKE '%" . $factura_final_buscar . "%' ";
        }


        DB::query("SELECT facturas.factura_id as idFactura,
                          facturas_estados.facturaEstado_id as estadoId,
                          facturas_estados.facturaEstado_nombre as estadoNombre,
                          prestadores.prestador_nombre as prestador,
                          facturas.factura_numero as numeroFactura,
                          facturas_prioridades.facturaPrioridad_nombre as prioridadFactura
                   FROM facturas
                         LEFT JOIN fc_items ON fcItem_factura_id = factura_Id
                         LEFT JOIN facturas_estados ON facturaEstado_id = factura_facturaEstado_id
                         LEFT JOIN prestadores ON prestador_id = factura_prestador_id
                         LEFT JOIN facturas_prioridades on facturaPrioridad_id = factura_prioridad_id
                   WHERE factura_numero LIKE :factura_numero 
                     " . $where . "
                     AND factura_prestador_id = COALESCE(:factura_prestador_id, factura_prestador_id)
                     AND (CASE WHEN :caso_numero is not null 
                             THEN (fcItem_caso_id = (select caso_id from casos where caso_numero = :caso_numero))
                             ELSE (factura_id = factura_id)
                          END)
                   GROUP BY factura_id");


        DB::bind(':factura_numero', "%$factura_numero_buscar%");
        DB::bind(':caso_numero', $fc_caso_numero_buscar);
        DB::bind(':factura_prestador_id', $factura_prestador_buscar);

        return DB::resultados();
    }
    
    
    // Método para mostrar la información de una factura
    public static function buscarPorId($factura_id) {
        
        DB::query("SELECT facturas.factura_id,
                          prestadores.prestador_nombre as prestador,
                          facturas.factura_prestador_id as idPrestador,
                          facturas.factura_numero as numeroFactura,
                          facturas.factura_facturaEstado_id estadoFacturaId,
                          facturas_estados.facturaEstado_nombre as estadoFactura,
                          facturas_prioridades.facturaPrioridad_nombre as prioridadFactura, 
                          facturas.factura_fechaIngresoSistema as fechaIngresoSistemaFactura,                           
                          facturas.factura_fechaEmision as fechaEmisionFactura, 
                          facturas.factura_fechaRecepcion as fechaRecepcionFactura, 
                          facturas.factura_fechaVencimiento as fechaVencimientoFactura, 
                          facturas.factura_observaciones as observacionesFactura
                    FROM facturas
                        LEFT JOIN prestadores ON prestadores.prestador_id = facturas.factura_prestador_id
                        LEFT JOIN facturas_prioridades ON facturas_prioridades.facturaPrioridad_id = facturas.factura_prioridad_id
                        LEFT JOIN facturas_estados ON facturas_estados.facturaEstado_id = factura_facturaEstado_id
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
        
        return $resultado;       
    }

    // Método para listar los ITEMS de FACTURAS
    public static function listar_items_facturas($factura_id, $order, $pagador, $factura_final)
    {
        $where = '';
        if ($pagador > 0) {
            $where = 'AND (fci.fcItem_clientePagador_id=' . $pagador . ' OR fci.fcItem_clientePagadorNuevo_id=' . $pagador . ') ';
        }

        if ($factura_final != '') {
            $where = $where . " AND fci.fcItem_facturaNuevo_id like '%" . $factura_final . "%' ";
        }

        DB::query("SELECT fci.fcItem_id AS fci_id,
                            facturas.factura_numero AS facturaNumero,
                            fci.fcItem_estado_id AS estadoId,
                            fci_movimientos_estados.fciMovEstado_nombre AS estado,                         
                            fci_movimientos_estados.fciMovEstado_sector AS estadoSector,                                                   
                            casos.caso_id AS casoId,
                            casos.caso_numero AS casoNumero, 
                            clientes.cliente_abreviatura AS pagador,
                            pagador_final.cliente_abreviatura AS pagador_final,
                            fci.fcItem_facturaNuevo_id AS factura_final, 
                            usuarios.usuario_nombre AS usuarioNombre, 
                            usuarios.usuario_apellido AS usuarioApellido, 
                            fci.fcItem_fechaIngresoSistema AS fechaIngresoSistema, 
                            monedas.moneda_nombre AS moneda, 
                            fci.fcItem_importeMedicoOrigen AS importeMedico, 
                            fci.fcItem_importeFeeOrigen AS importeFee,
                            fci.fcItem_descuento AS descuento,
                            fci.fcItem_tipoCambio AS tipoCambio, 
                            fci.fcItem_importeUSD AS importeUSD,
                            fci.fcItem_importeAprobadoUSD AS impAprobadoUSD,
                            fci.fcItem_importeAprobadoOrigen AS impAprobadoOrigen,
                            fci_serv.cantServ
                    FROM fc_items AS fci
                        LEFT JOIN facturas ON facturas.factura_Id = fci.fcItem_factura_id
                        LEFT JOIN casos ON casos.caso_id = fci.fcItem_caso_id
                        LEFT JOIN clientes ON clientes.cliente_id = fci.fcItem_clientePagador_id
                        LEFT JOIN clientes AS pagador_final ON pagador_final.cliente_id = fci.fcItem_clientePagadorNuevo_id
                        LEFT JOIN usuarios ON usuarios.usuario_id = fci.fcItem_usuario_id
                        LEFT JOIN monedas ON monedas.moneda_id = fci.fcItem_monedaOrigen_id
                        LEFT JOIN (SELECT fci_servicios.fciServicio_fcItem_id, COUNT(fci_servicios.fciServicio_id) AS cantServ 
                                    FROM fci_servicios GROUP BY fci_servicios.fciServicio_fcItem_id) AS fci_serv ON fci_serv.fciServicio_fcItem_id = fci.fcItem_id
                        LEFT JOIN fci_movimientos_estados ON fci_movimientos_estados.fciMovEstado_id = fci.fcItem_estado_id
                    WHERE fcItem_factura_id = :factura_id " . $where . " 
                    ORDER BY casos.caso_id " . $order );
                    //ORDER BY fci.fcItem_fechaIngresoSistema" );
        
        DB::bind(':factura_id', $factura_id);
        
        return DB::resultados();
    }
    
    // Método para listar los ITEMS de FACTURAS
    public static function buscarItemPorId($fci_id) {
        
        DB::query("SELECT fcItem_id,
                          fcItem_caso_id,
                          caso_numero,
                          fcItem_factura_id,
                          factura_numero,
                          factura_prestador_id,
                          fcItem_clientePagador_id as pagador_id, 
                          fcItem_importeMedicoOrigen as importeMedico, 
                          fcItem_importeFeeOrigen as importeFee,
                          fcItem_descuento as descuento,
                          fcItem_monedaOrigen_id as moneda_id,
                          fcItem_tipoCambio as tipoCambio, 
                          fcItem_importeUSD as importeUSD                    
                   FROM fc_items
                        LEFT JOIN facturas ON factura_Id = fcItem_factura_id
                        LEFT JOIN casos ON caso_id = fcItem_caso_id
                        LEFT JOIN monedas ON moneda_id = fcItem_monedaOrigen_id                   
                    WHERE fcItem_id = :fcItem_id");
        
        DB::bind(':fcItem_id', $fci_id);
        
        return DB::resultado();
    }
    
    
    /* BORRAR A PARTIR DEL: agosto 2019
    //Método para listar los ITEMS de FACTURAS
    public static function fci_importe_aprobado($fci_id) {
        
        DB::query("SELECT fci_movimientos.fciMov_fcItem_id, 
                          fci_movimientos.fciMov_importeAprobadoUSD as impAprobadoUSD,
                          fci_movimientos.fciMov_importeAprobadoOrigen as impAprobadoOrigen
                   FROM fci_movimientos
                   WHERE fci_movimientos.fciMov_fcItem_id = :fci_id AND (fci_movimientos.fciMov_fciMovEstado_id = 2 OR fci_movimientos.fciMov_fciMovEstado_id = 3)");
        
        DB::bind(':fci_id', $fci_id);
        
        return DB::resultado();
    }*/
    
    // Métodos ABM
    // 
    // Método para insertar una factura
    public static function insertar($factura_prestador_id_n,
                                    $factura_numero_n,
                                    $factura_prioridad_id_n,
                                    $factura_fechaRecepcion_n,
                                    $factura_fechaEmision_n,
                                    $factura_fechaVencimiento_n,
                                    $factura_observaciones_n,
                                    $sesion_usuario_id) {   
        
        
        // Valida por back-end si existe la factura que se intenta crear
        $existe = Self::existe_alta($factura_numero_n, $factura_prestador_id_n);

        if ($existe == false) {

            // Estado automatico de factura al momento del ingreso
            $factura_facturaEstado_id_n = 1;
            
            
            // Formateo de Fechas para el Insert
            $factura_fechaIngresoSistema_n = date("Y-m-d H:i:s"); 

            if (!empty($factura_fechaRecepcion_n)) {
                $factura_fechaRecepcion_n = date('Y-m-d', strtotime($factura_fechaRecepcion_n));
            } else {
                $factura_fechaRecepcion_n = NULL;
            }        
            if (!empty($factura_fechaEmision_n)) {
                $factura_fechaEmision_n = date('Y-m-d', strtotime($factura_fechaEmision_n));
            } else {
                $factura_fechaEmision_n = NULL;
            }        
            if (!empty($factura_fechaVencimiento_n)) {
                $factura_fechaVencimiento_n = date('Y-m-d', strtotime($factura_fechaVencimiento_n));
            } else {
                $factura_fechaVencimiento_n = NULL;
            }        
            if(empty($factura_prioridad_id_n)){
                $factura_prioridad_id_n = 0;
            }
            
            DB::query("INSERT INTO facturas (factura_prestador_id,
                                            factura_facturaEstado_id,           
                                            factura_numero,
                                            factura_prioridad_id,
                                            factura_usuario_id,
                                            factura_fechaIngresoSistema,
                                            factura_fechaEmision,
                                            factura_fechaRecepcion,
                                            factura_fechaVencimiento,
                                            factura_observaciones)
                                    VALUES (:factura_prestador_id_n,
                                            :factura_facturaEstado_id_n,
                                            :factura_numero_n,
                                            :factura_prioridad_id_n,
                                            :sesion_usuario_id,
                                            :factura_fechaIngresoSistema_n,
                                            :factura_fechaEmision_n,
                                            :factura_fechaRecepcion_n,
                                            :factura_fechaVencimiento_n,
                                            :factura_observaciones_n)");
            
            DB::bind(':factura_prestador_id_n', $factura_prestador_id_n);
            DB::bind(':factura_facturaEstado_id_n', $factura_facturaEstado_id_n);
            DB::bind(':factura_numero_n', "$factura_numero_n");
            DB::bind(':factura_prioridad_id_n', $factura_prioridad_id_n);
            DB::bind(':sesion_usuario_id', $sesion_usuario_id);
            DB::bind(':factura_fechaIngresoSistema_n', "$factura_fechaIngresoSistema_n");
            
            if (!empty($factura_fechaEmision_n)) {
                DB::bind(':factura_fechaEmision_n', "$factura_fechaEmision_n");
            } else {
                DB::bind(':factura_fechaEmision_n', $factura_fechaEmision_n);
            }
            
            if (!empty($factura_fechaRecepcion_n)) {
                DB::bind(':factura_fechaRecepcion_n', "$factura_fechaRecepcion_n");
            } else {
                DB::bind(':factura_fechaRecepcion_n', $factura_fechaRecepcion_n);
            }
            
            if (!empty($factura_fechaVencimiento_n)) {
                DB::bind(':factura_fechaVencimiento_n', "$factura_fechaVencimiento_n");
            } else {
                DB::bind(':factura_fechaVencimiento_n', $factura_fechaVencimiento_n);
            }
            
            DB::bind(':factura_observaciones_n', "$factura_observaciones_n");
            
            DB::execute();
            
            return DB::lastInsertId();

        } else {
            return false;
        }
    }
    
    
    //  Método para verificar si existe la factura que se intenta ingresar
    public static function existe_alta($factura_numero, $factura_prestador_id) {
        
        DB::query("SELECT factura_id FROM facturas 
                    WHERE factura_numero = :factura_numero AND factura_prestador_id = :factura_prestador_id");
        
        DB::bind(':factura_numero', "$factura_numero");
        DB::bind(':factura_prestador_id', $factura_prestador_id);
        
        $factura = DB::resultado();
        
        if (!empty($factura) && is_array($factura)) {       
            $existe = true;
        } else {
            $existe= false;            
        }

        return $existe;
    }
    
    
    // Método para actualizar una factura
    public static function actualizar($factura_prestador_id_m,
                                      $factura_numero_m,
                                      $factura_prioridad_id_m,
                                      $factura_fechaRecepcion_m,
                                      $factura_fechaEmision_m,
                                      $factura_fechaVencimiento_m,
                                      $factura_observaciones_m,
                                      $factura_id_m) {
        

        // Valida por back-end si existe la factura que se intenta modificar
        $existe = Self::existe_mod($factura_numero_m, $factura_prestador_id_m, $factura_id_m);

        if ($existe == false) {
        
            // Formateo de Fechas para el Insert
            $factura_fechaModificacion_m = date("Y-m-d H:i:s"); 
            
            if (!empty($factura_fechaRecepcion_m)) {
                $factura_fechaRecepcion_m = date('Y-m-d', strtotime($factura_fechaRecepcion_m));
            } else {
                $factura_fechaRecepcion_m = NULL;
            }
            
            if (!empty($factura_fechaEmision_m)) {
                $factura_fechaEmision_m = date('Y-m-d', strtotime($factura_fechaEmision_m));
            } else {
                $factura_fechaEmision_m = NULL;
            }
            
            if (!empty($factura_fechaVencimiento_m)) {
                $factura_fechaVencimiento_m = date('Y-m-d', strtotime($factura_fechaVencimiento_m));
            } else {
                $factura_fechaVencimiento_m = NULL;
            }
            
            DB::query("UPDATE facturas SET
                            factura_prestador_id = :factura_prestador_id_m,
                            factura_numero = :factura_numero_m,
                            factura_prioridad_id = :factura_prioridad_id_m,
                            factura_fechaModificacion = :factura_fechaModificacion_m,
                            factura_fechaRecepcion = :factura_fechaRecepcion_m,
                            factura_fechaEmision = :factura_fechaEmision_m,
                            factura_fechaVencimiento = :factura_fechaVencimiento_m,
                            factura_observaciones = :factura_observaciones_m
                        WHERE factura_id = :factura_id_m");

            DB::bind(':factura_prestador_id_m', $factura_prestador_id_m);
            DB::bind(':factura_numero_m', "$factura_numero_m");
            DB::bind(':factura_prioridad_id_m', $factura_prioridad_id_m);
            DB::bind(':factura_fechaModificacion_m', "$factura_fechaModificacion_m");
            
            if (!empty($factura_fechaRecepcion_m)) {
                DB::bind(':factura_fechaRecepcion_m', "$factura_fechaRecepcion_m");
            } else {
                DB::bind(':factura_fechaRecepcion_m', $factura_fechaRecepcion_m);
            }
            
            if (!empty($factura_fechaEmision_m)) {
                DB::bind(':factura_fechaEmision_m', "$factura_fechaEmision_m");
            } else {
                DB::bind(':factura_fechaEmision_m', $factura_fechaEmision_m);
            }
            
            if (!empty($factura_fechaVencimiento_m)) {
                DB::bind(':factura_fechaVencimiento_m', "$factura_fechaVencimiento_m");
            } else {
                DB::bind(':factura_fechaVencimiento_m', $factura_fechaVencimiento_m);
            }
            
            DB::bind(':factura_observaciones_m', "$factura_observaciones_m");
            DB::bind(':factura_id_m', $factura_id_m);

            DB::execute();
            
            $mensaje = "La factura fue actualizada con éxito";
            return $mensaje;

        } else {
            return false;
        }
    }

    // Método para actualizar pagador final y numero de factura final en cada item
    public static function actualizar_pagador(
        $nuevo_pagador,
        $nueva_factura,
        $items
    ) {

        if (!empty($items)) {
            $fci_array = explode(
                ",",
                $items
            );

            foreach ($fci_array as $item) {

                DB::query("UPDATE fc_items SET
                            fcItem_clientePagadorNuevo_id = :nuevo_pagador,
                            fcItem_facturaNuevo_id = :nueva_factura
                    WHERE fcItem_id = :fci_id");

                DB::bind(':nuevo_pagador', $nuevo_pagador);
                DB::bind(':nueva_factura', $nueva_factura);
                DB::bind(':fci_id', $item);

                DB::execute();
            }

            $mensaje = "La factura fue actualizada con éxito";
            return $mensaje;
        } else {
            return false;
        }
    }

    //  Método para verificar si existe la factura que se intenta modificar
    public static function existe_mod($factura_numero, $factura_prestador_id, $factura_id) {

        DB::query("SELECT factura_id FROM facturas 
                    WHERE factura_numero = :factura_numero AND factura_prestador_id = :factura_prestador_id AND NOT factura_id = :factura_id");
        
        DB::bind(':factura_numero', "$factura_numero");
        DB::bind(':factura_prestador_id', $factura_prestador_id);
        DB::bind(':factura_id', $factura_id);
        
        $factura = DB::resultado();
        
        if (!empty($factura) && is_array($factura)) {
            $existe = true;
        } else {
            $existe= false;            
        }

        return $existe;
    }
    
    
    // Método para insertar el ITEM de FACTURA
    public static function insertar_fci($fci_caso_id_n,
                                        $fci_factura_id_n,
                                        $fci_caso_n,
                                        $fci_seleccionados,
                                        $fci_pagador_id_n,
                                        $fci_imp_medicoOrigen_n,
                                        $fci_imp_feeOrigen_n,
                                        $fci_descuento_n,
                                        $fci_moneda_id_n,
                                        $fci_tipoCambio_n,
                                        $sesion_usuario_id) {
        
        try {
            DB::conecta_t();
            DB::beginTransaction_t();  // start Transaction
        
            if(empty($fci_descuento_n)){
                $fci_descuento_n = 0;
            }

            // Consulta la fecha de emision para el calculo historico del tipo de cambio
            DB::query_t("SELECT factura_fechaEmision
                        FROM facturas
                        WHERE factura_id = :fci_factura_id_n");
                            
            DB::bind(':fci_factura_id_n', $fci_factura_id_n);

            $resultado = DB::resultado();

            $fecha_emision = $resultado['factura_fechaEmision'];
            
            // Calcula el Tipo de Cambio (historico)
            $fci_tipoCambio_n = Moneda::calculo_tc_history($fci_moneda_id_n, $fecha_emision);

            // Calcula el Importe Real en USD
            $fci_importeUSD_n = (($fci_imp_medicoOrigen_n + $fci_imp_feeOrigen_n) - $fci_descuento_n) / $fci_tipoCambio_n;

            // Primer estado del Item (id. 1)
            $fcItem_estado_id = 1;

            // INSERT INTO fc_items
            DB::query_t("INSERT INTO fc_items (fcItem_caso_id,
                                            fcItem_factura_id,
                                            fcItem_clientePagador_id,
                                            fcItem_usuario_id,
                                            fcItem_monedaOrigen_id,
                                            fcItem_importeMedicoOrigen,
                                            fcItem_importeFeeOrigen,
                                            fcItem_descuento,
                                            fcItem_tipoCambio,
                                            fcItem_importeUSD,
                                            fcItem_estado_id)
                                    VALUES (:fcItem_caso_id_n,
                                            :fcItem_factura_id_n,
                                            :fci_pagador_id_n,
                                            :sesion_usuario_id,
                                            :fci_moneda_id_n,
                                            :fci_imp_medicoOrigen_n,
                                            :fci_imp_feeOrigen_n,
                                            :fci_descuento_n,
                                            :fci_tipoCambio_n,
                                            :fci_importeUSD_n,
                                            :fcItem_estado_id)");

            DB::bind(':fcItem_caso_id_n', $fci_caso_id_n);
            DB::bind(':fcItem_factura_id_n', $fci_factura_id_n);
            DB::bind(':fci_pagador_id_n', $fci_pagador_id_n);
            DB::bind(':sesion_usuario_id', $sesion_usuario_id);
            DB::bind(':fci_moneda_id_n', $fci_moneda_id_n);
            DB::bind(':fci_imp_medicoOrigen_n', $fci_imp_medicoOrigen_n);
            DB::bind(':fci_imp_feeOrigen_n', $fci_imp_feeOrigen_n);
            DB::bind(':fci_descuento_n', $fci_descuento_n);
            DB::bind(':fci_tipoCambio_n', $fci_tipoCambio_n);
            DB::bind(':fci_importeUSD_n', $fci_importeUSD_n);
            DB::bind(':fcItem_estado_id', $fcItem_estado_id);

            DB::execute();
        
            $fciMov_fcItem_id_n = DB::lastInsertId();
        
            // INSERT INTO fci_movimientos
            //         
            // Inserta el primer registro de movimientos para el item de factura creado
            $fciMov_fciMovEstado_id_n = 1;

            DB::query_t("INSERT INTO fci_movimientos (fciMov_fcItem_id,
                                                    fciMov_usuario_id,
                                                    fciMov_fciMovEstado_id)
                                            VALUES (:fciMov_fcItem_id_n,
                                                    :fciMov_usuario_id_n,
                                                    :fciMov_fciMovEstado_id_n)");

            DB::bind(':fciMov_fcItem_id_n', $fciMov_fcItem_id_n);
            DB::bind(':fciMov_usuario_id_n', $sesion_usuario_id);
            DB::bind(':fciMov_fciMovEstado_id_n', $fciMov_fciMovEstado_id_n);

            DB::execute();
        
            // INSERT INTO fci_servicios
            //
            // Insert para asociar el item a los servicios elegidos
            $fci_array_servicios = explode(",", $fci_seleccionados);

            foreach ($fci_array_servicios as $servicio) {

                DB::query_t("INSERT INTO fci_servicios (fciServicio_fcItem_id,
                                                      fciServicio_servicio_id,
                                                      fciServicio_factura_id)
                                              VALUES (:fciServicio_fcItem_id_n,
                                                      :fciServicio_servicio_id_n,
                                                      :fciServicio_factura_id_n)");

                DB::bind(':fciServicio_fcItem_id_n', $fciMov_fcItem_id_n);
                DB::bind(':fciServicio_servicio_id_n', $servicio);
                DB::bind(':fciServicio_factura_id_n', $fci_factura_id_n);

                DB::execute();
            }
        
            // UPDATE facturas
            //
            // Actualiza el estado de la factura
            DB::query_t("UPDATE facturas SET
                            factura_facturaEstado_id = 2
                        WHERE factura_id = :factura_id");

            DB::bind(':factura_id', $fci_factura_id_n);

            DB::execute();
            
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
    
    // Método para modificar un ITEM de FACTURA
    public static function actualizar_fci(//$fci_caso_id_m,
                                          $fci_factura_id_m,
                                          $fci_caso_m,
                                          $fci_seleccionados_m,
                                          $fci_seleccionados_b,
                                          $fci_pagador_id_m,
                                          $fci_imp_medicoOrigen_m,
                                          $fci_imp_feeOrigen_m,
                                          $fci_descuento_m,
                                          $fci_moneda_id_m,
                                          $fci_tipoCambio_m,
                                          $sesion_usuario_id,
                                          $fci_id) {
        
        // UPDATE fc_items
        // 
        // Calcula el Importe Real USD en base a:
        // ((Importe Medico + Importe FEE) - Descuento) / Tipo de Cambio
        $fci_importeUSD_m = (($fci_imp_medicoOrigen_m + $fci_imp_feeOrigen_m) - $fci_descuento_m) / $fci_tipoCambio_m;
        
        // Insert para cargar el nuevo ITEM de Factura en la tabla fc_items
        DB::query("UPDATE fc_items SET
                        fcItem_clientePagador_id = :fci_pagador_id_m,
                        fcItem_usuario_id = :sesion_usuario_id,
                        fcItem_monedaOrigen_id = :fci_moneda_id_m,
                        fcItem_importeMedicoOrigen = :fci_imp_medicoOrigen_m,
                        fcItem_importeFeeOrigen = :fci_imp_feeOrigen_m,
                        fcItem_descuento = :fci_descuento_m,
                        fcItem_tipoCambio = :fci_tipoCambio_m,
                        fcItem_importeUSD = :fci_importeUSD_m
                   WHERE fcItem_id = :fci_id");
        
        //DB::bind(':fcItem_caso_id_m', $fci_caso_id_m);
        //DB::bind(':fcItem_factura_id_m', $fci_factura_id_m);
        DB::bind(':fci_pagador_id_m', $fci_pagador_id_m);
        DB::bind(':sesion_usuario_id', $sesion_usuario_id);
        DB::bind(':fci_moneda_id_m', $fci_moneda_id_m);
        DB::bind(':fci_imp_medicoOrigen_m', $fci_imp_medicoOrigen_m);
        DB::bind(':fci_imp_feeOrigen_m', $fci_imp_feeOrigen_m);
        DB::bind(':fci_descuento_m', $fci_descuento_m);
        DB::bind(':fci_tipoCambio_m', $fci_tipoCambio_m);
        DB::bind(':fci_importeUSD_m', $fci_importeUSD_m);
        DB::bind(':fci_id', $fci_id);
            
        DB::execute();
        
        // UPDATE fci_servicios
        if (!empty($fci_seleccionados_m)) {

            // Asociar el item a los servicios elegidos
            $fci_array_servicios = explode(",", $fci_seleccionados_m);

            foreach ($fci_array_servicios as $servicio) {

                DB::query("INSERT INTO fci_servicios (fciServicio_fcItem_id,
                                                      fciServicio_servicio_id,
                                                      fciServicio_factura_id)
                                              VALUES (:fciServicio_fcItem_id_n,
                                                      :fciServicio_servicio_id_n,
                                                      :fciServicio_factura_id_n)");

                DB::bind(':fciServicio_fcItem_id_n', $fci_id);
                DB::bind(':fciServicio_servicio_id_n', $servicio);
                DB::bind(':fciServicio_factura_id_n', $fci_factura_id_m);

                DB::execute();
            }            
        }
        if (!empty($fci_seleccionados_b)) {
        // Desasociar el item a los servicios elegidos
            $fci_array_servicios_quitar = explode(",", $fci_seleccionados_b);

            foreach ($fci_array_servicios_quitar as $servicio) {

                DB::query("DELETE FROM fci_servicios
                           WHERE fciServicio_servicio_id = :fciServicio_servicio_id_b");

                DB::bind(':fciServicio_servicio_id_b', $servicio);

                DB::execute();
            }
        }
    }
    
    
    // LOGUEO: Método para insertar un registro en el LOG de Facturas y en FCI
    public static function insertar_mov_facturacion($fciMov_fci_id,
                                                    $fciMov_usuario_id,
                                                    $fciMov_movEstado_id,
                                                    $fciMov_motivoRechazo_id,
                                                    $fciMov_importeAprobadoUSD,
                                                    $fciMov_importeAprobadoOrigen,
                                                    $fciMov_fechaPago,
                                                    $fciMov_formaPago,
                                                    $fciMov_observaciones) {
        try {
            DB::conecta_t();
            DB::beginTransaction_t();  // start Transaction
        
            // Formateo de Fechas para el Insert
            if (!empty($fciMov_fechaPago)) {
                $fciMov_fechaPago = date('Y-m-d', strtotime($fciMov_fechaPago));
            } else {
                $fciMov_fechaPago = NULL;
            }
            if (empty($fciMov_motivoRechazo_id)) {
                $fciMov_motivoRechazo_id = NULL;            
            }
            if (empty($fciMov_formaPago)) {
                $fciMov_formaPago = NULL;            
            }

            if (empty($fciMov_importeAprobadoUSD)) {
                $fciMov_importeAprobadoUSD = 0;            
            }
            if (empty($fciMov_importeAprobadoOrigen)) {
                $fciMov_importeAprobadoOrigen = 0;            
            }


            // INSERT INTO fci_movimientos
            DB::query_t("INSERT INTO fci_movimientos (fciMov_fcItem_id,
                                                    fciMov_usuario_id,           
                                                    fciMov_fciMovEstado_id,
                                                    fciMov_facturaMotivoRechazo_id,
                                                    fciMov_importeAprobadoUSD,
                                                    fciMov_importeAprobadoOrigen,
                                                    fciMov_fechaPago,
                                                    fciMov_formaPago_id,
                                                    fciMov_observaciones)
                                            VALUES (:fciMov_fci_id,
                                                    :fciMov_usuario_id,
                                                    :fciMov_movEstado_id,
                                                    :fciMov_motivoRechazo_id,
                                                    :fciMov_importeAprobadoUSD,
                                                    :fciMov_importeAprobadoOrigen,
                                                    :fciMov_fechaPago,
                                                    :fciMov_formaPago,
                                                    :fciMov_observaciones)");

            DB::bind(':fciMov_fci_id', $fciMov_fci_id);
            DB::bind(':fciMov_usuario_id', $fciMov_usuario_id);
            DB::bind(':fciMov_movEstado_id', $fciMov_movEstado_id);
            DB::bind(':fciMov_motivoRechazo_id', $fciMov_motivoRechazo_id);
            DB::bind(':fciMov_importeAprobadoUSD', "$fciMov_importeAprobadoUSD");
            DB::bind(':fciMov_importeAprobadoOrigen', "$fciMov_importeAprobadoOrigen");
            if (!empty($fciMov_fechaPago)) {
                DB::bind(':fciMov_fechaPago', "$fciMov_fechaPago");
            } else {
                DB::bind(':fciMov_fechaPago', $fciMov_fechaPago);
            }
            DB::bind(':fciMov_formaPago', $fciMov_formaPago);
            DB::bind(':fciMov_observaciones', "$fciMov_observaciones");

            DB::execute();
        
            
            /*
            |   ESTADOS RECHAZADO - ID: 4 - 6 - 8
            */
            if (($fciMov_movEstado_id == 4) or ($fciMov_movEstado_id == 6) or ($fciMov_movEstado_id == 8)) {

                /* 
                |   PRIMERO - UPDATE en fc_items:
                |   Como se rechaza el item de factura, se toma solo el estado del movimiento, los importes se ponen en 0              
                */

                $fciMov_importeAprobadoUSD = 0;
                $fciMov_importeAprobadoOrigen = 0;

                DB::query_t("UPDATE fc_items SET 
                                    fcItem_estado_id = :fcItem_estado_id,
                                    fcItem_importeAprobadoUSD = :fcItem_importeAprobadoUSD,
                                    fcItem_importeAprobadoOrigen = :fcItem_importeAprobadoOrigen
                            WHERE fcItem_id = :fcItem_id");

                DB::bind(':fcItem_estado_id', $fciMov_movEstado_id);
                DB::bind(':fcItem_importeAprobadoUSD', "$fciMov_importeAprobadoUSD");
                DB::bind(':fcItem_importeAprobadoOrigen', "$fciMov_importeAprobadoOrigen");
                DB::bind(':fcItem_id', $fciMov_fci_id);

                DB::execute(); 

                /* 
                |   SEGUNDO - DELETE en fci_servicios:
                |   Se liberan los servicios asociados, dado que se rechazan los items de factura asociados
                */
                DB::query_t("DELETE FROM fci_servicios
                           WHERE fciServicio_fcItem_id = :fciServicio_fcItem_id");

                DB::bind(':fciServicio_fcItem_id', $fciMov_fci_id);

                DB::execute();

            /*
            |   ESTADOS APROBADO FACTURACION - ID: 2 - 3
            */  
            } else if (($fciMov_movEstado_id == 2) || ($fciMov_movEstado_id == 3)) {

                /* 
                |   UPDATE en fc_items:
                |   Como se aprueba el item de factura, se toman los importes + estado del movimiento      
                */

                DB::query_t("UPDATE fc_items SET 
                                    fcItem_estado_id = :fcItem_estado_id,
                                    fcItem_importeAprobadoUSD = :fcItem_importeAprobadoUSD,
                                    fcItem_importeAprobadoOrigen = :fcItem_importeAprobadoOrigen
                            WHERE fcItem_id = :fcItem_id");

                DB::bind(':fcItem_estado_id', $fciMov_movEstado_id);
                DB::bind(':fcItem_importeAprobadoUSD', "$fciMov_importeAprobadoUSD");
                DB::bind(':fcItem_importeAprobadoOrigen', "$fciMov_importeAprobadoOrigen");
                DB::bind(':fcItem_id', $fciMov_fci_id);

                DB::execute(); 
            
            /*
            |   ESTADOS SIN UPDATE DE IMPORTE - ID: 5 - 7 - 9 - 10
            */ 
            } else if (($fciMov_movEstado_id == 5) || ($fciMov_movEstado_id == 7) || ($fciMov_movEstado_id == 9) || ($fciMov_movEstado_id == 10)) {

                /* 
                |   UPDATE en fc_items:
                |   Con estos estados solo se hace update del estado del item de factura
                */

                DB::query_t("UPDATE fc_items SET 
                                    fcItem_estado_id = :fcItem_estado_id
                            WHERE fcItem_id = :fcItem_id");

                DB::bind(':fcItem_estado_id', $fciMov_movEstado_id);
                DB::bind(':fcItem_id', $fciMov_fci_id);

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
    
    
    // Método para buscar en casos la info que sera utilizada para la auditoria de facturas
    public static function fci_datos_caso($fci_id) {
        
        DB::query("SELECT casos.caso_beneficiarioNombre as beneficiario,
                            casos.caso_numeroVoucher as voucher,
                            product.product_name as producto,
                            casos.caso_agencia as agencia,
                            paises.pais_nombreEspanol as pais,
                            diagnosticos.diagnostico_nombre as diagnostico
                    FROM fc_items
                        LEFT JOIN casos on casos.caso_id = fc_items.fcItem_caso_id
                        LEFT JOIN product on product.product_id_interno = casos.caso_producto_id
                        LEFT JOIN paises on paises.pais_id = casos.caso_pais_id
                        LEFT JOIN diagnosticos on diagnosticos.diagnostico_id = casos.caso_diagnostico_id
                    WHERE fc_items.fcItem_id = :fci_id");
                        
        DB::bind(':fci_id', $fci_id);
        
        return DB::resultado();;
        
    }
    
    
    // Métodos de Select
    //
    // Método para listar los estados de los Items de Factura
    public static function listar_fci_estados(){
        
        DB::query("SELECT fciMovEstado_id, fciMovEstado_nombre, fciMovEstado_sector 
                   FROM fci_movimientos_estados");
        
        return DB::resultados();
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
    
    
    // Método para el Select - Lista las monedas en formulario ALTA Item de factura
    public static function listar_fciMonedas_alta(){
        
        DB::query("SELECT moneda_id, moneda_nombre
                   FROM monedas
                   WHERE moneda_activa = 1 
                   ORDER BY moneda_nombre");
        
        return DB::resultados();
    }
    // Método para el Select - Lista las monedas en formulario MODIFICACION Item de factura
    public static function listar_fciMonedas_modificacion($moneda_id){
        
        DB::query("SELECT moneda_id, moneda_nombre
                   FROM monedas
                        INNER JOIN fc_items ON fcItem_monedaOrigen_id = moneda_id
                   WHERE fcItem_monedaOrigen_id = :moneda_id
                   UNION
                   SELECT moneda_id, moneda_nombre
                   FROM monedas
                   WHERE moneda_activa = 1");
        
        DB::bind(':moneda_id', $moneda_id);
        
        return DB::resultados();
    }
        
    
    // Método para el Select - Lista los ESTADOS de LOG para el modal de autorizacion de ITEMS de Facturas
    // Logica segun workflow
    public static function listar_movEstados_alta($fci_estado_id_au, $fci_importe_aprobadoUSD_au){
        
        if ($fci_estado_id_au == 1 && Usuario::puede("fci_auto_admin") == 1) { // 1 - Item Ingresado

            // 2- Item Aprobado por Facturación 
            // 3- Item Aprobado Parcial por Facturación
            // 4- Item Rechazado por Facturación
            // 10- Seleccione (En caso que el 'Valor Aprobado' sea superior al 'Valor Real USD')
            $where = 'fciMovEstado_id = 2 OR fciMovEstado_id = 3 OR fciMovEstado_id = 4 OR fciMovEstado_id = 10';

        } else if ($fci_estado_id_au == 2 || $fci_estado_id_au == 3) { // 2 - Item Aprobado por Facturación OR 3- Item Aprobado Parcial por Facturación

            if ($fci_importe_aprobadoUSD_au >= 5000 && Usuario::puede("fci_auto_medica") == 1) { // Si supera los USD5.000 debe autorizar Dirección Médica
                // 5- Item Aprobado por Dir. Médica 
                // 6- Item Rechazado por Dir. Médica
                $where = 'fciMovEstado_id = 5 OR fciMovEstado_id = 6';   

            } else if ($fci_importe_aprobadoUSD_au < 5000 && Usuario::puede("fci_auto_finanzas") == 1) {

                // 7- Item Aprobado por Finanzas
                // 8- Item Rechazado por Finanzas
                $where = 'fciMovEstado_id = 7 OR fciMovEstado_id = 8';

            }
        } else if ($fci_estado_id_au == 5 && Usuario::puede("fci_auto_finanzas") == 1) { // 5- Item Aprobado por Dir. Médica  

            // 7- Item Aprobado por Finanzas
            // 8- Item Rechazado por Finanzas
            $where = 'fciMovEstado_id = 7 OR fciMovEstado_id = 8';

        } else if ($fci_estado_id_au == 7 && Usuario::puede("fci_auto_validacion") == 1) { // 7- Item Aprobado por Finanzas 

            // 9 - Item Validado
            $where = 'fciMovEstado_id = 9';

        } else if ($fci_estado_id_au == 9 && Usuario::puede("fci_auto_pago") == 1) { // 7- Item Validado

            // 10 - Item Pagado
            $where = 'fciMovEstado_id = 10';
            
        }
        
        DB::query("SELECT fciMovEstado_id, fciMovEstado_nombre
                   FROM fci_movimientos_estados
                   WHERE " . $where . "
                   ORDER BY fciMovEstado_id");
        
        return DB::resultados();
    }
    
    
    // Método para el Select - Lista los motivos de rechazo de un ITEM de Factura para el modal de autorizacion de facturas. 
    public static function listar_motivosRechazo_alta(){
        
        DB::query("SELECT fciMotivoRechazo_id, fciMotivoRechazo_descripcion
                    FROM fci_motivos_rechazos
                    ORDER BY fciMotivoRechazo_descripcion");
        
        return DB::resultados();
    }
    
    
    // Método para el Select - Lista los tipos de facturas en formulario MODIFICACION
    public static function listar_tiposFacturas_modificacion($factura_id){
        
        DB::query("SELECT tipoFactura_id, tipoFactura_nombre
                    FROM facturas 
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
                    FROM facturas 
                        INNER JOIN facturas_prioridades ON facturas_prioridades.facturaPrioridad_id = facturas.factura_prioridad_id 
                    WHERE factura_id = :factura_id
                    UNION
                    SELECT facturaPrioridad_id, facturaPrioridad_nombre
                    FROM facturas_prioridades
                    WHERE facturaPrioridad_activa = 1");
        
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
    
    
    // Método para mostrar la informacion de los ITEMS de Facturas pendientes autorizar
    public static function info_fci_pendientes_autorizar($fci_id) {
        
        DB::query("SELECT   fci.fcItem_id as idFci,
                            facturas.factura_id as idFactura,
                            facturas.factura_numero as numeroFactura,
                            fci_movimientos_estados.fciMovEstado_id as estadoFciId,                            
                            fci_movimientos_estados.fciMovEstado_nombre as estadoFciNombre,
                            fci.fcItem_importeUSD as importeUSDFci,
                            fc_mov.fciMov_importeAprobadoUSD as importeAprobadoUSD  
                    FROM fc_items as fci
                            LEFT JOIN facturas ON facturas.factura_Id = fci.fcItem_factura_id
                            LEFT JOIN (select fciMov_id, fciMov_fcItem_id, fciMov_fciMovEstado_id
                                from fci_movimientos
                                where fciMov_id IN (SELECT max(fciMov_id) FROM fci_movimientos GROUP BY fciMov_fcItem_id)) 
                                as fcimov on fcimov.fciMov_fcItem_id = fci.fcItem_id
                            LEFT JOIN fci_movimientos_estados ON fci_movimientos_estados.fciMovEstado_id = fcimov.fciMov_fciMovEstado_id
                            /* Esto lo hace para completar el campo importeAprobadoUSD buscando especificamente donde se encuentra este valor */
                            LEFT JOIN (SELECT fci_movimientos.fciMov_fcItem_id, fci_movimientos.fciMov_importeAprobadoUSD
                                    FROM fci_movimientos 
                                    WHERE fci_movimientos.fciMov_fcItem_id = :fci_id AND (fci_movimientos.fciMov_fciMovEstado_id = 2 or fci_movimientos.fciMov_fciMovEstado_id = 3)) 
                                    AS fc_mov ON fci.fcItem_id = fc_mov.fciMov_fcItem_id
                    WHERE fci.fcItem_id = :fci_id");
                        
        DB::bind(':fci_id', $fci_id);

        return DB::resultado();
    }
    
    
    // Método para listar el log de facturas
    public static function listar_mov_fci($fci_id) {
        
        DB::query("SELECT fci_movimientos.fciMov_id as idMovFci,
                            fci_movimientos.fciMov_fechaEvento as fechaEvento,
                            usuarios.usuario_nombre as usuarioNombreMov, 
                            usuarios.usuario_apellido as usuarioApellidoMov,
                            fci_movimientos.fciMov_fciMovEstado_id as estadoMovId,
                            fci_movimientos_estados.fciMovEstado_descripcion as estadoMovDesc, 
                            fci_motivos_rechazos.fciMotivoRechazo_descripcion as motivoRechazo, 
                            fci_movimientos.fciMov_observaciones as observacionesMov,
                            fci_movimientos.fciMov_importeAprobadoUSD as importeAprobadoUSD,                            
                            fci_movimientos.fciMov_fechaPago as fechaPago,
                            formas_pagos.formaPago_nombre as formaPago
                    FROM fci_movimientos
                        LEFT JOIN usuarios on usuarios.usuario_id = fci_movimientos.fciMov_usuario_id
                        LEFT JOIN fci_movimientos_estados on fci_movimientos_estados.fciMovEstado_id = fci_movimientos.fciMov_fciMovEstado_id
                        LEFT JOIN fci_motivos_rechazos on fci_motivos_rechazos.fciMotivoRechazo_id = fci_movimientos.fciMov_facturaMotivoRechazo_id
                        LEFT JOIN formas_pagos on formas_pagos.formaPago_id = fci_movimientos.fciMov_formaPago_id
                    WHERE fci_movimientos.fciMov_fcItem_id = :fci_id
                    ORDER BY fci_movimientos.fciMov_fechaEvento");
                        
        DB::bind(':fci_id', $fci_id);
        
        return DB::resultados();;
    }
    
    
    // Método para contar la cantidad de registros en el log consultados
    public static function contar_mov_fci($fci_id){
        
        DB::query("SELECT count(fci_movimientos.fciMov_fcItem_id) as fciMov_cantidad
                    FROM fci_movimientos
                    WHERE fci_movimientos.fciMov_fcItem_id = :fci_id");
        
        DB::bind(':fci_id', $fci_id);
        
        return DB::resultado();
    }
    
    public static function listar_servicios_fci($fci_id) {
            
        DB::query("SELECT servicios.servicio_id,
                          DATE_FORMAT(servicios.servicio_fecha, '%d-%m-%Y') as servicioFecha,
                          prestadores.prestador_id as prestadorId,
                          prestadores.prestador_nombre as prestador,
                          practicas.practica_nombre as practica,
                          servicios.servicio_presuntoOrigen as presuntoOrigen,
                          monedas.moneda_nombre as moneda,
                          servicios.servicio_tipoCambio as tipoCambio,
                          servicios.servicio_presuntoUSD as presuntoUSD,
                          casos.caso_numero as casoNumero,
                          casos.caso_id as casoId    
                   FROM servicios
                        LEFT JOIN prestadores ON prestador_id = servicio_prestador_id
                        LEFT JOIN practicas ON practica_id = servicio_practica_id
                        LEFT JOIN monedas ON moneda_id = servicio_moneda_id
                        LEFT JOIN casos ON caso_id = servicio_caso_id
                        LEFT JOIN fci_servicios ON fciServicio_servicio_id = servicio_id
                   WHERE fciServicio_fcItem_id = :fci_id");
        
        DB::bind(':fci_id', $fci_id);
        
        return DB::resultados();
    }
    
    // Método para buscar un prestador por nombre, es el resultado del autocomplete en alta de factura
    public static function buscar_selectPrestador($nombre) {
        DB::query("SELECT prestador_id, 
                          CONCAT(prestadores.prestador_nombre, ' - ', prestadores.prestador_razonSocial) as prestador_nombre
                   FROM prestadores
                   WHERE (CONCAT(prestadores.prestador_nombre, ' ', prestadores.prestador_razonSocial)) LIKE :prestador_nombre
                   AND prestador_activo = 1
                   LIMIT 15");
        
        DB::bind(':prestador_nombre', "%$nombre%");
        
        return DB::resultados();
    }
    
    // Método para actualizar el estado de una factura a pagada
    public static function imputar_pago_factura($factura_id_m,
                                                $sesion_usuario_id) {
        
        $factura_prestador_id_m = 7; // Pagada (id. 7)
        
        DB::query("UPDATE facturas SET
                        factura_facturaEstado_id = :factura_facturaEstado_id_m
                    WHERE factura_id = :factura_id_m");

        DB::bind(':factura_facturaEstado_id_m', $factura_prestador_id_m);
        DB::bind(':factura_id_m', $factura_id_m);

        DB::execute();
        
        // Formateo de Fechas para el INSERT
        $fecha_ingreso = date("Y-m-d H:i:s");
        // Comunicacion de cierre de reintegro
        $comunicacionF = "Factura pagada";

        DB::query("INSERT INTO comunicaciones_factura (comunicacionF,
                                                       comunicacionF_factura_id,
                                                       comunicacionF_usuario_id,
                                                       comunicacionF_fechaIngreso)
                                               VALUES (:comunicacionF,
                                                       :comunicacionF_factura_id,
                                                       :comunicacionF_usuario_id,
                                                       :comunicacionF_fechaIngreso)");

        DB::bind(':comunicacionF', "$comunicacionF");
        DB::bind(':comunicacionF_factura_id', "$factura_id_m");
        DB::bind(':comunicacionF_usuario_id', "$sesion_usuario_id");
        DB::bind(':comunicacionF_fechaIngreso', "$fecha_ingreso");

        DB::execute();
        
        $mensaje = "OK";
        return $mensaje;
    }
    
    
    // Método para validar los estos de los items
    public static function valida_estado_items($factura_id) {
        
        DB::query("SELECT fciMov_fciMovEstado_id
                    FROM fc_items as fci
                        LEFT JOIN (select fciMov_id, fciMov_fcItem_id, fciMov_fciMovEstado_id
                                   from fci_movimientos
                                   where fciMov_id IN (SELECT max(fciMov_id) FROM fci_movimientos GROUP BY fciMov_fcItem_id)) 
                                   as fcimov on fcimov.fciMov_fcItem_id = fci.fcItem_id
                        LEFT JOIN fci_movimientos_estados on fciMovEstado_id = fcimov.fciMov_fciMovEstado_id
                    WHERE fci.fcItem_factura_id = :factura_id");
        
        DB::bind(':factura_id', $factura_id);
        
        $resultados = DB::resultados();

        // Recorre el resultado del Array y valida si existen FCI con estado distinto a los rechazados o pagado
        // ID: Rechazado = 4, 6, 8 - Pagado = 10
        foreach ($resultados as $resultado) {
            if ($resultado['fciMov_fciMovEstado_id'] != 4 && $resultado['fciMov_fciMovEstado_id'] != 6 &&
                $resultado['fciMov_fciMovEstado_id'] != 8 && $resultado['fciMov_fciMovEstado_id'] != 10) {
                return false;
            }
        }
    }
    
    
    // Metodo para obtener el tipo de cambio de un item
    public static function buscar_tipoCambio($fci_id) {
        
        DB::query("SELECT fc_items.fcItem_tipoCambio 
                   FROM fc_items 
                   WHERE fc_items.fcItem_id = :fci_id");
        
        DB::bind(':fci_id', $fci_id);
        
        $resultado = DB::resultado();
        return $resultado['fcItem_tipoCambio'];
    }


    // Metodo para obtener la lista de FCI que hayan sido asignados a un Servicio
    public static function listar_fci_asignados($servicio_id) {

        DB::query("SELECT facturas.factura_id,
                            facturas.factura_numero,
                            facturas_estados.facturaEstado_nombre,
                            clientes.cliente_nombre,
                            fc_items.fcItem_fechaIngresoSistema,
                            fc_items.fcItem_importeMedicoOrigen,
                            fc_items.fcItem_importeFeeOrigen,
                            monedas.moneda_nombre,
                            fc_items.fcItem_importeUSD
                    FROM fci_servicios
                            LEFT JOIN fc_items ON fc_items.fcItem_id = fci_servicios.fciServicio_fcItem_id
                            LEFT JOIN facturas ON facturas.factura_Id = fc_items.fcItem_factura_id
                            LEFT JOIN facturas_estados ON facturas_estados.facturaEstado_id = facturas.factura_facturaEstado_id    
                            LEFT JOIN clientes ON clientes.cliente_id = fc_items.fcItem_clientePagador_id
                            LEFT JOIN monedas ON monedas.moneda_id = fc_items.fcItem_monedaOrigen_id
                    WHERE fci_servicios.fciServicio_servicio_id = :servicio_id");
        
        DB::bind(':servicio_id', $servicio_id);
        
        return DB::resultados();
    }
}