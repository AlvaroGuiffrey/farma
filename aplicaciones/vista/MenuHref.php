<?php
class MenuHref
{
	# Propiedades
	static  $dir;

	# Métodos
	public static function getHref()
	{
		// Se carga el directorio principal de la aplicación
		self::$dir = $_SESSION['dir'];
		// Se carga el array
		$aMenuHref = array(
			"{hrefInicio}" => self::$dir."/index.php",
			// inventario
			"{hrefRubros}" => self::$dir."/modulos/rubro/index.php",
			"{hrefMarcas}" => self::$dir."/modulos/marca/index.php",
			"{hrefArticulos}" => self::$dir."/modulos/articulo/index.php",
			"{hrefPartidas}" => self::$dir."/modulos/partida/index.php",
			"{hrefActualizaArticulos}" => self::$dir."/modulos/articulo/indexA.php",
			"{hrefActualizaArtPrueba}" => self::$dir."/modulos/articulo/indexAP.php",
		    "{hrefActualizaArticulosPU}" => self::$dir."/modulos/articulo/indexAPU.php",
			"{hrefRotulos}" => self::$dir."/modulos/rotulo/index.php",
			"{hrefDescartaArticulos}" => self::$dir."/modulos/articulo/indexD.php",
		    "{hrefArticulosCondi}" => self::$dir."/modulos/articulo/indexAC.php",
			"{hrefBackupTablasI}" => self::$dir."/modulos/backup/indexI.php",
			// compras
			"{hrefProveedores}" => self::$dir."/modulos/proveedor/index.php",
			"{hrefProductos}" => self::$dir."/modulos/producto/index.php",
		    "{hrefAsignaPendientes}" => self::$dir."/modulos/pendiente/index.php",
		    "{hrefPedidos}" => self::$dir."/modulos/pedido/index.php",
		    "{hrefReposiciones}" => self::$dir."/modulos/reponer/index.php",
		   	"{hrefIngresoCompras}" => self::$dir."/modulos/recibido/index.php",
			"{hrefActualizaDS}" => self::$dir."/modulos/producto/indexDS.php",
			"{hrefActualizaLI}" => self::$dir."/modulos/producto/indexLI.php",
			"{hrefActualizaKE}" => self::$dir."/modulos/producto/indexKE.php",
			"{hrefActualizaNI}" => self::$dir."/modulos/producto/indexNI.php",
			"{hrefActualizaDI}" => self::$dir."/modulos/producto/indexDI.php",
			"{hrefActualizaBA}" => self::$dir."/modulos/producto/indexBA.php",
		    "{hrefActualizaCF}" => self::$dir."/modulos/producto/indexCF.php",
		    "{hrefPrecioMenorProv}" => self::$dir."/modulos/producto/indexPMP.php",
			// ventas
		    "{hrefArticulosCondiBuscar}" => self::$dir."/modulos/articulo/indexAB.php",
			// otras
			"{hrefLocalidades}" => self::$dir."/modulos/localidad/index.php",
			// plex
			"{hrefPlexLaboratorios}" => self::$dir."/plex/laboratorio/index.php",
			"{hrefPlexProductos}" => self::$dir."/plex/producto/index.php",
		    "{hrefPlexFacturas}" => self::$dir."/plex/factura/index.php"
		);
		return $aMenuHref;
	}
}
