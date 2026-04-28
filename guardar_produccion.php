<?php
// 1. Conectamos con la base de datos usando el archivo que ya tenemos
require_once 'conexion.php'; 

try {
    // 2. Iniciamos la conexión PDO (Abrir la puerta de la base de datos)
    $pdo = Conexion::conectar(); 
    // Verificamos si la fecha realmente llegó
    if (!isset($_POST['fecha']) || empty($_POST['fecha'])) {
        echo "Error: Debes seleccionar una fecha.";
        exit; // Detenemos el proceso si no hay fecha
    }

    // 3. Recogemos los datos que envió el formulario
    // IMPORTANTE: Los nombres dentro de [''] deben ser iguales al 'name' del HTML
    $fecha        = $_POST['fecha'];
    $cant_grande  = $_POST['cantidad_grande'];
    $cant_pequeno = $_POST['cantidad_pequeno'];

    // 4. PREPARAMOS la primera inserción (Tabla principal: produccion)
    // Usamos marcadores como :fecha para evitar ataques de Inyección SQL
    $sql1 = "INSERT INTO produccion (fecha, observaciones) VALUES (:fecha, 'Cosecha del día')";
    $stmt1 = $pdo->prepare($sql1);
    
    // 5. Unimos el dato real al marcador de la plantilla
    $stmt1->bindParam(':fecha', $fecha);
    
    // 6. Ejecutamos la orden para crear la fila de producción
    $stmt1->execute();

    // 7. RECUPERAMOS EL ID: Necesitamos el número de esta producción para los detalles
    $id_produccion = $pdo->lastInsertId();

    // 8. PREPARAMOS la segunda plantilla (Tabla: produccion_detalle)
    // Esta la usaremos dos veces: una para grandes y otra para pequeños
    $sql2 = "INSERT INTO produccion_detalle (id_produccion, id_producto, cantidad) 
             VALUES (:id_p, :id_prod, :cant)";
    $stmt2 = $pdo->prepare($sql2);

    // --- GUARDAR HUEVOS GRANDES ---
    // 9. Definimos que el ID del producto grande es 2 (Revisa esto en tu tabla producto)
    $id_grande = 2; 
    $stmt2->bindParam(':id_p', $id_produccion);
    $stmt2->bindParam(':id_prod', $id_grande);
    $stmt2->bindParam(':cant', $cant_grande);
    $stmt2->execute();

    // --- GUARDAR HUEVOS PEQUEÑOS ---
    // 10. Definimos que el ID del producto pequeño es 1
    $id_pequeno = 1;
    $stmt2->bindParam(':id_p', $id_produccion);
    $stmt2->bindParam(':id_prod', $id_pequeno);
    $stmt2->bindParam(':cant', $cant_pequeno);
    $stmt2->execute();

    // 11. Si llegamos aquí sin errores, respondemos "ok" al JavaScript
    echo "ok";

} catch (Exception $e) {
    // 12. Si algo sale mal, enviamos el mensaje de error para saber qué falló
    echo "Error en el servidor: " . $e->getMessage();
}
?>