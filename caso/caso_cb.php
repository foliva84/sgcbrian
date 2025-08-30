<?php
require '../seguridad/seguridad.php';
include_once '../includes/herramientas.php';


// Toma las variables del formulario de alta
$caso_numeroVoucher_n = isset($_POST["caso_numeroVoucher_n"])?$_POST["caso_numeroVoucher_n"]:'';
$caso_beneficiarioNombre_n = isset($_POST["caso_beneficiarioNombre_n"])?$_POST["caso_beneficiarioNombre_n"]:'';
$caso_fechaSiniestro_n = isset($_POST["caso_fechaSiniestro_n"])?$_POST["caso_fechaSiniestro_n"]:'';
$caso_cliente_id_n = isset($_POST["caso_cliente_id_n"])?$_POST["caso_cliente_id_n"]:null;
$caso_tipoAsistencia_id_n = isset($_POST["caso_tipoAsistencia_id_n"])?$_POST["caso_tipoAsistencia_id_n"]:null;
$no_medical_cost_n = isset($_POST["no_medical_cost_n"])?$_POST["no_medical_cost_n"]:0;
$caso_tipoAsistencia_clasificacion_id_n = isset($_POST["caso_tipoAsistencia_clasificacion_id_n"])?$_POST["caso_tipoAsistencia_clasificacion_id_n"]:null;
$caso_fee_id_n = isset($_POST["caso_fee_id_n"])?$_POST["caso_fee_id_n"]:null;
$caso_deducible_n = isset($_POST["caso_deducible_n"])?$_POST["caso_deducible_n"]:'';
$caso_paxVIP_n = isset($_POST["caso_paxVIP_n"])?$_POST["caso_paxVIP_n"]:0;
$caso_legal_n = isset($_POST["caso_legal_n"])?$_POST["caso_legal_n"]:0;
$caso_beneficiarioNacimiento_n = isset($_POST["caso_beneficiarioNacimiento_n"])?$_POST["caso_beneficiarioNacimiento_n"]:'';
$caso_beneficiarioEdad_n = isset($_POST["caso_beneficiarioEdad_n"])?$_POST["caso_beneficiarioEdad_n"]:'';
$caso_beneficiarioGenero_id_n = isset($_POST["caso_beneficiarioGenero_id_n"])?$_POST["caso_beneficiarioGenero_id_n"]:null;
$caso_beneficiarioDocumento_n = isset($_POST["caso_beneficiarioDocumento_n"])?$_POST["caso_beneficiarioDocumento_n"]:'';
$caso_pais_id_n = isset($_POST["caso_pais_id_n"])?$_POST["caso_pais_id_n"]:null;
$caso_ciudad_id_n_2 = isset($_POST["caso_ciudad_id_n_2"])?$_POST["caso_ciudad_id_n_2"]:null;
$caso_direccion_n = isset($_POST["caso_direccion_n"])?$_POST["caso_direccion_n"]:'';
$caso_codigoPostal_n = isset($_POST["caso_codigoPostal_n"])?$_POST["caso_codigoPostal_n"]:'';
$caso_hotel_n = isset($_POST["caso_hotel_n"])?$_POST["caso_hotel_n"]:'';
$caso_habitacion_n = isset($_POST["caso_habitacion_n"])?$_POST["caso_habitacion_n"]:'';
$caso_producto_id_n = isset($_POST["caso_producto_id_n"])?$_POST["caso_producto_id_n"]:null;
$caso_agencia_n = isset($_POST["caso_agencia_n"])?$_POST["caso_agencia_n"]:'';
$caso_quienEmitioVoucher_n = isset($_POST["caso_quienEmitioVoucher_n"])?$_POST["caso_quienEmitioVoucher_n"]:'';
$caso_fechaSalida_n = isset($_POST["caso_fechaSalida_n"])?$_POST["caso_fechaSalida_n"]:'';
$caso_fechaEmisionVoucher_n = isset($_POST["caso_fechaEmisionVoucher_n"])?$_POST["caso_fechaEmisionVoucher_n"]:'';
$caso_vigenciaVoucherDesde_n = isset($_POST["caso_vigenciaVoucherDesde_n"])?$_POST["caso_vigenciaVoucherDesde_n"]:'';
$caso_vigenciaVoucherHasta_n = isset($_POST["caso_vigenciaVoucherHasta_n"])?$_POST["caso_vigenciaVoucherHasta_n"]:'';
$caso_fechaInicioSintomas_n = isset($_POST["caso_fechaInicioSintomas_n"])?$_POST["caso_fechaInicioSintomas_n"]:'';
$caso_sintomas_n = isset($_POST["caso_sintomas_n"])?$_POST["caso_sintomas_n"]:'';
$caso_antecedentes_n = isset($_POST["caso_antecedentes_n"])?$_POST["caso_antecedentes_n"]:'';
$caso_diagnostico_id_n_2 = isset($_POST["caso_diagnostico_id_n_2"])?$_POST["caso_diagnostico_id_n_2"]:null;
$caso_motivoVueloDemorado_n = isset($_POST["caso_motivoVueloDemorado_n"])?$_POST["caso_motivoVueloDemorado_n"]:'';
$caso_companiaAerea_n = isset($_POST["caso_companiaAerea_n"])?$_POST["caso_companiaAerea_n"]:'';
$caso_numeroVuelo_n = isset($_POST["caso_numeroVuelo_n"])?$_POST["caso_numeroVuelo_n"]:'';
$caso_numeroPIR_n = isset($_POST["caso_numeroPIR_n"])?$_POST["caso_numeroPIR_n"]:'';
$caso_titularPIR_n = isset($_POST["caso_titularPIR_n"])?$_POST["caso_titularPIR_n"]:'';
$caso_numeroEquipaje_n = isset($_POST["caso_numeroEquipaje_n"])?$_POST["caso_numeroEquipaje_n"]:'';
$caso_fechaPerdidaEquipaje_n = isset($_POST["caso_fechaPerdidaEquipaje_n"])?$_POST["caso_fechaPerdidaEquipaje_n"]:'';
$caso_fechaRecuperacionEquipaje_n = isset($_POST["caso_fechaRecuperacionEquipaje_n"])?$_POST["caso_fechaRecuperacionEquipaje_n"]:'';
$caso_observaciones_n = isset($_POST["caso_observaciones_n"])?$_POST["caso_observaciones_n"]:'';
$caso_campoSupervisor_n = isset($_POST["caso_campoSupervisor_n"])?$_POST["caso_campoSupervisor_n"]:'';
// Variable para identificar si la informacion que se carga en el caso viene de un WS
$caso_info_ws_n = isset($_POST["caso_info_ws_n"])?$_POST["caso_info_ws_n"]:'';
// Formulario telefonos + e-mail (Alta)
$caso_telefonoTipo_id_n = isset($_POST["caso_telefonoTipo_id_n"])?$_POST["caso_telefonoTipo_id_n"]:null;
$telefono_numero_n = isset($_POST["telefono_numero_n"])?$_POST["telefono_numero_n"]:'';
$caso_telefonoTipo_id_n_2 = isset($_POST["caso_telefonoTipo_id_n_2"])?$_POST["caso_telefonoTipo_id_n_2"]:null;
$telefono_numero_n_2 = isset($_POST["telefono_numero_n_2"])?$_POST["telefono_numero_n_2"]:'';
$email_email_n = isset($_POST["email_email_n"])?$_POST["email_email_n"]:'';

