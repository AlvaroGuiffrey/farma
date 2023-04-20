<?php
// Se definen las clases necesarias
Clase::define('ArticuloDActiveRecord');

class ArticuloDModelo extends ArticuloDActiveRecord
{
	#propiedades
	public $cantidad;
	public $lastId;
	private $_opcionListado;
	private $_marca;
	private $_rubro;
	private $_estado;
	private $_orden;
	private $_idProveedor;

	#métodos

	/**
	 * Nos permite obtener la cantidad de renglones de la consulta.
	 *
	 * @return integer
	 */
	public function getCantidad()
	{
		return $this->cantidad;
	}

	/**
	 * Nos permite obtener el identificador del último rubro actualizado.
	 *
	 * @return integer
	 */
	public function getLastId()
	{
		return $this->lastId;
	}



	/**
	 * Nos permite obtener la cantidad de articulos de la tabla
	 *
	 */
	public function count()
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT count(*) FROM articulos_d");
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$this->cantidad = $sth->fetchColumn();
			$dbh=null;

		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

		/**
	 * Nos permite obtener un array de la consulta de todos los articulos
	 * para listado con límite
	 *
	 * @return $aArticulos
	 */
	public function findAllLimite($renglonDesde, $limiteRenglones)
	{
		try {
			$this->_renglonDesde = $renglonDesde;
			$this->_limiteRenglones = $limiteRenglones;
			$this->_opcionListado = "SELECT * FROM articulos_d LIMIT ".$this->_renglonDesde.", ".$this->_limiteRenglones;
			$aArticulos = array();
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare($this->_opcionListado);
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$aArticulos = $sth->fetchAll();
			$this->cantidad = $sth->rowCount();
			$dbh=null;
			return $aArticulos;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

}
?>
