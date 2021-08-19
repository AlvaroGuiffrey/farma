<?php
/**
 * Archivo del include del módulo plex/laboratorio.
 *
 * Archivo del include del módulo plex/laboratorio que arma una tabla con todos
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
 * Clase del include del módulo plex/laboratorio.
 *
 * Clase LaboratorioTabla del módulo plex/laboratorio que permite armar
 * una tabla con todos los registros seleccionados para la vista listar.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class LaboratorioPlexTabla
{
	# Propiedades
	public static $cantActualizados;
	
	# Métodos
	/**
	* Nos permite crear y guardar un fichero html con una tabla de todos
	* los registros de plex/laboratorios para representar en la vista listar.
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
			fwrite($handle, '<caption>Listado de Laboratorios - (PLEX) </caption>'.PHP_EOL);
		
			// Pie de la tabla
			$renglon = '<tfoot><tr>
							<td></td>
							<td>Son: '.$cantidad.' laboratorios</td>
							<td></td>
						</tr></tfoot>'.PHP_EOL;
			fwrite($handle, $renglon);
			// Encabezado de la tabla
			fwrite($handle, '<thead><tr>
							<th title="Número de identificación del laboratorio">#</th>
							<th title="Nombre del Laboratorio">Nombre Laboratorio</th>
							<th title="Acciones que puede realizar sobre la tabla">Acciones</th>
						</tr></thead>'.PHP_EOL);

			// Cuerpo de la tabla
			If ($cantidad==0){
				fwrite($handle, '<tbody><tr>
									<td></td>
									<td></td>
									<td></td>
					        	 </tr>'.PHP_EOL);
			}else{
				fwrite($handle, '<tbody>'.PHP_EOL);
				// lee la tabla plex_laboratorios
				$query = "SELECT * FROM plex_laboratorios";
				$result = mysqli_query($this->_con, $query) or die(mysqli_error($this->_con));
				while ($item = mysqli_fetch_array($result)){
					$renglon  = '<tr>
									<td>'.$item[0].'</td>
									<td style="text-align:left">'.utf8_encode($item[1]).'</td>
									<td> <!-- Sin acciones a realizar --> </td>
								</tr>'.PHP_EOL;
					fwrite($handle, $renglon);
				}
				mysqli_free_result($result);
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
				$oMarcaVO = new MarcaVO();
				$oMarcaModelo = new MarcaModelo();
				fwrite($handle, '<tbody>'.PHP_EOL);
				// lee la tabla plex_laboratorios
				$query = "SELECT * FROM plex_laboratorios";
				$result = mysqli_query($this->_con, $query) or die(mysql_error($this->_con));
				while ($item = mysqli_fetch_array($result)){
					$oMarcaVO->setId($item[0]);
					$oMarcaModelo->find($oMarcaVO);
					//echo "Marca ID: ".$item[0]." - ".$oMarcaModelo->getCantidad()."<br>";
					/* por funciones nativas 
					$query = "SELECT count(*) FROM marcas WHERE id=".$item[0];
					$resultadoCount = mysql_query($query) or die(mysql_error());
					$count = mysql_fetch_array($resultadoCount);
					mysql_free_result($resultadoCount);
					if ($count[0]==0){
					*/	
					
					if ($oMarcaModelo->getCantidad()==0){	
						$nombreMarca = utf8_encode($item[1]);
						$nombreMarca = strtoupper($nombreMarca);
						//$nombreMarca = str_replace(" ", "a", $nombreMarca);
						//$nombreMarca = str_replace(",", "e", $nombreMarca);
						//$nombreMarca = str_replace("¡", "i", $nombreMarca);
						//$nombreMarca = str_replace("¢", "o", $nombreMarca);
						//$nombreMarca = str_replace("ñ", "ñ", $nombreMarca);
						/* Nativa
						$nombreMarca = strtoupper($nombreMarca);
						$comentario = " ";
						$fecha_act = date('Y-m-d H:i:s');
						$queryInsert = "INSERT INTO marcas (id, nombre, comentario, id_usuario_act, fecha_act) VALUES (".$item[0].", '".$nombreMarca."', '".$comentario."', ".$oLoginVO->getIdUsuario().", '".$fecha_act."')";
						$resultado = mysql_query($queryInsert) or die(mysql_error());
						*/
						$oMarcaVO->setId($item[0]);
						$oMarcaVO->setNombre($nombreMarca);
						$oMarcaVO->setComentario("");
						$oMarcaVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
						$fecha_act = date('Y-m-d H:i:s');
						$oMarcaVO->setFechaAct($fecha_act);
						$oMarcaModelo->insert($oMarcaVO);
							self::$cantActualizados++;
							$renglon  = '<tr>
									<td>'.$item[0].'</td>
									<td style="text-align:left">'.$nombreMarca.'</td>
									<td> <!-- Sin acciones a realizar --> </td>
								</tr>'.PHP_EOL;
							fwrite($handle, $renglon);
						
					}else{
						//echo "Existe registro ".$item[0]." en marcas <br>";
					}
				}
				mysql_free_result($result);
			}
			fwrite($handle, '<caption>Listado de Marcas actualizadas </caption>'.PHP_EOL);
			
			// Pie de la tabla
			$renglon = '<tfoot><tr>
							<td></td>
							<td>Son: '.self::$cantActualizados.' marcas actualizadas</td>
							<td></td>
						</tr></tfoot>'.PHP_EOL;
			fwrite($handle, $renglon);
			// Encabezado de la tabla
			fwrite($handle, '<thead><tr>
							<th title="Número de identificación de la marca">#</th>
							<th title="Nombre de la marca">Nombre Marca</th>
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