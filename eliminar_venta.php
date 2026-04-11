<?php

$conexion = new mysqli("localhost", "root", "", "gestion_de_huevos");


$id_venta = $_POST['id_venta'];

/* eliminar primero los detalles */
$sql1 = "DELETE FROM detalle_venta WHERE id_venta = '$id_venta'";
mysqli_query($conexion,$sql1);

/* eliminar la venta */
$sql2 = "DELETE FROM venta WHERE id_venta = '$id_venta'";
mysqli_query($conexion,$sql2);

echo "ok";

?>