<?php
// Se definen las clases necesarias 
Clase::define('DataBase');
Clase::define('ActiveRecordInterface');
Clase::define('ProveedorVO');

class ProveedorActiveRecord implements ActiveRecord
{
	#métodos CRUD implementados en la interface ActiveRecord

	/**
	 * Nos permite obtener un array de la consulta de todos los proveedores.
	 *
	 * @return $aProveedores
	 */
	public function findAll()
	{
		try{
			$aProveedores = array();
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM proveedores ORDER BY razon_social");
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
	public function find($oProveedorVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM proveedores WHERE id=?");
			$id = trim($oProveedorVO->getId());
			$sth->bindParam(1, $id);
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
	 * Nos permite insertar un registro en la tabla proveedores
	 *
	 * @param $oProveedorVO
	 */
	public function insert($oProveedorVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("INSERT INTO proveedores
						(razon_social, inicial, domicilio_fiscal, cuit, inscripto, ingresos_brutos, domicilio, cod_postal, id_localidad, telefono, movil, email, comentario, lista, lista_orden, estado, id_usuario_act, fecha_act)
					 	VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$razonSocial = $oProveedorVO->getRazonSocial();
			$sth->bindParam(1, $razonSocial);
			$inicial = $oProveedorVO->getInicial();
			$sth->bindParam(2, $inicial);
			$domicilioFiscal = $oProveedorVO->getDomicilioFiscal();
			$sth->bindParam(3, $domicilioFiscal);
			$cuit = $oProveedorVO->getCuit();
			$sth->bindParam(4, $cuit);
			$inscripto = $oProveedorVO->getInscripto();
			$sth->bindParam(5, $inscripto);
			$ingresosBrutos = $oProveedorVO->getIngresosBrutos();
			$sth->bindParam(6, $ingresosBrutos);
			$domicilio = $oProveedorVO->getDomicilio();
			$sth->bindParam(7, $domicilio);
			$codPostal = $oProveedorVO->getCodPostal();
			$sth->bindParam(8, $codPostal);
			$idLocalidad = $oProveedorVO->getIdLocalidad();
			$sth->bindParam(9, $idLocalidad);
			$telefono = $oProveedorVO->getTelefono();
			$sth->bindParam(10, $telefono);
			$movil = $oProveedorVO->getMovil();
			$sth->bindParam(11, $movil);
			$email = $oProveedorVO->getEmail();
			$sth->bindParam(12, $email);
			$comentario = $oProveedorVO->getComentario();
			$sth->bindParam(13, $comentario);
			$lista = $oProveedorVO->getLista();
			$sth->bindParam(14, $lista);
			$listaOrden = $oProveedorVO->getListaOrden();
			$sth->bindParam(15, $listaOrden);
			$estado = $oProveedorVO->getEstado();
			$sth->bindParam(16, $estado);
			$idUsuarioAct = $oProveedorVO->getIdUsuarioAct();
			$sth->bindParam(17, $idUsuarioAct);
			$fechaAct = $oProveedorVO->getFechaAct();
			$sth->bindParam(18, $fechaAct);
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
	 * Nos permite actualizar un registro en la tabla proveedores
	 *
	 * @param $oProveedorVO
	 */
	public function update($oProveedorVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("UPDATE proveedores
						SET razon_social=?, inicial=?, domicilio_fiscal=?, cuit=?, inscripto=?, ingresos_brutos=?, domicilio=?, cod_postal=?, id_localidad=?, telefono=?, movil=?, email=?, comentario=?, lista=?, lista_orden=?, estado=?, id_usuario_act=?, fecha_act=?
						WHERE id=?");
			$razonSocial = $oProveedorVO->getRazonSocial();
			$sth->bindParam(1, $razonSocial);
			$inicial = $oProveedorVO->getInicial();
			$sth->bindParam(2, $inicial);
			$domicilioFiscal = $oProveedorVO->getDomicilioFiscal();
			$sth->bindParam(3, $domicilioFiscal);
			$cuit = $oProveedorVO->getCuit();
			$sth->bindParam(4, $cuit);
			$inscripto = $oProveedorVO->getInscripto();
			$sth->bindParam(5, $inscripto);
			$ingresosBrutos = $oProveedorVO->getIngresosBrutos();
			$sth->bindParam(6, $ingresosBrutos);
			$domicilio = $oProveedorVO->getDomicilio();
			$sth->bindParam(7, $domicilio);
			$idCodPostal = $oProveedorVO->getCodPostal();
			$sth->bindParam(8, $idCodPostal);
			$idLocalidad = $oProveedorVO->getIdLocalidad();
			$sth->bindParam(9, $idLocalidad);
			$telefono = $oProveedorVO->getTelefono();
			$sth->bindParam(10, $telefono);
			$movil = $oProveedorVO->getMovil();
			$sth->bindParam(11, $movil);
			$email = $oProveedorVO->getEmail();
			$sth->bindParam(12, $email);
			$comentario = $oProveedorVO->getComentario();
			$sth->bindParam(13, $comentario);
			$lista = $oProveedorVO->getLista();
			$sth->bindParam(14, $lista);
			$listaOrden = $oProveedorVO->getListaOrden();
			$sth->bindParam(15, $listaOrden);
			$estado = $oProveedorVO->getEstado();
			$sth->bindParam(16, $estado);
			$idUsuarioAct = $oProveedorVO->getIdUsuarioAct();
			$sth->bindParam(17, $idUsuarioAct);
			$fechaAct = $oProveedorVO->getFechaAct();
			$sth->bindParam(18, $fechaAct);
			$id = $oProveedorVO->getId();
			$sth->bindParam(19, $id);
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
	 * Nos permite eliminar un registro en la tabla proveedores
	 *
	 * @param $oProveedorVO
	 */
	public function delete($oProveedorVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("DELETE FROM proveedores WHERE id=?)");
			$id = $oProveedorVO->getId();
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