<?php
/**
 * Archivo del include del módulo pedido.
 *
 * Archivo del include del módulo pedido que arma una tabla con todos
 * los registros seleccionados para la vista pedir.
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
 * Clase PedidoTabla del módulo pedido que permite armar
 * una tabla con todos los registros seleccionados para la vista listar
 * que permite confirmar los pedidos de artículos a asignar.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class PedidoTabla
{
    # Propiedades
    
    # Métodos
    /**
     * Nos permite crear y guardar un fichero html con una tabla de todos
     * los registros de pedidos para representar en la vista pedir.
     *
     * @param $items array()
     * @param $cantidad
     * @param $accion
     * @param $proveedor
     *
     * @return tabla.html (file.html)
     */
    static function armaTabla($items, $cantidad, $accion, $proveedor, $canal)
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
        fwrite($handle, '<caption>Listado de Pedido ('.$canal.') - '.$proveedor.'</caption>'.PHP_EOL);
        
        // Pie de la tabla
        // Arma las cantidades para los totales
        $cantidadCero = $importe = 0;
        foreach ($items as $item)
        {
            // calculo importe del pedido
            if ($item['precio']>0){
                $importe = $importe + ($item['precio'] * $item['cantidad']);
            } else {
                $cantidadCero++;
            }
        }
        $renglon = '<tfoot><tr>
			           <td></td>
                       <td></td>
                       <td>Son: '.$cantidad.' productos pedidos</td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td>Valuados en $ '.$importe.' (sin IVA)</td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>';
        if ($cantidadCero > 0){
            $renglon = $renglon.'<tr>
                            <td></td>
                            <td></td>
                            <td>*** '.$cantidadCero.' productos sin valuar ***</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>';
            
        }
        $renglon .= '<input type="hidden" name="proveedor" value="'.$proveedor.'">
                       <input type="hidden" name="canal" value="'.$canal.'">
                    </tfoot>'.PHP_EOL;
        // Final prueba
        
        /*
         // Pie de la página
         $renglon = '<tfoot><tr>
         <td></td>
         <td></td>
         <td>Son: '.$cantidad.' productos pedidos</td>
         <td></td>
         <td></td>
         <td></td>
         </tr>
         <input type="hidden" name="proveedor" value="'.$proveedor.'">
         <input type="hidden" name="canal" value="'.$canal.'">
         </tfoot>'.PHP_EOL;
         */
        fwrite($handle, $renglon);
        
        // Encabezado de los renglones
        fwrite($handle, '<thead><tr>
                            <th title="Código de barra del producto">Código Barra</th>
                            <th title="Código del proveedor del producto">Código Prov.</th>
                            <th title="Nombre y presentación del producto">Nombre Producto y Presentación</th>
                            <th title="Precio del producto por unidad">Precio Un</th>
                            <th style="text-align:center;" title="Cantidad del producto a pedir">Cantidad</th>
                            <th title="Seleccione las acciones para el pedido del producto">Acciones</th>
                          </tr></thead>'.PHP_EOL);
        
        // Renglones/modulos/pedido/index.php de la tabla
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
            reset($items);
            foreach ($items as $item)
            {
                // Si tiene id de proveedor
                if ($item['id_proveedor'] > 0){
                    $checked = "checked";
                } else {
                    $checked = "";
                }
                // Si es proveedor de menor precio
                if ($item['proveedor_menor_precio'] == 1){
                    $claseTR = 'class="success"';
                } else {
                    $claseTR = '';
                }
                
                // renglon de los datos principales y opciones
                $renglon  = '<tr '.$claseTR.'style="margin:0">
						<td style="text-align:left; margin:0">'.$item['codigo_b'].'</td>
                        <td style="text-align:left; margin:0">'.$item['codigo_p'].'</td>
						<td style="text-align:left; margin:0"><b>'.$item['nombre'].'</b></td>
                        <td style="text-align:right; margin:0">$ '.$item['precio'].'</td>
                        <td>
                            <input class= "input-mini" type="number" name="cantidad['.$item['id'].']" value="'.$item['cantidad'].'" min="1" max="24" style="text-align:center">
                        </td>
                                
						<td style="text-align:left; margin:0">
                                
							<input type="radio" name="pedidos['.$item['id'].']" value="Pedido" '.$checked.' title="Pide al proveedor">
							<i class="glyphicon glyphicon-shopping-cart" style="color:blue; margin:0"></i>
							<input type="radio" name="pedidos['.$item['id'].']" value="Elimina" title="Elimina del pedido y vuelve a pendientes">
							<i class="glyphicon glyphicon-remove-sign" style="color:red; margin:0"></i>
							<input type="radio" name="pedidos['.$item['id'].']" value="Descarta" title="Descarta del pedido y de los pendientes">
							<i class="glyphicon glyphicon-trash" style="color:red; margin:0"></i>
							    
						</td>
							    
                            </tr>'.PHP_EOL;
                fwrite($handle, $renglon);
                /*
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
                 */
                // renglon de precios de proveedores
                $renglon  = '<tr>
						<td></td>
						<td colspan="4" style="text-align:left; font-size:12px; margin:0px">Precios Prov: '.$item['precios_proveedores'].'</td>
                        </tr>'.PHP_EOL;
                fwrite($handle, $renglon);
                // renglon de cantidades vendidas
                $renglon  = '<tr>
						<td></td>
						<td colspan="4" style="text-align:left; font-size:12px; margin:0px">Cant. vendidas: '.$item['cantidad_ventas'].'</td>
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