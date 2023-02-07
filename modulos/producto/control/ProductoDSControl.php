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
 * @version    4.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      File available since Release 4.0
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
 * @version    4.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 4.0
 */
/**
 * Modificación para tomar más de un código de barra por producto,
 * los carga como otros productos en nuestra DB
 * @author     Alvaro Guiffrey
 * @date       17/10/2017
 *
 */
 /**
  * Modificación para agregar codigo iva y tipo descuentos del proveedor,
  * ver si los dos codigos de productos de la lista son diferentes, poner
  * el segundo como codigo_p, ver las diferentes opciones de precio y
  * mejorar el código utilizando arrays y evitar lecturas a la DB.
  * @author     Alvaro Guiffrey
  * @date       20/03/2019
  *
  */
class ProductoDSControl
{
	#Propiedades
	private $_html;
	private $_accion;
	private $_items;
	private $_item;
	private $_aProductos;
	private $_productos;
	private $_producto;
	private $_cantidad;
	private $_cantAgregados;
	private $_cantActualizados;
	private $_cantEliminados;
	private $_cantDuplicados;
	private $_cantDupliCodigoP;
	private $_cantCodigoBRep;
	private $_cantCodigoPRep;
	private $_cantUpdate;
	private $_renglonDesde;
	private $_id;
	private $_idProveedor;
	private $_codigoP;
	private $_codigoP1;
	private $_razonSocial;
	private $_estado;
	private $_precio;
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
		$archivoTXT = $_SERVER['DOCUMENT_ROOT'].$_SESSION['dir']."/archivos/delsud.txt";
		//echo $archivoTXT."<br>";
		$contenido = file ( $archivoTXT );
		$numero_registros = sizeof( $contenido );

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
				// arma la tabla de datos a representar
				/*
				* DROGUERIA DEL SUD SA
				* PRECAUCION: Tiene "codigo de proveedor" en 0
				* Tipo Registro: "T" final de tabla
				*/
				// carga los datos del proveedor
				$this->_idProveedor = 2;
				$oProveedorVO = new ProveedorVO();
				$oProveedorModelo = new ProveedorModelo();
				$oProveedorVO->setId($this->_idProveedor);
				$oProveedorModelo->find($oProveedorVO);
				$oDatoVista->setDato('{idProveedor}', $oProveedorVO->getId());
				$oDatoVista->setDato('{razonSocial}', $oProveedorVO->getRazonSocial());
				$oDatoVista->setDato('{inicial}', $oProveedorVO->getInicial());
				// cuenta la cantidad de productos activos del proveedor
				$this->_items = $oProductoModelo->countPorProveedor($oProveedorVO->getId());
				$this->_cantidad = $oProductoModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				$oDatoVista->setDato('{cantProductos}', $this->_cantidad);
				$oDatoVista->setDato('{cantLista}', sizeof( $contenido ));
				// pone en 0 los contadores
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
				$oDatoVista->setDato('{cantLista}', sizeof( $contenido ));
				// pone en 0 los totales
				$this->_cantAgregados = $this->_cantActualizados = $this->_cantEliminados = 0;
				$cont = $contP = $conB = $conE = 0;

			/**
			 * PASO 1
			 * Borra la tabla productos_prov para limpìar datos del proceso anterior
			 * y arma tabla nueva para el proveedor.
			 *
			 * Archivo descargado desde la web con los siguientes parámetros:
			 * txt (posición fija de los datos, ver en página web)
			 * importe neto y PVP (sin separación de decimales: 11,2)
			 */

