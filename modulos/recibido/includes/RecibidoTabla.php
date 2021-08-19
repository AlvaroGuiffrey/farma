<?php
/**
 * Archivo del include del módulo recibido.
 *
 * Archivo del include del módulo recibido que arma una tabla con todos
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
 * Clase del include del módulo recibido.
 *
 * Clase RecibidoTabla del módulo recibido que permite armar
 * una tabla con todos los registros para la vista listar.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class RecibidoTabla
{
	# Propiedades
	private $_icono;
	
	# Métodos
	/**
	* Nos permite crear y guardar un fichero html con una tabla de todos
	* los registros de recibidos para representar en la vista listar.
	*
	* @param $cantidad
	* @param $items array()
	* @param $accion
	* @param $proveedor
	* @param $comprobante
	*
	* @return tabla.html (file.html)
	*/
	static function armaTabla($cantidad, $items, $accion, $idProveedor, $proveedor, $fechaDesde, $fechaHasta)
	{
		// Establece el localismo para la moneda
		setlocale(LC_MONETARY, 'es_AR.UTF-8');
		// Abre archivo tabla.html (va a representar la consulta)
		$filename = "tabla.html";
		$handle = fopen($filename, "w");
		if (FALSE === $handle){
			exit("falla al abrir el archivo");
		}
		// Titulo y encabezado de la tabla
		fwrite($handle, '<table class="table table-hover">'.PHP_EOL);
		if ($accion == 'ConfirmarL'){
			fwrite($handle, '<caption>Listado de Comprobantes - Proveedor: '.$proveedor.' - Desde:'.$fechaDesde.' - Hasta:'.$fechaHasta.'</caption>'.PHP_EOL);
		// Pie de la tabla
			$renglon = '<tfoot><tr>
				<td><button type="submit" class="btn btn-default " name="bt_actualizar_listado" value="actualiza" data-toggle="tooltip" data-placement="bottom" title="Botón para actualizar el listado">
						<span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Actualizar</button></td>
				<td>Son: '.$cantidad.' comprobantes</td><td></td><td></td><td></td><td></td></tr></tfoot>'.PHP_EOL;
			fwrite($handle, $renglon);
		}
		if ($accion == 'ConfirmarB'){
			fwrite($handle, '<caption>Listado de Comprobantes Recibidos buscados</caption>'.PHP_EOL);
			// Pie de la tabla
			$renglon = '<tfoot><tr><td></td>
				<td>Son: '.$cantidad.' comprobantes</td>td></td><td></td><td></td><td></td></tr></tfoot>'.PHP_EOL;
			fwrite($handle, $renglon);
		}
		fwrite($handle, '<thead><tr><th title="Fecha del comprobante recibido">Fecha</th><th title="Comprobante recibido">Comprobante</th><th title="Importe total del comprobante">Imp.Total</th><th title="Indicador de consistencia de los importes del comprobante"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></th><th title="Proveedor emisor del comprobante"># Proveedor</th><th title="Acciones que puede realizar sobre la tabla">Acciones</th></tr></thead>'.PHP_EOL);

		// Cuerpo de la tabla
		If ($cantidad==0){
			fwrite($handle, '<tbody><tr><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody>'.PHP_EOL);
		}else{
			fwrite($handle, '<tbody>'.PHP_EOL);
			foreach ($items as $item)
			{
				$oProveedorVO = new ProveedorVO();
				$oProveedorVO->setId($item['id_proveedor']);
				$oProveedorModelo = new ProveedorModelo();
				$oProveedorModelo->find($oProveedorVO);
				if ($oProveedorModelo->getCantidad() == 0){
					$razonSocial = '';
				}else{
					$razonSocial = $oProveedorVO->getRazonSocial();
				}
				if($item['consistencia']==0){
					$renglon  = '<tr><td>'.$item['fecha'].'</td><td>'.$item['comprobante'].'</td><td style="text-align:right">'.money_format("%.2n", $item['total']).'</td><td><span class="glyphicon glyphicon-warning-sign icon-warning" aria-hidden="true" title="Inconsistente, ver renglones"></span></td><td>'.$item['id_proveedor'].'-'.$razonSocial.'</td>
						<td>
						<button type="button" class="btn btn-default btn-xs" value="Editar Comprobante" data-toggle="tooltip" data-placement="bottom" title="Botón para editar datos" onclick="javascrip:window.open(\'http://localhost/caro/modulos/recibido/indexV.php?accion=Editar&id='.$item['id'].'\',\'Editar\',\'width=600, height=500, top=100, left=100, menubar=0, toolbar=0, titlebar=1, location=0, scrollbars=1\'); void 0"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Editar</button>
						<button type="button" class="btn btn-default btn-xs" value="Ver Comprobante" data-toggle="tooltip" data-placement="bottom" title="Botón para ver datos" onclick="javascrip:window.open(\'http://localhost/caro/modulos/recibido/indexV.php?accion=Ver&id='.$item['id'].'\',\'Ver\',\'width=600, height=500, top=100, left=100, menubar=0, toolbar=0, titlebar=1, location=0, scrollbars=1\'); void 0"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Ver</button>
						<input type="hidden" name="accion" value="BuscarA">
						<button type="submit" class="btn btn-success btn-xs" name="bt_agregar_renglon" formaction="http://localhost/caro/modulos/partida/indexR.php" formmethod="post" value="'.$item['id'].'" title="Botón para agregar renglones" tabindex=53> 
 					    	<span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Agregar</button>';
				}else{
					$renglon  = '<tr><td>'.$item['fecha'].'</td><td>'.$item['comprobante'].'</td><td style="text-align:right">'.money_format("%.2n", $item['total']).'</td><td><span class="glyphicon glyphicon-ok icon-success" aria-hidden="true" title="Sumas de renglones Ok"></span></td><td>'.$item['id_proveedor'].'-'.$razonSocial.'</td>
						<td>
						<button type="button" class="btn btn-default btn-xs" value="Editar Comprobante" data-toggle="tooltip" data-placement="bottom" title="Botón para editar datos" onclick="javascrip:window.open(\'http://localhost/caro/modulos/recibido/indexV.php?accion=Editar&id='.$item['id'].'\',\'Editar\',\'width=600, height=500, top=100, left=100, menubar=0, toolbar=0, titlebar=1, location=0, scrollbars=1\'); void 0"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Editar</button>
						<button type="button" class="btn btn-default btn-xs" value="Ver Comprobante" data-toggle="tooltip" data-placement="bottom" title="Botón para ver datos" onclick="javascrip:window.open(\'http://localhost/caro/modulos/recibido/indexV.php?accion=Ver&id='.$item['id'].'\',\'Ver\',\'width=600, height=500, top=100, left=100, menubar=0, toolbar=0, titlebar=1, location=0, scrollbars=1\'); void 0"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Ver</button>';
				}			
				$renglon .=	'</td></tr>'.PHP_EOL;
				fwrite($handle, $renglon);
			}
			fwrite($handle, '<input type="hidden" name="proveedor" value="'.$idProveedor.'">'.PHP_EOL);
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