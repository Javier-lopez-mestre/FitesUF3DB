<html>
<head>
    <title>Exemple de llistat de països per continent amb checkboxes</title>
    <style>
        body{
        }
        table, td {
            border: 1px solid black;
            border-spacing: 0px;
        }
    </style>
</head>

<body>
    <h1>Llistat de països amb filtre per continent (checkbox múltiple)</h1>

    <?php
        $conn = mysqli_connect('localhost','admin','admin123');
        mysqli_select_db($conn, 'world');

        // Obtenim la llista de continents per mostrar checkboxes
        $continents_query = "SELECT DISTINCT Continent FROM country ORDER BY Continent";
        $continents_result = mysqli_query($conn, $continents_query);

        // Recollim els continents seleccionats (array)
        $continents_seleccionats = isset($_GET['continents']) ? $_GET['continents'] : [];

        // Comprovem si l'array no està buit
        if (!empty($continents_seleccionats)) {
            // Escapem cada continent per seguretat i preparem la consulta amb IN(...)
            $continents_escapats = array_map(function($c) use ($conn) {
                return "'".mysqli_real_escape_string($conn, $c)."'";
            }, $continents_seleccionats);

            $continents_list = implode(',', $continents_escapats);

            $consulta = "SELECT Name, Code, Region, Population FROM country WHERE Continent IN ($continents_list) ORDER BY Name";
        } else {
            // Si no hi ha continents seleccionats, mostrar tots
            $consulta = "SELECT Name, Code, Region, Population FROM country ORDER BY Name";
        }

        $resultat = mysqli_query($conn, $consulta);

        if (!$resultat) {
            $message  = 'Consulta invàlida: ' . mysqli_error($conn) . "\n";
            $message .= 'Consulta realitzada: ' . $consulta;
            die($message);
        }
    ?>

    <form method="GET">
        <label>Filtra per continent:</label><br>
        <?php
            mysqli_data_seek($continents_result, 0); 
            while ($row = mysqli_fetch_assoc($continents_result)) {
                $continent_name = $row['Continent'];
                $checked = in_array($continent_name, $continents_seleccionats) ? 'checked' : '';
                echo "<input type='checkbox' name='continents[]' value='" . htmlspecialchars($continent_name) . "' $checked> $continent_name<br>";
            }
        ?>
        <button type="submit">Tramet la consulta</button>
    </form>

    <br>

    <ul>
    <?php
        while ($registre = mysqli_fetch_assoc($resultat)) {
            echo "<li>".$registre["Name"]."</li>";
        }
    ?>
    </ul>
</body>
</html>
