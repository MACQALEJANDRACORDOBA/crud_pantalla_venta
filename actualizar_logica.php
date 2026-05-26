//¿Qué es lo que hace este "Cerebro"?  
UPDATE: A diferencia del INSERT (crear) o DELETE (borrar), el comando UPDATE busca una fila que ya existe y solo cambia los valores que le digamos.

WHERE: Es vital. Si olvidamos el WHERE id_produccion = :id, ¡el código cambiaría la fecha de todas las producciones de la historia por error!

Redirección: Al final uso header("Location: ..."). Esto es para no tener que darle atrás al navegador; el sistema solito te devuelve a la lista de registros apenas termina de guardar.

<?php
// 1. Incluimos la conexión a la base de datos
require_once 'conexion.php';

// 2. Recibimos todos los datos del formulario de edición
$id_produccion = $_POST['id_produccion'];
$fecha = $_POST['fecha'];
$cant_pequeno = $_POST['cant_pequeno'];
$cant_grande = $_POST['cant_grande'];

try {
    $pdo = Conexion::conectar();
    
    // 3. Iniciamos una "Transacción" (O se guarda todo o no se guarda nada)
    $pdo->beginTransaction();

    // 4. ACTUALIZACIÓN A: Cambiamos la fecha en la tabla principal
    $sql1 = "UPDATE produccion SET fecha = :fecha WHERE id_produccion = :id";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute([
        ':fecha' => $fecha,
        ':id'    => $id_produccion
    ]);

    // 5. ACTUALIZACIÓN B: Cambiamos las cantidades en la tabla detalle
    // Actualizamos el registro de huevos Pequeños (ID Producto 1)
    $sql2 = "UPDATE produccion_detalle SET cantidad = :cant 
             WHERE id_produccion = :id AND id_producto = 1";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute([
        ':cant' => $cant_pequeno,
        ':id'   => $id_produccion
    ]);

    // Actualizamos el registro de huevos Grandes (ID Producto 2)
    $sql3 = "UPDATE produccion_detalle SET cantidad = :cant 
             WHERE id_produccion = :id AND id_producto = 2";
    $stmt3 = $pdo->prepare($sql3);
    $stmt3->execute([
        ':cant' => $cant_grande,
        ':id'   => $id_produccion
    ]);

    // 6. Si no hubo errores, confirmamos los cambios en la base de datos
    $pdo->commit();

    // 7. Redirigimos al usuario de vuelta al reporte para que vea los cambios
    header("Location: reporte_diario.php?msj=editado");

} catch (Exception $e) {
    // 8. Si algo falla, cancelamos cualquier cambio a medio camino
    $pdo->rollBack();
    echo "Error al actualizar: " . $e->getMessage();
}
?>