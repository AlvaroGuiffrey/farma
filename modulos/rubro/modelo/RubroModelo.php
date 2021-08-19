<?php
// Se definen las clases necesarias
Clase::define('RubroActiveRecord');

class RubroModelo extends RubroActiveRecord 
{
	#propiedades
	public $cantidad;
	public $lastId;

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
	 * Nos permite obtener el identificador del último rubro actualizado.
	 *
	 * @return integer
	 */
	public function getLastId()
	{
		return $this->lastId;
	}

	/**
	 * Nos permite obtener un objeto de la clase VO
	 * del rubro buscado por el nombre.
	 * 
	 * @param $oRubroVO string $nombre
	 * @return $oRubroVO
	 */
	public function findPorNombre($oRubroVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM rubros WHERE nombre=?");
			$nombre = $oRubroVO->getNombre();
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
				$oRubroVO->setId($fila->id);
				$oRubroVO->setNombre($fila->nombre);
				$oRubroVO->setComentario($fila->comentario);
				$oRubroVO->setIdUsuarioAct($fila->id_usuario_act);
				$oRubroVO->setFechaAct($fila->fecha_act);
			}
			$dbh=null;
			return $oRubroVO;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

}
?>