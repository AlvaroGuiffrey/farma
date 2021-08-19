<?php
/*
 * Lee la lista de precios de Drog. del Sud para pruebas
 *
 *  Lee la lista de precios descargada de Drog del Sud para realizar diferentes
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
$archivoTXT = $_SERVER['DOCUMENT_ROOT'].$_SESSION['dir']."/archivos/delsud.txt";
//echo $archivoTXT."<br>";
$contenido = file ( $archivoTXT );
$numero_registros = sizeof( $contenido );

echo "========================================<br>";
echo "LEE LISTA DE PRECIOS DE: </br>";
echo "DROGUERIA DEL SUD S.A.<br>";
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

	$tipoReg = substr($linea, 0, 1);
	// --- TIPO REGISTRO "D" ---
	if ($tipoReg == "D"){ // Si tipo de registro es igual a "D" arma tabla
		$registroD++;
		// ------------------------------------------------------
		// Windows lee TXT sin problemas en caracteres especiales
		// Revisar todo para LINUX
		// ------------------------------------------------------
		// Codigo de Producto del Proveedor (Código MSP)
		$codigoP = substr($linea, 1, 18);
		$codigoP = trim($codigoP);
		// Codigo de Producto1 del Proveedor (Código Material)
		$codigoP1 = substr($linea, 250, 18);
		$codigoP1 = trim($codigoP1);

		if ($codigoP != '000000000000000000'){ // si codigo del proveedor > 0 continua para insertar en producto_prov
			// Codigos de Productos - Sustituye los 0 por + adelante
			$codigoPMod = "+".ltrim($codigoP, 0);
			$codigoP1Mod = "+".ltrim($codigoP1, 0);
			// Tipo de producto (Sección) 1 Medicinal, 2 No Medicinal y " " Servicios
			$tipo = substr($linea, 60, 1); // Extraigo solo el último dígito.
			if ($tipo == 1) {
				$seccion = "<b><FONT COLOR='red'>ME</FONT></b>";
			} elseif ($tipo == 2) {
				$seccion = "NM";
			} else {
				$seccion = "SE";
			}
			// Nombre para guardar
			$nombre = substr($linea, 19, 40);
			$nombre = trim($nombre);
			$nombreUTF8 = utf8_decode($nombre);
			// DS tiene varios códigos de barras por producto
			$aCodigoB = array();
			// Primer código de barra
			$codigoB = 0;
			$codigoB = substr($linea, 85, 18);
			if (ctype_space($codigoB) == false){
				$aCodigoB[] = $codigoB;
			}
			// Segundo código de barra
			$codigoB = 0;
			$codigoB = substr($linea, 105, 18);
			if (ctype_space($codigoB) == false){
				$aCodigoB[] = $codigoB;
			}
			// Tercer código de barra
			$codigoB = 0;
			$codigoB = substr($linea, 125, 18);
			if (ctype_space($codigoB) == false){
				$aCodigoB[] = $codigoB;
			}
			// Cuarto código de barra
			$codigoB = 0;
			$codigoB = substr($linea, 145, 18);
			if (ctype_space($codigoB) == false){
				$aCodigoB[] = $codigoB;
			}

			// Precio de Venta al Cliente del producto
			$precioVC = substr($linea, 163, 13);
			$precioVC = trim($precioVC);
			$precioVC = substr_replace($precioVC,'.',-2,0);
			//number_format($precioVC, 2, ".", "");

			// Precio de Venta al Público del producto
			$precioVP = substr($linea, 176, 13);
			$precioVP = trim($precioVP);
			$precioVP = substr_replace($precioVP,'.',-2,0);
			//number_format($precioVP, 2, ".", "");

			// Código de IVA del producto
			$ivaCod = substr($linea, 268, 1);
			if ($ivaCod==1){ // Suponemos iva 21%
				$codigoIva = 5;
				$ivaAli = "Gr: 21%";
			} elseif ($ivaCod==2) { // Suponemos iva 21% para todos
				$codigoIva = 5;
				$ivaAli = "Gr: 21%";
			} else { // Exentos
				$codigoIva = 2;
				$ivaAli = "<b><FONT COLOR='red'>EXENTO</FONT></b>";
			}

			// Grupo del producto
			$grupo = substr($linea, 269, 9);

			// Estado del producto
			$estadoPro = substr($linea, 82, 1);
			// --------------------------------
			// Fin para Windows sin problemas
			// --------------------------------
			$estado = 1;
			if ($estadoPro == 'B'){
				$estado = 0;
			}
			if ($estadoPro == 'S'){
				$estado = 2;
			}
			// Muestra los datos de la lista que interesan para la prueba
			echo "-----------------------------------------------<br>";
			echo $i."-> ".$codigoPMod." (Cod.Mat:".$codigoP1Mod."): ".$nombreUTF8
				." [".$seccion." / ".$ivaAli." / ".$grupo." ]<br>";
			echo "Cód. barra: ";
			foreach ($aCodigoB as $codigoB) {
				echo $codigoB." / ";
			}
			echo " PRECIO FARMACIA $ ".$precioVC." - VENTA PUB $ ".$precioVP."<br>";
			// Busco si existen artículos
			reset($aCodigoB);
			foreach ($aCodigoB as $codigoB) {
				$oArticuloVO->setCodigoB($codigoB);
				$oArticuloModelo->findPorCodigoB($oArticuloVO);
				if ($oArticuloModelo->getCantidad()>0) {
					// Si es artículo propio muestro información
					if ($oArticuloVO->getCodigo() > 9999900000) {
						// Datos del rubro
						if ($oArticuloVO->getIdRubro()==1) {
							$rubroArt = "MED";
						} elseif ($oArticuloVO->getIdRubro()==2) {
							$rubroArt = "ACC";
						} elseif ($oArticuloVO->getIdRubro()==3) {
							$rubroArt = "PERF";
						} elseif ($oArticuloVO->getIdRubro()==4) {
							$rubroArt = "VAR";
						} else {
							$rubroArt = "ERROR";
						}
						// Datos del iva
						if ($oArticuloVO->getCodigoIva()==3) { // OJO exento es 2 y 3 es 0% iva
							$ivaArt = "<FONT COLOR='blue'>EXENTO</FONT>";
						} elseif ($oArticuloVO->getCodigoIva()==5) {
							$ivaArt = "<FONT COLOR='blue'>21%</FONT>";
						} else {
							$ivaArt = "<FONT COLOR='red'><b>VER IVA</b></FONT>";
						}
						// Datos del Margen
						if ($oArticuloVO->getMargen()==37) {
							$margenArt = "<FONT COLOR='blue'>".$oArticuloVO->getMargen()."</FONT>";
						} else {
							$margenArt = "<FONT COLOR='red'><b>".$oArticuloVO->getMargen()."</b></FONT>";
						}
						// MUestra los datos del artículo
						echo "<FONT COLOR='blue'>## PROPIO - ".$oArticuloVO->getCodigo()." [".$rubroArt
							." / Margen: </FONT>".$margenArt." <FONT COLOR='blue'>/</FONT> ".$ivaArt."] "
							."<FONT COLOR='blue'> Costo $ ".$oArticuloVO->getCosto()." Precio $ ".$oArticuloVO->getPrecio()."</FONT>";
					} else {
						echo "++ Artículo PLEX ++";
					}
				} else {
					if ((count($aCodigoB))>1) {
						echo "** Cod.Barra ".$codigoB." SIN Articulo **";
					} else {
						echo "<b>** Producto SIN Artículo PLEX **</b>";
					}
				}
				echo "<br>";
			}
		} // Fin código proveedor del producto
	} // Fin de tipo de registro que utilizamos "D"
} // Fin foreach que lee la lista del proveedor
echo "-----------------------------------------------<br>";
echo "Cantidad de artículos leidos: ".$registroD."<br>";

?>
