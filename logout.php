<?php
session_start();
session_destroy(); // Destruye todas las variables de sesión
header("Location: login.php"); // Redirigir al login
exit();
?>
