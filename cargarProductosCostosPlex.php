<?php
/*
 * Carga por primera vez los precios de los productos de PLEX 
 * en la DB
*
*  Lee productoscostos.csv y los carga en la DB farma en la 
*  tabla plex_productoscostos
*/

// Inicia o reanuda la sesión
session_start();
$_SESSION['dir'] = substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT']));

// Carga las clases necesarias
require_once $_SERVER['DOCUMENT_ROOT'].$_SESSION['dir'].'/includes/control/Clase.php';


Clase::define('DataBasePlex');

/*
 // Realiza la conexión a la DB
define("DB_SERVER", "localhost");	//tu servidor
define("DB_USER", "root");			//tu usuario
define("DB_PW", "berlingo");		//contraseña
define("DB_NAME", "farma");			//base de datos
$conx = mysql_connect(DB_SERVER, DB_USER, DB_PW);
mysql_select_db(DB_NAME);
*/

$conx = DataBasePlex::getInstance();

// abre archivo CSV descargado de DB PLEX
$archivo = fopen ("/var/www/html/farma/plex/FarmaciaMySql/productoscostos.csv","r");
echo "CARGA BASE DE DATOS PRECIOS DE PRODUCTOS DE PLEX</br>";
echo "- PRIMERA CARGA -<br>";
echo "(Agrega todos los precios de los artículos sin verificar si ya estan cargados)<br>";

$cont = 0;
while ($data = fgetcsv ($archivo, 1000, ",")) {
	$cont++;
	//echo $cont." - ".$data[0]." - ".$data[2]." - $".$data[4]."<br>";
	// agrega cuando $cont > 1 para evitar los títulos del CSV
	if ($cont>1){
		$IDProducto = $data[0];
		$TipoLista = $data[1];
		$Fecha = $data[2];
		$IDUsuario = $data[3];
		$Precio = $data[4];
		$Origen = $data[5];

		$query = "INSERT INTO plex_productoscostos
				(IDProducto, TipoLista, Fecha, IDUsuario, Precio, Origen)
				VALUES
				('$IDProducto', '$TipoLista', '$Fecha', '$IDUsuario', '$Precio', '$Origen')";
		$res = mysql_query($query) or die(mysql_error());
		mysql_free_result($res);
			
	}

}
$cant=$cont - 1;
echo "Cantidad de productos cargados: ".$cant."<br>";
// cierra la conexión a la DB

DataBasePlex::closeInstance();
?>