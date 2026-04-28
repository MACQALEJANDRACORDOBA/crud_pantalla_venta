<?php
// 1. Incluimos la conexión para poder hablar con la base de datos
require_once 'conexion.php'; 

// 2. Verificamos que realmente hayamos recibido un ID por el método POST
if (isset($_POST['id'])) {
    try {
        // 3. Abrimos la conexión usando PDO
        $pdo = Conexion::conectar(); 
        
        // 4. Guardamos el ID que recibimos del botón en una variable
        $id = $_POST['id']; 

        // --- PASO A: BORRAR LOS HIJOS (Detalles) ---
        // 5. Preparamos la orden SQL. 
        // Primero borramos de 'produccion_detalle' porque depende de la principal.
        $sql1 = "DELETE FROM produccion_detalle WHERE id_produccion = :id";
        $stmt1 = $pdo->prepare($sql1);
        
        // 6. Vinculamos el ID real al marcador ':id' por seguridad
        $stmt1->execute([':id' => $id]);

        // --- PASO B: BORRAR EL PADRE (Producción) ---
        // 7. Ahora que los detalles se han ido, podemos borrar la fila principal.
        // Aquí usamos 'id_produccion' que es el nombre real en tu tabla.
        $sql2 = "DELETE FROM produccion WHERE id_produccion = :id";
        $stmt2 = $pdo->prepare($sql2);
        
        // 8. Ejecutamos la orden final
        $stmt2->execute([':id' => $id]);

        // 9. Si todo salió bien, respondemos "ok" al JavaScript para que refresque la pantalla
        echo "ok";

    } catch (Exception $e) {
        // 10. Si algo falla (ej. error de escritura), atrapamos el error y lo mostramos
        echo "Error: " . $e->getMessage();
    }
}
?>