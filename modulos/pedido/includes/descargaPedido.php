<?php
/**
 * Archivo que descarga los pedidos.
 *
 * Archivo que descarga los pedidos de los artículos ordenos por nombre.
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
Clase::define('Barcode_php');
Clase::define('ArticuloModelo');
Clase::define('PedidoModelo');
Clase::define('PendienteModelo');
Clase::define('ProductoModelo');
Clase::define('ProveedorModelo');
Clase::define('ArrayOrdenadoPor');
Clase::define('CalcularPrecioProv');

// Instancia las clases
$oLoginVO = new LoginVO();
$oLoginControl = new LoginControl();

$oPDF = new FPDF();
$oArticuloVO = new ArticuloVO();
$oArticuloModelo = new ArticuloModelo();
$oPedidoVO = new PedidoVO();
$oPedidoModelo = new PedidoModelo();
$oPendienteVO = new PendienteVO();
$oPendienteModelo = new PendienteModelo();
$oProductoVO = new ProductoVO();
$oProductoModelo = new ProductoModelo();
$oProveedorVO = new ProveedorVO();
$oProveedorModelo = new ProveedorModelo();


// Carga usuario y chequea login
$oLoginControl->cargarUsuario($oLoginVO);
$oLoginControl->chequearLogin($oLoginVO);

// consulta tabla pedidos según id recibido por POST
$oPedidoVO->setId($_GET['id']);
$items = $oPedidoModelo->find($oPedidoVO);

// consulta tabla proveedores
$oProveedorVO->setId($oPedidoVO->getIdProveedor());
$oProveedorModelo->find($oProveedorVO);

// consulta todos los pendientes para el id de pedido
$oPendienteVO->setIdPedido($oPedidoVO->getId());
$items = $oPendienteModelo->findAllPorIdPedido($oPendienteVO);
$cantPendientes = $oPendienteModelo->getCantidad();

// Arma los datos de los renglones y los ordena
foreach ($items as $item){
    $oArticuloVO->setId($item['id_articulo']);
    $oArticuloModelo->find($oArticuloVO);
    $existeProducto = 0;
    if ($item['codigo_b'] > 0){ // Articulo con código de barra
        $oProductoVO->setIdProveedor($oProveedorVO->getId());
        $oProductoVO->setCodigoB($item['codigo_b']);
        $oProductoModelo->findPorCodigoBProveedor($oProductoVO);
        $existeProducto = $oProductoModelo->getCantidad();
        $codigoB = $oProductoVO->getCodigoB();
        $codigoP = $oProductoVO->getCodigoP();
        $nombre = $oProductoVO->getNombre();
        // pone nombre del producto o del artículo si el anterior no existe para proveedor
        if ($existeProducto > 0){
            $nombre = $oProductoVO->getNombre();
            $codigoP = $oProductoVO->getCodigoP();
            $codigoB = $item['codigo_b'];
            $precio = CalcularPrecioProv::calculaUnPrecio($oProductoVO->getIdProveedor(), $oProductoVO->getPrecio());
        } else {
            $nombre = $oArticuloVO->getNombre()."-".$oArticuloVO->getPresentacion();
            $codigoP = "";
            $codigoB = $item['codigo_b'];
            $precio = 0;
        }
    } else { // Artículo sin código de barra
        $nombre = $oArticuloVO->getNombre()."-".$oArticuloVO->getPresentacion();
        $codigoB = $codigoP = "";
        $precio = 0;
    }
$aPedidos[] = array(
    'codigo_b' => $codigoB,
    'codigo_p' => $codigoP,
    'nombre' => $nombre,
    'precio' => $precio,
    'cantidad' => $item['cantidad']
    );
}
// ordena el array
$items = ArrayOrdenadoPor::ordenaArray($aPedidos, 'nombre', SORT_ASC);



/**
 * Arma PDF a descargar
 */

$oPDF->Open();
$oPDF->AddPage();
$oPDF->SetFont('Arial', '', 10);
$oPDF->SetTitle('Descarga Pedido PDF', true);
$oPDF->SetAuthor('Alvaro Guiffrey');
$oPDF->SetCreator('Farmacia Villa Elisa SRL');
$oPDF->SetTopMargin(10);
$oPDF->SetAutoPageBreak(10);

$oPDF->SetFont('Arial', '', 10);
$oPDF->SetFillColor(255,255,255);
$oPDF->SetLeftMargin(20); // setea el margen izquierdo

