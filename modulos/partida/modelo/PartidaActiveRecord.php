<?php
// Se definen las clases necesarias 
Clase::define('DataBase');
Clase::define('ActiveRecordInterface');
Clase::define('PartidaVO');

class PartidaActiveRecord implements ActiveRecord
{
	#métodos CRUD implementados en la interface ActiveRecord

	/**
	 * Nos permite obtener un array de la consulta de todas las partidas
	 * ordenadas por id Recibidos
	 *
	 * @return $aPartidas
	 */
	public function findAll()
	{
		try{
			$aPartidas = array();
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM partidas ORDER BY id_recibido");
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$aPartidas = $sth->fetchAll();
			$this->cantidad = $sth->rowCount();
			$dbh=null;
			return $aPartidas;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	/**
	 * Nos permite obtener un objeto de la clase VO de la tabla
	 * partidas buscado por el id.
	 *
	 * @param $oPartidaVO integer $id
	 * @return $oPartidaVO
	 */
	public function find($oPartidaVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM partidas WHERE id=?");
			$id = $oPartidaVO->getId();
			$sth->bindParam(1, $id);
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$fila = $sth->fetchObject();
			$this->cantidad = $sth->rowCount();
			$oPartidaVO->setId($fila->id);
			$oPartidaVO->setIdArticulo($fila->id_articulo);
			$oPartidaVO->setIdRecibido($fila->id_recibido);
			$oPartidaVO->setFecha($fila->fecha);
			$oPartidaVO->setCantIngresada($fila->cant_ingresada);
			$oPartidaVO->setCosto($fila->costo);
			$oPartidaVO->setStock($fila->stock);
			$oPartidaVO->setIvaAlicuota($fila->iva_alicuota);
			$oPartidaVO->setComentario($fila->comentario);
			$oPartidaVO->setIdUsuarioAct($fila->id_usuario_act);
			$oPartidaVO->setFechaAct($fila->fecha_act);
			$dbh=null;
			return $oPartidaVO;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	/**
	 * Nos permite insertar una partida en la tabla partidas
	 *
	 * @param $oPartidaVO
	 */
	public function insert($oPartidaVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("INSERT INTO partidas
						(id_articulo, id_recibido, fecha, cant_ingresada, costo, stock, iva_alicuota, comentario, id_usuario_act, fecha_act)
					 	VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$idArticulo = $oPartidaVO->getIdArticulo();
			$sth->bindParam(1, $idArticulo);
			$idRecibido = $oPartidaVO->getIdRecibido();
			$sth->bindParam(2, $idRecibido);
			$fecha = $oPartidaVO->getFecha();
			$sth->bindParam(3, $fecha);
			$cantIngresada = $oPartidaVO->getCantIngresada();
			$sth->bindParam(4, $cantIngresada);
			$costo = $oPartidaVO->getCosto();
			$sth->bindParam(5, $costo);
			$stock = $oPartidaVO->getStock();
			$sth->bindParam(6, $stock);
			$ivaAlicuota = $oPartidaVO->getIvaAlicuota();
			$sth->bindParam(7, $ivaAlicuota);
			$comentario = $oPartidaVO->getComentario();
			$sth->bindParam(8, $comentario);
			$idUsuarioAct = $oPartidaVO->getIdUsuarioAct();
			$sth->bindParam(9, $idUsuarioAct);
			$fechaAct = $oPartidaVO->getFechaAct();
			$sth->bindParam(10, $fechaAct);
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
	 * Nos permite actualizar un registro en la tabla partidas
	 *
	 * @param $oPartidaVO
	 */
	public function update($oPartidaVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("UPDATE partidas
						SET id_articulo=?, id_recibido=?, fecha=?, cant_ingresada=?, costo=?, stock=?, iva_alicuota=?, comentario=?, id_usuario_act=?, fecha_act=?
						WHERE id=?");
			$idArticulo = $oPartidaVO->getIdArticulo();
			$sth->bindParam(1, $idArticulo);
			$idRecibido = $oPartidaVO->getIdRecibido();
			$sth->bindParam(2, $idRecibido);
			$fecha = $oPartidaVO->getFecha();
			$sth->bindParam(3, $fecha);
			$cantIngresada = $oPartidaVO->getCantIngresada();
			$sth->bindParam(4, $cantIngresada);
			$costo = $oPartidaVO->getCosto();
			$sth->bindParam(5, $costo);
			$stock = $oPartidaVO->getStock();
			$sth->bindParam(6, $stock);
			$ivaAlicuota = $oPartidaVO->getIvaAlicuota();
			$sth->bindParam(7, $ivaAlicuota);
			$comentario = $oPartidaVO->getComentario();
			$sth->bindParam(8, $comentario);
			$idUsuarioAct = $oPartidaVO->getIdUsuarioAct();
			$sth->bindParam(9, $idUsuarioAct);
			$fechaAct = $oPartidaVO->getFechaAct();
			$sth->bindParam(10, $fechaAct);
			$id = $oPartidaVO->getId();
			$sth->bindParam(11, $id);
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
	 * Nos permite eliminar un registro en la tabla partidas
	 *
	 * @param $oPartidaVO
	 */
	public function delete($oPartidaVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("DELETE FROM partidas WHERE id=?)");
			$id = $oPartidaVO->getId();
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