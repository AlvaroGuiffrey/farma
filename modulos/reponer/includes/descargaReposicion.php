<?php
/**
 * Archivo que descarga las reposiciones.
 *
 * Archivo que descarga las reposiciones de los artículos ordenos por nombre.
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

// Fecha
date_default_timezone_set('America/Argentina/Buenos_Aires');
// Define las clases
Clase::define('LoginControl');
Clase::define('FPDF');
Clase::define('ArticuloModelo');
Clase::define('ReponerModelo');
Clase::define('ArrayOrdenadoPor');

// Instancia las clases
$oLoginVO = new LoginVO();
$oLoginControl = new LoginControl();

$oPDF = new FPDF();
$oArticuloVO = new ArticuloVO();
$oArticuloModelo = new ArticuloModelo();
$oReponerVO = new ReponerVO();
$oReponerModelo = new ReponerModelo();

// Carga usuario y chequea login
$oLoginControl->cargarUsuario($oLoginVO);
$oLoginControl->chequearLogin($oLoginVO);

// consulta tabla pedidos según id recibido por POST
$items = $oReponerModelo->findAllPorNumeroRep($_GET['id']);

// actualiza los registros como listados
if ($_GET['accion']=="Descargar"){
    $aReposiciones = array();
    foreach ($items as $item){
        array_push($aReposiciones, $item['id']);    
    }
    $idUsuario = $oLoginVO->getIdUsuario();
    $oReponerModelo->updateEstadoListado($aReposiciones, $idUsuario);
    //echo "Cantidad Actualizados -> ".$oReponerModelo->getCantidad()."<br>";
    reset($items);
    unset($aReposiciones);
}

// Arma los datos de los renglones y los ordena
foreach ($items as $item){
    $oArticuloVO->setCodigo($item['codigo']);
    $oArticuloModelo->findPorCodigo($oArticuloVO);
    //echo "Codigo: ".$oArticuloVO->getCodigo()."<br>";
    $aReposicion[] = array(
        'codigo' => $oArticuloVO->getCodigo(),
        'codigo_b' => $oArticuloVO->getCodigoB(),
        'nombre' => $oArticuloVO->getNombre(),
        'presentacion' => $oArticuloVO->getPresentacion(),
        'fecha_precio' => $oArticuloVO->getFechaPrecio(),
        'precio' => $oArticuloVO->getPrecio(),
        'cantidad' => $item['cantidad']
    );
}
unset($items);
// ordena el array
$items = ArrayOrdenadoPor::ordenaArray($aReposicion, 'nombre', SORT_ASC);
$oReponerVO->setNumeroRep($_GET['id']);
$oReponerModelo->findFechaReposicion($oReponerVO);
$fechaRep = $oReponerVO->getFechaRep();

/**
 * Arma PDF a descargar
 */

$oPDF->Open();
$oPDF->AddPage();
$oPDF->SetFont('Arial', '', 10);
$oPDF->SetTitle('Descarga Reposición PDF', true);
$oPDF->SetAuthor('Alvaro Guiffrey');
$oPDF->SetCreator('Farmacia Villa Elisa SRL');
$oPDF->SetTopMargin(10);
$oPDF->SetAutoPageBreak(10);

$oPDF->SetFont('Arial', '', 10);
$oPDF->SetFillColor(255,255,255);
$oPDF->SetLeftMargin(20); // setea el margen izquierdo

$renglon = $pagina = $productos = $productosCero = $unidades = $importe = 0;

foreach ($items as $item){
    if ($renglon == 63){ // Pie de la página
        $oPDF->Line(20, $oPDF->GetY(), 200, $oPDF->GetY());
        $oPDF->Ln();
        // datos de farmacia y de la impresión
        // Pie de la página
        $oPDF->Cell(80, 4,"Farmacia Villa Elisa SRL (CUIT: 30-71391160-3)",0,0,'L');
        $oPDF->Cell(80, 4,'Impreso: '.date("d-m-Y H:i:s"),0,0,'R');
        $oPDF->AddPage();
        $renglon = 0;
    }
    
    if ($renglon == 0){ // Encabezado de la página
        $pagina ++;
        $oPDF->SetFont('Arial', 'B', 10);
        $oPDF->Cell(160, 7,'- LISTADO DE LA REPOSICION #: '.$_GET["id"].' -',0,0,'C');
        $oPDF->Ln();
        $oPDF->SetFont('Arial', '', 8);
        $oPDF->Cell(160, 4,'Fecha Reposicion: '.$fechaRep,0,0,'L');
        $oPDF->Cell(20, 4,'Hoja #: '.$pagina,0,0,'R');
        $oPDF->Ln();
        $oPDF->Line(20, $oPDF->GetY(), 200, $oPDF->GetY());
        
        // Encabezado de los renglones
        $oPDF->Cell(20,5,"Codigo Plex",0,0,'L');
        $oPDF->Cell(25,5,"Codigo Barra",0,0,'L');
        $oPDF->Cell(90,5,"Nombre y Presentacion",0,0,'L');
        $oPDF->Cell(10,5,"Precio Un",0,0,'R');
        $oPDF->Cell(10,5,"Cant.",0,0,'R');
        $oPDF->Cell(10,5,"Chk",0,0,'L');
        $oPDF->Ln();
        $oPDF->Line(20, $oPDF->GetY(), 200, $oPDF->GetY());
        
    }

    $oPDF->SetFont('Arial', '', 8);
    //$oPDF->Cell(25,5,$item['codigo_b'],0,0,'L');
    $oPDF->Cell(20,4,$item['codigo'],0,0,'L');
    $oPDF->Cell(25,4,$item['codigo_b'],0,0,'L');
    $oPDF->Cell(54,4,substr($item['nombre'], 0, 30),0,0,'L');
    $oPDF->Cell(38,4,substr($item['presentacion'], 0, 17),0,0,'L');
    $oPDF->Cell(10,4,$item['precio'],0,0,'R');
    $oPDF->SetFont('Arial', 'B', 9);
    $oPDF->Cell(10,4,$item['cantidad'],0,0,'C');
    $oPDF->SetFont('Arial', '', 8);
    $oPDF->Cell(10,4,$oPDF->Image('../../../imagenes/varias/checkbox.png',$oPDF->GetX(),$oPDF->GetY(),4,4,'PNG'),0,0,'C');
    //$oPDF->Image('/farma/imagenes/varias/ajax-loader.gif',0,0,10,5,'GIF');
    $oPDF->Ln();
    $unidades = $unidades + $item['cantidad'];
    $productos ++;
    $renglon ++;

}

// Totales del pedido

$oPDF->Line(20, $oPDF->GetY(), 200, $oPDF->GetY());
$oPDF->Cell(160, 4,'Son '.$productos.' productos y '.$unidades.' unidades.',0,0,'L');
$oPDF->Ln();
$oPDF->Line(20, $oPDF->GetY(), 200, $oPDF->GetY());
$oPDF->Ln();
// Pie de la página
$oPDF->Cell(80, 4,"Farmacia Villa Elisa SRL (CUIT: 30-71391160-3)",0,0,'L');
$oPDF->Cell(80, 4,'Impreso: '.date("d-m-Y H:i:s"),0,0,'R');
$oPDF->Output('Reposicion','I');

?>