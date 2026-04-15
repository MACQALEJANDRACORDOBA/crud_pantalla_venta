<?php

$conexion = new mysqli("localhost", "root", "", "gestion_de_huevos");

$nombre = $_POST['nombre'];

$sql = "INSERT INTO clientes (nombre) VALUES ('$nombre')";

if($conexion->query($sql)){

    $id = $conexion->insert_id;

    echo json_encode([
        "id"=>$id,
        "nombre"=>$nombre
    ]);

}else{
    echo "error";
}

?>