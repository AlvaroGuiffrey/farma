<?php
/**
 * Archivo de la clase indice de backup.
 *
 * Archivo de la clase de inicio de backup de tablas del inventario.
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
 * Clase index de backup.
 *
 * Clase de inicio de backup de tablas del inventario.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class IndexBackupI
{
	/**
	 * Nos permite acceder al control de backup de tablas del inventario.
	 * Previamente chequea el login del usuario.
	 */
	public static function execute()
	{
		// Se definen las clases necesarias
		Clase::define('LoginControl');
		Clase::define('BackupControlI');
		
		// Instancia las clases
		$oLoginVO = new LoginVO();
		$oLoginControl = new LoginControl();
		$oBackupControlI = new BackupControlI();
			
		// Carga usuario y chequea login
		$oLoginControl->cargarUsuario($oLoginVO);
		$oLoginControl->chequearLogin($oLoginVO);
				
		// Inicia el control del módulo
		$oBackupControlI->inicio($oLoginVO, $_GET['accion'], $_GET['id']);
	}
}

IndexBackupI::execute();
?>