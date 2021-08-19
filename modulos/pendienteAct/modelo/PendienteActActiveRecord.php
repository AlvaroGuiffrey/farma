<?php
// Se definen las clases necesarias
Clase::define('DataBase');
Clase::define('ActiveRecordInterface');
Clase::define('PendienteActVO');

class PendienteActActiveRecord implements ActiveRecord
{
    #métodos CRUD implementados en la interface ActiveRecord
    public function findAll()
    {
        try{
            $aPendientesAct = array();
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT * FROM pendientes_act ORDER BY id_factura");
            $sth->execute();
            if (!$sth){
                $oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
                $oPDOe->errorInfo = $dbh->errorInfo();
                throw $oPDOe;
            }
            $aPendientesAct = $sth->fetchAll();
            $this->cantidad = $sth->rowCount();
            $dbh=null;
            return $aPendientesAct;
        }catch (Exception $e){
            echo $e;
            echo "<br/>";
            print_r($e->errorInfo);
        }
    }
    
    public function find($oPendienteActVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT * FROM pendientes_act WHERE id=?");
            $id = $oPendienteActVO->getId();
            $sth->bindParam(1, $id);
            $sth->execute();
            if (!$sth){
                $oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
                $oPDOe->errorInfo = $dbh->errorInfo();
                throw $oPDOe;
            }
            $fila = $sth->fetchObject();
            $this->cantidad = $sth->rowCount();
            $oPendienteActVO->setId($fila->id);
            $oPendienteActVO->setIdFactura($fila->id_factura);
            $oPendienteActVO->setFecha($fila->fecha);
            $oPendienteActVO->setEstado($fila->estado);
            $oPendienteActVO->setIdUsuarioAct($fila->id_usuario_act);
            $oPendienteActVO->setFechaAct($fila->fecha_act);
            $dbh=null;
            return $oPendienteActVO;
        }catch (Exception $e){
            echo $e;
            echo "<br/>";
            print_r($e->errorInfo);
        }
    }
  
    public function insert($oPendienteActVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("INSERT INTO pendientes_act(id_factura, fecha, estado, id_usuario_act, fecha_act) VALUES (?, ?, ?, ?, ?)");
            $idFactura = $oPendienteActVO->getIdFactura();
            $sth->bindParam(1, $idFactura);
            $fecha = $oPendienteActVO->getFecha();
            $sth->bindParam(2, $fecha);
            $estado = $oPendienteActVO->getEstado();
            $sth->bindParam(3, $estado);
            $idUsuarioAct = $oPendienteActVO->getIdUsuarioAct();
            $sth->bindParam(4, $idUsuarioAct);
            $fechaAct = $oPendienteActVO->getFechaAct();
            $sth->bindParam(5, $fechaAct);
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
    
    public function update($oPendienteActVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("UPDATE pendientes_act SET id_factura=?, fecha=?, estado=?, id_usuario_act=?, fecha_act=? WHERE id=?");
            $idFactura = $oPendienteActVO->getIdFactura();
            $sth->bindParam(1, $idFactura);
            $fecha = $oPendienteActVO->getFecha();
            $sth->bindParam(2, $fecha);
            $estado = $oPendienteActVO->getEstado();
            $sth->bindParam(3, $estado);
            $idUsuarioAct = $oPendienteActVO->getIdUsuarioAct();
            $sth->bindParam(4, $idUsuarioAct);
            $fechaAct = $oPendienteActVO->getFechaAct();
            $sth->bindParam(5, $fechaAct);
            $id = $oPendienteActVO->getId();
            $sth->bindParam(6, $id);
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
    
    public function delete($oPendienteActVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("DELETE FROM pendientes_act WHERE id=?)");
            $id = $oPendienteActVO->getId();
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