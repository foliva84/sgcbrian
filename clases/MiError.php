<?php
/**
 * Clase: Error
 *
 * @author ArgenCode
 */

class MiError {
    
    public static function mostrarError($error) {
        
       
        
        $tipo_errores = [
            array(0,"OK"),
            // Errores generales
            array(1," Error al intentar guardar el registro"),
            array(2," Error al intentar modificar el registro"),
            array(3," Error al intentar leer el registro"),
            array(4," Error al intentar eliminar el registro"),
            array(5," Error al..."),
            array(6," Error al..."),
            array(7," Error al..."),
            array(8," Error al..."),
            array(9," Error al..."),
            array(10," Error al..."),
            
            // Errores particulares
            
            // Errores email
            array(11," Ya existe un e-mail principal"),
            array(12," Existen 5 e-mails para el contacto"),
            
            //Errores teléfono
            array(13," Ya existe un teléfono principal"),
            array(14," Existen 5 teléfonos para el contacto")
            ];
        
        if (is_numeric($error)) {
        
            // función para buscar el error en el array
            function find_Error($tipo_errores, $error) {
            foreach($tipo_errores as $index => $tipo_error) {   
                if($tipo_error[0] == $error) {
                    return $index;
                }
            }
                return "error";
            }

            $posicion = find_Error($tipo_errores, $error);

            if(is_numeric($posicion)){
               $error_texto = $tipo_errores[$posicion][1];
            } else {
               $error_texto = "El tipo de error no se encontró.";
            }
        } else {
            
            $error_texto = "Error no es numerico.";
            
        }
            
        return $error_texto;
                   
    }
    
}
