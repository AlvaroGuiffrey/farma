<?php
// Se requiere la clase ActiveRecord del módulo
require_once $_SERVER['DOCUMENT_ROOT'].$_SESSION['dir'].'/modulos/provincia/modelo/ProvinciaActiveRecord.php';

class ProvinciaModelo extends ProvinciaActiveRecord
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

	public function findPorNombre($oProvinciaVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM provincias WHERE nombre=?");
			$nombre = $oProvinciaVO->getNombre();
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
				$oProvinciaVO->setId($fila->id);
				$oProvinciaVO->setNumero($fila->numero);
				$oProvinciaVO->setNombre($fila->nombre);
				$oProvinciaVO->setLetra($fila->letra);
				$oProvinciaVO->setPais($fila->pais);
				$oProvinciaVO->setIdUsuarioAct($fila->id_usuario_act);
				$oProvinciaVO->setFechaAct($fila->fecha_act);
			}
			$dbh=null;
			return $oProvinciaVO;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

}
?>