$renglon = $pagina = $productos = $productosCero = $unidades = $importe = 0;
foreach ($items as $item){
    // Pie de la página según el canal del pedido
    // Con código de barra
    if ($oPedidoVO->getCanal() == "CardShop"){
        if ($renglon == 30){ // Pie de la página 
            $oPDF->Line(20, $oPDF->GetY(), 200, $oPDF->GetY());
            $oPDF->Ln();
            // datos de farmacia y de la impresión
            // Pie de la página
            $oPDF->Cell(80, 4,"Farmacia Villa Elisa SRL (CUIT: 30-71391160-3)",0,0,'L');
            $oPDF->Cell(80, 4,'Impreso: '.date("d-m-Y H:i:s"),0,0,'R');
            $oPDF->AddPage();
            $renglon = 0;
        } 
    // Sin Código de Barra    
    } else { 
        if ($renglon == 48){ // Pie de la página
            $oPDF->Line(20, $oPDF->GetY(), 200, $oPDF->GetY());
            $oPDF->Ln();
            // datos de farmacia y de la impresión
            // Pie de la página
            $oPDF->Cell(80, 4,"Farmacia Villa Elisa SRL (CUIT: 30-71391160-3)",0,0,'L');
            $oPDF->Cell(80, 4,'Impreso: '.date("d-m-Y H:i:s"),0,0,'R');
            $oPDF->AddPage();
            $renglon = 0;
        }
    }
    // Encabezado de la página para todos los tipos de canales
    if ($renglon == 0){ // Encabezado de la página
        $pagina ++;
        $oPDF->SetFont('Arial', 'B', 10);
        $oPDF->Cell(160, 7,'- LISTADO DEL PEDIDO #: '.$oPedidoVO->getId().' -',0,0,'C');
        $oPDF->Ln();
        $oPDF->SetFont('Arial', '', 8);
        $oPDF->Cell(160, 4,'Proveedor: '.$oProveedorVO->getId().' - '.$oProveedorVO->getRazonSocial().'',0,0,'L');
        $oPDF->Cell(20, 4,'Hoja #: '.$pagina,0,0,'R');
        $oPDF->Ln();
        $oPDF->Cell(160, 4,'Fecha Pedido: '.$oPedidoVO->getFecha().' - Canal: '.$oPedidoVO->getCanal().' - Fecha Recibido: '.$oPedidoVO->getFechaRec(),0,0,'L');
        $oPDF->Ln();
        $oPDF->Line(20, $oPDF->GetY(), 200, $oPDF->GetY());
        
        // Encabezado de los renglones
        $oPDF->Cell(45,5,"Codigo Barra",0,0,'L');
        $oPDF->Cell(30,5,"Codigo Proveedor",0,0,'L');
        $oPDF->Cell(75,5,"Nombre y Presentacion",0,0,'L');
        $oPDF->Cell(10,5,"Precio Un",0,0,'R');
        $oPDF->Cell(10,5,"Cant.",0,0,'R');
        $oPDF->Cell(10,5,"Chk",0,0,'L');
        $oPDF->Ln();
        $oPDF->Line(20, $oPDF->GetY(), 200, $oPDF->GetY());
        
    }
    
    // Renglón según el canal del pedido
    // Con código de barra
    if ($oPedidoVO->getCanal() == "CardShop"){
        //barcode( $filepath, $text, $size, $orientation, $code_type, $print, $sizefactor )
        // Línea con código de barra
        barcode('codigo'.$item['codigo_b'].'.png', $item['codigo_b'], 20, 'horizontal', 'code128', true, 1);
        $oPDF->SetFont('Arial', '', 8);
        //$oPDF->Cell(25,5,$item['codigo_b'],0,0,'L');
        $oPDF->Cell(45,8,$oPDF->Image('codigo'.$item['codigo_b'].'.png',$oPDF->GetX(),$oPDF->GetY(),45,0,'PNG'),0,0,'L');
        $oPDF->Cell(30,8,$item['codigo_p'],0,0,'L');
        $oPDF->Cell(75,8,$item['nombre'],0,0,'L');
        $oPDF->Cell(10,8,$item['precio'],0,0,'R');
        $oPDF->SetFont('Arial', 'B', 9);
        $oPDF->Cell(10,8,$item['cantidad'],0,0,'C');
        $oPDF->SetFont('Arial', '', 8);
        $oPDF->Cell(10,8,$oPDF->Image('../../../imagenes/varias/checkbox.png',$oPDF->GetX(),$oPDF->GetY(),6,6,'PNG'),0,0,'C');
        //$oPDF->Image('/farma/imagenes/varias/ajax-loader.gif',0,0,10,5,'GIF');
        $oPDF->Ln();
        $unidades = $unidades + $item['cantidad'];
        if ($item['precio'] > 0){
            $importe = $importe + ($item['precio'] * $item['cantidad']);
        } else {
            $productosCero++;
        }
        $productos ++;
        $renglon ++;
        unlink('codigo'.$item['codigo_b'].'.png'); 
    // Sin Código de Barra
    } else {
        $oPDF->SetFont('Arial', '', 8);
        $oPDF->Cell(25,5,$item['codigo_b'],0,0,'L');
        $oPDF->Cell(30,5,$item['codigo_p'],0,0,'L');
        $oPDF->Cell(75,5,$item['nombre'],0,0,'L');
        $oPDF->Cell(10,5,$item['precio'],0,0,'R');
        $oPDF->SetFont('Arial', 'B', 8);
        $oPDF->Cell(10,5,$item['cantidad'],0,0,'C');
        $oPDF->SetFont('Arial', '', 8);
        $oPDF->Cell(10,5,$oPDF->Image('../../../imagenes/varias/checkbox.png',$oPDF->GetX(),$oPDF->GetY(),5,5,'PNG'),0,0,'C');
        //$oPDF->Image('/farma/imagenes/varias/ajax-loader.gif',0,0,10,5,'GIF');
        $oPDF->Ln();
        $unidades = $unidades + $item['cantidad'];
        if ($item['precio'] > 0){
            $importe = $importe + ($item['precio'] * $item['cantidad']);
        } else {
            $productosCero++;
        }
        $productos ++;
        $renglon ++;
    }
}

// Totales del pedido
$comentario = ' ';
if ($productosCero > 0) $comentario = " *** ".$productosCero." productos sin valuar ***";
$oPDF->Line(20, $oPDF->GetY(), 200, $oPDF->GetY());
$oPDF->Cell(160, 4,'Son '.$productos.' productos y '.$unidades.' unidades, valuado en $ '.$importe." (sin IVA) ".$comentario,0,0,'L');
$oPDF->Ln();
$oPDF->Line(20, $oPDF->GetY(), 200, $oPDF->GetY());
$oPDF->Ln();
// Pie de la página
$oPDF->Cell(80, 4,"Farmacia Villa Elisa SRL (CUIT: 30-71391160-3)",0,0,'L');
$oPDF->Cell(80, 4,'Impreso: '.date("d-m-Y H:i:s"),0,0,'R');
$oPDF->Output('Rotulos','I');

?>