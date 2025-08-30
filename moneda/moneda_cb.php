<?php
require '../seguridad/seguridad.php';
include_once '../includes/herramientas.php';

// Toma texto introducido en el campo Moneda Nombre para el autocomplete
$moneda  = isset($_POST["moneda"])?$_POST["moneda"]:'';


// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion     = isset($_POST["opcion"])?$_POST["opcion"]:'';


// Case
switch($opcion) {
    
    // Select - Formulario Alta
    case 'select_moneda':
        select_moneda($moneda);
        break;
    
    default:
        echo("Está mal seleccionada la funcion");
}


// Funciones auxiliares de formulario
function select_moneda($moneda){

    $monedas = Moneda::buscar_autocomplete($moneda);

    $data = array();

    foreach ($monedas as $moneda) {

        $name = $moneda['moneda_nombre'] . '|' . $moneda['moneda_id'];
        array_push($data, $name);

    }	

    echo json_encode($data);
}