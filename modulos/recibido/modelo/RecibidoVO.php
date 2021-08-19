<?php
/**
 * Archivo de la clase ValueObject.
 *
 * Archivo de la clase ValueObject que nos permite mapear la
 * estructura de la tabla recibidos en un objeto para poder
 * realizar operaciones de tipo CRUD u otras sobre la tabla
 * recibidoss de la base de datos.
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
 * Clase que nos permite mapear la tabla recibidos a un objeto.
 *
 * Clase que nos permite mapear la tabla recibidos a un objeto
 * que utlizaremos luego para realizar operaciones de tipo
 * CRUD y otras sobre la tabla recibidos de la DB caro.
 *
 * @copyright  Copyright (c) 2015 Alvaro A. Guiffrey (http://www.alvaroguiffrey.com.ar)
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class RecibidoVO
{
	#Propiedades
	private $_id;
	private $_comprobante;
	private $_fecha;
	private $_idProveedor;
	private $_gravado;
	private $_exento;
	private $_retencionDgi;
	private $_percepcionDgi;
	private $_retencionRenta;
	private $_percepcionRenta;
	private $_otros;
	private $_iva;
	private $_total;
	private $_comentario;
	private $_consistencia;
	private $_idUsuarioAct;
	private $_fechaAct;

	#Métodos
	/**
	* Nos permite obtener el identificador del comprobante recibido.
	*
	* @return integer
	*/
	public function getId()
	{
		return $this->_id;
	}

	/**
	 * Nos permite obtener el comprobante recibido.
	 *
	 * @return varchar
	 */
	public function getComprobante()
	{
		return $this->_comprobante;
	}

	/**
	 * Nos permite obtener fecha del comprobante recibido.
	 *
	 * @return date
	 */
	public function getFecha()
	{
		return $this->_fecha;
	}

	/**
	 * Nos permite obtener el identificador del proveedor.
	 *
	 * @return integer
	 */
	public function getIdProveedor()
	{
		return $this->_idProveedor;
	}

	/**
	 * Nos permite obtener el importe neto gravado del comprobante.
	 *
	 * @return decimal (8,2)
	 */
	public function getGravado()
	{
		return $this->_gravado;
	}

	/**
	 * Nos permite obtener el importe exento del comprobante.
	 *
	 * @return decimal (8,2)
	 */
	public function getExento()
	{
		return $this->_exento;
	}

	/**
	 * Nos permite obtener el importe de la retención DGI.
	 *
	 * @return decimal (8,2)
	 */
	public function getRetencionDgi()
	{
		return $this->_retencionDgi;
	}

	/**
	 * Nos permite obtener el importe de la percepcion DGI.
	 *
	 * @return decimal (8,2)
	 */
	public function getPercepcionDgi()
	{
		return $this->_percepcionDgi;
	}

	/**
	 * Nos permite obtener el importe de la retención de Rentas.
	 *
	 * @return decimal (8,2)
	 */
	public function getRetencionRenta()
	{
		return $this->_retencionRenta;
	}
	
	/**
	 * Nos permite obtener el importe de la percepcion de Rentas.
	 *
	 * @return decimal (8,2)
	 */
	public function getPercepcionRenta()
	{
		return $this->_percepcionRenta;
	}

	/**
	 * Nos permite obtener el importe de otros del comprobante.
	 *
	 * @return decimal (8,2)
	 */
	public function getOtros()
	{
		return $this->_otros;
	}

	/**
	 * Nos permite obtener el importe de IVA del comprobante.
	 *
	 * @return decimal (8,2)
	 */
	public function getIva()
	{
		return $this->_iva;
	}
	
	/**
	 * Nos permite obtener el importe total del comprobante.
	 *
	 * @return decimal (8,2)
	 */
	public function getTotal()
	{
		return $this->_total;
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
	 * Nos permite obtener la consistencia de la suma del comprobante.
	 *
	 * @return integer
	 */
	public function getConsistencia()
	{
		return $this->_consistencia;
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
	 * Nos permite establecer el identificador del comprobante recibido.
	 *
	 * @param integer $id
	 */
	public function setId($id)
	{
		$this->_id = $id;
	}

	/**
	 * Nos permite establecer el comprobante recibido.
	 *
	 * @param varchar $comprobante
	 */
	public function setComprobante($comprobante)
	{
		$this->_comprobante = $comprobante;
	}

	/**
	 * Nos permite establecer la fecha del comprobante recibido.
	 *
	 * @param date $fecha
	 */
	public function setFecha($fecha)
	{
		$this->_fecha = $fecha;
	}

	/**
	 * Nos permite establecer el índice del proveedor que emite el comprobante.
	 *
	 * @param integer $idProveedor
	 */
	public function setIdProveedor($idProveedor)
	{
		$this->_idProveedor = $idProveedor;
	}
	
	/**
	 * Nos permite establecer el importe gravado del comprobante.
	 *
	 * @param decimal (8,2) $gravado
	 */
	public function setGravado($gravado)
	{
		$this->_gravado = $gravado;
	}

	/**
	 * Nos permite establecer el importe exento del comprobante.
	 *
	 * @param decimal $exento
	 */
	public function setExento($exento)
	{
		$this->_exento = $exento;
	}

	/**
	 * Nos permite establecer el importe de la retención DGI.
	 *
	 * @param decimal $retencionDgi
	 */
	public function setRetencionDgi($retencionDgi)
	{
		$this->_retencionDgi = $retencionDgi;
	}

	/**
	 * Nos permite establecer el importe de la percepción de la DGI.
	 *
	 * @param decimal $percepcionDgi
	 */
	public function setPercepcionDgi($percepcionDgi)
	{
		$this->_percepcionDgi = $percepcionDgi;
	}

	/**
	 * Nos permite establecer el importe de la retención de Rentas.
	 *
	 * @param decimal $retencionRenta
	 */
	public function setRetencionRenta($retencionRenta)
	{
		$this->_retencionRenta = $retencionRenta;
	}
	
	/**
	 * Nos permite establecer el importe de la percepción de Rentas.
	 *
	 * @param decimal $percepcionRenta
	 */
	public function setPercepcionRenta($percepcionRenta)
	{
		$this->_percepcionRenta = $percepcionRenta;
	}

	/**
	 * Nos permite establecer el importe de otros.
	 *
	 * @param decimal $otros
	 */
	public function setOtros($otros)
	{
		$this->_otros = $otros;
	}

	/**
	 * Nos permite establecer el importe de IVA del comprobante.
	 *
	 * @param decimal $iva
	 */
	public function setIva($iva)
	{
		$this->_iva = $iva;
	}
	
	/**
	 * Nos permite establecer el importe total del comprobante.
	 *
	 * @param decimal $total
	 */
	public function setTotal($total)
	{
		$this->_total = $total;
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
	 * Nos permite establecer la consistencia de la suma del comprobante.
	 *
	 * @param string $consistencia
	 */
	public function setConsistencia($consistencia)
	{
		$this->_consistencia = $consistencia;
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