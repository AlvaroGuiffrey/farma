<?php
/**
 * Archivo de la clase ValueObject.
 *
 * Archivo de la clase ProvinciaVO que nos permite mapear la
 * estructura de la tabla provincias en un objeto para poder
 * realizar operaciones de tipo CRUD u otras sobre la tabla
 * provincias de la base de datos.
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
 * Clase que nos permite mapear la tabla provincias a un objeto.
 *
 * Clase que nos permite mapear la tabla provincias a un objeto
 * que utlizaremos luego para realizar operaciones de tipo
 * CRUD y otras sobre la tabla provincias ubicada en la DB caro.
 *
 * @copyright  Copyright (c) 2015 Alvaro A. Guiffrey (http://www.alvaroguiffrey.com.ar)
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class ProvinciaVO
{
	#Propiedades
	private $_id;
	private $_numero;
	private $_nombre;
	private $_letra;
	private $_pais;
	private $_idUsuarioAct;
	private $_fechaAct;


	#Métodos
	/**
	* Nos permite obtener el identificador de la provincia.
	*
	* @return integer
	*/
	public function getId()
	{
		return $this->_id;
	}

	/**
	 * Nos permite obtener el número de la provincia.
	 *
	 * @return integer
	 */
	public function getNumero()
	{
		return $this->_numero;
	}
	
	/**
	 * Nos permite obtener el nombre de la provincia.
	 *
	 * @return string
	 */
	public function getNombre()
	{
		return $this->_nombre;
	}

	/**
	 * Nos permite obtener la letra de la provincia.
	 *
	 * @return string
	 */
	public function getLetra()
	{
		return $this->_letra;
	}	
	/**
	 * Nos permite obtener el país que integra la provincia.
	 *
	 * @return string
	 */
	public function getPais()
	{
		return $this->_pais;
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
	 * Nos permite establecer el identificador de la provincia.
	 *
	 * @param integer $id
	 */
	public function setId($id)
	{
		$this->_id = $id;
	}

	/**
	 * Nos permite establecer el numero de la provincia.
	 *
	 * @param string $numero
	 */
	public function setNumero($numero)
	{
		$this->_numero = $numero;
	}
	
	/**
	 * Nos permite establecer el nombre de la provincia.
	 *
	 * @param string $nombre
	 */
	public function setNombre($nombre)
	{
		$this->_nombre = $nombre;
	}

	/**
	 * Nos permite establecer la letra de la provincia.
	 *
	 * @param string $letra
	 */
	public function setLetra($letra)
	{
		$this->_letra = $letra;
	}
	
	/**
	 * Nos permite establecer el país que integra la provincia.
	 *
	 * @param string $pais
	 */
	public function setPais($pais)
	{
		$this->_pais = $pais;
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