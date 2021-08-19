<?php
/**
 * Archivo del includes del módulo recibido.
 *
 * Archivo del includes del módulo recibido que permite cargar
 * datos de un registro de la tabla recibidos para representar
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
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      File available since Release 1.0
 */

/**
 * Clase del includes del módulo recibido.
 *
 * Clase del includes del módulo recibido que permite cargar
 * datos de un registro de la tabla recibidos para representar
 * en una vista.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
 
class RecibidoDatos
{
	# Propiedades
	
	# Métodos
	/**
	 * Nos permite cargar datos de un registro de la tabla
	 * recibidos para representar en una vista.
	 *
	 * @param $oRecibidoVO
	 * @param $oDatoVista
	 * @param $accion
	 *
	 * @return $oDatoVista
	 */
	static function cargaDatos($oRecibidoVO, $oDatoVista, $accion)
	{
		// Establece el localismo por moneda
		setlocale(LC_MONETARY, 'es_AR.UTF-8');
		
		$oDatoVista->setDato('{id}', $oRecibidoVO->getId());
		$oDatoVista->setDato('{comprobante}', $oRecibidoVO->getComprobante());
		$oDatoVista->setDato('{fecha}', $oRecibidoVO->getFecha());
		if ($accion == "Ver" OR $accion == "ConfirmarE"){
			$oDatoVista->setDato('{idProveedor}', $oRecibidoVO->getIdProveedor());
			$oProveedorVO = new ProveedorVO();
			$oProveedorModelo = new ProveedorModelo();
			$oProveedorVO->setId($oRecibidoVO->getIdProveedor());
			$oProveedorModelo->find($oProveedorVO);
			$oDatoVista->setDato('{proveedor}', $oProveedorVO->getRazonSocial());
		}
		if ($accion == "Editar"){
			$oDatoVista->setDato('{gravado}', $oRecibidoVO->getGravado());
			$oDatoVista->setDato('{exento}', $oRecibidoVO->getExento());
			$oDatoVista->setDato('{retencionDgi}', $oRecibidoVO->getRetencionDgi());
			$oDatoVista->setDato('{percepcionDgi}', $oRecibidoVO->getPercepcionDgi());
			$oDatoVista->setDato('{retencionRenta}', $oRecibidoVO->getRetencionRenta());
			$oDatoVista->setDato('{percepcionRenta}', $oRecibidoVO->getPercepcionRenta());
			$oDatoVista->setDato('{otros}', $oRecibidoVO->getOtros());
			$oDatoVista->setDato('{iva}', $oRecibidoVO->getIva());
			$oDatoVista->setDato('{total}', $oRecibidoVO->getTotal());
		}else{
			$oDatoVista->setDato('{gravado}', money_format("%.2n", $oRecibidoVO->getGravado()));
			$oDatoVista->setDato('{exento}', money_format("%.2n", $oRecibidoVO->getExento()));
			$oDatoVista->setDato('{retencionDgi}', money_format("%.2n", $oRecibidoVO->getRetencionDgi()));
			$oDatoVista->setDato('{percepcionDgi}', money_format("%.2n", $oRecibidoVO->getPercepcionDgi()));
			$oDatoVista->setDato('{retencionRenta}', money_format("%.2n", $oRecibidoVO->getRetencionRenta()));
			$oDatoVista->setDato('{percepcionRenta}', money_format("%.2n", $oRecibidoVO->getPercepcionRenta()));
			$oDatoVista->setDato('{otros}', money_format("%.2n", $oRecibidoVO->getOtros()));
			$oDatoVista->setDato('{iva}', money_format("%.2n", $oRecibidoVO->getIva()));
			$oDatoVista->setDato('{total}', money_format("%.2n", $oRecibidoVO->getTotal()));
		}
		$oDatoVista->setDato('{comentario}', $oRecibidoVO->getComentario());
		if ($oRecibidoVO->getConsistencia() == 1){
			$oDatoVista->setDato('{consistencia}', 'SI');
		}else{
			$oDatoVista->setDato('{consistencia}', 'NO');
		}
		return $oDatoVista;
	}
}
?>