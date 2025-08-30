<?php
/**
 * Clase: DB
 *
 * @author ArgenCode
 */

// Local
//define("DB_HOST", "localhost:3308");
//define("DB_NAME", "sgc_prod");
//define("DB_USER", "root");
//define("DB_PASS", "3e2d4d0e08af49d41e922cf5453f763ea6ed76378152ecef17ce93a85db175bd");

//define("DB_HOST", "mariadbsgcprd.corisnet");
//define("DB_NAME", "sgc_prod");
//define("DB_USER", "dbsgccoris");
//define("DB_PASS", "G8E6$&9pB9FA");

define("DB_HOST", "localhost:3306");
define("DB_NAME", "sgc_dev");
define("DB_USER", "root");
define("DB_PASS", "");

class DB {
       
    private static $host = DB_HOST;
    private static $base = DB_NAME;
    private static $usuario = DB_USER;
    private static $password = DB_PASS;
    
    // El statement a ejecutar
    private static $stmt;
    private static $conn;
    
    private static function conecta() {
        $host = self::$host;
        $base = self::$base;
        $usuario = self::$usuario;
        $password = self::$password;
            
        $dsn = 'mysql:host=' . $host . ';dbname=' . $base;
        $opciones = array(
                          PDO::ATTR_PERSISTENT => true,
                          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                         );
        try { 
            self::$conn = new PDO($dsn, $usuario, $password, $opciones);
            self::$conn -> exec("SET CHARACTER SET utf8");
        } catch (PDOException $e) {
            die("Por alguna razón ha fallado la conexión a la base de datos. " . $e->getMessage());
        }
    }
    
   
    private static function conecta_transac() {
        $host = self::$host;
        $base = self::$base;
        $usuario = self::$usuario;
        $password = self::$password;
            
        $dsn = 'mysql:host=' . $host . ';dbname=' . $base;
        $opciones = array(
                          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                          PDO::ATTR_AUTOCOMMIT, false
                         );
        try { 
            self::$conn = new PDO($dsn, $usuario, $password, $opciones);
            self::$conn -> exec("SET CHARACTER SET utf8");
        } catch (PDOException $e) {
            die("Por alguna razón ha fallado la conexión a la base de datos. " . $e->getMessage());
        }
    }

    



    private static function desconecta() {
        self::$conn = null;
    }
    
    
    public static function bind($param, $value, $type = null){
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        self::$stmt->bindValue($param, $value, $type);
    }
    
    
    
    
    public static function query($query){
        self::conecta();
        self::$stmt = self::$conn->prepare($query);
    }
    
    
    public static function execute(){
        try {
            return self::$stmt->execute();
        } catch (PDOException $e) {
            die("Por alguna razón ha fallado la ejecución de su consulta" . $e->getMessage());
        }
        // self::desconecta();
    }
    
    
    public static function resultado(){
        self::execute();   
        return self::$stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    
    public static function resultados(){
        self::execute();
        return self::$stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public static function rowCount(){
        return self::$stmt->rowCount();
    }
    
    public static function lastInsertId(){
        return self::$conn->lastInsertId();
    }

    
    // Métodos para transacciones
    
    
    public static function conecta_t(){
        self::conecta_transac();
    }
    
    public static function query_t($query){
        self::$stmt = self::$conn->prepare($query);
    }
    
    
    public static function beginTransaction_t(){
        self::$conn->beginTransaction();
    }

    public static function endTransaction_t(){
        self::$conn->commit();
    }
    
    public static function cancelTransaction_t(){
        self::$conn->rollBack();
    }


}
