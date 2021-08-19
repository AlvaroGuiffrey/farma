<?php
// Se definen las clases necesarias
Clase::define('DataBase');
Clase::define('ActiveRecordInterface');
Clase::define('PendienteVO');

class PendienteActiveRecord implements ActiveRecord
{
    #métodos CRUD implementados en la interface ActiveRecord
    public function findAll()
    {
        try{
            $aPendientes = array();
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT * FROM pendientes ORDER BY id");
            $sth->execute();
            if (!$sth){
                $oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
                $oPDOe->errorInfo = $dbh->errorInfo();
                throw $oPDOe;
            }
            $aPendientes = $sth->fetchAll();
            $this->cantidad = $sth->rowCount();
            $dbh=null;
            return $aPendientes;
        }catch (Exception $e){
            echo $e;
            echo "<br/>";
            print_r($e->errorInfo);
        }
    }
    
    public function find($oPendienteVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT * FROM pendientes WHERE id=?");
            $id = $oPendienteVO->getId();
            $sth->bindParam(1, $id);
            $sth->execute();
            if (!$sth){
                $oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
                $oPDOe->errorInfo = $dbh->errorInfo();
                throw $oPDOe;
            }
            $fila = $sth->fetchObject();
            $this->cantidad = $sth->rowCount();
            $oPendienteVO->setId($fila->id);
            $oPendienteVO->setIdArticulo($fila->id_articulo);
            $oPendienteVO->setCodigo($fila->codigo);
            $oPendienteVO->setCodigoB($fila->codigo_b);
            $oPendienteVO->setIdRubro($fila->id_rubro);
            $oPendienteVO->setIdProveedor($fila->id_proveedor);
            $oPendienteVO->setCantidad($fila->cantidad);
            $oPendienteVO->setIdPedido($fila->id_pedido);
            $oPendienteVO->setEstado($fila->estado);
            $oPendienteVO->setCantidadRec($fila->cantidad_rec);
            $oPendienteVO->setFechaRec($fila->fecha_rec);
            $oPendienteVO->setComprobante($fila->comprobante);
            $oPendienteVO->setComentario($fila->comentario);
            $oPendienteVO->setIdUsuarioAct($fila->id_usuario_act);
            $oPendienteVO->setFechaAct($fila->fecha_act);
            $dbh=null;
            return $oPendienteVO;
        }catch (Exception $e){
            echo $e;
            echo "<br/>";
            print_r($e->errorInfo);
        }
    }
    
    public function insert($oPendienteVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("INSERT INTO pendientes (id_articulo, codigo, codigo_b, id_rubro, id_proveedor, cantidad, id_pedido, estado, cantidad_rec, fecha_rec, comprobante, comentario, id_usuario_act, fecha_act)
                                                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $idArticulo = $oPendienteVO->getIdArticulo();
            $sth->bindParam(1, $idArticulo);
            $codigo = $oPendienteVO->getCodigo();
            $sth->bindParam(2, $codigo);
            $codigoB = $oPendienteVO->getCodigoB();
            $sth->bindParam(3, $codigoB);
            $idRubro = $oPendienteVO->getIdRubro();
            $sth->bindParam(4, $idRubro);
            $idProveedor = $oPendienteVO->getIdProveedor();
            $sth->bindParam(5, $idProveedor);
            $cantidad = $oPendienteVO->getCantidad();
            $sth->bindParam(6, $cantidad);
            $idPedido = $oPendienteVO->getIdPedido();
            $sth->bindParam(7, $idPedido);
            $estado = $oPendienteVO->getEstado();
            $sth->bindParam(8, $estado);
            $cantidadRec = $oPendienteVO->getCantidadRec();
            $sth->bindParam(9, $cantidadRec);
            $fechaRec = $oPendienteVO->getFechaRec();
            $sth->bindParam(10, $fechaRec);
            $comprobante = $oPendienteVO->getComprobante();
            $sth->bindParam(11, $comprobante);
            $comentario = $oPendienteVO->getComentario();
            $sth->bindParam(12, $comentario);
            $idUsuarioAct = $oPendienteVO->getIdUsuarioAct();
            $sth->bindParam(13, $idUsuarioAct);
            $fechaAct = $oPendienteVO->getFechaAct();
            $sth->bindParam(14, $fechaAct);
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
    
    public function update($oPendienteVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("UPDATE pendientes SET id_articulo=?, codigo=?, codigo_b=?, id_rubro=?, id_proveedor=?, cantidad=?, id_pedido=?, estado=?, cantidad_rec=?, fecha_rec=?, comprobante=?, comentario=?, id_usuario_act=?, fecha_act=? WHERE id=?");
            $idArticulo = $oPendienteVO->getIdArticulo();
            $sth->bindParam(1, $idArticulo);
            $codigo = $oPendienteVO->getCodigo();
            $sth->bindParam(2, $codigo);
            $codigoB = $oPendienteVO->getCodigoB();
            $sth->bindParam(3, $codigoB);
            $idRubro = $oPendienteVO->getIdRubro();
            $sth->bindParam(4, $idRubro);
            $idProveedor = $oPendienteVO->getIdProveedor();
            $sth->bindParam(5, $idProveedor);
            $cantidad = $oPendienteVO->getCantidad();
            $sth->bindParam(6, $cantidad);
            $idPedido = $oPendienteVO->getIdPedido();
            $sth->bindParam(7, $idPedido);
            $estado = $oPendienteVO->getEstado();
            $sth->bindParam(8, $estado);
            $cantidadRec = $oPendienteVO->getCantidadRec();
            $sth->bindParam(9, $cantidadRec);
            $fechaRec = $oPendienteVO->getFechaRec();
            $sth->bindParam(10, $fechaRec);
            $comprobante = $oPendienteVO->getComprobante();
            $sth->bindParam(11, $comprobante);
            $comentario = $oPendienteVO->getComentario();
            $sth->bindParam(12, $comentario);
            $idUsuarioAct = $oPendienteVO->getIdUsuarioAct();
            $sth->bindParam(13, $idUsuarioAct);
            $fechaAct = $oPendienteVO->getFechaAct();
            $sth->bindParam(14, $fechaAct);
            $id = $oPendienteVO->getId();
            $sth->bindParam(15, $id);
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
    
    public function delete($oPendienteVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("DELETE FROM pendientes WHERE id=?)");
            $id = $oPendienteVO->getId();
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