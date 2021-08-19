<?php
/**
 * Archivo de la clase ValueObject.
 *
 * Archivo de la clase CondicionTipoVO que nos permite mapear la
 * estructura de la tabla condiciones_tipos en un objeto para poder
 * realizar operaciones de tipo CRUD u otras sobre la tabla
 * condiciones_tipos de la base de datos.
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
 * Clase que nos permite mapear la tabla condiciones_tipos a un objeto.
 *
 * Clase que nos permite mapear la tabla condiciones_tipos a un objeto
 * que utlizaremos luego para realizar operaciones de tipo
 * CRUD y otras sobre la tabla condiciones_tipos ubicada en la DB.
 *
 * @copyright  Copyright (c) 2015 Alvaro A. Guiffrey (http://www.alvaroguiffrey.com.ar)
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class CondicionTipoVO
{
    #Propiedades
    private $_id;
    private $_nombre;
    private $_tipo;
    
    #Métodos
    /**
     * Nos permite obtener el índice de la condición de venta especial.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->_id;
    }
    
    /**
     * Nos permite obtener el nombre de la condición de venta especial.
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->_nombre;
    }
    
    /**
     * Nos permite obtener el tipo de la condición de venta especial.
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->_tipo;
    }
    
    /**
     * Nos permite establecer el índice de la condición de venta especial.
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }
    
    /**
     * Nos permite establecer el nombre de la condición de venta especial.
     *
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->_nombre = $nombre;
    }
    
    /**
     * Nos permite establecer el tipoa de la condición de venta especial.
     *
     * @param string $tipo
     */
    public function setTipo($tipo)
    {
        $this->_tipo = $tipo;
    }
}
?>