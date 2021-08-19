<?php
/**
 * Archivo del include del módulo rótulo.
 *
 * Archivo del include del módulo rótulo que arma una tabla con todos
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
 * Clase del include del módulo rótulo.
 *
 * Clase RotuloTabla del módulo rótulo que permite armar
 * una tabla con todos los registros seleccionados para la vista listar
 * que permite confirmar los rótulos de artículos a descargar.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class RotuloTabla
{
	# Propiedades

	# Métodos
	/**
	* Nos permite crear y guardar un fichero html con una tabla de todos
	* los registros de rótulos para representar en la vista listar.
	*
	* @param $items array()
	* @param $cantidad
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
		fwrite($handle, '<table class="table table-hover">'.PHP_EOL);
		if ($accion=='Descartar'){
			fwrite($handle, '<caption>Listado de <b>Rótulos a descartar</b></caption>'.PHP_EOL);
		} else {
			fwrite($handle, '<caption>Listado de Rótulos</caption>'.PHP_EOL);
		}
		// Pie de la tabla
		$renglon = '<tfoot><tr>
			<td></td><td>Son: '.$cantidad.' rótulos</td><td></td><td></td></tr>
			</tfoot>'.PHP_EOL;
		fwrite($handle, $renglon);
		
		// Encabezado de los renglones
		fwrite($handle, '<thead><tr><th title="Código de barra del artículo">Código Barra</th><th title="Nombre del artículo">Nombre</th><th title="Presentación del artículo">Presentación</th><th title="Seleccione las acciones para el rótulo del artículo">Acciones</th></tr></thead>'.PHP_EOL);

		// Renglones/modulos/rótulo/index.php de la tabla
		If ($cantidad==0){
			fwrite($handle, '<tbody><tr><td></td><td></td><td></td><td></td></tr></tbody>'.PHP_EOL);
		}else{
			fwrite($handle, '<tbody>'.PHP_EOL);
			foreach ($items as $item)
			{
				if ($item['rotulo']==3){
					$checked1 = "";
					$checked2 = "";
					$checked3 = "checked";
				} else {
					$checked1 = "";
					$checked2 = "";
					$checked3 = "";
				}
				if ($accion=='Descartar'){
					$renglon  = '<tr>
						<td>'.$item['codigo_b'].'</td>
						<td style="text-align:left">'.$item['nombre'].'</td>
						<td style="text-align:left">'.$item['presentacion'].'</td>
						<td style="text-align:left">
							<input type="hidden" name="rotulos['.$item['id'].']" value="1">	
							<i class="glyphicon glyphicon-trash" style="color:red" title="Descarta reserva rótulo"></i>
						</td></tr>'.PHP_EOL;
				} else {
					$renglon  = '<tr>
						<td>'.$item['codigo_b'].'</td>
						<td style="text-align:left">'.$item['nombre'].'</td>
						<td style="text-align:left">'.$item['presentacion'].'</td>
						<td style="text-align:left">
								
							<input type="radio" name="rotulos['.$item['id'].']" value="3" '.$checked3.' title="Descarga rótulo en PDF">
							<i class="glyphicon glyphicon-file" style="color:blue"></i>
							<input type="radio" name="rotulos['.$item['id'].']" value="2" '.$checked2.' title="Elimina rótulo de la descarga PDF">
							<i class="glyphicon glyphicon-remove-sign" style="color:red"></i>
							<input type="radio" name="rotulos['.$item['id'].']" value="1" '.$checked1.' title="Descarta reserva rótulo">
							<i class="glyphicon glyphicon-trash" style="color:red"></i>	
														
						</td></tr>'.PHP_EOL;
				}
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