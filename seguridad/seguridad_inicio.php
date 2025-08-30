<?php

if (isset($_SESSION)) { 
    session_destroy(); 
}

// Inicio de la session

if (!isset($_SESSION)) { 
    session_start(); 
}


if(!isset($_SESSION['usuario_usuario'])){
    header("location:seguridad/login.php");
} else {
    header("location:dashboard/dashboard.php");
}