<?php
/**
 * Archivo del include del módulo articulo.
 *
 * Archivo del include del módulo articulo que arma un array con las
 * opciones sobre el proveedor de referencia.
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
 * Clase del include del módulo articulo.
 *
 * Clase ArticuloOpcionProv del módulo articulo que permite armar
 * un array con las opciones de proveedor de referencia para las vistas.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class ArticuloOpcionProv
{
	# Propiedades

	# Métodos
	/**
	* Nos permite crear un array con las opciones sobre el proveedor de 
	* referencia.
	*
	*
	* @return array() $aOpcionProv 
	*/
	static function getArray()
	{
		$aOpcionProv = array(
								0 => array(
											"valor" => 0,
											"descripcion" => "NO ASIGNADA",
											"radioButton" => " ",
											"comentario" => " ",
											),
								1 => array(
											"valor" => 1,
											"descripcion" => "Por Artículo",
											"radioButton" => " por Artículo",
											"comentario" => "Permite modificar el proveedor de referencia solo modificando el artículo",
											),
								2 => array(
											"valor" => 2,
											"descripcion" => "Por Actualización",
											"radioButton" => " por Actualización",
											"comentario" => "Permite modificar el proveedor de referencia por -Actualización de Precios-",
				)
							);
		return $aOpcionProv;
	}
}
?>