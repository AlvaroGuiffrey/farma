<?php
// Se definen las clases necesarias
Clase::define('PendienteActiveRecord');

class PendienteModelo extends PendienteActiveRecord
{
    #propiedades
    public $cantidad;
    public $lastId;
    private $_opcionListado;
    private $_id;
    private $_idArticulo;
    private $_codigo;
    private $_codigoB;
    private $_idRubro;
    private $_idProveedor;
    private $_cantidad;
    private $_idPedido;
    private $_estado;
    private $_cantidadRec;
    private $_fechaRec;
    private $_comprobante;
    private $_comentario;
    private $_idUsuarioAct;
    private $_fechaAct;
    private $_orden;
    private $_desde;
    private $_lote;
    
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
     * Nos permite obtener un objeto de la clase VO
     * del pendiente buscado por el id del articulo
     * con estado = 1 - activo.
     *
     * @param $oPendienteVO int $idArticulo
     * @return $oPendienteVO
     */
    public function findPorIdArticulo($oPendienteVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT * FROM pendientes WHERE id_articulo=? AND estado=1");
            $idArticulo = $oPendienteVO->getIdArticulo();
            $sth->bindParam(1, $idArticulo);
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
    
    /**
     * Nos permite obtener un objeto de la clase VO
     * del pendiente buscado por el codigo del articulo 
     * con estado = 1 - activo.
     *
     * @param $oPendienteVO int $codigo
     * @return $oPendienteVO
     */
    public function findPorCodigo($oPendienteVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT * FROM pendientes WHERE codigo=? AND estado=1");
            $codigo = $oPendienteVO->getCodigo();
            $sth->bindParam(1, $codigo);
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
    
    /**
     * Nos permite obtener un array con todos los registros
     * de pendientes asignados a un proveedor o disponibles
     * para asignar con estado = 1 - activo.
     *
     * @param $oPendienteVO int $idProveedor
     * @return $oPendienteVO
     */
    public function findAllProvAsignar($oPendienteVO)
    {
        try{
            $aPendientes = array();
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT * FROM pendientes 
                                            WHERE id_proveedor=?
                                            OR id_proveedor=0
                                            AND estado=1 
                                            ORDER BY id");
            $idProveedor = $oPendienteVO->getIdProveedor();
            $sth->bindParam(1, $idProveedor);
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
 
    /**
     * Nos permite obtener un array con todos los registros
     * de pendientes asignados a un proveedor o disponibles
     * para asignar con estado = 1 - activo.
     * según los rubros seleccionados
     *
     * @param $oPendienteVO int $idProveedor
     * @param $consulta string
     * @return $aPendientes
     */
    public function findAllProvRubrosAsignar($oPendienteVO, $aRubros)
    {
    
        try{
            $this->_opcionListado = "SELECT * FROM pendientes WHERE id_rubro IN (";
            $cont = 1;  
            //$rubros = " AND id_rubro IN (2,3,4) ";
            $rubros = "";
            if(isset($aRubros)){
                $cantRubros = count($aRubros);
                foreach ($aRubros as $rubro){
                    $rubros = $rubros."$rubro";
                    if ($cont < $cantRubros) $rubros = $rubros.",";
                    $cont++;
                }
            }
            
            $this->_opcionListado = $this->_opcionListado."".$rubros.")
                     AND id_proveedor IN (".$oPendienteVO->getIdProveedor();
            $this->_opcionListado = $this->_opcionListado.", 0) ";
            $this->_opcionListado = $this->_opcionListado." AND estado= 1 ORDER BY id";
            //echo "Consulta -> ".$this->_opcionListado."<br>";
            $aPendientes = array();
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare($this->_opcionListado);
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
    
    /**
     * Nos permite obtener un array con todos los registros
     * de pendientes asignados a un proveedor para pedir con
     * estado = 1 - activo.
     *
     * @param $oPendienteVO int $idProveedor
     * @return $aPendientes
     */
    public function findAllProvPedir($oPendienteVO)
    {
        try{
            $aPendientes = array();
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT * FROM pendientes
                                            WHERE id_proveedor=?
                                            AND estado=1
                                            ORDER BY id");
            $idProveedor = $oPendienteVO->getIdProveedor();
            $sth->bindParam(1, $idProveedor);
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
  
    /**
     * Nos permite obtener un array con todos los registros 
     * de un lote (25 registros)
     * de pendientes asignados a un proveedor para pedir con
     * estado = 1 - activo.
     *
     * @param $oPendienteVO int $idProveedor
     * @param $lote
     * @return $aPendientes
     */
    public function findAllProvPedirLote($oPendienteVO, $lote)
    {
        try{
            $this->_lote = $lote;
            $this->_desde = ($this->_lote - 1)* 25;
            $this->_idProveedor = $oPendienteVO->getIdProveedor();
            $this->_opcionListado = "SELECT * FROM pendientes
                                           WHERE id_proveedor=".$this->_idProveedor."
                                           AND estado=1
                                           LIMIT ".$this->_desde.", 25";
            $aPendientes = array();
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare($this->_opcionListado);
            
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
    
    
    /**
     * Nos permite obtener un array con todos los registros
     * de pendientes por indice de pedido
     *
     * @param $oPendienteVO int $idPedido
     * @return $aPendiente
     */
    public function findAllPorIdPedido($oPendienteVO)
    {
        try{
            $aPendientes = array();
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT * FROM pendientes
                                            WHERE id_pedido=?
                                            ORDER BY id");
            $idPedido = $oPendienteVO->getIdPedido();
            $sth->bindParam(1, $idPedido);
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
    
    /**
     * Nos permite modificar la tabla pendientes para asignar el proveedor
     *
     * @param array() $aPendientes
     * @param int $proveedor
     */
    public function updateProveedor($aPendientes, $proveedor)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("UPDATE pendientes
						SET id_proveedor=$proveedor 
                        WHERE id IN (".implode(',',$aPendientes).")");
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
     * Nos permite modificar la tabla pendientes para modificar estado
     *
     * @param array() $aPendientes
     * @param int $estado
     */
    public function updateEstado($aPendientes, $estado)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("UPDATE pendientes
						SET estado=$estado
                        WHERE id IN (".implode(',',$aPendientes).")");
            $sth->execute();
            if (!$sth){
                $oPDOe = new PDOException('Problema al intentar medificar datos.', 1);
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
     * Nos permite obtener la cantidad de pendientes asignados o
     * no a un proveedor
     *
     * estado = 1
     * @return int $cantidad
     */
    public function countPendientes()
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT count(*) FROM pendientes WHERE
									estado=1");
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
     * Nos permite obtener la cantidad de pendientes asignados 
     * a un proveedor o sin asignar
     *
     * @param $oPendienteVO int $idProveedor
     * estado = 1
     * @return int $cantidad
     */
    public function countPendientesProvAsignar($oPendienteVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT count(*) FROM pendientes 
                                       WHERE id_proveedor=?
                                            OR id_proveedor=0
                                            AND estado=1");
            $idProveedor = $oPendienteVO->getIdProveedor();
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
    
    /**
     * Nos permite obtener la cantidad de pendientes asignados
     * a un proveedor
     *
     * @param $oPendienteVO int $idProveedor
     * estado = 1
     * @return int $cantidad
     */
    public function countPendientesProvAsignado($oPendienteVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT COUNT(*) FROM pendientes
                                       WHERE id_proveedor=?
                                       AND estado=1");
            $idProveedor = $oPendienteVO->getIdProveedor(); 
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