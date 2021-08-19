<?php
/**
 * Archivo de la clase ValueObject.
 *
 * Archivo de la clase LocalidadVO que nos permite mapear la
 * estructura de la tabla localidades en un objeto para poder
 * realizar operaciones de tipo CRUD u otras sobre la tabla
 * localidades de la base de datos.
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
 * Clase que nos permite mapear la tabla localidades a un objeto.
 *
 * Clase que nos permite mapear la tabla localidades a un objeto
 * que utlizaremos luego para realizar operaciones de tipo
 * CRUD y otras sobre la tabla localidades ubicada en la DB.
 *
 * @copyright  Copyright (c) 2015 Alvaro A. Guiffrey (http://www.alvaroguiffrey.com.ar)
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class LocalidadVO
{
	#Propiedades
	private $_id;
	private $_nombre;
	private $_codPostal;
	private $_departamento;
	private $_idProvincia;
	private $_idUsuarioAct;
	private $_fechaAct;


	#Métodos
	/**
	* Nos permite obtener el identificador de la localidad.
	*
	* @return integer
	*/
	public function getId()
	{
		return $this->_id;
	}

	/**
	 * Nos permite obtener el nombre de la localidad.
	 *
	 * @return string
	 */
	public function getNombre()
	{
		return $this->_nombre;
	}

	/**
	 * Nos permite obtener el codigo postal de la localidad.
	 *
	 * @return integer
	 */
	public function getCodPostal()
	{
		return $this->_codPostal;
	}
	
	/**
	 * Nos permite obtener el departamento que integra la localidad.
	 *
	 * @return string
	 */
	public function getDepartamento()
	{
		return $this->_departamento;
	}

	/**
	 * Nos permite obtener el identificador de la provincia que 
	 * integra la localidad.
	 *
	 * @return integer
	 */
	public function getIdProvincia()
	{
		return $this->_idProvincia;
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
	 * Nos permite establecer el identificador de la localidad.
	 *
	 * @param integer $id
	 */
	public function setId($id)
	{
		$this->_id = $id;
	}

	/**
	 * Nos permite establecer el nombre de la localidad.
	 *
	 * @param string $nombre
	 */
	public function setNombre($nombre)
	{
		$this->_nombre = $nombre;
	}

	/**
	 * Nos permite establecer el código postal de la localidad.
	 *
	 * @param integer $codPostal
	 */
	public function setCodPostal($codPostal)
	{
		$this->_codPostal = $codPostal;
	}

	/**
	 * Nos permite establecer el departamento que integra la localidad.
	 *
	 * @param string $departamento
	 */
	public function setDepartamento($departamento)
	{
		$this->_departamento = $departamento;
	}

	/**
	 * Nos permite establecer el identificador de la provincia
	 * que integra la localidad.
	 *
	 * @param string $idProvincia
	 */
	public function setIdProvincia($idProvincia)
	{
		$this->_idProvincia = $idProvincia;
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