<?php
/**
 * Archivo del includes.
 *
 * Archivo del includes que nos permite calcular los precios de compra según las
 * condiciones de los proveedores.
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
 */

/**
 * Clase que nos permite calcular los precios de compra de los productos.
 *
 * Clase que nos permite calcular los precios de compra de los preoductos
 * según las condiciones de los proveedores.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http:
 * @since      Class available since Release 1.0
 */

class CalcularPrecioProv
{
    /**
     * Nos permite calcular el precio de compra de los productos
     */
    
    # Propiedades
    
    # Metodos
    /**
     * Calcula los precios de los proveedores según la condiciones de compra
     * recibidos en un array
     * 
     * @param array $aProductos
     * @return array
     */
    static function calculaPrecios($aProductos)
    {   
        $aProductosPrecios = array();
        foreach ($aProductos as $producto){
            // Calcula descuentos y flete
            switch ($producto['id_proveedor']){
                case '2': // Del Sud
                    $iva = 0;
                    $desc = 5;
                    $flete = 0;
                    break;
                case '3': // Nippon
                    $iva = 21;
                    $desc = 0;
                    $flete = 3.15;
                    break;
                case '4': // Keller
                    $iva = 0;
                    $desc = 5;
                    $flete = 0;
                    break;
                case '5': // CoopLitoral
                    $iva = 0;
                    $desc = 2;
                    $flete = 0;
                    break;
                default:
                    $iva = 0;
                    $desc = 0;
                    $flete = 0;
                    break;
            }
            // calculo iva -------------
            if ($iva > 0) $producto['precio'] = round($producto['precio'] / (1 + ($iva / 100)), 2);
            // calculo descuento -------
            if ($desc > 0) $producto['precio'] = round($producto['precio'] * ((100 - $desc)/100), 2);
            // --------------------------
            // calculo flete ------------
            If ($flete > 0) $producto['precio'] = $producto['precio'] + round($producto['precio'] * $flete /100, 2);
            // ----------------------
           
            $producto['precio'] = number_format($producto['precio'] * 1, 2, '.', '');
            array_push($aProductosPrecios, $producto);
        }
        return $aProductosPrecios;
    }

    /**
     * Calcula precio de compra de un producto según la condición del proveedor.
     * 
     * @param int $idProveedor
     * @param #decimal $precio
     * @return #decimal $precio
     */
    static function calculaUnPrecio($idProveedor, $precio)
    {
        switch ($idProveedor){
            case '2': // Del Sud
                $iva = 0;
                $desc = 5;
                $flete = 0;
                break;
            case '3': // Nippon
                $iva = 21;
                $desc = 0;
                $flete = 3.15;
                break;
            case '4': // Keller
                $iva = 0;
                $desc = 5;
                $flete = 0;
                break;
            case '5': // CoopLitoral
                $iva = 0;
                $desc = 2;
                $flete = 0;
                break;
            default:
                $iva = 0;
                $desc = 0;
                $flete = 0;
                break;
            
        }
        // calculo iva -------------
        if ($iva > 0) $precio = round($precio / (1 + ($iva / 100)), 2);
        // calculo descuento -------
        if ($desc > 0) $precio = round($precio * ((100 - $desc)/100), 2);
        // --------------------------
        // calculo flete ------------
        If ($flete > 0) $precio = $precio + round($precio * $flete /100, 2);
        // ----------------------
        $precio = number_format($precio * 1, 2, '.', '');
        return $precio;
    }
}
?>