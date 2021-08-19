<?php
/// Se definen las clases necesarias 
Clase::define('DataBase');
Clase::define('ActiveRecordInterface');
Clase::define('RecibidoVO');

class RecibidoActiveRecord implements ActiveRecord
{
	#métodos CRUD implementados en la interface ActiveRecord

	/**
	 * Nos permite obtener un array de la consulta de todos los comprobantes
	 * recibidos
	 *
	 * @return $aRecibidos
	 */
	public function findAll()
	{
		try{
			$aRecibidos = array();
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM recibidos");
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$aRecibidos = $sth->fetchAll();
			$this->cantidad = $sth->rowCount();
			$dbh=null;
			return $aRecibidos;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	/**
	 * Nos permite obtener un objeto de la clase VO de la tabla
	 * recibidos buscado por el id.
	 *
	 * @param $oRecibidoVO integer $id
	 * @return $oRecibidoVO
	 */
	public function find($oRecibidoVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM recibidos WHERE id=?");
			$id = $oRecibidoVO->getId();
			$sth->bindParam(1, $id);
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$fila = $sth->fetchObject();
			$this->cantidad = $sth->rowCount();
			$oRecibidoVO->setId($fila->id);
			$oRecibidoVO->setComprobante($fila->comprobante);
			$oRecibidoVO->setFecha($fila->fecha);
			$oRecibidoVO->setIdProveedor($fila->id_proveedor);
			$oRecibidoVO->setGravado($fila->gravado);
			$oRecibidoVO->setExento($fila->exento);
			$oRecibidoVO->setRetencionDgi($fila->retencion_dgi);
			$oRecibidoVO->setPercepcionDgi($fila->percepcion_dgi);
			$oRecibidoVO->setRetencionRenta($fila->retencion_renta);
			$oRecibidoVO->setPercepcionRenta($fila->percepcion_renta);
			$oRecibidoVO->setOtros($fila->otros);
			$oRecibidoVO->setIva($fila->iva);
			$oRecibidoVO->setTotal($fila->total);
			$oRecibidoVO->setComentario($fila->comentario);
			$oRecibidoVO->setConsistencia($fila->consistencia);
			$oRecibidoVO->setIdUsuarioAct($fila->id_usuario_act);
			$oRecibidoVO->setFechaAct($fila->fecha_act);
			$dbh=null;
			return $oRecibidoVO;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	/**
	 * Nos permite insertar una partida en la tabla recibidos
	 *
	 * @param $oRecibidoVO
	 */
	public function insert($oRecibidoVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("INSERT INTO recibidos
						(comprobante, fecha, id_proveedor, gravado, exento, retencion_dgi, percepcion_dgi, retencion_renta, percepcion_renta, otros, iva, total, comentario, consistencia, id_usuario_act, fecha_act)
					 	VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$comprobante = $oRecibidoVO->getComprobante();
			$sth->bindParam(1, $comprobante);
			$fecha = $oRecibidoVO->getFecha();
			$sth->bindParam(2, $fecha);
			$idProveedor = $oRecibidoVO->getIdProveedor();
			$sth->bindParam(3, $idProveedor);
			$gravado = $oRecibidoVO->getGravado();
			$sth->bindParam(4, $gravado);
			$exento = $oRecibidoVO->getExento();
			$sth->bindParam(5, $exento);
			$retencionDgi = $oRecibidoVO->getRetencionDgi();
			$sth->bindParam(6, $retencionDgi);
			$percepcionDgi = $oRecibidoVO->getPercepcionDgi();
			$sth->bindParam(7, $percepcionDgi);
			$retencionRenta = $oRecibidoVO->getRetencionRenta();
			$sth->bindParam(8, $retencionRenta);
			$percepcionRenta = $oRecibidoVO->getPercepcionRenta();
			$sth->bindParam(9, $percepcionRenta);
			$otros = $oRecibidoVO->getOtros();
			$sth->bindParam(10, $otros);
			$iva = $oRecibidoVO->getIva();
			$sth->bindParam(11, $iva);
			$total = $oRecibidoVO->getTotal();
			$sth->bindParam(12, $total);
			$comentario = $oRecibidoVO->getComentario();
			$sth->bindParam(13, $comentario);
			$consistencia = $oRecibidoVO->getConsistencia();
			$sth->bindParam(14, $consistencia);			
			$idUsuarioAct = $oRecibidoVO->getIdUsuarioAct();
			$sth->bindParam(15, $idUsuarioAct);
			$fechaAct = $oRecibidoVO->getFechaAct();
			$sth->bindParam(16, $fechaAct);
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
	 * Nos permite actualizar un registro en la tabla recibidos
	 *
	 * @param $oRecibidoVO
	 */
	public function update($oRecibidoVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("UPDATE recibidos
						SET comprobante=?, fecha=?, id_proveedor=?, gravado=?, exento=?, retencion_dgi=?, percepcion_dgi=?, retencion_renta=?, percepcion_renta=?, otros=?, iva=?, total=?, comentario=?, consistencia=?, id_usuario_act=?, fecha_act=?
						WHERE id=?");
			$comprobante = $oRecibidoVO->getComprobante();
			$sth->bindParam(1, $comprobante);
			$fecha = $oRecibidoVO->getFecha();
			$sth->bindParam(2, $fecha);
			$idProveedor = $oRecibidoVO->getIdProveedor();
			$sth->bindParam(3, $idProveedor);
			$gravado = $oRecibidoVO->getGravado();
			$sth->bindParam(4, $gravado);
			$exento = $oRecibidoVO->getExento();
			$sth->bindParam(5, $exento);
			$retencionDgi = $oRecibidoVO->getRetencionDgi();
			$sth->bindParam(6, $retencionDgi);
			$percepcionDgi = $oRecibidoVO->getPercepcionDgi();
			$sth->bindParam(7, $percepcionDgi);
			$retencionRenta = $oRecibidoVO->getRetencionRenta();
			$sth->bindParam(8, $retencionRenta);
			$percepcionRenta = $oRecibidoVO->getPercepcionRenta();
			$sth->bindParam(9, $percepcionRenta);
			$otros = $oRecibidoVO->getOtros();
			$sth->bindParam(10, $otros);
			$iva = $oRecibidoVO->getIva();
			$sth->bindParam(11, $iva);
			$total = $oRecibidoVO->getTotal();
			$sth->bindParam(12, $total);
			$comentario = $oRecibidoVO->getComentario();
			$sth->bindParam(13, $comentario);
			$consistencia = $oRecibidoVO->getConsistencia();
			$sth->bindParam(14, $consistencia);			
			$idUsuarioAct = $oRecibidoVO->getIdUsuarioAct();
			$sth->bindParam(15, $idUsuarioAct);
			$fechaAct = $oRecibidoVO->getFechaAct();
			$sth->bindParam(16, $fechaAct);
			$id = $oRecibidoVO->getId();
			$sth->bindParam(17, $id);
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
	 * Nos permite eliminar un registro en la tabla recibidos
	 *
	 * @param $oRecibidoVO
	 */
	public function delete($oRecibidoVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("DELETE FROM recibidos WHERE id=?)");
			$id = $oRecibidoVO->getId();
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