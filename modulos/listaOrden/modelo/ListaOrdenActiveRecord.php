<?php
// Se definen las clases necesarias
Clase::define('DataBase');
Clase::define('ActiveRecordInterface');
Clase::define('ListaOrdenVO');

class ListaOrdenActiveRecord implements ActiveRecord
{
	#métodos CRUD implementados en la interface ActiveRecord
	public function findAll()
	{
		try{
			$aListasOrden = array();
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM listas_orden");
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$aListasOrden = $sth->fetchAll();
			$this->cantidad = $sth->rowCount();
			$dbh=null;
			return $aListasOrden;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	public function find($oListaOrdenVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM listas_orden WHERE id=?");
			$id = $oListaOrdenVO->getId();
			$sth->bindParam(1, $id);
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$fila = $sth->fetchObject();
			$this->cantidad = $sth->rowCount();
			$oListaOrdenVO->setId($fila->id);
			$oListaOrdenVO->setNombre($fila->nombre);
			$oListaOrdenVO->setIdProveedor($fila->id_proveedor);
			$oListaOrdenVO->setIdUsuarioAct($fila->id_usuario_act);
			$oListaOrdenVO->setFechaAct($fila->fecha_act);
			$dbh=null;
			return $oListaOrdenVO;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	public function insert($oListaOrdenVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("INSERT INTO listas_orden(id, nombre, id_proveedor, id_usuario_act, fecha_act) VALUES (?, ?, ?, ?, ?)");
			$id = $oListaOrdenVO->getId();
			$sth->bindParam(1, $id);
			$nombre = $oListaOrdenVO->getNombre();
			$sth->bindParam(2, $nombre);
			$idProveedor = $oListaOrdenVO->getIdProveedor();
			$sth->bindParam(3, $idProveedor);
			$idUsuarioAct = $oListaOrdenVO->getIdUsuarioAct();
			$sth->bindParam(4, $idUsuarioAct);
			$fechaAct = $oListaOrdenVO->getFechaAct();
			$sth->bindParam(5, $fechaAct);
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

	public function update($oListaOrdenVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("UPDATE listas_orden SET nombre=?, lista_orden=?, id_usuario_act=?, fecha_act=? WHERE id=?");
			$nombre = $oListaOrdenVO->getNombre();
			$sth->bindParam(1, $nombre);
			$idUsuario = $oListaOrdenVO->getIdUsuario();
			$sth->bindParam(2, $idUsuario);
			$idUsuarioAct = $oListaOrdenVO->getIdUsuarioAct();
			$sth->bindParam(3, $idUsuarioAct);
			$fechaAct = $oListaOrdenVO->getFechaAct();
			$sth->bindParam(4, $fechaAct);
			$id = $oListaOrdenVO->getId();
			$sth->bindParam(5, $id);
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

	public function delete($oListaOrdenVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("DELETE FROM listas_orden WHERE id=?)");
			$id = $oListaOrdenVO->getId();
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