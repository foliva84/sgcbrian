<?php
/**
 * Clase: Caso
 *
 *
 * @author ArgenCode
 */

class Caso {
    
    const ENTIDAD =  Entidad::CASO;

#LISTAS    
    // Método para listar casos
    public static function listar($caso_numero_desde_b,
                                  $caso_numero_hasta_b,
                                  $caso_estado_id_b,
                                  $caso_tipoAsistencia_id_b,
                                  $caso_fechaSiniestro_desde_b,
                                  $caso_fechaSiniestro_hasta_b,
                                  $caso_beneficiario_b,
                                  $caso_voucher_b,
                                  $caso_agencia_b) {
        
        if ($caso_numero_desde_b == '') {
            $caso_numero_desde_b = NULL;
            $caso_numero_hasta_b = NULL;
        } else {
            If ($caso_numero_hasta_b == '') {
                $caso_numero_hasta_b = $caso_numero_desde_b;
            }
        }
        if ($caso_estado_id_b == '') {
            $caso_estado_id_b = NULL;
        }
        if ($caso_tipoAsistencia_id_b == '') {
            $caso_tipoAsistencia_id_b = NULL;
        }
        if (!empty($caso_fechaSiniestro_desde_b)) {
            $caso_fechaSiniestro_desde_b = date('Y-m-d H:i:s', strtotime($caso_fechaSiniestro_desde_b));
        } else {
            $caso_fechaSiniestro_desde_b = NULL;
        }
        if (!empty($caso_fechaSiniestro_hasta_b)) {
            $caso_fechaSiniestro_hasta_b = date('Y-m-d H:i:s', strtotime($caso_fechaSiniestro_hasta_b));
        } else {
            $caso_fechaSiniestro_hasta_b = NULL;
        }
        if ($caso_beneficiario_b == '') {
            $caso_beneficiario_b = NULL;
        }
        if ($caso_voucher_b == '') {
            $caso_voucher_b = NULL;
        }
        if ($caso_agencia_b == '') {
            $caso_agencia_b = NULL;
        }
        
        DB::query("SELECT caso_id,
                          caso_numero,
                          caso_casoEstado_id,
                          casoEstado_nombre,
                          caso_fechaSiniestro,
                          caso_beneficiarioNombre,
                          caso_paxVIP,
                          caso_numeroVoucher,
                          pais_nombreEspanol,
                          ciudad_nombre
                          /*asignadoA.usuario_nombre,
                          asignadoA.usuario_apellido,
                          casos_agenda.casoAgenda_id*/
                    FROM casos  
                            LEFT JOIN casos_estados on casoEstado_id = caso_casoEstado_id                            
                            LEFT JOIN paises on pais_id = caso_pais_id
                            LEFT JOIN ciudades on ciudad_id = caso_ciudad_id
                            /*LEFT JOIN casos_agenda on casoAgenda_caso_id = caso_id
                            LEFT JOIN usuarios as asignadoA on asignadoA.usuario_id = casoAgenda_usuarioAsignado_id*/
                    WHERE caso_numero between COALESCE(:caso_numero_desde,caso_numero) and COALESCE(:caso_numero_hasta,caso_numero)
                      AND caso_casoEstado_id = COALESCE(:caso_casoEstado_id,caso_casoEstado_id)
                      AND caso_tipoAsistencia_id = COALESCE(:caso_tipoAsistencia_id,caso_tipoAsistencia_id)
                      AND caso_fechaSiniestro between COALESCE(:caso_fechaSiniestro_desde,caso_fechaSiniestro) and COALESCE(:caso_fechaSiniestro_hasta,caso_fechaSiniestro)
                      AND caso_beneficiarioNombre LIKE :caso_beneficiario
                      AND caso_numeroVoucher LIKE :caso_voucher
                      AND caso_agencia LIKE :caso_agencia
                    ORDER BY caso_numero DESC
                    LIMIT 50");
        
        DB::bind(':caso_numero_desde', $caso_numero_desde_b);
        DB::bind(':caso_numero_hasta', $caso_numero_hasta_b);
        DB::bind(':caso_casoEstado_id', $caso_estado_id_b);
        DB::bind(':caso_tipoAsistencia_id', $caso_tipoAsistencia_id_b);
        DB::bind(':caso_fechaSiniestro_desde', $caso_fechaSiniestro_desde_b);
        DB::bind(':caso_fechaSiniestro_hasta', $caso_fechaSiniestro_hasta_b);
        DB::bind(':caso_beneficiario', "%$caso_beneficiario_b%");
        DB::bind(':caso_voucher', "%$caso_voucher_b%");
        DB::bind(':caso_agencia', "%$caso_agencia_b%");
        
        return DB::resultados();
    }
    


    //
    // Método para listar casos
    //
    public static function listar_contar($caso_numero_desde_b,
                                        $caso_numero_hasta_b,
                                        $caso_estado_id_b,
                                        $caso_tipoAsistencia_id_b,
                                        $caso_fechaSiniestro_desde_b,
                                        $caso_fechaSiniestro_hasta_b,
                                        $caso_beneficiario_b,
                                        $caso_voucher_b,
                                        $caso_agencia_b) {
        
        If ($caso_numero_desde_b == '') {
            $caso_numero_desde_b = NULL;
        }
        If ($caso_numero_hasta_b == '') {
            $caso_numero_hasta_b = NULL;
        }        
        If ($caso_estado_id_b == '') {
            $caso_estado_id_b = NULL;
        }
        If ($caso_tipoAsistencia_id_b == '') {
            $caso_tipoAsistencia_id_b = NULL;
        }
        if (!empty($caso_fechaSiniestro_desde_b)) {
            $caso_fechaSiniestro_desde_b = date('Y-m-d H:i:s', strtotime($caso_fechaSiniestro_desde_b));
        } else {
            $caso_fechaSiniestro_desde_b = NULL;
        }
        if (!empty($caso_fechaSiniestro_hasta_b)) {
            $caso_fechaSiniestro_hasta_b = date('Y-m-d H:i:s', strtotime($caso_fechaSiniestro_hasta_b));
        } else {
            $caso_fechaSiniestro_hasta_b = NULL;
        }
        If ($caso_beneficiario_b == '') {
            $caso_beneficiario_b = NULL;
        }
        if ($caso_voucher_b == '') {
            $caso_voucher_b = NULL;
        }
        if ($caso_agencia_b == '') {
            $caso_agencia_b = NULL;
        }
        
        DB::query("SELECT COUNT(*) AS registros FROM
                    (SELECT caso_numero
                     FROM casos  
                     WHERE caso_numero between COALESCE(:caso_numero_desde,caso_numero) and COALESCE(:caso_numero_hasta,caso_numero)
                       AND caso_casoEstado_id = COALESCE(:caso_casoEstado_id,caso_casoEstado_id)
                       AND caso_tipoAsistencia_id = COALESCE(:caso_tipoAsistencia_id,caso_tipoAsistencia_id)
                       AND caso_fechaSiniestro between COALESCE(:caso_fechaSiniestro_desde,caso_fechaSiniestro) and COALESCE(:caso_fechaSiniestro_hasta,caso_fechaSiniestro)
                       AND caso_beneficiarioNombre LIKE :caso_beneficiario
                       AND caso_numeroVoucher LIKE :caso_voucher
                       AND caso_agencia LIKE :caso_agencia
                     ) AS casos_encontrados
                   ");
        
        DB::bind(':caso_numero_desde', $caso_numero_desde_b);
        DB::bind(':caso_numero_hasta', $caso_numero_hasta_b);
        DB::bind(':caso_casoEstado_id', $caso_estado_id_b);
        DB::bind(':caso_tipoAsistencia_id', $caso_tipoAsistencia_id_b);
        DB::bind(':caso_fechaSiniestro_desde', $caso_fechaSiniestro_desde_b);
        DB::bind(':caso_fechaSiniestro_hasta', $caso_fechaSiniestro_hasta_b);
        DB::bind(':caso_beneficiario', "%$caso_beneficiario_b%");
        DB::bind(':caso_voucher', "%$caso_voucher_b%");
        DB::bind(':caso_agencia', "%$caso_agencia_b%");
        
        return DB::resultado();
    }
    

    //
    // Método para listar los casos aplicando el filtro de la grilla Agenda
    //
    public static function listar_filtrado_agenda($caso_numero_desde_b,
                                                  $caso_numero_hasta_b,
                                                  $caso_estado_id_b,
                                                  $caso_usuarioAsignado_id_b 
                                                  ) {
        
        If ($caso_numero_desde_b == '') {
            $caso_numero_desde_b = NULL;
        }
        If ($caso_numero_hasta_b == '') {
            $caso_numero_hasta_b = NULL;
        }
        If ($caso_estado_id_b == '') {
            $caso_estado_id_b = NULL;
        }
        If ($caso_usuarioAsignado_id_b == '') {
            $caso_usuarioAsignado_id_b = NULL;
        }        
        
                    
            DB::query("SELECT caso_id,
                              casoEstado_nombre,
                              caso_numero,
                              caso_fechaSiniestro,
                              paises.pais_nombreEspanol,
                              clientes.cliente_nombre,
                              tipos_asistencias.tipoAsistencia_nombre,
                              asignadoA.usuario_nombre,
                              asignadoA.usuario_apellido,
                              casos_agenda.casoAgenda_id,
                              caso_beneficiarioNombre 
                        FROM casos 
                            LEFT JOIN casos_estados on casoEstado_id = caso_casoEstado_id
                            LEFT JOIN paises on pais_id = caso_pais_id
                            LEFT JOIN clientes on cliente_id = caso_cliente_id
                            LEFT JOIN tipos_asistencias on tipoAsistencia_id = caso_tipoAsistencia_id
                            LEFT JOIN casos_agenda on casoAgenda_caso_id = caso_id
                            LEFT JOIN usuarios as asignadoA on asignadoA.usuario_id = casoAgenda_usuarioAsignado_id
                        WHERE caso_numero between COALESCE(:caso_numero_desde,caso_numero) and COALESCE(:caso_numero_hasta,caso_numero)
                          AND caso_casoEstado_id = COALESCE(:caso_casoEstado_id,caso_casoEstado_id)
                          AND
                            (CASE WHEN :caso_usuarioAsignado_id is not null 
                                  THEN (casoAgenda_usuarioAsignado_id = :caso_usuarioAsignado_id)
                                  ELSE (caso_id = caso_id) 
                             END)
                        ORDER BY caso_numero DESC
			 LIMIT 400");
                       //JJM LIMIT 50");
                        
            DB::bind(':caso_numero_desde', $caso_numero_desde_b);
            DB::bind(':caso_numero_hasta', $caso_numero_hasta_b);
            DB::bind(':caso_casoEstado_id', $caso_estado_id_b);
            DB::bind(':caso_usuarioAsignado_id', $caso_usuarioAsignado_id_b);

            return DB::resultados();
    }


    //
    // Metodo para contar la cantidad de registros del resultado del filtro
    //
    public static function listar_filtrado_contar_agenda($caso_numero_desde_b,
                                                         $caso_numero_hasta_b,
                                                         $caso_estado_id_b,
                                                         $caso_usuarioAsignado_id_b) {
        
        If ($caso_numero_desde_b == '') {
            $caso_numero_desde_b = NULL;
        }
        If ($caso_numero_hasta_b == '') {
            $caso_numero_hasta_b = NULL;
        }
        If ($caso_estado_id_b == '') {
            $caso_estado_id_b = NULL;
        }
        If ($caso_usuarioAsignado_id_b == '') {
            $caso_usuarioAsignado_id_b = NULL;
        }
        
        DB::query("SELECT COUNT(*) AS registros
                    FROM casos LEFT JOIN casos_agenda on casoAgenda_caso_id = caso_id
                    WHERE caso_numero between COALESCE(:caso_numero_desde,caso_numero) and COALESCE(:caso_numero_hasta,caso_numero)
                      AND caso_casoEstado_id = COALESCE(:caso_casoEstado_id,caso_casoEstado_id)
                      AND
                          (CASE WHEN :caso_usuarioAsignado_id is not null 
                                THEN (casoAgenda_usuarioAsignado_id = :caso_usuarioAsignado_id)
                                ELSE (caso_id = caso_id) 
                           END)");
        
        DB::bind(':caso_numero_desde', $caso_numero_desde_b);
        DB::bind(':caso_numero_hasta', $caso_numero_hasta_b);
        DB::bind(':caso_casoEstado_id', $caso_estado_id_b);
        DB::bind(':caso_usuarioAsignado_id', $caso_usuarioAsignado_id_b);
        
        return DB::resultado();
    }

    // Método para listar los últimos n Casos creados
    public static function listar_ultimos_n() {
        
        DB::query("SELECT caso_id,
                            caso_numero, 
                            caso_beneficiarioNombre, 
                            caso_fechaSiniestro, 
                            caso_numeroVoucher, 
                            caso_casoEstado_id,
                            casos_estados.casoEstado_nombre
                    FROM casos
                        LEFT JOIN casos_estados ON casoEstado_id = caso_casoEstado_id
                    ORDER BY caso_numero DESC
                    LIMIT 4;");
        
        return DB::resultados();
    }

#LISTAS    

    //---------------------------------------------------------------------------------------------------------------------------------------------- 
    // Método para crear un nuevo caso 
    // Este método por seguridad, no guarda los datos relacionados al voucher desde el formulario del caso, sino que lo hace  desde el voucher
    // guardado. En el único caso que si lo hace, es cuando se tenga el permiso especial.
    //-----------------------------------------------------------------------------------------------------------------------------------------------
    public static function insertar($caso_numeroVoucher_n, 
                                    $caso_beneficiarioNombre_n, 
                                    $caso_fechaSiniestro_n, 
                                    $caso_cliente_id_n, 
                                    $caso_tipoAsistencia_id_n, 
                                    $no_medical_cost_n,
                                    $caso_fee_id_n, 
                                    $caso_deducible_n, 
                                    $caso_paxVIP_n,
                                    $caso_legal_n,
                                    $caso_beneficiarioNacimiento_n,
                                    $caso_beneficiarioEdad_n,
                                    $caso_beneficiarioGenero_id_n, 
                                    $caso_beneficiarioDocumento_n, 
                                    $caso_pais_id_n, 
                                    $caso_ciudad_id_n, 
                                    $caso_direccion_n, 
                                    $caso_codigoPostal_n, 
                                    $caso_hotel_n, 
                                    $caso_habitacion_n, 
                                    $caso_producto_id_n, 
                                    $caso_agencia_n, 
                                    $caso_quienEmitioVoucher_n, 
                                    $caso_fechaSalida_n,
                                    $caso_fechaEmisionVoucher_n, 
                                    $caso_vigenciaVoucherDesde_n, 
                                    $caso_vigenciaVoucherHasta_n, 
                                    $caso_fechaInicioSintomas_n, 
                                    $caso_sintomas_n, 
                                    $caso_antecedentes_n, 
                                    $caso_diagnostico_id_n, 
                                    $caso_motivoVueloDemorado_n,
                                    $caso_companiaAerea_n, 
                                    $caso_numeroVuelo_n, 
                                    $caso_numeroPIR_n,
                                    $caso_titularPIR_n,
                                    $caso_numeroEquipaje_n,
                                    $caso_fechaPerdidaEquipaje_n, 
                                    $caso_fechaRecuperacionEquipaje_n, 
                                    $caso_observaciones_n, 
                                    $caso_campoSupervisor_n,
                                    $sesion_usuario_id,
                                    $caso_info_ws_n) 
    {

        
        // ***************************************************************************
        // Convierte los datos que sean necesarios convertir del formulario
        // ***************************************************************************

        // Referencia al método maxCaso_numero dentro de la clase
        $caso_numero_n = self::maxCaso_numero();
                        
        // Al crear un caso el estado por default es Pendiente (id. 1)
        $caso_casoEstado_id_n = 1;
        
        // Datos provenientes del formulario  

        $caso_paxVIP_n = Herramientas::es_booleano($caso_paxVIP_n);
        $no_medical_cost_n = Herramientas::es_booleano($no_medical_cost_n);
         
        $caso_fechaAperturaCaso_n = date('Y-m-d H:i:s');
        $caso_fechaAperturaCaso_n = Herramientas::fecha_hora_formateo($caso_fechaAperturaCaso_n);     
        $caso_fechaSiniestro_n = Herramientas::fecha_formateo($caso_fechaSiniestro_n);
        $caso_fechaSalida_n = Herramientas::fecha_formateo($caso_fechaSalida_n);
        
        $caso_fechaInicioSintomas_n = Herramientas::fecha_formateo($caso_fechaInicioSintomas_n);
        $caso_fechaPerdidaEquipaje_n = Herramientas::fecha_formateo($caso_fechaPerdidaEquipaje_n);
        $caso_fechaRecuperacionEquipaje_n = Herramientas::fecha_formateo($caso_fechaRecuperacionEquipaje_n);

        $caso_beneficiarioNacimiento_n = Herramientas::fecha_formateo($caso_beneficiarioNacimiento_n);
        $caso_beneficiarioEdad_n = Herramientas::calcula_edad($caso_beneficiarioNacimiento_n);

        $caso_beneficiarioGenero_id_n = Herramientas::genero($caso_beneficiarioGenero_id_n);

        $caso_fechaEmisionVoucher_n = Herramientas::fecha_formateo($caso_fechaEmisionVoucher_n);
        $caso_vigenciaVoucherDesde_n = Herramientas::fecha_formateo($caso_vigenciaVoucherDesde_n);
        $caso_vigenciaVoucherHasta_n = Herramientas::fecha_formateo($caso_vigenciaVoucherHasta_n);  

        $caso_ultimaModificacion_n = date("Y-m-d H:i:s");

        // ****************************************************************************
        // Verifica si se por webservice y reescribe los campos
        // ****************************************************************************
        if($caso_info_ws_n == '1')
        {
            // Si proviene del WS sobreescribe las variables provenientes del formulario
            
            // Toma los datos del voucher para incorporarlos al caso
            $resultado_voucher = Voucher::buscar($caso_numeroVoucher_n);

 
            // Verifica que el voucher ha sido encontrado
            if (is_array($resultado_voucher)) {
  
                // Datos provenientes del voucher - Estos datos son 
                $caso_beneficiarioNombre_n = $resultado_voucher["passenger_last_name"].", ".$resultado_voucher["passenger_first_name"]." ".$resultado_voucher["passenger_second_name"];
                $caso_cliente_id_n = Cliente::obtener_cliente_coris_id($resultado_voucher["voucher_number"]);
                $caso_beneficiarioNacimiento_n = Herramientas::fecha_formateo($resultado_voucher["passenger_birth_date"]);
                $caso_deducible_n = $resultado_voucher["deducible"];
                $caso_beneficiarioEdad_n = Herramientas::calcula_edad($caso_beneficiarioNacimiento_n);
                $caso_beneficiarioGenero_id_n = Herramientas::genero($resultado_voucher["passenger_gender"]);
                $caso_beneficiarioDocumento_n = $resultado_voucher["passenger_document_number"];
                $caso_agencia_n = $resultado_voucher["agency_name"];
                $caso_quienEmitioVoucher_n = $resultado_voucher["issuing_user_name"];
                $caso_fechaEmisionVoucher_n = Herramientas::fecha_formateo($resultado_voucher["voucher_date"]);
                $caso_vigenciaVoucherDesde_n = Herramientas::fecha_formateo($resultado_voucher["voucher_date_from"]);
                $caso_vigenciaVoucherHasta_n = Herramientas::fecha_formateo($resultado_voucher["voucher_date_to"]);  

                // Para determinar que el voucher existe
                $voucher_existe = true;

            } 
          
        }

        // Comprueba si el voucher esta fuera de cobertura
        if  (($caso_fechaAperturaCaso_n < $caso_vigenciaVoucherDesde_n) || ($caso_fechaAperturaCaso_n > $caso_vigenciaVoucherHasta_n) ||
            ($caso_fechaSiniestro_n    < $caso_vigenciaVoucherDesde_n) || ($caso_fechaSiniestro_n    > $caso_vigenciaVoucherHasta_n)) {
            $caso_voucherFueraCobertura = 1;
        } else {
            $caso_voucherFueraCobertura = 0;
        }

        // Caso anulado = 0
        $caso_anulado_n = 0;
        // Caso de sistema antiguo
        $caso_sistemaAntiguo_n = 0;

        // Condición en donde guarda el voucher si el caso viene por medio manual y el usuario tiene permiso para carga manual
        // O si el caso viene  por voucher y el voucher existe. 
        // Si no se cumplen estas condiciones, no tiene autorización.
        If (($caso_info_ws_n == '0' && Usuario::puede("carga_caso_manual") == 1)||($caso_info_ws_n == '1' && $voucher_existe = true))
        {

            // Guarda el caso
            DB::query("INSERT INTO casos 
            (caso_numero,  
            caso_cliente_id, 
            caso_tipoAsistencia_id,
            no_medical_cost,
            caso_pais_id,
            caso_ciudad_id,
            caso_diagnostico_id,
            caso_abiertoPor_id,
            caso_producto_id, 
            caso_fee_id,
            caso_beneficiarioGenero_id, 
            caso_casoEstado_id,
            caso_fechaAperturaCaso,
            caso_ultimaModificacion,
            caso_beneficiarioNombre,             
            caso_numeroVoucher,
            caso_fechaSiniestro,
            caso_beneficiarioNacimiento,
            caso_beneficiarioEdad,
            caso_beneficiarioDocumento,
            caso_direccion,
            caso_hotel,
            caso_habitacion,
            caso_codigoPostal,
            caso_fechaSalida,
            caso_quienEmitioVoucher,
            caso_fechaEmisionVoucher, 
            caso_vigenciaVoucherDesde,  
            caso_vigenciaVoucherHasta,
            caso_agencia,
            caso_sintomas,
            caso_fechaInicioSintomas,
            caso_antecedentes,
            caso_motivoVueloDemorado,
            caso_numeroEquipaje,
            caso_numeroPIR,
            caso_titularPIR,
            caso_companiaAerea,
            caso_numeroVuelo,
            caso_fechaPerdidaEquipaje,
            caso_fechaRecuperacionEquipaje,
            caso_paxVIP, 
            caso_legal,
            caso_deducible,
            caso_observaciones,
            caso_campoSupervisor,
            caso_info_ws,
            caso_voucherFueraCobertura,
            caso_sistemaAntiguo,
            caso_anulado)
            VALUES 
            (:caso_numero_n,
            :caso_cliente_id_n,
            :caso_tipoAsistencia_id_n,
            :no_medical_cost_n,
            :caso_pais_id_n,
            :caso_ciudad_id_n,
            :caso_diagnostico_id_n,
            :caso_abiertoPor_id_n,
            :caso_producto_id_n,
            :caso_fee_id_n,
            :caso_beneficiarioGenero_id_n,
            :caso_casoEstado_id_n,
            :caso_fechaAperturaCaso_n,
            :caso_ultimaModificacion_n,
            :caso_beneficiarioNombre_n,
            :caso_numeroVoucher_n,
            :caso_fechaSiniestro_n,             
            :caso_beneficiarioNacimiento_n,             
            :caso_beneficiarioEdad_n,            
            :caso_beneficiarioDocumento_n,             
            :caso_direccion_n,             
            :caso_hotel_n,
            :caso_habitacion_n,             
            :caso_codigoPostal_n,             
            :caso_fechaSalida_n,             
            :caso_quienEmitioVoucher_n,             
            :caso_fechaEmisionVoucher_n,             
            :caso_vigenciaVoucherDesde_n,
            :caso_vigenciaVoucherHasta_n,
            :caso_agencia_n,
            :caso_sintomas_n,
            :caso_fechaInicioSintomas_n,
            :caso_antecedentes_n,
            :caso_motivoVueloDemorado_n,
            :caso_numeroEquipaje_n,
            :caso_numeroPIR_n,
            :caso_titularPIR_n,
            :caso_companiaAerea_n,
            :caso_numeroVuelo_n,
            :caso_fechaPerdidaEquipaje_n,
            :caso_fechaRecuperacionEquipaje_n,
            :caso_paxVIP_n,
            :caso_legal_n,
            :caso_deducible_n,
            :caso_observaciones_n,            
            :caso_campoSupervisor_n,            
            :caso_info_ws_n,             
            :caso_voucherFueraCobertura,
            :caso_sistemaAntiguo,
            :caso_anulado)");
     
            DB::bind(':caso_numero_n', $caso_numero_n);
            DB::bind(':caso_cliente_id_n', $caso_cliente_id_n);
            DB::bind(':caso_tipoAsistencia_id_n', $caso_tipoAsistencia_id_n);
            DB::bind(':no_medical_cost_n', $no_medical_cost_n);
            DB::bind(':caso_pais_id_n', $caso_pais_id_n);
            DB::bind(':caso_ciudad_id_n', $caso_ciudad_id_n);
            DB::bind(':caso_diagnostico_id_n', $caso_diagnostico_id_n);
            DB::bind(':caso_abiertoPor_id_n', $sesion_usuario_id);
            DB::bind(':caso_producto_id_n', $caso_producto_id_n);
            DB::bind(':caso_fee_id_n', $caso_fee_id_n);
            DB::bind(':caso_beneficiarioGenero_id_n', $caso_beneficiarioGenero_id_n);
            DB::bind(':caso_casoEstado_id_n', $caso_casoEstado_id_n);
            DB::bind(':caso_fechaAperturaCaso_n', (is_null($caso_fechaAperturaCaso_n)? $caso_fechaAperturaCaso_n : "$caso_fechaAperturaCaso_n") );   	    
            DB::bind(':caso_ultimaModificacion_n', "$caso_ultimaModificacion_n");
            DB::bind(':caso_beneficiarioNombre_n', "$caso_beneficiarioNombre_n");
            DB::bind(':caso_numeroVoucher_n', "$caso_numeroVoucher_n");
            DB::bind(':caso_fechaSiniestro_n', (is_null($caso_fechaSiniestro_n)? $caso_fechaSiniestro_n : "$caso_fechaSiniestro_n") );
            DB::bind(':caso_beneficiarioNacimiento_n', $caso_beneficiarioNacimiento_n);
            DB::bind(':caso_beneficiarioEdad_n', $caso_beneficiarioEdad_n);
            DB::bind(':caso_beneficiarioDocumento_n', "$caso_beneficiarioDocumento_n");
            DB::bind(':caso_direccion_n', "$caso_direccion_n");
            DB::bind(':caso_hotel_n', "$caso_hotel_n");
            DB::bind(':caso_habitacion_n', "$caso_habitacion_n");
            DB::bind(':caso_codigoPostal_n', "$caso_codigoPostal_n");  
            DB::bind(':caso_fechaSalida_n', (is_null($caso_fechaSalida_n)? $caso_fechaSalida_n : "$caso_fechaSalida_n"));
            DB::bind(':caso_quienEmitioVoucher_n', "$caso_quienEmitioVoucher_n");
            DB::bind(':caso_fechaEmisionVoucher_n', (is_null($caso_fechaEmisionVoucher_n)? $caso_fechaEmisionVoucher_n : "$caso_fechaEmisionVoucher_n") );
            DB::bind(':caso_vigenciaVoucherDesde_n', (is_null($caso_vigenciaVoucherDesde_n)? $caso_vigenciaVoucherDesde_n : "$caso_vigenciaVoucherDesde_n") );
            DB::bind(':caso_vigenciaVoucherHasta_n', (is_null($caso_vigenciaVoucherHasta_n)? $caso_vigenciaVoucherHasta_n : "$caso_vigenciaVoucherHasta_n") );
            DB::bind(':caso_agencia_n', "$caso_agencia_n");
            DB::bind(':caso_sintomas_n', "$caso_sintomas_n");
            DB::bind(':caso_fechaInicioSintomas_n', (is_null($caso_fechaInicioSintomas_n)? $caso_fechaInicioSintomas_n : "$caso_fechaInicioSintomas_n") );
            DB::bind(':caso_antecedentes_n', "$caso_antecedentes_n");
            DB::bind(':caso_motivoVueloDemorado_n', "$caso_motivoVueloDemorado_n");
            DB::bind(':caso_numeroEquipaje_n', "$caso_numeroEquipaje_n");
            DB::bind(':caso_numeroPIR_n', "$caso_numeroPIR_n");
            DB::bind(':caso_titularPIR_n', "$caso_titularPIR_n");
            DB::bind(':caso_companiaAerea_n', "$caso_companiaAerea_n");
            DB::bind(':caso_numeroVuelo_n', "$caso_numeroVuelo_n");
            DB::bind(':caso_fechaPerdidaEquipaje_n', (is_null($caso_fechaPerdidaEquipaje_n)? $caso_fechaPerdidaEquipaje_n : "$caso_fechaPerdidaEquipaje_n") );
            DB::bind(':caso_fechaRecuperacionEquipaje_n', (is_null($caso_fechaRecuperacionEquipaje_n)? $caso_fechaRecuperacionEquipaje_n : "$caso_fechaRecuperacionEquipaje_n") );
            DB::bind(':caso_paxVIP_n', $caso_paxVIP_n);
            DB::bind(':caso_legal_n', $caso_legal_n);
            DB::bind(':caso_deducible_n', "$caso_deducible_n");
            DB::bind(':caso_observaciones_n', "$caso_observaciones_n");
            DB::bind(':caso_campoSupervisor_n', "$caso_campoSupervisor_n");
            DB::bind(':caso_info_ws_n', $caso_info_ws_n);
            DB::bind(':caso_voucherFueraCobertura', $caso_voucherFueraCobertura);
            DB::bind(':caso_sistemaAntiguo', $caso_sistemaAntiguo_n);
            DB::bind(':caso_anulado', $caso_anulado_n);
            
            DB::execute(); 
            
            $resultado = DB::lastInsertId();

            // Se autoasigna el caso a la persona que lo creó
            Agenda::asignar_caso($resultado,
                                 $sesion_usuario_id,
                                 $sesion_usuario_id);

        } else {

            // No tiene autorización
            $resultado = "No tiene autorización para esta operación.";
        }
       
        return $resultado;

    }
       
    

    // Método para buscar el caso_numero mas grande e incrementarlo en +1 para asignar el nuevo número de caso
    public static function maxCaso_numero() {
        DB::query("SELECT MAX(caso_numero) as caso_numero FROM casos");
        
        $maxCaso = DB::resultado();
        
        // Incrementa en +1 el resultado del select
        $caso_numero = $maxCaso['caso_numero'] + 1;
        
        return $caso_numero;
    }
   


    // ****************************************************************************************
    // Método para MODIFICAR un Caso
    // Este método debe identificar que la información del voucher sea la proveniente del WS
    // ****************************************************************************************
    
    public static function actualizar($caso_id,
                                        $caso_numeroVoucher, 
                                        $caso_beneficiarioNombre, 
                                        $caso_fechaSiniestro, 
                                        $caso_cliente_id, 
                                        $caso_tipoAsistencia_id, 
                                        $no_medical_cost,
                                        $caso_fee_id, 
                                        $caso_deducible, 
                                        $caso_paxVIP, 
                                        $caso_legal,
                                        $caso_beneficiarioNacimiento,
                                        $caso_beneficiarioEdad,
                                        $caso_beneficiarioGenero_id, 
                                        $caso_beneficiarioDocumento, 
                                        $caso_pais_id, 
                                        $caso_ciudad_id, 
                                        $caso_direccion, 
                                        $caso_codigoPostal, 
                                        $caso_hotel, 
                                        $caso_habitacion, 
                                        $caso_producto_id, 
                                        $caso_agencia,
                                        $caso_quienEmitioVoucher,
                                        $caso_fechaSalida,
                                        $caso_fechaEmisionVoucher,
                                        $caso_vigenciaVoucherDesde,
                                        $caso_vigenciaVoucherHasta,
                                        $caso_fechaInicioSintomas,
                                        $caso_sintomas,
                                        $caso_antecedentes,
                                        $caso_diagnostico_id,
                                        $caso_motivoVueloDemorado,
                                        $caso_companiaAerea,
                                        $caso_numeroVuelo,
                                        $caso_numeroPIR,
                                        $caso_titularPIR,
                                        $caso_numeroEquipaje,
                                        $caso_fechaPerdidaEquipaje,
                                        $caso_fechaRecuperacionEquipaje,
                                        $caso_observaciones,
                                        $caso_campoSupervisor,
                                        $caso_info_ws) {
        
        // ***************************************************************************
        // Convierte los datos que sean necesarios convertir del formulario
        // ***************************************************************************

        
        // Datos provenientes del formulario
        $caso_paxVIP = Herramientas::es_booleano($caso_paxVIP);
        $caso_legal = Herramientas::es_booleano($caso_legal);
        $no_medical_cost = Herramientas::es_booleano($no_medical_cost);
        $caso_fechaSiniestro = Herramientas::fecha_formateo($caso_fechaSiniestro);
        $caso_fechaSalida = Herramientas::fecha_formateo($caso_fechaSalida);
        
        $caso_fechaInicioSintomas = Herramientas::fecha_formateo($caso_fechaInicioSintomas);
        $caso_fechaPerdidaEquipaje = Herramientas::fecha_formateo($caso_fechaPerdidaEquipaje);
        $caso_fechaRecuperacionEquipaje = Herramientas::fecha_formateo($caso_fechaRecuperacionEquipaje);

        $caso_beneficiarioNacimiento = Herramientas::fecha_formateo($caso_beneficiarioNacimiento);
        $caso_beneficiarioEdad = Herramientas::calcula_edad($caso_beneficiarioNacimiento);

        $caso_beneficiarioGenero_id = Herramientas::genero($caso_beneficiarioGenero_id);

        $caso_fechaEmisionVoucher = Herramientas::fecha_formateo($caso_fechaEmisionVoucher);
        $caso_vigenciaVoucherDesde = Herramientas::fecha_formateo($caso_vigenciaVoucherDesde);
        $caso_vigenciaVoucherHasta = Herramientas::fecha_formateo($caso_vigenciaVoucherHasta);  

        $caso_ultimaModificacion = date("Y-m-d H:i:s");


        // ****************************************************************************
        // Verifica si es por webservice y reescribe los campos
        // ****************************************************************************
        if ($caso_info_ws == '1')
        {
            // Si proviene del WS sobreescribe las variables provenientes del formulario
            
            // Toma los datos del voucher para incorporarlos al caso
            $resultado_voucher = Voucher::buscar($caso_numeroVoucher);

 
            // Verifica que el voucher ha sido encontrado
            if (is_array($resultado_voucher)) {
  
                // Datos provenientes del voucher - Estos datos son 
                $caso_beneficiarioNombre = $resultado_voucher["passenger_last_name"].", ".$resultado_voucher["passenger_first_name"]." ".$resultado_voucher["passenger_second_name"];
                $caso_cliente_id = Cliente::obtener_cliente_coris_id($resultado_voucher["voucher_number"]);
                $caso_beneficiarioNacimiento = Herramientas::fecha_formateo($resultado_voucher["passenger_birth_date"]);
                $caso_deducible = $resultado_voucher["deducible"];
                $caso_beneficiarioEdad = Herramientas::calcula_edad($caso_beneficiarioNacimiento);
                $caso_beneficiarioGenero_id = Herramientas::genero($resultado_voucher["passenger_gender"]);
                $caso_beneficiarioDocumento = $resultado_voucher["passenger_document_number"];
                $caso_agencia = $resultado_voucher["agency_name"];
                $caso_quienEmitioVoucher = $resultado_voucher["issuing_user_name"];
                $caso_fechaEmisionVoucher = Herramientas::fecha_formateo($resultado_voucher["voucher_date"]);
                $caso_vigenciaVoucherDesde = Herramientas::fecha_formateo($resultado_voucher["voucher_date_from"]);
                $caso_vigenciaVoucherHasta = Herramientas::fecha_formateo($resultado_voucher["voucher_date_to"]);  

                // Para determinar que el voucher existe
                $voucher_existe = true;

            } 

        }

        // Comprueba si el voucher esta fuera de cobertura
        if  (($caso_ultimaModificacion < $caso_vigenciaVoucherDesde) || ($caso_ultimaModificacion > $caso_vigenciaVoucherHasta) ||
            ($caso_fechaSiniestro < $caso_vigenciaVoucherDesde) || ($caso_fechaSiniestro > $caso_vigenciaVoucherHasta)) {
            $caso_voucherFueraCobertura = 1;
        } else {
            $caso_voucherFueraCobertura = 0;
        }
        
        // Condición en donde actualiza el voucher si el caso viene por medio manual y el usuario tiene permiso para carga manual
        // Y no tiene servicios o reintegos
        // O si el caso viene  por voucher y el voucher existe. 

        // Si no se cumplen estas condiciones, no tiene autorización.

        // Caso Manual?
        $caso_ws = Caso::caso_info_ws($caso_id);

        // Tiene servicios?
        $servicios = count(Servicio::listar($caso_id));

        // Tiene reintegros?
        $reintegros = count(Reintegro::listarPorCaso($caso_id));
        
        
        // Condición si existen servicios o reintegros cargados.
        if ((($servicios < 1) && ($reintegros < 1)) || $caso_ws == 0) {
            
        
            // El voucher se puede cambiar por otro. Pero solo desde el buscador, no manual.
            if ($caso_info_ws == '1' && $voucher_existe = true)

            {
                            
                DB::query("UPDATE casos SET
                            caso_numeroVoucher = :caso_numeroVoucher,
                            caso_beneficiarioNombre = :caso_beneficiarioNombre,
                            caso_fechaSiniestro = :caso_fechaSiniestro,
                            caso_cliente_id = :caso_cliente_id,
                            caso_tipoAsistencia_id = :caso_tipoAsistencia_id,
                            no_medical_cost = :no_medical_cost,
                            caso_fee_id = :caso_fee_id,
                            caso_deducible = :caso_deducible,
                            caso_paxVIP = :caso_paxVIP,
                            caso_legal = :caso_legal,
                            caso_beneficiarioNacimiento = :caso_beneficiarioNacimiento,
                            caso_beneficiarioEdad = :caso_beneficiarioEdad,
                            caso_beneficiarioGenero_id = :caso_beneficiarioGenero_id,
                            caso_beneficiarioDocumento = :caso_beneficiarioDocumento,
                            caso_pais_id = :caso_pais_id,
                            caso_ciudad_id = :caso_ciudad_id,
                            caso_direccion = :caso_direccion,
                            caso_codigoPostal = :caso_codigoPostal,
                            caso_hotel = :caso_hotel,
                            caso_habitacion = :caso_habitacion,
                            caso_producto_id = :caso_producto_id,
                            caso_agencia = :caso_agencia,
                            caso_quienEmitioVoucher = :caso_quienEmitioVoucher,
                            caso_fechaSalida = :caso_fechaSalida,
                            caso_fechaEmisionVoucher = :caso_fechaEmisionVoucher,
                            caso_vigenciaVoucherDesde = :caso_vigenciaVoucherDesde,
                            caso_vigenciaVoucherHasta = :caso_vigenciaVoucherHasta,
                            caso_fechaInicioSintomas = :caso_fechaInicioSintomas,
                            caso_sintomas = :caso_sintomas,
                            caso_antecedentes = :caso_antecedentes,
                            caso_diagnostico_id = :caso_diagnostico_id,
                            caso_motivoVueloDemorado = :caso_motivoVueloDemorado,
                            caso_companiaAerea = :caso_companiaAerea,
                            caso_numeroVuelo = :caso_numeroVuelo,
                            caso_numeroPIR = :caso_numeroPIR,
                            caso_titularPIR = :caso_titularPIR,
                            caso_numeroEquipaje = :caso_numeroEquipaje,
                            caso_fechaPerdidaEquipaje = :caso_fechaPerdidaEquipaje,
                            caso_fechaRecuperacionEquipaje = :caso_fechaRecuperacionEquipaje,
                            caso_observaciones = :caso_observaciones,
                            caso_campoSupervisor = :caso_campoSupervisor,
                            caso_voucherFueraCobertura = :caso_voucherFueraCobertura,
                            caso_ultimaModificacion = :caso_ultimaModificacion,
                            caso_info_ws = :caso_info_ws
                        WHERE caso_id = :caso_id");
                
                DB::bind(':caso_numeroVoucher', "$caso_numeroVoucher");
                DB::bind(':caso_beneficiarioNombre', "$caso_beneficiarioNombre");
                DB::bind(':caso_fechaSiniestro', (is_null($caso_fechaSiniestro)? $caso_fechaSiniestro : "$caso_fechaSiniestro"));
                DB::bind(':caso_cliente_id', "$caso_cliente_id");
                DB::bind(':caso_tipoAsistencia_id', "$caso_tipoAsistencia_id");
                DB::bind(':no_medical_cost', "$no_medical_cost");
                DB::bind(':caso_fee_id', "$caso_fee_id");
                DB::bind(':caso_deducible', "$caso_deducible");
                DB::bind(':caso_paxVIP', "$caso_paxVIP");
                DB::bind(':caso_legal', "$caso_legal");
                DB::bind(':caso_beneficiarioNacimiento', "$caso_beneficiarioNacimiento");
                DB::bind(':caso_beneficiarioEdad', "$caso_beneficiarioEdad");
                DB::bind(':caso_beneficiarioGenero_id', "$caso_beneficiarioGenero_id");
                DB::bind(':caso_beneficiarioDocumento', "$caso_beneficiarioDocumento");
                DB::bind(':caso_pais_id', "$caso_pais_id");
                DB::bind(':caso_ciudad_id', "$caso_ciudad_id");
                DB::bind(':caso_direccion', "$caso_direccion");
                DB::bind(':caso_codigoPostal', "$caso_codigoPostal");
                DB::bind(':caso_hotel', "$caso_hotel");
                DB::bind(':caso_habitacion', "$caso_habitacion");
                DB::bind(':caso_producto_id', "$caso_producto_id");
                DB::bind(':caso_agencia', "$caso_agencia");
                DB::bind(':caso_quienEmitioVoucher', "$caso_quienEmitioVoucher");
                DB::bind(':caso_fechaSalida', (is_null($caso_fechaSalida)? $caso_fechaSalida : "$caso_fechaSalida"));
                DB::bind(':caso_fechaEmisionVoucher', (is_null($caso_fechaEmisionVoucher)? $caso_fechaEmisionVoucher : "$caso_fechaEmisionVoucher"));
                DB::bind(':caso_vigenciaVoucherDesde', (is_null($caso_vigenciaVoucherDesde)? $caso_vigenciaVoucherDesde : "$caso_vigenciaVoucherDesde"));
                DB::bind(':caso_vigenciaVoucherHasta', (is_null($caso_vigenciaVoucherHasta)? $caso_vigenciaVoucherHasta : "$caso_vigenciaVoucherHasta"));
                DB::bind(':caso_fechaInicioSintomas', (is_null($caso_fechaInicioSintomas)? $caso_fechaInicioSintomas : "$caso_fechaInicioSintomas"));
                DB::bind(':caso_sintomas', "$caso_sintomas");
                DB::bind(':caso_antecedentes', "$caso_antecedentes");
                DB::bind(':caso_diagnostico_id', "$caso_diagnostico_id");
                DB::bind(':caso_motivoVueloDemorado', "$caso_motivoVueloDemorado");
                DB::bind(':caso_companiaAerea', "$caso_companiaAerea");
                DB::bind(':caso_numeroVuelo', "$caso_numeroVuelo");
                DB::bind(':caso_numeroPIR', "$caso_numeroPIR");
                DB::bind(':caso_titularPIR', "$caso_titularPIR");
                DB::bind(':caso_numeroEquipaje', "$caso_numeroEquipaje");
                DB::bind(':caso_fechaPerdidaEquipaje', (is_null($caso_fechaPerdidaEquipaje)? $caso_fechaPerdidaEquipaje : "$caso_fechaPerdidaEquipaje"));
                DB::bind(':caso_fechaRecuperacionEquipaje', (is_null($caso_fechaRecuperacionEquipaje)? $caso_fechaRecuperacionEquipaje : "$caso_fechaRecuperacionEquipaje"));
                DB::bind(':caso_observaciones', "$caso_observaciones");
                DB::bind(':caso_campoSupervisor', "$caso_campoSupervisor");
                DB::bind(':caso_voucherFueraCobertura', "$caso_voucherFueraCobertura");
                DB::bind(':caso_ultimaModificacion', "$caso_ultimaModificacion");
                DB::bind(':caso_id', "$caso_id");
                DB::bind(':caso_info_ws', "$caso_info_ws");

                DB::execute();

                return "Los cambios han sido guardados satisfactoriamente.";

            // El voucher es manual, por lo que no se pueden cambiar los datos del voucher

            } else {

                DB::query("UPDATE casos SET
                            caso_fechaSiniestro = :caso_fechaSiniestro,
                            caso_tipoAsistencia_id = :caso_tipoAsistencia_id,
                            no_medical_cost = :no_medical_cost,
                            caso_fee_id = :caso_fee_id,
                            caso_paxVIP = :caso_paxVIP,
                            caso_legal = :caso_legal,
                            caso_beneficiarioGenero_id = :caso_beneficiarioGenero_id,
                            caso_pais_id = :caso_pais_id,
                            caso_ciudad_id = :caso_ciudad_id,
                            caso_direccion = :caso_direccion,
                            caso_codigoPostal = :caso_codigoPostal,
                            caso_hotel = :caso_hotel,
                            caso_habitacion = :caso_habitacion,
                            caso_fechaSalida = :caso_fechaSalida,
                            caso_fechaInicioSintomas = :caso_fechaInicioSintomas,
                            caso_sintomas = :caso_sintomas,
                            caso_antecedentes = :caso_antecedentes,
                            caso_diagnostico_id = :caso_diagnostico_id,
                            caso_motivoVueloDemorado = :caso_motivoVueloDemorado,
                            caso_companiaAerea = :caso_companiaAerea,
                            caso_numeroVuelo = :caso_numeroVuelo,
                            caso_numeroPIR = :caso_numeroPIR,
                            caso_titularPIR = :caso_titularPIR,
                            caso_numeroEquipaje = :caso_numeroEquipaje,
                            caso_fechaPerdidaEquipaje = :caso_fechaPerdidaEquipaje,
                            caso_fechaRecuperacionEquipaje = :caso_fechaRecuperacionEquipaje,
                            caso_observaciones = :caso_observaciones,
                            caso_campoSupervisor = :caso_campoSupervisor,
                            caso_voucherFueraCobertura = :caso_voucherFueraCobertura,
                            caso_ultimaModificacion = :caso_ultimaModificacion,
                            caso_info_ws = :caso_info_ws
                        WHERE caso_id = :caso_id");

                
                DB::bind(':caso_fechaSiniestro', (is_null($caso_fechaSiniestro)? $caso_fechaSiniestro : "$caso_fechaSiniestro"));
                DB::bind(':caso_tipoAsistencia_id', "$caso_tipoAsistencia_id");
                DB::bind(':no_medical_cost', "$no_medical_cost");
                DB::bind(':caso_fee_id', "$caso_fee_id");
                DB::bind(':caso_paxVIP', "$caso_paxVIP");
                DB::bind(':caso_legal', "$caso_legal");
                DB::bind(':caso_beneficiarioGenero_id', "$caso_beneficiarioGenero_id");
                DB::bind(':caso_pais_id', "$caso_pais_id");
                DB::bind(':caso_ciudad_id', "$caso_ciudad_id");
                DB::bind(':caso_direccion', "$caso_direccion");
                DB::bind(':caso_codigoPostal', "$caso_codigoPostal");
                DB::bind(':caso_hotel', "$caso_hotel");
                DB::bind(':caso_habitacion', "$caso_habitacion");
                DB::bind(':caso_fechaSalida', (is_null($caso_fechaSalida)? $caso_fechaSalida : "$caso_fechaSalida") );
                DB::bind(':caso_fechaInicioSintomas', (is_null($caso_fechaInicioSintomas)? $caso_fechaInicioSintomas : "$caso_fechaInicioSintomas"));
                DB::bind(':caso_sintomas', "$caso_sintomas");
                DB::bind(':caso_antecedentes', "$caso_antecedentes");
                DB::bind(':caso_diagnostico_id', "$caso_diagnostico_id");
                DB::bind(':caso_motivoVueloDemorado', "$caso_motivoVueloDemorado");
                DB::bind(':caso_companiaAerea', "$caso_companiaAerea");
                DB::bind(':caso_numeroVuelo', "$caso_numeroVuelo");
                DB::bind(':caso_numeroPIR', "$caso_numeroPIR");
                DB::bind(':caso_titularPIR', "$caso_titularPIR");
                DB::bind(':caso_numeroEquipaje', "$caso_numeroEquipaje");
                DB::bind(':caso_fechaPerdidaEquipaje', (is_null($caso_fechaPerdidaEquipaje)? $caso_fechaPerdidaEquipaje : "$caso_fechaPerdidaEquipaje"));
                DB::bind(':caso_fechaRecuperacionEquipaje', (is_null($caso_fechaRecuperacionEquipaje)? $caso_fechaRecuperacionEquipaje : "$caso_fechaRecuperacionEquipaje"));
                DB::bind(':caso_observaciones', "$caso_observaciones");
                DB::bind(':caso_campoSupervisor', "$caso_campoSupervisor");
                DB::bind(':caso_voucherFueraCobertura', "$caso_voucherFueraCobertura");
                DB::bind(':caso_ultimaModificacion', "$caso_ultimaModificacion");
                DB::bind(':caso_id', "$caso_id");
                DB::bind(':caso_info_ws', "$caso_info_ws");

                DB::execute();
                
                return "No se guardaron los datos del voucher porque el mismo debe incorporarse mediante el buscador. El resto de los cambios han sido guardados satisfactoriamente.";

            }
  
        } else {

            // Al haber servicios o reintegros cargados, no se modifican los datos del voucher

            DB::query("UPDATE casos SET
                caso_fechaSiniestro = :caso_fechaSiniestro,
                caso_tipoAsistencia_id = :caso_tipoAsistencia_id,
                no_medical_cost = :no_medical_cost,
                caso_fee_id = :caso_fee_id,
                caso_paxVIP = :caso_paxVIP,
                caso_legal = :caso_legal,
                caso_beneficiarioGenero_id = :caso_beneficiarioGenero_id,
                caso_pais_id = :caso_pais_id,
                caso_ciudad_id = :caso_ciudad_id,
                caso_direccion = :caso_direccion,
                caso_codigoPostal = :caso_codigoPostal,
                caso_hotel = :caso_hotel,
                caso_habitacion = :caso_habitacion,
                caso_fechaSalida = :caso_fechaSalida,
                caso_fechaInicioSintomas = :caso_fechaInicioSintomas,
                caso_sintomas = :caso_sintomas,
                caso_antecedentes = :caso_antecedentes,
                caso_diagnostico_id = :caso_diagnostico_id,
                caso_motivoVueloDemorado = :caso_motivoVueloDemorado,
                caso_companiaAerea = :caso_companiaAerea,
                caso_numeroVuelo = :caso_numeroVuelo,
                caso_numeroPIR = :caso_numeroPIR,
                caso_titularPIR = :caso_titularPIR,
                caso_numeroEquipaje = :caso_numeroEquipaje,
                caso_fechaPerdidaEquipaje = :caso_fechaPerdidaEquipaje,
                caso_fechaRecuperacionEquipaje = :caso_fechaRecuperacionEquipaje,
                caso_observaciones = :caso_observaciones,
                caso_campoSupervisor = :caso_campoSupervisor,
                caso_ultimaModificacion = :caso_ultimaModificacion,
                caso_info_ws = :caso_info_ws
            WHERE caso_id = :caso_id");


            DB::bind(':caso_fechaSiniestro', (is_null($caso_fechaSiniestro)? $caso_fechaSiniestro : "$caso_fechaSiniestro"));
            DB::bind(':caso_tipoAsistencia_id', "$caso_tipoAsistencia_id");
            DB::bind(':no_medical_cost', "$no_medical_cost");
            DB::bind(':caso_fee_id', "$caso_fee_id");
            DB::bind(':caso_paxVIP', "$caso_paxVIP");
            DB::bind(':caso_legal', "$caso_legal");
            DB::bind(':caso_beneficiarioGenero_id', "$caso_beneficiarioGenero_id");
            DB::bind(':caso_pais_id', "$caso_pais_id");
            DB::bind(':caso_ciudad_id', "$caso_ciudad_id");
            DB::bind(':caso_direccion', "$caso_direccion");
            DB::bind(':caso_codigoPostal', "$caso_codigoPostal");
            DB::bind(':caso_hotel', "$caso_hotel");
            DB::bind(':caso_habitacion', "$caso_habitacion");
            DB::bind(':caso_fechaSalida', "$caso_fechaSalida");
            DB::bind(':caso_fechaInicioSintomas', (is_null($caso_fechaInicioSintomas)? $caso_fechaInicioSintomas : "$caso_fechaInicioSintomas"));
            DB::bind(':caso_sintomas', "$caso_sintomas");
            DB::bind(':caso_antecedentes', "$caso_antecedentes");
            DB::bind(':caso_diagnostico_id', "$caso_diagnostico_id");
            DB::bind(':caso_motivoVueloDemorado', "$caso_motivoVueloDemorado");
            DB::bind(':caso_companiaAerea', "$caso_companiaAerea");
            DB::bind(':caso_numeroVuelo', "$caso_numeroVuelo");
            DB::bind(':caso_numeroPIR', "$caso_numeroPIR");
            DB::bind(':caso_titularPIR', "$caso_titularPIR");
            DB::bind(':caso_numeroEquipaje', "$caso_numeroEquipaje");
            DB::bind(':caso_fechaPerdidaEquipaje', (is_null($caso_fechaPerdidaEquipaje)? $caso_fechaPerdidaEquipaje : "$caso_fechaPerdidaEquipaje"));
            DB::bind(':caso_fechaRecuperacionEquipaje', (is_null($caso_fechaRecuperacionEquipaje)? $caso_fechaRecuperacionEquipaje : "$caso_fechaRecuperacionEquipaje"));
            DB::bind(':caso_observaciones', "$caso_observaciones");
            DB::bind(':caso_campoSupervisor', "$caso_campoSupervisor");
            DB::bind(':caso_ultimaModificacion', "$caso_ultimaModificacion");
            DB::bind(':caso_id', "$caso_id");
            DB::bind(':caso_info_ws', "$caso_info_ws");

            DB::execute();

            return "Los datos del voucher no se modificaron, dado que el Caso posee Servicios o Reintegros cargados";

        }

    }





    // Método para buscar caso_id usando como parametro caso_numero
    public static function buscar_caso_id($caso_numero) {
        DB::query("SELECT caso_id
                    FROM casos
                    WHERE caso_numero = :caso_numero");
        
        DB::bind(':caso_numero', "$caso_numero");
        
        return DB::resultado();      
    }
    
    
    // Método para buscar caso_numero usando como parametro caso_id
    public static function buscar_caso_numero($caso_id) {
        DB::query("SELECT caso_numero
                    FROM casos
                    WHERE caso_id = :caso_id");
        
        DB::bind(':caso_id', "$caso_id");
        
        return DB::resultado();      
    }
    
    
    // Método para mostrar la información de un caso (según caso_id)
    public static function buscarPorId($caso_id) {

        /*
        | Valida si el usuario puede Ver la Información de los Casos, sino solo muestra información del Reintegro
        | Se aplica de esta forma para los usuario de Oficinas CORIS Externas
        */
        if (Usuario::puede("ver_info_casos") == 1) {

            DB::query("SELECT caso_id,
                            caso_numero,
                            caso_casoEstado_id,
                            casoEstado_nombre,
                            caso_abiertoPor_id,
                            abiertoPor.usuario_nombre AS abiertoPor_nombre,
                            abiertoPor.usuario_apellido AS abiertoPor_apellido,
                            asignadoA.usuario_nombre AS asignadoA_nombre,
                            asignadoA.usuario_apellido AS asignadoA_apellido,
                            caso_numeroVoucher,
                            caso_beneficiarioNombre,
                            caso_fechaSiniestro,
                            caso_cliente_id,
                            cliente_nombre,
                            caso_tipoAsistencia_id,
                            no_medical_cost,
                            tipoAsistencia_clasificacion_id,
                            tipoAsistencia_nombre,
                            caso_fee_id,
                            fee_nombre,
                            caso_deducible,
                            caso_paxVIP,
                            caso_legal,
                            caso_beneficiarioNacimiento,
                            caso_beneficiarioEdad,
                            caso_beneficiarioGenero_id,
                            caso_beneficiarioDocumento,
                            caso_pais_id,
                            pais_nombreEspanol,
                            caso_ciudad_id,
                            ciudad_nombre,
                            caso_direccion,
                            caso_codigoPostal,
                            caso_hotel,
                            caso_habitacion,
                            caso_producto_id,
                            product_name,
                            caso_agencia,
                            caso_quienEmitioVoucher,
                            caso_fechaSalida,
                            caso_fechaEmisionVoucher,
                            caso_vigenciaVoucherDesde,
                            caso_vigenciaVoucherHasta,
                            caso_fechaInicioSintomas,
                            caso_sintomas,
                            caso_antecedentes,
                            caso_diagnostico_id,
                            diagnostico_nombre,
                            caso_motivoVueloDemorado,
                            caso_companiaAerea,
                            caso_numeroVuelo,
                            caso_numeroPIR,
                            caso_titularPIR,
                            caso_numeroEquipaje,
                            caso_fechaPerdidaEquipaje,
                            caso_fechaRecuperacionEquipaje,
                            caso_observaciones,
                            caso_campoSupervisor,
                            caso_fechaAperturaCaso,
                            caso_ultimaModificacion,
                            email_email,
                            email_id,
                            caso_anulado,
                            caso_info_ws,
                            caso_sistemaAntiguo
                        FROM casos
                            LEFT JOIN casos_estados ON casoEstado_id = caso_casoEstado_id
                            LEFT JOIN ciudades ON ciudad_id = caso_ciudad_id
                            LEFT JOIN diagnosticos ON diagnostico_id = caso_diagnostico_id
                            LEFT JOIN usuarios AS abiertoPor ON abiertoPor.usuario_id = caso_abiertoPor_id
                            LEFT JOIN casos_agenda ON casoAgenda_caso_id = caso_id
                            LEFT JOIN usuarios AS asignadoA ON asignadoA.usuario_id = casos_agenda.casoAgenda_usuarioAsignado_id
                            LEFT JOIN tipos_asistencias ON tipoAsistencia_id = caso_tipoAsistencia_id
                            LEFT JOIN (select email_id, email_entidad_id, email_email
                    from emails
                                    where email_entidad_tipo = 2) 
                    AS emails_casos ON emails_casos.email_entidad_id = caso_id
                            LEFT JOIN clientes ON cliente_id = caso_cliente_id
                            LEFT JOIN paises ON pais_id = caso_pais_id
                            LEFT JOIN product ON product_id_interno = caso_producto_id
                            LEFT JOIN fees ON fee_id = caso_fee_id
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
            
            if ($resultado['caso_beneficiarioNacimiento'] !== NULL) {
                $caso_beneficiarioNacimiento = date("d-m-Y", strtotime($resultado['caso_beneficiarioNacimiento']));
                $resultado['caso_beneficiarioNacimiento'] = $caso_beneficiarioNacimiento;
            } else {
                $resultado['caso_beneficiarioNacimiento'] = NULL;
            }
            
            if ($resultado['caso_fechaSalida'] !== NULL) {
                $caso_fechaSalida = date("d-m-Y", strtotime($resultado['caso_fechaSalida']));
                $resultado['caso_fechaSalida'] = $caso_fechaSalida;
            } else {
                $resultado['caso_fechaSalida'] = NULL;
            }
            
            if ($resultado['caso_fechaEmisionVoucher'] !== NULL) {
                $caso_fechaEmisionVoucher = date("d-m-Y", strtotime($resultado['caso_fechaEmisionVoucher']));
                $resultado['caso_fechaEmisionVoucher'] = $caso_fechaEmisionVoucher;
            } else {
                $resultado['caso_fechaEmisionVoucher'] = NULL;
            }
            
            if ($resultado['caso_vigenciaVoucherDesde'] !== NULL) {         
                $caso_vigenciaVoucherDesde = date("d-m-Y", strtotime($resultado['caso_vigenciaVoucherDesde']));
                $resultado['caso_vigenciaVoucherDesde'] = $caso_vigenciaVoucherDesde;
            } else {
                $resultado['caso_vigenciaVoucherDesde'] = NULL;
            }
            
            if ($resultado['caso_vigenciaVoucherHasta'] !== NULL) {         
                $caso_vigenciaVoucherHasta = date("d-m-Y", strtotime($resultado['caso_vigenciaVoucherHasta']));
                $resultado['caso_vigenciaVoucherHasta'] = $caso_vigenciaVoucherHasta;
            } else {
                $resultado['caso_vigenciaVoucherHasta'] = NULL;
            }
            
            if ($resultado['caso_fechaInicioSintomas'] !== NULL) {         
                $caso_fechaInicioSintomas = date("d-m-Y", strtotime($resultado['caso_fechaInicioSintomas']));
                $resultado['caso_fechaInicioSintomas'] = $caso_fechaInicioSintomas;
            } else {
                $resultado['caso_fechaInicioSintomas'] = NULL;
            }
                    
            if ($resultado['caso_fechaPerdidaEquipaje'] !== NULL) {         
                $caso_fechaPerdidaEquipaje = date("d-m-Y", strtotime($resultado['caso_fechaPerdidaEquipaje']));
                $resultado['caso_fechaPerdidaEquipaje'] = $caso_fechaPerdidaEquipaje;
            } else {
                $resultado['caso_fechaPerdidaEquipaje'] = NULL;
            }
            
            if ($resultado['caso_fechaRecuperacionEquipaje'] !== NULL) {         
                $caso_fechaRecuperacionEquipaje = date("d-m-Y", strtotime($resultado['caso_fechaRecuperacionEquipaje']));
                $resultado['caso_fechaRecuperacionEquipaje'] = $caso_fechaRecuperacionEquipaje;
            } else {
                $resultado['caso_fechaRecuperacionEquipaje'] = NULL;
            }
            
            $caso_fechaAperturaCaso = $resultado['caso_fechaAperturaCaso'];
            $caso_fechaAperturaCaso = date("d-m-Y H:i", strtotime($caso_fechaAperturaCaso));
            $resultado['caso_fechaAperturaCaso'] = $caso_fechaAperturaCaso;
            
            if ($resultado['caso_ultimaModificacion'] !== NULL) {         
                $caso_ultimaModificacion = date("d-m-Y H:i", strtotime($resultado['caso_ultimaModificacion']));
                $resultado['caso_ultimaModificacion'] = $caso_ultimaModificacion;
            } else {
                $resultado['caso_ultimaModificacion'] = NULL;
            }
            
            return $resultado;
        } else {
            return false;
        }
    }
    
    
    // Método para mostrar la información de un caso (según caso_id)
    public static function buscarPorId_clonar($caso_id){
        DB::query("SELECT caso_id,
                        caso_pais_id,
                        caso_ciudad_id,
                        ciudad_nombre,
                        caso_direccion,
                        caso_codigoPostal,
                        caso_hotel,
                        caso_habitacion,
                        tipoTelefono_id,
                        telefono_numero,
                        email_email
                   FROM casos
                        LEFT JOIN ciudades ON ciudad_id = caso_ciudad_id
                        LEFT JOIN telefonos ON telefono_entidad_id = caso_id
                        LEFT JOIN tipos_telefonos ON tipoTelefono_id = telefono_tipoTelefono_id
                        LEFT JOIN emails ON email_entidad_id = caso_id
                   WHERE caso_id = :caso_id");
        
        DB::bind(':caso_id', "$caso_id");
        
        $resultado = DB::resultado();

        return $resultado;       
    }
    
    
    // Método para mostrar la informacion general del Caso que aparece en la cabecera de algunos Tabs en Ver Caso
    public static function buscarDatosGeneralesCaso($caso_id){
        
        DB::query("SELECT caso_id,
                        caso_numero as casoNumero,
                        caso_beneficiarioNombre as beneficiarioNombre,
                        telefonos.telefono_numero as telefonoPrincipal,
                        paises.pais_nombreEspanol as paisSiniestro,
                        caso_direccion as direccion
                    FROM casos
                        LEFT JOIN telefonos ON telefono_entidad_id = caso_id AND telefono_entidad_tipo = 2 AND telefono_principal = 1                        
                        LEFT JOIN paises ON pais_id = caso_pais_id
                    WHERE caso_id = :caso_id");
        
        DB::bind(':caso_id', "$caso_id");
        
        $resultado = DB::resultado();

        return $resultado;        
    }
    
    
    // Método para anular un Caso
    public static function anular_caso($caso_id, $sesion_usuario_id) {

        DB::conecta_t();
  
        try {
        
            DB::beginTransaction_t();  // start Transaction
            
                // UPDATE casos. Anula el caso y le cambia el estado a Anulado (id. 7)
                $caso_anulado = 1; // Anula el caso
                $caso_casoEstado_id = 7; // Le cambia el estado a Anulado (id. 7)

                DB::query_t("UPDATE casos SET
                                caso_anulado = :caso_anulado,
                                caso_casoEstado_id = :caso_casoEstado_id
                            WHERE caso_id = :caso_id");

                DB::bind(':caso_anulado', "$caso_anulado");
                DB::bind(':caso_casoEstado_id', "$caso_casoEstado_id");
                DB::bind(':caso_id', "$caso_id");

                DB::execute();


                // INSERT INTO comunicaciones. Agrega una comunicacion automatica con el detalle de la anulacion del caso
                $comunicacion_asunto_id = 7; // Pone el ASUNTO de la comunicacion en Anulado (id. 7)
                $comunicacion_casoEstado_id = 7; // Pone en la comunicacion el ESTADO del caso en Anulado (id. 7)
                $comunicacion = 'El caso fue ANULADO. No pueden generarse nuevas acciones en el mismo.';
                $fecha_ingreso = date("Y-m-d H:i:s");

                DB::query_t("INSERT INTO comunicaciones  (comunicacion_asunto_id, 
                                                        comunicacion_casoEstado_id, 
                                                        comunicacion,
                                                        comunicacion_caso_id,
                                                        comunicacion_usuario_id,
                                                        comunicacion_fechaIngreso)
                                                VALUES (:comunicacion_asunto_id,
                                                        :comunicacion_casoEstado_id,
                                                        :comunicacion,
                                                        :comunicacion_caso_id,
                                                        :comunicacion_usuario_id,
                                                        :comunicacion_fechaIngreso)");

                DB::bind(':comunicacion_asunto_id', "$comunicacion_asunto_id");
                DB::bind(':comunicacion_casoEstado_id', "$comunicacion_casoEstado_id");
                DB::bind(':comunicacion', "$comunicacion");
                DB::bind(':comunicacion_caso_id', "$caso_id");
                DB::bind(':comunicacion_usuario_id', "$sesion_usuario_id");
                DB::bind(':comunicacion_fechaIngreso', "$fecha_ingreso");

                DB::execute();

            DB::endTransaction_t(); // commit
            
            $respuesta = "200 OK";

        } catch (PDOException $e) {

            // Hace el rollback de todo 
            DB::cancelTransaction_t();  // rollBack

            // Mensaje en caso de errores en la transaccion
            $respuesta = 'Error:' . $e;
                
        }

        return $respuesta;

        
    }
    
    
    // Método para rehabilitar un Caso
    public static function rehabilitar_caso($caso_id, $sesion_usuario_id) {
        
        try {
            
            DB::conecta_t();
  
            DB::beginTransaction_t();  // start Transaction
        
            // UPDATE casos. Rehabilita el caso y le cambia al estado anterior a 'Anulado'
            $caso_anulado = 0; // Anula el caso
            $caso_casoEstado_id = 1; // Cambia al estado a 'Anulado' (id. 1)

            DB::query_t("UPDATE casos SET
                        caso_anulado = :caso_anulado,
                        caso_casoEstado_id = :caso_casoEstado_id
                       WHERE caso_id = :caso_id");

            DB::bind(':caso_anulado', "$caso_anulado");
            DB::bind(':caso_casoEstado_id', "$caso_casoEstado_id");
            DB::bind(':caso_id', "$caso_id");

            DB::execute();
        
            
            // INSERT INTO comunicaciones. Agrega una comunicacion automatica con el detalle de la reahbilitacion del caso
            $comunicacion_asunto_id = 8; // Pone el ASUNTO de la comunicacion en Rehabilitado (id. 8)
            $comunicacion_casoEstado_id = 1; // Pone en la comunicacion el ESTADO anterior a Anulado (id. 7)
            $comunicacion = 'El caso fue REHABILITADO.';
            $fecha_ingreso = date("Y-m-d H:i:s");

            DB::query_t("INSERT INTO comunicaciones  (comunicacion_asunto_id, 
                                                    comunicacion_casoEstado_id, 
                                                    comunicacion,
                                                    comunicacion_caso_id,
                                                    comunicacion_usuario_id,
                                                    comunicacion_fechaIngreso)
                                            VALUES (:comunicacion_asunto_id,
                                                    :comunicacion_casoEstado_id,
                                                    :comunicacion,
                                                    :comunicacion_caso_id,
                                                    :comunicacion_usuario_id,
                                                    :comunicacion_fechaIngreso)");

            DB::bind(':comunicacion_asunto_id', "$comunicacion_asunto_id");
            DB::bind(':comunicacion_casoEstado_id', "$comunicacion_casoEstado_id");
            DB::bind(':comunicacion', "$comunicacion");
            DB::bind(':comunicacion_caso_id', "$caso_id");
            DB::bind(':comunicacion_usuario_id', "$sesion_usuario_id");
            DB::bind(':comunicacion_fechaIngreso', "$fecha_ingreso");

            DB::execute();
        
            DB::endTransaction_t(); // commit
            
            $mensaje = "OK";
            return $mensaje;
        }
        
        // Bloque para capturar errores en la transaccion
        catch (Exception $e) {

            // Mensaje en caso de errores en la transaccion
            echo '<p>Existió un error en la transaccion con la base de datos, intente nuevamente. Error:</p>' . $e;

            // Hace el rollback de todo 
            DB::cancelTransaction_t();  // rollBack

        }
        
    }
    
    
    // Método para bucar cierta info en casos que sera utilizada al mostrar las alertas
    public static function alertas_caso($caso_id) {
        
             
        DB::query("SELECT caso_numero,
                          caso_numeroVoucher,
                          caso_fechaSiniestro,
                          caso_fechaAperturaCaso,
                          caso_fechaEmisionVoucher,
                          caso_vigenciaVoucherDesde,
                          caso_vigenciaVoucherHasta,
                          caso_deducible,
                          caso_paxVIP,
                          caso_anulado,
                          product.product_name,
                          caso_sistemaAntiguo,
                          caso_voucherFueraCobertura,
                          caso_info_ws
                    FROM casos
                        LEFT JOIN product on product.product_id_interno = casos.caso_producto_id
                    WHERE caso_id = :caso_id");
        
        DB::bind(':caso_id', "$caso_id");
        
        
        $resultado = DB::resultado();
       
        //Conversiones de la fecha de ANSI a normal
        if ($resultado['caso_vigenciaVoucherHasta'] !== NULL) {
            $caso_vigenciaHasta = date("d-m-Y", strtotime($resultado['caso_vigenciaVoucherHasta']));
            $resultado['caso_vigenciaVoucherHasta'] = $caso_vigenciaHasta;
        } else {
            $resultado['caso_vigenciaVoucherHasta'] = NULL;
        }
        
        return $resultado;
    }
    
    
    // Método para CONTAR vouchers repetidos en el sistema
    public static function buscar_vouchers_repetidos($numero_voucher) {
        
        DB::query("SELECT COUNT(casos.caso_id) as cantidad
                   FROM casos
                   WHERE casos.caso_numeroVoucher LIKE :caso_numeroVoucher");
        
        DB::bind(':caso_numeroVoucher', "%$numero_voucher%");
        
        return DB::resultado();
    }
    
    
    // Método para MOSTRAR vouchers repetidos en el sistema
    public static function mostrar_vouchers_repetidos($numero_voucher) {
        
        DB::query("SELECT casos.caso_id as casoId, 
                          casos.caso_numero casoNumero, 
                          casos.caso_fechaSiniestro as fechaSiniestro,
                          tipos_asistencias.tipoAsistencia_nombre as tipoAsistencia,
                          casos.caso_numeroVoucher as voucher,
                          casos.caso_beneficiarioNombre as beneficiario,
                          paises.pais_nombreEspanol as pais,
                          ciudades.ciudad_nombre as ciudad
                    FROM casos
                        LEFT JOIN tipos_asistencias on tipos_asistencias.tipoAsistencia_id = casos.caso_tipoAsistencia_id
                        LEFT JOIN paises on paises.pais_id = casos.caso_pais_id
                        LEFT JOIN ciudades on ciudades.ciudad_id = casos.caso_ciudad_id
                    WHERE casos.caso_numeroVoucher LIKE :caso_numeroVoucher");
        
        DB::bind(':caso_numeroVoucher', "%$numero_voucher%");
        
        return DB::resultados();
    }
    
    
    // Método para obtener el id de caso a partir del numero de caso
    public static function obtener_idCaso($caso_numero_buscar) {
        
        DB::query("SELECT casos.caso_id
                   FROM casos
                   WHERE casos.caso_numero = :caso_numero");
                        
        DB::bind(':caso_numero', $caso_numero_buscar);
        
        $resultado = DB::resultado();
        return $caso_id = $resultado['caso_id'];
    }
    
    
    // Método para la validación de la clasificacion del tipo de asistencia + el diagnostico en el caso
    // Se utiliza para mostrar o no el estado de caso 'Cerrado' en el select de comunicaciones
    public static function validaciones_caso($caso_id_n) {
        
        DB::query("SELECT tipos_asistencias.tipoAsistencia_clasificacion_id, casos.caso_diagnostico_id
                   FROM casos
                   LEFT JOIN tipos_asistencias ON tipos_asistencias.tipoAsistencia_id = casos.caso_tipoAsistencia_id
                   WHERE casos.caso_id = :caso_id");
                        
        DB::bind(':caso_id', $caso_id_n);
        
        $resultado = DB::resultado();
        
        $tipoAsistencia_clasificacion   = $resultado['tipoAsistencia_clasificacion_id'];
        $caso_diagnostico               = $resultado['caso_diagnostico_id'];
        
        
        if ($tipoAsistencia_clasificacion == 1 && $caso_diagnostico == 0) {
            $validacion = 'TRUE';
        } else {
            $validacion = 'FALSE';
        }
        
        return $validacion;
    }

    // Método para consulta la vigencia del voucher al momento de la carga del caso
    public static function consultar_voucher_vigencia($caso_id) {
        
        DB::query("SELECT caso_voucherFueraCobertura
                   FROM casos
                   WHERE casos.caso_id = :caso_id");
                        
        DB::bind(':caso_id', $caso_id);
        
        $resultado = DB::resultado();
        return $resultado['caso_voucherFueraCobertura'];
    }

    // Método para consultar la vigencia del voucher al momento de la carga del caso
    public static function consultar_caso_enVigencia($caso_id) {
        
        DB::query("SELECT caso_voucherFueraCobertura
                   FROM casos
                   WHERE casos.caso_id = :caso_id");
                        
        DB::bind(':caso_id', $caso_id);
        
        $resultado = DB::resultado();
        return $resultado['caso_voucherFueraCobertura'];
    }

    // Método para consultar la vigencia del voucher, respecto a la fecha actual
    public static function consultar_vigencia_actual($caso_id) {

        // Consulta SQL
        DB::query("SELECT casos.caso_vigenciaVoucherDesde AS vigenciaDesde, 
                            casos.caso_vigenciaVoucherHasta AS vigenciaHasta
                    FROM casos 
                    WHERE caso_id = :caso_id");

        DB::bind(':caso_id', $caso_id);

        $resultado = DB::resultado();

        // Variables
        $fechaActual    = date("Y-m-d");
        $vigenciaDesde  = $resultado['vigenciaDesde'];
        $vigenciaHasta  = $resultado['vigenciaHasta'];

        // Comprueba si la Fecha de Siniestro es MENOR a la Vigencia Desde o si es MAYOR a la Vigencia Hasta
        // En caso que la condicion se cumpla, ese voucher no se encuentra en cobertura
        if(($fechaActual < $vigenciaDesde) || ($fechaActual > $vigenciaHasta)) {
            $outPut = 1;
        } else {
            $outPut = 0;
        }

        // Return
        return $outPut;
    }
    
    // Método para buscar info del caso en el modulo de facturacion old
    public static function mostrar_info_para_facturacion($caso_numero) {

        DB::query("SELECT caso_id, 
                            casos.caso_beneficiarioNombre AS beneficiario,  
                            casos.caso_fechaSiniestro AS casoFecha,
                            product.product_name AS producto,
                            casos.caso_numeroVoucher AS casoVoucher,
                            casos.caso_agencia AS casoAgencia
                    FROM casos
                        LEFT JOIN product ON product.product_id_interno = casos.caso_producto_id
                    WHERE casos.caso_numero = :caso_numero");

        DB::bind(':caso_numero', $caso_numero);

        return DB::resultado();
    }

    // Método para consultar si el caso se cargo MANUAL o con info del WS
    public static function caso_info_ws($caso_id) {
       
        DB::query("SELECT casos.caso_info_ws AS info_ws
                    FROM casos
                    WHERE casos.caso_id = :caso_id");

        DB::bind(':caso_id', $caso_id);

        $resultado = DB::resultado();
        return $resultado['info_ws'];
    }

    // Método para buscar el cliente_id del caso
    public static function buscar_cliente($caso_id) {
        
        DB::query("SELECT caso_cliente_id
                   FROM casos
                   WHERE caso_id = :caso_id");
        
        DB::bind(':caso_id', "$caso_id");
        
        $resultado = DB::resultado();

        return $resultado['caso_cliente_id'];
    }
}
