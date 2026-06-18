<?php
require_once 'conexion.php'; 
$pdo = Conexion::conectar(); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Venta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="ventasStyle.css">
</head>
<body>

<div class="app-container">
    
    <div class="header-verde d-flex align-items-center justify-content-between" style="padding: 15px;">
        <div class="header-izq d-flex align-items-center">
            <i class="bi bi-calendar-event me-2" style="font-size: 1.5rem;"></i>
            <div>
                <strong id="textoFechaFormulario">Cargando...</strong>
                <small id="textoDiaFormulario" class="text-white-50">Día</small>
            </div>
        </div>
        
        <div class="header-der">
            <button type="button" id="btnAbrirCalendario" class="btn btn-light btn-sm rounded-circle shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-calendar3 text-success" style="font-size: 1.2rem;"></i>
            </button>
        </div>
    </div>

    <div class="titulo-seccion mt-2">Ventas</div>

    <form id="formVenta">
        <input type="date" name="fecha_venta" id="fecha_venta" class="d-none">
        <input type="hidden" name="cliente" id="clienteSeleccionado">
        <div class="dropdown mb-3">
            <button class="btn campo-app dropdown-toggle w-100 text-start" data-bs-toggle="dropdown" data-original="Cliente" type="button">
                Cliente
            </button>
            <div class="dropdown-menu w-100 p-3">
                <input type="text" class="form-control mb-2" placeholder="Buscar cliente">
                <ul class="lista-clientes">
                    <?php
                    $sql = "SELECT * FROM clientes ORDER BY nombre";
                    $stmt = $pdo->query($sql);
                    while($fila = $stmt->fetch(PDO::FETCH_ASSOC)){
                        echo "<li data-id='".$fila['id_cliente']."'> ".$fila['nombre']." 
                                <span class='eliminar-cliente' style='color:red; cursor:pointer; float:right;'>🗑</span>
                              </li>";
                    }
                    ?>
                </ul>
                <button type="button" class="btn btn-nuevo w-100 mt-2">
                    <i class="bi bi-plus-circle"></i> Nuevo cliente
                </button>
                <div class="nuevo-cliente-form mt-2 d-none">
                    <input type="text" id="nombreNuevoCliente" class="form-control mb-2" placeholder="Nombre del cliente">
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-sm btn-secondary">Cancelar</button>
                        <button type="button" class="btn btn-sm btn-success" id="guardarCliente">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="tamano" id="tamanoSeleccionado">
        <div class="dropdown mb-3">
            <button class="btn campo-app dropdown-toggle w-100 text-start" data-bs-toggle="dropdown" data-original="Tamaño" type="button">
                Tamaño
            </button>
            <ul class="dropdown-menu w-100">
                <li><a class="dropdown-item tamano" data-id="1">Pequeño</a></li>
                <li><a class="dropdown-item tamano" data-id="2">Grande</a></li>
            </ul>
        </div>

        <button type="button" class="btn campo-app w-100 text-start mb-3" id="btnCantidad">Cantidad</button>
        <div id="panelCantidad" class="mt-2 d-none">
            <label>Cubetas</label>
            <input type="number" id="cubetas" class="form-control mb-2" min="0">
            <label>Unidades</label>
            <input type="number" id="unidades" class="form-control mb-2" min="0" max="29">
        </div>
        <input type="hidden" name="cantidad" id="cantidadTotal">

        <div class="mb-3">
            <input type="text" name="precio" id="precio" class="form-control campo-app" placeholder="Precio (ej: 40.000)">
        </div>

        <div class="dropdown mb-3">
            <button class="btn campo-app dropdown-toggle w-100 text-start" data-bs-toggle="dropdown" data-original="Estado de cuenta" type="button">
                Estado de cuenta
            </button>
            <div class="dropdown-menu w-100 p-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="estado" value="pendiente">
                    <label class="form-check-label">Pendiente</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="estado" value="abono">
                    <label class="form-check-label">Abonó</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="estado" value="cancelado">
                    <label class="form-check-label">Canceló</label>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <textarea name="nota" class="form-control campo-app" rows="3" placeholder="Escribe una nota..."></textarea>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="ventas_inicio.html" class="btn btn-secondary">← Atrás</a>
            <button type="submit" class="btn btn-success">✔ Guardar</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {
    // 1. ASIGNAR FECHA DE HOY AL INICIAR FORMULARIO
    const fechaHoy = new Date();
    const hoyFormato = fechaHoy.toISOString().split('T')[0]; // Genera AAAA-MM-DD
    $("#fecha_venta").val(hoyFormato);

    // Formatear visualmente en el Header verde
    actualizarTextosFecha(hoyFormato);

    // 🌟 TRUCO GANADOR: Cuando toques el botón blanco, se abre el calendario nativo
    $("#btnAbrirCalendario").click(function() {
        // .showPicker() es la función nativa moderna para obligar a abrir el calendario
        document.getElementById('fecha_venta').showPicker();
    });
});

