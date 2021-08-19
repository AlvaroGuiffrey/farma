<?php
/**
 * Archivo de la clase control del módulo backup.
 *
 * Archivo de la clase control del módulo backup de tablas del inventario.
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
 * Clase control del módulo backup.
 *
 * Clase control del módulo backup que permite realizar
 * copias en otro servidor o BD de tablas del inventario
 * según la categoría asignada al usuario.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */

class BackupControlI
{
	#Propiedades
	private $_html;
	private $_accion;
	private $_items;
	private $_item;
	private $_date;
	private $_modi;
	private $_server;
	private $_dbName;
	private $_userName;
	private $_passwd;
	private $_cantidadReg;
	private $_cantMarcas;
	private $_cantLaboratorio;
	private $_cantArticulos;
	private $_cantZvet;
	private $_aZvet = array();
	private $_aLaboratorio = array();
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
		Clase::define('ArticuloModelo');
		Clase::define('MarcaModelo');
		Clase::define('DataBaseBackup');

		// Chequea login
		$oLoginControl = new LoginControl();
		$oLoginControl->chequearLogin($oLoginVO);
		$this->_accion = "Iniciar";
		$this->accionControl($oLoginVO, $oDataBaseBackupVO);
		
	}

	/**
	 * Nos permite ejecutar las acciones del módulo
	 * artículo del sistema, de acuerdo a la categoría del
	 * usuario.
	 */
	private function accionControl($oLoginVO)
	{
		// Carga acciones del formulario
		if (isset($_POST['bt_conectar'])) $this->_accion = "Conectar";
		if (isset($_POST['bt_conectar_conf'])) $this->_accion = "ConfirmarC";
		if (isset($_POST['bt_backup'])) $this->_accion = "Backup";
		if (isset($_POST['bt_backup_conf'])) $this->_accion = "ConfirmarB";
		if (isset($_POST['bt_actualizar_listado'])) $this->_accion = "ConfirmarL";
		if (isset($_POST['bt_actualizarEti'])) $this->_accion = "ActualizarEti";
		if (isset($_POST['bt_actualizarEti_conf'])) $this->_accion = "ConfirmarActEti";
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
				"conectar" => "/includes/vista/botonConectar.html",
				
		);
		$oCargarVista->setCarga('aAcciones', $this->_aAcciones);
		// Ingresa los datos a representar en el html de la vista
		$oDatoVista = new DatoVista();
		$oDatoVista->setDato('{tituloPagina}', 'Backup Tablas Inventario');
		$oDatoVista->setDato('{usuario}', $oLoginVO->getAlias());
		//$oDatoVista->setDato('{tituloBadge}', 'Registros ');
		// Alertas

		// Carga el contenido html y datos según la acción
		
		// Selector de acciones
		switch ($this->_accion){
			# ----> acción Iniciar
			case 'Iniciar':
				// carga el contenido html
				$oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
				$oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
				// ingresa los datos a representar en las alertas de la vista
				$oDatoVista->setDato('{alertaAdvertencia}', 'Debe realizar conexión con la Base de Datos');
				$oDatoVista->setDato('{alertaInfo}',  'Seleccione alguna acción con los botones.');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Backup - Selección de acciones');
				$oDatoVista->setDato('{informacion}', '<p>Puede seleccionar acciones para backup de tablas,</p><p>ver botones.');
				// arma la tabla de datos a representar
				$oDatoVista->setDato('{tituloBadge}', 'Conexiones ');
				$this->_cantidad = 0;
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				break;
				
			# ----> acción Conectar
			case 'Conectar':
				$this->_cantidad = 0;
				$oDatoVista->setDato('{tituloBadge}', 'Conexiones ');
				// carga el contenido html
				$oCargarVista->setCarga('datos', '/modulos/backup/vista/agregarDatos.html');
				$oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
				// ingresa los datos a representar en las alertas de la vista
				$oDatoVista->setDato('{alertaInfo}',  'Si la conexión se realiza con EXITO, ejecuta el BACKUP de tablas.');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Conexión a Base de Datos');
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				$oDatoVista->setDato('{informacion}', '<p>Realizar una conexión a Base de Datos y ejecuta el backup de tablas.</p>
														<p>También puede seleccionar otras acciones,</p>
														<p>ver botones.');
				// carga los eventos (botones)
				$this->_aEventos = array(
						"confirmar" => "/includes/vista/botonConectarConf.html",
						"borrar" => "/includes/vista/botonBorrar.html"
				);
				$oCargarVista->setCarga('aEventos', $this->_aEventos);
				break;
				
			# ----> acción Confirmar Listar
			case "ConfirmarC":
				set_time_limit(0);
				// recibe los datos enviados por POST
				$this->_server = $_POST['server'];
				$this->_dbName = $_POST['dbName'];
				$this->_userName = $_POST['userName'];
				$this->_passwd = $_POST['passwd'];
				// conecta a Base de Datos
				$timeInicio = date('H:i:s');
				$oDataBaseBackupVO = new DataBaseBackupVO();
				$oDataBaseBackup = new DataBaseBackup();
				
				$oDataBaseBackupVO->setServer($this->_server);
				$oDataBaseBackupVO->setDBName($this->_dbName);
				$oDataBaseBackupVO->setUserName($this->_userName);
				$oDataBaseBackupVO->setPasswd($this->_passwd);
				
				$dbh = $oDataBaseBackup->getInstance($oDataBaseBackupVO);
		// ************ MARCAS **************************		
				// backup de marcas en laboratorio
				$cantModificados = 0;
				$cantAgregados = 0;
				$oMarcaVO = new MarcaVO();
				$oMarcaModelo = new MarcaModelo();
				$this->_items = $oMarcaModelo->findAll();
				$this->_cantMarcas = $oMarcaModelo->getCantidad();
				foreach ($this->_items as $this->_item){
					$sth = $dbh->prepare("SELECT * FROM laboratorio WHERE entity_id=".$this->_item['id']);
					$sth->execute();
					$fila = $sth->fetchObject();
					$this->_cantidadReg = $sth->rowCount();
					if ($this->_cantidadReg == 1){
						$modi = "N";
						if ($fila->lab_name != $this->_item['nombre']) $modi="S";
						if ($fila->lab_status != 1) $modi="S";
						if ($modi == "S"){
							$sth = $dbh->prepare("UPDATE laboratorio SET lab_name=?, lab_status=? WHERE entity_id=?");
							$nombre = $this->_item['nombre'];
							$sth->bindParam(1, $nombre);
							$status = 1;
							$sth->bindParam(2, $status);
							$id = $this->_item['id'];
							$sth->bindParam(3, $id);
							$sth->execute();
							if (!$sth){
								$oPDOe = new PDOException('Problema al intentar insertar datos.', 1);
								$oPDOe->errorInfo = $dbh->errorInfo();
								throw $oPDOe;
							}	
							$cantModificados++;
															
						}
						
					} else {
						if ($this->_cantidadReg == 0){
							$sth = $dbh->prepare("INSERT INTO laboratorio(entity_id, lab_name, lab_status) VALUES (?, ?, ?)");
							$id = $this->_item['id'];
							$sth->bindParam(1, $id);
							$nombre = $this->_item['nombre'];
							$sth->bindParam(2, $nombre);
							$status = 1;
							$sth->bindParam(3, $status);
							$sth->execute();
							if (!$sth){
								$oPDOe = new PDOException('Problema al intentar insertar datos.', 1);
								$oPDOe->errorInfo = $dbh->errorInfo();
								throw $oPDOe;
							}
							$cantAgregados++;
							
						} else {
							echo "*** Mas de un Laboratorio ".$fila->entity_id." - VERIFICAR!!!<br>";
						}
					}
				}
				// elimina recursos
				$sth = null;
				echo "-------TABLA MARCAS-----------------------------<br>";
				echo "Registros Marcas : ".$this->_cantMarcas."<br>";
				echo "Agregados al backup : ".$cantAgregados."<br>";
				echo "Modificados en backup : ".$cantModificados."<br>";
				echo "------------------------------------------------<br>";
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{cantMarcas}', $this->_cantMarcas);
				$oDatoVista->setDato('{cantAgregadosMarcas}', $cantAgregados);
				$oDatoVista->setDato('{cantModificadosMarcas}', $cantModificados);
	// ***************** ARTICULOS ****************			
				// backup de articulos en zvet
				$cantModificados = 0;
				$cantNoModificados = 0;
				$cantAgregados = 0;
				
				$oArticuloVO = new ArticuloVO();
				$oArticuloModelo = new ArticuloModelo();
				
				$oArticuloModelo->count();
				$registrosArticulo = $oArticuloModelo->getCantidad();
				$renglonDesde=0;
				$limiteRenglones=1000;
				$cont=0;
				echo "--------- TABLA ARTICULOS ---------------<br>";
				echo " Cantidad Registros: ".$registrosArticulo."<br>";
				for ($i=0; $i < $registrosArticulo;  ){
					$this->_items = $oArticuloModelo->findAllLimite($renglonDesde, $limiteRenglones);
					echo " Cantidad consulta con limite: ".$oArticuloModelo->getCantidad()."<br>";

					foreach ($this->_items as $this->_item){
						$i++;
						$sth = $dbh->prepare("SELECT * FROM zvet_catalog_product_flat_1 WHERE cod_plex=".$this->_item['codigo']);
						$sth->execute();
						$fila = $sth->fetchObject();
						$this->_cantidadReg = $sth->rowCount();
						echo "#".$i." Lei zvet ->".$fila->entity_id." - ";
						if ($this->_cantidadReg == 1){
							$modi = "N";
							$nombre = $this->_item['nombre']." - ".$this->_item['presentacion'];
							if ($fila->name != $nombre) $modi="S";
							if ($fila->price != $this->_item['precio']) $modi="S";
							if ($fila->costo != $this->_item['costo']) $modi="S";
							if ($fila->cod_lab != $this->_item['id_marca']) $modi="S";
							if ($fila->prod_status != $this->_item['estado']) $modi="S";
							if ($fila->cod_bar != $this->_item['codigo_b']) $modi="S";
							if ($fila->rubro != $this->_item['id_rubro']) $modi="S";
						
							if ($modi == "S"){
								$sth = $dbh->prepare("UPDATE zvet_catalog_product_flat_1 SET name=?, price=?, costo=?, cod_lab=?, prod_status=?, cod_bar=?, rubro=? WHERE cod_plex=?");
								//$nombre = $this->_item['nombre']." - ".$this->_item['presentacion'];
								$sth->bindParam(1, $nombre);
								$precio = $this->_item['precio'];
								$sth->bindParam(2, $precio);
								$costo = $this->_item['costo'];
								$sth->bindParam(3, $costo);								$precio = $this->_item['precio'];
								$idMarca = $this->_item['id_marca'];
								$sth->bindParam(4, $idMarca);
								$estado = $this->_item['estado'];
								$sth->bindParam(5, $estado);
								$codigoB = $this->_item['codigo_b'];
								$sth->bindParam(6, $codigoB);
								$idRubro = $this->_item['id_rubro'];
								$sth->bindParam(7, $idRubro);
								$codigo = $this->_item['codigo'];
								$sth->bindParam(8, $codigo);
								$sth->execute();
								if (!$sth){
									$oPDOe = new PDOException('Problema al intentar insertar datos.', 1);
									$oPDOe->errorInfo = $dbh->errorInfo();
									throw $oPDOe;
								}	
								$cantModificados++;
								echo "*** MODIFICA **** #".$cantModificados."<br>";
								
							} else {
								echo " << IGUAL no modifica >> <br>";
								$cantNoModificados++;
							}
						
						} else {
							if ($this->_cantidadReg == 0){
								$sth = $dbh->prepare("INSERT INTO zvet_catalog_product_flat_1(name, price, special_price, costo, margen, iva, short_description, cod_lab, prod_status, opcion2, cod_plex, cod_bar, vta_libre, rubro, rubro_aux, psicof) 
														VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
								$nombre = $this->_item['nombre']." - ".$this->_item['presentacion'];
								$sth->bindParam(1, $nombre);
								$precio = $this->_item['precio'];
								$sth->bindParam(2, $precio);
								$precioEspecial = 0;
								$sth->bindParam(3, $precioEspecial);
								$costo = $this->_item['costo']; 
								$sth->bindParam(4, $costo);
								$margen = $this->_item['margen'];
								$sth->bindParam(5, $margen);
								$codIva = $this->_item['codigo_iva'];
								if ($codIva < 4) $iva = 0;
								if ($codIva == 4) $iva = 10.5;
								if ($codIva > 4) $iva = 21;
								$sth->bindParam(6, $iva);
								$short_description = " ";
								$sth->bindParam(7, $short_description);
								$idMarca = $this->_item['id_marca'];
								$sth->bindParam(8, $idMarca);
								$estado = $this->_item['estado'];
								$sth->bindParam(9, $estado);
								$opcion_2 = 1;
								$sth->bindParam(10, $opcion_2);
								$codigo = $this->_item['codigo'];
								$sth->bindParam(11, $codigo);
								$codigoB = $this->_item['codigo_b'];
								$sth->bindParam(12, $codigoB);
								$ventaLibre = " ";
								$sth->bindParam(13, $ventaLibre);
								$idRubro = $this->_item['id_rubro'];
								$sth->bindParam(14, $idRubro);
								$rubroAux = 0;
								$sth->bindParam(15, $rubroAux);
								$psicof = " ";
								$sth->bindParam(16, $psicof);
								$sth->execute();
								if (!$sth){
									$oPDOe = new PDOException('Problema al intentar insertar datos.', 1);
									$oPDOe->errorInfo = $dbh->errorInfo();
									throw $oPDOe;
								}
								$cantAgregados++;
								echo " +++ AGREGO REGISTRO +++ (".$codigo.")  #".$cantAgregados."<br>";	
							} else {
								echo "*** Mas de un Artículo ".$fila->entity_id." - VERIFICAR!!!<br>";
							}
						}
					}
				
					$renglonDesde = $renglonDesde + $limiteRenglones;
				}
				echo "Total Leidos ($i): ".$i."<br>";
				// elimina recursos
				$sth = null;
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{cantArticulos}', $registrosArticulo);
				$oDatoVista->setDato('{cantAgregadosArticulos}', $cantAgregados);
				$oDatoVista->setDato('{cantModificadosArticulos}', $cantModificados);
				$oDatoVista->setDato('{cantNoModificadosArticulos}', $cantNoModificados);				 
				// cierra conexión
				$dbh = null;
				$timeFin = date('H:i:s');
				$oDatoVista->setDato('{timeInicio}', $timeInicio);
				$oDatoVista->setDato('{timeFin}', $timeFin);
				// 
				$this->_cantidad = 2; // son dos tablas: articulos y marcas
				$oDatoVista->setDato('{tituloBadge}', 'Tablas ');
				// carga las acciones (botones) 
				$this->_aAcciones = array(
						"badge" => "/includes/vista/botonBadge.html",
						
				);
				$oCargarVista->setCarga('aAcciones', $this->_aAcciones);
				// carga el contenido html
				$oCargarVista->setCarga('datos', '/modulos/backup/vista/verDatosBackupI.html');
				$oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');		
				// ingresa los datos a representar en las alertas de la vista
				$oDatoVista->setDato('{alertaSuceso}',  'Finalizó BACKUP de tablas con EXITO!!!!.');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Backup de Tablas del Inventario');
				$oDatoVista->setDato('{informacion}', '<p>También puede seleccionar otras acciones</p>
														<p>desde el menu.'
									);

				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				
			
				break;

			# ----> acción Backup
			case 'Backup':
				// consulto registros de las tablas del backup
				$this->_cantidadReg = 0;
				echo $this->_cantidadReg."<br>";
				
				/*
				$dbh = $oDataBaseBackup->getInstance($oDataBaseBackupVO);
				$sth = $dbh->prepare("SELECT * FROM laboratorio");
				echo "Pase prepare<br>";
				$sth->execute();
				$this->_aLaboratorio = $sth->fetchAll();
				$this->_cantidadReg = $sth->rowCount();
				*/
				echo $this->_cantidadReg."<br>";
				var_dump($this->_aLaboratorio);
				break;
    		# ---> acción Confirmar Backup
			case 'ConfirmarB':

				break;

			# ----> acción Editar
			case 'Editar':

				break;
			# ----> acción Confirmar Editar
			case 'ConfirmarE':
		
				break;
			
			# ----> acción Actualizar etiquetas
			case 'ActualizarEti':
				// carga el contenido html
				$oCargarVista->setCarga('datos', '/modulos/articulo/vista/actualizarEtiquetas.html');
				$oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Artículos - Actualiza Etiqueta de Proveedor');
				$oDatoVista->setDato('{informacion}', '<p>Actualiza la etiqueta de proveedor de referencia en todos los artículos.</p><p>También puede seleccionar otras acciones</p><p>para los artículos, ver botones.');
				// carga los eventos (botones)
				$this->_aEventos = [
									"actualizarEtiConf" => "/includes/vista/botonActualizarEtiConf.html",
									];
				$oCargarVista->setCarga('aEventos', $this->_aEventos);
				// ingresa los datos a representar en las alertas de la vista
				$oDatoVista->setDato('{alertaPeligro}',  'Actualiza la <b>etiqueta de proveedor de referencia</b> en los artículos, confirme la acción.');

				// arma los datos a representar en la vista
				$oArticuloModelo->count();
				$this->_cantidad = $oArticuloModelo->getCantidad();
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				/**
		 		* Cuenta los artículos actualizables según los siguientes datos:
		 		*
		 		* codigo > 9999900000 ($oArticuloVO->setCodigo())
		 		* codigo_b > 0 (esta la condición en la consulta)
		 		* id_proveedor > 0 ($oArticuloVO->setIdProveedor())
		 		* estado = 1 ($oArticuloVO->setEstado())
				*/
				$oArticuloVO->setCodigo(9999900000); // mayor que
				$oArticuloVO->setIdProveedor(0); // mayor que
				$oArticuloVO->setEstado(1); // igual que
				$oArticuloModelo->countActualizables($oArticuloVO);
				$oDatoVista->setDato('{cantActualizables}', $oArticuloModelo->getCantidad());
				$oDatoVista->setDato('{cantActualizados}', 0);
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
				$oDatoVista->setDato('{cantActualizables}', $_POST['cantActualizables']);
				// carga el contenido html
				$oCargarVista->setCarga('datos', '/modulos/articulo/vista/actualizarEtiquetas.html');
				$oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
				// ingresa los datos a representar en el panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Artículos - Actualiza Etiqueta de Proveedor');
				$oDatoVista->setDato('{informacion}', '<p>Se actualizó la etiqueta de proveedor de referencia en todos los artículos.</p><p>También puede seleccionar otras acciones</p><p>para los artículos, ver botones.');
				// carga los eventos (botones)

				// ingresa los datos a representar en las alertas de la vista
				$oDatoVista->setDato('{alertaSuceso}',  'Finalizó el proceso de actualización de etiquetas de proveedores de referencia con EXITO!!!.');

				// actualiza etiquetas de proveedores en artículos
				$cantModificados = $cantAgregados = $cantActualizados = 0;

				// carga array del orden de listas de precios para actualizar precios
				$oListaOrdenVO = new ListaOrdenVO();
				$oListaOrdenModelo = new ListaOrdenModelo();
				$this->_items = $oListaOrdenModelo->findAll();
				foreach ($this->_items as $this->_item){
					$this->_aListaOrden[$this->_item['id_proveedor']] = $this->_item['id'];
				}

				/**
		 		* Carga los artículos actualizables según los siguientes datos:
				*
		 		* codigo > 9999900000 ($oArticuloVO->setCodigo())
		 		* codigo_b > 0 (esta la condición en la consulta)
		 		* id_proveedor > 0 ($oArticuloVO->setIdProveedor())
		 		* estado = 1 ($oArticuloVO->setEstado())
		 		*/
				$oArticuloVO->setCodigo(9999900000); // mayor que
				$oArticuloVO->setIdProveedor(0); // mayor que
				$oArticuloVO->setEstado(1); // igual que
				$this->_items = $oArticuloModelo->findAllActualizables($oArticuloVO);

				// actualizo los proveedores de referencia
				if ($oArticuloModelo->getCantidad()>0){ // (1) Si hay artículos actualizables continúa
				foreach ($this->_items as $this->_item){ // (2) extraigo los datos de artículos x artículo
					$this->_idProveedor = $this->_item['id_proveedor'];
					$this->_opcionProv = $this->_item['opcion_prov'];
					if ($this->_opcionProv==0 OR $this->_opcionProv==2){ // (3) si tiene opción de actualización "Automática" o no tiene asignada opción
						$oProductoVO->setCodigoB($this->_item['codigo_b']);
						$this->_aProductos = $oProductoModelo->findAllPorCodigoB($oProductoVO);
					if ($oProductoModelo->getCantidad()>0){ // (4) Hay productos con igual código de barra
						// Pone en CERO variables a utilizar
						$this->_listaPrioridad = $this->_listaOrden = 0;
						// Busca Proveedor de referencia para actualizar
						foreach ($this->_aProductos as $this->_producto){ // (5) lee los productos con igual codigo de barra para seleccionar opción de proveedor
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
							* Etiquetado de proveedor
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
				}	// (5) fin foreach de productos para encontrar proveedor de ref.
					// (6) actualiza artículo con proveedor de referencia
				/**
				* Actualizar el artículo con el proveedor de referencia
				* y en opción de proveedor (poner opcion "2")
				* Actualizar el producto con el id del artículo
				*/
				if ($this->_listaPrioridad > 0 OR $this->_listaOrden > 0){
					// Hacer comparaciones para ver si modifico el articulo y/o el producto
					$this->_modi = "N";
					$oArticuloVO->setId($this->_item['id']);
					$oArticuloModelo->find($oArticuloVO);
					$this->_idProveedor = $oArticuloVO->getIdProveedor();

					if ($this->_listaPrioridad > 0){
						$oArticuloVO->setIdProveedor($this->_listaPrioridad);
					}else{
						$oArticuloVO->setIdProveedor($this->_listaOrden);
					}
					if ($this->_idProveedor != $oArticuloVO->getIdProveedor()){
						$this->_modi = "S";
					}
					if ($this->_modi == "S"){
						$oArticuloVO->setOpcionProv(2); // cambia opcion de proveedor por actualización
						$oArticuloVO->setEquivalencia(1); // con productos equivalentes
						$oArticuloVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
						$this->_date = date('Y-m-d H:i:s');
						$oArticuloVO->setFechaAct($this->_date);
						$oArticuloModelo->update($oArticuloVO);
						$cantActualizados++;
						if ($this->_idProveedor > 1){ // ya tenia proveedor de referencia
							$cantModificados++;
						} else { // no tenía proveedor de referencia
							$cantAgregados++;
						}
				/*
				// leo producto para actualizar id_articulo
				$oProductoVO->setCodigoB($oArticuloVO->getCodigoB());
					$oProductoVO->setIdProveedor($oArticuloVO->getIdProveedor());
						$oProductoModelo->findPorCodigoBProveedor($oProductoVO);
						if ($oProductoModelo->getCantidad()>0){
						if ($oProductoVO->getIdArticulo() != $oArticuloVO->getId()){
						$oProductoVO->setIdArticulo($oArticuloVO->getId());
						$oProductoVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
						$this->_date = date('Y-m-d H:i:s');
						$oProductoVO->setFechaAct($this->_date);
						$oProductoModelo->update($oProductoVO);
						}
						}
						*/
					}
				}
				// (6) fin actualiza artículo
				} // (4) fin productos con igual codigo de barra y artículo sin proveedor de referencia
				} // (3) fin con opción para actualización automática o sin opción
				} // (2) fin foreach extraigo datos de artículos actualizables
				} // (1) fin si hay artículos actualizables

			// carga los datos a mostrar en la vista
				$oDatoVista->setDato('{cantActualizados}', $cantActualizados);
				$oDatoVista->setDato('{cantAgregados}', $cantAgregados);
				$oDatoVista->setDato('{cantModificados}', $cantModificados);
				break;
								
			# ----> acción Buscar
			case 'Buscar':

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