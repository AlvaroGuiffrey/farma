<?php
/**
 * Archivo de la clase control del módulo partida.
 *
 * Archivo de la clase control (del patrón MVC) para agregar renglones
 * al comprobante recibido del módulo partida.
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
 * Clase control del módulo partida.
 *
 * Clase control del módulo partida que permite realizar
 * operaciones sobre la tabla partidas (CRUD y otras)
 * según la categoría asignada al usuario.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class PartidaControlR
{
	#Propiedades
	private $_html;
	private $_accion;
	private $_aAcciones = array();
	private $_aVistas = array();
	private $_aEventos = array();
	private $_items;
	private $_cantidad;
	private $_id;
	private $_date;
	private $_idRecibido;
	private $_idProveedor;
	private $_proveedor;
	private $_sumaNetos;
	private $_cantidadUn;
	private $_stock;
	private $_costo;
	private $_importe;
	private $_suma;
	private $_netoRenglon;
	private $_diferencia;
	public $tabla;

	#Métodos
	/**
	* Verifica el login del usuario y nos envia a la
	* función que ejecuta las acciones en el módulo.
	*/
	public function inicio($oLoginVO, $accion, $id)
	{
		date_default_timezone_set('America/Argentina/Buenos_Aires');
		// Se definen las clases necesarias
		Clase::define('CargarVista');
		Clase::define('DatoVista');
		Clase::define('CargarMenu');
		Clase::define('MotorVista');
		Clase::define('RecibidoModelo');
		Clase::define('ProveedorModelo');
		Clase::define('ArticuloModelo');
		Clase::define('RubroModelo');
		Clase::define('PartidaModelo');
		Clase::define('MarcaModelo');
		Clase::define('PartidaRenglonTabla');
		Clase::define('PartidaRecibidoDatos');
		
		// Chequea login
		$oLoginControl = new LoginControl();
		$oLoginControl->chequearLogin($oLoginVO);
		$this->_accion = $accion;
		$this->_idRecibido = $id;
		if (isset($_POST['idRecibido'])) $this->_idRecibido = $_POST['idRecibido'];
		if (isset($_POST['idRecibidoBoton'])) $this->_idRecibido = $_POST['idRecibidoBoton'];
	    $this->accionControl($oLoginVO);
	
	}

	/**
	 * Nos permite agregar renglones de los comprobantes recibidos 
	 * y partidas en el módulo partida del sistema, de acuerdo a la 
	 * categoría del usuario.
	 */
	private function accionControl($oLoginVO)
	{
		// Carga acciones del formulario
		if (isset($_POST['bt_agregar'])) $this->_accion = "AgregarR";
		if (isset($_POST['bt_agregar_renglon'])) $this->_accion = "BuscarA";
		if (isset($_POST['bt_agregar_conf'])) $this->_accion = "ConfirmarA";
		if (isset($_POST['bt_agregar_otro'])) $this->_accion = "BuscarA";
		if (isset($_POST['bt_confirmar'])) $this->_accion = "GuardarA";
		if (isset($_POST['bt_buscar_conf'])) $this->_accion = "AgregarR";
		
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
			"agregar" => "/includes/vista/botonAgregarRenglon.html",
			"volver" => "/includes/vista/botonVolverIngresoCompras.html",
		);
		$oCargarVista->setCarga('aAcciones', $this->_aAcciones);
		// Ingresa los datos a representar en el html de la vista
		$oDatoVista = new DatoVista();
		$oDatoVista->setDato('{tituloPagina}', 'Partidas');
		$oDatoVista->setDato('{usuario}', $oLoginVO->getAlias());
		$oDatoVista->setDato('{tituloBadge}', 'Renglones ');
		
		// Carga el comprobante recibido y el proveedor
		$oRecibidoVO = new RecibidoVO();
		$oRecibidoModelo = new RecibidoModelo();
		$oRecibidoVO->setId($this->_idRecibido);
		$oRecibidoModelo->find($oRecibidoVO);
		$oProveedorVO = new ProveedorVO();
		$oProveedorModelo = new ProveedorModelo();
		$oProveedorVO->setId($oRecibidoVO->getIdProveedor());
		$oProveedorModelo->find($oProveedorVO);
		
		if ($oProveedorModelo->getCantidad()==0){
			$oProveedorVO->setRazonSocial('');
		}
		$oDatoVista->setDato('{idRecibidoBoton}', $this->_idRecibido);
		// Alertas

		// Carga el contenido html y datos según la acción
		// Selector de acciones
		switch ($this->_accion){
		# ----> acción Buscar Artículo para Agregar
			case 'BuscarA':
				$this->_cantidad = 0;
				// carga el contenido html
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Agregar Renglón');
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				$oDatoVista->setDato('{informacion}', '<p>Agrega un renglon al comprobante recibido.</p><p>También puede seleccionar otras acciones,</p><p>ver botones.');
				// suma los netos para comparar con suma de renglones en tabla
				$this->_sumaNetos = $oRecibidoVO->getGravado() + $oRecibidoVO->getExento();				
				$this->_netoRenglon = 0;
				// ingresa los datos a representar del comprobante
				RecibidoDatos::cargaDatos($oRecibidoVO, $oProveedorVO, $oDatoVista, $this->_sumaNetos, $this->_accion);
				// arma la tabla de datos de renglones a representar
				$oPartidaVO = new PartidaVO();
				$oPartidaVO->setIdRecibido($oRecibidoVO->getId());
				$oPartidaModelo = new PartidaModelo();
				$this->_items = $oPartidaModelo->findAllPorIdRecibido($oPartidaVO);
				$this->_cantidad = $oPartidaModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				RenglonTabla::armaTabla($this->_cantidad, $this->_items, $this->_accion, $this->_idRecibido, $this->_sumaNetos, $this->_netoRenglon);
				// Alerta de Recibido
				if($oRecibidoVO->getConsistencia()==0){
					// carga la alerta en el contenido html de la vista
					$oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
					// ingresa los datos a representar en las alertas de la vista
					$oDatoVista->setDato('{alertaPeligro}', 'Inconsistencias en los importes del comprobante recibido.');
				}else{
					// carga la alerta en el contenido html de la vista
					$oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
					// ingresa los datos a representar en las alertas de la vista
					$oDatoVista->setDato('{alertaInfo}', 'Comprobante recibido OK.');
				}
				// carga las vistas HTML
				$this->_aVistas = array(
					"opcion" => "/modulos/articulo/vista/agregarOpcion.html",
					"recibido" => "/modulos/partida/vista/buscarAgregar.html",
					"renglones" => "/modulos/partida/tabla.html"
				);
				$oCargarVista->setCarga('aVistas', $this->_aVistas);
				// carga los eventos (botones)
				$this->_aEventos = array( 
					"actualizar" => "/includes/vista/botonActualizar.html",
					"volverCompras" => "/includes/vista/botonVolverIngresoCompras.html",
				);
				$oCargarVista->setCarga('aEventos', $this->_aEventos);
				
				break;
		# ----> acción Agregar renglón
			case 'AgregarR':
				// carga el contenido html
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Agregar Renglón');
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				$oDatoVista->setDato('{informacion}', '<p>Carga datos de un artículo de un renglon para</p><p>agregar al comprobante recibido.</p><p>También puede seleccionar otras acciones,</p><p>ver botones.');
				// suma los netos para comparar con suma de renglones en tabla
				$this->_sumaNetos = $oRecibidoVO->getGravado() + $oRecibidoVO->getExento();
				$this->_netoRenglon = 0;
				// ingresa los datos a representar del comprobante
				RecibidoDatos::cargaDatos($oRecibidoVO, $oProveedorVO, $oDatoVista, $this->_sumaNetos, $this->_accion);

				// busca renglones (partidas) y arma tabla a representar
				$oPartidaVO = new PartidaVO();
				$oPartidaVO->setIdRecibido($oRecibidoVO->getId());
				$oPartidaModelo = new PartidaModelo();
				$this->_items = $oPartidaModelo->findAllPorIdRecibido($oPartidaVO);
				$this->_cantidad = $oPartidaModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				RenglonTabla::armaTabla($this->_cantidad, $this->_items, $this->_accion, $this->_idRecibido, $this->_sumaNetos, $this->_netoRenglon);
				// busca los artículos segun las opciones elegidas desde la izquierda
				$oArticuloModelo = new ArticuloModelo();
				$oArticuloVO = new ArticuloVO();
				if($_POST['nombre'] != " "){
					$oArticuloVO->setNombre(trim($_POST['nombre']));
					$this->_items = $oArticuloModelo->findPorNombre($oArticuloVO);
					$this->_cantidad = $oArticuloModelo->getCantidad();
				}else{ 
					if ($_POST['codigo'] > 0){
						$oArticuloVO->setCodigo($_POST['codigo']);
						$oArticuloModelo->findPorCodigo($oArticuloVO);
						$this->_cantidad = $oArticuloModelo->getCantidad();
					}else{
						if($_POST['codigoMarca'] > 0){
							$oArticuloVO->setCodigoM($_POST['codigoMarca']);
							$oArticuloModelo->findPorCodigoM($oArticuloVO);
							$this->_cantidad = $oArticuloModelo->getCantidad();
						}else{
							// carga el contenido html
							$oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
							$oDatoVista->setDato('{alertaAdvertencia}',  'No seleccionó opciones de búsqueda de artículo. Intente otra búsqueda.');
							}	
						}
					}
				//cargar los datos del artículo o el mensaje de advertencia	
				if ($this->_cantidad == 0){
						$oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
						$oDatoVista->setDato('{alertaAdvertencia}',  'No encontró artículo. Intente otra búsqueda.');
				}else{
					$oDatoVista->setDato('{idArticulo}', $oArticuloVO->getId());
					$oDatoVista->setDato('{codigo}', $oArticuloVO->getCodigo());
					$oDatoVista->setDato('{nombreArticulo}', $oArticuloVO->getNombre());
					// busca la marca del artículo a agregar
					$oMarcaModelo = new MarcaModelo();
					$oMarcaVO = new MarcaVO();
					$oMarcaVO->setId($oArticuloVO->getIdMarca());
					$oMarcaModelo->find($oMarcaVO);
					if ($oMarcaModelo->getCantidad()>0){
						$oDatoVista->setDato('{codigoMarca}', $oMarcaVO->getId());
						$oDatoVista->setDato('{nombreMarca}', $oMarcaVO->getNombre());
					}else{
						$oDatoVista->setDato('{codigoMarca}', $oArticuloVO->getIdMarca());
						$oDatoVista->setDato('{nombreMarca}', 'SIN MARCA IDENTIFICADA');
					}
					// busca el rubro del artículo a agregar
					$oRubroModelo = new RubroModelo();
					$oRubroVO = new RubroVO();		
					$oRubroVO->setId($oArticuloVO->getIdRubro());
					$oRubroModelo->find($oRubroVO);
					if ($oRubroModelo->getCantidad()>0){
						$oDatoVista->setDato('{nombreRubro}', $oRubroVO->getNombre());
					}else{
						$oDatoVista->setDato('{nombreRubro}', 'SIN RUBRO IDENTIFICADO');
					}
				}
				// Alerta de Recibido
				if($oRecibidoVO->getConsistencia()==0){
					// carga la alerta en el contenido html de la vista
					$oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
					// ingresa los datos a representar en las alertas de la vista
					$oDatoVista->setDato('{alertaPeligro}', 'Inconsistencias en los importes del comprobante recibido.');
				}else{
					// carga la alerta en el contenido html de la vista
					$oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
					// ingresa los datos a representar en las alertas de la vista
					$oDatoVista->setDato('{alertaInfo}', 'Comprobante recibido OK.');
				}
				// carga las vistas HTML
				$this->_aVistas = array(
					"opcion" => "/modulos/partida/vista/articuloAgregar.html",
					"recibido" => "/modulos/partida/vista/buscarAgregar.html",
					"renglones" => "/modulos/partida/tabla.html"
				);
				$oCargarVista->setCarga('aVistas', $this->_aVistas);
				// carga los eventos (botones)
				$this->_aEventos = array(
					"actualizar" => "/includes/vista/botonActualizar.html",
					"volverCompras" => "/includes/vista/botonVolverIngresoCompras.html",
				);
				$oCargarVista->setCarga('aEventos', $this->_aEventos);
				
				break;
		# ----> acción Confirmar Agregar renglón
			case 'ConfirmarA':
				// verifico que cantidad y costo del artículo sean números válidos
				if (is_numeric($_POST['cantidadUn'])){
					if ($_POST['cantidadUn'] < 1){
						$oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
						$oDatoVista->setDato('{alertaAdvertencia}',  'Cantidad menor que 1, vuelva a cargar renglón.');
					}
				}else{
					$oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
					$oDatoVista->setDato('{alertaAdvertencia}',  'Cantidad no es un número, vuelva a cargar renglón.');
				}
				$this->_cantidadUn = $_POST['cantidadUn'];
				if (is_numeric($_POST['costo'])){
					if ($_POST['costo'] < 0.01){
						$oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
						$oDatoVista->setDato('{alertaAdvertencia}',  'Costo debe ser mayor que $ 0,00 , vuelva a cargar renglón.');
					}	
				}else{
					$oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
					$oDatoVista->setDato('{alertaAdvertencia}',  'Costo no es un número, vuelva a cargar renglón.');
				}
				$this->_costo = $_POST['costo'];
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Agregar Renglón');
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				$oDatoVista->setDato('{informacion}', '<p>Confirmar datos de un artículo de un renglon para</p><p>agregar al comprobante recibido.</p><p>También puede seleccionar otras acciones,</p><p>ver botones.');
				// suma los netos para comparar con suma de renglones en tabla
				$this->_sumaNetos = $oRecibidoVO->getGravado() + $oRecibidoVO->getExento();
				$this->_netoRenglon = $this->_costo * $this->_cantidadUn;
				// ingresa los datos a representar del comprobante
				RecibidoDatos::cargaDatos($oRecibidoVO, $oProveedorVO, $oDatoVista, $this->_sumaNetos, $this->_accion);
				
				// busca renglones (partidas) y arma tabla a representar
				$oPartidaVO = new PartidaVO();
				$oPartidaVO->setIdRecibido($oRecibidoVO->getId());
				$oPartidaModelo = new PartidaModelo();
				$this->_items = $oPartidaModelo->findAllPorIdRecibido($oPartidaVO);
				$this->_cantidad = $oPartidaModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				RenglonTabla::armaTabla($this->_cantidad, $this->_items, $this->_accion, $this->_idRecibido, $this->_sumaNetos, $this->_netoRenglon);
				// busca el artículo a confirmar 
				$oArticuloModelo = new ArticuloModelo();
				$oArticuloVO = new ArticuloVO();
				$oArticuloVO->setId($_POST['idArticulo']);
				$oArticuloModelo->find($oArticuloVO);
				if ($oArticuloModelo->getCantidad()==0){
					$oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
					$oDatoVista->setDato('{alertaAdvertencia}',  'No encontró artículo. Intente otra búsqueda.');
				}else{
					$oDatoVista->setDato('{idArticulo}', $oArticuloVO->getId());
					$oDatoVista->setDato('{codigo}', $oArticuloVO->getCodigo());
					$oDatoVista->setDato('{nombreArticulo}', $oArticuloVO->getNombre());
					$oDatoVista->setDato('{cantidadUn}', $this->_cantidadUn);
					$oDatoVista->setDato('{costo}', $this->_costo);
					$oDatoVista->setDato('{netoRenglon}', $this->_netoRenglon);
					// busca la marca del artículo a agregar
					$oMarcaModelo = new MarcaModelo();
					$oMarcaVO = new MarcaVO();
					$oMarcaVO->setId($oArticuloVO->getIdMarca());
					$oMarcaModelo->find($oMarcaVO);					
					if ($oMarcaModelo->getCantidad()>0){
						$oDatoVista->setDato('{codigoMarca}', $oMarcaVO->getId());
						$oDatoVista->setDato('{nombreMarca}', $oMarcaVO->getNombre());
					}else{
						$oDatoVista->setDato('{codigoMarca}', $oArticuloVO->getIdMarca());
						$oDatoVista->setDato('{nombreMarca}', 'SIN MARCA IDENTIFICADA');
					}
				// busca el rubro del artículo a agregar
					$oRubroModelo = new RubroModelo();
					$oRubroVO = new RubroVO();
					$oRubroVO->setId($oArticuloVO->getIdRubro());
					$oRubroModelo->find($oRubroVO);
					if ($oRubroModelo->getCantidad()>0){
						$oDatoVista->setDato('{nombreRubro}', $oRubroVO->getNombre());
					}else{
						$oDatoVista->setDato('{nombreRubro}', 'SIN RUBRO IDENTIFICADO');
					}
				}
				// Alerta de Recibido
				if($oRecibidoVO->getConsistencia()==0){
					// carga la alerta en el contenido html de la vista
					$oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
					// ingresa los datos a representar en las alertas de la vista
					$oDatoVista->setDato('{alertaPeligro}', 'Inconsistencias en los importes del comprobante recibido.');
				}else{
					// carga la alerta en el contenido html de la vista
					$oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
					// ingresa los datos a representar en las alertas de la vista
					$oDatoVista->setDato('{alertaInfo}', 'Comprobante recibido OK.');
				}
				// carga las vistas HTML
				$this->_aVistas = array(
					"opcion" => "/modulos/partida/vista/articuloConfirmar.html",
					"recibido" => "/modulos/partida/vista/buscarAgregar.html",
					"renglones" => "/modulos/partida/tabla.html"
				);
				$oCargarVista->setCarga('aVistas', $this->_aVistas);
				// carga los eventos (botones)
				$this->_aEventos = array(
					"actualizar" => "/includes/vista/botonActualizar.html",
					"volverCompras" => "/includes/vista/botonVolverIngresoCompras.html",
				);
				$oCargarVista->setCarga('aEventos', $this->_aEventos);
				
				break;
		# ----> acción Grabar renglón
			case 'GuardarA':	
				// Graba renglon (partida)
				$oPartidaModelo = new PartidaModelo();
				$oPartidaVO = new PartidaVO();
				$oPartidaVO->setIdArticulo($_POST['idArticulo']);
				$oPartidaVO->setIdRecibido($oRecibidoVO->getId());
				$oPartidaVO->setFecha($_POST['fecha']);
				$oPartidaVO->setCantIngresada($_POST['cantidadUn']);
				$oPartidaVO->setStock($_POST['cantidadUn']);
				$oPartidaVO->setCosto($_POST['costo']);
				$oPartidaVO->setIvaAlicuota(0);
				$oPartidaVO->setComentario('');
				$oPartidaVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
				$this->_date = date('Y-m-d H:i:s');
				$oPartidaVO->setFechaAct($this->_date);
				$oPartidaModelo->insert($oPartidaVO);
				$this->_cantidad = $oPartidaModelo->getCantidad();
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Agregar Renglón');
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				$oDatoVista->setDato('{informacion}', '<p>Datos de un artículo de un renglon</p><p>agregados al comprobante recibido.</p><p>También puede seleccionar otras acciones,</p><p>ver botones.');
				// Alerta grabe renglon (partida)
				if($oPartidaModelo->getCantidad()>0){
					// carga la alerta en el contenido html de la vista
					$oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
					// ingresa los datos a representar en las alertas de la vista
					$oDatoVista->setDato('{alertaSuceso}', 'Se grabo renglón con EXITO!!!.');
				}else{
					// carga la alerta en el contenido html de la vista
					$oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
					// ingresa los datos a representar en las alertas de la vista
					$oDatoVista->setDato('{alertaAdvertencia}', 'No grabo el renglón. VERIFIQUE.');
				}
				// modifica stock y costo del artículo
				$oArticuloModelo = new ArticuloModelo();
				$oArticuloVO = new ArticuloVO();
				$oArticuloVO->setId($oPartidaVO->getIdArticulo());
				$oArticuloModelo->find($oArticuloVO);
				if ($oArticuloModelo->getCantidad()>0){
					$this->_stock = $oArticuloVO->getStock();
					$this->_cantidadUn = $_POST['cantidadUn'];
					$this->_stock = $this->_stock + $this->_cantidadUn;
					$oArticuloVO->setStock($this->_stock);
					$oArticuloVO->setCosto($oPartidaVO->getCosto());
					$oArticuloVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
					$this->_date = date('Y-m-d H:i:s');
					$oArticuloVO->setFechaAct($this->_date);
					$oArticuloModelo->update($oArticuloVO);
					if ($oArticuloModelo->getCantidad()==0){
						// carga la alerta en el contenido html de la vista
						$oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
						// ingresa los datos a representar en las alertas de la vista
						$oDatoVista->setDato('{alertaAdvertencia}', 'No actualizó el stock y el costo del artículo. VERIFIQUE.');
					}
				}
				// ver consistencia del comprobante recibido
				// suma los netos para comparar con renglones
				$this->_sumaNetos = $oRecibidoVO->getGravado() + $oRecibidoVO->getExento();
				// ingresa los datos a representar del comprobante
				RecibidoDatos::cargaDatos($oRecibidoVO, $oProveedorVO, $oDatoVista, $this->_sumaNetos, $this->_accion);
				// busca y suma renglones
				$oPartidaVO = new PartidaVO();
				$oPartidaVO->setIdRecibido($oRecibidoVO->getId());
				$oPartidaModelo = new PartidaModelo();
				$this->_items = $oPartidaModelo->findAllPorIdRecibido($oPartidaVO);
				$this->_cantidad = $oPartidaModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				RenglonTabla::armaTabla($this->_cantidad, $this->_items, $this->_accion, $this->_idRecibido, $this->_sumaNetos, $this->_netoRenglon);
				if ($this->_cantidad > 0){
					foreach ($this->_items as $item)
					{
						$this->_importe = +($item['costo'] * $item['cant_ingresada']);
						$this->_suma = $this->_suma + $this->_importe;
					}	
					if ($this->_suma == $this->_sumaNetos){
						if($oRecibidoVO->getConsistencia() == 0){
							$oRecibidoVO->setConsistencia(1);
							$oRecibidoVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
							$this->_date = date('Y-m-d H:i:s');
							$oRecibidoVO->setFechaAct($this->_date);							
							$oRecibidoModelo->update($oRecibidoVO);
							// carga la alerta en el contenido html de la vista
							$oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
							// ingresa los datos a representar en las alertas de la vista
							$oDatoVista->setDato('{alertaInfo}', 'Comprobante recibido OK.');
						}else{
							// carga la alerta en el contenido html de la vista
							$oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
							// ingresa los datos a representar en las alertas de la vista
							$oDatoVista->setDato('{alertaInfo}', 'Comprobante recibido OK.');
						}
					}else{
						if($oRecibidoVO->getConsistencia() == 1){
							$oRecibidoVO->setConsistencia(0);
							$oRecibidoVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
							$this->_date = date('Y-m-d H:i:s');
							$oRecibidoVO->setFechaAct($this->_date);
							$oRecibidoModelo->update($oRecibidoVO);
							// carga la alerta en el contenido html de la vista
							$oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
							// ingresa los datos a representar en las alertas de la vista
							$oDatoVista->setDato('{alertaPeligro}', 'Inconsistencias en los importes del comprobante recibido.');
						}else{
							// carga la alerta en el contenido html de la vista
							$oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
							// ingresa los datos a representar en las alertas de la vista
							$oDatoVista->setDato('{alertaPeligro}', 'Inconsistencias en los importes del comprobante recibido.');	
						}
					}
				}else{
					if($oRecibidoVO->getConsistencia()==0){
						// carga la alerta en el contenido html de la vista
						$oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
						// ingresa los datos a representar en las alertas de la vista
						$oDatoVista->setDato('{alertaPeligro}', 'Inconsistencias en los importes del comprobante recibido.');
					}else{
						// carga la alerta en el contenido html de la vista
						$oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
						// ingresa los datos a representar en las alertas de la vista
						$oDatoVista->setDato('{alertaInfo}', 'Comprobante recibido OK.');
					}
				}
				// carga las vistas HTML
				$this->_aVistas = array(
					"recibido" => "/modulos/partida/vista/buscarAgregar.html",
					"renglones" => "/modulos/partida/tabla.html"
				);
				$oCargarVista->setCarga('aVistas', $this->_aVistas);
				// carga los eventos HTML (botones)
				$this->_aEventos = array(
					"volverCompras" => "/includes/vista/botonVolverIngresoCompras.html",
				);
				$oCargarVista->setCarga('aEventos', $this->_aEventos);
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