<?php
// Se definen las clases necesarias
Clase::define('DataBase');
Clase::define('ActiveRecordInterface');
Clase::define('ArticuloPMVO');

class ArticuloPMActiveRecord implements ActiveRecord
{
	#métodos CRUD implementados en la interface ActiveRecord

	/**
	 * Nos permite obtener un array de la consulta de todos los articulos_pm
	 *
	 * @return $aArticulos
	 */
	public function findAll()
	{

		$aArticulos = array();
		$dbh = DataBase::getInstance();
		$sth = $dbh->prepare("SELECT * FROM articulos_pm");
		$sth->execute();
		if (!$sth){
			$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
			$oPDOe->errorInfo = $dbh->errorInfo();
			throw $oPDOe;
		}
		$aArticulos = $sth->fetchAll();
		$this->cantidad = $sth->rowCount();
		$dbh=null;
		return $aArticulos;


		try{
			$aArticulos = array();
			$dbh = DataBase::getInstance();
			//$sth = $dbh->prepare("SELECT * FROM articulos ORDER BY nombre");
			$sth = $dbh->prepare("SELECT * FROM articulos");
			$sth->execute();

			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}

			$aArticulos = $sth->fetchAll();
			$this->cantidad = $sth->rowCount();
			$dbh=null;
			return $aArticulos;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}

	}

	/**
	 * Nos permite obtener un objeto de la clase VO de la tabla
	 * articulo_pm buscado por el id.
	 *
	 * @param $oArticuloPMVO integer $id
	 * @return $oArticuloPMVO
	 */
	public function find($oArticuloPMVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM articulos_pm WHERE id=?");
			$id = $oArticuloVO->getId();
			$sth->bindParam(1, $id);
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$fila = $sth->fetchObject();
			$this->cantidad = $sth->rowCount();
			if ($this->cantidad == 1){
                $oArticuloPMVO->setId($fila->id);
				$oArticuloPMVO->setCodigoB($fila->codigo_b);
				$oArticuloPMVO->setNombre($fila->nombre);
				$oArticuloPMVO->setPrecio($fila->precio);
			}
			$dbh=null;
			return $oArticuloPMVO;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	/**
	 * Nos permite insertar un artículo en la tabla articulos_pm
	 *
	 * @param $oArticuloPMVO
	 */
	public function insert($oArticuloPMVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("INSERT INTO articulos_pm
						(codigo_b, nombre, precio)
					 	VALUES (?, ?, ?)");
			$codigoB = $oArticuloPMVO->getCodigoB();
			$sth->bindParam(1, $codigoB);
			$nombre = $oArticuloPMVO->getNombre();
			$sth->bindParam(2, $nombre);
			$precio = $oArticuloPMVO->getPrecio();
			$sth->bindParam(3, $precio);
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
	 * Nos permite actualizar un registro en la tabla articulos_pm
	 *
	 * @param $oArticuloPMVO
	 */
	public function update($oArticuloPMVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("UPDATE articulos_pm
						SET codigo_b=?, nombre=?, precio=? WHERE id=?");
			$codigoB = $oArticuloPMVO->getCodigoB();
			$sth->bindParam(1, $codigoB);
			$nombre = $oArticuloPMVO->getNombre();
			$sth->bindParam(2, $nombre);
			$precio = $oArticuloPMVO->getPrecio();
			$sth->bindParam(3, $precio);
			$id = $oArticuloPMVO->getId();
			$sth->bindParam(4, $id);
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
	 * Nos permite eliminar registro en la tabla articulos_pm
	 *
	 * @param $oArticuloPMVO
	 */
	public function delete($oArticuloPMVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("DELETE FROM articulos_pm WHERE id=?");
			$id = $oArticuloPMVO->getId();
			$sth->bindParam(1, $id);
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar deletear datos.', 1);
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
