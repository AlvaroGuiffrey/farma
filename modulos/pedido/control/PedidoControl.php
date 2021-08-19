<?php
/**
 * Archivo de la clase control del módulo pedido.
 *
 * Archivo de la clase control del módulo pedido.
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
 * Clase control del módulo pedido.
 *
 * Clase control del módulo pedido que permite realizar
 * operaciones sobre la tabla pedidos y pendientes (CRUD y otras)
 * necesarias para la administración de los pedidos
 * según la categoría asignada al usuario.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class PedidoControl
{
    #Propiedades
    private $_html;
    private $_accion;
    private $_items;
    private $_item;
    private $_idPedido;
    private $_canal;
    private $_fechaDesde;
    private $_fechaHasta;
    private $_rubro;
    private $_proveedor;
    private $_prov;
    private $_precio;
    private $_preciosProveedores;
    private $_aProveedores = array();
    private $_aIniciales = array();
    private $_idProveedor;
    private $_proveedorMenorPrecio;
    private $_producto;
    private $_nombre;
    private $_presentacion;
    private $_codigoP;
    private $_aProductos = array();
    private $_aProductosPorPrecios = array();
    private $_aPedidos = array();
    private $_aRadios = array();
    private $_aCantidad = array();
    private $_aCantidadVentas = array();
    private $_cantidadVentas;
    private $_estado;
    private $_existeProducto;
    private $_cantProveedor;
    private $_cantEliminados;
    private $_cantDescartados;
    private $_cantPedidos;
    private $_cantUnidades;
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
        Clase::define('ProductoModelo');
        Clase::define('PedidoModelo');
        Clase::define('PendienteModelo');
        Clase::define('ProveedorModelo');
        Clase::define('RubroModelo');
        Clase::define('ProveedorSelect');
        Clase::define('PedidoTabla');
        Clase::define('PedidoRegTabla');
        Clase::define('PendienteTabla');
        Clase::define('ArrayOrdenadoPor');
        Clase::define('CalcularPrecioProv');
        Clase::define('CalcularProductosVendidos');
        Clase::define('DataBasePlex');
        
        // Chequea login
        $oLoginControl = new LoginControl();
        $oLoginControl->chequearLogin($oLoginVO);
        $this->_accion = $accion;
        $this->accionControl($oLoginVO);
    }
    
    /**
     * Nos permite ejecutar las acciones del módulo
     * pedido del sistema, de acuerdo a la categoría del
     * usuario.
     */
    private function accionControl($oLoginVO)
    {
        
        // Carga acciones del formulario
        if (isset($_POST['bt_listar'])) $this->_accion = "Listar";
        if (isset($_POST['bt_listar_conf'])) $this->_accion = "ConfirmarL";
        if (isset($_POST['bt_actualizar_listado'])) $this->_accion = "ConfirmarL";
        if (isset($_POST['bt_pedir'])) $this->_accion = "Pedir";
        if (isset($_POST['bt_pedir_l'])) $this->_accion = "PedirL";
        if (isset($_POST['bt_pedir_conf'])) $this->_accion = "ConfirmarP";
        
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
            "listar" => "/includes/vista/botonListar.html",
            "pedir" => "/modulos/pedido/vista/botonPedir.html"
        );
        $oCargarVista->setCarga('aAcciones', $this->_aAcciones);
        
        // Ingresa los datos a representar en el html de la vista
        $oDatoVista = new DatoVista();
        $oDatoVista->setDato('{tituloPagina}', 'Pedidos');
        $oDatoVista->setDato('{usuario}', $oLoginVO->getAlias());
        $oDatoVista->setDato('{tituloBadge}', 'Pedidos ');
        
        // Alertas
        
        // Carga el contenido html y datos según la acción
        $oArticuloVO = new ArticuloVO();
        $oArticuloModelo = new ArticuloModelo();
        $oPedidoVO = new PedidoVO();
        $oPedidoModelo = new PedidoModelo();
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
                $oDatoVista->setDato('{tituloPanel}', 'Pedidos - Selección de acciones');
                $oDatoVista->setDato('{informacion}', '<p>Puede seleccionar acciones para los pedidos, ver botones.</p>');
                // arma la tabla de datos a representar
                $this->_items = $oPedidoModelo->countPedidos();
                $this->_cantidad = $oPedidoModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                break;
            # ----> acción Listar
            case 'Listar':
                // carga el contenido html
                
                // ingresa los datos a representar en el panel de la vista
                $oCargarVista->setCarga('datos', '/modulos/pedido/vista/listarOpcion.html');
                $oDatoVista->setDato('{tituloPanel}', 'Listado de Pedidos');
                $oDatoVista->setDato('{informacion}', '<p>Listado de los pedidos realizados a un proveedor en un período.</p><p>También puede seleccionar otras acciones</p><p>para los pedidos, ver botones.');
                // carga los eventos (botones)
                $this->_aEventos = [
                    "listarConfirmar" => "/includes/vista/botonListarConfirmar.html",
                    "borrar" => "/includes/vista/botonBorrar.html"
                ];
                $oCargarVista->setCarga('aEventos', $this->_aEventos);
                // ingresa los datos a representar en las alertas de la vista
                
                // arma los datos a representar
                $this->_items = $oPedidoModelo->countPedidos();
                $this->_cantidad = $oPedidoModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                $this->_items = $oProveedorModelo->findAll();
                $this->_cantidad = $oProveedorModelo->getCantidad();
                $this->_idProveedor = 0;
                $oDatoVista->setDato('{cantidadProveedor}', $this->_cantidad);
                ProveedorSelect::armaSelect($this->_cantidad, $this->_items, $this->_accion, $this->_idProveedor);
                $oCargarVista->setCarga('selectProveedor', '/modulos/pedido/selectProveedor.html');
                break;
            # ----> acción Confirmar Listar
            case "ConfirmarL":
                $this->_idProveedor = trim($_POST['proveedor']);
                $this->_fechaDesde = trim($_POST['fechaDesde']);
                $this->_fechaHasta = trim($_POST['fechaHasta']);
   
                // carga el contenido html
                
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Listado de Pedidos');
                $oDatoVista->setDato('{informacion}', '<p>Listado de todos los pedidos según opciones elegidas.</p>
												<p>También puede seleccionar otras acciones</p>
												<p>para los pedidos, ver botones.'
                    );
                // ingresa los datos a representar en las alertas de la vista
                
                // consulta tablas de la DB para titular la tabla a representar
               if ($this->_idProveedor != 0){
                    $oProveedorVO->setId($this->_idProveedor);
                    $oProveedorModelo->find($oProveedorVO);
                    $this->_proveedor = $oProveedorVO->getRazonSocial();
                } else {
                    $this->_proveedor = 'TODOS';
                    
                }

                // arma la tabla de datos a representar
           
                // realiza la consulta segun las opciones elegidas
                $this->_items = $oPedidoModelo->findAllOpcionListado($this->_idProveedor,$this->_fechaDesde,$this->_fechaHasta);

                foreach ($this->_items as $this->_item){
                    $oProveedorVO->setId($this->_item['id_proveedor']);
                    $oProveedorModelo->find($oProveedorVO);
                    $this->_aPedidos[] = array(
                        'id' => $this->_item['id'],
                        'id_proveedor' => $this->_item['id_proveedor'],
                        'proveedor' => $oProveedorVO->getRazonSocial(),
                        'fecha' => $this->_item['fecha'],
                        'canal' => $this->_item['canal'],
                        'estado' => $this->_item['estado'],
                        'fecha_rec' => $this->_item['fecha_rec']
                    );
                }
                // ordena el array
                $this->_items = ArrayOrdenadoPor::ordenaArray($this->_aPedidos, 'id', SORT_ASC, 'fecha', SORT_ASC);
                
                $this->_cantidad = $oPedidoModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                PedidoRegTabla::armaTabla($this->_cantidad, $this->_items, $this->_accion, $this->_idProveedor, $this->_proveedor, $this->_fechaDesde, $this->_fechaHasta);
                $oCargarVista->setCarga('tabla', '/modulos/pedido/tabla.html');
                break;
            # ----> acción Agregar
            case 'Agregar':
          
                break;
                
            # ---> acción Confirmar Agregar
            case 'ConfirmarA':
          
                break;
            # ---> acción Confirmar Agregar (1)
            case 'ConfirmarA1':
           
                break;
            # ----> acción Editar
            case 'Editar':
                
                break;
            # ----> acción Confirmar Editar
            case 'ConfirmarE':
                
                break;
            # ----> acción Pedir
            case 'Pedir':
                // carga el contenido html
                
                // ingresa los datos a representar en el panel de la vista
                $oCargarVista->setCarga('datos', '/modulos/pedido/vista/selectProveedor.html');
                $oDatoVista->setDato('{tituloPanel}', 'Pedidos - Selecciona Proveedor');
                $oDatoVista->setDato('{informacion}', '<p>Seleccione un proveedor para realizar el pedido.</p><p>También puede seleccionar otras acciones</p><p>para los pedidos, ver botones.');
                // carga los eventos (botones)
                $this->_aEventos = [
                    "pedirL" => "/modulos/pedido/vista/botonPedirLConfirmar.html",
                    "borrar" => "/includes/vista/botonBorrar.html"
                ];
                $oCargarVista->setCarga('aEventos', $this->_aEventos);
                // ingresa los datos a representar en las alertas de la vista
                
                // arma los datos a representar
                $this->_items = $oPedidoModelo->countPedidos();
                $this->_cantidad = $oPedidoModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                $this->_items = $oProveedorModelo->findAll();
                $this->_cantidad = $oProveedorModelo->getCantidad();
                $this->_idProveedor = 0;
                $oDatoVista->setDato('{cantidadProveedor}', $this->_cantidad);
                ProveedorSelect::armaSelect($this->_cantidad, $this->_items, $this->_accion, $this->_idProveedor);
                $oCargarVista->setCarga('selectProveedor', '/modulos/pedido/selectProveedor.html');
                break;
            # ----> acción Pedir Listar
            case 'PedirL':
                // recibe datos por POST
                $oProveedorVO->setId($_POST['proveedor']);
                $this->_canal = $_POST['canal'];
                //echo $this->_canal;
                // carga el contenido html
                
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Pedido al Proveedor');
                $oDatoVista->setDato('{informacion}', '<p>Seleccione los productos del pedido para el proveedor.</p><p>También puede seleccionar otras acciones</p><p>para los pedidos, ver botones.');
                // carga los eventos (botones)
                $this->_aEventos = [
                    "pedirConfirmar" => "/modulos/pedido/vista/botonPedirConfirmar.html",
                    "borrar" => "/includes/vista/botonBorrar.html"
                ];
                $oCargarVista->setCarga('aEventos', $this->_aEventos);
                // ingresa los datos a representar en las alertas de la vista
                
                // arma los datos a representar
                $oProveedorModelo->find($oProveedorVO);
                $this->_proveedor = $oProveedorVO->getRazonSocial();
                $this->_idProveedor = $oProveedorVO->getId();
                $oPedidoVO->setIdProveedor($oProveedorVO->getId());
                $oPedidoModelo->countPedidosProveedor($oPedidoVO);
                $this->_cantidad = $oPedidoModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                                
                // arma proveedores para precios
                $this->_aProveedores = $oProveedorModelo->findAllProveedoresRef();
                foreach ($this->_aProveedores as $this->_prov){
                    $this->_aIniciales[$this->_prov['id']] = $this->_prov['inicial'];
                }
                // arma la tabla de datos a representar
                $oPedidoVO->setIdProveedor($this->_idProveedor);
                $this->_items = $oPendienteModelo->findAllProvPedir($oPedidoVO);
                $this->_cantPedidos = $oPendienteModelo->getCantidad();
                //echo "MUESTRA PENDIENTES -> <br>";
                //var_dump($this->_items);
                //echo "----------- <br>";
                // arma array para mandar a la tabla de pedido
                foreach ($this->_items as $this->_item){ // arma array tabla pedidos
                    $oArticuloVO->setId($this->_item['id_articulo']);
                    $oArticuloModelo->find($oArticuloVO);
                    $this->_preciosProveedores = "* ";
                    $this->_existeProducto = $this->_precio = 0;
                    if ($this->_item['codigo_b'] > 0){ // Si tiene codigo de barra
                        //echo "Pend.ID: ".$this->_item['id']." Cod.Barra: ".$this->_item['codigo_b']." ProveedorId: ".$oProveedorVO->getId()."<br>";
                        $oProductoVO->setIdProveedor($oProveedorVO->getId());
                        $oProductoVO->setCodigoB($this->_item['codigo_b']);
                        $oProductoModelo->findPorCodigoBProveedor($oProductoVO);
                        $this->_existeProducto = $oProductoModelo->getCantidad();
                        // pone nombre del producto o del artículo si el anterior no existe para proveedor
                        if ($this->_existeProducto > 0){
                            $this->_nombre = $oProductoVO->getNombre();
                            $this->_codigoP = $oProductoVO->getCodigoP();
                            $this->_presentacion = "";
                            $this->_precio = CalcularPrecioProv::calculaUnPrecio($oProductoVO->getIdProveedor(), $oProductoVO->getPrecio());
                        } else {
                            $this->_nombre = $oArticuloVO->getNombre();
                            $this->_codigoP = "Sin código";
                            $this->_presentacion = $oArticuloVO->getPresentacion();
                            $this->_precio = 0;
                        }
                        // precios de todos los proveedores                      
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
                            
                        } // fin si hay productos para precios
                    } // Fin si tiene codigo de barra
