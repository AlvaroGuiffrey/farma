<?php
/**
 * Archivo de la clase ValueObject.
 *
 * Archivo de la clase CondicionVO que nos permite mapear la
 * estructura de la tabla condiciones en un objeto para poder
 * realizar operaciones de tipo CRUD u otras sobre la tabla
 * condiciones de la base de datos.
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
 * Clase que nos permite mapear la tabla condiciones a un objeto.
 *
 * Clase que nos permite mapear la tabla condiciones a un objeto
 * que utlizaremos luego para realizar operaciones de tipo
 * CRUD y otras sobre la tabla condiciones de la DB.
 *
 * @copyright  Copyright (c) 2015 Alvaro A. Guiffrey (http://www.alvaroguiffrey.com.ar)
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class CondicionVO
{
    #Propiedades
    private $_id;
    private $_idTipo;
    private $_nombre;
    private $_comentario;
    private $_cantidadUn;
    private $_cantidadPaga;
    private $_descuento;
    private $_cuota;
    private $_estado;
    private $_idUsuarioAct;
    private $_fechaAct;
    
    #Métodos
    /**
     * Nos permite obtener el identificador de la condición.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->_id;
    }
    
    
    /**
     * Nos permite obtener el identificador del tipo de la condición.
     *
     * @return integer
     */
    public function getIdTipo()
    {
        return $this->_idTipo;
    }
    
    /**
     * Nos permite obtener el nombre de la condición.
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->_nombre;
    }
    
    /**
     * Nos permite obtener el comentario sobre la condición.
     *
     * @return string
     */
    public function getComentario()
    {
        return $this->_comentario;
    }
    
    /**
     * Nos permite obtener la cantidad de unidades de la condición.
     *
     * @return integer
     */
    public function getCantidadUn()
    {
        return $this->_cantidadUn;
    }
    
    /**
     * Nos permite obtener la cantidad de unidades que paga de la condición.
     *
     * @return integer
     */
    public function getCantidadPaga()
    {
        return $this->_cantidadPaga;
    }
    
    /**
     * Nos permite obtener el descuento de venta de la condición.
     *
     * @return decimal(3,2)
     */
    public function getDescuento()
    {
        return $this->_descuento;
    }
    
    /**
     * Nos permite obtener las cuotas con tarjetas de la condición.
     *
     * @return integer
     */
    public function getCuota()
    {
        return $this->_cuota;
    }
    
     
    /**
     * Nos permite obtener el estado de la condición.
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
     * Nos permite establecer el identificador de la condición.
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }
    
    /**
     * Nos permite establecer el identificador del tipo de la condición.
     *
     * @param integer $idTipo
     */
    public function setIdTipo($idTipo)
    {
        $this->_idTipo = $idTipo;
    }
    
    /**
     * Nos permite establecer el nombre de la condición.
     *
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->_nombre = $nombre;
    }
    
    /**
     * Nos permite establecer comentario para la condición.
     *
     * @param string $comentario
     */
    public function setComentario($comentario)
    {
        $this->_comentario = $comentario;
    }
    
    /**
     * Nos permite establecer la cantidad de unidades de la condición.
     *
     * @param integer $cantidadUn
     */
    public function setCantidadUn($cantidadUn)
    {
        $this->_cantidadUn = $cantidadUn;
    }
    
    /**
     * Nos permite establecer la cantidad de unidades que paga en la condición.
     *
     * @param integer $cantidadPaga
     */
    public function setCantidadPaga($cantidadPaga)
    {
        $this->_cantidadPaga = $cantidadPaga;
    }
    
    /**
     * Nos permite establecer el descuento de venta de la condición.
     *
     * @param decimal(3,2) $descuento
     */
    public function setDescuento($descuento)
    {
        $this->_descuento = $descuento;
    }
    
    /**
     * Nos permite establecer las cuotas con tarjetas de la condición.
     *
     * @param integer $cuota
     */
    public function setCuota($cuota)
    {
        $this->_cuota = $cuota;
    }
    
    /**
     * Nos permite establecer el estado de la condición.
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