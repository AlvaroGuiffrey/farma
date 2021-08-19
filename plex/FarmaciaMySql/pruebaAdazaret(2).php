<?php
Echo "Carga DB local<br>";

$linkL = mysql_connect('localhost', 'root', 'berlingo')
or die('No se pudo conectar: ' . mysql_error());
echo 'Connected successfully';
mysql_select_db('caro') or die('No se pudo seleccionar la base de datos');

// Realizar una consulta MySQL
$query = 'SELECT * FROM marcas';
$result = mysql_query($query) or die('Consulta fallida: ' . mysql_error());

// Imprimir los resultados en HTML
echo "<table>\n";
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	echo "\t<tr>\n";
	foreach ($line as $col_value) {
		echo "\t\t<td>$col_value</td>\n";
	}
		echo "\t</tr>\n";
}
echo "</table>\n";

// Liberar resultados
mysql_free_result($result);

echo "EXITO!!! con la DB LOCAL<br>";

echo "Carga DB SERVIDOR<br>";

$linkS = mysql_connect('adazaret.com', 'alvaritz_admin', 'dsN5197gjKl')
or die('No se pudo conectar: ' . mysql_error());
echo 'Connected successfully';
mysql_select_db('alvaritz_internasUCR') or die('No se pudo seleccionar la base de datos');

// Realizar una consulta MySQL
$query = 'SELECT * FROM distritos';
$result = mysql_query($query) or die('Consulta fallida: ' . mysql_error());

// Imprimir los resultados en HTML
echo "<table>\n";
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	echo "\t<tr>\n";
	foreach ($line as $col_value) {
		echo "\t\t<td>$col_value</td>\n";
	}
	echo "\t</tr>\n";
}
echo "</table>\n";

// Liberar resultados
mysql_free_result($result);

echo "EXITO!!! con la DB SERVIDOR<br>";

// Cerrar las conexiones
mysql_close($linkS);
mysql_close($linkL);
echo "Cerro las conecciones a la DB<br>";
?>