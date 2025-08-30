<?php
require '../seguridad/seguridad.php';
include_once '../includes/herramientas.php';


// Toma las variables del formulario de alta
$cliente_nombre_n = isset($_POST["cliente_nombre_n"])?$_POST["cliente_nombre_n"]:'';
$cliente_razonSocial_n = isset($_POST["cliente_razonSocial_n"])?$_POST["cliente_razonSocial_n"]:'';
$cliente_abreviatura_n = isset($_POST["cliente_abreviatura_n"])?$_POST["cliente_abreviatura_n"]:'';
$cliente_tipoCliente_id_n = isset($_POST["cliente_tipoCliente_id_n"])?$_POST["cliente_tipoCliente_id_n"]:'';
$cliente_paginaWeb_n = isset($_POST["cliente_paginaWeb_n"])?$_POST["cliente_paginaWeb_n"]:'';
$cliente_direccion_n = isset($_POST["cliente_direccion_n"])?$_POST["cliente_direccion_n"]:'';
$cliente_codigoPostal_n = isset($_POST["cliente_codigoPostal_n"])?$_POST["cliente_codigoPostal_n"]:'';
$cliente_pais_id_n = isset($_POST["cliente_pais_id_n"])?$_POST["cliente_pais_id_n"]:'';
$cliente_ciudad_id_n = isset($_POST["cliente_ciudad_id_n_2"])?$_POST["cliente_ciudad_id_n_2"]:'';
$cliente_observaciones_n = isset($_POST["cliente_observaciones_n"])?$_POST["cliente_observaciones_n"]:'';

// Formulario telefonos + e-mail (Alta)
$telefonoTipo_id_n = isset($_POST["telefonoTipo_id_n"])?$_POST["telefonoTipo_id_n"]:'';
$telefono_numero_n = isset($_POST["telefono_numero_n"])?$_POST["telefono_numero_n"]:'';
$telefono_principal_n = isset($_POST["telefono_principal_n"])?$_POST["telefono_principal_n"]:'';   
$emailTipo_id_n = isset($_POST["emailTipo_id_n"])?$_POST["emailTipo_id_n"]:'';
$email_email_n = isset($_POST["email_email_n"])?$_POST["email_email_n"]:'';
$email_principal_n = isset($_POST["email_principal_n"])?$_POST["email_principal_n"]:'';


// Toma las variables del formulario de modificación
$cliente_id = isset($_POST["cliente_id"])?$_POST["cliente_id"]:'';
$cliente_nombre = isset($_POST["cliente_nombre"])?$_POST["cliente_nombre"]:'';
$cliente_razonSocial = isset($_POST["cliente_razonSocial"])?$_POST["cliente_razonSocial"]:'';
$cliente_abreviatura = isset($_POST["cliente_abreviatura"])?$_POST["cliente_abreviatura"]:'';
$cliente_tipoCliente_id = isset($_POST["cliente_tipoCliente_id"])?$_POST["cliente_tipoCliente_id"]:'';
$cliente_paginaWeb = isset($_POST["cliente_paginaWeb"])?$_POST["cliente_paginaWeb"]:'';
$cliente_direccion = isset($_POST["cliente_direccion"])?$_POST["cliente_direccion"]:'';
$cliente_codigoPostal = isset($_POST["cliente_codigoPostal"])?$_POST["cliente_codigoPostal"]:'';
$cliente_pais_id = isset($_POST["cliente_pais_id"])?$_POST["cliente_pais_id"]:'';
$cliente_ciudad_id = isset($_POST["cliente_ciudad_id_2"])?$_POST["cliente_ciudad_id_2"]:'';
$cliente_observaciones = isset($_POST["cliente_observaciones"])?$_POST["cliente_observaciones"]:'';


// Formulario telefono
$telefono_id = isset($_POST["telefono_id"])?$_POST["telefono_id"]:'';
$telefono_id_e = isset($_POST["telefono_id_e"])?$_POST["telefono_id_e"]:'';
$telefono_id_m = isset($_POST["telefono_id_m"])?$_POST["telefono_id_m"]:'';
$telefono_id_b = isset($_POST["telefono_id_b"])?$_POST["telefono_id_b"]:'';
$telefonoTipo_id = isset($_POST["telefonoTipo_id"])?$_POST["telefonoTipo_id"]:'';
$telefono_numero = isset($_POST["telefono_numero"])?$_POST["telefono_numero"]:'';
$telefono_principal = isset($_POST["telefono_principal"])?$_POST["telefono_principal"]:'';


