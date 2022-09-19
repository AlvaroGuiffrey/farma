<?php
// Se definen las clases necesarias
Clase::define('ArticuloActiveRecord');

class ArticuloModelo extends ArticuloActiveRecord
{
	#propiedades
	public $cantidad;
	public $lastId;
	private $_opcionListado;
	private $_marca;
	private $_rubro;
	private $_estado;
	private $_orden;
	private $_idProveedor;

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
	 * del articulo buscado por el código.
	 *
	 * @param $oArticuloVO string $codigo
	 * @return $oArticuloVO
	 */
	public function findPorCodigo($oArticuloVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM articulos WHERE codigo=?");
			$codigo = $oArticuloVO->getCodigo();
			$sth->bindParam(1, $codigo);
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
						$fila = $sth->fetchObject();
			$this->cantidad = $sth->rowCount();
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
			$dbh=null;
			return $oArticuloVO;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	/**
	 * Nos permite obtener un objeto de la clase VO
	 * del articulo buscado por el código de la marca.
	 *
	 * @param $oArticuloVO string $codigoM
	 * @return $oArticuloVO
	 */
	public function findPorCodigoM($oArticuloVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM articulos WHERE codigo_m=?");
			$codigoM = $oArticuloVO->getCodigoM();
			$sth->bindParam(1, $codigoM);
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$fila = $sth->fetchObject();
			$this->cantidad = $sth->rowCount();
			if ($this->cantidad > 0){
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
	 * Nos permite obtener un objeto de la clase VO
	 * del articulo "activo" buscado por el código de barra.
	 *
	 * estado = 1
	 *
	 * @param $oArticuloVO string $codigoB
	 * @return $oArticuloVO
	 */
	public function findPorCodigoB($oArticuloVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM articulos WHERE codigo_b=? AND estado=1");
			$codigoB = $oArticuloVO->getCodigoB();
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
	 * Nos permite obtener un objeto de la clase VO
	 * del articulo buscado por el nombre.
	 *
	 * @param $oArticuloVO string $nombre
	 * @return $oArticuloVO
	 */
	public function findPorNombre($oArticuloVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM articulos WHERE nombre=?");
			$nombre = $oArticuloVO->getNombre();
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
	 * Nos permite obtener un array de los articulos propios activos
	 * buscado por el codigo de barra.
	 *
	 * @param $oArticuloVO string $codigoB
	 * @return $aArticulos
	 */
	public function findAllPorCodigoB($oArticuloVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM articulos WHERE codigo_b=?
								AND codigo>9999900000
								AND estado=1
			 					ORDER BY id");
			$codigoB = $oArticuloVO->getCodigoB();
			$sth->bindParam(1, $codigoB);
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
	 * Nos permite obtener un array de los articulos
	 * buscado por parte del nombre.
	 *
	 * @param $oArticuloVO string $nombre
	 * @return $aArticulos
	 */
	public function findAllPorNombre($oArticuloVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM articulos WHERE nombre LIKE :NOMBRE ORDER BY nombre");
			$nombre = "%".$oArticuloVO->getNombre()."%";
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
	 * Nos permite obtener un array de los articulos activos
	 * buscado por parte de la presentación.
	 *
	 * estado = 1 (activo)
	 * codigo>9999900000 (artículos propios)
	 *
	 * @param $oArticuloVO string $presentacion
	 * @return $aArticulos
	 */
	public function findAllPorPresentacion($oArticuloVO)
	{
	    try{
	        $dbh = DataBase::getInstance();
	        $sth = $dbh->prepare("SELECT * FROM articulos WHERE presentacion LIKE :PRESENTACION
                                                            AND codigo>9999900000
                                                            AND estado=1
                                                            ORDER BY nombre");
	        $presentacion = "%".$oArticuloVO->getPresentacion()."%";
	        $sth->bindParam(':PRESENTACION', $presentacion, PDO::PARAM_STR);
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
	 * Nos permite obtener un array de los articulos actualizables.
	 *
	 * @param $oArticuloVO int $codigo, int $idProveedor, int $estado
	 * codigo_b > 0
	 *
	 * @return $aArticulos
	 */
	public function findAllActualizables($oArticuloVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM articulos WHERE
									codigo>? AND
									id_proveedor>? AND
									estado=? AND
									codigo_b>0
								");
			$codigo = $oArticuloVO->getCodigo();
			$sth->bindParam(1, $codigo);
			$idProveedor = $oArticuloVO->getIdProveedor();
			$sth->bindParam(2, $idProveedor);
			$estado = $oArticuloVO->getEstado();
			$sth->bindParam(3, $estado);
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
	 * Nos permite obtener un array de los articulos actualizables ordenos por nombre.
	 *
	 * @param $oArticuloVO int $codigo, int $idProveedor, int $estado
	 * codigo_b > 0
	 *
	 * @return $aArticulos ordenado por: nombre
	 */
	public function findAllActualizablesPorNombre($oArticuloVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM articulos WHERE
									codigo>? AND
									id_proveedor>? AND
									estado=? AND
									codigo_b>0
									ORDER BY nombre
								");
			$codigo = $oArticuloVO->getCodigo();
			$sth->bindParam(1, $codigo);
			$idProveedor = $oArticuloVO->getIdProveedor();
			$sth->bindParam(2, $idProveedor);
			$estado = $oArticuloVO->getEstado();
			$sth->bindParam(3, $estado);
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
	 * Nos permite obtener un array de los articulos actualizables ordenos.
	 *
	 * @param $oArticuloVO int $codigo, int $idProveedor, int $estado
	 * codigo_b > 0
	 *
	 * @return $aArticulos ordenado por: id_rubro y nombre
	 */
	public function findAllActualizablesPorRubroNombre($oArticuloVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM articulos WHERE
									codigo>? AND
									id_proveedor>? AND
									estado=? AND
									codigo_b>0
									ORDER BY id_rubro, nombre
								");
			$codigo = $oArticuloVO->getCodigo();
			$sth->bindParam(1, $codigo);
			$idProveedor = $oArticuloVO->getIdProveedor();
			$sth->bindParam(2, $idProveedor);
			$estado = $oArticuloVO->getEstado();
			$sth->bindParam(3, $estado);
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
	 * Nos permite obtener un array de los articulos etiquetables
	 * para etiquetar y buscar productos equivalentes.
	 *
	 * @return $aArticulos
	 */
	public function findAllEtiquetables()
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM articulos WHERE
									codigo>9999900000 AND
									estado=1 AND
									codigo_b>0
								");
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
	 * Nos permite obtener un array de los articulos con rótulos reservados
	 * por actualización del precio.
	 *
	 * @return $aArticulos
	 */
	public function findAllRotulos()
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM articulos WHERE
									rotulo>1 AND
									estado=1
									ORDER BY nombre
								");
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
	 * Nos permite obtener un array de los articulos con rótulos para
	 * descargar en PDF ordenados por presentación y nombre
	 *
	 * @return $aArticulos
	 */
	public function findAllRotulosPDF()
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM articulos WHERE
									rotulo=3 AND
									estado=1
									ORDER BY presentacion, nombre
								");
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
	public function findAllOpcionListado($marca, $rubro, $estado, $orden, $origen, $actualizaProv)
	{
		try{
			$this->_marca = $marca;
			$this->_rubro = $rubro;
			$this->_estado = $estado;
			$this->_orden = $orden;
			$this->_origen = $origen;
			$this->_actualizaProv = $actualizaProv;
			$this->_opcionListado = "SELECT * FROM articulos WHERE estado=".$this->_estado;
			if ($this->_marca != 0) $this->_opcionListado = $this->_opcionListado." AND id_marca=".$this->_marca;
			if ($this->_rubro != 0) $this->_opcionListado = $this->_opcionListado." AND id_rubro=".$this->_rubro;
			if ($this->_origen == 1) $this->_opcionListado = $this->_opcionListado." AND codigo < 9999900000";
			if ($this->_origen == 2){
				$this->_opcionListado = $this->_opcionListado." AND codigo > 9999900000";
				if ($this->_actualizaProv == 0) $this->_opcionListado = $this->_opcionListado." AND opcion_prov = 0";
				if ($this->_actualizaProv == 1) $this->_opcionListado = $this->_opcionListado." AND opcion_prov = 1";
				if ($this->_actualizaProv == 2) $this->_opcionListado = $this->_opcionListado." AND opcion_prov = 2";
			}
			$this->_opcionListado = $this->_opcionListado." ORDER BY ".$this->_orden;
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
	 * Nos permite obtener un array de la consulta de todos los articulos
	 * según las opciones seleccionadas para listado con límite
	 *
	 * @return $aArticulos
	 */
	public function findAllOpcionListadoLimite($marca, $rubro, $estado, $orden, $origen, $actualizaProv, $renglonDesde, $limiteRenglones)
	{
		try{
			$this->_marca = $marca;
			$this->_rubro = $rubro;
			$this->_estado = $estado;
			$this->_orden = $orden;
			$this->_origen = $origen;
			$this->_actualizaProv = $actualizaProv;
			$this->_renglonDesde = $renglonDesde;
			$this->_limiteRenglones = $limiteRenglones;
			$this->_codigo = 9999900000;
			$this->_opcionListado = "SELECT id, codigo, nombre, presentacion, precio FROM articulos WHERE estado=".$this->_estado;
			if ($this->_marca != 0) $this->_opcionListado = $this->_opcionListado." AND id_marca=".$this->_marca;
			if ($this->_rubro != 0) $this->_opcionListado = $this->_opcionListado." AND id_rubro=".$this->_rubro;
			if ($this->_origen == 1) $this->_opcionListado = $this->_opcionListado." AND codigo < ".$this->_codigo;
			if ($this->_origen == 2){
				$this->_opcionListado = $this->_opcionListado." AND codigo > ".$this->_codigo;
				if ($this->_actualizaProv == 0) $this->_opcionListado = $this->_opcionListado." AND opcion_prov = 0";
				if ($this->_actualizaProv == 1) $this->_opcionListado = $this->_opcionListado." AND opcion_prov = 1";
				if ($this->_actualizaProv == 2) $this->_opcionListado = $this->_opcionListado." AND opcion_prov = 2";
			}
			$this->_opcionListado = $this->_opcionListado." ORDER BY ".$this->_orden;
			$this->_opcionListado = $this->_opcionListado." LIMIT ".$this->_renglonDesde.", ".$this->_limiteRenglones;
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
	 * Nos permite obtener un array de la consulta de todos los articulos
	 * para listado con límite
	 *
	 * @return $aArticulos
	 */
	public function findAllLimite($renglonDesde, $limiteRenglones)
	{
		try{
			$this->_renglonDesde = $renglonDesde;
			$this->_limiteRenglones = $limiteRenglones;
			$this->_opcionListado = "SELECT * FROM articulos LIMIT ".$this->_renglonDesde.", ".$this->_limiteRenglones;
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
	 * Nos permite obtener un array con los codigos de los articulos
	 *
	 * @return $aArticulos
	 */
	public function findAllAlonCodigo()
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT codigo FROM articulos
									ORDER BY codigo ASC
								");
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
	 * Nos permite modificar la tabla para confirmar los rótulos
	 * a descargar
	 *
	 * @param array() $aRotulos
	 */
	public function updateRotulos($aRotulos, $rotulo)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("UPDATE articulos
						SET rotulo=$rotulo WHERE id IN (".implode(',',$aRotulos).")");
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
	 * Nos permite obtener la cantidad de articulos de la tabla
	 * según las opciones de listado
	 */
	public function countOpcionListado($marca, $rubro, $estado, $origen, $actualizaProv)
	{
		try{
			$this->_marca = $marca;
			$this->_rubro = $rubro;
			$this->_estado = $estado;
			$this->_codigo = 9999900000;
			$this->_origen = $origen;
			$this->_actualizaProv = $actualizaProv;
			$dbh = DataBase::getInstance();
			$this->_opcionListado = "SELECT count(*) FROM articulos";
			$this->_opcionListado = $this->_opcionListado." WHERE estado=".$this->_estado;
			if ($this->_marca != 0) $this->_opcionListado = $this->_opcionListado." AND id_marca=".$this->_marca;
			if ($this->_rubro != 0) $this->_opcionListado = $this->_opcionListado." AND id_rubro=".$this->_rubro;
			if ($this->_origen == 1) $this->_opcionListado = $this->_opcionListado." AND codigo < ".$this->_codigo;
			if ($this->_origen == 2){
				$this->_opcionListado = $this->_opcionListado." AND codigo > ".$this->_codigo;
				if ($this->_actualizaProv == 0) $this->_opcionListado = $this->_opcionListado." AND opcion_prov = 0";
				if ($this->_actualizaProv == 1) $this->_opcionListado = $this->_opcionListado." AND opcion_prov = 1";
				if ($this->_actualizaProv == 2) $this->_opcionListado = $this->_opcionListado." AND opcion_prov = 2";
			}
			$sth = $dbh->prepare($this->_opcionListado);
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
	 * Nos permite obtener la cantidad de articulos de la tabla
	 * que son actualizables según los siguientes datos:
	 * codigo > 9999900000
	 * codigo_b > 0
	 * estado > 0 - 1 artículos vigentes
	 * id_proveedor > $oArticuloVO->setIdProveedor($idProveedor)
	 * $idProveedor = 1 para contar los que se puede actualizar etiqueta de proveedor de ref
	 * $idProveedor > 1 para contar los que se puede actualizar precios.
	 */
	public function countActualizables($oArticuloVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT count(*) FROM articulos WHERE
									codigo>? AND
									id_proveedor>? AND
									estado=? AND
									codigo_b>0
								");
			$codigo = $oArticuloVO->getCodigo();
			$sth->bindParam(1, $codigo);
			$idProveedor = $oArticuloVO->getIdProveedor();
			$sth->bindParam(2, $idProveedor);
			$estado = $oArticuloVO->getEstado();
			$sth->bindParam(3, $estado);
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
	 * Nos permite obtener la cantidad de articulos de la tabla
	 * con igual codigo de barra y estado activo.
	 * estado > 0
	 * @param $oArticuloVO
	 */
	public function countCodigoB($oArticuloVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT count(*) FROM articulos WHERE
									codigo_b=? AND
									estado=1
								");
			$codigoB = $oArticuloVO->getCodigoB();
			$sth->bindParam(1, $codigoB);
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
	 * Nos permite obtener la cantidad de articulos activos de la tabla
	 * con rótulos en góndola
	 */
	public function countRotulosGondola()
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT count(*) FROM articulos WHERE
									rotulo > 0 AND
									estado = 1
								");
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
	 * Nos permite obtener la cantidad de articulos de la tabla
	 * con rótulos para descargar por modificación de precios
	 */
	public function countRotulos()
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT count(*) FROM articulos WHERE
									rotulo > 1 AND
									estado = 1
								");
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
	 * Nos permite obtener la cantidad de articulos de la tabla
	 * con rótulos para descargar por modificación de precios
	 */
	public function countRotulosPDF()
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT count(*) FROM articulos WHERE
									rotulo = 3 AND
									estado = 1
								");
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
	 * Nos permite obtener la cantidad de articulos activos de la tabla
	 * sin proveedor de referencia etiquetado.
	 */
	public function countSinProveedor()
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT count(*) FROM articulos WHERE
									codigo > 9999900000 AND
									id_proveedor < 2 AND
									estado = 1 AND
									codigo_b > 0
								");
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
	 * Nos permite obtener la cantidad de articulos activos de la tabla
	 * que son etiquetables (con o sin proveedor asignado)
	 * estado = 1 (activo)
	 * codigo > 9999900000 (artículos propios)
	 * codigo_b > 0 (tiene que tener codigo de barra)
	 */
	public function countEtiquetables()
	{
	    try{
	        $dbh = DataBase::getInstance();
	        $sth = $dbh->prepare("SELECT count(*) FROM articulos WHERE
									estado = 1 AND
									codigo > 9999900000 AND
                                    codigo_b > 0
								");
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
	 * Nos permite obtener la cantidad de articulos activos de la tabla
	 * etiquetados con proveedores de referencia
	 * id_proveedor > 0 (con proveedor de referencia asignado)
	 * estado = 1 (activo)
	 * codigo > 9999900000 (artículo propio)
	 */
	public function countEtiquetados()
	{
	    try{
	        $dbh = DataBase::getInstance();
	        $sth = $dbh->prepare("SELECT count(*) FROM articulos WHERE
									id_proveedor > 0 AND
									estado = 1 AND
									codigo > 9999900000
								");
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
	 * Nos permite obtener la cantidad de articulos activos de la tabla
	 * etiquetados al proveedor
	 */
	public function countEtiquetadosProv($idProveedor)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT count(*) FROM articulos WHERE
									id_proveedor = ? AND
									estado = 1 AND
									codigo > 9999900000
								");
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
	 * Nos permite obtener la cantidad de articulos activos
	 * buscado por parte de la presentación.
	 *
	 * estado = 1 (activo)
	 * codigo > 9999900000 (artículo propio)
	 *
	 * @param $oArticuloVO string $presentacion
	 * @return $cantidad
	 */
	public function countPorPresentacion($oArticuloVO)
	{
	    try{
	        $dbh = DataBase::getInstance();
	        $sth = $dbh->prepare("SELECT count(*) FROM articulos WHERE presentacion LIKE :PRESENTACION
                                                                    AND codigo > 9999900000
                                                                    AND estado=1");
	        $presentacion = "%".$oArticuloVO->getPresentacion()."%";
	        $sth->bindParam(':PRESENTACION', $presentacion, PDO::PARAM_STR);
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
	 * Nos permite obtener la cantidad de articulos de la tabla
	 *
	 */
	public function count()
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT count(*) FROM articulos");
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
