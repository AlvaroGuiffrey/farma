<?php
// Se definen las clases necesarias
Clase::define('MarcaActiveRecord');

class MarcaModelo extends MarcaActiveRecord
{
	#propiedades
	public $cantidad;
	public $lastId;

	#métodos
	public function getCantidad()
	{
	return $this->cantidad;
	}

	public function getLastId()
	{
		return $this->lastId;
	}
	
	public function findPorNombre($oMarcaVO)
	{
	try{
		$dbh = DataBase::getInstance();
		$sth = $dbh->prepare("SELECT * FROM marcas WHERE nombre=?");
		$nombre = $oMarcaVO->getNombre();
		$sth->bindParam(1, $nombre);
		$sth->execute();
		if (!$sth){
			$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
			$oPDOe->errorInfo = $dbh->errorInfo();
			throw $oPDOe;
		}
		$fila = $sth->fetchObject();
		$this->cantidad = $sth->rowCount();
		if ($this->cantidad > 0){
			$oMarcaVO->setId($fila->id);
			$oMarcaVO->setNombre($fila->nombre);
			$oMarcaVO->setComentario($fila->comentario);
			$oMarcaVO->setIdUsuarioAct($fila->id_usuario_act);
			$oMarcaVO->setFechaAct($fila->fecha_act);
		}
		$dbh=null;
		return $oMarcaVO;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

}
?>