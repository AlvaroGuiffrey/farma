<?php
/**
 * Archivo de la clase control del módulo proveedor.
 *
 * Archivo de la clase control del módulo proveedor.
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
 * Clase control del módulo proveedor.
 *
 * Clase control del módulo proveedor que permite realizar
 * operaciones sobre la tabla proveedores (CRUD y otras)
 * según la categoría asignada al usuario.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class ProveedorControl
{
	#Propiedades
	private $_html;
	private $_accion;
	private $_items;
	private $_item;
	private $_cantidad;
	private $_id;
	private $_codigo;
	private $_idLocalidad;
	private $_codPostalLoc;
	private $_codPostalPro;
	private $_date;
	private $_estado;
	private $_error;
	private $_aAcciones = array();
	private $_aEventos = array();
	private $_aCodPostal = array();
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
		Clase::define('ProveedorModelo');
		Clase::define('ProveedorDatos');
		Clase::define('ProveedorTabla');
		Clase::define('LocalidadModelo');
		Clase::define('LocalidadSelect');
		Clase::define('ProvinciaModelo');
		Clase::define('ArticuloModelo');
		Clase::define('ProductoModelo');
		Clase::define('AfipResponsablesModelo');
		Clase::define('AfipResponsablesSelect');

		// Chequea login
		$oLoginControl = new LoginControl();
		$oLoginControl->chequearLogin($oLoginVO);
		$this->_accion = $accion;
		$this->accionControl($oLoginVO);
	}

	/**
	 * Nos permite ejecutar las acciones del módulo
	 * proveedor del sistema, de acuerdo a la categoría del
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
		if (isset($_POST['bt_etiquetar'])) $this->_accion = "Etiquetar";
		if (isset($_POST['bt_etiquetar_conf'])) $this->_accion = "ConfirmarEti";
		//if (isset($_POST['bt_buscar'])) $this->_accion = "Buscar";
		//if (isset($_POST['bt_buscar_conf'])) $this->_accion = "ConfirmarB";
			
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
				"botonAgregar" => "/includes/vista/botonAgregar.html",
				"botonListar" => "/includes/vista/botonListar.html"
		);
		$oCargarVista->setCarga('aAcciones', $this->_aAcciones);
		// Ingresa los datos a representar en el html de la vista
		$oDatoVista = new DatoVista();
		$oDatoVista->setDato('{tituloPagina}', 'Proveedores');
		$oDatoVista->setDato('{usuario}', $oLoginVO->getAlias());
		$oDatoVista->setDato('{tituloBadge}', 'Proveedores ');
		// Alertas

		// Carga el contenido html y datos según la acción

		// Instancia las clases del módulo
		$oProveedorVO = new ProveedorVO();
		$oProveedorModelo = new ProveedorModelo();
		
		// Selector de acciones
		switch ($this->_accion){
			# ----> acción Iniciar
			case 'Iniciar':
				// carga las alertas html
				$oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
				// ingresa los datos a representar en las alertas de la vista
				$oDatoVista->setDato('{alertaInfo}',  'Seleccione alguna acción con los botones.');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Proveedores - Selección de opciones');
				$oDatoVista->setDato('{informacion}', '<p>Debe seleccionar un botón para realizar acciones</p><p>sobre la tabla proveedores.');
				// arma la tabla de datos a representar
				$this->_items = $oProveedorModelo->findAll();
				$this->_cantidad = $oProveedorModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				break;

			# ----> acción Listar
			case 'Listar':
			// carga el contenido html
			$oCargarVista->setCarga('contenido', '/includes/vista/listar.html');
			// ingresa los datos a representar en el panel de la vista
			$oDatoVista->setDato('{tituloPanel}', 'Listado de Proveedores');
			$oDatoVista->setDato('{informacion}', '<p>Listado de todos los proveedores.</p>
													<p>También puede seleccionar otras acciones</p>
													<p>para los proveedores, ver botones.');
			// arma la tabla de datos a representar
			$this->_items = $oProveedorModelo->findAll();
			$this->_cantidad = $oProveedorModelo->getCantidad();
			$oDatoVista->setDato('{cantidad}', $this->_cantidad);
			ProveedorTabla::armaTabla($this->_cantidad, $this->_items, $this->_accion);
			$oCargarVista->setCarga('tabla', '/modulos/proveedor/tabla.html');
			break;
		# ----> acción Agregar
		case 'Agregar':
			$this->_cantidad = 0;
			// carga el contenido html
			$oCargarVista->setCarga('datos', '/modulos/proveedor/vista/agregarDatos.html');
			// ingresa los datos a representar en el panel de la vista
			$oDatoVista->setDato('{tituloPanel}', 'Agregar Proveedor');
			$oDatoVista->setDato('{cantidad}', $this->_cantidad);
			$oDatoVista->setDato('{informacion}', '<p>Agrega un proveedor.</p>
													<p>También puede seleccionar otras acciones,</p>
													<p>ver botones.');
			// carga los eventos (botones)
			$this->_aEventos = array(
					"confirmar" => "/includes/vista/botonAgregarConf.html",
					"borrar" => "/includes/vista/botonBorrar.html"
			);
			$oCargarVista->setCarga('aEventos', $this->_aEventos);
			// arma el select de datos de la tabla afip_responsables a representar
			$oAfipResponsablesVO = new AfipResponsablesVO();
			$oAfipResponsablesModelo = new AfipResponsablesModelo();
			$this->_items = $oAfipResponsablesModelo->findAll();
			$this->_cantidad = $oAfipResponsablesModelo->getCantidad();
			$this->_codigo = 0;
			$oDatoVista->setDato('{cantidadInscripto}', $this->_cantidad);
			AfipResponsablesSelect::armaSelect($this->_cantidad, $this->_items, $this->_accion, $this->_codigo);
			$oCargarVista->setCarga('selectAfipResponsables', '/modulos/proveedor/selectAfipResponsables.html');
			// arma el select de datos de la tabla localidad a representar
			$oLocalidadVO = new LocalidadVO();
			$oLocalidadModelo = new LocalidadModelo();
			$this->_items = $oLocalidadModelo->findAll();
			$this->_cantidad = $oLocalidadModelo->getCantidad();
			$this->_idLocalidad = 0;
			$oDatoVista->setDato('{cantidadLocalidad}', $this->_cantidad);
			LocalidadSelect::armaSelect($this->_cantidad, $this->_items, $this->_accion, $this->_idLocalidad);
			$oCargarVista->setCarga('selectLocalidad', '/modulos/proveedor/selectLocalidad.html');
			break;
		# ---> acción Confirmar Agregar
		case 'ConfirmarA':
			// Setea para controlar errores e inconsistencias
			$this->_cantidad = 0;
			$this->_error = 'NO';
			// Verifica errores e inconsistencias
			// verifica si existe inicial igual en otro proveedor
			$oProveedorVO->setInicial($_POST['inicial']);
			$oProveedorModelo->findPorInicial($oProveedorVO);
			if ($oProveedorModelo->getCantidad() > 0){
				$this->_error = 'SI';
				// carga el contenido html
				$oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{informacion}', '<p>Precaución, falló el agregado del proveedor.</p>
															<p>Modifique y vuelva a intentar, o seleccione alguna acción</p>
															<p>u otra opción del menú.');
				// ingresa los datos a representar en las alertas de la vista
				$oDatoVista->setDato('{alertaPeligro}', 'Las INICIALES ya existen - No se agregaron los datos');
			}
			// verifica si corresponde el número de código postal a la localidad
			$this->_aCodPostal = array(
				"letra" => substr($_POST['codPostal'], 0, 1),
				"codigo" => substr($_POST['codPostal'], 1, 4),	
				"cara" => substr($_POST['codPostal'], 5, 3)
			);
			$oLocalidadVO = new LocalidadVO();
			$oLocalidadModelo = new LocalidadModelo();
			$oLocalidadVO->setId($_POST['idLocalidad']);
			$oLocalidadModelo->find($oLocalidadVO);
			$this->_codPostalLoc = trim($oLocalidadVO->getCodPostal())+0;
			$l = is_numeric($this->_codPostalLoc);
			var_dump($l);
			$this->_codPostalPro = trim($this->_aCodPostal['codigo'])+0;
			$p = is_numeric($this->_codPostalPro);
			var_dump($p);
			if ($this->_codPostalLoc !== $this->_codPostalPro){
				$this->_error = 'SI';
				// carga el contenido html
				$oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{informacion}', '<p>Precaución, falló el agregado del proveedor.</p>
															<p>Modifique y vuelva a intentar, o seleccione alguna acción</p>
															<p>u otra opción del menú.');
				// ingresa los datos a representar en las alertas de la vista
				$oDatoVista->setDato('{alertaPeligro}', 'El nro de código postal no coincide con Localidad - No se agregaron los datos');
			}
			// verifica si la letra del código postal corresponde con la provincia
			$oProvinciaVO = new ProvinciaVO();
			$oProvinciaModelo = new ProvinciaModelo();
			$oProvinciaVO->setId($oLocalidadVO->getIdProvincia());
			$oProvinciaModelo->find($oProvinciaVO);
			if ($oProvinciaVO->getLetra() !== $this->_aCodPostal["letra"]){
				$this->_error = 'SI';
				// carga el contenido html
				$oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{informacion}', '<p>Precaución, falló el agregado del proveedor.</p>
															<p>Modifique y vuelva a intentar, o seleccione alguna acción</p>
															<p>u otra opción del menú.');
				// ingresa los datos a representar en las alertas de la vista
				$oDatoVista->setDato('{alertaPeligro}', 'La letra de código postal no coincide con Provincia - No se agregaron los datos');
			}
			// recibe datos del proveedor (POST)
			$this->recibeDatosPost($oProveedorVO, $oLoginVO);
			// Si no hay errores o inconsistencias inserta los datos en la tabla
			if ($this->_error == 'NO'){
				$oProveedorModelo->insert($oProveedorVO); 
				$this->_cantidad = $oProveedorModelo->getCantidad();
			}	
			// carga el contenido html
			$oCargarVista->setCarga('datos', '/modulos/proveedor/vista/verDatos.html');
			// ingresa los datos a representar en el Panel de la vista
			$oDatoVista->setDato('{tituloPanel}', 'Ver Proveedor Agregado');
			$oDatoVista->setDato('{cantidad}', $this->_cantidad);
			// carga los eventos (botones)
			$this->_aEventos = array(
				"editar" => "/includes/vista/botonEditar.html",
			);
			$oCargarVista->setCarga('aEventos', $this->_aEventos);
			// ingresa los datos a representar en el contenido de la vista
			ProveedorDatos::cargaDatos($oProveedorVO, $oDatoVista, $this->_accion);
			break;
			
		# ----> acción Editar
		case 'Editar':
			// verifica si id=0 viene de agregar con error
			if ($_POST['id']!==0){
				// id diferente de 0 viene del listado a editar registro existente
				$oProveedorVO->setId($_POST['bt_editar']);
				$oProveedorModelo->find($oProveedorVO);
				$this->_cantidad = $oProveedorModelo->getCantidad();
			}else{
				$this->_cantidad = 0;
				// recibe datos del proveedor (POST)
				$this->recibeDatosPost($oProveedorVO, $oLoginVO);
			}
			// carga el contenido html
			$oCargarVista->setCarga('datos', '/modulos/proveedor/vista/editarDatos.html');
			// ingresa los datos a representar en el Panel de la vista
			$oDatoVista->setDato('{tituloPanel}', 'Editar Proveedor');
			$oDatoVista->setDato('{informacion}', '<p>Edita los datos del proveedor.</p>
														<p>Tambien puede seleccionar alguna acción</p>
														<p>u otra opción del menú.');
			$oDatoVista->setDato('{cantidad}', $this->_cantidad);
			// carga los eventos (botones)
			$this->_aEventos = array(
					"confirmar" => "/includes/vista/botonEditarConf.html",
					"borrar" => "/includes/vista/botonBorrar.html"
			);
			$oCargarVista->setCarga('aEventos', $this->_aEventos);
			// arma el select de datos de la tabla afip_responsables a representar
			$oAfipResponsablesVO = new AfipResponsablesVO();
			$oAfipResponsablesModelo = new AfipResponsablesModelo();
			$this->_items = $oAfipResponsablesModelo->findAll();
			$this->_cantidad = $oAfipResponsablesModelo->getCantidad();
			$this->_codigo = $oProveedorVO->getInscripto();
			$oDatoVista->setDato('{cantidadInscripto}', $this->_cantidad);
			AfipResponsablesSelect::armaSelect($this->_cantidad, $this->_items, $this->_accion, $this->_codigo);
			$oCargarVista->setCarga('selectAfipResponsables', '/modulos/proveedor/selectAfipResponsables.html');
			// arma el select de datos de la tabla localidad a representar
			$oLocalidadVO = new LocalidadVO();
			$oLocalidadModelo = new LocalidadModelo();
			$this->_items = $oLocalidadModelo->findAll();
			$this->_cantidad = $oLocalidadModelo->getCantidad();
			$this->_idLocalidad = $oProveedorVO->getIdLocalidad();
			$oDatoVista->setDato('{cantidadLocalidad}', $this->_cantidad);
			LocalidadSelect::armaSelect($this->_cantidad, $this->_items, $this->_accion, $this->_idLocalidad);
			$oCargarVista->setCarga('selectLocalidad', '/modulos/proveedor/selectLocalidad.html');
			// ingresa los datos a representar en el contenido de la vista
			ProveedorDatos::cargaDatos($oProveedorVO, $oDatoVista, $this->_accion);
			break;
		# ----> acción Confirmar Editar
		case 'ConfirmarE':
			// recibe datos del proveedor (POST)
			$this->recibeDatosPost($oProveedorVO, $oLoginVO);
			// insert o upgrade de acuerdo al id
			if ($oProveedorVO->getId()!==0){
				$oProveedorModelo->update($oProveedorVO);
				$this->_cantidad = $oProveedorModelo->getCantidad();
			}else{
				$oProveedorModelo->insert($oProveedorVO);
				$this->_cantidad = $oProveedorModelo->getCantidad();
			}
			// carga el contenido html
			$oCargarVista->setCarga('datos', '/modulos/proveedor/vista/verDatos.html');
			// ingresa los datos a representar en el Panel de la vista
			$oDatoVista->setDato('{tituloPanel}', 'Ver Proveedor Editado');
			$oDatoVista->setDato('{cantidad}', $this->_cantidad);
			// carga los eventos (botones)
			$this->_aEventos = array(
					"editar" => "/includes/vista/botonEditar.html",
			);
			$oCargarVista->setCarga('aEventos', $this->_aEventos);
			// ingresa los datos a representar en el contenido de la vista
			ProveedorDatos::cargaDatos($oProveedorVO, $oDatoVista, $this->_accion);
			break;
		# ----> acción Buscar
		case'Buscar':

			break;
		# ----> acción Confirmar Buscar
		case 'ConfirmarB':

			break;
		# ----> acción Etiquetar
		case'Etiquetar':
			$oProveedorVO->setId($_POST['bt_etiquetar']);
			$oProveedorModelo->find($oProveedorVO);
			$this->_cantidad = $oProveedorModelo->getCantidad();
			// carga el contenido html
			$oCargarVista->setCarga('datos', '/modulos/proveedor/vista/etiquetarDatos.html');
			// ingresa los datos a representar en el Panel de la vista
			$oDatoVista->setDato('{tituloPanel}', 'Proveedor - Etiquetar Artículos');
			$oDatoVista->setDato('{cantidad}', $this->_cantidad);
			$oDatoVista->setDato('{informacion}', '<p>Muestra los datos de un proveedor para etiquetar artículos.</p>
														<p>Seleccione alguna acción para el proveedor con botones</p>
														<p>u otra opción del menú.');
			// carga los eventos (botones)
			$this->_aEventos = array(
					"etiquetarConf" => "/includes/vista/botonEtiquetarConf.html",
			);
			$oCargarVista->setCarga('aEventos', $this->_aEventos);
			// ingresa los datos a representar en las alertas de la vista
			$oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
			$oDatoVista->setDato('{alertaPeligro}', '<b>PRECAUCIÓN !!!</b>. Etiqueta artículos y busca equivalencias con productos');
			// ingresa los datos a representar en la vista
			$oDatoVista->setDato('{id}', $oProveedorVO->getId());
			$oDatoVista->setDato('{razonSocial}', $oProveedorVO->getRazonSocial());
			$oDatoVista->setDato('{inicial}', $oProveedorVO->getInicial());
			$oArticuloVO = new ArticuloVO();
			$oArticuloModelo = new ArticuloModelo();
			$oProductoVO = new ProductoVO();
			$oProductoModelo = new ProductoModelo();
			$oArticuloModelo->countSinProveedor();
			$oDatoVista->setDato('{cantSinEtiquetar}', $oArticuloModelo->getCantidad());
			$oArticuloModelo->countEtiquetados($oProveedorVO->getId());
			$oDatoVista->setDato('{cantEtiquetados}', $oArticuloModelo->getCantidad());
			$oProductoModelo->countPorProveedor($oProveedorVO->getId());
			$oDatoVista->setDato('{cantProductos}', $oProductoModelo->getCantidad());
			$oProductoModelo->countEquivalentesPorProveedor($oProveedorVO->getId());
			$oDatoVista->setDato('{cantEquivalentes}', $oProductoModelo->getCantidad());
			$oDatoVista->setDato('{cantNuevosArticulos}', 0);
			$oDatoVista->setDato('{cantNuevosProductos}', 0);
			break;
		# ----> acción Confirmar Etiquetar
		case 'ConfirmarEti':
			$cantNuevosProductos=0;
			$cantNuevosArticulos=0;
			$oProveedorVO->setId($_POST['id']);
			$oProveedorModelo->find($oProveedorVO);
			$this->_cantidad = $oProveedorModelo->getCantidad();
			// carga el contenido html
			$oCargarVista->setCarga('datos', '/modulos/proveedor/vista/etiquetarDatos.html');
			// ingresa los datos a representar en el Panel de la vista
			$oDatoVista->setDato('{tituloPanel}', 'Proveedor - Etiquetar Artículos');
			$oDatoVista->setDato('{cantidad}', $this->_cantidad);
			$oDatoVista->setDato('{informacion}', '<p>Muestra los datos los artículos etiquetados con el proveedor.</p>
														<p>Seleccione alguna acción para el proveedor con botones</p>
														<p>u otra opción del menú.');
			// carga los eventos (botones)

			// carga las alertas de las vistas
			$oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
			$oDatoVista->setDato('{alertaSuceso}', 'Finalizó acción de etiquetar artículos y buscar equivalencias con EXITO!!!');
			// etiqueta y busca equivalencias 
			$oArticuloVO = new ArticuloVO();
			$oArticuloModelo = new ArticuloModelo();
			$oProductoVO = new ProductoVO();
			$oProductoModelo = new ProductoModelo();	
			$this->_items = $oArticuloModelo->findAllEtiquetables();
			if ($oArticuloModelo->getCantidad()>0){ // Si lee registros etiquetables continua el proceso
				foreach ($this->_items as $this->_item){
					// busco producto
					$modiArticulo='N';
					$oProductoVO->setCodigoB($this->_item['codigo_b']);
					$oProductoVO->setIdProveedor($oProveedorVO->getId());
					$oProductoModelo->findPorCodigoBProveedor($oProductoVO);
					if ($oProductoModelo->getCantidad()>0){ // lee un producto activo con equivalente codigo de barra de artículo
						$oArticuloVO->setId($this->_item['id']);
						$oArticuloModelo->find($oArticuloVO);
						if ($oArticuloVO->getEstado()==1){ // articulo con estado = activo
							if ($oArticuloVO->getIdProveedor()<2){	// artículo sin poveedor de referencia
								$oArticuloVO->setIdProveedor($oProveedorVO->getId());
								$oArticuloVO->setOpcionProv(2); // por Actualización de etiquetas
								$oArticuloVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
								$this->_date = date('Y-m-d H:i:s');
								$oArticuloVO->setFechaAct($this->_date);
								$modiArticulo='S';							
							}
							if ($oArticuloVO->getEquivalencia()==0){
								$oArticuloVO->setEquivalencia(1);
								$oArticuloVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
								$this->_date = date('Y-m-d H:i:s');
								$oArticuloVO->setFechaAct($this->_date);
								$modiArticulo='S';
							}
							if ($oProductoVO->getIdArticulo()==0){
								$oProductoVO->setIdArticulo($oArticuloVO->getId());
								$oProductoVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
								$this->_date = date('Y-m-d H:i:s');
								$oProductoVO->setFechaAct($this->_date);
								$oProductoModelo->update($oProductoVO);
								$cantNuevosProductos++;
							}
							if ($modiArticulo=='S'){
								$oArticuloModelo->update($oArticuloVO);
								$cantNuevosArticulos++;
							}
						} // cierra if de articulo activo	
					}	// cierra el if de cantidad producto
				}	// cierra el foreach
			}	// cierra el if de cantidad artículo	
			// ingresa los datos a representar en la vista 
			$oDatoVista->setDato('{id}', $oProveedorVO->getId());
			$oDatoVista->setDato('{razonSocial}', $oProveedorVO->getRazonSocial());
			$oDatoVista->setDato('{inicial}', $oProveedorVO->getInicial());

			$oArticuloModelo->countSinProveedor();
			$oDatoVista->setDato('{cantSinEtiquetar}', $oArticuloModelo->getCantidad());
			$oArticuloModelo->countEtiquetados($oProveedorVO->getId());
			$oDatoVista->setDato('{cantEtiquetados}', $oArticuloModelo->getCantidad());
			$oProductoModelo->countPorProveedor($oProveedorVO->getId());
			$oDatoVista->setDato('{cantProductos}', $oProductoModelo->getCantidad());
			$oProductoModelo->countEquivalentesPorProveedor($oProveedorVO->getId());
			$oDatoVista->setDato('{cantEquivalentes}', $oProductoModelo->getCantidad());
			// ingresa los datos a representar en la vista según el resultado del proceso
			$oDatoVista->setDato('{cantNuevosArticulos}', $cantNuevosArticulos);
			$oDatoVista->setDato('{cantNuevosProductos}', $cantNuevosProductos);
			break;
		# ----> acción Ver
		case 'Ver':
			$oProveedorVO->setId($_POST['bt_ver']);
			$oProveedorModelo->find($oProveedorVO);
			$this->_cantidad = $oProveedorModelo->getCantidad();
			// carga el contenido html
			$oCargarVista->setCarga('datos', '/modulos/proveedor/vista/verDatos.html');
			// ingresa los datos a representar en el Panel de la vista
			$oDatoVista->setDato('{tituloPanel}', 'Ver Proveedor');
			$oDatoVista->setDato('{cantidad}', $this->_cantidad);
			$oDatoVista->setDato('{informacion}', '<p>Muestra los datos de un proveedor.</p>
														<p>Seleccione alguna acción para el proveedor con botones</p>
														<p>u otra opción del menú.');
			// carga los eventos (botones)
			$this->_aEventos = array(
					"editar" => "/includes/vista/botonEditar.html",
			);
			$oCargarVista->setCarga('aEventos', $this->_aEventos);
			// ingresa los datos a representar en el contenido de la vista
			ProveedorDatos::cargaDatos($oProveedorVO, $oDatoVista, $this->_accion);
			break;
		# ----> acción por Defecto (ninguna acción seleccionada)
		default:

			break;
		}

		// instancio el motor de la vista y muestro la vista
		$oMotorVista = new MotorVista($oDatoVista->getAllDatos(), $oCargarVista->getAllCargas());
		$oMotorVista->mostrarVista();
		


	}

	// recibe datos por POST y carga $oProveedorVO
	private function recibeDatosPost($oProveedorVO, $oLoginVO){
		// recibe datos del proveedor (POST)
		$oProveedorVO->setId($_POST['id']);
		$oProveedorVO->setRazonSocial($_POST['razonSocial']);
		$oProveedorVO->setInicial($_POST['inicial']);
		$oProveedorVO->setDomicilioFiscal($_POST['domicilioFiscal']);
		$oProveedorVO->setCuit($_POST['cuit']);
		$oProveedorVO->setInscripto($_POST['codigo']);
		$oProveedorVO->setIngresosBrutos($_POST['ingresosBrutos']);
		$oProveedorVO->setDomicilio($_POST['domicilio']);
		$oProveedorVO->setCodPostal($_POST['codPostal']);
		$oProveedorVO->setIdLocalidad($_POST['idLocalidad']);
		$oProveedorVO->setTelefono($_POST['telefono']);
		$oProveedorVO->setMovil($_POST['movil']);
		$oProveedorVO->setEmail($_POST['email']);
		$oProveedorVO->setComentario($_POST['comentario']);
		$oProveedorVO->setLista($_POST['lista']);
		$oProveedorVO->setListaOrden($_POST['listaOrden']);
		$oProveedorVO->setEstado($_POST['estado']);
		$oProveedorVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
		$this->_date = date('Y-m-d H:i:s');
		$oProveedorVO->setFechaAct($this->_date);
		//return $oProveedorVO();
	}
}
?>