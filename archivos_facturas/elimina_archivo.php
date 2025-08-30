<?php
include_once '../includes/herramientas.php';

try {
   // Obtener el id del archivo
    $archivo_id = isset($_POST["id_archivo_borrar"])?$_POST["id_archivo_borrar"]:'';
    
    $archivo_arr = Archivo::obtener_nombre($archivo_id);
    
    $nombre_archivo = $archivo_arr["archivo_encriptado"];
    $extension_archivo = $archivo_arr["archivo_extension"];
    
    Archivo::eliminar_facturas($archivo_id);
    
    unlink("../a_edc6c6cba6ee5c14f146448b9fe908435d9b665f_repo_facturas/" . $nombre_archivo . "." . $extension_archivo);
    
    echo 'El archivo ha sido eliminado exitosamente.';
    
} catch (Exception $ex) {

   echo $ex->getMessage();
    
}