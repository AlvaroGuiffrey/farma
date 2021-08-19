<?php
/**
 * Archivo de la clase ValueObject.
 *
 * Archivo de la clase LoginVO que nos permite mapear la 
 * estructura de la tabla en un objeto para poder
 * realizar operaciones de tipo CRUD u otras sobre la tabla
 * login de la base de datos caro.
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
 * Clase que nos permite mapear la tabla login a un objeto.
 *
 * Clase que nos permite mapear la tabla login a un objeto
 * que utlizaremos luego para realizar operaciones de tipo
 * CRUD y otras sobre la tabla login ubicada en la DB caro.
 *
 * @copyright  Copyright (c) 2015 Alvaro A. Guiffrey (http://www.alvaroguiffrey.com.ar)
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class LoginVO
{
	#Propiedades
	private $_idUsuario;
	private $_alias;
	private $_usuario;
	private $_clave;
	private $_categoria;


	#Métodos
	/**
	 * Nos permite obtener el identificador del usuario.
	 *
	 * @return integer
	 */
	public function getIdUsuario()
	{
		return $this->_idUsuario;
	}

	/**
	 * Nos permite obtener el alias del usuario.
	 *
	 * @return string
	 */
	public function getAlias()
	{
		return $this->_alias;
	}

	/**
	 * Nos permite obtener el nombre del usuario.
	 *
	 * @return string
	 */
	public function getUsuario()
	{
		return $this->_usuario;
	}
	
	/**
	 * Nos permite obtener la clave del usuario.
	 *
	 * @return string
	 */
	public function getClave()
	{
		return $this->_clave;
	}
	
	/**
	 * Nos permite obtener la categoría del usuario.
	 *
	 * @return integer
	 */
	public function getCategoria()
	{
		return $this->_categoria;
	}

	
	/**
	 * Nos permite establecer el identificador del usuario.
	 *
	 * @param integer $idUsuario
	 */
	public function setIdUsuario($id_usuario)
	{
		$this->_idUsuario = $id_usuario;
	}

	/**
	 * Nos permite establecer el alias del usuario.
	 *
	 * @param string $alias
	 */
	public function setAlias($alias)
	{
		$this->_alias = $alias;
	}

	/**
	 * Nos permite establecer el nombre del usuario.
	 *
	 * @param string $usuario
	 */
	public function setUsuario($usuario)
	{
		$this->_usuario = $usuario;
	}

	/**
	 * Nos permite establecer la clave del usuario.
	 *
	 * @param string $clave
	 */
	public function setClave($clave)
	{
		$this->_clave = $clave;
	}
	
	/**
	 * Nos permite establecer la categoría del usuario.
	 *
	 * @param string $categoria
	 */
	public function setCategoria($categoria)
	{
		$this->_categoria = $categoria;
	}
	
}