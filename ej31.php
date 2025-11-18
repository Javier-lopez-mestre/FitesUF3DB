<html>
<head>
    <title>Filtrar ciutats</title>
    <style>
        table, td {
            border: 1px solid black;
            border-spacing: 0px;
        }
    </style>
</head>

<body>
    <h1>Filtrar ciutats</h1>

    <form method="GET" action="">
        <label>Nom ciutat (opcional):</label>
        <input type="text" name="city" placeholder="Barcelona, Madrid..." value="<?php echo isset($_GET['city']) ? htmlspecialchars($_GET['city']) : ''; ?>">

        <label>Població mínima:</label>
        <input type="number" name="min_pop" min="0" placeholder="0" value="<?php echo isset($_GET['min_pop']) ? htmlspecialchars($_GET['min_pop']) : ''; ?>">

        <label>Població màxima:</label>
        <input type="number" name="max_pop" min="0" placeholder="1000000" value="<?php echo isset($_GET['max_pop']) ? htmlspecialchars($_GET['max_pop']) : ''; ?>">

        <button type="submit">Filtrar</button>
    </form>

    <br>

    <?php
        # Conexión
        $conn = mysqli_connect('localhost','admin','admin123');
        mysqli_select_db($conn, 'world');

        # Construimos condiciones según lo que se haya enviado
        $condiciones = [];

        if (isset($_GET['city']) && $_GET['city'] !== '') {
            $city = mysqli_real_escape_string($conn, $_GET['city']);
            $condiciones[] = "Name LIKE '%$city%'";
        }

        if (isset($_GET['min_pop']) && $_GET['min_pop'] !== '') {
            $min = (int) $_GET['min_pop'];
            $condiciones[] = "Population >= $min";
        }

        if (isset($_GET['max_pop']) && $_GET['max_pop'] !== '') {
            $max = (int) $_GET['max_pop'];
            $condiciones[] = "Population <= $max";
        }

        # Consulta final
        $sql = "SELECT * FROM city";
        if (count($condiciones) > 0) {
            $sql .= " WHERE " . implode(" AND ", $condiciones);
        }
        $sql .= " ORDER BY Population DESC;";

        $result = mysqli_query($conn, $sql);

        if (!$result) {
            die("Consulta invàlida: " . mysqli_error($conn));
        }

        # Mostramos la tabla
        echo "<table>";
        echo "<thead><td colspan='4' align='center' bgcolor='cyan'>Llistat de ciutats</td></thead>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>".$row['Name']."</td>";
            echo "<td>".$row['CountryCode']."</td>";
            echo "<td>".$row['District']."</td>";
            echo "<td>".$row['Population']."</td>";
            echo "</tr>";
        }

        echo "</table>";
    ?>
</body>
</html>
