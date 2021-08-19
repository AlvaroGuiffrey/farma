<?php
/**
 * Archivo de la clase ValueObject.
 *
 * Archivo de la clase ValueObject que nos permite mapear la
 * estructura de la tabla proveedores en un objeto para poder
 * realizar operaciones de tipo CRUD u otras sobre la tabla
 * proveedores de la base de datos.
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
 * Clase que nos permite mapear la tabla proveedores a un objeto.
 *
 * Clase que nos permite mapear la tabla proveedores a un objeto
 * que utlizaremos luego para realizar operaciones de tipo
 * CRUD y otras sobre la tabla proveedores de la DB.
 *
 * @copyright  Copyright (c) 2015 Alvaro A. Guiffrey (http://www.alvaroguiffrey.com.ar)
 * @license    
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class ProveedorVO
{
	#Propiedades
	private $_id;
	private $_razonSocial;
	private $_inicial;
	private $_domicilioFiscal;
	private $_cuit;
	private $_inscripto;
	private $_ingresosBrutos;
	private $_domicilio;
	private $_codPostal;
	private $_idLocalidad;
	private $_telefono;
	private $_movil;
	private $_email;
	private $_comentario;
	private $_lista;
	private $_listaOrden;
	private $_estado;
	private $_idUsuarioAct;
	private $_fechaAct;

	#Métodos
	/**
	* Nos permite obtener el identificador del registro
	* @return integer
	*/
	public function getId()
	{
		return $this->_id;
	}

	/**
	 * Nos permite obtener la razón social del proveedor.
	 *
	 * @return string
	 */
	public function getRazonSocial()
	{
		return $this->_razonSocial;
	}

	/**
	 * Nos permite obtener la inicial que identifica al proveedor.
	 *
	 * @return string
	 */
	public function getInicial()
	{
		return $this->_inicial;
	}
	
	/**
	 * Nos permite obtener el domicilio fiscal del proveedor.
	 *
	 * @return string
	 */
	public function getDomicilioFiscal()
	{
		return $this->_domicilioFiscal;
	}

	/**
	 * Nos permite obtener el cuit del proveedor.
	 *
	 * @return string
	 */
	public function getCuit()
	{
		return $this->_cuit;
	}

	/**
	 * Nos permite obtener la inscripción ante el IVA del proveedor
	 * según el código de AFIP.
	 *
	 * @return string
	 */
	public function getInscripto()
	{
		return $this->_inscripto;
	}

	/**
	 * Nos permite obtener el numero de inscripcion de 
	 * ingresos brutos del proveedor.
	 *
	 * @return string
	 */
	public function getIngresosBrutos()
	{
		return $this->_ingresosBrutos;
	}

	/**
	 * Nos permite obtener el domicilio comercial del proveedor.
	 *
	 * @return string
	 */
	public function getDomicilio()
	{
		return $this->_domicilio;
	}

	/**
	 * Nos permite obtener el codigo postal de la Localidad del domicilio.
	 *
	 * @return varchar
	 */
	public function getCodPostal()
	{
		return $this->_codPostal;
	}
	
	/**
	 * Nos permite obtener el id de la Localidad del domicilio.
	 *
	 * @return int
	 */
	public function getIdLocalidad()
	{
		return $this->_idLocalidad;
	}
	
	/**
	 * Nos permite obtener el teléfono del proveedor.
	 *
	 * @return string
	 */
	public function getTelefono()
	{
		return $this->_telefono;
	}

	/**
	 * Nos permite obtener el movil del proveedor.
	 *
	 * @return string
	 */
	public function getMovil()
	{
		return $this->_movil;
	}

	/**
	 * Nos permite obtener el email del proveedor.
	 *
	 * @return string
	 */
	public function getEmail()
	{
		return $this->_email;
	}

	
	/**
	 * Nos permite obtener el comentario sobre el proveedor.
	 *
	 * @return string
	 */
	public function getComentario()
	{
		return $this->_comentario;
	}

	/**
	 * Nos permite obtener el tipo de lista de precios del proveedor.
	 * F: Precios Finales (IVA incluido)
	 * N: Precios Netos de IVA
	 *
	 * @return string
	 */
	public function getLista()
	{
		return $this->_lista;
	}
	
	/**
	 * Nos permite obtener el orden de la lista de precios del proveedor que se 
	 * tomará para actualizar precios.
	 * 0: Proveedor sin lista de precios
	 * 1: Proveedor con lista de precios con PRIORIDAD
	 * 2: Proveedor con lista de precios por Orden de Jerarquía asignado
	 *
	 * @return string
	 */
	public function getListaOrden()
	{
		return $this->_listaOrden;
	}
	
	/**
	 * Nos permite obtener el estado del proveedor.
	 *
	 * @return integer
	 */
	public function getEstado()
	{
		return $this->_estado;
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
	 * Nos permite establecer el identificador del proveedor.
	 *
	 * @param integer $id
	 */
	public function setId($id)
	{
		$this->_id = $id;
	}

	/**
	 * Nos permite establecer la razón social del proveedor.
	 *
	 * @param varchar $razonSocial
	 */
	public function setRazonSocial($razonSocial)
	{
		$this->_razonSocial = $razonSocial;
	}

	/**
	 * Nos permite establecer la inicial que identifica al proveedor.
	 *
	 * @param varchar $inicial
	 */
	public function setInicial($inicial)
	{
		$this->_inicial = $inicial;
	}
	
	/**
	 * Nos permite establecer el domicilio fiscal del proveedor.
	 *
	 * @param varchar $domicilioFiscal
	 */
	public function setDomicilioFiscal($domicilioFiscal)
	{
		$this->_domicilioFiscal = $domicilioFiscal;
	}
	
	/**
	 * Nos permite establecer el cuit del proveedor.
	 *
	 * @param varchar $cuit
	 */
	public function setCuit($cuit)
	{
		$this->_cuit = $cuit;
	}

	/**
	 * Nos permite establecer la inscripción ante el IVA del proveedor.
	 * Según código de AFIP.
	 *
	 * @param varchar $inscripto
	 */
	public function setInscripto($inscripto)
	{
		$this->_inscripto = $inscripto;
	}

	/**
	 * Nos permite establecer el número de ingresos brutos del proveedor.
	 *
	 * @param varchar $ingresosBrutos
	 */
	public function setIngresosBrutos($ingresosBrutos)
	{
		$this->_ingresosBrutos = $ingresosBrutos;
	}

	/**
	 * Nos permite establecer el domicilio comercial del proveedor.
	 *
	 * @param varchar $domicilio
	 */
	public function setDomicilio($domicilio)
	{
		$this->_domicilio = $domicilio;
	}

	/**
	 * Nos permite establecer el codigo postal del domicilio comercial del proveedor.
	 * Ejemplo: B1636FDA (letra Provincia-Codigo Postal-Cara de Manzana)
	 * @param varchar $codPostal
	 */
	public function setCodPostal($codPostal)
	{
		$this->_codPostal = $codPostal;
	}
	
	/**
	 * Nos permite establecer el id de la localidad del proveedor.
	 *
	 * @param int $idLocalidad
	 */
	public function setIdLocalidad($idLocalidad)
	{
		$this->_idLocalidad = $idLocalidad;
	}
	
	/**
	 * Nos permite establecer el teléfono del proveedor.
	 *
	 * @param varchar $telefono
	 */
	public function setTelefono($telefono)
	{
		$this->_telefono = $telefono;
	}

	/**
	 * Nos permite establecer el movil del proveedor.
	 *
	 * @param varchar $movil
	 */
	public function setMovil($movil)
	{
		$this->_movil = $movil;
	}

	/**
	 * Nos permite establecer el email del proveedor.
	 *
	 * @param varchar $email
	 */
	public function setEmail($email)
	{
		$this->_email = $email;
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
	 * Nos permite establecer el tipo de lista de precios del proveedor.
	 *
	 * @param string $lista
	 * @var "F" = Precios Finales (con IVA incluido)
	 * @var "N" = Precios Netos (sin IVA) 
	 */
	public function setLista($lista)
	{
		$this->_lista = $lista;
	}

	/**
	 * Nos permite establecer el orden de lista de precios del proveedor
	 * para actualizar precios.
	 *
	 * @param int $listaOrden
	 * @var 0 = Proveedor sin lista de precios
	 * @var 1 = Proveedor con lista de precios con PRIORIDAD
	 * @var 2 = Proveedor con lista de precios con orden de jerarquía asignado
	 */
	public function setListaOrden($listaOrden)
	{
		$this->_listaOrden = $listaOrden;
	}
	
	/**
	 * Nos permite establecer el estado del proveedor.
	 *
	 * @param integer $estado
	 */
	public function setEstado($estado)
	{
		$this->_estado = $estado;
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