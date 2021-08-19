<?php
session_start();
echo "Prueba Diccionario<br>";

require_once $_SERVER['DOCUMENT_ROOT'].'/caro/modulos/login/modelo/LoginVO.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/caro/includes/vista/Dato.php';
$oDato = Dato::singleton();
$oDato->setDato('tituloPagina', 'Inicio');
$oDato->setDato('tituloPagina', 'Modifico');
$oDato->setDato('usuario', 'Alvaro');
$oDato->setDato('botonVolver', 'Index.php');
$oDato->setDato('botonSalir', '../modulos/login/index.php?accion=Salir');

echo "Muestra datos por getDato()<br>";

echo "Título: ".$oDato->getDato('tituloPagina')."<br>";
echo "Usuario: ".$oDato->getDato('usuario')."<br>";
echo "Botón Volver: ".$oDato->getDato('botonVolver')."<br>";
echo "Botón Salir: ".$oDato->getDato('botonSalir')."<br><br>";

echo "Muestra datos de array<br>";

$aDatos = $oDato->getAllDatos();
var_dump($aDatos);
echo "<br>Título: ".$aDatos['tituloPagina']."<br>";
echo "Usuario: ".$aDatos['usuario']."<br>";
echo "Botón Volver: ".$aDatos['botonVolver']."<br>";
echo "Botón Salir: ".$aDatos['botonSalir']."<br><br>";

echo "2da Instancia<br><br>";
$oDato2 = Dato::singleton();
$oDato2->setDato('usuario', 'carina');
echo "Usuario2: ".$oDato2->getDato('usuario')."<br>";
echo "Usuario: ".$oDato->getDato('usuario')."<br>";


$oLoginVO = new LoginVO();
$oLoginVO->setAlias('Alvaro');
echo $oLoginVO->getAlias();
?> 
 