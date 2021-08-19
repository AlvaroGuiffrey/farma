<?php
/**
 * Archivo de la clase indice del módulo partida.
 *
 * Archivo de la clase de inicio del módulo partida.
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

// Se cargan las clases necesarias
require_once $_SERVER['DOCUMENT_ROOT'].$_SESSION['dir'].'/includes/control/Clase.php';

/**
 * Clase index del módulo partida.
 *
 * Clase de inicio del módulo partida.
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
	 * Nos permite acceder al control del módulo partida.
	 * Previamente chequea el login del usuario.
	 */
	public static function execute()
	{
		// Se definen las clases necesarias
		Clase::define('LoginControl');
		Clase::define('PartidaControlR');
		// Se instancian las clases
		$oLoginVO = new LoginVO();
		$oLoginControl = new LoginControl();

		// Carga usuario y chequea login
		$oLoginControl->cargarUsuario($oLoginVO);
		$oLoginControl->chequearLogin($oLoginVO);
		
		// Recibe la acción peticionada por POST o GET
		if (isset($_REQUEST['accion'])){
			$accion = $_REQUEST['accion'];
		} else {
			$accion = 'Iniciar';
		}

		// Si viene de recibido
		if (isset($_POST['bt_agregar_renglon'])){
			$accion = 'BuscarA';
			$id = $_POST['bt_agregar_renglon'];
		}
	 /**
	 * Inicia el control del módulo.
	 * @param $oLoginVO (objeto de la clase LoginVO con los datos del usuario)
	 * @param string $accion (acción a ejecutar por el control del módulo)
	 * @param int $id (índice de la tabla recibidos) 
	 */
		$oPartidaControlR = new PartidaControlR();
		$oPartidaControlR->inicio($oLoginVO, $accion, $id);

	}
}

Index::execute();
?>