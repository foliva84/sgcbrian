<?php
/**
 * Clase: Cliente
 *
 *
 * @author ArgenCode
 */

class Cliente {  
    
    const ENTIDAD =  Entidad::CLIENTE;
    
    // Método para devolver el cliente_id en base a un Número de Voucher
    public static function obtener_cliente_coris_id($numero_voucher) {

        // Obtiene las Primeras letras para Obtener el ID de Cliente CORIS
        $primeras_dos_letras = substr($numero_voucher, 0, 2);

        if ($primeras_dos_letras == 'A1') {
            $primeras_dos_letras = substr($numero_voucher, 2, 2);
        }

        switch ($primeras_dos_letras) {
            case 'AR': // Cliente CORIS Argentina (id. 1)
                $cliente_id = 1;
                break;
            case 'UY': // Cliente CORIS Uruguay (id. 2)
                $cliente_id = 2;
                break;
            case 'CL': // Cliente CORIS Chile (id. 3)
                $cliente_id = 3;
                break;
            case 'PY': // Cliente CORIS Paraguay (id. 4)
                $cliente_id = 4;
            case 'PA': // Cliente CORIS Paraguay (id. 4)
                $cliente_id = 4;
                break;   
            case 'BV': // Cliente CORIS Bolivia (id. 13)
                $cliente_id = 13;
                break;   
            case 'CO': // Cliente CORIS Colombia (id. 14)
                $cliente_id = 14;
                break;   
            case 'PE': // Cliente CORIS Perú (id. 15)
                $cliente_id = 15;
                break;
            case 'EC': // Cliente CORIS Ecuador (id. 16)
                $cliente_id = 16;
                break;
            default:
                $cliente_id = 0;
         }

         return $cliente_id;
         
    }