// Toma las variables del formulario de vista
$caso_id = isset($_POST["caso_id"])?$_POST["caso_id"]:'';
$asistencia_sin_costo = isset($_POST["asistencia_sin_costo"])?$_POST["asistencia_sin_costo"]:'';
$caso_numero = isset($_POST["caso_numero"])?$_POST["caso_numero"]:'';
$caso_numeroVoucher = isset($_POST["caso_numeroVoucher"])?$_POST["caso_numeroVoucher"]:'';
$caso_beneficiarioNombre = isset($_POST["caso_beneficiarioNombre"])?$_POST["caso_beneficiarioNombre"]:'';
$caso_fechaSiniestro = isset($_POST["caso_fechaSiniestro"])?$_POST["caso_fechaSiniestro"]:'';
$caso_cliente_id = isset($_POST["caso_cliente_id"])?$_POST["caso_cliente_id"]:'';
$caso_tipoAsistencia_id = isset($_POST["caso_tipoAsistencia_id"])?$_POST["caso_tipoAsistencia_id"]:'';
$no_medical_cost = isset($_POST["no_medical_cost"])?$_POST["no_medical_cost"]:0;
$caso_fee_id = isset($_POST["caso_fee_id"])?$_POST["caso_fee_id"]:'';
$caso_deducible = isset($_POST["caso_deducible"])?$_POST["caso_deducible"]:'';
$caso_paxVIP = isset($_POST["caso_paxVIP"])?$_POST["caso_paxVIP"]:'';
$caso_legal = isset($_POST["caso_legal"])?$_POST["caso_legal"]:'';
$caso_beneficiarioNacimiento = isset($_POST["caso_beneficiarioNacimiento"])?$_POST["caso_beneficiarioNacimiento"]:'';
$caso_beneficiarioEdad = isset($_POST["caso_beneficiarioEdad"])?$_POST["caso_beneficiarioEdad"]:'';
$caso_beneficiarioGenero_id = isset($_POST["caso_beneficiarioGenero_id"])?$_POST["caso_beneficiarioGenero_id"]:null;
$caso_beneficiarioDocumento = isset($_POST["caso_beneficiarioDocumento"])?$_POST["caso_beneficiarioDocumento"]:'';
$caso_pais_id = isset($_POST["caso_pais_id"])?$_POST["caso_pais_id"]:'';
$caso_ciudad_id_2 = isset($_POST["caso_ciudad_id_2"])?$_POST["caso_ciudad_id_2"]:'';
$caso_direccion = isset($_POST["caso_direccion"])?$_POST["caso_direccion"]:'';
$caso_codigoPostal = isset($_POST["caso_codigoPostal"])?$_POST["caso_codigoPostal"]:'';
$caso_hotel = isset($_POST["caso_hotel"])?$_POST["caso_hotel"]:'';
$caso_habitacion = isset($_POST["caso_habitacion"])?$_POST["caso_habitacion"]:'';
$caso_producto_id = isset($_POST["caso_producto_id"])?$_POST["caso_producto_id"]:'';
$caso_agencia = isset($_POST["caso_agencia"])?$_POST["caso_agencia"]:'';
$caso_quienEmitioVoucher = isset($_POST["caso_quienEmitioVoucher"])?$_POST["caso_quienEmitioVoucher"]:'';
$caso_fechaSalida = isset($_POST["caso_fechaSalida"])?$_POST["caso_fechaSalida"]:'';
$caso_fechaEmisionVoucher = isset($_POST["caso_fechaEmisionVoucher"])?$_POST["caso_fechaEmisionVoucher"]:'';
$caso_vigenciaVoucherDesde = isset($_POST["caso_vigenciaVoucherDesde"])?$_POST["caso_vigenciaVoucherDesde"]:'';
$caso_vigenciaVoucherHasta = isset($_POST["caso_vigenciaVoucherHasta"])?$_POST["caso_vigenciaVoucherHasta"]:'';
$caso_fechaInicioSintomas = isset($_POST["caso_fechaInicioSintomas"])?$_POST["caso_fechaInicioSintomas"]:'';
$caso_sintomas = isset($_POST["caso_sintomas"])?$_POST["caso_sintomas"]:'';
$caso_antecedentes = isset($_POST["caso_antecedentes"])?$_POST["caso_antecedentes"]:'';
$caso_diagnostico_id_2 = isset($_POST["caso_diagnostico_id_2"])?$_POST["caso_diagnostico_id_2"]:'';
$caso_motivoVueloDemorado = isset($_POST["caso_motivoVueloDemorado"])?$_POST["caso_motivoVueloDemorado"]:'';
$caso_companiaAerea = isset($_POST["caso_companiaAerea"])?$_POST["caso_companiaAerea"]:'';
$caso_numeroVuelo = isset($_POST["caso_numeroVuelo"])?$_POST["caso_numeroVuelo"]:'';
$caso_numeroPIR = isset($_POST["caso_numeroPIR"])?$_POST["caso_numeroPIR"]:'';
$caso_titularPIR = isset($_POST["caso_titularPIR"])?$_POST["caso_titularPIR"]:'';
$caso_numeroEquipaje = isset($_POST["caso_numeroEquipaje"])?$_POST["caso_numeroEquipaje"]:'';
$caso_fechaPerdidaEquipaje = isset($_POST["caso_fechaPerdidaEquipaje"])?$_POST["caso_fechaPerdidaEquipaje"]:'';
$caso_fechaRecuperacionEquipaje = isset($_POST["caso_fechaRecuperacionEquipaje"])?$_POST["caso_fechaRecuperacionEquipaje"]:'';
$caso_observaciones = isset($_POST["caso_observaciones"])?$_POST["caso_observaciones"]:'';
$caso_campoSupervisor = isset($_POST["caso_campoSupervisor"])?$_POST["caso_campoSupervisor"]:'';
$caso_ultimaModificacion = isset($_POST["caso_ultimaModificacion"])?$_POST["caso_ultimaModificacion"]:'';
// Variable para identificar si la informacion que se carga en el caso viene de un WS
$caso_info_ws = isset($_POST["caso_info_ws"])?$_POST["caso_info_ws"]:'';

