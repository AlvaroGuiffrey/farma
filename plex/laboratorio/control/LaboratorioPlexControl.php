<?php
/**
 * Archivo de la clase control del módulo plex/laboratorio.
 *
 * Archivo de la clase control del módulo plex/laboratorio.
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
 * Clase control del módulo plex/laboratorio.
 *
 * Clase control del módulo plex/laboratorio que permite realizar
 * operaciones sobre la tabla plex/laboratorios (CRUD y otras)
 * según la categoría asignada al usuario.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class LaboratorioPlexControl
{
	#Propiedades
	private $_html;
	private $_accion;
	private $_items;
	private $_cantidad;
	private $_cantLaboratorios;
	private $_cantMarcas;
	private $_cantActualizar;
	private $_cantActualizados;
	private $_id;
	private $_idLaboratorio;
	private $_Nombre;
	private $_date;
	private $_estado;
	private $_con;
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
		Clase::define('MarcaModelo');
		Clase::define('DataBasePlex');
		Clase::define('LaboratorioPlexTabla');

		// Chequea login
		$oLoginControl = new LoginControl();
		$oLoginControl->chequearLogin($oLoginVO);
		$this->_accion = $accion;
		$this->accionControl($oLoginVO);
	}

	/**
	 * Nos permite ejecutar las acciones del módulo
	 * plex/laboratorio del sistema, de acuerdo a la categoría del
	 * usuario.
	 */
	private function accionControl($oLoginVO)
	{
		// Carga acciones del formulario
		//if (isset($_POST['bt_agregar'])) $this->_accion = "Agregar";
		//if (isset($_POST['bt_agregar_conf'])) $this->_accion = "ConfirmarA";
		//if (isset($_POST['bt_editar'])) $this->_accion = "Editar";
		//if (isset($_POST['bt_editar_conf'])) $this->_accion = "ConfirmarE";
		//if (isset($_POST['bt_ver'])) $this->_accion = "Ver";
		if (isset($_POST['bt_listar'])) $this->_accion = "Listar";
		//if (isset($_POST['bt_buscar'])) $this->_accion = "Buscar";
		//if (isset($_POST['bt_buscar_conf'])) $this->_accion = "ConfirmarB";
		if (isset($_POST['bt_actualizar'])) $this->_accion = "Actualizar";
		if (isset($_POST['bt_actualizar_conf'])) $this->_accion = "ConfirmarAct";
			
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
				"botonListar" => "/includes/vista/botonListar.html",
				"botonActualizar" => "/includes/vista/botonActualizar.html"
		);
		$oCargarVista->setCarga('aAcciones', $this->_aAcciones);
		// Ingresa los datos a representar en el html de la vista
		$oDatoVista = new DatoVista();
		$oDatoVista->setDato('{tituloPagina}', 'Laboratorios (PLEX)');
		$oDatoVista->setDato('{usuario}', $oLoginVO->getAlias());
		$oDatoVista->setDato('{tituloBadge}', 'Laboratorios ');
		// Alertas

		// Carga el contenido html y datos según la acción

		// Instancia las clases del modelo
		$this->_con = DataBasePlex::getInstance();
		$oMarcaVO = new MarcaVO();
		$oMarcaModelo = new MarcaModelo();
		
		// Selector de acciones
		switch ($this->_accion){
			# ----> acción Iniciar
			case 'Iniciar':
				// carga el contenido html
		
				// carga las alertas html
				$oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
				// ingresa los datos a representar en las alertas de la vista
				$oDatoVista->setDato('{alertaInfo}',  'Seleccione una acción con los botones.');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Laboratorios - (PLEX)');
				$oDatoVista->setDato('{informacion}', '<p>Debe seleccionar una acción a realizar</p>
														<p>sobre la tabla plex/laboratorios.</p>');
				// carga los eventos (botones)

				// arma la tabla de datos a representar
				$query = "SELECT count(*) FROM plex_laboratorios";
				$result = mysqli_query($this->_con, $query) or die(mysqli_error($this->_con));
				$count = mysqli_fetch_array($result); 
				mysqli_free_result($result);
				DataBasePlex::closeInstance();
				$this->_cantidad = $count[0];
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				break;

			# ----> acción Listar
			case 'Listar':
				// carga el contenido html
				$oCargarVista->setCarga('contenido', '/includes/vista/listar.html');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Listado de Laboratorios (PLEX)');
				$oDatoVista->setDato('{informacion}', '<p>Listado de todos los laboratorios de la base PLEX.</p>
														<p>También puede seleccionar otras acciones para los</p>
														<p>laboratorios, ver botones.</p>');
				// arma la tabla de datos a representar
				$query = "SELECT count(*) FROM plex_laboratorios";
				$result = mysqli_query($this->_con, $query) or die(mysqli_error($this->_con));
				$count = mysqli_fetch_array($result);
				mysqli_free_result($result);
				$this->_cantidad = $count[0];				
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				LaboratorioPlexTabla::armaTabla($this->_cantidad, $this->_accion, $oLoginVO);
				DataBasePlex::closeInstance();
				$oCargarVista->setCarga('tabla', '/plex/laboratorio/tabla.html');
				break;
			# ----> acción Actualizar
			case 'Actualizar':
				// carga el contenido html
				$oCargarVista->setCarga('datos', '/plex/laboratorio/vista/actualizarDatos.html');

				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Actualiza Marcas con Laboratorios (PLEX)');
				$oDatoVista->setDato('{informacion}', '<p>Actualiza marcas con los laboratorios de la base PLEX.</p>
														<p>También puede seleccionar otras acciones para los</p>
														<p>laboratorios, ver botones.</p>');

				// Ingresa los datos a representar en el html de la vista
				$query = "SELECT count(*) FROM plex_laboratorios";
				$result = mysqli_query($this->_con, $query) or die(mysqli_error($this->_con));
				$count = mysqli_fetch_array($result);
				mysqli_free_result($result);
				DataBasePlex::closeInstance();
				$this->_cantidad = $count[0];
				$this->_cantLaboratorios = $count[0];
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				$oDatoVista->setDato('{cantLaboratorios}', $this->_cantLaboratorios);
				$oMarcaModelo->findAll();
				$this->_cantMarcas = $oMarcaModelo->getCantidad();
				$oDatoVista->setDato('{cantMarcas}', $this->_cantMarcas);
				$this->_cantActualizar = $this->_cantLaboratorios - $this->_cantMarcas;
				$oDatoVista->setDato('{cantActualizar}', $this->_cantActualizar);
				$this->_cantActualizados = 0;
				$oDatoVista->setDato('{cantActualizados}', $this->_cantActualizados);
				// segun cantidad a actualizar agrega al html
				if ($this->_cantActualizar > 0){
					// carga las alertas html
					$oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
					// ingresa los datos a representar en las alertas de la vista
					$oDatoVista->setDato('{alertaInfo}',  'Actualiza tabla Marcas con tabla Laboratorios, confirme la acción.');
					// carga los eventos (botones)
					$this->_aEventos = array(
							"actualizarConf" => "/includes/vista/botonActualizarConf.html",
					);
					$oCargarVista->setCarga('aEventos', $this->_aEventos);
				}else{
					// carga las alertas html
					$oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
					// ingresa los datos a representar en las alertas de la vista
					$oDatoVista->setDato('{alertaAdvertencia}',  'No hay registros para actualizar.');
				}
				break;
			# ----> acción Confirmar Actualizar
			case 'ConfirmarAct': 
				// recibe datos por POST
				$this->_cantActualizar = $_POST['cantActualizar'];
				$this->_cantLaboratorios = $_POST['cantLaboratorios'];
				$this->_cantidad = $_POST['cantLaboratorios'];
				$this->_cantMarcas = $_POST['cantMarcas'];
				// carga el contenido html
				$oCargarVista->setCarga('datos', '/plex/laboratorio/vista/actualizarDatos.html');
				// carga las alertas html

				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Actualizó Marcas con Laboratorios (PLEX)');
				$oDatoVista->setDato('{informacion}', '<p>Actualizó marcas con los laboratorios de la base PLEX.</p>
														<p>También puede seleccionar otras acciones para los</p>
														<p>laboratorios, ver botones.</p>');
				// Ingresa los datos a representar en el html de la vista
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				$oDatoVista->setDato('{cantLaboratorios}', $this->_cantLaboratorios);
				$oDatoVista->setDato('{cantMarcas}', $this->_cantMarcas);
				$oDatoVista->setDato('{cantActualizar}', $this->_cantActualizar);
				// Actualiza los datos de marcas con laboratorios
				$this->_cantActualizados = 0;
				
				
				LaboratorioPlexTabla::armaTabla($this->_cantidad, $this->_accion, $oLoginVO);
				$this->_cantActualizados = LaboratorioPlexTabla::$cantActualizados;
				$oDatoVista->setDato('{cantActualizados}', $this->_cantActualizados);
				DataBasePlex::closeInstance();
				$oCargarVista->setCarga('tabla', '/plex/laboratorio/tabla.html');
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