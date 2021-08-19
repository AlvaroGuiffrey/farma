<?php
/**
 * Archivo de la clase control del módulo artículo.
 *
 * Archivo de la clase control del módulo artículo.
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
 * Clase control del módulo artículo.
 *
 * Clase control del módulo artículo que permite controlar los
 * artículos con los productos de PLEX y dar de baja los articulos
 * inexistentes, según la categoría asignada al usuario.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class ArticuloControlDescarta
{
	#Propiedades
	private $_html;
	private $_accion;
	private $_items;
	private $_item;
	private $_id;
	private $_estado;
	private $_aAcciones = array();
	private $_aEventos = array();
	private $_idProveedor;
	private $_cantidad;
	private $_cantDescartados;
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
		Clase::define('DataBasePlex');

		// Chequea login
		$oLoginControl = new LoginControl();
		$oLoginControl->chequearLogin($oLoginVO);
		$this->_accion = $accion;
		$this->accionControl($oLoginVO);
	}

	/**
	 * Nos permite ejecutar las acciones del módulo
	 * artículo del sistema, de acuerdo a la categoría del
	 * usuario.
	 */
	private function accionControl($oLoginVO)
	{
		// Carga acciones del formulario
		if (isset($_POST['bt_descartar'])) $this->_accion = "Descartar";
		if (isset($_POST['bt_descartar_conf'])) $this->_accion = "ConfirmarDes";

			
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
				"descartar" => "/includes/vista/botonDescartar.html"
		);
		$oCargarVista->setCarga('aAcciones', $this->_aAcciones);
		// Ingresa los datos a representar en el html de la vista
		$oDatoVista = new DatoVista();
		$oDatoVista->setDato('{tituloPagina}', 'Artículos');
		$oDatoVista->setDato('{usuario}', $oLoginVO->getAlias());
		$oDatoVista->setDato('{tituloBadge}', 'Artículos ');
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
				$oDatoVista->setDato('{alertaInfo}',  'Seleccione alguna acción con los botones.');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Artículos - Descarta bajas productos PLEX - Selección de acciones');
				$oDatoVista->setDato('{informacion}', '<p>Puede seleccionar acciones para los artículos,</p><p>ver botones.');
				// arma la tabla de datos a representar
				$oArticuloModelo->count();
				$this->_cantidad = $oArticuloModelo->getCantidad();
				// arma la tabla de datos a representar
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				break;  
			# ----> acción Descartar
			case 'Descartar':
				// carga el contenido html
				$oCargarVista->setCarga('datos', '/modulos/articulo/vista/descartarArticulos.html');
				$oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
				// ingresa los datos a representar en las alertas de la vista
				$oDatoVista->setDato('{alertaPeligro}',  '<b>Descarta artículos!!! </b>, confirme la acción.');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Artículos - Descarta bajas productos PLEX');
				$oDatoVista->setDato('{informacion}', '<p>Confirme la acción seleccionada para los artículos,</p><p>ver botones.');
				// carga los eventos (botones)
				$this->_aEventos = [
				"descartarConfirmar" => "/includes/vista/botonDescartarConf.html"
						];
				$oCargarVista->setCarga('aEventos', $this->_aEventos);
				// arma la tabla de datos a representar
				$oArticuloModelo->count();
				$this->_cantidad = $oArticuloModelo->getCantidad();

				/**
				 * Cuenta los artículos actualizables según los siguientes datos:
				 *
				 * codigo > 9999900000 ($oArticuloVO->setCodigo())
				 * id_proveedor > 1 ($oArticuloVO->setIdProveedor())
				 * codigo_b > 0 (esta la condición en la consulta)
				 * estado = 1 ($oArticuloVO->setEstado())
				*/
				$oArticuloVO->setCodigo(9999900000); // mayor que
				$oArticuloVO->setIdProveedor(1); // mayor que: 1 - SIN IDENTIFICAR
				$oArticuloVO->setEstado(1); // igual que
				// codigo_b > 0

				$oArticuloModelo->countActualizables($oArticuloVO);
				$oDatoVista->setDato('{cantActualizables}',  $oArticuloModelo->getCantidad());
				$oDatoVista->setDato('{cantDescartados}',  0);
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				break;
				# ----> acción Confirmar Actualizar
			case 'ConfirmarDes':
				// carga el contenido html
				$oCargarVista->setCarga('datos', '/modulos/articulo/vista/descartarArticulos.html');
				$oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
				// ingresa los datos a representar en las alertas de la vista
				$oDatoVista->setDato('{alertaSuceso}',  'Finalizó la acción solicitada con <b>EXITO !!!</b>.');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Artículos - Descarta bajas productos PLEX');
				$oDatoVista->setDato('{informacion}', '<p>Finalizó la acción solicitada para los artículos,</p><p>ver botones u opciones del menú.');
				// arma la tabla de datos a representar
				$oDatoVista->setDato('{cantActualizables}',  $_POST['cantActualizables']);
				$oDatoVista->setDato('{cantidad}', $_POST['cantidad']);
				$descarta = 0;

				/**
				 * Carga los artículos actualizables según los siguientes datos:
				 *
				 * codigo > 9999900000 ($oArticuloVO->setCodigo())
				 * id_proveedor > 1 ($oArticuloVO->setIdProveedor())
				 * codigo_b > 0 (esta la condición en la consulta)
				 * estado = 1 ($oArticuloVO->setEstado())
				 */
				$oArticuloVO->setCodigo(9999900000); // may0r que
				$oArticuloVO->setIdProveedor(1); // mayor que: 1 - SIN IDENTIFICAR
				$oArticuloVO->setEstado(1); // igual que
				// codigo_b > 0
				$this->_items = $oArticuloModelo->findAllActualizables($oArticuloVO);

				$cont = $this->_cantDescartados = 0;

				// Descarta articulos
				if ($oArticuloModelo->getCantidad()>0){ // Si hay artículos actualizables continúa
					$conx = DataBasePlex::getInstance(); // conecto a DB PLEX
					foreach ($this->_items as $this->_item){ // extraigo los datos de artículos x artículo
						$cont++;
						$query = "SELECT count(*) FROM productos WHERE IDProducto=".$this->_item['codigo'];
						$result = mysql_query($query) or die(mysql_error());
						$count = mysql_fetch_array($result);
						mysql_free_result($result);
						if ($count[0]==0){
							echo $cont." #".$this->_item['id']." [".$this->_item['codigo']."] ".$this->_item['nombre']."/ BAJA: ";
							$oArticuloVO->setId($this->_item['id']);
							$oArticuloModelo->delete($oArticuloVO);
							echo $oArticuloModelo->getCantidad()."<br>";
							$this->_cantDescartados++;
						}	
					}
					DataBasePlex::closeInstance();
				} // fin artículos actualizables
				$oDatoVista->setDato('{cantDescartados}',  $this->_cantDescartados);
				break;
			# ----> acción Listar
			case 'Listar':

				break;
			# ----> acción Confirmar Listar
			case "ConfirmarL":

				break;
			# ----> acción Agregar
			case 'Agregar':

				break;
			# ---> acción Confirmar Agregar
			case 'ConfirmarA':

				break;
			# ----> acción Editar
			case 'Editar':

				break;
			# ----> acción Confirmar Editar
			case 'ConfirmarE':

				break;
			# ----> acción Buscar
			case'Buscar':

				break;
			# ----> acción Confirmar Buscar
			case 'ConfirmarB':

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