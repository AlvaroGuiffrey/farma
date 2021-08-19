<?php
// Se definen las clases necesarias
Clase::define('PendienteActActiveRecord');

class PendienteActModelo extends PendienteActActiveRecord
{
    #propiedades
    public $cantidad;
    public $lastId;
    private $_opcionListado;
    private $_idFactura;
    private $_estado;
    private $_orden;
    
    #métodos
    public function getCantidad()
    {
        return $this->cantidad;
    }
    
    public function getLastId()
    {
        return $this->lastId;
    }
   
    /**
     * Nos permite obtener la cantidad de facturas de PLEX pendientes actualizadas.
     *
     * @return int $cantidad
     */
    public function count()
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT count(*) FROM pendientes_act");
            $sth->execute();
            if (!$sth){
                $oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
                $oPDOe->errorInfo = $dbh->errorInfo();
                throw $oPDOe;
            }
            $this->cantidad = $sth->fetchColumn();
            $dbh=null;
            
        }catch (Exception $e){
            echo $e;
            echo "<br/>";
            print_r($e->errorInfo);
        }
    }

    /**
     * Nos permite obtener el último registro de pendientes actualizadas.
     *
     * @return int $id
     */
    public function lastId()
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT * FROM pendientes_act ORDER BY id DESC LIMIT 1");
            $sth->execute();
            if (!$sth){
                $oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
                $oPDOe->errorInfo = $dbh->errorInfo();
                throw $oPDOe;
            }
            $fila = $sth->fetchObject();
            $this->cantidad = $sth->rowCount();
            if ($this->cantidad > 0){
                $this->lastId = $fila->id; 
 
            }
            $dbh=null;
 

        }catch (Exception $e){
            echo $e;
            echo "<br/>";
            print_r($e->errorInfo);
        }
 
    }
}
?>