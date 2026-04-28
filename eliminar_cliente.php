<?php

require_once 'conexion.php'; // Esto "trae" el molde de la conexión
$pdo = Conexion::conectar(); // Esto activa la conexión y la guarda en $pdo

if ($conexion->connect_error) {
    die("Error de conexión");
}

$id = $_POST["id"];

// verificar si el cliente tiene ventas
$sqlCheck = "SELECT COUNT(*) as total FROM venta WHERE id_cliente = '$id'";
$resultado = $conexion->query($sqlCheck);
$fila = $resultado->fetch_assoc();

if($fila["total"] > 0){
    echo "tiene_ventas";
    exit();
}

// si no tiene ventas se puede eliminar
$sql = "DELETE FROM clientes WHERE id_cliente = '$id'";

if($conexion->query($sql)){
    echo "ok";
}else{
    echo "error";
}

$conexion->close();

?>


<?php
require_once 'conexion.php'; 
$pdo = Conexion::conectar(); 

$id = $_POST["id"];
//regla de negocio: no puedo borrar un cliente si ya le he vendido algo. ¡Eso está excelente! Evita que tu base de datos quede con "ventas huérfanas".
try {
    // 1. Verificar si el cliente tiene ventas
    // Usamos fetchColumn() que es perfecto para contar cosas rápidamente
    $sqlCheck = "SELECT COUNT(*) FROM venta WHERE id_cliente = ?";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute([$id]);
    $totalVentas = $stmtCheck->fetchColumn();

    if ($totalVentas > 0) {
        echo "tiene_ventas";
        exit();
    }

    // 2. Si no tiene ventas, procedemos a eliminar
    $sql = "DELETE FROM clientes WHERE id_cliente = ?";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$id])) {
        echo "ok";
    } else {
        echo "error";
    }

} catch (PDOException $e) {
    // En caso de un error técnico en la base de datos
    echo "ERROR: " . $e->getMessage();
}
?>