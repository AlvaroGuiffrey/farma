<?php
// Se definen las clases necesarias
Clase::define('ListaOrdenActiveRecord');

class ListaOrdenModelo extends ListaOrdenActiveRecord
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

	public function findPorNombre($oListaOrdenVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM listas_orden WHERE nombre=?");
			$nombre = $oListaOrdenVO->getNombre();
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
				$oListaOrdenVO->setId($fila->id);
				$oListaOrdenVO->setNombre($fila->nombre);
				$oListaOrdenVO->setIdProveedor($fila->id_proveedor);
				$oListaOrdenVO->setIdUsuarioAct($fila->id_usuario_act);
				$oListaOrdenVO->setFechaAct($fila->fecha_act);
			}
			$dbh=null;
			return $oListaOrdenVO;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

}
?>