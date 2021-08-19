<?php
// Se definen las clases necesarias
Clase::define('DataBase');
Clase::define('ActiveRecordInterface');
Clase::define('ProductoProvVO');

class ProductoProvActiveRecord implements ActiveRecord
{
	#métodos CRUD implementados en la interface ActiveRecord
	
    /**
     * Nos permite obtener un array de la consulta de todos los productos_prov
     * ordenados por nombre
     *
     * @return $aProductosProv
     */
	public function findAll()
	{
		try{
			$aProductosProv = array();
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM productos_prov ORDER BY nombre");
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$aProductosProv = $sth->fetchAll();
			$this->cantidad = $sth->rowCount();
			$dbh=null;
			return $aProductosProv;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	/**
	 * Nos permite obtener un objeto de la clase VO de la tabla
	 * productos_prov buscado por el id.
	 *
	 * @param $oProductoProvVO integer $id
	 * @return $oProductoProvVO
	 */
	public function find($oProductoProvVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM productos_prov WHERE id=?");
			$id = $oProductoProvVO->getId();
			$sth->bindParam(1, $id);
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$fila = $sth->fetchObject();
			$this->cantidad = $sth->rowCount();
			$oProductoProvVO->setId($fila->id);
			$oProductoProvVO->setIdProveedor($fila->id_proveedor);
			$oProductoProvVO->setCodigoB($fila->codigo_b);
			$oProductoProvVO->setCodigoP($fila->codigo_p);
			$oProductoProvVO->setNombre($fila->nombre);
			$oProductoProvVO->setPrecio($fila->precio);
			$oProductoProvVO->setCodigoIva($fila->codigo_iva);
			$oProductoProvVO->setTipoDescuento($fila->tipo_descuento);
			$oProductoProvVO->setEstado($fila->estado);
			$oProductoProvVO->setIdArticulo($fila->id_articulo);
			$oProductoProvVO->setIdUsuarioAct($fila->id_usuario_act);
			$oProductoProvVO->setFechaAct($fila->fecha_act);
			$dbh=null;
			return $oProductoProvVO;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	/**
	 * Nos permite insertar un producto en la tabla productos_prov
	 *
	 * @param $oProductoProvVO
	 */
	public function insert($oProductoProvVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("INSERT INTO productos_prov(id_proveedor, codigo_b, codigo_p, nombre, precio, codigo_iva, 
                                  tipo_descuento, estado, id_articulo, id_usuario_act, fecha_act) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$idProveedor = $oProductoProvVO->getIdProveedor();
			$sth->bindParam(1, $idProveedor);
			$codigoB = $oProductoProvVO->getCodigoB();
			$sth->bindParam(2, $codigoB);
			$codigoP = $oProductoProvVO->getCodigoP();
			$sth->bindParam(3, $codigoP);
			$nombre = $oProductoProvVO->getNombre();
			$sth->bindParam(4, $nombre);
			$precio = $oProductoProvVO->getPrecio();
			$sth->bindParam(5, $precio);
			$codigoIva = $oProductoProvVO->getCodigoIva();
			$sth->bindParam(6, $codigoIva);
			$tipoDescuento = $oProductoProvVO->getTipoDescuento();
			$sth->bindParam(7, $tipoDescuento);
			$estado = $oProductoProvVO->getEstado();
			$sth->bindParam(8, $estado);
			$idArticulo = $oProductoProvVO->getIdArticulo();
			$sth->bindParam(9, $idArticulo);
			$idUsuarioAct = $oProductoProvVO->getIdUsuarioAct();
			$sth->bindParam(10, $idUsuarioAct);
			$fechaAct = $oProductoProvVO->getFechaAct();
			$sth->bindParam(11, $fechaAct);
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
	 * Nos permite actualizar un registro en la tabla productos_prov
	 *
	 * @param $oProductoProvVO
	 */
	public function update($oProductoProvVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("UPDATE productos_prov SET id_proveedor=?, codigo_b=?, codigo_p=?, nombre=?, precio=?, 
                                  codigo_iva=?, tipo_descuento=?, estado=?, id_articulo=?, id_usuario_act=?, fecha_act=? 
                                  WHERE id=?");
			$idProveedor = $oProductoProvVO->getIdProveedor();
			$sth->bindParam(1, $idProveedor);
			$codigoB = $oProductoProvVO->getCodigoB();
			$sth->bindParam(2, $codigoB);
			$codigoP = $oProductoProvVO->getCodigoP();
			$sth->bindParam(3, $codigoP);
			$nombre = $oProductoProvVO->getNombre();
			$sth->bindParam(4, $nombre);
			$precio = $oProductoProvVO->getPrecio();
			$sth->bindParam(5, $precio);
			$codigoIva = $oProductoProvVO->getCodigoIva();
			$sth->bindParam(6, $codigoIva);
			$tipoDescuento = $oProductoProvVO->getTipoDescuento();
			$sth->bindParam(7, $tipoDescuento);
			$estado = $oProductoProvVO->getEstado();
			$sth->bindParam(8, $estado);
			$idArticulo = $oProductoProvVO->getIdArticulo();
			$sth->bindParam(9, $idArticulo);
			$idUsuarioAct = $oProductoProvVO->getIdUsuarioAct();
			$sth->bindParam(10, $idUsuarioAct);
			$fechaAct = $oProductoProvVO->getFechaAct();
			$sth->bindParam(11, $fechaAct);
			$id = $oProductoProvVO->getId();
			$sth->bindParam(12, $id);
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar actualizar datos.', 1);
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
	 * Nos permite eliminar registro en la tabla productos_prov
	 *
	 * @param $oProductoProvVO
	 */
	public function delete($oProductoProvVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("DELETE FROM productos_prov WHERE id=?)");
			$id = $oProductoProvVO->getId();
			$sth->bindParam(1, $id);
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar borrar el registro.', 1);
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