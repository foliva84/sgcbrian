<?php
require '../seguridad/seguridad.php';
include_once '../includes/herramientas.php';

//Toma las variables del formulario de confirmacion de servicios 
$services_confirmation                = isset($_POST["services_confirmation"])?$_POST["services_confirmation"]:[];

// Toma las variables del formulario de alta
$servicio_prestador_id_n            = isset($_POST["servicio_prestador_id_n"])?$_POST["servicio_prestador_id_n"]:'';
$servicio_practica_id_n             = isset($_POST["servicio_practica_id_n"])?$_POST["servicio_practica_id_n"]:'';
$servicio_moneda_id_n               = isset($_POST["servicio_moneda_id_n"])?$_POST["servicio_moneda_id_n"]:'';
$servicio_tipoCambio_n              = isset($_POST["servicio_tipoCambio_n"])?$_POST["servicio_tipoCambio_n"]:'';
$servicio_presuntoOrigen_n          = isset($_POST["servicio_presuntoOrigen_n"])?$_POST["servicio_presuntoOrigen_n"]:'';
$servicio_presuntoUSD_n             = isset($_POST["servicio_presuntoUSD_n"])?$_POST["servicio_presuntoUSD_n"]:'';
$servicio_justificacion_n           = isset($_POST["servicio_justificacion_n"])?$_POST["servicio_justificacion_n"]:'';
$caso_id_n                          = isset($_POST["caso_id_n"])?$_POST["caso_id_n"]:'';

// Toma las variables del formulario de modificación
$servicio_id                        = isset($_POST["servicio_id"])?$_POST["servicio_id"]:'';
$servicio_prestador_id              = isset($_POST["servicio_prestador_id"])?$_POST["servicio_prestador_id"]:'';
$servicio_practica_id               = isset($_POST["servicio_practica_id"])?$_POST["servicio_practica_id"]:'';
$servicio_moneda_id                 = isset($_POST["servicio_moneda_id"])?$_POST["servicio_moneda_id"]:'';
$servicio_tipoCambio                = isset($_POST["servicio_tipoCambio"])?$_POST["servicio_tipoCambio"]:'';
$servicio_presuntoOrigen            = isset($_POST["servicio_presuntoOrigen"])?$_POST["servicio_presuntoOrigen"]:'';
$servicio_presuntoUSD               = isset($_POST["servicio_presuntoUSD"])?$_POST["servicio_presuntoUSD"]:'';
$servicio_justificacion             = isset($_POST["servicio_justificacion"])?$_POST["servicio_justificacion"]:'';
$servicio_autorizado                = isset($_POST["servicio_autorizado"])?$_POST["servicio_autorizado"]:'';
$caso_id                            = isset($_POST["caso_id"])?$_POST["caso_id"]:'';

// Toma las variables del formulario de GOP
$servicio_id                        = isset($_POST["servicio_id"])?$_POST["servicio_id"]:'';
$servicio_prestador_id              = isset($_POST["servicio_prestador_id"])?$_POST["servicio_prestador_id"]:'';
$servicio_practica_id               = isset($_POST["servicio_practica_id"])?$_POST["servicio_practica_id"]:'';
$servicio_moneda_id                 = isset($_POST["servicio_moneda_id"])?$_POST["servicio_moneda_id"]:'';
$servicio_tipoCambio                = isset($_POST["servicio_tipoCambio"])?$_POST["servicio_tipoCambio"]:'';
$servicio_presuntoOrigen            = isset($_POST["servicio_presuntoOrigen"])?$_POST["servicio_presuntoOrigen"]:'';
$servicio_presuntoUSD               = isset($_POST["servicio_presuntoUSD"])?$_POST["servicio_presuntoUSD"]:'';
$servicio_autorizado                = isset($_POST["servicio_autorizado"])?$_POST["servicio_autorizado"]:'';
$caso_id                            = isset($_POST["caso_id"])?$_POST["caso_id"]:'';

// Toma variables para el calculo de importes
$importe_origen                     = isset($_POST["importe_origen"])?$_POST["importe_origen"]:'';
$tipo_cambio                        = isset($_POST["tipo_cambio"])?$_POST["tipo_cambio"]:'';

// Toma el servicio_id para deshabilitar
$servicio_id_b                      = isset($_POST["servicio_id_b"])?$_POST["servicio_id_b"]:'';

