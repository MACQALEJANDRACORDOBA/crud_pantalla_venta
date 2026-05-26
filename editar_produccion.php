//en mi archivo reporte_diario.php tengo un boton editar que es el que pertenece a este archivo 
// y este archivo en la pantalla que ve el usuario es el lapiz 
//¿Qué hace este código?
Captura el ID: Sabe exactamente cuál producción quieres editar porque lo lee de la URL ($_GET['id']).

Busca la info: Va a la base de datos y trae lo que habías guardado antes.

Rellena el formulario: Usa value="..." para que, al abrir la página, los cuadros de texto ya tengan los números y la fecha cargados.
<?php
// 1. Incluimos la conexión
require_once 'conexion.php';

// 2. Obtenemos el ID que viene desde el reporte (por la URL)
$id = $_GET['id'];

try {
    $pdo = Conexion::conectar();

    // 3. Traemos los datos básicos de la producción (La fecha)
    $sql1 = "SELECT * FROM produccion WHERE id_produccion = :id";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute([':id' => $id]);
    $produccion = $stmt1->fetch(PDO::FETCH_ASSOC);

    // 4. Traemos los detalles (Huevos grandes y pequeños)
    $sql2 = "SELECT * FROM produccion_detalle WHERE id_produccion = :id";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute([':id' => $id]);
    $detalles = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    // Organizamos los detalles para que sea fácil ponerlos en los cuadritos
    // Sabemos que id_producto 1 es pequeño y 2 es grande
    $cant_pequeno = 0;
    $cant_grande = 0;
    foreach ($detalles as $d) {
        if ($d['id_producto'] == 1) $cant_pequeno = $d['cantidad'];
        if ($d['id_producto'] == 2) $cant_grande = $d['cantidad'];
    }

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producción</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="produccionStyle.css">
</head>
<body>
    <div class="app-container">
        <h3>Editar Producción</h3>
        
        <!-- El formulario enviará los datos a un archivo que procesará el cambio -->
        <form action="actualizar_logica.php" method="POST">
            <!-- CAMPO OCULTO: Muy importante para saber qué ID estamos actualizando -->
            <input type="hidden" name="id_produccion" value="<?php echo $id; ?>">

            <div class="mb-3">
                <label class="form-label">Fecha:</label>
                <input type="date" name="fecha" class="form-control" value="<?php echo $produccion['fecha']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Huevos Pequeños:</label>
                <input type="number" name="cant_pequeno" class="form-control" value="<?php echo $cant_pequeno; ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Huevos Grandes:</label>
                <input type="number" name="cant_grande" class="form-control" value="<?php echo $cant_grande; ?>">
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success w-100">Guardar Cambios</button>
                <a href="reporte_diario.php" class="btn btn-secondary w-100">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>