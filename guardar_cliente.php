<?php
require_once 'conexion.php'; 
$pdo = Conexion::conectar(); 

// Recibimos el nombre del cliente
$nombre = $_POST['nombre'];

try {
    // 1. Preparamos la sentencia para insertar
    $sql = "INSERT INTO clientes (nombre) VALUES (?)";
    $stmt = $pdo->prepare($sql);
    
    // 2. Ejecutamos pasando el nombre en un arreglo
    if ($stmt->execute([$nombre])) {
        
        // Obtenemos el ID generado automáticamente
        $id = $pdo->lastInsertId();

        // Devolvemos los datos en formato JSON
        echo json_encode([
            "id" => $id,
            "nombre" => $nombre
        ]);
    } else {
        echo json_encode(["error" => "No se pudo insertar"]);
    }

} catch (PDOException $e) {
    // Si hay un error de base de datos (ej. nombre duplicado si fuera único)
    echo json_encode(["error" => $e->getMessage()]);
}
?>