// Toma el servicio_id para volver a habilitar
$servicio_id_a                      = isset($_POST["servicio_id_a"])?$_POST["servicio_id_a"]:'';

// Busqueda de PRESTADOR
$prestador_nombre_buscar            = isset($_POST["prestador_nombre_buscar"])?$_POST["prestador_nombre_buscar"]:'';
$prestador_tipoPrestador_id_buscar  = isset($_POST["prestador_tipoPrestador_id_buscar"])?$_POST["prestador_tipoPrestador_id_buscar"]:'';
$prestador_pais_id_buscar           = isset($_POST["prestador_pais_id_buscar"])?$_POST["prestador_pais_id_buscar"]:'';
$prestador_ciudad_id_buscar         = isset($_POST["prestador_ciudad_id_buscar"])?$_POST["prestador_ciudad_id_buscar"]:'';
$formulario_busqueda                = isset($_POST["formulario_busqueda"])?$_POST["formulario_busqueda"]:''; 
$prestador_id                       = isset($_POST["prestador_id"])?$_POST["prestador_id"]:'';

// Toma el id de país para los select dependientes
$pais_id                            = isset($_POST["pais_id"])?$_POST["pais_id"]:'';
$ciudad                             = isset($_POST["ciudad"])?$_POST["ciudad"]:'';

// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion                             = isset($_POST["opcion"])?$_POST["opcion"]:'';


switch($opcion){
    
    // Acciones de los formularios
    case 'formulario_alta':
        formulario_alta($servicio_prestador_id_n, 
                        $servicio_practica_id_n, 
                        $servicio_moneda_id_n, 
                        $servicio_tipoCambio_n,
                        $servicio_presuntoOrigen_n,
                        $servicio_justificacion_n,
                        $caso_id_n,
                        $permisos,
                        $sesion_usuario_id);
        break;
     
    case 'formulario_baja':
        formulario_baja($servicio_id_b);
        break;
    
    case 'formulario_habilita':
        formulario_habilita($servicio_id_a);
        break;
    
    case 'formulario_modificacion':
        formulario_modificacion($servicio_prestador_id, 
                                $servicio_practica_id, 
                                $servicio_moneda_id, 
                                $servicio_tipoCambio,
                                $servicio_presuntoOrigen,
                                $servicio_justificacion,
                                $servicio_autorizado,
                                $servicio_id);
        break;
    
    case 'formulario_confirmacion_servicios':
        formulario_confirmacion_servicios($services_confirmation);
        break;

    case 'formulario_lectura':
        formulario_lectura($servicio_id);
        break;

    case 'crear_gop':
        crear_gop($servicio_id);
        break;
    
    // Case grillas
    case 'grilla_listar':
        grilla_listar($caso_id, $permisos);
        break;
    
    case 'grilla_listar_servicios_sin_confirmar':
        grilla_listar_servicios_sin_confirmar($caso_id, $permisos);
        break;
    
    case 'grilla_listar_prestador':
        grilla_listar_prestador($prestador_nombre_buscar, 
                                $prestador_tipoPrestador_id_buscar, 
                                $prestador_pais_id_buscar,
                                $prestador_ciudad_id_buscar,
                                $formulario_busqueda);
        break;
        
    case 'mostrar_fci_asignados':
        mostrar_fci_asignados($servicio_id);
        break;


    // Acciones auxiliares en el formulario
    case 'listarPractica_altaServicio':
        listarPractica_altaServicio($prestador_id);
        break;
    case 'prestador_altaServicio':
        prestador_altaServicio($prestador_id);
        break;
    
    case 'listarPractica_modificarServicio':
        listarPractica_modificarServicio($servicio_id, $prestador_id);
        break;
    
    case 'listarMoneda_altaServicio':
        listarMoneda_altaServicio();
        break;
    
    case 'listarMoneda_modificarServicio':
        listarMoneda_modificarServicio($servicio_id);
        break;
    
    case 'calcular_importe_usd':
        calcular_importe_usd($importe_origen, $tipo_cambio);
        break;
    
    
    //funciones para busqueda de prestador
    case 'listarTipoPrestador_buscarPrestador':
        listarTipoPrestador_buscarPrestador();
        break;
    
    case 'listarPaises_buscarPrestador':
        listarPaises_buscarPrestador();
        break;

    case 'select_ciudades':
        select_ciudades($ciudad, $pais_id);
        break;

           
    default:
       echo("Está mal seleccionada la funcion");
}


