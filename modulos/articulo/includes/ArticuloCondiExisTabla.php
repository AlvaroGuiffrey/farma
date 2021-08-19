<?php
/**
 * Archivo del include del módulo articulo.
 *
 * Archivo del include del módulo articulo que arma una tabla con todos
 * los registros de articulos_condi con condiciones vigentes de un artículo
 * para la vista.
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
 * Clase del include del módulo articulo.
 *
 * Clase ArticuloCondiExisTabla del módulo articulo que permite armar
 * una tabla con todas las condiciones vigentes existentes de un articulo
 * para la vista.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class ArticuloCondiExisTabla
{
    # Propiedades
    
    # Métodos
    /**
     * Nos permite crear y guardar un fichero html con una tabla de todos
     * los registros los articulos_condi con las condiciones vigentes existentes de un artículo
     * para representar en la vista.
     *
     * @param $items array()
     * @param int $cantListado
     *
     * @return tabla.html (file.html)
     */
    static function armaTabla($items, $idCondicion, $cantListado)
    {
        // Abre archivo tabla.html (va a representar la consulta)
        $filename = "tabla.html";
        $handle = fopen($filename, "w");
        if (FALSE === $handle){
            exit("falla al abrir el archivo");
        }
        
        // Titulo y pie de la tabla
        fwrite($handle, '<table class="table table-hover">'.PHP_EOL);
        fwrite($handle, '<caption style="font-size: 11pt">Listado de Condiciones Existentes</caption>'.PHP_EOL);
        // Pie de la tabla
        $renglon = '<tfoot><tr style="font-size: 10pt">
		<td>Son: '.$cantListado.' condicion/es vigente/s existente/s.</td><td></td><td></td></tr></tfoot>'.PHP_EOL;
        fwrite($handle, $renglon);
        // Encabezado de los renglones
        fwrite($handle, '<thead><tr style="font-size: 11pt"><th title="Nombre de la condición de venta">Condición</th>
                                    <th title="Condición vigente hasta">Vigencia</th>
                                    <th title="Observación sobre la condición">Observación</th>
                        </tr></thead>'.PHP_EOL);
        // Renglones/modulos/articulo/tabla.php de la tabla
        If ($cantListado==0){
            fwrite($handle, '<tbody><tr><td></td><td></td><td></td></tr></tbody>'.PHP_EOL);
        }else{
            fwrite($handle, '<tbody>'.PHP_EOL);
            $cont = 0;
            
            foreach ($items as $item)
            {
                if ($item['idCondicion'] == $idCondicion) {
                    $observacion = "<span class='glyphicon glyphicon-remove-sign' style='color:red'><font color=red> <b>Condición EXISTENTE</b></span>";
                } else {
                    $observacion = "--"; 
                }

                $renglon  = '<tr">
                                    <td><span class="label label-default">'.$item['nombre'].'</span></td>
                                    <td>'.$item['fechaHasta'].'</td>
                                    <td>'.$observacion.'</td>
                             </tr>'.PHP_EOL;
                fwrite($handle, $renglon);
            }
        }
        // Cierra la tabla
        fwrite($handle, '</table>'.PHP_EOL);
        
        // Cierra el archivo tabla.html
        fclose($handle);
    }
}
?>