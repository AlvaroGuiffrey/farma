<?php
/**
 * Archivo de la clase control del módulo rubro.
 *
 * Archivo de la clase control del módulo rubro.
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
 * Clase control del módulo rubro.
 *
 * Clase control del módulo rubro que permite realizar
 * operaciones sobre la tabla rubros (CRUD y otras)
 * según la categoría asignada al usuario.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class RubroControl
{
	#Propiedades
	private $_html;
	private $_accion;
	private $_items;
	private $_cantidad;
	private $_id;
	private $_date;
	public $tabla;

	#Métodos
	/**
	* Verifica el login del usuario y nos envia a la
	* función que ejecuta las acciones en el módulo.
	*/
	public function inicio($oLoginVO, $accion)
	{
	date_default_timezone_set('America/Argentina/Buenos_Aires');
	// Define las clases
	Clase::define('CargarVista');
	Clase::define('DatoVista');
	Clase::define('CargarMenu');
	Clase::define('MotorVista');
	Clase::define('RubroModelo');
	Clase::define('RubroDatos');
	Clase::define('RubroTabla');
	Clase::define('RubroSelect');
	// Chequea login
	$oLoginControl = new LoginControl();
	$oLoginControl->chequearLogin($oLoginVO);
	$this->_accion = $accion;
	$this->accionControl($oLoginVO);
	}

	/**
	* Nos permite ejecutar las acciones del módulo
	* rubro del sistema, de acuerdo a la categoría del
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

	// Carga los archivos html para la vista
		$oCargarVista = new CargarVista();
		$oCargarVista->setCarga('pagina', '/includes/vista/pagina.html');
		$oCargarVista->setCarga('botones', '/includes/vista/botonesFooter.html');
	// Carga el menú de la vista según la categoría del usuario
		$oCargarVista->setCarga('menu', CargarMenu::selectMenu($oLoginVO->getCategoria()));	
	// Ingresa los datos a representar en el html de la vista
		$oDatoVista = new DatoVista();
		$oDatoVista->setDato('{tituloPagina}', 'Rubros');
		$oDatoVista->setDato('{usuario}', $oLoginVO->getAlias());
		$oDatoVista->setDato('{tituloBadge}', 'Rubros ');
	// Alertas
			 
	// Carga el contenido html y datos según la acción
		$oRubroVO = new RubroVO();
		$oRubroModelo = new RubroModelo();
	// Selector de acciones		
		switch ($this->_accion){
		# ----> acción Select
			case 'Select':
				// carga el contenido html
				$oCargarVista->setCarga('contenido', '/includes/vista/listar.html');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Selección de Rubro');
				$oDatoVista->setDato('{informacion}', '<p>Selecciona un rubro.</p><p>También puede seleccionar otras acciones</p><p>para los rubros, ver botones.');
				// arma la tabla de datos a representar
				$this->_items = $oRubroModelo->findAll();
				$this->_cantidad = $oRubroModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				RubroSelect::armaSelect($this->_cantidad, $this->_items);
				$oCargarVista->setCarga('tabla', '/modulos/rubro/selectRubro.html');
				break;
		# ----> acción Listar
			case 'Listar':
			// carga el contenido html
				$oCargarVista->setCarga('contenido', '/includes/vista/listar.html');
			// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Listado de Rubros');
				$oDatoVista->setDato('{informacion}', '<p>Listado de todos los rubros.</p><p>También puede seleccionar otras acciones</p><p>para los rubros, ver botones.');
			// arma la tabla de datos a representar
				$this->_items = $oRubroModelo->findAll();
				$this->_cantidad = $oRubroModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				RubroTabla::armaTabla($this->_cantidad, $this->_items);
				$oCargarVista->setCarga('tabla', '/modulos/rubro/tabla.html');
				break;
		# ----> acción Agregar		
			case 'Agregar':
				$this->_cantidad = 0;
			// carga el contenido html
				$oCargarVista->setCarga('contenido', '/includes/vista/agregar.html');
				$oCargarVista->setCarga('datos', '/modulos/rubro/vista/agregarDatos.html');
			// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Agregar Rubro');
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				$oDatoVista->setDato('{informacion}', '<p>Agrega un rubro.</p><p>También puede seleccionar otras acciones</p><p>para los rubros, ver botones.');
				break;
		# ---> acción Confirmar Agregar
			case 'ConfirmarA':
				$oRubroVO->setNombre($_POST['nombre']);
				$oRubroVO->setComentario($_POST['comentario']);
				$oRubroVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
				$this->_date = date('Y-m-d H:i:s');
				$oRubroVO->setFechaAct($this->_date);
				$oRubroModelo->insert($oRubroVO);
				$this->_cantidad = $oRubroModelo->getCantidad();
				// carga el contenido html
				$oCargarVista->setCarga('contenido', '/includes/vista/ver.html');
				$oCargarVista->setCarga('datos', '/modulos/rubro/vista/verDatos.html');
				// ingresa los datos a representar en el Panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Ver Rubro Agregado');
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				// ingresa los datos a representar en el contenido de la vista
				RubroDatos::cargaDatos($oRubroVO, $oDatoVista, $accion);
				// ingresa otros datos de acuerdo al resultado de la consulta
				if ($this->_cantidad==1){
				// carga el contenido html
					$oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
				// ingresa los datos a representar en el Panel de la vista
					$oDatoVista->setDato('{informacion}', '<p>Agregó un rubro con exito!!!.</p><p>Seleccione alguna acción para los rubros,</p><p>o alguna opción del menú.');
				// ingresa los datos a representar en las alertas de la vista
					$oDatoVista->setDato('{alertaSuceso}', 'Se agregó rubro con EXITO!!!');
				}else{
				// carga el contenido html
					$oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
				// ingresa los datos a representar en el panel de la vista
					$oDatoVista->setDato('{informacion}', '<p>Precuación, falló agregar rubro.</p><p>Vuelva a intentar, o seleccione alguna acción</p><p>u otra opción del menú.');
				// ingresa los datos a representar en las alertas de la vista
					$oDatoVista->setDato('{alertaPeligro}', 'PELIGRO - No se agregaron datos');
				}
				break;
		# ----> acción Editar
			case 'Editar':
				$oRubroVO->setId($_POST['bt_editar']);
				$oRubroModelo->find($oRubroVO);
				$this->_cantidad = $oRubroModelo->getCantidad();
			// carga el contenido html
				$oCargarVista->setCarga('contenido', '/includes/vista/editar.html');
				$oCargarVista->setCarga('datos', '/modulos/rubro/vista/editarDatos.html');
			// ingresa los datos a representar en el Panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Editar Rubro');
				$oDatoVista->setDato('{informacion}', '<p>Edita los datos del rubro.</p><p>Tambien puede seleccionar alguna acción</p><p>u otra opción del menú.');
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
			// ingresa los datos a representar en el contenido de la vista
				RubroDatos::cargaDatos($oRubroVO, $oDatoVista, $accion);
				break;
		# ----> acción Confirmar Editar
			case 'ConfirmarE':
				$oRubroVO->setId($_POST['id']);
				$oRubroVO->setNombre($_POST['nombre']);
				$oRubroVO->setComentario($_POST['comentario']);
				$oRubroVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
				$this->_date = date('Y-m-d H:i:s');
				$oRubroVO->setFechaAct($this->_date);
				$oRubroModelo->update($oRubroVO);
				$this->_cantidad = $oRubroModelo->getCantidad();
				// carga el contenido html
				$oCargarVista->setCarga('contenido', '/includes/vista/ver.html');
				$oCargarVista->setCarga('datos', '/modulos/rubro/vista/verDatos.html');				
				// ingresa los datos a representar en el Panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Ver Rubro Editado');
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				// ingresa los datos a representar en el contenido de la vista
				RubroDatos::cargaDatos($oRubroVO, $oDatoVista, $accion);				
				// ingresa otros datos de acuerdo al resultado de la consulta
				if ($this->_cantidad==1){
				// carga el contenido html
					$oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
				// ingresa los datos a representar en el Panel de la vista
					$oDatoVista->setDato('{informacion}', '<p>Editó un rubro con exito!!!.</p><p>Seleccione alguna acción para los rubros,</p><p>o alguna opción del menú.');
				// ingresa los datos a representar en las alertas de la vista	
					$oDatoVista->setDato('{alertaSuceso}', 'Se editó el rubro con EXITO!!!');
				}else{
				// carga el contenido html
					$oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
				// ingresa los datos a representar en el panel de la vista
					$oDatoVista->setDato('{informacion}', '<p>Precuación, falló la edición del rubro.</p><p>Vuelva a intentar, o seleccione alguna acción</p><p>u otra opción del menú.');
				// ingresa los datos a representar en las alertas de la vista
					$oDatoVista->setDato('{alertaPeligro}', 'PELIGRO - No se editaron los datos');
				}
					break;
		# ----> acción Ver
			case 'Ver':
				$oRubroVO->setId($_POST['bt_ver']);
				$oRubroModelo->find($oRubroVO);
				$this->_cantidad = $oRubroModelo->getCantidad();
			// carga el contenido html
				$oCargarVista->setCarga('contenido', '/includes/vista/ver.html');
				$oCargarVista->setCarga('datos', '/modulos/rubro/vista/verDatos.html');
			// ingresa los datos a representar en el Panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Ver Rubro');
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				$oDatoVista->setDato('{informacion}', '<p>Muestra los datos de un rubro.</p><p>Seleccione alguna acción para el rubro con botones</p><p>u otra opción del menú.');
			// ingresa los datos a representar en el contenido de la vista
				RubroDatos::cargaDatos($oRubroVO, $oDatoVista, $accion);
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