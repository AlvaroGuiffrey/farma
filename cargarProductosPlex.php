<?php
/*
 * Carga por primera vez los productos de PLEX en la DB
 * 
 *  Lee productos.csv y los carga en la DB farma en la tabla plex_productos
 */

// Inicia o reanuda la sesión
session_start();
$_SESSION['dir'] = substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT']));
echo "ruta: ".$_SERVER['DOCUMENT_ROOT']."<br>";
echo "dir: ".$_SESSION['dir']."<br>";
// Carga las clases necesarias
require_once $_SERVER['DOCUMENT_ROOT'].$_SESSION['dir'].'/includes/control/Clase.php';


Clase::define('DataBasePlex');
echo "Cargue clases<br>";
/*
// Realiza la conexión a la DB
define("DB_SERVER", "localhost");	//tu servidor
define("DB_USER", "root");			//tu usuario
define("DB_PW", "berlingo");		//contraseña
define("DB_NAME", "farma");			//base de datos
$conx = mysql_connect(DB_SERVER, DB_USER, DB_PW);
mysql_select_db(DB_NAME);
*/

$conx = DataBasePlex::getInstance();

// abre archivo CSV descargado de DB PLEX
$archivo = fopen ("/var/www/html/farma/plex/FarmaciaMySql/productos.csv","r");
echo "CARGA BASE DE DATOS PRODUCTOS DE PLEX</br>";
echo "- PRIMERA CARGA -<br>";
echo "(Agrega todos los artículos sin verificar si ya estan cargados)<br>";

$cont = 0;
while ($data = fgetcsv ($archivo, 1000, ",")) {
	$cont++;
	echo $cont." - ".$data[0]." - ".$data[10]."<br>";
	// agrega cuando $cont > 1 para evitar los títulos del CSV
	if ($cont>1){
		$IDProducto = $data[0];
		$IDLaboratorio = $data[1];
		$IDTamano = $data[2];
		$IDRubro = $data[3];
		$IDTipoUnidad = $data[4];
		$IDTipoConc = $data[5];
		$Concentracion = $data[6];
		$IDForma = $data[7];
		$Troquel = $data[8];
		//$Codebar = $data[9];
		if ($data[9]==''){
			$Codebar = 0;
		}else{
			$Codebar = $data[9];
		}
		$Producto = $data[10];
		$Presentacion = $data[11];
		$Unidades = $data[12];
		$Importado = $data[13];
		$Activo = $data[14];
		$Refrigeracion = $data[15];
		$Costo = $data[16];
		$Margen = $data[17];
		$CodAlfabeta = $data[18];
		$IDSubRubro = $data[19];
		$IDPsicofarmaco = $data[20];
		$UltimoCosto = $data[21];
		$costoPPP = $data[22];
		$idProveedor = $data[23];
		$idTipoVenta = $data[24]; 
		$idTipoIVA = $data[25];
		$gtin = $data[26];
		$trazable = $data[27];
		$IDPerfumeria = $data[28];
		$vencimiento = $data[29];
		$FecSyncFidely = $data[30];
		$IDActividad = $data[31];
        $visible = $data[32];
        $CodProductoProveedor = $data[33];
        
		$query = "INSERT INTO plex_productos 
				(IDProducto, IDLaboratorio, IDTamano, IDRubro, IDTipoUnidad, IDTipoConc, Concentracion, IDForma, Troquel, Codebar, Producto,
				Presentacion, Unidades, Importado, Activo, Refrigeracion, Costo, Margen, CodAlfabeta, IDSubRubro, IDPsicofarmaco, UltimoCosto,
				costoPPP, idProveedor, idTipoVenta, idTipoIVA, gtin, trazable, IDPerfumeria, vencimiento, FecSyncFidely, IDActividad, visible, 
				CodProductoProveedor) 
				VALUES
				('$IDProducto', '$IDLaboratorio', '$IDTamano', '$IDRubro', '$IDTipoUnidad', '$IDTipoConc', '$Concentracion', '$IDForma', '$Troquel',
					'$Codebar', '$Producto', '$Presentacion', '$Unidades', '$Importado', '$Activo', '$Refrigeracion', '$Costo', '$Margen', '$CodAlfabeta',
					'$IDSubRubro', '$IDPsicofarmaco', '$UltimoCosto', '$costoPPP', '$idProveedor', '$idTipoVenta', '$idTipoIVA', '$gtin', '$trazable',
					'$IDPerfumeria', '$vencimiento', '$FecSyncFidely', '$IDActividad', '$visible', '$CodProductoProveedor')";
		$res = mysql_query($query) or die(mysql_error());
		 
	}

}
$cant=$cont - 1;
echo "Cantidad de productos cargados: ".$cant."<br>";
// cierra la conexión a la DB
//mysql_close($conx);
?>