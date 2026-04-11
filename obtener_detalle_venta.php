<?php

header('Content-Type: application/json'); #esto es para evitar errores de codificación

$conexion = new mysqli("localhost", "root", "", "gestion_de_huevos");

$id = $_GET['id'];

$sql = "SELECT 
venta.id_venta,
venta.cliente,
venta.fecha,
venta.estado,
SUM(detalle_venta.subtotal) AS total
FROM venta
LEFT JOIN detalle_venta 
ON venta.id_venta = detalle_venta.id_venta
WHERE venta.id_venta = $id
GROUP BY venta.id_venta";

$resultado = $conexion->query($sql);

$venta = $resultado->fetch_assoc();

echo json_encode($venta);

?>