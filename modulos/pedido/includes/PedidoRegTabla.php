<?php
/**
 * Archivo del include del módulo pedido.
 *
 * Archivo del include del módulo pedido que arma una tabla con todos
 * los registros para la vista listar.
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
 * Clase PedidoRegTabla del módulo pedido que permite armar
 * una tabla con todos los registros para la vista listar.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class PedidoRegTabla
{
	# Propiedades
	private $_icono;
	
	# Métodos
	/**
	* Nos permite crear y guardar un fichero html con una tabla de todos
	* los registros de pedidos para representar en la vista listar.
	*
	* @param $cantidad
	* @param $items array()
	* @param $accion
	* @param $idProveedor
	* @param $proveedor
	* @param $fechaDesde
	* @param $fechaHasta
	*
	* @return tabla.html (file.html)
	*/
	static function armaTabla($cantidad, $items, $accion, $idProveedor, $proveedorT, $fechaDesde, $fechaHasta)
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

			fwrite($handle, '<caption>Listado de Pedidos - Proveedor: '.$proveedorT.' - Desde:'.$fechaDesde.' - Hasta:'.$fechaHasta.'</caption>'.PHP_EOL);
		// Pie de la tabla
		$renglon = '<tfoot><tr>
				        <td><button type="submit" class="btn btn-default " name="bt_actualizar_listado" value="actualiza" data-toggle="tooltip" data-placement="bottom" title="Botón para actualizar el listado">
						      <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Actualizar</button></td>
				        <td></td>
                        <td>Son: '.$cantidad.' pedidos</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr></tfoot>'.PHP_EOL;
		fwrite($handle, $renglon);

		// Encabezado de renglones
		fwrite($handle, '<thead><tr>
                            <th title="Fecha del pedido">Fecha</th>
                            <th title="Número del pedido">Número</th>
                            <th title="Nombre del proveedor">Proveedor</th>
                            <th title="Canal que se realizó pedido" style="text-align:center">Canal</th>
                            <th title="Estado del pedido" style="text-align:center"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></th>
                            <th title="Fecha de recibido">Fecha Rec.</th>
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
                                    <td></td>
                                    <td></td>
                             </tr></tbody>'.PHP_EOL);
		}else{
			fwrite($handle, '<tbody>'.PHP_EOL);
			foreach ($items as $item)
			{
			    // busca glyphicon para canal
			    switch ($item['canal']){
			        case 'Telefonico':
			            $canal = '<span class="glyphicon glyphicon-phone-alt" style="color:blue" aria-hidden="true" title="Pedido telefónico"></span>';
			            break;
			        case 'CardShop':
			            $canal = '<span class="glyphicon glyphicon-shopping-cart" style="color:blue" aria-hidden="true" title="Pedido por página web"></span>';
			            break;
			        case 'Email':
			            $canal = '<span class="glyphicon glyphicon-send" style="color:blue" aria-hidden="true" title="Pedido por Email"></span>';
			            break;
			        case 'Visitador':
			            $canal = '<span class="glyphicon glyphicon-briefcase" style="color:blue" aria-hidden="true" title="Pedido al visitador del proveedor"></span>';
			            break;
			        case 'en Local':
			            $canal = '<span class="glyphicon glyphicon-home" style="color:blue" aria-hidden="true" title="Pedido en local del proveedor"></span>';
			            break;
			        default:
			            $canal = "";
			    }
			 
	            // busca glyphicon para estado
			    switch ($item['estado']){
			        case '1':
			            $estado = '<span class="glyphicon glyphicon-list-alt" style="color:gray" aria-hidden="true" title="Pedido sin recibir"></span>';
			            break;
			        case '2':
			            $estado = '<span class="glyphicon glyphicon-edit" style="color:yellow" aria-hidden="true" title="Pedido recibido parcial"></span>';
			            break;
			        case '3':
			            $estado = '<span class="glyphicon glyphicon-check" style="color:green" aria-hidden="true" title="Pedido recibido"></span>';
			            break;
			        default:
			            $estado = "";
			    }
			     
    			$renglon  = '<tr style="margin:0">
                                <td>'.$item['fecha'].'</td>
                                <td>'.$item['id'].'</td>
                                <td>'.substr($item['proveedor'], 0, 30).'</td>
                                <td style="text-align:center">'.$canal.'</td>
                                <td style="text-align:center">'.$estado.'</td>
                                <td>'.$item['fecha_rec'].'</td>
						        <td> 
						          <button type="button" class="btn btn-default btn-xs" value="Ver" data-toggle="tooltip" data-placement="bottom" title="Botón para ver datos del pedido" onclick="javascrip:window.open(\'http://localhost/farma/modulos/pedido/indexV.php?accion=Ver&id='.$item['id'].'\',\'Ver\',\'width=600, height=500, top=100, left=100, menubar=0, toolbar=0, titlebar=1, location=0, scrollbars=1\'); void 0"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Ver</button>
							      <a target="_blank" href="/farma/modulos/pedido/includes/descargaPedido.php?id='.$item['id'].'"><button type="button" class="btn btn-default btn-xs" name="bt_descargar" data-toggle="tooltip" data-placement="bottom" title="Botón para descargar datos en PDF" tabindex=51><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Descargar PDF</button></a> 
                                </td>
                             </tr>'.PHP_EOL;
				fwrite($handle, $renglon);
			}
			
			fwrite($handle, '<input type="hidden" name="proveedor" value="'.$proveedorT.'">'.PHP_EOL);
			fwrite($handle, '<input type="hidden" name="idProveedor" value="'.$idProveedor.'">'.PHP_EOL);
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