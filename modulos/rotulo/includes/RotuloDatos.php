<?php
/**
 * Archivo del includes del módulo rotulo.
 *
 * Archivo del includes del módulo rotulo que permite cargar
 * datos de un registro de la tabla artículos para representar
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
 * Clase del includes del módulo rotulo.
 *
 * Clase del includes del módulo rotulo que permite cargar
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

class RotuloDatos
{
	# Propiedades

	# Métodos
	/**
	* Nos permite cargar datos de un registro de la tabla
	* artículos para representar en una vista de rótulos.
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
		$oDatoVista->setDato('{nombre}', $oArticuloVO->getNombre());
		$oDatoVista->setDato('{presentacion}', $oArticuloVO->getPresentacion());
		$oDatoVista->setDato('{precio}', number_format($oArticuloVO->getPrecio(), 2, ',', '.'));
		$oDatoVista->setDato('{fechaPrecio}', $oArticuloVO->getFechaPrecio());

		return $oDatoVista;
	}
}
?>