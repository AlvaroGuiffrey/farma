<?php
/*
 * Conexión con la DB MySql de PLEX (en forma nativa) 
 */
class DataBasePlexAnt  // Patron Singleton
{
	private static $instance;
	private static $server = 'serversrl'; // tu servidor
	private static $username = 'consulta';    // tu usuario
	private static $passwd = 'readonly';  // tu contraseña
	private static $db_name = 'plex';    // tu base de datos
	
	private function __construct(){}

	public static function getInstance()
	{
		if (!isset(self::$instance)){
			self::$instance = mysql_connect(self::$server, self::$username, self::$passwd);
			mysql_select_db(self::$db_name, self::$instance);
		}

		return self::$instance;
	}

	public static function closeInstance()
	{
		mysql_close(self::$instance);	
	}
	
	private function __clone(){}
}
