<?php
/*
 * Carga por primera vez Drog. del Sud como proveedor de referencia para los precios 
 * de los artículos con código > 9999900000 (cargados por farmacia)
* en la tabla articulos de la DB que coincide el codigo de barra
*
*  Lee el código de barra de un artículo y lo compara para el proveedor Drog del Sud
*  si existe en la lista de precios de la tabla productos, en caso afirmativo
*  vincula la equivalencia en la DB farma en las tablas articulos y productos
*/

// Inicia o reanuda la sesión
session_start();
$_SESSION['dir'] = substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT']));

// Carga las clases necesarias
require_once $_SERVER['DOCUMENT_ROOT'].$_SESSION['dir'].'/includes/control/Clase.php';


Clase::define('ProductoModelo');
Clase::define('ArticuloModelo');


echo "CARGA DROG DEL SUD COMO PROV. DE REF.PARA LOS PRECIO DE </br>";
echo "LA TABLA ARTICULOS Y SEÑALA LA EQUIVALENCIA CON LA TABLA PRODUCTOS<br>";
echo "- PRIMERA CARGA -<br>";
echo "<br>";

$cont = 0;
date_default_timezone_set('America/Argentina/Buenos_Aires');

$oArticuloVO = new ArticuloVO();
$oArticuloModelo = new ArticuloModelo();
$oProductoVO = new ProductoVO();
$oProductoModelo = new ProductoModelo();

$oArticuloModelo->count();
$cantArticulos = $oArticuloModelo->getCantidad();
echo "<br>Son: ".$cantArticulos." registros en tabla articulos<br>";

//cargo idProveedor para Drog. del Sud
$idProveedor = 2;
// cargo idUsuarioAct
$idUsuarioAct = 1;

// Primer articulo con código mayor a 9999900000
// id: 46932 codigo 9999900002 codigo_b 7501056346164
for ($i=46932; $i<=$cantArticulos; $i++){

	$codigoBarra = "No";
	$actArticulo = "No";
	$actProducto = "No";
	$oArticuloVO->setId($i);
	$oArticuloModelo->find($oArticuloVO);
	if ($oArticuloModelo->getCantidad()>0){
		$cantLeidos++;
		$codigoBarra = $oArticuloVO->getCodigoB();
		if ($oArticuloVO->getCodigoB() > 0){
			$idArticulo = $oArticuloVO->getId();
			$oProductoVO->setIdProveedor($idProveedor);
			$oProductoVO->setCodigoB($oArticuloVO->getCodigoB());
			$oProductoModelo->findPorCodigoBProveedor($oProductoVO);
			if ($oProductoModelo->getCantidad()>0){
				$cantEquivalentes++;
				$codigoBarra = $oProductoVO->getCodigoB();
				$equivalencia = 1;
				$fechaAct = date('Y-m-d H:i:s');
				// actualizo tabla articulos
				$oArticuloVO->setIdProveedor($idProveedor);
				$oArticuloVO->setEquivalencia($equivalencia);
				$oArticuloVO->setIdUsuarioAct($idUsuarioAct);
				$oArticuloVO->setFechaAct($fechaAct);
				$oArticuloModelo->update($oArticuloVO);
				$actArticulo = $oArticuloVO->getCodigo();
				// actualizo tabla productos
				$oProductoVO->setIdArticulo($idArticulo);
				$oProductoVO->setIdUsuarioAct($idUsuarioAct);
				$oProductoVO->setFechaAct($fechaAct);
				$oProductoModelo->update($oProductoVO);
				$actProducto = $oProductoVO->getCodigoB();
			}
		}
	}
	echo $i."-> ".$codigoBarra." - ".$actArticulo." / ".$actProducto."<br>";
}
echo "-----------------------------------------------<br>";
echo "Cantidad de artículos leidos: ".$cantLeidos."<br>";
echo "Cantidad de precios agregados: ".$cantEquivalentes."<br>";

?>