// Formulario telefonos + e-mail (Modificacion)
$telefono_id = isset($_POST["telefono_id"])?$_POST["telefono_id"]:'';
$telefono_id_e = isset($_POST["telefono_id_e"])?$_POST["telefono_id_e"]:'';
$telefono_id_m = isset($_POST["telefono_id_m"])?$_POST["telefono_id_m"]:'';
$telefono_id_b = isset($_POST["telefono_id_b"])?$_POST["telefono_id_b"]:'';
$caso_telefonoTipo_id = isset($_POST["caso_telefonoTipo_id"])?$_POST["caso_telefonoTipo_id"]:'';
$telefono_numero = isset($_POST["telefono_numero"])?$_POST["telefono_numero"]:'';
$telefono_principal = isset($_POST["telefono_principal"])?$_POST["telefono_principal"]:'';
$email_id = isset($_POST["email_id"])?$_POST["email_id"]:'';
$email_email = isset($_POST["email_email"])?$_POST["email_email"]:'';

// Toma el id de cliente para el autocomplete del producto
$cliente_id = isset($_POST["cliente_id"])?$_POST["cliente_id"]:'';
// Toma el id de país y ciudad para el autocomplete de ciudad
$pais_id = isset($_POST["pais_id"])?$_POST["pais_id"]:'';
// Toma texto introducido en ciudad para el autocomplete
$ciudad = isset($_POST["ciudad"])?$_POST["ciudad"]:'';
// Toma el tipo de asistencia para buscar su clasificacion
$tipoAsistencia = isset($_POST["tipoAsistencia"])?$_POST["tipoAsistencia"]:'';
// Toma la fecha de nacimiento para calcular la edad
$fechaNacimiento = isset($_POST["fechaNacimiento"])?$_POST["fechaNacimiento"]:'';
// Toma texto introducido en diagnostico para el autocomplete
$diagnostico = isset($_POST["diagnostico"])?$_POST["diagnostico"]:'';
// Toma el numero de voucher
$numero_voucher = isset($_POST["numero_voucher"])?$_POST["numero_voucher"]:'';

// Toma las variables del BUSCADOR
$caso_numero_desde_b            = isset($_POST["caso_numero_desde_b"])?$_POST["caso_numero_desde_b"]:'';
$caso_numero_hasta_b            = isset($_POST["caso_numero_hasta_b"])?$_POST["caso_numero_hasta_b"]:'';
$caso_estado_id_b               = isset($_POST["caso_estado_id_b"])?$_POST["caso_estado_id_b"]:'';
$caso_tipoAsistencia_id_b       = isset($_POST["caso_tipoAsistencia_id_b"])?$_POST["caso_tipoAsistencia_id_b"]:'';
$caso_fechaSiniestro_desde_b    = isset($_POST["caso_fechaSiniestro_desde_b"])?$_POST["caso_fechaSiniestro_desde_b"]:'';
$caso_fechaSiniestro_hasta_b    = isset($_POST["caso_fechaSiniestro_hasta_b"])?$_POST["caso_fechaSiniestro_hasta_b"]:'';
$caso_beneficiario_b            = isset($_POST["caso_beneficiario_b"])?$_POST["caso_beneficiario_b"]:'';
$caso_voucher_b                 = isset($_POST["caso_voucher_b"])?$_POST["caso_voucher_b"]:'';
$caso_agencia_b                 = isset($_POST["caso_agencia_b"])?$_POST["caso_agencia_b"]:'';

// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';


