<?php
/**
 * Archivo del includes del módulo pedido.
 *
 * Archivo del includes del módulo pedido que permite cargar
 * datos de un registro de la tabla pedidos para representar
 * en una vista.
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
 * Clase del includes del módulo pedido.
 *
 * Clase del includes del módulo pedido que permite cargar
 * datos de un registro de la tabla pedidos para representar
 * en una vista.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */

class PedidoDatos
{
    # Propiedades
    
    # Métodos
    /**
     * Nos permite cargar datos de un registro de la tabla
     * pedidos para representar en una vista.
     *
     * @param $oPedidoVO
     * @param $oDatoVista
     * @param $accion
     *
     * @return $oDatoVista
     */
    static function cargaDatos($oPedidoVO, $oProveedorVO, $oDatoVista, $accion)
    {
        // Establece el localismo por moneda
        setlocale(LC_MONETARY, 'es_AR.UTF-8');
        
        $oDatoVista->setDato('{id}', $oPedidoVO->getId());
        $oDatoVista->setDato('{idProveedor}', $oProveedorVO->getId());
        $oDatoVista->setDato('{proveedor}', $oProveedorVO->getRazonSocial());
        $oDatoVista->setDato('{fecha}', $oPedidoVO->getFecha());
        
        // busca glyphicon para canal
        switch ($oPedidoVO->getCanal()){
            case 'Telefonico':
                $canal = '<span class="glyphicon glyphicon-phone-alt" style="color:blue" aria-hidden="true" title="Pedido telefónico"></span>';
                break;
            case 'CardShop':
                $canal = '<span class="glyphicon glyphicon-shopping-cart" style="color:blue" aria-hidden="true" title="Pedido por página web"></span>';
                break;
            case 'Email':
                $canal = '<span class="glyphicon glyphicon-send" style="color:blue" aria-hidden="true" title="Pedido por Email"></span>';
                break;
            case 'Visitador':
                $canal = '<span class="glyphicon glyphicon-briefcase" style="color:blue" aria-hidden="true" title="Pedido al visitador del proveedor"></span>';
                break;
            case 'en Local':
                $canal = '<span class="glyphicon glyphicon-home" style="color:blue" aria-hidden="true" title="Pedido en local del proveedor"></span>';
                break;
            default:
                $canal = "";
        }
        
        // busca glyphicon para estado
        switch ($oPedidoVO->getEstado()){
            case '1':
                $estado = '<span class="glyphicon glyphicon-list-alt" style="color:gray" aria-hidden="true" title="Pedido sin recibir"></span>';
                break;
            case '2':
                $estado = '<span class="glyphicon glyphicon-edit" style="color:yellow" aria-hidden="true" title="Pedido recibido parcial"></span>';
                break;
            case '3':
                $estado = '<span class="glyphicon glyphicon-check" style="color:green" aria-hidden="true" title="Pedido recibido"></span>';
                break;
            default:
                $estado = "";
        }

        /*
        $canal = $oPedidoVO->getCanal();
        $estado = $oPedidoVO->getEstado();
        */
        $oDatoVista->setDato('{canal}', $canal);
        $oDatoVista->setDato('{estado}', $estado);
        $oDatoVista->setDato('{fechaRec}', $oPedidoVO->getFechaRec());
        $oDatoVista->setDato('{comentario}', $oPedidoVO->getComentario());
        return $oDatoVista;
    }
}
?>