<?php
/**
 * Archivo de la clase vista.
 *
 * Archivo de la clase vista que renderiza el archivo HTML.
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
 * Clase de la vista.
 *
 * Clase de la vista que renderiza el archivo HTML con los datos
 * recibidos y los presenta.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
// Carga las clases necesarias
require_once $_SERVER['DOCUMENT_ROOT'].'/farma/aplicaciones/vista/MenuHref.php';

class MotorVista
{
	#Propiedades
	private $_html;
	private $_archivo;
	private $_archivoHtml;
	private $_aDatosHtml;
	private $_accionesHtml;
	private $_vistasHtml;
	private $_eventosHtml;
	private $_tablaHtml;
	private $_selectHtml;
	private $_aDatos = array();
	private $_aCargas = array();
	private $_aMenuHref = array();

	#Métodos
	// Inicia la clase con las únicas instancias de las clases Dato y Carga
	public function __construct($aDatos, $aCargas)
	{
	$this->_aDatos = $aDatos;
	$this->_aCargas = $aCargas;
	}

	/* maneja el armado del html y la representación
	* de los datos; y muestra la vista
	*/
	public function mostrarVista()
	{
	$this->armarVista();
	$this->representarDatos();
	return $this->_html;
	}

	/* arma la vista cargando los diferentes archivos html que la
	* componen (página, menú, alertas, contenido y botones)
	*/
	private function armarVista()
	{
	// carga la plantilla de la página
		if (isset($this->_aCargas['pagina'])){
		$this->_archivo = $_SERVER['DOCUMENT_ROOT'].$this->_aCargas['pagina'];
		$this->_html = file_get_contents($this->_archivo);
		// carga el menú a utilizar en la página
		if (isset($this->_aCargas['menu'])){
		$this->_archivo = $_SERVER['DOCUMENT_ROOT'].$this->_aCargas['menu'];
		$this->_archivoHtml = file_get_contents($this->_archivo);
		$this->_html = str_replace('[menu]', $this->_archivoHtml, $this->_html);
		/* representa los href del menú dentro del html de acuerdo
		* a los valores recibidos en array del objeto MenuHref
		*/
		$oMenuHref = new MenuHref();
		$this->_aMenuHref = $oMenuHref->getHref();
		foreach ($this->_aMenuHref as $clave=>$valor){
		$this->_html = str_replace($clave, $valor, $this->_html);
		}
		} else {
		$this->_html = str_replace('[menu]', '', $this->_html);
		}
		// carga las alertas y las muestras si existe mensaje ($valor)
		// Alerta suceso
		if (isset($this->_aCargas['alertaSuceso'])){
		$this->_archivo = $_SERVER['DOCUMENT_ROOT'].$this->_aCargas['alertaSuceso'];
		$this->_archivoHtml = file_get_contents($this->_archivo);
		$this->_html = str_replace('[alertaSuceso]', $this->_archivoHtml, $this->_html);
		} else {
				$this->_html = str_replace('[alertaSuceso]', '', $this->_html);
		}
		// Alerta peligro
			if (isset($this->_aCargas['alertaPeligro'])){
		$this->_archivo = $_SERVER['DOCUMENT_ROOT'].$this->_aCargas['alertaPeligro'];
		$this->_archivoHtml = file_get_contents($this->_archivo);
		$this->_html = str_replace('[alertaPeligro]', $this->_archivoHtml, $this->_html);
		} else {
				$this->_html = str_replace('[alertaPeligro]', '', $this->_html);
		}
		// Alerta advertencia
			if (isset($this->_aCargas['alertaAdvertencia'])){
		$this->_archivo = $_SERVER['DOCUMENT_ROOT'].$this->_aCargas['alertaAdvertencia'];
		$this->_archivoHtml = file_get_contents($this->_archivo);
				$this->_html = str_replace('[alertaAdvertencia]', $this->_archivoHtml, $this->_html);
		} else {
				$this->_html = str_replace('[alertaAdvertencia]', '', $this->_html);
				}
				// Alerta info
				if (isset($this->_aCargas['alertaInfo'])){
				$this->_archivo = $_SERVER['DOCUMENT_ROOT'].$this->_aCargas['alertaInfo'];
				$this->_archivoHtml = file_get_contents($this->_archivo);
				$this->_html = str_replace('[alertaInfo]', $this->_archivoHtml, $this->_html);
				} else {
				$this->_html = str_replace('[alertaInfo]', '', $this->_html);
				}
				// carga los contenidos del body (formularios,listados, etc)
				if (isset($this->_aCargas['contenido'])){
				$this->_archivo = $_SERVER['DOCUMENT_ROOT'].$this->_aCargas['contenido'];
				$this->_archivoHtml = file_get_contents($this->_archivo);

				if (isset($this->_aCargas['aAcciones'])){
				$aAcciones = $this->_aCargas['aAcciones'];
				$this->_accionesHtml = '';
				foreach ($aAcciones as $accion){
				$this->_archivo = $_SERVER['DOCUMENT_ROOT'].$accion;
				$this->_accionesHtml .= file_get_contents($this->_archivo);
				}
				$this->_archivoHtml = str_replace('[aAcciones]', $this->_accionesHtml, $this->_archivoHtml);
				}else{
				$this->_archivoHtml = str_replace('[aAcciones]', '', $this->_archivoHtml);
				}

				if (isset($this->_aCargas['datos'])){
					$this->_archivo = $_SERVER['DOCUMENT_ROOT'].$this->_aCargas['datos'];
					$this->_aDatosHtml = file_get_contents($this->_archivo);
					$this->_archivoHtml = str_replace('[datos]', $this->_aDatosHtml, $this->_archivoHtml);
					}else{
					$this->_archivoHtml = str_replace('[datos]', '', $this->_archivoHtml);
					}
						
					if (isset($this->_aCargas['tabla'])){
					$this->_archivo = $_SERVER['DOCUMENT_ROOT'].$this->_aCargas['tabla'];
						$this->_tablaHtml = file_get_contents($this->_archivo);
						$this->_archivoHtml = str_replace('[tabla]', $this->_tablaHtml, $this->_archivoHtml);
				}else{
				$this->_archivoHtml = str_replace('[tabla]', '', $this->_archivoHtml);
						}

						if (isset($this->_aCargas['opcion'])){
						$this->_archivo = $_SERVER['DOCUMENT_ROOT'].$this->_aCargas['opcion'];
						$this->_tablaHtml = file_get_contents($this->_archivo);
								$this->_archivoHtml = str_replace('[opcion]', $this->_tablaHtml, $this->_archivoHtml);
						} else {
								$this->_archivoHtml = str_replace('[opcion]', '', $this->_archivoHtml);
								}

								if (isset($this->_aCargas['aVistas'])){
								$aVistas = $this->_aCargas['aVistas'];
										$this->_vistasHtml = '';
										foreach ($aVistas as $vista){
										$this->_archivo = $_SERVER['DOCUMENT_ROOT'].$vista;
										$this->_vistasHtml .= file_get_contents($this->_archivo);
										}
										$this->_archivoHtml = str_replace('[aVistas]', $this->_vistasHtml, $this->_archivoHtml);
										}else{
										$this->_archivoHtml = str_replace('[aVistas]', '', $this->_archivoHtml);
										}

										if (isset($this->_aCargas['aEventos'])){
												$aEventos = $this->_aCargas['aEventos'];
												$this->_eventosHtml = '';
												foreach ($aEventos as $evento){
													$this->_archivo = $_SERVER['DOCUMENT_ROOT'].$evento;
													$this->_eventosHtml .= file_get_contents($this->_archivo);
												}
												$this->_archivoHtml = str_replace('[aEventos]', $this->_eventosHtml, $this->_archivoHtml);
												}else{
													$this->_archivoHtml = str_replace('[aEventos]', '', $this->_archivoHtml);
												}

												if (isset($this->_aCargas['selectMarca'])){
													$this->_archivo = $_SERVER['DOCUMENT_ROOT'].$this->_aCargas['selectMarca'];
													$this->_selectHtml = file_get_contents($this->_archivo);
													$this->_archivoHtml = str_replace('[selectMarca]', $this->_selectHtml, $this->_archivoHtml);
												}else{
													$this->_archivoHtml = str_replace('[selectMarca]', '', $this->_archivoHtml);
												}

												if (isset($this->_aCargas['selectRubro'])){
													$this->_archivo = $_SERVER['DOCUMENT_ROOT'].$this->_aCargas['selectRubro'];
													$this->_selectHtml = file_get_contents($this->_archivo);
													$this->_archivoHtml = str_replace('[selectRubro]', $this->_selectHtml, $this->_archivoHtml);
												}else{
													$this->_archivoHtml = str_replace('[selectRubro]', '', $this->_archivoHtml);
												}

												if (isset($this->_aCargas['selectProveedor'])){
													$this->_archivo = $_SERVER['DOCUMENT_ROOT'].$this->_aCargas['selectProveedor'];
													$this->_selectHtml = file_get_contents($this->_archivo);
													$this->_archivoHtml = str_replace('[selectProveedor]', $this->_selectHtml, $this->_archivoHtml);
												}else{
													$this->_archivoHtml = str_replace('[selectProveedor]', '', $this->_archivoHtml);
												}

												$this->_html = str_replace('[contenido]', $this->_archivoHtml, $this->_html);
													
													
} else {
	$this->_html = str_replace('[contenido]', '', $this->_html);
}
// carga botones del footer
if (isset($this->_aCargas['botones'])){
	$this->_archivo = $_SERVER['DOCUMENT_ROOT'].$this->_aCargas['botones'];
	$this->_archivoHtml = file_get_contents($this->_archivo);
	$this->_html = str_replace('[botones]', $this->_archivoHtml, $this->_html);
} else {
	$this->_html = str_replace('[botones]', '', $this->_html);
}
}
}

/* representa los datos dentro del html armado de
 * acuerdo a los valores recibidos del objeto Dato
*/
private function representarDatos()
{
	foreach ($this->_aDatos as $clave=>$valor){
		$this->_html = str_replace($clave, $valor, $this->_html);
	}
}
}
?>