<?php
/**
 * Archivo del includes del módulo articulo.
 *
 * Archivo del includes del módulo articulo que permite cargar
 * datos de un registro de la tabla articulos para representar
 * en una vista.
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
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://
 * @since      File available since Release 1.0
 */

/**
 * Clase del includes del módulo articulo.
 *
 * Clase del includes del módulo articulo que permite cargar
 * datos de un registro de la tabla articulos para representar
 * en una vista.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://
 * @since      Class available since Release 1.0
 */

class ArticuloDatos
{
	# Propiedades

	# Métodos
	/**
	* Nos permite cargar datos de un registro de la tabla
	* articulos para representar en una vista.
	*
	* @param $oArticuloVO
	* @param $oDatoVista
	* @param $accion
	*
	* @return $oDatoVista
	*/
	static function cargaDatos($oArticuloVO, $oDatoVista, $accion)
	{
		// Establece el localismo por moneda
		//setlocale(LC_MONETARY, 'es_AR.UTF-8');
		
		$oDatoVista->setDato('{id}', $oArticuloVO->getId());
		$oDatoVista->setDato('{codigo}', $oArticuloVO->getCodigo());
		$oDatoVista->setDato('{codigoBarra}', $oArticuloVO->getCodigoB());
		$oMarcaVO = new MarcaVO();
		$oMarcaVO->setId($oArticuloVO->getIdMarca());
		$oMarcaModelo = new MarcaModelo();
		$oMarcaModelo->find($oMarcaVO);
		$oDatoVista->setDato('{marca}', $oMarcaVO->getNombre());
		$oRubroVO = new RubroVO();
		$oRubroVO->setId($oArticuloVO->getIdRubro());
		$oRubroModelo = new RubroModelo();
		$oRubroModelo->find($oRubroVO);
		$oDatoVista->setDato('{rubro}', $oRubroVO->getNombre());
		$oDatoVista->setDato('{nombre}', $oArticuloVO->getNombre());
		$oDatoVista->setDato('{presentacion}', $oArticuloVO->getPresentacion());
		$oDatoVista->setDato('{comentario}', $oArticuloVO->getComentario());
		$oDatoVista->setDato('{margen}', $oArticuloVO->getMargen());
		if ($accion == "Editar"){
			$oDatoVista->setDato('{costo}', $oArticuloVO->getCosto());
			$oDatoVista->setDato('{precio}', $oArticuloVO->getPrecio());
		}else{
			//$oDatoVista->setDato('{costo}', money_format("%.2n", $oArticuloVO->getCosto()));
			//$oDatoVista->setDato('{precio}', money_format("%.2n", $oArticuloVO->getPrecio()));
			$oDatoVista->setDato('{costo}', number_format($oArticuloVO->getCosto(), 2, ",", "."));
			$oDatoVista->setDato('{precio}', number_format($oArticuloVO->getPrecio(), 2, ",", "."));
		}
		$oDatoVista->setDato('{fechaPrecio}', $oArticuloVO->getFechaPrecio());
		$oDatoVista->setDato('{stock}', $oArticuloVO->getStock());
		if ($oArticuloVO->getEstado() == 1){
			$estado = "Activo";
		}else{
			$estado = "Pasivo";
		}
		if ($oArticuloVO->getEstado() == 1){
			$oDatoVista->setDato('{checkedA}', "checked");
			$oDatoVista->setDato('{checkedP}', "");
		}else{
			$oDatoVista->setDato('{checkedP}', "checked");
			$oDatoVista->setDato('{checkedA}', "");
		}
		$oDatoVista->setDato('{estado}', $estado);
		
		if ($oArticuloVO->getRotulo() == 1){
			$rotulo = "SI";
		}else{
			$rotulo = "NO";
		}
		if ($oArticuloVO->getRotulo() >= 1){
			$oDatoVista->setDato('{checkedS}', "checked");
			$oDatoVista->setDato('{checkedN}', "");
		}else{
			$oDatoVista->setDato('{checkedN}', "checked");
			$oDatoVista->setDato('{checkedS}', "");
		}
		$oDatoVista->setDato('{rotulo}', $rotulo);

		$oProveedorVO = new ProveedorVO();
		$oProveedorVO->setId($oArticuloVO->getIdProveedor());
		$oProveedorModelo = new ProveedorModelo();
		$oProveedorModelo->find($oProveedorVO);
		$oDatoVista->setDato('{proveedor}', $oProveedorVO->getRazonSocial());

		if ($oArticuloVO->getOpcionProv()==1){
			$oDatoVista->setDato('{opcionProv}', 'por Artículo');
			$oDatoVista->setDato('{checkedPAr}', "checked");
			$oDatoVista->setDato('{checkedPAc}', "");
		}else{
			if ($oArticuloVO->getOpcionProv()==2){
				$oDatoVista->setDato('{opcionProv}', 'por Actualización');
				$oDatoVista->setDato('{checkedPAc}', "checked");
				$oDatoVista->setDato('{checkedPAr}', "");
			}else{
				$oDatoVista->setDato('{opcionProv}', ' ');
				$oDatoVista->setDato('{checkedPAc}', "");
				$oDatoVista->setDato('{checkedPAr}', "");
			}
		} 
		
		return $oDatoVista;
	}
}
?>