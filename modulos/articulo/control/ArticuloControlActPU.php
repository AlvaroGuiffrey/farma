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
 * actualización de los precios a grupos de artículos con
 * precios único (iguales), según el indicador asignado por el usuario.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class ArticuloControlActPU
{
    #Propiedades
    private $_html;
    private $_accion;
    private $_items;
    private $_item;
    private $_id;
    private $_estado;
    private $_presentacion;
    private $_indicadorPU;
    private $_precioNuevo;
    private $_opcionProv;
    private $_orden;
    private $_date;
    private $_margen;
    private $_costo;
    private $_precio;
    private $_porcentaje;
    private $_diferenciaPrecio;
    private $_porcentajeDiferencia;
    private $_modifico;
    private $_aAcciones = array();
    private $_aEventos = array();
    private $_aCondicionIva = array();
    private $_aProveedores = array();
    private $_idProveedor;
    private $_aProveedoresLista = array();
    private $_aProveedoresRef = array();
    private $_aProductos = array();
    private $_producto;
    private $_count;
    private $_cantidad;
    private $_con;
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
        Clase::define('ProveedorModelo');
        Clase::define('AfipCondicionIvaModelo');
        Clase::define('DataBasePlex');
        
        // Chequea login
        $oLoginControl = new LoginControl();
        $oLoginControl->chequearLogin($oLoginVO);
        $this->_accion = $accion;
        $this->accionControl($oLoginVO);
    }
    
    /**
     * Nos permite ejecutar las acciones del módulo
     * artículo del sistema, de acuerdo a los indicadores
     * agregados por el usuario.
     */
    private function accionControl($oLoginVO)
    {
        // Carga acciones del formulario
        if (isset($_POST['bt_actualizar'])) $this->_accion = "Actualizar";
        if (isset($_POST['bt_actualizar_conf'])) $this->_accion = "ConfirmarAct";
                
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
            "actualizar" => "/includes/vista/botonActualizar.html"
        );
        $oCargarVista->setCarga('aAcciones', $this->_aAcciones);
        // Ingresa los datos a representar en el html de la vista
        $oDatoVista = new DatoVista();
        $oDatoVista->setDato('{tituloPagina}', 'Artículos');
        $oDatoVista->setDato('{usuario}', $oLoginVO->getAlias());
        $oDatoVista->setDato('{tituloBadge}', 'Artículos ');
        // Alertas
        
        // Carga el contenido html y datos según la acción
        $oArticuloVO = new ArticuloVO();
        $oArticuloModelo = new ArticuloModelo();
        $oProductoVO = new ProductoVO();
        $oProductoModelo = new ProductoModelo();
        
        // Selector de acciones
        switch ($this->_accion){
            # ----> acción Iniciar
            case 'Iniciar':
                // carga el contenido html
                $oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
                // ingresa los datos a representar en las alertas de la vista
                $oDatoVista->setDato('{alertaInfo}',  'Seleccione alguna acción con los botones.');
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Artículos - Actualización de Precios Unificados - Selección de acciones');
                $oDatoVista->setDato('{informacion}', '<p>Puede seleccionar acciones para los artículos,</p><p>ver botones.');
                // arma la tabla de datos a representar
                // Cuenta todos los artículos con Precios Unificados
                $this->_presentacion = "(PU.";
                $oArticuloVO->setPresentacion($this->_presentacion);
                $oArticuloModelo->countPorPresentacion($oArticuloVO);
                $this->_cantidad = $oArticuloModelo->getCantidad();
                // arma la tabla de datos a representar
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                // Mensaje de advertencia si cantidad es CERO
                if ($this->_cantidad==0) {
                    $oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
                    // ingresa los datos a representar en las alertas de la vista
                    $oDatoVista->setDato('{alertaAdvertencia}',  'No hay artículos con PRECIOS UNIFICADOS. Modifique presentación PLEX.');
                }
                break;
                # ----> acción Actualizar
            case 'Actualizar':
                // carga el contenido html
                $oCargarVista->setCarga('datos', '/modulos/articulo/vista/modificarPreciosU.html');
                $oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
                // ingresa los datos a representar en las alertas de la vista
                $oDatoVista->setDato('{alertaPeligro}',  '<b>Actualiza PRECIOS UNIFICADOS</b>, ingrese datos y confirme la acción.');
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Artículos - Actualización de Precios Unificados');
                $oDatoVista->setDato('{informacion}', '<p>Confirme la acción seleccionada para los artículos,</p><p>ver botones.');
                
                // arma la tabla de datos a representar
                // Cuenta todos los artículos con Precios Unificados
                $this->_presentacion = "(PU.";
                $oArticuloVO->setPresentacion($this->_presentacion);
                $oArticuloModelo->countPorPresentacion($oArticuloVO);
                $this->_cantidad = $oArticuloModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                
                // carga los eventos (botones) si hay artículos con precios unificados
                if ($this->_cantidad>0){
                    $this->_aEventos = [
                        "actualizarConfirmar" => "/includes/vista/botonActualizarConf.html"
                    ];
                    $oCargarVista->setCarga('aEventos', $this->_aEventos);
                } else {
                    $oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
                    // ingresa los datos a representar en las alertas de la vista
                    $oDatoVista->setDato('{alertaAdvertencia}',  'No hay artículos con PRECIOS UNIFICADOS. Modifique presentación en PLEX, agregue indicador (PU.000).');
                    
                }
                break;
                # ----> acción Confirmar Actualizar
            case 'ConfirmarAct':
                // recibe los datos por POST
                $this->_cantidad = $_POST['cantidad'];
                $this->_indicadorPU = $_POST['indicadorPU'];
                $this->_precioNuevo = $_POST['precioNuevo'];
                echo $this->_indicadorPU."<br>";
                echo $this->_precioNuevo."<br>";
                // carga el contenido html
                $oCargarVista->setCarga('datos', '/modulos/articulo/vista/actualizarPreciosU.html');

                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Artículos - Actualización de Precios Unificados');
                $oDatoVista->setDato('{informacion}', '<p>Finalizó la acción solicitada para los artículos,</p><p>ver botones u opciones del menú.');
                 
                // arma la tabla de datos a representar
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                
                $actualiza = 0;
                $noActualiza = 0;
                $aumentos = 0;
                $bajas = 0;
                $noLeidos = 0;
                $promoNoActualiza = 0;
                $imprimeRotulo = 0;
                // actualiza los precios unificados de los artículos
                
                // carga array condición IVA
                $oAfipCondicionIvaVO = new AfipCondicionIvaVO();
                $oAfipCondicionIvaModelo = new AfipCondicionIvaModelo();
                $this->_items = $oAfipCondicionIvaModelo->findAll();
                foreach ($this->_items as $this->_item){
                    $this->_aCondicionIva[$this->_item['codigo']] = $this->_item['alicuota'];
                }
                
                // carga array proveedores
                $oProveedorVO = new ProveedorVO();
                $oProveedorModelo = new ProveedorModelo();
                $this->_items = $oProveedorModelo->findAllProveedoresRef();
                foreach ($this->_items as $this->_item){
                    $this->_aProveedores[$this->_item['id']] = $this->_item['inicial'];
                    $this->_aProveedoresLista[$this->_item['id']] = $this->_item['lista'];
                }
                
                /**
                 * Carga los artículos actualizables según los siguientes datos:
                 *
                 * presentacion LIKE ($oArticuloVO->setPresentacion($this->_indicadorPU))
                 * codigo > 9999900000 (esta la condición en la consulta)
                 * estado = 1 (esta la condición en la consulta)
                 */
                $oArticuloVO->setPresentacion($this->_indicadorPU); // indicador de PRECIO UNIFICADO
                
                $this->_items = $oArticuloModelo->findAllPorPresentacion($oArticuloVO);
                $oDatoVista->setDato('{cantActualizables}', $oArticuloModelo->getCantidad());
                /*
                 * 1 -Cambiar el codigo para la etiqueta
                 * 2 -Agregar en PLEX en producto_costo el nuevo precio
                 */
                
                // Fija el tope de precios a agregar igual a productos actualizables
                $topeAgrega = $oArticuloModelo->getCantidad();
                // $topeAgrega = 0;
                $agrega = 0;
                $cont = 0;
                
                // actualizo los precios de artículos y los productos en DB PLEX
                if ($oArticuloModelo->getCantidad()>0){ // Si hay artículos actualizables continúa
                   /**
                    * Conexión a DB PLEX
                    */ 
                    $this->_con = DataBasePlex::getInstance(); // conecto a DB PLEX
                  
                    foreach ($this->_items as $this->_item){ // extraigo los datos de artículos x artículo
                        $cont++;
                        // Muestra datos del registro de artículo
                        echo $cont." #".$this->_item['id']." [".$this->_item['codigo_b']."] ".$this->_item['nombre']." - $ ".$this->_item['costo']." (".$this->_item['margen']."%)<br>";
                        echo ". . . . Precio $".$this->_item['precio']." - Prov: ".$this->_item['id_proveedor']." (".$this->_aProveedores[$this->_item['id_proveedor']].") ";
                        
                        /**
                         * Si es PROMO no modifica precio
                         */
                        $pos = strpos($this->_item['nombre'], 'PR.');
                        if ($pos !== false) {
                            echo "<b> <<< PROMO >>>> NO MODIFICA </b><br>";
                            $promoNoActualiza++;
                        } else {	// Artículo sin PROMO sigue actualiza precio
                            /**
                             * Calcula el costo con lista del proveedor de referencia para actualizar
                             * artículo, es SOLO informativo al precio lo toma del ingresado
                             */
                            
                            // Carga el NUEVO precio ingresado
                            $this->_precio = $this->_precioNuevo;
                            // Carga el costo para información
                            $oProductoVO->setIdArticulo($this->_item['id']);
                            $oProductoVO->setCodigoB(trim($this->_item['codigo_b']));
                            $oProductoVO->setIdProveedor($this->_item['id_proveedor']);
                            $oProductoModelo->findPorIdArticuloCodigoBProveedor($oProductoVO); // consulta producto ACTIVO
                            if ($oProductoModelo->getCantidad()>0){ // tiene producto activo en lista del proveedor de referencia
                                echo " -> lista $ ".$oProductoVO->getPrecio()." (".$this->_aProveedoresLista[$this->_item['id_proveedor']].") ";
                                // verifico que margen no sea CERO
                                $this->_margen = $this->_item['margen'];
                                if ($this->_margen == 0) $this->_margen = 37;  // margen = 0 pongo treinta y siete de prepo
                                
                                // costo para guardar es "NETO" (sin  IVA)
                                if ($this->_aProveedoresLista[$this->_item['id_proveedor']] == 'F'){
                                    // Lista con precios finales
                                    $this->_costo = round($oProductoVO->getPrecio() / (1 + ($this->_aCondicionIva[$this->_item['codigo_iva']] / 100)), 2);
                                    
                                } else {
                                    if ($this->_aProveedoresLista[$this->_item['id_proveedor']] == 'N'){
                                        // Lista con precios netos
                                        $this->_costo = round($oProductoVO->getPrecio(), 2);
                                        
                                    } else {
                                        // Proveedor sin tipo de lista
                                        $this->_costo = 0;
                                        
                                        echo " ** FALTA TIPO DE LISTA ** ";
                                    }
                                }
                            } else { // No encontró el producto para el proveedor
                                $noLeidos++;
                                if ($this->_item['id_proveedor'] > 1) { // Tenía proveedores de ref lo cambio por 1
                                    // modificar artículo poner id_proveedor en 1, equivalencia en 0, opcion_prov en 0 y actualizar el registro
                                    echo "NO LEI PRODUCTO (MODIF. PROVEEDOR REF. = 1)<br>";
                                    $oArticuloVO->setId($this->_item['id']);
                                    $oArticuloModelo->find($oArticuloVO);
                                    $oArticuloVO->setIdProveedor(1);
                                    $oArticuloVO->setOpcionProv(0);
                                    $oArticuloVO->setEquivalencia(0);
                                    $oArticuloVO->setRotulo(1);
                                    $oArticuloVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
                                    $this->_date = date('Y-m-d H:i:s');
                                    $oArticuloVO->setFechaAct($this->_date);
                                    $oArticuloModelo->update($oArticuloVO);
                                } else {
                                    echo "NO LEI PRODUCTO - NO ACTUALIZA<br>";
                                }
                            } // fin NO encontró el producto en proveedores
                                
                            echo " Precio Nuevo $ ".$this->_precio." - ";
                                
                            /**
                             * PRECIOS - VEO SI ACTUALIZO
                             * 1 - Precios iguales sigo sin actualizar
                             * 2 - Precios diferentes actualizo "SI o SI" (no interesa la diferencia)
                             */
                            $this->_diferenciaPrecio = $this->_porcentajeDiferencia = 0;
                                
                            if ($this->_item['precio'] == $this->_precio){ // Precios iguales no actualiza
                                echo " *** PRECIO IGUAL NO ACTUALIZO ***<br>";
                                $noActualiza++;
                            } else { // Precios diferentes actualizo SI o SI
                                    
                                // Armo totales para mostrar
                                if ($this->_precio > $this->_item['precio']){
                                    // Nuevo Precio es mayor - Aumenta precio
                                    echo " AUMENTA PRECIO ACTUALIZO <br>";
                                    $aumentos++;
                                } else {
                                    // Nuevo precio es menor - Baja precio
                                    echo " BAJA PRECIO ACTUALIZO <br>";
                                    $bajas++;
                                }
                                    
                                if ($agrega < $topeAgrega){ // Agrega precio a PLEX
                                    // Agrego registro a BD PLEX con precio
                                    $this->agregarProductoCosto($this->_item['codigo'], $this->_precio);
                                    // Modifico articulo con los nuevos datos
                                    $oArticuloVO->setId($this->_item['id']);
                                    $oArticuloModelo->find($oArticuloVO);
                                    $oArticuloVO->setCosto($this->_costo);
                                    $oArticuloVO->setPrecio($this->_precio);
                                    $oArticuloVO->setFechaPrecio(date('Y-m-d'));
                                    // cambia el rótulo para imprimir
                                    if ($oArticuloVO->getRotulo() > 0){
                                        $oArticuloVO->setRotulo(2);
                                        $imprimeRotulo++;
                                    }
                                    $oArticuloVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
                                    $this->_date = date('Y-m-d H:i:s');
                                    $oArticuloVO->setFechaAct($this->_date);
                                    $oArticuloModelo->update($oArticuloVO);
                                    $actualiza++;
                                    $agrega++;
                                            
                                } // fin agrego precio a PLEX
                            } // fin precios diferentes
                        } // fin sin PROMO
                    } // fin foreach de articulos x artículo
                    
                    DataBasePlex::closeInstance(); // cierra conección con PLEX
                    // Carga alertas de la vista
                    $oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
                    // ingresa los datos a representar en las alertas de la vista
                    $oDatoVista->setDato('{alertaSuceso}',  'Finalizó la acción solicitada con <b>EXITO !!!</b>. Indicador: '.$this->_indicadorPU.' a $ '.$this->_precio.'.');
                } else { // fin artículos actualizables
                    // Carga alertas de la vista
                    $oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
                    // ingresa los datos a representar en las alertas de la vista
                    $oDatoVista->setDato('{alertaAdvertencia}',  'NO actualizó precios, <b>NO EXISTE</b> el indicador: '.$this->_indicadorPU.'.');
                }
                $oDatoVista->setDato('{cantActualizados}',  $actualiza);
                $oDatoVista->setDato('{cantNoActualiza}',  $noActualiza);
                $oDatoVista->setDato('{cantPromoNoActualiza}', $promoNoActualiza);
                $oDatoVista->setDato('{cantAumentos}',  $aumentos);
                $oDatoVista->setDato('{cantBajas}',  $bajas);
                $oDatoVista->setDato('{cantNoLeidos}',  $noLeidos);
                $oDatoVista->setDato('{cantImprimeRotulo}', $imprimeRotulo);
                
                break;
                # ----> acción Listar
            case 'Listar':

                break;
                # ----> acción Confirmar Listar
            case "ConfirmarL":
                
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
    
    /**
     * Nos permite agregar un registro a la tabla productoscostos de la DB Plex
     * con el nuevo precio del artículo
     */
    private function agregarProductoCosto($IDProducto, $Precio)
    {
        $TipoLista = 'P';
        $Fecha = date('Y-m-d');
        $IDUsuario = '1';
        $Origen = 'A';
        // verifico si no hay precio para esta fecha
        $query = "SELECT count(*) FROM productoscostos WHERE IDProducto='$IDProducto' AND Fecha='$Fecha' AND TipoLista='P'";
        $result = mysqli_query($this->_con, $query) or die(mysqli_error($this->_con));
        $count = mysqli_fetch_array($result);
        mysqli_free_result($result);
        $this->_count = $count[0];
        // actualizo no hay precio para la fecha
        if ($this->_count==0){
            $query = "INSERT INTO productoscostos
		(IDProducto, TipoLista, Fecha, IDUsuario, Precio, Origen)
		VALUES
		('$IDProducto', '$TipoLista', '$Fecha', '$IDUsuario', '$Precio', '$Origen')";
            $res = mysqli_query($this->_con, $query) or die(mysqli_error($this->_con));
            mysqli_free_result($res);
        }
    }
    
}

?>