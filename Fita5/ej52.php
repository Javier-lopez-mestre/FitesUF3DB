<html>
<head>
    <title>Cerca de llengües per nom de país</title>
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
    <h1>Cerca de llengües filtrant pel nom del país</h1>

    <?php
        // Connexió
        $conn = mysqli_connect('localhost','admin','admin123');
        mysqli_select_db($conn, 'world');

        // Recollim el text de cerca
        $text_pais = isset($_GET['pais']) ? $_GET['pais'] : '';

        // Construïm la consulta
        if (!empty($text_pais)) {
            // Escape del text per seguretat
            $text_escape = mysqli_real_escape_string($conn, $text_pais);

            $consulta = "
                SELECT country.Name AS CountryName,
                       countrylanguage.Language AS LanguageName,
                       countrylanguage.IsOfficial AS Official,
                       countrylanguage.Percentage AS Percentage
                FROM country
                JOIN countrylanguage ON country.Code = countrylanguage.CountryCode
                WHERE country.Name LIKE '%$text_escape%'
                ORDER BY country.Name, countrylanguage.Language
            ";
        } else {
            // Si no s'ha escrit res, mostrar tots els països
            $consulta = "
                SELECT country.Name AS CountryName,
                       countrylanguage.Language AS LanguageName,
                       countrylanguage.IsOfficial AS Official,
                       countrylanguage.Percentage AS Percentage
                FROM country
                JOIN countrylanguage ON country.Code = countrylanguage.CountryCode
                ORDER BY country.Name, countrylanguage.Language
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

    <!-- Resultats -->
    <table>
        <tr>
            <td><strong>País</strong></td>
            <td><strong>Llengua</strong></td>
            <td><strong>Oficial</strong></td>
            <td><strong>Percentatge</strong></td>
        </tr>

        <?php
            while ($fila = mysqli_fetch_assoc($resultat)) {
                echo "<tr>";
                echo "<td>".$fila['CountryName']."</td>";
                echo "<td>".$fila['LanguageName']."</td>";
                echo "<td>".$fila['Official']."</td>";
                echo "<td>".$fila['Percentage']."</td>";
                echo "</tr>";
            }
        ?>
    </table>

</body>
</html>
