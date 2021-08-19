<?php
/**
 * Archivo que descarga los rotulos de los artículos.
 *
 * Archivo que descarga los rótulos de los artículos ordenos por nombre.
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
Clase::define('FPDF');
Clase::define('ArticuloModelo');

// Instancia las clases
$oLoginVO = new LoginVO();
$oLoginControl = new LoginControl();

$oPDF = new FPDF();
$oArticuloVO = new ArticuloVO();
$oArticuloModelo = new ArticuloModelo();

// Carga usuario y chequea login
$oLoginControl->cargarUsuario($oLoginVO);
$oLoginControl->chequearLogin($oLoginVO);

// consulta tabla artículos
$aRotulos = $oArticuloModelo->findAllRotulosPDF();

/**
 * Arma PDF de los rótulos de artículos a descargar
 */
$oPDF->Open();
$oPDF->AddPage();
$oPDF->SetFont('Arial', '', 10);
$oPDF->SetTitle('Descarga Rótulos PDF', true);
$oPDF->SetAuthor('Alvaro Guiffrey');
$oPDF->SetCreator('Farmacia Villa Elisa SRL');
$rot=0;
$pag=0;
foreach ($aRotulos as $row){
	// cambio valor de rotulo en tabla articulos
	$oArticuloVO->setId($row['id']);
	$oArticuloModelo->find($oArticuloVO);
	$rotulo = 1; // vuelve a artículo con rótulo
	$oArticuloVO->setRotulo($rotulo);
	$oArticuloModelo->update($oArticuloVO);
	
	// armo rotulos con datos del array $aRotulos
	$rot++;
	if ($rot==1){
		$codigoB1 = $row['codigo_b'];
		$nombre1 = $row['nombre'];
		$presentacion1 = $row['presentacion'];
		$precio1 = $row['precio'];
		$fechaPrecio1 = $row['fecha_precio'];
		$idProveedor1 = $row['id_proveedor'];
		$codigoB2 = $codigoB3 = $codigoB4 = "";
		$nombre2 = $nombre3 = $nombre4 = "";
		$presentacion2 = $presentacion3 = $presentacion4 = "";
		$precio2 = $precio3 = $precio4 = 0;
		$fechaPrecio2 = $fechaPrecio3 = $fechaPrecio4 = "";
		$idProveedor2 = $idProveedor3 = $idProveedor4 = 0;
	}
	if ($rot==2){
		$codigoB2 = $row['codigo_b'];
		$nombre2 = $row['nombre'];
		$presentacion2 = $row['presentacion'];
		$precio2 = $row['precio'];
		$fechaPrecio2 = $row['fecha_precio'];
		$idProveedor2 = $row['id_proveedor'];
		$codigoB3 = $codigoB4 = "";
		$nombre3 = $nombre4 = "";
		$presentacion3 = $presentacion4 = "";
		$precio3 = $precio4 = 0;
		$fechaPrecio3 = $fechaPrecio4 = "";
		$idProveedor3 = $idProveedor4 = 0;
	}
	if ($rot==3){
		$codigoB3 = $row['codigo_b'];
		$nombre3 = $row['nombre'];
		$presentacion3 = $row['presentacion'];
		$precio3 = $row['precio'];
		$fechaPrecio3 = $row['fecha_precio'];
		$idProveedor3 = $row['id_proveedor'];
		$codigoB4 = "";
		$nombre4 = "";
		$presentacion4 = "";
		$precio4 = 0;
		$fechaPrecio4 = "";
		$idProveedor4 = 0;
	}
	if ($rot==4){
		$codigoB4 = $row['codigo_b'];
		$nombre4 = $row['nombre'];
		$presentacion4 = $row['presentacion'];
		$precio4 = $row['precio'];
		$fechaPrecio4 = $row['fecha_precio'];
		$idProveedor4 = $row['id_proveedor'];
	
		$oPDF->SetFont('Arial', 'B', 10);
		$oPDF->SetFillColor(255,255,255);
		// Línea superior
		$oPDF->Cell(180,0,'','T');
		$oPDF->Ln(1);
		$oPDF->SetFont('Arial', '', 8);
		$oPDF->Cell(45,4,'','LR',0,'L',1);
		$oPDF->Cell(45,4,'','LR',0,'L',1);
		$oPDF->Cell(45,4,'','LR',0,'L',1);
		$oPDF->Cell(45,4,'','LR',0,'L',1);
		$oPDF->Ln();
		
		// Línea códigos de barra
		$oPDF->SetFont('Arial', '', 8);
		$oPDF->Cell(45,2,$codigoB1,'LR',0,'L',1);
		$oPDF->Cell(45,2,$codigoB2,'LR',0,'L',1);
		$oPDF->Cell(45,2,$codigoB3,'LR',0,'L',1);
		$oPDF->Cell(45,2,$codigoB4,'LR',0,'L',1);
		$oPDF->Ln();
		
		// Línea nombres
		// Primer nombre 
		if (strlen(trim($nombre1))<17){
			$oPDF->SetFont('Arial', 'B', 12);
		}else{
			$oPDF->SetFont('Arial', 'B', 10);
		}
		if(strlen(trim($nombre1))>21){
			$oPDF->SetFont('Arial', 'B', 8);
		}
		$oPDF->Cell(45,7,$nombre1,'LR',0,'L',1);
		// Segundo nombre
		if (strlen(trim($nombre2))<17){
			$oPDF->SetFont('Arial', 'B', 12);
		}else{
			$oPDF->SetFont('Arial', 'B', 10);
		}
		if(strlen(trim($nombre2))>21){
			$oPDF->SetFont('Arial', 'B', 8);
		}
		$oPDF->Cell(45,7,$nombre2,'LR',0,'L',1);
		// Tercer nombre
		if (strlen(trim($nombre3))<17){
			$oPDF->SetFont('Arial', 'B', 12);
		}else{
			$oPDF->SetFont('Arial', 'B', 10);
		}
		if(strlen(trim($nombre3))>21){
			$oPDF->SetFont('Arial', 'B', 8);
		}
		$oPDF->Cell(45,7,$nombre3,'LR',0,'L',1);				
		// Cuarto nombre
		if (strlen(trim($nombre4))<17){
			$oPDF->SetFont('Arial', 'B', 12);
		}else{
			$oPDF->SetFont('Arial', 'B', 10);
		}
		if(strlen(trim($nombre4))>21){
			$oPDF->SetFont('Arial', 'B', 8);
		}
		$oPDF->Cell(45,7,$nombre4,'LR',0,'L',1);
		$oPDF->Ln(7);	
		
		// Linea presentaciones
		$oPDF->SetFont('Arial', '', 8);
		$oPDF->Cell(45,3,$presentacion1,'LR',0,'L',1);
		$oPDF->Cell(45,3,$presentacion2,'LR',0,'L',1);
		$oPDF->Cell(45,3,$presentacion3,'LR',0,'L',1);
		$oPDF->Cell(45,3,$presentacion4,'LR',0,'L',1);
		$oPDF->Ln(4);
	
		// Linea precios
		$oPDF->SetFont('Arial', 'B', 18);
		$oPDF->Cell(45,9,'$'.' '.$precio1,'LR',0,'C',1);
		$oPDF->Cell(45,9,'$'.' '.$precio2,'LR',0,'C',1);
		$oPDF->Cell(45,9,'$'.' '.$precio3,'LR',0,'C',1);
		$oPDF->Cell(45,9,'$'.' '.$precio4,'LR',0,'C',1);
		$oPDF->Ln();
		
		// Línea fecha
		$oPDF->SetFont('Arial', '', 6);
		$oPDF->Cell(45,2,$fechaPrecio1.' - Prov:('.$idProveedor1.')','LR',0,'L',1);
		$oPDF->Cell(45,2,$fechaPrecio2.' - Prov:('.$idProveedor2.')','LR',0,'L',1);
		$oPDF->Cell(45,2,$fechaPrecio3.' - Prov:('.$idProveedor3.')','LR',0,'L',1);
		$oPDF->Cell(45,2,$fechaPrecio4.' - Prov:('.$idProveedor4.')','LR',0,'L',1);
		$oPDF->Ln();
		
		// Espacios finales
		$oPDF->SetFont('Arial', '', 8);
		$oPDF->Cell(45,3,'','LR',0,'L',1);
		$oPDF->Cell(45,3,'','LR',0,'L',1);
		$oPDF->Cell(45,3,'','LR',0,'L',1);
		$oPDF->Cell(45,3,'','LR',0,'L',1);
		$oPDF->Ln();
		
		// Línea separadora
		$oPDF->Cell(180,0,'','T');
		$oPDF->Ln(1);
		
		// totaliza para renglones y página
		$rot=0;
		$pag++;
	}
	// avanza una página
	if ($pag==8){
		$oPDF->AddPage();
		$pag=0;
	}
	
}
	// menos de 4 rótulos
	if ($rot<4){
		$oPDF->SetFont('Arial', 'B', 10);
		$oPDF->SetFillColor(255,255,255);
		
		// Línea superior
		$oPDF->Cell(180,0,'','T');
		$oPDF->Ln(1);
		$oPDF->SetFont('Arial', '', 8);
		$oPDF->Cell(45,4,'','LR',0,'L',1);
		$oPDF->Cell(45,4,'','LR',0,'L',1);
		$oPDF->Cell(45,4,'','LR',0,'L',1);
		$oPDF->Cell(45,4,'','LR',0,'L',1);
		$oPDF->Ln();

		// Línea códigos de barra
		$oPDF->SetFont('Arial', '', 8);
		$oPDF->Cell(45,2,$codigoB1,'LR',0,'L',1);
		$oPDF->Cell(45,2,$codigoB2,'LR',0,'L',1);
		$oPDF->Cell(45,2,$codigoB3,'LR',0,'L',1);
		$oPDF->Cell(45,2,$codigoB4,'LR',0,'L',1);
		$oPDF->Ln();
		
		// Línea nombres
		// Primer nombre
		if (strlen(trim($nombre1))<17){
			$oPDF->SetFont('Arial', 'B', 12);
		}else{
			$oPDF->SetFont('Arial', 'B', 10);
		}
		if(strlen(trim($nombre1))>21){
			$oPDF->SetFont('Arial', 'B', 8);
		}
		$oPDF->Cell(45,7,$nombre1,'LR',0,'L',1);
		// Segundo nombre
		if (strlen(trim($nombre2))<17){
			$oPDF->SetFont('Arial', 'B', 12);
		}else{
			$oPDF->SetFont('Arial', 'B', 10);
		}
		if(strlen(trim($nombre2))>21){
			$oPDF->SetFont('Arial', 'B', 8);
		}
		$oPDF->Cell(45,7,$nombre2,'LR',0,'L',1);
		// Tercer nombre
		if (strlen(trim($nombre3))<17){
			$oPDF->SetFont('Arial', 'B', 12);
		}else{
			$oPDF->SetFont('Arial', 'B', 10);
		}
		if(strlen(trim($nombre3))>21){
			$oPDF->SetFont('Arial', 'B', 8);
		}
		$oPDF->Cell(45,7,$nombre3,'LR',0,'L',1);
		// Cuarto nombre
		if (strlen(trim($nombre4))<17){
			$oPDF->SetFont('Arial', 'B', 12);
		}else{
			$oPDF->SetFont('Arial', 'B', 10);
		}
		if(strlen(trim($nombre4))>21){
			$oPDF->SetFont('Arial', 'B', 8);
		}
		$oPDF->Cell(45,7,$nombre4,'LR',0,'L',1);
		$oPDF->Ln(8);
		
		// Linea presentaciones
		$oPDF->SetFont('Arial', '', 8);
		$oPDF->Cell(45,3,$presentacion1,'LR',0,'L',1);
		$oPDF->Cell(45,3,$presentacion2,'LR',0,'L',1);
		$oPDF->Cell(45,3,$presentacion3,'LR',0,'L',1);
		$oPDF->Cell(45,3,$presentacion4,'LR',0,'L',1);
		$oPDF->Ln(4);
		
		// Linea precios
		$oPDF->SetFont('Arial', 'B', 18);
		$oPDF->Cell(45,9,'$'.' '.$precio1,'LR',0,'C',1);
		$oPDF->Cell(45,9,'$'.' '.$precio2,'LR',0,'C',1);
		$oPDF->Cell(45,9,'$'.' '.$precio3,'LR',0,'C',1);
		$oPDF->Cell(45,9,'$'.' '.$precio4,'LR',0,'C',1);
		$oPDF->Ln();
		
		// Línea fecha
		$oPDF->SetFont('Arial', '', 6);
		$oPDF->Cell(45,2,$fechaPrecio1.' - Prov:('.$idProveedor1.')','LR',0,'L',1);
		$oPDF->Cell(45,2,$fechaPrecio2.' - Prov:('.$idProveedor2.')','LR',0,'L',1);
		$oPDF->Cell(45,2,$fechaPrecio3.' - Prov:('.$idProveedor3.')','LR',0,'L',1);
		$oPDF->Cell(45,2,$fechaPrecio4.' - Prov:('.$idProveedor4.')','LR',0,'L',1);
		$oPDF->Ln();
		
		// Espacios finales
		$oPDF->SetFont('Arial', '', 8);
		$oPDF->Cell(45,3,'','LR',0,'L',1);
		$oPDF->Cell(45,3,'','LR',0,'L',1);
		$oPDF->Cell(45,3,'','LR',0,'L',1);
		$oPDF->Cell(45,3,'','LR',0,'L',1);
		$oPDF->Ln();
		
		// Linea separadora
		$oPDF->Cell(180,0,'','T');
		$oPDF->Ln(1);
	}


$oPDF->Output('Rotulos','I');

?>