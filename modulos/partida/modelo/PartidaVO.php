<?php
/**
 * Archivo de la clase ValueObject.
 *
 * Archivo de la clase ValueObject que nos permite mapear la
 * estructura de la tabla partidas en un objeto para poder
 * realizar operaciones de tipo CRUD u otras sobre la tabla
 * partidas de la base de datos.
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
 * Clase que nos permite mapear la tabla partidas a un objeto.
 *
 * Clase que nos permite mapear la tabla partidas a un objeto
 * que utlizaremos luego para realizar operaciones de tipo
 * CRUD y otras sobre la tabla partidas de la DB.
 *
 * @copyright  Copyright (c) 2015 Alvaro A. Guiffrey (http://www.alvaroguiffrey.com.ar)
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class PartidaVO
{
	#Propiedades
	private $_id;
	private $_idArticulo;
	private $_idRecibido;
	private $_fecha;
	private $_cantIngresada;
	private $_costo;
	private $_stock;
	private $_ivaAlicuota;
	private $_comentario;
	private $_idUsuarioAct;
	private $_fechaAct;

	#Métodos
	/**
	* Nos permite obtener el identificador de la partida.
	*
	* @return integer
	*/
	public function getId()
	{
		return $this->_id;
	}

	/**
	 * Nos permite obtener el id del artículo.
	 *
	 * @return integer
	 */
	public function getIdArticulo()
	{
		return $this->_idArticulo;
	}

	/**
	 * Nos permite obtener el id del comprobante recibido.
	 *
	 * @return integer
	 */
	public function getIdRecibido()
	{
		return $this->_idRecibido;
	}

	/**
	 * Nos permite obtener la fecha de la partida.
	 *
	 * @return date
	 */
	public function getFecha()
	{
		return $this->_fecha;
	}

	/**
	 * Nos permite obtener cantidad ingresada de la partida.
	 *
	 * @return integer
	 */
	public function getCantIngresada()
	{
		return $this->_cantIngresada;
	}

	/**
	 * Nos permite obtener el costo unitario de la partida.
	 *
	 * @return decimal
	 */
	public function getCosto()
	{
		return $this->_costo;
	}

	/**
	 * Nos permite obtener el stock de la partida.
	 *
	 * @return integer
	 */
	public function getStock()
	{
		return $this->_stock;
	}

	/**
	 * Nos permite obtener la alicuota del IVA de la partida.
	 *
	 * @return decimal(4,2)
	 */
	public function getIvaAlicuota()
	{
		return $this->_ivaAlicuota;
	}
	
	/**
	 * Nos permite obtener el comentario sobre la partida.
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
	 * @return integer
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
	 * Nos permite establecer el identificador de la partida.
	 *
	 * @param integer $id
	 */
	public function setId($id)
	{
		$this->_id = $id;
	}

	/**
	 * Nos permite establecer el id del artículo de la partida.
	 *
	 * @param integer $idArticulo
	 */
	public function setIdArticulo($idArticulo)
	{
		$this->_idArticulo = $idArticulo;
	}

	/**
	 * Nos permite establecer el id del comprobante recibido.
	 *
	 * @param integer $idRecibido
	 */
	public function setIdRecibido($idRecibido)
	{
		$this->_idRecibido = $idRecibido;
	}
		
	/**
	 * Nos permite establecer la fecha de la partida.
	 *
	 * @param date $fecha
	 */
	public function setFecha($fecha)
	{
		$this->_fecha = $fecha;
	}

	/**
	 * Nos permite establecer la cantidad ingresa de la partida.
	 *
	 * @param integer $cantIngresada
	 */
	public function setCantIngresada($cantIngresada)
	{
		$this->_cantIngresada = $cantIngresada;
	}

	/**
	 * Nos permite establecer el costo de la partida.
	 *
	 * @param decimal $costo
	 */
	public function setCosto($costo)
	{
		$this->_costo = $costo;
	}

	/**
	 * Nos permite establecer el stock de la partida.
	 *
	 * @param integer $stock
	 */
	public function setStock($stock)
	{
		$this->_stock = $stock;
	}

	/**
	 * Nos permite establecer la alicuota de IVA de la partida.
	 *
	 * @param decimal(4,2) $margen
	 */
	public function setIvaAlicuota($ivaAlicuota)
	{
		$this->_ivaAlicuota = $ivaAlicuota;
	}
	
	/**
	 * Nos permite establecer comentario para la partida.
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
	 * @param integer $id_usuario_act
	 */
	public function setIdUsuarioAct($id_usuario_act)
	{
		$this->_idUsuarioAct = $id_usuario_act;
	}

	/**
	 * Nos permite establecer la fecha de la última actualización.
	 *
	 * @param DateTime $fecha_act
	 */
	public function setFechaAct($fecha_act)
	{
		$this->_fechaAct = $fecha_act;
	}

}
?>