<?php
include_once '../includes/herramientas.php';

// Toma las variables del formulario de búsqueda
$buscar_voucher = isset($_POST["buscar_voucher"])?$_POST["buscar_voucher"]:'';
$sistema_emision = isset($_POST["sistema_emision"])?$_POST["sistema_emision"]:'';
$producto_id = isset($_POST["producto_id"])?$_POST["producto_id"]:'';
$voucher_numero = isset($_POST["voucher_numero"])?$_POST["voucher_numero"]:'';


// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';


switch($opcion){
    
    // Acciones de los formularios
    case 'formulario_voucher':
        formulario_voucher($voucher_numero, $sistema_emision);
        break;

    case 'grilla_producto':
        grilla_producto($producto_id);
        break;

    case 'grilla_listar':
        grilla_listar($buscar_voucher, $sistema_emision);
        break;

    default:
       echo("Está mal seleccionada la funcion");  
}


// Funciones de Formulario


function formulario_voucher($buscar_voucher, $sistema_emision){
      
    $voucher = Voucher::buscar_por_numero($buscar_voucher, $sistema_emision);
    
    $numero_voucher = $voucher->voucher[0]->voucher_number;
    $producto = $voucher->voucher[0]->product_name;
    $nombre= $voucher->voucher[0]->passenger_first_name;
    $apellido = $voucher->voucher[0]->passenger_last_name;
    $producto_id = $voucher->voucher[0]->product_id;
   

    $resultado = '<div class="panel-body" id="panel_formulario_vista">';
    $resultado.=    '<div class="row">';
    $resultado.=        '<div class="col-lg-6">';
    $resultado.=            '<div class="card-box">';
    $resultado.=                '<h4 class="m-t-0 header-title"><b>Formulario Vista de Voucher</b></h4>';
    $resultado.=                '<p class="text-muted font-13 m-b-30">Información del voucher obtenida mediante Web Service.</p>';
    $resultado.=                '<div class="form-group">';
    $resultado.=                    '<label for="voucher_numero">Número de voucher</label>';
    $resultado.=                    '<input type="text" name="voucher_numero" placeholder="" value="'.$numero_voucher.'"   class="form-control" id="voucher_numero">';
    $resultado.=                '</div>';
    $resultado.=                '<div class="form-group">';
    $resultado.=                    '<label for="voucher_numero">Nombre del producto</label>';
    $resultado.=                    '<input type="text" name="nombre_producto" placeholder="" value="'.$producto.'" class="form-control" id="nombre_producto">';
    $resultado.=                '</div>';
    $resultado.=                '<div class="form-group">';
    $resultado.=                    '<label for="voucher_numero">Beneficiario Nombre</label>';
    $resultado.=                    '<input type="text" name="beneficiario_nombre" placeholder="" value="'.$nombre.'" class="form-control" id="beneficiario_nombre">';
    $resultado.=                '</div>';
    $resultado.=                '<div class="form-group">';
    $resultado.=                    '<label for="voucher_numero">Beneficiario Apellido</label>';
    $resultado.=                    '<input type="text" name="beneficiario_apellido" placeholder="" value="'.$apellido.'" class="form-control" id="beneficiario_apellido">';
    $resultado.=                '</div>';
    $resultado.=                '<button onclick="javascript:formulario_voucher_cerrar();" class="btn btn-primary waves-effect waves-light" type="submit">Cerrar</button>';
    $resultado.=                '<button onclick="javascript:parect.test();" class="btn btn-primary waves-effect waves-light" type="submit">Test</button>';
    $resultado.=             '</div>';
    $resultado.=        '</div>';
    $resultado.=    '</div>';
    $resultado.= '</div>'; 

    echo $resultado;
    
}


// Funciones de Grilla

function grilla_listar($buscar_voucher, $sistema_emision){
    
    $vouchers = Voucher::buscar_por_numero($buscar_voucher, $sistema_emision);
    
    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
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
    foreach ($vouchers->voucher as $voucher){
            $numero_voucher_b = "'" . $voucher->voucher_number . "'";
            $numero_voucher = $voucher->voucher_number;
            $producto = $voucher->product_name;
            $nombre = $voucher->passenger_first_name;
            $apellido =  $voucher->passenger_last_name;
            $producto_id = $voucher->product_id;
            
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
    $grilla .=                      '<a href="#"> <i onclick="javascript:formulario_voucher(' . ($numero_voucher_b) . ')" class="fa fa-credit-card" data-toggle="tooltip" data-placement="top" title="Ver voucher"></i></a>';
    $grilla .=                      "<a href='#'> <i onclick='grilla_producto($producto_id)' class='fa fa-tasks' data-toggle='tooltip' data-placement='top' title='Ver producto'></i></a>";
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



function grilla_producto($producto_id){
    
    $resultado = Voucher::buscar_producto($producto_id);
    
    $nombre_producto = $resultado->product[0]->product_name;
   
    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      ' <div class="form-group text-right m-b-0"><button onclick="javascript:grilla_producto_cerrar();" class="btn btn-primary waves-effect waves-light" type="submit">Cerrar</button></div>';
    $grilla .=      "<h4 class='m-t-0 header-title'><b>$nombre_producto</b></h4>";      
    $grilla .=      "<table id='dt_producto' class='table table-hover m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Id</th>";
    $grilla .=                  "<th>Nombre</th>";
    $grilla .=                  "<th>Valor</th>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach ($resultado->product->coverages->coverage as $coverage){
            $id = $coverage->coverage_id;
            $nombre = $coverage->coverage_name;
            $valor = $coverage->coverage_val;
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    $grilla .=                      $id;
    $grilla .=                  "</td>";
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