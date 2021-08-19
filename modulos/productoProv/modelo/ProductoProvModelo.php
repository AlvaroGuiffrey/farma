<?php
// Se definen las clases necesarias
Clase::define('ProductoProvActiveRecord');

class ProductoProvModelo extends ProductoProvActiveRecord
{
	#propiedades
	public $cantidad;
	public $lastId;
	private $_opcionListado;
	private $_idProveedor;
	private $_estado;
	private $_orden;

	#métodos
	/**
	 * Nos permite obtener la cantidad de registros de la consulta realizada
	 */
	public function getCantidad()
	{
		return $this->cantidad;
	}

	/**
	 * Nos permite obtener el id del último registro actualizado
	 */
	public function getLastId()
	{
		return $this->lastId;
	}

	/**
	 * Nos permite vaciar la tabla productos_prov
	 */
	public function truncate()
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("TRUNCATE TABLE productos_prov");
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
	 * Nos permite obtener un array de los productos_prov activos
	 * de la lista del proveedor.
	 *
	 * estado = 1
	 *
	 * @return $aProductosProv
	 */
	public function findAllActivos()
	{
	    try{
	        $aProductosProv = array();
	        $dbh = DataBase::getInstance();
	        $sth = $dbh->prepare("SELECT * FROM productos_prov WHERE estado=1");
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
	 * Nos permite obtener un array con algunos datos de los productos_prov activos
	 * buscado por el id del proveedor para comparar con los productos por si el 
	 * proveedor realizó modificaciones.
	 *
	 * estado = 1
	 *
	 * @param $oProductoProvVO  $idProveedor
	 * @return $aProductosProv (id, codigo_p, codigo_b. precio)
	 */
	public function findAllPorIdProveedorParaModi($oProductoProvVO)
	{
	    try{
	        $aProductosProv = array();
	        $dbh = DataBase::getInstance();
	        $sth = $dbh->prepare("SELECT id, codigo_p, codigo_b, precio, codigo_iva, tipo_descuento 
                                    FROM productos_prov WHERE id_proveedor=? AND estado=1");
	        $idProveedor = $oProductoProvVO->getIdProveedor();
	        $sth->bindParam(1, $idProveedor);
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
	 * Nos permite obtener un array de los productos_prov activos
	 * buscado por el código de proveedor.
	 * 
	 * estado = 1
	 * 
	 * @param $oProductoProvVO  $codigoP
	 * @return $aProductosProv
	 */
	public function findAllPorCodigoP($oProductoProvVO)
	{
		try{
			$aProductosProv = array();
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM productos_prov WHERE codigo_p=? AND estado=1");
			$codigoP = $oProductoProvVO->getCodigoP();
			$sth->bindParam(1, $codigoP, PDO::PARAM_STR);
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
	 * Nos permite obtener un array de los productos_prov activos
	 * buscado por el código de barra.
	 *
	 * estado = 1
	 * 
	 * @param $oProductoProvVO #bigint $codigoB
	 * @return $aProductosProv
	 */
	public function findAllPorCodigoB($oProductoProvVO)
	{
		try{
			$aProductosProv = array();
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM productos_prov WHERE codigo_b=? AND estado=1");
			$codigoB = $oProductoProvVO->getCodigoB();
			$sth->bindParam(1, $codigoB);
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
	 * Nos permite obtener un array de los productos_prov activos
	 * buscado por el código de proveedor y de barra.
	 *
	 * estado = 1
	 *
	 * @param $oProductoProvVO (bigint $codigoB)
	 * @param $oProductoProvVO (varchar $codigoP)
	 * @return $aProductosProv
	 */
	public function findAllPorCodigoBCodigoP($oProductoProvVO)
	{
	    try{
	        $aProductosProv = array();
	        $dbh = DataBase::getInstance();
	        $sth = $dbh->prepare("SELECT * FROM productos_prov WHERE codigo_b=? AND codigo_p=? AND estado=1");
	        $codigoB = $oProductoProvVO->getCodigoB();
	        $sth->bindParam(1, $codigoB);
	        $codigoP = $oProductoProvVO->getCodigoP();
	        $sth->bindParam(1, $codigoP);
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
	 * Nos permite obtener un array de los productos_prov activos
	 * buscado por el código de proveedor y el código de barra.
	 * 
	 * estado = 1
	 *
	 * @param $oProductoProvVO  $codigoP
	 * @param $oProductoProvVO  $codigoB
	 * @return $aProductosProv
	 */
	public function findAllPorCodigoPCodigoB($oProductoProvVO)
	{
	    try{
	        $aProductosProv = array();
	        $dbh = DataBase::getInstance();
	        $sth = $dbh->prepare("SELECT * FROM productos_prov WHERE codigo_p=? AND codigo_b=? 
                                                                     AND estado=1");
	        $codigoP = $oProductoProvVO->getCodigoP();
	        $sth->bindParam(1, $codigoP);
	        $codigoB = $oProductoProvVO->getCodigoB();
	        $sth->bindParam(2, $codigoB);
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
	 * Nos permite obtener un array de la consulta de todos los productos_prov
	 * según las opciones seleccionadas para listado con límite
	 *
	 * @return $aProductosProv
	 */
	public function findAllOpcionListadoLimite($idProveedor, $estado, $orden, $renglonDesde, $limiteRenglones)
	{
		try{
			$this->_idProveedor = $idProveedor;
			$this->_estado = $estado;
			$this->_orden = $orden;
			$this->_renglonDesde = $renglonDesde;
			$this->_limiteRenglones = $limiteRenglones;
			$this->_opcionListado = "SELECT id, id_proveedor, codigo_b, nombre, precio, id_articulo FROM productos_prov WHERE estado=".$this->_estado;
			if ($this->_idProveedor != 0) $this->_opcionListado = $this->_opcionListado." AND id_proveedor=".$this->_idProveedor;
			if ($this->_orden != '') $this->_opcionListado = $this->_opcionListado." ORDER BY ".$this->_orden;
			$this->_opcionListado = $this->_opcionListado." LIMIT ".$this->_renglonDesde.", ".$this->_limiteRenglones;
			$aProductosProv = array();
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare($this->_opcionListado);
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
	 * Nos permite obtener un objeto de la clase VO del producto 
	 * buscado por el código de barra e Id del proveedor que este activo.
	 *
	 * @param estado = 1 (activo)
	 *
	 * @param $oProductoProvVO bigint(13) $codigoB 
	 * @param $oProductoProvVO int $idProveedor
	 * @return $oProductoProvVO
	 */
	public function findPorCodigoBProveedor($oProductoProvVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM productos_prov WHERE codigo_b=? AND id_proveedor=? 
                                                                     AND estado=1");
			$codigoB = $oProductoProvVO->getCodigoB();
			$sth->bindParam(1, $codigoB);
			$idProveedor = $oProductoProvVO->getIdProveedor();
			$sth->bindParam(2, $idProveedor);
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
	 * Nos permite obtener un objeto de la clase VO
	 * del producto activo buscado por el código de proveedor e Id del proveedor.
	 *
	 * estado = 1
	 * 
	 * @param $oProductoProvVO #varchar $codigoP
	 * @param $oProductoProvVO int $idProveedor
	 * @return $oProductoProvVO
	 */
	public function findPorCodigoPProveedor($oProductoProvVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM productos_prov WHERE codigo_p=? AND id_proveedor=?
                                                                     AND estado=1");
			$codigoP = $oProductoProvVO->getCodigoP();
			$sth->bindParam(1, $codigoP, PDO::PARAM_STR);
			$idProveedor = $oProductoProvVO->getIdProveedor();
			$sth->bindParam(2, $idProveedor);
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
	 * Nos permite obtener un array con datos de los codigos de barra
	 * repetidos de los registros de la tabla productos_prov con 
	 * estado activo.
	 * 
	 * codigo_b repetido
	 * estado = 1
	 * 
	 * @return $aProductosProv array()
	 */
	public function findAllCodigoBRepetidos()
	{
	    try{
	        $aProductosProv = array();
	        $dbh = DataBase::getInstance();
	        $sth = $dbh->prepare("SELECT codigo_b, COUNT(*) FROM productos_prov WHERE estado=1 
                                  GROUP BY codigo_b HAVING COUNT(*)>1");
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
	 * Nos permite obtener un array con datos de los codigos del proveedor
	 * repetidos de los registros de la tabla productos_prov con
	 * estado activo.
	 *
	 * codigo_p repetido
	 * 
	 * estado = 1
	 *
	 * @return $aProductosProv array()
	 */
	public function findAllCodigoPRepetidos()
	{
	    try{
	        $aProductosProv = array();
	        $dbh = DataBase::getInstance();
	        $sth = $dbh->prepare("SELECT codigo_p, COUNT(*) FROM productos_prov WHERE estado=1
                                  GROUP BY codigo_p HAVING COUNT(*)>1");
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
	 * Nos permite obtener un array con datos de los codigos de barra y de proveedor
	 * repetidos de los registros de la tabla productos_prov con
	 * estado activo.
	 *
	 * codigo_b  y codigo_p repetido
	 * estado = 1
	 *
	 * @return $aProductosProv array()
	 */
	public function findAllCodigoBCodigoPRepetidos()
	{
	    try{
	        $aProductosProv = array();
	        $dbh = DataBase::getInstance();
	        $sth = $dbh->prepare("SELECT codigo_b, codigo_p, COUNT(*) FROM productos_prov WHERE estado=1
                                  GROUP BY codigo_b, codigo_p HAVING COUNT(*)>1");
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
	 * Nos permite obtener la cantidad de productos_prov activos
	 * 
	 * estado = 1
	 * 
	 * @return int $cantidad 
	 */
	public function count()
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT count(*) FROM productos_prov WHERE estado=1");
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