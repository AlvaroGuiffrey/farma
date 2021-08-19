<?php
/**
 * Archivo del include del módulo proveedor.
 *
 * Archivo del include del módulo proveedor que arma una tabla con todos
 * los registros seleccionados para la vista listar.
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
 * Clase del include del módulo proveedor.
 *
 * Clase ProveedorTabla del módulo proveedor que permite armar
 * una tabla con todos los registros seleccionados para la vista listar.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class ProveedorTabla
{
	# Propiedades

	# Métodos
	/**
	* Nos permite crear y guardar un fichero html con una tabla de todos
	* los registros de proveedores para representar en la vista listar.
	*
	* @param $cantidad
	* @param $items array()
	* @param $accion
	*
	* @return tabla.html (file.html)
	*/
	static function armaTabla($cantidad, $items, $accion)
	{
		// Abre archivo tabla.html (va a representar la consulta)
		$filename = "tabla.html";
		$handle = fopen($filename, "w");
		if (FALSE === $handle){
			exit("falla al abrir el archivo");
		}
		// Titulo de la tabla
		fwrite($handle, '<table class="table table-hover">'.PHP_EOL);
		if ($accion == 'Listar'){
			fwrite($handle, '<caption>Listado de Proveedores</caption>'.PHP_EOL);
		}
		if ($accion == 'ConfirmarB'){
			fwrite($handle, '<caption>Listado de Proveedores buscados</caption>'.PHP_EOL);
		}
		// Pie de la tabla
		$renglon = '<tfoot><tr>
							<td></td>
							<td>Son: '.$cantidad.' proveedores</td>
							<td></td>
							<td></td>
						</tr></tfoot>'.PHP_EOL;
		fwrite($handle, $renglon);
		// Encabezado de la tabla
		fwrite($handle, '<thead><tr>
							<th title="Indice del proveedor">#</th>
							<th title="Razón Social del proveedor">Razón Social</th>
							<th title="Inicial que identifica al proveedor">Inicial</th>
							<th title="Acciones que puede realizar sobre la tabla">Acciones</th>
						</tr></thead>'.PHP_EOL);

		// Cuerpo de la tabla
		If ($cantidad==0){
			fwrite($handle, '<tbody><tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
					         </tr>'.PHP_EOL);
		}else{
			fwrite($handle, '<tbody>'.PHP_EOL);
			foreach ($items as $item)
			{
				$renglon  = '<tr>
								<td>'.$item['id'].'</td>
								<td style="text-align:left">'.$item['razon_social'].'</td>
								<td style="text-align:center">'.$item['inicial'].'</td>
								<td>
									<button type="submit" class="btn btn-default btn-xs" name="bt_editar" value="'.$item['id'].'" data-toggle="tooltip" data-placement="bottom" title="Botón para editar datos">
										<span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Editar</button>
									<button type="submit" class="btn btn-default btn-xs" name="bt_ver" value="'.$item['id'].'" data-toggle="tooltip" data-placement="bottom" title="Botón para ver datos">
										<span class="glyphicon glyphicon-search" aria-hidden="true"></span> Ver</button>';
				if ($item['inicial']!='' || $item['inicial']!=null ){
					$renglon .= ' <button type="submit" class="btn btn-warning btn-xs" name="bt_etiquetar" value="'.$item['id'].'" data-toggle="tooltip" data-placement="bottom" title="Botón para etiquetar artículos con proveedor">
										<span class="glyphicon glyphicon-tag" aria-hidden="true"></span> Etiquetar</button>									
								</td>';
				}				
				$renglon .= '</tr>'.PHP_EOL;
				fwrite($handle, $renglon);
			}

		}
		// Datos necesarios a enviar por hidden
		// No se envian datos por hidden
		fwrite($handle, '</tbody>'.PHP_EOL);
		// Cierra la tabla
		fwrite($handle, '</table>'.PHP_EOL);

		// Cierra el archivo tabla.html
		fclose($handle);
	}
}
?>