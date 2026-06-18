<?php
require_once 'conexion.php'; 

try {
    $pdo = Conexion::conectar(); 
    
    if (!isset($_POST['fecha']) || empty($_POST['fecha'])) {
        echo "Error: Debes seleccionar una fecha.";
        exit;
    }

    $fecha        = $_POST['fecha'];
    // Validamos que si el usuario deja el input vacío, se guarde un 0 en vez de un valor nulo
    $cant_grande  = !empty($_POST['cantidad_grande']) ? intval($_POST['cantidad_grande']) : 0;
    $cant_pequeno = !empty($_POST['cantidad_pequeno']) ? intval($_POST['cantidad_pequeno']) : 0;

    // Iniciamos transacción para proteger los datos de producción
    $pdo->beginTransaction();

    // 1. Insertar en tabla principal
    $sql1 = "INSERT INTO produccion (fecha, observaciones) VALUES (:fecha, 'Cosecha del día')";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute([':fecha' => $fecha]);

    $id_produccion = $pdo->lastInsertId();

    // 2. Insertar Detalles utilizando un único molde seguro por ejecución
    $sql2 = "INSERT INTO produccion_detalle (id_produccion, id_producto, cantidad) VALUES (:id_p, :id_prod, :cant)";
    $stmt2 = $pdo->prepare($sql2);

    // HUEVOS GRANDES (ID: 2)
    $stmt2->execute([
        ':id_p'    => $id_produccion,
        ':id_prod' => 2,
        ':cant'    => $cant_grande
    ]);

    // HUEVOS PEQUEÑOS (ID: 1)
    $stmt2->execute([
        ':id_p'    => $id_produccion,
        ':id_prod' => 1,
        ':cant'    => $cant_pequeno
    ]);

    // Confirmamos todos los cambios juntos
    $pdo->commit();
    echo "ok";

} catch (Exception $e) {
    // Si algo falla, cancelamos todo para no dejar tablas incompletas
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "Error en el servidor: " . $e->getMessage();
}
// Dejamos el archivo abierto sin el ?> para evitar espacios basura