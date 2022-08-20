<?php
/*
 * Lee la lista de precios de Nippon para pruebas
 *
 *  Lee la lista de precios enviada por Nippon para realizar diferentes
 *  pruebas con los datos
 */

// Inicia o reanuda la sesión
session_start();
// Problemas por que no estoy en el directorio farma - OJO CUANDO CARGO CLASES
$_SESSION['dir'] = substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT']));

// Carga las clases necesarias
require_once $_SERVER['DOCUMENT_ROOT'].$_SESSION['dir'].'/includes/control/Clase.php';


Clase::define('ProductoModelo');
Clase::define('ArticuloModelo');

// Carga Lista del proveedor
//$archivoTXT = $_SERVER['DOCUMENT_ROOT'].$_SESSION['dir']."/archivos/nippon.csv";
// Carga Lista del proveedor
$archivoCSV = fopen ($_SERVER['DOCUMENT_ROOT'].$_SESSION['dir']."/archivos/nippon.csv","r");
// Cuenta registros Lista del proveedor
$numero_registros = 0;
while ($registro = fgetcsv($archivoCSV)){
    $numero_registros++;
}
fclose($archivoCSV);
//echo $archivoTXT."<br>";
//$contenido = file ( $archivoTXT );
//$numero_registros = sizeof( $contenido );

echo "========================================<br>";
echo "LEE LISTA DE PRECIOS DE: </br>";
echo "NIPPON<br>";
echo "========================================<br>";
echo "<br>";
echo "<br>Son: ".$numero_registros." registros en lista del proveedor<br>";
echo "<br>";

// Pone los totalizadores y contadores en 0
$cont = $registroD = 0;
// Setea la hora de Buenos Aires
date_default_timezone_set('America/Argentina/Buenos_Aires');

$oArticuloVO = new ArticuloVO();
$oArticuloModelo = new ArticuloModelo();
$oProductoVO = new ProductoVO();
$oProductoModelo = new ProductoModelo();

$archivoCSV = fopen ($_SERVER['DOCUMENT_ROOT'].$_SESSION['dir']."/archivos/nippon.csv","r");
/**
// Lee los productos de la lista descargada del proveedor
while ($data = fgetcsv ($archivoCSV, 1000, ";")) {
    $cont++;
    $codigoP = trim($data[0]);
    $codigoB = $data[1];
    $nombre = $data[2];
    $precio = str_replace(",", ".", $data[4]);
    //echo $codigoP." [".$codigoB."] ".$nombreUTF8d."(".$nombreUTF8e.") $ ".$precio."<br>";
    echo $codigoP." [".$codigoB."] ".$nombre." - $ ".$precio."<br>";


} // Fin foreach que lee la lista del proveedor
*/
echo "-----------------------------------------------<br>";
echo "Cantidad de artículos leidos: ".$cont."<br>";
fclose($archivoCSV);
?>
