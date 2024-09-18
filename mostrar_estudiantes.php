<?php
include_once("conexion.php");

$mostrar_resultados = false; // Por defecto, no se mostrarán los resultados
$errores = ""; // Para almacenar errores o mensajes

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nivel_educativo_filtro = isset($_POST['nivel_educativo']) ? $_POST['nivel_educativo'] : '';
    $grado_filtro = isset($_POST['grado']) ? $_POST['grado'] : '';

    // Asegurarnos de que el nivel educativo fue seleccionado
    if (!empty($nivel_educativo_filtro)) {
        // Depuración: Mostrar el valor del nivel educativo recibido
        echo "Nivel educativo recibido: " . htmlspecialchars($nivel_educativo_filtro) . "<br>";

        $query = "SELECT * FROM estudiantes WHERE nivel_educativo = '$nivel_educativo_filtro'";

        // Si es Primaria o Secundaria y se seleccionó un grado, agregar el filtro del grado
        if (($nivel_educativo_filtro === 'Primaria' || $nivel_educativo_filtro === 'Secundaria') && !empty($grado_filtro)) {
            $query .= " AND grado = '$grado_filtro'";
        }

        $resultado = mysqli_query($conn, $query);

        // Verificar si la consulta tuvo resultados
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $mostrar_resultados = true;
        } else {
            $errores = "No se encontraron estudiantes para el nivel educativo seleccionado: " . htmlspecialchars($nivel_educativo_filtro);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Buscar Estudiantes por Nivel Educativo</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <style>
        form {
            text-align: center; /* Centrar los botones dentro del formulario */
        }

        .btn-filtro {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
            display: inline-block; /* Asegurarse de que se alineen en línea */
        }

        .btn-filtro:hover {
            background-color: #0056b3;
        }

        #grado-selector {
            margin: 10px 0;
            display: none;
        }

        header h1 {
            margin: 0;
            color: blue; /* Cambia el color del texto a azul */
        }

        nav {
            background-color: #004d40; /* Verde más oscuro */
            padding: 10px;
            text-align: center;
        }

        nav a {
            color: #b2dfdb; /* Verde claro */
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }

        nav a:hover {
            text-decoration: underline;
            color: #ffffff; /* Blanco para el hover */
        }
    </style>
    <script>
        function seleccionarNivel(nivel) {
            // Establecer el valor del nivel educativo
            document.getElementById('nivel_educativo').value = nivel;

            // Cambiar la etiqueta según el nivel educativo seleccionado
            var gradoLabel = document.getElementById('grado-label');
            gradoLabel.textContent = nivel; // Cambiar la etiqueta al nivel educativo seleccionado

            // Si el nivel es "Primaria" o "Secundaria", mostrar el selector de grados
            var gradoSelector = document.getElementById('grado-selector');
            var gradoSelect = document.getElementById('grado');
            gradoSelect.innerHTML = ''; // Limpiar opciones previas

            if (nivel === 'Primaria') {
                var gradosPrimaria = ['Primero', 'Segundo', 'Tercero', 'Cuarto', 'Quinto', 'Sexto'];
                gradosPrimaria.forEach(function(grado) {
                    var option = document.createElement('option');
                    option.value = grado;
                    option.text = grado;
                    gradoSelect.add(option);
                });
                gradoSelector.style.display = 'block'; // Mostrar el selector de grados
            } else if (nivel === 'Secundaria') {
                var gradosSecundaria = ['Primero', 'Segundo', 'Tercero', 'Cuarto', 'Quinto'];
                gradosSecundaria.forEach(function(grado) {
                    var option = document.createElement('option');
                    option.value = grado;
                    option.text = grado;
                    gradoSelect.add(option);
                });
                gradoSelector.style.display = 'block'; // Mostrar el selector de grados
            } else {
                gradoSelector.style.display = 'none'; // Ocultar el selector de grados para Inicial
            }

            // Enviar el formulario automáticamente si es "Inicial"
            if (nivel === 'Inicial') {
                document.getElementById('form-filtro').submit();
            }
        }
    </script>
</head>
<body>
    <header>
        <h1>Control de Asistencia - Pentágono School</h1>
    </header>

    <!-- Navegación -->
    <nav>
        <a href="index.php">Página Principal</a>
        <a href="registrar_asistencia.php">Registrar Asistencia</a>
        <a href="consultar_asistencia.php">Consultar Asistencia</a>
    </nav>

    <div class="content">
        <h1>Buscar Estudiantes por Nivel Educativo</h1>
        
        <!-- Botones centrados para seleccionar el nivel educativo -->
        <form method="POST" id="form-filtro">
            <input type="hidden" name="nivel_educativo" id="nivel_educativo" value="">
            <button type="button" onclick="seleccionarNivel('Inicial')" class="btn-filtro">Inicial</button>
            <button type="button" onclick="seleccionarNivel('Primaria')" class="btn-filtro">Primaria</button>
            <button type="button" onclick="seleccionarNivel('Secundaria')" class="btn-filtro">Secundaria</button>

            <!-- Selector de grados (oculto inicialmente) -->
            <div id="grado-selector">
                <label for="grado" id="grado-label">Grado:</label>
                <select name="grado" id="grado">
                    <option value="">Selecciona un grado</option>
                </select>
                <input type="submit" value="Buscar">
            </div>
        </form>

        <!-- Mostrar la tabla de resultados solo si se realiza una consulta -->
        <?php if ($mostrar_resultados): ?>
            <table>
                <tr>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Apoderado</th>
                    <th>Celular</th>
                    <th>Parentesco</th>
                    <th>Nivel Educativo</th>
                    <th>Grado</th>
                </tr>

                <?php
                // Recorrer resultados
                while ($row = mysqli_fetch_assoc($resultado)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['dni']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nombres']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['apellidos']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['apoderado']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['celular']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['parentesco']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nivel_educativo']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['grado']) . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        <?php elseif (!empty($errores)): ?>
            <!-- Mostrar errores o mensajes -->
            <p><?php echo htmlspecialchars($errores); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
