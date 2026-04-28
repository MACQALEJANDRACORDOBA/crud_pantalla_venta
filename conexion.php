<?php
class Conexion {
    public static function conectar() {  //static, eso cambia la forma de usar la clase. 👉 Significa que NO necesitas crear un objeto
        //Separas configuración (muy buena práctica)
        $host = "localhost";
        $db   = "contando_huevos"; 
        $user = "root";
        $pass = "";
        $charset = "utf8mb4";

        try { 
            $dsn = "mysql:host=$host;dbname=$db;charset=$charset"; //¿Qué es DSN? 👉 Data Source Name. Es la “cadena de conexión”.
            $pdo = new PDO($dsn, $user, $pass); //Aquí creas la conexión
            
            
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);// Configuración para que PHP nos avise de errores de SQL
            
            return $pdo;// Devuelves la conexión → reutilizable
        } catch (PDOException $e) {
            // Si algo falla, nos dirá exactamente qué pasó
            die("Error de conexión: " . $e->getMessage());
        }
    }
}
?>