<?php
/**
 * Archivo de la clase ValueObject.
 *
 * Archivo de la clase DataBaseBackupVO que nos permite manejar las variables
 * necesarias para persistir por el mètodo PDO sobre bases de datos utilizadas 
 * como backup del sistema.
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
 * @copyright  Copyright (c) 2015 Alvaro A. Guiffrey (http://www.alvaroguiffrey.com.ar)
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      File available since Release 1.0
 */

/**
 * Clase que nos permite manejar variables.
 *
 * Clase que nos permite manejar variables necesarias para
 * persistir por el método PDO sobre bases de datos que 
 * utilizaremos de backup del sistema.
 *
 * @copyright  Copyright (c) 2015 Alvaro A. Guiffrey (http://www.alvaroguiffrey.com.ar)
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class DataBaseBackupVO
{
	#Propiedades
	private $_server;
	private $_dbname;
	private $_username;
	private $_passwd;
	

	#Métodos
	/**
	* Nos permite obtener el servidor donde recide la DB.
	*
	* @return string
	*/
	public function getServer()
	{
		return $this->_server;
	}

	/**
	 * Nos permite obtener el nombre de la DB.
	 *
	 * @return string
	 */
	public function getDBName()
	{
		return $this->_dbname;
	}

	/**
	 * Nos permite obtener el nombre del usuario.
	 *
	 * @return string
	 */
	public function getUserName()
	{
		return $this->_username;
	}

	/**
	 * Nos permite obtener la contraseña del usuario.
	 *
	 * @return string
	 */
	public function getPassword()
	{
		return $this->_passwd;
	}


	/**
	 * Nos permite establecer el nombre del servidor.
	 *
	 * @param string $server
	 */
	public function setServer($server)
	{
		$this->_server = $server;
	}

	/**
	 * Nos permite establecer el nombre de la DB.
	 *
	 * @param string $dbname
	 */
	public function setDBName($dbname)
	{
		$this->_dbname = $dbname;
	}

	/**
	 * Nos permite establecer el nombre del usuario.
	 *
	 * @param string $username
	 */
	public function setUserName($username)
	{
		$this->_username = $username;
	}

	/**
	 * Nos permite establecer la contraseña del usuario.
	 *
	 * @param string $passwd
	 */
	public function setPasswd($passwd)
	{
		$this->_passwd = $passwd;
	}

}
?>