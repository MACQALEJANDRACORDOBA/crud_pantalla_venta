<!--paso 2: ¿por qué no inicio con el codigo php? nuevo_gasto.php (El formulario): Su trabajo no es mostrar el pasado, su única misión es darle cajas vacías al usuario para que escriba algo nuevo. 
En este momento, este archivo no necesita preguntarle nada a la base de datos; solo necesita pintar el formulario en blanco.-->


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Gasto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="produccionStyle.css">
</head>
<body>

    <div class="app-container">

        <div class="d-flex align-items-center mt-3 mb-4">
            <a href="gastos.php" class="text-dark me-3 text-decoration-none">
                <i class="bi bi-arrow-left fs-2"></i>
            </a>
            <h2 class="fw-bold mb-0" style="color: #333;">Nuevo Registro</h2>
        </div>

        <form action="guardar_gasto_logica.php" method="POST">

            <div class="mb-3">
                <label class="form-label fw-bold text-muted small text-uppercase">Fecha del Gasto</label>
                <input type="date" name="fecha" class="form-control p-3 border-dark" style="border-radius: 12px;" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold text-muted small text-uppercase">¿En qué se gastó?</label>
                <input type="text" name="descripcion" class="form-control p-3 border-dark" style="border-radius: 12px;" placeholder="Ej: 1 Bulto de purina" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold text-muted small text-uppercase">Valor ($)</label>
                <input type="number" step="0.01" name="valor" class="form-control p-3 border-dark" style="border-radius: 12px;" placeholder="0.00" required>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold text-muted small text-uppercase">Notas Adicionales</label>
                <textarea name="notas" class="form-control p-3" rows="4" style="background-color: #ffd1dc; border: 1.5px dashed #ffb6c1; border-radius: 15px; color: #555;" placeholder="Escribe aquí detalles importantes de la compra..."></textarea>
            </div>

            <div class="text-center mt-5">
                <button type="submit" class="btn btn-success w-100 py-3 fw-bold fs-5 shadow-sm" style="border-radius: 15px; background-color: #28a745 !important;">
                    <i class="bi bi-check-circle me-2"></i> Guardar Gasto
                </button>
            </div>

        </form> </div> </body>
</html>