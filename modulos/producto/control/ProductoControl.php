<?php
/**
 * Archivo de la clase control del módulo producto.
 *
 * Archivo de la clase control del módulo producto.
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
 * Clase control del módulo producto.
 *
 * Clase control del módulo producto que permite realizar
 * operaciones sobre la tabla productos (CRUD y otras)
 * según la categoría asignada al usuario.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class ProductoControl
{
	#Propiedades
	private $_html;
	private $_accion;
	private $_items;
	private $_item;
	private $_cantidad;
	private $_nroPagina;
	private $_limiteRenglones=100;
	private $_cantPaginas;
	private $_cantResto;
	private $_cantListado;
	private $_renglonDesde;
	private $_id;
	private $_idProveedor;
	private $_idMarca;
	private $_nombreMarca;
	private $_idRubro;
	private $_nombreRubro;
	private $_estado;
	private $_orden;
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
		Clase::define('Paginador');
		Clase::define('ProductoModelo');
		//Clase::define('ProductoTabla');
		//Clase::define('ProductoDatos');
		Clase::define('ArticuloModelo');
		Clase::define('RubroModelo');
		Clase::define('RubroSelect');
		Clase::define('MarcaModelo');
		Clase::define('MarcaSelect');
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
	 * producto del sistema, de acuerdo a la categoría del
	 * usuario.
	 */
	private function accionControl($oLoginVO)
	{
		
		// Carga acciones del formulario
		// anula agregar para farmacia
		if (isset($_POST['bt_ver'])) $this->_accion = "Ver";
		if (isset($_POST['bt_actualizar'])) $this->_accion = "Actualizar";
		if (isset($_POST['bt_actualizar_conf'])) $this->_accion = "ConfirmarAct";
		if (isset($_POST['bt_listar'])) $this->_accion = "Listar";
		if (isset($_POST['bt_listar_conf'])) $this->_accion = "ConfirmarL";
		if (isset($_POST['bt_pagina_ant'])) $this->_accion = "ConfirmarL";
		if (isset($_POST['bt_pagina_sig'])) $this->_accion = "ConfirmarL";
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
				"listar" => "/includes/vista/botonListar.html",
				"buscar" => "/includes/vista/botonBuscar.html",
				"actualizar" => "/includes/vista/botonActualizar.html"
		);
		$oCargarVista->setCarga('aAcciones', $this->_aAcciones);
		// Ingresa los datos a representar en el html de la vista
		$oDatoVista = new DatoVista();
		$oDatoVista->setDato('{tituloPagina}', 'Productos');
		$oDatoVista->setDato('{usuario}', $oLoginVO->getAlias());
		$oDatoVista->setDato('{tituloBadge}', 'Productos ');
		// Alertas

		// Carga el contenido html y datos según la acción
		$oProductoVO = new ProductoVO();
		$oProductoModelo = new ProductoModelo();
		// Selector de acciones
		switch ($this->_accion){
			# ----> acción Iniciar
			case 'Iniciar':
				// carga el contenido html
				$oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
				// ingresa los datos a representar en las alertas de la vista
				$oDatoVista->setDato('{alertaInfo}',  'Seleccione alguna acción con los botones.');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Productos - Selección de acciones');
				$oDatoVista->setDato('{informacion}', '<p>Puede seleccionar acciones para los productos de las listas</p>
														<p>de precios de los proveedores, ver botones.');
				// arma la tabla de datos a representar
				$this->_items = $oProductoModelo->count();
				$this->_cantidad = $oProductoModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				break;
			# ----> acción Actualizar
			case 'Actualizar':
				// carga el contenido html
				$oCargarVista->setCarga('opcion', '/modulos/producto/vista/proveedorOpcion.html');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Actualización de Productos');
				$oDatoVista->setDato('{informacion}', '<p>Actualiza todos los productos según opciones elegidas.</p><p>También puede seleccionar otras acciones</p><p>para los productos, ver botones.');
				// carga los eventos (botones)
				$this->_aEventos = [
					"actualizarConfirmar" => "/includes/vista/botonActualizarConf.html",
					"borrar" => "/includes/vista/botonBorrar.html"
						];
				$oCargarVista->setCarga('aEventos', $this->_aEventos);
				// ingresa los datos a representar en las alertas de la vista

				 // arma el select de datos de la tabla proveedores a representar
				$oProveedorVO = new ProveedorVO();
				$oProveedorModelo = new ProveedorModelo();
				$this->_items = $oProveedorModelo->findAllProveedoresRef();
				$this->_idProveedor = 0;
				$this->_cantidad = $oProveedorModelo->getCantidad();
				$oDatoVista->setDato('{cantidadProveedor}', $this->_cantidad);
				ProveedorSelect::armaSelect($this->_cantidad, $this->_items, $this->_accion, $this->_idProveedor);
				$oCargarVista->setCarga('selectProveedor', '/modulos/producto/selectProveedor.html');
									
				// arma la tabla de datos a representar
				$this->_items = $oProductoModelo->count();
				$this->_cantidad = $oProductoModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				break;
			# ----> acción Confirmar Actualizar
			case 'ConfirmarAct':
				
				break;
			# ----> acción Listar
			case 'Listar':
				// carga el contenido html
				$oCargarVista->setCarga('opcion', '/modulos/articulo/vista/listarOpcion.html');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Listado de Artículos');
				$oDatoVista->setDato('{informacion}', '<p>Listado de todos los productos según opciones elegidas.</p><p>También puede seleccionar otras acciones</p><p>para los productos, ver botones.');
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
				/*
				// arma el select de datos de la tabla proveedores a representar
				$oProveedorVO = new ProveedorVO();
				$oProveedorModelo = new ProveedorModelo();
				$this->_items = $oProveedorModelo->findAll();
				$this->_cantidad = $oProveedorModelo->getCantidad();
				$this->_idProveedor = $oArticuloVO->getIdProveedor();
				$oDatoVista->setDato('{cantidadProveedor}', $this->_cantidad);
				ProveedorSelect::armaSelect($this->_cantidad, $this->_items, $this->_accion, $this->_idProveedor);
				$oCargarVista->setCarga('selectProveedor', '/modulos/articulo/selectProveedor.html');
				*/
					
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
					$this->_items = $oArticuloModelo->countOpcionListado($_POST['marca'],$_POST['rubro'],$_POST['estado']);
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
				$this->_cantidad = $_POST['cantidad'];
				$this->_nroPagina = $_POST['nroPagina'];
				$this->_cantPaginas = $_POST['cantPaginas'];

				// calcula el "renglón desde" donde comienza la consulta a la tabla
				if (isset($_POST['bt_pagina_ant'])){
					$this->_nroPagina = $this->_nroPagina - 2;
					$this->_renglonDesde = $this->_nroPagina * $this->_limiteRenglones;
				}else{
					$this->_renglonDesde = $this->_nroPagina * $this->_limiteRenglones;
				}

				// lee tabla de productos según las opciones con límite
				$this->_items = $oArticuloModelo->findAllOpcionListadoLimite($this->_idMarca, $this->_idRubro, $this->_estado, $this->_orden, $this->_renglonDesde, $this->_limiteRenglones);
				$this->_cantListado = $oArticuloModelo->getCantidad();

				// ingresa los datos a representar en el panel de la vista
				if ($this->_estado == 1) $oDatoVista->setDato('{tituloPanel}', 'Listado de Artículos (activos)');
				if ($this->_estado == 0) $oDatoVista->setDato('{tituloPanel}', 'Listado de Artículos (pasivos)');
				$oDatoVista->setDato('{informacion}', '<p>Listado de todos los productos según opciones elegidas.</p>
														<p>También puede seleccionar otras acciones</p>
														<p>para los productos, ver botones.'
									);
				// ingresa los datos a representar en las alertas de la vista

				// arma la tabla de datos a representar
					$oDatoVista->setDato('{cantidad}', $this->_cantidad);
					$oDatoVista->setDato('{cantPaginas}', $this->_cantPaginas);
					$this->_nroPagina++;
					$oDatoVista->setDato('{nroPagina}', $this->_nroPagina);
					$oDatoVista->setDato('{cantListado}', $this->_cantListado);

					ArticuloTabla::armaTabla($this->_items, $this->_accion, $this->_idMarca, $this->_nombreMarca, $this->_idRubro, $this->_nombreRubro, $this->_estado, $this->_orden, $this->_cantListado);
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

					break;
				# ----> acción Confirmar Editar
				case 'ConfirmarE':

					break;
				# ----> acción Buscar
				case'Buscar':
					// carga el contenido html
					$oCargarVista->setCarga('opcion', '/modulos/articulo/vista/buscarOpcion.html');
					// ingresa los datos a representar en el panel de la vista
					$oDatoVista->setDato('{tituloPanel}', 'Busqueda de Artículos');
					$oDatoVista->setDato('{informacion}', '<p>Buscar los productos según opciones elegidas.</p>
															<p>La búsqueda se realiza por una sola opción, comenzando por la izquierda.</p>
															<p>También puede seleccionar otras acciones</p>
															<p>para los productos, ver botones.'
										);
					// ingresa los datos a representar en las alertas de la vista

					// arma los datos a representar
					$this->_items = $oArticuloModelo->count();
					$this->_cantidad = $oArticuloModelo->getCantidad();
					$oDatoVista->setDato('{cantidad}', $this->_cantidad);
					break;
				# ----> acción Confirmar Buscar
				case 'ConfirmarB':
					// busco los productos segun las opciones elegidas
					if ($_POST['codigo'] > 0){
						$oArticuloVO->setCodigo($_POST['codigo']);
						$oArticuloModelo->findPorCodigo($oArticuloVO);
						$this->_cantidad = $oArticuloModelo->getCantidad();
						if ($this->_cantidad == 0){
							// carga el contenido html
							$oCargarVista->setCarga('opcion', '/modulos/articulo/vista/buscarOpcion.html');
							$oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
							$oDatoVista->setDato('{alertaAdvertencia}',  'No encontró producto. Intente otra búsqueda.');
						}else{
							// carga el contenido html
							$oCargarVista->setCarga('datos', '/modulos/articulo/vista/verDatos.html');
							// ingresa los datos a representar en el Panel de la vista
							$oDatoVista->setDato('{tituloPanel}', 'Ver Artículo buscado');
							$oDatoVista->setDato('{cantidad}', $this->_cantidad);
							$oDatoVista->setDato('{informacion}', '<p>Muestra los datos de un producto.</p>
																	<p>Seleccione alguna acción para el producto con botones</p>
																	<p>u otra opción del menú.'
												);
							ArticuloDatos::cargaDatos($oArticuloVO, $oDatoVista, $this->_accion);
						}
					}else{
						if($_POST['codigoBarra'] > 0){
							$oArticuloVO->setCodigoB($_POST['codigoBarra']);
							$oArticuloModelo->findPorCodigoB($oArticuloVO);
							$this->_cantidad = $oArticuloModelo->getCantidad();
							if ($this->_cantidad == 0){
								// carga el contenido html
								$oCargarVista->setCarga('opcion', '/modulos/articulo/vista/buscarOpcion.html');
								$oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
								$oDatoVista->setDato('{alertaAdvertencia}',  'No encontró producto. Intente otra búsqueda.');
							}else{
								// carga el contenido html
								$oCargarVista->setCarga('datos', '/modulos/articulo/vista/verDatos.html');
								// ingresa los datos a representar en el Panel de la vista
								$oDatoVista->setDato('{tituloPanel}', 'Ver Artículo buscado');
								$oDatoVista->setDato('{cantidad}', $this->_cantidad);
								$oDatoVista->setDato('{informacion}', '<p>Muestra los datos de un producto.</p>
																		<p>Seleccione alguna acción para el producto con botones</p>
																		<p>u otra opción del menú.'
													);
								ArticuloDatos::cargaDatos($oArticuloVO, $oDatoVista, $this->_accion);
							}
						}else{
							if($_POST['nombre'] != " "){
								$oArticuloVO->setNombre(trim($_POST['nombre']));
								$this->_items = $oArticuloModelo->findAllPorNombre($oArticuloVO);
								$this->_cantidad = $oArticuloModelo->getCantidad();
								// carga el contenido html

								// ingresa los datos a representar en el panel de la vista
								$oDatoVista->setDato('{tituloPanel}', 'Listado de Artículos buscados');
								$oDatoVista->setDato('{informacion}', '<p>Listado de todos los productos buscados según las opciones elegidas.</p>
																	<p>También puede seleccionar otras acciones</p>
																	<p>para los productos, ver botones.'
													);
								if ($this->_cantidad == 0){
									$oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
									$oDatoVista->setDato('{alertaAdvertencia}',  'No encontró producto. Intente otra búsqueda.');
								}else{
									// arma la tabla de datos a representar
									$oDatoVista->setDato('{cantidad}', $this->_cantidad);
									ArticuloTabla::armaTabla($this->_items, $this->_accion, 0, 'TODAS', 0, 'TODOS', 1, '', $this->_cantidad);
									//ArticuloTabla::armaTabla($this->_cantidad, $this->_items, $this->_accion, $_POST['marca'], $oMarcaVO->getNombre(), $_POST['rubro'], $oRubroVO->getNombre(), $_POST['estado'], $_POST['orden']);
									$oCargarVista->setCarga('tabla', '/modulos/articulo/tabla.html');
								}
							}else{
								// carga el contenido html
								$oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
								$oCargarVista->setCarga('opcion', '/modulos/articulo/vista/buscarOpcion.html');
								// ingresa los datos a representar en el panel de la vista
								$oDatoVista->setDato('{tituloPanel}', 'Busqueda de Artículos');
								$oDatoVista->setDato('{informacion}', '<p>Buscar los productos según opciones elegidas.</p>
																	<p>La búsqueda se realiza por una sola opción, comenzando por la izquierda.</p>
																	<p>También puede seleccionar otras acciones</p>
																	<p>para los productos, ver botones.'
														);
								// ingresa los datos a representar en las alertas de la vista
								$oDatoVista->setDato('{alertaAdvertencia}',  'Debe ingresar algún dato del producto a buscar.');
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
					$oDatoVista->setDato('{informacion}', '<p>Muestra los datos de un producto.</p>
														<p>Seleccione alguna acción para el producto con botones</p>
														<p>u otra opción del menú.');
					// ingresa los datos a representar en el contenido de la vista
					ArticuloDatos::cargaDatos($oArticuloVO, $oDatoVista, $this->_accion);
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