<!---paso 1: Ese código php sirve para LEER de la base de datos
(por eso usa la palabra SELECT, que significa "Seleccionar o traer datos que ya existen").
gastos.php (La lista): Necesita ese código porque su trabajo es mostrarme el historial de lo que ya se ha gastado en el pasado. Necesita abrir la base de datos, sacar los registros y pintarlos en la pantalla.
nuevo_gasto.php (El formulario): Su trabajo no es mostrar el pasado, su única misión es darle cajas vacías al usuario para que escriba algo nuevo.-->

<?php
// === TRAER LOS DATOS ===
require_once 'conexion.php';

try {
    $pdo = Conexion::conectar();
    
    // Traemos los gastos ordenados por la más reciente
    $sql = "SELECT id_gasto, fecha, descripcion, valor FROM gasto ORDER BY fecha DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e){
    die("Error en la base de datos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gastos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="produccionStyle.css">
</head>

<body>
    <div class="app-container">

        <form id="formGastos" class="mt-2">
            <div class="tarjeta-fecha" onclick="document.getElementById('selectorFecha').showPicker()">
                <i class="bi bi-calendar-check fs-3"></i>
                <div>
                    <div id="fechaTexto" class="fw-bold">Filtrar por Fecha</div>
                    <div id="diaTexto" class="small text-muted">Toca para elegir un día</div>
                </div>
            </div>
            <input type="date" id="selectorFecha" name="fecha" hidden>
            
            <div class="text-center mt-2" id="contenedorLimpiar" style="display: none;">
                <button type="button" id="btnLimpiarFiltro" class="btn btn-sm btn-secondary rounded-pill px-3">
                    <i class="bi bi-x-circle"></i> Ver todos los gastos
                </button>
            </div>

            <div class="titulo-seccion mt-4 mb-4 text-center">Gastos</div>

            <div class="mt-4 mb-4">
                <a href="nuevo_gasto.php" class="tarjeta-blanca-boton">
                    <i class="bi bi-plus-circle me-2"></i> Nuevo Gasto
                </a>
            </div>

            <div class="lista-gastos mt-4">
                <?php if (empty($registros)): ?>
                    <p class="text-center text-muted" id="mensajeVacio">Aún no tienes gastos registrados.</p>
                <?php else: ?>
                    
                    <p class="text-center text-muted" id="mensajeNoCoincide" style="display: none;">
                        No hay gastos registrados en esta fecha.
                    </p>

                    <?php foreach ($registros as $gasto): ?>
                        <div class="card card-gasto mb-3 p-3 shadow-sm" data-fecha="<?php echo $gasto['fecha']; ?>" style="border-radius: 15px; border-left: 5px solid #dc3545; background-color: #fff;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1 fw-bold text-dark"><?php echo htmlspecialchars($gasto['descripcion']); ?></h5>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-event me-1"></i> <?php echo $gasto['fecha']; ?>
                                    </small>
                                </div>
                                <div>
                                    <span class="fs-5 fw-bold text-danger">
                                        -$<?php echo number_format($gasto['valor'], 2); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="col-12 text-center mt-5">
                <a href="menu.php" class="btn btn-light border px-4">
                    <i class="bi bi-arrow-left"></i> Atrás
                </a>
            </div>
        </form> 
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <script>
        $(document).ready(function() {
            
            // ESCUCHADOR: Detecta cuando el usuario cambia la fecha en el calendario
            $("#selectorFecha").on("change", function() {
                let fechaSeleccionada = $(this).val(); // Da formato: YYYY-MM-DD
                
                if (fechaSeleccionada) {
                    // 1. Modificar textos de la tarjeta superior
                    $("#fechaTexto").text("Filtrado por:");
                    $("#diaTexto").text(fechaSeleccionada);
                    
                    // 2. Mostrar el botón de limpiar filtro
                    $("#contenedorLimpiar").fadeIn();
                    
                    // 3. Lógica del filtro físico en las tarjetas de abajo
                    let contadorVisibles = 0;
                    
                    $(".card-gasto").each(function() {
                        let fechaTarjeta = $(this).data("fecha");
                        
                        if (fechaTarjeta === fechaSeleccionada) {
                            $(this).show(); // Muestra las tarjetas que coinciden
                            contadorVisibles++;
                        } else {
                            $(this).hide(); // Oculta las que no corresponden al día
                        }
                    });
                    
                    // 4. Si ninguna tarjeta coincide con el día elegido, muestra mensaje de aviso
                    if (contadorVisibles === 0) {
                        $("#mensajeNoCoincide").show();
                    } else {
                        $("#mensajeNoCoincide").hide();
                    }
                }
            });

            // ACCIÓN: Cuando el usuario hunde el botón "Ver todos los gastos"
            $("#btnLimpiarFiltro").on("click", function() {
                // Reseteamos el input fecha y los textos originales
                $("#selectorFecha").val("");
                $("#fechaTexto").text("Filtrar por Fecha");
                $("#diaTexto").text("Toca para elegir un día");
                
                // Ocultamos el botón de limpiar y el mensaje de error
                $("#contenedorLimpiar").hide();
                $("#mensajeNoCoincide").hide();
                
                // Volvemos a mostrar absolutamente todas las tarjetas de gastos
                $(".card-gasto").show();
            });

        });
    </script>
</body>
</html>