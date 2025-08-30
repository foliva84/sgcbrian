<?php
include_once '../includes/herramientas.php';



try {
       
    
    
    // Evitar parámetros inválidos
    if (
        !isset($_FILES['upfile']['error']) ||
        is_array($_FILES['upfile']['error'])
    ) {
        throw new RuntimeException('Parametros inválidos.');
    } else {
        $archivo_nombre = $_FILES["upfile"]["name"]; // Nombre de archivo
        
        $arch_ext_tmp = explode(".", $archivo_nombre);
        
        $archivo_extension = end($arch_ext_tmp);
        
        $archivo_nombre_temporario = $_FILES["upfile"]["tmp_name"]; // Nombre temporario
        
        $archivo_encriptado = sha1_file($archivo_nombre_temporario); // Nombre de archivo encriptado

        $fileType = $_FILES["upfile"]["type"]; // Tipo mime del archivo
        $fileSize = $_FILES["upfile"]["size"]; // Tamaño del archivo
        
        $factura_id_archivo = isset($_POST["factura_id_archivo"])?$_POST["factura_id_archivo"]:'';  // Factura del archivo
        
    }
    
    
    // Verificar el tipo de error
    switch ($_FILES['upfile']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No se envio el archivo.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Excedió el limite del tamaño del archivo.');
        default:
            throw new RuntimeException('Error desconocido.');
    }


    // Verificar el tamaño del archivo
    if ($_FILES['upfile']['size'] > 10000000) {
        throw new RuntimeException('Exedio el límite del tamaño.');
    }
    
    
    // Verificar el tipo de archivo
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search(
        $finfo->file($_FILES['upfile']['tmp_name']),
        array(
            // image
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            // application
            'pdf' => 'application/pdf',
            // MS-applications
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            // text
            'txt' => 'text/plain',
        ),
        true
    )) {
        throw new RuntimeException('Formato o extensión de archivo inválido.');
    }
    
    // Crear un valor único para el archivo
    if (!move_uploaded_file(
        $_FILES['upfile']['tmp_name'],
        sprintf('../a_edc6c6cba6ee5c14f146448b9fe908435d9b665f_repo_facturas/%s.%s',$archivo_encriptado,$ext))) 
    {
        throw new RuntimeException('Ah fallado el grabado del archivo.');
    }
    
    // Acá guardar los datos en DB
           
    Archivo::guardar_facturas($archivo_nombre, $archivo_extension, $archivo_encriptado, $factura_id_archivo);    
    echo 'El archivo ' . $archivo_nombre . ' ha sido guardado exitosamente.';

    
} catch (RuntimeException $e) {

    echo $e->getMessage();

}

    
  

