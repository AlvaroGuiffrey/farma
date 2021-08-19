<?php
/**
 * Archivo del include del módulo condicion.
 *
 * Archivo del include del módulo condicion que arma una select html con todos
 * los registros para la vista.
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
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      File available since Release 1.0
 */

/**
 * Clase del include del módulo condicion.
 *
 * Clase CondicionSelect del módulo condicion que permite armar
 * una select html con todos los registros para la vista.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class CondicionSelect
{
    # Propiedades
    
    # Métodos
    /**
     * Nos permite crear y guardar un fichero html con una select de todos
     * los registros de condiciones para representar en la vista.
     *
     * @param $cantidad
     * @param $items array()
     * @param $accion
     * @param $id
     *
     * @return selectCondicion.html (file.html)
     */
    static function armaSelect($cantidad, $items, $accion, $id)
    {
        // Abre archivo select.html (va a representar la consulta)
        $filename = "selectCondicion.html";
        $handle = fopen($filename, "w");
        if (FALSE === $handle){
            exit("falla al abrir el archivo");
        }
        // Cuerpo del select
        fwrite($handle, '<select class="form-control" name="condicion">'.PHP_EOL);
        if ($accion == 'Listar'){
            $renglon  = '<option value="0">TODOS</option>'.PHP_EOL;
            fwrite($handle, $renglon);
        }
        foreach ($items as $item)
        {
            if ($accion == 'Editar'){
                if ($id == $item['id']){
                    $renglon  = '<option value="'.$item['id'].'" selected>'.$item['nombre'].'</option>'.PHP_EOL;
                }else{
                    $renglon  = '<option value="'.$item['id'].'">'.$item['nombre'].'</option>'.PHP_EOL;
                }
            }else{
                $renglon  = '<option value="'.$item['id'].'">'.$item['nombre'].'</option>'.PHP_EOL;
            }
            
            fwrite($handle, $renglon);
        }
        // Cierra el select y el archivo
        fwrite($handle, '</select>'.PHP_EOL);
        fclose($handle);
    }
}
?>