<?php
/**
 * Archivo del include del módulo producto.
 *
 * Archivo del include del módulo producto que arma una tabla con todos
 * los registros seleccionados para la vista listar.
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
 * Clase del include del módulo producto.
 *
 * Clase ProductoPrecioMenorTabla del módulo producto que permite armar
 * una tabla con todos los registros seleccionados para la vista listar.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class ProductoPrecioMenorTabla
{
    # Propiedades
    
    # Métodos
    /**
     * Nos permite crear y guardar un fichero html con una tabla de todos
     * los registros de productos para representar en la vista listar.
     *
     * @param $items array()
     * @param $accion
     * @param $cantListado
     *
     * @return tabla.html (file.html)
     */
    static function armaTabla($items, $accion, $cantListado)
    {
        // Establece el localismo para la moneda
        //	setlocale(LC_MONETARY, 'es_AR.UTF-8');
        // Abre archivo tabla.html (va a representar la consulta)
        $filename = "tabla.html";
        $handle = fopen($filename, "w");
        if (FALSE === $handle){
            exit("falla al abrir el archivo");
        }
        // Titulo y pie de la tabla
        fwrite($handle, '<table class="table table-hover">'.PHP_EOL);
        if ($accion == 'ConfirmarL'){
            fwrite($handle, '<caption>Listado de Productos equivalentes del Proveedor</caption>'.PHP_EOL);
            // Pie de la tabla
            $renglon = '<tfoot><tr>
				        <td>Son: '.$cantListado.' productos en página.</td><td></td><td></td><td></td></tr>
			            </tfoot>'.PHP_EOL;
            fwrite($handle, $renglon);
        }
        
        // Encabezado de los renglones
        fwrite($handle, '<thead><tr><th title="Nombre del producto">Nombre</th><th title="Código de barra del producto">Cod. Barra</th><th title="Costo del producto">Costo</th><th title="Observaciones al producto">Observaciones</th></tr></thead>'.PHP_EOL);
        
        // Renglones/modulos/articulo/index.php de la tabla
        If ($cantListado==0){
            fwrite($handle, '<tbody><tr><td></td><td></td><td></td><td></td></tr></tbody>'.PHP_EOL);
        }else{
            fwrite($handle, '<tbody>'.PHP_EOL);
            foreach ($items as $item)
            {
                /**
                 * Renglón principal
                 */
                /* Renglón con money_format */
                //'<tr><td>'.$item['codigo'].'</td><td>'.$item['nombre'].'</td><td style="text-align:left">'.$item['presentacion'].'</td><td style="text-align:right">'.money_format("%.2n", $item['precio']).'</td>
                /* Renglón con number_format */
                $renglon  = '<tr><td>'.$item['nombre'].'</td><td>'.$item['codigoB'].'</td><td style="text-align:right"> $ '.number_format($item['costoProv'], 2, ',', '.').'</td>';
                $renglon .= '<td>';
                if ($item['precioMenor']=='SI') $renglon .= '<button type="button" class="btn btn-success btn-xs" title="Precio menor"><span class="glyphicon glyphicon-usd" aria-hidden="true"></span> menor</button>';
                if ($item['provUnico']=='SI') $renglon .= '<button type="button" class="btn btn-primary btn-xs" title="Proveedor único"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> único</button>';
                if ($item['condicionEspecial']=='SI') $renglon .= '<button type="button" class="btn btn-default btn-xs" title="Condición especial"><span class="glyphicon glyphicon-exclamation-sign" style="color:red" aria-hidden="true"></span></button>';
                if ($item['provUnico']=='NO') {
                    /* Comentario con number_format */
                    $aPrecios = $item['aPreciosProv'];
                    $comentario ='';
                    foreach ($aPrecios as $clave => $valor){
                        $comentario .= '['.$clave.'] $ '.number_format($valor, 2, ',', '.').' / ';
                    }
                    $renglon .= '<button type="button" class="btn btn-info btn-xs" title="'.$comentario.'"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button>';
                }
                $renglon .= '</td></tr>'.PHP_EOL;
                fwrite($handle, $renglon);
                
                
            }
            
            fwrite($handle, '</tbody>'.PHP_EOL);
            
        }
        // Cierra la tabla
        fwrite($handle, '</table>'.PHP_EOL);
        
        // Cierra el archivo tabla.html
        fclose($handle);
    }
}
?>