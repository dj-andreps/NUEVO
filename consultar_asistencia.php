<?php
include_once("conexion.php");

// Configurar la zona horaria de Perú
date_default_timezone_set('America/Lima');

// Obtener la fecha seleccionada y el DNI
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');
$dni = isset($_GET['dni']) ? $_GET['dni'] : '';

// Construir la consulta SQL
$query = "
SELECT e.dni as estudiante_id, e.nombres, e.apellidos, 
    CASE 
        WHEN e.nivel_educativo = 'Inicial' THEN 'Inicial'
        ELSE e.nivel_educativo
    END as nivel_educativo,
    e.grado,
    COALESCE(a.estado_asistencia, 'No registrado') as estado_asistencia, 
    COALESCE(DATE_FORMAT(a.hora_registro, '%Y-%m-%d %H:%i:%s'), 'No registrado') as fecha_hora_registro
FROM estudiantes e
LEFT JOIN asistencia a ON e.dni = a.estudiante_id AND a.fecha = '$fecha'
WHERE e.dni LIKE '%$dni%'
";

// Ejecutar la consulta
$resultado = mysqli_query($conn, $query);

// Verificar si la consulta fue exitosa
if (!$resultado) {
    die("Error en la consulta de asistencia: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Consultar Asistencia</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Control de Asistencia - Pentágono School</h1>
    </header>
    <nav>
        <a href="index.php">Página Principal</a>
        <a href="agregar_estudiante.php">Agregar Estudiante</a>
        <a href="registrar_asistencia.php">Registrar Asistencia</a>
    </nav>
    <div class="content">
        <h1>Consultar Asistencia</h1>
        <form method="GET" style="display: inline-block;">
            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" class="input-fecha" value="<?php echo htmlspecialchars($fecha); ?>" required><br>

            <label for="dni">DNI:</label>
            <input type="text" name="dni" value="<?php echo htmlspecialchars($dni); ?>" placeholder="Ingrese DNI"><br>

            <input type="submit" value="Consultar">
        </form>

        <!-- Enlace para descargar asistencias en CSV con clase button -->
        <a href="descargar_asistencias.php?fecha=<?php echo urlencode($fecha); ?>&dni=<?php echo urlencode($dni); ?>" class="button">Descargar Asistencias</a>

        <table>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Nivel Educativo</th>
                <th>Grado</th>
                <th>Estado de Asistencia</th>
                <th>Fecha y Hora de Registro</th>
            </tr>

            <?php
            while ($row = mysqli_fetch_assoc($resultado)) {
                $estado_asistencia = $row['estado_asistencia'];
                $fecha_hora_registro = $row['fecha_hora_registro'];

                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['nombres']) . "</td>";
                echo "<td>" . htmlspecialchars($row['apellidos']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nivel_educativo']) . "</td>";
                echo "<td>" . htmlspecialchars($row['grado']) . "</td>";
                echo "<td>" . htmlspecialchars($estado_asistencia) . "</td>";
                echo "<td>" . htmlspecialchars($fecha_hora_registro) . "</td>";
                echo "</tr>";
            }
            ?>

        </table>
    </div>
</body>
</html>
