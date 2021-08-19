<?php
/**
 * Archivo de la clase ValueObject.
 *
 * Archivo de la clase PendienteVO que nos permite mapear la
 * estructura de la tabla pendientes en un objeto para poder
 * realizar operaciones de tipo CRUD u otras sobre la tabla
 * pendientes de la base de datos.
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
 * Clase que nos permite mapear la tabla pendientes a un objeto.
 *
 * Clase que nos permite mapear la tabla pendientes a un objeto
 * que utlizaremos luego para realizar operaciones de tipo
 * CRUD y otras sobre la tabla pendientes ubicada en la DB.
 *
 * @copyright  Copyright (c) 2015 Alvaro A. Guiffrey (http://www.alvaroguiffrey.com.ar)
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class PendienteVO
{
    #Propiedades
    private $_id;
    private $_idArticulo;
    private $_codigo;
    private $_codigoB;
    private $_idRubro;
    private $_idProveedor;
    private $_cantidad;
    private $_idPedido;
    private $_estado;
    private $_cantidadRec;
    private $_fechaRec;
    private $_comprobante;
    private $_comentario;
    private $_idUsuarioAct;
    private $_fechaAct;
    
    
    #Métodos
    /**
     * Nos permite obtener el identificador del pendiente.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->_id;
    }
    
    /**
     * Nos permite obtener el índice del artículo del pendiente.
     *
     * @return integer
     */
    public function getIdArticulo()
    {
        return $this->_idArticulo;
    }
    
    /**
     * Nos permite obtener el codigo del artículo del pendiente.
     *
     * @return bigint
     */
    public function getCodigo()
    {
        return $this->_codigo;
    }
    
    /**
     * Nos permite obtener el codigo de barra del artículo del pendiente.
     *
     * @return bigint
     */
    public function getCodigoB()
    {
        return $this->_codigoB;
    }
    
    /**
     * Nos permite obtener el indice del rubro del pendiente.
     *
     * @return int
     */
    public function getIdRubro()
    {
        return $this->_idRubro;
    }
    
    /**
     * Nos permite obtener el indice del proveedor del pendiente.
     *
     * @return integer
     */
    public function getIdProveedor()
    {
        return $this->_idProveedor;
    }

    /**
     * Nos permite obtener la cantidad pedida del pendiente.
     *
     * @return integer
     */
    public function getCantidad()
    {
        return $this->_cantidad;
    }

    /**
     * Nos permite obtener el indice del pedido del pendiente.
     *
     * @return integer
     */
    public function getIdPedido()
    {
        return $this->_idPedido;
    }
    
    /**
     * Nos permite obtener el estado del pendiente.
     *
     * @return int
     */
    public function getEstado()
    {
        return $this->_estado;
    }
    
    /**
     * Nos permite obtener la cantidad recibida del artículo.
     *
     * @return int
     */
    public function getCantidadRec()
    {
        return $this->_cantidadRec;
    }
    
    /**
     * Nos permite obtener la fecha de recibido el artículo.
     *
     * @return date
     */
    public function getFechaRec()
    {
        return $this->_fechaRec;
    }
    
    /**
     * Nos permite obtener el comprobante de recibido.
     *
     * @return varchar
     */
    public function getComprobante()
    {
        return $this->_comprobante;
    }
    
    /**
     * Nos permite obtener el comentario sobre el pendiente.
     *
     * @return varchar
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
     * Nos permite establecer el identificador del pendiente.
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }
    
    /**
     * Nos permite establecer el índice del artículo del pendiente.
     *
     * @param integer $idArticulo
     */
    public function setIdArticulo($idArticulo)
    {
        $this->_idArticulo = $idArticulo;
    }
    
    /**
     * Nos permite establecer el código del artículo del pendiente.
     *
     * @param bigint $codigo
     */
    public function setCodigo($codigo)
    {
        $this->_codigo = $codigo;
    }
    
    /**
     * Nos permite establecer el código de barra del artículo del pendiente.
     *
     * @param bigint $codigoB
     */
    public function setCodigoB($codigoB)
    {
        $this->_codigoB = $codigoB;
    }
    
    /**
     * Nos permite establecer el índice del rubro del pendiente.
     *
     * @param int $idRubro
     */
    public function setIdRubro($idRubro)
    {
        $this->_idRubro = $idRubro;
    }
    
    /**
     * Nos permite establecer el indice del proveedor del pendiente.
     *
     * @param int $idProveedor
     */
    public function setIdProveedor($idProveedor)
    {
        $this->_idProveedor = $idProveedor;
    }
    
    /**
     * Nos permite establecer la cantidad del pedido del pendiente.
     *
     * @param int $cantidad
     */
    public function setCantidad($cantidad)
    {
        $this->_cantidad = $cantidad;
    }

    /**
     * Nos permite establecer el indice del pedido del pendiente.
     *
     * @param int $idPedido
     */
    public function setIdPedido($idPedido)
    {
        $this->_idPedido = $idPedido;
    }
    
    /**
     * Nos permite establecer el estado del pendiente.
     *
     * @param int $estado
     */
    public function setEstado($estado)
    {
        $this->_estado = $estado;
    }
    
    /**
     * Nos permite establecer la cantidad recibida del artículo.
     *
     * @param int $cantidadRec
     */
    public function setCantidadRec($cantidadRec)
    {
        $this->_cantidadRec = $cantidadRec;
    }
    
    /**
     * Nos permite establecer la fecha de recibido el artículo.
     *
     * @param date $fechaRec
     */
    public function setFechaRec($fechaRec)
    {
        $this->_fechaRec = $fechaRec;
    }
 
    /**
     * Nos permite establecer el comprobante de recibido del artículo.
     *
     * @param varchar $comprobante
     */
    public function setComprobante($comprobante)
    {
        $this->_comprobante = $comprobante;
    }
    
    /**
     * Nos permite establecer el comentario del recibido del artículo.
     *
     * @param varchar $comentario
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