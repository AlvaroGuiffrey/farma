<?php
/**
 * Archivo del includes.
 *
 * Archivo del includes que nos permite ordenar un array multidimensional.
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
 */

/**
 * Clase que nos permite ordenar un array.
 *
 * Clase que nos permite ordenar un array multidimensional por diferentes columnas
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http:
 * @since      Class available since Release 1.0
 */

class ArrayOrdenadoPor
{
    /**
     * Nos permite ordenar un array multidimensional por diferentes columnas
     */
    
    # Propiedades
    
    # Metodos
    
    static function ordenaArray()
    {
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field){
            if (is_string($field)) {
                $tmp = array();
                foreach ($data as $key => $row){
                    $tmp[$key] = $row[$field];
                }
                $args[$n] = $tmp;
            }
            
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return array_pop($args);
    }
}
?>