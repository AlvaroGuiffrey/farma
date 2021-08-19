<?php
/**
 * Archivo del includes. 
 *
 * Archivo del includes que nos permite calcular la cantidad de productos
 * vendidos en PLEX.
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
 * Clase que nos permite calcular la cantidad de productos vendidos en PLEX.
 *
 * Clase que nos permite calcular la cantidad de productos vendidos en PLEX
 * en diferentes períodos para agregar a pedidos.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http:
 * @since      Class available since Release 1.0
 */

class CalcularProductosVendidos
{
    /**
     * Nos permite calcular los productos vendidos en diferentes períodos
     */
    
    # Propiedades

    # Metodos
 
    /**
     * Calcula las ventas de un producto en diferentes períodos de tiempo
     * según el código de producto recibido.
     *
     * @param integer $codigo
     * @return array
     */
    static function calculaVentas($codigo)
    {
        $fecha = date('Y-m-d');
        $fecha_7d = date('Y-m-d', strtotime("-7 day"));-
        $fecha_14d = date('Y-m-d', strtotime("-14 day"));
        $fecha_30d = date('Y-m-d', strtotime("-30 day"));
        $fecha_3m = date('Y-m-d', strtotime("-3 month"));
        $fecha_6m = date('Y-m-d', strtotime("-6 month"));
        $aFechas = array(
            $fecha_7d,
            $fecha_14d,
            $fecha_30d,
            $fecha_3m,
            $fecha_6m
        );
        // Instancia la conexión con PLEX
        DataBasePlex::getInstance();
        $aCantidadVentas = array();
        $hasta = $fecha;
        foreach ($aFechas as $desde){
            $query = "SELECT SUM(factlineas.Cantidad) AS cantidad FROM factlineas
            INNER JOIN factcabecera ON (factlineas.IDComprobante = factcabecera.IDComprobante)
            WHERE factlineas.IDProducto = ".$codigo."
            AND factcabecera.Emision >'".$desde."'
            AND factcabecera.Emision <='".$hasta."'
            AND (factcabecera.Tipo='TF' OR factcabecera.Tipo='TK')";
 // ERROR ->           $result = mysql_query($query) or die(mysql_error());
            $result = mysql_query($query); // Saque el die
            $fila = mysql_fetch_array($result);
            $aCantidadVentas[] = $fila;
            mysql_free_result($result);
            $hasta = $desde;
        }
        return $aCantidadVentas;
    }
    
}
?>