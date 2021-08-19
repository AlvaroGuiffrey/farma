<?php
/**
 * Archivo de la clase control del módulo recibido.
 *
 * Archivo de la clase control del módulo recibido.
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
 * Clase control del módulo recibido.
 *
 * Clase control del módulo recibido que permite realizar
 * operaciones sobre la tabla artículos (CRUD y otras)
 * según la categoría asignada al usuario.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class RecibidoControl
{
	#Propiedades
	private $_html;
	private $_accion;
	private $_items;
	private $_cantidad;
	private $_id;
	private $_fechaDesde;
	private $_fechaHasta;
	private $_date;
	private $_idProveedor;
	private $_proveedor;
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
		// Define las clases
		Clase::define('CargarVista');
		Clase::define('DatoVista');
		Clase::define('CargarMenu');
		Clase::define('MotorVista');
		Clase::define('RecibidoModelo');
		Clase::define('RecibidoTabla');
		Clase::define('RecibidoDatos');
		Clase::define('ProveedorModelo');
		Clase::define('ProveedorSelect');

		// Chequea login
		$oLoginControl = new LoginControl();
		$oLoginControl->chequearLogin($oLoginVO);
		$this->_accion = $accion;
		$this->accionControl($oLoginVO);
	}

	/**
	 * Nos permite ejecutar las acciones del módulo
	 * recibido del sistema, de acuerdo a la categoría del
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
		if (isset($_POST['bt_listar_conf'])) $this->_accion = "ConfirmarL";
		if (isset($_POST['bt_actualizar_listado'])) $this->_accion = "ConfirmarL";
		if (isset($_POST['bt_buscar'])) $this->_accion = "Buscar";
		if (isset($_POST['bt_buscar_conf'])) $this->_accion = "ConfirmarB";
			
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
		"buscar" => "/includes/vista/botonBuscar.html"
				);
		$oCargarVista->setCarga('aAcciones', $this->_aAcciones);
		// Ingresa los datos a representar en el html de la vista
		$oDatoVista = new DatoVista();
		$oDatoVista->setDato('{dir}', $_SESSION['dir']);
		$oDatoVista->setDato('{tituloPagina}', 'Comprobantes Recibidos');
		$oDatoVista->setDato('{usuario}', $oLoginVO->getAlias());
		$oDatoVista->setDato('{tituloBadge}', 'Recibidos ');
		// Alertas

		// Carga el contenido html y datos según la acción
		$oRecibidoVO = new RecibidoVO();
		$oRecibidoModelo = new RecibidoModelo();
		// Selector de acciones
		switch ($this->_accion){
			# ----> acción Iniciar
			case 'Iniciar':
				// carga el contenido html
				$oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Ingreso Compras - Selección de acciones');
				$oDatoVista->setDato('{informacion}', '<p>Puede seleccionar acciones para los comprobantes recibidos por compras,</p>
													<p>ver botones.'
									);
				// ingresa los datos a representar en las alertas de la vista
				$oDatoVista->setDato('{alertaInfo}',  'Seleccione alguna acción con los botones.');
				// arma la tabla de datos a representar
				$this->_items = $oRecibidoModelo->findAll();
				$this->_cantidad = $oRecibidoModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				break;
			# ----> acción Listar
			case 'Listar':
				// carga el contenido html
				$oCargarVista->setCarga('opcion', '/modulos/recibido/vista/listarOpcion.html');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Listado de Comprobantes Recibidos');
				$oDatoVista->setDato('{informacion}', '<p>Listado de todos los comprobantes recibidos según opciones elegidas.</p><p>También puede seleccionar otras acciones</p><p>para los comprobantes recibidos, ver botones.');
				// carga los eventos (botones)
				$this->_aEventos = array(
				"listarConfirmar" => "/includes/vista/botonListarConfirmar.html",
				"borrar" => "/includes/vista/botonBorrar.html"
						);
				$oCargarVista->setCarga('aEventos', $this->_aEventos);
				// ingresa los datos a representar en las alertas de la vista

				// arma el select de datos de la tabla Proveedor a representar
				$oProveedorVO = new ProveedorVO();
				$oProveedorModelo = new ProveedorModelo();
				$this->_items = $oProveedorModelo->findAll();
				$this->_cantidad = $oProveedorModelo->getCantidad();
				$this->_idProveedor = 0;
				$oDatoVista->setDato('{cantidadProveedor}', $this->_cantidad);
				ProveedorSelect::armaSelect($this->_cantidad, $this->_items, $this->_accion, $this->_idProveedor);
				$oCargarVista->setCarga('selectProveedor', '/modulos/recibido/selectProveedor.html');
				// arma la tabla de datos a representar
				$this->_items = $oRecibidoModelo->findAll();
				$this->_cantidad = $oRecibidoModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				break;
			# ----> acción Confirmar Listar
			case "ConfirmarL":
				$this->_idProveedor = trim($_POST['proveedor']);
				$this->_fechaDesde = trim($_POST['fechaDesde']);
				$this->_fechaHasta = trim($_POST['fechaHasta']);
				// carga el contenido html

				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Listado de Comprobantes Recibidos');
				$oDatoVista->setDato('{informacion}', '<p>Listado de todos los comprobantes recibidos según opciones elegidas.</p>
												<p>También puede seleccionar otras acciones</p>
												<p>para los comprobantes recibidos, ver botones.'
									);
				// ingresa los datos a representar en las alertas de la vista

				// consulta tablas de la DB para titular la tabla a representar
				$oProveedorVO = new ProveedorVO();
				$oProveedorModelo = new ProveedorModelo();
				if ($this->_idProveedor != 0){
					$oProveedorVO->setId($this->_idProveedor);
					$oProveedorModelo->find($oProveedorVO);
				} else {
					$oProveedorVO->setId($this->_idProveedor);
					$oProveedorVO->setRazonSocial('TODOS');
					
				}
				// arma la tabla de datos a representar
				
				// realiza la consulta segun las opciones elegidas
				$this->_items = $oRecibidoModelo->findAllOpcionListado($this->_idProveedor,$this->_fechaDesde,$this->_fechaHasta);
				$this->_cantidad = $oRecibidoModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				RecibidoTabla::armaTabla($this->_cantidad, $this->_items, $this->_accion, $this->_idProveedor, $oProveedorVO->getRazonSocial(), $this->_fechaDesde, $this->_fechaHasta);
				$oCargarVista->setCarga('tabla', '/modulos/recibido/tabla.html');
				break;
			# ----> acción Agregar
			case 'Agregar':
				$this->_cantidad = 0;
				// carga el contenido html
				$oCargarVista->setCarga('datos', '/modulos/recibido/vista/agregarDatos.html');
				// carga los eventos (botones)
				$this->_aEventos = array(
				"agregarConf" => "/includes/vista/botonAgregarConf.html",
				);
				$oCargarVista->setCarga('aEventos', $this->_aEventos);
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Agregar Comprobante Recibido');
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				$oDatoVista->setDato('{informacion}', '<p>Agrega un comprobante recibido.</p><p>También puede seleccionar otras acciones</p><p>para los comprobante recibidos, ver botones.');
				// arma el select de datos de la tabla proveedores a representar
				$oProveedorVO = new ProveedorVO();
				$oProveedorModelo = new ProveedorModelo();
				$this->_items = $oProveedorModelo->findAll();
				$this->_cantidad = $oProveedorModelo->getCantidad();
				$this->_idProveedor = 0;
				$oDatoVista->setDato('{cantidadProveedor}', $this->_cantidad);
				ProveedorSelect::armaSelect($this->_cantidad, $this->_items, $this->_accion, $this->_idProveedor);
				$oCargarVista->setCarga('selectProveedor', '/modulos/recibido/selectProveedor.html');
	
				break;
			# ---> acción Confirmar Agregar
			case 'ConfirmarA':
				$oRecibidoVO->setComprobante($_POST['comprobante']);
				$oRecibidoVO->setFecha($_POST['fecha']);
				$oRecibidoVO->setIdProveedor($_POST['proveedor']);
				$oRecibidoVO->setGravado($_POST['gravado']);
				$oRecibidoVO->setExento($_POST['exento']);
				$oRecibidoVO->setRetencionDgi($_POST['retencionDgi']);
				$oRecibidoVO->setPercepcionDgi($_POST['percepcionDgi']);
				$oRecibidoVO->setRetencionRenta($_POST['retencionRenta']);
				$oRecibidoVO->setPercepcionRenta($_POST['percepcionRenta']);
				$oRecibidoVO->setOtros($_POST['otros']);
				$oRecibidoVO->setIva($_POST['iva']);
				$oRecibidoVO->setTotal($_POST['total']);
				$oRecibidoVO->setComentario($_POST['comentario']);
				$total = $_POST['gravado']+$_POST['exento']+$_POST['retencionDgi']+$_POST['percepcionDgi']+$_POST['retencionRenta']+$_POST['percepcionRenta']+$_POST['otros']+$_POST['iva'];
				$oRecibidoVO->setConsistencia($_POST['consistencia']);
				$oRecibidoVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
				$this->_date = date('Y-m-d H:i:s');
				$oRecibidoVO->setFechaAct($this->_date);
				// Verifica suma de importes
				if ($_POST['total'] == $total){
					$oRecibidoModelo->insert($oRecibidoVO);
					$this->_cantidad = $oRecibidoModelo->getCantidad();
					$oRecibidoVO->setId($oRecibidoModelo->getLastId());
				}else{
					$this->_cantidad = 0;
				}
				// carga el contenido html
				$oCargarVista->setCarga('datos', '/modulos/recibido/vista/verDatos.html');
				// carga los eventos (botones)
				$this->_aEventos = array(
				"editar" => "/includes/vista/botonEditar.html",
				"agregarPartida" => "/includes/vista/botonAgregarRenglon.html",
				);
				$oCargarVista->setCarga('aEventos', $this->_aEventos);
				// ingresa los datos a representar en el Panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Ver Comprobante Recibido Agregado');
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				// ingresa los datos a representar en el contenido de la vista
				$this->cargaDatos($oRecibidoVO, $oDatoVista);
				// ingresa otros datos de acuerdo al resultado de la consulta
				if ($this->_cantidad == 1){
				// carga el contenido html
					$oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
				// ingresa los datos a representar en el Panel de la vista
					$oDatoVista->setDato('{informacion}', '<p>Agregó un comprobante recibido con exito!!!.</p>
														<p>Seleccione alguna acción para los comprobantes recibidos,</p>
														<p>o alguna opción del menú.'
										);
				// ingresa los datos a representar en las alertas de la vista
					$oDatoVista->setDato('{alertaSuceso}', 'Se agregó comprobante recibido con EXITO!!!');
				}else{
				// carga el contenido html
					$oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
				// ingresa los datos a representar en el panel de la vista
					$oDatoVista->setDato('{informacion}', '<p>Precaución, falló agregar comprobante recibido.</p>
															<p>Vuelva a intentar, o seleccione alguna acción</p>
															<p>u otra opción del menú.'
										);
				// ingresa los datos a representar en las alertas de la vista
					$oDatoVista->setDato('{alertaPeligro}', 'PELIGRO - No se agregaron datos, verifique suma de importes');
				}
				break;
			# ----> acción Editar
			case 'Editar':
				$oRecibidoVO->setId($_POST['bt_editar']);
				$oRecibidoModelo->find($oRecibidoVO);
				$this->_cantidad = $oRecibidoModelo->getCantidad();
				// carga el contenido html
				$oCargarVista->setCarga('contenido', '/includes/vista/editar.html');
				$oCargarVista->setCarga('datos', '/modulos/recibido/vista/editarDatos.html');
				// carga los eventos (botones)
				$this->_aEventos = array(
				"editarConf" => "/includes/vista/botonEditarConf.html",
				"borrar" => "/includes/vista/botonBorrar.html",
				);
				$oCargarVista->setCarga('aEventos', $this->_aEventos);
				// ingresa los datos a representar en el Panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Editar Comprobante Recibido');
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				$oDatoVista->setDato('{informacion}', '<p>Edita un comprobante recibido.</p><p>También puede seleccionar otras acciones</p><p>para los comprobante recibidos, ver botones.');
				// ingresa los datos a representar en el contenido de la vista
				RecibidoDatos::cargaDatos($oRecibidoVO, $oDatoVista, $this->_accion);
				// arma el select de datos de la tabla proveedores a representar
				$oProveedorVO = new ProveedorVO();
				$oProveedorModelo = new ProveedorModelo();
				$this->_items = $oProveedorModelo->findAll();
				$this->_cantidad = $oProveedorModelo->getCantidad();
				$this->_idProveedor = 0;
				$oDatoVista->setDato('{cantidadProveedor}', $this->_cantidad);
				ProveedorSelect::armaSelect($this->_cantidad, $this->_items, $this->_accion, $this->_idProveedor);
				$oCargarVista->setCarga('selectProveedor', '/caro/modulos/recibido/selectProveedor.html');
				break;
				
			# ----> acción Confirmar Editar
			case 'ConfirmarE':
				$oRecibidoVO->setId($_POST['id']);
				$oRecibidoVO->setComprobante($_POST['comprobante']);
				$oRecibidoVO->setFecha($_POST['fecha']);
				$oRecibidoVO->setIdProveedor($_POST['proveedor']);
				$oRecibidoVO->setGravado($_POST['gravado']);
				$oRecibidoVO->setExento($_POST['exento']);
				$oRecibidoVO->setRetencionDgi($_POST['retencionDgi']);
				$oRecibidoVO->setPercepcionDgi($_POST['percepcionDgi']);
				$oRecibidoVO->setRetencionRenta($_POST['retencionRenta']);
				$oRecibidoVO->setPercepcionRenta($_POST['percepcionRenta']);
				$oRecibidoVO->setOtros($_POST['otros']);
				$oRecibidoVO->setIva($_POST['iva']);
				$oRecibidoVO->setTotal($_POST['total']);
				$oRecibidoVO->setComentario($_POST['comentario']);
				$total = $_POST['gravado']+$_POST['exento']+$_POST['retencionDgi']+$_POST['percepcionDgi']+$_POST['retencionRenta']+$_POST['percepcionRenta']+$_POST['otros']+$_POST['iva'];
				if ($_POST['consistencia']=="SI"){
					$oRecibidoVO->setConsistencia(1);
				}else{
					$oRecibidoVO->setConsistencia(0);
				}
				$oRecibidoVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
				$this->_date = date('Y-m-d H:i:s');
				$oRecibidoVO->setFechaAct($this->_date);
				// Verifica suma de importes
				if ($_POST['total'] == $total){
					$oRecibidoModelo->update($oRecibidoVO);
					$this->_cantidad = $oRecibidoModelo->getCantidad();
				}else{
					$this->_cantidad = 0;
				}

				// carga el contenido html
				$oCargarVista->setCarga('contenido', '/includes/vista/ver.html');
				$oCargarVista->setCarga('datos', '/modulos/recibido/vista/verDatos.html');
				// carga los eventos (botones)
				$this->_aEventos = array(
				"editar" => "/includes/vista/botonEditar.html",
				);
				$oCargarVista->setCarga('aEventos', $this->_aEventos);
				// ingresa los datos a representar en el Panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Ver Comprobante Recibido Editado');
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				// ingresa los datos a representar en el contenido de la vista
				RecibidoDatos::cargaDatos($oRecibidoVO, $oDatoVista, $this->_accion);
				// ingresa otros datos de acuerdo al resultado de la consulta
				if ($this->_cantidad==1){
					// carga el contenido html
					$oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
					// ingresa los datos a representar en el Panel de la vista
					$oDatoVista->setDato('{informacion}', '<p>Editó un artículo con exito!!!.</p>
														<p>Seleccione alguna acción para los artículos,</p>
														<p>o alguna opción del menú.'
										);
					// ingresa los datos a representar en las alertas de la vista
					$oDatoVista->setDato('{alertaSuceso}', 'Se editó el artículo con EXITO!!!');
				}else{
				 // carga el contenido html
				 $oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
				 // ingresa los datos a representar en el panel de la vista
				 $oDatoVista->setDato('{informacion}', '<p>Precuación, falló la edición del artículo.</p>
				 										<p>Vuelva a intentar, o seleccione alguna acción</p>
				 										<p>u otra opción del menú.'
									);
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
				$oRecibidoVO->setId($_POST['bt_ver']);
				$oRecibidoModelo->find($oRecibidoVO);
				$this->_cantidad = $oRecibidoModelo->getCantidad();
				// carga el contenido html
				$oCargarVista->setCarga('datos', '/modulos/recibido/vista/verDatos.html');
				// carga los eventos (botones)
				$this->_aEventos = array(
					"editar" => "/includes/vista/botonEditar.html",
				);
				$oCargarVista->setCarga('aEventos', $this->_aEventos);
				// ingresa los datos a representar en el Panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Ver Comprobante Recibido');
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				$oDatoVista->setDato('{informacion}', '<p>Muestra los datos de un comprobante recibido.</p>
													<p>Seleccione alguna acción para el comprobante con botones</p>
													<p>u otra opción del menú.'
									);
				// ingresa los datos a representar en el contenido de la vista
				$this->cargaDatos($oRecibidoVO, $oDatoVista);
				break;
			# ----> acción por Defecto (ninguna acción seleccionada)
			default:

				break;
		}

	// instancio el motor de la vista y muestro la vista
	$oMotorVista = new MotorVista($oDatoVista->getAllDatos(), $oCargarVista->getAllCargas());
	$oMotorVista->mostrarVista();
	
	}
	
	// Función para cargar datos de RecibidoVO a la vista
	public function cargaDatos($oRecibidoVO, $oDatoVista)
	{
		$oDatoVista->setDato('{id}', $oRecibidoVO->getId());
		$oDatoVista->setDato('{comprobante}', $oRecibidoVO->getComprobante());
		$oDatoVista->setDato('{fecha}', $oRecibidoVO->getFecha());
		$oDatoVista->setDato('{idProveedor}', $oRecibidoVO->getIdProveedor());
		$oProveedorVO = new ProveedorVO();
		$oProveedorModelo = new ProveedorModelo();
		$oProveedorVO->setId($oRecibidoVO->getIdProveedor());
		$oProveedorModelo->find($oProveedorVO);
		$oDatoVista->setDato('{proveedor}', $oProveedorVO->getRazonSocial());
		$oDatoVista->setDato('{gravado}', $oRecibidoVO->getGravado());
		$oDatoVista->setDato('{exento}', $oRecibidoVO->getExento());
		$oDatoVista->setDato('{retencionDgi}', $oRecibidoVO->getRetencionDgi());
		$oDatoVista->setDato('{percepcionDgi}', $oRecibidoVO->getPercepcionDgi());
		$oDatoVista->setDato('{retencionRenta}', $oRecibidoVO->getRetencionRenta());
		$oDatoVista->setDato('{percepcionRenta}', $oRecibidoVO->getPercepcionRenta());
		$oDatoVista->setDato('{otros}', $oRecibidoVO->getOtros());
		$oDatoVista->setDato('{iva}', $oRecibidoVO->getIva());
		$oDatoVista->setDato('{total}', $oRecibidoVO->getTotal());
		$oDatoVista->setDato('{comentario}', $oRecibidoVO->getComentario());
		if ($oRecibidoVO->getConsistencia() == 1){
			$oDatoVista->setDato('{consistencia}', 'SI');
		}else{
			$oDatoVista->setDato('{consistencia}', 'NO');
		}
		return $oDatoVista;
	}
}
?>