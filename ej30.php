<html>
<head>
    <title>Exemple de lectura de dades a MySQL</title>
    <style>
        table,td {
            border: 1px solid black;
            border-spacing: 0px;
        }
    </style>
</head>

<body>
    <h1>Exemple de lectura de dades a MySQL</h1>

    <form method="GET" action="">
        <label>Buscar CountryCode:</label>
        <input type="text" name="code" required>
        <button type="submit">Buscar</button>
    </form>

    <br>

    <?php
        # conectar
        $conn = mysqli_connect('localhost','admin','admin123');
        mysqli_select_db($conn, 'world');

        if (isset($_GET['code'])) {

            $code = $_GET['code'];
            $code = mysqli_real_escape_string($conn, $code);

            # consulta
            $consulta = "SELECT * FROM city WHERE Name Like '%$code%';";

            $resultat = mysqli_query($conn, $consulta);

            if (!$resultat) {
                die("Consulta invàlida: " . mysqli_error($conn));
            }

            echo "<table>";
            echo "<thead><td colspan='4' align='center' bgcolor='cyan'>Llistat de ciutats</td></thead>";

            while ($registre = mysqli_fetch_assoc($resultat)) {
                echo "<tr>";
                echo "<td>".$registre['Name']."</td>";
                echo "<td>".$registre['CountryCode']."</td>";
                echo "<td>".$registre['District']."</td>";
                echo "<td>".$registre['Population']."</td>";
                echo "</tr>";
            }

            echo "</table>";
        }
    ?>

</body>
</html>
