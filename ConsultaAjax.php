<?php
/**
 * Clase de prueba de consulta con AJAX
 *
 * Probaremos de realizar la consulta a la DB farma desde una clase.
 * Versión: 01
 */
// Inicia o reanuda la sesión
session_start();
// Carga las clases necesarias
require_once $_SERVER['DOCUMENT_ROOT'].'/farma/includes/control/Clase.php';


Class Consulta
{
    
    static function execute() {
        // Define las clases
        Clase::define('ArticuloModelo');
        // Instancia las Clases necesarios
        $oArticuloVO = new ArticuloVO();
        $oArticuloModelo = new ArticuloModelo();
        // Recibe la petición por POST
        //$id = $_POST['id'];
        $id = 125;
        // Consulta el artículo
        $oArticuloVO->setId($id);
        $oArticuloModelo->find($oArticuloVO);
        // Arma datos en un objeto php
        $oDatos = new stdClass();
        $oDatos->nombre = $oArticuloVO->getNombre();
        $oDatos->presentacion = $oArticuloVO->getPresentacion();
        $oDatos->codigo_b = $oArticuloVO->getCodigoB();
        
        // Arma datos de respuesta en un array
        /*
         $aDatos = [
         nombre => $oArticuloVO->getNombre(),
         presentacion => $oArticuloVO->getPresentacion(),
         codigoB => $oArticuloVO->getCodigoB(),
         ];
         */
        //var_dump($aDatos);
        // Envia la respuesta con jSON de un objeto
        //echo json_encode($oDatos, JSON_FORCE_OBJECT);
        echo json_encode($oDatos);
        exit();
    }
}

Consulta::execute();
?>