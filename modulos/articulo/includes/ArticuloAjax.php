<?php
/**
 * Archivo de la clase ajax del módulo artículo.
 *
 * Archivo de la clase ajax del módulo artículo para realizar operaciones
 * asíncronas.
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
 * Clase ajax del módulo artículo.
 *
 * Clase ajax del módulo artículo que permite realizar
 * operaciones asíncronas sobre la tabla artículos (CRUD y otras).
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
// Carga las clases necesarias
require_once $_SERVER['DOCUMENT_ROOT'].'/farma/includes/control/Clase.php';

Class ArticuloAjax
{
    #Propiedades
    
    #Métodos
    /**
     * Función estática que recibe la petición y responde la misma.
     */
    static function execute()
    {
        // Define las clases
        Clase::define('ArticuloModelo');
        // Instancia las Clases necesarios
        $oArticuloVO = new ArticuloVO();
        $oArticuloModelo = new ArticuloModelo();
        $oDatos = new stdClass(); // Objeto PHP para respuesta
        // Recibe la columna de la tabla por POST
        $columna = $_POST['columna'];
        // Recibe la acción de la petición por POST
        $accion = $_POST['accion'];
        // Selector de acciones de acuerdo a la columna de la tabla
        switch (TRUE){
            # ----> Acción modificar rótulo
            case ($columna == "rotulo" && $accion == "actualizar"):
                // Recibe el id del artículo de la petición
                $id = $_POST['id'];
                // Consulta el artículo
                $oArticuloVO->setId($id);
                $oArticuloModelo->find($oArticuloVO);
                // Cambia al valor de la columnma rótulo
                if ($oArticuloVO->getRotulo() > 0) {
                    $oArticuloVO->setRotulo(0);
                } else {
                    $oArticuloVO->setRotulo(1);
                }
                // Modifica el renglón de la tabla artículos
                $oArticuloModelo->update($oArticuloVO);
                // Arma datos para respuesta 
                $oDatos->columna = $columna;
                $oDatos->accion = $accion;
                $oDatos->valor = $oArticuloVO->getRotulo();
                $oDatos->cantidad = $oArticuloModelo->getCantidad();
                // Envia la respuesta con jSON de un objeto
                //echo json_encode($oDatos, JSON_FORCE_OBJECT);
                echo json_encode($oDatos);
                break;
        }
        exit();   
    }
}

ArticuloAjax::execute();
?>
