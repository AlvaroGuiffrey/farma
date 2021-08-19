<?php
/*
* Carga por primera vez los precios de los productos de PLEX
* en la tabla articulos de la DB
*
*  Lee el último precio de la tabla plex_productoscostos de un producto y los carga 
*  en la DB farma en la tabla articulos
*/

// Inicia o reanuda la sesión
session_start();
$_SESSION['dir'] = substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT']));

// Carga las clases necesarias
require_once $_SERVER['DOCUMENT_ROOT'].$_SESSION['dir'].'/includes/control/Clase.php';


Clase::define('DataBasePlex');
Clase::define('ArticuloModelo');

$conx = DataBasePlex::getInstance();

echo "CARGA EL ÚLTIMO PRECIO DE PRODUCTOS DE PLEX A </br>";
echo "LA TABLA ARTICULOS<br>";
echo "- PRIMERA CARGA -<br>";
echo "(Modifica la tabla articulos con los pecios de los productos de plex)<br>";

$cont = 0;
date_default_timezone_set('America/Argentina/Buenos_Aires');

$oArticuloVO = new ArticuloVO();
$oArticuloModelo = new ArticuloModelo();

$oArticuloModelo->count();
$cantArticulos = $oArticuloModelo->getCantidad();
echo "<br>Son: ".$cantArticulos." registros en tabla articulos<br>";

for ($i=0; $i<=$cantArticulos; $i++){
	$oArticuloVO->setId($i);
	$oArticuloModelo->find($oArticuloVO);
	if ($oArticuloModelo->getCantidad()>0){
		$cantLeidos++;
		if ($oArticuloVO->getPrecio()==0){
			$query = "SELECT IDProducto, Fecha, Precio FROM plex_productoscostos WHERE IDProducto=".$oArticuloVO->getCodigo()." ORDER BY Fecha DESC LIMIT 1";
			$res = mysql_query($query) or die(mysql_error());
			if (mysql_num_rows($res)>0){
				$item = mysql_fetch_array($res);
				$oArticuloVO->setFechaPrecio($item[1]);
				$oArticuloVO->setPrecio($item[2]);
				$oArticuloModelo->update($oArticuloVO);
				$cantActualizados++;
			}
		}
		mysql_free_result($res);
	}
}

echo "Cantidad de artículos leidos: ".$cantLeidos."<br>";
echo "Cantidad de precios agregados: ".$cantActualizados."<br>";
// cierra la conexión a la DB

DataBasePlex::closeInstance();
?>