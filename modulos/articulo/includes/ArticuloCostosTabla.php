<?php
/**
 * Archivo del include del módulo articulo.
 *
 * Archivo del include del módulo articulo que arma una tabla con todos
 * los registros de productos con igual código de barra para la vista.
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
 * Clase ArticuloCostosTabla del módulo articulo que permite armar
 * una tabla con todos los productos con igual código de barra
 *  para la vista listar.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class ArticuloCostosTabla
{
	# Propiedades

	# Métodos
	/**
	* Nos permite crear y guardar un fichero html con una tabla de todos
	* los registros los productos con igual codigo de barra
	*  para representar en la vista listar.
	*
	* @param $items array()
	* @param $aProveedores array()
	* @param $aProveedoresLista arral()
	* @param int $cantListado
	*
	* @return tabla.html (file.html)
	*/
	static function armaTabla($items, $aProveedores, $aProveedoresLista, $cantListado)
	{
		// Abre archivo tabla.html (va a representar la consulta)
		$filename = "tabla.html";
		$handle = fopen($filename, "w");
		if (FALSE === $handle){
			exit("falla al abrir el archivo");
		}
		
		// Titulo y pie de la tabla
		fwrite($handle, '<table class="table table-hover">'.PHP_EOL);  
		fwrite($handle, '<caption>Listado de Productos equivalentes</caption>'.PHP_EOL);
		// Pie de la tabla
		$renglon = '<tfoot><tr><td></td>
		<td>Son: '.$cantListado.' productos equivalentes</td><td></td><td></td><td></td></tr></tfoot>'.PHP_EOL;
		fwrite($handle, $renglon);
		// Encabezado de los renglones
		fwrite($handle, '<thead><tr><th title="Iniciales del Proveedor">Pr</th><th title="Código del artículo asignado por proveedor">Código</th><th title="Nombre del producto asignado por proveedor">Nombre</th><th title="% de FLETE a nuestro cargo">Flete</th><th title="% de DESC. del Proveedor">Dcto.</th><th title="Costo (sin IVA) del producto">Costo</th></tr></thead>'.PHP_EOL);
		// Renglones/modulos/articulo/tabla.php de la tabla
		If ($cantListado==0){ 
			fwrite($handle, '<tbody><tr><td></td><td></td><td></td><td></td></tr></tbody>'.PHP_EOL);
		}else{
			fwrite($handle, '<tbody>'.PHP_EOL);
			foreach ($items as $item)
			{
				$inicialProveedor = $aProveedores[$item['id_proveedor']];
				$lista = $aProveedoresLista[$item['id_proveedor']];
				if ($lista == 'N'){
					$costo = $item['precio'];
				}else{
					if ($lista == 'F'){
						$costo = $item['precio'] / 1.21;
					}else{
						$costo = 0;
					}
				}
	/* ----------------------------------------------
	 * ARMO DESCUENTOS Y FLETES HASTA MODIFICAR SISTEMA
	 * ----------------------------------------------
	 */			 
				switch ($item['id_proveedor']){
				    case '2': // Del Sud
				        $desc = 5;
				        $flete = 0;
				        break;
				    case '3': // Nippon
				        $desc = 0;
				        $flete = 3.15;
				        break;
				    case '4': // Keller
				        $desc = 5;
				        $flete = 0;
				        break;
				    case '5': // CoopLitoral
				        $desc = 2;
				        $flete = 0;
				        break;
				    case '10': // CasaFlorian
				        $desc = 5;
				        $flete = 3.15;
				        break;
				    default:
				        $desc = 0;
				        $flete = 0;
				        break;
				}
				// calculo descuento -------
				if ($desc > 0) $costo = $costo * ((100 - $desc)/100);
				// --------------------------
				// calculo flete ------------
				If ($flete > 0) $costo = $costo + ($costo * $flete / 100);
				// ----------------------
	/* --------------------------------------------------
	 * FINAL CALCULO DESCUENTO Y FLETES
	 * -----------------------------------------------
	 */
				$renglon  = '<tr><td>'.$inicialProveedor.'</td><td>'.$item['codigo_p'].'</td><td>'.$item['nombre'].'</td><td>(+ '.$flete.'%)</td><td>(- '.$desc.'%)</td><td style="text-align:right"> $ '.number_format($costo, 2, ',', '.').'</td></tr>'.PHP_EOL;
				fwrite($handle, $renglon);
			}
		}
		// Cierra la tabla
		fwrite($handle, '</table>'.PHP_EOL);

		// Cierra el archivo tabla.html
		fclose($handle);
	}
}
?>