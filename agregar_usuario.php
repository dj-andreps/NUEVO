include_once("conexion.php"); ?>
<?php
include_once("conexion.php"); // Asegúrate de que este archivo tenga la conexión correcta a la base de datos

if (isset($_POST['agregar_usuario'])) {
    // Recoger los datos del formulario
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cifrar la contraseña antes de guardarla en la base de datos
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Consulta para insertar el nuevo usuario en la base de datos
    $query = "INSERT INTO usuarios (username, password) VALUES ('$username', '$password_hash')";

    // Ejecutar la consulta e informar si fue exitosa o no
    if (mysqli_query($conn, $query)) {
        echo "<p>Usuario agregado exitosamente.</p>";
    } else {
        echo "<p>Error al agregar usuario: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            color: #333;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            max-width: 300px;
            margin: 0 auto;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        p {
            text-align: center;
            font-weight: bold;
            color: green;
        }
    </style>
</head>
<body>
    <h1>Agregar Usuario</h1>
    <form method="POST" action="agregar_usuario.php">
        <label for="username">Nombre de usuario:</label><br>
        <input type="text" name="username" required><br><br>

        <label for="password">Contraseña:</label><br>
        <input type="password" name="password" required><br><br>

        <input type="submit" name="agregar_usuario" value="Agregar Usuario">
    </form>
</body>
</html>