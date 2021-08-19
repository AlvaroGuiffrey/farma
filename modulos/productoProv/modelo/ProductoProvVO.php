<?php
/**
 * Archivo de la clase ValueObject.
 *
 * Archivo de la clase ProductosProvVO que nos permite mapear la
 * estructura de la tabla productos_prov en un objeto para poder
 * realizar operaciones de tipo CRUD u otras sobre la tabla
 * productos_prov de la base de datos.
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
 * Clase que nos permite mapear la tabla productos_prov a un objeto.
 *
 * Clase que nos permite mapear la tabla productos_prov a un objeto
 * que utlizaremos luego para realizar operaciones de tipo
 * CRUD y otras sobre la tabla productos_prov ubicada en la DB.
 *
 * @copyright  Copyright (c) 2015 Alvaro A. Guiffrey (http://www.alvaroguiffrey.com.ar)
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class ProductoProvVO
{
	#Propiedades
	private $_id;
	private $_idProveedor;
	private $_codigoB;
	private $_codigoP;
	private $_nombre;
	private $_precio;
	private $_codigoIva;
	private $_tipoDescuento;
	private $_estado;
	private $_idArticulo;
	private $_idUsuarioAct;
	private $_fechaAct;


	#Métodos
	/**
	* Nos permite obtener el identificador del producto.
	*
	* @return integer
	*/
	public function getId()
	{
		return $this->_id;
	}

	/**
	 * Nos permite obtener el índice del proveedor del producto.
	 *
	 * @return integer
	 */
	public function getIdProveedor()
	{
		return $this->_idProveedor;
	}

	/**
	 * Nos permite obtener el codigo de barra del producto.
	 *
	 * @return #bigint 
	 */
	public function getCodigoB()
	{
		return $this->_codigoB;
	}

	/**
	 * Nos permite obtener el codigo del proveedor del producto.
	 *
	 * @return string
	 */
	public function getCodigoP()
	{
		return $this->_codigoP;
	}
	
	/**
	 * Nos permite obtener el nombre del producto.
	 *
	 * @return string
	 */
	public function getNombre()
	{
		return $this->_nombre;
	}

	/**
	 * Nos permite obtener el precio del producto.
	 *
	 * @return decimal(10,2)
	 */
	public function getPrecio()
	{
		return $this->_precio;
	}
	
	/**
	 * Nos permite obtener el código de la alícuota asignado por AFIP del producto.
	 *
	 * @return int
	 */
	public function getCodigoIva()
	{
	    return $this->_codigoIva;
	}
	
	/**
	 * Nos permite obtener el tipo de descuento del producto.
	 *
	 * @return int
	 */
	public function getTipoDescuento()
	{
	    return $this->_tipoDescuento;
	}

	/**
	 * Nos permite obtener el estado del producto.
	 *
	 * @return int
	 */
	public function getEstado()
	{
		return $this->_estado;
	}

	/**
	 * Nos permite obtener el identificador del artículo.
	 *
	 * @return int
	 */
	public function getIdArticulo()
	{
		return $this->_idArticulo;
	}
	
	/**
	 * Nos permite obtener Id del usuario que actualizó último.
	 *
	 * @return string
	 */
	public function getIdUsuarioAct()
	{
		return $this->_idUsuarioAct;
	}

	/**
	 * Nos permite obtener la fecha de la última actualización.
	 *
	 * @return DateTime
	 */
	public function getFechaAct()
	{
		return $this->_fechaAct;
	}


	/**
	 * Nos permite establecer el identificador del producto.
	 *
	 * @param integer $id
	 */
	public function setId($id)
	{
		$this->_id = $id;
	}

	/**
	 * Nos permite establecer el índice del proveedor del producto.
	 *
	 * @param integer $idProveedor
	 */
	public function setIdProveedor($idProveedor)
	{
		$this->_idProveedor = $idProveedor;
	}

	/**
	 * Nos permite establecer el código de barra del producto.
	 *
	 * @param bigint(13) $codigoB
	 */
	public function setCodigoB($codigoB)
	{
		$this->_codigoB = $codigoB;
	}

	/**
	 * Nos permite establecer el código del proveedor del producto.
	 *
	 * @param varchar(40) $codigoP
	 */
	public function setCodigoP($codigoP)
	{
		$this->_codigoP = $codigoP;
	}
	
	/**
	 * Nos permite establecer el nombre del producto.
	 *
	 * @param string $nombre
	 */
	public function setNombre($nombre)
	{
		$this->_nombre = $nombre;
	}

	/**
	 * Nos permite establecer el precio del producto.
	 *
	 * @param decimal(10,2) $precio
	 */
	public function setPrecio($precio)
	{
		$this->_precio = $precio;
	}
	
	/**
	 * Nos permite establecer el código de alícuota del Iva asignado por AFIP del producto.
	 *
	 * @param int $codigoIva
	 */
	public function setCodigoIva($codigoIva)
	{
	    $this->_codigoIva = $codigoIva;
	}
	
	/**
	 * Nos permite establecer el tipo de descuento del producto.
	 *
	 * @param int $tipoDescuento
	 */
	public function setTipoDescuento($tipoDescuento)
	{
	    $this->_tipoDescuento = $tipoDescuento;
	}

	/**
	 * Nos permite establecer el estado del producto.
	 *
	 * @param int $estado
	 */
	public function setEstado($estado)
	{
		$this->_estado = $estado;
	}

	/**
	 * Nos permite establecer el identificar del artículo.
	 *
	 * @param int $idArticulo
	 */
	public function setIdArticulo($idArticulo)
	{
		$this->_idArticulo = $idArticulo;
	}
	
	/**
	 * Nos permite establecer la ID del último usuario que actualizó tabla.
	 *
	 * @param string $id_usuario_act
	 */
	public function setIdUsuarioAct($id_usuario_act)
	{
		$this->_idUsuarioAct = $id_usuario_act;
	}

	/**
	 * Nos permite establecer la fecha de la última actualización.
	 *
	 * @param string $fecha_act
	 */
	public function setFechaAct($fecha_act)
	{
		$this->_fechaAct = $fecha_act;
	}

}
?>