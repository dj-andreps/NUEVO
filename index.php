<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirigir a la página de inicio de sesión si no ha iniciado sesión
    exit();
}

include_once("conexion.php"); // Incluir conexión a la base de datos (si necesitas hacer consultas más adelante)
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Control de Asistencia - Pentágono School</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Control de Asistencia - Pentágono School</h1>
    </header>
    <nav>
        <a href="agregar_estudiante.php">Agregar Estudiante</a>
        <a href="registrar_asistencia.php">Registrar Asistencia</a>
        <a href="consultar_asistencia.php">Consultar Asistencia</a>
        <a href="mostrar_estudiantes.php">Mostrar Estudiantes</a>
        <a href="logout.php">Cerrar Sesión</a> <!-- Botón para cerrar sesión -->
    </nav>
    <div class="content">
        <h1>Bienvenido al Sistema de Control de Asistencia</h1>
        <h2>COLEGIO PENTAGONO SCHOOL</h2>
    </div>
</body>
</html>
