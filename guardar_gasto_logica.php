//Paso 3: 
<?php
// =========================================================================
// PASO 1: TRAER NUESTRAS HERRAMIENTAS
// =========================================================================
// Le decimos a PHP que traiga el archivo de conexión para poder abrir la base de datos.
require_once 'conexion.php';

// =========================================================================
// PASO 2: RECIBIR LAS REMANSITAS DEL "CAMIÓN MENSAJERO" (FORMULARIO)
// =========================================================================
// Verificamos si los datos realmente vienen por el método seguro POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Capturamos lo que el usuario escribió en cada cajita del formulario.
    // Lo guardamos en variables (las cajitas con el signo $) para manejarlas fácil.
    $fecha = $_POST['fecha'];
    $descripcion = $_POST['descripcion'];
    $valor = $_POST['valor'];
    $notas = $_POST['notas']; // Recuerda que este es tu post-it rosa
    
    // Como en tu base de datos tienes un campo 'id_usuario' y aún no hay login,
    // le asignamos el usuario 1 por defecto para que la base de datos no se queje.
    $id_usuario = 1; 

    // =========================================================================
    // PASO 3: HABLAR CON LA BASE DE DATOS (EL INTENTO)
    // =========================================================================
    try {
        // Abrimos la puerta de la base de datos usando tu clase Conexion
        $pdo = Conexion::conectar();
        
        // Creamos la orden mágica. Usamos 'INSERT INTO' que significa "Meter dentro de...".
        // Le decimos a qué tabla (gasto) y a qué columnas van los datos.
        // Usamos incógnitas (:fecha, :descripcion, etc.) por seguridad, para que nadie nos hackee.
        $sql = "INSERT INTO gasto (fecha, descripcion, valor, notas, id_usuario) 
                VALUES (:fecha, :descripcion, :valor, :notas, :id_usuario)";
        
        // Preparamos la orden en el cerebro del servidor
        $stmt = $pdo->prepare($sql);
        
        // Aquí cambiamos las incógnitas por los datos reales que escribió el usuario
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':valor', $valor);
        $stmt->bindParam(':notas', $notas);
        $stmt->bindParam(':id_usuario', $id_usuario);
        
        // ¡FUEGO! Ejecutamos la orden para que los datos se guarden finalmente
        $stmt->execute();
        
        // =========================================================================
        // PASO 4: EL VIAJE DE REGRESO
        // =========================================================================
        // Si todo salió bien, el cerebro limpia la pantalla y teletransporta (redirecciona)
        // al usuario de vuelta a la lista principal de gastos para que vea su nuevo registro.
        header("Location: gastos.php");
        exit(); // Cerramos el archivo para que no gaste más energía

    } catch (Exception $e) {
        // Si algo falla (por ejemplo, si la base de datos se cayó), nos muestra el error en pantalla
        die("¡Ups! Algo salió mal al guardar el gasto: " . $e->getMessage());
    }

} else {
    // Si alguien intenta entrar a este archivo escribiendo la URL a la fuerza en el navegador
    // sin llenar el formulario, lo expulsamos y lo mandamos a la lista de gastos.
    header("Location: gastos.php");
    exit();
}
?>