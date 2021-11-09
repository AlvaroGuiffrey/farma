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
 * comñaración de los precios máximos con las listas de los proveedores
 * para prueba de modificaciones.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class ArticuloControlPM
{
	#Propiedades
	private $_html;
	private $_accion;
	private $_items;
	private $_item;
	private $_condiciones;
	private $_condicion;
	private $_id;
	private $_estado;
	private $_opcionProv;
	private $_orden;
	private $_date;
	private $_fechaHasta;
	private $_rotuloCondi;
	private $_margen;
	private $_costo;
	private $_precio;
    private $_precio_max;
	private $_porcentaje;
	private $_diferenciaPrecio;
	private $_porcentajeDiferencia;
	private $_modifico;
	private $_aAcciones = array();
	private $_aEventos = array();
	private $_aArticulosCondi = array();
	private $_aCondicionIva = array();
	private $_aProveedores = array();
	private $_aCantidadProv = array();
	private $_idProveedor;
	private $_aProveedoresLista = array();
	private $_aProveedoresRef = array();
	private $_aProductos = array();
    private $_aArticulosPM = array();
	private $_producto;
	private $_aCondiFarma = array();
	private $_condiFarma;
	private $_aCantidadRubros = array();
	private $_aCantidadPorProv = array();
	private $_aCantidadProvUnico = array();
	private $_aCantidadPrecioMenor = array();
	private $_aCantidadIva = array();
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
        Clase::define('ArticuloPMModelo');
		Clase::define('ProductoModelo');
		Clase::define('ProveedorModelo');
		Clase::define('AfipCondicionIvaModelo');
		Clase::define('CondicionModelo');
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
		// Anulo la actualización - SOLO PRUEBAS
		//if (isset($_POST['bt_actualizar'])) $this->_accion = "Actualizar";
		//if (isset($_POST['bt_actualizar_conf'])) $this->_accion = "ConfirmarAct";
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
		// Para prueba elimina el de actualización
		$this->_aAcciones = array(
				"badge" => "/includes/vista/botonBadge.html",
				"listar" => "/includes/vista/botonListar.html"
				//"listar" => "/includes/vista/botonListar.html",
				//"actualizar" => "/includes/vista/botonActualizar.html"
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
		$oCondicionVO = new CondicionVO();
		$oCondicionModelo = new CondicionModelo();

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
				$oDatoVista->setDato('{tituloPanel}', 'Artículos - Compara Precios Máximos - PRUEBA');
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
				// ingresa los datos a representar en las alertas de la vista
				$oDatoVista->setDato('{alertaPeligro}',  '<b>Actualiza los precios de los artículos</b>, confirme la acción.');
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
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				break;
			# ----> acción Confirmar Actualizar
			case 'ConfirmarAct':
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

				/*
				 * 1 -Cambiar el codigo para la etiqueta
				 * 2 -Agregar en PLEX en producto_costo el nuevo precio
				 */

				// Fija el tope de precios a agregar igual a productos actualizables
				$topeAgrega = $_POST['cantActualizables'];
				// $topeAgrega = 0;
				echo "**************************************<br>";
				echo "   <b>LISTADO DE ACTUALIZACIONES</b><br>";
				echo "**************************************<br>";
				echo "Articulos actualizables: ".$topeAgrega."<br>";
				$cont = $agrega = $condi = $masTreinta = $masVeinte = $masDiez = $masCinco = $menosCinco = 0;

				// actualizo los precios de artículos y los productos en DB PLEX
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
                $oDatoVista->setDato('{cantActualizados}', 0);
                $oDatoVista->setDato('{cantNoActualiza}', 0);
                $oDatoVista->setDato('{cantAumentos}', 0);
                $oDatoVista->setDato('{cantBajas}', 0);
                $oDatoVista->setDato('{cantNoLeidos}', 0);
                $oDatoVista->setDato('{cantPromo}', 0);
                $oDatoVista->setDato('{cantPrecioUnificado}', 0);
                $oDatoVista->setDato('{cantPrecioMaximo}', 0);
                $oDatoVista->setDato('{cantImprimeRotulo}', 0);
                $oDatoVista->setDato('{cantImprimeRotuloPromo}', 0);
                $oDatoVista->setDato('{cantInexistentesPlex}', 0);
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                $oDatoVista->setDato('{masTreinta}', 0);
                $oDatoVista->setDato('{masVeinte}', 0);
                $oDatoVista->setDato('{masDiez}', 0);
                $oDatoVista->setDato('{masCinco}', 0);
                $oDatoVista->setDato('{menosCinco}', 0);
                $oDatoVista->setDato('{img1}', ' ');
                $oDatoVista->setDato('{img2}', ' ');
                $oDatoVista->setDato('{img3}', ' ');
                $oDatoVista->setDato('{img4}', ' ');
                $oDatoVista->setDato('{img5}', ' ');
                $oDatoVista->setDato('{img6}', ' ');
                $oDatoVista->setDato(
                    '{evento}',
                    '<h3 style="color:blue">Lista comparación Precios Máximos - NO modifica PLEX</h3>');
				$actualiza = 0;
				$noActualiza = 0;
				$promoNoActualiza = 0;
				$PUNoActualiza = 0;
				$aumentos = 0;
				$baja = 0;
				$noLeidos = 0;
				$diferenciaMenor = 0;
				$imprimeRotulo = 0;
				// carga fecha para Condiciones
				$this->_fechaHasta = date('Y-m-d');

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

				// carga array de indices especiales de Proveedores (las condiciones para la farmacia)
				$this->_aCondiFarma[2] = 0.95; // Del Sud descuento del 5%
				$this->_aCondiFarma[3] = 1.0325; // Nippon carga gastos de flete 3.25%
				$this->_aCondiFarma[4] = 0.95; // Keller descuento del 5%
				$this->_aCondiFarma[5] = 0.98; // CoFarLit descuento del 2%

                // carga array artículos con precios máximos
				$oArticuloPMVO = new ArticuloPMVO();
				$oArticuloPMModelo = new ArticuloPMModelo();
				$this->_items = $oArticuloPMModelo->findAll();
				foreach ($this->_items as $this->_item){
					$this->_aArticulosPM[$this->_item['codigo_b']] = $this->_item['precio'];
				}


				// carga datos para la vista
				$oDatoVista->setDato('{cantActualizables}',  $oArticuloPMModelo->getCantidad());
				// Fija el tope de precios a agregar igual a productos actualizables
				$topeAgrega = $oArticuloPMModelo->getCantidad();
				echo "**************************************<br>";
				echo "   <b>LISTADO INFORMATIVO</b><br>";
				echo "   (PRUEBA - Compara PRECIOS MAXIMOS)<br>";
				echo "**************************************<br>";
				echo "Articulos Precios Máximos: ".$topeAgrega."<br><br>";
				// $topeAgrega = 0;
				$agrega = $condi = 0;
				$masTreinta = $masVeinte = $masDiez = $masCinco = $menosCinco = 0;
				$cont = 0;
                //var_dump($this->_aArticulosPM);
				// Lista los artículos con precios máximos
				if ($oArticuloPMModelo->getCantidad()>0){ // Si hay artículos con precios máximos continúa
					foreach ($this->_aArticulosPM as $key => $value){ // extraigo los datos de artículos x artículo
					    // busca el artículo en la DB
                        $oArticuloVO->setCodigoB($key);
                        echo $oArticuloVO->getCodigoB()."<br>";
                        $oArticuloModelo->findPorCodigoB($oArticuloVO);
                        if ($oArticuloModelo->getCantidad() > 0) {
                            $cont++;
                        	// Renglón con datos del artículo
					        echo $cont." #".$oArticuloVO->getId()." [".$oArticuloVO->getCodigoB()."] <b>".$oArticuloVO->getNombre()."</b> - ".$oArticuloVO->getPresentacion();
						    $rubro = " ";
						    if ($oArticuloVO->getIdRubro() == 1) $rubro = "<b><FONT COLOR='red'>MED.</FONT></b>";
						    if ($oArticuloVO->getIdRubro() == 2) $rubro = "ACC.";
						    if ($oArticuloVO->getIdRubro() == 3) $rubro = "<b><FONT COLOR='blue'>PERF.</FONT></b>";
						    if ($oArticuloVO->getIdRubro() == 4) $rubro = "VARIOS";
						    // Carga cantidad de rotulos en array $_aCantidadRubros
						    if (isset($this->_aCantidadRubros[$oArticuloVO->getIdRubro()])) {
							    $this->_aCantidadRubros[$oArticuloVO->getIdRubro()]++;
						    } else {
							    $this->_aCantidadRubros[$oArticuloVO->getIdRubro()] = 1;
						    }
						    // Carga cantidad de artículos por proveedor de ref en array $_aCantidadPorProv
						    if (isset($this->_aCantidadPorProv[$oArticuloVO->getIdProveedor()])) {
							    $this->_aCantidadPorProv[$oArticuloVO->getIdProveedor()]++;
						    } else {
							    $this->_aCantidadPorProv[$oArticuloVO->getIdProveedor()] = 1;
						    }
						    // Carga cantidad de artículos por alícuota de IVA en array _aCantidadIva
						    if (isset($this->_aCantidadIva[$oArticuloVO->getCodigoIva()])) {
							    $this->_aCantidadIva[$oArticuloVO->getCodigoIva()]++;
						    } else {
							    $this->_aCantidadIva[$oArticuloVO->getCodigoIva()] = 1;
						    }
						    // Verifica si el artículo tiene rótulo para góndola
						    $impRotulo = "NO";
						    if ($oArticuloVO->getRotulo() > 0) $impRotulo = "SI";
						    echo " < Rubro: ".$oArticuloVO->getIdRubro()." - ".$rubro." > /";
						    if ($oArticuloVO->getCodigoIva()==5) {
							    echo "(IVA: ".$this->_aCondicionIva[$oArticuloVO->getCodigoIva()]."%)";
						    } else {
							    echo "<b><FONT COLOR='red'>(IVA: ".$this->_aCondicionIva[$oArticuloVO->getCodigoIva()]."%)</b></FONT>";
						    }
						    echo " / Impr.Rótulo: ".$impRotulo."<br>";
						    // Renglón con datos del costo, proveedor de ref y precio para PLEX
					        echo "<= PLEX => Costo Neto $ ".$oArticuloVO->getCosto()." (Margen: ".$oArticuloVO->getMargen()."%) ";
					        echo "<b>Precio Máximo $".$value."</b> - ";
                            if ($oArticuloVO->getIdProveedor()>0) {
                                echo "Prov: ".$oArticuloVO->getIdProveedor()." (".$this->_aProveedores[$oArticuloVO->getIdProveedor()].")<br>";
                            } else {
                                echo "<FONT COLOR='red'>Prov: *** Artículo SIN PROVEEDOR ***</FONT><br>";
                            }
					        /**
						    * Si es PROMO no modifica precio
						    */
					        $pos = strpos($oArticuloVO->getNombre(), 'PR.');
					        if ($pos !== false) {
					      	    // Muestra datos del registro de artículo
					      	    echo "<b> <<< PROMO >>>> NO MODIFICA </b><br>";
						  	    $promoNoActualiza++;
					   	    } else {	// Artículo sin PROMO sigue a ver si actualiza precio
						  	    /**
						   	    * Si es PRECIO UNIFICADO no modifica precio
						   	    */
						  	    $pos = strpos($oArticuloVO->getPresentacion(), '(PU.');
						  	    if ($pos !== false) {
						      	    echo "<b> <<< PRECIO UNIFICADO >>>> NO MODIFICA </b><br>";
						      	    $PUNoActualiza++;
						  	    } else {	// Artículo sin PRECIO UNIFICADO sigue a ver si actualiza precio
							  	    /**
							   	    * Busca si tiene Condiciones para reimprimir rótulos de ofertas y promo
							   	    */
							  	    $oArticuloCondiVO->setIdArticulo($oArticuloVO->getId());
							  	    $oArticuloCondiVO->setFechaHasta($this->_fechaHasta);
							  	    $this->_condiciones = $oArticuloCondiModelo->findAllIdArticulo($oArticuloCondiVO);
							  	    if ($oArticuloCondiModelo->getCantidad()>0) {
							  	 	    // Muestro las condiciones del artículos
							  	 	    foreach ($this->_condiciones as $this->_condicion) {
										    $oCondicionVO->setId($this->_condicion['id_condicion']);
										    $oCondicionModelo->find($oCondicionVO);
										    if ($oCondicionModelo->getCantidad()>0) {
											    echo "<b><FONT COLOR='green'>+++ Condición: ".$oCondicionVO->getNombre()." +++</FONT></b><br>";
										    } else {
											    echo "+++ Condición: NO ENCONTRADA<br>";
										    }
							  	 	    }
							  	    }
						      	    /**
						      	    * Calcula el precio con lista del proveedor de referencia
						      	    */
						      	    $oProductoVO->setIdArticulo($oArticuloVO->getId());
						      	    $oProductoVO->setCodigoB(trim($oArticuloVO->getCodigoB()));
							  	    // consulta productos ACTIVOS de todos los proveedores
						      	    $this->_aProductos = $oProductoModelo->findAllPorIdArticuloCodigoB($oProductoVO);
								    // Creo un array para comparar los precios de costo de los Proveedores
								    if ($oProductoModelo->getCantidad()>1) $aPreciosProv = array();
								    // tiene producto activo en listas de proveedores
						      	    if ($oProductoModelo->getCantidad()>0){
									    // Cargo cantidad de proveedores en array $_aCantidadProv
									    if (isset($this->_aCantidadProv[$oProductoModelo->getCantidad()])) {
								  		    $this->_aCantidadProv[$oProductoModelo->getCantidad()]++;
									    } else {
										    $this->_aCantidadProv[$oProductoModelo->getCantidad()] = 1;
									    }
									    // verifico que margen no sea CERO
							     	    $this->_margen = $oArticuloVO->getMargen();
							  	 	    if ($this->_margen == 0) $this->_margen = 37;  // margen = 0 pongo treinta y siete de prepo

							  		    // Recorre el array de productos para mostrar los diferentes precios calculados
						  	  		    foreach ($this->_aProductos as $this->_producto) {
                                 		    /**
                                  		    * Calculo precio del artículo con el costo del producto
                                  		    */

								 		    // busca condiciones del proveedor para la farmacia
								 		    if (isset($this->_aCondiFarma[$this->_producto['id_proveedor']])) {
								 			    $this->_condiFarma = $this->_aCondiFarma[$this->_producto['id_proveedor']];
										    } else {
											    $this->_condiFarma = 1;
										    }
							     		    // el costo para guardar en tabla es "NETO" (sin  IVA)
								 		    if ($this->_aProveedoresLista[$this->_producto['id_proveedor']] == 'F'){
							        		    // Lista con precios finales
							        		    $this->_costo = round(($this->_producto['precio'] * $this->_condiFarma)
													/ (1 + ($this->_aCondicionIva[$oArticuloVO->getCodigoIva()] / 100))
													, 2);
							        		    $this->_precio = round((($this->_producto['precio'] * $this->_condiFarma)
									 				* (1 + ($this->_margen / 100))) , 2);
								 		    } elseif ($this->_aProveedoresLista[$this->_producto['id_proveedor']] == 'N') {
							               	    // Lista con precios netos
									       	    $this->_costo = round(($this->_producto['precio'] * $this->_condiFarma), 2);
									       	    $this->_precio = round(((($this->_producto['precio'] * $this->_condiFarma)
										   						* (1 + ($this->_aCondicionIva[$oArticuloVO->getCodigoIva()] / 100)))
																* (1 + ($this->_margen / 100))) , 2);
								 		    } else {
									       	    // Proveedor sin tipo de lista
									       	    $this->_costo = 0;
									       	    $this->_precio = 0;
									       	    //echo " ** FALTA TIPO DE LISTA ** ";

							        	    }
										    // Carga array de proveedores únicos _aCantidadProvUnico
										    if ($oProductoModelo->getCantidad()==1) {
											    if (isset($this->_aCantidadProvUnico[$this->_producto['id_proveedor']])) {
									  			    $this->_aCantidadProvUnico[$this->_producto['id_proveedor']]++;
											    } else {
												    $this->_aCantidadProvUnico[$this->_producto['id_proveedor']] = 1;
											    }
										    }
										    // Carga en el array de precios de proveedores el costo calculado del producto
										    // si hay más de un proveedor
										    if ($oProductoModelo->getCantidad()>1) $aPreciosProv[$this->_producto['id_proveedor']] = $this->_costo;
								 		    // Anterior cálculo del precio sin aplicar los descuentos de los proveedores
							     		    /*
							     		    if ($this->_aProveedoresLista[$this->_producto['id_proveedor']] == 'F'){
							        		    // Lista con precios finales
							        		    $this->_costo = round($this->_producto['precio'] / (1 + ($this->_aCondicionIva[$this->_item['codigo_iva']] / 100)), 2);
							        		    $this->_precio = round(($this->_producto['precio'] * (1 + ($this->_margen / 100))) , 2);
							        	    } else {
								        	    if ($this->_aProveedoresLista[$this->_producto['id_proveedor']] == 'N'){
									       	    // Lista con precios netos
									       		    $this->_costo = round($this->_producto['precio'], 2);
									       		    $this->_precio = round((($this->_producto['precio'] *
										   							(1 + ($this->_aCondicionIva[$this->_item['codigo_iva']] / 100))) *
																	(1 + ($this->_margen / 100))) , 2);
								        	    } else {
									       		    // Proveedor sin tipo de lista
									       		    $this->_costo = 0;
									       		    $this->_precio = 0;
									       		    //echo " ** FALTA TIPO DE LISTA ** ";
								        	    }
							        	    }
										    */

								    	    /**
								    	    * PRECIOS - VEO SI ACTUALIZO
								    	    * 1 - Precios iguales sigo sin actualizar
								    	    * 2 - Precios diferentes y la diferencia es > o = al 2% actualizo registros
								    	    * 3 - Precios diferentes con diferencia menor al 2% sigo sin actualizar
								    	    *
								    	    */
								    	    $this->_diferenciaPrecio = $this->_porcentajeDiferencia = 0;
								    	    $this->_modifico = 'N';
										    // Muestra precio de lista del proveedor
										    echo " -> (".$this->_aProveedores[$this->_producto['id_proveedor']].") lista $ "
											 	.$this->_producto['precio']." ("
												.$this->_aProveedoresLista[$this->_producto['id_proveedor']].") costo Farma $ "
												.$this->_costo." // ";
								    	    if ($value == $this->_precio){ // Precios iguales no actualiza
											    $noActualiza++;
											    echo "<FONT COLOR='green'> Precio Nuevo $ ".$this->_precio." *** PRECIO IGUAL NO MODIFICA *** </FONT><br>";

								    	    } else { // Precios diferentes hago comparaciones y veo si actualizo
									   	    /**
									   	    * Con una diferencia igual o menor al 2% no modifico los precios de los artículos
									   	    */
                                                if ($value > $this->_precio) {
                                                    echo "<FONT COLOR='green'> <b> Precio Nuevo $ ".$this->_precio." - </b></FONT>";
                                                } else {
									   		        echo " Precio Nuevo $ ".$this->_precio." - ";
                                                }
									   		    // Armo totales para mostrar
									   		    if ($this->_precio > $value){
										  		    // calculo diferencia de precio
										  		    $this->_diferenciaPrecio = round(($this->_precio - $value), 2);
										  		    // calculo 2% del precio del artículo
										  		    $this->_porcentajeDiferencia = round(($value*2/100), 2);
										  		    // Si diferencia es menor o igual a 2% no actualizo
										  		    if ($this->_porcentajeDiferencia > $this->_diferenciaPrecio){
											 		    echo "<FONT COLOR='green'> NO ACTUALIZO - DIF $".$this->_diferenciaPrecio." < 2% </FONT><br>";
											 		    $diferenciaMenor++;
										  		    } else {
											 		    // Actualiza - Nuevo precio mayor que precio de artículo
											 		    // Verifica proveedor si es el de referencia
											 		    if ($this->_producto['id_proveedor'] == $oArticuloVO->getIdProveedor()) {
											 			    $this->_modifico = 'S';
											 			    $aumentos++;
														    echo "<b> ACTUALIZO PROV. REF.</b> ";
													    } else {
														    echo " ACTUALIZO OTRO PROV. ";
													    }

				 						 	 		    // Calcula porcentaje de aumento
											 		    $this->_porcentaje = round((($this->_precio - $value) / $value * 100), 2);
											 		    echo $this->_porcentaje."% ";
											 		    if ($this->_porcentaje > 30){
											     		    echo " -> MAS DE 30 % <br>";
												 		    $masTreinta++;
											 		    } elseif ($this->_porcentaje > 20) {
														    echo " -> MAS DE 20 % <br>";
														    $masVeinte++;
											 		    } elseif ($this->_porcentaje > 10) {
														    echo " -> MAS DE 10 % <br>";
														    $masDiez++;
											 		    } elseif ($this->_porcentaje > 5) {
														    echo " -> MAS DE 5 % <br>";
														    $masCinco++;
											 		    } else {
														    echo "<FONT COLOR='green'> -> MENOS O IGUAL A 5 % </FONT><br>";
														    $menosCinco++;
													    }
												    }
									 		    } else {
												    // Actualiza - Nuevo precio menor que precio de artículo
												    if ($this->_precio < $value){
													    // Calcula porcentaje de baja
													    $this->_porcentaje = round((($this->_precio - $value) / $value * 100), 2);
													    // calculo diferencia de precio
													    $this->_diferenciaPrecio = round(($value - $this->_precio), 2);
													    // calculo 2% del precio del artículo
													    $this->_porcentajeDiferencia = round(($value*2/100), 2);
													    // Si diferencia es menor o igual a 2% no actualizo
													    if ($this->_porcentajeDiferencia > $this->_diferenciaPrecio){
														    echo "<FONT COLOR='green'> NO ACTUALIZO - DIF $".$this->_diferenciaPrecio." < 2% </FONT><br>";
														    $diferenciaMenor++;
													    } elseif ($this->_producto['id_proveedor'] == $oArticuloVO->getIdProveedor()) {
														    $this->_modifico = 'S';
   											 			    $baja++;
   														    echo "<FONT COLOR='green'><b> ACTUALIZO -BAJA PRECIO- PROV. REF. </b>".$this->_porcentaje."% </FONT><br>";
													    } else {
   														    echo "<FONT COLOR='green'> ACTUALIZO -BAJA PRECIO- OTRO PROV.</b> ".$this->_porcentaje."% </FONT><br>";
													    }
												    }
											    }


			// Verificar si el proveedor que viene es el de referencia para totalizar modificación
											   // Precios diferentes, con diferencia > 2%, agrego registros a PLEX
											    if ($this->_modifico == 'S'){
												    if ($agrega < $topeAgrega){
													    $actualiza++;
													    $agrega++;
													    // cambia el rótulo para imprimir
													    if ($oArticuloVO->getRotulo() > 0){
											    		    $imprimeRotulo++;
													    }
													    // Revisa si tiene condiciones venta
													    $oArticuloCondiVO->setIdArticulo($oArticuloVO->getId());
													    $this->_aArticulosCondi = $oArticuloCondiModelo->findAllIdArticulo($oArticuloCondiVO);
													    $condi = $condi + $oArticuloCondiModelo->getCantidad();

												    } // fin agrego precio a PLEX
											    }

										    } // fin precios diferentes
							  		    }// fin del forech de productos para el artículo

									    /**
									     * Muestra el proveedor con el menor precio
									     *
									     */
									    if (isset($aPreciosProv)) {
										    asort($aPreciosProv);
										    echo $this->_aProveedores[key($aPreciosProv)]." con precio costo menor <br>";
										    // Carga el array de cantidad de precios menores por proveedores
										    if (isset($this->_aCantidadPrecioMenor[key($aPreciosProv)])) {
									  		    $this->_aCantidadPrecioMenor[key($aPreciosProv)]++;
										    } else {
											    $this->_aCantidadPrecioMenor[key($aPreciosProv)] = 1;
										    }
										    unset($aPreciosProv);
									    }

								    } else { // No encontró productos de ningún proveedor
									    echo "NO encontro producto en ninguna Lista<br>";
									    $noLeidos++;
							 	    }
						  	    } // fin sin PRECIO UNIFICADO
					   	    }
                        }
					   	// Línea para separar artículos
					   	echo "-----------------------------------------------------<br>";
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
					// Muestra el total de artículos con rótulos en góndolas
					$oArticuloModelo->countRotulosGondola();
					echo "Son: ".$oArticuloModelo->getCantidad()." artículos con Rótulos p/Góndola<br>";
					echo "------------------------------------- <br>";
					ksort($this->_aCantidadPorProv);
					foreach ($this->_aCantidadPorProv as $key => $value) {
						echo "Proveedor: (".$key.") ".$this->_aProveedores[$key]." con ".$value." artículos etiquetados";
						if (isset($this->_aCantidadProvUnico[$key])) echo "; en ".$this->_aCantidadProvUnico[$key]." como Prov. único";
						if (isset($this->_aCantidadPrecioMenor[$key])) {
							echo "; y ".$this->_aCantidadPrecioMenor[$key]." precio de costo menor.<br>";
						} else {
							echo ".<br>";
						}
					}
					echo "------------------------------------- <br>";
					ksort($this->_aCantidadProv);
					foreach ($this->_aCantidadProv as $key => $value) {
						echo "Son: ".$value." artículos con ".$key." proveedor/es <br>";
					}
					echo "------------------------------------- <br>";
					ksort($this->_aCantidadRubros);
					foreach ($this->_aCantidadRubros as $key => $value) {
						$rubro = " ";
						if ($key == 1) $rubro = "<b><FONT COLOR='red'>MED.</FONT></b>";
						if ($key == 2) $rubro = "ACC.";
						if ($key == 3) $rubro = "PERF.";
						if ($key == 4) $rubro = "VARIOS";
						echo "Son: ".$value." artículos del rubro: ".$key." - ".$rubro." <br>";
					}
					echo "------------------------------------- <br>";
					ksort($this->_aCantidadIva);
					foreach ($this->_aCantidadIva as $key => $value) {
						echo "Son: ".$value." artículos con IVA del: (".$key.") ".$this->_aCondicionIva[$key]."% <br>";
					}
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