// Funciones de Formulario
function formulario_alta($servicio_prestador_id_n, 
                         $servicio_practica_id_n, 
                         $servicio_moneda_id_n, 
                         $servicio_tipoCambio_n,
                         $servicio_presuntoOrigen_n,
                         $servicio_justificacion_n,
                         $caso_id_n,
                         $permisos,
                         $sesion_usuario_id) {
    
    Servicio::insertar($servicio_prestador_id_n, 
                       $servicio_practica_id_n, 
                       $servicio_moneda_id_n, 
                       $servicio_tipoCambio_n,
                       $servicio_presuntoOrigen_n,
                       $servicio_justificacion_n,
                       $caso_id_n,
                       $permisos,
                       $sesion_usuario_id); 
}


function formulario_baja($servicio_id_b){
    
    $resultado = Servicio::borradoLogico($servicio_id_b);
    
    echo json_encode($resultado);    
}


function formulario_habilita($servicio_id_a) {
    
    $resultado = Servicio::reActivar($servicio_id_a);
    
    echo json_encode($resultado);    
}


function formulario_modificacion($servicio_prestador_id, 
                                $servicio_practica_id, 
                                $servicio_moneda_id, 
                                $servicio_tipoCambio,
                                $servicio_presuntoOrigen,
                                $servicio_justificacion,
                                $servicio_autorizado,
                                $servicio_id) {
    
    Servicio::actualizar($servicio_prestador_id, 
                        $servicio_practica_id, 
                        $servicio_moneda_id, 
                        $servicio_tipoCambio,
                        $servicio_presuntoOrigen,
                        $servicio_justificacion,
                        $servicio_autorizado,
                        $servicio_id);
}

function formulario_confirmacion_servicios($services_ids){
    $servicio = Servicio::actualizar_confirmacion($services_ids);
    echo json_encode($servicio);
}

function formulario_lectura($servicio_id) {
    
    $servicio = Servicio::buscarPorId($servicio_id);
    
    echo json_encode($servicio);
}


function crear_gop($servicio_id) {
    
    $gop_info = Servicio::armar_gop($servicio_id);
    
    echo json_encode($gop_info);
}


// Funciones auxiliares de formulario
    // Funciones para llenar los Select

    function listarPractica_altaServicio($prestador_id) {

        $practicas = Servicio::listarPractica_altaServicio($prestador_id);

        echo json_encode($practicas);   
    }
    function prestador_altaServicio($prestador_id) {

        $prestador = Prestador::buscar_por_id($prestador_id);

        echo json_encode($prestador);   
    }
    
    function listarPractica_modificarServicio($servicio_id, $prestador_id) {

        $practicas = Servicio::listarPractica_modificarServicio($servicio_id, $prestador_id);

        echo json_encode($practicas);  
    }
    
    function listarMoneda_altaServicio(){

        $monedas = Servicio::listarMoneda_altaServicio();

        echo json_encode($monedas);   
    }
    
    function listarMoneda_modificarServicio($servicio_id) {

        $monedas = Servicio::listarMoneda_modificarServicio($servicio_id);

        echo json_encode($monedas);   
    }
    
    function calcular_importe_usd($importe_origen, $tipo_cambio) {

        $calculo = ($importe_origen / $tipo_cambio);

        echo json_encode($calculo);
    }
    
    //Funciones para busqueda de prestador
    function listarTipoPrestador_buscarPrestador() {

        $tiposPrestadores = TipoPrestador::formulario_alta_tipoPrestador();

        echo json_encode($tiposPrestadores);   
    }
    
    function listarPaises_buscarPrestador(){

        $paises = Pais::formulario_alta_paises();

        echo json_encode($paises);
    }
    
    function select_ciudades($ciudad, $pais_id) {

        $ciudades = Ciudad::buscar_select($ciudad, $pais_id);

        $data = array();
        foreach ($ciudades as $ciudad) {
            $name = $ciudad['ciudad_nombre'] . '|' . $ciudad['ciudad_id'];
            array_push($data, $name);	
        }	

        echo json_encode($data);
    }
     
    
