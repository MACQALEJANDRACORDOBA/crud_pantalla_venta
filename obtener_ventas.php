<?php
$conexion = new mysqli("localhost", "root", "", "gestion_de_huevos");

$sql = "SELECT 
            v.id_venta,
            v.fecha,
            c.nombre AS cliente,
            v.estado,
            p.tipo AS tamano,
            SUM(d.cantidad) AS cantidad_total,
            SUM(d.subtotal) AS total,
            MAX(d.nota) AS nota
        FROM venta v
        INNER JOIN clientes c ON v.id_cliente = c.id_cliente
        INNER JOIN detalle_venta d ON v.id_venta = d.id_venta
        INNER JOIN producto p ON d.id_producto = p.id_producto
        GROUP BY v.id_venta, v.fecha, c.nombre, v.estado, p.tipo
        ORDER BY v.id_venta DESC";

$resultado = $conexion->query($sql);

$ventas = [];

while ($fila = $resultado->fetch_assoc()) {
    $ventas[] = $fila;
}

echo json_encode($ventas);
?>

