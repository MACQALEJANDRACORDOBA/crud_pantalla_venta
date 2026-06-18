<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $fecha = !empty($_POST['fecha']) ? $_POST['fecha'] : date('Y-m-d');
    $motivo = !empty($_POST['notas']) ? $_POST['notas'] : null;
    
    $cantidad_hg = !empty($_POST['cantidad_hg']) ? (int)$_POST['cantidad_hg'] : 0;
    $cantidad_hp = !empty($_POST['cantidad_hp']) ? (int)$_POST['cantidad_hp'] : 0;

    // Si ambos van vacíos, AJAX recibirá este texto de advertencia
    if ($cantidad_hg === 0 && $cantidad_hp === 0) {
        echo "Debe ingresar al menos una cantidad de huevos para registrar el consumo.";
        exit();
    }

    try {
        $pdo = Conexion::conectar();
        
        $sql = "INSERT INTO consumo_interno (id_producto, cantidad, motivo, fecha) 
                VALUES (:id_producto, :cantidad, :motivo, :fecha)";
        $stmt = $pdo->prepare($sql);

        // Registro de Grandes (ID 1)
        if ($cantidad_hg > 0) {
            $id_producto_hg = 1; 
            $stmt->execute([
                ':id_producto' => $id_producto_hg,
                ':cantidad' => $cantidad_hg,
                ':motivo' => $motivo,
                ':fecha' => $fecha
            ]);
        }

        // Registro de Pequeños (ID 2)
        if ($cantidad_hp > 0) {
            $id_producto_hp = 2; 
            $stmt->execute([
                ':id_producto' => $id_producto_hp,
                ':cantidad' => $cantidad_hp,
                ':motivo' => $motivo,
                ':fecha' => $fecha
            ]);
        }

        // ¡ESTO ES LO MÁS IMPORTANTE! Le responde "ok" a AJAX para que active el letrero verde
        echo "ok";
        exit();

    } catch (Exception $e) {
        echo "Error en la base de datos: " . $e->getMessage();
        exit();
    }
}
?>