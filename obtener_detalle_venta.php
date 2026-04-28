<?php
require_once 'conexion.php'; 
$pdo = Conexion::conectar(); 

// Recibimos el ID de la venta
$id_venta = $_GET['id_venta']; 


$sql = "SELECT 
            v.fecha,
            v.estado,
            c.nombre AS cliente,
            d.id_detalle_venta,
            p.tipo AS producto,
            d.cantidad,
            d.precio_unitario,
            d.subtotal,
            d.nota
        FROM venta v
        INNER JOIN clientes c ON v.id_cliente = c.id_cliente
        INNER JOIN detalle_venta d ON v.id_venta = d.id_venta
        INNER JOIN producto p ON d.id_producto = p.id_producto
        WHERE v.id_venta = ?";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_venta]);

    // Usamos fetchAll para obtener todas las filas (por si hay varios productos en una venta)
    $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($detalles);

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>







