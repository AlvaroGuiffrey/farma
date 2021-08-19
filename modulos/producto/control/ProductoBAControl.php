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
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey (alvaroguiffrey@gmail.com)
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Release 1.0
 */

/**
 * Clase control del módulo producto.
 *
 * Clase control del módulo producto que permite realizar
 * operaciones sobre la tabla productos (CRUD y otras)
 * según la categoría asignada al usuario.
 *
 * @author     Alvaro Guiffrey (alvaroguiffrey@gmail.com)
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 * @date 	   16/03/2020
 */

class ProductoBAControl
{
	#Propiedades
	private $_html;
	private $_accion;
	private $_items;
	private $_item;
	private $_cantidad;
	private $_aProductos;
	private $_aProductosAgrega = array();
	private $_aProductosBaja = array();
	private $_aProductosModi = array();
	private $_producto;
	private $_cantAgregados;
	private $_cantActualizados;
	private $_cantEliminados;
	private $_cantDuplicados;
	private $_cantDupliCodigoP;
	private $_cantCodigoBRep;
	private $_cantCodigoPRep;
	private $_cantUpdate;
	private $_cantLista;
	private $_renglonDesde;
	private $_id;
	private $_idProveedor;
	private $_razonSocial;
	private $_estado;
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
		Clase::define('ProductoModelo');
		Clase::define('ProductoProvModelo');
		Clase::define('ArticuloModelo');
		Clase::define('ProveedorModelo');


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
				"actualizar" => "/includes/vista/botonActualizar.html"
		);
		$oCargarVista->setCarga('aAcciones', $this->_aAcciones);
		// Ingresa los datos a representar en el html de la vista
		$oDatoVista = new DatoVista();
		$oDatoVista->setDato('{tituloPagina}', 'Productos ');
		$oDatoVista->setDato('{usuario}', $oLoginVO->getAlias());
		$oDatoVista->setDato('{tituloBadge}', 'Productos ');
		// Alertas

		// Carga el contenido html y datos según la acción
		$oProductoVO = new ProductoVO();
		$oProductoModelo = new ProductoModelo();
		$oProductoProvVO = new ProductoProvVO();
		$oProductoProvModelo = new ProductoProvModelo();
		// Carga Lista del proveedor
		$archivoCSV = fopen ($_SERVER['DOCUMENT_ROOT'].$_SESSION['dir']."/archivos/babelito.csv","r");
		// Cuenta registros Lista del proveedor
		while ($registro = fgetcsv($archivoCSV)){
			$this->_cantLista++;
		}
		fclose($archivoCSV);

		// Selector de acciones
		switch ($this->_accion){
			# ----> acción Iniciar
			case 'Iniciar':
				// carga el contenido html
				$oCargarVista->setCarga('datos', '/modulos/producto/vista/actualizarProductos.html');
				$oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
				// ingresa los datos a representar en las alertas de la vista
				$oDatoVista->setDato('{alertaInfo}',  'Actualiza productos con lista del proveedor.');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Actualiza Productos - Selección de acciones');
				$oDatoVista->setDato('{informacion}', '<p>Puede seleccionar acciones para los productos de las listas</p>
														<p>de precios de los proveedores, ver botones.');
				/*
				// Mensaje de problemas del proveedor
				$oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
				// ingresa los datos a representar en las alertas de la vista
				$oDatoVista->setDato('{alertaPeligro}',  'Proveedor con PROBLEMAS en la lista. <b>NO ACTUALIZA!!!</b>.');
				*/
				// arma la tabla de datos a representar
				// carga datos del proveedor
				$this->_idProveedor = 9;
				$oProveedorVO = new ProveedorVO();
				$oProveedorModelo = new ProveedorModelo();
				$oProveedorVO->setId($this->_idProveedor);
				$oProveedorModelo->find($oProveedorVO);
				$oDatoVista->setDato('{idProveedor}', $oProveedorVO->getId());
				$oDatoVista->setDato('{razonSocial}', $oProveedorVO->getRazonSocial());
				$oDatoVista->setDato('{inicial}', $oProveedorVO->getInicial());
				// carga cantidad de productos
				$this->_items = $oProductoModelo->countPorProveedor($oProveedorVO->getId());
				$this->_cantidad = $oProductoModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				$oDatoVista->setDato('{cantProductos}', $this->_cantidad);
				$oDatoVista->setDato('{cantLista}', $this->_cantLista);
				$this->_cantAgregados = $this->_cantActualizados = $this->_cantEliminados = 0;
				$oDatoVista->setDato('{cantAgregados}', $this->_cantAgregados);
				$oDatoVista->setDato('{cantActualizados}', $this->_cantActualizados);
				$oDatoVista->setDato('{cantEliminados}', $this->_cantEliminados);
				break;
			# ----> acción Actualizar
			case 'Actualizar':
				// carga el contenido html
				$oCargarVista->setCarga('datos', '/modulos/producto/vista/actualizarProductos.html');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Actualización de Productos');
				$oDatoVista->setDato('{informacion}', '<p>Actualiza todos los productos del proveedor de referencia</p>
														<p>con la lista del proveedor.</p>');
				// recibe los datos por POST
				$this->_idProveedor = $_POST['idProveedor'];
				$oDatoVista->setDato('{idProveedor}', $this->_idProveedor);
				$oDatoVista->setDato('{razonSocial}', $_POST['razonSocial']);
				$oDatoVista->setDato('{inicial}', $_POST['inicial']);
				$oDatoVista->setDato('{cantidad}', $_POST['cantProductos']);
				$oDatoVista->setDato('{cantProductos}', $_POST['cantProductos']);
				$oDatoVista->setDato('{cantLista}', $this->_cantLista);
				// pone en 0 los totales
				$this->_cantAgregados = $this->_cantActualizados = $this->_cantEliminados = $this->_cantUpdate = 0;
				$cont = $contP = $conB = $conE = 0;

			/**
			 * PASO 1
			 * Borra la tabla productos_prov para limpìar datos del proceso anterior
			 * y arma tabla nueva para el proveedor.
			 *
			 * Archivo descargado desde la web con los siguientes parámetros:
			 * csv (separado por ;)
			 * importe neto (separado por ,)
			 */

				// Hora de inicio de Paso1
				//echo "Paso 1 Carga productos_prov (inicio): ".date('Y-m-d H:i:s')."<br>";
				// elimina la tabla productos_prov
				$oProductoProvModelo->truncate();
				// Arma la nueva tabla productos_prov para el proveedor
				$archivoCSV = fopen ($_SERVER['DOCUMENT_ROOT'].$_SESSION['dir']."/archivos/babelito.csv","r");
				while ($data = fgetcsv ($archivoCSV, 1000, ";")) {
					$cont++;
					$codigoP = trim($data[1]);
					$codigoB = trim($data[0]);
					$nombre = utf8_decode($data[2]);
					$precioSinMiles = str_replace(".", "", $data[3]);
					$precio = str_replace(",", ".", $precioSinMiles);
					$contP++;
					if (ctype_space($codigoP) == false){ // Sigo si tiene código de producto del proveedor
					    //echo " -> ".$codigoP." - (".$codigoB.") - ".$nombre." - ".$precio."<br>";
					    // Agrega otros datos necesarios
						$codigoIva = 5; // Precio neto + iva
						$tipoDescuento = 3; // Precio neto sin descuento
						$estado = 1; // Estado activo
						// Inserto el producto en tabla producto_prov
						$idArticulo = 0;
						$oProductoProvVO->setIdProveedor($this->_idProveedor);
						$oProductoProvVO->setCodigoB($codigoB);
						$oProductoProvVO->setCodigoP($codigoP);
						$oProductoProvVO->setNombre($nombre);
						$oProductoProvVO->setPrecio($precio);
						$oProductoProvVO->setCodigoIva($codigoIva);
						$oProductoProvVO->setTipoDescuento($tipoDescuento);
						$oProductoProvVO->setEstado($estado);
						$oProductoProvVO->setIdArticulo($idArticulo);
						$oProductoProvVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
						$this->_date = date('Y-m-d H:i:s');
						$oProductoProvVO->setFechaAct($this->_date);
						$oProductoProvModelo->insert($oProductoProvVO);
						//$this->_cantAgregados++;
					} else { // Código del proveedor en blanco "NO" inserto datos
						$contB++;
						//echo "***** ---> BLANCO ".$codigoP." - (".$codigoB.") - ".$nombre." - ".$precio."<br>";
					}

				}
				// Cierra archivo csv
				fclose($archivoCSV);

				// Muestra totales para control
				//echo "Registros leidos CSV: ".$cont."<br>";
				//echo "Registro con P: ".$contP."<br>";
				//echo "Registros P con código vacio: ".$contB."<br>";
				//echo "Otros registros: ".$contNoP."<br>";
				// Fin mujestra totales para control


			/**
			 *
			 * PASO 2:
			 * Tabla productos_prov: Verifico si existen pares de codigo de barra y
			 * de proveedor repetidos y cambio de estado.
			 *
			 *  estado = 9 (repetido)
			 *
			 */

				// Hora de inicio de Paso2
				//echo "Paso 2 Pares de Codigos repetidos (inicio): ".date('Y-m-d H:i:s')."<br>";
				$this->_cantDuplicados = $this->_cantCodigoBRep = 0;
				$this->_aProductos = $oProductoProvModelo->findAllCodigoBCodigoPRepetidos();
				//var_dump($this->_aProductos);
				//echo "<br> Cantidad Leidos repetidos: ".$oProductoProvModelo->getCantidad()."<br>";
				if ($oProductoProvModelo->getCantidad()>0){ // hay productos_prov con código de barra y de proveedor repetidos
				    // cambiar estado a los productos_prov con codigo de barra y de proveedor repetidos
				    foreach ($this->_aProductos as $this->_producto){
				        if ($this->_producto['codigo_b']>0){ // Si codigo_b es CERO no cambia estado
				            $this->_cantCodigoBRep++;
				            $oProductoProvVO->setCodigoB($this->_producto['codigo_b']);
				            $oProductoProvVO->setCodigoP($this->_producto['codigo_p']);
				            $this->_items = $oProductoProvModelo->findAllPorCodigoBCodigoP($oProductoProvVO);
				            //var_dump($this->_items);
				            //echo "<br> Cantidad Leidos repetidos: ".$oProductoProvModelo->getCantidad()."<br>";
				            if ($oProductoProvModelo->getCantidad()>1){ // Encontro los repetidos
				                foreach ($this->_items as $this->_item){
				                    $oProductoProvVO->setId($this->_item['id']);
				                    $oProductoProvModelo->find($oProductoProvVO);
				                    //echo "-> ".$oProductoProvVO->getCodigoP()." (".$oProductoProvVO->getCodigoB().") $ ".$oProductoProvVO->getPrecio()." - ";
				                    //echo " Iva: ".$oProductoProvVO->getCodigoIva()." Desc: ".$oProductoProvVO->getTipoDescuento()."<br>";
				                    $oProductoProvVO->setEstado(9);
				                    $oProductoProvVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
				                    $this->_date = date('Y-m-d H:i:s');
				                    $oProductoProvVO->setFechaAct($this->_date);
				                    $oProductoProvModelo->update($oProductoProvVO);
				                    $this->_cantDuplicados++;
				                }
				            }
				        }
				    }
				    // Avisa que hay productos con codigo de barra repetidos
				    $oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
				    // ingresa los datos a representar en las alertas de la vista
				    $oDatoVista->setDato('{alertaPeligro}',  'Lista del proveedor con <b>CODIGOS de BARRA y de PROVEEDOR repetidos</b>, CONSULTE...
                                                              <p>Son '.$this->_cantCodigoBRep.' códigos de barras repetidos en '.$this->_cantDuplicados.' productos.</p>');
				}
				unset($this->_aProductos);

			/**
			 *
			 * PASO 3:
			 * Tabla productos_prov: Verifico si existen codigos del proveedor repetidos y cambio de
			 * estado al producto
			 *
			 *  estado = 9 (repetido)
			 *
			 */

                // Hora de inicio de Paso3
				//echo "Paso 3 Codigo Proveedor repetidos (inicio): ".date('Y-m-d H:i:s')."<br>";
				$this->_cantDupliCodigoP = $this->_cantCodigoPRep = 0;
				$this->_aProductos = $oProductoProvModelo->findAllCodigoPRepetidos();
				//var_dump($this->_aProductos);
				//echo "<br> Cantidad Leidos repetidos: ".$oProductoProvModelo->getCantidad()."<br>";
				if ($oProductoProvModelo->getCantidad()>0){ // hay productos_prov con código de barra repetidos
				   // cambiar estado a los productos_prov con codigo de barra repetidos
				   foreach ($this->_aProductos as $this->_producto){
				       $this->_cantCodigoPRep++;
				       $oProductoProvVO->setCodigoP($this->_producto['codigo_p']);
				       $this->_items = $oProductoProvModelo->findAllPorCodigoP($oProductoProvVO);
				       if ($oProductoProvModelo->getCantidad()>1){ // Encontro los repetidos
				           foreach ($this->_items as $this->_item){
				               $oProductoProvVO->setId($this->_item['id']);
				               $oProductoProvModelo->find($oProductoProvVO);
				               $oProductoProvVO->setEstado(9);
				               $oProductoProvVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
				               $this->_date = date('Y-m-d H:i:s');
				               $oProductoProvVO->setFechaAct($this->_date);
				               $oProductoProvModelo->update($oProductoProvVO);
				               $this->_cantDupliCodigoP++;
				          }
				       }
				   }
				   // Avisa que hay productos con codigo del proveedor repetidos
				   $oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
				   // ingresa los datos a representar en las alertas de la vista
				   $oDatoVista->setDato('{alertaAdvertencia}',  'Lista del proveedor con <b>CODIGOS de producto repetidos</b>, CONSULTE...
				                                               <p>Son '.$this->_cantCodigoPRep.' códigos de producto repetidos en '.$this->_cantDupliCodigoP.' productos.</p>');
				}
				unset($this->_aProductos);

			/**
			 *
			 * PASO 4:
			 * Armo los arrays necesarios para agregados y bajas de productos de los
			 * proveedores con las tablas: productos y productos_prov.
			 *
			 */

	            // Hora de inicio de Paso4
				//echo "Paso 4 Arma arrays (inicio): ".date('Y-m-d H:i:s')."<br>";

				// Armo array con productos_prov del proveedor
				$aProductosProvModi = array();
				$oProductoProvVO->setIdProveedor($this->_idProveedor);
				$this->_items = $oProductoProvModelo->findAllPorIdProveedorParaModi($oProductoProvVO);
				//echo "Cantidad productos_prov: ".$oProductoProvModelo->getCantidad()."<br>";
				foreach ($this->_items as $this->_item){
				    $aValores = array($this->_item['codigo_p'], $this->_item['codigo_b']);
				    $aProductosProv[$this->_item['id']] = implode(",", $aValores);
				    $aDatos = array($this->_item['codigo_p'], $this->_item['codigo_b'], $this->_item['precio'],
				                    $this->_item['codigo_iva'], $this->_item['tipo_descuento']);
				    $aProductosProvModi[] = implode(",", $aDatos);
				    unset($aDatos);
				    unset($aValores);
				}
				unset($this->_items);
				// Armo array con productos del proveedor
				$oProductoVO->setIdProveedor($this->_idProveedor);
				$this->_aProductos = $oProductoModelo->findAllPorIdProveedorParaModi($oProductoVO);
				//echo "Cantidad de productos: ". $oProductoModelo->getCantidad()."<br>";
				foreach ($this->_aProductos as $this->_item){
				    $aValores = array($this->_item['codigo_p'], $this->_item['codigo_b']);
				    $aProductos[$this->_item['id']] = implode(",", $aValores);
				    unset($aValores);
				}
				unset($this->_aProductos);

			/**
			 * PASO 5:
			 * Agrega productos nuevos del proveedor con las diferencias de comparar
			 * los arrays de productos y productos_prov evaluando el par (codigo_p y codigo_b).
			 */

				// Hora de inicio de Paso5
				//echo "Paso 5 - Agrega (inicio): ".date('Y-m-d H:i:s')."<br>";
				// Busca diferencias de codigo_p para agregar productos del proveedor
				$this->_aProductosAgrega = array_diff($aProductosProv, $aProductos);
				//Primer carga de productos del proveedor utiliza el $aProductosProv
				//$this->_aProductosAgrega = $aProductosProv;
				//var_dump($this->_aProductosAgrega);
				//echo "<br> Cantidad Productos Agrega: ".count($this->_aProductosAgrega)."<br>";
				$idArticulo = 0;
				foreach ($this->_aProductosAgrega as $id => $valor){
				    //echo "Agrega Id: ".$id." - Código: ".$codigo_p."<br>";
				    $oProductoProvVO->setId($id);
				    $oProductoProvModelo->find($oProductoProvVO);
				    if ($oProductoProvModelo->getCantidad()>0){
						$oProductoVO->setIdProveedor($this->_idProveedor);
						$oProductoVO->setCodigoB($oProductoProvVO->getCodigoB());
				        $oProductoVO->setCodigoP($oProductoProvVO->getCodigoP());
				        $oProductoModelo->findPorCodigoPCodigoBProveedor($oProductoVO); // busca producto por id y código del proveedor, y código de barra
				        if ($oProductoModelo->getCantidad() == 0){ // Agrega un producto
				            $oProductoVO->setIdProveedor($this->_idProveedor);
				            $oProductoVO->setCodigoB($oProductoProvVO->getCodigoB());
				            $oProductoVO->setCodigoP($oProductoProvVO->getCodigoP());
				            $oProductoVO->setNombre($oProductoProvVO->getNombre());
				            $oProductoVO->setPrecio($oProductoProvVO->getPrecio());
				            $oProductoVO->setCodigoIva($oProductoProvVO->getCodigoIva());
				            $oProductoVO->setTipoDescuento($oProductoProvVO->getTipoDescuento());
				            $oProductoVO->setEstado($oProductoProvVO->getEstado());
				            $oProductoVO->setIdArticulo($idArticulo);
				            $oProductoVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
				            $this->_date = date('Y-m-d H:i:s');
				            $oProductoVO->setFechaAct($this->_date);
				            $oProductoModelo->insert($oProductoVO);
				            $this->_cantAgregados++;
				        }
				    }
            	}
				unset($this->_aProductosAgrega);

			/**
			 * PASO 6:
			 * Baja productos eliminados del proveedor con las diferencias de comparar
			 * los arrays de productos y productos_prov evaluando el par (codigo_p y codigo_b).
			 */

				// Hora de inicio de Paso6
				//echo "Paso 6 - Baja (inicio): ".date('Y-m-d H:i:s')."<br>";
				// Busca diferencias de codigo_p para bajas de productos para el proveedor
				$this->_aProductosBaja = array_diff($aProductos, $aProductosProv);
				unset($aProductos);
				unset($aProductosProv);
				//var_dump($this->_aProductosBaja);
				//echo "<br> Cantidad Productos de Baja: ".count($this->_aProductosBaja)."<br>";
				foreach ($this->_aProductosBaja as $id => $valor){
				    //echo "Baja Id: ".$id." - Código: ".$codigo_p."<br>";
				    $oProductoVO->setId($id);
				    $oProductoModelo->find($oProductoVO);
				    $oProductoVO->setIdArticulo(0); // pongo 0 a idArticulo a prepo
				    $oProductoVO->setEstado(0); // producto con estado inactivo
				    $oProductoVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
				    $this->_date = date('Y-m-d H:i:s');
				    $oProductoVO->setFechaAct($this->_date);
				    $oProductoModelo->update($oProductoVO);
				    $this->_cantEliminados++;
				}
				unset($this->_aProductosBaja);

			/**
			 * PASO 7:
			 * Arma array de la tabla productos con las altas y bajas realizadas y el
			 * arrays necesario para las actualizaciones.
			 */

				// Hora de inicio de Paso7
				//echo "Paso 7 - Array productos (inicio): ".date('Y-m-d H:i:s')."<br>";
				// Armo array con productos del proveedor
				$aProductosModi = array();
				$oProductoVO->setIdProveedor($this->_idProveedor);
				$this->_aProductos = $oProductoModelo->findAllPorIdProveedorParaModi($oProductoVO);
				//echo "Cantidad de productos: ". $oProductoModelo->getCantidad()."<br>";
				foreach ($this->_aProductos as $this->_item){
				    $aDatos = array($this->_item['codigo_p'], $this->_item['codigo_b'], $this->_item['precio'],
				        $this->_item['codigo_iva'], $this->_item['tipo_descuento']);
				    $aProductosModi[] = implode(",", $aDatos);
				    unset($aDatos);
				}
				unset($this->_aProductos);

			/**
			 * PASO 8:
			 * Actualiza datos de los productos.
			 */

				// Hora de inicio de Paso8
				//echo "Paso8  - Actualiza (inicio): ".date('Y-m-d H:i:s')."<br>";
				// Busca diferencias en codigos_b, precio, codigo_iva, tipo_descuento para modificar el producto del proveedor
				$this->_aProductosModi = array_diff($aProductosProvModi, $aProductosModi);
				unset($aProductosModi);
				unset($aProductosProvModi);
				//var_dump($this->_aProductosModi);
				//echo "<br> Cantidad de productos a Modificar: ".count($this->_aProductosModi)."<br>";
				// Actualizo la tabla productos
				if (count($this->_aProductosModi)>0){
				    foreach ($this->_aProductosModi as $datos){
				        $aDatos = explode(',', $datos);
				        //echo "Actualizo Producto: ".$aDatos[0]." (".$aDatos[1].") $ ".$aDatos[2]." Iva: ".$aDatos[3]." Desc: ".$aDatos[4]." <br>";
				        $oProductoVO->setIdProveedor($this->_idProveedor);
				        $oProductoVO->setCodigoB($aDatos[1]);
				        $oProductoVO->setCodigoP($aDatos[0]);
				        $oProductoModelo->findPorCodigoPCodigoBProveedor($oProductoVO);
				        if ($oProductoModelo->getCantidad()>0){ // Encontro el producto para actualizar
				            $oProductoVO->setPrecio($aDatos[2]);
				            $oProductoVO->setCodigoIva($aDatos[3]);
				            $oProductoVO->setTipoDescuento($aDatos[4]);
				            $oProductoVO->setEstado(1); // Pongo a prepo activo
				            $oProductoVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
				            $this->_date = date('Y-m-d H:i:s');
				            $oProductoVO->setFechaAct($this->_date);
				            $oProductoModelo->update($oProductoVO);
				            $this->_cantActualizados++;
				        }
				        unset($aDatos);
				    }
				}
				unset($this->_aProductosModi);

			/**
		     * PASO 9
			 * Actualiza los datos de contadores para la vista
			 *
			 */

				$oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
				// ingresa los datos a representar en las alertas de la vista
				$oDatoVista->setDato('{alertaSuceso}',  'Se ejecutó la acción con <b>EXITO !!!</b>.');
				$oDatoVista->setDato('{cantAgregados}', $this->_cantAgregados);
				$oDatoVista->setDato('{cantActualizados}', $this->_cantActualizados);
				$oDatoVista->setDato('{cantEliminados}', $this->_cantEliminados);
				break;
			# ----> acción Confirmar Actualizar
			case 'ConfirmarAct':

				break;
			# ----> acción Listar
			case 'Listar':

				break;
			# ----> acción Confirmar Listar
			case "ConfirmarL":

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