// Formulario e-mails
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


// Toma el cliente_id para una baja
$cliente_id_b = isset($_POST["cliente_id_b"])?$_POST["cliente_id_b"]:'';


// Toma el cliente_id para volver a habilitarlo
$cliente_id_a = isset($_POST["cliente_id_a"])?$_POST["cliente_id_a"]:'';


// Busqueda
$cliente_nombre_buscar = isset($_POST["cliente_nombre_buscar"])?$_POST["cliente_nombre_buscar"]:'';
$cliente_pais_id_buscar = isset($_POST["cliente_pais_id_buscar"])?$_POST["cliente_pais_id_buscar"]:'';
$cliente_tipoCliente_id_buscar = isset($_POST["cliente_tipoCliente_id_buscar"])?$_POST["cliente_tipoCliente_id_buscar"]:'';


// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';


switch($opcion){
    
    // Acciones de los formularios
       
    case 'formulario_alta':
        formulario_alta($cliente_nombre_n, 
                        $cliente_razonSocial_n, 
                        $cliente_abreviatura_n,
                        $cliente_tipoCliente_id_n,
                        $cliente_paginaWeb_n,
                        $cliente_direccion_n,
                        $cliente_codigoPostal_n,
                        $cliente_pais_id_n, 
                        $cliente_ciudad_id_n, 
                        $cliente_observaciones_n,
                        $telefonoTipo_id_n,
                        $telefono_numero_n,
                        $telefono_principal_n,   
                        $emailTipo_id_n,
                        $email_email_n,
                        $email_principal_n);
        break;   
    
    case 'formulario_baja':
        formulario_baja($cliente_id_b);
        break;
    
    case 'formulario_habilita':
        formulario_habilita($cliente_id_a);
        break;
    
    case 'formulario_modificacion':
        formulario_modificacion($cliente_nombre,
                                $cliente_razonSocial, 
                                $cliente_abreviatura,
                                $cliente_tipoCliente_id,
                                $cliente_paginaWeb,
                                $cliente_direccion,
                                $cliente_codigoPostal,
                                $cliente_pais_id, 
                                $cliente_ciudad_id, 
                                $cliente_observaciones, 
                                $cliente_id);
        break;
    
    case 'formulario_modifica':
        formulario_modifica($cliente_id);
        break;


    case 'formulario_ver':
        formulario_ver($cliente_id);
        break;
    
    // Case Telefonos
    case 'listarTipoTelefonos_cliente':
        listarTipoTelefonos_cliente();
        break;
    
    case 'listarTipoTelefonos_modificacion':
        listarTipoTelefonos_modificacion($telefono_id_e);
        break;
    
    case 'telefono_guardar':
        telefono_guardar($telefonoTipo_id,
                         $telefono_numero, 
                         $telefono_principal, 
                         $cliente_id);
        break;
    
    case 'telefono_modificar':
        telefono_modificar($telefonoTipo_id,
                           $telefono_numero, 
                           $telefono_principal, 
                           $cliente_id,
                           $telefono_id_m);
        break;
    
    case 'telefono_borrar':
        telefono_borrar($telefono_id_b);
        break;
    
    case 'telefono_editar':
        telefono_editar($telefono_id_e);
        break;
    
    // Case email
    case 'listarTipoEmail_cliente':
        listarTipoEmail_cliente();
        break;
    
    case 'listarTipoEmail_modificacion':
        listarTipoEmail_modificacion($email_id_e);
        break;
    
    case 'email_guardar':
        email_guardar($emailTipo_id, $email_email, $email_principal, $cliente_id);
        break;
    
    case 'email_modificar':
        email_modificar($emailTipo_id, $email_email, $email_principal, $cliente_id, $email_id_m);
        break;
    
    case 'email_borrar':
        email_borrar($email_id_b);
        break;
    
    case 'email_editar':
        email_editar($email_id_e);
        break;
    
    // Case Grillas
    case 'grilla_listar':
        grilla_listar($cliente_nombre_buscar, 
                      $cliente_pais_id_buscar,
                      $cliente_tipoCliente_id_buscar,
                      $permisos);
        break;
    
    case 'grilla_listar_contar':
        grilla_listar_contar($cliente_nombre_buscar, 
                             $cliente_pais_id_buscar,
                             $cliente_tipoCliente_id_buscar);
        break;
     
    case 'grilla_telefonos':
       grilla_telefonos($cliente_id);
       break;
   
   case 'grilla_telefonos_v':
        grilla_telefonos_v($cliente_id);
        break;

    case 'grilla_emails':
        grilla_emails($cliente_id);
        break;   
    
    case 'grilla_emails_v':
        grilla_emails_v($cliente_id);
        break;
    
// Acciones auxiliares en el formulario

    case 'cliente_existe':
        cliente_existe($cliente_nombre_n);
        break;

    case 'cliente_existe_modificacion':
        cliente_existe_modificacion($cliente_nombre, $cliente_id);
        break;
    
    case 'formulario_alta_tipoCliente':
        formulario_alta_tipoCliente();
        break;
    
    case 'formulario_modificacion_tipoCliente':
        formulario_modificacion_tipoCliente($cliente_id);
        break;
    
    case 'formulario_alta_paises':
        formulario_alta_paises();
        break;

    case 'formulario_modificacion_paises':
        formulario_modificacion_paises($cliente_id);
        break;    
 
    case 'select_ciudades':
        select_ciudades($ciudad, $pais_id);
        break;
    
       
    default:
        echo("Está mal seleccionada la funcion");
        
        
}


