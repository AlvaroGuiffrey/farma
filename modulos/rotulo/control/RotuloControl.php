<?php
/**
 * Archivo de la clase control del módulo rótulo.
 *
 * Archivo de la clase control del módulo rótulo.
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
 * Clase control del módulo rótulo.
 *
 * Clase control del módulo rótulo que permite realizar
 * operaciones sobre la tabla artículos (CRUD y otras)
 * necesarias para la administración de los rótulos
 * según la categoría asignada al usuario.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class RotuloControl
{
	#Propiedades
	private $_html;
	private $_accion;
	private $_items;
	private $_item;
	private $_cantidad;
	private $_date;
	private $_aAcciones = array();
	private $_aEventos = array();
	public $tabla;

	#Métodos
	/**
	* Verifica el login del usuario y nos envia a la
	* función que ejecuta las acciones en el módulo.
	*/
	public function inicio($oLoginVO, $accion)
	{
		date_default_timezone_set('America/Argentina/Buenos_Aires');
		// Se definen las clases necesarias
		Clase::define('CargarVista');
		Clase::define('DatoVista');
		Clase::define('CargarMenu');
		Clase::define('MotorVista');
		Clase::define('ArticuloModelo');
		Clase::define('RotuloTabla');
		Clase::define('RotuloDatos');

		// Chequea login
		$oLoginControl = new LoginControl();
		$oLoginControl->chequearLogin($oLoginVO);
		$this->_accion = $accion;
		$this->accionControl($oLoginVO);
	}

	/**
	 * Nos permite ejecutar las acciones del módulo
	 * rótulo del sistema, de acuerdo a la categoría del
	 * usuario.
	 */
	private function accionControl($oLoginVO)
	{
		// Carga acciones del formulario
		if (isset($_POST['bt_agregar'])) $this->_accion = "Agregar";
		if (isset($_POST['bt_buscar_conf'])) $this->_accion = "ConfirmarA";
		if (isset($_POST['bt_agregar_conf'])) $this->_accion = "ConfirmarA1";
		if (isset($_POST['bt_listar'])) $this->_accion = "Listar";
		if (isset($_POST['bt_listar_conf'])) $this->_accion = "ConfirmarL";
		if (isset($_POST['bt_descargar'])) $this->_accion = "Descargar";
		if (isset($_POST['bt_descartar'])) $this->_accion = "Descartar";
		if (isset($_POST['bt_descartar_conf'])) $this->_accion = "ConfirmarD";
			
		// Carga los archivos html para la vista
		$oCargarVista = new CargarVista();
		$oCargarVista->setCarga('pagina', '/includes/vista/pagina.html');
		$oCargarVista->setCarga('botones', '/includes/vista/botonesFooter.html');
		// Carga el menú de la vista según la categoría del usuario
		$oCargarVista->setCarga('menu', CargarMenu::selectMenu($oLoginVO->getCategoria()));
		// Carga el formulario
		$oCargarVista->setCarga('contenido', '/includes/vista/form.html');
		// carga las acciones (botones)
		$this->_aAcciones = array(
				"badge" => "/includes/vista/botonBadge.html",
				"agregar" => "/includes/vista/botonAgregar.html",
				"listar" => "/includes/vista/botonListar.html",
				"descargar" => "/includes/vista/botonDescargar.html",
				"descartar" => "/includes/vista/botonDescartar.html"
		);
		$oCargarVista->setCarga('aAcciones', $this->_aAcciones);
		// Ingresa los datos a representar en el html de la vista
		$oDatoVista = new DatoVista();
		$oDatoVista->setDato('{tituloPagina}', 'Rótulos');
		$oDatoVista->setDato('{usuario}', $oLoginVO->getAlias());
		$oDatoVista->setDato('{tituloBadge}', 'Rótulos ');
		// carga href de boton descargar
		$oDatoVista->setDato('{hrefDescarga}', '/farma/modulos/rotulo/includes/descargaRotulos.php');
		// Alertas

		// Carga el contenido html y datos según la acción
		$oArticuloVO = new ArticuloVO();
		$oArticuloModelo = new ArticuloModelo();
		// Selector de acciones
		switch ($this->_accion){
			# ----> acción Iniciar
			case 'Iniciar':
				// carga el contenido html
				$oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
				// ingresa los datos a representar en las alertas de la vista
				$oDatoVista->setDato('{alertaInfo}',  'Seleccione alguna acción con los botones.<p>Debe confirmar en <b>Listado</b> los rótulos a descargar.');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Rótulos - Selección de acciones');
				$oDatoVista->setDato('{informacion}', '<p>Puede seleccionar acciones para los rótulos, ver botones.</p>
														<p>Debe confirmar en <b>Listado</b> los rótulos a descargar</p>
														<p>para imprimir.</p>');
				// arma la tabla de datos a representar
				$this->_items = $oArticuloModelo->countRotulos();
				$this->_cantidad = $oArticuloModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				break;
				# ----> acción Listar
			case 'Listar':
				// carga el contenido html

				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Listado de Rotulos');
				$oDatoVista->setDato('{informacion}', '<p>Listado de todos los rótulos para confirmar la descarga.</p><p>También puede seleccionar otras acciones</p><p>para los rótulos, ver botones.');
				// carga los eventos (botones)
				$this->_aEventos = [
						"listarConfirmar" => "/includes/vista/botonListarConfirmar.html",
						"borrar" => "/includes/vista/botonBorrar.html"
						];
				$oCargarVista->setCarga('aEventos', $this->_aEventos);
				// ingresa los datos a representar en las alertas de la vista

				// arma los datos a representar
				$this->_items = $oArticuloModelo->countRotulos();
				$this->_cantidad = $oArticuloModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				// arma la tabla de datos a representar
				$this->_items = $oArticuloModelo->findAllRotulos();
				RotuloTabla::armaTabla($this->_items, $this->_cantidad, $this->_accion);
				$oCargarVista->setCarga('tabla', '/modulos/rotulo/tabla.html');
				break;
			# ----> acción Confirmar Listar
			case "ConfirmarL":
				// carga el contenido html
				$oCargarVista->setCarga('datos', '/modulos/rotulo/vista/actualizarDatos.html');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Rotulos - Totales');
				$oDatoVista->setDato('{informacion}', '<p>Totales de los rótulos reservados o para descarga.</p>
														<p>También puede seleccionar otras acciones</p>
														<p>para los rótulos, ver botones.');
				// recibe datos por POST y arma los array para actualizar tabla articulos
				$aRadios = $_POST['rotulos'];
				while (list($key, $val) = each($aRadios)){
					if ($val==3){
						$aRotulos3[] = $key;
					}
					if ($val==2){
						$aRotulos2[] = $key;
					}
					if ($val==1){
						$aRotulos1[] = $key;
					}
				}	
				// actualiza el estado de los rótulos
				$rotulo=3;
				$oArticuloModelo->updateRotulos($aRotulos3, $rotulo);
				$rotulo=2;
				$oArticuloModelo->updateRotulos($aRotulos2, $rotulo);
				$rotulo=1;
				$oArticuloModelo->updateRotulos($aRotulos1, $rotulo);
				// arma los datos a representar
				$this->_items = $oArticuloModelo->countRotulos();
				$this->_cantidad = $oArticuloModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				$oDatoVista->setDato('{cantReservados}', $this->_cantidad);
				$this->_items = $oArticuloModelo->countRotulosPDF();
				$this->_cantidad = $oArticuloModelo->getCantidad();
				$oDatoVista->setDato('{cantDescargas}', $this->_cantidad);
				break;
			# ----> acción Agregar
			case 'Agregar':
				// carga el contenido html
				$oCargarVista->setCarga('opcion', '/modulos/rotulo/vista/buscarOpcion.html');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Agrega Rótulo para descarga');
				$oDatoVista->setDato('{informacion}', '<p>Buscar los artículos según opciones elegidas para agregar rótulo.</p>
													<p>La búsqueda se realiza por una sola opción, comenzando por la izquierda.</p>
													<p>También puede seleccionar otras acciones</p>
													<p>para los rotulos, ver botones.'
				);
				// ingresa los datos a representar en las alertas de la vista
				
				// arma los datos a representar
				$this->_items = $oArticuloModelo->countRotulos();
				$this->_cantidad = $oArticuloModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				break;
			# ---> acción Confirmar Agregar
			case 'ConfirmarA':
				// busco los artículos segun las opciones elegidas para agregar rótulo
				if ($_POST['codigo'] > 0){
					$oArticuloVO->setCodigo($_POST['codigo']);
					$oArticuloModelo->findPorCodigo($oArticuloVO);
					$this->_cantidad = $oArticuloModelo->getCantidad();
					if ($this->_cantidad == 0){
						// carga el contenido html
						$oCargarVista->setCarga('opcion', '/modulos/rotulo/vista/buscarOpcion.html');
						$oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
						$oDatoVista->setDato('{alertaAdvertencia}',  'No encontró artículo. Intente otra búsqueda.');
					}else{
						// carga el contenido html
						$oCargarVista->setCarga('datos', '/modulos/rotulo/vista/verDatos.html');
						// ingresa los datos a representar en el Panel de la vista
						$oDatoVista->setDato('{tituloPanel}', 'Ver Rótulo buscado para agregar');
						$oDatoVista->setDato('{cantidad}', $this->_cantidad);
						$oDatoVista->setDato('{informacion}', '<p>Muestra los datos de un artículo para agregar rótulo.</p>
													<p>Seleccione alguna acción para el rótulo con botones</p>
													<p>u otra opción del menú.'
						);
						// carga los eventos (botones)
						$this->_aEventos = array(
								"agregarConf" => "/includes/vista/botonAgregarConf.html",
						);
						$oCargarVista->setCarga('aEventos', $this->_aEventos);
						RotuloDatos::cargaDatos($oArticuloVO, $oDatoVista, $this->_accion);
					}
				}else{
					if($_POST['codigoBarra'] > 0){
						$oArticuloVO->setCodigoB($_POST['codigoBarra']);
						$oArticuloModelo->findPorCodigoB($oArticuloVO);
						$this->_cantidad = $oArticuloModelo->getCantidad();
						if ($this->_cantidad == 0){
							// carga el contenido html
							$oCargarVista->setCarga('opcion', '/modulos/rotulo/vista/buscarOpcion.html');
							$oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
							$oDatoVista->setDato('{alertaAdvertencia}',  'No encontró artículo. Intente otra búsqueda.');
						}else{
							// carga el contenido html
							$oCargarVista->setCarga('datos', '/modulos/rotulo/vista/verDatos.html');
							// ingresa los datos a representar en el Panel de la vista
							$oDatoVista->setDato('{tituloPanel}', 'Ver Rótulo buscado para agregar');
							$oDatoVista->setDato('{cantidad}', $this->_cantidad);
							$oDatoVista->setDato('{informacion}', '<p>Muestra los datos de un artículo para agregar rótulo.</p>
														<p>Seleccione alguna acción para el rótulo con botones</p>
														<p>u otra opción del menú.'
							);
							// carga los eventos (botones)
							$this->_aEventos = array(
									"agregarConf" => "/includes/vista/botonAgregarConf.html",
							);
							$oCargarVista->setCarga('aEventos', $this->_aEventos);
							RotuloDatos::cargaDatos($oArticuloVO, $oDatoVista, $this->_accion);
						}
					}
				}
				break;
			# ---> acción Confirmar Agregar (1)
			case 'ConfirmarA1':
				// agrega el rótulo del artículo confirmado para descargar
				$oArticuloVO->setId($_POST['id']);
				$oArticuloModelo->find($oArticuloVO);
				$rotulo = 3 ; //lo agrega para descarga directa en PDF
				$oArticuloVO->setRotulo($rotulo);
				$oArticuloModelo->update($oArticuloVO);
				// carga el contenido html
				$oCargarVista->setCarga('opcion', '/modulos/rotulo/vista/buscarOpcion.html');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Agrega Rótulo para descarga');
				$oDatoVista->setDato('{informacion}', '<p>Buscar los artículos según opciones elegidas para agregar rótulo.</p>
													<p>La búsqueda se realiza por una sola opción, comenzando por la izquierda.</p>
													<p>También puede seleccionar otras acciones</p>
													<p>para los rotulos, ver botones.'
				);
				// ingresa los datos a representar en las alertas de la vista
				$oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
				$oDatoVista->setDato('{alertaSuceso}',  'Agregó '.$oArticuloVO->getNombre().' con exito!!!.');
				// arma los datos a representar
				$this->_items = $oArticuloModelo->countRotulos();
				$this->_cantidad = $oArticuloModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				
				break; 
			# ----> acción Editar
			case 'Editar':

				break;
			# ----> acción Confirmar Editar
			case 'ConfirmarE':

				break;
			# ----> acción Descartar
			case 'Descartar':
				// carga el contenido html
				
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Descarta Rotulos');
				$oDatoVista->setDato('{informacion}', '<p>Listado de todos los <b>rótulos a descartar</b>.</p><p>También puede seleccionar otras acciones</p><p>para los rótulos, ver botones.');
				// carga los eventos (botones)
				$this->_aEventos = [
				"descartaConfirmar" => "/includes/vista/botonDescartarConf.html"
				
						];
				$oCargarVista->setCarga('aEventos', $this->_aEventos);
				// arma los datos a representar
				$this->_items = $oArticuloModelo->countRotulos();
				$this->_cantidad = $oArticuloModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				// ingresa los datos a representar en las alertas de la vista
				if ($this->_cantidad > 0){
				$oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
				$oDatoVista->setDato('{alertaPeligro}',  'Va a descartar todos los rótulos. <b>Precaución!!!</b>.');
				} else {
					$oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
					$oDatoVista->setDato('{alertaAdvertencia}',  'No hay rótulos para descartar.');
				}
				// arma la tabla de datos a representar
				$this->_items = $oArticuloModelo->findAllRotulos();
				RotuloTabla::armaTabla($this->_items, $this->_cantidad, $this->_accion);
				$oCargarVista->setCarga('tabla', '/modulos/rotulo/tabla.html');
				break;
			# ----> acción Confirmar Descartar
			case 'ConfirmarD':
				$cantDescartados=0;

				// carga el contenido html
				$oCargarVista->setCarga('datos', '/modulos/rotulo/vista/descartarDatos.html');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Rotulos Descartados');
				$oDatoVista->setDato('{informacion}', '<p>Informa total de los rótulos descartados.</p>
														<p>También puede seleccionar otras acciones</p>
														<p>para los rótulos, ver botones.');
				// ingresa los datos a representar en las alertas de la vista
				$oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
				$oDatoVista->setDato('{alertaSuceso}',  'Descartó los rótulos con exito!!!.');
				// recibe datos por POST y arma los array para actualizar tabla articulos
				$aRotulos = $_POST['rotulos'];
				while (list($key, $val) = each($aRotulos)){
						$aRotulos1[] = $key;
						$cantDescartados++;
				}
				// actualiza el estado de los rótulos
				$rotulo=1;
				$oArticuloModelo->updateRotulos($aRotulos1, $rotulo);
				// arma los datos a representar
				$this->_items = $oArticuloModelo->countRotulos();
				$this->_cantidad = $oArticuloModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				$oDatoVista->setDato('{cantDescartados}', $cantDescartados);
				break;
			# ----> acción Ver
			case 'Ver':

				break;
			# ----> acción por Defecto (ninguna acción seleccionada)
			default:

				break;
		}

	// instancio el motor de la vista y muestro la vista
	$oMotorVista = new MotorVista($oDatoVista->getAllDatos(), $oCargarVista->getAllCargas());
	$oMotorVista->mostrarVista();

	}

}
?>