<?php
require '../seguridad/seguridad.php';
include_once '../includes/herramientas.php';

// Toma las variables del formulario de alta
$prestador_nombre_n = isset($_POST["prestador_nombre_n"])?$_POST["prestador_nombre_n"]:'';
$prestador_razonSocial_n = isset($_POST["prestador_razonSocial_n"])?$_POST["prestador_razonSocial_n"]:'';
$prestador_tipoPrestador_id_n = isset($_POST["prestador_tipoPrestador_id_n"])?$_POST["prestador_tipoPrestador_id_n"]:'';
$prestador_paginaWeb_n = isset($_POST["prestador_paginaWeb_n"])?$_POST["prestador_paginaWeb_n"]:'';
$prestador_prestadorPrioridad_id_n = isset($_POST["prestador_prestadorPrioridad_id_n"])?$_POST["prestador_prestadorPrioridad_id_n"]:'';
$prestador_direccion_n = isset($_POST["prestador_direccion_n"])?$_POST["prestador_direccion_n"]:'';
$prestador_codigoPostal_n = isset($_POST["prestador_codigoPostal_n"])?$_POST["prestador_codigoPostal_n"]:'';
$prestador_pais_id_n = isset($_POST["prestador_pais_id_n"])?$_POST["prestador_pais_id_n"]:'';
$prestador_ciudad_id_n = isset($_POST["prestador_ciudad_id_n_2"])?$_POST["prestador_ciudad_id_n_2"]:'';
$prestador_contrato_id_n = isset($_POST["prestador_contrato_id_n"])?$_POST["prestador_contrato_id_n"]:'';
$prestador_contratoObservaciones_n = isset($_POST["prestador_contratoObservaciones_n"])?$_POST["prestador_contratoObservaciones_n"]:'';
$prestador_bancoIntermediario_n = isset($_POST["prestador_bancoIntermediario_n"])?$_POST["prestador_bancoIntermediario_n"]:'';
$prestador_bancoBeneficiario_n = isset($_POST["prestador_bancoBeneficiario_n"])?$_POST["prestador_bancoBeneficiario_n"]:'';
$prestador_taxID_n = isset($_POST["prestador_taxID_n"])?$_POST["prestador_taxID_n"]:'';
$prestador_inicioActividades_n = isset($_POST["prestador_inicioActividades_n"])?$_POST["prestador_inicioActividades_n"]:'';
$prestador_observaciones_n = isset($_POST["prestador_observaciones_n"])?$_POST["prestador_observaciones_n"]:'';

// Formulario telefonos + e-mail (Alta)
$telefonoTipo_id_n = isset($_POST["telefonoTipo_id_n"])?$_POST["telefonoTipo_id_n"]:'';
$telefono_numero_n = isset($_POST["telefono_numero_n"])?$_POST["telefono_numero_n"]:'';
$telefono_principal_n = isset($_POST["telefono_principal_n"])?$_POST["telefono_principal_n"]:'';   
$emailTipo_id_n = isset($_POST["emailTipo_id_n"])?$_POST["emailTipo_id_n"]:'';
$email_email_n = isset($_POST["email_email_n"])?$_POST["email_email_n"]:'';
$email_principal_n = isset($_POST["email_principal_n"])?$_POST["email_principal_n"]:'';

