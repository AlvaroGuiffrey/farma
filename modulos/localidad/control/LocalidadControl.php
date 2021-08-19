<?php
/**
 * Archivo de la clase control del módulo localidad.
 *
 * Archivo de la clase control del módulo localidad.
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
 * Clase control del módulo localidad.
 *
 * Clase control del módulo localidad que permite realizar
 * operaciones sobre la tabla localidades (CRUD y otras)
 * según la categoría asignada al usuario.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class LocalidadControl
{
	#Propiedades
	private $_html;
	private $_accion;
	private $_items;
	private $_cantidad;
	private $_id;
	private $_idProvincia;
	private $_nombreProvincia;
	private $_date;
	private $_estado;
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
		Clase::define('LocalidadModelo');
		Clase::define('LocalidadTabla');
		Clase::define('LocalidadDatos');
		Clase::define('ProvinciaModelo');
		Clase::define('ProvinciaSelect');
	
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
		if (isset($_POST['bt_agregar'])) $this->_accion = "Agregar";
		if (isset($_POST['bt_agregar_conf'])) $this->_accion = "ConfirmarA";
		if (isset($_POST['bt_editar'])) $this->_accion = "Editar";
		if (isset($_POST['bt_editar_conf'])) $this->_accion = "ConfirmarE";
		if (isset($_POST['bt_ver'])) $this->_accion = "Ver";
		if (isset($_POST['bt_listar'])) $this->_accion = "Listar";
		//if (isset($_POST['bt_buscar'])) $this->_accion = "Buscar";
		//if (isset($_POST['bt_buscar_conf'])) $this->_accion = "ConfirmarB";
		if (isset($_POST['bt_provincia_conf'])) $this->_accion = "Listar";
		if (isset($_POST['bt_actualizar_prov'])) $this->_accion = "Iniciar";
			
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
				"badge" => "/includes/vista/botonBadge.html"
		);
		// agrega boton listar a las acciones
		if ($this->_accion !== 'Iniciar'){
			$this->_aAcciones = array(
					"badge" => "/includes/vista/botonBadge.html",
					"botonAgregar" => "/includes/vista/botonAgregar.html",
					"botonListar" => "/includes/vista/botonListar.html",
					"botonActualizarProvincia" => "/includes/vista/botonActualizarProv.html"
			);
		}
		$oCargarVista->setCarga('aAcciones', $this->_aAcciones);
		// Ingresa los datos a representar en el html de la vista
		$oDatoVista = new DatoVista();
		$oDatoVista->setDato('{tituloPagina}', 'Localidades');
		$oDatoVista->setDato('{usuario}', $oLoginVO->getAlias());
		$oDatoVista->setDato('{tituloBadge}', 'Localidades ');
		// Alertas

		// Carga el contenido html y datos según la acción
		$oLocalidadVO = new LocalidadVO();
		$oLocalidadModelo = new LocalidadModelo();
		// Selector de acciones
		switch ($this->_accion){
			# ----> acción Iniciar
			case 'Iniciar':
				// carga el contenido html
				$oCargarVista->setCarga('opcion', '/modulos/localidad/vista/provinciaOpcion.html');
				// carga las alertas html
				$oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
				// ingresa los datos a representar en las alertas de la vista
				$oDatoVista->setDato('{alertaInfo}',  'Seleccione una provincia.');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Localidades - Selección de provincia');
				$oDatoVista->setDato('{informacion}', '<p>Debe seleccionar una provincia para realizar acciones</p><p>sobre la tabla localidades.');
				// carga los eventos (botones)
				$this->_aEventos = array(
						"provinciaConf" => "/includes/vista/botonProvinciaConf.html",
				);
				$oCargarVista->setCarga('aEventos', $this->_aEventos);
				// arma la tabla de datos a representar
				$this->_items = $oLocalidadModelo->findAll();
				$this->_cantidad = $oLocalidadModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				// arma el select de datos de la tabla provincias a representar
				$oProvinciaVO = new ProvinciaVO();
				$oProvinciaModelo = new ProvinciaModelo();
				$this->_items = $oProvinciaModelo->findAll();
				$this->_cantidad = $oProvinciaModelo->getCantidad();
				$this->_idProvincia = 0;
				$oDatoVista->setDato('{cantidadProvincia}', $this->_cantidad);
				ProvinciaSelect::armaSelect($this->_cantidad, $this->_items, $this->_accion, $this->_idMarca);
				$oCargarVista->setCarga('selectProvincia', '/modulos/localidad/selectProvincia.html');
				break;
	
			# ----> acción Listar
			case 'Listar':
				// carga el contenido html
				$oCargarVista->setCarga('contenido', '/includes/vista/listar.html');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Listado de Localidades');
				$oDatoVista->setDato('{informacion}', '<p>Listado de todas las localidades de una provincia.</p>
														<p>También puede seleccionar otras acciones para las</p>
														<p>localidades, ver botones.');
				// se busca la provincia seleccionada
				$this->_idProvincia = $_POST['provincia'];
				$oProvinciaVO = new ProvinciaVO();
				$oProvinciaModelo = new ProvinciaModelo();
				$oProvinciaVO->setId($this->_idProvincia);
				$oProvinciaModelo->find($oProvinciaVO);
				if ($oProvinciaModelo->getCantidad() > 0){
					$this->_nombreProvincia = $oProvinciaVO->getNombre();
				}else{
					$this->_nombreProvincia = 'NO IDENTIFICADA';
				}
				// arma la tabla de datos a representar
				$oLocalidadVO->setIdProvincia($this->_idProvincia);
				$this->_items = $oLocalidadModelo->findAllPorProvincia($oLocalidadVO);
				$this->_cantidad = $oLocalidadModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				LocalidadTabla::armaTabla($this->_cantidad, $this->_items, $this->_accion, $this->_idProvincia, $this->_nombreProvincia);
				$oCargarVista->setCarga('tabla', '/modulos/localidad/tabla.html');
				break;
			# ----> acción Agregar
			case 'Agregar':
				$this->_cantidad = 0;
				// carga el contenido html
				$oCargarVista->setCarga('datos', '/modulos/localidad/vista/agregarDatos.html');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Agregar Localidad');
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				$oDatoVista->setDato('{informacion}', '<p>Agrega una localidad a la provincia.</p>
														<p>También puede seleccionar otras acciones,</p>
														<p>ver botones.');
				// carga los eventos (botones)
				$this->_aEventos = array(
						"confirmar" => "/includes/vista/botonAgregarConf.html",
						"borrar" => "/includes/vista/botonBorrar.html"
				);
				$oCargarVista->setCarga('aEventos', $this->_aEventos);
				// ingresa datos de Provincia a representar en el contenido de la vista
				$this->_idProvincia = $_POST['provincia'];
				$this->_nombreProvincia = $_POST['nombreProvincia'];
				$oDatoVista->setDato('{provincia}', $this->_idProvincia);
				$oDatoVista->setDato('{nombreProvincia}', $this->_nombreProvincia);
				break;
			# ---> acción Confirmar Agregar
			case 'ConfirmarA':
				$oLocalidadVO->setNombre($_POST['nombre']);
				$oLocalidadVO->setCodPostal($_POST['codPostal']);
				$oLocalidadVO->setDepartamento($_POST['departamento']);
				$oLocalidadVO->setIdProvincia($_POST['provincia']);
				$oLocalidadVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
				$this->_date = date('Y-m-d H:i:s');
				$oLocalidadVO->setFechaAct($this->_date);
				$oLocalidadModelo->insert($oLocalidadVO);
				$this->_cantidad = $oLocalidadModelo->getCantidad();
				// carga el contenido html
				$oCargarVista->setCarga('datos', '/modulos/localidad/vista/verDatos.html');
				// ingresa los datos a representar en el Panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Ver Localidad Agregada');
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				// carga los eventos (botones)
				$this->_aEventos = array(
						"editar" => "/includes/vista/botonEditar.html",
				);
				$oCargarVista->setCarga('aEventos', $this->_aEventos);
				// ingresa datos de Provincia a representar en el contenido de la vista
				$this->_idProvincia = $_POST['provincia'];
				$this->_nombreProvincia = $_POST['nombreProvincia'];
				$oDatoVista->setDato('{provincia}', $this->_idProvincia);
				$oDatoVista->setDato('{nombreProvincia}', $this->_nombreProvincia);
				// ingresa los datos a representar en el contenido de la vista
				LocalidadDatos::cargaDatos($oLocalidadVO, $oDatoVista, $accion);
				// ingresa otros datos de acuerdo al resultado de la consulta
				if ($this->_cantidad==1){
					// carga el contenido html
					$oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
					// ingresa los datos a representar en el Panel de la vista
					$oDatoVista->setDato('{informacion}', '<p>Agregó una localidad con exito!!!.</p>
															<p>Seleccione alguna acción acción</p><p>u otra opción del menú.');
					// ingresa los datos a representar en las alertas de la vista
					$oDatoVista->setDato('{alertaSuceso}', 'Se agregaron los datos con EXITO!!!');
				}else{
					// carga el contenido html
					$oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
					// ingresa los datos a representar en el panel de la vista
					$oDatoVista->setDato('{informacion}', '<p>Precuación, falló el agregado de la localidad.</p>
															<p>Vuelva a intentar, o seleccione alguna acción</p><p>u otra opción del menú.');
					// ingresa los datos a representar en las alertas de la vista
					$oDatoVista->setDato('{alertaPeligro}', 'PELIGRO - No se agregaron los datos');
				}
				break;
			# ----> acción Editar
			case 'Editar':
				$oLocalidadVO->setId($_POST['bt_editar']);
				$oLocalidadModelo->find($oLocalidadVO);
				$this->_cantidad = $oLocalidadModelo->getCantidad();
				// carga el contenido html
				$oCargarVista->setCarga('datos', '/modulos/localidad/vista/editarDatos.html');
				// ingresa los datos a representar en el Panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Editar Localidad');
				$oDatoVista->setDato('{informacion}', '<p>Edita los datos de la localidad.</p>
														<p>Tambien puede seleccionar alguna acción</p>
														<p>u otra opción del menú.');
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				// carga los eventos (botones)
				$this->_aEventos = array(
						"confirmar" => "/includes/vista/botonEditarConf.html",
						"borrar" => "/includes/vista/botonBorrar.html"
				);
				$oCargarVista->setCarga('aEventos', $this->_aEventos);
				// ingresa datos de Provincia a representar en el contenido de la vista
				$this->_idProvincia = $_POST['provincia'];
				$this->_nombreProvincia = $_POST['nombreProvincia'];
				$oDatoVista->setDato('{provincia}', $this->_idProvincia);
				$oDatoVista->setDato('{nombreProvincia}', $this->_nombreProvincia);
				// ingresa los datos a representar en el contenido de la vista
				LocalidadDatos::cargaDatos($oLocalidadVO, $oDatoVista, $accion);
				break;
			# ----> acción Confirmar Editar
			case 'ConfirmarE':
				$oLocalidadVO->setId($_POST['id']);
				$oLocalidadVO->setNombre($_POST['nombre']);
				$oLocalidadVO->setCodPostal($_POST['codPostal']);
				$oLocalidadVO->setDepartamento($_POST['departamento']);
				$oLocalidadVO->setIdProvincia($_POST['provincia']);
				$oLocalidadVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
				$this->_date = date('Y-m-d H:i:s');
				$oLocalidadVO->setFechaAct($this->_date);
				$oLocalidadModelo->update($oLocalidadVO);
				$this->_cantidad = $oLocalidadModelo->getCantidad();
				// carga el contenido html
				$oCargarVista->setCarga('datos', '/modulos/localidad/vista/verDatos.html');
				// ingresa los datos a representar en el Panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Ver Localidad Editada');
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				// carga los eventos (botones)
				$this->_aEventos = array(
						"editar" => "/includes/vista/botonEditar.html",
				);
				$oCargarVista->setCarga('aEventos', $this->_aEventos);
				// ingresa datos de Provincia a representar en el contenido de la vista
				$this->_idProvincia = $_POST['provincia'];
				$this->_nombreProvincia = $_POST['nombreProvincia'];
				$oDatoVista->setDato('{provincia}', $this->_idProvincia);
				$oDatoVista->setDato('{nombreProvincia}', $this->_nombreProvincia);
				// ingresa los datos a representar en el contenido de la vista
				LocalidadDatos::cargaDatos($oLocalidadVO, $oDatoVista, $accion);
				// ingresa otros datos de acuerdo al resultado de la consulta
				if ($this->_cantidad==1){
					// carga el contenido html
					$oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
					// ingresa los datos a representar en el Panel de la vista
					$oDatoVista->setDato('{informacion}', '<p>Editó una localidad con exito!!!.</p>
															<p>Seleccione alguna acción acción</p><p>u otra opción del menú.');
					// ingresa los datos a representar en las alertas de la vista
					$oDatoVista->setDato('{alertaSuceso}', 'Se editaron los datos con EXITO!!!');
				}else{
					// carga el contenido html
					$oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
					// ingresa los datos a representar en el panel de la vista
					$oDatoVista->setDato('{informacion}', '<p>Precuación, falló la edición de la localidad.</p>
															<p>Vuelva a intentar, o seleccione alguna acción</p><p>u otra opción del menú.');
					// ingresa los datos a representar en las alertas de la vista
					$oDatoVista->setDato('{alertaPeligro}', 'PELIGRO - No se editaron los datos');
				}
				break;
			# ----> acción Buscar
			case'Buscar':

				break;
			# ----> acción Confirmar Buscar
			case 'ConfirmarB':

					break;
			# ----> acción Ver
			case 'Ver':
				$oLocalidadVO->setId($_POST['bt_ver']);
				$oLocalidadModelo->find($oLocalidadVO);
				$this->_cantidad = $oLocalidadModelo->getCantidad();
				// carga el contenido html
				$oCargarVista->setCarga('datos', '/modulos/localidad/vista/verDatos.html');
				// ingresa los datos a representar en el Panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Ver Localidad');
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				$oDatoVista->setDato('{informacion}', '<p>Muestra los datos de una localidad.</p>
														<p>Seleccione alguna acción para la localidad con botones</p>
														<p>u otra opción del menú.');
				// carga los eventos (botones)
				$this->_aEventos = array(
						"editar" => "/includes/vista/botonEditar.html",
				);
				$oCargarVista->setCarga('aEventos', $this->_aEventos);
				// ingresa los datos a representar en el contenido de la vista
				$this->_idProvincia = $_POST['provincia'];
				$this->_nombreProvincia = $_POST['nombreProvincia']; 
				$oDatoVista->setDato('{provincia}', $this->_idProvincia);
				$oDatoVista->setDato('{nombreProvincia}', $this->_nombreProvincia);
				LocalidadDatos::cargaDatos($oLocalidadVO, $oDatoVista, $accion);
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