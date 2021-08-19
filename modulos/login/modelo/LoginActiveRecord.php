<?php
// Se definen las clases necesarias
Clase::define('DataBase');
Clase::define('ActiveRecordInterface');
Clase::define('LoginVO');

class LoginActiveRecord implements ActiveRecord
{
	#métodos CRUD implementados en la interface ActiveRecord
	public function findAll()
	{
		try{
			$aLoginVO = array();
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM usuarios");
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			while ($fila = mysql_fetch_object($sth) ) {
				$oLoginVO = new LoginVO();
				$oLoginVO->setIdUsuario($fila->id_usuario); 
				$oLoginVO->setAlias($fila->alias);
				$oLoginVO->setUsuario($fila->usuario);
				$oLoginVO->setClave($fila->clave);
				$oLoginVO->setCategoria($fila->categoria); 
				$aLoginVO[] = $oLoginVO;
				unset($oLoginVO);
			}
			$this->_cantidad = $sth->rowCount();
			$dbh=null;
			return $aLoginVO;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}
	
	public function find($oLoginVO)
	{ 
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("SELECT * FROM usuarios WHERE id_usuario=?");
			$idUsuario = $oLoginVO->getIdUsuario();
			$sth->bindParam(1, $idUsuario);
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$fila = $sth->fetchObject();
			$this->_cantidad = $sth->rowCount();
			$oLoginVO->setIdUsuario($fila->id_usuario); 
			$oLoginVO->setAlias($fila->alias);
			$oLoginVO->setUsuario($fila->usuario);
			$oLoginVO->setClave($fila->clave);
			$oLoginVO->setCategoria($fila->categoria); 
			$dbh=null;
			return $oLoginVO;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}
	
	public function insert($oLoginVO)
	{
		try{
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare("INSERT INTO usuarios(alias, usuario, clave, categoria) VALUES (?, ?, ?, ?)");
			$alias = $oLoginVO->getAlias();
			$sth->bindParam(1, $alias);
			$usuario = $oLoginVO->getUsuario();
			$sth->bindParam(2, $usuario);
			$clave = $oLoginVO->getClave();
			$sth->bindParam(3, $clave);
			$categoria = $oLoginVO->getCategoria();
			$sth->bindParam(4, $categoria);
			$sth->execute();
			if (!$sth){
				$oPDOe = new PDOException('Problema al intentar insertar datos.', 1);
				$oPDOe->errorInfo = $dbh->errorInfo();
				throw $oPDOe;
			}
			$this->_cantidad = $sth->rowCount();
			$dbh=null;
			return true;
		}catch (Exception $e){
			echo $e;
			echo "<br/>";
			print_r($e->errorInfo);
		}
	}
	
	public function update($oLoginVO)
	{
		
	}
	
	public function delete($oLoginVO)
	{
		
	}

}
?>