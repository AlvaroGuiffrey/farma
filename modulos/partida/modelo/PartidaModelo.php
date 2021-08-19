<?php
// Se definen las clases necesarias
Clase::define('PartidaActiveRecord');

class PartidaModelo extends PartidaActiveRecord
{
	#propiedades
	public $cantidad;
	public $lastId;
	private $_idArticulo;
	private $_idRecibido;

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
	 * Nos permite obtener el identificador de la última partida actualizada.
	 *
	 * @return integer
	 */
	public function getLastId()
	{
		return $this->lastId;
	}

	/**
	 * Nos permite obtener un array de la consulta de todas las partidas
	 * ordenadas por id Recibidos
	 *
	 * @return $aPartidas
	 */
	public function findAllPorIdRecibido($oPartidaVO)
	{
		try{
			$aPartidas = array();
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM partidas WHERE id_recibido=?");
			$idRecibido = $oPartidaVO->getIdRecibido();
			$sth->bindParam(1, $idRecibido);
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
	 * partidas buscado por el id de artículo y fecha mayor.
	 *
	 * @param $oPartidaVO integer $idArticulo
	 * 
	 * @return $oPartidaVO
	 */
	public function findPorFechaMayor($oPartidaVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM partidas WHERE id_articulo=? AND 
									fecha=(SELECT MAX(fecha) FROM partidas WHERE id_articulo=?)");
			$idArticulo = $oPartidaVO->getIdArticulo();
			$sth->bindParam(1, $idArticulo);
			$sth->bindParam(2, $idArticulo);
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
	 * Nos permite obtener un objeto de la clase VO de la tabla
	 * partidas del último registro buscado por el id del artículo.
	 *
	 * @param $oPartidaVO integer $idArticulo
	 *
	 * @return $oPartidaVO
	 */
	public function findPorUltimo($oPartidaVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM partidas WHERE id_articulo=? ORDER BY id DESC LIMIT 1");
			$idArticulo = $oPartidaVO->getIdArticulo();
			$sth->bindParam(1, $idArticulo);
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
}
?>