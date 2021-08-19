<?php
/**
 * Archivo de la clase ValueObject.
 *
 * Archivo de la clase ReponerVO que nos permite mapear la
 * estructura de la tabla reposiciones en un objeto para poder
 * realizar operaciones de tipo CRUD u otras sobre la tabla
 * reposiciones de la base de datos.
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
 * Clase que nos permite mapear la tabla reposiciones a un objeto.
 *
 * Clase que nos permite mapear la tabla reposiciones a un objeto
 * que utlizaremos luego para realizar operaciones de tipo
 * CRUD y otras sobre la tabla reposiciones ubicada en la DB.
 *
 * @copyright  Copyright (c) 2015 Alvaro A. Guiffrey (http://www.alvaroguiffrey.com.ar)
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class ReponerVO
{
    #Propiedades
    private $_id;
    private $_codigo;
    private $_idRubro;
    private $_cantidad;
    private $_numeroRep;
    private $_fechaRep;
    private $_estado;
    private $_idUsuarioAct;
    private $_fechaAct;
    
    
    #Métodos
    /**
     * Nos permite obtener el identificador de la reposición.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->_id;
    }
    
    /**
     * Nos permite obtener el código del artículo de la reposición.
     *
     * @return integer
     */
    public function getCodigo()
    {
        return $this->_codigo;
    }
    
    /**
     * Nos permite obtener id del rubro del artículo de la reposición.
     *
     * @return int
     */
    public function getIdRubro()
    {
        return $this->_idRubro;
    }
    
    /**
     * Nos permite obtener la cantidad de la reposición.
     *
     * @return int
     */
    public function getCantidad()
    {
        return $this->_cantidad;
    }
    
    /**
     * Nos permite obtener el número de la reposición.
     *
     * @return int
     */
    public function getNumeroRep()
    {
        return $this->_numeroRep;
    }
    
    /**
     * Nos permite obtener la fecha de la reposición.
     *
     * @return DateTime
     */
    public function getFechaRep()
    {
        return $this->_fechaRep;
    }
    
    /**
     * Nos permite obtener el estado de la reposición.
     *
     * 0=Inactivo, 1=Activo, 9=Listado
     * @return int
     */
    public function getEstado()
    {
        return $this->_estado;
    }
    
    /**
     * Nos permite obtener Id del usuario que actualizó último.
     *
     * @return int
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
     * Nos permite establecer el identificador de la reposición.
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }
    
    /**
     * Nos permite establecer el código del artículo de la reposición.
     *
     * @param int $codigo
     */
    public function setCodigo($codigo)
    {
        $this->_codigo = $codigo;
    }
    
    /**
     * Nos permite establecer el Id del Rubro del articulo de la reposición.
     *
     * @param int $idRubro
     */
    public function setIdRubro($idRubro)
    {
        $this->_idRubro = $idRubro;
    }
    
    /**
     * Nos permite establecer la cantidad de la reposición.
     *
     * @param int $cantidad
     */
    public function setCantidad($cantidad)
    {
        $this->_cantidad = $cantidad;
    }
    
    /**
     * Nos permite establecer el número de la reposición.
     *
     * @param int $numeroRep
     */
    public function setNumeroRep($numeroRep)
    {
        $this->_numeroRep = $numeroRep;
    }
    
    /**
     * Nos permite establecer la fecha de la reposición.
     *
     * @param date $fechaRep
     */
    public function setFechaRep($fechaRep)
    {
        $this->_fechaRep = $fechaRep;
    }
    
    /**
     * Nos permite establecer el estado del artículo de la reposición.
     *
     * @param int $estado  0=Inactivo, 1=Activo, 9=Listado
     */
    public function setEstado($estado)
    {
        $this->_estado = $estado;
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