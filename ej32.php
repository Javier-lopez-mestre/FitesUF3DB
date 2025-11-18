<html>
<head>
    <title>Filtrar llengües</title>
    <style>
        table, td {
            border: 1px solid black;
            border-spacing: 0px;
        }
    </style>
</head>

<body>
    <h1>Filtrar llengües</h1>

    <!-- FORMULARIO -->
    <form method="GET" action="">
        <label>Nom de la llengua (pot ser parcial):</label>
        <input type="text" name="lang" placeholder="Espanyol, Eng...">
        <button type="submit">Filtrar</button>
    </form>

    <br>

    <?php
        # Conexión
        $conn = mysqli_connect('localhost','admin','admin123');
        mysqli_select_db($conn, 'world');

        if (isset($_GET['lang']) && $_GET['lang'] !== '') {
            $lang_input = mysqli_real_escape_string($conn, $_GET['lang']);

            # Consulta: mostramos lenguaje, si es oficial lo indicamos
            $sql = "
                SELECT DISTINCT Language, IsOfficial
                FROM countrylanguage
                WHERE Language LIKE '%$lang_input%'
                ORDER BY Language ASC;
            ";

            $result = mysqli_query($conn, $sql);

            if (!$result) {
                die("Consulta invàlida: " . mysqli_error($conn));
            }

            echo "<table>";
            echo "<thead><td colspan='1' align='center' bgcolor='cyan'>Llistat de llengües</td></thead>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>".$row['Language'];
                if ($row['IsOfficial'] === 'T' || strtolower($row['IsOfficial']) === 'yes' || $row['IsOfficial'] === 'S') {
                    echo " [OFICIAL]";
                }
                echo "</td>";
                echo "</tr>";
            }

            echo "</table>";
        }
    ?>
</body>
</html>
