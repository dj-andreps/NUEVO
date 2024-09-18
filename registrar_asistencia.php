<?php
include_once("conexion.php");

// Configurar la zona horaria de Perú
date_default_timezone_set('America/Lima');

// Obtener la fecha seleccionada y el DNI
$fecha = isset($_POST['fecha']) ? $_POST['fecha'] : date('Y-m-d');
$dni = isset($_POST['dni']) ? $_POST['dni'] : '';

// Obtener la hora actual con segundos
$hora_actual = date('H:i:s'); // Hora actual en formato de 24 horas
$hora_registro = date('Y-m-d H:i:s'); // Fecha y hora actual con segundos

// Definir el rango de tiempo permitido en formato de 24 horas
$hora_inicio = '08:00:00'; // Hora de inicio para considerar "Asistió"
$hora_limite = '09:30:00'; // Hora límite para considerar "Tarde"

$mensaje_error = "";

if (isset($_POST['btnRegistrar'])) {
    // Determinar el estado de asistencia
    if ($hora_actual >= $hora_inicio && $hora_actual <= $hora_limite) {
        $estado_asistencia = 'Asistió';
    } elseif ($hora_actual > $hora_limite) {
        $estado_asistencia = 'Tarde';
    } else {
        $estado_asistencia = 'Faltó';
    }

    // Verificar si el DNI existe en la tabla de estudiantes
    $dni_query = "SELECT dni FROM estudiantes WHERE dni = '$dni'";
    $dni_result = mysqli_query($conn, $dni_query);
    
    if ($dni_result && mysqli_num_rows($dni_result) > 0) {
        // Consulta de inserción o actualización
        $query = "
            INSERT INTO asistencia (estudiante_id, fecha, estado_asistencia, hora, hora_registro) 
            VALUES ('$dni', '$fecha', '$estado_asistencia', '$hora_actual', '$hora_registro')
            ON DUPLICATE KEY UPDATE estado_asistencia='$estado_asistencia', hora='$hora_actual', hora_registro='$hora_registro'
        ";
        if (!mysqli_query($conn, $query)) {
            echo "<p>Error: " . mysqli_error($conn) . "</p>";
        } else {
            echo "<p>Asistencia registrada exitosamente.</p>";
        }
    } else {
        $mensaje_error = "DNI '$dni' no encontrado en la base de datos.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registrar Asistencia</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <script>
        function updateClock() {
            var now = new Date();
            var hours = now.getHours().toString().padStart(2, '0');
            var minutes = now.getMinutes().toString().padStart(2, '0');
            var seconds = now.getSeconds().toString().padStart(2, '0');
            document.getElementById('hora').value = hours + ':' + minutes + ':' + seconds;
        }

        setInterval(updateClock, 1000); // Actualiza cada segundo
        window.onload = updateClock; // Actualiza inmediatamente al cargar la página
    </script>
    <style>
        .error-message {
            color: white;
            background-color: red;
            padding: 10px;
            margin-bottom: 10px;
            text-align: center;
            font-weight: bold;
            border-radius: 5px;
        }
        input[type="date"],
        input[type="text"] {
            width: 150px; /* Ajusta este valor según prefieras */
            padding: 5px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <header>
        <h1>Control de Asistencia - Pentágono School</h1>
    </header>
    <nav>
        <a href="index.php">Página Principal</a>
        <a href="agregar_estudiante.php">Agregar Estudiante</a>
        <a href="consultar_asistencia.php">Consultar Asistencia</a>
    </nav>
    <div class="content">
        <h1>Registrar Asistencia</h1>
        <form method="POST">
            <?php if ($mensaje_error) : ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($mensaje_error); ?>
                </div>
            <?php endif; ?>

            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" value="<?php echo htmlspecialchars($fecha); ?>" readonly><br>

            <label for="hora">Hora:</label>
            <input type="text" id="hora" name="hora" value="<?php echo htmlspecialchars($hora_actual); ?>" readonly><br>

            <label for="dni">DNI del Estudiante:</label>
            <input type="text" name="dni" required pattern="\d{8}" title="Ingrese exactamente 8 dígitos numéricos" maxlength="8" minlength="8"><br>
            
            <input type="submit" name="btnRegistrar" value="Registrar">
        </form>
    </div>
</body>
</html>















