<?php
// Se definen las clases necesarias
Clase::define('ProductoActiveRecord');

class ProductoModelo extends ProductoActiveRecord
{
	#propiedades
	public $cantidad;
	public $lastId;
	private $_opcionListado;
	private $_idProveedor;
	private $_estado;
	private $_orden;

	#métodos
	public function getCantidad()
	{
		return $this->cantidad;
	}

	public function getLastId()
	{
		return $this->lastId;
	}

	/**
	 * Nos permite obtener un array de los productos activos
	 * buscado por el código de barra.
	 *
	 * estado = 1
	 *
	 * @param $oProductoVO  $codigoB
	 * @return $aProductos
	 */
	public function findAllPorCodigoB($oProductoVO)
	{
		try{
			$aProductos = array();
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM productos WHERE codigo_b=? AND estado=1");
			$codigoB = $oProductoVO->getCodigoB();
			$sth->bindParam(1, $codigoB);
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

	/**
	 * Nos permite obtener un array de los productos activos
	 * buscado por el código de proveedor.
	 *
	 * estado = 1
	 *
	 * @param $oProductoVO  $codigoP
	 * @return $aProductos
	 */
	public function findAllPorCodigoP($oProductoVO)
	{
	    try{
	        $aProductos = array();
	        $dbh = DataBase::getInstance();
	        $codigoP = trim($oProductoVO->getCodigoP()); // es una cadena string
	        $sth = $dbh->prepare("SELECT * FROM productos WHERE codigo_p='".$codigoP."' AND estado=1");
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

	/**
	 * Nos permite obtener un array de los productos activos
	 * buscado por el id de proveedor.
	 *
	 * estado = 1
	 *
	 * @param $oProductoVO int $idProveedor
	 * @return $aProductos
	 */
	public function findAllPorIdProveedor($oProductoVO)
	{
		try{
			$aProductos = array();
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM productos WHERE id_proveedor=? AND estado=1");
			$idProveedor = $oProductoVO->getIdProveedor();
			$sth->bindParam(1, $idProveedor);
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


	/**
	 * Nos permite obtener un array de los productos activos
	 * buscado por el id de proveedor con algunos datos para
	 * comparar con productos_prov por si el proveedor realizó modificaciones.
	 *
	 * estado = 1
	 *
	 * @param $oProductoVO int $idProveedor
	 * @return $aProductos (id, codigo_p, codigo_b, precio)
	 */
	public function findAllPorIdProveedorParaModi($oProductoVO)
	{
	    try{
	        $aProductos = array();
	        $dbh = DataBase::getInstance();
	        $sth = $dbh->prepare("SELECT id, codigo_p, codigo_b, precio, codigo_iva, tipo_descuento
                                    FROM productos WHERE id_proveedor=? AND estado=1");
	        $idProveedor = $oProductoVO->getIdProveedor();
	        $sth->bindParam(1, $idProveedor);
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

	/**
	 * Nos permite obtener un array de los productos activos
	 * buscado por el id de proveedor con algunos datos para
	 * comparar precio menor con todos los proveedores.
	 *
	 * estado = 1
	 *
	 * @param $oProductoVO int $idProveedor
	 * @return $aProductos (id, codigo_p, codigo_b, id_articulo) ordenado por: nombre
	 */
	public function findAllPorIdProveedorPorNombre($oProductoVO)
	{
	    try{
	        $aProductos = array();
	        $dbh = DataBase::getInstance();
	        $sth = $dbh->prepare("SELECT id, codigo_p, codigo_b, id_articulo
                                    FROM productos WHERE id_proveedor=? AND estado=1
                                    ORDER BY nombre");
	        $idProveedor = $oProductoVO->getIdProveedor();
	        $sth->bindParam(1, $idProveedor);
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

	/**
	 * Nos permite obtener un array de los productos
	 * buscado por el id de proveedor y por código del proveedor.
	 *
	 * @param $oProductoVO int $idProveedor
	 * @param $oProductoVO int $codigoP
	 * @return $aProductos
	 */
	public function findAllPorIdProveedorCodigoP($oProductoVO)
	{
		try{
			$aProductos = array();
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM productos WHERE id_proveedor=? codigo_p=?");
			$idProveedor = $oProductoVO->getIdProveedor();
			$sth->bindParam(1, $idProveedor);
			$codigoP = $oProductoVO->getCodigoP();
			$sth->bindParam(2, $codigoP);
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

	/**
	 * Nos permite obtener un array de los productos activos ordenados por id del proveedor
	 * buscado por el id del artículo y por código de barra.
	 *
	 * @param $oProductoVO int $idArticulo
	 * @param $oProductoVO int $codigoB
	 * @return $aProductos
	 */
	public function findAllPorIdArticuloCodigoB($oProductoVO)
	{
		try{
			$aProductos = array();
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM productos
									WHERE id_articulo=? AND codigo_b=? AND estado=1
									ORDER BY id_proveedor");
			$idArticulo = $oProductoVO->getIdArticulo();
			$sth->bindParam(1, $idArticulo);
			$codigoB = $oProductoVO->getCodigoB();
			$sth->bindParam(2, $codigoB);
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


	/**
	 * Nos permite obtener un array de la consulta de todos los productos
	 * según las opciones seleccionadas para listado con límite
	 *
	 * @return $aProductos
	 */
	public function findAllOpcionListadoLimite($idProveedor, $estado, $orden, $renglonDesde, $limiteRenglones)
	{
		try{
			$this->_idProveedor = $idProveedor;
			$this->_estado = $estado;
			$this->_orden = $orden;
			$this->_renglonDesde = $renglonDesde;
			$this->_limiteRenglones = $limiteRenglones;
			$this->_opcionListado = "SELECT id, id_proveedor, codigo_b, nombre, precio, codigo_iva, tipo_descuento, id_articulo FROM productos WHERE estado=".$this->_estado;
			if ($this->_idProveedor != 0) $this->_opcionListado = $this->_opcionListado." AND id_proveedor=".$this->_idProveedor;
			if ($this->_orden != '') $this->_opcionListado = $this->_opcionListado." ORDER BY ".$this->_orden;
			$this->_opcionListado = $this->_opcionListado." LIMIT ".$this->_renglonDesde.", ".$this->_limiteRenglones;
			$aProductos = array();
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare($this->_opcionListado);
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

	/**
	 * Nos permite obtener un objeto de la clase VO
	 * del producto buscado por el Id del articulo e Id del proveedor
	 * con estado = 1 - activo.
	 *
	 * @param $oProductoVO int $idArticulo
	 * @param $oProductoVO int $idProveedor
	 * @return $oProductoVO
	 */
	public function findPorIdArticuloProveedor($oProductoVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM productos WHERE id_articulo=? AND id_proveedor=? AND estado=1");
			$idArticulo = $oProductoVO->getIdArticulo();
			$sth->bindParam(1, $idArticulo);
			$idProveedor = $oProductoVO->getIdProveedor();
			$sth->bindParam(2, $idProveedor);
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$fila = $sth->fetchObject();
			$this->cantidad = $sth->rowCount();
			if ($this->cantidad > 0) {
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
			    return $oProductoVO;
			}
			$dbh=null;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	/**
	 * Nos permite obtener un objeto de la clase VO
	 * del producto buscado por el Id del articulo, codigo de barra e
	 * Id del proveedor con estado = 1 - activo.
	 *
	 * @param $oProductoVO int $idArticulo
	 * @param $oProductoVO int $codigoB
	 * @param $oProductoVO int $idProveedor
	 * @return $oProductoVO
	 */
	public function findPorIdArticuloCodigoBProveedor($oProductoVO)
	{
	    try{
	        $dbh = DataBase::getInstance();
	        $sth = $dbh->prepare("SELECT * FROM productos WHERE id_articulo=?
                                                          AND codigo_b=?
                                                          AND id_proveedor=?
                                                          AND estado=1");
	        $idArticulo = $oProductoVO->getIdArticulo();
	        $sth->bindParam(1, $idArticulo);
	        $codigoB = $oProductoVO->getCodigoB();
	        $sth->bindParam(2, $codigoB);
	        $idProveedor = $oProductoVO->getIdProveedor();
	        $sth->bindParam(3, $idProveedor);
	        $sth->execute();
	        if (!$sth){
	            $oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
	            $oPDOe->errorInfo = $dbh->errorInfo();
	            throw $oPDOe;
	        }
	        $fila = $sth->fetchObject();
	        $this->cantidad = $sth->rowCount();
	        if ($this->cantidad > 0) {
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
	            return $oProductoVO;
	        }
	        $dbh=null;
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
	 * @param $oProductoVO #bigint $codigoB
	 * @param $oProductoVO int $idProveedor
	 * @param estado = 1 (activo)
	 * @return $oProductoVO
	 */
	public function findPorCodigoBProveedor($oProductoVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM productos WHERE codigo_b=? AND id_proveedor=? AND estado=1");
			$codigoB = $oProductoVO->getCodigoB();
			$sth->bindParam(1, $codigoB);
			$idProveedor = $oProductoVO->getIdProveedor();
			$sth->bindParam(2, $idProveedor);
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$fila = $sth->fetchObject();
			$this->cantidad = $sth->rowCount();
			echo "db ->".$this->cantidad."<br>";
			if ($this->cantidad > 0) {
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
			    return $oProductoVO;
			}
			$dbh=null;

		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	/**
	 * Nos permite obtener un objeto de la clase VO
	 * del producto buscado por el código de proveedor
	 * y estado activo.
	 *
	 * @param $oProductoVO #varchar $codigoP
	 * @param estado = 1
	 * @return $oProductoVO
	 */
	public function findPorCodigoP($oProductoVO)
	{
	    try{
	        $codigoP = '"'.trim($oProductoVO->getCodigoP()).'"';
	        //echo "CodigoP en consulta: ".$codigoP."<br>";
	        $dbh = DataBase::getInstance();
	        $sth = $dbh->prepare("SELECT * FROM productos WHERE codigo_p like ".$codigoP." AND estado=1");
	        $sth->execute();
	        if (!$sth){
	            $oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
	            $oPDOe->errorInfo = $dbh->errorInfo();
	            throw $oPDOe;
	        }
	        $fila = $sth->fetchObject();
	        $this->cantidad = $sth->rowCount();
	        if ($this->cantidad > 0) {
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
	            return $oProductoVO;
	        }
	        $dbh=null;
	    }catch (Exception $e){
	        echo $e;
	        echo "<br/>";
	        print_r($e->errorInfo);
	    }
	}

	/**
	 * Nos permite obtener un objeto de la clase VO
	 * del producto buscado por el código de proveedor e Id del proveedor.
	 * No tiene en cuenta el estado.
	 *
	 * @param $oProductoVO #varchar $codigoP
	 * @param $oProductoVO int $idProveedor
	 * @return $oProductoVO
	 */
	public function findPorCodigoPProveedor($oProductoVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM productos WHERE codigo_p=? AND id_proveedor=?");
			$codigoP = $oProductoVO->getCodigoP();
			$sth->bindParam(1, $codigoP);
			$idProveedor = $oProductoVO->getIdProveedor();
			$sth->bindParam(2, $idProveedor);
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$fila = $sth->fetchObject();
			$this->cantidad = $sth->rowCount();
			if ($this->cantidad > 0) {
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
			    return $oProductoVO;
			}
			$dbh=null;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	/**
	 * Nos permite obtener un objeto de la clase VO
	 * del producto activo buscado por el código de proveedor
	 * e Id del proveedor.
	 *
	 * estado = 1
	 *
	 * @param $oProductoVO #varchar $codigoP
	 * @param $oProductoVO #bigint $codigoB
	 * @param $oProductoVO int $idProveedor
	 * @return $oProductoVO
	 */
	public function findPorCodigoPCodigoBProveedor($oProductoVO)
	{
	    try{
	        $dbh = DataBase::getInstance();
	        $sth = $dbh->prepare("SELECT * FROM productos WHERE codigo_p=?
                                                            AND codigo_b=?
                                                            AND id_proveedor=?
                                                            AND estado = 1");
	        $codigoP = $oProductoVO->getCodigoP();
	        $sth->bindParam(1, $codigoP);
	        $codigoB = $oProductoVO->getCodigoB();
	        $sth->bindParam(2, $codigoB);
	        $idProveedor = $oProductoVO->getIdProveedor();
	        $sth->bindParam(3, $idProveedor);
	        $sth->execute();
	        if (!$sth){
	            $oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
	            $oPDOe->errorInfo = $dbh->errorInfo();
	            throw $oPDOe;
	        }
	        $fila = $sth->fetchObject();
	        $this->cantidad = $sth->rowCount();
	        if ($this->cantidad > 0) {
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
	            return $oProductoVO;
	        }
	        $dbh=null;
	    }catch (Exception $e){
	        echo $e;
	        echo "<br/>";
	        print_r($e->errorInfo);
	    }
	}

	/**
	 * Nos permite obtener la cantidad de productos activos de todas las listas
	 * de precios de todos los proveedores
	 *
	 * @param int estado = 1
	 *
	 * @return int $cantidad
	 */
	public function count()
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT count(*) FROM productos WHERE estado=1");
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

	/**
	 * Nos permite obtener la cantidad de productos activos de la lista
	 * de precios de un proveedor
	 *
	 * estado = 1
	 *
	 * @param int $idProveedor
	 * @return int $cantidad
	 */
	public function countPorProveedor($idProveedor)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT count(*) FROM productos WHERE id_proveedor=? AND estado=1");
			$sth->bindParam(1, $idProveedor);
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

	/**
	 * Nos permite obtener la cantidad de productos activos de la lista
	 * de precios de un proveedor equivalentes con artículos.
	 *
	 *  estado = 1
	 *  id_articulo > 0
	 *
	 * @param int $idProveedor
	 * @return int $cantidad
	 */
	public function countEquivalentesPorProveedor($idProveedor)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT count(*) FROM productos WHERE
									id_proveedor=? AND
									estado=1 AND
									id_articulo>0");
			$sth->bindParam(1, $idProveedor);
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