// Ver desde acá las modificaciones                 
                    // Calcula ventas del producto hasta 6 meses antes
                    $cont = $total = 0;
                    $this->_cantidadVentas = "* ";
                    /* Anulo consulta de productos vendidos
                    $this->_aCantidadVentas = CalcularProductosVendidos::calculaVentas($oArticuloVO->getCodigo());                 
                    //var_dump($this->_aCantidadVentas);
                    foreach ($this->_aCantidadVentas as $venta){
                        switch ($cont){
                            case 0:
                                $this->_cantidadVentas .= " (7d -> <b>".$venta[0]."</b>) * ";
                                break;
                            case 1:
                                $this->_cantidadVentas .= " (8-14d -> <b>".$venta[0]."</b>) * ";
                                break;
                            case 2:
                                $this->_cantidadVentas .= " (15-30d -> <b>".$venta[0]."</b>) * ";
                                break;
                            case 3:
                                $this->_cantidadVentas .= " (31-90d -> <b>".$venta[0]."</b>) * ";
                                break;
                            case 4:
                                $this->_cantidadVentas .= " (91-180d -> <b>".$venta[0]."</b>) * ";
                                break;
                        }  
                        $cont++;
                        $total = $total + $venta[0];
                    }
                    */
                    $this->_cantidadVentas .= "<b><ins>Total -> ".$total."</ins></b> * ";
                    // Busca rubro
                    $oRubroVO->setId($this->_item['id_rubro']);
                    $oRubroModelo->find($oRubroVO);
                    $this->_rubro = $oRubroVO->getId();
                    $this->_aPedidos[] = array(
                        'id' => $this->_item['id'],
                        'codigo_b' => $this->_item['codigo_b'],
                        'codigo_p' => $this->_codigoP,
                        'rubro' => $this->_rubro,
                        'nombre' => $this->_nombre,
                        'presentacion' => $this->_presentacion,
                        'cantidad' => $this->_item['cantidad'],
                        'cantidad_ventas' => $this->_cantidadVentas,
                        'precio' => $this->_precio,
                        'id_proveedor' => $this->_item['id_proveedor'],
                        'existe_producto' => $this->_existeProducto,
                        'precios_proveedores' => $this->_preciosProveedores,
                        'proveedor_menor_precio' => $this->_proveedorMenorPrecio
                    );
                } // Cierra foreach arma array tabla pedidos
                // ordena el array
                $this->_items = ArrayOrdenadoPor::ordenaArray($this->_aPedidos, 'rubro', SORT_ASC, 'nombre', SORT_ASC);
