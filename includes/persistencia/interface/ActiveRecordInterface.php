<?php
/**
 * Archivo de la interfaz ActiveRecord.
*
* Archivo de la interfaz ActiveRecord que nos realizar contratos
* de implementación con las clases que la implementen y como
* ejemplo podemos mencionar todas las tablas que utilicen
* ActiveRecord del Sistema de Gestión (SG) deberán implementarla.
*
* LICENSE:  This file is part of SGU.
* SGU is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* SGU is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with SG.  If not, see <http://www.gnu.org/licenses/>.
*
* @copyright  Copyright (c) 2015 Alvaro A. Guiffrey (http://www.alvaroguiffrey.com.ar)
* @license    http://www.gnu.org/licenses/   GPL License
* @version    1.0
* @link       http://www.alvaroguiffrey.com.ar
* @since      File available since Release 1.0
*/

interface ActiveRecord
{
	public function findAll();

	public function find($oValueObject);

	public function insert($oValueObject);

	public function update($oValueObject);

	public function delete($oValueObject);

}