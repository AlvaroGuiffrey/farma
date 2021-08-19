<?php
/*
 * Conexión con la DB MySql de PLEX (en forma nativa)
 */
class DataBasePlex  // Patron Singleton
{
    private static $instance;
    private static $server = 'serversrl';  // tu servidor
    private static $username = 'consulta'; // tu usuario
    private static $passwd = 'readonly';   // tu contraseña
    private static $db_name = 'plex';      // tu base de datos

    private function __construct(){}

    public static function getInstance()
    {
        if (!isset(self::$instance)){
            self::$instance = new mysqli(self::$server, self::$username, self::$passwd, self::$db_name);

        }

        return self::$instance;
    }

    public static function closeInstance()
    {
        return self::$instance->close();

    }

    private function __clone(){}
}
?>
