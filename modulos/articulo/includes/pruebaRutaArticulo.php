<?php
// Carga las clases necesarias
require_once $_SERVER['DOCUMENT_ROOT'].'/farma/includes/control/Clase.php';

Clase::define('ArticuloModelo');
$id = 12;
$oArticuloVO = new ArticuloVO();
$oArticuloModelo = new ArticuloModelo();
$oArticuloVO->setId($id);
$oArticuloModelo->find($oArticuloVO);
echo $oArticuloModelo->cantidad."<br>";
echo $oArticuloVO->getNombre();

?>