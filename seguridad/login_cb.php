<?php

   
    require_once('../includes/herramientas.php');


    $usuario_usuario = isset($_POST["usuario"])?$_POST["usuario"]:'';
    $usuario_password = isset($_POST["password"])?$_POST["password"]:'';


    $miusuario = Usuario::login($usuario_usuario, $usuario_password);
    
    echo json_encode($miusuario);
      
   
?>