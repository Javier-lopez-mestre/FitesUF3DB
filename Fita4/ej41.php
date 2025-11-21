<html>
 <head>
 	<title>Exemple de llistat de països per continent</title>
 	<style>
 		body{
 		}
 		table,td {
 			border: 1px solid black;
 			border-spacing: 0px;
 		}
 	</style>
 </head>
 
 <body>
 	<h1>Llistat de països amb filtre per continent</h1>

 	<?php
 		# (1.1) Connectem a MySQL (host,usuari,contrassenya)	
 		$conn = mysqli_connect('localhost','admin','admin123');
 
 		# (1.2) Triem la base de dades amb la que treballarem
 		mysqli_select_db($conn, 'world');

        // Obtenim la llista de continents per al menú desplegable
        $continents_query = "SELECT DISTINCT Continent FROM country ORDER BY Continent";
        $continents_result = mysqli_query($conn, $continents_query);

        // Recollim el continent seleccionat (si n'hi ha)
        $continent = isset($_GET['continent']) ? $_GET['continent'] : "";

        // Si s'ha seleccionat un continent fem la consulta filtrada
        if ($continent != "") {
            $continent_safe = mysqli_real_escape_string($conn, $continent);
            $consulta = "SELECT Name, Code, Region, Population FROM country WHERE Continent = '$continent_safe' ORDER BY Name";
        } else {
            // Si no, per defecte mostrem tots els països ordenats pel nom
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
        <label>Filtra per continent:</label>
        <select name="continent">
            <option value="">-- Tots els continents --</option>
            <?php while ($row = mysqli_fetch_assoc($continents_result)): ?>
                <option value="<?= $row['Continent'] ?>" <?= ($continent == $row['Continent']) ? 'selected' : '' ?>>
                    <?= $row['Continent'] ?>
                </option>
            <?php endwhile; ?>
        </select>
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
