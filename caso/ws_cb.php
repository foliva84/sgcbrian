<?php

require '../seguridad/seguridad.php';
include_once '../includes/herramientas.php';

// Toma las variables del formulario de búsqueda
$voucher_number = isset($_POST["voucher_number"])?$_POST["voucher_number"]:'';
$passenger_first_name = isset($_POST["passenger_first_name"])?$_POST["passenger_first_name"]:'';
$passenger_last_name = isset($_POST["passenger_last_name"])?$_POST["passenger_last_name"]:'';
$passenger_document_number = isset($_POST["passenger_document_number"])?$_POST["passenger_document_number"]:'';
$sistema_emision = isset($_POST["sistema_emision"])?$_POST["sistema_emision"]:'';

// Sirve para validar de que formulario viene la consulta al WS. Alta Caso = 1 / Modificacion Caso = 2
$opcion_caso = isset($_POST["opcion_caso"])?$_POST["opcion_caso"]:'';

// Pasar los datos al caso
$voucher_numero = isset($_POST["voucher_numero"])?$_POST["voucher_numero"]:'';       

$voucher_number_b = isset($_POST["voucher_number_b"])?$_POST["voucher_number_b"]:'';

// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';


switch($opcion){
    
    case 'select_sistema_emision':
        select_sistema_emision();
    break;
    
    // Acciones de los formularios
    case 'buscar_voucher':
        buscar_voucher($voucher_number, $passenger_first_name, $passenger_last_name, $passenger_document_number, $sistema_emision, $opcion_caso);
        break;
    
    case 'mostrar_voucher':
        mostrar_voucher($voucher_number, $sistema_emision);
        break;

     case 'caso_completar_voucher':
        caso_completar_voucher($voucher_numero, $sistema_emision);
        break;
    
    default:
       echo("Está mal seleccionada la funcion");  
}


// Funciones de Formulario

// Este select completa los sistemas de emisión utilizados
function select_sistema_emision(){
    
    $sistema_emision = SistemaEmision::listar();
    
    echo json_encode($sistema_emision);
}



// Grilla para generar el Listado de los voucher consultados por WS
function buscar_voucher($voucher_number, $passenger_first_name, $passenger_last_name, $passenger_document_number, $sistema_emision, $opcion_caso) {
    
    $vouchers_ws = WS::buscar_ws($voucher_number, $passenger_first_name, $passenger_last_name, $passenger_document_number, $sistema_emision);
    $vouchers = json_decode($vouchers_ws, true);

    
        $grilla = "<div class='row'>";
        $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
        $grilla .=  "<div class='card-box-form table-responsive'>";
        $grilla .=      "<h4 class='m-t-0 header-title'><b>Vouchers obtenidos</b></h4>";
        $grilla .=      "<table id='dt_voucher' class='table table-hover m-0 table-responsive'>";
        $grilla .=          "<thead>";
        $grilla .=              "<tr>";
        $grilla .=                  "<th>Voucher</th>";
        $grilla .=                  "<th>Producto</th>";
        $grilla .=                  "<th>Nombre</th>";
        $grilla .=                  "<th>Apellido</th>";
        $grilla .=                  "<th>Acciones</th>";
        $grilla .=              "</tr>";
        $grilla .=          "</thead>";
        $grilla .=          "<tbody>";  
    
    
        if(is_array($vouchers)){
            
            foreach($vouchers as $voucher){
            
                if (is_array($voucher)){
                    
                    foreach($voucher as $atributo){
                    
                        //saco el valor de cada elemento
                        $numero_voucher_b = "'" . $atributo['voucher_number'] . "'";
                        $numero_voucher = $atributo['voucher_number'];
                        $producto = $atributo['product_name'];
                        $nombre = $atributo['passenger_first_name'];
                        $apellido =  $atributo['passenger_last_name'];
                        $producto_nombre = $atributo['product_name'];
    
                        $grilla .=              "<tr>";
                        $grilla .=                  "<td>";
                        $grilla .=                      $numero_voucher;
                        $grilla .=                  "</td>";
                        $grilla .=                  "<td>";
                        $grilla .=                      $producto;
                        $grilla .=                  "</td>";
                        $grilla .=                  "<td>";
                        $grilla .=                      $nombre;
                        $grilla .=                  "</td>";
                        $grilla .=                  "<td>";
                        $grilla .=                      $apellido;
                        $grilla .=                  "</td>";
                        $grilla .=                  "<td>";
                        if ($opcion_caso == 1) {
                            $grilla .=                      '<a href="#"> <i onclick="javascript:datos_ws_alta_caso(' . ($numero_voucher_b) . ',' . ($sistema_emision) .')" class="fa fa-user-plus" data-toggle="tooltip" data-placement="top" title="Seleccionar pax"></i></a>';
                        } else if ($opcion_caso == 2) {
                            $grilla .=                      '<a href="#"> <i onclick="javascript:datos_ws_modificar_caso(' . ($numero_voucher_b) . ',' . ($sistema_emision) .')" class="fa fa-user-plus" data-toggle="tooltip" data-placement="top" title="Seleccionar pax"></i></a>';
                        }
                        $grilla .=                      '<a href="#"> <i onclick="javascript:mostrar_voucher(' . ($numero_voucher_b) . ',' . ($sistema_emision) .')" class="fa fa-credit-card" data-toggle="tooltip" data-placement="top" title="Ver voucher"></i></a>';
                        $grilla .=                  "</td>";
                        $grilla .=              "</tr>";
           
                    }
                }
            }
        }   
       
        $grilla .=          "</tbody>";
        $grilla .=       "</table>";
        $grilla .=    "</div>";
        $grilla .=  "</div>";
        $grilla .="</div>";   
          
        echo $grilla;
    }

   
  




