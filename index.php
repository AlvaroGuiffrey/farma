<?php
/**
 * Archivo de la clase indice de la aplicación.
 *
 * Archivo de la clase de inicio de la aplicación que nos lleva al login.
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

/**
 * Clase index del inicio de la aplicación.
 *
 * Clase de inicio de la aplicación que nos lleva al login
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
	 * llevando los datos del token de seguridad
	 * y agrega el directorio a session
	 */
	public static function execute()
	{
		// Se agrega el tokenInfo a la sesión
		$token = md5(uniqid(rand(), true));
		$_SESSION['token'] = $token;
		// Se agrega del directorio a la sesión
		$_SESSION['dir'] = substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT']));
		// Voy al index de login
		header("Location:".$_SESSION['dir']."/modulos/login/index.php?token=$token")
		or die(print("No pude cargar script, consulte con su Administrador"));
	}
}

Index::execute();

?>