
<?php

$conexion = new mysqli("localhost", "root", "", "gestion_de_huevos");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}


// RECIBIR DATOS
$cliente = $_POST['cliente'];
$cantidad = intval($_POST['cantidad']);
$precio = intval(str_replace('.', '', $_POST['precio']));// intval convierte a número. y str_replace Quita los puntos (.) del número. ejmplo:40.000 Eso es texto ❌ (no número válido para BD)
                                                  //Usuario ve: 40.000 (bonito) 👉 PHP guarda: 40000 (correcto)

$id_producto = $_POST['tamano'];  
$estado = isset($_POST["estado"]) ? $_POST["estado"] : "pendiente";                
$nota = $_POST['nota'];



// CALCULAR SUBTOTAL
$subtotal = $precio;

// FECHA ACTUAL
$fecha = date("Y-m-d");

// 1️⃣ INSERTAR EN VENTA
$sqlVenta = "INSERT INTO venta (fecha, id_cliente, estado) 
VALUES ('$fecha', '$cliente', '$estado')";

if ($conexion->query($sqlVenta) === TRUE) {

    $id_venta = $conexion->insert_id;

    $sqlDetalle = "INSERT INTO detalle_venta 
    (cantidad, precio_unitario, subtotal, id_producto, id_venta, nota) 
    VALUES ('$cantidad', '$precio', '$subtotal', '$id_producto', '$id_venta', '$nota')";

    if ($conexion->query($sqlDetalle) === TRUE) {
        echo "ok";
    } else {
        echo "ERROR DETALLE: " . $conexion->error;
    }

} else {
    echo "ERROR VENTA: " . $conexion->error;
}

$conexion->close();

?>