function mostrar_voucher($voucher_number, $sistema_emision){
      
    $vouchers = WS::buscar_ws($voucher_number,'','','', $sistema_emision);
    $vouchers = json_decode($vouchers, true);
        
    if(is_array($vouchers)) {
    
        foreach($vouchers as $voucher) {
        
            if (is_array($voucher)) {
                
                foreach($voucher as $atributo) {
                             
                    $voucher_id = $atributo['voucher_id'];
                    $voucher_number = $atributo['voucher_number'];
                    
                    $numero_voucher_b = "'" . $voucher_number . "'";
                    
                    $voucher_date = $atributo['voucher_date'];
                    $voucher_date_from =$atributo['voucher_date_from'];
                    $voucher_date_to = $atributo['voucher_date_to'];
                    $voucher_int_ref = $atributo['voucher_int_ref'];
                    $voucher_notes = $atributo['voucher_notes'];
                    $voucher_total_cost = $atributo['voucher_total_cost'];
                    $voucher_taxes = $atributo['voucher_taxes'];
                    $currency_id = $atributo['currency_id'];
                    $currency_name = $atributo['currency_name'];
                    $currency_tc = $atributo['currency_tc']; 
                    $product_id = $atributo['product_id'];
                    $product_name = $atributo['product_name'];
                    $passenger_document_type_id = $atributo['passenger_document_type_id'];
                    $passenger_document_type_name = $atributo['passenger_document_type_name'];
                    $passenger_document_number = $atributo['passenger_document_number'];
                    $passenger_birth_date = $atributo['passenger_birth_date'];
                    $passenger_gender = $atributo['passenger_gender'];
                    $passenger_first_name = $atributo['passenger_first_name'];
                    $passenger_last_name = $atributo['passenger_last_name'];
                    $passenger_second_name = $atributo['passenger_second_name'];
                    //Es una función propia
                    $nombre_pasajero = $passenger_first_name . " " . $passenger_second_name;
                    $passenger_city =  $atributo['passenger_city'];
                    $passenger_address =  $atributo['passenger_address'];
                    $passenger_phone =  $atributo['passenger_phone'];
                    $passenger_email =  $atributo['passenger_email'];
                    $passenger_emergency_first_name = $atributo['passenger_emergency_first_name'];
                    $passenger_emergency_last_name = $atributo['passenger_emergency_last_name'];
                    $passenger_emergency_phone_1 = $atributo['passenger_emergency_phone_1'];
                    $passenger_emergency_phone_2 = $atributo['passenger_emergency_phone_2'];
                    
                    $coberturas = $atributo['coverages'];
                               
                }
            }   
        }
        
        $resultado = '<div class="panel-body" id="panel_formulario_vista">';
        $resultado.=    '<div class="row">';
        $resultado.=        '<div class="col-lg-12">';
        $resultado.=            '<div class="card-box-form">';
        $resultado.=                '<h4 class="m-t-0 header-title"><b>Voucher</b></h4>';
        $resultado.=                '<div class="form-group col-lg-6">';
        $resultado.=                    '<label for="voucher_numero">Número de voucher</label>';
        $resultado.=                    '<input type="text" name="voucher_number" value="'.$voucher_number.'" class="form-control" id="voucher_number">';
        $resultado.=                '</div>';
        $resultado.=                '<div class="form-group col-lg-6">';
        $resultado.=                    '<label for="voucher_numero">Nombre del producto</label>';
        $resultado.=                    '<input type="text" name="product_name" placeholder="" value="'.$product_name.'" class="form-control" id="product_name">';
        $resultado.=                '</div>';
        $resultado.=                '<div class="form-group col-lg-6">';
        $resultado.=                    '<label for="voucher_numero">Beneficiario Nombre</label>';
        $resultado.=                    '<input type="text" name="passenger_first_name" placeholder="" value="'.$nombre_pasajero.'" class="form-control" id="passenger_first_name">';
        $resultado.=                '</div>';
        $resultado.=                '<div class="form-group col-lg-6">';
        $resultado.=                    '<label for="voucher_numero">Beneficiario Apellido</label>';
        $resultado.=                    '<input type="text" name="passenger_last_name" placeholder="" value="'.$passenger_last_name.'" class="form-control" id="passenger_last_name">';
        $resultado.=                '</div>';
        $resultado.=                '<div class="form-group col-lg-4">';
        $resultado.=                    '<label for="voucher_numero">Fecha del voucher</label>';
        $resultado.=                    '<input type="text" name="voucher_date" value="'.$voucher_date.'" class="form-control" id="voucher_date">';
        $resultado.=                '</div>';
        $resultado.=                '<div class="form-group col-lg-4">';
        $resultado.=                    '<label for="voucher_date_from">Fecha desde</label>';
        $resultado.=                    '<input type="text" name="voucher_date_from" value="'.$voucher_date_from.'" class="form-control" id="voucher_date_from">';
        $resultado.=                '</div>';
        $resultado.=                '<div class="form-group col-lg-4">';
        $resultado.=                    '<label for="voucher_date_to">Fecha hasta</label>';
        $resultado.=                    '<input type="text" name="voucher_date_to" value="'.$voucher_date_to.'" class="form-control" id="voucher_date_to">';
        $resultado.=                '</div>';
        $resultado.=                '<div class="form-group">';
        $resultado.=                    '<button onclick="javascript:datos_ws_alta_caso(' . ($numero_voucher_b) . ',' . ($sistema_emision) .')" class="btn btn-inverse waves-effect waves-light" type="submit">Seleccionar</button>';
        $resultado.=                '</div>';
        $resultado.=             '</div>';
        $resultado.=        '</div>';
        $resultado.=    '</div>';
        $resultado.= '</div>'; 

    echo $resultado;
    
        $grilla = "<div class='row'>";
        $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
        $grilla .=  "<div class='card-box-form table-responsive'>";
        $grilla .=      "<h4 class='m-t-0 header-title'><b>$product_name</b></h4>";      
        $grilla .=      "<table id='dt_producto' class='table table-hover m-0 table-responsive'>";
        $grilla .=          "<thead>";
        $grilla .=              "<tr>";
        $grilla .=                  "<th>Nombre</th>";
        $grilla .=                  "<th>Valor</th>";
        $grilla .=          "</thead>";
        $grilla .=          "<tbody>";  
      
        $id = 0;
        foreach ($coberturas as $cobertura){
            $id = $id + 1;
            $nombre = $cobertura["coverage_name"];
            $valor = $cobertura["coverage_value"];
            
            $grilla .=              "<tr>";
            $grilla .=                  "<td>";
            $grilla .=                      $nombre;
            $grilla .=                  "</td>";
            $grilla .=                  "<td>";
            $grilla .=                      $valor;
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
}



// Esta función tiene que guardar el voucher y luego el producto relacionado con el id de ese voucher
// Función para completar el voucher en el caso

function caso_completar_voucher($voucher_numero, $sistema_emision){
            
    $vouchers = WS::insertar_voucher_producto($voucher_numero, $sistema_emision);

    if (is_array($vouchers)) {
        
        if ($sistema_emision == "1") {
            foreach($vouchers['vouchers'] as $atributo) {
                // Trae todas las coberturas del producto
                $coberturas = $atributo['coverages'];
             }

        }

        if ($sistema_emision == "2") {
            foreach($vouchers['vouchers'] as $atributo) {
                // Trae todas las coberturas del producto
                $coberturas = $atributo['coverages'];
             }
            
        }

        if ($sistema_emision == "3") {
            foreach($vouchers['vouchers'] as $atributo) {
                // Trae todas las coberturas del producto
                $coberturas = $atributo['coverages'];
             }
            
        }

        if ($sistema_emision == "4") {
            foreach($vouchers['vouchers'] as $atributo) {
                // Trae todas las coberturas del producto
                $coberturas = $atributo['coverages'];
             }
            
        }
                
        // Busca si dentro de las coberturas hay deducible
        // El valor por defecto si no lo encuentra es no

        $deducible = "No";
        foreach ($coberturas as $cobertura){
    
            $coverage_name = $cobertura["coverage_name"];
            $coverage_val = $cobertura["coverage_value"];
            
            
            // Busca dentro del texto convirtiendo todo en mayúsculas
            
            if (strpos(strtoupper($coverage_name),'DEDUCIBLE') !==FALSE ){
                
                $deducible = $coverage_val;
                break;
            }
            
        }
        
        // Se inserta en el json a enviar como respuesta la existencia o no del deducible
        
        array_unshift ( $vouchers , $deducible );
 
        echo json_encode($vouchers);  
                               
    }
            
}   
    



