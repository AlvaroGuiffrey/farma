<?php
// Inicia o reanuda la sesión
session_start();
echo $_SERVER['DOCUMENT_ROOT']."<br>";
// Carga las clases necesarias
require_once $_SERVER['DOCUMENT_ROOT'].'/farma/includes/persistencia/singleton/DataBasePlex.php';

// Instancia la clase conexión

DataBasePlex::getInstance();
DataBasePlex::closeInstance();
?>