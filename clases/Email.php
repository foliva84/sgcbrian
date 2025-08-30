<?php
/**
 * Clase: Email
 * 
 *
 * @author ArgenCode
 */

class Email {
   
    // Metodo para listar Email por Id de la Entidad
    public static function listarPorEntidadId($entidad_id, $entidad_tipo) {
        DB::query("SELECT email_id,
                          tipoEmail_nombre,
                          email_email, 
                          email_principal
                    FROM emails INNER JOIN tipos_emails ON tipoEmail_id = email_tipoEmail_id 
                    WHERE email_entidad_id = :email_entidad_id
                    AND email_entidad_tipo = :email_entidad_tipo
                    ORDER BY email_principal desc");
        
        DB::bind(':email_entidad_id', "$entidad_id");
        DB::bind(':email_entidad_tipo', "$entidad_tipo");
        
        return DB::resultados();
    }
       
    // Metodo para listar Email por Id de la Entidad
    public static function listarPorId($email_id) {
        DB::query("SELECT email_id, email_nombre, email_email, email_principal
                    FROM emails 
                    WHERE email_id = :email_id
                    ORDER BY email_principal desc");
        
        DB::bind(':email_id', "$email_id");
        
        return DB::resultado();   
    }
    
    // Metodo para listar los tipos de telefonos segun la entidad que llama al metodo
    public static function listarTipoEmail($entidad_id) {

        DB::query("SELECT tipoEmail_id, tipoEmail_nombre
                   FROM tipos_emails
                   WHERE tipoEmail_entidad = :tipoEmail_entidad");
        
        DB::bind(':tipoEmail_entidad', $entidad_id);
        
        return DB::resultados();
    }
    
    // Metodo para listar los tipos de telefonos segun la entidad que llama al metodo
    public static function listarTipoEmail_modificacion($email_id_e, $entidad_id) {

        DB::query("SELECT tipoEmail_id, tipoEmail_nombre
                   FROM tipos_emails
                        LEFT JOIN emails ON email_tipoEmail_id = tipoEmail_id
                   WHERE email_id = :email_id
                   UNION
                   SELECT tipoEmail_id, tipoEmail_nombre
                   FROM tipos_emails
                   WHERE tipoEmail_entidad = :tipoEmail_entidad");
        
        DB::bind(':email_id', $email_id_e);
        DB::bind(':tipoEmail_entidad', $entidad_id);
        
        return DB::resultados();
    }
    
    
    // Metodo para insertar un nuevo e-mail. Comprueba email principal y si hay mas de 5 emails
    public static function insertar($emailTipo_id, $email_email, $email_principal, $email_entidad_id, $email_entidad_tipo) {
        
        $error = 0;
        
        //Comprobar si se va a guardar un email principal
        if($email_principal == 1) {
            DB::query("SELECT COUNT(email_principal) AS NumerodePrincipales FROM emails
                        WHERE email_principal = 1
                        AND email_entidad_id = :email_entidad_id
                        AND email_entidad_tipo = :email_entidad_tipo");

            // Preparar los parámetros
            DB::bind(':email_entidad_id', "$email_entidad_id");
            DB::bind(':email_entidad_tipo', "$email_entidad_tipo");
            
            $principales = DB::resultado();
            
            $cant_principales = $principales["NumerodePrincipales"];
            
            if($cant_principales > 0)
            {
                return $error = 11;
            }
        }
            // Comprobar si hay más de 5 e-mails
            DB::query("SELECT COUNT(email_id) AS NumerodeEmails FROM emails
                        WHERE email_entidad_id = :email_entidad_id
                        AND email_entidad_tipo = :email_entidad_tipo");
            
            // Preparar los parámetros
            DB::bind(':email_entidad_id', "$email_entidad_id");
            DB::bind(':email_entidad_tipo', "$email_entidad_tipo");
            
            $cantidad_email = DB::resultado();
            
            $cant_email = $cantidad_email["NumerodeEmails"];
            
            if($cant_email > 4)
            {
                return $error = 12;
            }
        
        // Preparar el sql a ser ejecutado
        DB::query("INSERT INTO emails (email_tipoEmail_id,
                                       email_email,
                                       email_principal,
                                       email_entidad_id,
                                       email_entidad_tipo)
                              VALUES (:email_tipoEmail_id,
                                      :email_email,
                                      :email_principal,
                                      :email_entidad_id,
                                      :email_entidad_tipo)");
        
        DB::bind(':email_tipoEmail_id', "$emailTipo_id");
        DB::bind(':email_email', "$email_email");
        DB::bind(':email_principal', "$email_principal");
        DB::bind(':email_entidad_id', "$email_entidad_id");
        DB::bind(':email_entidad_tipo', "$email_entidad_tipo");
             
        $resultado = DB::execute();
        
        If ($resultado) {
            $error = 0;
        } else {
            $error = 1;
        }
        
        return $error; 
    }
    
    // Metodo para eliminar un e-mail
    public static function eliminar($email_id){
        
        DB::query("DELETE FROM emails WHERE email_id = :email_id");
        
        DB::bind(':email_id', "$email_id");
        
        $resultado = DB::execute();
        
        If ($resultado) {
            $error = 0;
        } else {
            $error = 4;
        }
        
        return $error;
    }
    
    // Metodo para modificar un e-mail
    public static function modificar($emailTipo_id, $email_email, $email_principal, $email_entidad_id, $email_entidad_tipo, $email_id) {
        
        if($email_principal == 2){
            DB::query("SELECT COUNT(email_principal) AS NumerodePrincipales FROM emails
                        WHERE email_principal = 1
                        AND email_entidad_id = :email_entidad_id
                        AND email_entidad_tipo = :email_entidad_tipo");
            
            DB::bind(':email_entidad_id', "$email_entidad_id");
            DB::bind(':email_entidad_tipo', "$email_entidad_tipo");
            
            $principales = DB::resultado();
            
            $cant_principales = $principales["NumerodePrincipales"];
                
            if($cant_principales >= 1)
            {
                return $error = 11;
            }
        }
        // Preparar el sql a ser ejecutado
        DB::query("UPDATE emails SET
                    email_tipoEmail_id = :email_tipoEmail_id,
                    email_email = :email_email,
                    email_principal = :email_principal
                    WHERE email_id = :email_id");
        
        DB::bind(':email_tipoEmail_id', "$emailTipo_id");
        DB::bind(':email_email', "$email_email");
        DB::bind(':email_principal', "$email_principal");
        DB::bind(':email_id', "$email_id");
    
        $resultado = DB::Execute();
            
        If ($resultado) {
            $error = 0;
        } else {
            $error = 1;
        }
        
        return $error;
    } 
    
    // Metodo para insertar un e-mail desde el formulario de alta de casos
    public static function insertar_enCaso($email_email, $email_principal, $email_entidad_id, $email_entidad_tipo) {
        
        // Preparar el sql a ser ejecutado
        DB::query("INSERT INTO emails (email_email,
                                       email_principal,
                                       email_entidad_id,
                                       email_entidad_tipo,
                                       email_tipoEmail_id)
                    VALUES (:email_email,
                            :email_principal,
                            :email_entidad_id,
                            :email_entidad_tipo,
                            :email_tipoEmail_id)");
        
        DB::bind(':email_email', "$email_email");
        DB::bind(':email_principal', "$email_principal");
        DB::bind(':email_entidad_id', $email_entidad_id);
        DB::bind(':email_entidad_tipo', "$email_entidad_tipo");
        DB::bind(':email_tipoEmail_id', 1);
             
        $resultado = DB::execute();
        
        If ($resultado) {
            $error = 0;
        } else {
            $error = 1;
        }
        
        return $error; 
    }
    
    // Metodo para modificar un e-mail desde el formulario de modifcacion de casos
    public static function modificar_enCaso($email_email, $email_id) {
        
        // Preparar el sql a ser ejecutado
        DB::query("UPDATE emails SET
                    email_email = :email_email
                   WHERE email_id = :email_id");
        
        DB::bind(':email_email', "$email_email");
        //DB::bind('email_principal', "$email_principal");
        DB::bind(':email_id', "$email_id");
    
        $resultado = DB::Execute();
            
        If ($resultado) {
            $error = 0;
        } else {
            $error = 1;
        }
        
        return $error;
    }
}