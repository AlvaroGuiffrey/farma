<?php
class DatoVista 
{
	#Propiedades
	private $_datos = array();
	
	#Métodos
	// Con setDato guardamos nuestras variables 
	public function setDato($clave, $valor)
	{
		if (!isset($this->_datos[$clave])){
			$this->_datos[$clave] = $valor;
		}
	}
	
	// Con getDato('nombreDeLaClave') recuperamos el valor
	public function getDato($clave)
	{
		return $this->_datos[$clave];
	} 
	
	// Con getAllDatos() recuperamos el arreglo
	public function getAllDatos()
	{
		return $this->_datos;
	}

}
?>
