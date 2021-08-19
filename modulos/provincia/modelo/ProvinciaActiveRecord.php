<?php
// Se cargan clases
require_once $_SERVER["DOCUMENT_ROOT"].$_SESSION['dir'].'/includes/persistencia/singleton/DataBase.php';
require_once $_SERVER["DOCUMENT_ROOT"].$_SESSION['dir'].'/includes/persistencia/interface/ActiveRecordInterface.php';
require_once $_SERVER["DOCUMENT_ROOT"].$_SESSION['dir'].'/modulos/provincia/modelo/ProvinciaVO.php';

class ProvinciaActiveRecord implements ActiveRecord
{
	#métodos CRUD implementados en la interface ActiveRecord
	public function findAll()
	{
		try{
			$aProvincias = array();
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM provincias ORDER BY nombre");
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$aProvincias = $sth->fetchAll();
			$this->cantidad = $sth->rowCount();
			$dbh=null;
			return $aProvincias;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	public function find($oProvinciaVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM provincias WHERE id=?");
			$id = $oProvinciaVO->getId();
			$sth->bindParam(1, $id);
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$fila = $sth->fetchObject();
			$this->cantidad = $sth->rowCount();
			$oProvinciaVO->setId($fila->id);
			$oProvinciaVO->setNumero($fila->numero);
			$oProvinciaVO->setNombre($fila->nombre);
			$oProvinciaVO->setLetra($fila->letra);
			$oProvinciaVO->setPais($fila->pais);
			$oProvinciaVO->setIdUsuarioAct($fila->id_usuario_act);
			$oProvinciaVO->setFechaAct($fila->fecha_act);
			$dbh=null;
			return $oProvinciaVO;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	public function insert($oProvinciaVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("INSERT INTO provincias(numero, nombre, letra, pais, id_usuario_act, fecha_act) VALUES (?, ?, ?, ?, ?, ?)");
			$numero = $oProvinciaVO->getNumero();
			$sth->bindParam(1, $numero);
			$nombre = $oProvinciaVO->getNombre();
			$sth->bindParam(2, $nombre);
			$letra = $oProvinciaVO->getLetra();
			$sth->bindParam(3, $letra);
			$pais = $oProvinciaVO->getPais();
			$sth->bindParam(4, $pais);
			$idUsuarioAct = $oProvinciaVO->getIdUsuarioAct();
			$sth->bindParam(5, $idUsuarioAct);
			$fechaAct = $oProvinciaVO->getFechaAct();
			$sth->bindParam(6, $fechaAct);
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

	public function update($oProvinciaVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("UPDATE provincias SET numero=?, nombre=?, letra=?, pais=?, id_usuario_act=?, fecha_act=? WHERE id=?");
			$numero = $oProvinciaVO->getNumero();
			$sth->bindParam(1, $numero);
			$nombre = $oProvinciaVO->getNombre();
			$sth->bindParam(2, $nombre);
			$letra = $oProvinciaVO->getLetra();
			$sth->bindParam(3, $letra);
			$pais = $oProvinciaVO->getPais();
			$sth->bindParam(4, $pais);
			$idUsuarioAct = $oProvinciaVO->getIdUsuarioAct();
			$sth->bindParam(5, $idUsuarioAct);
			$fechaAct = $oProvinciaVO->getFechaAct();
			$sth->bindParam(6, $fechaAct);
			$id = $oMarcaVO->getId();
			$sth->bindParam(7, $id);
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

	public function delete($oProvinciaVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("DELETE FROM provincias WHERE id=?)");
			$id = $oProvinciaVO->getId();
			$sth->bindParam(1, $id);
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