				// Hora de inicio de Paso1
				//echo "Paso 1 Carga productos_prov (inicio): ".date('Y-m-d H:i:s')."<br>";
				// elimina la tabla productos_prov
				$oProductoProvModelo->truncate();
				// Pone en CERO control registros
				$registroD = $registroNoD = $registroNoMedicinal = $registroMedicinal = $registroEspecial = 0;
				$registroOtro = $registroCodCero = $registroInsert = $registroUpdate = 0;
				$contNMPVP = 0;
				// Arma la nueva tabla productos_prov para el proveedor
				for( $i = 0; $i < sizeof( $contenido ); $i++) {
					$linea = trim( $contenido[ $i ] );

					$tipoReg = substr($linea, 0, 1);
					// --- TIPO REGISTRO "D" ---
					if ($tipoReg == "D"){ // Si tipo de registro es igual a "D" arma tabla
					    $registroD++;
					    // ------------------------------------------------------
					    // Windows lee TXT sin problemas en caracteres especiales
					    // Revisar todo para LINUX
					    // ------------------------------------------------------
					    // Codigo de Producto del Proveedor (Código MSP)
					    $codigoP = substr($linea, 1, 18);
					    $codigoP = trim($codigoP);
						// Codigo de Producto1 del Proveedor (Código Material)
						$codigoP1 = substr($linea, 250, 18);
					    $codigoP1 = trim($codigoP1);
						// Tipo de producto (Sección) 1 Medicinal y 2 No Medicinal
					    $tipo = substr($linea, 60, 1); // Extraigo solo el último dígito.
					    if ($codigoP != '000000000000000000'){ // si codigo del proveedor > 0 continua para insertar en producto_prov
					        // Codigos de Productos - Sustituye los 0 por + adelante
					        $this->_codigoP = "+".ltrim($codigoP, 0);
							$this->_codigoP1 = "+".ltrim($codigoP1, 0);
					        // Nombre para guardar
					        $nombre = substr($linea, 19, 40);
					        $nombre = trim($nombre);
					        $nombreUTF8 = utf8_decode($nombre);
					        // DS tiene varios códigos de barras por producto
					        $aCodigoB = array();
					        // Primer código de barra
					        $codigoB = 0;
					        $codigoB = substr($linea, 85, 18);
					        if (ctype_space($codigoB) == false){
					            $aCodigoB[] = $codigoB;
					        }
					        // Segundo código de barra
					        $codigoB = 0;
					        $codigoB = substr($linea, 105, 18);
					        if (ctype_space($codigoB) == false){
					            $aCodigoB[] = $codigoB;
					        }
					        // Tercer código de barra
					        $codigoB = 0;
					        $codigoB = substr($linea, 125, 18);
					        if (ctype_space($codigoB) == false){
					            $aCodigoB[] = $codigoB;
					        }
					        // Cuarto código de barra
					        $codigoB = 0;
					        $codigoB = substr($linea, 145, 18);
					        if (ctype_space($codigoB) == false){
					            $aCodigoB[] = $codigoB;
					        }
					        // Precio de Venta al Cliente del producto
					        $precioVC = substr($linea, 163, 13);
					        $precioVC = trim($precioVC);
					        $precioVC = substr_replace($precioVC,'.',-2,0);
					        //number_format($precioVC, 2, ".", "");

					        // Precio de Venta al Público del producto
					        $precioVP = substr($linea, 176, 13);
					        $precioVP = trim($precioVP);
					        $precioVP = substr_replace($precioVP,'.',-2,0);
					        //number_format($precioVP, 2, ".", "");

					        // Código de IVA del producto
					        $ivaCod = substr($linea, 268, 1);
					        if ($ivaCod==1){ // Suponemos iva 21%
					            $codigoIva = 5;
							} elseif ($ivaCod==2) { // Suponemos iva 21% para todos
								$codigoIva = 5;
							} else { // Exentos
					            $codigoIva = 2;
					        }

					        // Estado del producto
					        $estadoPro = substr($linea, 82, 1);
					        // --------------------------------
					        // Fin para Windows sin problemas
					        // --------------------------------
					        $estado = 1;
					        if ($estadoPro == 'B'){
					            $estado = 0;
					        }
					        if ($estadoPro == 'S'){
					            $estado = 2;
					        }

					        /* --- VERIFICAR PARA LINUX (No modifique 12/12/2019)
					         // Para LINUX con problemas de caracteres especiales
					         // Nombre largo para buscar caracteres especiales
					         $nombreL = substr($linea, 19, 50);
					         $nombreL = trim($nombreL);
					         $nombreLUTF8 = utf8_decode($nombreL);

					         if (stristr($nombreLUTF8, '?')==TRUE){ // nombre con caracteres especiales
					         $cantidad = substr_count($nombreLUTF8, '?');
					         $nombre = $nombreUTF8;
					         //$nombre = str_replace('?', 'Ñ', $nombreUTF8);
					         switch ($cantidad){
					         case 1:
					         $tipo = substr($linea, 62, 1);
					         $codigoB = substr($linea, 87, 18);
					         $precio = substr($linea, 165, 13);
					         $precio = trim($precio);
					         $precio = substr_replace($precio,'.',-2,0);
					         $estadoPro = substr($linea, 84, 1);
					         break;
					         case 2:
					         $tipo = substr($linea, 64, 1);
					         $codigoB = substr($linea, 89, 18);
					         $precio = substr($linea, 167, 13);
					         $precio = trim($precio);
					         $precio = substr_replace($precio,'.',-2,0);
					         $estadoPro = substr($linea, 86, 1);
					         break;
					         case 3:
					         $tipo = substr($linea, 66, 1);
					         $codigoB = substr($linea, 91, 18);
					         $precio = substr($linea, 169, 13);
					         $precio = trim($precio);
					         $precio = substr_replace($precio,'.',-2,0);
					         $estadoPro = substr($linea, 88, 1);
					         break;
					         case 4:
					         $tipo = substr($linea, 68, 1);
					         $codigoB = substr($linea, 93, 18);
					         $precio = substr($linea, 171, 13);
					         $precio = trim($precio);
					         $precio = substr_replace($precio,'.',-2,0);
					         $estadoPro = substr($linea, 90, 1);
					         break;
					         case 5:
					         $tipo = substr($linea, 70, 1);
					         $codigoB = substr($linea, 95, 18);
					         $precio = substr($linea, 173, 13);
					         $precio = trim($precio);
					         $precio = substr_replace($precio,'.',-2,0);
					         $estadoPro = substr($linea, 92, 1);
					         break;
					         }
					         } else { // nombre sin caracteres especiales
					         $tipo = substr($linea, 60, 1);
					         $codigoB = substr($linea, 85, 18);
					         $precio = substr($linea, 163, 13);
					         $precio = trim($precio);
					         $precio = substr_replace($precio,'.',-2,0);
					         $estadoPro = substr($linea, 82, 1);
					         }
					         // Fin para Linux con problemas de caracteres especiales
					         */

					        // --- CALCULA EL PRECIO DE COSTO ---
					        $precio = 0.00;
					        // Producto es "No Medicinal"
					        if ($tipo==2){
								// Producto con precio venta cliente sin precio venta al público
					            if ($precioVP==0){
					                $precio = $precioVC; // Precio de venta cliente es el precio
					                //number_format($precio, 2);
					                $tipoDescuento = 2; // Descuento de Drog. para "No Medicamentos"
					                // habilitar para pruebas
					                //echo "NO Medicinal sin PVP --> ".$codigoP." - ".$nombre." (".$tipo.") ".$aCodigoB[0]." - $ ".$precio." Desc:".$tipoDescuento." (Iva: ".$ivaCod.")<br>";
					            } else {
					                //$contNMPVP++;
					                $precioNeto = $precioVP;
					                $tipoDescuento = 3; // Sin descuento
					                if ($ivaCod>0) $precioNeto = round($precioVP / 1.21, 2);
					                $dif = round($precioVC / $precioNeto, 3);
					                if ($dif < 0.798){ // No Medicinal con diferencia > a 20% entre PVC y PVP-IVA
					                    $precio = $precioVC;
										//echo "NO Medicinal **CON** PVP > 20% --> ".$codigoP." - ".$nombre." (".$aCodigoB[0].") - PVP $ ".$precioVP." PVC $ ".$precioVC." (Iva: ".$ivaCod.") Dif: ".$dif."<br>";
					                } else { // No Medicinal con diferencia = a 20% entre PVC y PVP-IVA
					                    $contNMPVP++;
										// Para estos casos hay muchos porcentajes de descuentos diferentes
					                    $precio = round($precioNeto * 0.69, 2); // Hago descuento genérico 31% (20% + 14% sobre neto)
					                    //echo "NO Medicinal **CON** PVP -20% --> ".$codigoP." - ".$nombre." (".$aCodigoB[0].") - PVP $ ".$precioVP." PVC $ ".$precioVC." (Iva: ".$ivaCod.") Dif: ".$dif."<br>";
					                }

					                // habilitar para pruebas
					                //echo "NO Medicinal **CON** PVP --> ".$codigoP." - ".$nombre." (".$tipo.") ".$aCodigoB[0]." - $ ".$precio." Desc:".$tipoDescuento." (Iva: ".$ivaCod.") Dif: ".$dif."<br>";
					            }
					            $registroNoMedicinal++;
					        } else { // fin tipo no medicinal
								// Tipo Medicinal
					            if ($tipo==1){
					                $precio = $precioVP * 1;
					                $tipoDescuento = 1;
					                $registroMedicinal++;
					                // habilitar para pruebas
					                //echo "Medicinal (PVP) --> ".$codigoP." - ".$nombre." (".$tipo.") ".$aCodigoB[0]." - $ ".$precio." Desc:".$tipoDescuento." (Iva: ".$ivaCod.")<br>";
					            } else {
					                if ($tipo=='E'){
					                    $precio = $precioVC * 1;
					                    $tipoDescuento = 3;
					                    $registroEspecial++;
					                    // habilitar para pruebas
					                    //echo "Servicio --> ".$codigoP." - ".$nombre." (".$tipo.") ".$aCodigoB[0]." - $ ".$precio." Desc:".$tipoDescuento." (Iva: ".$ivaCod.")<br>";
					                } else {
					                    $precio = $precioVC * 1;
					                    $tipoDescuento = 3;
					                    $registroOtro++;
					                    // habilitar para pruebas
					                    //echo "Otros --> ".$codigoP." - ".$nombre." (".$tipo.") ".$aCodigoB[0]." - $ ".$precio." Desc:".$tipoDescuento." (Iva: ".$ivaCod.")<br>";
					                }


					            } // Fin tipo "Medicinal"
					        } // Fin calcula el PRECIO DE COSTO

                            // Cargo los datos del producto en ProductoProvVO
			               	$idArticulo = 0;
			            	$oProductoProvVO->setIdProveedor($this->_idProveedor);
							// *** Agrega el código del proveedor ***
							// Si son distintos agrega con codigoP1
							if ($this->_codigoP == $this->_codigoP1) {
								$oProductoProvVO->setCodigoP($this->_codigoP);
							} else {
								$oProductoProvVO->setCodigoP($this->_codigoP1);
								//echo "Codigo P diferente: ".$this->_codigoP." / ".$this->_codigoP1."<br>";
							}
				    		$oProductoProvVO->setNombre($nombre);
				    		$oProductoProvVO->setPrecio($precio);
				    		$oProductoProvVO->setCodigoIva($codigoIva);
				    		$oProductoProvVO->setTipoDescuento($tipoDescuento);
				    		$oProductoProvVO->setEstado($estado);
					      $oProductoProvVO->setIdArticulo($idArticulo);
				    		$oProductoProvVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
				    		// Inserta en la tabla producto_prov tantas veces como codigo de barras diferente tenga
				    		foreach ($aCodigoB as $codigoB){
				    		    $oProductoProvVO->setCodigoB($codigoB);
					       	    $this->_date = date('Y-m-d H:i:s');
					       	    $oProductoProvVO->setFechaAct($this->_date);
					       	    // INSERT del Registro
                                $oProductoProvModelo->insert($oProductoProvVO);
					       	    $registroInsert++;
					       	 }

					    }else{
					        $registroCodCero++;
					    }

					} else { // fin tipo de registro "D", si no es "D" suma al contador
						$registroNoD++;
					}

					//echo "Leí registro: ".$i."<br>";
					unset($aCodigoB); // borra el array de Códigos de Barra que armamos para el producto
				} // finaliza carga nuevos datos tabla productos_prov para el proveedor

