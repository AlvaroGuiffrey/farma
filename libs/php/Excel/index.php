<?php

//require_once("login/sesion.class.php");
require_once($_SERVER['DOCUMENT_ROOT']."/gestion3/login/sesion.class.php");
$sesion = new sesion();
 
if( $usuario == false ) {
   // si no se ha iniciado sesión redirecciona a la pagina login.php
   header("Location: login/login.php");
} else {
   // Aquí va el contenido de la pagina qu se mostrara en caso de que se haya iniciado sesion
header("Location:ajax/Suggest/catalogo.php");
}

?>