    // Método para listar los clientes
    public static function listar(){
        DB::query("SELECT cliente_id,
                          cliente_nombre,
                          tipoCliente_nombre,
                          cliente_abreviatura,
                          cliente_direccion,
                          pais_nombreEspanol,
                          ciudad_nombre,
                          cliente_activo
                   FROM clientes LEFT JOIN paises on pais_id = cliente_pais_id
                                 LEFT JOIN ciudades on ciudad_id = cliente_ciudad_id
                                 LEFT JOIN tipos_clientes on tipoCliente_id = cliente_tipoCliente_id");
        
        return DB::resultados();
    }
    
    
    // Método para listar los clientes
    public static function listar_filtrado($cliente_nombre_buscar, 
                                           $cliente_pais_id_buscar,
                                           $cliente_tipoCliente_id_buscar){
        
        If ($cliente_pais_id_buscar == ''){
            $cliente_pais_id_buscar = NULL;
        }
        If ($cliente_tipoCliente_id_buscar == ''){
            $cliente_tipoCliente_id_buscar = NULL;
        }
        
        DB::query("SELECT cliente_id,
                          cliente_nombre,
                          tipoCliente_nombre,
                          cliente_abreviatura,
                          cliente_direccion,
                          pais_nombreEspanol,
                          ciudad_nombre,
                          cliente_activo,
                          cliente_modifica
                   FROM clientes 
                   LEFT JOIN paises on pais_id = cliente_pais_id
                   LEFT JOIN ciudades on ciudad_id = cliente_ciudad_id
                   LEFT JOIN tipos_clientes on tipoCliente_id = cliente_tipoCliente_id
                   WHERE cliente_nombre LIKE :cliente_nombre
                   AND cliente_pais_id = COALESCE(:cliente_pais_id,cliente_pais_id)
                   AND cliente_tipoCliente_id = COALESCE(:cliente_tipoCliente_id,cliente_tipoCliente_id)
                   LIMIT 50");
        
        DB::bind(':cliente_nombre', "%$cliente_nombre_buscar%");
        DB::bind(':cliente_pais_id', $cliente_pais_id_buscar);
        DB::bind(':cliente_tipoCliente_id', $cliente_tipoCliente_id_buscar);
        
        return DB::resultados();
    }
    
    
    public static function listar_filtrado_contar($cliente_nombre_buscar, 
                                                  $cliente_pais_id_buscar,
                                                  $cliente_tipoCliente_id_buscar) {
        
        If ($cliente_pais_id_buscar == ''){
            $cliente_pais_id_buscar = NULL;
        }
        If ($cliente_tipoCliente_id_buscar == ''){
            $cliente_tipoCliente_id_buscar = NULL;
        }
    
        DB::query("SELECT COUNT(*) AS registros
                   FROM clientes 
                   LEFT JOIN paises on pais_id = cliente_pais_id
                   LEFT JOIN ciudades on ciudad_id = cliente_ciudad_id
                   LEFT JOIN tipos_clientes on tipoCliente_id = cliente_tipoCliente_id
                   WHERE cliente_nombre LIKE :cliente_nombre
                   AND cliente_pais_id = COALESCE(:cliente_pais_id,cliente_pais_id)
                   AND cliente_tipoCliente_id = COALESCE(:cliente_tipoCliente_id,cliente_tipoCliente_id)
                ");
        
        DB::bind(':cliente_nombre', "%$cliente_nombre_buscar%");
        DB::bind(':cliente_pais_id', $cliente_pais_id_buscar);
        DB::bind(':cliente_tipoCliente_id', $cliente_tipoCliente_id_buscar);
        
        return DB::resultado();
    }
    
    
    // Método para insertar un cliente
    public static function insertar($cliente_nombre_n, 
                                    $cliente_razonSocial_n,
                                    $cliente_abreviatura_n,
                                    $cliente_tipoCliente_id_n,
                                    $cliente_paginaWeb_n,
                                    $cliente_direccion_n,
                                    $cliente_codigoPostal_n,
                                    $cliente_pais_id_n, 
                                    $cliente_ciudad_id_n, 
                                    $cliente_observaciones_n){
        
        DB::query("INSERT INTO clientes (cliente_nombre,
                                         cliente_razonSocial,
                                         cliente_abreviatura,
                                         cliente_tipoCliente_id,
                                         cliente_paginaWeb,
                                         cliente_direccion,
                                         cliente_codigoPostal,
                                         cliente_pais_id,
                                         cliente_ciudad_id,
                                         cliente_observaciones,
                                         cliente_activo)
                                VALUES (:cliente_nombre_n,
                                        :cliente_razonSocial_n,
                                        :cliente_abreviatura_n,
                                        :cliente_tipoCliente_id_n,
                                        :cliente_paginaWeb_n,
                                        :cliente_direccion_n,
                                        :cliente_codigoPostal_n,
                                        :cliente_pais_id_n,
                                        :cliente_ciudad_id_n,
                                        :cliente_observaciones_n,
                                        :cliente_activo_n)");
        
        DB::bind(':cliente_nombre_n', "$cliente_nombre_n");
        DB::bind(':cliente_razonSocial_n', "$cliente_razonSocial_n");
        DB::bind(':cliente_abreviatura_n', "$cliente_abreviatura_n");
        DB::bind(':cliente_tipoCliente_id_n', "$cliente_tipoCliente_id_n");
        DB::bind(':cliente_paginaWeb_n', "$cliente_paginaWeb_n");
        DB::bind(':cliente_direccion_n', "$cliente_direccion_n");
        DB::bind(':cliente_codigoPostal_n', "$cliente_codigoPostal_n");
        DB::bind(':cliente_pais_id_n', "$cliente_pais_id_n");
        DB::bind(':cliente_ciudad_id_n', "$cliente_ciudad_id_n");
        DB::bind(':cliente_observaciones_n', "$cliente_observaciones_n");
        DB::bind(':cliente_activo_n', true);
                
        DB::execute();     
        
        $cliente_id = DB::lastInsertId();
        
        return $cliente_id;
    }
    
    
    // Método para actualizar un cliente
    public static function actualizar($cliente_nombre, 
                                      $cliente_razonSocial, 
                                      $cliente_abreviatura,
                                      $cliente_tipoCliente_id,
                                      $cliente_paginaWeb,
                                      $cliente_direccion,
                                      $cliente_codigoPostal,
                                      $cliente_pais_id, 
                                      $cliente_ciudad_id, 
                                      $cliente_observaciones, 
                                      $cliente_id){                     
        $existe = self::existeUpdate($cliente_nombre, $cliente_id);
        
        If ($existe == 1){
            $mensaje = "El cliente ya existe en la base";
            return $mensaje;
        } else {
        
                DB::query("
                            UPDATE clientes SET
                                cliente_nombre = :cliente_nombre,
                                cliente_razonSocial = :cliente_razonSocial,
                                cliente_abreviatura = :cliente_abreviatura,
                                cliente_tipoCliente_id = :cliente_tipoCliente_id,
                                cliente_paginaWeb = :cliente_paginaWeb,
                                cliente_direccion = :cliente_direccion,
                                cliente_codigoPostal = :cliente_codigoPostal,
                                cliente_pais_id = :cliente_pais_id,
                                cliente_ciudad_id = :cliente_ciudad_id,
                                cliente_observaciones = :cliente_observaciones
                            WHERE cliente_id = :cliente_id
                          ");

        DB::bind(':cliente_nombre', "$cliente_nombre");
        DB::bind(':cliente_razonSocial', "$cliente_razonSocial");
        DB::bind(':cliente_abreviatura', "$cliente_abreviatura");
        DB::bind(':cliente_tipoCliente_id', "$cliente_tipoCliente_id");
        DB::bind(':cliente_paginaWeb', "$cliente_paginaWeb");
        DB::bind(':cliente_direccion', "$cliente_direccion");
        DB::bind(':cliente_codigoPostal', "$cliente_codigoPostal");
        DB::bind(':cliente_pais_id', "$cliente_pais_id");
        DB::bind(':cliente_ciudad_id', "$cliente_ciudad_id");
        DB::bind(':cliente_observaciones', "$cliente_observaciones");
        DB::bind(':cliente_id', "$cliente_id");

        DB::execute();
        
        $mensaje = "El cliente fue actualizado con éxito";
        return $mensaje;
        }
    }
    
    
    // Método para verificar si existe el cliente antes de hacer un insert   
    public static function existe($cliente_nombre){
        DB::query("SELECT 
                        cliente_id,
                        cliente_nombre,
                        cliente_activo
                   FROM clientes
                   WHERE cliente_nombre = :cliente_nombre");
        
        DB::bind(':cliente_nombre', "$cliente_nombre");
                
        $cliente = DB::resultado(); 
        
        if(!empty($cliente) && is_array($cliente)){       
            $existe = true;
        }else{
            $existe = false;            
        }
        return $existe;      
    }
    
    
    // Método para verificar si existe el cliente antes de hacer un update
    public static function existeUpdate($cliente_nombre, $cliente_id){ 
        DB::query("SELECT 
                        cliente_id,
                        cliente_nombre,
                        cliente_activo
                   FROM clientes 
                   WHERE cliente_nombre = :cliente_nombre
                   AND cliente_id <> :cliente_id");
        
        DB::bind(':cliente_nombre', "$cliente_nombre");
        DB::bind(':cliente_id', "$cliente_id");
       
        $cliente = DB::resultado(); 
        
        if(!empty($cliente) && is_array($cliente)){       
            $existe = true;
        }else{
            $existe= false;            
        }
        return $existe;      
    }
    
    
    // Método para leer registro de un cliente
    public static function buscarPorId($cliente_id){
        DB::query("SELECT 
                        cliente_id,
                        cliente_nombre,
                        cliente_razonSocial,
                        cliente_abreviatura,
                        cliente_tipoCliente_id,
                        cliente_paginaWeb,
                        cliente_direccion,
                        cliente_codigoPostal,
                        cliente_pais_id,
                        cliente_ciudad_id,
                        cliente_observaciones,
                        cliente_activo,
                        ciudad_nombre
                   FROM clientes INNER JOIN ciudades ON ciudad_id = cliente_ciudad_id
                   WHERE cliente_id = :cliente_id");
        
        DB::bind(':cliente_id', "$cliente_id");
        
        $resultado = DB::resultado();

        return $resultado;
    }
    
    
    // Método para desactivar un cliente.
    public static function borradoLogico($cliente_id){
        DB::query("
                    UPDATE clientes SET
                        cliente_activo = :cliente_activo                               
                    WHERE cliente_id = :cliente_id
                  ");
        
        DB::bind(':cliente_activo', false);
        DB::bind(':cliente_id', "$cliente_id");
        
        DB::execute();
        $mensaje = "ok";
        return $mensaje;
    }
    
    
    // Método para activar un cliente.
    public static function reActivar($cliente_id){
        DB::query("
                    UPDATE clientes SET
                               cliente_activo = :cliente_activo                               
                    WHERE cliente_id = :cliente_id
                  ");
        
        DB::bind(':cliente_activo', true);
        DB::bind(':cliente_id', "$cliente_id");
        
        DB::execute();
        $mensaje = "ok";
        return $mensaje;
    }
    
    
    // Método para el Select - Lista los Clientes en Altas
    public static function formulario_alta_clientes(){
        
        DB::query("SELECT cliente_id, 
                          cliente_nombre
                   FROM clientes 
                   WHERE cliente_activo = 1");
        
        return DB::resultados();
    }
    
    
    // Método para el Select - Lista los clientes en Modificar Caso
    public static function listar_modificacion_casos($caso_id){
        
        DB::query("SELECT cliente_id, 
                          cliente_nombre 
                   FROM clientes 
                        INNER JOIN casos ON caso_cliente_id = cliente_id 
                   WHERE caso_id = :caso_id
                   UNION
                   SELECT cliente_id, 
                          cliente_nombre 
                   FROM clientes
                   WHERE cliente_activo = 1");

        DB::bind(':caso_id', $caso_id);

        return DB::resultados();
    }
    
    // Método para el Select - Lista los clientes en Modificar Item de Facturas
    public static function listar_clientes_modificacionItemFactura($pagador_id){
        
        DB::query("SELECT cliente_id, 
                          cliente_nombre 
                   FROM clientes 
                        INNER JOIN fc_items ON fcItem_clientePagador_id = cliente_id 
                   WHERE fcItem_clientePagador_id = :pagador_id
                   UNION
                   SELECT cliente_id, 
                          cliente_nombre 
                   FROM clientes
                   WHERE cliente_activo = 1");

        DB::bind(':pagador_id', $pagador_id);

        return DB::resultados();
    }
    
    
    // Método para el Select - Lista los tipos de clientes en alta de cliente
    public static function listar_tiposClientes_alta_Cliente(){
        
        DB::query("SELECT tipoCliente_id, 
                          tipoCliente_nombre
                   FROM tipos_clientes 
                   WHERE tipoCliente_activo = 1 
                   ORDER BY tipoCliente_nombre");
        
        return DB::resultados();
    }
    
    
    // Método para el Select - Lista los tipos de clientes en modificar cliente
    public static function listar_tiposClientes_modificacion_Cliente($cliente_id){
        DB::query(" 
                    SELECT tipoCliente_id, tipoCliente_nombre 
                    FROM tipos_clientes 
                    WHERE tipoCliente_id IN (SELECT cliente_tipoCliente_id FROM `clientes` WHERE cliente_id = :cliente_id) 
                    UNION
                    SELECT tipoCliente_id, tipoCliente_nombre 
                    FROM tipos_clientes 
                  ");
        
        DB::bind(':cliente_id', "$cliente_id");
    
        return DB::resultados();
    }
    
    
    // Método para listar los productos asociados a los clientes
    public static function listar_productos($cliente_id){
        
        DB::query("SELECT clientes_products.clienteProduct_id, product.product_name
                    FROM clientes_products 
                        LEFT JOIN product on product.product_id_interno = clientes_products.clienteProduct_product_id
                    WHERE 
                        clientes_products.clienteProduct_cliente_id = :clienteProduct_cliente_id");
        
        DB::bind(':clienteProduct_cliente_id', $cliente_id);
        
        return DB::resultados();
    }
    
    
    // Método para insertar un producto al cliente
    public static function insertar_producto($cliente_id, $producto_id){   
        
        $existe = self::existe_producto($cliente_id, $producto_id);
        
        If ($existe == 1){
            
            return $existe;
                
        } else {
            
            DB::query("INSERT INTO clientes_products 
                            (clienteProduct_cliente_id,
                             clienteProduct_product_id)
                        VALUES (:clienteProduct_cliente_id,
                                :clienteProduct_product_id)");

            DB::bind(':clienteProduct_cliente_id', $cliente_id);
            DB::bind(':clienteProduct_product_id', $producto_id);

            DB::execute();
            
        }
        
    }
    
    
    // Método para evaluar si el cliente ya posee el producto ingresado
    public static function existe_producto($cliente_id, $producto_id){
        
        DB::query("SELECT COUNT(*) as cantidad
                    FROM clientes_products
                    WHERE clienteProduct_cliente_id = :clienteProduct_cliente_id
                    AND clienteProduct_product_id = :clienteProduct_product_id");
        
        DB::bind(':clienteProduct_cliente_id', "$cliente_id");
        DB::bind(':clienteProduct_product_id', "$producto_id");
        
        $resultado = DB::resultado(); 
        $cantidad = $resultado['cantidad'];
        
        if($cantidad >=1){
            $existe = true;
        } else {
            $existe = false;
        }
                
        return $existe;      
    }
    
    
    // Método para eliminar un producto del cliente
    public static function eliminar_producto($clienteProducto_id){   
        DB::query("DELETE FROM clientes_products
                    WHERE clienteProduct_id = :clienteProduct_id");
        
        DB::bind(':clienteProduct_id', $clienteProducto_id);

        DB::execute();     
    }
    
    
    // Método para el Select - Lista los clientes en Modificar Caso y Alta Facturas
    public static function listar_facturasPagador_modificacion($factura_id){
        
        DB::query("SELECT cliente_id, cliente_nombre
                    FROM facturas 
                        INNER JOIN clientes ON clientes.cliente_id = facturas.factura_clientePagador_id 
                    WHERE factura_id = :factura_id
                    UNION
                    SELECT cliente_id, cliente_nombre 
                    FROM clientes
                    WHERE cliente_activo = 1");

        DB::bind(':factura_id', "$factura_id");

        return DB::resultados();
    }
}