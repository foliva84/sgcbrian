<?php

include_once("../includes/herramientas.php");
// Inicio de la session

if (!isset($_SESSION)) { 
    session_start(); 
}

// El primer número son los minutos que estará activa la sesion.
if ($_SESSION['timeout'] + 360 * 60 < time()) {
     session_destroy();
     header("location:../seguridad/login.php");
} else {
     // session ok
}


//   Funciones de seguridad

// Se asegura que el usuario tenga una sesion iniciada
if(!isset($_SESSION['usuario_usuario'])){
    header("location:../seguridad/login.php");
}

// Se asegura que la sesión corresponda al sitio actual
if(!isset($_SESSION['usuario_sitio'])){
    header("location:../seguridad/login.php");
} elseif ($_SESSION['usuario_sitio']=="coris-SGC"){
    
} else {
    header("location:../seguridad/login.php");
}


if (isset($_SESSION)) { 
    // Variables de seguridad
    $rol_id = $_SESSION['usuario_rol_id'];

    $permisos = Permiso::listar_por_rolid($rol_id);

    $sesion_usuario_id = $_SESSION['usuario_id'];
    $sesion_usuario_nombre = $_SESSION['usuario_nombre'];
    $sesion_usuario_apellido = $_SESSION['usuario_apellido'];
    $sesion_usuario_rol =  $_SESSION['usuario_rol_nombre'];
}

