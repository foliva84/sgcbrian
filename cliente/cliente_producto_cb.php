<?php
include_once '../includes/herramientas.php';


// Toma las variables del formulario de modificación
$cliente_id = isset($_POST["cliente_id"])?$_POST["cliente_id"]:'';
$producto_id = isset($_POST["producto_id"])?$_POST["producto_id"]:'';
$clienteProducto_id = isset($_POST["clienteProducto_id"])?$_POST["clienteProducto_id"]:'';

// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';




switch($opcion){
    
    // Acciones de los formularios
    case 'productos_llenar':
        productos_llenar();
        break;
    
    case 'cliente_productos_llenar':
        cliente_productos_llenar($cliente_id);
        break;
      
    case 'cliente_productos_insertar':
        cliente_productos_insertar($cliente_id, $producto_id);
        break;
    
    case 'cliente_productos_eliminar':
        cliente_productos_eliminar($clienteProducto_id);
        break;
    
     case 'practicas_prestador_existe':
        practicas_prestador_existe($cliente_id, $producto_id);
        break;
    
    default:
        echo("Está mal seleccionada la funcion");        
}


// Funciones
function productos_llenar(){
    
    $productos = Product::listar_activos();

    echo json_encode($productos);
      
}


function cliente_productos_llenar($cliente_id){
    
    $productos = Cliente::listar_productos($cliente_id);
    
    echo json_encode($productos, JSON_UNESCAPED_UNICODE);
}


function cliente_productos_insertar($cliente_id, $producto_id){
    
    $existe = Cliente::insertar_producto($cliente_id, $producto_id);
    
    echo json_encode($existe, JSON_UNESCAPED_UNICODE);                                
}


function cliente_productos_eliminar($clienteProducto_id){
    
    Cliente::eliminar_producto($clienteProducto_id);
}