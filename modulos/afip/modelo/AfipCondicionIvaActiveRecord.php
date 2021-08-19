<?php
// Se definen las clases necesarias
Clase::define('DataBase');
Clase::define('ActiveRecordInterface');
Clase::define('AfipCondicionIvaVO');

class AfipCondicionIvaActiveRecord implements ActiveRecord
{
	#métodos CRUD implementados en la interface ActiveRecord

	/**
	 * Nos permite obtener un array de la consulta de todas las condiciones
	 * de IVA asignadas por AFIP
	 *
	 * @return $aAfipCondicionIva
	 */
	public function findAll()
	{
		try{
			$aAfipCondicionIva = array();
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM afip_condicion_iva ORDER BY codigo");
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$aAfipCondicionIva = $sth->fetchAll();
			$this->cantidad = $sth->rowCount();
			$dbh=null;
			return $aAfipCondicionIva;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	/**
	 * Nos permite obtener un objeto de la clase VO de la tabla
	 * afip_condicion_iva buscado por el codigo.
	 *
	 * @param $oAfipCondicionIvaVO integer $codigo
	 * @return $oAfipCondicionIvaVO
	 */
	public function find($oAfipCondicionIvaVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM afip_condicion_iva WHERE codigo=?");
			$codigo = $oAfipCondicionIvaVO->getCodigo();
			$sth->bindParam(1, $codigo);
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$fila = $sth->fetchObject();
			$this->cantidad = $sth->rowCount();
			$oAfipCondicionIvaVO->setCodigo($fila->codigo);
			$oAfipCondicionIvaVO->setDescripcion($fila->descripcion);
			$oAfipCondicionIvaVO->setAlicuota($fila->alicuota);
			$dbh=null;
			return $oAfipCondicionIvaVO;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	/**
	 * Nos permite insertar un registro en la tabla afip_condicion_iva
	 *
	 * @param $oAfipCondicionIvaVO
	 */
	public function insert($oAfipCondicionIvaVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("INSERT INTO afip_condicion_iva(codigo, descripcion, alicuota) VALUES (?, ?, ?)");
			$codigo = $oAfipCondicionIvaVO->getCodigo();
			$sth->bindParam(1, $codigo);
			$descripcion = $oAfipCondicionIvaVO->getDescripcion();
			$sth->bindParam(2, $descripcion);
			$alicuota = $oAfipCondicionIvaVO->getAlicuota();
			$sth->bindParam(3, $alicuota);
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
	 * Nos permite actualizar un registro en la tabla afip_condicion_iva
	 *
	 * @param $oAfipCondicionIvaVO
	 */
	public function update($oAfipCondicionIvaVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("UPDATE afip_condicion_iva SET descripcion=?, alicuota=? WHERE codigo=?");
			$descripcion = $oAfipCondicionIvaVO->getDescripcion();
			$sth->bindParam(1, $descripcion);
			$alicuota = $oAfipCondicionIvaVO->getAlicuota();
			$sth->bindParam(2, $alicuota);
			$codigo = $oAfipCondicionIvaVO->getCodigo();
			$sth->bindParam(3, $codigo);
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
	 * Nos permite eliminar registro en la tabla afip_condicion_iva
	 *
	 * @param $oAfipCondicionIvaVO
	 */
	public function delete($oAfipCondicionIvaVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("DELETE FROM afip_condicion_iva WHERE codigo=?)");
			$codigo = $oAfipCondicionIvaVO->getCodigo();
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