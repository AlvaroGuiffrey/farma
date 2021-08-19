<?php
// Se definen las clases necesarias
Clase::define('RecibidoActiveRecord');

class RecibidoModelo extends RecibidoActiveRecord
{
	#propiedades
	public $cantidad;
	public $lastId;
	private $_proveedor;
	private $_fechaDesde;
	private $_fechaHasta;
	private $_comprobante;
	private $_opcionListado;

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
	 * Nos permite obtener el identificador del última comprobante recibido actualizada.
	 *
	 * @return integer
	 */
	public function getLastId()
	{
		return $this->lastId;
	}

	/**
	 * Nos permite obtener un array de la consulta de todos los comprobantes
	 * recibidos según las opciones seleccionadas para listado
	 *
	 * @return $aRecibidos
	 */
	public function findAllOpcionListado($proveedor, $fechaDesde, $fechaHasta)
	{
		try{
			$this->_proveedor = $proveedor;
			$this->_fechaDesde = $fechaDesde;
			$this->_fechaHasta = $fechaHasta;
			$this->_opcionListado = "SELECT * FROM recibidos WHERE ";
			if ($this->_proveedor != 0) $this->_opcionListado = $this->_opcionListado."id_proveedor=".$this->_proveedor." AND ";
			if ($this->_fechaDesde != '') $this->_opcionListado = $this->_opcionListado."fecha >=\"".$this->_fechaDesde."\"";
			if ($this->_fechaDesde != '') $this->_opcionListado = $this->_opcionListado." AND fecha <=\"".$this->_fechaHasta."\"";
			$this->_opcionListado = $this->_opcionListado." ORDER BY id, fecha";
			$aArticulos = array();
			$dbh = DataBase::getInstance();
			$sth = $dbh->prepare($this->_opcionListado);
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
}
?>