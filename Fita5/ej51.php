<html>
<head>
    <title>Cerca de ciutats per nom de país</title>
    <style>
        table {
            border-collapse: collapse;
            width: 60%;
        }
        table, th, td {
            border: 1px solid #444;
        }
        th {
            background-color: #f2f2f2;
            padding: 8px;
        }
        td {
            padding: 6px;
        }
        tr:nth-child(even){
            background-color: #fafafa;
        }
    </style>
</head>

<body>
    <h1>Cerca de ciutats filtrant pel nom del país</h1>

    <?php
        // Connexió
        $conn = mysqli_connect('localhost','admin','admin123');
        mysqli_select_db($conn, 'world');

        // Recollim el text de cerca
        $text_pais = isset($_GET['pais']) ? $_GET['pais'] : '';

        // Construïm la consulta
        if (!empty($text_pais)) {
            $text_escape = mysqli_real_escape_string($conn, $text_pais);

            $consulta = "
                SELECT city.Name AS CityName, country.Name AS CountryName
                FROM city
                JOIN country ON city.CountryCode = country.Code
                WHERE country.Name LIKE '%$text_escape%'
                ORDER BY city.Name
            ";
        } else {
            $consulta = "
                SELECT city.Name AS CityName, country.Name AS CountryName
                FROM city
                JOIN country ON city.CountryCode = country.Code
                ORDER BY city.Name
            ";
        }

        $resultat = mysqli_query($conn, $consulta);

        if (!$resultat) {
            $message  = 'Consulta invàlida: ' . mysqli_error($conn) . "\n";
            $message .= 'Consulta realitzada: ' . $consulta;
            die($message);
        }
    ?>

    <!-- Formulari -->
    <form method="GET">
        <label>Introdueix el nom del país (coincidència parcial):</label><br>
        <input type="text" name="pais" value="<?= htmlspecialchars($text_pais) ?>">
        <button type="submit">Cercar</button>
    </form>

    <br>

    <!-- Taula de resultats -->
    <table>
        <tr>
            <th>Ciutat</th>
            <th>País</th>
        </tr>

        <?php
            while ($fila = mysqli_fetch_assoc($resultat)) {
                echo "<tr>";
                echo "<td>".$fila["CityName"]."</td>";
                echo "<td>".$fila["CountryName"]."</td>";
                echo "</tr>";
            }
        ?>
    </table>

</body>
</html>
