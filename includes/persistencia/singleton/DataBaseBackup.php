<?php
/*
 *  Conexión con la DB de Backup
*/

// Se definen las clases necesarias
Clase::define('DataBaseBackupVO');

class DataBaseBackup  // Patron Singleton
{
	private $_instance;
	private $_dsn;
	private $_username;
	private $_passwd;

	
	public function getInstance($oDataBaseBackupVO)
	{
		if (!isset($this->_instance)){
			try {
				$this->_dsn = 'mysql:dbname='.$oDataBaseBackupVO->getDBName().';host='.$oDataBaseBackupVO->getServer();
				$this->_username = $oDataBaseBackupVO->getUserName();
				$this->_passwd = $oDataBaseBackupVO->getPassword();
				$this->_instance = new PDO($this->_dsn, $this->_username, $this->_passwd);
			} catch (PDOException $e) {
				print "¡ERROR!: ".$e->getMessage()."<br/>";
				die();
			}	
		}

		return $this->_instance;
	}

	private function __clone(){}
}
?>