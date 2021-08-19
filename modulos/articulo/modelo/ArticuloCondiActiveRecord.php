<?php
// Se definen las clases necesarias
Clase::define('DataBase');
Clase::define('ActiveRecordInterface');
Clase::define('ArticuloCondiVO');

class ArticuloCondiActiveRecord implements ActiveRecord
{
    #métodos CRUD implementados en la interface ActiveRecord
    
    /**
     * Nos permite obtener un array de la consulta de todos los articulos_condi 
     *
     * @param estado=1
     */
    public function findAll()
    {
        
        try{
            $aArticulosCondi = array();
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT * FROM articulos_condi WHERE estado=1");
            $sth->execute();
            $aArticulosCondi = $sth->fetchAll();
            $this->cantidad = $sth->rowCount();
            $dbh=null;
            return $aArticulosCondi;
        }catch (Exception $e){
            echo $e;
            echo "<br/>";
            print_r($e->errorInfo);
        }
        
    }
    
    /**
     * Nos permite obtener un objeto de la clase VO de la tabla
     * articulo_condi buscado por el id.
     *
     * @param $oArticuloCondiVO integer $id
     * @return $oArticuloCondiVO
     */
    public function find($oArticuloCondiVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT * FROM articulos_condi WHERE id=?");
            $id = $oArticuloCondiVO->getId();
            $sth->bindParam(1, $id);
            $sth->execute();
            if (!$sth){
                $oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
                $oPDOe->errorInfo = $dbh->errorInfo();
                throw $oPDOe;
            }
            $fila = $sth->fetchObject();
            $this->cantidad = $sth->rowCount();
            $oArticuloCondiVO->setId($fila->id);
            $oArticuloCondiVO->setIdArticulo($fila->id_articulo);
            $oArticuloCondiVO->setIdCondicion($fila->id_condicion);
            $oArticuloCondiVO->setFechaHasta($fila->fecha_hasta);
            $oArticuloCondiVO->setRotulo($fila->rotulo);
            $oArticuloCondiVO->setEstado($fila->estado);
            $oArticuloCondiVO->setIdUsuarioAct($fila->id_usuario_act);
            $oArticuloCondiVO->setFechaAct($fila->fecha_act);
            $dbh=null;
            return $oArticuloCondiVO;
        }catch (Exception $e){
            echo $e;
            echo "<br/>";
            print_r($e->errorInfo);
        }
    }
    
    /**
     * Nos permite insertar un artículo con condición en la tabla articulos_condi
     *
     * @param $oArticuloCondiVO
     */
    public function insert($oArticuloCondiVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("INSERT INTO articulos_condi
						(id_articulo, id_condicion, fecha_hasta, rotulo, estado, id_usuario_act, fecha_act)
					 	VALUES (?, ?, ?, ?, ?, ?, ?)");
            $idArticulo = $oArticuloCondiVO->getIdArticulo();
            $sth->bindParam(1, $idArticulo);
            $idCondicion = $oArticuloCondiVO->getIdCondicion();
            $sth->bindParam(2, $idCondicion);
            $fechaHasta = $oArticuloCondiVO->getFechaHasta();
            $sth->bindParam(3, $fechaHasta);
            $rotulo = $oArticuloCondiVO->getRotulo();
            $sth->bindParam(4, $rotulo);
            $estado = $oArticuloCondiVO->getEstado();
            $sth->bindParam(5, $estado);
            $idUsuarioAct = $oArticuloCondiVO->getIdUsuarioAct();
            $sth->bindParam(6, $idUsuarioAct);
            $fechaAct = $oArticuloCondiVO->getFechaAct();
            $sth->bindParam(7, $fechaAct);
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
     * Nos permite actualizar un registro en la tabla articulos_condi
     *
     * @param $oArticuloCondiVO
     */
    public function update($oArticuloCondiVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("UPDATE articulos_condi
						SET id_articulo=?, id_condicion=?, fecha_hasta=?, rotulo=?, estado=?, id_usuario_act=?, fecha_act=?
						WHERE id=?");
            $idArticulo = $oArticuloCondiVO->getIdArticulo();
            $sth->bindParam(1, $idArticulo);
            $idCondicion = $oArticuloCondiVO->getIdCondicion();
            $sth->bindParam(2, $idCondicion);
            $fechaHasta = $oArticuloCondiVO->getFechaHasta();
            $sth->bindParam(3, $fechaHasta);
            $rotulo = $oArticuloCondiVO->getRotulo();
            $sth->bindParam(4, $rotulo);
            $estado = $oArticuloCondiVO->getEstado();
            $sth->bindParam(5, $estado);
            $idUsuarioAct = $oArticuloCondiVO->getIdUsuarioAct();
            $sth->bindParam(6, $idUsuarioAct);
            $fechaAct = $oArticuloCondiVO->getFechaAct();
            $sth->bindParam(7, $fechaAct);
            $id = $oArticuloCondiVO->getId();
            $sth->bindParam(8, $id);
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
     * Nos permite eliminar registro en la tabla articulos_condi
     *
     * @param $oArticuloCondiVO
     */
    public function delete($oArticuloCondiVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("DELETE FROM articulos_condi WHERE id=:ID");
            $id = $oArticuloCondiVO->getId();
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