<?php
/**
 * Archivo del includes.
 *
 * Archivo del includes que nos permite paginar listados.
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
 */

/**
 * Clase que nos permite paginar listados.
 *
 * Clase que nos permite paginar listados.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http:
 * @since      Class available since Release 1.0
 */

class Paginador
{
	/**
	 * Nos permite paginar listados
	 */

	# Propiedades

	# Metodos
	/**
	 * Nos permite obtener la cantidad de páginas.
	 *
	 * @param $cantidad
	 * @param $limiteRenglones
	 * @param $nroPagina
	 *
	 * @return $cantPaginas
	 */
	static function cantidadPaginas($cantidad, $limiteRenglones)
	{
		$cantPaginas = floor($cantidad / $limiteRenglones);
		$cantResto = $cantidad - ($cantPaginas * $limiteRenglones);
		if ($cantResto > 0 ){
			$cantPaginas++;
		}
		return $cantPaginas;
	}
	
	static function vistaPaginador($nroPagina, $cantPaginas)
	{
		if ($cantPaginas > 0){
			if ($cantPaginas > 1){
				if ($cantPaginas == $nroPagina){
					$paginador = '/includes/vista/paginadorAnt.html';
				}else{
					if ($nroPagina > 1){
						$paginador = '/includes/vista/paginador.html';
					}else {
						$paginador = '/includes/vista/paginadorSig.html';
					}
				}
			}else{
				$paginador = '/includes/vista/paginadorUnica.html';
			}
		}
		return $paginador;
	}
	
}
?>