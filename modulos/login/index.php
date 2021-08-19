<?php
/**
 * Archivo de la clase indice del módulo login.
 *
 * Archivo de la clase de inicio del módulo login que nos lleva al login.
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
 * Clase index del inicio del módulo login.
 *
 * Clase de inicio del módulo login que nos lleva al login
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
*/
class Index
{
	/**
	 * Nos permite acceder al login de la aplicación
	 * cuando ingresamos por primera vez
	 * llevando los datos del token de seguridad y
	 * realiza el chequeo del login para enviarnos
	 * a aplicaciones
	 */
	public static function execute()
	{
		Clase::define('LoginControl');
		// Instancia la clase Control
		$oLoginControl = new LoginControl();
		// Ejecuta acciones en la clase control del módulo 
		if (isset($_GET['token']) && isset($_SESSION['token']) && $_GET['token'] == $_SESSION['token'] ){
			$oLoginControl->login();
		} else {
			// Si viene de boton SALIR del footer sale del sistema
			if ($_GET['accion']=='Salir'){
				echo "sali por aca<br>";
				$oLoginControl->logout();
			}else{
			// Cheque si existe un buen login
			$oLoginControl->chequearLogin();
			header("Location:".$_SESSION['dir']."/aplicaciones/index.php?")
			or die(print("No pude cargar script, consulte con su Administrador"));
			}
		} 
	}
}

Index::execute();

?>