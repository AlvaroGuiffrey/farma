<?php
// Se definen las clases necesarias 
Clase::define('DataBase');
Clase::define('ActiveRecordInterface');
Clase::define('MarcaVO');

class MarcaActiveRecord implements ActiveRecord
{
	#métodos CRUD implementados en la interface ActiveRecord
	public function findAll() 
	{
		try{
			$aMarcas = array();
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM marcas ORDER BY nombre");
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$aMarcas = $sth->fetchAll();
			$this->cantidad = $sth->rowCount();
			$dbh=null;
			return $aMarcas;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	public function find($oMarcaVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM marcas WHERE id=?");
			$id = $oMarcaVO->getId();
			$sth->bindParam(1, $id);
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$fila = $sth->fetchObject();
			$this->cantidad = $sth->rowCount();
			$oMarcaVO->setId($fila->id); 
			$oMarcaVO->setNombre($fila->nombre);
			$oMarcaVO->setComentario($fila->comentario);
			$oMarcaVO->setIdUsuarioAct($fila->id_usuario_act);
			$oMarcaVO->setFechaAct($fila->fecha_act);
			$dbh=null;
			return $oMarcaVO;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	public function insert($oMarcaVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("INSERT INTO marcas(id, nombre, comentario, id_usuario_act, fecha_act) VALUES (?, ?, ?, ?, ?)");
			$id = $oMarcaVO->getId();
			$sth->bindParam(1, $id);
			$nombre = $oMarcaVO->getNombre();
			$sth->bindParam(2, $nombre);
			$comentario = $oMarcaVO->getComentario();
			$sth->bindParam(3, $comentario);
			$idUsuarioAct = $oMarcaVO->getIdUsuarioAct();
			$sth->bindParam(4, $idUsuarioAct);
			$fechaAct = $oMarcaVO->getFechaAct();
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

	public function update($oMarcaVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("UPDATE marcas SET nombre=?, comentario=?, id_usuario_act=?, fecha_act=? WHERE id=?");
			$nombre = $oMarcaVO->getNombre();
			$sth->bindParam(1, $nombre);
			$comentario = $oMarcaVO->getComentario();
			$sth->bindParam(2, $comentario);
			$idUsuarioAct = $oMarcaVO->getIdUsuarioAct();
			$sth->bindParam(3, $idUsuarioAct);
			$fechaAct = $oMarcaVO->getFechaAct();
			$sth->bindParam(4, $fechaAct);
			$id = $oMarcaVO->getId();
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

	public function delete($oMarcaVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("DELETE FROM marcas WHERE id=?)");
			$id = $oMarcaVO->getId();
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