// Toma las variables del formulario de modificación
$prestador_id = isset($_POST["prestador_id"])?$_POST["prestador_id"]:'';
$prestador_nombre = isset($_POST["prestador_nombre"])?$_POST["prestador_nombre"]:'';
$prestador_razonSocial = isset($_POST["prestador_razonSocial"])?$_POST["prestador_razonSocial"]:'';
$prestador_tipoPrestador_id = isset($_POST["prestador_tipoPrestador_id"])?$_POST["prestador_tipoPrestador_id"]:'';
$prestador_paginaWeb = isset($_POST["prestador_paginaWeb"])?$_POST["prestador_paginaWeb"]:'';
$prestador_prestadorPrioridad_id = isset($_POST["prestador_prestadorPrioridad_id"])?$_POST["prestador_prestadorPrioridad_id"]:'';
$prestador_direccion = isset($_POST["prestador_direccion"])?$_POST["prestador_direccion"]:'';
$prestador_codigoPostal = isset($_POST["prestador_codigoPostal"])?$_POST["prestador_codigoPostal"]:'';
$prestador_pais_id = isset($_POST["prestador_pais_id"])?$_POST["prestador_pais_id"]:'';
$prestador_ciudad_id = isset($_POST["prestador_ciudad_id_2"])?$_POST["prestador_ciudad_id_2"]:'';
$prestador_contrato_id = isset($_POST["prestador_contrato_id"])?$_POST["prestador_contrato_id"]:'';
$prestador_contratoObservaciones = isset($_POST["prestador_contratoObservaciones"])?$_POST["prestador_contratoObservaciones"]:'';
$prestador_bancoIntermediario = isset($_POST["prestador_bancoIntermediario"])?$_POST["prestador_bancoIntermediario"]:'';
$prestador_bancoBeneficiario = isset($_POST["prestador_bancoBeneficiario"])?$_POST["prestador_bancoBeneficiario"]:'';
$prestador_taxID = isset($_POST["prestador_taxID"])?$_POST["prestador_taxID"]:'';
$prestador_inicioActividades = isset($_POST["prestador_inicioActividades"])?$_POST["prestador_inicioActividades"]:'';
$prestador_observaciones = isset($_POST["prestador_observaciones"])?$_POST["prestador_observaciones"]:'';
//chequear habilitado sirve o sobra
$prestador_habilitado = isset($_POST["prestador_habilitado"])?$_POST["prestador_habilitado"]:'';

// Formulario telefonos + e-mail (Modificacion)
$telefono_id = isset($_POST["telefono_id"])?$_POST["telefono_id"]:'';
$telefono_id_e = isset($_POST["telefono_id_e"])?$_POST["telefono_id_e"]:'';
$telefono_id_m = isset($_POST["telefono_id_m"])?$_POST["telefono_id_m"]:'';
$telefono_id_b = isset($_POST["telefono_id_b"])?$_POST["telefono_id_b"]:'';
$telefonoTipo_id = isset($_POST["telefonoTipo_id"])?$_POST["telefonoTipo_id"]:'';
$telefono_numero = isset($_POST["telefono_numero"])?$_POST["telefono_numero"]:'';
$telefono_principal = isset($_POST["telefono_principal"])?$_POST["telefono_principal"]:'';
$email_id = isset($_POST["email_id"])?$_POST["email_id"]:'';
$email_id_e = isset($_POST["email_id_e"])?$_POST["email_id_e"]:'';
$email_id_m = isset($_POST["email_id_m"])?$_POST["email_id_m"]:'';
$email_id_b = isset($_POST["email_id_b"])?$_POST["email_id_b"]:'';
$emailTipo_id = isset($_POST["emailTipo_id"])?$_POST["emailTipo_id"]:'';
$email_email = isset($_POST["email_email"])?$_POST["email_email"]:'';
$email_principal = isset($_POST["email_principal"])?$_POST["email_principal"]:'';


// Toma el id de país para los select dependientes
$pais_id = isset($_POST["pais_id"])?$_POST["pais_id"]:'';
$ciudad = isset($_POST["ciudad"])?$_POST["ciudad"]:'';


// Toma el prestador_id para una baja
$prestador_id_b = isset($_POST["prestador_id_b"])?$_POST["prestador_id_b"]:'';


// Toma el prestador_id para volver a habilitarlo
$prestador_id_a = isset($_POST["prestador_id_a"])?$_POST["prestador_id_a"]:'';


// Busqueda
$prestador = isset($_POST["prestador"])?$_POST["prestador"]:'';
$prestador_nombre_buscar = isset($_POST["prestador_nombre_buscar"])?$_POST["prestador_nombre_buscar"]:'';
$prestador_tipoPrestador_id_buscar = isset($_POST["prestador_tipoPrestador_id_buscar"])?$_POST["prestador_tipoPrestador_id_buscar"]:'';
$prestador_pais_id_buscar = isset($_POST["prestador_pais_id_buscar"])?$_POST["prestador_pais_id_buscar"]:'';
$prestador_ciudad_id_buscar = isset($_POST["prestador_ciudad_id_buscar"])?$_POST["prestador_ciudad_id_buscar"]:'';



// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';


