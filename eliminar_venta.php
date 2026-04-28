<?php
require_once 'conexion.php'; 
$pdo = Conexion::conectar(); 

// Recibimos el ID de la venta
$id_venta = $_POST['id_venta'];

try {
    // 1. Eliminar primero los detalles (por la relación de llaves foráneas)
    $sql1 = "DELETE FROM detalle_venta WHERE id_venta = ?";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute([$id_venta]);

    // 2. Eliminar la venta principal
    $sql2 = "DELETE FROM venta WHERE id_venta = ?";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute([$id_venta]);

    // Si todo salió bien, respondemos "ok"
    echo "ok";

} catch (PDOException $e) {
    // Si hay un error (por ejemplo, un problema de llaves foráneas), lo avisamos
    echo "ERROR: " . $e->getMessage();
}
?>

