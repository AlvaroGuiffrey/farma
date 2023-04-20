<?php
/* -----------------------------------
	LIMPIAMOS LA TABLA ARTICULOS DE
	CODIGOS CARGADOS MAL POR PLEX
	DE MANERA AUTOMATICA.
*/

// Inicia o reanuda la sesión
session_start();

// Carga las clases necesarias
//require_once $_SERVER['DOCUMENT_ROOT'].$_SESSION['dir'].'/includes/control/Clase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/farma/includes/control/Clase.php';
// Define las clases
Clase::define('ArticuloDModelo');
Clase::define('ArticuloModelo');

$oArticuloVO = new ArticuloVO();
$oArticuloModelo = new ArticuloModelo();
$tiempoInicio = time();
$oArticuloModelo->count();
$registrosArticulo = $oArticuloModelo->getCantidad();

$oArticuloDVO = new ArticuloDVO();
$oArticuloDModelo = new ArticuloDModelo();
$oArticuloDModelo->count();
$registrosArticuloD = $oArticuloDModelo->getCantidad();

$dbh = DataBase::getInstance();

$renglonDesde=0;
$limiteRenglones=10000;
$cont=0;
$i=0;
$cont_agrega = 0;
$cont_no = 0;
echo "--------- TABLA ARTICULOS ---------------<br>";
echo " Toma los datos de tabla articulos_d y los agrega en <br>";
echo "tabla articulos descartando los códigos mal agregado por PLEX<br>";
echo " Cantidad Registros articulos_d: ".$registrosArticuloD."<br>";
echo " Cantidad Registros articulos: ".$registrosArticulo."<br>";
//echo " ID ultimo :".$fila->id."<br>";
//echo " cantidad leidos:".$cantidad."<br>";
echo "------------------------------------------------<br>";

for ($i=0; $i < $registrosArticuloD; ){
//for ($i=0; $i < 1000; ){	
	$aArticulos = $oArticuloDModelo->findAllLimite($renglonDesde, $limiteRenglones);
	echo " Cantidad consulta con limite: ".$oArticuloModelo->getCantidad()."<br>";
	
	foreach ($aArticulos as $articulo){
		$i++;
		// Verifico los que voy a agregar 
		if ($articulo['codigo'] < 3000000000 OR $articulo['codigo'] > 3999999999) {
			echo "#".$i." ->".$articulo['id']." - (".$articulo['codigo'].") - ".$articulo['nombre']."<br>";
			$oArticuloVO->setCodigo($articulo['codigo']);
			$oArticuloVO->setCodigoM($articulo['codigo_m']);
			$oArticuloVO->setCodigoB($articulo['codigo_b']);
			$oArticuloVO->setIdMarca($articulo['id_marca']);
			$oArticuloVO->setIdRubro($articulo['id_rubro']);
			$oArticuloVO->setNombre($articulo['nombre']);
			$oArticuloVO->setPresentacion($articulo['presentacion']);
			$oArticuloVO->setComentario($articulo['comentario']);
			$oArticuloVO->setMargen($articulo['margen']);
			$oArticuloVO->setCosto($articulo['costo']);
			$oArticuloVO->setPrecio($articulo['precio']);
			$oArticuloVO->setFechaPrecio($articulo['fecha_precio']);
			$oArticuloVO->setStock($articulo['stock']);
			$oArticuloVO->setRotulo($articulo['rotulo']);
			$oArticuloVO->setIdProveedor($articulo['id_proveedor']);
			$oArticuloVO->setOpcionProv($articulo['opcion_prov']);
			$oArticuloVO->setEquivalencia($articulo['equivalencia']);
			$oArticuloVO->setCodigoIva($articulo['codigo_iva']);
			$oArticuloVO->setFoto($articulo['foto']);
			$oArticuloVO->setEstado($articulo['estado']);
			$oArticuloVO->setIdUsuarioAct($articulo['id_usuario_act']);
			$oArticuloVO->setFechaAct($articulo['fecha_act']);
			$oArticuloModelo->insert($oArticuloVO);
			$cont_agrega++;
		} else {
			echo "** NO #".$i." ->".$articulo['id']." - (".$articulo['codigo'].") - ".$articulo['nombre']." --> NO AGREGA<br>";
			$cont_no++;
		}
		
	}
	$renglonDesde = $renglonDesde + $limiteRenglones;
}

echo "Total Leidos ($i): ".$i."<br>";
echo "Total Agregados ($cont_agrega): ".$cont_agrega."<br>";
echo "Total Leidos ($cont_no): ".$cont_no."<br>";
?>
