<?php
// Se definen las clases necesarias
Clase::define('DataBase');
Clase::define('ActiveRecordInterface');
Clase::define('CondicionTipoVO');

class CondicionTipoActiveRecord implements ActiveRecord
{
    #métodos CRUD implementados en la interface ActiveRecord
    
    /**
     * Nos permite obtener un array de la consulta de todos los tipos
     * de condiciones de ventas especiales
     *
     * @return $aCondicionTipo
     */
    public function findAll()
    {
        try{
            $aCondicionTipo = array();
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT * FROM condiciones_tipos ORDER BY id");
            $sth->execute();
            if (!$sth){
                $oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
                $oPDOe->errorInfo = $dbh->errorInfo();
                throw $oPDOe;
            }
            $aCondicionTipo = $sth->fetchAll();
            $this->cantidad = $sth->rowCount();
            $dbh=null;
            return $aCondicionTipo;
        }catch (Exception $e){
            echo $e;
            echo "<br/>";
            print_r($e->errorInfo);
        }
    }
    
    /**
     * Nos permite obtener un objeto de la clase VO de la tabla
     * condiciones_tipos buscado por el índice.
     *
     * @param $oCondicionTipoVO integer $id
     * @return $oCondicionTipoVO
     */
    public function find($oCondicionTipoVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT * FROM condiciones_tipos WHERE id=?");
            $id = $oCondicionTipoVO->getId();
            $sth->bindParam(1, $id);
            $sth->execute();
            if (!$sth){
                $oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
                $oPDOe->errorInfo = $dbh->errorInfo();
                throw $oPDOe;
            }
            $fila = $sth->fetchObject();
            $this->cantidad = $sth->rowCount();
            $oCondicionTipoVO->setId($fila->id);
            $oCondicionTipoVO->setNombre($fila->nombre);
            $oCondicionTipoVO->setTipo($fila->tipo);
            $dbh=null;
            return $oCondicionTipoVO;
        }catch (Exception $e){
            echo $e;
            echo "<br/>";
            print_r($e->errorInfo);
        }
    }
    
    /**
     * Nos permite insertar un registro en la tabla condiciones_tipos
     *
     * @param $oCondicionTipoVO
     */
    public function insert($oCondicionTipoVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("INSERT INTO condiciones_tipos(nombre, tipo) VALUES (?, ?)");
            $nombre = $oCondicionTipoVO->getNombre();
            $sth->bindParam(1, $nombre);
            $tipo = $oCondicionTipoVO->getTipo();
            $sth->bindParam(2, $tipo);
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
     * Nos permite actualizar un registro en la tabla condiciones_tipos
     *
     * @param $oCondicionTipoVO
     */
    public function update($oCondicionTipoVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("UPDATE condiciones_tipos SET nombre=?, tipo=? WHERE id=?");
            $nombre = $oCondicionTipoVO->getNombre();
            $sth->bindParam(1, $nombre);
            $tipo = $oCondicionTipoVO->getTipo();
            $sth->bindParam(2, $tipo);
            $id = $oCondicionTipoVO->getId();
            $sth->bindParam(3, $id);
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
     * Nos permite eliminar registro en la tabla condiciones_tipos
     *
     * @param $oCondicionTipoVO
     */
    public function delete($oCondicionTipoVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("DELETE FROM condiciones_tipos WHERE id=?)");
            $id = $oCondicionTipoVO->getId();
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
