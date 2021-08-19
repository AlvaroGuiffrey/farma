<?php
/**
 * Archivo de control de ventana del módulo recibido.
 *
 * Archivo de control de ventana del módulo recibido.
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
 * Clase control de ventana del módulo recibido.
 *
 * Clase control de ventana del módulo recibido que permite realizar
 * operaciones para ver y editar un artículo de la tabla recibidos
 * según la categoría asignada al usuario.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class RecibidoControlVentana
{
	#Propiedades
	private $_html;
	private $_accion;
	private $_items;
	private $_cantidad;
	private $_id;
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
		// Define las clases
		Clase::define('CargarVista');
		Clase::define('DatoVista');
		Clase::define('CargarMenu');
		Clase::define('MotorVista');
		Clase::define('RecibidoModelo');
		Clase::define('RecibidoTabla');
		Clase::define('RecibidoDatos');
		Clase::define('ProveedorModelo');
		Clase::define('ProveedorSelect');
		// Chequea login
		$oLoginControl = new LoginControl();
		$oLoginControl->chequearLogin($oLoginVO);
		$this->_accion = $accion;
		$this->_id = $id;
		$this->accionControl($oLoginVO);
	}

	/**
	 * Nos permite ejecutar las acciones para ventana del módulo
	 * recibido del sistema, de acuerdo a la categoría del usuario.
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
		$oDatoVista->setDato('{tituloPagina}', 'Comprobantes Recibidos');

		// Alertas

		// Carga el contenido html y datos según la acción
		$oRecibidoVO = new RecibidoVO();
		$oRecibidoModelo = new RecibidoModelo();
		// Selector de acciones
		switch ($this->_accion){
			# ----> acción Editar
			case 'Editar':
				$oRecibidoVO->setId($this->_id);
				$oRecibidoModelo->find($oRecibidoVO);
				$this->_cantidad = $oRecibidoModelo->getCantidad();
					
				// carga el contenido html
				$oCargarVista->setCarga('contenido', '/includes/vista/editarVentana.html');
				$oCargarVista->setCarga('datos', '/modulos/recibido/vista/editarDatos.html');
				// ingresa los datos a representar en el Panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Editar Comprobante Recibido');
					
				// ingresa los datos a representar en el contenido de la vista
				RecibidoDatos::cargaDatos($oRecibidoVO, $oDatoVista, $this->_accion);
				// arma el select de datos de la tabla proveedores a representar
				$oProveedorVO = new ProveedorVO();
				$oProveedorModelo = new ProveedorModelo();
				$this->_items = $oProveedorModelo->findAll();
				$this->_cantidad = $oProveedorModelo->getCantidad();
				$this->_idProveedor = 0;
				$oDatoVista->setDato('{cantidadProveedor}', $this->_cantidad);
				ProveedorSelect::armaSelect($this->_cantidad, $this->_items, $this->_accion, $this->_idProveedor);
				$oCargarVista->setCarga('selectProveedor', '/modulos/recibido/selectProveedor.html');
				break;
					
			# ----> acción Confirmar Editar
			case 'ConfirmarE':
				$oRecibidoVO->setId($_POST['id']);
				$oRecibidoVO->setComprobante($_POST['comprobante']);
				$oRecibidoVO->setFecha($_POST['fecha']);
				$oRecibidoVO->setIdProveedor($_POST['proveedor']);
				$oRecibidoVO->setGravado($_POST['gravado']);
				$oRecibidoVO->setExento($_POST['exento']);
				$oRecibidoVO->setRetencionDgi($_POST['retencionDgi']);
				$oRecibidoVO->setPercepcionDgi($_POST['percepcionDgi']);
				$oRecibidoVO->setRetencionRenta($_POST['retencionRenta']);
				$oRecibidoVO->setPercepcionRenta($_POST['percepcionRenta']);
				$oRecibidoVO->setOtros($_POST['otros']);
				$oRecibidoVO->setIva($_POST['iva']);
				$oRecibidoVO->setTotal($_POST['total']);
				$oRecibidoVO->setComentario($_POST['comentario']);
				$total = $_POST['gravado']+$_POST['exento']+$_POST['retencionDgi']+$_POST['percepcionDgi']+$_POST['retencionRenta']+$_POST['percepcionRenta']+$_POST['otros']+$_POST['iva'];
				if ($_POST['consistencia']=="SI"){
					$oRecibidoVO->setConsistencia(1);
				}else{
					$oRecibidoVO->setConsistencia(0);
				}
				$oRecibidoVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
				$this->_date = date('Y-m-d H:i:s');
				$oRecibidoVO->setFechaAct($this->_date);
				// Verifica suma de importes
				if ($_POST['total'] == $total){
					$oRecibidoModelo->update($oRecibidoVO);
					$this->_cantidad = $oRecibidoModelo->getCantidad();
				}else{
					$this->_cantidad = 0;
				}

				// carga el contenido html
				$oCargarVista->setCarga('contenido', '/includes/vista/verVentana.html');
				$oCargarVista->setCarga('datos', '/modulos/recibido/vista/verDatos.html');
				// ingresa los datos a representar en el Panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Ver Comprobante Recibido Editado');
				$oDatoVista->setDato('{cantidad}', $this->_cantidad);
				// ingresa los datos a representar en el contenido de la vista
				RecibidoDatos::cargaDatos($oRecibidoVO, $oDatoVista, $this->_accion);
				// ingresa otros datos de acuerdo al resultado de la consulta
				if ($this->_cantidad==1){
					// carga el contenido html
					$oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
					// ingresa los datos a representar en el Panel de la vista
					$oDatoVista->setDato('{informacion}', '<p>Editó un artículo con exito!!!.</p><p>Seleccione alguna acción para los artículos,</p><p>o alguna opción del menú.');
					// ingresa los datos a representar en las alertas de la vista
					$oDatoVista->setDato('{alertaSuceso}', 'Se editó el artículo con EXITO!!!');
				}else{
				 // carga el contenido html
				 $oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
				 // ingresa los datos a representar en el panel de la vista
				 $oDatoVista->setDato('{informacion}', '<p>Precuación, falló la edición del artículo.</p><p>Vuelva a intentar, o seleccione alguna acción</p><p>u otra opción del menú.');
				 // ingresa los datos a representar en las alertas de la vista
				 $oDatoVista->setDato('{alertaPeligro}', 'PELIGRO - No se editaron los datos');
				}

				break;
			# ----> acción Ver
			case 'Ver':
				$oRecibidoVO->setId($this->_id);
				$oRecibidoModelo->find($oRecibidoVO);
				$this->_cantidad = $oRecibidoModelo->getCantidad();
				// carga el contenido html
				$oCargarVista->setCarga('contenido', '/includes/vista/verVentana.html');
				$oCargarVista->setCarga('datos', '/modulos/recibido/vista/verDatos.html');
				// ingresa los datos a representar en el Panel de la vista
				$oDatoVista->setDato('{tituloPanel}', 'Ver Comprobante Recibido');
				// ingresa los datos a representar en el contenido de la vista
				RecibidoDatos::cargaDatos($oRecibidoVO, $oDatoVista, $this->_accion);
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