// Funciones de Formulario

function formulario_alta($cliente_nombre_n, 
                         $cliente_razonSocial_n, 
                         $cliente_abreviatura_n,
                         $cliente_tipoCliente_id_n,
                         $cliente_paginaWeb_n,
                         $cliente_direccion_n,
                         $cliente_codigoPostal_n,
                         $cliente_pais_id_n, 
                         $cliente_ciudad_id_n, 
                         $cliente_observaciones_n,
                         $telefonoTipo_id_n,
                         $telefono_numero_n,
                         $telefono_principal_n,   
                         $emailTipo_id_n,
                         $email_email_n,
                         $email_principal_n){   
   
    $cliente_id = Cliente::insertar($cliente_nombre_n, 
                                    $cliente_razonSocial_n, 
                                    $cliente_abreviatura_n,
                                    $cliente_tipoCliente_id_n,
                                    $cliente_paginaWeb_n,
                                    $cliente_direccion_n,
                                    $cliente_codigoPostal_n,
                                    $cliente_pais_id_n, 
                                    $cliente_ciudad_id_n, 
                                    $cliente_observaciones_n);
        
                
    // Insert de telefono - El if comprueba que se haya ingresado un telefono
    if(!empty($telefonoTipo_id_n) and !empty($telefonoTipo_id_n)){
        $telefono_entidad_id_n = $cliente_id;
        $telefono_entidad_tipo_n = CLIENTE::ENTIDAD;
        Telefono::insertar($telefonoTipo_id_n, $telefono_numero_n, $telefono_principal_n, $telefono_entidad_id_n, $telefono_entidad_tipo_n);
    }
    
    // Insert de e-mail - El if comprueba que se haya ingresado un e-mail
    if(!empty($emailTipo_id_n) and !empty($email_email_n)){
        $email_entidad_id_n = $cliente_id;
        $email_entidad_tipo_n = CLIENTE::ENTIDAD;
        Email::insertar($emailTipo_id_n, $email_email_n, $email_principal_n, $email_entidad_id_n, $email_entidad_tipo_n);
    }
}

function formulario_baja($cliente_id_b){
    
    $resultado = Cliente::borradoLogico($cliente_id_b);
    
    echo json_encode($resultado);    
}

function formulario_habilita($cliente_id_a){
    
    $resultado = Cliente::reActivar($cliente_id_a);
    
    echo json_encode($resultado);    
}

function formulario_modificacion($cliente_nombre,
                                 $cliente_razonSocial, 
                                 $cliente_abreviatura,
                                 $cliente_tipoCliente_id,
                                 $cliente_paginaWeb,
                                 $cliente_direccion,
                                 $cliente_codigoPostal,
                                 $cliente_pais_id, 
                                 $cliente_ciudad_id, 
                                 $cliente_observaciones,
                                 $cliente_id){
   
    
            Cliente::actualizar($cliente_nombre, 
                                $cliente_razonSocial, 
                                $cliente_abreviatura,
                                $cliente_tipoCliente_id,
                                $cliente_paginaWeb,
                                $cliente_direccion,
                                $cliente_codigoPostal,
                                $cliente_pais_id, 
                                $cliente_ciudad_id, 
                                $cliente_observaciones,
                                $cliente_id);

}

function formulario_ver($cliente_id){
    
    $cliente = Cliente::buscarPorId($cliente_id);
    
    echo json_encode($cliente);
}


