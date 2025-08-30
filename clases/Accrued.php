 <?php
/**
 * Clase: Accrued
 *
 *
 * @author ArgenCode
 */

class Accrued {
    
    # Listas #

    // Método para listar Reintegros en la generación del Accrued
    public static function listar_reintegros($reint_fechaPago_desde, $reint_fechaPago_hasta) {
        
        // Formateo de Fechas
        $reint_fechaPago_desde = Herramientas::fecha_formateo($reint_fechaPago_desde);
        $reint_fechaPago_hasta = Herramientas::fecha_formateo($reint_fechaPago_hasta);
        
        // SELECT
        DB::query("SELECT casos.caso_numero, paises.pais_nombreEspanol, reintegro_fechaPago, 
                            formas_pagos.formaPago_nombre, SUM(reintegros_items.reintegroItem_importeAprobadoUSD) AS aprobado_usd
                    FROM reintegros
                        LEFT JOIN reintegros_items ON reintegroItem_reintegro_id = reintegro_id
                        LEFT JOIN casos ON caso_id = reintegro_caso_id
                        LEFT JOIN paises ON pais_id = caso_pais_id
                        LEFT JOIN formas_pagos ON formaPago_id = reintegro_formaPago_id
                    /* 
                    |   El WHERE filtra por: 
                    |       1- Fecha de pago
                    |       2- Reintegros con estado Abonado (Id. 6)
                    |       3- Que no se hayan procesado en otro Accrued 
                    |       4- El importe_aprobado_USD debe ser mayor a 0
                    */
                    WHERE (reintegro_fechaPago BETWEEN COALESCE(:reint_fechaPago_desde,reintegro_fechaPago) AND COALESCE(:reint_fechaPago_hasta,reintegro_fechaPago)) AND
                            (reintegro_reintegroEstado_id = 6) AND 
                            (reintegro_accruedLote_id IS NULL) AND
                            (reintegros_items.reintegroItem_importeAprobadoUSD > 0)
                    GROUP BY casos.caso_numero, paises.pais_nombreEspanol, reintegro_fechaPago, formas_pagos.formaPago_nombre");
        
        DB::bind(':reint_fechaPago_desde', $reint_fechaPago_desde);
        DB::bind(':reint_fechaPago_hasta', $reint_fechaPago_hasta);
        
        return DB::resultados();
    }


    // Método para listar Reintegros en Borrador
    public static function listar_borrador_reintegros() {
        
        // Llama al método para buscar el ID del Lote Reintegros (Id.1) en estado Borrador (Id. 1)
        $reintegro_borrador_id = Self::buscar_lote_numero(1,1);

        // SELECT para mostrar únicamente los Reintegros en Borrador
        DB::query("SELECT casos.caso_numero, paises.pais_nombreEspanol, reintegro_fechaPago, formas_pagos.formaPago_nombre, SUM(reintegroItem_importeAprobadoUSD) AS aprobado_usd
                    FROM reintegros
                        LEFT JOIN reintegros_items ON reintegroItem_reintegro_id = reintegro_id
                        LEFT JOIN casos ON caso_id = reintegro_caso_id
                        LEFT JOIN paises ON pais_id = caso_pais_id
                        LEFT JOIN formas_pagos ON formaPago_id = reintegro_formaPago_id
                    WHERE (reintegro_accruedLote_id = :reintegro_borrador_id) AND (reintegroItem_importeAprobadoUSD > 0)
                    GROUP BY casos.caso_numero, paises.pais_nombreEspanol, reintegro_fechaPago, formas_pagos.formaPago_nombre");

        DB::bind(':reintegro_borrador_id', $reintegro_borrador_id);
        
        return DB::resultados();
    }


    // Método para listar únicamente los Reintegros correspondientes a Lote seleccionado
    public static function reint_procesado($lote_numero) {
        
        DB::query("SELECT casos.caso_numero, paises.pais_nombreEspanol, reintegro_fechaPago, 
                            formas_pagos.formaPago_nombre, SUM(movimientos.aprobado_usd) AS aprobado_usd
                    FROM reintegros
                        LEFT JOIN reintegros_items ON reintegroItem_reintegro_id = reintegro_id
                        LEFT JOIN (SELECT riMov_reintegroItem_id, riMov_importeAprobadoUSD AS aprobado_usd 
                                    FROM ri_movimientos
                                    WHERE riMov_riEstado_id = 2 OR riMov_riEstado_id = 3) AS movimientos ON movimientos.riMov_reintegroItem_id = reintegroItem_id
                        LEFT JOIN casos ON caso_id = reintegro_caso_id
                        LEFT JOIN paises ON pais_id = caso_pais_id
                        LEFT JOIN formas_pagos ON formaPago_id = reintegro_formaPago_id
                    WHERE reintegro_accruedLote_id = :lote_numero
                    GROUP BY casos.caso_numero, paises.pais_nombreEspanol, reintegro_fechaPago, formas_pagos.formaPago_nombre");

        DB::bind(':lote_numero', $lote_numero);
        
        return DB::resultados();
    }


    // Método para listar FCI en la generación del Accrued
    public static function listar_fci($fci_fechaPago_desde, $fci_fechaPago_hasta) {
        
        // Formateo de Fechas
        // $fci_fechaPago_desde = Herramientas::fecha_formateo($fci_fechaPago_desde);
        // $fci_fechaPago_hasta = Herramientas::fecha_formateo($fci_fechaPago_hasta);
        
        // SELECT
        DB::query("SELECT fcItem_id, 
                            casos.caso_numero,
                            facturas.factura_numero,
                            prestadores.prestador_nombre, 
                            fci_movimientos_estados.fciMovEstado_descripcion AS estado, 
                            fcItem_importeAprobadoUSD AS aprobado_usd
                    FROM fc_items
                        LEFT JOIN facturas ON factura_id = fcItem_factura_id
                        LEFT JOIN fci_movimientos_estados ON fciMovEstado_id = fcItem_estado_id
                        LEFT JOIN casos ON caso_id = fcItem_caso_id
                        LEFT JOIN prestadores ON prestador_id = factura_prestador_id
                    /* El WHERE filtra por fecha de pago + FCI con auditoria y que no se hayan rechazado + Que no se hayan procesado en otro Accrued */
                    WHERE 
                        /*(reintegro_fechaPago BETWEEN COALESCE(:fci_fechaPago_desde,reintegro_fechaPago) AND COALESCE(:fci_fechaPago_hasta,reintegro_fechaPago)) AND*/
                        (fcItem_accruedLote_id IS NULL) AND NOT
                        (fcItem_estado_id = 1 OR fcItem_estado_id = 4 OR fcItem_estado_id = 6 OR fcItem_estado_id = 8 OR fcItem_estado_id = 11) AND 
                        fcItem_importeAprobadoUSD > 0");
        
        DB::bind(':fci_fechaPago_desde', $fci_fechaPago_desde);
        DB::bind(':fci_fechaPago_hasta', $fci_fechaPago_hasta);
        
        return DB::resultados();
    }


    // Método para listar FCI en Borrador
    public static function listar_borrador_fci() {

        // Llama al método para buscar el ID del Lote FCI (Id.2) en estado Borrador (Id. 1)
        $fci_borrador_id = Self::buscar_lote_numero(2,1);
        
        // SELECT
        DB::query("SELECT fcItem_id, 
                            casos.caso_numero, 
                            prestadores.prestador_nombre, 
                            fci_movimientos_estados.fciMovEstado_descripcion AS estado, 
                            fcItem_importeAprobadoUSD AS aprobado_usd
                    FROM fc_items
                        LEFT JOIN fci_movimientos_estados ON fciMovEstado_id = fcItem_estado_id
                        LEFT JOIN casos ON caso_id = fcItem_caso_id
                        LEFT JOIN facturas ON factura_id = fcItem_factura_id
                        LEFT JOIN prestadores ON prestador_id = factura_prestador_id
                    WHERE fcItem_accruedLote_id = :fci_borrador_id");
        
        DB::bind(':fci_borrador_id', $fci_borrador_id);
        
        return DB::resultados();
    }


    // Método para listar únicamente los FCI correspondientes a Lote seleccionado
    public static function fci_procesado($lote_numero) {
        
        DB::query("SELECT fcItem_id, casos.caso_numero, 
                            prestadores.prestador_nombre, 
                            fci_movimientos_estados.fciMovEstado_descripcion AS estado, 
                            fcItem_importeAprobadoUSD AS aprobado_usd
                    FROM fc_items
                        LEFT JOIN fci_movimientos_estados ON fciMovEstado_id = fcItem_estado_id
                        LEFT JOIN casos ON caso_id = fcItem_caso_id
                        LEFT JOIN facturas ON factura_id = fcItem_factura_id
                        LEFT JOIN prestadores ON prestador_id = factura_prestador_id
                    WHERE fcItem_accruedLote_id = :lote_numero");
        
        DB::bind(':lote_numero', $lote_numero);
        
        return DB::resultados();
    }


    // Método para listar Accrued Generados
    public static function listar_accrued_generados() {

        DB::query("SELECT accruedLote_lote AS lote, 
                            accrued_estados.accrued_estado_id AS estado_id,
                            accrued_estados.accrued_estado_nombre AS estado, 
                            accruedLote_fechaBorrador AS fecha_borrador, 
                            accruedLote_fechaProcesado AS fecha_procesado, 
                            accruedLote_fechaInformado AS fecha_informado, 
                            reint.aprobado_usd AS reint_aprobado_usd, 
                            fci.aprobado_usd AS fci_aprobado_usd
                    FROM accrued_lotes
                        LEFT JOIN accrued_estados ON accrued_estado_id = accruedLote_estado_id
                        INNER JOIN (SELECT reintegro_accruedLote_id, SUM(reintegros_items.reintegroItem_importeAprobadoUSD) AS aprobado_usd 
                                    FROM reintegros
                                        LEFT JOIN reintegros_items ON reintegroItem_reintegro_id = reintegro_id
                                        LEFT JOIN accrued_lotes ON accruedLote_lote = reintegros.reintegro_accruedLote_id
                                    GROUP BY reintegro_accruedLote_id) AS reint ON reint.reintegro_accruedLote_id = accrued_lotes.accruedLote_lote
                                                
                        INNER JOIN (SELECT fcItem_accruedLote_id, SUM(fcItem_importeAprobadoUSD) AS aprobado_usd 
                                    FROM fc_items
                                        LEFT JOIN accrued_lotes ON accruedLote_lote = fc_items.fcItem_accruedLote_id
                                    GROUP BY fcItem_accruedLote_id) AS fci ON fci.fcItem_accruedLote_id = accrued_lotes.accruedLote_lote
                    GROUP BY accruedLote_lote");
        
        return DB::resultados();
    }



    # Acciones #

    
    // Método para Guardar el borrador de Reintegros
    public static function guardar_acc_reint($reint_fechaPago_desde, $reint_fechaPago_hasta) {

        if (Self::existe_lote_pendiente_informar(1) != true) {

            /* 
            | INSERT INTO tabla 'accrued_lotes'
            | Inserta en la tabla el nuevo Lote en estado Borrador
            | Lote Tipo = Reintegros (Id. 1) + Lote Estado = Borrador (Id. 1)
            */
            $lote_numero = Self::crear_lote(1,1);

            /*
            | UPDATE tabla 'reintegros'
            | Actualiza la tabla con el ID de Lote Borrador
            */
            
            // Formateo de Fechas
            $reint_fechaPago_desde = Herramientas::fecha_formateo($reint_fechaPago_desde);
            $reint_fechaPago_hasta = Herramientas::fecha_formateo($reint_fechaPago_hasta);

            // UPDATE
            DB::query("UPDATE reintegros SET
                                reintegro_accruedLote_id = :lote_numero
                        /* El WHERE filtra por fecha de pago + Reintegros con estado Abonado (Id. 6) + Que no se hayan procesado en otro Accrued */
                        WHERE (reintegro_fechaPago BETWEEN COALESCE(:reint_fechaPago_desde,reintegro_fechaPago) AND COALESCE(:reint_fechaPago_hasta,reintegro_fechaPago)) AND
                                (reintegro_reintegroEstado_id = 6) AND 
                                (reintegro_accruedLote_id IS NULL)");
            
            DB::bind(':lote_numero', $lote_numero);
            DB::bind(':reint_fechaPago_desde', $reint_fechaPago_desde);
            DB::bind(':reint_fechaPago_hasta', $reint_fechaPago_hasta);
            
            DB::execute();

            return true;

        } else {

            return false;
            
        }
    }

    // Método para Guardar el borrador de FCI
    public static function guardar_acc_fci($fci_seleccionados) {

        if (Self::existe_lote_pendiente_informar(2) != true) {

            /* 
            | INSERT INTO tabla 'accrued_lotes'
            | Inserta en la tabla el nuevo Lote en estado Borrador
            | Lote Tipo = FCI (Id. 2) + Lote Estado = Borrador (Id. 1)
            */
            $lote_numero = Self::crear_lote(2,1);

            /* 
            | UPDATE tabla 'fc_items'
            | Actualiza la tabla con el ID de Lote Borrador
            */
            if ($fci_seleccionados == '') {
                $fci_seleccionados = 0;
            }

            DB::query("UPDATE fc_items SET fcItem_accruedLote_id = :lote_numero
                        /* El WHERE filtra por los FCI seleccionados + Que no se hayan procesado en otro Accrued */
                        WHERE fcItem_id IN (" .$fci_seleccionados. ")");
            
            DB::bind(':lote_numero', $lote_numero);
            
            DB::execute();

            return true;
        
        } else {

            return false;

        }
    }

    // Método para insertar un Nuevo Lote en la tabla 'accrued_lotes'
    public static function crear_lote($accruedLote_loteTipo_id, $accruedLote_estado_id) {

        /* 
        | INSERT INTO tabla 'accrued_lotes'
        | Inserta en la tabla el nuevo Lote en estado Borrador, tomando un único número de lote 'accruedLote_lote'
        */
        $lote_numero = Self::generar_lote_numero();
        $fecha_actual = date("Y-m-d H:i:s");

        DB::query("INSERT INTO accrued_lotes (accruedLote_lote,
                                                accruedLote_loteTipo_id,
                                                accruedLote_estado_id,
                                                accruedLote_fechaBorrador)
                                        VALUES (:lote_numero,
                                                :accruedLote_loteTipo_id,
                                                :accruedLote_estado_id,
                                                :fecha_actual)");

        DB::bind(':lote_numero', "$lote_numero");       
        DB::bind(':accruedLote_loteTipo_id', $accruedLote_loteTipo_id);        
        DB::bind(':accruedLote_estado_id', $accruedLote_estado_id);
        DB::bind(':fecha_actual', "$fecha_actual");

        DB::execute();

        $lote_id = DB::lastInsertId();

        return $lote_numero;
    }

    // Método para Generar el Número de Lote
    public static function generar_lote_numero() {

        /* 
        | SELECT tabla 'accrued_lotes'
        | Busca si existe un lote cargado con estado Borrador (Id. 1)
        */
        DB::query("SELECT accruedLote_lote
                    FROM accrued_lotes
                    WHERE accruedLote_estado_id = 1");

        $lote_borrador = DB::resultado();

        /* 
        | Sino encuentra un Lote Borrador, GENERA un nuevo número de lote
        | Si encuentra un Lote Borrador, TOMA ese número de lote
        */
        if ($lote_borrador == NULL) {
            
            DB::query("SELECT MAX(accruedLote_lote) AS lote_numero FROM accrued_lotes");

            $max_lote = DB::resultado();
            
            // Incrementa en +1 el resultado del Select
            $lote_numero = $max_lote['lote_numero'] + 1;

        } else {

            $lote_numero = $lote_borrador['accruedLote_lote'];
        
        }

        return $lote_numero;
    }

    // Método para validar si ya existe un Lote en estado Borrado (Id. 1)
    public static function existe_lote_pendiente_informar($tipo) {

        // SELECT - Consulta si existen Lotes Borrador
        DB::query("SELECT accruedLote_id
                    FROM accrued_lotes
                    WHERE accruedLote_loteTipo_id = :tipo AND (accruedLote_estado_id = 1 OR accruedLote_estado_id = 2)");
        
        DB::bind(':tipo', $tipo);
        
        $resultado = DB::resultado();

        return $resultado['accruedLote_id'];
    }

    // Método para validar si ya existe un Lote en estado Borrado (Id. 1)
    public static function existe_lote_borrador($tipo) {

        // SELECT - Consulta si existen Lotes Borrador
        DB::query("SELECT accruedLote_id
                    FROM accrued_lotes
                    WHERE accruedLote_loteTipo_id = :tipo AND (accruedLote_estado_id = 1)");
        
        DB::bind(':tipo', $tipo);
        
        $resultado = DB::resultado();

        return $resultado['accruedLote_id'];
    }
    
    // Método para buscar el Número de un Lote
    public static function buscar_lote_numero($tipo, $estado) {

        DB::query("SELECT accruedLote_lote
                    FROM accrued_lotes
                    WHERE accruedLote_loteTipo_id = :tipo AND accruedLote_estado_id = :estado");

        DB::bind(':tipo', $tipo);
        DB::bind(':estado', $estado);

        $resultado = DB::resultado();

        return $resultado['accruedLote_lote'];
    }

    // Método para cambiar el Estado del Accrued a Procesado (Id. 2)
    public static function procesar() {
        
        $fecha_actual = date("Y-m-d H:i:s");

        /* 
        | UPDATE tabla 'accrued_lotes'
        | Actualiza a Lote Procesado (Id. 2), todos los Lotes con estado Lote Borrador (Id. 1)
        */
        DB::query("UPDATE accrued_lotes SET accruedLote_estado_id = 2, accruedLote_fechaProcesado = :fecha_actual
                    WHERE accruedLote_estado_id = 1");
        
        DB::bind(':fecha_actual', $fecha_actual);

        DB::execute();

        return 0;
    }

    // Método para marcar como informado el Accrued
    public static function informar($lote_numero) {

        $fecha_actual = date("Y-m-d H:i:s");

        /* 
        | UPDATE tabla 'accrued_lotes'
        | Actualiza a Lote Procesado (Id. 2), todos los Lotes con estado Lote Borrador (Id. 1)
        */
        DB::query("UPDATE accrued_lotes SET accruedLote_estado_id = 3, accruedLote_fechaInformado = :fecha_actual
                    WHERE accruedLote_lote = :lote_numero");
        
        DB::bind(':fecha_actual', $fecha_actual);
        DB::bind(':lote_numero', $lote_numero);

        DB::execute();
        
        return 0;
    }

    // Generar el Excel
    public static function generar_excel($exp_acc_id) {

        DB::query("SELECT cliente_nombre AS 'Platform_Code', 
                            caso_numero AS 'Case_Number', 
                            DATE_FORMAT(caso_fechaSiniestro, '%d-%m-%Y') AS 'Claim_Occurrence_Date', 
                            SUM(costos_reint.costos) AS 'REI-Claim_Settled_Amount', 
                            SUM(costos_fci.costos) AS 'FCI-Claim_Settled_Amount',
                            pais_nombreEspanol AS 'Claim_Country_Name', 
                            caso_numeroVoucher AS 'Claim_Policy_Code',  
                            tipoAsistencia_nombre AS 'Settlement_Nature_Assistance_Name', 
                            DATE_FORMAT(caso_fechaEmisionVoucher, '%d-%m-%Y') AS 'Policy_Creation_Date',                           
                            caso_beneficiarioNombre AS 'Beneficiary_Name', 
                            product_name AS 'Product_Name'
                    FROM casos
                    /* REINTEGROS */
                    LEFT JOIN (SELECT reintegros.reintegro_caso_id, SUM(reintegros_items.reintegroItem_importeAprobadoUSD) AS costos
                                            FROM reintegros_items
                                                                            LEFT JOIN reintegros ON reintegros.reintegro_id = reintegros_items.reintegroItem_reintegro_id
                                                                            LEFT JOIN accrued_lotes ON accruedLote_lote = reintegros.reintegro_accruedLote_id
                                            WHERE accrued_lotes.accruedLote_loteTipo_id = 1 AND accrued_lotes.accruedLote_lote = :exp_acc_id
                                            GROUP BY reintegros.reintegro_caso_id) AS costos_reint ON costos_reint.reintegro_caso_id = casos.caso_id                
                    /* FACTURAS */                                
                    LEFT JOIN (SELECT fc_items.fcItem_caso_id, SUM(fc_items.fcItem_importeAprobadoUSD) AS costos
                                            FROM fc_items
                                                            LEFT JOIN accrued_lotes ON accruedLote_lote = fcItem_accruedLote_id
                                            WHERE accrued_lotes.accruedLote_loteTipo_id = 2 AND accrued_lotes.accruedLote_lote = :exp_acc_id
                                            GROUP BY fc_items.fcItem_caso_id) AS costos_fci ON costos_fci.fcItem_caso_id = casos.caso_id
                    /* VARIOS */
                    LEFT JOIN clientes ON cliente_id = caso_cliente_id
                    LEFT JOIN paises ON pais_id = caso_pais_id
                    LEFT JOIN tipos_asistencias ON tipoAsistencia_id = caso_tipoAsistencia_id
                    LEFT JOIN product ON product.product_id_interno = caso_producto_id
                    WHERE costos_reint.costos > 0 OR costos_fci.costos > 0
                    GROUP BY cliente_nombre, caso_numero, caso_fechaSiniestro, pais_nombreEspanol, caso_numeroVoucher, tipoAsistencia_nombre, caso_fechaEmisionVoucher, caso_beneficiarioNombre, product_name  
                    ORDER BY casos.caso_id");
        
        DB::bind(':exp_acc_id', $exp_acc_id);
        
        return DB::resultados();
    }
}