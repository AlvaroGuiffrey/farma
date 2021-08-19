<?php
/**
 * Archivo que descarga los rotulos de los artículos con condiciones de venta.
 *
 * Archivo que descarga los rótulos de los artículos con condiciones de ventas ordenos 
 * por id de la condición.
 *
 * LICENSE:  This file is part of (SF) Sistema de Fiscales.
 * SF is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * SF is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with SF.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright  Copyright (c) 2013 Alvaro A. Guiffrey (http://www.alvaroguiffrey.com.ar)
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      File available since Release 1.0
 */
session_start();

// Carga las clases necesarias
require_once $_SERVER['DOCUMENT_ROOT'].$_SESSION['dir'].'/includes/control/Clase.php';
// Define las clases
Clase::define('LoginControl');
//Clase::define('FPDF');
Clase::define('ArticuloModelo');
Clase::define('ArticuloCondiModelo');
Clase::define('CondicionModelo');
Clase::define('CondicionTipoModelo');
Clase::define('CondicionCalculo');
Clase::define('OfertaRotulos');

// Instancia las clases
$oLoginVO = new LoginVO();
$oLoginControl = new LoginControl();

$oPDF = new OfertaRotulos();
$oArticuloVO = new ArticuloVO();
$oArticuloModelo = new ArticuloModelo();
$oArticuloCondiVO = new ArticuloCondiVO();
$oArticuloCondiModelo = new ArticuloCondiModelo();
$oCondicionVO = new CondicionVO();
$oCondicionModelo = new CondicionModelo();
$oCondicionTipoVO = new CondicionTipoVO();
$oCondicionTipoModelo = new CondicionTipoModelo();


// Carga usuario y chequea login
$oLoginControl->cargarUsuario($oLoginVO);
$oLoginControl->chequearLogin($oLoginVO);

// consulta tabla artículos
$aRotulos = $oArticuloCondiModelo->findAllRotulosPDF();
//var_dump($aRotulos);
//echo "-------------------- <br>";

/**
 * Arma vector de los rótulos de artículos a descargar
 */

$aDatos = array();
$cont = 0;

foreach ($aRotulos as $item){
    // Carga el artículo
    $oArticuloVO->setId($item['id_articulo']);
    $oArticuloModelo->find($oArticuloVO);
    // Carga la fecha de vigencia (fechaHasta)
    $fechaHasta = $item['fecha_hasta'];
    // Carga la condición
    $oCondicionVO->setId($item['id_condicion']);
    $oCondicionModelo->find($oCondicionVO);
    // Carga el tipo de la condicion
    $oCondicionTipoVO->setId($oCondicionVO->getIdTipo());
    $oCondicionTipoModelo->find($oCondicionTipoVO);
    // Calcula el precio especial de la condición
    $aPreciosCondi = CondicionCalculo::preciosCondi($oArticuloVO, $oCondicionVO);
    // Modifico algunos campos
    // PRESENTACION
    $presen = $oArticuloVO->getPresentacion();
    if (is_null($oArticuloVO->getPresentacion())) {
        $presen = "--";
    } 
    if (empty($oArticuloVO->getPresentacion())) {
        $presen = "--";
    }
    // NOMBRE de CONDICION
    $condi = $oCondicionVO->getNombre();
    // Modifica nombre condiciones para el rótulo
    if ($oCondicionVO->getIdTipo()==1){ // Descuento XX%
        $condi = "-".$oCondicionVO->getDescuento()."%";
    } 
    // ---- Fin modifica campos
    // Arma el vector de los datos
    $aDatos[$cont] = array(
        'tipo' => $oCondicionTipoVO->getTipo(),
        'idTipo' => $oCondicionVO->getIdTipo(),
        'condi' => $condi,
        'nombre' => $oArticuloVO->getNombre(),
        'presen' => $presen,
        'codigo' => $oArticuloVO->getCodigoB(),
        'precio' => number_format($oArticuloVO->getPrecio(), 2, ",", "."),
        'precioCondi' => number_format($aPreciosCondi['precioCondi'], 2, ",", "."),
        'fechaHasta' => $fechaHasta,
        'cantidad' => $oCondicionVO->getCantidadUn(),
        'cuota' => $oCondicionVO->getCuota(),
        'importeCuota' => number_format($aPreciosCondi['importeCuota'], 2, ",", ".")
    );
    $cont++;
    $oArticuloCondiVO->setId($item['id']);
    $oArticuloCondiModelo->find($oArticuloCondiVO);
    $oArticuloCondiVO->setRotulo(1);
    $oArticuloCondiVO->setFechaAct(date('Y-m-d H:i:s'));
    $oArticuloCondiModelo->update($oArticuloCondiVO);
}
//var_dump($aDatos);

// Ordena $aDatos por el nombre
$aNombres = array_column($aDatos, 'nombre'); // Array ordenado de una columna
array_multisort($aNombres, SORT_ASC, $aDatos); // Multisort ordenando el array por la columna nombre
//var_dump($aDatos);


$oPDF->AddPage();

$oPDF->datos($aDatos);

$oPDF->Output(); //Salida al navegador

?>