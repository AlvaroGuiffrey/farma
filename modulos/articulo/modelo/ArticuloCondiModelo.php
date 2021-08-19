<?php
// Se definen las clases necesarias
Clase::define('ArticuloCondiActiveRecord');

class ArticuloCondiModelo extends ArticuloCondiActiveRecord
{
    #propiedades
    public $cantidad;
    public $lastId;
    
    #métodos
    
    /**
     * Nos permite obtener la cantidad de renglones de la consulta.
     *
     * @return integer
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }
    
    /**
     * Nos permite obtener el identificador del último rubro actualizado.
     *
     * @return integer
     */
    public function getLastId()
    {
        return $this->lastId;
    }
    
    /**
     * Nos permite obtener la cantidad de articulos de la tabla
     * con condiciones vigentes
     * 
     *  @param $oArticuloCondiVO->getFechaHasta()
     *  @param estado = 1
     *  @return int cantidad
     */
    public function countArticulosCondiVig()
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT count(*) FROM articulos_condi WHERE
									fecha_hasta>='".date('Y-m-d')."' AND
                                    estado = 1
								");
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
     * Nos permite obtener la cantidad de articulos_condi de la tabla
     * con rótulos y condiciones vigentes.
     */
    public function countRotulos()
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT count(*) FROM articulos_condi WHERE
									rotulo >= 1 AND
									estado = 1 AND
                                    fecha_hasta >='".date('Y-m-d')."'"
								);
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
     * Nos permite obtener la cantidad de articulos de la tabla
     * con rótulos para descargar por modificación de precios 
     * o seleccionados por el operador con condiciones vigentes
     */
    public function countRotulosPDF()
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT count(*) FROM articulos_condi WHERE
									rotulo = 3 AND
									estado = 1 AND
                                    fecha_hasta >='".date('Y-m-d')."'"
								);
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
     * Nos permite obtener un array de la consulta de todos los articulos_condi vigente
     * de un artículo
     *
     * @param $oArticuloCondiVO(IdArticulo)
     * @param $oArticuloCondiVO(fechaHasta)
     * @param estado=1
     * 
     * @return $aArticulosCondi
     */
    public function findAllIdArticulo($oArticuloCondiVO)
    {
        
        try{
            $aArticulosCondi = array();
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("SELECT * FROM articulos_condi WHERE id_articulo=? AND fecha_hasta>=? AND estado=1");
            $idArticulo = $oArticuloCondiVO->getIdArticulo();
            $sth->bindParam(1, $idArticulo);
            $fechaHasta = $oArticuloCondiVO->getFechaHasta();
            $sth->bindParam(2, $fechaHasta);
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
     * Nos permite obtener un array de los articulos con rótulos con condiciones
     * y que éstas se encuentren vigentes.
     *
     * @return $aArticulos
     */
    public function findAllRotulos()
    {
        try{
            $dbh = DataBase::getInstance();
            
            $sth = $dbh->prepare("SELECT * FROM articulos_condi WHERE
									rotulo>=1 AND
									estado=1 AND 
                                    fecha_hasta>='".date('Y-m-d')."'
									ORDER BY id_condicion
								");
 
            $sth->execute();
            if (!$sth){
                $oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
                $oPDOe->errorInfo = $dbh->errorInfo();
                throw $oPDOe;
            }
            $aArticulos = $sth->fetchAll();
            $this->cantidad = $sth->rowCount();
            $dbh=null;
            return $aArticulos;
        }catch (Exception $e){
            echo $e;
            echo "<br/>";
            print_r($e->errorInfo);
        }
    }
 
    /**
     * Nos permite obtener un array de los articulos con rótulo y
     * con condición de venta vigente.
     *
     * @return $aArticulos
     */
    public function findAllRotulosVig()
    {
        try{
            $dbh = DataBase::getInstance();
            $consulta = "SELECT * FROM articulos_condi WHERE
									rotulo>=1 AND
									estado=1 AND
                                    fecha_hasta>='".date('Y-m-d')."'
									ORDER BY id_condicion
								";
            echo "Consulta -> ".$consulta."<br>";
            $sth = $dbh->prepare($consulta);
            $sth->execute();
            if (!$sth){
                $oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
                $oPDOe->errorInfo = $dbh->errorInfo();
                throw $oPDOe;
            }
            $aArticulos = $sth->fetchAll();
            $this->cantidad = $sth->rowCount();
            $dbh=null;
            return $aArticulos;
        }catch (Exception $e){
            echo $e;
            echo "<br/>";
            print_r($e->errorInfo);
        }
    }
    
    /**
     * Nos permite obtener un array de los articulos con rótulos reservados
     * por actualización del precio o por selección del operador.
     *
     * @return $aArticulos
     */
    public function findAllRotulosPDF()
    {
        try{
            $dbh = DataBase::getInstance();
            
            $sth = $dbh->prepare("SELECT * FROM articulos_condi WHERE
									rotulo=3 AND
									estado=1 AND
                                    fecha_hasta>='".date('Y-m-d')."'
									ORDER BY id_condicion
								");
            
            $sth->execute();
            if (!$sth){
                $oPDOe = new PDOException('Problema al intentar consultar datos.', 1);
                $oPDOe->errorInfo = $dbh->errorInfo();
                throw $oPDOe;
            }
            $aArticulos = $sth->fetchAll();
            $this->cantidad = $sth->rowCount();
            $dbh=null;
            return $aArticulos;
        }catch (Exception $e){
            echo $e;
            echo "<br/>";
            print_r($e->errorInfo);
        }
    }
    
    /**
     * Nos permite modificar la tabla para confirmar los rótulos
     * a descargar
     *
     * @param array() $aRotulos
     */
    public function updateRotulos($aRotulos, $rotulo)
    {
        try{
            $dbh = DataBase::getInstance();
            $sth = $dbh->prepare("UPDATE articulos_condi
						SET rotulo=$rotulo WHERE id IN (".implode(',',$aRotulos).")");
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