switch($opcion){
      
    // Acciones de los formularios
        case 'formulario_alta':
            formulario_alta($caso_numeroVoucher_n, 
                            $caso_beneficiarioNombre_n, 
                            $caso_fechaSiniestro_n, 
                            $caso_cliente_id_n, 
                            $caso_tipoAsistencia_id_n, 
                            $no_medical_cost_n,
                            $caso_fee_id_n, 
                            $caso_deducible_n, 
                            $caso_paxVIP_n,
                            $caso_legal_n,
                            $caso_telefonoTipo_id_n,
                            $telefono_numero_n,
                            $caso_telefonoTipo_id_n_2,
                            $telefono_numero_n_2,
                            $email_email_n,
                            $caso_beneficiarioNacimiento_n,
                            $caso_beneficiarioEdad_n,
                            $caso_beneficiarioGenero_id_n, 
                            $caso_beneficiarioDocumento_n, 
                            $caso_pais_id_n, 
                            $caso_ciudad_id_n_2, 
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
                            $caso_diagnostico_id_n_2, 
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
                            $caso_info_ws_n);
            break;
        
        case 'formulario_modificacion':
            formulario_modificacion($caso_id, 
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
                                    $caso_ciudad_id_2, 
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
                                    $caso_diagnostico_id_2,
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
                                    $caso_info_ws,
                                    $email_id,
                                    $email_email);
            break;
        
        case 'formulario_lectura':
            formulario_lectura($caso_id);
            break;
        
        case 'formulario_clonar':
            formulario_clonar($caso_id);
            break;
    
    // Case telefono
        case 'listarTipoTelefonos_caso':
            listarTipoTelefonos_caso();
            break;
        
        case 'listarTipoTelefonos_modificacion':
            listarTipoTelefonos_modificacion($telefono_id_e);
            break;
        
        case 'telefono_guardar':
            telefono_guardar($caso_telefonoTipo_id, $telefono_numero, $telefono_principal, $caso_id);
            break;
        
        case 'telefono_modificar':
            telefono_modificar($caso_telefonoTipo_id, $telefono_numero, $telefono_principal, $caso_id, $telefono_id_m);
            break;
        
        case 'telefono_borrar':
            telefono_borrar($telefono_id_b);
            break;
        
        case 'telefono_editar':
            telefono_editar($telefono_id_e);
            break;  
  
    // Case grillas
        case 'grilla_listar':
            grilla_listar($caso_numero_desde_b,
                        $caso_numero_hasta_b,
                        $caso_estado_id_b,
                        $caso_tipoAsistencia_id_b,
                        $caso_fechaSiniestro_desde_b,
                        $caso_fechaSiniestro_hasta_b,
                        $caso_beneficiario_b,
                        $caso_voucher_b,
                        $caso_agencia_b);
            break;
        
        case 'grilla_listar_contar':
            grilla_listar_contar($caso_numero_desde_b,
                                $caso_numero_hasta_b,
                                $caso_estado_id_b,
                                $caso_tipoAsistencia_id_b,
                                $caso_fechaSiniestro_desde_b,
                                $caso_fechaSiniestro_hasta_b,
                                $caso_beneficiario_b,
                                $caso_voucher_b,
                                $caso_agencia_b);
            break;

        case 'grilla_telefonos':
            grilla_telefonos($caso_id);
            break;
        
        case 'grilla_telefonos_v':
            grilla_telefonos_v($caso_id);
            break;

        case 'listar_ultimos_n':
            listar_ultimos_n();
            break;
    
    // Acciones auxiliares en el formulario
        case 'buscar_caso_id':
            buscar_caso_id($caso_numero);
            break;
    
        case 'select_ciudades':
            select_ciudades($ciudad, $pais_id);
            break;
        
        case 'select_diagnosticos':
            select_diagnosticos($diagnostico);
            break;   
        
        case 'tipoAsistencia_clasificacion':
            tipoAsistencia_clasificacion($tipoAsistencia);
            break; 
        
        case 'anular_casos':
            anular_casos($caso_id, $sesion_usuario_id);
            break; 
        
        case 'rehabilitar_casos':
            rehabilitar_casos($caso_id, $sesion_usuario_id);
            break;
        
        case 'alertas_caso':
            alertas_caso($caso_id);
            break;
        
        case 'buscar_vouchers_repetidos':
            buscar_vouchers_repetidos($numero_voucher);
            break;
        
        case 'mostrar_vouchers_repetidos':
            mostrar_vouchers_repetidos($numero_voucher);
            break;

        case 'valida_servicios_reintegros':
            valida_servicios_reintegros($caso_id);
            break;

        case 'valida_servicios_costo_asistencia':
            valida_servicios_costo_asistencia($caso_id, $asistencia_sin_costo);
            break;

    // Select - Formulario Alta Casos
        case 'formulario_alta_clientes':
            formulario_alta_clientes();
            break;

        case 'formulario_alta_tiposAsistencias':
            formulario_alta_tiposAsistencias();
            break;

        case 'formulario_alta_fees':
            formulario_alta_fees();
            break;

        case 'formulario_alta_paises':
            formulario_alta_paises();
            break;

        case 'productosListar_altaCasos':
            productosListar_altaCasos($cliente_id);
            break;
        

    // Input - Formulario Modificar Casos
        case 'estadoCasoMostrar_modificacionCasos':
            estadoCasoMostrar_modificacionCasos($caso_id);
            break;
        
        case 'abrioCasoMostrar_modificacionCasos':
            abrioCasoMostrar_modificacionCasos($caso_id);
            break;
        
        case 'casoAsignadoMostrar_modificacionCasos':
            casoAsignadoMostrar_modificacionCasos($caso_id);
            break;
        
    // Select - Formulario Modificar Casos
        case 'clientesListar_modificacionCasos':
            clientesListar_modificacionCasos($caso_id);
            break;

        case 'tiposAsistenciaListar_modificacionCasos':
            tiposAsistenciaListar_modificacionCasos($caso_id);
            break;

        case 'feesListar_modificacionCasos':
            feesListar_modificacionCasos($caso_id);
            break; 

        case 'paisesListar_modificacionCasos':
            paisesListar_modificacionCasos($caso_id);
            break;

        case 'productosListar_modificacionCasos':
            productosListar_modificacionCasos($caso_id, $cliente_id);
            break;
        
    // Select - Buscador 
        case 'tipoAsistenciaListar_buscadorCasos':
            tipoAsistenciaListar_buscadorCasos();
            break;
        
        case 'casoEstadoslistar_buscadorCasos':
            casoEstadoslistar_buscadorCasos();
            break;        
    
    default:
       echo("Está mal seleccionada la funcion");        
}


// Funciones de Formulario
function formulario_alta($caso_numeroVoucher_n, 
                         $caso_beneficiarioNombre_n, 
                         $caso_fechaSiniestro_n, 
                         $caso_cliente_id_n, 
                         $caso_tipoAsistencia_id_n,
                         $no_medical_cost_n,
                         $caso_fee_id_n, 
                         $caso_deducible_n, 
                         $caso_paxVIP_n,
                         $caso_legal_n,
                         $caso_telefonoTipo_id_n,
                         $telefono_numero_n,
                         $caso_telefonoTipo_id_n_2,
                         $telefono_numero_n_2,   
                         $email_email_n,
                         $caso_beneficiarioNacimiento_n,
                         $caso_beneficiarioEdad_n,
                         $caso_beneficiarioGenero_id_n, 
                         $caso_beneficiarioDocumento_n, 
                         $caso_pais_id_n, 
                         $caso_ciudad_id_n_2, 
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
                         $caso_diagnostico_id_n_2,
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
                        
     $caso_id = Caso::insertar($caso_numeroVoucher_n, 
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
                                $caso_ciudad_id_n_2, 
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
                                $caso_diagnostico_id_n_2,
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
                                $caso_info_ws_n);

    if ( is_numeric($caso_id) AND $caso_id > 0 ) { 
        // Insert de telefono - El if comprueba que se haya ingresado un telefono
        if(!empty($caso_telefonoTipo_id_n) and !empty($telefono_numero_n)){
            $telefono_entidad_id_n = $caso_id;
            $telefono_entidad_tipo_n = Caso::ENTIDAD;
            $telefono_principal_n = 1;
            Telefono::insertar($caso_telefonoTipo_id_n, $telefono_numero_n, $telefono_principal_n, $telefono_entidad_id_n, $telefono_entidad_tipo_n);
        }
        if(!empty($caso_telefonoTipo_id_n_2) and !empty($telefono_numero_n_2)){
            $telefono_entidad_id_n = $caso_id;
            $telefono_entidad_tipo_n = Caso::ENTIDAD;
            $telefono_principal_n = 0;
            Telefono::insertar($caso_telefonoTipo_id_n_2, $telefono_numero_n_2, $telefono_principal_n, $telefono_entidad_id_n, $telefono_entidad_tipo_n);
        }
        
        // Insert de e-mail - El if comprueba que se haya ingresado un e-mail
        if(!empty($email_email_n)){
            $email_entidad_id_n = $caso_id;
            $email_entidad_tipo_n = Caso::ENTIDAD;
            $email_principal_n = 1;
            Email::insertar_enCaso($email_email_n, $email_principal_n, $email_entidad_id_n, $email_entidad_tipo_n);   
        }
    }
    echo json_encode($caso_id);
}

