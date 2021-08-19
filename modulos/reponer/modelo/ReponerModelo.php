<?php
// Se definen las clases necesarias
Clase::define('ReponerActiveRecord');

class ReponerModelo extends ReponerActiveRecord
{
    #propiedades
    public $cantidad;
    public $lastId;
    private $_opcionListado;
    private $_id;
    private $_codigo;
    private $_idRubro;
    private $_cantidad;
    private $_numeroRep;
    private $_fechaRep;
    private $_estado;
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
     * Nos permite obtener un objeto de la clase VO con los datos de
     * un registro activo y sin número de reposición de la tabla reponer 
     * por el código de artículo.
     * 
     * estado = 1
     * numero_rep = 0
     * @param $oReponerVO int codigo
     * @return $oReponerVO
     */
    public function findPorCodigoSinNumero($oReponerVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT * FROM reposiciones WHERE codigo=? 
                                                             AND estado=1
                                                             AND numero_rep=0
                                 ");
            $codigo = $oReponerVO->getCodigo();
            $sth->bindParam(1, $codigo);
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
    
    /**
     * Nos permite obtener un array con todos los registros
     * activos de los articulos para reponer
     *
     * $estado = 1
     *
     * @return array $aReposiciones
     */
    public function findAllActivos()
    {
        try{
            $aReposiciones = array();
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT * FROM reposiciones
                                            WHERE estado=1
                                            ORDER BY id");
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
    
    /**
     * Nos permite obtener la fecha de una reposición listada
     * 
     * @param $oReponerVO numero_rep
     * @return $oReponerVO fecha_rep
     */
    public function findFechaReposicion($oReponerVO)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT * FROM reposiciones WHERE numero_rep=?");
            $numeroRep = $oReponerVO->getNumeroRep();
            $sth->bindParam(1, $numeroRep);
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

    /**
     * Nos permite obtener un array con todos los registros
     * activos de los articulos sin numero de reposición
     *
     * estado = 1
     * numero_rep = 0
     *
     * @return array $aReposiciones
     */
    public function findAllSinNumero()
    {
        try{
            $aReposiciones = array();
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT * FROM reposiciones
                                            WHERE estado=1
                                            AND numero_rep=0
                                            ORDER BY id");
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

    /**
     * Nos permite obtener un array con todos los registros
     * de una reposición realizada listada o sin listar
     *
     * @param $numeroRep
     * @return array $aReposiciones
     */
    public function findAllPorNumeroRep($numeroRep)
    {
        try{
            $aReposiciones = array();
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT * FROM reposiciones
                                            WHERE numero_rep=?
                                            ORDER BY id");
            $sth->bindParam(1, $numeroRep);
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

    /**
     * Nos permite obtener un array con todos los registros
     * de reposiciones para listar con estado = 1 - activo y
     * numero_rep = 0, según los rubros seleccionados
     *
     * @param $aRubro array()
     * @return $aReposiciones
     */
    public function findAllPorRubros($aRubros)
    {
        
        try{
            $cont = 1;
            $rubros = "";
            if(isset($aRubros)){
                $cantRubros = count($aRubros);
                foreach ($aRubros as $rubro){
                    $rubros = $rubros."$rubro";
                    if ($cont < $cantRubros) $rubros = $rubros.",";
                    $cont++;
                }
            }
            $this->_opcionListado = "SELECT id FROM reposiciones WHERE id_rubro IN (".$rubros.")";
            $this->_opcionListado = $this->_opcionListado." AND estado=1 AND numero_rep=0 ORDER BY id";
            //echo "Consulta -> ".$this->_opcionListado."<br>";
            $aReposiciones = array();
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare($this->_opcionListado);
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
    
    /**
     * Nos permite modificar la tabla reposiciones para asignar un estado
     * 0 (inactivo) a reposiciones
     *
     * @param array() $aReposiciones
     *
     */
    public function updateEstadoInactivo($aReposiciones)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("UPDATE reposiciones
						SET estado=0
                        WHERE id IN (".implode(',',$aReposiciones).")");
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
     * Nos permite modificar la tabla reposiciones para asignar un estado
     * 9 (listado) a reposiciones
     *
     * @param array() $aReposiciones
     *
     */
    public function updateEstadoListado($aReposiciones, $idUsuario)
    {
        try{
            $fechaRep = date("Y-m-d H:i:s");
            //echo $fechaRep."<br>";
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare('UPDATE reposiciones
						SET estado=9, 
                            fecha_rep="'.$fechaRep.'",
                            id_usuario_act='.$idUsuario.',
                            fecha_act="'.date("Y-m-d H:i:s").'"
                        WHERE id IN ('.implode(",",$aReposiciones).')');
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
     * Nos permite modificar la tabla reposiciones para asignar un numero de
     * reposición
     *
     * @param array() $aReposiciones
     * @param int $numeroRep
     *
     */
    public function updateNumeroRep($aReposiciones, $numeroRep)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("UPDATE reposiciones
						SET numero_rep=".$numeroRep."
                        WHERE id IN (".implode(',',$aReposiciones).")");
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
     * reposiciones según las opciones seleccionadas para listado
     *
     * @param date $fechaDesde
     * @param date $fechaHasta
     * @return $aReposiciones
     */
    public function findAllOpcionListado($fechaDesde, $fechaHasta)
    {
        try{
            $this->_fechaDesde = $fechaDesde;
            $this->_fechaHasta = date('Y-m-d', strtotime("$fechaHasta + 1 day"));
            //echo $this->_fechaDesde." hasta ".$this->_fechaHasta."<br>";
            $this->_opcionListado = "SELECT * FROM reposiciones WHERE ";
            $this->_opcionListado = $this->_opcionListado."fecha_rep >=\"".$this->_fechaDesde."\"";
            $this->_opcionListado = $this->_opcionListado." AND fecha_rep <\"".$this->_fechaHasta."\"";
            $this->_opcionListado = $this->_opcionListado." ORDER BY id, fecha_rep";
            $aReposiciones = array();
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare($this->_opcionListado);
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

    /**
     * Nos permite obtener un array de la consulta de los números de
     * reposiciones listados según las opciones seleccionadas 
     *
     * @param date $fechaDesde
     * @param date $fechaHasta
     * @return $aReposiciones
     */
    public function findAllOpcionRepListadas($fechaDesde, $fechaHasta)
    {
        try{
            $this->_fechaDesde = $fechaDesde;
            $this->_fechaHasta = date('Y-m-d', strtotime("$fechaHasta + 1 day"));
            $this->_opcionListado = "SELECT numero_rep FROM reposiciones WHERE ";
            if ($this->_fechaDesde != '') $this->_opcionListado = $this->_opcionListado."fecha_rep >=\"".$this->_fechaDesde."\"";
            if ($this->_fechaDesde != '') $this->_opcionListado = $this->_opcionListado." AND fecha_rep <\"".$this->_fechaHasta."\"";
            $this->_opcionListado = $this->_opcionListado." GROUP BY numero_rep ORDER BY numero_rep ASC";
            $aReposiciones = array();
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare($this->_opcionListado);
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

    /**
     * Nos permite obtener un array de la consulta de los números de
     * reposiciones a descargar sin listar
     *
     * numero_rep > 0
     * fecha_rep IS NULL
     * @return $aReposiciones
     */
    public function findAllRepDescarga()
    {
        try{
            $this->_opcionListado = "SELECT numero_rep FROM reposiciones WHERE
                                     numero_rep > 0
                                     AND fecha_rep IS NULL
                                     GROUP BY numero_rep ORDER BY numero_rep ASC";
            $aReposiciones = array();
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare($this->_opcionListado);
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
    
    /**
     * Nos permite obtener el último número de reposición asignado
     *
     * numero_rep > de la tabla
     * @return int $numeroRep
     */
    public function findNumeroRepAsignado()
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT MAX(numero_rep) AS numeroRep FROM reposiciones");
            $sth->execute();
            if (!$sth){
                $oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
                $oPDOe->errorInfo = $dbh->errorInfo();
                throw $oPDOe;
            }
            $fila = $sth->fetchObject();
            $numeroRep = $fila->numeroRep;
            $this->cantidad = $sth->rowCount();
            $dbh=null;
            return $numeroRep;
        }catch (Exception $e){
            echo $e;
            echo "<br/>";
            print_r($e->errorInfo);
        }
    }

    /**
     * Nos permite obtener el último número de reposición listado
     *
     * estado = 9  y  numero_rep > de la tabla
     * @return int $numeroRep
     */
    public function findNumeroRepListado()
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT MAX(numero_rep) AS numeroRep FROM reposiciones 
                                           WHERE estado=9");
            $sth->execute();
            if (!$sth){
                $oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
                $oPDOe->errorInfo = $dbh->errorInfo();
                throw $oPDOe;
            }
            $fila = $sth->fetchObject();
            $numeroRep = $fila->numeroRep;
            $this->cantidad = $sth->rowCount();
            $dbh=null;
            return $numeroRep;
        }catch (Exception $e){
            echo $e;
            echo "<br/>";
            print_r($e->errorInfo);
        }
    }

    /**
     * Nos permite obtener el número de la última reposición sin listar
     *
     * estado = 1  y  numero_rep > de la tabla
     * @return int $numeroRep
     */
    public function findNumeroRepSinListar()
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT MAX(numero_rep) AS numeroRep FROM reposiciones
                                           WHERE estado=1");
            $sth->execute();
            if (!$sth){
                $oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
                $oPDOe->errorInfo = $dbh->errorInfo();
                throw $oPDOe;
            }
            $fila = $sth->fetchObject();
            $numeroRep = $fila->numeroRep;
            $this->cantidad = $sth->rowCount();
            $dbh=null;
            return $numeroRep;
        }catch (Exception $e){
            echo $e;
            echo "<br/>";
            print_r($e->errorInfo);
        }
    }

    /**
     * Nos permite obtener la cantidad de reposiciones listadas
     *
     * fecha_rep is not NULL
     * @return int $cantidad
     */
    public function countReposicionesListadas()
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT count(*) FROM reposiciones WHERE
									fecha_rep IS NOT NULL");
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
     * Nos permite obtener la cantidad de reposiciones sin listadas
     *
     * fecha_rep is NULL
     * @return int $cantidad
     */
    public function countReposicionesSinListar()
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT count(*) FROM reposiciones WHERE
									fecha_rep IS NULL");
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
     * Nos permite obtener la cantidad de artículos para reposición
     *
     * estado = 1
     * @return int $cantidad
     */
    public function countReponer()
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT count(*) FROM reposiciones WHERE
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
     * Nos permite obtener la cantidad de artículos para agregar a reposición
     *
     * estado = 1 
     * numero_rep = 0
     * @return int $cantidad
     */
    public function countAgregarReponer()
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT count(*) FROM reposiciones
                                        WHERE estado=1
                                        AND numero_rep=0");
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
     * Nos permite obtener la cantidad de artículos de reposición con número 
     * sin listar o listada
     *
     * numero_rep = $numeroRep 
     * 
     * @param int $numeroRep
     * @return int $cantidad
     */
    public function countArticulosReposicion($numeroRep)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT count(*) FROM reposiciones
                                        WHERE numero_rep=?"
                                 );
            $sth->bindParam(1, $numeroRep);
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
     * Nos permite obtener la cantidad de unidades de la reposición con número
     * sin listar o listada
     *
     * numero_rep = $numeroRep
     *
     * @param int $numeroRep
     * @return int $cantidad
     */
    public function sumaUnidadesReposicion($numeroRep)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT SUM(cantidad) FROM reposiciones
                                        WHERE numero_rep=?"
                );
            $sth->bindParam(1, $numeroRep);
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