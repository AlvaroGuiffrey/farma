<?php
/**
 * Archivo de la clase ValueObject.
 *
 * Archivo de la clase ArticuloVO que nos permite mapear la
 * estructura de la tabla articulos en un objeto para poder
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
 * Clase que nos permite mapear la tabla articulos_d a un objeto.
 *
 * Clase que nos permite mapear la tabla articulos_d a un objeto
 * que utlizaremos luego para realizar operaciones de tipo
 * CRUD y otras sobre la tabla articulos de la DB farma.
 *
 * @copyright  Copyright (c) 2015 Alvaro A. Guiffrey (http://www.alvaroguiffrey.com.ar)
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class ArticuloDVO
{
	#Propiedades
	private $_id;
	private $_codigo;
	private $_codigoM;
	private $_codigoB;
	private $_idMarca;
	private $_idRubro;
	private $_nombre;
	private $_presentacion;
	private $_comentario;
	private $_margen;
	private $_costo;
	private $_precio;
	private $_fechaPrecio;
	private $_stock;
	private $_rotulo;
	private $_idProveedor;
	private $_opcionProv;
	private $_equivalencia;
	private $_codigoIva;
	private $_foto;
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
	 * Nos permite obtener el código del artículo.
	 *
	 * @return integer
	 */
	public function getCodigo()
	{
		return $this->_codigo;
	}

	/**
	 * Nos permite obtener el código asignado por la marca al artículo.
	 *
	 * @return string
	 */
	public function getCodigoM()
	{
		return $this->_codigoM;
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
	 * Nos permite obtener el identificador de la marca del artículo.
	 *
	 * @return integer
	 */
	public function getIdMarca()
	{
		return $this->_idMarca;
	}

	/**
	 * Nos permite obtener el identificador del rubro del artículo.
	 *
	 * @return integer
	 */
	public function getIdRubro()
	{
		return $this->_idRubro;
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
	 * Nos permite obtener la presentación del artículo.
	 *
	 * @return string
	 */
	public function getPresentacion()
	{
		return $this->_presentacion;
	}

	/**
	 * Nos permite obtener el comentario sobre el artículo.
	 *
	 * @return string
	 */
	public function getComentario()
	{
		return $this->_comentario;
	}

	/**
	 * Nos permite obtener el margen bruto del artículo.
	 *
	 * @return decimal(3,2)
	 */
	public function getMargen()
	{
		return $this->_margen;
	}

	/**
	 * Nos permite obtener el costo del artículo.
	 *
	 * @return decimal(6,2)
	 */
	public function getCosto()
	{
		return $this->_costo;
	}

	/**
	 * Nos permite obtener el precio de venta del artículo.
	 *
	 * @return decimal(6,2)
	 */
	public function getPrecio()
	{
		return $this->_precio;
	}

	/**
	 * Nos permite obtener fecha del precio del artículo.
	 *
	 * @return DateTime
	 */
	public function getFechaPrecio()
	{
		return $this->_fechaPrecio;
	}

	/**
	 * Nos permite obtener el stock del artículo.
	 *
	 * @return integer
	 */
	public function getStock()
	{
		return $this->_stock;
	}

	/**
	 * Nos permite obtener el estado del rotulo del artículo.
	 * Si se imprime rotulo para excibir o colocar en góndola
	 *
	 * @return integer
	 */
	public function getRotulo()
	{
		return $this->_rotulo;
	}

	/**
	 * Nos permite obtener el índice del proveedor de referencia.
	 *
	 * @return integer
	 */
	public function getIdProveedor()
	{
		return $this->_idProveedor;
	}

	/**
	 * Nos permite obtener la opción para actualizar el proveedor
	 * de referencia.
	 *
	 * @return integer
	 */
	public function getOpcionProv()
	{
		return $this->_opcionProv;
	}

	/**
	 * Nos permite obtener si existen equivalencias de productos
	 * con el de los proveedores.
	 *
	 * @return integer
	 */
	public function getEquivalencia()
	{
		return $this->_equivalencia;
	}

	/**
	 * Nos permite obtener el código de iva de AFIP.
	 *
	 * @return integer
	 */
	public function getCodigoIva()
	{
		return $this->_codigoIva;
	}

	/**
	 * Nos permite obtener la ruta de la foto del artículo.
	 *
	 * @return string
	 */
	public function getFoto()
	{
		return $this->_foto;
	}

	/**
	 * Nos permite obtener el estado del artículo.
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
	 * Nos permite establecer el identificador del rubro.
	 *
	 * @param integer $id
	 */
	public function setId($id)
	{
		$this->_id = $id;
	}

	/**
	 * Nos permite establecer el código del artículo.
	 *
	 * @param integer $codigo
	 */
	public function setCodigo($codigo)
	{
		$this->_codigo = $codigo;
	}

	/**
	 * Nos permite establecer el código de la marca del artículo.
	 *
	 * @param string $codigoM
	 */
	public function setCodigoM($codigoM)
	{
		$this->_codigoM = $codigoM;
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
	 * Nos permite establecer el identificador de la marca del artículo.
	 *
	 * @param integer $idMarca
	 */
	public function setIdMarca($idMarca)
	{
		$this->_idMarca = $idMarca;
	}

	/**
	 * Nos permite establecer el identificador del rubro del artículo.
	 *
	 * @param integer $idRubro
	 */
	public function setIdRubro($idRubro)
	{
		$this->_idRubro = $idRubro;
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
	 * Nos permite establecer la presentación del articulo.
	 *
	 * @param string $presentacion
	 */
	public function setPresentacion($presentacion)
	{
		$this->_presentacion = $presentacion;
	}

	/**
	 * Nos permite establecer comentario para el articulo.
	 *
	 * @param string $comentario
	 */
	public function setComentario($comentario)
	{
		$this->_comentario = $comentario;
	}

	/**
	 * Nos permite establecer el margen bruto del artículo.
	 *
	 * @param decimal(3,2) $margen
	 */
	public function setMargen($margen)
	{
		$this->_margen = $margen;
	}

	/**
	 * Nos permite establecer el costo del artículo.
	 *
	 * @param decimal(6,2) $costo
	 */
	public function setCosto($costo)
	{
		$this->_costo = $costo;
	}

	/**
	 * Nos permite establecer el precio de venta del artículo.
	 *
	 * @param decimal(6,2) $precio
	 */
	public function setPrecio($precio)
	{
		$this->_precio = $precio;
	}

	/**
	 * Nos permite establecer la fecha del precio del artículo.
	 *
	 * @param DateTime $fechaPrecio
	 */
	public function setFechaPrecio($fechaPrecio)
	{
		$this->_fechaPrecio = $fechaPrecio;
	}

	/**
	 * Nos permite establecer el stock del artículo.
	 *
	 * @param integer $stock
	 */
	public function setStock($stock)
	{
		$this->_stock = $stock;
	}

	/**
	 * Nos permite establecer el estado del rotulo.
	 * Para imprimir rotulo para excibir o poner en góndola
	 *
	 * @param integer $rotulo
	 */
	public function setRotulo($rotulo)
	{
		$this->_rotulo = $rotulo;
	}

	/**
	 * Nos permite establecer el id del proveedor de referencia.
	 *
	 * @param integer $idProveedor
	 */
	public function setIdProveedor($idProveedor)
	{
		$this->_idProveedor = $idProveedor;
	}

	/**
	 * Nos permite establecer la opción para actualizar el proveedor
	 * de referencia.
	 *
	 * @param integer $opcionProv
	 */
	public function setOpcionProv($opcionProv)
	{
		$this->_opcionProv = $opcionProv;
	}

	/**
	 * Nos permite establecer un indicador para saber si hay equivalencias
	 * de artículos de los proveedores.
	 *
	 * @param integer $equivalencia
	 */
	public function setEquivalencia($equivalencia)
	{
		$this->_equivalencia = $equivalencia;
	}

	/**
	 * Nos permite establecer el código de IVA de la AFIP.
	 *
	 * @param integer $codigoIva
	 */
	public function setCodigoIva($codigoIva)
	{
		$this->_codigoIva = $codigoIva;
	}

	/**
	 * Nos permite establecer ruta de la foto del artículo.
	 *
	 * @param string $foto
	 */
	public function setFoto($foto)
	{
		$this->_foto = $foto;
	}

	/**
	 * Nos permite establecer el estado el artículo.
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