// Funciones de Grilla de Servicios
function grilla_listar($caso_id, $permisos) {
    
    $servicios = Servicio::listar($caso_id);

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<table class='table table-hover table-striped m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th class='col-sm-2'>Fecha Ingreso</th>";
    $grilla .=                  "<th class='col-sm-2'>Usuario</th>";    
    $grilla .=                  "<th class='col-sm-2'>Prestador</th>";
    $grilla .=                  "<th class='col-sm-2'>Practica</th>";
    $grilla .=                  "<th class='col-sm-2'>Presunto Origen</th>";
    $grilla .=                  "<th class='col-sm-1'>Moneda</th>";
    $grilla .=                  "<th class='col-sm-1'>T/C</th>";
    $grilla .=                  "<th class='col-sm-2'>Confirmación</th>";
    $grilla .=                  "<th class='col-sm-2'>Presunto USD</th>";
    $grilla .=                  "<th class='col-sm-2'>Estado</th>";
    $grilla .=                  "<th class='col-sm-2'>Acciones</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($servicios as $servicio) {
            $servicio_id                = $servicio["servicio_id"];
            $cant_fci                   = Servicio::servicio_con_fci($servicio_id);
            $servicio_fecha             = $servicio["servicio_fecha"];
            $servicio_prestador_nombre  = $servicio["prestador_nombre"];
            $servicio_practica_nombre   = $servicio["practica_nombre"];
            $servicio_presuntoOrigen    = $servicio["servicio_presuntoOrigen"];
            $servicio_presuntoOrigen    = number_format($servicio_presuntoOrigen, 2, ',', '.');
            $servicio_tipoCambio        = $servicio["servicio_tipoCambio"];
            $moneda_nombre              = $servicio["moneda_nombre"];
            $servicio_presuntoUSD       = $servicio["servicio_presuntoUSD"];
            $servicio_presuntoUSD       = number_format($servicio_presuntoUSD, 2, ',', '.');
            $servicio_autorizado        = $servicio["servicio_autorizado"];
            $servicio_confirmado       = $servicio["servicio_confirmado"];
            $servicio_cargaFC           = $servicio["servicio_cargaFueraCobertura"];
            
            $servicio_usuario           = $servicio["servicio_usuario"];
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    $grilla .=                      $servicio_fecha;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $servicio_usuario;
    $grilla .=                  "</td>";    
    $grilla .=                  "<td>";
    $grilla .=                      $servicio_prestador_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $servicio_practica_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $servicio_presuntoOrigen;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $moneda_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $servicio_tipoCambio;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    if($servicio_confirmado == 1){
        $grilla .=                  "<span class='label label-success'>Confirmado</span>";
    }else{
        $grilla .=                  "<span class='label label-danger'>Sin Confirmar</span>";
    }
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $servicio_presuntoUSD;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    if($servicio_autorizado == 1){
    $grilla .=                  "<span class='label label-success'>Autorizado</span>";
    }else{
    $grilla .=                  "<span class='label label-danger'>Sin Autorizar</span>";
    }
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    // Valida si el servicio tiene FCI asignados en Facturacion. 
    // En caso de tener, no permite modificar el servicio ni enviar GOP
    if ($cant_fci == 0) {
        // Validacion de permisos para: Editar el servicio
        if (Usuario::puede("servicios_modificar") == 1) {
            // Si el servicio esta autorizado, solo puede editarse el servicio -para desautorizar- teniendo el permiso servicios_autorizar
            if ($servicio_autorizado == 0 || (Usuario::puede("servicios_autorizar") == 1)) {
                // Si el servicio se cargo en un caso fuera de cobertura, solo usuarios con permisos especiales pueden editar el servicio
                if ($servicio_cargaFC == 0 || (Usuario::puede("carga_servicio_casoFueraCobertura") == 1)) {
                    $grilla .= "<a href='javascript:void(0)'> <i onclick='formulario_lectura($servicio_id)' class='fa fa-edit' data-toggle='tooltip' data-placement='top' title='Editar servicio'></i></a>";
                } else {
                    $grilla .= "<i class='fa fa-info-circle' data-toggle='tooltip' data-placement='top' title='El servicio solo puede editarse con permisos especiales'></i>"; 
                }
            } else {
                $grilla .= "<i class='fa fa-info-circle' data-toggle='tooltip' data-placement='top' title='El servicio no puede editarse, dado que se encuentra Autorizado'></i>"; 
            } 
        }
        // Validacion de permisos para: Enviar GOP
        $servicios_gop_enviar = array_search('servicios_gop_enviar', array_column($permisos, 'permiso_variable'));
        if (!empty($servicios_gop_enviar) || ($servicios_gop_enviar === 0)) {
            $grilla .=                      "<a href='javascript:void(0)'> <i onclick='return crear_gop($servicio_id)' class='md md-mail' data-toggle='tooltip' data-placement='top' title='Enviar GOP'></i></a>";
        }
    } else {
        $grilla .=                      "<i class='fa fa-info-circle' data-toggle='tooltip' data-placement='top' title='Este servicio tiene facturas asociadas. No puede ser editado o enviarse GOP'></i>";
        if (Usuario::puede("facturas_ver") == 1) {
            $grilla .=                      "<a href='javascript:void(0)'> <i onclick='return mostrar_fci_asignados($servicio_id)' class='fa fa-sticky-note-o' data-toggle='tooltip' data-placement='top' title='Ver Factura'></i></a>";
        }
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


function grilla_listar_servicios_sin_confirmar($caso_id, $permisos) {
    
    $servicios = Servicio::listar_sin_confirmar($caso_id);

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=  "<input type='hidden' id='opcion' name='opcion' value='formulario_confirmacion_servicios'>";
    $grilla .=      "<table class='table table-hover table-striped m-0 table-responsive' id='listado'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th class='col-sm-2'><input type='checkbox' id='seleccionar-todos'></th>";
    $grilla .=                  "<th class='col-sm-2'>Fecha Ingreso</th>";
    $grilla .=                  "<th class='col-sm-2'>Usuario</th>";    
    $grilla .=                  "<th class='col-sm-2'>Prestador</th>";
    $grilla .=                  "<th class='col-sm-2'>Practica</th>";
    $grilla .=                  "<th class='col-sm-2'>Presunto Origen</th>";
    $grilla .=                  "<th class='col-sm-1'>Moneda</th>";
    $grilla .=                  "<th class='col-sm-1'>T/C</th>";
    $grilla .=                  "<th class='col-sm-2'>Presunto USD</th>";
    $grilla .=                  "<th class='col-sm-2'>Estado</th>";
    // $grilla .=                  "<th class='col-sm-2'>Acciones</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($servicios as $servicio) {
            $servicio_id                = $servicio["servicio_id"];
            $cant_fci                   = Servicio::servicio_con_fci($servicio_id);
            $servicio_fecha             = $servicio["servicio_fecha"];
            $servicio_prestador_nombre  = $servicio["prestador_nombre"];
            $servicio_practica_nombre   = $servicio["practica_nombre"];
            $servicio_presuntoOrigen    = $servicio["servicio_presuntoOrigen"];
            $servicio_presuntoOrigen    = number_format($servicio_presuntoOrigen, 2, ',', '.');
            $servicio_tipoCambio        = $servicio["servicio_tipoCambio"];
            $moneda_nombre              = $servicio["moneda_nombre"];
            $servicio_presuntoUSD       = $servicio["servicio_presuntoUSD"];
            $servicio_presuntoUSD       = number_format($servicio_presuntoUSD, 2, ',', '.');
            $servicio_autorizado        = $servicio["servicio_autorizado"];
            $servicio_confirmado       = $servicio["servicio_confirmado"];
            $servicio_cargaFC           = $servicio["servicio_cargaFueraCobertura"];
            
            $servicio_usuario           = $servicio["servicio_usuario"];
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    if($servicio_confirmado == 1){
        $checked = "checked";
    }else{
        $checked = "";
    }

    $data = $servicio_fecha . ',' . 
    $servicio_usuario . ',' . 
    $servicio_prestador_nombre . ',' .
    $servicio_practica_nombre . ',' .
    $servicio_presuntoOrigen . ',' .
    $moneda_nombre . ',' .
    $servicio_tipoCambio . ',' .
    $servicio_presuntoUSD ; 

    $grilla .=   "<input name='services_confirmation[]' id='services_confirmation' type='checkbox' value='" . $servicio_id . "' " . $checked . " >
    <input name='".$servicio_id."'  id='".$servicio_id."' type='hidden' value='" . $data . "'  >";
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $servicio_fecha;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $servicio_usuario;
    $grilla .=                  "</td>";    
    $grilla .=                  "<td>";
    $grilla .=                      $servicio_prestador_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $servicio_practica_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $servicio_presuntoOrigen;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $moneda_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $servicio_tipoCambio;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $servicio_presuntoUSD;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    if($servicio_autorizado == 1){
    $grilla .=                  "<span class='label label-success'>Autorizado</span>";
    }else{
    $grilla .=                  "<span class='label label-danger'>Sin Autorizar</span>";
    }
    $grilla .=                  "</td>";
    
    // $grilla .=                  "<td>";
    // Valida si el servicio tiene FCI asignados en Facturacion. 
    // En caso de tener, no permite modificar el servicio ni enviar GOP
    // if ($cant_fci == 0) {
        // Validacion de permisos para: Editar el servicio
        // if (Usuario::puede("servicios_modificar") == 1) {
            // Si el servicio esta autorizado, solo puede editarse el servicio -para desautorizar- teniendo el permiso servicios_autorizar
            // if ($servicio_autorizado == 0 || (Usuario::puede("servicios_autorizar") == 1)) {
                // Si el servicio se cargo en un caso fuera de cobertura, solo usuarios con permisos especiales pueden editar el servicio
        //         if ($servicio_cargaFC == 0 || (Usuario::puede("carga_servicio_casoFueraCobertura") == 1)) {
        //             $grilla .= "<a href='javascript:void(0)'> <i onclick='formulario_lectura($servicio_id)' class='fa fa-edit' data-toggle='tooltip' data-placement='top' title='Editar servicio'></i></a>";
        //         } else {
        //             $grilla .= "<i class='fa fa-info-circle' data-toggle='tooltip' data-placement='top' title='El servicio solo puede editarse con permisos especiales'></i>"; 
        //         }
        //     } else {
        //         $grilla .= "<i class='fa fa-info-circle' data-toggle='tooltip' data-placement='top' title='El servicio no puede editarse, dado que se encuentra Autorizado'></i>"; 
        //     } 
        // }
        // Validacion de permisos para: Enviar GOP
        // $servicios_gop_enviar = array_search('servicios_gop_enviar', array_column($permisos, 'permiso_variable'));
        // if (!empty($servicios_gop_enviar) || ($servicios_gop_enviar === 0)) {
        //     $grilla .=                      "<a href='javascript:void(0)'> <i onclick='return crear_gop($servicio_id)' class='md md-mail' data-toggle='tooltip' data-placement='top' title='Enviar GOP'></i></a>";
        // }
    // } else {
    //     $grilla .=                      "<i class='fa fa-info-circle' data-toggle='tooltip' data-placement='top' title='Este servicio tiene facturas asociadas. No puede ser editado o enviarse GOP'></i>";
    //     if (Usuario::puede("facturas_ver") == 1) {
    //         $grilla .=                      "<a href='javascript:void(0)'> <i onclick='return mostrar_fci_asignados($servicio_id)' class='fa fa-sticky-note-o' data-toggle='tooltip' data-placement='top' title='Ver Factura'></i></a>";
    //     }
    // }      
    // $grilla .=                  "</td>";
    $grilla .=              "</tr>";
    }
    $grilla .=          "</tbody>";
    $grilla .=       "</table>";
    $grilla .=    "</div>";
    $grilla .=  "</div>";
    $grilla .="</div>"; 
    
    $grilla .="<script>$(function(){ $('#seleccionar-todos').change(function() { $('#listado  :input[type=checkbox]').prop('checked', $(this).is(':checked')); }); });</script>";
    
    echo $grilla;
}


function grilla_listar_prestador($prestador_nombre_buscar, 
                                 $prestador_tipoPrestador_id_buscar, 
                                 $prestador_pais_id_buscar,
                                 $prestador_ciudad_id_buscar,
                                 $formulario_busqueda){
    
    $prestadores = Prestador::listar_filtrado_enServicios($prestador_nombre_buscar, 
                                                          $prestador_tipoPrestador_id_buscar, 
                                                          $prestador_pais_id_buscar,
                                                          $prestador_ciudad_id_buscar);
    
    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<table class='table table-hover table-striped m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Nombre</th>";
    $grilla .=                  "<th>Prioridad</th>";
    $grilla .=                  "<th>Prestador tipo</th>";
    $grilla .=                  "<th>Direccion</th>";
    $grilla .=                  "<th>Pais</th>";
    $grilla .=                  "<th>Ciudad</th>";
    $grilla .=                  "<th>Acciones</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($prestadores as $prestador){
            $prestador_id = $prestador["prestador_id"];
            $prestador_nombre = $prestador["prestador_nombre"];
            $prestadorPrioridad_id = $prestador["prestadorPrioridad_id"];
            $prestadorPrioridad_nombre = $prestador["prestadorPrioridad_nombre"];
            $prestadorTipo_nombre = $prestador["tipoPrestador_nombre"];
            $prestador_direccion = $prestador["prestador_direccion"];    
            $prestadorPais_nombre = $prestador["pais_nombreEspanol"];
            $prestadorCiudad_nombre = $prestador["ciudad_nombre"];
            $prestador_activo = $prestador["prestador_activo"];
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    $grilla .=                      "<a target='_blank' href='../prestador/prestador.php?vprovider=" . $prestador_id . "'>" . $prestador_nombre . "</a>";
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    if($prestadorPrioridad_id == 1){
        $grilla .=                 "<span class='label label-primary'>$prestadorPrioridad_nombre</span>";
    }else{
        $grilla .=                 "<span class='label label-default'>$prestadorPrioridad_nombre</span>";
    }
    $grilla .=                  "</td>";    
    $grilla .=                  "<td>";
    $grilla .=                      $prestadorTipo_nombre;
    $grilla .=                  "</td>";    
    $grilla .=                  "<td>";
    $grilla .=                      $prestador_direccion;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $prestadorPais_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $prestadorCiudad_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      "<a href='../prestador/prestador.php?vprovider=" . $prestador_id . "' target='_blank'> <i class='fa fa-search' data-toggle='tooltip' data-placement='top' title='Ver prestador'></i></a>";
    if ($formulario_busqueda == 1) {
        $grilla .=                      "<a href='javascript:void(0)'> <i onclick='asignarPrestador_servicio($prestador_id, 1)' class='fa fa-edit' data-toggle='tooltip' data-placement='top' title='Seleccionar prestador'></i></a>";
    } else {
        $grilla .=                      "<a href='javascript:void(0)'> <i onclick='asignarPrestador_servicio($prestador_id, 2)' class='fa fa-edit' data-toggle='tooltip' data-placement='top' title='Seleccionar prestador'></i></a>";
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


function mostrar_fci_asignados($servicio_id){
    
    $fci_asignados = Facturacion::listar_fci_asignados($servicio_id);
    
    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<table class='table table-hover table-striped m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Factura</th>";
    $grilla .=                  "<th>Estado</th>";
    $grilla .=                  "<th>Pagador</th>";
    $grilla .=                  "<th>Fecha Ingreso FCI</th>";
    $grilla .=                  "<th>Importe Médico</th>";
    $grilla .=                  "<th>Importe Fee</th>";
    $grilla .=                  "<th>Moneda</th>";
    $grilla .=                  "<th>Importe USD</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($fci_asignados as $fci_asignado) {
            $factura_id = $fci_asignado["factura_id"];
            $factura_numero = $fci_asignado["factura_numero"];
            $estado = $fci_asignado["facturaEstado_nombre"];
            $cliente_nombre = $fci_asignado["cliente_nombre"];
            $fci_fecha_ingreso = $fci_asignado["fcItem_fechaIngresoSistema"];
            $importe_medico_origen = $fci_asignado["fcItem_importeMedicoOrigen"];
            $importe_fee = $fci_asignado["fcItem_importeFeeOrigen"];
            $moneda = $fci_asignado["moneda_nombre"];
            $importe_USD = $fci_asignado["fcItem_importeUSD"];
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    $grilla .=                      "<a target='_blank' href='../facturacion/facturacion.php?vinvoice=" . $factura_id . "'>" . $factura_numero . "</a>";
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $estado;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $cliente_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $fci_fecha_ingreso;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $importe_medico_origen;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $importe_fee;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $moneda;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $importe_USD;
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