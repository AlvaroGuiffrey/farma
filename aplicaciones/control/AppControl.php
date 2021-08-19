<?php 
/**
 * Archivo de la clase control de las aplicaciones.
 *
 * Archivo de la clase control de las aplicaciones del sistema.
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
 * Clase control de las aplicaciones.
 *
 * Clase control de las aplicaciones del sistema 
 * que permite al usuario utilizar las aplicaciones
 * según la categoría asignada.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class AppControl
{
	#Propiedades
	private $_html;

	#Métodos
	/**
	 * Nos permite acceder a las aplicaciones del
	 * sistema de acuerdo a la categoría del
	 * usuario.
	 */
	public function inicio($oLoginVO)
	{
		// Chequea login
		$oLoginControl = new LoginControl();
		$oLoginControl->chequearLogin($oLoginVO);
		// Carga los archivos html para la vista
		Clase::define('CargarVista');
		$oCargarVista = new CargarVista();
		$oCargarVista->setCarga('pagina', '/includes/vista/pagina.html');
		$oCargarVista->setCarga('botones', '/includes/vista/botonesFooter.html');
		$oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
		// Carga el menú de la vista según la categoría del usuario
		Clase::define('CargarMenu');
		$oCargarVista->setCarga('menu', CargarMenu::selectMenu($oLoginVO->getCategoria()));
		// Ingresa los datos a representar en el html de la vista
		Clase::define('DatoVista');
		$oDatoVista = new DatoVista();
		$oDatoVista->setDato('{tituloPagina}', 'Menú');
		$oDatoVista->setDato('{usuario}', $oLoginVO->getAlias());
		$oDatoVista->setDato('{tituloPanel}', 'Menú del Usuario');
		// Menú

		// Alertas
		$oDatoVista->setDato('{alertaSuceso}', 'Seleccione una opción del menú');
		// Información del footer
		$oDatoVista->setDato('{informacion}', 'Seleccione una opción del menú'); 
		// instancio el motor de la vista y muestro la vista
		Clase::define('MotorVista');
		$oMotorVista = new MotorVista($oDatoVista->getAllDatos(), $oCargarVista->getAllCargas());
		$oMotorVista->mostrarVista();
	}
}
?>