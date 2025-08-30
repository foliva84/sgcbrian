<?php
/**
 * Clase: Caso
 *
 *
 * @author ArgenCode
 */

class Dashboard {
    
    // Método para mostrar la información en el Dashboard
    public static function estadisticas_casos() {

        # CASOS > Cantidad de Casos Totales #
        DB::query("SELECT COUNT(caso_id) AS cantidad FROM casos");

        $casos_totales = DB::resultado();

        # CASOS > Cantidad de Casos Abiertos #
        DB::query("SELECT COUNT(caso_id) AS cantidad FROM casos WHERE NOT casos.caso_casoEstado_id = 6");

        $casos_abiertos = DB::resultado();

        # CASOS > Cantidad de Casos Simples Abiertos #
        DB::query("SELECT COUNT(caso_id) AS cantidad FROM casos WHERE (casos.caso_fee_id = 1 AND casos.caso_casoEstado_id != 6)");

        $casos_simples_abiertos = DB::resultado();

        # CASOS > Cantidad de Casos Complejos Abiertos #
        DB::query("SELECT COUNT(caso_id) AS cantidad FROM casos WHERE (casos.caso_fee_id = 2 AND casos.caso_casoEstado_id != 6)");

        $casos_complejos_abiertos = DB::resultado();

        # CASOS > Cantidad de Casos Cerrados #
        DB::query("SELECT COUNT(caso_id) AS cantidad FROM casos WHERE casos.caso_casoEstado_id = 6");

        $casos_cerrados = DB::resultado();

        # FACTURACIÓN > Ingresadas (Cantidad y Total) #
        DB::query("SELECT COUNT(fcItem_id) as cantidad, SUM(fcItem_importeUSD) AS total FROM fc_items");

        $fci_ingresado = DB::resultado();

        # FACTURACIÓN > Pendiente Auditar (Cantidad) #
        DB::query("SELECT COUNT(fcItem_id) as cantidad FROM fc_items WHERE (fcItem_estado_id = 2 OR fcItem_estado_id = 3 OR fcItem_estado_id = 5)");

        $fci_pendiente_auditar = DB::resultado();

        # FACTURACIÓN > Pendiente Pago (Cantidad y Total) #
        DB::query("SELECT COUNT(fcItem_id) as cantidad, SUM(fcItem_importeUSD) AS total FROM fc_items WHERE fcItem_estado_id = 7");

        $fci_pendiente_pago = DB::resultado();

        # FACTURACIÓN > Pagado (Cantidad y Total) #
        DB::query("SELECT COUNT(fcItem_id) as cantidad, SUM(fcItem_importeUSD) AS total FROM fc_items WHERE fcItem_estado_id = 10");

        $fci_pagado = DB::resultado();


        # Return #
        return array(   # CASOS #
                        $casos_totales['cantidad'], $casos_abiertos['cantidad'], $casos_simples_abiertos['cantidad'], $casos_complejos_abiertos['cantidad'], $casos_cerrados['cantidad'],
                        # FACTURAS #
                        $fci_ingresado['cantidad'], $fci_ingresado['total'], $fci_pendiente_auditar['cantidad'], $fci_pendiente_pago['cantidad'], $fci_pendiente_pago['total'], $fci_pagado['cantidad'], $fci_pagado['total']
                    );
        
    }
}
