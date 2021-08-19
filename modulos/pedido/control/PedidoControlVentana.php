<?php
/**
 * Archivo de control de ventana del módulo pedido.
 *
 * Archivo de control de ventana del módulo pedido.
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
 * Clase control de ventana del módulo pedido.
 *
 * Clase control de ventana del módulo pedido que permite realizar
 * operaciones para ver los registros de la tabla pendientes
 * según la categoría asignada al usuario.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class PedidoControlVentana
{
    #Propiedades
    private $_html;
    private $_accion;
    private $_items;
    private $_item;
    private $_cantidad;
    private $_id;
    private $_date;
    private $_estado;
    private $_cantPedidos;
    private $_aPedidos = array();
    public $tabla;
    
    #Métodos
    /**
     * Verifica el login del usuario y nos envia a la
     * función que ejecuta las acciones en el módulo.
     */
    public function inicio($oLoginVO, $accion, $id)
    {
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        // Define las clases
        Clase::define('CargarVista');
        Clase::define('DatoVista');
        Clase::define('CargarMenu');
        Clase::define('MotorVista');
        Clase::define('ArticuloModelo');
        Clase::define('PedidoModelo');
        Clase::define('PedidoTablaV');
        Clase::define('PendienteModelo');
        Clase::define('PedidoDatos');
        Clase::define('ProveedorModelo');
        Clase::define('ProveedorSelect');
        Clase::define('ProductoModelo');
        Clase::define('RubroModelo');
        Clase::define('ArrayOrdenadoPor');
       
        // Chequea login
        $oLoginControl = new LoginControl();
        $oLoginControl->chequearLogin($oLoginVO);
        $this->_accion = $accion;
        $this->_id = $id;
        $this->accionControl($oLoginVO);
    }
    
    /**
     * Nos permite ejecutar las acciones para ventana del módulo
     * pedido del sistema, de acuerdo a la categoría del usuario.
     */
    private function accionControl($oLoginVO)
    {
        // Carga acciones del formulario
        if (isset($_POST['bt_ver'])) $this->_accion = "Ver";
        
        // Carga los archivos html para la vista
        $oCargarVista = new CargarVista();
        $oCargarVista->setCarga('pagina', '/includes/vista/pagina.html');
        // Carga el menú de la vista según la categoría del usuario
        
        // Ingresa los datos a representar en el html de la vista
        $oDatoVista = new DatoVista();
        $oDatoVista->setDato('{dir}', $_SESSION['dir']);
        $oDatoVista->setDato('{tituloPagina}', 'Ver Pedido');
        
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
            # ----> acción Editar
            case 'Editar':

                break;
                
            # ----> acción Confirmar Editar
            case 'ConfirmarE':
                
                break;
            # ----> acción Ver
            case 'Ver':
                $oPedidoVO->setId($this->_id);
                $oPedidoModelo->find($oPedidoVO);
                $this->_cantidad = $oPedidoModelo->getCantidad();

                // carga el contenido html
                $oCargarVista->setCarga('contenido', '/includes/vista/verVentana.html');
                $oCargarVista->setCarga('datos', '/modulos/pedido/vista/verDatos.html');
                // ingresa los datos a representar en el Panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Ver Pedido');
                // ingresa los datos a representar en el contenido de la vista
                $oProveedorVO->setId($oPedidoVO->getIdProveedor());
                $oProveedorModelo->find($oProveedorVO);
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
 
                PedidoDatos::cargaDatos($oPedidoVO, $oProveedorVO, $oDatoVista, $this->_accion);

                // arma la tabla de datos a representar
                $oPendienteVO->setIdPedido($this->_id);
                $this->_items = $oPendienteModelo->findAllPorIdPedido($oPendienteVO);
                $this->_cantPedidos = $oPendienteModelo->getCantidad();

                // arma array para mandar a la tabla de pedido
                foreach ($this->_items as $this->_item){
                    $oArticuloVO->setId($this->_item['id_articulo']);
                    $oArticuloModelo->find($oArticuloVO);
                    $this->_existeProducto = 0;
                    if ($this->_item['codigo_b'] > 0){
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
                        } else {
                            $this->_nombre = $oArticuloVO->getNombre();
                            $this->_codigoP = "Sin código";
                            $this->_presentacion = $oArticuloVO->getPresentacion();
                        }
                    } else {
                        $this->_nombre = $oArticuloVO->getNombre();
                        $this->_codigoP = "Sin código";
                        $this->_presentacion = $oArticuloVO->getPresentacion();
                    }
                    // busca el rubro
                    $oRubroVO->setId($this->_item['id_rubro']);
                    $oRubroModelo->find($oRubroVO);
                    $this->_rubro = $oRubroVO->getId();
                    // arma el array
                    $this->_aPedidos[] = array(
                        'id' => $this->_item['id'],
                        'codigo_b' => $this->_item['codigo_b'],
                        'codigo_p' => $this->_codigoP,
                        'rubro' => $this->_rubro,
                        'nombre' => $this->_nombre,
                        'presentacion' => $this->_presentacion,
                        'cantidad' => $this->_item['cantidad'],
                        'id_proveedor' => $this->_item['id_proveedor'],
                        'existe_producto' => $this->_existeProducto,
                        'estado' => $this->_item['estado']
                    );
                }

                // ordena el array
                $this->_items = ArrayOrdenadoPor::ordenaArray($this->_aPedidos, 'estado', SORT_ASC, 'rubro', SORT_ASC, 'nombre', SORT_ASC);
                // arma tabla para la ventana con el array
                PedidoTablaV::armaTabla($this->_items, $this->_cantPedidos, $this->_accion);
                $oCargarVista->setCarga('tabla', '/modulos/pedido/tabla.html');
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