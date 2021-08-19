<?php
/**
 * Archivo de la clase control del módulo pendiente.
 *
 * Archivo de la clase control del módulo pendiente.
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
 * Clase control del módulo pendiente.
 *
 * Clase control del módulo pendiente que permite realizar
 * operaciones sobre la tabla pendientes (CRUD y otras)
 * necesarias para la administración de los pendientes
 * según la categoría asignada al usuario.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class PendienteControl
{
    #Propiedades
    private $_html;
    private $_accion;
    private $_items;
    private $_item;
    private $_rubro;
    private $_aRubros = array();
    private $_proveedor;
    private $_prov;
    private $_preciosProveedores;
    private $_aProveedores = array();
    private $_aIniciales = array();
    private $_idProveedor;
    private $_proveedorMenorPrecio;
    private $_producto;
    private $_aProductos = array();
    private $_aProductosPorPrecios = array();
    private $_aPendientes = array();
    private $_aRadios = array();
    private $_existeProducto;
    private $_cantAsigProveedor;
    private $_cantAsignados;
    private $_cantEliminados;
    private $_cantDescartados;
    private $_cantidadAgregar;
    private $_cantidad;
    private $_date;
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
        Clase::define('MarcaModelo');
        Clase::define('ProductoModelo');
        Clase::define('PendienteModelo');
        Clase::define('ProveedorModelo');
        Clase::define('RubroModelo');
        Clase::define('ProveedorSelect');
        Clase::define('PendienteTabla');
        Clase::define('PendienteDatos');
        Clase::define('ArrayOrdenadoPor');
        Clase::define('CalcularPrecioProv');
        
        // Chequea login
        $oLoginControl = new LoginControl();
        $oLoginControl->chequearLogin($oLoginVO);
        $this->_accion = $accion;
        $this->accionControl($oLoginVO);
    }
    
    /**
     * Nos permite ejecutar las acciones del módulo
     * pendiente del sistema, de acuerdo a la categoría del
     * usuario.
     */
    private function accionControl($oLoginVO)
    {
// ARREGLAR LAS ACCIONES NECESARIAS        
        // Carga acciones del formulario
        if (isset($_POST['bt_agregar'])) $this->_accion = "Agregar";
        if (isset($_POST['bt_agregar_b'])) $this->_accion = "AgregarB";
        if (isset($_POST['bt_agregar_conf'])) $this->_accion = "ConfirmarA";
        if (isset($_POST['bt_listar'])) $this->_accion = "Listar";
        if (isset($_POST['bt_listar_conf'])) $this->_accion = "ConfirmarL";
        if (isset($_POST['bt_asignar'])) $this->_accion = "Asignar";
        if (isset($_POST['bt_asignar_l'])) $this->_accion = "AsignarL";
        if (isset($_POST['bt_asignar_conf'])) $this->_accion = "ConfirmarAsig";
        
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
            "asignar" => "/includes/vista/botonAsignar.html"//,
            //"listar" => "/includes/vista/botonListar.html"
        );
        $oCargarVista->setCarga('aAcciones', $this->_aAcciones);
        
        // Ingresa los datos a representar en el html de la vista
        $oDatoVista = new DatoVista();        
        $oDatoVista->setDato('{tituloPagina}', 'Pendientes');
        $oDatoVista->setDato('{usuario}', $oLoginVO->getAlias());
        $oDatoVista->setDato('{tituloBadge}', 'Pendientes ');

        // Alertas
        
        // Carga el contenido html y datos según la acción
        $oArticuloVO = new ArticuloVO();
        $oArticuloModelo = new ArticuloModelo();
        $oPendienteVO = new PendienteVO();
        $oPendienteModelo = new PendienteModelo();
        $oProductoVO = new ProductoVO();
        $oProductoModelo = new ProductoModelo();
        $oProveedorVO = new ProveedorVO();
        $oProveedorModelo = new ProveedorModelo();
        $oRubroVO = new RubroVO();
        $oRubroModelo = new RubroModelo();
        // Selector de acciones
        switch ($this->_accion){
            # ----> acción Iniciar
            case 'Iniciar':
                // carga el contenido html
                $oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
                // ingresa los datos a representar en las alertas de la vista
                $oDatoVista->setDato('{alertaInfo}',  'Seleccione alguna acción con los botones.');
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Pendientes - Selección de acciones');
                $oDatoVista->setDato('{informacion}', '<p>Puede seleccionar acciones para los pendientes, ver botones.</p>');
                // arma la tabla de datos a representar
                $this->_items = $oPendienteModelo->countPendientes();
                $this->_cantidad = $oPendienteModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                break;
                # ----> acción Listar
            case 'Listar':

                break;
            # ----> acción Confirmar Listar
            case "ConfirmarL":

                break;
                
            # ----> acción Agregar
            case 'Agregar':
                $oDatoVista->setDato('{tituloBadge}', 'Artículos ');
                // carga el contenido html
                $oCargarVista->setCarga('opcion', '/modulos/pendiente/vista/buscarOpcion.html');
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Agrega Artículo a Pendientes');
                $oDatoVista->setDato('{informacion}', '<p>Buscar los artículos según opciones elegidas para agregar a pendientes.</p>
													<p>La búsqueda se realiza por una sola opción, comenzando por la izquierda.</p>
													<p>También puede seleccionar otras acciones</p>
													<p>para los pendientes, ver botones.'
                    );
                // ingresa los datos a representar en las alertas de la vista
                
                // arma los datos a representar
                $this->_items = $oPendienteModelo->countPendientes();
                $this->_cantidad = $oPendienteModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                $this->_items = $oArticuloModelo->count();
                $this->_cantidad = $oArticuloModelo->getCantidad();
                $oDatoVista->setDato('{cantidadArticulos}', $this->_cantidad);
                break;
            # ---> acción Agregar buscar artículo
            case 'AgregarB':
                
                // busco los artículos segun las opciones elegidas para agregar pendiente
                if ($_POST['codigo'] > 0){ // Por codigo PLEX
                    $oArticuloVO->setCodigo($_POST['codigo']);
                    $oArticuloModelo->findPorCodigo($oArticuloVO);
                    $this->_cantidad = $oArticuloModelo->getCantidad();
                    if ($this->_cantidad == 0){ // No encuentra el artículo buscado por codigo PLEX
                        // carga el contenido html
                        $oCargarVista->setCarga('opcion', '/modulos/pendiente/vista/buscarOpcion.html');
                        $oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
                        $oDatoVista->setDato('{alertaAdvertencia}',  'No encontró artículo. Intente otra búsqueda.');
                    }else{ // Encontró el artículo buscado por codigo PLEX
                        // carga el contenido html
                        $oCargarVista->setCarga('datos', '/modulos/pendiente/vista/verDatos.html');
                        // ingresa los datos a representar en el Panel de la vista
                        $oDatoVista->setDato('{tituloPanel}', 'Ver Artículo buscado para agregar');
                        $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                        $oDatoVista->setDato('{informacion}', '<p>Muestra los datos de un artículo para agregar a pendientes.</p>
													<p>Seleccione alguna acción para el pendiente con botones</p>
													<p>u otra opción del menú.'
                            );
                        // carga los eventos (botones)
                        $this->_aEventos = array(
                            "agregarConf" => "/includes/vista/botonAgregarConf.html",
                        );
                        $oCargarVista->setCarga('aEventos', $this->_aEventos);
                        PendienteDatos::cargaDatos($oArticuloVO, $oDatoVista, $this->_accion);
                    }
                }else{
                    if($_POST['codigoBarra'] > 0){ // Por código de Barra
                        $oArticuloVO->setCodigoB($_POST['codigoBarra']);
                        $oArticuloModelo->findPorCodigoB($oArticuloVO);
                        $this->_cantidad = $oArticuloModelo->getCantidad();
                        if ($this->_cantidad == 0){ // No encontró artículo por código de barra
                            // carga el contenido html 
                            $oCargarVista->setCarga('opcion', '/modulos/pendiente/vista/buscarOpcion.html');
                            $oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
                            $oDatoVista->setDato('{alertaAdvertencia}',  'No encontró artículo. Intente otra búsqueda.');
                        }else{ // Encontró artículo por código de barra
                            // carga el contenido html
                            $oCargarVista->setCarga('datos', '/modulos/pendiente/vista/verDatos.html');
                            // ingresa los datos a representar en el Panel de la vista
                            $oDatoVista->setDato('{tituloPanel}', 'Ver Artículo buscado para agregar');
                            $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                            $oDatoVista->setDato('{informacion}', '<p>Muestra los datos de un artículo para agregar a pendientes.</p>
														<p>Seleccione alguna acción para el pendiente con botones</p>
														<p>u otra opción del menú.'
                                );
                            // carga los eventos (botones)
                            $this->_aEventos = array(
                                "agregarConf" => "/includes/vista/botonAgregarConf.html",
                            );
                            $oCargarVista->setCarga('aEventos', $this->_aEventos);
                            PendienteDatos::cargaDatos($oArticuloVO, $oDatoVista, $this->_accion);
                        }
                    } else{
                        if($_POST['nombre'] != " "){
                            $oArticuloVO->setNombre(trim($_POST['nombre']));
                            $this->_items = $oArticuloModelo->findAllPorNombre($oArticuloVO);
                            $this->_cantidad = $oArticuloModelo->getCantidad();
                            // carga el contenido html
                            
                            // ingresa los datos a representar en el panel de la vista
                            $oDatoVista->setDato('{tituloPanel}', 'Ver Artículo buscado para agregar');
                            $oDatoVista->setDato('{informacion}', '<p>Muestra los datos de un artículo para agregar a pendientes.</p>
													<p>Seleccione alguna acción para el pendiente con botones</p>
													<p>u otra opción del menú.'
                                );
                            if ($this->_cantidad == 0){
                                $oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
                                $oDatoVista->setDato('{alertaAdvertencia}',  'No encontró artículo. Intente otra búsqueda.');
                            }else{
                                // carga el contenido html
                                $oCargarVista->setCarga('datos', '/modulos/pendiente/vista/verDatos.html');
                                // ingresa los datos a representar en el Panel de la vista
                                $oDatoVista->setDato('{tituloPanel}', 'Ver Artículo buscado para agregar');
                                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                                $oDatoVista->setDato('{informacion}', '<p>Muestra los datos de un artículo para agregar a pendientes.</p>
														<p>Seleccione alguna acción para el pendiente con botones</p>
														<p>u otra opción del menú.'
                                    );
                                // carga los eventos (botones)
                                $this->_aEventos = array(
                                    "agregarConf" => "/includes/vista/botonAgregarConf.html",
                                );
       // hacer tabla para buscar artículo por nombre                         
                                //$oCargarVista->setCarga('aEventos', $this->_aEventos);
                                //PendienteDatos::cargaDatos($oArticuloVO, $oDatoVista, $this->_accion);
                            }
                        }else{
                            // carga el contenido html
                            $oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
                            $oCargarVista->setCarga('opcion', '/modulos/pendiente/vista/buscarOpcion.html');
                            // ingresa los datos a representar en el panel de la vista
                            $oDatoVista->setDato('{tituloPanel}', 'Agrega Artículo a Pendientes');
                            $oDatoVista->setDato('{informacion}', '<p>Buscar los artículos según opciones elegidas para agregar a pendientes.</p>
													<p>La búsqueda se realiza por una sola opción, comenzando por la izquierda.</p>
													<p>También puede seleccionar otras acciones</p>
													<p>para los pendientes, ver botones.'
                                );
                            // ingresa los datos a representar en las alertas de la vista
                            
                            // arma los datos a representar
                            $this->_items = $oPendienteModelo->countPendientes();
                            $this->_cantidad = $oPendienteModelo->getCantidad();
                            $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                            $this->_items = $oArticuloModelo->count();
                            $this->_cantidad = $oArticuloModelo->getCantidad();
                            $oDatoVista->setDato('{cantidadArticulos}', $this->_cantidad);
                            // ingresa los datos a representar en las alertas de la vista
                            $oDatoVista->setDato('{alertaAdvertencia}',  'Debe ingresar algún dato del artículo a buscar.');

                        }
                    }
                }

                break;
            # ---> acción Confirmar Agregar
            case 'ConfirmarA':
                // Recibe datos por POST
                //echo "Id: ".$_POST['id']."<br>";
                //echo "Cantidad: ".$_POST['cantidad']."<br>";
                $this->_cantidadAgregar = $_POST['cantidad'];
                
                // busco los artículos segun las opciones elegidas para agregar pendiente
                if ($this->_cantidadAgregar > 0){
                    $oArticuloVO->setId($_POST['id']);
                    $oArticuloModelo->find($oArticuloVO);
                    $this->_cantidad = $oArticuloModelo->getCantidad();
                    if ($this->_cantidad == 0){
                        // carga el contenido html
                        $oCargarVista->setCarga('opcion', '/modulos/pendiente/vista/buscarOpcion.html');
                        $oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
                        $oDatoVista->setDato('{alertaAdvertencia}',  'No encontró artículo. Intente otra búsqueda.');
                    }else{ // Encontro el artículo para agregar un pendiente
                        // carga el contenido html
                        $oCargarVista->setCarga('opcion', '/modulos/pendiente/vista/buscarOpcion.html');
                        // ingresa los datos a representar en el panel de la vista
                        $oDatoVista->setDato('{tituloPanel}', 'Agrega Artículo a Pendientes');
                        $oDatoVista->setDato('{informacion}', '<p>Buscar los artículos según opciones elegidas para agregar a pendientes.</p>
													<p>La búsqueda se realiza por una sola opción, comenzando por la izquierda.</p>
													<p>También puede seleccionar otras acciones</p>
													<p>para los pendientes, ver botones.'
                            );
                        // agrega el pendiente 
                        $oPendienteVO->setIdArticulo($oArticuloVO->getId());
                        $oPendienteModelo->findPorIdArticulo($oPendienteVO);
                        if ($oPendienteModelo->getCantidad() > 0){ // Existe el pendiente, sumo la cantidad
                            $this->_cantidadAgregar = $this->_cantidadAgregar + $oPendienteVO->getCantidad();
                            $oPendienteVO->setCantidad($this->_cantidadAgregar);
                            $oPendienteVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
                            $this->_date = date('Y-m-d H:i:s');
                            $oPendienteVO->setFechaAct($this->_date);
                            $oPendienteModelo->update($oPendienteVO);
                            if ($oPendienteModelo->getCantidad() == 0){ // No pudo actualizar el pendiente
                                // ingresa los datos a representar en las alertas de la vista
                                $oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
                                $oDatoVista->setDato('{alertaPeligro}',  'No pudo agregar la cantidad al pendiente existente.');
                            } else {
                                // ingresa los datos a representar en las alertas de la vista
                                $oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
                                $oDatoVista->setDato('{alertaSuceso}',  'Agregó la cantidad al pendiente existente con EXITO!!!.');
                            }
                        } else { // No existe pendiente agrego uno nuevo
                            $oPendienteVO->setIdArticulo($oArticuloVO->getId());
                            $oPendienteVO->setCodigo($oArticuloVO->getCodigo());
                            $oPendienteVO->setCodigoB($oArticuloVO->getCodigoB());
                            $oPendienteVO->setIdRubro($oArticuloVO->getIdRubro());
                            $oPendienteVO->setIdProveedor(0);
                            $oPendienteVO->setCantidad($this->_cantidadAgregar);
                            $oPendienteVO->setIdPedido(0);
                            $oPendienteVO->setEstado(1);
                            $oPendienteVO->setCantidadRec(0);
                            $oPendienteVO->setFechaRec(NULL);
                            $oPendienteVO->setComprobante(NULL);
                            $oPendienteVO->setComentario(NULL);
                            $oPendienteVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
                            $this->_date = date('Y-m-d H:i:s');
                            $oPendienteVO->setFechaAct($this->_date);
                            $oPendienteModelo->insert($oPendienteVO);
                            if ($oPendienteModelo->getCantidad() == 0){ // No pudo insertar el pendiente
                                // ingresa los datos a representar en las alertas de la vista
                                $oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
                                $oDatoVista->setDato('{alertaPeligro}',  'No pudo agregar el pendiente.');
                            } else {
                                // ingresa los datos a representar en las alertas de la vista
                                $oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
                                $oDatoVista->setDato('{alertaSuceso}',  'Agregó el pendiente con EXITO!!!.');
                            }
                       }
                   }
                }else{ // Cantidad igual a CERO 
                    // carga el contenido html
                    $oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
                    $oCargarVista->setCarga('opcion', '/modulos/pendiente/vista/buscarOpcion.html');
                    // ingresa los datos a representar en el panel de la vista
                    $oDatoVista->setDato('{tituloPanel}', 'Agrega Artículo a Pendientes');
                    $oDatoVista->setDato('{informacion}', '<p>Buscar los artículos según opciones elegidas para agregar a pendientes.</p>
													<p>La búsqueda se realiza por una sola opción, comenzando por la izquierda.</p>
													<p>También puede seleccionar otras acciones</p>
													<p>para los pendientes, ver botones.'
                        );
                    // arma los datos a representar

                    // ingresa los datos a representar en las alertas de la vista
                    $oDatoVista->setDato('{alertaAdvertencia}',  'Debe ingresar una cantidad a agregar mayor que CERO.');
                    
                }
                // arma los datos a representar
                $this->_items = $oPendienteModelo->countPendientes();
                $this->_cantidad = $oPendienteModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                $this->_items = $oArticuloModelo->count();
                $this->_cantidad = $oArticuloModelo->getCantidad();
                $oDatoVista->setDato('{cantidadArticulos}', $this->_cantidad);
                break;
            # ---> acción Confirmar Agregar (1)
            case 'ConfirmarA1':
                /*
                // agrega el pendiente del artículo confirmado para descargar
                $oArticuloVO->setId($_POST['id']);
                $oArticuloModelo->find($oArticuloVO);
                $rotulo = 3 ; //lo agrega para descarga directa en PDF
                $oArticuloVO->setRotulo($rotulo);
                $oArticuloModelo->update($oArticuloVO);
                // carga el contenido html
                $oCargarVista->setCarga('opcion', '/modulos/rotulo/vista/buscarOpcion.html');
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Agrega Rótulo para descarga');
                $oDatoVista->setDato('{informacion}', '<p>Buscar los artículos según opciones elegidas para agregar pendiente.</p>
													<p>La búsqueda se realiza por una sola opción, comenzando por la izquierda.</p>
													<p>También puede seleccionar otras acciones</p>
													<p>para los rotulos, ver botones.'
                    );
                // ingresa los datos a representar en las alertas de la vista
                $oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
                $oDatoVista->setDato('{alertaSuceso}',  'Agregó '.$oArticuloVO->getNombre().' con exito!!!.');
                // arma los datos a representar
                $this->_items = $oArticuloModelo->countRotulos();
                $this->_cantidad = $oArticuloModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                */
                break;
                # ----> acción Editar
            case 'Editar':
                
                break;
                # ----> acción Confirmar Editar
            case 'ConfirmarE':
                
                break;
            # ----> acción Asignar
            case 'Asignar':
                // carga el contenido html
                
                // ingresa los datos a representar en el panel de la vista
                $oCargarVista->setCarga('datos', '/modulos/pendiente/vista/asignarProveedor.html');
                $oDatoVista->setDato('{tituloPanel}', 'Asigna Pendientes - Selecciona Proveedor');
                $oDatoVista->setDato('{informacion}', '<p>Seleccione un proveedor para asignar pendientes.</p><p>También puede seleccionar otras acciones</p><p>para los pendientes, ver botones.');
                // carga los eventos (botones)
                $this->_aEventos = [
                    "asignarLConfirmar" => "/includes/vista/botonAsignarLConfirmar.html",
                    "borrar" => "/includes/vista/botonBorrar.html"
                ];
                $oCargarVista->setCarga('aEventos', $this->_aEventos);
                // ingresa los datos a representar en las alertas de la vista
                
                // arma los datos a representar
                $this->_items = $oPendienteModelo->countPendientes();
                $this->_cantidad = $oPendienteModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                
                $this->_items = $oProveedorModelo->findAll();
                $this->_cantidad = $oProveedorModelo->getCantidad();
                $this->_idProveedor = 0;
                $oDatoVista->setDato('{cantidadProveedor}', $this->_cantidad);
                ProveedorSelect::armaSelect($this->_cantidad, $this->_items, $this->_accion, $this->_idProveedor);
                $oCargarVista->setCarga('selectProveedor', '/modulos/pendiente/selectProveedor.html');
                
                $this->_aRubros = $oRubroModelo->findAll();
                foreach ($this->_aRubros as $rubro){
                    $oDatoVista->setDato('{Rubro'.$rubro['id'].'Id}', $rubro['id']);
                    $oDatoVista->setDato('{Rubro'.$rubro['id'].'Nombre}', $rubro['nombre']);
                }
                break;
                # ----> acción Asignar Listar
            case 'AsignarL':
                // recibe datos por POST
                $oProveedorVO->setId($_POST['proveedor']);
                if(isset($_POST['rubro'])){
                    $this->_aRubros = $_POST['rubro'];
                }    
                //echo "Consulta Rubros -> ".$consulta."<br>";
                //var_dump($this->_aRubros);
                
                // carga el contenido html
                
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Asigna Pendientes al Proveedor');
                $oDatoVista->setDato('{informacion}', '<p>Seleccione los pendientes para asignar al proveedor.</p><p>También puede seleccionar otras acciones</p><p>para los pendientes, ver botones.');
                // carga los eventos (botones)
                $this->_aEventos = [
                    "asignarConfirmar" => "/includes/vista/botonAsignarConfirmar.html",
                    "borrar" => "/includes/vista/botonBorrar.html"
                ];
                $oCargarVista->setCarga('aEventos', $this->_aEventos);
                // ingresa los datos a representar en las alertas de la vista
                
                // arma los datos a representar
                $oProveedorModelo->find($oProveedorVO);
                $this->_proveedor = $oProveedorVO->getRazonSocial();
                $this->_idProveedor = $oProveedorVO->getId();
                $oPendienteVO->setIdProveedor($oProveedorVO->getId());
                $oPendienteModelo->countPendientesProvAsignar($oPendienteVO);
                $this->_cantidad = $oPendienteModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                $this->_aProveedores = $oProveedorModelo->findAllProveedoresRef();
                foreach ($this->_aProveedores as $this->_prov){
                    $this->_aIniciales[$this->_prov['id']] = $this->_prov['inicial'];
                }
                $oPendienteVO->setIdProveedor($this->_idProveedor);
                // arma la tabla de datos a representar - findAllProvRubrosAsignar()
                $this->_items = $oPendienteModelo->findAllProvRubrosAsignar($oPendienteVO, $this->_aRubros);
                $this->_cantidad = $oPendienteModelo->getCantidad();
      
                
                // arma array para mandar a la tabla de pendiente
                foreach ($this->_items as $this->_item){
                    $oArticuloVO->setId($this->_item['id_articulo']);
                    $oArticuloModelo->find($oArticuloVO);
                    if ($oArticuloModelo->getCantidad() > 0){ // Existe el artículo
                        $this->_preciosProveedores = "* ";
                        $this->_existeProducto = 0;
                        if ($this->_item['codigo_b'] > 0){
                            //echo "Pend.ID: ".$this->_item['id']." Cod.Barra: ".$this->_item['codigo_b']." ProveedorId: ".$oProveedorVO->getId()."<br>";
                            $oProductoVO->setIdProveedor($oProveedorVO->getId());
                            $oProductoVO->setCodigoB($this->_item['codigo_b']);
                            $oProductoModelo->findPorCodigoBProveedor($oProductoVO);
                            $this->_existeProducto = $oProductoModelo->getCantidad();
                            $oProductoVO->setCodigoB($this->_item['codigo_b']);
                            $this->_aProductos = $oProductoModelo->findAllPorCodigoB($oProductoVO);
                            // Calcula precios segun las condiciones de los proveedores
                            $this->_aProductos = CalcularPrecioProv::calculaPrecios($this->_aProductos);
                            $this->_preciosProveedores = "* ";
                            $this->_proveedorMenorPrecio = 0;
                            if ($oProductoModelo->getCantidad() > 0){
                                $this->_aProductosPorPrecios = ArrayOrdenadoPor::ordenaArray($this->_aProductos, 'precio', SORT_ASC);
                                $cont = 0;
                                foreach ($this->_aProductosPorPrecios as $this->_producto){
                                    if ($cont == 0){
                                        if ($this->_producto['id_proveedor'] == $this->_idProveedor){
                                            $this->_proveedorMenorPrecio = 1;
                                        }
                                    }
                                    $this->_preciosProveedores .= $this->_aIniciales[$this->_producto['id_proveedor']]." $ ".$this->_producto['precio']." * "; 
                                    $cont++;
                                }
                                                 
                            } 
                        }   
                        $oRubroVO->setId($this->_item['id_rubro']);
                        $oRubroModelo->find($oRubroVO);
                        $this->_rubro = substr($oRubroVO->getNombre(), 0, 3);
                        $this->_aPendientes[] = array(
                                    'id' => $this->_item['id'],
                                    'codigo_b' => $this->_item['codigo_b'],
                                    'id_rubro' => $this->_item['id_rubro'],
                                    'rubro' => $this->_rubro,
                                    'nombre' => $oArticuloVO->getNombre(),
                                    'presentacion' => $oArticuloVO->getPresentacion(),
                                    'cantidad' => $this->_item['cantidad'],
                                    'id_proveedor' => $this->_item['id_proveedor'],
                                    'existe_producto' => $this->_existeProducto,
                                    'precios_proveedores' => $this->_preciosProveedores,
                                    'proveedor_menor_precio' => $this->_proveedorMenorPrecio
                        );
                    } else { // No existe el artículo paso a INACTIVO el pendiente
                        $oPendienteVO->setId($this->_item['id']);
                        $oPendienteModelo->find($oPendienteVO);
                        if ($oPendienteModelo->getCantidad()>0){
                            $oPendienteVO->setEstado(0);
                            $oPendienteModelo->update($oPendienteVO);
                        }
                    }
                } // Fin foreach arma array para tabla pendientes
                //var_dump($this->_aPendientes);
                $this->_items = ArrayOrdenadoPor::ordenaArray($this->_aPendientes, 'existe_producto', SORT_DESC, 'id_proveedor', SORT_DESC, 'id_rubro', SORT_ASC, 'nombre', SORT_ASC);     
                //var_dump($this->_items);
                
                PendienteTabla::armaTabla($this->_items, $this->_cantidad, $this->_accion, $this->_proveedor);
                $oCargarVista->setCarga('tabla', '/modulos/pendiente/tabla.html');
                
                break;
            # ----> acción Confirmar Asignar
            case 'ConfirmarAsig':
                // recibe datos por POST
                $oProveedorVO->setRazonSocial($_POST['proveedor']);
                $oProveedorModelo->findPorRazonSocial($oProveedorVO);
                
                // carga el contenido html
                $oCargarVista->setCarga('datos', '/modulos/pendiente/vista/asignarDatos.html');
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Pendientes Asignados');
                $oDatoVista->setDato('{informacion}', '<p>Totales de los pendientes asignados a proveedores.</p>
														<p>También puede seleccionar otras acciones</p>
														<p>para los pendientes, ver botones.');
                // recibe datos por POST y arma los array para actualizar tabla pendientes
                $aRadios = $_POST['pendientes'];
                
                while (list($key, $val) = each($aRadios)){
                    if ($val == "Asigna"){
                        $proveedor = $oProveedorVO->getId();
                        $aPendientes3[] = $key;
                    }
                    if ($val == "Elimina"){
                        $proveedorEli = 0; // pone 0 al idProveedor
                        $aPendientes2[] = $key;
                    }
                    if ($val == "Descarta"){
                        $aPendientes1[] = $key;
                    }
                }
                
                // actualiza los pendientes 
                // asigna pendientes a un proveedor
                $oPendienteModelo->updateProveedor($aPendientes3, $proveedor);
                $this->_cantAsignados = $oPendienteModelo->getCantidad();
                // elimina al proveedor de los pendientes
                $oPendienteModelo->updateProveedor($aPendientes2, $proveedorEli);
                $this->_cantEliminados = $oPendienteModelo->getCantidad();
                // descarta el pendiente, lo da de BAJA
                $estado = 0;
                $oPendienteModelo->updateEstado($aPendientes1, $estado);
                $this->_cantDescartados = $oPendienteModelo->getCantidad();

                // arma los datos a representar
                $oPendienteVO->setIdProveedor($oProveedorVO->getId());
                
                $oPendienteModelo->countPendientesProvAsignado($oPendienteVO);
                $this->_cantAsigProveedor = $oPendienteModelo->getCantidad();
                $oPendienteModelo->countPendientes();
                $this->_cantidad = $oPendienteModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                $oDatoVista->setDato('{proveedor}', $oProveedorVO->getRazonSocial());
                $oDatoVista->setDato('{cantAsigProveedor}', $this->_cantAsigProveedor);
                $oDatoVista->setDato('{cantAsignados}', $this->_cantAsignados);
                $oDatoVista->setDato('{cantEliminados}', $this->_cantEliminados);
                $oDatoVista->setDato('{cantDescartados}', $this->_cantDescartados);
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