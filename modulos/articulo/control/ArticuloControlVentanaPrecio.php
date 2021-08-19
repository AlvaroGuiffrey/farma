<?php
/**
 * Archivo de la clase control de ventana modifica precio del módulo artículo.
 *
 * Archivo de la clase control de ventana modifica precio del módulo artículo.
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
 * Clase control de ventana modifica precio del módulo artículo.
 *
 * Clase control de ventana modifica precio del módulo artículo que 
 * permite realizar operaciones para modificar precio de un artículo 
 * de la tabla artículos según la categoría asignada al usuario.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class ArticuloControlVentanaPrecio
{
	#Propiedades
	private $_html;
	private $_accion;
	private $_items;
	private $_cantidad;
	private $_id;
	private $_precioNuevo;
	private $_date;
	private $_estado;
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
		Clase::define('MotorVista');
		Clase::define('ArticuloModelo');
		Clase::define('ArticuloDatos');
		Clase::define('RubroModelo');
		Clase::define('MarcaModelo');
		
		// Chequea login
		$oLoginControl = new LoginControl();
		$oLoginControl->chequearLogin($oLoginVO);
		$this->_accion = $accion;
		$this->_id = $id;
		$this->accionControl($oLoginVO);		
	}

	/**
	 * Nos permite ejecutar las acciones para ventana modifica precio
	 * del módulo artículo del sistema, de acuerdo a la categoría del 
	 * usuario.
	 */
	private function accionControl($oLoginVO)
	{
		// Carga acciones del formulario
		if (isset($_POST['bt_editar'])) $this->_accion = "Modificar";
		if (isset($_POST['bt_editar_conf'])) $this->_accion = "ConfirmarM";
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
		# ----> acción Modificar
			case 'Modificar':
				$oArticuloVO->setId($this->_id);
				$oArticuloModelo->find($oArticuloVO);
				$this->_cantidad = $oArticuloModelo->getCantidad();
				// carga el contenido html
				$oCargarVista->setCarga('contenido', '/includes/vista/editarVentana.html');
				$oCargarVista->setCarga('datos', '/modulos/articulo/vista/modificarPrecio.html');
				// ingresa los datos a representar en el Panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Modificar Precio del Artículo');
					
				// ingresa los datos a representar en el contenido de la vista
				ArticuloDatos::cargaDatos($oArticuloVO, $oDatoVista, $this->_accion);
				/*
				$oDatoVista->setDato('{id}', $oArticuloVO->getId());
				$oDatoVista->setDato('{codigo}', $oArticuloVO->getCodigo());
				$oDatoVista->setDato('{codigoMarca}', $oArticuloVO->getCodigoM());

				// busca la marca del artículo
				$oMarcaVO = new MarcaVO();
				$oMarcaModelo = new MarcaModelo();
				$oMarcaVO->setId($oArticuloVO->getIdMarca());
				$oMarcaModelo->find($oMarcaVO);
				$this->_cantidad = $oMarcaModelo->getCantidad();
				if ($this->_cantidad > 0){
					$oDatoVista->setDato('{marca}', $oMarcaVO->getNombre());
				}else{
					$oDatoVista->setDato('{marca}', 'SIN IDENTIFICAR MARCA');
				}
				
				// busca el rubro del artículo
				$oRubroVO = new RubroVO();
				$oRubroModelo = new RubroModelo();
				$oRubroVO->setId($oArticuloVO->getIdRubro());
				$oRubroModelo->find($oRubroVO);
				$this->_cantidad = $oRubroModelo->getCantidad();
				if ($this->_cantidad > 0){
					$oDatoVista->setDato('{rubro}', $oRubroVO->getNombre());
				}else{
					$oDatoVista->setDato('{rubro}', 'SIN IDENTIFICAR RUBRO');
				}	
					
				$oDatoVista->setDato('{nombre}', $oArticuloVO->getNombre());
				$oDatoVista->setDato('{margen}', $oArticuloVO->getMargen());
				$oDatoVista->setDato('{costo}', $oArticuloVO->getCosto());
				$oDatoVista->setDato('{precio}', $oArticuloVO->getPrecio());
				$oDatoVista->setDato('{fechaPrecio}', $oArticuloVO->getFechaPrecio());
				*/
				// calculo nuevo precio
				$this->_precioNuevo = $oArticuloVO->getCosto() * (1 + ($oArticuloVO->getMargen() / 100));
				$oDatoVista->setDato('{precioNuevo}', $this->_precioNuevo);
				// Alertas según la situación del nuevo precio
				if ($this->_precioNuevo > $oArticuloVO->getPrecio()){
					// carga la alerta en el contenido html de la vista
					$oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
					// ingresa los datos a representar en las alertas de la vista
					$oDatoVista->setDato('{alertaPeligro}', 'Se sugiere AUMENTAR el precio.');
				}else{
					// carga la alerta en el contenido html de la vista
					$oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
					// ingresa los datos a representar en las alertas de la vista
					$oDatoVista->setDato('{alertaAdvertencia}', 'Se sugiere BAJAR el precio.');
				}	
							
				break;
					
		# ----> acción Confirmar Modificar
			case 'ConfirmarM':
				$oArticuloVO->setId($_POST['id']);
				$oArticuloModelo->find($oArticuloVO);
				$oArticuloVO->setPrecio($_POST['precioNuevo']);
				$oArticuloVO->setFechaPrecio(date('Y-m-d'));	
				$oArticuloVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
				$this->_date = date('Y-m-d H:i:s');
				$oArticuloVO->setFechaAct($this->_date);
				$oArticuloModelo->update($oArticuloVO);
				$this->_cantidad = $oArticuloModelo->getCantidad();

				// carga el contenido html
				$oCargarVista->setCarga('contenido', '/includes/vista/verVentana.html');
				$oCargarVista->setCarga('datos', '/modulos/articulo/vista/verDatosPrecio.html');
				
				// ingresa los datos a representar en el Panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Ver Precio del Artículo');
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				// ingresa los datos a representar en el contenido de la vista
				ArticuloDatos::cargaDatos($oArticuloVO, $oDatoVista, $this->_accion);
				/*
				$oDatoVista->setDato('{codigo}', $oArticuloVO->getCodigo());
				$oDatoVista->setDato('{codigoMarca}', $oArticuloVO->getCodigoM());
				$oMarcaVO = new MarcaVO();
				$oMarcaVO->setId($oArticuloVO->getIdMarca());
				$oMarcaModelo = new MarcaModelo();
				$oMarcaModelo->find($oMarcaVO);
				$oDatoVista->setDato('{marca}', $oMarcaVO->getNombre());
				$oRubroVO = new RubroVO();
				$oRubroVO->setId($oArticuloVO->getIdRubro());
				$oRubroModelo = new RubroModelo();
				$oRubroModelo->find($oRubroVO);
				$oDatoVista->setDato('{rubro}', $oRubroVO->getNombre());
				$oDatoVista->setDato('{nombre}', $oArticuloVO->getNombre());
				$oDatoVista->setDato('{margen}', $oArticuloVO->getMargen());
				$oDatoVista->setDato('{costo}', $oArticuloVO->getCosto());
				$oDatoVista->setDato('{precio}', $oArticuloVO->getPrecio());
				$oDatoVista->setDato('{fechaPrecio}', $oArticuloVO->getFechaPrecio());
				*/
				// ingresa otros datos de acuerdo al resultado de la modificación del precio
				if ($this->_cantidad==1){
					// carga el contenido html
					$oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
					// ingresa los datos a representar en el Panel de la vista
					$oDatoVista->setDato('{informacion}', '<p>Modifico el precio del artículo con exito!!!.</p>
														<p>Seleccione alguna acción para los artículos,</p>
														<p>o alguna opción del menú.'
										);
					// ingresa los datos a representar en las alertas de la vista
					$oDatoVista->setDato('{alertaSuceso}', 'Se modificó el precio del artículo con EXITO!!!');
				}else{
				 // carga el contenido html
				 $oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
				 // ingresa los datos a representar en el panel de la vista
				 $oDatoVista->setDato('{informacion}', '<p>Precaución, falló la modificación del precio del artículo.</p>
				 										<p>Vuelva a intentar, o seleccione alguna acción</p>
				 										<p>u otra opción del menú.'
										);
				 // ingresa los datos a representar en las alertas de la vista
				 $oDatoVista->setDato('{alertaPeligro}', 'PELIGRO - No se modificó el precio del artículo.');
				}

				break;
			case 'Ver':
				$oArticuloVO->setId($this->_id);
				$oArticuloModelo->find($oArticuloVO);
				$this->_cantidad = $oArticuloModelo->getCantidad();
				// carga el contenido html
				$oCargarVista->setCarga('contenido', '/includes/vista/verVentana.html');
				$oCargarVista->setCarga('datos', '/modulos/articulo/vista/verDatosPrecio.html');
				// ingresa los datos a representar en el Panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Ver Precio del Artículo');
				// ingresa los datos a representar en el contenido de la vista
				ArticuloDatos::cargaDatos($oArticuloVO, $oDatoVista, $this->_accion);
				/*
				$oDatoVista->setDato('{codigo}', $oArticuloVO->getCodigo());
				$oDatoVista->setDato('{codigoMarca}', $oArticuloVO->getCodigoM());
				$oMarcaVO = new MarcaVO();
				$oMarcaVO->setId($oArticuloVO->getIdMarca());
				$oMarcaModelo = new MarcaModelo();
				$oMarcaModelo->find($oMarcaVO);
				$oDatoVista->setDato('{marca}', $oMarcaVO->getNombre());
				$oRubroVO = new RubroVO();
				$oRubroVO->setId($oArticuloVO->getIdRubro());
				$oRubroModelo = new RubroModelo();
				$oRubroModelo->find($oRubroVO);
				$oDatoVista->setDato('{rubro}', $oRubroVO->getNombre());
				$oDatoVista->setDato('{nombre}', $oArticuloVO->getNombre());
				$oDatoVista->setDato('{margen}', $oArticuloVO->getMargen());
				$oDatoVista->setDato('{costo}', $oArticuloVO->getCosto());
				$oDatoVista->setDato('{precio}', $oArticuloVO->getPrecio());
				$oDatoVista->setDato('{fechaPrecio}', $oArticuloVO->getFechaPrecio());
				*/
				break;
				# ----> acción por Defecto (ninguna acción seleccionada)
			default:
					
				break;
					
		}
		
		// Muestra la vista html
		$this->mostrarHtml($oDatoVista, $oCargarVista);
		
		}
		
	/**
	 * Nos permite mostrar la vista html renderizada por
	 * la clase MotorVista
	 * @param object $oDatoVista
	 * @param object $oCargarVista
	 */
	private function mostrarHtml($oDatoVista, $oCargarVista)
	{
		// instancio el motor de la vista y muestro la vista
		$oMotorVista = new MotorVista($oDatoVista->getAllDatos(), $oCargarVista->getAllCargas());
		$oMotorVista->mostrarVista();		
	}
}
?>