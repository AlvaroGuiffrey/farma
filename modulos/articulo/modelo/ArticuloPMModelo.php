<?php
// Se definen las clases necesarias
Clase::define('ArticuloPMActiveRecord');

class ArticuloPMModelo extends ArticuloPMActiveRecord
{
	#propiedades
	public $cantidad;
	public $lastId;
	private $_opcionListado;
	private $_marca;
	private $_rubro;
	private $_estado;
	private $_orden;

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
	 * del articulo_pm buscado por el código de barra.
	 *
	 * @param $oArticuloPMVO string $codigoB
	 * @return $oArticuloPMVO
	 */
	public function findPorCodigoB($oArticuloPMVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM articulos_pm WHERE codigo_b=?");
			$codigoB = $oArticuloPMVO->getCodigoB();
			$sth->bindParam(1, $codigoB);
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$fila = $sth->fetchObject();
			$this->cantidad = $sth->rowCount();
			if ($this->cantidad > 0){
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
	 * Nos permite obtener un objeto de la clase VO
	 * del articulo_pm buscado por el nombre.
	 *
	 * @param $oArticuloPMVO string $nombre
	 * @return $oArticuloPMVO
	 */
	public function findPorNombre($oArticuloPMVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM articulos_pm WHERE nombre=?");
			$nombre = $oArticuloPMVO->getNombre();
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
	 * Nos permite obtener un array de los articulos_pm
	 * buscado por parte del nombre.
	 *
	 * @param $oArticuloPMVO string $nombre
	 * @return $aArticulos
	 */
	public function findAllPorNombre($oArticuloPMVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM articulos_pm WHERE nombre LIKE :NOMBRE ORDER BY nombre");
			$nombre = "%".$oArticuloPMVO->getNombre()."%";
			$sth->bindParam(':NOMBRE', $nombre, PDO::PARAM_STR);
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
	 * Nos permite obtener un array de la consulta de todos los articulos
	 * según las opciones seleccionadas para listado
	 *
	 * @return $aArticulos
	 */
	public function findAllOpcionListado($orden)
	{
		try{
			$this->_orden = $orden;
			$this->_opcionListado = "SELECT * FROM articulos_pm ORDER BY ".$this->_orden;
			$aArticulos = array();
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare($this->_opcionListado);
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
	 * Nos permite vaciar la tabla articulos_pm
	 */
	public function truncate()
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("TRUNCATE TABLE articulos_pm");
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar borrar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$this->cantidad = $sth->rowCount();
			$dbh=null;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}
    
    /**
	 * Nos permite obtener la cantidad de articulos de la tabla
	 *
	 */
	public function count()
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT count(*) FROM articulos_pm");
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$this->cantidad = $sth->fetchColumn();
			$dbh=null;

		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}
}
?>
