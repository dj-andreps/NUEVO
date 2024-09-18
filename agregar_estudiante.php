<?php
include_once("conexion.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Agregar Estudiante</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <style>
        input[type="text"], input[type="number"], select {
            width: 250px; /* Ajusta este valor según prefieras */
            padding: 5px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
    </style>
    <script>
        function actualizarGrados() {
            var nivel = document.getElementsByName("nivel_educativo")[0].value;
            var gradosInicial = ['Inicial'];
            var gradosPrimaria = ['Primero', 'Segundo', 'Tercero', 'Cuarto', 'Quinto', 'Sexto'];
            var gradosSecundaria = ['Primero', 'Segundo', 'Tercero', 'Cuarto', 'Quinto'];
            
            var gradoSelect = document.getElementsByName("grado")[0];
            gradoSelect.innerHTML = '';
            
            if (nivel === 'Inicial') {
                gradosInicial.forEach(function(grado) {
                    var option = document.createElement('option');
                    option.value = grado;
                    option.text = grado;
                    gradoSelect.add(option);
                });
            } else if (nivel === 'Primaria') {
                gradosPrimaria.forEach(function(grado) {
                    var option = document.createElement('option');
                    option.value = grado;
                    option.text = grado;
                    gradoSelect.add(option);
                });
            } else if (nivel === 'Secundaria') {
                gradosSecundaria.forEach(function(grado) {
                    var option = document.createElement('option');
                    option.value = grado;
                    option.text = grado;
                    gradoSelect.add(option);
                });
            }
        }
    </script>
</head>
<body>
    <header>
        <h1>Control de Asistencia - Pentágono School</h1>
    </header>
    <nav>
        <a href="index.php">Página Principal</a>
        <a href="registrar_asistencia.php">Registrar Asistencia</a>
        <a href="consultar_asistencia.php">Consultar Asistencia</a>
    </nav>
    <div class="content">
        <h1>Agregar Estudiante</h1>
        <form method="POST">
            <label for="nombre">Nombres:</label>
            <input type="text" name="nombre" required><br>

            <label for="apellidos">Apellidos:</label>
            <input type="text" name="apellido" required><br>

            <label for="dni">DNI:</label>
            <input type="number" name="dni" required pattern="\d{8}" title="Ingrese exactamente 8 dígitos numéricos" maxlength="8" minlength="8"><br>

            <label for="apoderado">Apoderado:</label>
            <input type="text" name="apoderado" required><br>

            <label for="celular">Celular:</label>
            <input type="text" name="celular"><br>

            <label for="parentesco">Parentesco:</label>
            <input type="text" name="parentesco"><br>

            <label for="nivel_educativo">Nivel Educativo:</label>
            <select name="nivel_educativo" onchange="actualizarGrados()" required>
                <option value="">Selecciona un nivel</option>
                <option value="Inicial">Inicial</option>
                <option value="Primaria">Primaria</option>
                <option value="Secundaria">Secundaria</option>
            </select><br>

            <label for="grado">Grado:</label>
            <select name="grado" required>
                <option value="">Selecciona un grado</option>
            </select><br>

            <input type="submit" name="btnAgregar" value="Agregar">
        </form>

        <?php
        if (isset($_POST['btnAgregar'])) {
            $nombre = $_POST['nombre'] ?? '';  
            $apellido = $_POST['apellido'] ?? '';
            $dni = $_POST['dni'] ?? '';
            $apoderado = $_POST['apoderado'] ?? '';
            $celular = $_POST['celular'] ?? '';
            $parentesco = $_POST['parentesco'] ?? '';
            $nivel_educativo = $_POST['nivel_educativo'] ?? '';
            $grado = $_POST['grado'] ?? '';

            $query = "
            INSERT INTO estudiantes (nombres, apellidos, dni, apoderado, celular, parentesco, nivel_educativo, grado)
            VALUES ('$nombre', '$apellido', '$dni', '$apoderado', '$celular', '$parentesco', '$nivel_educativo', '$grado')
            ";
            if (mysqli_query($conn, $query)) {
                echo "<p>Estudiante agregado exitosamente.</p>";
            } else {
                echo "<p>Error: " . mysqli_error($conn) . "</p>";
            }
        }
        ?>
    </div>
</body>
</html>



