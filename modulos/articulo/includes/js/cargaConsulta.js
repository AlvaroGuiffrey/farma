/**
 * Función que carga la consulta en js
 * 
 * Autor: Alvaro Guiffrey
 * Aplicación:
 * Fecha versión: 20/02/2020
 * Versión: 001
 */
// Tareas que se hacen, con funciones, luego que se carga la página
window.onload = function() {
	modificaBotones();
}

// Función que carga la consulta
function cargaConsulta() {
	// Datos para realizar la consulta
	datosPeticion = "id="+document.getElementById('idArticulo').value;
	datosPeticion += "&accion="+document.getElementById('botonConsultar').value;
	datosPeticion += "&columna="+document.getElementById('botonConsultar').name;
	// Reescribo especificaciones de la consulta
	//url = "consultaAjax.php"; // Script que carga para consulta
	//url="Consulta.php";
	url="ConsultaV01.php";
	// Petición Ajax
	procesarPeticionAjax();
}

// Función que muestra la respuesta
function mostrarRespuesta(respuestaJson) {
	document.getElementById('demo').innerHTML = respuestaJson.nombre + " - "
	+ respuestaJson.presentacion + " [" + respuestaJson.codigo_b + "]";
}

function modificaBotones() {
	document.getElementById('botonConsultar').title = "Consulta el artículo por el id" ;
	document.getElementById('botonConsultar').onclick = function() { cargaConsulta() };
}

