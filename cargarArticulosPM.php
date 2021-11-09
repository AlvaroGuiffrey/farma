<?php
/*
 * Carga por primera vez artículos con precios máximos
 * en la tabla articulos_pm de la DB
 *
 *  Lee el csv con datos de los artículos con precios máximos según
 *  resolución del Gobierno Nacional
 */

// Inicia o reanuda la sesión
session_start();
$_SESSION['dir'] = substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT']));

// Carga las clases necesarias
require_once $_SERVER['DOCUMENT_ROOT'].$_SESSION['dir'].'/includes/control/Clase.php';

Clase::define('ArticuloPMModelo');


echo "CARGA LOS PRECIO MAXIMOS IMPUESTO POR EL GOB.NACIONAL</br>";
echo "- PRIMERA CARGA -<br>";
echo "<br>";

$cont = $cantAgregados = $cantLista = 0;
date_default_timezone_set('America/Argentina/Buenos_Aires');

$oArticuloPMVO = new ArticuloPMVO();
$oArticuloPMModelo = new ArticuloPMModelo();

$oArticuloPMModelo->truncate();
$oArticuloPMModelo->count();
$cantArticulos = $oArticuloPMModelo->getCantidad();
echo "<br>Son: ".$cantArticulos." registros en tabla PRECIOS MÁXIMOS<br>";

// Carga Lista del proveedor
$archivoCSV = fopen ($_SERVER['DOCUMENT_ROOT'].$_SESSION['dir']."/archivos/preciosMaximos.csv","r");
// Cuenta registros Lista del proveedor
while ($registro = fgetcsv($archivoCSV)){
    $cantLista++;
}
fclose($archivoCSV);

// Arma la nueva tabla productos_prov para el proveedor
$archivoCSV = fopen ($_SERVER['DOCUMENT_ROOT'].$_SESSION['dir']."/archivos/preciosMaximos.csv","r");
while ($data = fgetcsv ($archivoCSV, 1000, ";")) {
    $cont++;
    $codigoB = $data[0];
    $nombre = $data[1];
    $precio = str_replace(",", ".", $data[2]);
    // Inserto el producto en tabla producto_prov
    $id = 0;
    $oArticuloPMVO->setCodigoB($codigoB);
    $oArticuloPMVO->setNombre($nombre);
    $oArticuloPMVO->setPrecio($precio);
    $oArticuloPMVO->setId($id);
    $oArticuloPMModelo->insert($oArticuloPMVO);
    $cantAgregados++;
}
// Cierra archivo csv
fclose($archivoCSV);
$cantLeidos = $cont;


echo "-----------------------------------------------<br>";
echo "Cantidad de la lista: ".$cantLista."<br>";
echo "Cantidad de artículos leidos: ".$cantLeidos."<br>";
echo "Cantidad de cargados: ".$cantAgregados."<br>";

?>
