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
	private $_aAcciones = array();
	private $_aEventos = array();
	private $_aCondicionIva = array();
	private $_aProveedores = array();
	private $_idProveedor;
	private $_aProveedoresLista = array();
	private $_aProveedoresRef = array();
	private $_aProductos = array();
	private $_producto;
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
		$oDatoVista->setDato('{tituloPagina}', 'Artículos');
		$oDatoVista->setDato('{usuario}', $oLoginVO->getAlias());
		$oDatoVista->setDato('{tituloBadge}', 'Artículos ');
		// Alertas

		// Carga el contenido html y datos según la acción
		$oArticuloVO = new ArticuloVO();
		$oArticuloModelo = new ArticuloModelo();
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
				$aumentos = 0;
				$baja = 0;
				$noLeidos = 0;
		
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
				$oArticuloVO->setCodigo(9999900000); // mayor que
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
				$agrega = 0;
				$cont = 0;
				
				// actualizo los precios de artículos y los productos PLEX 
				if ($oArticuloModelo->getCantidad()>0){ // Si hay artículos actualizables continúa
					$conx = DataBasePlex::getInstance(); // conecto a DB PLEX
					foreach ($this->_items as $this->_item){ // extraigo los datos de artículos x artículo
							$cont++;
							/**
							 * Calcula el precio con lista del proveedor de referencia
							 */
						
							echo $cont." #".$this->_item['id']." [".$this->_item['codigo_b']."] ".$this->_item['nombre']." - $ ".$this->_item['costo']." (".$this->_item['margen']."%)<br>";
							echo ". . . . Precio $".$this->_item['precio']." - Prov: ".$this->_item['id_proveedor']." (".$this->_aProveedores[$this->_item['id_proveedor']].") ";
							$oProductoVO->setIdArticulo($this->_item['id']);
							$oProductoVO->setIdProveedor($this->_item['id_proveedor']);
							$oProductoModelo->findPorIdArticuloProveedor($oProductoVO); // Consulta solo en productos ACTIVOS
							if ($oProductoModelo->getCantidad()>0){ // tiene producto en lista del proveedor de referencia
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
								echo " Precio Nuevo $ ".$this->_precio." - ";
								if ($this->_item['precio'] == $this->_precio){
									// Precios iguales no actualiza
									echo " *** NO ACTUALIZO / PRECIO IGUAL ***<br>";
									$noActualiza++;
								} else {
									// Precios diferentes actualizo registros
									if ($agrega < $topeAgrega){
										// Agrego registro a BD PLEX con precio 
										$this->agregarProductoCosto($this->_item['codigo'], $this->_precio);
										// Modifico articulo con los nuevos datos
										$oArticuloVO->setId($this->_item['id']);
										$oArticuloModelo->find($oArticuloVO);
										$oArticuloVO->setCosto($this->_costo);
										$oArticuloVO->setPrecio($this->_precio);
										$oArticuloVO->setFechaPrecio(date('Y-m-d'));
										if ($oArticuloVO->getRotulo() > 0){
											$oArticuloVO->setRotulo(2);
										}
										$oArticuloVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
										$this->_date = date('Y-m-d H:i:s');
										$oArticuloVO->setFechaAct($this->_date);
										$oArticuloModelo->update($oArticuloVO);
										$actualiza++;
										$agrega++;
									}
									
									// Armo totales para mostrar
									if ($this->_precio > $this->_item['precio']){
										// Actualiza - Nuevo precio mayor que precio de artículo
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
									} else {
										// Actualiza - Nuevo precio menor que precio de artículo
										if ($this->_precio < $this->_item['precio']){
										echo " ACTUALIZO -BAJA PRECIO- <br>";
										$baja++;
										} 
									}
								} // fin agrego precio a PLEX
							} else { // No encontró el producto para el proveedor
								// modificar artículo poner id_proveedor en 1, equivalencia en 0, opcion_prov en 0 y actualizar el registro
								echo "NO LEI PRODUCTO (MODIF. PROVEEDOR REF. = 1)<br>";
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
								$noLeidos++;
							}
						
					} // fin foreach de articulos x artículo
					DataBasePlex::closeInstance(); // cierra conección con PLEX
					echo "TOTALES <br>";
					echo "-------------------------<br>";
					echo "Actualiza: ".$actualiza."<br>";
					echo "Aumentos: ".$aumentos."<br>";
					echo "---> MAS 30% : ".$masTreinta."<br>";
					echo "---> MAS 20% : ".$masVeinte."<br>";
					echo "---> MAS 10% : ".$masDiez."<br>";
					echo "---> MAS 5% : ".$masCinco."<br>";
					echo "---> MENOS o IGUAL 5% : ".$menosCinco."<br>";
					echo "Bajas: ".$baja."<br>";
					echo "No modificados: ".$noActualiza."<br>";
					echo "No leidos: ".$noLeidos."<br>";
				} // fin artículos actualizables
				$oDatoVista->setDato('{cantActualizados}',  $actualiza);
				$oDatoVista->setDato('{cantNoActualiza}',  $noActualiza);
				$oDatoVista->setDato('{cantAumentos}',  $aumentos);
				$oDatoVista->setDato('{cantBajas}',  $baja);
				$oDatoVista->setDato('{cantNoLeidos}',  $noLeidos);
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

/**
 * Nos permite agregar un registro a la tabla productoscostos de la DB Plex
 * con el nuevo precio del artículo
 */
private function agregarProductoCosto($IDProducto, $Precio)
{
	$TipoLista = 'P';
	$Fecha = date('Y-m-d');
	$IDUsuario = 0;
	$Origen = 'A';
	$query = "INSERT INTO plex_productoscostos
	(IDProducto, TipoLista, Fecha, IDUsuario, Precio, Origen)
	VALUES
	('$IDProducto', '$TipoLista', '$Fecha', '$IDUsuario', '$Precio', '$Origen')";
	$res = mysql_query($query) or die(mysql_error());
	mysql_free_result($res);
}

}
?>