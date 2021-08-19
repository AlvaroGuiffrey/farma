<?php
// Se definen las clases necesarias 
Clase::define('DataBase');
Clase::define('ActiveRecordInterface');
Clase::define('RubroVO');

class RubroActiveRecord implements ActiveRecord
{
	#métodos CRUD implementados en la interface ActiveRecord
	
	/**
	 * Nos permite obtener un array de la consulta de todos los rubros
	 *
	 * @return $aRubros
	 */
	public function findAll()
	{
		try{
			$aRubros = array();
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM rubros ORDER BY nombre");
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$aRubros = $sth->fetchAll();
			$this->cantidad = $sth->rowCount();
			$dbh=null;
			return $aRubros;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	/**
	 * Nos permite obtener un objeto de la clase VO de la tabla
	 * rubro buscado por el id.
	 *
	 * @param $oRubroVO integer $id
	 * @return $oRubroVO
	 */
	public function find($oRubroVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM rubros WHERE id=?");
			$id = $oRubroVO->getId();
			$sth->bindParam(1, $id);
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$fila = $sth->fetchObject();
			$this->cantidad = $sth->rowCount();
			$oRubroVO->setId($fila->id);
			$oRubroVO->setNombre($fila->nombre);
			$oRubroVO->setComentario($fila->comentario);
			$oRubroVO->setIdUsuarioAct($fila->id_usuario_act);
			$oRubroVO->setFechaAct($fila->fecha_act);
			$dbh=null; 
			return $oRubroVO;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	/**
	 * Nos permite insertar un rubro en la tabla rubros
	 *
	 * @param $oRubroVO 
	 */
	public function insert($oRubroVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("INSERT INTO rubros(nombre, comentario, id_usuario_act, fecha_act) VALUES (?, ?, ?, ?)");
			$nombre = $oRubroVO->getNombre();
			$sth->bindParam(1, $nombre);
			$comentario = $oRubroVO->getComentario();
			$sth->bindParam(2, $comentario);
			$idUsuarioAct = $oRubroVO->getIdUsuarioAct();
			$sth->bindParam(3, $idUsuarioAct);
			$fechaAct = $oRubroVO->getFechaAct();
			$sth->bindParam(4, $fechaAct);
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
	 * Nos permite actualizar un registro en la tabla rubros
	 *
	 * @param $oRubroVO
	 */
	public function update($oRubroVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("UPDATE rubros SET nombre=?, comentario=?, id_usuario_act=?, fecha_act=? WHERE id=?");
			$nombre = $oRubroVO->getNombre();
			$sth->bindParam(1, $nombre);
			$comentario = $oRubroVO->getComentario();
			$sth->bindParam(2, $comentario);
			$idUsuarioAct = $oRubroVO->getIdUsuarioAct();
			$sth->bindParam(3, $idUsuarioAct);
			$fechaAct = $oRubroVO->getFechaAct();
			$sth->bindParam(4, $fechaAct);
			$id = $oRubroVO->getId();
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

	/**
	 * Nos permite eliminar registro en la tabla rubros
	 *
	 * @param $oRubroVO
	 */
	public function delete($oRubroVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("DELETE FROM rubros WHERE id=?)");
			$id = $oRubroVO->getId();
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