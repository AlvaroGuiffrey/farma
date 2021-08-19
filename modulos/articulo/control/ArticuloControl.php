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
 * Clase control del módulo artículo que permite realizar
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
class ArticuloControl
{
	#Propiedades
	private $_html;
	private $_accion;
	private $_items;
	private $_item;
	private $_aProductos;
	private $_producto;
	private $_cantidad;
	private $_cantCodigoB;
	private $_nroPagina;
	private $_limiteRenglones=100;
	private $_cantPaginas;
	private $_cantResto;
	private $_cantListado;
	private $_renglonDesde;
	private $_id;
	private $_idMarca;
	private $_nombreMarca;
	private $_idRubro;
	private $_nombreRubro;
	private $_idProveedor;
	private $_opcionProv;
	private $_estado;
	private $_orden;
	private $_origen;
	private $_nombreOrigen;
	private $_actualizaProv;
	private $_nombreActualizaProv;
	private $_codigo;
	private $_date;
	private $_modiArticulo;
	private $_modiProducto;
	private $_aAcciones = array();
	private $_aEventos = array();
	private $_aListaOrden = array();
	private $_aScriptJS = array();
	private $_listaOrden;
	private $_listaPrioridad;
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
		Clase::define('Paginador');
		Clase::define('ArticuloModelo');
		Clase::define('ArticuloTabla');
		Clase::define('ArticuloDatos');
		Clase::define('ArticuloCostosTabla');
        Clase::define('RubroModelo');
		Clase::define('RubroSelect');
		Clase::define('MarcaModelo');
		Clase::define('MarcaSelect');
		Clase::define('ProductoModelo');
		Clase::define('ProveedorModelo');
		Clase::define('ProveedorSelect');
		Clase::define('ListaOrdenModelo');
		
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
		// anula agregar para farmacia
		//if (isset($_POST['bt_agregar'])) $this->_accion = "Agregar";
		//if (isset($_POST['bt_agregar_conf'])) $this->_accion = "ConfirmarA";
		if (isset($_POST['bt_editar'])) $this->_accion = "Editar";
		if (isset($_POST['bt_editar_conf'])) $this->_accion = "ConfirmarE";
		if (isset($_POST['bt_ver'])) $this->_accion = "Ver";
		if (isset($_POST['bt_listar'])) $this->_accion = "Listar";
		if (isset($_POST['bt_listar_conf'])) $this->_accion = "ConfirmarL";
		if (isset($_POST['bt_pagina_ant'])) $this->_accion = "ConfirmarL";
		if (isset($_POST['bt_pagina_sig'])) $this->_accion = "ConfirmarL";
		if (isset($_POST['bt_actualizar_listado'])) $this->_accion = "ConfirmarL";
		if (isset($_POST['bt_actualizarEti'])) $this->_accion = "ActualizarEti";
		if (isset($_POST['bt_actualizarEti_conf'])) $this->_accion = "ConfirmarActEti";
		if (isset($_POST['bt_buscar'])) $this->_accion = "Buscar";
		if (isset($_POST['bt_buscar_conf'])) $this->_accion = "ConfirmarB";
		if (isset($_POST['bt_volver'])) $this->_accion = "ConfirmarB";
			
		// Carga los archivos html para la vista
		$oCargarVista = new CargarVista();
		$oCargarVista->setCarga('pagina', '/includes/vista/pagina.html');
		$oCargarVista->setCarga('botones', '/includes/vista/botonesFooter.html');
		// Carga los script JS
		$this->_aScriptJS = array(
		    "ajaxApp" => "/includes/js/ajaxApp.js",
		    "articuloAjax" => "/modulos/articulo/includes/js/articuloAjax.js"
		);
		
