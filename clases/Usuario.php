<?php
/**
 * Clase: Usuario
 *
 *
 * @author ArgenCode
 */

class Usuario {
    

     // Un tipo de búsqueda, se debería definir el resto.
    public static function buscar($nombre){
        DB::query("SELECT * FROM usuarios 
                                WHERE usuario_nombre LIKE :usuario_nombre");
        DB::bind(':usuario_nombre', "%$nombre%");
        return DB::resultado();
    }
    
    
    public static function bucarFiltrado($usuario_nombre,$usuario_apellido){
        DB::query("SELECT usuario_id, usuario_nombre, usuario_apellido, usuario_usuario
                    FROM usuarios 
                    WHERE usuario_nombre LIKE :usuario_nombre
                    AND usuario_apellido LIKE :usuario_apellido");
        
        DB::bind(':usuario_nombre', "%$usuario_nombre%");
        DB::bind(':usuario_apellido', "%$usuario_apellido%");
        
        return DB::resultados();
    }
    
    
    // Metodo para listar en los select los usuarios, mostrando el nombre completo
    public static function listar_usuarios(){
        DB::query("SELECT usuario_id, usuario_nombre, usuario_apellido
                    FROM usuarios
                    WHERE usuario_activo = 1");
        
        return DB::resultados();
    }
    
    // Metodo para listar en los select los usuarios, mostrando el nombre completo
    // Con filtro para que solo muestre los usuarios con perfil de OPERADOR
    public static function listar_usuarios_op(){
        DB::query("SELECT usuario_id, usuario_nombre, usuario_apellido
                    FROM usuarios
                    WHERE usuario_activo = 1 AND (usuario_rol_id = 5 OR usuario_rol_id = 17)");
        
        return DB::resultados();
    }

    // Metodo para listar en los select los usuarios, mostrando el nombre completo
    // Con filtro para que solo muestre los usuarios con perfil de REINTEGROS
    public static function listar_usuarios_reint(){
        DB::query("SELECT usuario_id, usuario_nombre, usuario_apellido
                    FROM usuarios
                    WHERE usuario_activo = 1 AND usuario_rol_id = 10");
        
        return DB::resultados();
    }
    
    // Metodo para listar en el select de Agenda Casos Re-Asignacion los usuarios, mostrando el nombre completo
    public static function listarUsuarios_reAsignacion($casoAgenda_id) {
        
        DB::query("SELECT usuario_id, usuario_nombre, usuario_apellido
                   FROM usuarios 
                        INNER JOIN casos_agenda ON casoAgenda_usuarioAsignado_id = usuario_id 
                   WHERE casoAgenda_id = :casoAgenda_id
                   UNION
                   SELECT usuario_id, usuario_nombre, usuario_apellido
                   FROM usuarios
                   WHERE usuario_activo = 1 AND (usuario_rol_id = 5 OR usuario_rol_id = 17)");

        DB::bind(':casoAgenda_id', "$casoAgenda_id");

        return DB::resultados();
    }
    // Metodo para listar en el select de Agenda Reintegos Re-Asignacion los usuarios, mostrando el nombre completo
    public static function listarUsuarios_reAsignacionR($reintegroAgenda_id) {
        
        DB::query("SELECT usuario_id, usuario_nombre, usuario_apellido
                   FROM usuarios 
                        INNER JOIN reintegros_agenda ON reintegroAgenda_usuarioAsignado_id = usuario_id 
                   WHERE reintegroAgenda_id = :reintegroAgenda_id
                   UNION
                   SELECT usuario_id, usuario_nombre, usuario_apellido
                   FROM usuarios
                   WHERE usuario_activo = 1 AND usuario_rol_id = 10");

        DB::bind(':reintegroAgenda_id', "$reintegroAgenda_id");

        return DB::resultados();
    }
    
     
    // Para listar los usuarios
    public static function listar(){
        DB::query("SELECT 
                        usuarios.usuario_id,
                        usuarios.usuario_usuario,
                        usuarios.usuario_password,
                        usuarios.usuario_nombre,
                        usuarios.usuario_apellido,
                        usuarios.usuario_rol_id,
                        usuarios.usuario_activo,
                        roles.rol_nombre as usuario_rol_nombre
                    FROM usuarios
                    LEFT OUTER JOIN roles 
                    ON roles.rol_id = usuarios.usuario_rol_id");
        
        return DB::resultados();
    }
    
    
    // Para listar en la grilla
    public static function listarGrilla(){
        DB::query("SELECT 
                        usuario_id,
                        usuario_nombre,
                        usuario_apellido,
                        usuario_usuario
                    FROM usuarios");
        
        return DB::resultados();
    }
    
    
    // Un tipo de búsqueda, se debería definir el resto.
    public static function buscarPorId($usuario_id){
        
        
        DB::query("SELECT 
                    usuarios.usuario_id,
                    usuarios.usuario_usuario,
                    usuarios.usuario_password,
                    usuarios.usuario_nombre,
                    usuarios.usuario_apellido,
                    usuarios.usuario_rol_id,
                    usuarios.usuario_activo,
                    usuarios.usuario_fecha_baja,
                    roles.rol_nombre as usuario_rol_nombre
                    FROM usuarios
                    LEFT OUTER JOIN roles 
                    ON roles.rol_id = usuarios.usuario_rol_id
                    WHERE usuario_id = :usuario_id");
        
        DB::bind(':usuario_id', "$usuario_id");
        
        return DB::resultado();        
    }
    
    
    public static function ultimoAcceso($usuario_id){
            $usuario_ultimo_acceso = date("Y-m-d H:i:s");
            DB::query("
                    UPDATE usuarios SET
                           usuario_ultimo_acceso = :usuario_ultimo_acceso
                    WHERE usuario_id = :usuario_id
                    ");
            DB::bind(':usuario_ultimo_acceso', "$usuario_ultimo_acceso");
            DB::bind(':usuario_id', "$usuario_id");
            
            DB::execute();
    }
    
    
    public static function login($usuario_usuario, $usuario_password){
    
        DB::query("SELECT 
                    usuarios.usuario_id,
                    usuarios.usuario_usuario,
                    usuarios.usuario_password,
                    usuarios.usuario_nombre,
                    usuarios.usuario_apellido,
                    usuarios.usuario_rol_id,
                    usuarios.usuario_activo,
                    roles.rol_nombre as usuario_rol_nombre
                  FROM usuarios 
                    LEFT OUTER JOIN roles 
                    ON roles.rol_id = usuarios.usuario_rol_id 
                    WHERE usuario_usuario = :usuario_usuario
                    AND usuario_activo = :usuario_activo");
        
        DB::bind(':usuario_usuario', "$usuario_usuario");
        DB::bind(':usuario_activo', true);
        
        $usuario = DB::resultado();     
        
        // Verificar si existe un usuario
        if(!empty($usuario) && is_array($usuario)){
            
            
            // Verificación del password con password_hash

            $usuario_verify = $usuario["usuario_password"];
            
            if (password_verify($usuario_password,$usuario_verify)){
                
                            
                //Cargar en la base el último acceso
                self::ultimoAcceso($usuario["usuario_id"]);
                
                //cargar las variables de sesion
                $_SESSION['usuario_sitio'] = "coris-SGC";
                $_SESSION['usuario_usuario'] = $usuario["usuario_usuario"];
                $_SESSION['usuario_nombre'] = $usuario["usuario_nombre"];
                $_SESSION['usuario_apellido'] = $usuario["usuario_apellido"];
                $_SESSION['usuario_rol_id'] = $usuario["usuario_rol_id"];
                $_SESSION['usuario_rol_nombre'] = $usuario["usuario_rol_nombre"];
                $_SESSION['usuario_id'] = $usuario["usuario_id"];
                $_SESSION['timeout'] = time();
               

                $resultado = true;
            
            } else {
                if (isset($_SESSION)) { session_destroy(); } 

                $resultado = false;
            }
      
              
        }else{
            if (isset($_SESSION)) { session_destroy(); } 
            $resultado = false;

        }
        return $resultado;
    }
    
    
    public static function reActivar($usuario_id){
        $usuario_fecha_reactivacion = date("Y-m-d H:i:s");
        // $usuario_sesion = $_SESSION['usuario_id'];   
        $usuario_sesion = 1;
        
        DB::query("
                    UPDATE usuarios SET
                           usuario_activo = :usuario_activo,
                           usuario_fecha_reactivacion = :usuario_fecha_reactivacion,
                           usuario_reactivacion_usuario_id = :usuario_reactivacion_usuario_id
                    WHERE usuario_id = :usuario_id
                    ");
            
          
            DB::bind(':usuario_activo', true);
            DB::bind(':usuario_fecha_reactivacion', "$usuario_fecha_reactivacion");
            DB::bind(':usuario_reactivacion_usuario_id', "$usuario_sesion");
            DB::bind(':usuario_id', "$usuario_id");
            
            DB::execute();
            $mensaje = "ok";
            return $mensaje;
    }

    
    // Métodos ABM
    public static function actualizar($usuario_nombre, 
                                        $usuario_apellido,
                                        $usuario_usuario,
                                        $usuario_rol_id,
                                        $usuario_id
                                        )
    {
        $existe = self::existeUpdate($usuario_usuario, $usuario_id);   
        If ($existe == 1){
            $mensaje = "Ese uausrio ya existe en la base, ocurrió un error";
            return $mensaje;
        } else {
            
            $usuario_fecha_modificacion = date("Y-m-d H:i:s");
            $usuario_sesion = $_SESSION['usuario_id'];
            
                  DB::query("
                        UPDATE usuarios SET
                               usuario_nombre = :usuario_nombre,
                               usuario_apellido = :usuario_apellido,
                               usuario_usuario = :usuario_usuario,
                               usuario_rol_id = :usuario_rol_id,
                               usuario_fecha_modificacion = :usuario_fecha_modificacion,
                               usuario_modificacion_usuario_id = :usuario_modificacion_usuario_id
                        WHERE usuario_id = :usuario_id
                        ");
          
            DB::bind(':usuario_nombre', "$usuario_nombre");
            DB::bind(':usuario_apellido', "$usuario_apellido");
            DB::bind(':usuario_usuario', "$usuario_usuario");
            DB::bind(':usuario_rol_id', "$usuario_rol_id");
            DB::bind(':usuario_id', "$usuario_id");
            DB::bind(':usuario_fecha_modificacion', "$usuario_fecha_modificacion");
            DB::bind(':usuario_modificacion_usuario_id', "$usuario_sesion");
            
            DB::execute();
            $mensaje = "El usuario fue actualizado con éxito";
            return $mensaje;
        }
    }
    
    
    // Métodos ABM
    public static function modificarPassword(
                                            $usuario_id,
                                            $usuario_password
                                            )
    {
        $usuario_fecha_modificacion = date("Y-m-d H:i:s");
        $usuario_sesion = $_SESSION['usuario_id'];

        // Encriptación en un sólo sentido según las recomendaciones para PHP 7.0
        $usuario_password_encriptado = password_hash($usuario_password, PASSWORD_DEFAULT);

        DB::query("
              UPDATE usuarios SET
                     usuario_password = :usuario_password,
                     usuario_fecha_modificacion = :usuario_fecha_modificacion,
                     usuario_modificacion_usuario_id = :usuario_modificacion_usuario_id
              WHERE usuario_id = :usuario_id
                ");

        DB::bind(':usuario_password', "$usuario_password_encriptado");
        DB::bind(':usuario_fecha_modificacion', "$usuario_fecha_modificacion");
        DB::bind(':usuario_modificacion_usuario_id', "$usuario_sesion");
        DB::bind(':usuario_id', "$usuario_id");

        DB::execute();
        $mensaje = "El password fue actualizado con éxito";
        return $mensaje;
    }    
      
       
    // Método para insertar el usuario
    public static function insertar($usuario_nombre, 
                                    $usuario_apellido,
                                    $usuario_usuario,
                                    $usuario_rol_id,
                                    $usuario_password
                                    )        
    {   
        $usuario_fecha_alta = date("Y-m-d H:i:s");
        $usuario_sesion = $_SESSION['usuario_id'];
        // Encriptación en un sólo sentido según las recomendaciones para PHP 7.0
        $usuario_password_encriptado = password_hash($usuario_password, PASSWORD_DEFAULT);

        DB::query("
                    INSERT INTO usuarios (usuario_nombre,
                                          usuario_apellido,
                                          usuario_usuario,
                                          usuario_rol_id,
                                          usuario_password,
                                          usuario_fecha_alta,
                                          usuario_alta_usuario_id,
                                          usuario_activo)
                                VALUES (:usuario_nombre,
                                        :usuario_apellido,
                                        :usuario_usuario,
                                        :usuario_rol_id,
                                        :usuario_password,
                                        :usuario_fecha_alta,
                                        :usuario_alta_usuario_id,
                                        :usuario_activo)       
                    ");
        
        DB::bind(':usuario_nombre', "$usuario_nombre");
        DB::bind(':usuario_apellido', "$usuario_apellido");
        DB::bind(':usuario_usuario', "$usuario_usuario");
        DB::bind(':usuario_rol_id', "$usuario_rol_id");
        DB::bind(':usuario_password', "$usuario_password_encriptado");
        DB::bind(':usuario_fecha_alta', "$usuario_fecha_alta");
        DB::bind(':usuario_alta_usuario_id', "$usuario_sesion");
        DB::bind(':usuario_activo', true);
               
        DB::execute();     
        
        
        
        $mensaje = "El usuario fue creado con éxito";
        return $mensaje;
    }
    
    
    // Eliminación física del usuario - no se debería de utilizar salvo en casos de mantenimiento
    // Lo ideal sería eliminar si no está activo, depurando la base. Sino hay que hacer un borrado lógico.
    public static function eliminar($usuario_id){
        DB::query("
                    DELETE FROM usuarios WHERE usuario_id = :usuario_id                                
                  ");
        DB::bind(':usuario_id', "$usuario_id");
        DB::execute();
    }

    
    public static function borradoLogico($usuario_id){
        
        $usuario_fecha_baja = date("Y-m-d H:i:s");
        // $usuario_sesion = $_SESSION['usuario_id'];
        $usuario_sesion = 1;
        if ($usuario_id !== $usuario_sesion) {
        
            DB::query("
                        UPDATE usuarios SET
                               usuario_activo = :usuario_activo,
                               usuario_fecha_baja = :usuario_fecha_baja,
                               usuario_baja_usuario_id = :usuario_baja_usuario_id
                        WHERE usuario_id = :usuario_id                                
                      ");
            DB::bind(':usuario_activo', false);
            DB::bind(':usuario_fecha_baja', "$usuario_fecha_baja");
            DB::bind(':usuario_baja_usuario_id', "$usuario_sesion");
            DB::bind(':usuario_id', "$usuario_id");
            DB::execute();
            $mensaje = "ok";
            return $mensaje;
        }else{
            $mensaje = "El usuario que intenta dar de baja es usted mismo.";
            return $mensaje;
        }
     }    
       
    public static function existe($usuario_usuario)
    {
        DB::query("
                  SELECT * FROM usuarios 
                  WHERE usuario_usuario = :usuario_usuario
                  AND usuario_activo = :usuario_activo
                    ");
        DB::bind(':usuario_usuario', "$usuario_usuario");
        DB::bind(':usuario_activo', true);
        $usuario = DB::resultado(); 
        
        if(!empty($usuario) && is_array($usuario)){       
            $existe = true;
        }else{
            $existe= false;            
        }
        return $existe;      
    }
    
    // Verificar si existe un usuario antes de hacer un update
    public static function existeUpdate($usuario_usuario, $usuario_id)
    { 
        DB::query("
                  SELECT * FROM usuarios 
                                WHERE usuario_usuario = :usuario_usuario
                                AND usuario_id <> :usuario_id
                    ");
        DB::bind(':usuario_usuario', "$usuario_usuario");
        DB::bind(':usuario_id', "$usuario_id");
       
        $usuario = DB::resultado(); 
        
        if(!empty($usuario) && is_array($usuario)){     
            $existe = true;
        }else{
            $existe= false;            
        }
        return $existe;
    }
     
    
    // Método para el Input de Modificacion/Ver Caso - Muestra el usuario que abrio el caso
    public static function mostrarAbiertoPor($caso_id) {
        
        DB::query("SELECT usuario_id, usuario_nombre, usuario_apellido
                    FROM usuarios 
                    INNER JOIN casos ON caso_abiertoPor_id = usuario_id 
                    WHERE caso_id = :caso_id");

        DB::bind(':caso_id', "$caso_id");

        return DB::resultados();
    } 
    
    // Método para el Input de Modificacion/Ver Caso - Muestra el usuario que abrio el caso
    public static function mostrarAsignadoA($caso_id) {
        
        DB::query("SELECT usuario_id,usuario_nombre,usuario_apellido
                    FROM agendas
                    INNER JOIN usuarios ON usuario_id = agenda_usuario_id
                    WHERE agenda_caso_id = :caso_id");

        DB::bind(':caso_id', "$caso_id");

        return DB::resultados();
    } 


    public static function permisos(){
        return $permisos = Permiso::listar_por_rolid($_SESSION['usuario_rol_id']);
    }

    public static function nombre(){
        return $_SESSION['usuario_nombre'];
    }

    public static function apellido(){
        return $_SESSION['usuario_apellido'];
    }


    public static  function usuario_id(){
        return $_SESSION['usuario_id'];
    }


    // Función para saber si un usuario tiene un permiso asignado.
    // Si tiene permiso devuelve un 1 y si no lo tiene un cero.
    public static function puede($permiso){
        $permisos = Permiso::listar_por_rolid($_SESSION['usuario_rol_id']);
        $autorizacion = array_search($permiso, array_column($permisos, 'permiso_variable'));
        if (!empty($autorizacion) || ($autorizacion === 0)) {
            $autorizado = 1;
        } else {
            $autorizado = 0;
        }
        return  $autorizado;
    }

}
