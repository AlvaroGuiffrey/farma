<?php
// Se definen las clases necesarias
Clase::define('ProveedorActiveRecord');


class ProveedorModelo extends ProveedorActiveRecord
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
	 * Nos permite obtener el identificador del último registro actualizado.
	 *
	 * @return integer
	 */
	public function getLastId()
	{
		return $this->lastId;
	}

	/**
	 * Nos permite obtener un array de la consulta de todos los proveedores de
	 * referencia con listas de precios.
	 *
	 * @return $aProveedores
	 */
	public function findAllProveedoresRef()
	{
		try{
			$aProveedores = array();
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM proveedores WHERE inicial IS NOT NULL ORDER BY razon_social");
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$aProveedores = $sth->fetchAll();
			$this->cantidad = $sth->rowCount();
			$dbh=null;
			return $aProveedores;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}
	
	/**
	 * Nos permite obtener un objeto de la clase VO de la tabla
	 * proveedores buscado por el id.
	 *
	 * @param $oProveedorVO integer $id
	 * @return $oProveedorVO
	 */
	public function findPorInicial($oProveedorVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM proveedores WHERE inicial=?");
			$inicial = trim($oProveedorVO->getInicial());
			$sth->bindParam(1, $inicial);
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$fila = $sth->fetchObject();
			$this->cantidad = $sth->rowCount();
			$oProveedorVO->setId($fila->id);
			$oProveedorVO->setRazonSocial($fila->razon_social);
			$oProveedorVO->setInicial($fila->inicial);
			$oProveedorVO->setDomicilioFiscal($fila->domicilio_fiscal);
			$oProveedorVO->setCuit($fila->cuit);
			$oProveedorVO->setInscripto($fila->inscripto);
			$oProveedorVO->setIngresosBrutos($fila->ingresos_brutos);
			$oProveedorVO->setDomicilio($fila->domicilio);
			$oProveedorVO->setCodPostal($fila->cod_postal);
			$oProveedorVO->setIdLocalidad($fila->id_localidad);
			$oProveedorVO->setTelefono($fila->telefono);
			$oProveedorVO->setMovil($fila->movil);
			$oProveedorVO->setEmail($fila->email);
			$oProveedorVO->setComentario($fila->comentario);
			$oProveedorVO->setLista($fila->lista);
			$oProveedorVO->setListaOrden($fila->lista_orden);
			$oProveedorVO->setEstado($fila->estado);
			$oProveedorVO->setIdUsuarioAct($fila->id_usuario_act);
			$oProveedorVO->setFechaAct($fila->fecha_act);
			$dbh=null;
			return $oProveedorVO;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}

	/**
	 * Nos permite obtener un objeto de la clase VO de la tabla
	 * proveedores buscado por la razon social.
	 *
	 * @param $oProveedorVO varchar $razonSocial
	 * @return $oProveedorVO
	 */
	public function findPorRazonSocial($oProveedorVO)
	{
	    try{
	        $dbh = DataBase::getInstance();
	        $sth = $dbh->prepare("SELECT * FROM proveedores WHERE razon_social=?");
	        $razonSocial = trim($oProveedorVO->getRazonSocial());
	        $sth->bindParam(1, $razonSocial);
	        $sth->execute();
	        if (!$sth){
	            $oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
	            $oPDOe->errorInfo = $dbh->errorInfo();
	            throw $oPDOe;
	        }
	        $fila = $sth->fetchObject();
	        $this->cantidad = $sth->rowCount();
	        $oProveedorVO->setId($fila->id);
	        $oProveedorVO->setRazonSocial($fila->razon_social);
	        $oProveedorVO->setInicial($fila->inicial);
	        $oProveedorVO->setDomicilioFiscal($fila->domicilio_fiscal);
	        $oProveedorVO->setCuit($fila->cuit);
	        $oProveedorVO->setInscripto($fila->inscripto);
	        $oProveedorVO->setIngresosBrutos($fila->ingresos_brutos);
	        $oProveedorVO->setDomicilio($fila->domicilio);
	        $oProveedorVO->setCodPostal($fila->cod_postal);
	        $oProveedorVO->setIdLocalidad($fila->id_localidad);
	        $oProveedorVO->setTelefono($fila->telefono);
	        $oProveedorVO->setMovil($fila->movil);
	        $oProveedorVO->setEmail($fila->email);
	        $oProveedorVO->setComentario($fila->comentario);
	        $oProveedorVO->setLista($fila->lista);
	        $oProveedorVO->setListaOrden($fila->lista_orden);
	        $oProveedorVO->setEstado($fila->estado);
	        $oProveedorVO->setIdUsuarioAct($fila->id_usuario_act);
	        $oProveedorVO->setFechaAct($fila->fecha_act);
	        $dbh=null;
	        return $oProveedorVO;
	    }catch (Exception $e){
	        echo $e;
	        echo "<br/>";
	        print_r($e->errorInfo);
	    }
	}
	
	/**
	 * Nos permite obtener la cantidad de registros de la tabla
	 * proveedores.
	 *
	 * @return $cantidad
	 */
	public function count()
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT count(*) FROM proveedores");
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
	 * Nos permite obtener la cantidad de registros de proveedores de
	 * referencia de la tabla proveedores.
	 *
	 * @return $cantidad
	 */
	public function countProveedoresRef()
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT count(*) FROM proveedores WHERE inicial NOT NULL");
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