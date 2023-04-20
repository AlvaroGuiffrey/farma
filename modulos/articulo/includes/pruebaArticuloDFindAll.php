<?php
// Inicia o reanuda la sesión
session_start();

// Carga las clases necesarias
//require_once $_SERVER['DOCUMENT_ROOT'].$_SESSION['dir'].'/includes/control/Clase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/farma/includes/control/Clase.php';
// Define las clases
Clase::define('ArticuloDModelo');

$oArticuloVO = new ArticuloDVO();
$oArticuloModelo = new ArticuloDModelo();
$tiempoInicio = time();
$oArticuloModelo->count();
$registrosArticulo = $oArticuloModelo->getCantidad();

$dbh = DataBase::getInstance();

$renglonDesde=0;
$limiteRenglones=10000;
$cont=0;
$i=0;
echo "--------- TABLA ARTICULOS D ---------------<br>";
echo " Cantidad Registros: ".$registrosArticulo."<br>";
//echo " ID ultimo :".$fila->id."<br>";
//echo " cantidad leidos:".$cantidad."<br>";
echo "------------------------------------------------<br>";

for ($i=0; $i < $registrosArticulo; ){
//for ($i=0; $i < 1000; ){	
	$aArticulos = $oArticuloModelo->findAllLimite($renglonDesde, $limiteRenglones);
	echo " Cantidad consulta con limite: ".$oArticuloModelo->getCantidad()."<br>";
	
	foreach ($aArticulos as $articulo){
		$i++;
		// Verifico los que voy a agregar 
		if ($articulo['codigo'] < 3000000000 OR $articulo['codigo'] > 3999999999) {
			echo "#".$i." ->".$articulo['id']." - (".$articulo['codigo'].") - ".$articulo['nombre']."<br>";
			$cont++;
		} else {
			echo "** NO #".$i." ->".$articulo['id']." - (".$articulo['codigo'].") - ".$articulo['nombre']." --> NO AGREGA<br>";
		}
		
	}
	$renglonDesde = $renglonDesde + $limiteRenglones;
}

echo "Total Leidos ($i): ".$i."<br>";
echo "Total agrega ($cont): ".$cont."<br>";
?>
