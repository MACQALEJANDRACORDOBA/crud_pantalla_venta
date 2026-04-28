<?php
require_once 'conexion.php'; 
$pdo = Conexion::conectar(); 

// Recibimos los datos por POST
$id     = $_POST['id_venta'];
$estado = $_POST['estado'];

try {
    // 1. Preparamos la sentencia con marcadores (?)
    $sql = "UPDATE venta SET estado = ? WHERE id_venta = ?";
    $stmt = $pdo->prepare($sql);
    
    // 2. Ejecutamos pasando los valores en el orden de los signos de interrogación
    if ($stmt->execute([$estado, $id])) {
        echo "ok";
    } else {
        echo "error";
    }

} catch (PDOException $e) {
    // Si algo falla en la base de datos, capturamos el error
    echo "ERROR: " . $e->getMessage();
}
?>