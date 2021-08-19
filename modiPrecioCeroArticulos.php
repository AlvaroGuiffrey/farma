<?php
/*
 * Modifica los precios de los artículos en CERO
* en la tabla articulos de la DB con los precios 
* de los productos de la tabla productoscostos DB Plex
*
*  Si el artículo tiene precio CERO, lee el último precio
*  de la tabla plex_productoscostos de un producto y los carga
*  en la DB farma en la tabla articulos si fuera diferente
*  a CERO
*/

// Inicia o reanuda la sesión
session_start();
$_SESSION['dir'] = substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT']));

// Carga las clases necesarias
require_once $_SERVER['DOCUMENT_ROOT'].$_SESSION['dir'].'/includes/control/Clase.php';


Clase::define('DataBasePlex');
Clase::define('ArticuloModelo');

$conx = DataBasePlex::getInstance();

echo "MODIFICA PRECIOS EN CERO DE ";
echo "LA TABLA ARTICULOS<br>";
echo "- REVISION DE PRECIOS -<br>";
echo "(Modifica la tabla articulos, si precio es CERO, con los precios de los productos de plex)<br>";

$cont = 0;
date_default_timezone_set('America/Argentina/Buenos_Aires');

$oArticuloVO = new ArticuloVO();
$oArticuloModelo = new ArticuloModelo();

$oArticuloModelo->count();
$cantArticulos = $oArticuloModelo->getCantidad();
echo "<br>Son: ".$cantArticulos." registros en tabla articulos<br>";
$ultimoId = 58346;
for ($i=0; $i<=$ultimoId; $i++){
	$oArticuloVO->setId($i);
	$oArticuloModelo->find($oArticuloVO);
	if ($oArticuloModelo->getCantidad()>0){
		$cantLeidos++;
		if ($oArticuloVO->getPrecio()==0){
			if ($oArticuloVO->getCodigo()>9999900000){
				if ($oArticuloVO->getEstado()>0){
					$query = "SELECT IDProducto, Fecha, Precio FROM productoscostos WHERE IDProducto=".$oArticuloVO->getCodigo()." ORDER BY Fecha DESC LIMIT 1";
					$res = mysql_query($query) or die(mysql_error());
					if (mysql_num_rows($res)>0){
						$item = mysql_fetch_array($res);
						echo "(".$i.") ".$item[0]." -> $ ".$item[2]." fecha ".$item[1]."<br>";
						if ($item[2]>0){
							$oArticuloVO->setFechaPrecio($item[1]);
							$oArticuloVO->setPrecio($item[2]);
							$oArticuloVO->setIdUsuarioAct(1);
							$oArticuloVO->setFechaAct(date('Y-m-d H:i:s'));
							$oArticuloModelo->update($oArticuloVO);
							echo "  Modi--> $ ".$oArticuloVO->getPrecio()." Update: ".$oArticuloModelo->getCantidad()."<br>";
							$cantActualizados++;
						}
					}
				}
			}
		}
		mysql_free_result($res);
	}
}

echo "Cantidad de artículos leidos: ".$cantLeidos."<br>";
echo "Cantidad de precios modificados: ".$cantActualizados."<br>";
// cierra la conexión a la DB

DataBasePlex::closeInstance();
?>