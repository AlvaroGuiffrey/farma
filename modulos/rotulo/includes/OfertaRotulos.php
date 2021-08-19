<?php
/**
 * Archivo que descarga los rotulos de los artículos.
 *
 * Archivo que descarga los rótulos de los artículos ordenos por nombre a un PDF.
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

require('C://xampp/htdocs/farma/libs/php/FPDF_v1.7/fpdf.php');

class OfertaRotulos extends FPDF
{
	
	function tabla($datos)
	{
		$this->datos($datos);
	}
	
	function cabecera($cabecera, $ejeXcabecera, $ejeYcabecera)
    {
        
        $this->SetFont('Arial','B',20);// Tipo Letra 
        $this->SetFillColor(145,145,145);//Fondo de celda
        $this->SetTextColor(240, 255, 240); //Letra color blanco
        $this->RoundedRect($ejeXcabecera, $ejeYcabecera, 40, 70, 2, 'D');// Rect�ngulo del r�tulo
        $this->RoundedRect($ejeXcabecera, $ejeYcabecera, 40, 14, 2, 'FD');// Rect�ngulo del tipo (Oferta, Promo, etc)
        $this->SetXY($ejeXcabecera, $ejeYcabecera);
        $this->CellFitSpace(40,14, utf8_decode($cabecera),0, 0 , 'C');
                
    }
 
    function datos($datos)
    {
        
        $ejeX = 5;
        $ejeYcabecera = 20; // Posici�n de la primer cabecera
        $ejeY = 34; //Aqu� se encuentra la primer CellFitSpace e ir� incrementando
        $letra = 'D'; //'D' Dibuja borde de cada CellFitSpace -- 'FD' Dibuja borde y rellena
        $cont = 0; // Contador de r�tulos para avanzar en la p�gina a la pr�xima l�nea
        $contL = 0; // Contador de l�neas de r�tulos para saltar a otra p�gina
        foreach($datos as $fila)
        {
        	/**
        	 * Cuenta los r�tulos de una l�nea
        	 */
        	if ($cont == 5){
        		$cont = 0; // Vuelve a cero el contador de r�tulos de la l�nea
        		$contL++; //Cuenta una l�nea de r�tulos para avanzar de p�gina
        		$ejeX = 5; // Vuelve al margen izquierdo
        		$ejeY = $ejeY + 90;
        		$ejeYcabecera = $ejeYcabecera + 90; // Avanza a otra l�nea de r�tulos
        	}
        	/**
        	 * Cuenta las l�neas de r�tulos para salto de p�gina
        	 */
        	if ($contL == 3){
        		$contL = 0;
        		$ejeYcabecera = 20;
        		$ejeY = 34;
        		$this->AddPage();
        	}
        	/**
        	 * Cabecera del r�tulo
        	 */
        	$cabecera = $fila['tipo'];
        	$ejeXcabecera = $ejeX;
        	$this->cabecera($cabecera, $ejeXcabecera, $ejeYcabecera);
        	/**
        	 * Datos del r�tulo
        	 */
        	// CONDICION
        	$this->SetTextColor(3, 3, 3); //Color del texto: Negro
        	$this->SetXY($ejeX, $ejeY);
        	if ($fila['idTipo']==3){ // Texto para el tipo de condición 3 (X% en 2da UN)
        	    $porcentaje = substr($fila['condi'], 0, 3);
        	    $condi = substr($fila['condi'], 3, 10);
        	    $this->SetFont('Arial','B',20);
        	    $this->CellFitSpace(15,12, utf8_decode($porcentaje),1, 0 , 'C');
        	    $this->SetFont('Arial','',14);
        	    $this->CellFitSpace(25,12, utf8_decode($condi),1, 0 , 'C');
        	} else { // Texto para los demás tipos de condiciones
        	    // Corta el texto si es mayor de 10 caracteres
        	    if (strlen($fila['condi'])>10){
        	        $condi = substr($fila['condi'], 0, 10);
        	    } else {
        	        $condi = $fila['condi'];
        	    }
        	    // Cambia el tipo de letra de acuerdo al largo del texto
        	    if (strlen($condi) < 7) {
        	        $this->SetFont('Arial','B',32);// Tipo Letra
        	    } else {
        	        $this->SetFont('Arial','B',20);// Tipo Letra
        	    }
        	    $this->CellFitSpace(40,12, utf8_decode($condi),1, 0 , 'C');
        	}
        	// NOMBRE Y PRESENTACION ARTICULO
        	$ejeYLinea = $ejeY + 12;
        	$this->SetXY($ejeX, $ejeYLinea);
        	$this->SetFont('Arial','B',8);// Tipo Letra
        	$this->CellFitSpace(40,7,utf8_decode(substr($fila['nombre'],0,25)),0, 0 , 'C');
        	$ejeYLinea = $ejeYLinea + 5;
        	$this->SetXY($ejeX, $ejeYLinea);
        	$this->SetFont('Arial','',8);// Tipo Letra
        	$this->CellFitSpace(40,7,utf8_decode(substr($fila['presen'],0,25)),0, 0 , 'C');
			// CODIGO DE BARRA
        	$ejeYLinea = $ejeYLinea + 5;
        	$this->SetXY($ejeX, $ejeYLinea);
        	$this->SetFont('Arial','',7);// Tipo Letra
        	$this->CellFitSpace(40,7,'Cod.: '.$fila['codigo'],0, 0 , 'C');
        	// PRECIO VENTA
        	$ejeYLinea = $ejeYLinea + 5;
        	$this->SetXY($ejeX, $ejeYLinea);
        	$this->SetFont('Arial','',10);// Tipo Letra
        	$this->CellFitSpace(40,7,' Precio: $ '.$fila['precio'],0, 0 , 'L');
        	// PRECIO ESPECIAL
        	$ejeYLinea = $ejeYLinea + 7;
        	$this->SetXY($ejeX, $ejeYLinea);
        	$ejeXLinea = $ejeX + 2;
        	$this->RoundedRect($ejeXLinea, $ejeYLinea, 36, 15, 2, 'D');
        	$this->SetXY($ejeXLinea, $ejeYLinea);
        	$this->SetFont('Arial','',10);// Tipo Letra
        	if ($fila['idTipo']==5){
        	    $this->CellFitSpace(36,7,'En '.$fila['cuota'].' cuotas de:',0, 0 , 'L');
        	} else {
        	   $this->CellFitSpace(36,7,$fila['tipo'].' (la Un.):',0, 0 , 'L');
        	}
        	$ejeYLinea = $ejeYLinea + 7;
        	$this->SetXY($ejeXLinea, $ejeYLinea);
        	$this->SetFont('Arial','B',19);// Tipo Letra
        	if ($fila['idTipo']==5){
        	   $this->CellFitSpace(36,7,'$ '.$fila['importeCuota'],0, 0 , 'C');
        	} else {
        	    $this->CellFitSpace(36,7,'$ '.$fila['precioCondi'],0, 0 , 'C');
        	}
        	// CANTIDAD MINIMA
        	$ejeYLinea = $ejeYLinea + 8;
        	$this->SetXY($ejeX, $ejeYLinea);
        	$this->SetFont('Arial','B',7);// Tipo Letra
        	$this->CellFitSpace(40,5,'Cantidad: '.$fila['cantidad']." Un.",0, 0 , 'C');
        	// CONDICION
        	$ejeYLinea = $ejeYLinea + 2;
        	$this->SetXY($ejeX, $ejeYLinea);
        	$this->SetFont('Arial','I',6);// Tipo Letra
        	if ($fila['idTipo']==5){
        	    $this->CellFitSpace(40,7,'c/TARJETAS de CREDITOS',0, 0 , 'C');
        	} else {
            	$this->CellFitSpace(40,7,'c/EFECTIVO, TARJ. DEB. Y CRED.',0, 0 , 'C');
        	}
        	// FECHA VIGENCIA
        	$ejeYLinea = $ejeYLinea + 3;
        	$this->SetXY($ejeX, $ejeYLinea);
        	$this->SetFont('Arial','',6);// Tipo Letra
        	$this->CellFitSpace(40,7,'Vig. hasta: '.$fila['fechaHasta'],0, 0 , 'C');
           
        	// Fin datos del r�tulo
            //Aumenta la siguiente posici�n de X
            $ejeX = $ejeX + 40;
            $cont++;
            
        }
    }
 
 
    //**************************************************************************************************************
    function CellFit($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $scale=false, $force=true)
    {
        //Get string width
        $str_width=$this->GetStringWidth($txt);
 
        //Calculate ratio to fit cell
        if($w==0)
            $w = $this->w-$this->rMargin-$this->x;
            $ratio = ($w-$this->cMargin*2)/$str_width;
 
        $fit = ($ratio < 1 || ($ratio > 1 && $force));
        if ($fit)
        {
            if ($scale)
            {
                //Calculate horizontal scaling
                $horiz_scale=$ratio*100.0;
                //Set horizontal scaling
                $this->_out(sprintf('BT %.2F Tz ET',$horiz_scale));
            }
            else
            {
                //Calculate character spacing in points
                $char_space=($w-$this->cMargin*2-$str_width)/max($this->MBGetStringLength($txt)-1,1)*$this->k;
                //Set character spacing
                $this->_out(sprintf('BT %.2F Tc ET',$char_space));
            }
            //Override user alignment (since text will fill up cell)
            $align='';
        }
 
        //Pass on to Cell method
        $this->Cell($w,$h,$txt,$border,$ln,$align,$fill,$link);
 
        //Reset character spacing/horizontal scaling
        if ($fit)
            $this->_out('BT '.($scale ? '100 Tz' : '0 Tc').' ET');
    }
 
    function CellFitSpace($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,false);
    }
 
    //Patch to also work with CJK double-byte text
    function MBGetStringLength($s)
    {
        if($this->CurrentFont['type']=='Type0')
        {
            $len = 0;
            $nbbytes = strlen($s);
            for ($i = 0; $i < $nbbytes; $i++)
            {
                if (ord($s[$i])<128)
                    $len++;
                else
                {
                    $len++;
                    $i++;
                }
            }
            return $len;
        }
        else
            return strlen($s);
    }
