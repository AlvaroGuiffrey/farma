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
 * actualización de los precios con las listas de los proveedores
 * de referencia, según la categoría asignada al usuario.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class ArticuloControlActualiza
{
	#Propiedades
	private $_html;
	private $_accion;
	private $_items;
	private $_item;
	private $_id;
	private $_estado;
	private $_opcionProv;
	private $_orden;
	private $_date;
	private $_margen;
	private $_costo;
	private $_precio;
	private $_porcentaje;
	private $_diferenciaPrecio;
	private $_porcentajeDiferencia;
	private $_modifico;
	private $_aAcciones = array();
	private $_aEventos = array();
	private $_aArticulosCondi = array();
	private $_aCondicionIva = array();
	private $_aProveedores = array();
	private $_idProveedor;
	private $_aProveedoresLista = array();
	private $_aProveedoresRef = array();
	private $_aProductos = array();
	private $_producto;
	private $_aRangos = array();
	private $_count;
	private $_con;
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
		Clase::define('ArticuloModelo');
		Clase::define('ArticuloCondiModelo');
		Clase::define('ProductoModelo');
		Clase::define('ProveedorModelo');
		Clase::define('AfipCondicionIvaModelo');
		Clase::define('DataBasePlex');

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
		if (isset($_POST['bt_actualizar'])) $this->_accion = "Actualizar";
		if (isset($_POST['bt_actualizar_conf'])) $this->_accion = "ConfirmarAct";
		if (isset($_POST['bt_listar'])) $this->_accion = "Listar";
			
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
				"actualizar" => "/includes/vista/botonActualizar.html"
		);
		$oCargarVista->setCarga('aAcciones', $this->_aAcciones);
		// Ingresa los datos a representar en el html de la vista
		$oDatoVista = new DatoVista();
		$oDatoVista->setDato('{tituloPagina}', 'Artículos');
		$oDatoVista->setDato('{usuario}', $oLoginVO->getAlias());
		$oDatoVista->setDato('{tituloBadge}', 'Artículos ');
		// Alertas

		// Instancia las Clases necesarias
		$oArticuloVO = new ArticuloVO();
		$oArticuloModelo = new ArticuloModelo();
		$oProductoVO = new ProductoVO();
		$oProductoModelo = new ProductoModelo();
		$oArticuloCondiVO = new ArticuloCondiVO();
		$oArticuloCondiModelo = new ArticuloCondiModelo();
		
		// Carga el contenido html y datos según la acción
		// Selector de acciones
		switch ($this->_accion){
			# ----> acción Iniciar
			case 'Iniciar':
				// carga el contenido html
				$oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
				// ingresa los datos a representar en las alertas de la vista
				$oDatoVista->setDato('{alertaInfo}',  'Seleccione alguna acción con los botones.');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Artículos - Actualización de Precios - Selección de acciones');
				$oDatoVista->setDato('{informacion}', '<p>Puede seleccionar acciones para los artículos,</p><p>ver botones.');
				// arma la tabla de datos a representar
				$oArticuloModelo->count();
				$this->_cantidad = $oArticuloModelo->getCantidad();
				// arma la tabla de datos a representar
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				break;
			# ----> acción Actualizar
			case 'Actualizar':
				// carga el contenido html
				$oCargarVista->setCarga('datos', '/modulos/articulo/vista/actualizarPrecios.html');
				$oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
				$oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
				
				// ingresa los datos a representar en las alertas de la vista
				$oDatoVista->setDato('{alertaPeligro}',  '<b>Actualiza los precios de los artículos</b>, confirme la acción.');
				$oDatoVista->setDato('{alertaInfo}',  'Puede seleccionar rangos de aumentos a aplicar.');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Artículos - Actualización de Precios');
				$oDatoVista->setDato('{informacion}', '<p>Confirme la acción seleccionada para los artículos,</p><p>ver botones.');
				// carga los eventos (botones)
				$this->_aEventos = [
				"actualizarConfirmar" => "/includes/vista/botonActualizarConf.html"
				];
				$oCargarVista->setCarga('aEventos', $this->_aEventos);
				
				// arma la tabla de datos a representar
				$oArticuloModelo->count();
				$this->_cantidad = $oArticuloModelo->getCantidad();
				
				/**
				 * Cuenta los artículos actualizables según los siguientes datos:
				 *
				 * codigo > 9999900000 ($oArticuloVO->setCodigo())
				 * id_proveedor > 1 ($oArticuloVO->setIdProveedor())
				 * codigo_b > 0 (esta la condición en la consulta)
				 * estado = 1 ($oArticuloVO->setEstado())
				 */
				$oArticuloVO->setCodigo(9999900000); // mayor que
				$oArticuloVO->setIdProveedor(1); // mayor que: 1 - SIN IDENTIFICAR
				$oArticuloVO->setEstado(1); // igual que
				// codigo_b > 0
				
				$oArticuloModelo->countActualizables($oArticuloVO);
				$oDatoVista->setDato('{cantActualizables}',  $oArticuloModelo->getCantidad());
				$oDatoVista->setDato('{cantActualizados}',  0);
				$oDatoVista->setDato('{cantNoActualiza}',  0);
				$oDatoVista->setDato('{cantAumentos}',  0);
				$oDatoVista->setDato('{cantBajas}',  0);
				$oDatoVista->setDato('{cantNoLeidos}',  0);
				$oDatoVista->setDato('{cantPromo}',  0);
				$oDatoVista->setDato('{cantPrecioUnificado}',  0);
				$oDatoVista->setDato('{cantImprimeRotulo}',  0);
				$oDatoVista->setDato('{cantImprimeRotuloPromo}',  0);
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				break;				
			# ----> acción Confirmar Actualizar
			case 'ConfirmarAct':
			    
			    /**
			     * Actualiza los precios de acuerdo a los rangos de % de 
			     * aumentos seleccionados.
			     * Nota: Actualiza todas las bajas de precios.
			     * Fecha: 14/11/2020
			     */
			    
			    // Recibe los datos por POST
			    $this->_aRangos = $_POST['rangos']; // rangos de % de aumentos
			    $topeAgrega = $_POST['cantActualizables']; // tope de artículos a actualizar
				// carga el contenido html
			    $oCargarVista->setCarga('datos', '/modulos/articulo/vista/actualizarPrecios.html');
				$oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
				// ingresa los datos a representar en las alertas de la vista
				$oDatoVista->setDato('{alertaSuceso}',  'Finalizó la acción solicitada con <b>EXITO !!!</b>.');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Artículos - Actualización de Precios');
				$oDatoVista->setDato('{informacion}', '<p>Finalizó la acción solicitada para los artículos,</p><p>ver botones u opciones del menú.');
				// arma la tabla de datos a representar
				$oDatoVista->setDato('{cantActualizables}',  $_POST['cantActualizables']);
				$oDatoVista->setDato('{cantidad}', $_POST['cantidad']);
				// pone en cero los totalizadores y auxiliares varios
				$actualiza = $noActualiza = $promoNoActualiza = $PUNoActualiza = $aumentos = $baja = 0;
				$diferenciaMenor = $imprimeRotulo = $imprimeRotuloPromo = 0;
				$cont = $agrega = $condi = $masTreinta = $masVeinte = $masDiez = $masCinco = $menosCinco = 0;
				// carga array condición IVA
				$oAfipCondicionIvaVO = new AfipCondicionIvaVO();
				$oAfipCondicionIvaModelo = new AfipCondicionIvaModelo();
				$this->_items = $oAfipCondicionIvaModelo->findAll();
				foreach ($this->_items as $this->_item){
					$this->_aCondicionIva[$this->_item['codigo']] = $this->_item['alicuota'];
				}
				// carga array proveedores
				$oProveedorVO = new ProveedorVO();
				$oProveedorModelo = new ProveedorModelo();
				$this->_items = $oProveedorModelo->findAllProveedoresRef();
				foreach ($this->_items as $this->_item){
					$this->_aProveedores[$this->_item['id']] = $this->_item['inicial'];
					$this->_aProveedoresLista[$this->_item['id']] = $this->_item['lista'];
				}

				/**
				 * Carga los artículos actualizables según los siguientes datos:
				 * 
				 * codigo > 9999900000 ($oArticuloVO->setCodigo())
				 * id_proveedor > 1 ($oArticuloVO->setIdProveedor())
				 * codigo_b > 0 (esta la condición en la consulta)
				 * estado = 1 ($oArticuloVO->setEstado())
				   */
				$oArticuloVO->setCodigo(9999900000); // may0r que
				$oArticuloVO->setIdProveedor(1); // mayor que: 1 - SIN IDENTIFICAR
				$oArticuloVO->setEstado(1); // igual que
				// codigo_b > 0 // condición esta en la consulta
				$this->_items = $oArticuloModelo->findAllActualizables($oArticuloVO);
				
				/*
				 * Actualizo precios en PLEX y rótulos para la impresión
				 * 
				 * 1 - Cambiar el codigo para el rótulo de precio del artículo
				 * 2 - Cambiar el código para el rótulo de of/promo del artículo
				 * 3 - Agregar en PLEX en producto_costo el nuevo precio 
				 */
				
				
				echo "**************************************<br>";
				echo "   <b>LISTADO DE ACTUALIZACIONES</b><br>";
				echo "**************************************<br>";
				echo "Articulos actualizables: ".$topeAgrega."<br>";
								
				// verifica si hay artículos actualizables 
				if ($oArticuloModelo->getCantidad()>0){ // Si hay artículos actualizables continúa
					$this->_con = DataBasePlex::getInstance(); // conecto a DB PLEX
					foreach ($this->_items as $this->_item){ // extraigo los datos de artículos x artículo
						$cont++;
						// Muestra datos del registro de artículo
						echo $cont." #".$this->_item['id']." [".$this->_item['codigo_b']."] ".$this->_item['nombre']." - $ ".$this->_item['costo']." (".$this->_item['margen']."%)<br>";
						echo ". . . . Precio $".$this->_item['precio']." - Prov: ".$this->_item['id_proveedor']." (".$this->_aProveedores[$this->_item['id_proveedor']].") ";
						
						/**
						 * Si es PROMO no modifica precio
						 */	
						$pos = strpos($this->_item['nombre'], 'PR.');
						if ($pos !== false) {
						    echo "<b> <<< PROMO >>>> NO MODIFICA </b><br>";
						    $promoNoActualiza++;
						} else {	// Artículo sin PROMO sigue a ver si actualiza precio

						    /**
						     * Si es PRECIO UNIFICADO no modifica precio
						     */
						    $pos = strpos($this->_item['presentacion'], '(PU.');
						    if ($pos !== false) {
						        echo "<b> <<< PRECIO UNIFICADO >>>> NO MODIFICA </b><br>";
						        $PUNoActualiza++;
						    } else {	// Artículo sin PRECIO UNIFICADO sigue a ver si actualiza precio
							    /**
							     * Calcula el precio con lista del proveedor de referencia
							     */

							     $oProductoVO->setIdArticulo($this->_item['id']);
							     $oProductoVO->setCodigoB(trim($this->_item['codigo_b']));
							     $oProductoVO->setIdProveedor($this->_item['id_proveedor']);
							     $oProductoModelo->findPorIdArticuloCodigoBProveedor($oProductoVO); // consulta producto ACTIVO
							     if ($oProductoModelo->getCantidad()>0){ // tiene producto activo en lista del proveedor de referencia
								    echo " -> lista $ ".$oProductoVO->getPrecio()." (".$this->_aProveedoresLista[$this->_item['id_proveedor']].") ";
								    // verifico que margen no sea CERO
								    $this->_margen = $this->_item['margen'];
								    if ($this->_margen == 0) $this->_margen = 37;  // margen = 0 pongo treinta y siete de prepo

								    // calculo precio con el costo del producto
								    // costo para guardar es "NETO" (sin  IVA)
								    if ($this->_aProveedoresLista[$this->_item['id_proveedor']] == 'F'){
									   // Lista con precios finales
									   $this->_costo = round($oProductoVO->getPrecio() / (1 + ($this->_aCondicionIva[$this->_item['codigo_iva']] / 100)), 2); 
									   $this->_precio = round(($oProductoVO->getPrecio() * (1 + ($this->_margen / 100))) , 2);
								    } else {
									   if ($this->_aProveedoresLista[$this->_item['id_proveedor']] == 'N'){
										  // Lista con precios netos
										  $this->_costo = round($oProductoVO->getPrecio(), 2);
										  $this->_precio = round((($oProductoVO->getPrecio() * (1 + ($this->_aCondicionIva[$this->_item['codigo_iva']] / 100))) * (1 + ($this->_margen / 100))) , 2);
									   } else {
										  // Proveedor sin tipo de lista 
										  $this->_costo = 0;
										  $this->_precio = 0;
										  echo " ** FALTA TIPO DE LISTA ** ";
									   }
								    }
								    								
								    /**
								    * PRECIOS - VEO SI ACTUALIZO
								    * 1 - Precios iguales sigo sin actualizar
								    * 2 - Precios diferentes y la diferencia es > o = al 2% actualizo registros
								    * 3 - Precios diferentes con diferencia menor al 2% sigo sin actualizar
								    */
								    $this->_diferenciaPrecio = $this->_porcentajeDiferencia = 0;
								    $this->_modifico = 'N';
								    
								    if ($this->_item['precio'] == $this->_precio){ // Precios iguales no actualiza
									   $noActualiza++;
									   echo " Precio Nuevo $ ".$this->_precio." *** PRECIO IGUAL NO ACTUALIZO ***<br>";
								    } else { // Precios diferentes hago comparaciones y veo si actualizo
									   /**
									    * Con una diferencia igual o menor al 2% no modifico los precios de los artículos
									    */
								        echo " -> lista $ ".$oProductoVO->getPrecio()." (".$this->_aProveedoresLista[$this->_item['id_proveedor']].") ";
								        echo " Precio Nuevo $ ".$this->_precio." - ";
									   // Armo totales para mostrar
									   if ($this->_precio > $this->_item['precio']){ // PRECIO CON AUMENTO
										  // calculo diferencia de precio
										  $this->_diferenciaPrecio = round(($this->_precio - $this->_item['precio']), 2);
										  // calculo 2% del precio del artículo
										  $this->_porcentajeDiferencia = round(($this->_item['precio']*2/100), 2);
										  // Si diferencia es menor o igual a 2% no actualizo
										  if ($this->_porcentajeDiferencia > $this->_diferenciaPrecio){
											 echo " NO ACTUALIZO - DIF $".$this->_diferenciaPrecio." < 2% <br>";
											 $diferenciaMenor++;
										  } else {										
											 // Actualiza - Nuevo precio mayor que precio de artículo
											 $this->_modifico = 'S';
											 echo " -ACTUALIZO- ";
											 $aumentos++;
											 // Calcula porcentaje de aumento
											 $this->_porcentaje = round((($this->_precio - $this->_item['precio']) / $this->_item['precio'] * 100), 2);
											 echo $this->_porcentaje."% ";
											 if ($this->_porcentaje > 30){
											     echo " -> MAS DE 30 % <br>";
												 $masTreinta++;
											 } else {
											     if ($this->_porcentaje > 20){
													echo " -> MAS DE 20 % <br>";
													$masVeinte++;
												} else {
													if ($this->_porcentaje > 10){
														echo " -> MAS DE 10 % <br>";
														$masDiez++;
													}else{
														if ($this->_porcentaje > 5){
															echo " -> MAS DE 5 % <br>";
															$masCinco++;
														}else{
											// ---> Bloqueo de manera manual el aumento
														    /**
														     * Bloqueo la actualización de precios con aumento menor
														     * o igual a 5%.
														     *
														     * @var ArticuloControlActualiza $_modifico
														     */
														    $this->_modifico = 'N';
														    echo " << BLOQUEADO >> ";
											// ---> Fin bloqueo manual del aumento			    
															echo " -> MENOS O IGUAL A 5 % <br>";
															$menosCinco++;
															
														}
													}
												}
											}
										}
									} else { // PRECIO CON BAJA
										// Actualiza - Nuevo precio menor que precio de artículo
										if ($this->_precio < $this->_item['precio']){
											// calculo diferencia de precio
											$this->_diferenciaPrecio = round(($this->_item['precio'] - $this->_precio), 2);
											// calculo 2% del precio del artículo
											$this->_porcentajeDiferencia = round(($this->_item['precio']*2/100), 2);
											// Si diferencia es menor o igual a 2% no actualizo
											if ($this->_porcentajeDiferencia > $this->_diferenciaPrecio){
												echo " NO ACTUALIZO - DIF $".$this->_diferenciaPrecio." < 2% <br>";
												$diferenciaMenor++;
											} else {
												$this->_modifico = 'S';
												echo " ACTUALIZO -BAJA PRECIO- <br>";
												$baja++;
											}	
										}
									}  
									
									// Precios diferentes, con variación > 2%, agrego registros
									if ($this->_modifico == 'S'){
										if ($agrega < $topeAgrega){
											// Agrego registro a BD PLEX con precio 
											$this->agregarProductoCosto($this->_item['codigo'], $this->_precio);
											// Modifico articulo con los nuevos datos
											$oArticuloVO->setId($this->_item['id']);
											$oArticuloModelo->find($oArticuloVO);
											$oArticuloVO->setCosto($this->_costo);
											$oArticuloVO->setPrecio($this->_precio);
											$oArticuloVO->setFechaPrecio(date('Y-m-d'));
											// cambia el rótulo para imprimir
											// pone 3 (imprime el rótulo)
											if ($oArticuloVO->getRotulo() > 0){
												$oArticuloVO->setRotulo(3);
												$imprimeRotulo++;
											}
											$oArticuloVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
											$this->_date = date('Y-m-d H:i:s');
											$oArticuloVO->setFechaAct($this->_date);
											$oArticuloModelo->update($oArticuloVO);
											$actualiza++;
											$agrega++;
											// Revisa si tiene condiciones venta
											$oArticuloCondiVO->setIdArticulo($oArticuloVO->getId());
											$this->_aArticulosCondi = $oArticuloCondiModelo->findAllIdArticulo($oArticuloCondiVO);
											if ($oArticuloCondiModelo->getCantidad()>0){
											    foreach ($this->_aArticulosCondi as $datos){
											        $oArticuloCondiVO->setId($datos['id']);
											        $oArticuloCondiVO->setRotulo(3); // Cambia estado del rótulo psrs descargar en PDF
											        $oArticuloCondiVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
											        $this->_date = date('Y-m-d H:i:s');
											        $oArticuloCondiVO->setFechaAct($this->_date);
											        $oArticuloCondiModelo->update($oArticuloCondiVO);
											        $condi++;
											    }
											}
									
										} // fin agrego precio a PLEX
									}
								} // fin precios diferentes
							 } else { // No encontró el producto para el proveedor
							     /**
							      * Artículo con proveedor de referencia pero el producto no se encuentra 
							      * en la lista del proveedor.
							      * Modifica el id del proveedor, la opción y la equivalencia.
							      */
							    $noLeidos++;
							    echo $cont." #".$this->_item['id']." [".$this->_item['codigo_b']."] ".$this->_item['nombre']." - ";
							    if ($this->_item['id_proveedor'] > 1) { // Tenía proveedores de ref lo cambio por 1
								    // modificar artículo poner id_proveedor en 1, equivalencia en 0, opcion_prov en 0 y actualizar el registro
								    echo "NO LEI PRODUCTO - NO ACTUALIZA (MODIF. PROVEEDOR REF. = 1)<br>";
								    $oArticuloVO->setId($this->_item['id']);
								    $oArticuloModelo->find($oArticuloVO);
								    $oArticuloVO->setIdProveedor(1);
								    $oArticuloVO->setOpcionProv(0);
								    $oArticuloVO->setEquivalencia(0);
								    $oArticuloVO->setRotulo(1);
								    $oArticuloVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
								    $this->_date = date('Y-m-d H:i:s');
								    $oArticuloVO->setFechaAct($this->_date);
								    $oArticuloModelo->update($oArticuloVO);
							    } else {
							        echo "NO LEI PRODUCTO - NO ACTUALIZA<br>";
							    }
							 } // fin NO encontró el producto en proveedores
						  } // fin sin PRECIO UNIFICADO
					   } // fin sin PROMO
					} // fin foreach de articulos x artículo
					DataBasePlex::closeInstance(); // cierra conección con PLEX
					echo "------------------------------------- <br>";
					echo "<b>TOTALES </b><br>";
					echo "------------------------------------- <br>";
					echo "Actualiza: ".$actualiza." artículos<br>";
					echo "Aumentos de Precios: ".$aumentos."<br>";
					echo "---> MAS 30% : ".$masTreinta."<br>";
					echo "---> MAS 20% : ".$masVeinte."<br>";
					echo "---> MAS 10% : ".$masDiez."<br>";
					echo "---> MAS  5% : ".$masCinco."<br>";
					echo "---> MENOS o IGUAL 5% : ".$menosCinco."<br>";
					echo "Bajas de Precios: ".$baja."<br>";
					echo "------------------------------------- <br>";
					echo "<b>NO ACTUALIZA</b><br>";
					echo "Articulos en PROMO: ".$promoNoActualiza." (NO actualiza)<br>";
					echo "PRECIOS UNIFICADOS: ".$PUNoActualiza." (NO actualiza)<br>";
					echo "Actualización -2%: ".$diferenciaMenor." (NO actualiza)<br>";
					echo "No actualizados: ".$noActualiza."<br>";
					echo "No leidos (SIN PROV.): ".$noLeidos."<br>";
					echo "------------------------------------- <br>";
					echo "Imprime: ".$imprimeRotulo." Rótulos<br>";
					echo "Imprime: ".$condi." Rótulos de Art.c/Condiciones<br>";
					echo "------------------------------------- <br>";
					
				} // fin artículos actualizables
				$oDatoVista->setDato('{cantActualizados}',  $actualiza);
				$oDatoVista->setDato('{cantNoActualiza}',  $noActualiza);
				$oDatoVista->setDato('{cantAumentos}',  $aumentos);
				$oDatoVista->setDato('{cantBajas}',  $baja);
				$oDatoVista->setDato('{cantNoLeidos}',  $noLeidos);
				$oDatoVista->setDato('{cantPromo}',  $promoNoActualiza);
				$oDatoVista->setDato('{cantPrecioUnificado}',  $PUNoActualiza);
				$oDatoVista->setDato('{cantImprimeRotulo}',  $imprimeRotulo);
				$oDatoVista->setDato('{cantImprimeRotuloPromo}',  $imprimeRotuloPromo);
				break;				
			# ----> acción Listar
			case 'Listar':
				// carga el contenido html
				$oCargarVista->setCarga('datos', '/modulos/articulo/vista/actualizarPrecios.html');
				$oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
				$oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
				// ingresa los datos a representar en las alertas de la vista
				$oDatoVista->setDato('{alertaSuceso}',  'Finalizó la acción solicitada con <b>EXITO !!!</b>.');
				$oDatoVista->setDato('{alertaAdvertencia}',  '<i>Listado informativo</i>, <b>NO ACTUALIZÓ</b> los precios.');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Artículos - Listado Informativo (No actualiza precios)');
				$oDatoVista->setDato('{informacion}', '<p>Finalizó la acción solicitada para los artículos,</p><p>ver botones u opciones del menú.');
				
				$oArticuloModelo->count();
				$this->_cantidad = $oArticuloModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				$oDatoVista->setDato('{cantActualizados}',  0);
				$oDatoVista->setDato('{cantNoActualiza}',  0);
				$oDatoVista->setDato('{cantAumentos}',  0);
				$oDatoVista->setDato('{cantBajas}',  0);
				$oDatoVista->setDato('{cantNoLeidos}',  0);
				$actualiza = 0;
				$noActualiza = 0;
				$promoNoActualiza = 0;
				$PUNoActualiza = 0;
				$aumentos = 0;
				$baja = 0;
				$noLeidos = 0;
				$diferenciaMenor = 0;
				$imprimeRotulo = 0;
				
				// carga array condición IVA
				$oAfipCondicionIvaVO = new AfipCondicionIvaVO();
				$oAfipCondicionIvaModelo = new AfipCondicionIvaModelo();
				$this->_items = $oAfipCondicionIvaModelo->findAll();
				foreach ($this->_items as $this->_item){
					$this->_aCondicionIva[$this->_item['codigo']] = $this->_item['alicuota'];
				}
				
				// carga array proveedores
				$oProveedorVO = new ProveedorVO();
				$oProveedorModelo = new ProveedorModelo();
				$this->_items = $oProveedorModelo->findAllProveedoresRef();
				foreach ($this->_items as $this->_item){
					$this->_aProveedores[$this->_item['id']] = $this->_item['inicial'];
					$this->_aProveedoresLista[$this->_item['id']] = $this->_item['lista'];
				}
				
				/**
				 * Carga los artículos actualizables según los siguientes datos:
				 *
				 * codigo > 9999900000 ($oArticuloVO->setCodigo())
				 * id_proveedor > 1 ($oArticuloVO->setIdProveedor())
				 * codigo_b > 0 (esta la condición en la consulta)
				 * estado = 1 ($oArticuloVO->setEstado())
				 */
				$oArticuloVO->setCodigo(9999900000); // may0r que
				$oArticuloVO->setIdProveedor(1); // mayor que: 1 - SIN IDENTIFICAR
				$oArticuloVO->setEstado(1); // igual que
				// codigo_b > 0
				$this->_items = $oArticuloModelo->findAllActualizables($oArticuloVO);
				// carga datos para la vista
				$oDatoVista->setDato('{cantActualizables}',  $oArticuloModelo->getCantidad());
				// Fija el tope de precios a agregar igual a productos actualizables
				$topeAgrega = $oArticuloModelo->getCantidad();
				echo "**************************************<br>";
				echo "   <b>LISTADO INFORMATIVO</b><br>";
				echo "   (No actualiza los precios)<br>";
				echo "**************************************<br>";
				echo "Articulos actualizables: ".$topeAgrega."<br>";
				// $topeAgrega = 0;
				$agrega = $condi = 0;
				$masTreinta = $masVeinte = $masDiez = $masCinco = $menosCinco = 0;
				$cont = 0;
				
				// Lista los artículos con variación de precios
				if ($oArticuloModelo->getCantidad()>0){ // Si hay artículos actualizables continúa
					foreach ($this->_items as $this->_item){ // extraigo los datos de artículos x artículo
					   $cont++; 
					   echo $cont." #".$this->_item['id']." [".$this->_item['codigo_b']."] ".$this->_item['nombre']." - $ ".$this->_item['costo']." (".$this->_item['margen']."%)<br>";
					   echo ". . . . Precio $".$this->_item['precio']." - Prov: ".$this->_item['id_proveedor']." (".$this->_aProveedores[$this->_item['id_proveedor']].") ";
					   /**
						* Si es PROMO no modifica precio
						*/
					   $pos = strpos($this->_item['nombre'], 'PR.');
					   if ($pos !== false) {
					      // Muestra datos del registro de artículo
					      echo "<b> <<< PROMO >>>> NO MODIFICA </b><br>";
						  $promoNoActualiza++;
					   } else {	// Artículo sin PROMO sigue a ver si actualiza precio
						  /**
						   * Si es PRECIO UNIFICADO no modifica precio
						   */
						  $pos = strpos($this->_item['presentacion'], '(PU.');
						  if ($pos !== false) {
						      echo "<b> <<< PRECIO UNIFICADO >>>> NO MODIFICA </b><br>";
						      $PUNoActualiza++;
						  } else {	// Artículo sin PRECIO UNIFICADO sigue a ver si actualiza precio
						      /**
						      * Calcula el precio con lista del proveedor de referencia
						      */
						      $oProductoVO->setIdArticulo($this->_item['id']);
						      $oProductoVO->setIdProveedor($this->_item['id_proveedor']);
						      $oProductoVO->setCodigoB(trim($this->_item['codigo_b']));  
						      $oProductoModelo->findPorIdArticuloCodigoBProveedor($oProductoVO); // consulta producto ACTIVO
						      if ($oProductoModelo->getCantidad()>0){ // tiene producto activo en lista del proveedor de referencia
							     // verifico que margen no sea CERO
							     $this->_margen = $this->_item['margen'];
							     if ($this->_margen == 0) $this->_margen = 37;  // margen = 0 pongo treinta y siete de prepo
							         // calculo precio con el costo del producto
							         // costo para guardar es "NETO" (sin  IVA)
							         if ($this->_aProveedoresLista[$this->_item['id_proveedor']] == 'F'){
								        // Lista con precios finales
								        $this->_costo = round($oProductoVO->getPrecio() / (1 + ($this->_aCondicionIva[$this->_item['codigo_iva']] / 100)), 2);
								        $this->_precio = round(($oProductoVO->getPrecio() * (1 + ($this->_margen / 100))) , 2);
							         } else {
								        if ($this->_aProveedoresLista[$this->_item['id_proveedor']] == 'N'){
									       // Lista con precios netos
									       $this->_costo = round($oProductoVO->getPrecio(), 2);
									       $this->_precio = round((($oProductoVO->getPrecio() * (1 + ($this->_aCondicionIva[$this->_item['codigo_iva']] / 100))) * (1 + ($this->_margen / 100))) , 2);
								        } else {
									       // Proveedor sin tipo de lista
									       $this->_costo = 0;
									       $this->_precio = 0;
									       //echo " ** FALTA TIPO DE LISTA ** ";
								        }
							         }
							
								
								    /**
								    * PRECIOS - VEO SI ACTUALIZO
								    * 1 - Precios iguales sigo sin actualizar
								    * 2 - Precios diferentes y la diferencia es > o = al 2% actualizo registros
								    * 3 - Precios diferentes con diferencia menor al 2% sigo sin actualizar
								    * 
								    * NO IMPRIMO EN PANTALLA precios iguales
								    * 
								    */
								    $this->_diferenciaPrecio = $this->_porcentajeDiferencia = 0;
								    $this->_modifico = 'N';
								    if ($this->_item['precio'] == $this->_precio){ // Precios iguales no actualiza
										$noActualiza++;
										echo " Precio Nuevo $ ".$this->_precio." *** PRECIO IGUAL NO MODIFICA ***<br>";
										
								    } else { // Precios diferentes hago comparaciones y veo si actualizo
									   /**
									   * Con una diferencia igual o menor al 2% no modifico los precios de los artículos
									   */
									   echo " -> lista $ ".$oProductoVO->getPrecio()." (".$this->_aProveedoresLista[$this->_item['id_proveedor']].") ";
									   echo " Precio Nuevo $ ".$this->_precio." - ";
									   // Armo totales para mostrar
									   if ($this->_precio > $this->_item['precio']){
										  // calculo diferencia de precio
										  $this->_diferenciaPrecio = round(($this->_precio - $this->_item['precio']), 2);
										  // calculo 2% del precio del artículo
										  $this->_porcentajeDiferencia = round(($this->_item['precio']*2/100), 2);
										  // Si diferencia es menor o igual a 2% no actualizo
										  if ($this->_porcentajeDiferencia > $this->_diferenciaPrecio){
											 echo " NO ACTUALIZO - DIF $".$this->_diferenciaPrecio." < 2% <br>";
											 $diferenciaMenor++;
										  } else {										
											 // Actualiza - Nuevo precio mayor que precio de artículo
											 $this->_modifico = 'S';
											 echo " ACTUALIZO ";
											 $aumentos++;
											 // Calcula porcentaje de aumento
											 $this->_porcentaje = round((($this->_precio - $this->_item['precio']) / $this->_item['precio'] * 100), 2);
											 echo $this->_porcentaje."% ";
											 if ($this->_porcentaje > 30){
											     echo " -> MAS DE 30 % <br>";
												 $masTreinta++;
											 } else {
											     if ($this->_porcentaje > 20){
													echo " -> MAS DE 20 % <br>";
													$masVeinte++;
												 } else {
													if ($this->_porcentaje > 10){
														echo " -> MAS DE 10 % <br>";
														$masDiez++;
													}else{
														if ($this->_porcentaje > 5){
															echo " -> MAS DE 5 % <br>";
															$masCinco++;
														}else{
															echo " -> MENOS O IGUAL A 5 % <br>";
															$menosCinco++;
														}
													}
												}
											}
										}
									 } else {
										// Actualiza - Nuevo precio menor que precio de artículo
										if ($this->_precio < $this->_item['precio']){
											// calculo diferencia de precio
											$this->_diferenciaPrecio = round(($this->_item['precio'] - $this->_precio), 2);
											// calculo 2% del precio del artículo
											$this->_porcentajeDiferencia = round(($this->_item['precio']*2/100), 2);
											// Si diferencia es menor o igual a 2% no actualizo
											if ($this->_porcentajeDiferencia > $this->_diferenciaPrecio){
												echo " NO ACTUALIZO - DIF $".$this->_diferenciaPrecio." < 2% <br>";
												$diferenciaMenor++;
											} else {
												$this->_modifico = 'S';
												echo " ACTUALIZO -BAJA PRECIO- <br>";
												$baja++;
											}	
										}
									}
									
									// Precios diferentes, con diferencia > 2%, agrego registros
									if ($this->_modifico == 'S'){
										if ($agrega < $topeAgrega){
											$actualiza++;
											$agrega++;
											// cambia el rótulo para imprimir
											if ($this->_item['rotulo'] > 0){
											    $imprimeRotulo++;
											}
											// Revisa si tiene condiciones venta
											$oArticuloCondiVO->setIdArticulo($this->_item['id']);
											$this->_aArticulosCondi = $oArticuloCondiModelo->findAllIdArticulo($oArticuloCondiVO);
											$condi = $condi + $oArticuloCondiModelo->getCantidad();
										
										} // fin agrego precio a PLEX
									}
								} // fin precios diferentes
							 } else { // No encontró el producto para el proveedor
								$noLeidos++;
							 }
						  } // fin sin PRECIO UNIFICADO
					   }
					} // fin foreach de articulos x artículo
					echo "------------------------------------- <br>";
					echo "<b>TOTALES </b><br>";
					echo "------------------------------------- <br>";
					echo "Actualiza: ".$actualiza." artículos<br>";
					echo "Aumentos de Precios: ".$aumentos."<br>";
					echo "---> MAS 30% : ".$masTreinta."<br>";
					echo "---> MAS 20% : ".$masVeinte."<br>";
					echo "---> MAS 10% : ".$masDiez."<br>";
					echo "---> MAS  5% : ".$masCinco."<br>";
					echo "---> MENOS o IGUAL 5% : ".$menosCinco."<br>";
					echo "Bajas de Precios: ".$baja."<br>";
					echo "------------------------------------- <br>";
					echo "<b>NO ACTUALIZA</b><br>";
					echo "Articulos en PROMO: ".$promoNoActualiza." (NO actualiza)<br>";
					echo "PRECIOS UNIFICADOS: ".$PUNoActualiza." (NO actualiza)<br>";
					echo "Actualización -2%: ".$diferenciaMenor." (NO actualiza)<br>";
					echo "No actualizados: ".$noActualiza."<br>";
					echo "No leidos (SIN PROV.): ".$noLeidos."<br>";
					echo "------------------------------------- <br>";
					echo "Imprime: ".$imprimeRotulo." Rótulos<br>";
					echo "Imprime: ".$condi." Rótulos de Art.c/Condiciones<br>";
					echo "------------------------------------- <br>";
				} // fin artículos actualizables
				$oDatoVista->setDato('{cantActualizados}',  $actualiza);
				$oDatoVista->setDato('{cantNoActualiza}',  $noActualiza);
				$oDatoVista->setDato('{cantAumentos}',  $aumentos);
				$oDatoVista->setDato('{cantBajas}',  $baja);
				$oDatoVista->setDato('{cantNoLeidos}',  $noLeidos);
				$oDatoVista->setDato('{cantPromo}',  $promoNoActualiza);
				$oDatoVista->setDato('{cantPrecioUnificado}',  $PUNoActualiza);
				$oDatoVista->setDato('{cantImprimeRotulo}',  $imprimeRotulo);
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

/**
 * Nos permite agregar un registro a la tabla productoscostos de la DB Plex
 * con el nuevo precio del artículo
 */
private function agregarProductoCosto($IDProducto, $Precio)
{
	$TipoLista = 'P';
	$Fecha = date('Y-m-d');
	$IDUsuario = '1';
	$Origen = 'A';
	// verifico si no hay precio para esta fecha
	$query = "SELECT count(*) FROM productoscostos WHERE IDProducto='$IDProducto' AND Fecha='$Fecha' AND TipoLista='P'";
	$result = mysqli_query($this->_con, $query) or die(mysqli_error($this->_con));
	$count = mysqli_fetch_array($result);
	mysqli_free_result($result);
	$this->_count = $count[0];
	// actualizo no hay precio para la fecha
	if ($this->_count==0){
		$query = "INSERT INTO productoscostos
		(IDProducto, TipoLista, Fecha, IDUsuario, Precio, Origen)
		VALUES
		('$IDProducto', '$TipoLista', '$Fecha', '$IDUsuario', '$Precio', '$Origen')";
		$res = mysqli_query($this->_con, $query) or die(mysqli_error($this->_con));
		mysqli_free_result($res); 
	}
}

}

?>