function formulario_modificacion($caso_id, 
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
                                 $caso_ciudad_id_2, 
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
                                 $caso_diagnostico_id_2, 
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
                                 $caso_info_ws,
                                 $email_id,
                                 $email_email){
    

    $resultado = Caso::actualizar($caso_id, 
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
                     $caso_ciudad_id_2, 
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
                     $caso_diagnostico_id_2,
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
                     $caso_info_ws);
    
    
    // Update de e-mail
    if(!empty($email_id)){
        Email::modificar_enCaso($email_email, $email_id);
    } else {    
        $email_entidad_id = $caso_id;
        $email_entidad_tipo = Caso::ENTIDAD;
        $email_principal = 1;
        Email::insertar_enCaso($email_email, $email_principal, $email_entidad_id, $email_entidad_tipo);   
    }


    echo json_encode($resultado);
}





function formulario_lectura($caso_id){
    
    $caso = Caso::buscarPorId($caso_id);
    
    echo json_encode($caso);
}

function formulario_clonar($caso_id){
    
    $caso = Caso::buscarPorId_clonar($caso_id);
    
    echo json_encode($caso);
}


// Funciones auxiliares de formulario
    
    function buscar_caso_id($caso_numero){

        $caso_id = Caso::buscar_caso_id($caso_numero);

        echo json_encode($caso_id);    
    }

    // Funciones para llenar los Select
        // Funciones AutoComplete
        function select_ciudades($ciudad, $pais_id){

            $ciudades = Ciudad::buscar_select($ciudad, $pais_id);

            $data = array();
            foreach ($ciudades as $ciudad) {
                $name = $ciudad['ciudad_nombre'] . '|' . $ciudad['ciudad_id'];
                array_push($data, $name);	
            }	

            echo json_encode($data);    
        }
        
        function select_diagnosticos($diagnostico){

            $diagnosticos = Diagnostico::buscar_select($diagnostico);

            $data = array();
            foreach ($diagnosticos as $diagnostico) {
                $name = $diagnostico['diagnostico_nombre'] . '|' . $diagnostico['diagnostico_id'];
                array_push($data, $name);	
            }	

            echo json_encode($data);    
        }
        
        function tipoAsistencia_clasificacion($tipoAsistencia) {
            
            $tipoAsistencia_clasificacion_id = TipoAsistencia::buscarClasificacion($tipoAsistencia);
    
            echo json_encode($tipoAsistencia_clasificacion_id);    
        }

        function anular_casos($caso_id, $sesion_usuario_id) {
            
            $resultado = Caso::anular_caso($caso_id, $sesion_usuario_id);
    
            echo json_encode($resultado);    
        }
        
        function rehabilitar_casos($caso_id, $sesion_usuario_id) {
            
            $resultado = Caso::rehabilitar_caso($caso_id, $sesion_usuario_id);
    
            echo json_encode($resultado);    
        }
        
        function alertas_caso($caso_id) {
            
            $resultado = Caso::alertas_caso($caso_id);
    
            echo json_encode($resultado);    
        }
        
        function buscar_vouchers_repetidos($numero_voucher) {
            
            $resultado = Caso::buscar_vouchers_repetidos($numero_voucher);
    
            echo json_encode($resultado);    
        }
        
        // Select - Formularios Alta Casos
        function formulario_alta_clientes(){

            $clientes = Cliente::formulario_alta_clientes();

            echo json_encode($clientes);   
        }

        function formulario_alta_tiposAsistencias(){

            $tiposAsistencias = TipoAsistencia::listar();

            echo json_encode($tiposAsistencias);   
        }

        function formulario_alta_fees(){

            $fees = Fee::listar();

            echo json_encode($fees);   
        }

        function formulario_alta_paises(){

            $paises = Pais::formulario_alta_paises();

            echo json_encode($paises);
        }
        
        function productosListar_altaCasos($cliente_id){

            $productos = Product::listar_segunCliente($cliente_id);
            
            echo json_encode($productos);   
        }
        
        function listarTipoTelefonos_caso(){

            $tiposTelefonos = Telefono::listarTipoTelefonos(2);

            echo json_encode($tiposTelefonos);
        }
        
        // Input - Formularios Modificacion Casos
        function estadoCasoMostrar_modificacionCasos($caso_id){

            $estadoCaso = EstadoCaso::mostrar($caso_id);

            echo json_encode($estadoCaso);
        }
        
        function abrioCasoMostrar_modificacionCasos($caso_id){

            $usuario = Usuario::mostrarAbiertoPor($caso_id);

            echo json_encode($usuario);
        }
        
        function casoAsignadoMostrar_modificacionCasos($caso_id){

            $usuario = Usuario::mostrarAsignadoA($caso_id);

            echo json_encode($usuario);
        }

        // Select - Formularios Modificacion Casos
        function clientesListar_modificacionCasos($caso_id){

            $clientes = Cliente::listar_modificacion_casos($caso_id);

            echo json_encode($clientes);
        }

        function tiposAsistenciaListar_modificacionCasos($caso_id){

            $tiposAsistencias = TipoAsistencia::listar_modificacion_casos($caso_id);

            echo json_encode($tiposAsistencias);   
        }

        function feesListar_modificacionCasos($caso_id){

            $fees = Fee::listar_modificacion_casos($caso_id);

            echo json_encode($fees);   
        } 

        function paisesListar_modificacionCasos($caso_id){

            $paises = Pais::listar_modificacion_casos($caso_id);

            echo json_encode($paises);   
        }

        function productosListar_modificacionCasos($caso_id, $cliente_id){

            $productos = Product::listar_modificacion_casos($caso_id, $cliente_id);

            echo json_encode($productos);   
        }
        
        function listarTipoTelefonos_modificacion($telefono_id_e){
            
            $tiposTelefonos = Telefono::listarTipoTelefonos_modificacion($telefono_id_e, 2);

            echo json_encode($tiposTelefonos);
        }
        
        function tipoAsistenciaListar_buscadorCasos(){

            $tiposAsistencias = TipoAsistencia::listar();

            echo json_encode($tiposAsistencias);   
        }

        function casoEstadoslistar_buscadorCasos(){

            $casoEstados = EstadoCaso::listar_casoEstados();

            echo json_encode($casoEstados);      
        }
                
        
