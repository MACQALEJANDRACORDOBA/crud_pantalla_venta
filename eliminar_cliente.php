<?php

$conexion = new mysqli("localhost", "root", "", "gestion_de_huevos");

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