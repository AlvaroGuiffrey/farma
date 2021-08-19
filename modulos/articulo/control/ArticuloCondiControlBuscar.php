<?php
/**
 * Archivo de la clase control del módulo artículo.
 *
 * Archivo de la clase control del módulo artículo.
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
 * Clase control del módulo artículo.
 *
 * Clase control del módulo artículo que permite realizar
 * consultas sobre la tabla artículos_condi para buscar
 * las condiciones de venta de los artículos, según la 
 * categoría asignada al usuario.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class ArticuloCondiControlBuscar
{
    #Propiedades
    private $_html;
    private $_accion;
    private $_id;
    private $_idCondicion;
    private $_items;
    private $_item;
    private $_cantidad;
    private $_date;
    private $_aPreciosCondi = array();
    private $_aCondicion = array();
    private $_aArticulosCondi = array();
    private $_aRotulos = array();
    private $_aAcciones = array();
    private $_aEventos = array();
    public $tabla;
    
    #Métodos
    /**
     * Verifica el login del usuario y nos envia a la
     * función que ejecuta las acciones en el módulo.
     */
    public function inicio($oLoginVO, $accion)
    {
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        // Se definen las clases necesarias
        Clase::define('CargarVista');
        Clase::define('DatoVista');
        Clase::define('CargarMenu');
        Clase::define('MotorVista');
        Clase::define('ArticuloModelo');
        Clase::define('ArticuloCondiModelo');
        Clase::define('CondicionModelo');
        Clase::define('CondicionTipoModelo');
        Clase::define('CondicionSelect');
        Clase::define('CondicionCalculo');
        Clase::define('ArticuloCondiDatos');
        Clase::define('ArticuloCondiTabla');
        Clase::define('ArticuloCondiExisTabla');
        Clase::define('RotuloCondiTabla');
        
        
        // Chequea login
        $oLoginControl = new LoginControl();
        $oLoginControl->chequearLogin($oLoginVO);
        $this->_accion = $accion;
        $this->accionControl($oLoginVO);
    }
    
    /**
     * Nos permite ejecutar las acciones del módulo
     * artículo del sistema, de acuerdo a la categoría del
     * usuario.
     */
    private function accionControl($oLoginVO)
    {
        // Carga acciones del formulario
        /*
        if (isset($_POST['bt_agregar'])) $this->_accion = "Agregar";
        if (isset($_POST['bt_volver'])) $this->_accion = "BuscarArticulo";
        if (isset($_POST['bt_buscar_condi_conf'])) $this->_accion = "BuscarArticulo";
        if (isset($_POST['bt_buscar_articulo_conf'])) $this->_accion = "ConfirmarA";
        if (isset($_POST['bt_agregar_conf'])) $this->_accion = "ConfirmarA1";
        if (isset($_POST['bt_buscar'])) $this->_accion = "Buscar";
        if (isset($_POST['bt_buscar_conf'])) $this->_accion = "ConfirmarB";
        if (isset($_POST['bt_listar'])) $this->_accion = "Listar";
        if (isset($_POST['bt_listar_conf'])) $this->_accion = "ConfirmarL";
        if (isset($_POST['bt_descargar'])) $this->_accion = "Descargar";
        if (isset($_POST['bt_descartar'])) $this->_accion = "Descartar";
        if (isset($_POST['bt_descartar_conf'])) $this->_accion = "ConfirmarD";
        */
        $this->_accion = "Buscar";
        
        // Carga los archivos html para la vista
        $oCargarVista = new CargarVista();
        $oCargarVista->setCarga('pagina', '/includes/vista/pagina.html');
        $oCargarVista->setCarga('botones', '/includes/vista/botonesFooter.html');
        // Carga el menú de la vista según la categoría del usuario
        $oCargarVista->setCarga('menu', CargarMenu::selectMenu($oLoginVO->getCategoria()));
        // Carga el formulario
        $oCargarVista->setCarga('contenido', '/includes/vista/form.html');
        // carga las acciones (botones)
        $this->_aAcciones = array(
            "badge" => "/includes/vista/botonBadge.html",
            /*
            "agregar" => "/includes/vista/botonAgregar.html",
            "listar" => "/includes/vista/botonListar.html",
            "buscar" => "/includes/vista/botonBuscar.html",
            "descargar" => "/includes/vista/botonDescargar.html",
            "descartar" => "/includes/vista/botonDescartar.html"
            */
        );
        $oCargarVista->setCarga('aAcciones', $this->_aAcciones);
        // Ingresa los datos a representar en el html de la vista
        $oDatoVista = new DatoVista();
        $oDatoVista->setDato('{tituloPagina}', 'Articulos c/Condiciones');
        $oDatoVista->setDato('{usuario}', $oLoginVO->getAlias());
        $oDatoVista->setDato('{tituloBadge}', 'Artículos ');
        // carga href de boton descargar
        $oDatoVista->setDato('{hrefDescarga}', '/farma/modulos/rotulo/includes/descargaRotulosCondi.php');
        // Alertas
        
        // Instancia las Clases necesarios
        $oArticuloVO = new ArticuloVO();
        $oArticuloModelo = new ArticuloModelo();
        $oArticuloCondiVO = new ArticuloCondiVO();
        $oArticuloCondiModelo = new ArticuloCondiModelo();
        $oCondicionModelo = new CondicionModelo();
        $oCondicionVO = new CondicionVO();
        
        // Carga el contenido html y datos según la acción
        // Selector de acciones
        switch ($this->_accion){
            # ----> acción Iniciar
            case 'Iniciar':
                // carga el contenido html
                $oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
                // ingresa los datos a representar en las alertas de la vista
                $oDatoVista->setDato('{alertaInfo}',  'Seleccione alguna acción con los botones.<p>Debe confirmar en <b>Listar</b> los rótulos a descargar en PDF.');
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Artículos c/Condiciones - Selección de acciones');
                $oDatoVista->setDato('{informacion}', '<p>Puede seleccionar acciones para los artículos, ver botones.</p>
														<p>Debe confirmar en <b>Listar</b> los rótulos a descargar</p>
														<p>en PDF para imprimir.</p>');
                // arma la tabla de datos a representar
                $oArticuloCondiVO->setFechaHasta(date("Y-m-d"));
                
                $this->_items = $oArticuloCondiModelo->countArticulosCondiVig();
                $this->_cantidad = $oArticuloCondiModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                break;
                # ----> acción Listar
            case 'Listar':

                # ----> acción Agregar
            case 'Agregar':
 
                # ----> acción Editar
            case 'Editar':
                
                break;

                # ----> acción Descartar
            case 'Descartar':

                # ----> acción Buscar
            case 'Buscar':
                // carga el contenido html
                if (isset($_POST['codigoBarra'])){
                    if ($_POST['codigoBarra']==0){
                        $oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
                        $oDatoVista->setDato('{alertaInfo}',  'Ingrese el <b>Código de Barra</b> del Artículo.');
                    } else {
                        $oArticuloVO->setCodigoB($_POST['codigoBarra']);
                        $oArticuloModelo->findPorCodigoB($oArticuloVO);
                        if ($oArticuloModelo->getCantidad()>0){
                            $oCargarVista->setCarga('datos', '/modulos/articulo/vista/verDatosArticuloCondi.html');
                            $oDatoVista->setDato('{codigoBarra}', $oArticuloVO->getCodigoB());
                            $oDatoVista->setDato('{nombre}', $oArticuloVO->getNombre());
                            $oDatoVista->setDato('{presentacion}', $oArticuloVO->getPresentacion());
                            $oDatoVista->setDato('{precio}', number_format($oArticuloVO->getPrecio(), 2, ",", "."));
                            $oDatoVista->setDato('{fechaPrecio}', $oArticuloVO->getFechaPrecio());
                            $oArticuloCondiVO->setIdArticulo($oArticuloVO->getId());
                            $oArticuloCondiVO->setFechaHasta(date('Y-m-d'));
                            $items = $oArticuloCondiModelo->findAllIdArticulo($oArticuloCondiVO);
                            if ($oArticuloCondiModelo->getCantidad()>0){
                                $cont = 0;
                                foreach ($items as $item)
                                {
                                    $oCondicionVO->setId($item['id_condicion']);
                                    $oCondicionModelo->find($oCondicionVO);
                                    $this->_aPreciosCondi = CondicionCalculo::preciosCondi($oArticuloVO, $oCondicionVO);
                                    $this->_aArticulosCondi[$cont] = array (
                                        'nombre' => $oCondicionVO->getNombre(),
                                        'cantidad' => $oCondicionVO->getCantidadUn(),
                                        'importe' => $this->_aPreciosCondi['importeTotal'],
                                        'ahorro' => $this->_aPreciosCondi['ahorro'],
                                        'fecha' => $item['fecha_hasta']
                                    );
                                    $cont++;
                                }
                                //var_dump($this->_aArticulosCondi);
                                ArticuloCondiTabla::armaTabla($this->_aArticulosCondi, $oArticuloCondiModelo->getCantidad());
                                $oCargarVista->setCarga('tabla', '/modulos/articulo/tabla.html');
                            } else {
                                $oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
                                $oDatoVista->setDato('{alertaAdvertencia}',  'Artículo <b>SIN CONDICIÓN VIGENTE</b>!!!.');
                            }
                        } else {
                            $oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
                            $oDatoVista->setDato('{alertaPeligro}',  'El Artículo <b>NO EXISTE</b>, verifique!!!.');
                        }
                    }
                    
                }
                $oCargarVista->setCarga('opcion', '/modulos/articulo/vista/buscarArticuloConsultaCondi.html');
                
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Busca Artículos para consulta Condición');
                $oDatoVista->setDato('{informacion}', '<p>Busca artículos por el código de barra para consultar condición.</p>
													   <p>También puede seleccionar otras acciones, ver botones.</p>'
                    );
                // ingresa los datos a representar en las alertas de la vista
                
                // arma los datos a representar
                $oArticuloCondiVO->setFechaHasta(date('Y-m-d'));
                $this->_items = $oArticuloCondiModelo->countArticulosCondiVig();
                $this->_cantidad = $oArticuloCondiModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                // arma el select de datos de la tabla condiciones a representar
                break;
                # ----> acción Ver
            case 'Ver':
                
                break;
                # ----> acción por Defecto (ninguna acción seleccionada)
            default:
                
                break;
        }
        
        // instancio el motor de la vista y muestro la vista
        $oMotorVista = new MotorVista($oDatoVista->getAllDatos(), $oCargarVista->getAllCargas());
        $oMotorVista->mostrarVista();
        
    }
    
}
?>