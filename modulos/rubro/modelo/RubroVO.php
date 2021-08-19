<?php
/**
 * Archivo de la clase ValueObject.
 *
 * Archivo de la clase MarcaVO que nos permite mapear la
 * estructura de la tabla rubros en un objeto para poder
 * realizar operaciones de tipo CRUD u otras sobre la tabla
 * rubros de la base de datos caro.
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
 * Clase que nos permite mapear la tabla rubros a un objeto.
 *
 * Clase que nos permite mapear la tabla rubros a un objeto
 * que utlizaremos luego para realizar operaciones de tipo
 * CRUD y otras sobre la tabla rubros ubicada en la DB caro.
 *
 * @copyright  Copyright (c) 2015 Alvaro A. Guiffrey (http://www.alvaroguiffrey.com.ar)
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class RubroVO
{
	#Propiedades
	private $_id;
	private $_nombre;
	private $_comentario;
	private $_idUsuarioAct;
	private $_fechaAct;


	#Métodos
	/**
	* Nos permite obtener el identificador del rubro.
	*
	* @return integer
	*/
	public function getId()
	{
		return $this->_id;
	}

	/**
	 * Nos permite obtener el nombre del rubro.
	 *
	 * @return string
	 */
	public function getNombre()
	{
		return $this->_nombre;
	}

	/**
	 * Nos permite obtener el comentario sobre el rubro.
	 *
	 * @return string
	 */
	public function getComentario()
	{
		return $this->_comentario;
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
	 * Nos permite establecer el identificador del rubro.
	 *
	 * @param integer $id
	 */
	public function setId($id)
	{
		$this->_id = $id;
	}

	/**
	 * Nos permite establecer el nombre del rubro.
	 *
	 * @param string $nombre
	 */
	public function setNombre($nombre)
	{
		$this->_nombre = $nombre;
	}

	/**
	 * Nos permite establecer comentario para el rubro.
	 *
	 * @param string $comentario
	 */
	public function setComentario($comentario)
	{
		$this->_comentario = $comentario;
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