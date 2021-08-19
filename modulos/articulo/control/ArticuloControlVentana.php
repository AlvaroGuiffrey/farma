<?php
/**
 * Archivo de la clase control de ventana del módulo artículo.
 *
 * Archivo de la clase control de ventana del módulo artículo.
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
 * Clase control de ventana del módulo artículo.
 *
 * Clase control de ventana del módulo artículo que permite realizar
 * operaciones para ver y editar un artículo de la tabla artículos
 * según la categoría asignada al usuario.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class ArticuloControlVentana 
{
	#Propiedades
	private $_html;
	private $_accion;
	private $_items;
	private $_cantidad;
	private $_cantListado;
	private $_id;
	private $_idProveedor;
	private $_idRubro;
	private $_date;
	private $_estado;
	private $_aProveedores = array();
	private $_aProveedoresLista = array();
	public $tabla;
	
	#Métodos
	/**
	 * Verifica el login del usuario y nos envia a la
	 * función que ejecuta las acciones en el módulo.
	 */
	public function inicio($oLoginVO, $accion, $id)
	{
		date_default_timezone_set('America/Argentina/Buenos_Aires');
		// Define las clases
		Clase::define('CargarVista');
		Clase::define('DatoVista');
		Clase::define('MotorVista');
		Clase::define('ArticuloModelo');
		Clase::define('ArticuloDatos');
		Clase::define('ArticuloCostosTabla');
		Clase::define('RubroModelo');
		Clase::define('RubroSelect');
		Clase::define('MarcaModelo');
		Clase::define('MarcaSelect');
		Clase::define('ProveedorModelo');
		Clase::define('ProveedorSelect');
		Clase::define('ProductoModelo');

		// Chequea login
		$oLoginControl = new LoginControl();
		$oLoginControl->chequearLogin($oLoginVO);
		$this->_accion = $accion;
		$this->_id = $id;
		$this->accionControl($oLoginVO);
	}
	
	/**
	 * Nos permite ejecutar las acciones para ventana del módulo
	 * artículo del sistema, de acuerdo a la categoría del usuario.
	 */
	private function accionControl($oLoginVO)
	{
		// Carga acciones del formulario
		if (isset($_POST['bt_editar'])) $this->_accion = "Editar";
		if (isset($_POST['bt_editar_conf'])) $this->_accion = "ConfirmarE";
		if (isset($_POST['bt_ver'])) $this->_accion = "Ver";
	
		// Carga los archivos html para la vista
		$oCargarVista = new CargarVista();
		$oCargarVista->setCarga('pagina', '/includes/vista/pagina.html');
		// Carga el menú de la vista según la categoría del usuario
	
		// Ingresa los datos a representar en el html de la vista
		$oDatoVista = new DatoVista();
		$oDatoVista->setDato('{dir}', $_SESSION['dir']);
		$oDatoVista->setDato('{tituloPagina}', 'Artículos');

		// Alertas
	
		// Carga el contenido html y datos según la acción
		$oArticuloVO = new ArticuloVO();
		$oArticuloModelo = new ArticuloModelo();
		// Selector de acciones
		switch ($this->_accion){
			# ----> acción Editar
			case 'Editar':
				 $oArticuloVO->setId($this->_id);
				 $oArticuloModelo->find($oArticuloVO);
				 $this->_cantidad = $oArticuloModelo->getCantidad();

				 // carga el contenido html
				 $oCargarVista->setCarga('contenido', '/includes/vista/editarVentana.html');
				 $oCargarVista->setCarga('datos', '/modulos/articulo/vista/editarDatos.html');
				 // ingresa los datos a representar en el Panel de la vista
				 $oDatoVista->setDato('{tituloPanel}', 'Editar Artículo');
				 
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
				$oCargarVista->setCarga('contenido', '/includes/vista/verVentana.html');
				$oCargarVista->setCarga('datos', '/modulos/articulo/vista/verDatos.html');
				// ingresa los datos a representar en el Panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Ver Artículo Editado');
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				// ingresa los datos a representar en el contenido de la vista
				ArticuloDatos::cargaDatos($oArticuloVO, $oDatoVista, $this->_accion);

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
			case 'Ver':
				$oArticuloVO->setId($this->_id);
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
				$oCargarVista->setCarga('contenido', '/includes/vista/verVentana.html');
				$oCargarVista->setCarga('datos', '/modulos/articulo/vista/verDatos.html');
				// ingresa los datos a representar en el Panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Ver Artículo');
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