// Voy acá error pedido
                PedidoTabla::armaTabla($this->_items, $this->_cantPedidos, $this->_accion, $this->_proveedor, $this->_canal);
                $oCargarVista->setCarga('tabla', '/modulos/pedido/tabla.html');
                break;
            # ----> acción Confirmar Pedir
            case 'ConfirmarP':
                // recibe datos por POST
                $this->_canal = $_POST['canal'];
                $aRadios = $_POST['pedidos'];
                $aCantidad = $_POST['cantidadProd'];
                // muestra los array recibidos para prueba
                //var_dump($aRadios);
                //echo "<br>";
                //var_dump($aCantidad);
                //echo "<br>";
                $oProveedorVO->setRazonSocial($_POST['razonSocial']);
                $oProveedorModelo->findPorRazonSocial($oProveedorVO);
                
                // carga el contenido html
                $oCargarVista->setCarga('datos', '/modulos/pedido/vista/pedidoDatos.html');
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Pedido Realizado');
                $oDatoVista->setDato('{informacion}', '<p>Totales del pedido realizado al proveedor.</p>
														<p>También puede seleccionar otras acciones</p>
														<p>para los pedidos, ver botones.');
               // totalizadores en cero
                $this->_cantDescartados = $this->_cantUnidades = $this->_cantEliminados = $this->_cantPedidos = 0;
                
                // inserta el pedido nuevo para el proveedor
                $oPedidoVO->setIdProveedor($oProveedorVO->getId());
                $fecha = date('Y-m-d');
                $oPedidoVO->setFecha($fecha);
                $oPedidoVO->setCanal($this->_canal);
                $oPedidoVO->setEstado(1);
                $oPedidoVO->setFechaRec(NULL);
                $oPedidoVO->setComentario(NULL);
                $oPedidoVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
                $this->_date = date('Y-m-d H:i:s');
                $oPedidoVO->setFechaAct($this->_date);
                //echo "-> ".$oPedidoVO->getIdProveedor()." - ".$oPedidoVO->getFecha()." - ".$oPedidoVO->getCanal()." - ".$oPedidoVO->getEstado()." - ".$oPedidoVO->getFechaRec().
                //     " - ".$oPedidoVO->getComentario()." - ".$oPedidoVO->getIdUsuarioAct()." - ".$oPedidoVO->getFechaAct()."<br>";
                $oPedidoModelo->insert($oPedidoVO);
                $this->_idPedido = $oPedidoModelo->getLastId();
                //echo "Pedido ID: ".$this->_idPedido."<br>";
                
                // arma el array() $_aPedidos con datos datos recibidos 
                // por POST para actualizar la tabla de pendientes
                while (list($key, $val) = each($aRadios)){
                    if ($val == "Pedido"){
                        $estado = 2; // estado 2 - producto pedido en tabla pendientes
                        $aPedidos3[] = $key;
                        $this->_aPedidos[$key]['estado'] = $estado;
                        $this->_aPedidos[$key]['id_pedido'] = $this->_idPedido;
                    }
                    if ($val == "Elimina"){
                        $proveedorEli = 0; // pone 0 al idProveedor
                        $aPedidos2[] = $key;
                        $this->_aPedidos[$key]['id_proveedor'] = $proveedorEli;
                    }
                    if ($val == "Descarta"){
                        $estadoDescarta = 0; // estado en 0 - descarta el producto para pedidos
                        $aPedidos1[] = $key;
                        $this->_aPedidos[$key]['estado'] = $estadoDescarta;
                    }
                }
                while (list($key, $val) = each($aCantidad)){
                    $this->_aPedidos[$key]['cantidad'] = $val;
                }
                
                //var_dump($this->_aPedidos);
                //echo "<br>";
                
                // modifica pendientes por el pedido realizado al proveedor
                foreach ($this->_aPedidos as $clave => $valor){
                    $oPendienteVO->setId($clave);
                    $oPendienteModelo->find($oPendienteVO);
                    if (array_key_exists('estado', $valor)){
                        $oPendienteVO->setEstado($valor['estado']);
                        if ($valor['estado'] == 2) $this->_cantPedidos++;
                        if ($valor['estado'] == 0) $this->_cantDescartados++;
                    }
                    if (array_key_exists('id_proveedor', $valor)) {
                        $oPendienteVO->setIdProveedor($valor['id_proveedor']);
                        if ($valor['id_proveedor'] == 0) $this->_cantEliminados++;
                    }
                    if (array_key_exists('cantidad', $valor)){
                        $oPendienteVO->setCantidad($valor['cantidad']);
                        $this->_cantUnidades = $this->_cantUnidades + $valor['cantidad'];
                    }
                    if (array_key_exists('id_pedido', $valor)) $oPendienteVO->setIdPedido($valor['id_pedido']);
                    $oPendienteModelo->update($oPendienteVO);
                }
                // ---> final modifica pendientes
               
                // arma los datos a representar 
                $oPedidoVO->setIdProveedor($oProveedorVO->getId());
                $oPedidoModelo->countPedidosProveedor($oPedidoVO);
                $this->_cantProveedor = $oPedidoModelo->getCantidad();
                $oPedidoModelo->countPedidos();
                $this->_cantidad = $oPedidoModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                $oDatoVista->setDato('{razonSocial}', $oProveedorVO->getRazonSocial());
                $oDatoVista->setDato('{idProveedor}', $oProveedorVO->getId());
                $oDatoVista->setDato('{canal}', $this->_canal);
                $oDatoVista->setDato('{cantProveedor}', $this->_cantProveedor);
                $oDatoVista->setDato('{idPedido}', $this->_idPedido);
                $oDatoVista->setDato('{cantPedidos}', $this->_cantPedidos);
                $oDatoVista->setDato('{cantUnidades}', $this->_cantUnidades);
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