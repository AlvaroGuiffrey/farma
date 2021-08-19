<?php
/**
 * Archivo de la clase control del módulo marca.
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
 * Clase control del módulo marca.
 *
 * Clase control del módulo marca que permite realizar
 * operaciones sobre la tabla marcas (CRUD y otras)
 * según la categoría asignada al usuario.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class MarcaControl
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
		Clase::define('MarcaModelo');
		Clase::define('MarcaDatos');
		Clase::define('MarcaTabla');
		// Chequea login
		$oLoginControl = new LoginControl();
		$oLoginControl->chequearLogin($oLoginVO);
		$this->_accion = $accion;
		$this->accionControl($oLoginVO);
	}

	/**
	 * Nos permite ejecutar las acciones del módulo
	 * marca del sistema, de acuerdo a la categoría del
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

		// Ingresa los datos a representar en el html de la vista
		$oDatoVista = new DatoVista();
		$oDatoVista->setDato('{tituloPagina}', 'Marcas');
		$oDatoVista->setDato('{usuario}', $oLoginVO->getAlias());
		$oDatoVista->setDato('{tituloBadge}', 'Marcas ');
   		// Alertas
		 
		// Carga el menú html segun la categoría del usuario
		$oCargarVista->setCarga('menu', CargarMenu::selectMenu($oLoginVO->getCategoria()));

		// Carga el contenido html y datos según la acción 
		$oMarcaVO = new MarcaVO();
		$oMarcaModelo = new MarcaModelo();
	
		// Selector de acciones
		switch ($this->_accion){
			# ----> Acción para listar las marcas
			case 'Listar':
				// carga el contenido html
				$oCargarVista->setCarga('contenido', '/includes/vista/listar.html');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Listado de Marcas');
				$oDatoVista->setDato('{informacion}', '<p>Listado de todas las marcas.</p><p>También puede seleccionar otras acciones</p><p>para las marcas, ver botones.');
				$this->_items = $oMarcaModelo->findAll();
				$this->_cantidad = $oMarcaModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				MarcaTabla::armaTabla($this->_cantidad, $this->_items);
				$oCargarVista->setCarga('tabla', '/modulos/marca/tabla.html');
				break; 
			# ---> Acción para agregar una marca	
			case 'Agregar':
				$this->_cantidad = 0;
				// carga el contenido html
				$oCargarVista->setCarga('contenido', '/includes/vista/agregar.html');
				$oCargarVista->setCarga('datos', '/modulos/marca/vista/agregarDatos.html');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Agregar Marca');
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				$oDatoVista->setDato('{informacion}', '<p>Agrega una marca.</p><p>También puede seleccionar otras acciones</p><p>para las marcas, ver botones.');
				break;
			# ---> Acción para confirmar el agregado de una marca	
			case 'ConfirmarA':
				$oMarcaVO->setNombre($_POST['nombre']);
				$oMarcaVO->setComentario($_POST['comentario']);
				$oMarcaVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
				$this->_date = date('Y-m-d H:i:s');
				$oMarcaVO->setFechaAct($this->_date);
				$oMarcaModelo->insert($oMarcaVO); 
				$this->_cantidad = $oMarcaModelo->getCantidad();
				$oMarcaVO->setId($oMarcaModelo->getLastId());
				// carga el contenido html
				$oCargarVista->setCarga('contenido', '/includes/vista/ver.html');
				$oCargarVista->setCarga('datos', '/modulos/marca/vista/verDatos.html');
				// ingresa los datos a representar en el Panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Ver Marca Agregada');
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				// ingresa los datos a representar en el contenido de la vista
				MarcaDatos::cargaDatos($oMarcaVO, $oDatoVista, $accion);
				if ($this->_cantidad==1){
					$oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
					$oDatoVista->setDato('{informacion}', '<p>Agregó una marca con exito!!!.</p><p>Seleccione alguna acción para las marcas,</p><p>o alguna opción del menú.');
					$oDatoVista->setDato('{alertaSuceso}', 'Se agregó marca con EXITO!!!');
				}else{
					$oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
					$oDatoVista->setDato('{informacion}', '<p>Precuación, falló agregar marca.</p><p>Vuelva a intentar, o seleccione alguna acción</p><p>u otra opción del menú.');
					$oDatoVista->setDato('{alertaPeligro}', 'PELIGRO - No se agregaron datos');
				} 
				break;		
			# ---> Acción para editar una marca		
			case 'Editar':
				$oMarcaVO->setId($_POST['bt_editar']);
				$oMarcaModelo->find($oMarcaVO);
				$this->_cantidad = $oMarcaModelo->getCantidad();
				// carga el contenido html
				$oCargarVista->setCarga('contenido', '/includes/vista/editar.html');
				$oCargarVista->setCarga('datos', '/modulos/marca/vista/editarDatos.html');
				// ingresa los datos a representar en el Panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Editar Marca');
				$oDatoVista->setDato('{informacion}', '<p>Edita los datos de la marca.</p><p>Tambien puede seleccionar alguna acción</p><p>u otra opción del menú.');
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				MarcaDatos::cargaDatos($oMarcaVO, $oDatoVista, $accion);				
				break;
			# ---> Acción para confirmar la edición de una marca
			case 'ConfirmarE':
				$oMarcaVO->setId($_POST['id']);
				$oMarcaVO->setNombre($_POST['nombre']);
				$oMarcaVO->setComentario($_POST['comentario']);
				$oMarcaVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
				$this->_date = date('Y-m-d H:i:s');
				$oMarcaVO->setFechaAct($this->_date);
				$oMarcaModelo->update($oMarcaVO); 
				$this->_cantidad = $oMarcaModelo->getCantidad();
				// carga el contenido html
				$oCargarVista->setCarga('contenido', '/includes/vista/ver.html');
				$oCargarVista->setCarga('datos', '/modulos/marca/vista/verDatos.html');
				// ingresa los datos a representar en el Panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Ver Marca Editada');
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				// ingresa los datos a representar en el contenido de la vista
				MarcaDatos::cargaDatos($oMarcaVO, $oDatoVista, $accion);
				if ($this->_cantidad==1){
					// carga el contenido html
					$oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
					$oDatoVista->setDato('{informacion}', '<p>Editó una marca con exito!!!.</p><p>Seleccione alguna acción para las marcas,</p><p>o alguna opción del menú.');
					$oDatoVista->setDato('{alertaSuceso}', 'Se editó la marca con EXITO!!!');
				}else{
					// carga el contenido html
					$oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
					$oDatoVista->setDato('{informacion}', '<p>Precuación, falló la edición de la marca.</p><p>Vuelva a intentar, o seleccione alguna acción</p><p>u otra opción del menú.');
					$oDatoVista->setDato('{alertaPeligro}', 'PELIGRO - No se editaron los datos');
				}
				break;		
			# ---> Acción para ver una marca		
			case 'Ver':
				$oMarcaVO->setId($_POST['bt_ver']);
				$oMarcaModelo->find($oMarcaVO);
				$this->_cantidad = $oMarcaModelo->getCantidad();
				// carga el contenido html
				$oCargarVista->setCarga('contenido', '/includes/vista/ver.html');
				$oCargarVista->setCarga('datos', '/modulos/marca/vista/verDatos.html');
				// ingresa los datos a representar en el Panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Ver Marca');
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				$oDatoVista->setDato('{informacion}', '<p>Muestra los datos de una marca.</p><p>Seleccione alguna acción para la marca con botones</p><p>u otra opción del menú.');
				
				// ingresa los datos a representar en el contenido de la vista
				MarcaDatos::cargaDatos($oMarcaVO, $oDatoVista, $accion);				
				break;
			default:

				break;
		}
		
		// instancio el motor de la vista y muestro la vista
		$oMotorVista = new MotorVista($oDatoVista->getAllDatos(), $oCargarVista->getAllCargas());
		$oMotorVista->mostrarVista();
		
	}
	
}
?>