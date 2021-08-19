/**
 * Archivo javascript para implementar ajax en la aplicación.
 *
 * Archivo javascript con variables y funciones para implementar ajax en 
 * la aplicación.
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
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      File available since Release 1.0
 */

/*
 * Variables de las funciones ajax
 */

// Declara e inicializa las variables 
var xhttp; // Objeto XMLHttpRequest
var datosPeticion = ""; // Petición en formato url
var peticionJson; // Petición en formato JSON
var metodo = "POST"; // Método de la consulta (POST o GET)
var asinc = true; // Consulta asíncrona = true
var url = ""; // Nombre del script que carga para consulta
var respuestaJson; // Respuesta en formato JSON

/*
 * Funciones del script
 */

// Función que crea el objeto
function crearObjXhttp() {
	// Crea el objeto de acuerdo al navegador
	if (window.XMLHttpRequest) {
		// Para navegadores modernos
		xhttp = new XMLHttpRequest();
	} else {
		// Para navegadores IE6, IE5
		xhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
}

// Función que procesa la petición
function procesarPeticionAjax() {
	// Crea el objeto si no existe
	if (!xhttp) {
		crearObjXhttp();
	}
	// Respuesta a la solicitud
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			respuestaJson = JSON.parse(this.responseText);
			//alert(respuestaJson);
			mostrarRespuesta(respuestaJson); // Función que muestra la respuesta
		} 
	}
	// Especificaciones 
	xhttp.open(metodo, url, asinc);
	// Solicitud
	if (metodo == "GET") {
		// con método GET realiza la petición
		xhttp.send();
	} else {
		// con método POST agrega cabecera
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		// Realiza la petición al servidor
		xhttp.send(datosPeticion);
	}
}
