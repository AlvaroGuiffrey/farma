<?php
// Se definen las clases necesarias
Clase::define('DataBase');
Clase::define('ActiveRecordInterface');
Clase::define('AfipResponsablesVO');

class AfipResponsablesActiveRecord implements ActiveRecord
{
	#métodos CRUD implementados en la interface ActiveRecord

	/**
	 * Nos permite obtener un array de la consulta de todos las categorías
	 * de responsables asignada por AFIP
	 *
	 * @return $aAfipResponsables
	 */
	public function findAll()
	{
		try{
			$aAfipResponsables = array();
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM afip_responsables ORDER BY codigo");
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$aAfipResponsables = $sth->fetchAll();
			$this->cantidad = $sth->rowCount();
			$dbh=null;
			return $aAfipResponsables;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	/**
	 * Nos permite obtener un objeto de la clase VO de la tabla
	 * afip_responsables buscado por el codigo.
	 *
	 * @param $oAfipResponsablesVO integer $codigo
	 * @return $oAfipResponsablesVO
	 */
	public function find($oAfipResponsablesVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM afip_responsables WHERE codigo=?");
			$codigo = $oAfipResponsablesVO->getCodigo();
			$sth->bindParam(1, $codigo);
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$fila = $sth->fetchObject();
			$this->cantidad = $sth->rowCount();
			$oAfipResponsablesVO->setCodigo($fila->codigo);
			$oAfipResponsablesVO->setDescripcion($fila->descripcion);
			$dbh=null;
			return $oAfipResponsablesVO;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	/**
	 * Nos permite insertar un afip_responsables en la tabla afip_responsabless
	 *
	 * @param $oAfipResponsablesVO
	 */
	public function insert($oAfipResponsablesVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("INSERT INTO afip_responsabless(codigo, descripcion) VALUES (?, ?)");
			$codigo = $oAfipResponsablesVO->getCodigo();
			$sth->bindParam(1, $codigo);
			$descripcion = $oAfipResponsablesVO->getDescripcion();
			$sth->bindParam(2, $descripcion);
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar insertar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$this->cantidad = $sth->rowCount();
			$this->lastId = $dbh->lastInsertId();
			$dbh=null;
			return true;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	/**
	 * Nos permite actualizar un registro en la tabla afip_responsabless
	 *
	 * @param $oAfipResponsablesVO
	 */
	public function update($oAfipResponsablesVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("UPDATE afip_responsabless SET descripcion=? WHERE codigo=?");
			$descripcion = $oAfipResponsablesVO->getDescripcion();
			$sth->bindParam(1, $descripcion);
			$codigo = $oAfipResponsablesVO->getCodigo();
			$sth->bindParam(2, $codigo);
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar insertar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$this->cantidad = $sth->rowCount();
			$dbh=null;
			return true;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	/**
	 * Nos permite eliminar registro en la tabla afip_responsabless
	 *
	 * @param $oAfipResponsablesVO
	 */
	public function delete($oAfipResponsablesVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("DELETE FROM afip_responsables WHERE codigo=?)");
			$codigo = $oAfipResponsablesVO->getCodigo();
			$sth->bindParam(1, $codigo);
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar insertar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$this->cantidad = $sth->rowCount();
			$dbh=null;
			return true;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

}
?>