// Función auxiliar para cambiar los textos elegantes del header verde
function actualizarTextosFecha(fechaString) {
    let fechaObj = new Date(fechaString + "T00:00:00");
    let diaMesAnio = fechaObj.toLocaleDateString('es-CO', { day: 'numeric', month: 'long', year: 'numeric' });
    let diaSemana = fechaObj.toLocaleDateString('es-CO', { weekday: 'long' });
    let diaSemanaFormateado = diaSemana.charAt(0).toUpperCase() + diaSemana.slice(1);

    $("#textoFechaFormulario").text(diaMesAnio);
    $("#textoDiaFormulario").text(diaSemanaFormateado);
}

// 🌟 CAMBIAR FECHA AL INTERACTUAR CON EL CALENDARIO
$("#fecha_venta").on("change", function() {
    let fechaSeleccionada = $(this).val();
    if(fechaSeleccionada) {
        actualizarTextosFecha(fechaSeleccionada);
    }
});

$("#formVenta").submit(function(e){
    e.preventDefault();

    // 1. Validaciones previas antes de enviar
    if(!$("#clienteSeleccionado").val()){
        Swal.fire("Atención", "Por favor, selecciona un cliente de la lista.", "warning");
        return;
    }
    if(!$("#tamanoSeleccionado").val()){
        Swal.fire("Atención", "Por favor, selecciona el tamaño del huevo.", "warning");
        return;
    }
    if(!$("#cantidadTotal").val() || $("#cantidadTotal").val() == "0"){
        Swal.fire("Atención", "Debes ingresar una cantidad válida en cubetas o unidades.", "warning");
        return;
    }
    if(!$("input[name='estado']:checked").val()){
        Swal.fire("Atención", "Selecciona el estado de cuenta de la venta.", "warning");
        return;
    }

    // 2. Clonamos y limpiamos el precio para el envío a PHP
    let datosFormulario = $(this).serializeArray();
    datosFormulario.forEach(function(campo) {
        if (campo.name === "precio") {
            campo.value = campo.value.replace(/\./g, ""); // Quita los puntos
        }
    });

    // 3. Petición AJAX
    $.ajax({
        url: "guardar_venta.php",
        type: "POST",
        data: $.param(datosFormulario), 
        success: function(respuesta){
            console.log("Respuesta PHP:", respuesta);

            if(respuesta.trim() === "ok"){
                
                Swal.fire({
                    title: "¡Venta Guardada!",
                    text: "Su venta quedó registrada correctamente.",
                    icon: "success",
                    confirmButtonText: "Aceptar",
                    confirmButtonColor: "#198754"
                }).then((result) => {
                    $("#formVenta")[0].reset();

                    // Limpiar valores ocultos y restablecer fecha de hoy
                    $("#clienteSeleccionado").val("");
                    $("#tamanoSeleccionado").val("");
                    $("#cantidadTotal").val("");
                    
                    const fechaHoy = new Date();
                    const hoyFormato = fechaHoy.toISOString().split('T')[0];
                    $("#fecha_venta").val(hoyFormato);
                    actualizarTextosFecha(hoyFormato);

                    $("input[name='estado']").prop("checked", false);
                    $("#btnCantidad").text("Cantidad");

                    $(".dropdown button").each(function(){
                        let texto = $(this).data("original");
                        if(texto){
                            $(this).text(texto);
                        }
                    });
                });

            } else {
                Swal.fire("Error al guardar", "El sistema devolvió: " + respuesta, "error");
            }
        }
    });
});

