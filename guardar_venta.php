<?php
require_once 'conexion.php'; 
$pdo = Conexion::conectar(); // Usamos $pdo porque es un objeto PDO

// RECIBIR DATOS
$cliente     = $_POST['cliente'];
$cantidad    = intval($_POST['cantidad']);
// Quitamos puntos y convertimos a número
$precio      = floatval(str_replace('.', '', $_POST['precio'])); 
$id_producto = $_POST['tamano'];  
$estado      = isset($_POST["estado"]) ? $_POST["estado"] : "pendiente";                
$nota        = $_POST['nota'];
$subtotal    = $precio * $cantidad; 
$fecha       = date("Y-m-d");

// try {...} Esto es como una red de seguridad. Le dices a PHP: Intenta hacer esto, y si algo sale mal con la base de datos, avísame en la sección de error"
try {
    // 1️⃣ INSERTAR EN VENTA (Usando Sentencias Preparadas con ?)
    $sqlVenta = "INSERT INTO venta (fecha, id_cliente, estado) VALUES (?, ?, ?)";
    $stmtVenta = $pdo->prepare($sqlVenta); //Sentencia Preparada: En lugar de meter las variables directamente en el texto (que es peligroso porque permite hackeos), ponemos signos de interrogación ?. Luego, en el execute, mandamos los datos.
    $stmtVenta->execute([$fecha, $cliente, $estado]);

    // Con lastInsertId Obtenemos el ID de la venta que se acaba de crear, es decir sirve para saber qué número de venta se generó y poder usarlo en el detalle.
    $id_venta = $pdo->lastInsertId(); 

    // 2️⃣ INSERTAR EN DETALLE_VENTA
    $sqlDetalle = "INSERT INTO detalle_venta 
    (cantidad, precio_unitario, subtotal, id_producto, id_venta, nota) 
    VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmtDetalle = $pdo->prepare($sqlDetalle);
    $resultado = $stmtDetalle->execute([$cantidad, $precio, $subtotal, $id_producto, $id_venta, $nota]);

    if ($resultado) {
        echo "ok";
    }

} catch (PDOException $e) {
    // Si algo falla, el catch nos dirá qué pasó sin detener todo el servidor
    echo "ERROR: " . $e->getMessage();
}

// En PDO no es estrictamente necesario cerrar la conexión, 
// se cierra sola al terminar el script.
?>