switch($opcion){
    
    // Acciones de los formularios   
    case 'formulario_alta':
        formulario_alta($prestador_nombre_n, 
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
                        $prestador_observaciones_n,
                        $telefonoTipo_id_n,
                        $telefono_numero_n,
                        $telefono_principal_n,   
                        $emailTipo_id_n,
                        $email_email_n,
                        $email_principal_n);
        
        break;
     
    case 'formulario_baja':
        formulario_baja($prestador_id_b);
        break;
    
    case 'formulario_habilita':
        formulario_habilita($prestador_id_a);
        break;
    
    case 'formulario_modificacion':
        formulario_modificacion($prestador_nombre,
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
                                $prestador_id);
        break;
    
    case 'formulario_lectura':
        formulario_lectura($prestador_id);
        break;
    
    // Case telefonos
    case 'listarTipoTelefonos_prestador':
        listarTipoTelefonos_prestador();
        break;
    
    case 'listarTipoTelefonos_modificacion':
        listarTipoTelefonos_modificacion($telefono_id_e);
        break;
    
    case 'telefono_guardar':
        telefono_guardar($telefonoTipo_id, $telefono_numero, $telefono_principal, $prestador_id);
        break;
    
    case 'telefono_modificar':
        telefono_modificar($telefonoTipo_id, $telefono_numero, $telefono_principal, $prestador_id, $telefono_id_m);
        break;
    
    case 'telefono_borrar':
        telefono_borrar($telefono_id_b);
        break;
    
    case 'telefono_editar':
        telefono_editar($telefono_id_e);
        break;
    
    // Case email
    case 'listarTipoEmail_prestador':
        listarTipoEmail_prestador();
        break;
    
    case 'listarTipoEmail_modificacion':
        listarTipoEmail_modificacion($email_id_e);
        break;
    
    case 'email_guardar':
        email_guardar($emailTipo_id, $email_email, $email_principal, $prestador_id);
        break;
    
    case 'email_modificar':
        email_modificar($emailTipo_id, $email_email, $email_principal, $prestador_id, $email_id_m);
        break;
    
    case 'email_borrar':
        email_borrar($email_id_b);
        break;
    
    case 'email_editar':
        email_editar($email_id_e);
        break;
    
    // Case grillas
    case 'grilla_listar':
        grilla_listar($prestador_nombre_buscar, 
                      $prestador_tipoPrestador_id_buscar, 
                      $prestador_pais_id_buscar,
                      $prestador_ciudad_id_buscar,
                      $permisos);
        break;
    
    case 'grilla_listar_contar':
        grilla_listar_contar($prestador_nombre_buscar, 
                             $prestador_tipoPrestador_id_buscar, 
                             $prestador_pais_id_buscar,
                             $prestador_ciudad_id_buscar);
        break;
    
    case 'grilla_telefonos':
        grilla_telefonos($prestador_id);
        break;
    
    case 'grilla_telefonos_v':
        grilla_telefonos_v($prestador_id);
        break;
    
    case 'grilla_emails':
        grilla_emails($prestador_id);
        break;
    
    case 'grilla_emails_v':
        grilla_emails_v($prestador_id);
        break;
     
    // Acciones auxiliares en el formulario
    case 'prestador_existe':
        prestador_existe($prestador_nombre_n);
        break;

    case 'prestador_existe_modificacion':
        prestador_existe_modificacion($prestador_nombre, $prestador_id);
        break;
    
    case 'formulario_alta_tipoPrestador':
        formulario_alta_tipoPrestador();
        break;
    
    case 'formulario_modificacion_tipoPrestador':
        formulario_modificacion_tipoPrestador($prestador_id);
        break;
    
    case 'formulario_alta_prestadorPrioridad':
        formulario_alta_prestadorPrioridad();
        break;
    
    case 'formulario_modificacion_prestadorPrioridad':
        formulario_modificacion_prestadorPrioridad($prestador_id);
        break;
    
    case 'formulario_alta_paises':
        formulario_alta_paises();
        break;

    case 'buscar_prestador_nombre':
        buscar_prestador_nombre($prestador);
        break;

    case 'select_ciudades':
        select_ciudades($ciudad, $pais_id);
        break;
    
    case 'formulario_modificacion_paises':
        formulario_modificacion_paises($prestador_id);
        break;    
 
    case 'formulario_alta_prestadorContrato':
        formulario_alta_prestadorContrato();
        break;
    
    case 'formulario_modificacion_prestadorContrato':
        formulario_modificacion_prestadorContrato($prestador_id);
        break;
    
    case 'listar_prestadores_select':
        listar_prestadores_select();
        break;
    
    case 'listar_prestadores_conRazonSocial_select':
        listar_prestadores_conRazonSocial_select();
        break;
    
    default:
        echo("Está mal seleccionada la funcion");        
}


