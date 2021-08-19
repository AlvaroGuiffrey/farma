<?php
/**
 * Archivo de la clase cargar menú del includes.
 *
 * Archivo de la clase cargar menú del includes del sistema.
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

// Carga las clases necesarias

/**
 * Clase cargar menú del includes.
 *
 * Clase cargar menú del includes del sistema
 * que permite cargar el menú de las aplicaciones
 * según la categoría asignada.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class CargarMenu
{
	# Propiedades

	# Métodos
	static  function selectMenu($categoria)
	{
		switch ($categoria){
			case 1:
				$menu = '/aplicaciones/vista/menuAdmin.html';
				break;
			case 2:
				$menu = '/aplicaciones/vista/menuSupervisor.html';
				break;
			case 3:
				$menu = '/aplicaciones/vista/menuOperador.html';
				break;
			default;
				// Genero token y voy al login si categoría de usuario no corresponde
				$token = md5(uniqid(rand(), true));
				$_SESSION['token'] = $token;
				header("Location:".$_SESSION['dir']."/modulos/login/index.php?token=$token") or die(print("No pude cargar script, consulte con su Administrador"));
			break;
		}
		return $menu;
	}
}
?>