// Funciones de Grilla
    function grilla_listar($caso_numero_desde_b,
                            $caso_numero_hasta_b,
                            $caso_estado_id_b,
                            $caso_tipoAsistencia_id_b,
                            $caso_fechaSiniestro_desde_b,
                            $caso_fechaSiniestro_hasta_b,
                            $caso_beneficiario_b,
                            $caso_voucher_b,
                            $caso_agencia_b){
        
        $casos = Caso::listar($caso_numero_desde_b,
                                $caso_numero_hasta_b,
                                $caso_estado_id_b,
                                $caso_tipoAsistencia_id_b,
                                $caso_fechaSiniestro_desde_b,
                                $caso_fechaSiniestro_hasta_b,
                                $caso_beneficiario_b,
                                $caso_voucher_b,
                                $caso_agencia_b);

        $grilla = "<div class='row'>";
        $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
        $grilla .=  "<div class='card-box table-responsive'>";
        $grilla .=      "<h4 class='m-t-0 header-title'><b>Tabla de Casos</b></h4>";
        $grilla .=      "<table id='dt_caso' class='table table-hover table-striped m-0 table-responsive'>";
        $grilla .=          "<thead>";
        $grilla .=              "<tr>";
        $grilla .=                  "<th>Caso</th>";
        $grilla .=                  "<th>Estado</th>";
        $grilla .=                  "<th>Fecha</th>";
        $grilla .=                  "<th>Beneficiario</th>";
        $grilla .=                  "<th>VIP</th>";
        $grilla .=                  "<th>Voucher</th>";
        $grilla .=                  "<th>País</th>";
        $grilla .=                  "<th>Ciudad</th>";
        //$grilla .=                  "<th>Asignado</th>";
        $grilla .=                  "<th>Acciones</th>";
        $grilla .=              "</tr>";
        $grilla .=          "</thead>";
        $grilla .=          "<tbody>";  
        foreach($casos as $caso){
                $caso_id = $caso["caso_id"];
                $caso_numero = $caso["caso_numero"];
                $caso_casoEstado_id = $caso["caso_casoEstado_id"];
                $casoEstado_nombre = $caso["casoEstado_nombre"];
                $caso_fechaSiniestro = date('d-m-Y', strtotime($caso["caso_fechaSiniestro"]));
                $caso_beneficiarioNombre = $caso["caso_beneficiarioNombre"]; 
                $caso_paxVIP = $caso["caso_paxVIP"];
                $caso_numeroVoucher = $caso["caso_numeroVoucher"];
                $caso_paisNombre = $caso["pais_nombreEspanol"];
                $caso_ciudadNombre = $caso["ciudad_nombre"];
                //$usuarioAsignado_nombre = $caso["usuario_nombre"];
                //$usuarioAsignado_apellido = $caso["usuario_apellido"];
                //$casoAgenda_id = $caso["casoAgenda_id"];
        $grilla .=              "<tr>";
        $grilla .=                  "<td>";
        $grilla .=                      $caso_numero;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        if($caso_casoEstado_id == 7) { // Si es igual a Anulado (id. 7)
        $grilla .=                      "<span class='label label-danger'>" . $casoEstado_nombre . "</span>";
        } else if ($caso_casoEstado_id == 6) { // Si es igual a Cerrado (id. 7)
        $grilla .=                      "<span class='label label-success'>" . $casoEstado_nombre . "</span>";
        } else {
        $grilla .=                      "<span class='label label-inverse'>" . $casoEstado_nombre . "</span>";    
        }
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $caso_fechaSiniestro;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $caso_beneficiarioNombre;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        if($caso_paxVIP == 1){
        $grilla .=                      "<span class='label label-success'>VIP</span>";
        }else{
        $grilla .=                      "<span class='label label-danger'></span>";
        }
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $caso_numeroVoucher;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $caso_paisNombre;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $caso_ciudadNombre;
        $grilla .=                  "</td>";
        /*$grilla .=                  "<td>";
        if($casoAgenda_id != ''){
        $grilla .=                      "<span class='label label-success'>" . $usuarioAsignado_nombre . ' ' . $usuarioAsignado_apellido . "</span>";
        }else{
        $grilla .=                      "<span class='label label-danger'>Sin Asignar</span>";
        }
        $grilla .=                  "</td>";*/
        $grilla .=                  "<td>";
        $grilla .=                      "<a href='javascript:void(0)'> <i onclick='formulario_lectura($caso_id)' class='fa fa-edit'></i></a>";
        $grilla .=                  "</td>";
        $grilla .=              "</tr>";
        }
        $grilla .=          "</tbody>";
        $grilla .=       "</table>";
        $grilla .=    "</div>";
        $grilla .=  "</div>";
        $grilla .="</div>";   

        echo $grilla;
    }
    
    function grilla_listar_contar($caso_numero_desde_b,
                                $caso_numero_hasta_b,
                                $caso_estado_id_b,
                                $caso_tipoAsistencia_id_b,
                                $caso_fechaSiniestro_desde_b,
                                $caso_fechaSiniestro_hasta_b,
                                $caso_beneficiario_b,
                                $caso_voucher_b,
                                $caso_agencia_b){

        $casos = Caso::listar_contar($caso_numero_desde_b,
                                    $caso_numero_hasta_b,
                                    $caso_estado_id_b,
                                    $caso_tipoAsistencia_id_b,
                                    $caso_fechaSiniestro_desde_b,
                                    $caso_fechaSiniestro_hasta_b,
                                    $caso_beneficiario_b,
                                    $caso_voucher_b,
                                    $caso_agencia_b);

        $cantidad = $casos['registros'];
        
        If ($cantidad > 50){
            $texto = "<i class='fa fa-exclamation-circle'></i>   Se han encontrado " . $cantidad . " registros. Se muestran sólo los primeros 50 resultados. Por favor refine su búsqueda.";
        } else {
            $texto = "<p> Se han encontrado " . $cantidad . " registros.</p>";
        }

        $grilla = "<div class='row'>";
        $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
        $grilla .=  "<div class='card-box table-responsive'>";
        $grilla .=      $texto;
        $grilla .=    "</div>";
        $grilla .=  "</div>";
        $grilla .="</div>";   

        echo $grilla;
    }
    
    
    // Grilla Casos Repetidos
    function mostrar_vouchers_repetidos($numero_voucher) {
        
        $casos = Caso::mostrar_vouchers_repetidos($numero_voucher);
        
        $grilla = "<div class='row'>";
        $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
        $grilla .=  "<div class='card-box table-responsive'>";
        $grilla .=      "<table id='dt_caso' class='table table-hover table-striped m-0 table-responsive'>";
        $grilla .=          "<thead>";
        $grilla .=              "<tr>";
        $grilla .=                  "<th>Caso</th>";
        $grilla .=                  "<th>Fecha</th>";
        $grilla .=                  "<th>Tipo de Asistencia</th>";
        $grilla .=                  "<th>Voucher</th>";
        $grilla .=                  "<th>Beneficiario</th>";
        $grilla .=                  "<th>País</th>";
        $grilla .=                  "<th>Ciudad</th>";
        $grilla .=              "</tr>";
        $grilla .=          "</thead>";
        $grilla .=          "<tbody>";  
        foreach($casos as $caso) {
                $caso_id = $caso["casoId"];
                $caso_numero = $caso["casoNumero"];
                $caso_fechaSiniestro = date('d-m-Y', strtotime($caso["fechaSiniestro"]));
                $tipoAsistencia = $caso["tipoAsistencia"];
                $caso_numeroVoucher = $caso["voucher"];
                $caso_beneficiarioNombre = $caso["beneficiario"]; 
                $caso_paisNombre = $caso["pais"];
                $caso_ciudadNombre = $caso["ciudad"];
        $grilla .=              "<tr>";
        $grilla .=                  "<td>";
        $grilla .=                      "<a target='_blank' href='../caso/caso.php?vcase=" . $caso_id . "'>" . $caso_numero . "</a>";
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $caso_fechaSiniestro;
        $grilla .=                  "</td>";  
        $grilla .=                  "<td>";
        $grilla .=                      $tipoAsistencia;
        $grilla .=                  "</td>";  
        $grilla .=                  "<td>";
        $grilla .=                      $caso_numeroVoucher;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $caso_beneficiarioNombre;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $caso_paisNombre;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $caso_ciudadNombre;
        $grilla .=                  "</td>";
        $grilla .=              "</tr>";
        }
        $grilla .=          "</tbody>";
        $grilla .=       "</table>";
        $grilla .=    "</div>";
        $grilla .=  "</div>";
        $grilla .="</div>";   

        echo $grilla;
    }


    // Grilla para mostrar los últimos n Casos Creados
    function listar_ultimos_n(){
        
        $casos = Caso::listar_ultimos_n();

        $grilla = "<div class='col-lg-8'>";
        $grilla .= "<div class='card-box'>";
        $grilla .=  "<h4 class='text-dark  header-title m-t-0'>Últimos 4 Casos Abiertos</h4>";
        $grilla .=      "<div class='table-responsive'>";
        $grilla .=      "<table class='table mb-0'>";
        $grilla .=          "<thead>";
        $grilla .=              "<tr>";
        $grilla .=                  "<th>#</th>";
        $grilla .=                  "<th>Beneficiario</th>";
        $grilla .=                  "<th>Fecha Siniestro</th>";
        $grilla .=                  "<th>Voucher</th>";
        $grilla .=                  "<th>Estado</th>";
        $grilla .=              "</tr>";
        $grilla .=          "</thead>";
        $grilla .=          "<tbody>";  
        foreach($casos as $caso){
                $caso_id = $caso["caso_id"];
                $caso_numero = $caso["caso_numero"];
                $caso_beneficiarioNombre = $caso["caso_beneficiarioNombre"];
                $caso_fechaSiniestro = date('d-m-Y', strtotime($caso["caso_fechaSiniestro"]));
                $caso_numeroVoucher = $caso["caso_numeroVoucher"];
                $caso_casoEstado_id = $caso["caso_casoEstado_id"];
                $casoEstado_nombre = $caso["casoEstado_nombre"];
        $grilla .=              "<tr>";
        $grilla .=                  "<td>";
        $grilla .=                      "<a href='../caso/caso.php?vcase=" . $caso_id . "'>" . $caso_numero . "</a>";
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $caso_beneficiarioNombre;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $caso_fechaSiniestro;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $caso_numeroVoucher;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        if($caso_casoEstado_id == 7) { // Si es igual a Anulado (id. 7)
        $grilla .=                      "<span class='badge badge-danger'>" . $casoEstado_nombre . "</span>";
        } else if ($caso_casoEstado_id == 6) { // Si es igual a Cerrado (id. 7)
        $grilla .=                      "<span class='badge badge-success'>" . $casoEstado_nombre . "</span>";
        } else {
        $grilla .=                      "<span class='badge badge-purple'>" . $casoEstado_nombre . "</span>";    
        }
        $grilla .=                  "</td>";
        $grilla .=              "</tr>";
        }
        $grilla .=          "</tbody>";
        $grilla .=       "</table>";
        $grilla .=    "</div>";
        $grilla .=  "</div>";
        $grilla .="</div>";   

        echo $grilla;
    }


    // Función para validar si el Caso tiene Servicios o Reintegros cargados
    function valida_servicios_reintegros($caso_id) {

        // Caso Manual?
        $caso_ws = Caso::caso_info_ws($caso_id);

        // Tiene servicios?
        $servicios = count(Servicio::listar($caso_id));

        // Tiene reintegros?
        $reintegros = count(Reintegro::listarPorCaso($caso_id));

        /*  Lógica:
        *   No deja modificar los datos del voucher si el caso tiene Servicios o Reintegros cargados.
        *   La excepción es que el caso se haya cargado de forma manual.
        */

        if ((($servicios < 1) && ($reintegros < 1)) || $caso_ws == 0) {
            $resultado = false;
        } else {
            $resultado = true;
        }

        echo json_encode($resultado);
    }

    // Función para validar si el Caso tiene Servicios asistencia sin o con costo
    function valida_servicios_costo_asistencia($caso_id, $asistencia_sin_costo) {

        $resultado = false;

        if($asistencia_sin_costo !=""){
            $servicios = count(Servicio::listar_asistencia_costo($caso_id, $asistencia_sin_costo));
            if($servicios > 0){
                $resultado = true;
            }else{
                $resultado = false;
            }
        }
        echo json_encode($resultado);
    }
    
