<?php
session_start();
$_SESSION['dir']="/caro";
require_once $_SERVER['DOCUMENT_ROOT'].'/caro/modulos/partida/modelo/PartidaModelo.php';

$oPartidaVO = new PartidaVO();
$oPartidaVO->setIdArticulo(3);
$oPartidaModelo = new PartidaModelo();
$oPartidaModelo->findPorUltimo($oPartidaVO);
echo "Muestro Id Ultimo registro del artículo<br>";
echo $oPartidaVO->getId();
?>