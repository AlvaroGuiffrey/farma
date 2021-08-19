<?php
// Se definen las clases necesarias
Clase::define('DataBase');
Clase::define('ActiveRecordInterface');
Clase::define('ReponerVO');

class ReponerActiveRecord implements ActiveRecord
{
    #métodos CRUD implementados en la interface ActiveRecord
    public function findAll()
    {
        try{
            $aReposiciones = array();
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT * FROM reposiciones ORDER BY id");
            $sth->execute();
            if (!$sth){
                $oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
                $oPDOe->errorInfo = $dbh->errorInfo();
                throw $oPDOe;
            }
            $aReposiciones = $sth->fetchAll();
            $this->cantidad = $sth->rowCount();
            $dbh=null;
            return $aReposiciones;
        }catch (Exception $e){
            echo $e;
            echo "<br/>";
            print_r($e->errorInfo);
        }
    }
    
    public function find($oReponerVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT * FROM reposiciones WHERE id=?");
            $id = $oReponerVO->getId();
            $sth->bindParam(1, $id);
            $sth->execute();
            if (!$sth){
                $oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
                $oPDOe->errorInfo = $dbh->errorInfo();
                throw $oPDOe;
            }
            $fila = $sth->fetchObject();
            $this->cantidad = $sth->rowCount();
            $oReponerVO->setId($fila->id);
            $oReponerVO->setCodigo($fila->codigo);
            $oReponerVO->setIdRubro($fila->id_rubro);
            $oReponerVO->setCantidad($fila->cantidad);
            $oReponerVO->setNumeroRep($fila->numero_rep);
            $oReponerVO->setFechaRep($fila->fecha_rep);
            $oReponerVO->setEstado($fila->estado);
            $oReponerVO->setIdUsuarioAct($fila->id_usuario_act);
            $oReponerVO->setFechaAct($fila->fecha_act);
            $dbh=null;
            return $oReponerVO;
        }catch (Exception $e){
            echo $e;
            echo "<br/>";
            print_r($e->errorInfo);
        }
    }
    
    public function insert($oReponerVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("INSERT INTO reposiciones (codigo, id_rubro, cantidad, numero_rep, fecha_rep, estado, id_usuario_act, fecha_act)
                                                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $codigo = $oReponerVO->getCodigo();
            $sth->bindParam(1, $codigo);
            $idRubro = $oReponerVO->getIdRubro();
            $sth->bindParam(2, $idRubro);
            $cantidad = $oReponerVO->getCantidad();
            $sth->bindParam(3, $cantidad);
            $numeroRep = $oReponerVO->getNumeroRep();
            $sth->bindParam(4, $numeroRep);
            $fechaRep = $oReponerVO->getFechaRep();
            $sth->bindParam(5, $fechaRep);
            $estado = $oReponerVO->getEstado();
            $sth->bindParam(6, $estado);
            $idUsuarioAct = $oReponerVO->getIdUsuarioAct();
            $sth->bindParam(7, $idUsuarioAct);
            $fechaAct = $oReponerVO->getFechaAct();
            $sth->bindParam(8, $fechaAct);
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
    
    public function update($oReponerVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("UPDATE reposiciones SET codigo=?, id_rubro=?, cantidad=?, numero_rep=?, fecha_rep=?, estado=?, id_usuario_act=?, fecha_act=? WHERE id=?");
            $codigo = $oReponerVO->getCodigo();
            $sth->bindParam(1, $codigo);
            $idRubro = $oReponerVO->getIdRubro();
            $sth->bindParam(2, $idRubro);
            $cantidad = $oReponerVO->getCantidad();
            $sth->bindParam(3, $cantidad);
            $numeroRep = $oReponerVO->getNumeroRep();
            $sth->bindParam(4, $numeroRep);
            $fechaRep = $oReponerVO->getFechaRep();
            $sth->bindParam(5, $fechaRep);
            $estado = $oReponerVO->getEstado();
            $sth->bindParam(6, $estado);
            $idUsuarioAct = $oReponerVO->getIdUsuarioAct();
            $sth->bindParam(7, $idUsuarioAct);
            $fechaAct = $oReponerVO->getFechaAct();
            $sth->bindParam(8, $fechaAct);
            $id = $oReponerVO->getId();
            $sth->bindParam(9, $id);
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
    
    public function delete($oReponerVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("DELETE FROM reposiciones WHERE id=?)");
            $id = $oReponerVO->getId();
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