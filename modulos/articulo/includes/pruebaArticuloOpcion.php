<?php
// Inicia o reanuda la sesión
session_start();

// Carga las clases necesarias
require_once $_SERVER['DOCUMENT_ROOT'].$_SESSION['dir'].'/includes/control/Clase.php';
// Define las clases
Clase::define('ArticuloOpcionProv');

$aOpciones = $array = ArticuloOpcionProv::getArray();
$html  = '<!DOCTYPE html>
		<html>
		<body>
		<form>
			<br>
  			<label title="Opciones para modificar proveedor de referencia">Modifica Prov.: </label>';		
$ultimo = array_pop($array);
print_r($array);
echo "Ultimo: ".$ultimo["valor"]."<br>";
foreach ($aOpciones as $clave=>$valor){
	echo $clave." -".$valor["valor"]."<br>";
	if ($valor["valor"] > 0){
  		$html .='<input type="radio" name="opcionProv" value="'.$valor["valor"].'" title="'.$valor["comentario"].'"> <label title="'.$valor["comentario"].'">'.$valor["radioButton"].'</label> ';
  		if ($valor["valor"]<$ultimo["valor"]){
  			$html .=' - ';
  		}
	}
  	
}

$html .='<br></form> 
		</body>
		</html>';

echo $html;

?>