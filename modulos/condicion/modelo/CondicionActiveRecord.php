<?php
// Se definen las clases necesarias
Clase::define('DataBase');
Clase::define('ActiveRecordInterface');
Clase::define('CondicionVO');

class CondicionActiveRecord implements ActiveRecord
{
    #métodos CRUD implementados en la interface ActiveRecord
    
    /**
     * Nos permite obtener un array de la consulta de todas las condiciones activas
     * ordenadas por nombre
     *
     * @param estado = 1
     * @return $aCondiciones
     */
    public function findAll()
    {
        
        $aCondiciones = array();
        $dbh = DataBase::getInstance();
        try{
            $aCondiciones = array();
            $dbh = DataBase::getInstance();
            //$sth = $dbh->prepare("SELECT * FROM condiciones ORDER BY nombre");
            $sth = $dbh->prepare("SELECT * FROM condiciones WHERE estado=1 ORDER BY nombre");
            $sth->execute();
            /*
             if (!$sth){
             $oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
             $oPDOe->errorInfo = $dbh->errorInfo();
             throw $oPDOe;
             }
             */
            $aCondiciones = $sth->fetchAll();
            $this->cantidad = $sth->rowCount();
            $dbh=null;
            return $aCondiciones;
        }catch (Exception $e){
            echo $e;
            echo "<br/>";
            print_r($e->errorInfo);
        }
        
    }
    
    /**
     * Nos permite obtener un objeto de la clase VO de la tabla
     * condiciones buscado por el id.
     *
     * @param $oCondicionVO integer $id
     * @return $oCondicionVO
     */
    public function find($oCondicionVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT * FROM condiciones WHERE id=?");
            $id = $oCondicionVO->getId();
            $sth->bindParam(1, $id);
            $sth->execute();
            if (!$sth){
                $oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
                $oPDOe->errorInfo = $dbh->errorInfo();
                throw $oPDOe;
            }
            $fila = $sth->fetchObject();
            $this->cantidad = $sth->rowCount();
            $oCondicionVO->setId($fila->id);
            $oCondicionVO->setIdTipo($fila->id_tipo);
            $oCondicionVO->setNombre($fila->nombre);
            $oCondicionVO->setComentario($fila->comentario);
            $oCondicionVO->setCantidadUn($fila->cantidad_un);
            $oCondicionVO->setCantidadPaga($fila->cantidad_paga);
            $oCondicionVO->setDescuento($fila->descuento);
            $oCondicionVO->setCuota($fila->cuota);
            $oCondicionVO->setEstado($fila->estado);
            $oCondicionVO->setIdUsuarioAct($fila->id_usuario_act);
            $oCondicionVO->setFechaAct($fila->fecha_act);
            $dbh=null;
            return $oCondicionVO;
        }catch (Exception $e){
            echo $e;
            echo "<br/>";
            print_r($e->errorInfo);
        }
    }
    
    /**
     * Nos permite insertar una condición en la tabla condiciones
     *
     * @param $oCondicionVO
     */
    public function insert($oCondicionVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("INSERT INTO condiciones
						(id_tipo, nombre, comentario, cantidad, cantidad_paga, descuento, cuota, estado, id_usuario_act, fecha_act)
					 	VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $id_tipo = $oCondicionVO->getIdTipo();
            $sth->bindParam(1, $id_tipo);
            $nombre = $oCondicionVO->getNombre();
            $sth->bindParam(2, $nombre);
            $comentario = $oCondicionVO->getComentario();
            $sth->bindParam(3, $comentario);
            $cantidadUn = $oCondicionVO->getCantidadUn();
            $sth->bindParam(4, $cantidadUn);
            $cantidadPaga = $oCondicionVO->getCantidadPaga();
            $sth->bindParam(5, $cantidadPaga);
            $descuento = $oCondicionVO->getDescuento();
            $sth->bindParam(6, $descuento);
            $cuota = $oCondicionVO->getCuota();
            $sth->bindParam(7, $cuota);
            $estado = $oCondicionVO->getEstado();
            $sth->bindParam(8, $estado);
            $idUsuarioAct = $oCondicionVO->getIdUsuarioAct();
            $sth->bindParam(9, $idUsuarioAct);
            $fechaAct = $oCondicionVO->getFechaAct();
            $sth->bindParam(10, $fechaAct);
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
     * Nos permite actualizar un registro en la tabla condiciones
     *
     * @param $oCondicionVO
     */
    public function update($oCondicionVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("UPDATE condiciones
						SET id_tipo=?, nombre=?, comentario=?, cantidad_un=?, cantidad_paga=?, descuento=?, cuota=?, estado=?, id_usuario_act=?, fecha_act=?
						WHERE id=?");
            $id_tipo = $oCondicionVO->getIdTipo();
            $sth->bindParam(1, $id_tipo);
            $nombre = $oCondicionVO->getNombre();
            $sth->bindParam(2, $nombre);
            $comentario = $oCondicionVO->getComentario();
            $sth->bindParam(3, $comentario);
            $cantidadUn = $oCondicionVO->getCantidadUn();
            $sth->bindParam(4, $cantidadUn);
            $cantidadPaga = $oCondicionVO->getCantidadPaga();
            $sth->bindParam(5, $cantidadPaga);
            $descuento = $oCondicionVO->getDescuento();
            $sth->bindParam(6, $descuento);
            $cuota = $oCondicionVO->getCuota();
            $sth->bindParam(7, $cuota);
            $estado = $oCondicionVO->getEstado();
            $sth->bindParam(8, $estado);
            $idUsuarioAct = $oCondicionVO->getIdUsuarioAct();
            $sth->bindParam(9, $idUsuarioAct);
            $fechaAct = $oCondicionVO->getFechaAct();
            $sth->bindParam(10, $fechaAct);
            $id = $oCondicionVO->getId();
            $sth->bindParam(11, $id);
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
     * Nos permite eliminar registro en la tabla condiciones
     *
     * @param $oCondicionVO
     */
    public function delete($oCondicionVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("DELETE FROM condiciones WHERE id=:ID");
            $id = $oCondicionVO->getId();
            echo "Id (".$id.") - ";
            $sth->bindParam(':ID', $id, PDO::PARAM_INT);
            $sth->execute();
            if (!$sth){
                $oPDOe = new PDOException('Problema al intentar deletear datos.', 1);
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