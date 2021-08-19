<?php
// Se definen las clases necesarias
Clase::define('DataBase');
Clase::define('ActiveRecordInterface');
Clase::define('PedidoVO');

class PedidoActiveRecord implements ActiveRecord
{
    #métodos CRUD implementados en la interface ActiveRecord
    public function findAll()
    {
        try{
            $aPedidos = array();
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT * FROM pedidos ORDER BY id");
            $sth->execute();
            if (!$sth){
                $oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
                $oPDOe->errorInfo = $dbh->errorInfo();
                throw $oPDOe;
            }
            $aPedidos = $sth->fetchAll();
            $this->cantidad = $sth->rowCount();
            $dbh=null;
            return $aPedidos;
        }catch (Exception $e){
            echo $e;
            echo "<br/>";
            print_r($e->errorInfo);
        }
    }
    
    public function find($oPedidoVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT * FROM pedidos WHERE id=?");
            $id = $oPedidoVO->getId();
            $sth->bindParam(1, $id);
            $sth->execute();
            if (!$sth){
                $oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
                $oPDOe->errorInfo = $dbh->errorInfo();
                throw $oPDOe;
            }
            $fila = $sth->fetchObject();
            $this->cantidad = $sth->rowCount();
            $oPedidoVO->setId($fila->id);
            $oPedidoVO->setIdProveedor($fila->id_proveedor);
            $oPedidoVO->setFecha($fila->fecha);
            $oPedidoVO->setCanal($fila->canal);
            $oPedidoVO->setEstado($fila->estado);
            $oPedidoVO->setFechaRec($fila->fecha_rec);
            $oPedidoVO->setComentario($fila->comentario);
            $oPedidoVO->setIdUsuarioAct($fila->id_usuario_act);
            $oPedidoVO->setFechaAct($fila->fecha_act);
            $dbh=null;
            return $oPedidoVO;
        }catch (Exception $e){
            echo $e;
            echo "<br/>";
            print_r($e->errorInfo);
        }
    }
    
    public function insert($oPedidoVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("INSERT INTO pedidos (id_proveedor, fecha, canal, estado, fecha_rec, comentario, id_usuario_act, fecha_act)
                                                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $idProveedor = $oPedidoVO->getIdProveedor();
            $sth->bindParam(1, $idProveedor);
            $fecha = $oPedidoVO->getFecha();
            $sth->bindParam(2, $fecha);
            $canal = $oPedidoVO->getCanal();
            $sth->bindParam(3, $canal);
            $estado = $oPedidoVO->getEstado();
            $sth->bindParam(4, $estado);
            $fechaRec = $oPedidoVO->getFechaRec();
            $sth->bindParam(5, $fechaRec);
            $comentario = $oPedidoVO->getComentario();
            $sth->bindParam(6, $comentario);
            $idUsuarioAct = $oPedidoVO->getIdUsuarioAct();
            $sth->bindParam(7, $idUsuarioAct);
            $fechaAct = $oPedidoVO->getFechaAct();
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
    
    public function update($oPedidoVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("UPDATE pedidos SET id_proveedor=?, fecha=?, canal=?, estado=?, fecha_rec=?, comentario=?, id_usuario_act=?, fecha_act=? WHERE id=?");
            $idProveedor = $oPedidoVO->getIdProveedor();
            $sth->bindParam(1, $idProveedor);
            $fecha = $oPedidoVO->getFecha();
            $sth->bindParam(2, $fecha);
            $canal = $oPedidoVO->getCanal();
            $sth->bindParam(3, $canal);
            $estado = $oPedidoVO->getEstado();
            $sth->bindParam(4, $estado);
            $fechaRec = $oPedidoVO->getFechaRec();
            $sth->bindParam(5, $fechaRec);
            $comentario = $oPedidoVO->getComentario();
            $sth->bindParam(6, $comentario);
            $idUsuarioAct = $oPedidoVO->getIdUsuarioAct();
            $sth->bindParam(7, $idUsuarioAct);
            $fechaAct = $oPedidoVO->getFechaAct();
            $sth->bindParam(8, $fechaAct);
            $id = $oPedidoVO->getId();
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
    
    public function delete($oPedidoVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("DELETE FROM pedidos WHERE id=?)");
            $id = $oPedidoVO->getId();
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