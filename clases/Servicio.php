<?php
/**
 * Clase: Servicio
 *
 *
 * @author ArgenCode
 */

class Servicio {
    
    // Método para listar los servicios usando como parametro el id del caso
    public static function listar($caso_id){
        
        DB::query("SELECT 
                        servicio_id,
                        servicio_prestador_id,
                        servicio_practica_id,
                        servicio_presuntoOrigen,
                        servicio_tipoCambio,
                        moneda_nombre,
                        servicio_presuntoUSD,
                        servicio_autorizado,
                        DATE_FORMAT(servicio_fecha, '%d-%m-%Y') as servicio_fecha,
                        prestador_nombre,
                        practica_nombre,
                        servicio_cargaFueraCobertura,
                        servicio_cancelado,
                        servicio_confirmado,
                        CONCAT(usuario_nombre, ' ', usuario_apellido) as servicio_usuario
                   FROM servicios
                   LEFT JOIN prestadores ON prestador_id = servicio_prestador_id
                   LEFT JOIN practicas ON practica_id = servicio_practica_id
                   LEFT JOIN monedas ON moneda_id = servicio_moneda_id
                   LEFT JOIN usuarios ON usuarios.usuario_id = servicio_usuario_id
                   WHERE servicio_caso_id = :caso_id
                   ORDER BY servicio_id DESC");
        
        DB::bind(':caso_id', "$caso_id");
        
        return DB::resultados();  
    }

    // Método para listar los servicios usando como parametro el id del caso
    public static function listar_asistencia_costo($caso_id, $asistencia_sin_costo){
        
        if($asistencia_sin_costo==1){//sin costo de asistencia 
            DB::query("SELECT *
                       FROM servicios
                       WHERE servicio_caso_id = :caso_id
                       AND servicio_presuntoOrigen > 0");
        }

        if($asistencia_sin_costo==0){//con costo de asistencia 
            DB::query("SELECT *
                       FROM servicios
                       WHERE servicio_caso_id = :caso_id
                       AND servicio_presuntoOrigen = 0");
        }

        DB::bind(':caso_id', "$caso_id");
        
        return DB::resultados();  
    }

    public static function listar_sin_confirmar($caso_id){
        
        DB::query("SELECT 
                        servicio_id,
                        servicio_prestador_id,
                        servicio_practica_id,
                        servicio_presuntoOrigen,
                        servicio_tipoCambio,
                        moneda_nombre,
                        servicio_presuntoUSD,
                        servicio_autorizado,
                        DATE_FORMAT(servicio_fecha, '%d-%m-%Y') as servicio_fecha,
                        prestador_nombre,
                        practica_nombre,
                        servicio_cargaFueraCobertura,
                        servicio_cancelado,
                        servicio_confirmado,
                        CONCAT(usuario_nombre, ' ', usuario_apellido) as servicio_usuario
                   FROM servicios
                   LEFT JOIN prestadores ON prestador_id = servicio_prestador_id
                   LEFT JOIN practicas ON practica_id = servicio_practica_id
                   LEFT JOIN monedas ON moneda_id = servicio_moneda_id
                   LEFT JOIN usuarios ON usuarios.usuario_id = servicio_usuario_id
                   WHERE servicio_caso_id = :caso_id AND servicio_confirmado=0
                   ORDER BY servicio_id DESC");
        
        DB::bind(':caso_id', "$caso_id");
        
        return DB::resultados();  
    }

    public static function listar_confirmados($caso_id){
        
        DB::query("SELECT 
                        servicio_id,
                        servicio_prestador_id,
                        servicio_practica_id,
                        servicio_presuntoOrigen,
                        servicio_tipoCambio,
                        moneda_nombre,
                        servicio_presuntoUSD,
                        servicio_autorizado,
                        DATE_FORMAT(servicio_fecha, '%d-%m-%Y') as servicio_fecha,
                        prestador_nombre,
                        practica_nombre,
                        servicio_cargaFueraCobertura,
                        servicio_cancelado,
                        servicio_confirmado,
                        CONCAT(usuario_nombre, ' ', usuario_apellido) as servicio_usuario
                   FROM servicios
                   LEFT JOIN prestadores ON prestador_id = servicio_prestador_id
                   LEFT JOIN practicas ON practica_id = servicio_practica_id
                   LEFT JOIN monedas ON moneda_id = servicio_moneda_id
                   LEFT JOIN usuarios ON usuarios.usuario_id = servicio_usuario_id
                   WHERE servicio_caso_id = :caso_id AND servicio_confirmado=1
                   ORDER BY servicio_id DESC");
        
        DB::bind(':caso_id', "$caso_id");
        
        return DB::resultados();  
    }

    // Método para contar servicios
    public static function contar($caso_id){

        // $caso_id = 18988;

        DB::query("SELECT servicios.servicio_id
        FROM servicios 
        WHERE servicios.servicio_caso_id = :caso_id");
        
        DB::bind(':caso_id', "$caso_id");
        
        return DB::resultados();

    }

    // Método para insertar una servicio
    public static function insertar($servicio_prestador_id_n, 
                                    $servicio_practica_id_n, 
                                    $servicio_moneda_id_n, 
                                    $servicio_tipoCambio_n,
                                    $servicio_presuntoOrigen_n,
                                    $servicio_justificacion_n,
                                    $caso_id_n,
                                    $permisos,
                                    $sesion_usuario_id) {
        
        // Formateo de Fechas para el INSERT
        $servicio_fecha = date("Y-m-d H:i:s");  
        
        // Consulta el Tipo de Cambio
        $servicio_tipoCambio_n = Moneda::calculo_tc_live($servicio_moneda_id_n);

        // Calcula el Servicio Presunto USD
        $servicio_presuntoUSD_n = $servicio_presuntoOrigen_n / $servicio_tipoCambio_n;
        
        /*
            Consulta si cuando se creo el caso, el voucher tenia cobertura:
            1- Si el resultado es 0, el caso se creo estando EN VIGENCIA
            2- Si el resultado es 1, el caso se creo estando FUERA DE VIGENCIA
        */
        $caso_cobertura = Caso::consultar_caso_enVigencia($caso_id_n);

        /* 
            Consulta la vigencia del voucher, respecto a la fecha actual
            1- Si el resultado es 0, el caso sigue EN VIGENCIA
            2- Si el resultado es 1, el caso esta FUERA DE VIGENCIA
        */
        $vigencia_actual = Caso::consultar_vigencia_actual($caso_id_n);
        
        /* 
            Consulta el estado del caso respecto a si tiene info MANUAL o del WS
            1- Si el resultado es 0, el caso se cargo MANUAL
            2- Si el resultado es 1, el caso se cargo con info del WS
        */
        $info_ws = Caso::caso_info_ws($caso_id_n);

        /* 
            Para la creacion de un Servicio, valida:

            1 - Si el caso fue creado fuera de cobertura.
            2 - Si la fecha actual este fuera de los rangos de cobertura
            3 - Si el caso se cargo de forma MANUAL y aún no se pusieron los datos del WS 
            4 - En caso que estas condiciones se cumplan, el usuario debera tener los permisos correspondientes para crear el servicio
        */
        if  (($info_ws == 1 || Usuario::puede("carga_servicio_casoManual")) &&
             (($caso_cobertura == 0 && $vigencia_actual == 0) || (($caso_cobertura == 1 || $vigencia_actual == 1) && (Usuario::puede("carga_servicio_casoFueraCobertura"))))
        ) {

                DB::query("INSERT INTO servicios (servicio_prestador_id, 
                                            servicio_practica_id, 
                                            servicio_moneda_id,
                                            servicio_tipoCambio,
                                            servicio_presuntoOrigen,
                                            servicio_presuntoUSD,
                                            servicio_justificacion,
                                            servicio_caso_id,
                                            servicio_usuario_id,
                                            servicio_fecha)
                                    VALUES (:servicio_prestador_id,
                                            :servicio_practica_id,
                                            :servicio_moneda_id,
                                            :servicio_tipoCambio,
                                            :servicio_presuntoOrigen,
                                            :servicio_presuntoUSD,
                                            :servicio_justificacion,
                                            :servicio_caso_id,
                                            :servicio_usuario_id,
                                            :servicio_fecha)");
            
                DB::bind(':servicio_prestador_id', "$servicio_prestador_id_n");
                DB::bind(':servicio_practica_id', "$servicio_practica_id_n");
                DB::bind(':servicio_moneda_id', "$servicio_moneda_id_n");
                DB::bind(':servicio_tipoCambio', "$servicio_tipoCambio_n");
                DB::bind(':servicio_presuntoOrigen', "$servicio_presuntoOrigen_n");
                DB::bind(':servicio_presuntoUSD', "$servicio_presuntoUSD_n");
                DB::bind(':servicio_justificacion', "$servicio_justificacion_n");
                DB::bind(':servicio_caso_id', "$caso_id_n");
                DB::bind(':servicio_usuario_id', "$sesion_usuario_id");
                DB::bind(':servicio_fecha', "$servicio_fecha");

                DB::execute();

                $servicio_ultimo_id = DB::lastInsertId();

                /* 
                |   Cuando info_ws == 0 implica que el Caso es Manual
                */
                if ($info_ws == 0) {

                    // Primero > Actualiza el campo 'servicio_casoManual' de la tabla 'servicios'
                    DB::query("UPDATE servicios SET
                                servicio_casoManual = 1
                            WHERE servicio_id = :servicio_id");

                    DB::bind(':servicio_id', "$servicio_ultimo_id");

                    DB::execute();


                    // Segundo > Busca info para el envio de alerta
                    Alerta::datos_alerta_servicios('Caso Manual', $servicio_ultimo_id);
                
                /* 
                |   Cuando caso_cobertura == 1 o vigencia_actual == 1 y el usuario tenga permisos de carga
                |       1- Se marca el caso como servicio_cargaFueraCobertura = 1
                |       2- Se envia un correo de alerta
                */
                } else if (($caso_cobertura == 1 || $vigencia_actual == 1) && Usuario::puede("carga_servicio_casoFueraCobertura") == 1) {
                    
                    // Primero > Actualiza el campo 'servicio_cargaFueraCobertura' de la tabla 'servicios'
                    DB::query("UPDATE servicios SET
                                servicio_cargaFueraCobertura = 1
                            WHERE servicio_id = :servicio_id");

                    DB::bind(':servicio_id', "$servicio_ultimo_id");

                    DB::execute();


                    // Segundo > Busca info para el envio de alerta
                    Alerta::datos_alerta_servicios('Caso Fuera de Cobertura', $servicio_ultimo_id);
                    
            } else {
                echo 200;
            }
        } else {
            echo false;
        }
    }
    
        
    // Método para buscar por ID el servicio
    public static function buscarPorId($servicio_id){
        DB::query("SELECT 
                        servicio_id,
                        servicio_prestador_id,
                        servicio_practica_id,
                        servicio_tipoCambio,
                        servicio_presuntoOrigen,
                        servicio_presuntoUSD,
                        servicio_autorizado,
                        servicio_justificacion,
                        prestador_nombre
                   FROM servicios
                   INNER JOIN prestadores ON prestador_id = servicio_prestador_id
                   WHERE servicio_id = :servicio_id");
        
        DB::bind(':servicio_id', "$servicio_id");
        
        return DB::resultado();        
    }
    
    // Método para buscar la informacion para el armado de la GOP
    public static function armar_gop($servicio_id){
        DB::query("SELECT   prestadores.prestador_nombre as prestador,
                            prestadores.prestador_id,
                            emails.email_email as email,
                            casos.caso_numero as casoNumero, 
                            casos.caso_beneficiarioNombre as nombreBeneficiario,
                            casos.caso_beneficiarioNacimiento as nacimientoBeneficiario,
                            casos.caso_beneficiarioNacimiento as nacimientoBeneficiario_ansi,
                            casos.caso_beneficiarioEdad as edad,
                            casos.caso_numeroVoucher as voucher,
                            casos.caso_sintomas as sintomas,
                            (select telefono_numero 
                            from telefonos
                            where telefono_entidad_id = casos.caso_id and telefono_entidad_tipo = 2 and telefono_principal = 1) as telefonoPrincipal,
                            (select GROUP_CONCAT(DISTINCT telefono_numero ORDER BY telefono_numero SEPARATOR ' / ')
                            from telefonos
                            where telefono_entidad_id = casos.caso_id and telefono_entidad_tipo = 2 and telefono_principal <> 1) as telefonosSecundarios,
                            paises.pais_nombreEspanol as pais,
                            paises.pais_id,
                            ciudades.ciudad_nombre as ciudad,
                            ciudades.ciudad_id,
                            casos.caso_direccion as direccion,
                            casos.caso_codigoPostal as cp,
                            casos.caso_hotel as hotel,
                            casos.caso_habitacion as habitacion
                    FROM servicios
                            LEFT JOIN prestadores ON prestador_id = servicio_prestador_id
                            LEFT JOIN emails ON email_entidad_id = prestador_id AND email_entidad_tipo = 3 AND email_principal = 1
                            LEFT JOIN casos ON caso_id = servicio_caso_id
                            LEFT JOIN paises ON pais_id = caso_pais_id
                            LEFT JOIN ciudades ON ciudad_id = caso_ciudad_id
                    WHERE servicio_id = :servicio_id");
        
        DB::bind(':servicio_id', "$servicio_id");
        
        $resultado = DB::resultado();   
        
        if ($resultado['nacimientoBeneficiario'] !== NULL) {
            $caso_beneficiarioNacimiento = date("d/m/Y", strtotime($resultado['nacimientoBeneficiario']));
            $resultado['nacimientoBeneficiario'] = $caso_beneficiarioNacimiento;
        } else {
            $resultado['nacimientoBeneficiario'] = '';
        }
        
        if ($resultado['edad'] == 0) {
            $resultado['edad'] = '';
        }
        
        return $resultado;
    }
    
    // Método para modificar el servicio
    public static function actualizar($servicio_prestador_id, 
                                    $servicio_practica_id, 
                                    $servicio_moneda_id, 
                                    $servicio_tipoCambio,
                                    $servicio_presuntoOrigen,
                                    $servicio_justificacion,
                                    $servicio_autorizado,
                                    $servicio_id){
            
            // Consulta el Tipo de Cambio
            $servicio_tipoCambio = Moneda::calculo_tc_live($servicio_moneda_id);

            // Calcula el Servicio Presunto USD
            $servicio_presuntoUSD = $servicio_presuntoOrigen / $servicio_tipoCambio;

            // Update
            DB::query("UPDATE servicios SET
                            servicio_prestador_id = :servicio_prestador_id,
                            servicio_practica_id = :servicio_practica_id,
                            servicio_moneda_id = :servicio_moneda_id,
                            servicio_tipoCambio = :servicio_tipoCambio,
                            servicio_presuntoOrigen = :servicio_presuntoOrigen,
                            servicio_presuntoUSD = :servicio_presuntoUSD,
                            servicio_justificacion = :servicio_justificacion,
                            servicio_autorizado = :servicio_autorizado
                        WHERE servicio_id = :servicio_id");
          
            DB::bind(':servicio_prestador_id', "$servicio_prestador_id");
            DB::bind(':servicio_practica_id', "$servicio_practica_id");
            DB::bind(':servicio_moneda_id', "$servicio_moneda_id");
            DB::bind(':servicio_tipoCambio', "$servicio_tipoCambio");
            DB::bind(':servicio_presuntoOrigen', "$servicio_presuntoOrigen");
            DB::bind(':servicio_presuntoUSD', "$servicio_presuntoUSD");
            DB::bind(':servicio_justificacion', $servicio_justificacion);
            DB::bind(':servicio_autorizado', "$servicio_autorizado"); 
            DB::bind(':servicio_id', "$servicio_id");
            
            DB::execute();
            $mensaje = "El servicio fue actualizado con éxito";
            return $mensaje;
    }
    
    public static function actualizar_confirmacion($ids){
        if(is_array($ids)){
            $ids = implode( ",", $ids);
             // Update
             if(!empty($ids)){
                DB::query("UPDATE servicios SET
                                servicio_confirmado = 1
                            WHERE servicio_id in (".$ids.")");
    
                DB::execute();
             }
        }
        $mensaje = "Los servicios fueron actualizados con éxito ";
        return $mensaje;

    } 
       
    // Método para el Select - Lista las practicas en alta servicio
    public static function listarPractica_altaServicio($prestador_id) {
        
        DB::query("SELECT practica_id, practica_nombre, presuntoOrigen 
                    FROM practicas
                    INNER JOIN prestadores_practicas ON prestadorPractica_practica_id = practica_id
                    WHERE prestadorPractica_prestador_id = :prestador_id
                  ");
        
        DB::bind(':prestador_id', "$prestador_id");

        return DB::resultados();
    }
    
    // Método para el Select - Lista las practicas en modificar servicio
    public static function listarPractica_modificarServicio($servicio_id, $prestador_id) {
        
        // DB::query("SELECT practica_id, 
        //                   practica_nombre, 
        //                   presuntoOrigen
        //            FROM practicas
        //            INNER JOIN servicios ON servicio_practica_id = practica_id
        //            WHERE servicio_id = :servicio_id
        //            UNION
        //            SELECT practica_id, practica_nombre 
        //            FROM practicas
        //            INNER JOIN prestadores_practicas ON prestadorPractica_practica_id = practica_id
        //            WHERE prestadorPractica_prestador_id = :prestador_id
        //            ");

        DB::query("SELECT practica_id, practica_nombre,  presuntoOrigen
            FROM practicas
            INNER JOIN prestadores_practicas ON prestadorPractica_practica_id = practica_id
            WHERE prestadorPractica_prestador_id = :prestador_id
        ");

        // DB::bind(':servicio_id', "$servicio_id");
        DB::bind(':prestador_id', "$prestador_id");

        return DB::resultados();
    }
    
    // Método para el Select - Lista las monedas en alta servicio
    public static function listarMoneda_altaServicio() {
        
        DB::query("SELECT moneda_id, 
                          moneda_nombre 
                   FROM monedas
                   ORDER BY moneda_nombre");

        return DB::resultados();
    }
    
    // Método para el Select - Lista las monedas en modificar servicio
    public static function listarMoneda_modificarServicio($servicio_id) {
        
        DB::query("SELECT moneda_id, 
                          moneda_nombre 
                   FROM monedas
                   INNER JOIN servicios ON servicio_moneda_id = moneda_id
                   WHERE servicio_id = :servicio_id
                   UNION
                   SELECT moneda_id, 
                          moneda_nombre 
                   FROM monedas
                   ");

        DB::bind(':servicio_id', "$servicio_id");

        return DB::resultados();
    }
    
    // Método para listar los servicios asignados a un caso para ser utilizado en facturacion. 
    // Solo lista aquellos autorizados
    public static function listar_para_facturacion($caso_id) {
        
        DB::query("SELECT servicios.servicio_id, 
                            servicios.servicio_fecha as fechaServicio, 
                            prestadores.prestador_nombre as prestador, 
                            tipos_prestadores.tipoPrestador_nombre as tipoPrestador, 
                            practicas.practica_nombre as practica, 	   	
                            servicios.servicio_presuntoUSD as presuntoUSD
                FROM servicios
                    LEFT JOIN prestadores on prestadores.prestador_id = servicios.servicio_prestador_id
                    LEFT JOIN tipos_prestadores on tipos_prestadores.tipoPrestador_id = prestadores.prestador_tipoPrestador_id
                    LEFT JOIN practicas on practicas.practica_id = servicios.servicio_practica_id    

                WHERE servicios.servicio_caso_id = :caso_id AND servicio_autorizado = 1
                
                ORDER BY servicios.servicio_id");
                        
        DB::bind(':caso_id', $caso_id);

        return DB::resultados();
    }
    
    // Método para listar los servicios autorizados (Para: Facturacion)
    // Verifica si los servicios estan asignados o no a un item de la factura
    public static function listar_servicios_autorizados($caso_numero, $prestador_id) {
            
        DB::query("SELECT  servicios.servicio_id as servicioId,
                           DATE_FORMAT(servicios.servicio_fecha, '%d-%m-%Y') as servicioFecha,
                           prestadores.prestador_id as prestadorId,
                           prestadores.prestador_nombre as prestador,
                           clientes.cliente_nombre as pagador,
                           practicas.practica_nombre as practica,
                           servicios.servicio_presuntoOrigen as presuntoOrigen,
                           monedas.moneda_nombre as moneda,
                           servicios.servicio_tipoCambio as tipoCambio,
                           servicios.servicio_presuntoUSD as presuntoUSD,
                           casos.caso_numero as casoNumero,
                           casos.caso_id as casoId,
                           (select count(fciServicio_servicio_id) from fci_servicios where fciServicio_servicio_id = servicioId) as asignado,
                           casos.caso_cliente_id
                    FROM servicios
                        LEFT JOIN prestadores ON prestador_id = servicio_prestador_id
                        LEFT JOIN practicas ON practica_id = servicio_practica_id
                        LEFT JOIN monedas ON moneda_id = servicio_moneda_id
                        LEFT JOIN casos ON caso_id = servicio_caso_id
                        LEFT JOIN clientes ON cliente_id = caso_cliente_id
                    WHERE servicio_caso_id = (select caso_id from casos where caso_numero = :caso_numero) AND
                          servicio_autorizado = true AND
                          servicio_prestador_id = :prestador_id
                    ORDER BY servicio_id DESC");
        
        DB::bind(':caso_numero', "$caso_numero");
        DB::bind(':prestador_id', $prestador_id);
        
        return DB::resultados();
    }
    
    // Método para listar los servicios autorizados asignados a un item 
    // mas
    // los servicios autorizados que no estan asignados a ningun item (Para: Facturacion)
    public static function listar_servicios_asignadosPorItem($fci_id, 
                                                             $caso_id, 
                                                             $prestador_id) {
            
        DB::query("SELECT servicios.servicio_id as servicioId,
                          DATE_FORMAT(servicios.servicio_fecha, '%d-%m-%Y') as servicioFecha,
                          prestadores.prestador_id as prestadorId,
                          prestadores.prestador_nombre as prestador,
                          practicas.practica_nombre as practica,
                          servicios.servicio_presuntoOrigen as presuntoOrigen,
                          monedas.moneda_nombre as moneda,
                          servicios.servicio_tipoCambio as tipoCambio,
                          servicios.servicio_presuntoUSD as presuntoUSD,
                          casos.caso_numero as casoNumero,
                          casos.caso_id as casoId,
                          (select count(fciServicio_servicio_id) from fci_servicios where fciServicio_servicio_id = servicioId) as asignado      
                   FROM servicios
                        LEFT JOIN prestadores ON prestador_id = servicio_prestador_id
                        LEFT JOIN practicas ON practica_id = servicio_practica_id
                        LEFT JOIN monedas ON moneda_id = servicio_moneda_id
                        LEFT JOIN casos ON caso_id = servicio_caso_id
                        LEFT JOIN fci_servicios ON fciServicio_servicio_id = servicio_id
                   WHERE fciServicio_fcItem_id = :fci_id
                   UNION
                   SELECT servicios.servicio_id as servicioId,
                          DATE_FORMAT(servicios.servicio_fecha, '%d-%m-%Y') as servicioFecha,
                          prestadores.prestador_id as prestadorId,
                          prestadores.prestador_nombre as prestador,
                          practicas.practica_nombre as practica,
                          servicios.servicio_presuntoOrigen as presuntoOrigen,
                          monedas.moneda_nombre as moneda,
                          servicios.servicio_tipoCambio as tipoCambio,
                          servicios.servicio_presuntoUSD as presuntoUSD,
                          casos.caso_numero as casoNumero,
                          casos.caso_id as casoId,
                          (select count(fciServicio_servicio_id) from fci_servicios where fciServicio_servicio_id = servicioId) as asignado
                   FROM servicios
                        LEFT JOIN prestadores ON prestador_id = servicio_prestador_id
                        LEFT JOIN practicas ON practica_id = servicio_practica_id
                        LEFT JOIN monedas ON moneda_id = servicio_moneda_id
                        LEFT JOIN casos ON caso_id = servicio_caso_id
                    WHERE servicio_caso_id = :caso_id
                    AND servicio_prestador_id = :prestador_id
                    AND servicio_autorizado = true
                    AND servicio_id NOT IN (select fciServicio_servicio_id from fci_servicios)");
        
        DB::bind(':fci_id', $fci_id);
        DB::bind(':caso_id', $caso_id);
        DB::bind(':prestador_id', $prestador_id);
        
        return DB::resultados();
    }
    
    
    // Metodo para buscar si existen servicios con ITEMS de Factura asociados
    public static function servicio_con_fci($servicio_id) {
        
        DB::query("SELECT COUNT(fciServicio_servicio_id) as cant_fci
                   FROM fci_servicios
                   WHERE fciServicio_servicio_id = :servicio_id");
                        
        DB::bind(':servicio_id', $servicio_id);
        
        $resultado = DB::resultado();
        
        return $resultado['cant_fci'];
    }
}