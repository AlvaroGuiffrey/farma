<?php
/**
 * Archivo del includes.
 *
 * Archivo del includes que nos permite definir clases.
 *
 * LICENSE:  This file is part of Sistema de Gestión (SG).
 * SG is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * SG is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with SG.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

/**
 * Clase que nos permite definir clases.
 *
 * Clase que nos permite definir clases de acuerdo a las rutas
 * declaradas en el array
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    2.0
 * @link       http:
 * @since      Class available since Release 1.0
 */

/**
 * Sintesis de modificaciones.
 * Versión 2.0
 * @author Alvaro Guiffrey
 * @fecha 22/04/2021.
 * Se cambia la forma de obtener el directorio principal del proyecto para
 * utilizar esta clase en solicitudes ajax.
 */

class Clase
{
	/**
	 * Nos permite definir clases de acuerdo a las rutas
	 * declaradas en el array
	 */

	# Propiedades

	# Metodos

	static function define($clase)
	{
		// Ruta del directorio principal del sistema
		//$ruta = $_SERVER['DOCUMENT_ROOT'].$_SESSION['dir']; // Versión 1.0
		// Modificación en Versión 2.0 para utilizar ajax
	    $dir = substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT']));
	    $aDir = explode("\\", $dir);
	    $ruta = $_SERVER['DOCUMENT_ROOT']."/".$aDir[1];

		// Se declaran las clases en el array
		$aClases = array(
				// aplicaciones
				"MenuHref"=>$ruta."/aplicaciones/vista/MenuHref.php",
				"AppControl"=>$ruta."/aplicaciones/control/AppControl.php",
				// includes
        "ArrayOrdenadoPor"=>$ruta."/includes/control/ArrayOrdenadoPor.php",
        "CalcularPrecioProv"=>$ruta."/includes/control/CalcularPrecioProv.php",
        "CalcularProductosVendidos"=>$ruta."/includes/control/CalcularProductosVendidos.php",
				"CargarMenu"=>$ruta."/includes/control/CargarMenu.php",
				"CargarVista"=>$ruta."/includes/control/CargarVista.php",
				"DatoVista"=>$ruta."/includes/control/DatoVista.php",
				"MotorVista"=>$ruta."/includes/control/MotorVista.php",
				"Paginador"=>$ruta."/includes/control/Paginador.php",
				"ActiveRecordInterface"=>$ruta."/includes/persistencia/interface/ActiveRecordInterface.php",
				"DataBase"=>$ruta."/includes/persistencia/singleton/DataBase.php",
				"DataBasePlex"=>$ruta."/includes/persistencia/singleton/DataBasePlex.php",
				"DataBaseBackup"=>$ruta."/includes/persistencia/singleton/DataBaseBackup.php",
				"DataBaseBackupVO"=>$ruta."/includes/persistencia/valueObject/DataBaseBackupVO.php",
				// libs
				"FPDF"=>$ruta."/libs/php/FPDF_v1.7/fpdf.php",
		    "Barcode_php"=>$ruta."/libs/php/Barcode_php/barcode.php",
				// modulos/afip
				"AfipCondicionIvaActiveRecord"=>$ruta."/modulos/afip/modelo/AfipCondicionIvaActiveRecord.php",
				"AfipCondicionIvaModelo"=>$ruta."/modulos/afip/modelo/AfipCondicionIvaModelo.php",
				"AfipCondicionIvaVO"=>$ruta."/modulos/afip/modelo/AfipCondicionIvaVO.php",
				"AfipResponsablesSelect"=>$ruta."/modulos/afip/includes/AfipResponsablesSelect.php",
				"AfipResponsablesActiveRecord"=>$ruta."/modulos/afip/modelo/AfipResponsablesActiveRecord.php",
				"AfipResponsablesModelo"=>$ruta."/modulos/afip/modelo/AfipResponsablesModelo.php",
				"AfipResponsablesVO"=>$ruta."/modulos/afip/modelo/AfipResponsablesVO.php",
				// modulos/articulo
		    "ArticuloAjax"=>$ruta."/modulos/articulo/includes/ArticuloAjax.php",
				"ArticuloControl"=>$ruta."/modulos/articulo/control/ArticuloControl.php",
		    "ArticuloCondiControl"=>$ruta."/modulos/articulo/control/ArticuloCondiControl.php",
		    "ArticuloCondiControlBuscar"=>$ruta."/modulos/articulo/control/ArticuloCondiControlBuscar.php",
				"ArticuloControlVentana"=>$ruta."/modulos/articulo/control/ArticuloControlVentana.php",
				"ArticuloControlVentanaPrecio"=>$ruta."/modulos/articulo/control/ArticuloControlVentanaPrecio.php",
				"ArticuloControlActualiza"=>$ruta."/modulos/articulo/control/ArticuloControlActualiza.php",
				"ArticuloControlActPrueba"=>$ruta."/modulos/articulo/control/ArticuloControlActPrueba.php",
		    "ArticuloControlActPU"=>$ruta."/modulos/articulo/control/ArticuloControlActPU.php",
				"ArticuloControlDescarta"=>$ruta."/modulos/articulo/control/ArticuloControlDescarta.php",
				"ArticuloControlPM"=>$ruta."/modulos/articulo/control/ArticuloControlPM.php",
				"ArticuloDatos"=>$ruta."/modulos/articulo/includes/ArticuloDatos.php",
		    "ArticuloCondiDatos"=>$ruta."/modulos/articulo/includes/ArticuloCondiDatos.php",
				"ArticuloOpcionProv"=>$ruta."/modulos/articulo/includes/ArticuloOpcionProv.php",
				"ArticuloTabla"=>$ruta."/modulos/articulo/includes/ArticuloTabla.php",
        "ArticuloActTabla"=>$ruta."/modulos/articulo/includes/ArticuloActTabla.php",
        "ArticuloCondiTabla"=>$ruta."/modulos/articulo/includes/ArticuloCondiTabla.php",
        "ArticuloCondiExisTabla"=>$ruta."/modulos/articulo/includes/ArticuloCondiExisTabla.php",
				"ArticuloCostosTabla"=>$ruta."/modulos/articulo/includes/ArticuloCostosTabla.php",
        "ArticuloCondiActiveRecord"=>$ruta."/modulos/articulo/modelo/ArticuloCondiActiveRecord.php",
        "ArticuloCondiModelo"=>$ruta."/modulos/articulo/modelo/ArticuloCondiModelo.php",
        "ArticuloCondiVO"=>$ruta."/modulos/articulo/modelo/ArticuloCondiVO.php",
				"ArticuloActiveRecord"=>$ruta."/modulos/articulo/modelo/ArticuloActiveRecord.php",
				"ArticuloModelo"=>$ruta."/modulos/articulo/modelo/ArticuloModelo.php",
				"ArticuloVO"=>$ruta."/modulos/articulo/modelo/ArticuloVO.php",
				"ArticuloDActiveRecord"=>$ruta."/modulos/articulo/modelo/ArticuloDActiveRecord.php",
				"ArticuloDModelo"=>$ruta."/modulos/articulo/modelo/ArticuloDModelo.php",
				"ArticuloDVO"=>$ruta."/modulos/articulo/modelo/ArticuloDVO.php",
				"ArticuloPMActiveRecord"=>$ruta."/modulos/articulo/modelo/ArticuloPMActiveRecord.php",
				"ArticuloPMModelo"=>$ruta."/modulos/articulo/modelo/ArticuloPMModelo.php",
				"ArticuloPMVO"=>$ruta."/modulos/articulo/modelo/ArticuloPMVO.php",
				// modulos/backup
				"BackupControlI"=>$ruta."/modulos/backup/control/BackupControlI.php",
				// modulos/condicion
        "CondicionActiveRecord"=>$ruta."/modulos/condicion/modelo/CondicionActiveRecord.php",
        "CondicionModelo"=>$ruta."/modulos/condicion/modelo/CondicionModelo.php",
        "CondicionVO"=>$ruta."/modulos/condicion/modelo/CondicionVO.php",
        "CondicionTipoActiveRecord"=>$ruta."/modulos/condicion/modelo/CondicionTipoActiveRecord.php",
        "CondicionTipoModelo"=>$ruta."/modulos/condicion/modelo/CondicionTipoModelo.php",
        "CondicionTipoVO"=>$ruta."/modulos/condicion/modelo/CondicionTipoVO.php",
        "CondicionSelect"=>$ruta."/modulos/condicion/includes/CondicionSelect.php",
        "CondicionCalculo"=>$ruta."/modulos/condicion/includes/CondicionCalculo.php",
				// modulos/listaOrden
				"ListaOrdenActiveRecord"=>$ruta."/modulos/listaOrden/modelo/ListaOrdenActiveRecord.php",
				"ListaOrdenModelo"=>$ruta."/modulos/listaOrden/modelo/ListaOrdenModelo.php",
				"ListaOrdenVO"=>$ruta."/modulos/listaOrden/modelo/ListaOrdenVO.php",
				// modulos/localidad
				"LocalidadControl"=>$ruta."/modulos/localidad/control/LocalidadControl.php",
				"LocalidadDatos"=>$ruta."/modulos/localidad/includes/LocalidadDatos.php",
				"LocalidadTabla"=>$ruta."/modulos/localidad/includes/LocalidadTabla.php",
				"LocalidadSelect"=>$ruta."/modulos/localidad/includes/LocalidadSelect.php",
				"LocalidadActiveRecord"=>$ruta."/modulos/localidad/modelo/LocalidadActiveRecord.php",
				"LocalidadModelo"=>$ruta."/modulos/localidad/modelo/LocalidadModelo.php",
				"LocalidadVO"=>$ruta."/modulos/localidad/modelo/LocalidadVO.php",
				// modulos/login
				"LoginControl"=>$ruta."/modulos/login/control/LoginControl.php",
				"LoginActiveRecord"=>$ruta."/modulos/login/modelo/LoginActiveRecord.php",
				"LoginModelo"=>$ruta."/modulos/login/modelo/LoginModelo.php",
				"LoginVO"=>$ruta."/modulos/login/modelo/LoginVO.php",
				// modulos/marca
				"MarcaControl"=>$ruta."/modulos/marca/control/MarcaControl.php",
				"MarcaSelect"=>$ruta."/modulos/marca/includes/MarcaSelect.php",
				"MarcaDatos"=>$ruta."/modulos/marca/includes/MarcaDatos.php",
				"MarcaTabla"=>$ruta."/modulos/marca/includes/MarcaTabla.php",
				"MarcaActiveRecord"=>$ruta."/modulos/marca/modelo/MarcaActiveRecord.php",
				"MarcaModelo"=>$ruta."/modulos/marca/modelo/MarcaModelo.php",
				"MarcaVO"=>$ruta."/modulos/marca/modelo/MarcaVO.php",
				// modulos/partida
				"PartidaControlR"=>$ruta."/modulos/partida/control/PartidaControlR.php",
				"PartidaActiveRecord"=>$ruta."/modulos/partida/modelo/PartidaActiveRecord.php",
				"PartidaModelo"=>$ruta."/modulos/partida/modelo/PartidaModelo.php",
				"PartidaVO"=>$ruta."/modulos/partida/modelo/PartidaVO.php",
				"PartidaRecibidoDatos"=>$ruta."/modulos/partida/includes/RecibidoDatos.php",
				"PartidaRenglonTabla"=>$ruta."/modulos/partida/includes/RenglonTabla.php",
        // modulos/pedido
        "PedidoControl"=>$ruta."/modulos/pedido/control/PedidoControl.php",
        "PedidoControlVentana"=>$ruta."/modulos/pedido/control/PedidoControlVentana.php",
        "PedidoDatos"=>$ruta."/modulos/pedido/includes/PedidoDatos.php",
        "PedidoTabla"=>$ruta."/modulos/pedido/includes/PedidoTabla.php",
        "PedidoTablaV"=>$ruta."/modulos/pedido/includes/PedidoTablaV.php",
        "PedidoRegTabla"=>$ruta."/modulos/pedido/includes/PedidoRegTabla.php",
        "PedidoActiveRecord"=>$ruta."/modulos/pedido/modelo/PedidoActiveRecord.php",
        "PedidoModelo"=>$ruta."/modulos/pedido/modelo/PedidoModelo.php",
        "PedidoVO"=>$ruta."/modulos/pedido/modelo/PedidoVO.php",
        // modulos/pendiente
        "PendienteControl"=>$ruta."/modulos/pendiente/control/PendienteControl.php",
        "PendienteTabla"=>$ruta."/modulos/pendiente/includes/PendienteTabla.php",
        "PendienteDatos"=>$ruta."/modulos/pendiente/includes/PendienteDatos.php",
        "PendienteActiveRecord"=>$ruta."/modulos/pendiente/modelo/PendienteActiveRecord.php",
        "PendienteModelo"=>$ruta."/modulos/pendiente/modelo/PendienteModelo.php",
        "PendienteVO"=>$ruta."/modulos/pendiente/modelo/PendienteVO.php",
        // modulos/pendienteAct
        "PendienteActActiveRecord"=>$ruta."/modulos/pendienteAct/modelo/PendienteActActiveRecord.php",
        "PendienteActModelo"=>$ruta."/modulos/pendienteAct/modelo/PendienteActModelo.php",
        "PendienteActVO"=>$ruta."/modulos/pendienteAct/modelo/PendienteActVO.php",
				// modulos/producto
				"ProductoControl"=>$ruta."/modulos/producto/control/ProductoControl.php",
		    "ProductoControlPrecioMenor"=>$ruta."/modulos/producto/control/ProductoControlPrecioMenor.php",
				"ProductoNIControl"=>$ruta."/modulos/producto/control/ProductoNIControl.php",
				"ProductoDSControl"=>$ruta."/modulos/producto/control/ProductoDSControl.php",
				"ProductoLIControl"=>$ruta."/modulos/producto/control/ProductoLIControl.php",
				"ProductoKEControl"=>$ruta."/modulos/producto/control/ProductoKEControl.php",
				"ProductoDIControl"=>$ruta."/modulos/producto/control/ProductoDIControl.php",
        "ProductoBAControl"=>$ruta."/modulos/producto/control/ProductoBAControl.php",
		    "ProductoCFControl"=>$ruta."/modulos/producto/control/ProductoCFControl.php",
				"ProductoDRControl"=>$ruta."/modulos/producto/control/ProductoDRControl.php",
				"ProductoActiveRecord"=>$ruta."/modulos/producto/modelo/ProductoActiveRecord.php",
				"ProductoModelo"=>$ruta."/modulos/producto/modelo/ProductoModelo.php",
		    "ProductoPrecioMenorTabla"=>$ruta."/modulos/producto/includes/ProductoPrecioMenorTabla.php",
				"ProductoVO"=>$ruta."/modulos/producto/modelo/ProductoVO.php",
				// modulos/productoProv
				"ProductoProvActiveRecord"=>$ruta."/modulos/productoProv/modelo/ProductoProvActiveRecord.php",
				"ProductoProvModelo"=>$ruta."/modulos/productoProv/modelo/ProductoProvModelo.php",
				"ProductoProvVO"=>$ruta."/modulos/productoProv/modelo/ProductoProvVO.php",
				// modulos/proveedor
				"ProveedorControl"=>$ruta."/modulos/proveedor/control/ProveedorControl.php",
				"ProveedorDatos"=>$ruta."/modulos/proveedor/includes/ProveedorDatos.php",
				"ProveedorTabla"=>$ruta."/modulos/proveedor/includes/ProveedorTabla.php",
				"ProveedorSelect"=>$ruta."/modulos/proveedor/includes/ProveedorSelect.php",
				"ProveedorActiveRecord"=>$ruta."/modulos/proveedor/modelo/ProveedorActiveRecord.php",
				"ProveedorModelo"=>$ruta."/modulos/proveedor/modelo/ProveedorModelo.php",
				"ProveedorVO"=>$ruta."/modulos/proveedor/modelo/ProveedorVO.php",
				// modulos/provincia
				"ProvinciaSelect"=>$ruta."/modulos/provincia/includes/ProvinciaSelect.php",
				"ProvinciaActiveRecord"=>$ruta."/modulos/provincia/modelo/ProvinciaActiveRecord.php",
				"ProvinciaModelo"=>$ruta."/modulos/provincia/modelo/ProvinciaModelo.php",
				"ProvinciaVO"=>$ruta."/modulos/provincia/modelo/ProvinciaVO.php",
				// modulos/recibido
				"RecibidoControl"=>$ruta."/modulos/recibido/control/RecibidoControl.php",
				"RecibidoControlVentana"=>$ruta."/modulos/recibido/control/RecibidoControlVentana.php",
				"RecibidoDatos"=>$ruta."/modulos/recibido/includes/RecibidoDatos.php",
				"RecibidoTabla"=>$ruta."/modulos/recibido/includes/RecibidoTabla.php",
				"RecibidoActiveRecord"=>$ruta."/modulos/recibido/modelo/RecibidoActiveRecord.php",
				"RecibidoModelo"=>$ruta."/modulos/recibido/modelo/RecibidoModelo.php",
				"RecibidoVO"=>$ruta."/modulos/recibido/modelo/RecibidoVO.php",
				// modulos/reponer
        "ReponerControl"=>$ruta."/modulos/reponer/control/ReponerControl.php",
        "ReponerTabla"=>$ruta."/modulos/reponer/includes/ReponerTabla.php",
        "ReponerNumeroTabla"=>$ruta."/modulos/reponer/includes/ReponerNumeroTabla.php",
        "ReponerActiveRecord"=>$ruta."/modulos/reponer/modelo/ReponerActiveRecord.php",
        "ReponerModelo"=>$ruta."/modulos/reponer/modelo/ReponerModelo.php",
        "ReponerVO"=>$ruta."/modulos/reponer/modelo/ReponerVO.php",
				// modulos/rotulo
				"RotuloControl"=>$ruta."/modulos/rotulo/control/RotuloControl.php",
				"RotuloDatos"=>$ruta."/modulos/rotulo/includes/RotuloDatos.php",
				"RotuloTabla"=>$ruta."/modulos/rotulo/includes/RotuloTabla.php",
        "RotuloCondiTabla"=>$ruta."/modulos/rotulo/includes/RotuloCondiTabla.php",
        "OfertaRotulos"=>$ruta."/modulos/rotulo/includes/OfertaRotulos.php",
				// modulos/rubro
				"RubroControl"=>$ruta."/modulos/rubro/control/RubroControl.php",
				"RubroDatos"=>$ruta."/modulos/rubro/includes/RubroDatos.php",
				"RubroSelect"=>$ruta."/modulos/rubro/includes/RubroSelect.php",
				"RubroTabla"=>$ruta."/modulos/rubro/includes/RubroTabla.php",
				"RubroActiveRecord"=>$ruta."/modulos/rubro/modelo/RubroActiveRecord.php",
				"RubroModelo"=>$ruta."/modulos/rubro/modelo/RubroModelo.php",
				"RubroVO"=>$ruta."/modulos/rubro/modelo/RubroVO.php",
				// plex/laboratorio
				"LaboratorioPlexControl"=>$ruta."/plex/laboratorio/control/LaboratorioPlexControl.php",
				"LaboratorioPlexTabla"=>$ruta."/plex/laboratorio/includes/LaboratorioPlexTabla.php",
				// plex/producto
				"ProductoPlexControl"=>$ruta."/plex/producto/control/ProductoPlexControl.php",
				"ProductoPlexTabla" => $ruta."/plex/producto/includes/ProductoPlexTabla.php",
				// plex/factlineas
		    "FacturaPlexControl"=>$ruta."/plex/factura/control/FacturaPlexControl.php"

		);

		// Se define la clase si ya no fue definida con anterioridad
		if (isset($aClases[$clase])){
			if (!class_exists($clase)){
				require_once $aClases[$clase];
			}else{
				return true;
			}
		}else{
			echo "Clase no declarada, consulte con su administrador.<br>";
			echo $clase."<br>";
			die;
		}

	}

}
?>
