<?php
// Se definen las clases necesarias
Clase::define('LoginActiveRecord');

class LoginModelo extends LoginActiveRecord
{
	#propiedades
	public $_cantidad;
	
	#métodos
	public function getCantidad()
	{
		return $this->_cantidad;
	}
	
	public function findPorUsuario($oLoginVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM usuarios WHERE usuario=?");
			$usuario = $oLoginVO->getUsuario();
			$sth->bindParam(1, $usuario);
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$fila = $sth->fetchObject();
			$this->_cantidad = $sth->rowCount();
			if ($this->_cantidad > 0){
				$oLoginVO->setIdUsuario($fila->id_usuario); 
				$oLoginVO->setAlias($fila->alias);
				$oLoginVO->setUsuario($fila->usuario);
				$oLoginVO->setClave($fila->clave);
				$oLoginVO->setCategoria($fila->categoria); 
			}
			$dbh=null;
			return $oLoginVO;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}
	 
}