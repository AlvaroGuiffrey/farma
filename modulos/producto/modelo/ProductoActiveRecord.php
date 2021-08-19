<?php
// Se definen las clases necesarias
Clase::define('DataBase');
Clase::define('ActiveRecordInterface');
Clase::define('ProductoVO');

class ProductoActiveRecord implements ActiveRecord
{
	#métodos CRUD implementados en la interface ActiveRecord
	public function findAll()
	{
		try{
			$aProductos = array();
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM productos ORDER BY nombre");
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$aProductos = $sth->fetchAll();
			$this->cantidad = $sth->rowCount();
			$dbh=null;
			return $aProductos;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	public function find($oProductoVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM productos WHERE id=?");
			$id = $oProductoVO->getId();
			$sth->bindParam(1, $id);
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$fila = $sth->fetchObject();
			$this->cantidad = $sth->rowCount();
			$oProductoVO->setId($fila->id);
			$oProductoVO->setIdProveedor($fila->id_proveedor);
			$oProductoVO->setCodigoB($fila->codigo_b);
			$oProductoVO->setCodigoP($fila->codigo_p);
			$oProductoVO->setNombre($fila->nombre);
			$oProductoVO->setPrecio($fila->precio);
			$oProductoVO->setCodigoIva($fila->codigo_iva);
			$oProductoVO->setTipoDescuento($fila->tipo_descuento);
			$oProductoVO->setEstado($fila->estado);
			$oProductoVO->setIdArticulo($fila->id_articulo);
			$oProductoVO->setIdUsuarioAct($fila->id_usuario_act);
			$oProductoVO->setFechaAct($fila->fecha_act);
			$dbh=null;
			return $oProductoVO;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	public function insert($oProductoVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("INSERT INTO productos(id_proveedor, codigo_b, codigo_p, nombre, precio, codigo_iva, 
                                                        tipo_descuento, estado, id_articulo, id_usuario_act, fecha_act) 
                                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$idProveedor = $oProductoVO->getIdProveedor();
			$sth->bindParam(1, $idProveedor);
			$codigoB = $oProductoVO->getCodigoB();
			$sth->bindParam(2, $codigoB);
			$codigoP = $oProductoVO->getCodigoP();
			$sth->bindParam(3, $codigoP);
			$nombre = $oProductoVO->getNombre();
			$sth->bindParam(4, $nombre);
			$precio = $oProductoVO->getPrecio();
			$sth->bindParam(5, $precio);
			$codigoIva = $oProductoVO->getCodigoIva();
			$sth->bindParam(6, $codigoIva);
			$tipoDescuento = $oProductoVO->getTipoDescuento();
			$sth->bindParam(7, $tipoDescuento);
			$estado = $oProductoVO->getEstado();
			$sth->bindParam(8, $estado);
			$idArticulo = $oProductoVO->getIdArticulo();
			$sth->bindParam(9, $idArticulo);
			$idUsuarioAct = $oProductoVO->getIdUsuarioAct();
			$sth->bindParam(10, $idUsuarioAct);
			$fechaAct = $oProductoVO->getFechaAct();
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

	public function update($oProductoVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("UPDATE productos SET id_proveedor=?, codigo_b=?, codigo_p=?, nombre=?, precio=?, 
                                                        codigo_iva=?, tipo_descuento=?, estado=?, id_articulo=?, 
                                                        id_usuario_act=?, fecha_act=? WHERE id=?");
			$idProveedor = $oProductoVO->getIdProveedor();
			$sth->bindParam(1, $idProveedor);
			$codigoB = $oProductoVO->getCodigoB();
			$sth->bindParam(2, $codigoB);
			$codigoP = $oProductoVO->getCodigoP();
			$sth->bindParam(3, $codigoP);
			$nombre = $oProductoVO->getNombre();
			$sth->bindParam(4, $nombre);
			$precio = $oProductoVO->getPrecio();
			$sth->bindParam(5, $precio);
			$codigoIva = $oProductoVO->getCodigoIva();
			$sth->bindParam(6, $codigoIva);
			$tipoDescuento = $oProductoVO->getTipoDescuento();
			$sth->bindParam(7, $tipoDescuento);
			$estado = $oProductoVO->getEstado();
			$sth->bindParam(8, $estado);
			$idArticulo = $oProductoVO->getIdArticulo();
			$sth->bindParam(9, $idArticulo);
			$idUsuarioAct = $oProductoVO->getIdUsuarioAct();
			$sth->bindParam(10, $idUsuarioAct);
			$fechaAct = $oProductoVO->getFechaAct();
			$sth->bindParam(11, $fechaAct);
			$id = $oProductoVO->getId();
			$sth->bindParam(12, $id);
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

	public function delete($oProductoVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("DELETE FROM productos WHERE id=?)");
			$id = $oProductoVO->getId();
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