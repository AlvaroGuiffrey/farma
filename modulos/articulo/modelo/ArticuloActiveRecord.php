<?php
// Se definen las clases necesarias
Clase::define('DataBase');
Clase::define('ActiveRecordInterface');
Clase::define('ArticuloVO');

class ArticuloActiveRecord implements ActiveRecord
{
	#métodos CRUD implementados en la interface ActiveRecord

	/**
	 * Nos permite obtener un array de la consulta de todos los articulos
	 *
	 * @return $aArticulos
	 */
	public function findAll()
	{
		/*
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
		*/

		try{
			$aArticulos = array();
			$dbh = DataBase::getInstance();
			//$sth = $dbh->prepare("SELECT * FROM articulos ORDER BY nombre");
			$sth = $dbh->prepare("SELECT * FROM articulos");
			$sth->execute();
			/*
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			*/
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
	 * articulo buscado por el id.
	 *
	 * @param $oArticuloVO integer $id
	 * @return $oArticuloVO
	 */
	public function find($oArticuloVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM articulos WHERE id=?");
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
			  $oArticuloVO->setId($fila->id);
				$oArticuloVO->setCodigo($fila->codigo);
				$oArticuloVO->setCodigoM($fila->codigo_m);
				$oArticuloVO->setCodigoB($fila->codigo_b);
				$oArticuloVO->setIdMarca($fila->id_marca);
				$oArticuloVO->setIdRubro($fila->id_rubro);
				$oArticuloVO->setNombre($fila->nombre);
				$oArticuloVO->setPresentacion($fila->presentacion);
				$oArticuloVO->setComentario($fila->comentario);
				$oArticuloVO->setMargen($fila->margen);
				$oArticuloVO->setCosto($fila->costo);
				$oArticuloVO->setPrecio($fila->precio);
				$oArticuloVO->setFechaPrecio($fila->fecha_precio);
				$oArticuloVO->setStock($fila->stock);
				$oArticuloVO->setRotulo($fila->rotulo);
				$oArticuloVO->setIdProveedor($fila->id_proveedor);
				$oArticuloVO->setOpcionProv($fila->opcion_prov);
				$oArticuloVO->setEquivalencia($fila->equivalencia);
				$oArticuloVO->setCodigoIva($fila->codigo_iva);
				$oArticuloVO->setFoto($fila->foto);
				$oArticuloVO->setEstado($fila->estado);
				$oArticuloVO->setIdUsuarioAct($fila->id_usuario_act);
				$oArticuloVO->setFechaAct($fila->fecha_act);
			}
			$dbh=null;
			return $oArticuloVO;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	/**
	 * Nos permite insertar un artículo en la tabla articulos
	 *
	 * @param $oArticuloVO
	 */
	public function insert($oArticuloVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("INSERT INTO articulos
						(codigo, codigo_m, codigo_b, id_marca, id_rubro, nombre, presentacion, comentario, margen, costo,
							precio, fecha_precio, stock, rotulo, id_proveedor, opcion_prov, equivalencia, codigo_iva,
							foto, estado, id_usuario_act, fecha_act)
					 	VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$codigo = $oArticuloVO->getCodigo();
			$sth->bindParam(1, $codigo);
			$codigoM = $oArticuloVO->getCodigoM();
			$sth->bindParam(2, $codigoM);
			$codigoB = $oArticuloVO->getCodigoB();
			$sth->bindParam(3, $codigoB);
			$idMarca = $oArticuloVO->getIdMarca();
			$sth->bindParam(4, $idMarca);
			$idRubro = $oArticuloVO->getIdRubro();
			$sth->bindParam(5, $idRubro);
			$nombre = $oArticuloVO->getNombre();
			$sth->bindParam(6, $nombre);
			$presentacion = $oArticuloVO->getPresentacion();
			$sth->bindParam(7, $presentacion);
			$comentario = $oArticuloVO->getComentario();
			$sth->bindParam(8, $comentario);
			$margen = $oArticuloVO->getMargen();
			$sth->bindParam(9, $margen);
			$costo = $oArticuloVO->getCosto();
			$sth->bindParam(10, $costo);
			$precio = $oArticuloVO->getPrecio();
			$sth->bindParam(11, $precio);
			$fechaPrecio = $oArticuloVO->getFechaPrecio();
			$sth->bindParam(12, $fechaPrecio);
			$stock = $oArticuloVO->getStock();
			$sth->bindParam(13, $stock);
			$rotulo = $oArticuloVO->getRotulo();
			$sth->bindParam(14, $rotulo);
			$idProveedor = $oArticuloVO->getIdProveedor();
			$sth->bindParam(15, $idProveedor);
			$opcionProv = $oArticuloVO->getOpcionProv();
			$sth->bindParam(16, $opcionProv);
			$equivalencia = $oArticuloVO->getEquivalencia();
			$sth->bindParam(17, $equivalencia);
			$codigoIva = $oArticuloVO->getCodigoIva();
			$sth->bindParam(18, $codigoIva);
			$foto = $oArticuloVO->getFoto();
			$sth->bindParam(19, $foto);
			$estado = $oArticuloVO->getEstado();
			$sth->bindParam(20, $estado);
			$idUsuarioAct = $oArticuloVO->getIdUsuarioAct();
			$sth->bindParam(21, $idUsuarioAct);
			$fechaAct = $oArticuloVO->getFechaAct();
			$sth->bindParam(22, $fechaAct);
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
	 * Nos permite actualizar un registro en la tabla articulos
	 *
	 * @param $oArticuloVO
	 */
	public function update($oArticuloVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("UPDATE articulos
						SET codigo=?, codigo_m=?, codigo_b=?, id_marca=?, id_rubro=?, nombre=?, presentacion=?,
						comentario=?, margen=?, costo=?, precio=?, fecha_precio=?, stock=?, rotulo=?, id_proveedor=?,
						opcion_prov=?, equivalencia=?, codigo_iva=?, foto=?, estado=?, id_usuario_act=?, fecha_act=?
						WHERE id=?");
			$codigo = $oArticuloVO->getCodigo();
			$sth->bindParam(1, $codigo);
			$codigoM = $oArticuloVO->getCodigoM();
			$sth->bindParam(2, $codigoM);
			$codigoB = $oArticuloVO->getCodigoB();
			$sth->bindParam(3, $codigoB);
			$idMarca = $oArticuloVO->getIdMarca();
			$sth->bindParam(4, $idMarca);
			$idRubro = $oArticuloVO->getIdRubro();
			$sth->bindParam(5, $idRubro);
			$nombre = $oArticuloVO->getNombre();
			$sth->bindParam(6, $nombre);
			$presentacion = $oArticuloVO->getPresentacion();
			$sth->bindParam(7, $presentacion);
			$comentario = $oArticuloVO->getComentario();
			$sth->bindParam(8, $comentario);
			$margen = $oArticuloVO->getMargen();
			$sth->bindParam(9, $margen);
			$costo = $oArticuloVO->getCosto();
			$sth->bindParam(10, $costo);
			$precio = $oArticuloVO->getPrecio();
			$sth->bindParam(11, $precio);
			$fechaPrecio = $oArticuloVO->getFechaPrecio();
			$sth->bindParam(12, $fechaPrecio);
			$stock = $oArticuloVO->getStock();
			$sth->bindParam(13, $stock);
			$rotulo = $oArticuloVO->getRotulo();
			$sth->bindParam(14, $rotulo);
			$idProveedor = $oArticuloVO->getIdProveedor();
			$sth->bindParam(15, $idProveedor);
			$opcionProv = $oArticuloVO->getOpcionProv();
			$sth->bindParam(16, $opcionProv);
			$equivalencia = $oArticuloVO->getEquivalencia();
			$sth->bindParam(17, $equivalencia);
			$codigoIva = $oArticuloVO->getCodigoIva();
			$sth->bindParam(18, $codigoIva);
			$foto = $oArticuloVO->getFoto();
			$sth->bindParam(19, $foto);
			$estado = $oArticuloVO->getEstado();
			$sth->bindParam(20, $estado);
			$idUsuarioAct = $oArticuloVO->getIdUsuarioAct();
			$sth->bindParam(21, $idUsuarioAct);
			$fechaAct = $oArticuloVO->getFechaAct();
			$sth->bindParam(22, $fechaAct);
			$id = $oArticuloVO->getId();
			$sth->bindParam(23, $id);
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
	 * Nos permite eliminar registro en la tabla articulos
	 *
	 * @param $oArticuloVO
	 */
	public function delete($oArticuloVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("DELETE FROM articulos WHERE id=?");
			$id = $oArticuloVO->getId();
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