//**********************************************************************************************
 
 function RoundedRect($x, $y, $w, $h, $r, $style = '', $angle = '1234')
    {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' or $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2f %.2f m', ($x+$r)*$k, ($hp-$y)*$k ));
 
        $xc = $x+$w-$r;
        $yc = $y+$r;
        $this->_out(sprintf('%.2f %.2f l', $xc*$k, ($hp-$y)*$k ));
        if (strpos($angle, '2')===false)
            $this->_out(sprintf('%.2f %.2f l', ($x+$w)*$k, ($hp-$y)*$k ));
        else
            $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
 
        $xc = $x+$w-$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2f %.2f l', ($x+$w)*$k, ($hp-$yc)*$k));
        if (strpos($angle, '3')===false)
            $this->_out(sprintf('%.2f %.2f l', ($x+$w)*$k, ($hp-($y+$h))*$k));
        else
            $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
 
        $xc = $x+$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2f %.2f l', $xc*$k, ($hp-($y+$h))*$k));
        if (strpos($angle, '4')===false)
            $this->_out(sprintf('%.2f %.2f l', ($x)*$k, ($hp-($y+$h))*$k));
        else
            $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
 
        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2f %.2f l', ($x)*$k, ($hp-$yc)*$k ));
        if (strpos($angle, '1')===false)
        {
            $this->_out(sprintf('%.2f %.2f l', ($x)*$k, ($hp-$y)*$k ));
            $this->_out(sprintf('%.2f %.2f l', ($x+$r)*$k, ($hp-$y)*$k ));
        }
        else
            $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }
 
    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }
} // FIN Class PDF
?>