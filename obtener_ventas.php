<?php
require_once 'conexion.php'; 
$pdo = Conexion::conectar(); 

// La consulta SQL es perfecta, no le voy a cambiar nada a la lógica del SELECT
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

try {
    // 1. Preparamos y ejecutamos
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // 2. Traemos todos los resultados de una vez
    //Una de las ventajas de PDO es que puedes usar fetchAll(). ¡Menos código para mi! Esto guarda todas las filas directamente en el arreglo $ventas sin necesidad de recorrerlas una por una con un ciclo while. 
    // PDO::FETCH_ASSOC parámetro que le dice a PDO: "tráeme los datos con los nombres de las columnas (cliente, fecha, etc.)".Trae todos los datos de golpe
    $ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3. Enviamos la respuesta al Front-end
    echo json_encode($ventas);

} catch (PDOException $e) {
    // Si hay un error en el SQL, esto me avisará, es un Manejo de errores: Si te equivocas en una coma o un nombre de tabla en el SQL, el catch te devolverá un JSON con el error exacto, lo cual es genial para cuando estás haciendo pruebas.
    echo json_encode(["error" => $e->getMessage()]);
}
?>