// Funciones de Formulario

function formulario_alta($prestador_nombre_n, 
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
                         $prestador_observaciones_n,
                         $telefonoTipo_id_n,
                         $telefono_numero_n,
                         $telefono_principal_n,   
                         $emailTipo_id_n,
                         $email_email_n,
                         $email_principal_n){
   
    $prestador_id = Prestador::insertar($prestador_nombre_n, 
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
                                        $prestador_observaciones_n);
    
    
    // Insert de telefono - El if comprueba que se haya ingresado un telefono
    if(!empty($telefonoTipo_id_n) and !empty($telefonoTipo_id_n)){
        $telefono_entidad_id_n = $prestador_id;
        $telefono_entidad_tipo_n = Prestador::ENTIDAD;
        Telefono::insertar($telefonoTipo_id_n, $telefono_numero_n, $telefono_principal_n, $telefono_entidad_id_n, $telefono_entidad_tipo_n);
    }
    
    // Insert de e-mail - El if comprueba que se haya ingresado un e-mail
    if(!empty($emailTipo_id_n) and !empty($email_email_n)){
        $email_entidad_id_n = $prestador_id;
        $email_entidad_tipo_n = Prestador::ENTIDAD;
        Email::insertar($emailTipo_id_n, $email_email_n, $email_principal_n, $email_entidad_id_n, $email_entidad_tipo_n);
    }
}

function formulario_baja($prestador_id_b){
    
    $resultado = Prestador::borradoLogico($prestador_id_b);
    
    echo json_encode($resultado);    
}

function formulario_habilita($prestador_id_a){
    
    $resultado = Prestador::reActivar($prestador_id_a);
    
    echo json_encode($resultado);    
}

function formulario_modificacion($prestador_nombre,
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
                                $prestador_id){
    
   
    Prestador::actualizar($prestador_nombre, 
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
                        $prestador_id);

}

function formulario_lectura($prestador_id){
    
    $prestador = Prestador::buscar_por_id($prestador_id);
    
    echo json_encode($prestador);
}

//--------------------------------------- Operaciones con Teléfono -------------------------------------------
// Lista los tipos de telefono
function listarTipoTelefonos_prestador(){

    $tiposTelefonos = Telefono::listarTipoTelefonos(3);

    echo json_encode($tiposTelefonos);
}

function listarTipoTelefonos_modificacion($telefono_id_e){
            
    $tiposTelefonos = Telefono::listarTipoTelefonos_modificacion($telefono_id_e, 3);

    echo json_encode($tiposTelefonos);
}

// Inserta un teléfono nuevo
function telefono_guardar($telefonoTipo_id,
                          $telefono_numero, 
                          $telefono_principal, 
                          $prestador_id)
{
    
    $telefono_entidad_id = $prestador_id;
    $telefono_entidad_tipo = Prestador::ENTIDAD;
    
    $resultado = Telefono::insertar($telefonoTipo_id, $telefono_numero, $telefono_principal, $telefono_entidad_id, $telefono_entidad_tipo);
    
    $mierror = MiError::mostrarError($resultado);
    
    
    
    echo json_encode($mierror, JSON_UNESCAPED_UNICODE);
}


// Modifica un teléfono existente 
function telefono_modificar($telefonoTipo_id,
                            $telefono_numero, 
                            $telefono_principal, 
                            $prestador_id,
                            $telefono_id_m)
{
    
    $telefono_entidad_id = $prestador_id;
    $telefono_entidad_tipo = Prestador::ENTIDAD;
    
    $resultado = Telefono::modificar($telefonoTipo_id, $telefono_numero, $telefono_principal, $telefono_entidad_id, $telefono_entidad_tipo, $telefono_id_m);
    
    $error = MiError::mostrarError($resultado);
    
    echo json_encode($error, JSON_UNESCAPED_UNICODE);
}

    