// CLIENTE
$(document).on("click", ".lista-clientes li", function(e){
    e.preventDefault();
    let id = $(this).data("id");
    let nombre = $(this).text();
    $("#clienteSeleccionado").val(id);
    $(this).closest(".dropdown").children("button").first().text(nombre);
});

// Boton nuevo cliente
$(".btn-nuevo").click(function(){
    $(".nuevo-cliente-form").removeClass("d-none");
});

// TAMAÑO → PRODUCTO
$(".tamano").click(function(e){
    e.preventDefault(); 
    let id = $(this).data("id");
    $("#tamanoSeleccionado").val(id);
    console.log("tamano:", id);
});

// PRECIO con puntos (40.000)$$$$
$("#precio").on("input", function(){
    let valor = $(this).val().replace(/\./g, "").replace(/\D/g, "");
    if(valor){
        valor = parseInt(valor).toLocaleString("es-CO");
    }
    $(this).val(valor);
});

// ESTADO
$("input[name='estado']").on("change", function() {
    let valor = $(this).val();
    $("#estadoSeleccionado").val(valor);
});

// Mostrar tamaño seleccionado
$(".tamano").click(function(){
    let id = $(this).data("id");
    let texto = $(this).text();
    $("#tamanoSeleccionado").val(id);
    $(this).closest(".dropdown").find("button").text(texto);
});

// abrir/cerrar panel cantidad
$("#btnCantidad").click(function(){
    $("#panelCantidad").toggleClass("d-none");
});

// calcular total
$("#cubetas, #unidades").on("input", function(){
    let cubetas = parseInt($("#cubetas").val()) || 0;
    let unidades = parseInt($("#unidades").val()) || 0;
    let total = (cubetas * 30) + unidades;
    $("#cantidadTotal").val(total);
    $("#btnCantidad").text(
        cubetas + " cubetas + " + unidades + " unidades (" + total + " huevos)"
    );
});

// auto cerrar
$("#cubetas, #unidades").on("blur", function(){
    $("#panelCantidad").addClass("d-none");
});

// Mostrar estado seleccionado
$("input[name='estado']").change(function(){
    let estado = $(this).val();
    $("#estadoSeleccionado").val(estado);
    $(this).closest(".dropdown").find("button").text(estado);
});

// boton guardar nuevo cliente
$("#guardarCliente").click(function(){
    let nombre = $("#nombreNuevoCliente").val();
    if(nombre == ""){
        alert("Escribe el nombre del cliente");
        return;
    }
    $.ajax({
        url: "guardar_cliente.php",
        type: "POST",
        data: {nombre:nombre},
        success:function(respuesta){
            let cliente = JSON.parse(respuesta);
            $(".lista-clientes").append(
                "<li data-id='"+cliente.id+"'>"+cliente.nombre+
                "<span class='eliminar-cliente' style='color:red; cursor:pointer; float:right;'>🗑</span></li>"
            );
            $("#clienteSeleccionado").val(cliente.id);
            $(".dropdown").first().find("button").text(cliente.nombre);
            $("#nombreNuevoCliente").val("");
            $(".nuevo-cliente-form").addClass("d-none");
        }
    });
});

$(".btn-nuevo").click(function(e){
    e.stopPropagation(); 
    $(".nuevo-cliente-form").removeClass("d-none");
});

$(document).on("click", ".eliminar-cliente", function(e){
    e.stopPropagation(); 
    if(!confirm("¿Eliminar este cliente?")){
        return;
    }
    let li = $(this).closest("li");
    let id = li.data("id");

    $.ajax({
        url: "eliminar_cliente.php",
        type: "POST",
        data: {id:id},
        success:function(respuesta){
            if(respuesta.trim() == "ok"){
                li.remove();
            }
            else if(respuesta.trim() == "tiene_ventas"){
                alert("No puedes eliminar este cliente porque tiene ventas registradas");
            }
            else{
                alert("Error al eliminar cliente");
            }
        }
    });
});
</script>
</body>
</html>




