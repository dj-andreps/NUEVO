<?php
// Datos de conexión a la base de datos
$host = "localhost";      // Cambiar si es necesario
$user = "root";           // Cambiar si tienes otro usuario
$password = "";           // Cambiar si tienes una contraseña para el usuario root
$database = "colegio";    // Nombre de la base de datos

// Crear la conexión
$conn = mysqli_connect($host, $user, $password, $database);

// Verificar la conexión
if (!$conn) {
    die("Error en la conexión a la base de datos: " . mysqli_connect_error());
}
?>