// Borra un teléfono, lo hace en forma definitivo y no lógica
function telefono_borrar($telefono_id_b)
{    
    $resultado = Telefono::eliminar($telefono_id_b);
    
    $error = MiError::mostrarError($resultado);
    
    echo json_encode($error, JSON_UNESCAPED_UNICODE);
}

// Trae el teléfono para ser editado
function telefono_editar($telefono_id_e)
{
    $resultado = Telefono::listarPorId($telefono_id_e);
          
    echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
}



// Funciones de Grilla
function grilla_telefonos_v($prestador_id){

    $entidad_tipo = Prestador::ENTIDAD;
    
    $telefonos = Telefono::listarPorEntidadId($prestador_id,  $entidad_tipo);

    
    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>"; 
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<table id='dt_prestador' class='table table-hover table-striped m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Nombre</th>";
    $grilla .=                  "<th>Número</th>";
    $grilla .=                  "<th>Principal</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($telefonos as $telefono)
    {
        $telefono_id = $telefono["telefono_id"];
        $tipoTelefono_nombre = $telefono["tipoTelefono_nombre"];
        $telefono_numero = $telefono["telefono_numero"];
        $telefono_principal = $telefono["telefono_principal"];
        
        $grilla .=              "<tr>";
        $grilla .=                  "<td>";
        $grilla .=                      $tipoTelefono_nombre;
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


function grilla_telefonos($prestador_id){

    $entidad_tipo = Prestador::ENTIDAD;
    
    $telefonos = Telefono::listarPorEntidadId($prestador_id,  $entidad_tipo);

    
    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>"; 
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<table id='dt_prestador' class='table table-hover table-striped m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Nombre</th>";
    $grilla .=                  "<th>Número</th>";
    $grilla .=                  "<th>Principal</th>";
    $grilla .=                  "<th>Acciones</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($telefonos as $telefono)
    {
        $telefono_id = $telefono["telefono_id"];
        $tipoTelefono_nombre = $telefono["tipoTelefono_nombre"];
        $telefono_numero = $telefono["telefono_numero"];
        $telefono_principal = $telefono["telefono_principal"];
        
        $grilla .=              "<tr>";
        $grilla .=                  "<td>";
        $grilla .=                      $tipoTelefono_nombre;
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


//--------------------------------------- Operaciones con E-mails -------------------------------------------
// Lista los tipos de email
function listarTipoEmail_prestador(){

    $tiposEmail = Email::listarTipoEmail(3);

    echo json_encode($tiposEmail);
}

function listarTipoEmail_modificacion($email_id_e){
            
    $tiposEmail = Email::listarTipoEmail_modificacion($email_id_e, 3);

    echo json_encode($tiposEmail);
}


// Inserta un email nuevo
function email_guardar($emailTipo_id, $email_email, $email_principal, $prestador_id) {
    
    $email_entidad_id = $prestador_id;
    $email_entidad_tipo = Prestador::ENTIDAD;
    
    $resultado = Email::insertar($emailTipo_id, $email_email, $email_principal, $email_entidad_id, $email_entidad_tipo);
    
    $error = MiError::mostrarError($resultado);
    
    echo json_encode($error, JSON_UNESCAPED_UNICODE);
}


// Modifica un teléfono existente 
function email_modificar($emailTipo_id, $email_email, $email_principal, $prestador_id, $email_id_m) {
    
    $email_entidad_id = $prestador_id;
    $email_entidad_tipo = Prestador::ENTIDAD;
    
    $resultado = Email::modificar($emailTipo_id, $email_email, $email_principal, $email_entidad_id, $email_entidad_tipo, $email_id_m);
    
    $error = MiError::mostrarError($resultado);
    
    echo json_encode($error, JSON_UNESCAPED_UNICODE);
}

    

// Borra un e-mail, lo hace en forma definitivo y no lógica
function email_borrar($email_id_b) {    
    $resultado = Email::eliminar($email_id_b);
    
    $error = MiError::mostrarError($resultado);
    
    echo json_encode($error, JSON_UNESCAPED_UNICODE);
}

// Trae el e-mail para ser editado
function email_editar($email_id_e) {
    
    $resultado = Email::listarPorId($email_id_e);
          
    echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
}


// Funciones de Grilla
function grilla_emails_v($prestador_id){

    $entidad_tipo = Prestador::ENTIDAD;
    
    $emails = Email::listarPorEntidadId($prestador_id, $entidad_tipo);

    
    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>"; 
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<table id='dt_prestador' class='table table-hover m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Nombre</th>";
    $grilla .=                  "<th>Email</th>";
    $grilla .=                  "<th>Principal</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($emails as $email)
    {
        $email_id = $email["email_id"];
        $tipoEmail_nombre = $email["tipoEmail_nombre"];
        $email_email = $email["email_email"];
        $email_principal = $email["email_principal"];
        
        $grilla .=              "<tr>";
        $grilla .=                  "<td>";
        $grilla .=                      $tipoEmail_nombre;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $email_email;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        if($email_principal == 1){
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

function grilla_emails($prestador_id){

    $entidad_tipo = Prestador::ENTIDAD;
    
    $emails = Email::listarPorEntidadId($prestador_id, $entidad_tipo);

    
    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>"; 
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<table id='dt_prestador' class='table table-hover m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Nombre</th>";
    $grilla .=                  "<th>Email</th>";
    $grilla .=                  "<th>Principal</th>";
    $grilla .=                  "<th>Acciones</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($emails as $email)
    {
        $email_id = $email["email_id"];
        $tipoEmail_nombre = $email["tipoEmail_nombre"];
        $email_email = $email["email_email"];
        $email_principal = $email["email_principal"];
        
        $grilla .=              "<tr>";
        $grilla .=                  "<td>";
        $grilla .=                      $tipoEmail_nombre;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $email_email;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        if($email_principal == 1){
            $grilla .=                 "<span class='label label-primary'>Principal</span>";
        }else{
            $grilla .=                 "<span class='label label-default'>Secundario</span>";
        }
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      "<a href='javascript:void(0)'> <i onclick='email_editar($email_id)' class='fa fa-edit'></i></a>";
        $grilla .=                      "<a href='javascript:void(0)'> <i onclick='modal_email_borrar($email_id)' class='fa fa-trash'></i></a>";
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

// ------------------------------------------------ Fin operaciones E-mails -------------------------------------------


// Funciones auxiliares de formulario

    // Funciones para llenar los Select
    function formulario_alta_tipoPrestador(){

        $tiposPrestadores = TipoPrestador::formulario_alta_tipoPrestador();

        echo json_encode($tiposPrestadores);   
    }
    
    function formulario_modificacion_tipoPrestador($prestador_id){

        $tiposPrestadores = TipoPrestador::formulario_modificacion_tipoPrestador($prestador_id);

        echo json_encode($tiposPrestadores);   
    }
    
    function formulario_alta_prestadorPrioridad(){

        $prestadorPrioridades = Prestador::formulario_alta_prestadorPrioridad();

        echo json_encode($prestadorPrioridades);   
    }
    
    function formulario_modificacion_prestadorPrioridad($prestador_id) {

        $prestadorPrioridades = Prestador::formulario_modificacion_prestadorPrioridad($prestador_id);

        echo json_encode($prestadorPrioridades);   
    }

    function formulario_alta_paises(){

        $paises = Pais::formulario_alta_paises();

        echo json_encode($paises);   
    }
    
    function formulario_modificacion_paises($prestador_id) {

        $paises = Pais::formulario_modificacion_prestador_paises($prestador_id);

        echo json_encode($paises);   
    }

    function buscar_prestador_nombre($prestador) {

        $prestadores = Prestador::buscar_por_nombre($prestador);

        $data = array();
        foreach ($prestadores as $prestador) {
            $name = $prestador['prestador_nombre'] . '|' . $prestador['prestador_id'];
            array_push($data, $name);	
        }	

        echo json_encode($data);
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
    
    function formulario_alta_prestadorContrato() {

        $prestadorContratos = Prestador::formulario_alta_prestadorContrato();

        echo json_encode($prestadorContratos);   
    }
    
    function formulario_modificacion_prestadorContrato($prestador_id) {

        $prestadorContratos = Prestador::formulario_modificacion_prestadorContrato($prestador_id);

        echo json_encode($prestadorContratos);   
    }

    
    function prestador_existe($prestador_nombre_n) {

        $prestador_existente = Prestador::existe($prestador_nombre_n);

        if($prestador_existente == 1) {
           echo(json_encode("El tipo de prestador ingresado ya existe"));
        }else{    
           echo(json_encode("true"));
        }
    }

    function prestador_existe_modificacion($prestador_nombre, $prestador_id) {

        $prestador_existente = Prestador::existeUpdate($prestador_nombre, $prestador_id);

        if($prestador_existente == 1) {
           echo(json_encode("El tipo de prestador ingresado ya existe"));
        }else{
           echo(json_encode("true"));
        }
    }
    
    function listar_prestadores_select() {
        
        $prestadores = Prestador::listar_prestadores_select();
        
        echo json_encode($prestadores);
    }
    
    function listar_prestadores_conRazonSocial_select() {
        
        $prestadores = Prestador::listar_prestadores_conRazonSocial_select();
        
        echo json_encode($prestadores);
    }


// Funciones de Grilla
    
function grilla_listar_contar($prestador_nombre_buscar, 
                              $prestador_tipoPrestador_id_buscar, 
                              $prestador_pais_id_buscar,
                              $prestador_ciudad_id_buscar){
    
    $prestadores = Prestador::listar_filtrado_contar($prestador_nombre_buscar, 
                                                     $prestador_tipoPrestador_id_buscar, 
                                                     $prestador_pais_id_buscar,
                                                     $prestador_ciudad_id_buscar);

    $cantidad = $prestadores['registros'];
    If ($cantidad > 50){
        $texto = "<i class='fa fa-exclamation-circle'></i>   Se han encontrado " . $cantidad . " registros. Se muestran sólo los primeros 200 resultados. Por favor refine su búsqueda.";
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


function grilla_listar($prestador_nombre_buscar, 
                       $prestador_tipoPrestador_id_buscar, 
                       $prestador_pais_id_buscar,
                       $prestador_ciudad_id_buscar,
                       $permisos){
    
    $prestadores = Prestador::listar_filtrado($prestador_nombre_buscar, 
                                              $prestador_tipoPrestador_id_buscar, 
                                              $prestador_pais_id_buscar,
                                              $prestador_ciudad_id_buscar);

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Tabla de prestadores</b></h4>";
    $grilla .=      "<table id='dt_prestador' class='table table-hover m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Nombre</th>";
    $grilla .=                  "<th>Prestador tipo</th>";
    $grilla .=                  "<th>Direccion</th>";
    $grilla .=                  "<th>Pais</th>";
    $grilla .=                  "<th>Ciudad</th>";
    $grilla .=                  "<th>Prioridad</th>";
    $grilla .=                  "<th>Activo</th>";
    $grilla .=                  "<th>Acciones</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($prestadores as $prestador){
            $prestador_id = $prestador["prestador_id"];
            $prestador_nombre = $prestador["prestador_nombre"];
            $prestadorTipo_nombre = $prestador["tipoPrestador_nombre"];
            $prestador_direccion = $prestador["prestador_direccion"];    
            $prestadorPais_nombre = $prestador["pais_nombreEspanol"];
            $prestadorCiudad_nombre = $prestador["ciudad_nombre"];
            $prestadorPrioridad_nombre = $prestador["prestadorPrioridad_nombre"];
            $prestador_activo = $prestador["prestador_activo"];
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    $grilla .=                      $prestador_nombre;
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
    $grilla .=                      $prestadorPrioridad_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    if($prestador_activo == 1){
    $grilla .=                 "<span class='label label-success'>Activo</span>";
    }else{
    $grilla .=                 "<span class='label label-danger'>Inactivo</span>";
    }
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      "<a href='javascript:void(0)'> <i onclick='formulario_lectura($prestador_id)' class='fa fa-edit'></i></a>";
    
    $prestadores_baja = array_search('prestadores_baja', array_column($permisos, 'permiso_variable'));
    if (!empty($prestadores_baja) || ($prestadores_baja === 0)) {
        if($prestador_activo == 1){
        $grilla .=                      "<a href='javascript:void(0)'> <i onclick='modal_baja($prestador_id)' class='fa fa-user-times'></i></a>";
        }else{
        $grilla .=                      "<a href='javascript:void(0)'> <i onclick='modal_alta($prestador_id)' class='fa fa-user-plus'></i></a>";
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