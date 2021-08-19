<?php
/**
 * Archivo del include del módulo condicion.
 *
 * Archivo del include del módulo condicion que realiza los cálculos 
 * necesarios para el precio y otros.
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

/**
 * Clase del include del módulo condicion.
 *
 * Clase CondicionCalculo del módulo condicion que permite realizar los
 * cálculos necesarios para el precio y otro.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class CondicionCalculo
{
    # Propiedades
    
    
    # Métodos
    /**
     * 
     */
    static function preciosCondi($oArticuloVO, $oCondicionVO)
    {
        $precioCondi = $precioDif = $totalPVP = $importeTotal = $ahorro = 0;
        if ($oCondicionVO->getIdTipo() == 1 OR $oCondicionVO->getIdTipo() == 2 OR $oCondicionVO->getIdTipo() == 5) {
            $multiplicador = (100 - $oCondicionVO->getDescuento())/100;
            $precioCondi = round(($oArticuloVO->getPrecio()*$multiplicador),2);
            $precioDif = $oArticuloVO->getPrecio() - $precioCondi;
            $importeTotal = $precioCondi * $oCondicionVO->getCantidadUn();
            $ahorro = $precioDif * $oCondicionVO->getCantidadUn();
            // Si es en cuotas con tarjetas divido el precio de la condición
            //if ($oCondicionVO->getIdTipo() == 5) $precioCondi = $precioCondi / $oCondicionVO->getCuota();
        } else {
            if ($oCondicionVO->getIdTipo() == 3) {
                $multiplicador = (100 - $oCondicionVO->getDescuento()) / 100;
                $precioCondi = round(($oArticuloVO->getPrecio() + round(($oArticuloVO->getPrecio()*$multiplicador),2))/ 2, 2);
                $precioDif = $oArticuloVO->getPrecio() - $precioCondi;
                $totalPVP = $oArticuloVO->getPrecio() * $oCondicionVO->getCantidadUn();
                $importeTotal = $oArticuloVO->getPrecio() + round(($oArticuloVO->getPrecio()*$multiplicador),2);
                $ahorro = $totalPVP - $importeTotal;
            } else {
                if ($oCondicionVO->getIdTipo() == 4) {
                    $precioCondi = round(($oArticuloVO->getPrecio() * $oCondicionVO->getCantidadPaga()) / $oCondicionVO->getCantidadUn(), 2);
                    $precioDif = $oArticuloVO->getPrecio() - $precioCondi;
                    $totalPVP = $oArticuloVO->getPrecio() * $oCondicionVO->getCantidadUn();
                    $importeTotal = $oArticuloVO->getPrecio() * $oCondicionVO->getCantidadPaga();
                    $ahorro = $totalPVP - $importeTotal;
                } else {
                    $precioCondi = $oArticuloVO->getPrecio();
                    $precioDif = $oArticuloVO->getPrecio() - $precioCondi;
                    $importeTotal = $precioCondi * $oCondicionVO->getCantidadUn();
                    $ahorro = $precioDif * $oCondicionVO->getCantidadUn();
                }
            }
        }
        
        if ($oCondicionVO->getCuota() == 0) {
            $cuota = 1;
            $importeCuota = $importeTotal;
        } else {
            $cuota = $oCondicionVO->getCuota();
            $importeCuota = round($importeTotal / $oCondicionVO->getCuota(), 2);
        }
        // arma vector
        $aDatos = array(
            "precioCondi" => $precioCondi,
            "precioDif" => $precioDif,
            "totalPVP" => $totalPVP,
            "importeTotal" => $importeTotal,
            "ahorro" => $ahorro,
            "cuota" => $cuota,
            "importeCuota" => $importeCuota
        );
        // Retorna el vector
        //var_dump($aDatos);
        return $aDatos;
    }
    
}
?>