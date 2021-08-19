/**
 * Archivo javaScript del módulo artículo.
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
// Tareas que se hacen, con funciones, luego que se carga la página
window.onload = function() {
	modificarBotones();
}

//Funciones que cargan datos para las petición ajax
function cargarDatosRotulo() {
	// Datos para realizar la petición ajax
	datosPeticion = "id="+document.getElementById('idArticulo').value;
	datosPeticion += "&accion="+document.getElementById('botonActRotulo').value;
	datosPeticion += "&columna="+"rotulo";
	// Reescribo especificaciones de la petición ajax
	url="/farma/modulos/articulo/includes/ArticuloAjax.php";
	// Petición Ajax
	procesarPeticionAjax();
}

// Función que muestra la respuesta
function mostrarRespuesta(respuestaJson) {
	switch(true) {
		case (respuestaJson.columna == "rotulo") && (respuestaJson.accion == "actualizar")
			&& (respuestaJson.cantidad == 1):
			document.getElementById('botonActRotulo').style.display="none";
			document.getElementById('etiquetaActRotulo').style.display="";
			break;
		default:
			document.getElementById('botonActRotulo').style.display="none";
			document.getElementById('etiquetaErrRotulo').style.display="";
		
	}
}

// Función que muestra el botón de rótulo
function mostrarBotonRotulo() {
	document.getElementById('etiquetaErrRotulo').style.display="none";
	document.getElementById('etiquetaActRotulo').style.display="none";
	document.getElementById('botonActRotulo').style.display="";
}

// Funciones que se ejecutan luego de cargar el window
function modificarBotones() {
	document.getElementById('rotuloSi').onclick = function() { mostrarBotonRotulo() };
	document.getElementById('rotuloNo').onclick = function() { mostrarBotonRotulo() };
	document.getElementById('botonActRotulo').title = "Actualiza el rótulo del artículo" ;
	document.getElementById('botonActRotulo').onclick = function() { cargarDatosRotulo() };
}