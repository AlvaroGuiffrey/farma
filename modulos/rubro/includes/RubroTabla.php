<?php
/**
 * Archivo del include del módulo rubro.
 *
 * Archivo del include del módulo rubro que arma una tabla con todos
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
 * Clase del include del módulo rubro.
 *
 * Clase RubroTabla del módulo rubro que permite armar
 * una tabla con todos los registros para la vista listar.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class RubroTabla
{
	# Propiedades
	
	# Métodos
	/**
	 * Nos permite crear y guardar un fichero html con una tabla de todos
	 * los registros de rubros para representar en la vista listar.
	 *
	 * @param $cantidad
	 * @param $items array()
	 *
	 * @return tabla.html (file.html)
	 */
	static function armaTabla($cantidad, $items)
	{
	// Abre archivo tabla.html (va a representar la consulta)
		$filename = "tabla.html";
		$handle = fopen($filename, "w");
		if (FALSE === $handle){
			exit("falla al abrir el archivo");
		}
	// Titulo y encabezado de la tabla
		fwrite($handle, '<table class="table table-hover">'.PHP_EOL);
		fwrite($handle, '<caption>Listado de Rubros</caption>'.PHP_EOL);
		fwrite($handle, '<thead><tr><th title="Identificador del rubro">#</th><th title="Nombre del rubro">Nombre</th><th title="Comentario sobre el rubro">Comentario</th><th title="Acciones que puede realizar sobre la tabla">Acciones</th></tr></thead>'.PHP_EOL);
	// Pie de la tabla
		$renglon = "<tfoot><tr><td></td><td>Son: ".$cantidad." rubros</td><td></td><td></td></tr></tfoot>".PHP_EOL;
		fwrite($handle, $renglon);
	// Cuerpo de la tabla
		If ($cantidad==0){
			fwrite($handle, '<tbody><tr><td></td><td></td><td></td><td></td></tr></tbody>'.PHP_EOL);
		}else{
			fwrite($handle, '<tbody>'.PHP_EOL);
			foreach ($items as $item)
			{
				$renglon  = '<tr><td>'.$item['id'].'</td><td>'.$item['nombre'].'</td><td>'.$item['comentario'].'</td>
							<td><button type="submit" class="btn btn-default btn-xs" name="bt_editar" value="'.$item['id'].'" data-toggle="tooltip" data-placement="bottom" title="Botón para editar datos">
							<span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Editar</button>
							<button type="submit" class="btn btn-default btn-xs" name="bt_ver" value="'.$item['id'].'" data-toggle="tooltip" data-placement="bottom" title="Botón para ver datos">
							<span class="glyphicon glyphicon-search" aria-hidden="true"></span> Ver</button>
							</td></tr>'.PHP_EOL;
				fwrite($handle, $renglon);
			}
			fwrite($handle, '</tbody>'.PHP_EOL);
		}
	// Cierra la tabla y el archivo
		fwrite($handle, '</table>');
		fclose($handle);
	}
}
?>