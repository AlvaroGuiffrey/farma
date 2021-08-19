<?php
class CargarVista
{
	#Propiedades
	private $_cargas = array();

	#Métodos
	// Con setCarga guardamos nuestras variables
	public function setCarga($clave, $valor)
	{
		// ingresa las últimas acciones 
	//if (!isset($this->_cargas[$clave])){
	$this->_cargas[$clave] = $valor;
	//}
	}

	// Con getCarga('nombreDeLaClave') recuperamos el valor
	public function getCarga($clave)
	{
	return $this->_cargas[$clave];
	}

	// Con getAllCargas() recuperamos el arreglo
	public function getAllCargas()
	{
	return $this->_cargas;
	}

}
?>