//--------------------------------------- Operaciones con Teléfono -------------------------------------------

// Vista Caso
    // Funciones de Grilla
    function grilla_telefonos_v($caso_id){

        $entidad_tipo = Caso::ENTIDAD;

        $telefonos = Telefono::listarPorEntidadId($caso_id, $entidad_tipo);

        $grilla = "<div class='row'>";
        $grilla .= "<div id='grilla_abm' class='col-sm-12'>"; 
        $grilla .=  "<div class='card-box table-responsive'>";
        $grilla .=      "<table id='dt_caso' class='table table-hover table-striped m-0 table-responsive'>";
        $grilla .=          "<thead>";
        $grilla .=              "<tr>";
        $grilla .=                  "<th>Tipo</th>";
        $grilla .=                  "<th>Número</th>";
        $grilla .=                  "<th>Principal</th>";
        $grilla .=              "</tr>";
        $grilla .=          "</thead>";
        $grilla .=          "<tbody>";  
        foreach($telefonos as $telefono)
        {
            $telefono_id = $telefono["telefono_id"];
            $telefono_tipo = $telefono["tipoTelefono_nombre"];
            $telefono_numero = $telefono["telefono_numero"];
            $telefono_principal = $telefono["telefono_principal"];

            $grilla .=              "<tr>";
            $grilla .=                  "<td>";
            $grilla .=                      $telefono_tipo;
            $grilla .=                  "</td>";
            $grilla .=                  "<td>";
            $grilla .=                      $telefono_numero;
            $grilla .=                  "</td>";
            $grilla .=                  "<td>";
            if($telefono_principal == 1){
                $grilla .=                 "<span class='label label-primary'>Principal</span>";
            }else{
                $grilla .=                 "<span class='label label-default'>Secundario</span>";
            }
            $grilla .=                  "</td>";
            $grilla .=              "</tr>";
        }
        $grilla .=          "</tbody>";
        $grilla .=       "</table>";
        $grilla .=    "</div>";
        $grilla .=  "</div>";
        $grilla .="</div>";   

        echo $grilla;

    }
    
