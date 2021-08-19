<?php
/**
 * Archivo del include del módulo plex/producto.
 *
 * Archivo del include del módulo plex/producto que arma una tabla con todos
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
 * Clase del include del módulo plex/producto.
 *
 * Clase LaboratorioTabla del módulo plex/producto que permite armar
 * una tabla con todos los registros seleccionados para la vista listar.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class ProductoPlexTabla
{
	# Propiedades
	public static $cantActualizados;

	# Métodos
	/**
	* Nos permite crear y guardar un fichero html con una tabla de todos
	* los registros de plex/productos para representar en la vista listar.
	*
	* @param $cantidad
	* @param $accion
	* @param $oLoginVO
	*
	* @return tabla.html (file.html)
	*/
	static function armaTabla($cantidad, $accion, $oLoginVO)
	{
	// Abre archivo tabla.html (va a representar la consulta)
	$filename = "tabla.html";
			$handle = fopen($filename, "w");
			if (FALSE === $handle){
			exit("falla al abrir el archivo");
			}
			// Titulo de la tabla
			fwrite($handle, '<table class="table table-hover">'.PHP_EOL);
			// Si acción es Listar
			if ($accion == 'Listar'){
				fwrite($handle, '<caption>Listado de Productos - (PLEX) </caption>'.PHP_EOL);

				// Pie de la tabla
				$renglon = '<tfoot><tr>
							<td></td>
							<td>Son: '.$cantidad.' productos</td>
							<td></td>
							<td></td>				
							<td></td>
						</tr></tfoot>'.PHP_EOL;
				fwrite($handle, $renglon);
				// Encabezado de la tabla
				fwrite($handle, '<thead><tr>
							<th title="Código de identificación del producto">Código</th>
							<th title="Nombre del producto">Nombre</th>
							<th title="Presentación del producto">Presentación</th>
							<th title="Estado del producto">Activo</th>
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
					        	 </tr>'.PHP_EOL);
				}else{
					fwrite($handle, '<tbody>'.PHP_EOL);
					// lee la tabla plex_laboratorios
					$query = "SELECT * FROM plex_productos";
					$result = mysql_query($query) or die(mysql_error());
					while ($item = mysql_fetch_array($result)){
						$renglon  = '<tr>
									<td>'.$item[0].'</td>
									<td style="text-align:left">'.utf8_encode($item[10]).'</td>
									<td style="text-align:left">'.utf8_encode($item[11]).'</td>	
									<td style="text-align:center">'.$item[14].'</td>			
									<td> <!-- Sin acciones a realizar --> </td>
								</tr>'.PHP_EOL;
						fwrite($handle, $renglon);
					}
					mysql_free_result($result);
				}
				// Datos necesarios a enviar por hidden
			}

			// Si acción es ConfirmarAct
			if ($accion == 'ConfirmarAct'){

				// Cuerpo de la tabla
				If ($cantidad==0){
					fwrite($handle, '<tbody><tr>
									<td></td>
									<td></td>
									<td></td>
					        	 </tr>'.PHP_EOL);
				}else{
					date_default_timezone_set('America/Argentina/Buenos_Aires');
				
					fwrite($handle, '<tbody>'.PHP_EOL);
					// lee la tabla plex_laboratorios
					$query = "SELECT * FROM plex_productos";
					$result = mysql_query($query) or die(mysql_error());
					while ($item = mysql_fetch_array($result)){
						/* por funciones nativas */
						$query = "SELECT count(*) FROM articulos WHERE codigo=".$item[0];
						$resultadoCount = mysql_query($query) or die(mysql_error());
						$count = mysql_fetch_array($resultadoCount);
						mysql_free_result($resultadoCount);
						if ($count[0]==0){
							//if ($item[0]==3016500206){
								//echo "fijate 3016500206 que sale: ".$item[0]."<br>";
								//$codigo = trim($item[0]);
								//echo "codigo : ".$codigo."<br>";
							//}else{
								$codigo = trim($item[0]);
							//}
							$codigoM = " ";
							if ($item[9]!= " "){
								$codigoB = trim($item[9]);
							} else {
								$codigoB = 0;
							}	
							$idMarca = trim($item[1]);
							if ($idMarca == 0){
								$idMarca = 9009;
							}
							$idRubro = trim($item[3]);
											
							$nombre = strtoupper(utf8_encode($item[10]));
							
							if ($item[11]!=' '){
								$presentacion = strtoupper(utf8_encode($item[11]));
							} else {
								$presentacion = " ";
							}	
							$comentario = " ";
							$margen = trim($item[17]);
							$costo = trim($item[16]);
							$precio = 0;
							$fecha_precio = date('Y-m-d');
							$stock = 0;
							if($idRubro>1){
								$rotulo = 1;
							}else{
								$rotulo = 0;
							}	 
							$idProveedor = 0;
							$equivalencia = 0;
							// codigo de iva según tabla AFIP
							if ($item[25]==1){ // iva 0%
								$codigoIva = 3;
							}else{
								if ($item[25]==3){ // iva 21%
									$codigoIva = 5;
								}else{
									$codigoIva = 0;
								}
							} 
							$foto = " ";
							// código de estado "S" Activo - "N" Inactivo
							if ($item[14]=="S"){
								$estado=1;  // Activo = S
							}else{
								$estado=0;  // Activo = N u otra letra
							}
							$idUsuarioAct = $oLoginVO->getIdUsuario();
							$fechaAct = date('Y-m-d H:i:s');
							
							$queryInsert = "INSERT INTO articulos 
											(codigo, codigo_m, codigo_b, id_marca, id_rubro, nombre, presentacion, comentario, margen, costo, precio, fecha_precio, stock, rotulo, id_proveedor, equivalencia, codigo_iva, foto, estado, id_usuario_act, fecha_act)
											VALUES 
											(".$codigo.", '".$codigoM."', ".$codigoB.", ".$idMarca.", ".$idRubro.", '".$nombre."', '".$presentacion."', '".$comentario."', ".$margen.", ".$costo.", ".$precio.", '".$fecha_precio."', ".$stock.", ".$rotulo.", ".$idProveedor.", ".$equivalencia.", ".$codigoIva.", '".$foto."', ".$estado.", ".$idUsuarioAct.", '".$fechaAct."' )"; 
							
							$resultado = mysql_query($queryInsert) or die(mysql_error());
							if ($estado==1){
								$activo = 'SI';
							}else{
								$activo= 'NO';
							}
							self::$cantActualizados++;
							$renglon  = '<tr>
									<td>'.$codigo.'</td>
									<td style="text-align:left">'.$nombre.'</td>
									<td style="text-align:left">'.$presentacion.'</td>	
									<td style="text-align:center">'.$activo.'</td>			
									<td> <!-- Sin acciones a realizar --> </td>
								</tr>'.PHP_EOL;
							fwrite($handle, $renglon);

						}
					}
					mysql_free_result($result);
				}
				fwrite($handle, '<caption>Listado de Artículos actualizados </caption>'.PHP_EOL);
					
				// Pie de la tabla
				$renglon = '<tfoot><tr>
							<td></td>
							<td>Son: '.self::$cantActualizados.' articulos actualizados</td>
							<td></td>
						</tr></tfoot>'.PHP_EOL;
				fwrite($handle, $renglon);
				// Encabezado de la tabla
				fwrite($handle, '<thead><tr>
							<th title="Código de identificación del producto">Código</th>
							<th title="Nombre del producto">Nombre</th>
							<th title="Presentación del producto">Presentación</th>
							<th title="Estado del producto">Activo</th>
							<th title="Acciones que puede realizar sobre la tabla">Acciones</th>
						</tr></thead>'.PHP_EOL);
					
				// Datos necesarios a enviar por hidden
			}
			// Cierra el body
			fwrite($handle, '</tbody>'.PHP_EOL);
			// Cierra la tabla
			fwrite($handle, '</table>'.PHP_EOL);
			// Cierra el archivo tabla.html
			fclose($handle);
	}
}
?>