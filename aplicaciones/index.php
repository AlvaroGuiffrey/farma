<?php
/**
 * Archivo de la clase indice de las aplicaciones.
 *
 * Archivo de la clase de inicio de las aplicaciones del sistema.
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
 * @link       http:
 * @since      File available since Release 1.0
 */
// Inicia o reanuda la sesión
session_start();

// Carga las clases necesarias
require_once $_SERVER['DOCUMENT_ROOT'].$_SESSION['dir'].'/includes/control/Clase.php';

/**
 * Clase index del inicio de las aplicaciones.
 *
 * Clase de inicio de las aplicaciones del sistema.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http:
 * @since      Class available since Release 1.0
 */
class Index
{
	/**
	 * Nos permite acceder al control de las aplicaciones 
	 * del sistema.
	 * Previamente chequea el login del usuario
	 */
	public static function execute()
	{
		// Se definen las clases necesarias
		Clase::define('LoginControl');
		Clase::define('AppControl');

		// Instancia las clases 
		$oLoginControl = new LoginControl();
		$oLoginVO = new LoginVO();
		$oAppControl = new AppControl();

		// Carga y chequea el login del usuario
		$oLoginControl->cargarUsuario($oLoginVO);
		$oLoginControl->chequearLogin($oLoginVO); 

		// Inicia la aplicación
		$oAppControl->inicio($oLoginVO);
	}
}

Index::execute();

?>