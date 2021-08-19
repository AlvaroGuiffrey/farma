<?php
// Se definen las clases necesarias
Clase::define('LocalidadActiveRecord');

class LocalidadModelo extends LocalidadActiveRecord
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

	public function findPorNombre($oLocalidadVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM localidades WHERE nombre=?");
			$nombre = $oLocalidadVO->getNombre();
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
				$oLocalidadVO->setId($fila->id);
				$oLocalidadVO->setNombre($fila->nombre);
				$oLocalidadVO->setCodPostal($fila->cod_postal);
				$oLocalidadVO->setDepartamento($fila->departamento);
				$oLocalidadVO->setIdProvincia($fila->id_provincia);
				$oLocalidadVO->setIdUsuarioAct($fila->id_usuario_act);
				$oLocalidadVO->setFechaAct($fila->fecha_act);
			}
			$dbh=null;
			return $oLocalidadVO;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	public function findAllPorProvincia($oLocalidadVO)
	{
		try{
			$aLocalidades = array();
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM localidades WHERE id_provincia=? ORDER BY nombre");
			$idProvincia = $oLocalidadVO->getIdProvincia();
			$sth->bindParam(1, $idProvincia);
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
	
}
?>