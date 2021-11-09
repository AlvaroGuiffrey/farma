<?php
/**
 * Archivo de la clase indice del módulo artículos.
 *
 * Archivo de la clase de inicio del módulo artículos
 * que nos permite cargar el control para comparar precios máximos de los
 * artículos con las listas de los proveedores.
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
// Inicia o reanuda la sesión
session_start();

// Carga las clases necesarias
require_once $_SERVER['DOCUMENT_ROOT'].$_SESSION['dir'].'/includes/control/Clase.php';

/**
 * Clase index del módulo artículo.
 *
 * Clase de inicio del módulo artículo que carga el control
 * para probar actualizar precios.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class IndexArticuloPM
{
	/**
	 * Nos permite acceder al control del módulo artículo para actualizar
	 * precios con las listas de los proveedores de referencia.
	 * Previamente chequea el login del usuario.
	 */
	public static function execute()
	{
		// Se definen las clases necesarias
		Clase::define('LoginControl');
		Clase::define('ArticuloControlPM');
		// Instancia las clases
		$oLoginVO = new LoginVO();
		$oLoginControl = new LoginControl();
		$oArticuloControlPM = new ArticuloControlPM();

		// Carga usuario y chequea login
		$oLoginControl->cargarUsuario($oLoginVO);
		$oLoginControl->chequearLogin($oLoginVO);

		// Recibe la acción peticionada por GET
		if (isset($_GET['accion'])){
			$accion = $_GET['accion'];
		} else {
			$accion = 'Iniciar';
		}

		// Inicia el control del módulo
		$oArticuloControlPM->inicio($oLoginVO, $accion);
	}
}

IndexArticuloPM::execute();
?>
