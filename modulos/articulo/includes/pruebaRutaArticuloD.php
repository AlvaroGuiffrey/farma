<?php
// Carga las clases necesarias
require_once $_SERVER['DOCUMENT_ROOT'].'/farma/includes/control/Clase.php';

Clase::define('ArticuloDModelo');
$id = 3213;
$oArticuloVO = new ArticuloDVO();
$oArticuloModelo = new ArticuloDModelo();
$oArticuloVO->setId($id);
$oArticuloModelo->find($oArticuloVO);
echo $oArticuloModelo->cantidad."<br>";
echo $oArticuloVO->getNombre();

?>