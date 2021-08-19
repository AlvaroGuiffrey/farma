<?php
/**
 * Archivo del include del módulo articulo.
 *
 * Archivo del include del módulo articulo que arma una tabla con todos
 * los registros seleccionados para la vista.
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
 * Clase ArticuloActTabla del módulo articulo que permite armar
 * una tabla con todos los registros seleccionados para la vista
 * de las tareas: actualizar y listar.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class ArticuloActTabla
{
    # Propiedades
    
    # Métodos
    /**
     * Nos permite crear y guardar un fichero html con una tabla de todos
     * los registros de articulos para representar en la vista.
     *
     * @param $items array()
     * @param $accion
     * @param $cantidad
     *
     * @return tabla.html (file.html)
     */
    static function armaTabla($items, $accion, $cantidad)
    {
        // Establece el localismo para la moneda
        //	setlocale(LC_MONETARY, 'es_AR.UTF-8');
        // Abre archivo tabla.html (va a representar la consulta)
        $filename = "tabla.html";
        $handle = fopen($filename, "w");
        if (FALSE === $handle){
            exit("falla al abrir el archivo");
        }
        
        // Titulo y pie de la tabla
        fwrite($handle, '<table class="table table-condensed">'.PHP_EOL);
        if ($accion == "ConfirmarL") {
            fwrite($handle, '<caption>Listado Informativo de Artículos para Actualizar</caption>'.PHP_EOL);
        } else {
            fwrite($handle, '<caption>Listado de Artículos Actualizados</caption>'.PHP_EOL);
        }
        
               
        // Pie de la tabla
        $renglon = '<tfoot><tr>
			           <td></td>
                       <td>Son: '.$cantidad.' actualizados</td>
                       <td></td>
                       <td></td>
                    </tr></tfoot>'.PHP_EOL;
        fwrite($handle, $renglon);
        
        // Encabezado de los renglones
        fwrite($handle, '<thead><tr>
                            <th title="Índice del artículo">Id.</th>
                            <th title="Nombre del artículo">Nombre</th>
                            <th title="Código de barra del artículo">Código Barra</th>
                            <th title="Obsevaciones para el artículo">Observaciones</th>
                          </tr></thead>'.PHP_EOL);
        
        // Renglones/modulos/pendiente/index.php de la tabla
        If ($cantidad==0){
            fwrite($handle, '<tbody><tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                             </tr></tbody>'.PHP_EOL);
        }else{
            fwrite($handle, '<tbody>'.PHP_EOL);
            foreach ($items as $item)
            {
                // Color del renglón de acuerdo a la variación del precio
                if ($item['baja'] < 0){
                    $claseTR = 'class="success"';
                    $claseButton = 'class="btn btn-success btn-xs"';
                    $porcentaje = $item['baja']."%";
                    $glyphiconPorc = 'class="glyphicon glyphicon-arrow-down" style="color:white"';
                } else {
                    $claseTR = 'class="warning"';
                    $claseButton = 'class="btn btn-danger btn-xs"';
                    $porcentaje = $item['aumenta']."%";
                    $glyphiconPorc = 'class="glyphicon glyphicon-arrow-up" style="color:white"';
                }
                $precios = " ";
                if ($item['precioAnt'] > 0){
                    $precios .= "Ant: $ ".$item['precioAnt']." / ";
                } 
                if ($item['precio'] > 0){
                    $precios .= "Act: $ ".$item['precio'];
                }
                                
                
                $renglon  = '<tr '.$claseTR.'style="margin:0">
						<td style="text-align:left; margin:0" title="Índice del artículo">'.$item['id'].'</td>
						<td style="text-align:left; margin:0" title="'.$item['presentacion'].'"><b>'.$item['nombre'].'</b></td>
                        <td style="text-align:center; margin:0" title="Código de barra del artículo">'.$item['codigoB'].'</td>
						<td style="text-align:left; margin:0">
                            <button type="button" class="btn btn-light btn-xs" title="'.$precios.'"><span class="glyphicon glyphicon-usd style="color:grey"" aria-hidden="true"></span></button>
                            <button type="button" '.$claseButton.' title="'.$porcentaje.'"><span '.$glyphiconPorc.' aria-hidden="true"></span></button>
							<span class="glyphicon glyphicon-tag" style="color:orange; margin-left:5px" title="'.$item['proveedorRef'].'" aria-hidden="true"></span>';
                if ($item['rotulo'] > 0) {
                    $renglon .= '<span class="glyphicon glyphicon-modal-window" style="color:blue; margin-left:5px" title="con Rótulo" aria-hidden="true"></span>';
                }
                if ($item['condicion'] != "NO") {
                    $renglon .= '<button type="button" class="btn btn-warning btn-xs" title="'.$item['condicion'].'" style="margin-left:5px"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span></button>';
                }
				$renglon .= '</td></tr>'.PHP_EOL;
                fwrite($handle, $renglon);
            }
            fwrite($handle, '</tbody>'.PHP_EOL);
            
        }
        // Cierra la tabla
        fwrite($handle, '</table>'.PHP_EOL);
        
        // Cierra el archivo tabla.html
        fclose($handle);
    }
}
?>