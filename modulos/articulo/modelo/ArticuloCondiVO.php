<?php
/**
 * Archivo de la clase ValueObject.
 *
 * Archivo de la clase ArticuloCondiVO que nos permite mapear la
 * estructura de la tabla articulos_condi en un objeto para poder
 * realizar operaciones de tipo CRUD u otras sobre la tabla
 * articulos_condi de la base de datos.
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
 * Clase que nos permite mapear la tabla articulos_condi a un objeto.
 *
 * Clase que nos permite mapear la tabla articulos_condi a un objeto
 * que utlizaremos luego para realizar operaciones de tipo
 * CRUD y otras sobre la tabla articulos_condi de la DB.
 *
 * @copyright  Copyright (c) 2015 Alvaro A. Guiffrey (http://www.alvaroguiffrey.com.ar)
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class ArticuloCondiVO
{
    #Propiedades
    private $_id;
    private $_idArticulo;
    private $_idCondicion;
    private $_fechaHasta;
    private $_rotulo;
    private $_estado;
    private $_idUsuarioAct;
    private $_fechaAct;
    
    #Métodos
    /**
     * Nos permite obtener el identificador del registro.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->_id;
    }
    
    /**
     * Nos permite obtener el identificador del artículo.
     *
     * @return integer
     */
    public function getIdArticulo()
    {
        return $this->_idArticulo;
    }
    
    
    /**
     * Nos permite obtener el identificador de la condición del artículo.
     *
     * @return integer
     */
    public function getIdCondicion()
    {
        return $this->_idCondicion;
    }
    
    /**
     * Nos permite obtener la fecha de vigencia de la condición del artículo.
     *
     * @return date
     */
    public function getFechaHasta()
    {
        return $this->_fechaHasta;
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
     * Nos permite establecer el identificador del registro de la tabla.
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }
    
    /**
     * Nos permite establecer el identificador del artículo.
     *
     * @param integer $idArticulo
     */
    public function setIdArticulo($idArticulo)
    {
        $this->_idArticulo = $idArticulo;
    }
    
    /**
     * Nos permite establecer el identificador de la condición del artículo.
     *
     * @param integer $idCondicion
     */
    public function setIdCondicion($idCondicion)
    {
        $this->_idCondicion = $idCondicion;
    }
    
    /**
     * Nos permite establecer la fecha de vigencia de la condición del articulo.
     *
     * @param date $fechaHasta
     */
    public function setFechaHasta($fechaHasta)
    {
        $this->_fechaHasta = $fechaHasta;
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
     * Nos permite establecer el estado del artículo.
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