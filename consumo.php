<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consumo Interno</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="produccionStyle.css">
    

</head>
<body>
    
    <div class="app-container py-3">

        <form action="guardar_consumo_logica.php" method="POST" id="formConsumo">
            
<div class="tarjeta-fecha" onclick="document.getElementById('selectorFecha').showPicker()">
            <i class="bi bi-calendar-check fs-3"></i>
            <div>
                <div id="fechaTexto" class="fw-bold">Seleccionar Fecha</div>
                <div id="diaTexto" class="small text-muted">Toca para elegir</div>
            </div>
        </div>
        <input type="date" id="selectorFecha" name="fecha" hidden>


              <div class="titulo-seccion mt-5 mb-4">Consumo Interno</div>

            
            <div class="mb-3">
                <label class="form-label fw-bold">Cantidad HG (Huevos Grandes)</label>
                <input type="number" class="form-control" name="cantidad_hg" placeholder="Ej: 30" style="border: 2px solid black; border-radius: 10px;">
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Cantidad HP (Huevos Pequeños)</label>
                <input type="number" class="form-control" name="cantidad_hp" placeholder="Ej: 12" style="border: 2px solid black; border-radius: 10px;">
            </div>

            <label class="form-label fw-bold mb-2">Destino del consumo (Opcional)</label>
            <div class="row g-3 mb-4">
                <div class="col-6">
                    <div class="btn-destino">
                        <span><i class="bi bi-house-door fs-4 me-2"></i> Casa</span>
                        <input type="radio" name="destino" value="casa" style="width:20px; height:20px; accent-color: black;">
                    </div>
                </div>
                <div class="col-6">
                    <div class="btn-destino">
                        <span><i class="bi bi-gift fs-4 me-2"></i> Regalo</span>
                        <input type="radio" name="destino" value="regalo" style="width:20px; height:20px; accent-color: black;">
                    </div>
                </div>
            </div>

           
           <div class="mb-4">
                <label class="form-label fw-bold text-muted small text-uppercase">Notas Adicionales</label>
                <textarea name="notas" class="form-control p-3" rows="4" style="background-color: #ffd1dc; border: 1.5px dashed #ffb6c1; border-radius: 15px; color: #555;" placeholder="Escribe aquí detalles importantes de la compra..."></textarea>
            </div>

<div id="mensajeAlerta" class="text-center my-3"></div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="menu.php" class="btn btn-light border-2 border-dark px-4 fw-bold" style="border-radius: 12px;">
                    <i class="bi bi-arrow-left"></i> Atrás
                </a>
                <button type="submit" class="btn btn-success border-2 border-dark px-4 fw-bold" style="border-radius: 12px; background-color: #28a745;">
                    Guardar <i class="bi bi-check-circle ms-1"></i>
                </button>
            </div>

        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        // 1. Script para cambiar el texto cuando el usuario elija fecha
        $("#selectorFecha").on("change", function(){
            let fechaSeleccionada = $(this).val();
            $("#fechaTexto").text(fechaSeleccionada);
            $("#diaTexto").text("Fecha lista");
        });

    // 2. Lógica AJAX para enviar los datos de CONSUMO sin recargar la página
    $("#formConsumo").on("submit", function(e){
        e.preventDefault(); // Evita que la página parpadee o se recargue

        // Validamos que haya una fecha antes de enviar
        if(!$("#selectorFecha").val()){
            $("#mensajeAlerta").html('<span class="text-danger fw-bold">⚠️ Selecciona una fecha</span>');
            return;
        }

        $.ajax({
            url: "guardar_consumo_logica.php", // El archivo lógico que procesa el consumo
            type: "POST", 
            data: $(this).serialize(),    // Empaqueta cantidades, notas, destino, etc.
            success: function(respuesta){
                console.log("Servidor responde: " + respuesta);
                
                if(respuesta.includes("ok")){
                    // Mensaje verde flotante o fijo del prototipo
                    $("#mensajeAlerta").html('<span class="text-success fw-bold">✓ Consumo interno registrado correctamente.</span>');
                    $("#formConsumo")[0].reset(); // Limpia los inputs automáticamente
                    $("#fechaTexto").text("Seleccionar Fecha");
                    $("#diaTexto").text("Toca para elegir");
                } else {
                    // Mensaje de alerta (Por ejemplo, si HG y HP van vacíos)
                    $("#mensajeAlerta").html('<span class="text-danger fw-bold">⚠️ ' + respuesta + '</span>');
                }
            }
        });
    });

</script>
</body>
</html>