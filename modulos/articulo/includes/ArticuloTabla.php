<?php
/**
 * Archivo del include del módulo articulo.
 *
 * Archivo del include del módulo articulo que arma una tabla con todos
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
 * Clase del include del módulo articulo.
 *
 * Clase ArticuloTabla del módulo articulo que permite armar
 * una tabla con todos los registros seleccionados para la vista listar.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class ArticuloTabla
{
	# Propiedades

	# Métodos
	/**
	* Nos permite crear y guardar un fichero html con una tabla de todos
	* los registros de articulos para representar en la vista listar.
	*
	* @param $items array()
	* @param $accion
	* @param $idMarca
	* @param $marca
	* @param $idRubro
	* @param $rubro 
	* @param $estado
	* @param $orden
	* @param $cantListado
	*
	* @return tabla.html (file.html)
	*/
	static function armaTabla($items, $accion, $idMarca, $nombreMarca, $idRubro, $nombreRubro, $estado, $orden, $origen, $nombreOrigen, $actualizaProv, $nombreActualizaProv, $cantListado)
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
		if ($accion == 'ConfirmarL'){
			fwrite($handle, '<caption>Listado de Articulos - Marca: '.$nombreMarca.' - Rubro: '.$nombreRubro.' - Origen: '.$nombreOrigen.' - Actualiza por: '.$nombreActualizaProv.'</caption>'.PHP_EOL);
			// Pie de la tabla
			$renglon = '<tfoot><tr>
				<td><button type="submit" class="btn btn-default " name="bt_actualizar_listado" value="actualiza" data-toggle="tooltip" data-placement="bottom" title="Botón para actualizar el listado">
						<span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Actualizar</button></td>
				<td>Son: '.$cantListado.' artículos en página</td><td></td><td></td><td></td></tr>
			</tfoot>'.PHP_EOL;
			fwrite($handle, $renglon);
		}
		if ($accion == 'ConfirmarB'){
			fwrite($handle, '<caption>Listado de Articulos buscados</caption>'.PHP_EOL);
			// Pie de la tabla
			$renglon = '<tfoot><tr><td></td>
				<td>Son: '.$cantListado.' artículos en página</td><td></td><td></td><td></td></tr></tfoot>'.PHP_EOL;
			fwrite($handle, $renglon);
		}	
		
		// Encabezado de los renglones
		fwrite($handle, '<thead><tr><th title="Código del artículo">Código</th><th title="Nombre del artículo">Nombre</th><th title="Presentación del artículo">Presentación</th><th title="Precio del artículo">Precio</th><th title="Acciones que puede realizar sobre la tabla">Acciones</th></tr></thead>'.PHP_EOL);
		
		// Renglones/modulos/articulo/index.php de la tabla
		If ($cantListado==0){
		fwrite($handle, '<tbody><tr><td></td><td></td><td></td><td></td><td></td></tr></tbody>'.PHP_EOL);
		}else{
		fwrite($handle, '<tbody>'.PHP_EOL); 
		foreach ($items as $item)
		{
			
			/* Renglón con money_format */
			//'<tr><td>'.$item['codigo'].'</td><td>'.$item['nombre'].'</td><td style="text-align:left">'.$item['presentacion'].'</td><td style="text-align:right">'.money_format("%.2n", $item['precio']).'</td>
			/* Renglón con number_format */
			$renglon  = '<tr><td>'.$item['codigo'].'</td><td>'.$item['nombre'].'</td><td style="text-align:left">'.$item['presentacion'].'</td><td style="text-align:right"> $ '.number_format($item['precio'], 2, ',', '.').'</td>
						<td>
						<button type="button" class="btn btn-default btn-xs" value="Editar Artículo" data-toggle="tooltip" data-placement="bottom" title="Botón para editar datos" onclick="javascrip:window.open(\'http://localhost/farma/modulos/articulo/indexV.php?accion=Editar&id='.$item['id'].'\',\'Editar\',\'width=600, height=500, top=100, left=100, menubar=0, toolbar=0, titlebar=1, location=0, scrollbars=1\'); void 0"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Editar</button>
						<button type="button" class="btn btn-default btn-xs" value="Ver Artículo" data-toggle="tooltip" data-placement="bottom" title="Botón para ver datos" onclick="javascrip:window.open(\'http://localhost/farma/modulos/articulo/indexV.php?accion=Ver&id='.$item['id'].'\',\'Ver\',\'width=600, height=500, top=100, left=100, menubar=0, toolbar=0, titlebar=1, location=0, scrollbars=1\'); void 0"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Ver</button>
						</td></tr>'.PHP_EOL;
			fwrite($handle, $renglon);
		} 
		fwrite($handle, '<input type="hidden" name="marca" value="'.$idMarca.'">'.PHP_EOL);
		fwrite($handle, '<input type="hidden" name="nombreMarca" value="'.$nombreMarca.'">'.PHP_EOL);
		fwrite($handle, '<input type="hidden" name="rubro" value="'.$idRubro.'">'.PHP_EOL);
		fwrite($handle, '<input type="hidden" name="nombreRubro" value="'.$nombreRubro.'">'.PHP_EOL);
		fwrite($handle, '<input type="hidden" name="estado" value="'.$estado.'">'.PHP_EOL);
		fwrite($handle, '<input type="hidden" name="orden" value="'.$orden.'">'.PHP_EOL);
		fwrite($handle, '<input type="hidden" name="origen" value="'.$origen.'">'.PHP_EOL);
		fwrite($handle, '<input type="hidden" name="actualizaProv" value="'.$actualizaProv.'">'.PHP_EOL);
		fwrite($handle, '</tbody>'.PHP_EOL);

		}
	// Cierra la tabla 
		fwrite($handle, '</table>'.PHP_EOL);
		
	// Cierra el archivo tabla.html
		fclose($handle);
	}
	}
	?>