// Modificar Caso
    // Inserta un teléfono nuevo
    function telefono_guardar($caso_telefonoTipo_id, $telefono_numero, $telefono_principal, $caso_id){

        $telefono_entidad_id = $caso_id;
        $telefono_entidad_tipo = Caso::ENTIDAD;

        $resultado = Telefono::insertar($caso_telefonoTipo_id, $telefono_numero, $telefono_principal, $telefono_entidad_id, $telefono_entidad_tipo);
        //echo json_encode($resultado);
        
        $error = MiError::mostrarError($resultado);
        echo json_encode($error, JSON_UNESCAPED_UNICODE);
    }


    // Modifica un teléfono existente 
    function telefono_modificar($caso_telefonoTipo_id, $telefono_numero, $telefono_principal, $caso_id, $telefono_id_m){

        $telefono_entidad_id = $caso_id;
        $telefono_entidad_tipo = Caso::ENTIDAD;

        $resultado = Telefono::modificar($caso_telefonoTipo_id, $telefono_numero, $telefono_principal, $telefono_entidad_id, $telefono_entidad_tipo, $telefono_id_m);
        //echo json_encode($resultado);
        
        $error = MiError::mostrarError($resultado);
        echo json_encode($error, JSON_UNESCAPED_UNICODE);
    }


    // Borra un teléfono, lo hace en forma definitivo y no lógica
    function telefono_borrar($telefono_id_b){    

        $resultado = Telefono::eliminar($telefono_id_b);
        //echo json_encode($resultado);
        
        $error = MiError::mostrarError($resultado);
        echo json_encode($error, JSON_UNESCAPED_UNICODE);
    }

    // Trae el teléfono para ser editado
    function telefono_editar($telefono_id_e){

        $resultado = Telefono::listarPorId($telefono_id_e);

        echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
    }


    // Funciones de Grilla
    function grilla_telefonos($caso_id){

        $entidad_tipo = Caso::ENTIDAD;

        $telefonos = Telefono::listarPorEntidadId($caso_id, $entidad_tipo);

        $grilla = "<div class='row'>";
        $grilla .= "<div id='grilla_abm' class='col-sm-12'>"; 
        $grilla .=  "<div class='card-box table-responsive'>";
        $grilla .=      "<table id='dt_caso' class='table table-hover table-striped m-0 table-responsive'>";
        $grilla .=          "<thead>";
        $grilla .=              "<tr>";
        $grilla .=                  "<th>Tipo</th>";
        $grilla .=                  "<th>Número</th>";
        $grilla .=                  "<th>Principal</th>";
        $grilla .=                  "<th>Acciones</th>";
        $grilla .=              "</tr>";
        $grilla .=          "</thead>";
        $grilla .=          "<tbody>";  
        foreach($telefonos as $telefono)
        {
            $telefono_id = $telefono["telefono_id"];
            $telefono_tipo = $telefono["tipoTelefono_nombre"];
            $telefono_numero = $telefono["telefono_numero"];
            $telefono_principal = $telefono["telefono_principal"];

            $grilla .=              "<tr>";
            $grilla .=                  "<td>";
            $grilla .=                      $telefono_tipo;
            $grilla .=                  "</td>";
            $grilla .=                  "<td>";
            $grilla .=                      $telefono_numero;
            $grilla .=                  "</td>";
            $grilla .=                  "<td>";
            if($telefono_principal == 1){
                $grilla .=                 "<span class='label label-primary'>Principal</span>";
            }else{
                $grilla .=                 "<span class='label label-default'>Secundario</span>";
            }
            $grilla .=                  "</td>";
            $grilla .=                  "<td>";
            $grilla .=                      "<a href='javascript:void(0)'> <i onclick='telefono_editar($telefono_id)' class='fa fa-edit'></i></a>";
            $grilla .=                      "<a href='javascript:void(0)'> <i onclick='modal_telefono_borrar($telefono_id)' class='fa fa-trash'></i></a>";
            $grilla .=                  "</td>";
            $grilla .=              "</tr>";
        }
        $grilla .=          "</tbody>";
        $grilla .=       "</table>";
        $grilla .=    "</div>";
        $grilla .=  "</div>";
        $grilla .="</div>";   

        echo $grilla;

    }

// ------------------------------------------------ Fin operaciones Teléfonos -------------------------------------------