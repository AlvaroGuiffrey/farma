<?php
/*
 *  Conexión con la DB en localhost
 */
class DataBase  // Patron Singleton
{
	private static $instance;
	private static $dsn = 'mysql:dbname=farma;localhost';
	private static $username = 'root';
	private static $passwd = '';

	private function __construct(){}

	public static function getInstance()
	{
		if (!isset(self::$instance)){
			self::$instance = new PDO(self::$dsn, self::$username, self::$passwd);
		}

		return self::$instance;
	}

	private function __clone(){}
}