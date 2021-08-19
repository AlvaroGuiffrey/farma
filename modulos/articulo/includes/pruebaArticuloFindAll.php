<?php
// Inicia o reanuda la sesión
session_start();

// Carga las clases necesarias
require_once $_SERVER['DOCUMENT_ROOT'].$_SESSION['dir'].'/includes/control/Clase.php';
// Define las clases
Clase::define('ArticuloModelo');

$oArticuloVO = new ArticuloVO();
$oArticuloModelo = new ArticuloModelo();
$tiempoInicio = time();
$oArticuloModelo->count();
$registrosArticulo = $oArticuloModelo->getCantidad();

$dbh = DataBase::getInstance();

$renglonDesde=0;
$limiteRenglones=10000;
$cont=0;
$i=0;
echo "--------- TABLA ARTICULOS ---------------<br>";
echo " Cantidad Registros: ".$registrosArticulo."<br>";
echo " ID ultimo :".$fila->id."<br>";
echo " cantidad leidos:".$cantidad."<br>";

for ($i=0; $i < $registrosArticulo; ){
//for ($i=0; $i < 1000; ){	
	$aArticulos = $oArticuloModelo->findAllLimite($renglonDesde, $limiteRenglones);
	echo " Cantidad consulta con limite: ".$oArticuloModelo->getCantidad()."<br>";
	
	foreach ($aArticulos as $articulo){
		$i++;
		echo "#".$i." ->".$articulo['id']." - (".$articulo['codigo'].") - ".$articulo['nombre']."<br>";
	}
	$renglonDesde = $renglonDesde + $limiteRenglones;
}

echo "Total Leidos ($i): ".$i."<br>";
?>
