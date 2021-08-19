<?php
/**
 * Archivo de la clase ValueObject.
 *
 * Archivo de la clase AfipCondicionIvaVO que nos permite mapear la
 * estructura de la tabla afip_condicion_iva en un objeto para poder
 * realizar operaciones de tipo CRUD u otras sobre la tabla
 * afip_condicion_iva de la base de datos.
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
 * Clase que nos permite mapear la tabla afip_condicion_iva a un objeto.
 *
 * Clase que nos permite mapear la tabla afip_condicion_iva a un objeto
 * que utlizaremos luego para realizar operaciones de tipo
 * CRUD y otras sobre la tabla afip_condicion_iva ubicada en la DB.
 *
 * @copyright  Copyright (c) 2015 Alvaro A. Guiffrey (http://www.alvaroguiffrey.com.ar)
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class AfipCondicionIvaVO
{
	#Propiedades
	private $_codigo;
	private $_descripcion;
	private $_alicuota;

	#Métodos
	/**
	* Nos permite obtener el código de la condición del IVA asignado por AFIP.
	*
	* @return integer
	*/
	public function getCodigo()
	{
		return $this->_codigo;
	}

	/**
	 * Nos permite obtener la descripcion de la condición del IVA
	 * asignado por AFIP.
	 *
	 * @return string
	 */
	public function getDescripcion()
	{
		return $this->_descripcion;
	}

	/**
	 * Nos permite obtener la alícuota de la condición del IVA
	 * asignado por AFIP.
	 *
	 * @return decimal(4,2)
	 */
	public function getAlicuota()
	{
		return $this->_alicuota;
	}
	
	/**
	 * Nos permite establecer el código de la condición del IVA asignado
	 * por AFIP.
	 *
	 * @param integer $codigo
	 */
	public function setCodigo($codigo)
	{
		$this->_codigo = $codigo;
	}

	/**
	 * Nos permite establecer la descripción de la condición del IVA
	 * asignado por AFIP.
	 *
	 * @param string $descripcion
	 */
	public function setDescripcion($descripcion)
	{
		$this->_descripcion = $descripcion;
	}

	/**
	 * Nos permite establecer la alícuota de la condición del IVA
	 * asignado por AFIP.
	 *
	 * @param decimal $alicuota
	 */
	public function setAlicuota($alicuota)
	{
		$this->_alicuota = $alicuota;
	}
}
?>