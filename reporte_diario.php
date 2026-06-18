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

// Configuración local en PHP para que los nombres de los días salgan en español
setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'Spanish_Spain', 'Spanish');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Diario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="produccionStyle.css"> 
</head>
<body>
    <div class="app-container">
        <div class="titulo-seccion mb-4">
            <i class="bi bi-eye"></i> Historial de Producción
        </div>

        <div class="list-group">
            <?php if (empty($registros)): ?>
                <div class="text-center text-muted my-4">No hay registros de producción cargados.</div>
            <?php endif; ?>

            <?php foreach ($registros as $fila): 
                // Convertimos la fecha "AAAA-MM-DD" en un formato legible en español
                $fechaObjeto = strtotime($fila['fecha']);
                // Formato resultante: "10 de Junio, 2026"
                $fechaFormateada = date("d", $fechaObjeto) . " de " . ucfirst(strftime("%B", $fechaObjeto)) . ", " . date("Y", $fechaObjeto);
            ?>
            <div class="tarjeta-fecha mb-2 d-flex justify-content-between align-items-center" style="cursor: default;">
                <div>
                    <span class="fw-bold"><?php echo $fechaFormateada; ?></span>
                </div>
                <div>
                    <div class="d-flex align-items-center">
                        <a href="editar_produccion.php?id=<?php echo $fila['id_produccion']; ?>" class="btn btn-sm text-primary me-2 px-1">
                            <i class="bi bi-pencil-square fs-4"></i>
                        </a>

                        <button onclick="eliminarRegistro(<?php echo $fila['id_produccion']; ?>)" class="btn btn-sm text-danger px-1">
                            <i class="bi bi-trash fs-4"></i>
                        </button>
                     </div>
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
    $(document).ready(function() {
        // DETECTOR DE MENSAJES (Para cuando volvemos de editar_produccion)
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('msj') === 'editado') {
            Swal.fire({
                title: "¡Cambios Guardados!",
                text: "La producción fue actualizada correctamente.",
                icon: "success",
                toast: true, // Se muestra como una pequeña notificación flotante elegante
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            
            // Limpiamos la URL quitando el ?msj=editado para que no se repita la alerta si refrescan la página
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    });

    function eliminarRegistro(idRecibido) {
        // Alerta estética de confirmación de SweetAlert2
        Swal.fire({
            title: "¿Estás seguro?",
            text: "Esta acción borrará la producción y sus cantidades detalladas.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545", // Rojo peligro
            cancelButtonColor: "#6c757d",  // Gris secundario
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            // Si el usuario confirma la acción:
            if (result.isConfirmed) {
                $.ajax({
                    url: "eliminar_produccion.php", 
                    type: "POST",                  
                    data: { id: idRecibido }, 
                    success: function(respuesta) {
                        if(respuesta.trim() === "ok") {
                            Swal.fire({
                                title: "¡Eliminado!",
                                text: "El registro ha sido borrado.",
                                icon: "success",
                                confirmButtonColor: "#198754"
                            }).then(() => {
                                location.reload(); // Recarga la lista limpia
                            });
                        } else {
                            Swal.fire("Error", "El servidor respondió: " + respuesta, "error");
                        }
                    },
                    error: function() {
                        Swal.fire("Error Crítico", "No se pudo establecer comunicación con el servidor.", "error");
                    }
                });
            }
        });
    }
    </script>
</body>
</html>