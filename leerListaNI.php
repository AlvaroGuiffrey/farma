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
$archivoTXT = $_SERVER['DOCUMENT_ROOT'].$_SESSION['dir']."/archivos/nippon.txt";
//echo $archivoTXT."<br>";
$contenido = file ( $archivoTXT );
$numero_registros = sizeof( $contenido );

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

// Lee los productos de la lista descargada del proveedor
for( $i = 0; $i < sizeof( $contenido ); $i++) {
    $linea = trim( $contenido[ $i ] );
    
    echo $linea."<br>";
    // Lista con caracteres especiales tiene que hacer el decode
    // con Ñ no necesita hacerlo
     //$linea = utf8_decode($linea); // pone el signo ? cuando viene caracter especial 
    
    // Lista con ÑÑÑÑÑÑÑ
    $codigoP = substr($linea, 0, 13);
    $codigoP = trim($codigoP);
    $codigoB = substr($linea, 14, 13);
    $codigoB = trim($codigoB);
    // Nombre para prueba
    $nombre = substr($linea, 28, 30);
    $nombre = trim($nombre);
    // ------ Lista de Nippon con Ñ -----------
    $nombre = utf8_encode($nombre); // muestra las ñ
    // ------ LIsta de NIPPON con Caracteres ------------
    
    // ---------------------------------------
    $precio = substr($linea, 59, 6);
    $precio =trim($precio);
    // Fin lista con ÑÑÑÑÑ
            
    //echo $codigoP." [".$codigoB."] ".$nombreUTF8d."(".$nombreUTF8e.") $ ".$precio."<br>"; 
    echo $codigoP." [".$codigoB."] ".$nombre." - $ ".$precio."<br>"; 
         
           
} // Fin foreach que lee la lista del proveedor
echo "-----------------------------------------------<br>";
echo "Cantidad de artículos leidos: ".$registroD."<br>";

?>
