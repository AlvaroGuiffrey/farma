<?php
/*
 * Cambia Codigo de Proveedor para Droguería del Sud
 *
 * Susutituye los 0 a la izquierda por el signo +
 *
 */

// Inicia o reanuda la sesión
session_start();
$_SESSION['dir'] = substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT']));

// Carga las clases necesarias
require_once $_SERVER['DOCUMENT_ROOT'].$_SESSION['dir'].'/includes/control/Clase.php';

Clase::define('ProductoModelo');


echo "CAMBIA LOS CODIGOS DE PROVEEDOR EN LOS ARTICULOS</br>";
echo "-- PARA DROG. DEL SUD -- <br>";
echo "SUSTITUYE LOS 0 DE LA IZQUIERDA POR +<br>";
echo "<br>";

$cont = 0;
date_default_timezone_set('America/Argentina/Buenos_Aires');

$oProductoVO = new ProductoVO();
$oProductoModelo = new ProductoModelo();


//cargo idProveedor para Drog. del Sud
$idProveedor = 2;
// cargo idUsuarioAct
$idUsuarioAct = 1;

$oProductoVO->setIdProveedor($idProveedor);
$aProductos = $oProductoModelo->findAllPorIdProveedor($oProductoVO);
$cantProductos = $oProductoModelo->getCantidad();
echo "<br>Son: ".$cantProductos." registros en tabla productos de DS<br>";

$cantLeidos = $cantModi = 0;

foreach ($aProductos as $fila){
    $oProductoVO->setId($fila['id']);
    $oProductoModelo->find($oProductoVO);
    if($oProductoModelo->getCantidad()>0) $cantLeidos++;
    // Modifico el codigo del proveedor
    // Reemplazo 0 a la izquierda por +
    $codigo_p = $oProductoVO->getCodigoP();
    $codigo_p = "+".ltrim($codigo_p, 0);

    echo " Cod. ".$oProductoVO->getCodigoP()." - ".$codigo_p."<br>";
    $oProductoVO->setCodigoP($codigo_p); // Codigo de Proveedor con +
    // -----------------------------

    $oProductoModelo->update($oProductoVO);
    if($oProductoModelo->getCantidad()>0) $cantModi++;
}

echo "-----------------------------------------------<br>";
echo "Cantidad de artículos leidos: ".$cantLeidos."<br>";
echo "Cantidad de CODIGOS modificados: ".$cantModi."<br>";
?>
