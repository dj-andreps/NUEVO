<?php
include_once("conexion.php");

// Configurar la zona horaria de Perú
date_default_timezone_set('America/Lima');

// Establecer la zona horaria en la base de datos
mysqli_query($conn, "SET time_zone = '-05:00'"); // Hora de Perú

// Obtener la fecha seleccionada y el DNI
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');
$dni = isset($_GET['dni']) ? $_GET['dni'] : '';

// Construir la consulta SQL con orden adecuado
$query = "
    SELECT e.nombres as nombre, e.apellidos as apellido, 
        CASE 
            WHEN e.nivel_educativo = 'Inicial' THEN 'Inicial'
            ELSE e.nivel_educativo
        END as nivel_educativo,
        e.grado,
        COALESCE(a.estado_asistencia, 'No registrado') as estado_asistencia, 
        COALESCE(DATE_FORMAT(a.hora_registro, '%d/%m/%Y %H:%i:%s'), 'No registrado') as fecha_hora_registro
    FROM estudiantes e
    LEFT JOIN asistencia a ON e.dni = a.estudiante_id AND a.fecha = '$fecha'
    WHERE e.dni LIKE '%$dni%'
    ORDER BY e.apellidos ASC, e.nombres ASC
";

// Ejecutar la consulta
$resultado = mysqli_query($conn, $query);

// Verificar si la consulta fue exitosa
if (!$resultado) {
    die("Error en la consulta de asistencia: " . mysqli_error($conn));
}

// Configurar el tipo de contenido para descarga CSV
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment;filename=asistencias_' . date('Ymd') . '.csv');

// Abrir el archivo en modo escritura
$output = fopen('php://output', 'w');
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));  // Añade BOM para UTF-8

// Escribir la cabecera del archivo CSV
fputcsv($output, ['Nombre', 'Apellido', 'Nivel Educativo', 'Grado', 'Estado de Asistencia', 'Fecha y Hora de Registro'], ';');

// Escribir los datos de la consulta en el archivo CSV
while ($row = mysqli_fetch_assoc($resultado)) {
    fputcsv($output, [
        $row['nombre'],
        $row['apellido'],
        $row['nivel_educativo'],
        $row['grado'],
        $row['estado_asistencia'],
        $row['fecha_hora_registro'] !== 'No registrado' ? $row['fecha_hora_registro'] : 'No registrado'
    ], ';');
}

// Cerrar el archivo
fclose($output);
exit();
?>
