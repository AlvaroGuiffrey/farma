<?php
/**
 * Archivo del include del módulo reponer.
 *
 * Archivo del include del módulo reponer que arma una tabla con todos
 * los registros de reposiciones listadas para la vista listar.
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
 * Clase del include del módulo reponer.
 *
 * Clase ReponerNumeroTabla del módulo reponer que permite armar
 * una tabla con todos los registros de reposiciones listadas 
 * para la vista listar.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class ReponerNumeroTabla
{
    # Propiedades
    private $_icono;
    
    # Métodos
    /**
     * Nos permite crear y guardar un fichero html con una tabla de todos
     * los registros de reposiciones listadas para representar en la vista listar.
     *
     * @param $cantidad
     * @param $items array()
     * @param $accion
     * @param $fechaDesde
     * @param $fechaHasta
     *
     * @return tabla.html (file.html)
     */
    static function armaTabla($cantidad, $items, $accion, $fechaDesde, $fechaHasta)
    {
        
        // Establece el localismo para la moneda
        // setlocale(LC_MONETARY, 'es_AR.UTF-8');
        // Abre archivo tabla.html (va a representar la consulta)
        $filename = "tabla.html";
        $handle = fopen($filename, "w");
        if (FALSE === $handle){
            exit("falla al abrir el archivo");
        }
        
        // Titulo y encabezado de la tabla
        fwrite($handle, '<table class="table table-hover">'.PHP_EOL);
        if ($accion == "Descargar") {
            fwrite($handle, '<caption>Listado de Reposiciones para Descargar</caption>'.PHP_EOL);
        } else {
            fwrite($handle, '<caption>Listado de Reposiciones - Desde: '.$fechaDesde.' - Hasta: '.$fechaHasta.'</caption>'.PHP_EOL);
        }
        // Pie de la tabla
        if ($cantidad > 1){
            $datoPie = "reposiciones";
        } else {
            $datoPie = "reposición";
        }
        $renglon = '<tfoot><tr>
		      	        <td></td>
                        <td>Son: '.$cantidad.' '.$datoPie.'</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr></tfoot>'.PHP_EOL;
        fwrite($handle, $renglon);
        
        // Encabezado de renglones
        fwrite($handle, '<thead><tr>
                            <th title="Número de la reposición">Número</th>
                            <th title="Fecha del listado de la reposición">Fecha Reposición</th>
                            <th title="Cantidad de artículos de la reposición" style="text-align:center">Artículos</th>
                            <th title="Cantidad de unidades de la reposición" style="text-align:center">Unidades</th>
                            <th title="Acciones que puede realizar sobre la tabla">Acciones</th>
                         </tr></thead>'.PHP_EOL);
        
        // Cuerpo de la tabla
        If ($cantidad==0){
            fwrite($handle, '<tbody><tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                             </tr></tbody>'.PHP_EOL);
        }else{
            fwrite($handle, '<tbody>'.PHP_EOL);
            foreach ($items as $item)
            {
                $renglon  = '<tr style="margin:0">
                                <td>'.$item['numero_rep'].'</td>
                                <td>'.$item['fecha_rep'].'</td>
                                <td style="text-align:center">'.$item['cant_articulos'].'</td>
                                <td style="text-align:center">'.$item['cant_unidades'].'</td>
                                <td>
						           <a target="_blank" href="/farma/modulos/reponer/includes/descargaReposicion.php?id='.$item['numero_rep'].'&accion='.$accion.'"><button type="button" class="btn btn-default btn-xs" name="bt_descargar" data-toggle="tooltip" data-placement="bottom" title="Botón para descargar datos en PDF" tabindex=51><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Descargar PDF</button></a>
                                </td>
                             </tr>'.PHP_EOL;
                fwrite($handle, $renglon);
            }
            fwrite($handle, '<input type="hidden" name="fechaDesde" value="'.$fechaDesde.'">'.PHP_EOL);
            fwrite($handle, '<input type="hidden" name="fechaHasta" value="'.$fechaHasta.'">'.PHP_EOL);
            fwrite($handle, '</tbody>'.PHP_EOL);
        }
        // Cierra la tabla
        fwrite($handle, '</table>'.PHP_EOL);
        
        // Cierra el archivo tabla.html
        fclose($handle);
        
    }
    
}
?>