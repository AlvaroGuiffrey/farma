<?php
/**
 * Archivo del include del módulo partida.
 *
 * Archivo del include del módulo partida que arma una tabla con todos
 * los registros de renglones de comprobantes recibidos para la vista.
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
 * Clase del include del módulo partida.
 *
 * Clase RenglonTabla del módulo partida que permite armar
 * una tabla con todos los registros para la vista.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class RenglonTabla
{
	# Propiedades

	# Métodos
	/**
	* Nos permite crear y guardar un fichero html con una tabla de todos
	* los registros de partidas para representar en la vista.
	*
	* @param int $cantidad
	* @param array $items
	* @param string $accion
	* @param int $idRecibido
	* @param double $sumaNetos
	* @param double $netoRenglon
	*
	* @return tabla.html (file.html)
	*/
	static function armaTabla($cantidad, $items, $accion, $idRecibido, $sumaNetos, $netoRenglon)
	{
	// Abre archivo tabla.html (va a representar la consulta)
	$filename = "tabla.html";
			$handle = fopen($filename, "w");
			if (FALSE === $handle){
			exit("falla al abrir el archivo");
			} 
			// Titulo y encabezado de la tabla
			fwrite($handle, '<table class="table table-hover">'.PHP_EOL);
			fwrite($handle, '<caption>Renglones</caption>'.PHP_EOL);
			fwrite($handle, '<thead><tr><th title="Nombre del artículo del renglon">Artículo</th><th title="Marca del artículo">Marca</th><th title="Cantidad de unidades">Cantidad</th><th title="Precio de costo del artículo">Costo Un.</th><th title="Importe del renglón">Importe</th><th title="Acciones que puede realizar sobre la tabla">Acciones</th></tr></thead>'.PHP_EOL);
		
			// Cuerpo de la tabla
			If ($cantidad==0){
				$diferencia = $sumaNetos - $netoRenglon;
				fwrite($handle, '<tbody><tr><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody>'.PHP_EOL);
			}else{
				fwrite($handle, '<tbody>'.PHP_EOL);
				foreach ($items as $item)
				{
					$oArticuloVO = new ArticuloVO();
					$oArticuloVO->setId($item['id_articulo']);
					$oArticuloModelo = new ArticuloModelo();
					$oArticuloModelo->find($oArticuloVO);
					if ($oArticuloModelo->getCantidad() == 0) $oArticuloVO->setNombre('SIN NOMBRE');
					
					$oMarcaVO = new MarcaVO();
					$oMarcaVO->setId($oArticuloVO->getIdMarca());
					$oMarcaModelo = new MarcaModelo();
					$oMarcaModelo->find($oMarcaVO);
					if ($oMarcaModelo->getCantidad() == 0) $oMarcaVO->setNombre('SIN MARCA');
					
					$importe = $item['costo'] * $item['cant_ingresada'];
					$suma = $suma + $importe;
					
					$renglon  = '<tr><td>'.$oArticuloVO->getNombre().'</td><td>'.$oMarcaVO->getNombre().'</td><td style="text-align:center">'.$item['cant_ingresada'].'</td><td style="text-align:rigth">$'.$item['costo'].'</td><td style="text-align:rigth">$'.$importe.'</td>
						<td>';
					// calculo precio para seleccionar el boton a utilizar
					if ($item['iva_alicuota']==0){
						$precioNuevo = $item['costo'] * (1 + ($oArticuloVO->getMargen() / 100));
					}else{
						$precioNuevo = +(($item['costo'] * (1 +($item['iva_alicuota'] / 100))) * (1 + ($oArticuloVO->getMargen() / 100)));
					} 	
					if ($precioNuevo == $oArticuloVO->getPrecio()){
						$renglon .= '<button type="button" class="btn btn-success btn-xs" value="Precio OK" data-toggle="tooltip" data-placement="bottom" title="Botón para ver estado del precio" onclick="javascrip:window.open(\'http://localhost/caro/modulos/articulo/indexVP.php?accion=Ver&id='.$oArticuloVO->getId().'\',\'Ver\',\'width=600, height=500, top=100, left=100, menubar=0, toolbar=0, titlebar=1, location=0, scrollbars=1\'); void 0"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Precio</button>';
					}else{
						if ($precioNuevo > $oArticuloVO->getPrecio()){
							$renglon .= '<button type="button" class="btn btn-danger btn-xs" value="Modificar Precio" data-toggle="tooltip" data-placement="bottom" title="Botón para modificar precio" onclick="javascrip:window.open(\'http://localhost/caro/modulos/articulo/indexVP.php?accion=Modificar&id='.$oArticuloVO->getId().'\',\'Ver\',\'width=600, height=500, top=100, left=100, menubar=0, toolbar=0, titlebar=1, location=0, scrollbars=1\'); void 0"><span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span> Precio</button>';
						}else{
							$renglon .= '<button type="button" class="btn btn-warning btn-xs" value="Modificar Precio" data-toggle="tooltip" data-placement="bottom" title="Botón para modificar precio" onclick="javascrip:window.open(\'http://localhost/caro/modulos/articulo/indexVP.php?accion=Modificar&id='.$oArticuloVO->getId().'\',\'Ver\',\'width=600, height=500, top=100, left=100, menubar=0, toolbar=0, titlebar=1, location=0, scrollbars=1\'); void 0"><span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span> Precio</button>';
						}
					}	
							
					$renglon .=	'
							<button type="button" class="btn btn-default btn-xs" value="Editar Renglón" data-toggle="tooltip" data-placement="bottom" title="Botón para editar datos" onclick="javascrip:window.open(\'http://localhost/caro/modulos/partida/indexV.php?accion=Editar&id='.$oArticuloVO->getId().'\',\'Editar\',\'width=600, height=500, top=100, left=100, menubar=0, toolbar=0, titlebar=1, location=0, scrollbars=1\'); void 0"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Editar</button>
							<button type="button" class="btn btn-default btn-xs" value="Ver Renglón" data-toggle="tooltip" data-placement="bottom" title="Botón para ver datos" onclick="javascrip:window.open(\'http://localhost/caro/modulos/partida/indexV.php?accion=Ver&id='.$oArticuloVO->getId().'\',\'Ver\',\'width=600, height=500, top=100, left=100, menubar=0, toolbar=0, titlebar=1, location=0, scrollbars=1\'); void 0"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Ver</button>
							</td></tr>'.PHP_EOL;
					fwrite($handle, $renglon);
				}
				$diferencia = $sumaNetos - $suma - $netoRenglon;
				fwrite($handle, '</tbody>'.PHP_EOL);
				
			}

			// Pie de la tabla
			$renglon = '<tfoot><tr>
				<td><input type="hidden" name="idRecibido" value="'.$idRecibido.'"/></td>
				<td>Son: '.$cantidad.' renglones';
			//if ($diferencia > 0) $renglon .= ', con diferencia de $ '.$diferencia.' en la suma.';
			$renglon .= '</td><td></td><td></td><td></td><td></td></tr></tfoot>'.PHP_EOL;
			fwrite($handle, $renglon);
						
			// Cierra la tabla
			fwrite($handle, '</table>'.PHP_EOL);
			
			// Muestra la diferencia
			if ($diferencia == 0) {
				fwrite($handle, '<span class="label label-info"> Si confirma renglon comprobante OK.</span>'.PHP_EOL);
			}else{
				fwrite($handle, '<span class="label label-danger"> Diferencia de $ '.$diferencia.' en la suma.</span>'.PHP_EOL); 
			}
				
			// Cierra el archivo tabla.html
			fclose($handle);
	}
}
?>