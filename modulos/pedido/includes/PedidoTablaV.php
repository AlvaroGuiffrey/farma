<?php
/**
 * Archivo del include del módulo pedido.
 *
 * Archivo del include del módulo pedido que arma una tabla con todos
 * los registros seleccionados para la ventana.
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
 * Clase del include del módulo pedido.
 *
 * Clase PedidoTablaV del módulo pedido que permite armar
 * una tabla con todos los registros seleccionados para la ventana
 * que permite ver los pedidos de artículos.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class PedidoTablaV
{
    # Propiedades
    
    # Métodos
    /**
     * Nos permite crear y guardar un fichero html con una tabla de todos
     * los registros de un pedido para representar en la vista de la ventana ver.
     *
     * @param $items array()
     * @param $cantidad
     * @param $accion
     *
     * @return tabla.html (file.html)
     */
    static function armaTabla($items, $cantidad, $accion)
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
        fwrite($handle, '<caption>Productos del Pedido</caption>'.PHP_EOL);
        
        // Pie de la tabla
        $renglon = '<tfoot><tr>
			           <td></td>
                       <td></td>
                       <td>Son: '.$cantidad.' productos pedidos</td>
                       <td></td>
                       <td></td>
                    </tr>
                    </tfoot>'.PHP_EOL;
        fwrite($handle, $renglon);
        
        // Encabezado de los renglones
        fwrite($handle, '<thead><tr>
                            <th title="Código de barra del producto">Código Barra</th>
                            <th title="Código del proveedor del producto">Código Prov.</th>
                            <th title="Nombre y presentación del producto">Nombre Producto y Presentación</th>
                            <th title="Cantidad del producto a pedir">Cant.</th>
                            <th title="Estado del producto pedido" style="text-align:center"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></th>
                         </tr></thead>'.PHP_EOL);
        
        // Renglones/modulos/pedido/index.php de la tabla
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
                // busca glyphicon para estado
                switch ($item['estado']){
                    case '1':
                        $estado = '<span class="glyphicon glyphicon-edit" style="color:yellow" aria-hidden="true" title="Producto pendiente de pedido"></span>';
                        break;
                    case '2':
                        $estado = '<span class="glyphicon glyphicon-list-alt" style="color:gray" aria-hidden="true" title="Producto pedido sin recibir"></span>';
                        break;   
                    case '3':
                        $estado = '<span class="glyphicon glyphicon-check" style="color:green" aria-hidden="true" title="Producto recibido"></span>';
                        break;
                    case '4':
                        $estado = '<span class="glyphicon glyphicon-remove-circle" style="color:red" aria-hidden="true" title="Producto faltante"></span>';
                        break;
                    default:
                        $estado = "";
                }
                
                
                // renglon de los datos principales y opciones
                $renglon  = '<tr style="margin:0">
						<td style="text-align:left; margin:0">'.$item['codigo_b'].'</td>
                        <td style="text-align:left; margin:0">'.$item['codigo_p'].'</td>
						<td style="text-align:left; margin:0"><b>'.$item['nombre'].'</b></td>
                        <td style="text-align:center; margin:0">'.$item['cantidad'].'</td>
			            <td>'.$estado.'</td>
                            </tr>'.PHP_EOL;
                fwrite($handle, $renglon);

                 // renglon de la presentación
                 if ($item['presentacion'] = ""){
                      $renglon  = '<tr>
                          <td></td>
                          <td></td>
                          <td style="text-align:left"><p style="font-size:10px; margin:0px ">'.$item['presentacion'].'</p></td>
                          <td></td>
                          <td></td>
                      </tr>'.PHP_EOL;
                      fwrite($handle, $renglon);
                 }

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