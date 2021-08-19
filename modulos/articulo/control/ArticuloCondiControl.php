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
 * operaciones sobre la tabla artículos_condi (CRUD y otras)
 * necesarias para la administración de las condiciones de venta
 * de los artículos, según la categoría asignada al usuario.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.1
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 * 
 * @modificación    Armo vector para imprimir en orden alfabético los rótulos.
 * @date            03/12/2020
 * 
 */
class ArticuloCondiControl
{
    #Propiedades
    private $_html;
    private $_accion;
    private $_id;
    private $_idCondicion;
    private $_items;
    private $_item;
    private $_cont;
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
        Clase::define('ProductoModelo');
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
            "agregar" => "/includes/vista/botonAgregar.html",
            "listar" => "/includes/vista/botonListar.html",
            "buscar" => "/includes/vista/botonBuscar.html",
            "descargar" => "/includes/vista/botonDescargar.html",
            "descartar" => "/includes/vista/botonDescartar.html"
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
        $oProductoVO = new ProductoVO();
        $oProductoModelo = new ProductoModelo();
        
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
                // carga el contenido html
                
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Listado de Artículos c/Condiciones');
                $oDatoVista->setDato('{informacion}', '<p>Listado de todos los artículos con condiciones, puede confirmar la descarga</p>
                                                       <p>de rótulos en PDF para imprimir.</p>
                                                       <p>También puede seleccionar otras acciones para los artículos, ver botones.');
                // carga los eventos (botones)
                $this->_aEventos = [
                    "listarConfirmar" => "/includes/vista/botonListarConfirmar.html",
                    "borrar" => "/includes/vista/botonBorrar.html"
                ];
                $oCargarVista->setCarga('aEventos', $this->_aEventos);
                // ingresa los datos a representar en las alertas de la vista
                
                // arma los datos a representar
                $oArticuloCondiModelo->countRotulos();
                $this->_cantidad = $oArticuloCondiModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                // arma la tabla de datos a representar
                $this->_items = $oArticuloCondiModelo->findAllRotulos(); 
                
                // arma vector con datos de los artículos y de las condiciones
                $cont = 0;
                foreach ($this->_items as $item)
                {
                    $oArticuloVO->setId($item['id_articulo']);
                    $oArticuloModelo->find($oArticuloVO);
                    if ($oArticuloModelo->getCantidad()>0){
                        $oCondicionVO->setId($item['id_condicion']);
                        $oCondicionModelo->find($oCondicionVO);
                        if ($oCondicionModelo->getCantidad()>0){
                            $nombreCondi = $oCondicionVO->getNombre();
                        } else {
                            $nombreCondi = "SIN CONDICION VIG";
                        }
                        // Vector de datos
                        $this->_aRotulos[$cont] = array (
                            'id' => $item['id'],
                            'codBarra' => $oArticuloVO->getCodigoB(),
                            'nombre' => $oArticuloVO->getNombre(),
                            'presentacion' => $oArticuloVO->getPresentacion(),
                            'nombreCondi' => $nombreCondi,
                            'rotulo' => $item['rotulo']
                        );
                    }
                    $cont++; // Suma 1 al contador de registros del vector
                }
                RotuloCondiTabla::armaTabla($this->_aRotulos, $this->_cantidad, $this->_accion);
                $oCargarVista->setCarga('tabla', '/modulos/articulo/tabla.html');
                break;
            # ----> acción Confirmar Listar
            case "ConfirmarL":
                // carga el contenido html
                $oCargarVista->setCarga('datos', '/modulos/rotulo/vista/actualizarDatos.html');
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Listado de Artículos c/Condiciones');
                $oDatoVista->setDato('{informacion}', '<p>Listado de los artículos c/condiciones vigentes, muestra rótulos reservados o para descarga.</p>
														<p>También puede seleccionar otras accione para los artículos c/condiciones, ver botones.');
                // recibe datos por POST y arma los array para actualizar tabla articulos_condi
                $aRadios = $_POST['rotulos'];
                while (list($key, $val) = each($aRadios)){
                    if ($val==3){
                        $aRotulos3[] = $key;
                    }
                    if ($val==2){
                        $aRotulos2[] = $key;
                    }
                    if ($val==1){
                        $aRotulos1[] = $key;
                    }
                }
                // actualiza el estado de los rótulos en tabla articulos_condi
                $rotulo=3; // Seleccionado para descargar en PDF
                $oArticuloCondiModelo->updateRotulos($aRotulos3, $rotulo);
                $rotulo=2; // Rótulo reservado
                $oArticuloCondiModelo->updateRotulos($aRotulos2, $rotulo);
                $rotulo=1; // Artículo con rótulo
                $oArticuloCondiModelo->updateRotulos($aRotulos1, $rotulo);
                // arma los datos a representar
                $this->_items = $oArticuloCondiModelo->countRotulos();
                $this->_cantidad = $oArticuloCondiModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                $oDatoVista->setDato('{cantReservados}', $this->_cantidad);
                $this->_items = $oArticuloCondiModelo->countRotulosPDF();
                $this->_cantidad = $oArticuloCondiModelo->getCantidad();
                $oDatoVista->setDato('{cantDescargas}', $this->_cantidad);
                break;
            # ----> acción Agregar
            case 'Agregar':
                /*
                 * Busca la condición para agregar a los artículos
                 */
                // carga el contenido html
                $oCargarVista->setCarga('opcion', '/modulos/articulo/vista/buscarCondicion.html');
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Agrega Condición a los Artículos');
                $oDatoVista->setDato('{informacion}', '<p>Seleccionar la condición para agregar a los artículos.</p>
													   <p>También puede seleccionar otras acciones, ver botones.</p>'
                                     );
                // ingresa los datos a representar en las alertas de la vista
                
                // arma los datos a representar
                $this->_items = $oArticuloCondiModelo->countArticulosCondiVig();
                $this->_cantidad = $oArticuloCondiModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                // arma el select de datos de la tabla condiciones a representar
                $this->_items = $oCondicionModelo->findAll();
                $this->_cantidad = $oCondicionModelo->getCantidad();
                $oDatoVista->setDato('{cantidadCondicion}', $this->_cantidad);
                $this->_id = 0;
                // Si existe array de condición lo destruyo
                CondicionSelect::armaSelect($this->_cantidad, $this->_items, $this->_accion, $this->_id);
                $oCargarVista->setCarga('selectCondicion', '/modulos/articulo/selectCondicion.html');
                break; 
            # ---> acción Buscar Artículo para Agregar
            case 'BuscarArticulo':
                /*
                 * Busca el artículo para agregar la condición de venta
                 */
                
                // Carga datos recibidos por POST
                $this->_id = $_POST['condicion'];
                $oCondicionVO->setId($this->_id); 
                $oCondicionModelo->find($oCondicionVO);
                $oDatoVista->setDato('{idCondi}', $oCondicionVO->getId());
                $oDatoVista->setDato('{nombreCondi}', $oCondicionVO->getNombre());
                $oDatoVista->setDato('{fechaHasta}', $_POST['fechaHasta']);
                
                // carga el contenido html
                $oCargarVista->setCarga('opcion', '/modulos/articulo/vista/buscarArticuloCondi.html');
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Busca Artículos para agregar Condición');
                $oDatoVista->setDato('{informacion}', '<p>Ingrese algún dato del artículo para buscar.</p>
													   <p>También puede seleccionar otras acciones, ver botones.</p>'
                    );
                // ingresa los datos a representar en las alertas de la vista
                
                // arma los datos a representar
                $this->_items = $oArticuloCondiModelo->countArticulosCondiVig();
                $this->_cantidad = $oArticuloCondiModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                break; 
            # ---> acción Confirmar Agregar
            case 'ConfirmarA':
                //echo "Codigo Plex: ".$_POST['codigo']." - Codigo Barra: ".$_POST['codigoBarra']." - Código Prov: ".$_POST['codigoProv']."<br>";
                // Carga la condición
                $this->_id = $_POST['condicion'];
                $oCondicionVO->setId($this->_id);
                $oCondicionModelo->find($oCondicionVO);
                $oDatoVista->setDato('{idCondi}', $oCondicionVO->getId());
                $oDatoVista->setDato('{nombreCondi}', $oCondicionVO->getNombre());
                $oDatoVista->setDato('{fechaHasta}', $_POST['fechaHasta']);
                // ingresa los datos a representar en el Panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Ver Artículo para agregar Condición');
                $oDatoVista->setDato('{informacion}', '<p>Muestra los datos de un artículo para agregar una condición de venta.</p>
													<p>Seleccione alguna acción para el artículo con botones</p>
													<p>u otra opción del menú.</p>'
                    );
                // busco los artículos segun las opciones elegidas para agregar condición
                if ($_POST['codigo'] > 0){ // Por código PLEX
                    $oArticuloVO->setCodigo($_POST['codigo']);
                    $oArticuloModelo->findPorCodigo($oArticuloVO);
                    $this->_cantidad = $oArticuloModelo->getCantidad();
                    // ingresa los datos a representar en el Panel de la vista
                    $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                    if ($this->_cantidad == 0){
                        // carga el contenido html
                        $oCargarVista->setCarga('opcion', '/modulos/articulo/vista/buscarArticuloCondi.html');
                        $oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
                        $oDatoVista->setDato('{alertaPeligro}',  'No encontró artículo en PLEX. Intente otra búsqueda o ver p/AGREGAR.');
                    }else{
                        // revisa si imprime rótulo y avisa
                        if ($oArticuloVO->getRotulo() == 0){
                            $oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
                            $oDatoVista->setDato('{alertaInfo}',  'Artículo sin <b>ROTULO</b> para góndola.');
                        }
                        // carga el contenido html
                        $oCargarVista->setCarga('datos', '/modulos/articulo/vista/verDatosCondi.html');
                        // carga los eventos (botones)
                        $this->_aEventos = array(
                            "agregarConf" => "/includes/vista/botonAgregarConf.html",
                            "volver" => "/includes/vista/botonVolver.html"
                        );
                        $oCargarVista->setCarga('aEventos', $this->_aEventos);
                        ArticuloCondiDatos::cargaDatos($oArticuloVO, $oDatoVista, $oCondicionVO, $this->_accion);
                    }
                } else { // Fin por código PLEX. Busca por Código de Barra
                    if($_POST['codigoBarra'] > 0){
                        $oArticuloVO->setCodigoB($_POST['codigoBarra']);
                        $oArticuloModelo->findPorCodigoB($oArticuloVO);
                        $this->_cantidad = $oArticuloModelo->getCantidad();
                        // ingresa los datos a representar en el Panel de la vista
                        $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                        if ($this->_cantidad == 0){
                            // carga el contenido html
                            $oCargarVista->setCarga('opcion', '/modulos/articulo/vista/buscarArticuloCondi.html');
                            $oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
                            $oDatoVista->setDato('{alertaPeligro}',  'No encontró artículo en PLEX. Intente otra búsqueda o ver p/AGREGAR.');
                        }else{
                            // revisa si imprime rótulo y avisa
                            if ($oArticuloVO->getRotulo() == 0) {
                                $oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
                                $oDatoVista->setDato('{alertaInfo}',  'Artículo sin <b>ROTULO</b> para góndola.');
                            }
                            // carga el contenido html
                            $oCargarVista->setCarga('datos', '/modulos/articulo/vista/verDatosCondi.html');
                            // carga los eventos (botones)
                            $this->_aEventos = array(
                                "agregarConf" => "/includes/vista/botonAgregarConf.html",
                                "volver" => "/includes/vista/botonVolver.html"
                            );
                            $oCargarVista->setCarga('aEventos', $this->_aEventos);
                            ArticuloCondiDatos::cargaDatos($oArticuloVO, $oDatoVista, $oCondicionVO, $this->_accion);
                        }
                    } else { // fin por Código Barra. Busca por código del proveedor
// >>>>  CODIGO DE PROVEEDOR <<<<<
                        if($_POST['codigoProv'] != " "){
                            $codigoProv = strval($_POST['codigoProv']);
                            $oProductoVO->setCodigoP($codigoProv);
                            // busca todos los productos con igual codigo_p
                            $this->_items = $oProductoModelo->findAllPorCodigoP($oProductoVO);
                            $this->_cantidad = $oProductoModelo->getCantidad();
                            
                            if ($this->_cantidad == 0){ // no existe código de proveedor ingresado
                                // ingresa los datos a representar en el Panel de la vista
                                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                                // carga el contenido html
                                $oCargarVista->setCarga('opcion', '/modulos/articulo/vista/buscarArticuloCondi.html');
                                $oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
                                $oDatoVista->setDato('{alertaPeligro}',  '<b>No encontró el PRODUCTO</b>. Intente otra búsqueda o ver en LISTA proveedor.');
                            } else { // existe código de proveedor ingresado
                                // Si hay mas de un producto aviso con mensaje - puede repetir el proceso para más artículos
                                if ($this->_cantidad > 1) { // más de un código de barra
                                    // ingresa los datos a representar en el Panel de la vista
                                    $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                                    // carga el contenido html
                                    // $oCargarVista->setCarga('opcion', '/modulos/articulo/vista/buscarArticuloCondi.html');
                                    $alertaInfo = 'Producto con <b>'.$this->_cantidad.' códigos de barra !!!</b>';
                                    $this->_cont = 0;
                                    foreach ($this->_items as $this->_item) {
                                        $oArticuloVO->setCodigoB($this->_item['codigo_b']);
                                        $oArticuloModelo->findPorCodigoB($oArticuloVO);
                                        if ($oArticuloModelo->getCantidad() == 1) {
                                            $alertaInfo = $alertaInfo.'<br> --> Buscar p/Cod.Barra: <b>'.$oArticuloVO->getCodigoB().'</b>';
                                            $this->_cont++;
                                        }
                                    }
                                    // Ver desde acá si hay un solo artículo de varios códigos de barra
                                    if ($this->_cont == 1) {
                                        // Vuelve a consultar artículo para cargar datos de cod. barra
                                        $oArticuloModelo->find($oArticuloVO);
                                        $this->_cantidad = $oArticuloModelo->getCantidad();
                                        $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                                        // revisa si imprime rótulo y avisa
                                        if ($oArticuloVO->getRotulo() == 0) {
                                            $oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
                                            $oDatoVista->setDato('{alertaInfo}',  'Artículo sin <b>ROTULO</b> para góndola.');
                                        }
                                        // carga el contenido html
                                        $oCargarVista->setCarga('datos', '/modulos/articulo/vista/verDatosCondi.html');
                                        // carga los eventos (botones)
                                        $this->_aEventos = array(
                                            "agregarConf" => "/includes/vista/botonAgregarConf.html",
                                            "volver" => "/includes/vista/botonVolver.html"
                                        );
                                        $oCargarVista->setCarga('aEventos', $this->_aEventos);
                                        ArticuloCondiDatos::cargaDatos($oArticuloVO, $oDatoVista, $oCondicionVO, $this->_accion);
                                    } elseif($this->_cont > 1) {
                                        $oCargarVista->setCarga('opcion', '/modulos/articulo/vista/buscarArticuloCondi.html');
                                        $oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
                                        $oDatoVista->setDato('{alertaInfo}',  $alertaInfo);
                                        $oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
                                        $oDatoVista->setDato('{alertaSuceso}',  '<b>Encontró '.$this->_cont.' artículos en PLEX</b>.');
                                    } else {
                                        $oCargarVista->setCarga('opcion', '/modulos/articulo/vista/buscarArticuloCondi.html');
                                        $oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
                                        $oDatoVista->setDato('{alertaPeligro}',  '<b>No encontró artículos en PLEX</b>.');
                                    }
                                    
                                } else { // un código de barra
                                    foreach ($this->_items as $this->_item) {
                                        $oArticuloVO->setCodigoB($this->_item['codigo_b']);
                                    }
                                    $oArticuloModelo->findPorCodigoB($oArticuloVO);
                                    $this->_cantidad = $oArticuloModelo->getCantidad();
                                    // ingresa los datos a representar en el Panel de la vista
                                    $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                                    if ($this->_cantidad == 0){
                                        // carga el contenido html
                                        $oCargarVista->setCarga('opcion', '/modulos/articulo/vista/buscarArticuloCondi.html');
                                        $oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
                                        $oDatoVista->setDato('{alertaPeligro}',  '<b>No encontró artículo en PLEX</b>. Intente otra búsqueda o ver p/AGREGAR.');
                                    }else{
                                        // revisa si imprime rótulo y avisa
                                        if ($oArticuloVO->getRotulo() == 0){
                                            $oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
                                            $oDatoVista->setDato('{alertaInfo}',  'Artículo sin <b>ROTULO</b> para góndola.');
                                        }
                                        // carga el contenido html
                                        $oCargarVista->setCarga('datos', '/modulos/articulo/vista/verDatosCondi.html');
                                        // carga los eventos (botones)
                                        $this->_aEventos = array(
                                            "agregarConf" => "/includes/vista/botonAgregarConf.html",
                                            "volver" => "/includes/vista/botonVolver.html"
                                        );
                                        $oCargarVista->setCarga('aEventos', $this->_aEventos);
                                        ArticuloCondiDatos::cargaDatos($oArticuloVO, $oDatoVista, $oCondicionVO, $this->_accion);
                                    }
                                }        
                            } // Fin tiene código de proveedor
                        } // Fin por Código de Proveedor
                    }
                } // Fin busqueda de artículo
                // Si existe el artículo, busco si tiene condiciones vigentes
                if ($oArticuloModelo->getCantidad()>0) {
                    $this->_idCondicion = $oCondicionVO->getId();
                    $oArticuloCondiVO->setIdArticulo($oArticuloVO->getId());
                    $oArticuloCondiVO->setFechaHasta(date('Y-m-d'));
                    $this->_aArticulosCondi = $oArticuloCondiModelo->findAllIdArticulo($oArticuloCondiVO);
                    // Si existen condiciones para el artículo
                    if ($oArticuloCondiModelo->getCantidad()>0) {
                        $cont = 0;
                        foreach ($this->_aArticulosCondi as $dato) {
                            $oCondicionVO->setId($dato['id_condicion']);
                            $oCondicionModelo->find($oCondicionVO);
                             $this->_aCondicion[$cont] = array(
                                 "idCondicion" => $oCondicionVO->getId(),
                                 "nombre" => $oCondicionVO->getNombre(),
                                 "fechaHasta" => $dato['fecha_hasta']
                              );
                             $cont++;
                        }
                        $oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
                        $oDatoVista->setDato('{alertaAdvertencia}',  'Artículo con <b>CONDICIÓN VIGENTE</b>. PRECAUCIÓN al agregar!!!');
                        ArticuloCondiExisTabla::armaTabla($this->_aCondicion, $this->_idCondicion, $oArticuloCondiModelo->getCantidad());
                        $oCargarVista->setCarga('tabla', '/modulos/articulo/tabla.html');
                    }
                }
                
                break;
            # ---> acción Confirmar Agregar (1)
            case 'ConfirmarA1':
                // agrega la condición del artículo y confirma para descargar rótulo
                $rotulo=1;
                if (isset($_POST['rotulo'])) $rotulo = $_POST['rotulo'];
                $oArticuloCondiVO->setIdArticulo($_POST['id']);
                $oArticuloCondiVO->setIdCondicion($_POST['condicion']);
                $oArticuloCondiVO->setFechaHasta($_POST['fechaHasta']);
                $oArticuloCondiVO->setRotulo($rotulo); //agrega si descarga rotulo en PDF
                $oArticuloCondiVO->setEstado(1);
                $oArticuloCondiVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
                $this->_date = date('Y-m-d H:i:s');
                $oArticuloCondiVO->setFechaAct($this->_date);
                $oArticuloCondiModelo->insert($oArticuloCondiVO);
 
                // carga el contenido html
                $this->_id = $_POST['condicion'];
                $oCondicionVO->setId($this->_id);
                $oCondicionModelo->find($oCondicionVO);
                $oDatoVista->setDato('{idCondi}', $oCondicionVO->getId());
                $oDatoVista->setDato('{nombreCondi}', $oCondicionVO->getNombre());
                $oDatoVista->setDato('{fechaHasta}', $_POST['fechaHasta']);
                $oCargarVista->setCarga('opcion', '/modulos/articulo/vista/buscarArticuloCondi.html');

                // ingresa los datos a representar en el Panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Ver Artículo para agregar Condición');
                
                $oDatoVista->setDato('{informacion}', '<p>Muestra los datos de un artículo para agregar una condición de venta.</p>
													<p>Seleccione alguna acción para el artículo con botones</p>
													<p>u otra opción del menú.</p>'
                    );
                // ingresa los datos a representar en las alertas de la vista
                if ($oArticuloCondiModelo->getCantidad() > 0){
                    $oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
                    $oDatoVista->setDato('{alertaSuceso}',  'Agregó condición a '.$_POST['nombre'].' con exito!!!.');
                } else {
                    $oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
                    $oDatoVista->setDato('{alertaPeligro}',  'NO agregó condición a '.$_POST['nombre'].', verificar.');
                }
               
                // arma los datos a representar
                $this->_items = $oArticuloCondiModelo->countArticulosCondiVig();
                $this->_cantidad = $oArticuloCondiModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                break;
            # ----> acción Editar
            case 'Editar':
                
                break;
            # ----> acción Confirmar Editar
            case 'ConfirmarE':
                
                break;
            # ----> acción Descartar
            case 'Descartar':
                // carga el contenido html
                
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Descarta Rotulos');
                $oDatoVista->setDato('{informacion}', '<p>Listado de todos los <b>rótulos a descartar</b>.</p>
                                                       <p>También puede seleccionar otras acciones para los artículos con condiciones, ver botones.');
                // carga los eventos (botones)
                $this->_aEventos = [
                    "descartaConfirmar" => "/includes/vista/botonDescartarConf.html"
                ];
                $oCargarVista->setCarga('aEventos', $this->_aEventos);
                // arma los datos a representar
                $this->_items = $oArticuloCondiModelo->countRotulosPDF();
                $this->_cantidad = $oArticuloCondiModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                // ingresa los datos a representar en las alertas de la vista
                if ($this->_cantidad > 0){
                    $oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
                    $oDatoVista->setDato('{alertaPeligro}',  'Va a descartar todos los rótulos. <b>Precaución!!!</b>.');
                } else {
                    $oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
                    $oDatoVista->setDato('{alertaAdvertencia}',  'No hay rótulos para descartar.');
                }
                // arma la tabla de datos a representar
                $this->_items = $oArticuloCondiModelo->findAllRotulosPDF();
                // arma vector con datos de los artículos y de las condiciones
                $cont = 0;
                foreach ($this->_items as $item)
                {
                    $oArticuloVO->setId($item['id_articulo']);
                    $oArticuloModelo->find($oArticuloVO);
                    if ($oArticuloModelo->getCantidad()>0){
                        $oCondicionVO->setId($item['id_condicion']);
                        $oCondicionModelo->find($oCondicionVO);
                        if ($oCondicionModelo->getCantidad()>0){
                            $nombreCondi = $oCondicionVO->getNombre();
                        } else {
                            $nombreCondi = "SIN CONDICION VIG";
                        }
                        // Vector de datos
                        $this->_aRotulos[$cont] = array (
                            'id' => $item['id'],
                            'codBarra' => $oArticuloVO->getCodigoB(),
                            'nombre' => $oArticuloVO->getNombre(),
                            'presentacion' => $oArticuloVO->getPresentacion(),
                            'nombreCondi' => $nombreCondi,
                            'rotulo' => $item['rotulo']
                        );
                    }
                    $cont++; // Suma 1 al contador de registros del vector
                }
                RotuloCondiTabla::armaTabla($this->_aRotulos, $this->_cantidad, $this->_accion);
                $oCargarVista->setCarga('tabla', '/modulos/articulo/tabla.html');
                break;
            # ----> acción Confirmar Descartar
            case 'ConfirmarD':
                $cantDescartados=0;
                
                // carga el contenido html
                $oCargarVista->setCarga('datos', '/modulos/rotulo/vista/descartarDatos.html');
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Rotulos Descartados');
                $oDatoVista->setDato('{informacion}', '<p>Informa total de los rótulos descartados.</p>
														<p>También puede seleccionar otras acciones</p>
														<p>para los rótulos, ver botones.');
                // ingresa los datos a representar en las alertas de la vista
                $oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
                $oDatoVista->setDato('{alertaSuceso}',  'Descartó los rótulos con exito!!!.');
                // recibe datos por POST y arma los array para actualizar tabla articulos
                $aRotulos = $_POST['rotulos'];
                while (list($key, $val) = each($aRotulos)){
                    $aRotulos1[] = $key;
                    $cantDescartados++;
                }
                // actualiza el estado de los rótulos
                $rotulo=1;
                $oArticuloCondiModelo->updateRotulos($aRotulos1, $rotulo);
                // arma los datos a representar
                $this->_items = $oArticuloCondiModelo->countRotulosPDF();
                $this->_cantidad = $oArticuloModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                $oDatoVista->setDato('{cantDescartados}', $cantDescartados);
                break;
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