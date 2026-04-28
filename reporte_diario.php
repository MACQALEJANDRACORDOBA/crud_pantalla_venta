<?php
require_once 'conexion.php';
try {
    $pdo = Conexion::conectar();
    // Traemos las producciones ordenadas por la más reciente
    $sql = "SELECT id_produccion, fecha FROM produccion ORDER BY fecha DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Diario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="produccionStyle.css"> </head>
<body>
    <div class="app-container">
        <div class="titulo-seccion mb-4">
            <i class="bi bi-eye"></i> Historial de Producción
        </div>

        <div class="list-group">
            <?php foreach ($registros as $fila): ?>
            <div class="tarjeta-fecha mb-2 d-flex justify-content-between align-items-center" style="cursor: default;">
                <div>
                    <span class="fw-bold"><?php echo $fila['fecha']; ?></span>
                </div>
                <div>
                    <button onclick="eliminarRegistro(<?php echo $fila['id_produccion']; ?>)" class="btn btn-sm text-danger">
                        <i class="bi bi-trash fs-4"></i>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="barra-inferior mt-4">
            <a href="menu.html" class="btn btn-atras">
                <i class="bi bi-house"></i> Menú
            </a>
            <a href="produccion.html" class="btn btn-guardar">
                <i class="bi bi-plus-lg"></i> Nuevo
            </a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
function eliminarRegistro(idRecibido) {
    // 1. Mostramos una ventana de confirmación al usuario
    if(confirm("¿Estás seguro de que quieres eliminar este registro de producción?")) {
        
        // 2. Iniciamos la llamada AJAX (el mensajero que va al servidor)
        $.ajax({
            url: "eliminar_produccion.php", // A qué archivo le enviamos la orden
            type: "POST",                  // Método de envío seguro
            // 3. Enviamos el dato 'id' que el PHP recogerá como $_POST['id']
            data: { id: idRecibido }, 
            
            // 4. Qué hacer si el servidor responde con éxito
            success: function(respuesta) {
                // trim() quita espacios vacíos invisibles que a veces manda el servidor
                if(respuesta.trim() === "ok") {
                    alert("Registro eliminado con éxito.");
                    // 5. Recargamos la página para que el registro ya no aparezca en la lista
                    location.reload(); 
                } else {
                    // Si el PHP mandó un error, lo mostramos aquí
                    alert("El servidor dice: " + respuesta);
                }
            },
            // 6. Qué hacer si hay un error de conexión o el archivo no existe
            error: function() {
                alert("Error crítico: No se pudo contactar con el archivo eliminar_produccion.php");
            }
        });
    }
}
</script>
</body>
</html>