<?php
/**
 * Archivo de la clase control del módulo producto.
 *
 * Archivo de la clase control del módulo producto.
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
 * Clase control del módulo producto.
 *
 * Clase control del módulo producto que permite realizar
 * un listado de productos con precio menor de un proveedor
 * de referencia.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class ProductoControlPrecioMenor
{
    #Propiedades
    private $_html;
    private $_accion;
    private $_items;
    private $_item;
    private $_condiciones;
    private $_condicion;
    private $_id;
    private $_nombre;
    private $_codigoB;
    private $_provUnico;
    private $_precioMenor;
    private $_condicionEspecial;
    private $_estado;
    private $_opcionProv;
    private $_orden;
    private $_date;
    private $_fechaHasta;
    private $_rotuloCondi;
    private $_margen;
    private $_costo;
    private $_costoProv;
    private $_precio;
    private $_porcentaje;
    private $_diferenciaPrecio;
    private $_porcentajeDiferencia;
    private $_modifico;
    private $_aAcciones = array();
    private $_aEventos = array();
    private $_aArticulosCondi = array();
    private $_aCondicionIva = array();
    private $_aProveedores = array();
    private $_aCantidadProv = array();
    private $_idProveedor;
    private $_aProveedoresLista = array();
    private $_aProveedoresRef = array();
    private $_aProductos = array();
    private $_aProductosEq = array();
    private $_aProductosLista = array();
    private $_producto;
    private $_productoEq;
    private $_aCondiFarma = array();
    private $_condiFarma;
    private $_aCantidadRubros = array();
    private $_aCantidadPorProv = array();
    private $_aCantidadProvUnico = array();
    private $_aCantidadPrecioMenor = array();
    private $_aCantidadIva = array();
    private $_cantListado;
    private $_count;
    private $_cont;
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
        Clase::define('ProductoModelo');
        Clase::define('ProductoPrecioMenorTabla');
        Clase::define('ProveedorModelo');
        Clase::define('ProveedorSelect');
        Clase::define('AfipCondicionIvaModelo');
        Clase::define('CondicionModelo');
        Clase::define('DataBasePlex');
        
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
        if (isset($_POST['bt_listar'])) $this->_accion = "Listar";
        if (isset($_POST['bt_listar_conf'])) $this->_accion = "ConfirmarL";
        
        
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
            "listar" => "/includes/vista/botonListar.html"
        );
        $oCargarVista->setCarga('aAcciones', $this->_aAcciones);
        // Ingresa los datos a representar en el html de la vista
        $oDatoVista = new DatoVista();
        $oDatoVista->setDato('{tituloPagina}', 'Productos');
        $oDatoVista->setDato('{usuario}', $oLoginVO->getAlias());
        $oDatoVista->setDato('{tituloBadge}', 'Productos ');
        // Alertas
        
        // Instancia las Clases necesarias
        $oArticuloVO = new ArticuloVO();
        $oArticuloModelo = new ArticuloModelo();
        $oProductoVO = new ProductoVO();
        $oProductoModelo = new ProductoModelo();
        $oProveedorVO = new ProveedorVO();
        $oProveedorModelo = new ProveedorModelo();
        $oArticuloCondiVO = new ArticuloCondiVO();
        $oArticuloCondiModelo = new ArticuloCondiModelo();
        $oCondicionVO = new CondicionVO();
        $oCondicionModelo = new CondicionModelo();
        
        // Carga el contenido html y datos según la acción
        // Selector de acciones
        switch ($this->_accion){
            # ----> acción Iniciar
            case 'Iniciar':
                // carga el contenido html
                $oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
                // ingresa los datos a representar en las alertas de la vista
                $oDatoVista->setDato('{alertaInfo}',  'Seleccione alguna acción con los botones.');
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Productos - Listado de Precio Menor p/Proveedor');
                $oDatoVista->setDato('{informacion}', '<p>Puede seleccionar acciones para los productos,</p><p>ver botones.');
                // arma la tabla de datos a representar
                $oProductoModelo->count();
                $this->_cantidad = $oProductoModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                break;
                # ----> acción Actualizar
            case 'Actualizar':
                break;
                # ----> acción Confirmar Actualizar
            case 'ConfirmarAct':
                break;
                # ----> acción Listar
            case 'Listar':
                // carga el contenido html
                $oCargarVista->setCarga('datos', '/modulos/producto/vista/proveedorOpcion.html');
                $oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
                // ingresa los datos a representar en las alertas de la vista
                $oDatoVista->setDato('{alertaInfo}',  'Seleccione el Proveedor a listar.');
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Productos - Listado de Precio Menor p/Proveedor');
                $oDatoVista->setDato('{informacion}', '<p>Seleccione un proveedor para listar productos,</p>
                                                       <p>ver botones u opciones del menú.');
                // carga los eventos (botones)
                $this->_aEventos = [
                    "listarConfirmar" => "/includes/vista/botonListarConfirmar.html",
                    "borrar" => "/includes/vista/botonBorrar.html"
                ];
                $oCargarVista->setCarga('aEventos', $this->_aEventos);
                // arma los datos a representar
                $oProductoModelo->count();
                $this->_cantidad = $oProductoModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                $this->_items = $oProveedorModelo->findAll();
                $this->_cantidad = $oProveedorModelo->getCantidad();
                $this->_idProveedor = 0;
                $oDatoVista->setDato('{cantidadProveedor}', $this->_cantidad);
                ProveedorSelect::armaSelect($this->_cantidad, $this->_items, $this->_accion, $this->_idProveedor);
                $oCargarVista->setCarga('selectProveedor', '/modulos/producto/selectProveedor.html');
                break;
                # ----> acción Confirmar Listar
            case "ConfirmarL":
                // recibe datos por POST
                $this->_idProveedor = $_POST['proveedor'];
                $oProveedorVO->setId($this->_idProveedor);
                //echo $oProveedorVO->getId()."<br>";
                // carga el contenido html
                $oCargarVista->setCarga('datos', '/modulos/producto/vista/listarPrecioMenor.html');
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Productos - Listado de Precio Menor p/Proveedor');
                $oDatoVista->setDato('{informacion}', '<p>Puede seleccionar acciones para los productos,</p>
                                                        <p>ver botones.');
                // arma los datos a representar
                $oProductoModelo->countPorProveedor($this->_idProveedor);
                $this->_cantidad = $oProductoModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                $oProveedorModelo->find($oProveedorVO);
                $oDatoVista->setDato('{proveedor}', $oProveedorVO->getRazonSocial());
                $oDatoVista->setDato('{cantProductos}', $this->_cantidad);
                // carga array proveedores
                $this->_items = $oProveedorModelo->findAllProveedoresRef();
                foreach ($this->_items as $this->_item){
                    $this->_aProveedores[$this->_item['id']] = $this->_item['inicial'];
                    $this->_aProveedoresLista[$this->_item['id']] = $this->_item['lista'];
                    $this->_aCondiFarma[$this->_item['id']] = 1; // Pone a todos sin condición, luego carga condiciones especiales
                }
                // carga array condición IVA
                $oAfipCondicionIvaVO = new AfipCondicionIvaVO();
                $oAfipCondicionIvaModelo = new AfipCondicionIvaModelo();
                $this->_items = $oAfipCondicionIvaModelo->findAll();
                foreach ($this->_items as $this->_item){
                    $this->_aCondicionIva[$this->_item['codigo']] = $this->_item['alicuota'];
                }
                // modifica array de indices especiales de Proveedores (las condiciones para la farmacia)
                $this->_aCondiFarma[2] = 0.95; // Del Sud descuento del 5%
                $this->_aCondiFarma[3] = 1.0325; // Nippon carga gastos de flete 3.25%
                $this->_aCondiFarma[4] = 0.95; // Keller descuento del 5%
                $this->_aCondiFarma[5] = 0.98; // CoFarLit descuento del 2%
                $this->_aCondiFarma[10] = 0.98; // Casa Florian descuento del 5% Contado más flete 3.25%
                // carga fecha para Condiciones
                $this->_fechaHasta = date('Y-m-d');
                // arma array de productos del proveedor
                $oProductoVO->setIdProveedor($this->_idProveedor);
                $this->_aProductos = $oProductoModelo->findAllPorIdProveedorPorNombre($oProductoVO);
                // recorre array de productos del proveedor y arma array de productos con artículos equivalentes
                foreach ($this->_aProductos as $this->_producto){
                    if ($this->_producto['id_articulo'] > 0){ // producto con artículo equivalente
                        $oArticuloVO->setId($this->_producto['id_articulo']);
                        $oArticuloModelo->find($oArticuloVO);
                        if ($oArticuloModelo->getCantidad() == 1){
                            if ($oArticuloVO->getEstado() == 1 AND $oArticuloVO->getIdRubro() > 1){
                                array_push($this->_aProductosEq, $this->_producto['codigo_b']);                       
                            }
                        }
                    }
                }
                //print "Son: ".count($this->_aProductosEq)." productos equivalentes.";
                $oDatoVista->setDato('{cantProductosEq}', count($this->_aProductosEq));
                unset($this->_aProductos);
                //var_dump($this->_aProductos);
                $cantProvUnico = $cantPrecioMenor = $this->_cont = 0;
                $this->_provUnico = $this->_precioMenor = $this->_condicionEspecial = 'NO';
                // recorre array de productos equivalentes para buscar precio menor
                foreach ($this->_aProductosEq as $this->_productoEq){
                    $oProductoVO->setCodigoB($this->_productoEq);
                    $this->_items = $oProductoModelo->findAllPorCodigoB($oProductoVO);
                    $aPreciosProv = array();
                    /**
                     * Calcula el costo del producto de los proveedores
                     */
                    foreach ($this->_items as $this->_item){
                        // busca condiciones del proveedor para la farmacia
                        if (isset($this->_aCondiFarma[$this->_item['id_proveedor']])) {
                            $this->_condiFarma = $this->_aCondiFarma[$this->_item['id_proveedor']];
                        } else {
                            $this->_condiFarma = 1;
                        }
                        // el costo para guardar en tabla es "NETO" (sin  IVA)
                        if ($this->_aProveedoresLista[$this->_item['id_proveedor']] == 'F'){
                            // Lista con precios finales
                            $this->_costo = round(($this->_item['precio'] * $this->_condiFarma)
                                / (1 + ($this->_aCondicionIva[$this->_item['codigo_iva']] / 100))
                                , 2);
                        } elseif ($this->_aProveedoresLista[$this->_item['id_proveedor']] == 'N') {
                            // Lista con precios netos
                            $this->_costo = round(($this->_item['precio'] * $this->_condiFarma), 2);
                        } else {
                            // Proveedor sin tipo de lista
                            $this->_costo = 0;
                            //echo " ** FALTA TIPO DE LISTA ** ";
                        } 
                        //echo "Prov:[".$this->_aProveedores[$this->_item['id_proveedor']]."] Precio: ".$this->_item['precio']." Condicion: ".$this->_condiFarma
                        //    ." IVA: ".$this->_aCondicionIva[$this->_item['codigo_iva']]." Costo ".$this->_costo."<br>";
                        if ($this->_item['id_proveedor'] == $this->_idProveedor) {
                            $this->_nombre = $this->_item['nombre'];
                            $this->_codigoB = $this->_item['codigo_b'];
                            $this->_costoProv = $this->_costo;
                            //echo "<b>".$this->_item['nombre']."</b> // Cod.Barra: ".$this->_item['codigo_b']."<br>";
                            /**
                             * Busca si tiene Condiciones especiales de ofertas y promo
                             */
                            $oArticuloCondiVO->setIdArticulo($this->_item['id_articulo']);
                            $oArticuloCondiVO->setFechaHasta($this->_fechaHasta);
                            $this->_condiciones = $oArticuloCondiModelo->findAllIdArticulo($oArticuloCondiVO);
                            $this->_condicionEspecial = 'NO';
                            if ($oArticuloCondiModelo->getCantidad()>0) {
                                // Muestro las condiciones del artículos
                                foreach ($this->_condiciones as $this->_condicion) {
                                    $oCondicionVO->setId($this->_condicion['id_condicion']);
                                    $oCondicionModelo->find($oCondicionVO);
                                    if ($oCondicionModelo->getCantidad()>0) {
                                        //echo "<b><FONT COLOR='green'>+++ VERIFICAR - Condición: ".$oCondicionVO->getNombre()." +++</FONT></b><br>";
                                        $this->_condicionEspecial = 'SI';
                                    } else {
                                        //echo "+++ Condición: NO ENCONTRADA<br>";
                                        $this->_condicionEspecial = 'NO';
                                    }
                                }
                            }
                        }
                        // Carga en el array de precios de proveedores el costo calculado del producto
                        // si hay más de un proveedor
                        $aPreciosProv[$this->_aProveedores[$this->_item['id_proveedor']]] = $this->_costo;
                    }
                    /**
                     * Revisa el array para ver si proveedor tiene costo menor
                     */
                    asort($aPreciosProv);
                    reset($aPreciosProv); // posiciona el puntero en el primer elemento
                    $this->_precioMenor = $this->_provUnico = "NO";
                    //var_dump($aPreciosProv);
                    if (key($aPreciosProv) == $this->_aProveedores[$this->_item['id_proveedor']]){
                        if (count($aPreciosProv)==1) {
                            //echo "<b><FONT COLOR='red'>*** PROVEEDOR UNICO *** </FONT></b><br>";
                            $this->_provUnico = 'SI';
                            $cantProvUnico++;
                        } else {
                            //echo "<b><FONT COLOR='blue'><<< PRECIO MENOR >>> </FONT></b><br>";
                            $this->_precioMenor = 'SI';
                            $cantPrecioMenor++;
                        }
                    }
                    //foreach ($aPreciosProv as $clave => $valor){
                    //    echo "[".$clave."] $ ".$valor." - ";
                    //}
                    //echo "<br>-------------------------- <br>";
                    // Arma tabla a representar en la vista
                    $this->_cont++;
                    $this->_aProductosLista[$this->_cont] = [
                        "nombre" => $this->_nombre,
                        "codigoB" => $this->_codigoB,
                        "costoProv" => $this->_costoProv,
                        "aPreciosProv" => $aPreciosProv,
                        "provUnico" => $this->_provUnico,
                        "precioMenor" => $this->_precioMenor,
                        "condicionEspecial" => $this->_condicionEspecial,
                    ];
                    
                } // fin foreach de productos equivalentes
                // Muestra datos en vista
                $oDatoVista->setDato('{cantProvUnico}', $cantProvUnico);
                $oDatoVista->setDato('{cantPrecioMenor}', $cantPrecioMenor);
                $this->_cantListado = count($this->_aProductosLista);
                ProductoPrecioMenorTabla::armaTabla($this->_aProductosLista, $this->_accion, $this->_cantListado);
                $oCargarVista->setCarga('tabla', '/modulos/producto/tabla.html');
                break;
                # ----> acción Agregar
            case 'Agregar':
                
                break;
                # ---> acción Confirmar Agregar
            case 'ConfirmarA':
                
                break;
                # ----> acción Editar
            case 'Editar':
                
                break;
                # ----> acción Confirmar Editar
            case 'ConfirmarE':
                
                break;
                # ----> acción Buscar
            case'Buscar':
                
                break;
                # ----> acción Confirmar Buscar
            case 'ConfirmarB':
                
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

