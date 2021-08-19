<?php
/**
 * Archivo del include del módulo pendiente.
 *
 * Archivo del include del módulo pendiente que arma una tabla con todos
 * los registros seleccionados para la vista asignar.
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
 * Clase del include del módulo pendiente.
 *
 * Clase PendienteTabla del módulo pendiente que permite armar
 * una tabla con todos los registros seleccionados para la vista listar
 * que permite confirmar los pendientes de artículos a asignar.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class PendienteTabla
{
    # Propiedades
    
    # Métodos
    /**
     * Nos permite crear y guardar un fichero html con una tabla de todos
     * los registros de pendientes para representar en la vista asignar.
     *
     * @param $items array()
     * @param $cantidad
     * @param $accion
     * @param $proveedor
     *
     * @return tabla.html (file.html)
     */
    static function armaTabla($items, $cantidad, $accion, $proveedor)
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
        fwrite($handle, '<caption>Listado de Pendientes - '.$proveedor.'</caption>'.PHP_EOL);
  
        // Pie de la tabla
        $renglon = '<tfoot><tr>
			           <td></td>
                       <td>Son: '.$cantidad.' pendientes</td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                       <input type="hidden" name="proveedor" value="'.$proveedor.'">
                    </tfoot>'.PHP_EOL;
        fwrite($handle, $renglon);

        // Encabezado de los renglones
        fwrite($handle, '<thead><tr>
                            <th title="Código de barra del artículo">Código Barra</th>
                            <th title="Nombre y presentación del artículo">Nombre y Presentación</th>
                            <th title="Producto existe?">?</th>
                            <th title="Rubro del artículo">Rubro</th>
                            <th title="Cantidad pendiente del artículo">Cant.</th>
                            <th title="Seleccione las acciones para el pendiente del artículo">Acciones</th>
                          </tr></thead>'.PHP_EOL);
        
        // Renglones/modulos/pendiente/index.php de la tabla
        If ($cantidad==0){
            fwrite($handle, '<tbody><tr>
                                <td></td>
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
                if ($item['proveedor_menor_precio'] == 1){
                    $claseTR = 'class="success"';
                } else {
                    $claseTR = 'class="warning"';
                }           
                if ($item['id_proveedor'] > 0){
                    $checked = "checked";
                    $color = "green";
                } else {
                    $checked = "";
                    $color = "blue";
                }
                if ($item['existe_producto'] > 0){
                    $glyphicon = 'class="glyphicon glyphicon-ok-circle" style="color:green"';
                } else{
                    $glyphicon = 'class="glyphicon glyphicon-ban-circle" style="color:red"';
                    $claseTR = 'class="danger"';
                }

                
                $renglon  = '<tr '.$claseTR.'style="margin:0">
						<td style="text-align:left; margin:0">'.$item['codigo_b'].'</td>
						<td style="text-align:left; margin:0"><b>'.$item['nombre'].'</b></td>
                        <td style="margin:0"><i '.$glyphicon.'></i></td>
						<td style="text-align:left; margin:0">'.$item['rubro'].'</td>
                        <td style="text-align:center; margin:0">'.$item['cantidad'].'</td>
						<td style="text-align:left; margin:0">
						    
							<input type="radio" name="pendientes['.$item['id'].']" value="Asigna" '.$checked.' title="Asigna pendiente al proveedor">
							<i class="glyphicon glyphicon-shopping-cart" style="color:'.$color.'; margin:0"></i>
							<input type="radio" name="pendientes['.$item['id'].']" value="Elimina" title="Elimina asignación del pendiente al proveedor">
							<i class="glyphicon glyphicon-remove-sign" style="color:red; margin:0"></i>
							<input type="radio" name="pendientes['.$item['id'].']" value="Descarta" title="Descarta pendiente">
							<i class="glyphicon glyphicon-trash" style="color:red; margin:0"></i>
							   
						</td></tr>'.PHP_EOL;
                fwrite($handle, $renglon);
                $renglon  = '<tr>
						<td></td>
						<td style="text-align:left"><p style="font-size:10px; margin:0px ">'.$item['presentacion'].'</p></td>
                        <td></td><td></td><td></td><td></td>
                        </tr>'.PHP_EOL;
                fwrite($handle, $renglon);
                $renglon  = '<tr>
						<td></td>
						<td colspan="5" style="text-align:left; font-size:12px; margin:0px">Precios Prov: '.$item['precios_proveedores'].'</td>
                        </tr>'.PHP_EOL;
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