				// Muestras los totalizadores para realizar pruebas
				/*
				echo "Registros D: ".$registroD."<br>";
				echo "-------------------------------------<br>";
				echo "Registros No Medicinal: ".$registroNoMedicinal."<br>";
				echo "No Medicinal con PVP son -> ".$contNMPVP."<br>";
				echo "Registros Insert: ".$registroInsert."<br>";
				echo "Registros Cod. CERO: ".$registroCodCero."<br>";
				echo "--------------------------------------<br>";
				echo "Registros Medicinales: ".$registroMedicinal."<br>";
				echo "Registro Especial: ".$registroEspecial."<br>";
				echo "Registros Otros: ".$registroOtro."<br>";
				echo "--------------------------------------<br>";
				echo "Registro No D: ".$registroNoD."<br>";
				*/

			/**
			 *
			 * PASO 2:
			 * Verifico si existen codigos de barra repetidos y cambio de
			 * estado al producto_prov
			 *
			 *  estado = 9
			 *
			 */

			 	// Hora de inicio de Paso2
			 	//echo "Paso 2 Pares de Codigos repetidos (inicio): ".date('Y-m-d H:i:s')."<br>";
			 	$this->_cantDuplicados = $this->_cantCodigoBRep = 0;
			 	$this->_aProductos = $oProductoProvModelo->findAllCodigoBCodigoPRepetidos();
			 	//var_dump($this->_aProductos);
			 	//echo "<br> Cantidad Leidos repetidos: ".$oProductoProvModelo->getCantidad()."<br>";
				if ($oProductoProvModelo->getCantidad()>0){  // hay productos_prov con código de barra y de proveedor repetidos
				    // cambiar estado a los productos_prov con codigo de barra y de proveedor repetidos
				    foreach ($this->_aProductos as $this->_producto){
				        //echo "Producto: - Cod. Bar. ".$this->_producto['codigo_b']."<br>";
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
				} // Fin codigos de barras repetidos
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

	            // No se utiliza este paso ya que hay más de un codigoP igual, el proveedor utiliza
	            // hasta cuatro codigoB para cada producto.

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
				$aProductos = [];
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
