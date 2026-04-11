<?php

$conexion = new mysqli("localhost", "root", "", "gestion_de_huevos");

$id = $_POST['id_venta'];
$estado = $_POST['estado'];

$sql = "UPDATE venta SET estado='$estado' WHERE id_venta=$id";

if($conexion->query($sql)){
    echo "ok";
}else{
    echo "error";
}

?>