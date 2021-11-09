<?php
/**
 * Archivo de la clase ValueObject.
 *
 * Archivo de la clase ArticuloPMVO que nos permite mapear la
 * estructura de la tabla articulos_pm en un objeto para poder
 * realizar operaciones de tipo CRUD u otras sobre la tabla
 * articulos de la base de datos.
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
 * @copyright  Copyright (c) 2015 Alvaro A. Guiffrey (http://www.alvaroguiffrey.com.ar)
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      File available since Release 1.0
 */

/**
 * Clase que nos permite mapear la tabla articulos_pm a un objeto.
 *
 * Clase que nos permite mapear la tabla articulos_pm a un objeto
 * que utlizaremos luego para realizar operaciones de tipo
 * CRUD y otras sobre la tabla articulos de la DB farma.
 *
 * @copyright  Copyright (c) 2015 Alvaro A. Guiffrey (http://www.alvaroguiffrey.com.ar)
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class ArticuloPMVO
{
	#Propiedades
	private $_id;
	private $_codigoB;
	private $_nombre;
	private $_precio;
	private $_estado;
	private $_idUsuarioAct;
	private $_fechaAct;

	#Métodos
	/**
	* Nos permite obtener el identificador del artículo.
	*
	* @return integer
	*/
	public function getId()
	{
		return $this->_id;
	}

	/**
	 * Nos permite obtener el código de barra del artículo.
	 *
	 * @return integer
	 */
	public function getCodigoB()
	{
		return $this->_codigoB;
	}

	/**
	 * Nos permite obtener el nombre del artículo.
	 *
	 * @return string
	 */
	public function getNombre()
	{
		return $this->_nombre;
	}

	/**
	 * Nos permite obtener el precio máximo de venta del artículo.
	 *
	 * @return decimal(6,2)
	 */
	public function getPrecio()
	{
		return $this->_precio;
	}

	/**
	 * Nos permite establecer el identificador del rubro.
	 *
	 * @param integer $id
	 */
	public function setId($id)
	{
		$this->_id = $id;
	}

	/**
	 * Nos permite establecer el código de barra del artículo.
	 *
	 * @param integer $codigoB
	 */
	public function setCodigoB($codigoB)
	{
		$this->_codigoB = $codigoB;
	}

	/**
	 * Nos permite establecer el nombre del articulo.
	 *
	 * @param string $nombre
	 */
	public function setNombre($nombre)
	{
		$this->_nombre = $nombre;
	}

	/**
	 * Nos permite establecer el precio máximo de venta del artículo.
	 *
	 * @param decimal(6,2) $precio
	 */
	public function setPrecio($precio)
	{
		$this->_precio = $precio;
	}


}
?>
