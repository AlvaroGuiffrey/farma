<?php
// Se definen las clases necesarias
Clase::define('PedidoActiveRecord');

class PedidoModelo extends PedidoActiveRecord
{
    #propiedades
    public $cantidad;
    public $lastId;
    private $_opcionListado;
    private $_id;
    private $_idProveedor;
    private $_fecha;
    private $_canal;
    private $_estado;
    private $_fechaRec;
    private $_comentario;
    private $_idUsuarioAct;
    private $_fechaAct;
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
     * Nos permite obtener un array con todos los registros
     * de pedidos realizados a un proveedor 
     *
     * @param $oPedidoVO int $idProveedor
     * @return array $aPedidos 
     */
    public function findAllProveedor($oPedidoVO)
    {
        try{
            $aPedidos = array();
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT * FROM pedidos
                                            WHERE id_proveedor=?
                                            ORDER BY id");
            $idProveedor = $oPedidoVO->getIdProveedor();
            $sth->bindParam(1, $idProveedor);
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
    

    /**
     * Nos permite modificar la tabla pedidos para asignar un estado 
     * 0 (inactivo) a pedidos
     *
     * @param array() $aPedidos
     *
     */
    public function updateEstadoInactivo($aPedidos)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("UPDATE pedidos
						SET estado=0
                        WHERE id IN (".implode(',',$aPedidos).")");
            $sth->execute();
            if (!$sth){
                $oPDOe = new PDOException('Problema al intentar modificar datos.', 1);
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
     * Nos permite obtener un array de la consulta de todos los 
     * pedidos según las opciones seleccionadas para listado
     *
     * @return $aPedidos
     */
    public function findAllOpcionListado($proveedor, $fechaDesde, $fechaHasta)
    {
        try{
            $this->_proveedor = $proveedor;
            $this->_fechaDesde = $fechaDesde;
            $this->_fechaHasta = $fechaHasta;
            $this->_opcionListado = "SELECT * FROM pedidos WHERE ";
            if ($this->_proveedor != 0) $this->_opcionListado = $this->_opcionListado."id_proveedor=".$this->_proveedor." AND ";
            if ($this->_fechaDesde != '') $this->_opcionListado = $this->_opcionListado."fecha >=\"".$this->_fechaDesde."\"";
            if ($this->_fechaDesde != '') $this->_opcionListado = $this->_opcionListado." AND fecha <=\"".$this->_fechaHasta."\"";
            $this->_opcionListado = $this->_opcionListado." ORDER BY id, fecha";
            $aPedidos = array();
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare($this->_opcionListado);
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


    /**
     * Nos permite obtener la cantidad de pedidos realizados 
     *
     * estado > 0
     * @return int $cantidad
     */
    public function countPedidos()
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT count(*) FROM pedidos WHERE
									estado>0");
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
     * Nos permite obtener la cantidad de pedidos realizados
     * a un proveedor
     *
     * @param $oPedidoVO int $idProveedor
     * estado = 1
     * @return int $cantidad
     */
    public function countPedidosProveedor($oPedidoVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT count(*) FROM pedidos
                                       WHERE id_proveedor=?
                                       AND estado=1");
            $idProveedor = $oPedidoVO->getIdProveedor();
            $sth->bindParam(1, $idProveedor);
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
    
}
?>