<?php
// Se definen las clases necesarias 
Clase::define('DataBase');
Clase::define('ActiveRecordInterface');
Clase::define('LocalidadVO'); 

class LocalidadActiveRecord implements ActiveRecord
{
	#métodos CRUD implementados en la interface ActiveRecord
	public function findAll()
	{
		try{
			$aLocalidades = array();
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM localidades ORDER BY nombre");
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$aLocalidades = $sth->fetchAll();
			$this->cantidad = $sth->rowCount();
			$dbh=null;
			return $aLocalidades;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	public function find($oLocalidadVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM localidades WHERE id=?");
			$id = $oLocalidadVO->getId();
			$sth->bindParam(1, $id);
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$fila = $sth->fetchObject();
			$this->cantidad = $sth->rowCount();
			$oLocalidadVO->setId($fila->id);
			$oLocalidadVO->setNombre($fila->nombre);
			$oLocalidadVO->setCodPostal($fila->cod_postal);
			$oLocalidadVO->setDepartamento($fila->departamento);
			$oLocalidadVO->setIdProvincia($fila->id_provincia);
			$oLocalidadVO->setIdUsuarioAct($fila->id_usuario_act);
			$oLocalidadVO->setFechaAct($fila->fecha_act);
			$dbh=null;
			return $oLocalidadVO;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	public function insert($oLocalidadVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("INSERT INTO localidades(nombre, cod_postal, departamento, id_provincia, id_usuario_act, fecha_act) VALUES (?, ?, ?, ?, ?, ?)");
			$nombre = $oLocalidadVO->getNombre();
			$sth->bindParam(1, $nombre);
			$codPostal = $oLocalidadVO->getCodPostal();
			$sth->bindParam(2, $codPostal);
			$departamento = $oLocalidadVO->getDepartamento();
			$sth->bindParam(3, $departamento);
			$idProvincia = $oLocalidadVO->getIdProvincia();
			$sth->bindParam(4, $idProvincia);
			$idUsuarioAct = $oLocalidadVO->getIdUsuarioAct();
			$sth->bindParam(5, $idUsuarioAct);
			$fechaAct = $oLocalidadVO->getFechaAct();
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

	public function update($oLocalidadVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("UPDATE localidades SET nombre=?, cod_postal=?, departamento=?, id_provincia=?, id_usuario_act=?, fecha_act=? WHERE id=?");
			$nombre = $oLocalidadVO->getNombre();
			$sth->bindParam(1, $nombre);
			$codPostal = $oLocalidadVO->getCodPostal();
			$sth->bindParam(2, $codPostal);
			$departamento = $oLocalidadVO->getDepartamento();
			$sth->bindParam(3, $departamento);
			$idProvincia = $oLocalidadVO->getIdProvincia();
			$sth->bindParam(4, $idProvincia);
			$idUsuarioAct = $oLocalidadVO->getIdUsuarioAct();
			$sth->bindParam(5, $idUsuarioAct);
			$fechaAct = $oLocalidadVO->getFechaAct();
			$sth->bindParam(6, $fechaAct);
			$id = $oLocalidadVO->getId();
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

	public function delete($oLocalidadVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("DELETE FROM localidades WHERE id=?)");
			$id = $oLocalidadVO->getId();
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