function formulario_modifica($cliente_id){
    
    $cliente = Cliente::buscarPorId($cliente_id);
    
    echo json_encode($cliente);
}



//---------------------------------------Operaciones con Teléfono-------------------------------------------
// Lista los tipos de telefono
function listarTipoTelefonos_cliente(){

            $tiposTelefonos = Telefono::listarTipoTelefonos(1);

            echo json_encode($tiposTelefonos);
        }
        
function listarTipoTelefonos_modificacion($telefono_id_e){
            
    $tiposTelefonos = Telefono::listarTipoTelefonos_modificacion($telefono_id_e, 1);

    echo json_encode($tiposTelefonos);
}

// Inserta un teléfono nuevo
function telefono_guardar($telefonoTipo_id,
                          $telefono_numero, 
                          $telefono_principal, 
                          $cliente_id)
{
    
    $telefono_entidad_id = $cliente_id;
    $telefono_entidad_tipo = CLIENTE::ENTIDAD;
    
    $resultado = Telefono::insertar($telefonoTipo_id, $telefono_numero, $telefono_principal, $telefono_entidad_id, $telefono_entidad_tipo);
    
    $error = MiError::mostrarError($resultado);
    
    echo json_encode($error, JSON_UNESCAPED_UNICODE);
}


// Modifica un teléfono existente 
function telefono_modificar($telefonoTipo_id,
                            $telefono_numero, 
                            $telefono_principal, 
                            $cliente_id,
                            $telefono_id_m)
{
    
    $telefono_entidad_id = $cliente_id;
    $telefono_entidad_tipo = CLIENTE::ENTIDAD;
    
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

function grilla_telefonos_v($cliente_id){
    
    $entidad_tipo = CLIENTE::ENTIDAD;
    
    $telefonos = Telefono::listarPorEntidadId($cliente_id, $entidad_tipo);

    
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

function grilla_telefonos($cliente_id){
    
    $entidad_tipo = CLIENTE::ENTIDAD;
    
    $telefonos = Telefono::listarPorEntidadId($cliente_id, $entidad_tipo);

    
    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>"; 
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<table class='table table-hover table-striped m-0 table-responsive'>";
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

// ------------------------------------------------ Fin operaciones Teléfonos ------------------------------------------------------------------------------


//--------------------------------------- Operaciones con E-mails -------------------------------------------
// Lista los tipos de email
function listarTipoEmail_cliente(){

    $tiposEmail = Email::listarTipoEmail(1);

    echo json_encode($tiposEmail);
}

function listarTipoEmail_modificacion($email_id_e){
            
    $tiposEmail = Email::listarTipoEmail_modificacion($email_id_e, 1);

    echo json_encode($tiposEmail);
}

// Inserta un email nuevo
function email_guardar($emailTipo_id, $email_email, $email_principal, $cliente_id) {
    
    $email_entidad_id = $cliente_id;
    $email_entidad_tipo = CLIENTE::ENTIDAD;
    
    $resultado = Email::insertar($emailTipo_id, $email_email, $email_principal, $email_entidad_id, $email_entidad_tipo);
    
    $error = MiError::mostrarError($resultado);
    
    echo json_encode($error, JSON_UNESCAPED_UNICODE);
}


// Modifica un teléfono existente 
function email_modificar($emailTipo_id, $email_email, $email_principal, $cliente_id, $email_id_m) {
    
    $email_entidad_id = $cliente_id;
    $email_entidad_tipo = CLIENTE::ENTIDAD;
    
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
function grilla_emails_v($cliente_id){
    
    $entidad_tipo = CLIENTE::ENTIDAD;
    
    $emails = Email::listarPorEntidadId($cliente_id, $entidad_tipo);

    
    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>"; 
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<table class='table table-hover table-striped m-0 table-responsive'>";
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

function grilla_emails($cliente_id){
    
    $entidad_tipo = CLIENTE::ENTIDAD;
    
    $emails = Email::listarPorEntidadId($cliente_id, $entidad_tipo);

    
    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>"; 
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<table class='table table-hover table-striped m-0 table-responsive'>";
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

    function formulario_alta_tipoCliente(){

        $tiposClientes = Cliente::listar_tiposClientes_alta_Cliente();

        echo json_encode($tiposClientes);   
    }
    
    function formulario_modificacion_tipoCliente($cliente_id){

        $tiposClientes = Cliente::listar_tiposClientes_modificacion_Cliente($cliente_id);

        echo json_encode($tiposClientes);   
    }

    function formulario_alta_paises(){

        $paises = Pais::formulario_alta_paises();

        echo json_encode($paises);   
    }
    
    function formulario_modificacion_paises($cliente_id){

        $paises = Pais::formulario_modificacion_cliente_paises($cliente_id);

        echo json_encode($paises);   
    }

    function select_ciudades($ciudad, $pais_id){

        $ciudades = Ciudad::buscar_select($ciudad, $pais_id);

        $data = array();
        foreach ($ciudades as $ciudad) {
            $name = $ciudad['ciudad_nombre'] . '|' . $ciudad['ciudad_id'];
            array_push($data, $name);	
        }	

        echo json_encode($data);

    }
    
          
    function cliente_existe($cliente_nombre_n){
 
        $cliente_existente = Cliente::existe($cliente_nombre_n);

        if($cliente_existente == 1) {
           echo(json_encode("El tipo de cliente ingresado ya existe"));
        }else{    
           echo(json_encode("true"));
        }
    
    }

    function cliente_existe_modificacion($cliente_nombre, $cliente_id){

        $cliente_existente = Cliente::existeUpdate($cliente_nombre, $cliente_id);

        if($cliente_existente == 1) {

           echo(json_encode("El tipo de cliente ingresado ya existe"));

        }else{

           echo(json_encode("true"));

        }
    }


// Funciones de Grilla
    
function grilla_listar_contar($cliente_nombre_buscar, 
                              $cliente_pais_id_buscar,
                              $cliente_tipoCliente_id_buscar){
    $clientes = Cliente::listar_filtrado_contar($cliente_nombre_buscar, 
                                                $cliente_pais_id_buscar,
                                                $cliente_tipoCliente_id_buscar);

    $cantidad = $clientes['registros'];
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

function grilla_listar($cliente_nombre_buscar, 
                       $cliente_pais_id_buscar,
                       $cliente_tipoCliente_id_buscar,
                       $permisos)
{
    
    $clientes = Cliente::listar_filtrado($cliente_nombre_buscar, 
                                         $cliente_pais_id_buscar,
                                         $cliente_tipoCliente_id_buscar);

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Tabla de clientes</b></h4>";
    $grilla .=      "<table id='dt_cliente' class='table table-hover table-striped m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Nombre</th>";
    $grilla .=                  "<th>Cliente tipo</th>";
    $grilla .=                  "<th>Abreviatura</th>";
    $grilla .=                  "<th>Direccion</th>";
    $grilla .=                  "<th>País</th>";
    $grilla .=                  "<th>Ciudad</th>";
    $grilla .=                  "<th>Activo</th>";
    $grilla .=                  "<th>Acciones</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($clientes as $cliente){
            $cliente_id = $cliente["cliente_id"];
            $cliente_nombre = $cliente["cliente_nombre"];
            $cliente_tipoCliente = $cliente["tipoCliente_nombre"];
            $cliente_abreviatura = $cliente["cliente_abreviatura"];
            $cliente_direccion = $cliente["cliente_direccion"];    
            $clientePais_nombre = $cliente["pais_nombreEspanol"];
            $clienteCiudad_nombre = $cliente["ciudad_nombre"];
            $cliente_activo = $cliente["cliente_activo"];
            $cliente_modifica = $cliente["cliente_modifica"];
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    $grilla .=                      $cliente_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $cliente_tipoCliente;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $cliente_abreviatura;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $cliente_direccion;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $clientePais_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $clienteCiudad_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    if($cliente_activo == 1){
    $grilla .=                 "<span class='label label-success'>Activo</span>";
    }else{
    $grilla .=                 "<span class='label label-danger'>Inactivo</span>";
    }
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    if($cliente_modifica == 1){
        $grilla .=                      "<a href='javascript:void(0)'> <i onclick='formulario_ver($cliente_id)' class='fa fa-edit'></i></a>";
    
        $clientes_baja = array_search('clientes_baja', array_column($permisos, 'permiso_variable'));
        if (!empty($clientes_baja) || ($clientes_baja === 0)) {
            if($cliente_activo == 1){
            $grilla .=                      "<a href='javascript:void(0)'> <i onclick='modal_baja($cliente_id)' class='fa fa-trash'></i></a>";
            }else{
            $grilla .=                      "<a href='javascript:void(0)'> <i onclick='modal_alta($cliente_id)' class='fa fa-user-plus'></i></a>";
            }
        }
    }else{
        $grilla .=                      "<a href='javascript:void(0)'> <i onclick='formulario_ver($cliente_id)' class='fa fa-search'></i></a>";
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