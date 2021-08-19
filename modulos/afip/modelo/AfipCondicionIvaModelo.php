<?php
// Se definen las clases necesarias
Clase::define('AfipCondicionIvaActiveRecord');

class AfipCondicionIvaModelo extends AfipCondicionIvaActiveRecord
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
	 * Nos permite obtener el identificador del último registro actualizado.
	 *
	 * @return integer
	 */
	public function getLastId()
	{
		return $this->lastId;
	}


}
?>