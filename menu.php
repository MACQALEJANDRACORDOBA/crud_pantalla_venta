<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>menu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link href="menuStyle.css" rel="stylesheet">
</head>

<body>

    <header class="encabezado">
        <div class="encabezado-contenido">
            <img src="imagen/logo.png" alt="Logo del sistema" class="logo">
            <h1 class="titulo-sistema">Contando Huevos</h1>
        </div>
    </header>

   <section class="selector-mes">
    <button class="btn-nav">&lt;</button> 
    <div class="tarjeta-mes">
        <div class="mes-info">
            <span>
                <?php 
                    // 1. Creamos una lista con los meses en español ordenados
                    $meses = [
                        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                    ];

                    // 2. Le preguntamos a la computadora cuál es el número de mes actual (del 1 al 12)
                    $numeroMes = (int)date('n');

                    // 3. Le preguntamos cuál es el año actual (ej: 2026)
                    $anio = date('Y');

                    // 4. Buscamos el nombre del mes en nuestra lista y lo pintamos junto al año
                    echo $meses[$numeroMes] . " " . $anio;
                ?>
            </span>
            <i class="bi bi-calendar4 icono-calendario"></i>
        </div>
    </div>
    <button class="btn-nav">&gt;</button>
</section>

    <div class="menu-principal">
        
        <div class="dropdown">
            <button class="btn boton-menu dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="icono-circulo">+</span>
                <span class="texto-boton">Nuevo registro</span>
            </button>
            
            <ul class="dropdown-menu dropdown-menu-end shadow" style="border: 2px solid black; border-radius: 16px; background-color: #f8f9fa;"> 
                <li>
                    <a class="dropdown-item item-menu" href="produccion.html">
                        <i class="bi bi-box-seam icono-item"></i>
                        <span>Producción</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item item-menu" href="ventas_inicio.html">
                        <i class="bi bi-cash-coin icono-item"></i>
                        <span>Ventas</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item item-menu" href="gastos.php">
                        <i class="bi bi-receipt icono-item"></i>
                        <span>Gastos</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item item-menu" href="consumo.php">
                        <i class="bi bi-basket icono-item"></i>
                        <span>Consumo</span>
                    </a>
                </li>
            </ul>
        </div>

        <a href="reportes.php" class="boton-menu boton-reportes">
            <span class="icono-circulo icono-reportes">
                <i class="bi bi-bar-chart"></i>
            </span>
            <span class="texto-boton">Reportes</span>
        </a>
    </div> 

    <div class="contenedor-salida">
        <a href="#" class="btn boton-salida">
            <i class="bi bi-box-arrow-right"></i>
            <span>Salida</span>
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>