<?php
/**
 * Archivo del includes del módulo proveedor.
 *
 * Archivo del includes del módulo proveedor que permite cargar
 * datos de un registro de la tabla proveedores para representar
 * en una vista.
 *
 * LICENSE:  This file is part of Sistema de Gestión (SG).
 * SG is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * SG is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with SG.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://
 * @since      File available since Release 1.0
 */

/**
 * Clase del includes del módulo proveedor.
 *
 * Clase del includes del módulo proveedor que permite cargar
 * datos de un registro de la tabla proveedores para representar
 * en una vista.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://
 * @since      Class available since Release 1.0
 */

class ProveedorDatos
{
	# Propiedades

	# Métodos
	/**
	* Nos permite cargar datos de un registro de la tabla
	* proveedores para representar en una vista.
	*
	* @param $oProveedorVO
	* @param $oDatoVista
	* @param $accion
	*
	* @return $oDatoVista
	*/
	static function cargaDatos($oProveedorVO, $oDatoVista, $accion)
	{
		/**
		 * Carga los datos recibidos del registro a $oDatoVista
		 */
		$oDatoVista->setDato('{id}', $oProveedorVO->getId());
		$oDatoVista->setDato('{razonSocial}', $oProveedorVO->getRazonSocial());
		$oDatoVista->setDato('{inicial}', $oProveedorVO->getInicial());
		$oDatoVista->setDato('{domicilioFiscal}', $oProveedorVO->getDomicilioFiscal());
		$oDatoVista->setDato('{cuit}', $oProveedorVO->getCuit());
		$oDatoVista->setDato('{inscripto}', $oProveedorVO->getInscripto());
		$oDatoVista->setDato('{ingresosBrutos}', $oProveedorVO->getIngresosBrutos());
		$oDatoVista->setDato('{domicilio}', $oProveedorVO->getDomicilio());
		$oDatoVista->setDato('{codPostal}', $oProveedorVO->getCodPostal());
		$oDatoVista->setDato('{idLocalidad}', $oProveedorVO->getIdLocalidad());
		$oDatoVista->setDato('{telefono}', $oProveedorVO->getTelefono());
		$oDatoVista->setDato('{movil}', $oProveedorVO->getMovil());
		$oDatoVista->setDato('{email}', $oProveedorVO->getEmail());
		$oDatoVista->setDato('{comentario}', $oProveedorVO->getComentario());
		$oDatoVista->setDato('{lista}', $oProveedorVO->getLista());
		$oDatoVista->setDato('{listaOrden}', $oProveedorVO->getListaOrden());
		$oDatoVista->setDato('{estado}', $oProveedorVO->getEstado());
		$oDatoVista->setDato('{idUsuarioAct}', $oProveedorVO->getIdUsuarioAct());
		$oDatoVista->setDato('{fechaAct}', $oProveedorVO->getFechaAct());
		
		/**
		 * Carga otros datos necesarios para representar en las vistas
		 */
		// ingresa nombre de la localidad a representar
		$oLocalidadVO = new LocalidadVO();
		$oLocalidadModelo = new LocalidadModelo();
		$oLocalidadVO->setId($oProveedorVO->getIdLocalidad());
		$oLocalidadModelo->find($oLocalidadVO);
		$oDatoVista->setDato('{nombreLocalidad}', $oLocalidadVO->getNombre());
		// ingresa descripción de la inscripción del proveedor en AFIP
		$oAfipResponsablesVO = new AfipResponsablesVO();
		$oAfipResponsablesModelo = new AfipResponsablesModelo();
		$oAfipResponsablesVO->setCodigo($oProveedorVO->getInscripto());
		$oAfipResponsablesModelo->find($oAfipResponsablesVO);
		if ($oAfipResponsablesModelo->getCantidad()>0){
			$oDatoVista->setDato('{descripcionInscripto}', $oAfipResponsablesVO->getDescripcion());
		}else{
			$oDatoVista->setDato('{descripcionInscripto}', '- Código inexistente -');
		}
		// ingresa descripción del tipo de lista de precios del proveedor
		if ($oProveedorVO->getLista() == 'N'){
			$oDatoVista->setDato('{descripcionLista}', 'PRECIOS NETOS');
		}
		if ($oProveedorVO->getLista() == 'F'){
			$oDatoVista->setDato('{descripcionLista}', 'PRECIOS FINALES');
		}
		if ($oProveedorVO->getLista() == NULL){
			$oDatoVista->setDato('{descripcionLista}', '');
		}
		// ingresa descripción del orden de la lista de precios para actualizar precios
		if ($oProveedorVO->getListaOrden() == '0'){
			$oDatoVista->setDato('{descripcionListaOrden}', 'Sin Lista de Precios');
		}
		if ($oProveedorVO->getListaOrden() == '1'){
			$oDatoVista->setDato('{descripcionListaOrden}', 'Con PREFERENCIA');
		}
		if ($oProveedorVO->getListaOrden() == '2'){
			$oDatoVista->setDato('{descripcionListaOrden}', 'Por ORDEN JERARQUICO');
		}
		// ingresa descripción del estado del proveedor
		if ($oProveedorVO->getEstado() == 0){
			$oDatoVista->setDato('{descripcionEstado}', 'PASIVO');
		}
		if ($oProveedorVO->getEstado() == 1){
			$oDatoVista->setDato('{descripcionEstado}', 'ACTIVO');
		}
		if ($oProveedorVO->getEstado() > 1){
			$oDatoVista->setDato('{descripcionEstado}', 'NO INDICADO');
		}
		
		/**
		 * Carga los datos a representar en las vistas para la acción "Editar"
		 */
		if ($accion == "Editar"){
			// selecciona el estado
			if ($oProveedorVO->getEstado()==0){
				$oDatoVista->setDato('{checkedA}', ' ');
				$oDatoVista->setDato('{checkedP}', 'checked');
			}else{
				$oDatoVista->setDato('{checkedA}', 'checked');
				$oDatoVista->setDato('{checkedP}', ' ');
			}
			// selecciona el tipo de lista de precios
			if ($oProveedorVO->getLista()=='N'){
				$oDatoVista->setDato('{checkedF}', ' ');
				$oDatoVista->setDato('{checkedN}', 'checked');
			}else{
				if ($oProveedorVO->getLista()=='F'){
					$oDatoVista->setDato('{checkedF}', 'checked');
					$oDatoVista->setDato('{checkedN}', ' ');
				}else{
					$oDatoVista->setDato('{checkedF}', ' ');
					$oDatoVista->setDato('{checkedN}', ' ');
				}
			}
			// selecciona el orden de lista de precios para actualizar precios
			if ($oProveedorVO->getListaOrden()=='1'){
				$oDatoVista->setDato('{checkedSL}', ' ');
				$oDatoVista->setDato('{checkedLCP}', 'checked');
				$oDatoVista->setDato('{checkedLOJ}', ' ');
			}else{
				if ($oProveedorVO->getListaOrden ()=='2'){
					$oDatoVista->setDato('{checkedSL}', ' ');
					$oDatoVista->setDato('{checkedLCP}', ' ');
					$oDatoVista->setDato('{checkedLOJ}', 'checked');
				}else{
					$oDatoVista->setDato('{checkedSL}', 'checked');
					$oDatoVista->setDato('{checkedLCP}', ' ');
					$oDatoVista->setDato('{checkedLOJ}', ' ');
				}
			}
		}
		
		/**
		 * Retorna $oDatoVista para ser representado en las vistas
		 */
		return $oDatoVista;
	}
}
?>