		//var_dump($this->_aScriptJS);
		$oCargarVista->setCarga('aScriptJS', $this->_aScriptJS);
		// Carga el menú de la vista según la categoría del usuario
		$oCargarVista->setCarga('menu', CargarMenu::selectMenu($oLoginVO->getCategoria()));
		// Carga el formulario
		$oCargarVista->setCarga('contenido', '/includes/vista/form.html');
		// carga las acciones (botones)
		$this->_aAcciones = array(
		      "badge" => "/includes/vista/botonBadge.html",
		      "listar" => "/includes/vista/botonListar.html",
		      "buscar" => "/includes/vista/botonBuscar.html",
		      "actulizarEti" => "/includes/vista/botonActualizarEti.html"		
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
				$oDatoVista->setDato('{tituloPanel}', 'Artículos - Selección de acciones');
				$oDatoVista->setDato('{informacion}', '<p>Puede seleccionar acciones para los artículos,</p><p>ver botones.');
				// arma la tabla de datos a representar
				$this->_items = $oArticuloModelo->count();
				$this->_cantidad = $oArticuloModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				break; 
			# ----> acción Listar
			case 'Listar':
				// carga el contenido html
				$oCargarVista->setCarga('opcion', '/modulos/articulo/vista/listarOpcion.html');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Listado de Artículos');
				$oDatoVista->setDato('{informacion}', '<p>Listado de todos los artículos según opciones elegidas.</p><p>También puede seleccionar otras acciones</p><p>para los artículos, ver botones.');
				// carga los eventos (botones)
				$this->_aEventos = [
					"listarConfirmar" => "/includes/vista/botonListarConfirmar.html",
					"borrar" => "/includes/vista/botonBorrar.html"	
					];
				$oCargarVista->setCarga('aEventos', $this->_aEventos);
				// ingresa los datos a representar en las alertas de la vista

				// arma el select de datos de la tabla marca a representar
				$oMarcaVO = new MarcaVO();
				$oMarcaModelo = new MarcaModelo();
				$this->_items = $oMarcaModelo->findAll();
				$this->_cantidad = $oMarcaModelo->getCantidad();
				$this->_idMarca = 0;
				$oDatoVista->setDato('{cantidadMarca}', $this->_cantidad);
				MarcaSelect::armaSelect($this->_cantidad, $this->_items, $this->_accion, $this->_idMarca);
				$oCargarVista->setCarga('selectMarca', '/modulos/articulo/selectMarca.html');				
				
				// arma el select de datos de la tabla rubro a representar
				$oRubroVO = new RubroVO();
				$oRubroModelo = new RubroModelo();
				$this->_items = $oRubroModelo->findAll();
				$this->_cantidad = $oRubroModelo->getCantidad();
				$this->_idRubro = 0;
				$oDatoVista->setDato('{cantidadRubro}', $this->_cantidad);
				RubroSelect::armaSelect($this->_cantidad, $this->_items, $this->_accion, $this->_idRubro);
				$oCargarVista->setCarga('selectRubro', '/modulos/articulo/selectRubro.html');
						
				// arma la tabla de datos a representar
				$this->_items = $oArticuloModelo->count();
				$this->_cantidad = $oArticuloModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				break;
			# ----> acción Confirmar Listar
			case "ConfirmarL":
				// Si viene de acción Listado carga marca y rubro para representar en la vista
				if (!isset($_POST['bt_pagina_ant']) AND !isset($_POST['bt_pagina_sig'])){
					// consulta tablas de la DB para titular la tabla a representar
					$oMarcaVO = new MarcaVO();
					$oMarcaModelo = new MarcaModelo();
					if ($_POST['marca'] != 0){
						$oMarcaVO->setId($_POST['marca']);
						$oMarcaModelo->find($oMarcaVO);
						$_POST['nombreMarca'] = $oMarcaVO->getNombre();
					} else {
						$_POST['nombreMarca'] = 'TODAS';
					}
					$oRubroVO = new RubroVO();
					$oRubroModelo = new RubroModelo();
					if ($_POST['rubro'] != 0){
						$oRubroVO->setId($_POST['rubro']);
						$oRubroModelo->find($oRubroVO);
						$_POST['nombreRubro'] = $oRubroVO->getNombre();
					} else {
						$_POST['nombreRubro'] = 'TODOS';
					}
					// cuenta los registros que coinciden con las opciones elegidas
					$this->_items = $oArticuloModelo->countOpcionListado($_POST['marca'],$_POST['rubro'],$_POST['estado'], $_POST['origen'], $_POST['actualizaProv']);
					$this->_cantidad = $oArticuloModelo->getCantidad();
					$this->_nroPagina = 0;
					// calcula páginador
					$this->_cantPaginas = Paginador::cantidadPaginas($this->_cantidad, $this->_limiteRenglones);
					$_POST['cantidad'] = $this->_cantidad;
					$_POST['nroPagina'] = $this->_nroPagina;
					$_POST['cantPaginas'] = $this->_cantPaginas;
				}
				
				// recibe los datos enviados por POST
				$this->_idMarca = $_POST['marca'];
				$this->_nombreMarca = $_POST['nombreMarca'];
				$this->_idRubro = $_POST['rubro'];
				$this->_nombreRubro = $_POST['nombreRubro'];
				$this->_estado = $_POST['estado'];
				$this->_orden = $_POST['orden'];
				$this->_origen = $_POST['origen'];
				$this->_actualizaProv = $_POST['actualizaProv'];
				$this->_cantidad = $_POST['cantidad'];
				$this->_nroPagina = $_POST['nroPagina'];
				$this->_cantPaginas = $_POST['cantPaginas'];
				// asigna el nombre del origen de los datos
				$this->_nombreOrigen = " ";
				if ($this->_origen == 0) $this->_nombreOrigen = "TODOS";
				if ($this->_origen == 1) $this->_nombreOrigen = "PLEX";
				if ($this->_origen == 2) $this->_nombreOrigen = "PROPIO";
				// asigna el nombre a la actualización del artículo
				$this->_nombreActualizaProv = " ";
				if ($this->_origen == 1) $this->_nombreActualizaProv = "PLEX";
				if ($this->_origen == 2){
					if ($this->_actualizaProv == 0) $this->_nombreActualizaProv = "SIN OPCION";
					if ($this->_actualizaProv == 1) $this->_nombreActualizaProv = "ARTICULO";
					if ($this->_actualizaProv == 2) $this->_nombreActualizaProv = "ACTUALIZACION";
				}
				// calcula el "renglón desde" donde comienza la consulta a la tabla
				if (isset($_POST['bt_pagina_ant'])){
					$this->_nroPagina = $this->_nroPagina - 2;
					$this->_renglonDesde = $this->_nroPagina * $this->_limiteRenglones;
				}else{
					$this->_renglonDesde = $this->_nroPagina * $this->_limiteRenglones;
				}
				
				// lee tabla de artículos según las opciones con límite
				$this->_items = $oArticuloModelo->findAllOpcionListadoLimite($this->_idMarca, $this->_idRubro, $this->_estado, $this->_orden, $this->_origen, $this->_actualizaProv, $this->_renglonDesde, $this->_limiteRenglones);
				$this->_cantListado = $oArticuloModelo->getCantidad();

				// ingresa los datos a representar en el panel de la vista
				if ($this->_estado == 1) $oDatoVista->setDato('{tituloPanel}', 'Listado de Artículos (activos)');
				if ($this->_estado == 0) $oDatoVista->setDato('{tituloPanel}', 'Listado de Artículos (pasivos)');
				$oDatoVista->setDato('{informacion}', '<p>Listado de todos los artículos según opciones elegidas.</p>
												<p>También puede seleccionar otras acciones</p>
												<p>para los artículos, ver botones.'
				);
				// ingresa los datos a representar en las alertas de la vista
				
				// arma la tabla de datos a representar
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				$oDatoVista->setDato('{cantPaginas}', $this->_cantPaginas);
				$this->_nroPagina++;
				$oDatoVista->setDato('{nroPagina}', $this->_nroPagina);
				$oDatoVista->setDato('{cantListado}', $this->_cantListado);

				ArticuloTabla::armaTabla($this->_items, $this->_accion, $this->_idMarca, $this->_nombreMarca, $this->_idRubro, $this->_nombreRubro, $this->_estado, $this->_orden, $this->_origen, $this->_nombreOrigen, $this->_actualizaProv, $this->_nombreActualizaProv, $this->_cantListado);
				$oCargarVista->setCarga('tabla', '/modulos/articulo/tabla.html');

				// carga el paginador html
				$oCargarVista->setCarga('paginador', Paginador::vistaPaginador($this->_nroPagina, $this->_cantPaginas));

				break;
		
			# ----> acción Agregar
			case 'Agregar':

				break;
			# ---> acción Confirmar Agregar
			case 'ConfirmarA':

				break;
	
			# ----> acción Editar
			case 'Editar':
				$this->_id = $_POST['bt_editar'];
				$oArticuloVO->setId($this->_id);
				$oArticuloModelo->find($oArticuloVO);
				$this->_cantidad = $oArticuloModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				// carga el contenido html
				$oCargarVista->setCarga('datos', '/modulos/articulo/vista/editarDatos.html');
				// ingresa los datos a representar en el Panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Editar Artículo');
				// carga los eventos (botones)
				$this->_aEventos = [ 
				"editarConf" => "/includes/vista/botonEditarConf.html",
				"borrar" => "/includes/vista/botonBorrar.html",
				"volver" => "/includes/vista/botonVolver.html"
						];
				$oCargarVista->setCarga('aEventos', $this->_aEventos);
				// ingresa los datos a representar en el contenido de la vista
				ArticuloDatos::cargaDatos($oArticuloVO, $oDatoVista, $this->_accion);
					
				// arma el select de datos de la tabla rubro a representar
				$oRubroVO = new RubroVO();
				$oRubroModelo = new RubroModelo();
				$this->_items = $oRubroModelo->findAll();
				$this->_cantidad = $oRubroModelo->getCantidad();
				$this->_idRubro = 0;
				$oDatoVista->setDato('{cantidadRubro}', $this->_cantidad);
				RubroSelect::armaSelect($this->_cantidad, $this->_items, $this->_accion, $this->_idRubro);
				$oCargarVista->setCarga('selectRubro', '/modulos/articulo/selectRubro.html');
				
				// arma el select de datos de la tabla proveedores a representar
				$oProveedorVO = new ProveedorVO();
				$oProveedorModelo = new ProveedorModelo();
				$this->_items = $oProveedorModelo->findAll();
				$this->_cantidad = $oProveedorModelo->getCantidad();
				$this->_idProveedor = $oArticuloVO->getIdProveedor();
				$oDatoVista->setDato('{cantidadProveedor}', $this->_cantidad);
				ProveedorSelect::armaSelect($this->_cantidad, $this->_items, $this->_accion, $this->_idProveedor);
				$oCargarVista->setCarga('selectProveedor', '/modulos/articulo/selectProveedor.html');
				break;
			# ----> acción Confirmar Editar
			case 'ConfirmarE':
				// carga array proveedores
				$oProveedorVO = new ProveedorVO();
				$oProveedorModelo = new ProveedorModelo();
				$this->_items = $oProveedorModelo->findAllProveedoresRef();
				foreach ($this->_items as $this->_item){
					$this->_aProveedores[$this->_item['id']] = $this->_item['inicial'];
					$this->_aProveedoresLista[$this->_item['id']] = $this->_item['lista'];
				}
				// busca artículo y recibe datos por POST
				$oArticuloVO->setId($_POST['id']);
				$oArticuloModelo->find($oArticuloVO);
				$oArticuloVO->setComentario($_POST['comentario']);
				$oArticuloVO->setRotulo($_POST['rotulo']);
				$oArticuloVO->setIdProveedor($_POST['proveedor']);
				if ($_POST['opcionProv']>0){
					$oArticuloVO->setOpcionProv($_POST['opcionProv']);
				}else{
					$oArticuloVO->setOpcionProv(0);
				}
				$oArticuloVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
				$this->_date = date('Y-m-d H:i:s');
				$oArticuloVO->setFechaAct($this->_date);
				// ---------------------
				// Ojota aca actualiza por edición de datos
				$oArticuloModelo->update($oArticuloVO);
				// ----------------------
				$this->_cantidad = $oArticuloModelo->getCantidad();
				
				// carga el contenido html
				
				$oCargarVista->setCarga('datos', '/modulos/articulo/vista/verDatos.html');
				// ingresa los datos a representar en el Panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Ver Artículo Editado');
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				// ingresa los datos a representar en el contenido de la vista
				ArticuloDatos::cargaDatos($oArticuloVO, $oDatoVista, $this->_accion);
				// arma la tabla de productos para comparar costos de diferentes proveedores
				$oProductoVO = new ProductoVO();
				$oProductoModelo = new ProductoModelo();
				$oProductoVO->setCodigoB($oArticuloVO->getCodigoB());
				$this->_items = $oProductoModelo->findAllPorCodigoB($oProductoVO);
				$this->_cantListado = $oProductoModelo->getCantidad();
				ArticuloCostosTabla::armaTabla($this->_items, $this->_aProveedores, $this->_aProveedoresLista, $this->_cantListado);
				$oCargarVista->setCarga('tabla', '/modulos/articulo/tabla.html');
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
			
			# ----> acción Actualizar etiquetas
			case 'ActualizarEti':
				// carga el contenido html
				$oCargarVista->setCarga('datos', '/modulos/articulo/vista/actualizarEtiquetas.html');
				$oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Artículos - Actualiza Etiqueta de Proveedor');
				$oDatoVista->setDato('{informacion}', '<p>Actualiza la etiqueta de proveedor de referencia en todos los artículos propios.</p><p>También puede seleccionar otras acciones</p><p>para los artículos, ver botones.');
				// carga los eventos (botones)
				$this->_aEventos = [
						"actualizarEtiConf" => "/includes/vista/botonActualizarEtiConf.html",
						];
				$oCargarVista->setCarga('aEventos', $this->_aEventos);
				// ingresa los datos a representar en las alertas de la vista
				$oDatoVista->setDato('{alertaPeligro}',  'Actualiza la <b>etiqueta de proveedor de referencia</b> en los artículos propios, confirme la acción.');				
				
				// arma los datos a representar en la vista
				$oArticuloModelo->count();
				$this->_cantidad = $oArticuloModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				/**
				 * Cuenta los artículos etiquetables y etiquetados
				 */
				$oArticuloModelo->countEtiquetables();
				$oDatoVista->setDato('{cantEtiquetables}', $oArticuloModelo->getCantidad());
				$oArticuloModelo->countEtiquetados();
				$oDatoVista->setDato('{cantEtiquetados}', $oArticuloModelo->getCantidad());
				$oDatoVista->setDato('{cantAgregados}', 0);
				$oDatoVista->setDato('{cantModificados}', 0);
				break;
			# ----> acción Confirmar Actualizar etiquetas
			case 'ConfirmarActEti':
				// instancia las clases necesarias
				$oProductoVO = new ProductoVO();
				$oProductoModelo = new ProductoModelo();
				$oProveedorVO = new ProveedorVO();
				$oProveedorModelo = new ProveedorModelo();
				// Recibe datos por POST
				$oDatoVista->setDato('{cantidad}', $_POST['cantidad']);
				$oDatoVista->setDato('{cantEtiquetables}', $_POST['cantEtiquetables']);
				$oDatoVista->setDato('{cantEtiquetados}', $_POST['cantEtiquetados']);
				// carga el contenido html
				$oCargarVista->setCarga('datos', '/modulos/articulo/vista/actualizarEtiquetas.html');
				$oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Artículos - Actualiza Etiqueta de Proveedor');
				$oDatoVista->setDato('{informacion}', '<p>Se actualizó la etiqueta de proveedor de referencia en todos los artículos propios.</p><p>También puede seleccionar otras acciones</p><p>para los artículos, ver botones.');
				// carga los eventos (botones)
				
				// ingresa los datos a representar en las alertas de la vista
				$oDatoVista->setDato('{alertaSuceso}',  'Finalizó el proceso de actualización de etiquetas de proveedores de referencia con EXITO!!!.');

				// actualiza etiquetas de proveedores en artículos
				$cantModificados = $cantAgregados = 0;
				
				// carga array del orden de listas de precios para actualizar precios
				$oListaOrdenVO = new ListaOrdenVO();
				$oListaOrdenModelo = new ListaOrdenModelo();
				$this->_items = $oListaOrdenModelo->findAll();
				foreach ($this->_items as $this->_item){
					$this->_aListaOrden[$this->_item['id_proveedor']] = $this->_item['id'];
				}
				
	// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	// >>>>>>> LIMPIA LOS ARTICULOS QUE ESTAN INACTIVOS <<<<<<<<
	// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	
				/**
				 * Carga los artículos actualizables según los siguientes datos:
				 *
				 * codigo > 9999900000 ($oArticuloVO->setCodigo())
				 * codigo_b > 0 (esta la condición en la consulta)
				 * id_proveedor > 0 ($oArticuloVO->setIdProveedor())
				 * estado = 0 ($oArticuloVO->setEstado() - Inactivo)
				 */
				$oArticuloVO->setCodigo(9999900000); // mayor que
				$oArticuloVO->setIdProveedor(0); // mayor que - Tiene proveedor asignado
				$oArticuloVO->setEstado(0); // igual que - Inactivo
				$this->_items = $oArticuloModelo->findAllActualizables($oArticuloVO);
				
				// elimino los proveedores de referencia en artículos inactivos
				if ($oArticuloModelo->getCantidad() > 0){ // (1) Si hay artículos actualizables continúa
				    foreach ($this->_items as $this->_item){ // (2) extraigo los datos de artículos x artículo
				        $oArticuloVO->setId($this->_item['id']);
				        $oArticuloModelo->find($oArticuloVO);
	                    $oArticuloVO->setIdProveedor(0);
	                    $oArticuloVO->setOpcionProv(0);
	                    $oArticuloVO->setEquivalencia(0); // con productos equivalentes
	                    $oArticuloVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
	                    $this->_date = date('Y-m-d H:i:s');
	                    $oArticuloVO->setFechaAct($this->_date);
	                    $oArticuloModelo->update($oArticuloVO);
	                    if($oArticuloModelo->getCantidad() == 1) $cantModificados++;
	                    // elimino los id de artículos inactivos en productos
                        $oProductoVO->setCodigoB($oArticuloVO->getCodigoB());
                        $this->_aProductos = $oProductoModelo->findAllPorCodigoB($oProductoVO);
                        if($oProductoModelo->getCantidad() > 0){ //(3) Si hay productos le pongo 0 en IdArticulo
                            foreach ($this->_aProductos as $this->_producto){ // (4) recorre todos los productos
                                $oProductoVO->setId($this->_producto['id']);
                                $oProductoModelo->find($oProductoVO);
                                if($this->_producto["id_articulo"] > 0){ // Pone 0 a idArticulo
                                    $oProductoVO->setIdArticulo(0);
                                    $oProductoVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
                                    $this->_date = date('Y-m-d H:i:s');
                                    $oProductoVO->setFechaAct($this->_date);
                                    $oProductoModelo->update($oProductoVO);
                                }
                            } // (4) Fin recorre productos
                            
                        } //(3) Fin si hay productos con igual codigo de barra al artículo
                        
				    } //(2) Fin extraigo datos de artículo x artículo
				} //(1) No hay artículos actualizables inactivos
				
	// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	// >>>>>>> ACTUALIZA ETIQUETAS DE ARTICULOS >>>>>>>>>>>>>>>>
	// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
				/**
				 * Carga los artículos etiquetables según los siguientes datos:
				 *
				 * codigo > 9999900000 
				 * codigo_b > 0 
				 * estado = 1 
				 * 
				 * (Datos estan en consulta)
				 */

				$this->_items = $oArticuloModelo->findAllEtiquetables();
	
				// actualizo los proveedores de referencia
				if ($oArticuloModelo->getCantidad() > 0){ // (1) Si hay artículos etiquetables continúa
					foreach ($this->_items as $this->_item){ // (2) extraigo los datos de artículos x artículo
					    $this->_opcionProv = $this->_item['opcion_prov'];
					    if ($this->_opcionProv==0 OR $this->_opcionProv==2){ // (3) si tiene opción de actualización "Automática" o no tiene asignada opción
					        // Busco si hay productos con igual codigo de barra
					        $oProductoVO->setCodigoB($this->_item['codigo_b']);
					        $this->_aProductos = $oProductoModelo->findAllPorCodigoB($oProductoVO);
					        if ($oProductoModelo->getCantidad() > 0){ // (4) Hay productos con igual código de barra
					            // Pone en CERO variables a utilizar para dar prioridad y orden
					            $this->_listaPrioridad = $this->_listaOrden = 0;
					            // Busca Proveedor de referencia para actualizar
					            foreach ($this->_aProductos as $this->_producto){ // (5) lee productos con igual codigo de barra para seleccionar opción de proveedor
					                /**
					                 * Actualiza id_artículo en producto equivalente
					                 */
					                if ($this->_producto['id_articulo'] != $this->_item['id']){
					                    $oProductoVO->setId($this->_producto['id']);
					                    $oProductoModelo->find($oProductoVO);
					                    $oProductoVO->setIdArticulo($this->_item['id']);
					                    $oProductoVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
					                    $this->_date = date('Y-m-d H:i:s');
					                    $oProductoVO->setFechaAct($this->_date);
					                    $oProductoModelo->update($oProductoVO);
					                }
					                /**
					                 * Selecciona el proveedor a etiquetar
					                 */
					                // Ver tipo de lista del proveedor para etiquetar
					                $oProveedorVO->setId($this->_producto['id_proveedor']);
					                $oProveedorModelo->find($oProveedorVO);
					                if ($oProveedorVO->getListaOrden()>0){
					                    if ($oProveedorVO->getListaOrden()==1){ // Proveedor con LCP (Lista con prioridad)
					                        $this->_listaPrioridad = $oProveedorVO->getId();
					                    } else { // Proveedor con LOJ (Lista con orden jerárquico)
					                        if ($this->_listaOrden==0 OR $this->_aListaOrden[$this->_listaOrden] > $this->_aListaOrden[$oProveedorVO->getId()]){
					                            $this->_listaOrden = $oProveedorVO->getId();
					                        }
					                    }
					                } else { // Error en proveedor no tiene orden lista y tiene productos (lista de precios)
					                    echo "ERROR Proveedor: ".$oProveedorVO->getId()." - ".$oProveedorVO->getRazonSocial()." --> Modificar Lista Orden (tiene lista)<br>";
					                }
					            } // (5) Fin lee productos con igual codigo de barra		
					            // (6) actualiza artículo con proveedor de referencia
					            /**
					             * Actualizar el artículo con el proveedor de referencia
					             * y en opción de proveedor (poner opcion "2")
					             * Actualizar el producto con el id del artículo
					             */
					            if ($this->_listaPrioridad > 0 OR $this->_listaOrden > 0){
					                // Hacer comparaciones para ver si modifico el articulo 
					                $this->_modi = "N";
					                $oArticuloVO->setId($this->_item['id']);
					                $oArticuloModelo->find($oArticuloVO);
					               					               
					                if ($this->_listaPrioridad > 0){
					                    $oArticuloVO->setIdProveedor($this->_listaPrioridad);
					                } else {
					                    $oArticuloVO->setIdProveedor($this->_listaOrden);
					                }
					               
					                if ($this->_item['id_proveedor'] != $oArticuloVO->getIdProveedor()){
					                    $oArticuloVO->setOpcionProv(2); // cambia opcion de proveedor por actualización
					                    $oArticuloVO->setEquivalencia(1); // con productos equivalentes
					                    $oArticuloVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
					                    $this->_date = date('Y-m-d H:i:s');
					                    $oArticuloVO->setFechaAct($this->_date);
					                    $oArticuloModelo->update($oArticuloVO);
					                    if ($this->_idProveedor > 1){ // ya tenia proveedor de referencia
					                        $cantModificados++;
					                    } else { // no tenía proveedor de referencia
					                        $cantAgregados++;
					                    }
					                }
					            } // (6) fin actualiza artículo
					           
						    } else { // (4) sigue
						        // No hay productos con codigo de  barra igual
						        // Actualizo el artículo con cero id_proveedor
						        if ($this->_item['id_proveedor'] > 0){
						            $oArticuloVO->setId($this->_item['id']);
						            $oArticuloModelo->find($oArticuloVO);
						            $oArticuloVO->setIdProveedor(0);
						            $oArticuloVO->setOpcionProv(0);
						            $oArticuloVO->setEquivalencia(0); // con productos equivalentes
						            $oArticuloVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
						            $this->_date = date('Y-m-d H:i:s');
						            $oArticuloVO->setFechaAct($this->_date);
						            $oArticuloModelo->update($oArticuloVO);
						            if($oArticuloModelo->getCantidad() == 1){
						                $cantModificados++;
						            }
						        }
			                } // (4) Fin productos con igual codigo de barra 
					    } // (3) Fin opción proveedor, sale si opción es 1 (actualización por artículo)
					} // (2) fin foreach extraigo datos de artículos actualizables
				} // (1) fin si hay artículos actualizables 	
				
				// carga los datos a mostrar en la vista
				$oDatoVista->setDato('{cantAgregados}', $cantAgregados);
				$oDatoVista->setDato('{cantModificados}', $cantModificados);				
				break;
									
			# ----> acción Buscar
			case 'Buscar':
				// carga el contenido html
				$oCargarVista->setCarga('opcion', '/modulos/articulo/vista/buscarOpcion.html');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Busqueda de Artículos');
				$oDatoVista->setDato('{informacion}', '<p>Buscar los artículos según opciones elegidas.</p>
													<p>La búsqueda se realiza por una sola opción, comenzando por la izquierda.</p>
													<p>También puede seleccionar otras acciones</p>
													<p>para los artículos, ver botones.'
									);
				// ingresa los datos a representar en las alertas de la vista
				
				// arma los datos a representar 
				$this->_items = $oArticuloModelo->count();
				$this->_cantidad = $oArticuloModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				break;
			# ----> acción Confirmar Buscar
			case 'ConfirmarB':
				// carga array proveedores
				$oProveedorVO = new ProveedorVO();
				$oProveedorModelo = new ProveedorModelo();
				$this->_items = $oProveedorModelo->findAllProveedoresRef();
				foreach ($this->_items as $this->_item){
					$this->_aProveedores[$this->_item['id']] = $this->_item['inicial'];
					$this->_aProveedoresLista[$this->_item['id']] = $this->_item['lista'];
				}
				// busco los artículos segun las opciones elegidas
				if ($_POST['codigo'] > 0){
					$oArticuloVO->setCodigo($_POST['codigo']);
					$oArticuloModelo->findPorCodigo($oArticuloVO);
					$this->_cantidad = $oArticuloModelo->getCantidad();
					if ($this->_cantidad == 0){
						// carga el contenido html
						$oCargarVista->setCarga('opcion', '/modulos/articulo/vista/buscarOpcion.html');
						$oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
						$oDatoVista->setDato('{alertaAdvertencia}',  'No encontró artículo. Intente otra búsqueda.');
					}else{
						// carga el contenido html
						
						$oCargarVista->setCarga('datos', '/modulos/articulo/vista/verDatos.html');
						// ingresa los datos a representar en el Panel de la vista
						$oDatoVista->setDato('{tituloPanel}', 'Ver Artículo buscado');
						$oDatoVista->setDato('{cantidad}', $this->_cantidad);
						$oDatoVista->setDato('{informacion}', '<p>Muestra los datos de un artículo.</p>
													<p>Seleccione alguna acción para el artículo con botones</p>
													<p>u otra opción del menú.'
											);
						ArticuloDatos::cargaDatos($oArticuloVO, $oDatoVista, $this->_accion);
						// arma la tabla de productos para comparar costos de diferentes proveedores
						$oProductoVO = new ProductoVO();
						$oProductoModelo = new ProductoModelo();
						$oProductoVO->setCodigoB($oArticuloVO->getCodigoB());
						$this->_items = $oProductoModelo->findAllPorCodigoB($oProductoVO);
						$this->_cantListado = $oProductoModelo->getCantidad();
						ArticuloCostosTabla::armaTabla($this->_items, $this->_aProveedores, $this->_aProveedoresLista, $this->_cantListado);
						$oCargarVista->setCarga('tabla', '/modulos/articulo/tabla.html');
					}
				}else{
					if($_POST['codigoBarra'] > 0){
						$oArticuloModelo->count();
						$this->_cantidad = $oArticuloModelo->getCantidad();
						$oDatoVista->setDato('{cantidad}', $this->_cantidad);
						$oArticuloVO->setCodigoB($_POST['codigoBarra']);
						$oArticuloModelo->countCodigoB($oArticuloVO);
						$this->_cantCodigoB = $oArticuloModelo->getCantidad();
						if ($this->_cantCodigoB>1){
							// carga el contenido html
							$oCargarVista->setCarga('opcion', '/modulos/articulo/vista/buscarOpcion.html');
							$oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
							$oDatoVista->setDato('{alertaPeligro}',  'Existen '.$this->_cantCodigoB.' artículos con igual código de barra ('.$oArticuloVO->getCodigoB().'). Verifique en PLEX');
						}else{
							$oArticuloModelo->findPorCodigoB($oArticuloVO);
							$this->_cantidad = $oArticuloModelo->getCantidad();
							if ($this->_cantidad == 0){
								// carga el contenido html
								$oCargarVista->setCarga('opcion', '/modulos/articulo/vista/buscarOpcion.html');
								$oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
								$oDatoVista->setDato('{alertaAdvertencia}',  'No encontró artículo. Intente otra búsqueda.');
							}else{
								// carga el contenido html
								$oCargarVista->setCarga('contenido', '/includes/vista/verVentana.html');
								$oCargarVista->setCarga('datos', '/modulos/articulo/vista/verDatos.html');
								// ingresa los datos a representar en el Panel de la vista
								$oDatoVista->setDato('{tituloPanel}', 'Ver Artículo buscado');
								$oDatoVista->setDato('{cantidad}', $this->_cantidad);
								$oDatoVista->setDato('{informacion}', '<p>Muestra los datos de un artículo.</p>
															<p>Seleccione alguna acción para el artículo con botones</p>
															<p>u otra opción del menú.'
													);
								ArticuloDatos::cargaDatos($oArticuloVO, $oDatoVista, $this->_accion);
								// arma la tabla de productos para comparar costos de diferentes proveedores
								$oProductoVO = new ProductoVO();
								$oProductoModelo = new ProductoModelo();
								$oProductoVO->setCodigoB($oArticuloVO->getCodigoB());
								$this->_items = $oProductoModelo->findAllPorCodigoB($oProductoVO);
								$this->_cantListado = $oProductoModelo->getCantidad();
								ArticuloCostosTabla::armaTabla($this->_items, $this->_aProveedores, $this->_aProveedoresLista, $this->_cantListado);
								$oCargarVista->setCarga('tabla', '/modulos/articulo/tabla.html');
							}
						}	
					}else{
						if($_POST['nombre'] != " "){
							$oArticuloVO->setNombre(trim($_POST['nombre']));
							$this->_items = $oArticuloModelo->findAllPorNombre($oArticuloVO);
							$this->_cantidad = $oArticuloModelo->getCantidad();
							// carga el contenido html

							// ingresa los datos a representar en el panel de la vista
							$oDatoVista->setDato('{tituloPanel}', 'Listado de Artículos buscados');
							$oDatoVista->setDato('{informacion}', '<p>Listado de todos los artículos buscados según las opciones elegidas.</p>
															<p>También puede seleccionar otras acciones</p>
															<p>para los artículos, ver botones.'
												);
							if ($this->_cantidad == 0){ 
								$oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
								$oDatoVista->setDato('{alertaAdvertencia}',  'No encontró artículo. Intente otra búsqueda.');
							}else{
								// arma la tabla de datos a representar
								$oDatoVista->setDato('{cantidad}', $this->_cantidad);
	
								ArticuloTabla::armaTabla($this->_items, $this->_accion, 0, 'TODAS', 0, 'TODOS', 1, '', 0, 'TODOS', 0, '', $this->_cantidad);
								//ArticuloTabla::armaTabla($this->_items, $this->_accion, $this->_idMarca, $this->_nombreMarca, $this->_idRubro, $this->_nombreRubro, $this->_estado, $this->_orden, $this->_origen, $this->_nombreOrigen, $this->_actualizaProv, $this->_nombreActualizaProv, $this->_cantListado);
								//ArticuloTabla::armaTabla($this->_cantidad, $this->_items, $this->_accion, $_POST['marca'], $oMarcaVO->getNombre(), $_POST['rubro'], $oRubroVO->getNombre(), $_POST['estado'], $_POST['orden']);
								$oCargarVista->setCarga('tabla', '/modulos/articulo/tabla.html');
							}
						}else{
							// carga el contenido html
							$oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
							$oCargarVista->setCarga('opcion', '/modulos/articulo/vista/buscarOpcion.html');
							// ingresa los datos a representar en el panel de la vista
							$oDatoVista->setDato('{tituloPanel}', 'Busqueda de Artículos');
							$oDatoVista->setDato('{informacion}', '<p>Buscar los artículos según opciones elegidas.</p>
														<p>La búsqueda se realiza por una sola opción, comenzando por la izquierda.</p>
														<p>También puede seleccionar otras acciones</p>
														<p>para los artículos, ver botones.'
												);
							// ingresa los datos a representar en las alertas de la vista
							$oDatoVista->setDato('{alertaAdvertencia}',  'Debe ingresar algún dato del artículo a buscar.');
							// otros datos a representar
							$this->_items = $oArticuloModelo->count();
							$this->_cantidad = $oArticuloModelo->getCantidad();
							$oDatoVista->setDato('{cantidad}', $this->_cantidad);								
						}
					}
				}
				break;
			# ----> acción Ver
			case 'Ver':
				$oArticuloVO->setId($_POST['bt_ver']);
				$oArticuloModelo->find($oArticuloVO);
				$this->_cantidad = $oArticuloModelo->getCantidad();
				// carga array proveedores
				$oProveedorVO = new ProveedorVO();
				$oProveedorModelo = new ProveedorModelo();
				$this->_items = $oProveedorModelo->findAllProveedoresRef();
				foreach ($this->_items as $this->_item){
					$this->_aProveedores[$this->_item['id']] = $this->_item['inicial'];
					$this->_aProveedoresLista[$this->_item['id']] = $this->_item['lista'];
				}
				// carga el contenido html
				
				$oCargarVista->setCarga('datos', '/modulos/articulo/vista/verDatos.html');
				// carga los eventos (botones)
				$this->_aEventos = array(
					"editar" => "/includes/vista/botonEditar.html",
				);
				$oCargarVista->setCarga('aEventos', $this->_aEventos);
				// ingresa los datos a representar en el Panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Ver Artículo');
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				$oDatoVista->setDato('{informacion}', '<p>Muestra los datos de un artículo.</p>
													<p>Seleccione alguna acción para el artículo con botones</p>
													<p>u otra opción del menú.');
				// ingresa los datos a representar en el contenido de la vista
				ArticuloDatos::cargaDatos($oArticuloVO, $oDatoVista, $this->_accion);
				// arma la tabla de productos para comparar costos de diferentes proveedores
				$oProductoVO = new ProductoVO();
				$oProductoModelo = new ProductoModelo();
				$oProductoVO->setCodigoB($oArticuloVO->getCodigoB());
				$this->_items = $oProductoModelo->findAllPorCodigoB($oProductoVO);
				$this->_cantListado = $oProductoModelo->getCantidad();
				ArticuloCostosTabla::armaTabla($this->_items, $this->_aProveedores, $this->_aProveedoresLista, $this->_cantListado);
				$oCargarVista->setCarga('tabla', '/modulos/articulo/tabla.html');
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