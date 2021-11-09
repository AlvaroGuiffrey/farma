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
 * @version    5.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      File available since Release 1.0
 */

/**
 * Clase control del módulo artículo.
 *
 * Clase control del módulo artículo que permite realizar
 * actualización de los precios con las listas de los proveedores
 * de referencia, según la categoría asignada al usuario.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    5.0
 * @date       26/10/2021 modificado
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 *
 * Síntesis modificaciones:
 * Versión 3.0 - Se mejora la vista eliminando los "echo", se crea una tabla con
 * datos de los artículos que actualiza en un archivo html.
 *
 * Versión 4.0 - Se controla si el artículo existe en plex antes de actualizar.
 *
 * Versión 5.0 - Se controla si el artículo tiene precio máximo s/resolución
 * del Gob.Nacional
 */

class ArticuloControlActualiza
{
    #Propiedades
    private $_html;
    private $_accion;
    private $_items;
    private $_item;
    private $_id;
    private $_estado;
    private $_nombre;
    private $_codigoB;
    private $_idProveedor;
    private $_proveedorRef;
    private $_opcionProv;
    private $_rotulo;
    private $_precioPR;
    private $_precioPU;
    private $_precioAnt;
    private $_precioAct;
    private $_precioMax;
    private $_condicion;
    private $_aumenta;
    private $_baja;
    private $_orden;
    private $_date;
    private $_margen;
    private $_costo;
    private $_proveedoresCosto;
    private $_precio;
    private $_porcentaje;
    private $_diferenciaPrecio;
    private $_porcentajeDiferencia;
    private $_modifico;
    private $_aAcciones = array();
    private $_aEventos = array();
    private $_aArticulosCondi = array();
    private $_aArticulosPM = array();
    private $_aCondicionIva = array();
    private $_aProveedores = array();
    private $_aProveedoresLista = array();
    private $_aProveedoresRef = array();
    private $_aProductos = array();
    private $_producto;
    private $_aDatosTabla = array();
    private $_aRangos = array();
    private $_count;
    private $_con;
    private $_cantListado;
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
        Clase::define('ArticuloActTabla');
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
     * artículo del sistema, de acuerdo a la categoría del
     * usuario.
     */
    private function accionControl($oLoginVO)
    {
        // Carga acciones del formulario
        if (isset($_POST['bt_actualizar'])) {
            $this->_accion = "Actualizar";
        }
        if (isset($_POST['bt_actualizar_conf'])) {
            $this->_accion = "ConfirmarAct";
        }
        if (isset($_POST['bt_listar'])) {
            $this->_accion = "Listar";
        }
        if (isset($_POST['bt_listar_conf'])) {
            $this->_accion = "ConfirmarL";
        }

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
            "actualizar" => "/includes/vista/botonActualizar.html"
        );
        $oCargarVista->setCarga('aAcciones', $this->_aAcciones);
        // Ingresa los datos a representar en el html de la vista
        $oDatoVista = new DatoVista();
        $oDatoVista->setDato('{tituloPagina}', 'Artículos');
        $oDatoVista->setDato('{usuario}', $oLoginVO->getAlias());
        $oDatoVista->setDato('{tituloBadge}', 'Artículos ');
        // Alertas

        // Instancia las Clases necesarias
        $oArticuloVO = new ArticuloVO();
        $oArticuloModelo = new ArticuloModelo();
        $oProductoVO = new ProductoVO();
        $oProductoModelo = new ProductoModelo();
        $oArticuloCondiVO = new ArticuloCondiVO();
        $oArticuloCondiModelo = new ArticuloCondiModelo();

        // Carga el contenido html y datos según la acción
        // Selector de acciones
        switch ($this->_accion) {
            # ----> acción Iniciar
            case 'Iniciar':
                // carga el contenido html
                $oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
                // ingresa los datos a representar en las alertas de la vista
                $oDatoVista->setDato('{alertaInfo}', 'Seleccione alguna acción con los botones.');
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Artículos - Actualización de Precios - Selección de acciones');
                $oDatoVista->setDato('{informacion}', '<p>Puede seleccionar acciones para los artículos,</p><p>ver botones.');
                // arma la tabla de datos a representar
                $oArticuloModelo->count();
                $this->_cantidad = $oArticuloModelo->getCantidad();
                // arma la tabla de datos a representar
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                break;
                # ----> acción Actualizar
            case 'Actualizar':
                // carga el contenido html
                $oCargarVista->setCarga('datos', '/modulos/articulo/vista/actualizarPrecios.html');
                $oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
                $oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');

                // ingresa los datos a representar en las alertas de la vista
                $oDatoVista->setDato('{alertaPeligro}', '<b>Actualiza los precios de los artículos</b>, confirme la acción.');
                $oDatoVista->setDato('{alertaInfo}', 'Puede seleccionar rangos de aumentos a aplicar.');
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Artículos - Actualización de Precios');
                $oDatoVista->setDato('{informacion}', '<p>Confirme la acción seleccionada para los artículos,</p><p>ver botones.');
                // carga los eventos (botones)
                $this->_aEventos = [
                    "actualizarConfirmar" => "/includes/vista/botonActualizarConf.html"
                ];
                $oCargarVista->setCarga('aEventos', $this->_aEventos);

                // arma la tabla de datos a representar
                $oArticuloModelo->count();
                $this->_cantidad = $oArticuloModelo->getCantidad();
                /**
                 * Cuenta los artículos actualizables según los siguientes datos:
                 *
                 * codigo > 9999900000 ($oArticuloVO->setCodigo())
                 * id_proveedor > 1 ($oArticuloVO->setIdProveedor())
                 * codigo_b > 0 (esta la condición en la consulta)
                 * estado = 1 ($oArticuloVO->setEstado())
                 */
                $oArticuloVO->setCodigo(9999900000); // mayor que
                $oArticuloVO->setIdProveedor(1); // mayor que: 1 - SIN IDENTIFICAR
                $oArticuloVO->setEstado(1); // igual que
                // codigo_b > 0
                $oArticuloModelo->countActualizables($oArticuloVO);
                $oDatoVista->setDato('{cantActualizables}', $oArticuloModelo->getCantidad());
                $oDatoVista->setDato('{cantActualizados}', 0);
                $oDatoVista->setDato('{cantNoActualiza}', 0);
                $oDatoVista->setDato('{cantAumentos}', 0);
                $oDatoVista->setDato('{cantBajas}', 0);
                $oDatoVista->setDato('{cantNoLeidos}', 0);
                $oDatoVista->setDato('{cantPromo}', 0);
                $oDatoVista->setDato('{cantPrecioUnificado}', 0);
                $oDatoVista->setDato('{cantPrecioMaximo}', 0);
                $oDatoVista->setDato('{cantImprimeRotulo}', 0);
                $oDatoVista->setDato('{cantImprimeRotuloPromo}', 0);
                $oDatoVista->setDato('{cantInexistentesPlex}', 0);
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                $oDatoVista->setDato('{masTreinta}', 0);
                $oDatoVista->setDato('{masVeinte}', 0);
                $oDatoVista->setDato('{masDiez}', 0);
                $oDatoVista->setDato('{masCinco}', 0);
                $oDatoVista->setDato('{menosCinco}', 0);
                $oDatoVista->setDato('{img1}', ' ');
                $oDatoVista->setDato('{img2}', ' ');
                $oDatoVista->setDato('{img3}', ' ');
                $oDatoVista->setDato('{img4}', ' ');
                $oDatoVista->setDato('{img5}', ' ');
                $oDatoVista->setDato('{img6}', ' ');
                $oDatoVista->setDato(
                    '{evento}',
                    '<h3 style="color:red">Modifica precios en PLEX</h3>'
                );

                break;
                # ----> acción Confirmar Actualizar
            case 'ConfirmarAct':

                /**
                 * Actualiza los precios de acuerdo a los rangos de % de
                 * aumentos seleccionados.
                 * Nota: Actualiza todas las bajas de precios.
                 * Fecha: 14/11/2020
                 */

                // Recibe los datos por POST
                $this->_aRangos = $_POST['rangos']; // rangos de % de aumentos
                $topeAgrega = $_POST['cantActualizables']; // tope de artículos a actualizar
                // carga el contenido html
                $oCargarVista->setCarga('datos', '/modulos/articulo/vista/actualizarPrecios.html');
                $oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
                // ingresa los datos a representar en las alertas de la vista
                $oDatoVista->setDato('{alertaSuceso}', 'Finalizó la acción solicitada con <b>EXITO !!!</b>.');
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Artículos - Actualización de Precios');
                $oDatoVista->setDato('{informacion}', '<p>Finalizó la acción solicitada para los artículos,</p><p>ver botones u opciones del menú.');
                // arma la tabla de datos a representar
                $oDatoVista->setDato('{cantActualizables}', $_POST['cantActualizables']);
                $oDatoVista->setDato('{cantidad}', $_POST['cantidad']);
                // pone en cero los totalizadores y auxiliares varios
                $actualiza = $noActualiza = $promoNoActualiza = $PUNoActualiza = $aumentos = $baja = 0;
                $diferenciaMenor = $imprimeRotulo = $imprimeRotuloPromo = $noLeidos = $inexistentesPlex = 0;
                $cont = $agrega = $condi = $masTreinta = $masVeinte = $masDiez = $masCinco = $menosCinco = 0;
                $PMNoActualiza = 0;
                $linea1 = $linea2 = ''; // Lineas para mostrar datos de los artículo
                unset($this->_aDatosTabla); // elimina datos del array para tabla
                // carga array condición IVA
                $oAfipCondicionIvaVO = new AfipCondicionIvaVO();
                $oAfipCondicionIvaModelo = new AfipCondicionIvaModelo();
                $this->_items = $oAfipCondicionIvaModelo->findAll();
                foreach ($this->_items as $this->_item) {
                    $this->_aCondicionIva[$this->_item['codigo']] = $this->_item['alicuota'];
                }
                // carga array proveedores
                $oProveedorVO = new ProveedorVO();
                $oProveedorModelo = new ProveedorModelo();
                $this->_items = $oProveedorModelo->findAllProveedoresRef();
                foreach ($this->_items as $this->_item) {
                    $this->_aProveedores[$this->_item['id']] = $this->_item['inicial'];
                    $this->_aProveedoresLista[$this->_item['id']] = $this->_item['lista'];
                }
                // carga array precios máximos
                $this->_items = $oArticuloPMModelo->findAll();
                foreach ($this->_items as $this->_item) {
                    $this->_aArticulosPM[$this->_item['codigo_b']] = $this->_item['precio'];
                }
                /**
                 * Carga los artículos actualizables según los siguientes datos:
                 *
                 * codigo > 9999900000 ($oArticuloVO->setCodigo())
                 * id_proveedor > 1 ($oArticuloVO->setIdProveedor())
                 * codigo_b > 0 (esta la condición en la consulta)
                 * estado = 1 ($oArticuloVO->setEstado())
                 */
                $oArticuloVO->setCodigo(9999900000); // mayor que
                $oArticuloVO->setIdProveedor(1); // mayor que: 1 - SIN IDENTIFICAR
                $oArticuloVO->setEstado(1); // igual que (activo)
                // codigo_b > 0 // condición esta en la consulta (tiene que tener codigo de barra)
                $this->_items = $oArticuloModelo->findAllActualizables($oArticuloVO);

                /*
                 * Actualiza precios en PLEX y rótulos para la impresión
                 *
                 * 1 - Cambiar el codigo para el rótulo de precio del artículo
                 * 2 - Cambiar el código para el rótulo de of/promo del artículo
                 * 3 - Agregar en PLEX en producto_costo el nuevo precio
                 */


                //echo "**************************************<br>";
                //echo "   <b style='color:red;'>LISTADO DE ACTUALIZACIONES</b><br>";
                //echo " <b style='color:red;'><< MODIFICO PRECIOS EN PLEX >></b><br>";
                //echo "**************************************<br>";
                //echo "Articulos actualizables: ".$topeAgrega."<br>";

                $this->_cantListado = 0;
                // verifica si hay artículos actualizables
                if ($oArticuloModelo->getCantidad()>0) { // Si hay artículos actualizables continúa
                    // Conecta a la BD PLEX en servidor
                    $this->_con = DataBasePlex::getInstance(); // conecto a DB PLEX
                    // Lee artículos actualizables
                    foreach ($this->_items as $this->_item) { // extraigo los datos de artículos x artículo
                        $cont++;
                        $this->_condicion = 'NO';
                        // Muestra datos del registro de artículo
                        //$linea1 = $cont." #".$this->_item['id']." [".$this->_item['codigo_b']."] ".$this->_item['nombre'].
                        //" (Rotulo: ".$this->_item['rotulo'].") ".
                        //" - $ ".$this->_item['costo']." (".$this->_item['margen']."%) Precio $".$this->_item['precio'];
                        //$linea2 = " Prov: ".$this->_item['id_proveedor']." (".$this->_aProveedores[$this->_item['id_proveedor']].") ";

                        /**
                         * Versión 4.0 - Se busca en db plex si existe el producto para evitar el error
                         * al intentar agregar un precio cuando el producto fue eliminado.
                         */
                        $query = "SELECT count(*) FROM productos WHERE IDProducto=".$this->_item['codigo'];
                        $result = mysqli_query($this->_con, $query) or die(mysqli_error($this->_con));
                        $count = mysqli_fetch_array($result);
                        mysqli_free_result($result);
                        $this->_cantProductos = $count[0];

                        if ($count[0]==0) { // no hay un producto en PLEX
                            echo "<b><<< Artículo inexistente en PLEX >>> ".$this->_item['codigo']."</b><br>";
                            $inexistentesPlex++;
                        /**
                         * SI es PROMO; PRECIO UNIFICADO o PRECIO MAXIMO (en nombre del artículo) no actualizo
                         */
                        } elseif (strpos($this->_item['nombre'], 'PR.') !== false) { // Artículo con PROMO
                            //$linea2 .= "<b style='color:orange;'> <<< PROMO >>>> NO MODIFICA </b>";
                            $this->_condicion = "PROMO";
                            $promoNoActualiza++;
                        } elseif (strpos($this->_item['nombre'], 'PMAX.') !== false) { // Artículo con Precio Máximo
                            //$linea2 .= "<b style='color:orange;'> <<< PR.MAX. >>>> NO MODIFICA </b>";
                            $this->_condicion = "PR.MAX.";
                            $PMNoActualiza++;
                        } elseif (strpos($this->_item['presentacion'], '(PU.') !== false) {	// Artículo con PRECIO UNIFICADO
                            //$linea2 .= "<b style='color:orange;'> <<< PRECIO UNIFICADO >>>> NO MODIFICA </b>";
                            $this->_condicion = "P.UNIF";
                            $PUNoActualiza++;
                        } else {	// Artículo sin condición desde el nombre (PR. ; PU. o PM.)
                            /**
                             * Calcula el precio con lista del proveedor de referencia
                             */
                            $oProductoVO->setIdArticulo($this->_item['id']);
                            $oProductoVO->setCodigoB(trim($this->_item['codigo_b']));
                            $oProductoVO->setIdProveedor($this->_item['id_proveedor']);
                            $oProductoModelo->findPorIdArticuloCodigoBProveedor($oProductoVO); // consulta producto ACTIVO
                            if ($oProductoModelo->getCantidad()>0) { // tiene producto activo en lista del proveedor de referencia
                                //$linea2 .= " -> lista $ ".$oProductoVO->getPrecio()." (".$this->_aProveedoresLista[$this->_item['id_proveedor']].") ";
                                // verifico que margen no sea CERO
                                $this->_margen = $this->_item['margen'];
                                if ($this->_margen == 0) {
                                    $this->_margen = 37;
                                }  // margen = 0% pongo 37% de prepo
                                // calculo precio con el costo del producto
                                // costo para guardar es "NETO" (sin  IVA)
                                if ($this->_aProveedoresLista[$this->_item['id_proveedor']] == 'F') {
                                    // Lista con precios finales
                                    $this->_costo = round($oProductoVO->getPrecio() / (1 + ($this->_aCondicionIva[$this->_item['codigo_iva']] / 100)), 2);
                                    $this->_precio = round(($oProductoVO->getPrecio() * (1 + ($this->_margen / 100))), 2);
                                } elseif ($this->_aProveedoresLista[$this->_item['id_proveedor']] == 'N') {
                                    // Lista con precios netos
                                    $this->_costo = round($oProductoVO->getPrecio(), 2);
                                    $this->_precio = round((($oProductoVO->getPrecio() * (1 + ($this->_aCondicionIva[$this->_item['codigo_iva']] / 100))) * (1 + ($this->_margen / 100))), 2);
                                } else {
                                    // Proveedor sin tipo de lista
                                    $this->_costo = 0;
                                    $this->_precio = 0;
                                    //$linea2 .= " ** FALTA TIPO DE LISTA ** ";
                                }
                                $this->_precioAct = $this->_precio;
                                /**
                                 * PRECIOS - VEO SI ACTUALIZO
                                 * 1 - Precios iguales sigo sin actualizar
                                 * 2 - Precios diferentes con diferencia menor al 2% sigo sin actualizar
                                 * 3 - Precios diferentes con diferencia mayor al 2%:
                                 *      Actualiza si está seleccionado el rango del % de aumento
                                 */
                                // Pone valores a los parámetros para ver si actualiza
                                $this->_diferenciaPrecio = $this->_porcentajeDiferencia = 0;
                                $this->_modifico = 'N';
                                $this->_precioAnt = $this->_item['precio'];

                                if ($this->_item['precio'] == $this->_precio) { // Precios iguales no actualiza
                                    $noActualiza++;
                                //$linea2 .= " Precio Nuevo $ ".$this->_precio." *** PRECIO IGUAL NO ACTUALIZO ***";
                                } else {
                                    // Precios diferentes hago comparaciones y veo si actualizo
                                    $this->_aumenta = $this->_baja = $this->_porcentaje = 0;
                                    /**
                                     * Con una diferencia igual o menor al 2% no modifico
                                     * los precios de los artículos
                                     */
                                    //$linea2 .= " -> lista $ ".$oProductoVO->getPrecio()." (".$this->_aProveedoresLista[$this->_item['id_proveedor']].") ";
                                    //$linea2 .= " Precio Nuevo $ ".$this->_precio." - ";
                                    // Armo totales para mostrar e indico si actualiza según rangos de % seleccionados
                                    if ($this->_precio > $this->_item['precio']) { // PRECIO CON AUMENTO
                                        // calculo diferencia de precio
                                        $this->_diferenciaPrecio = round(($this->_precio - $this->_item['precio']), 2);
                                        // calculo 2% del precio del artículo
                                        $this->_porcentajeDiferencia = round(($this->_item['precio']*2/100), 2);
                                        // Si diferencia es menor o igual a 2% no actualizo
                                        if ($this->_porcentajeDiferencia > $this->_diferenciaPrecio) {
                                            //$linea2 .= " DIF $".$this->_diferenciaPrecio." < 2% ";
                                            $diferenciaMenor++;
                                        } else {
                                            // Actualiza si el rango de % esta seleccionado - Nuevo precio mayor
                                            // que precio de artículo
                                            $aumentos++;
                                            // Calcula porcentaje de aumento
                                            $this->_porcentaje = round((($this->_precio - $this->_item['precio']) / $this->_item['precio'] * 100), 2);
                                            //$linea2 .= $this->_porcentaje."% ";
                                            $this->_aumenta = $this->_porcentaje;
                                            // Verifica rangos de % de aumentos
                                            if ($this->_porcentaje > 30) {
                                                if (isset($this->_aRangos['r5'])) {
                                                    $this->_modifico = 'S';
                                                }
                                                //$linea2 .= " -> MAS DE 30 % ";
                                                $masTreinta++;
                                            } elseif ($this->_porcentaje > 20) {
                                                if (isset($this->_aRangos['r4'])) {
                                                    $this->_modifico = 'S';
                                                }
                                                //$linea2 .= " -> MAS DE 20 % ";
                                                $masVeinte++;
                                            } elseif ($this->_porcentaje > 10) {
                                                if (isset($this->_aRangos['r3'])) {
                                                    $this->_modifico = 'S';
                                                }
                                                //$linea2 .= " -> MAS DE 10 % ";
                                                $masDiez++;
                                            } elseif ($this->_porcentaje > 5) {
                                                if (isset($this->_aRangos['r2'])) {
                                                    $this->_modifico = 'S';
                                                }
                                                //$linea2 .= " -> MAS DE 5 % ";
                                                $masCinco++;
                                            } else {
                                                if (isset($this->_aRangos['r1'])) {
                                                    $this->_modifico = 'S';
                                                }
                                                //$linea2 .= " -> MENOS O IGUAL A 5 % ";
                                                $menosCinco++;
                                            }
                                        }
                                        // Muestra si actualiza
                                        //if ($this->_modifico == 'S') $linea2 .= " +++ ACTUALIZA +++ ";
                                        //if ($this->_modifico == 'N') $linea2 .= " - NO ACTUALIZA - ";
                                    } else {
                                        // PRECIO CON BAJA
                                        // Actualiza - Nuevo precio menor que precio de artículo
                                        if ($this->_precio < $this->_item['precio']) {
                                            // Calcula porcentaje de baja
                                            $this->_porcentaje = round((($this->_precio - $this->_item['precio']) / $this->_item['precio'] * 100), 2);
                                            $this->_baja = $this->_porcentaje;
                                            // calculo diferencia de precio
                                            $this->_diferenciaPrecio = round(($this->_item['precio'] - $this->_precio), 2);
                                            // calculo 2% del precio del artículo
                                            $this->_porcentajeDiferencia = round(($this->_item['precio']*2/100), 2);
                                            // Si diferencia es menor o igual a 2% no actualizo
                                            if ($this->_porcentajeDiferencia > $this->_diferenciaPrecio) {
                                                //$linea2 .= " DIF $".$this->_diferenciaPrecio." < 2% ";
                                                $diferenciaMenor++;
                                            } else {
                                                //$this->_modifico = 'S';
                                                // Verifica el rango para ver si actualiza
                                                if (isset($this->_aRangos['r6'])) {
                                                    $this->_modifico = 'S';
                                                }
                                                //$linea2 .= " -BAJA PRECIO- ";
                                                $baja++;
                                            }
                                            // Muestra si actualiza
                                            //if ($this->_modifico == 'S') $linea2 .= " +++ ACTUALIZA +++ ";
                                            //if ($this->_modifico == 'N') $linea2 .= " - NO ACTUALIZA - ";
                                        }
                                    }

                                    // Artículos con modificación de precios agrego registros a PLEX
                                    if ($this->_modifico == 'S') {
                                        if ($agrega < $topeAgrega) {
                                            // Agrego registro a BD PLEX con precio
                                            echo "-> ".$this->_item['codigo'];
                                            $this->agregarProductoCosto($this->_item['codigo'], $this->_precio);
                                            $agrega++;
                                            // Modifico articulo con los nuevos datos
                                            $oArticuloVO->setId($this->_item['id']);
                                            $oArticuloModelo->find($oArticuloVO);
                                            $oArticuloVO->setCosto($this->_costo);
                                            $oArticuloVO->setPrecio($this->_precio);
                                            $oArticuloVO->setFechaPrecio(date('Y-m-d'));
                                            // Cambia el rótulo para imprimir
                                            // pone 3 (imprime el rótulo)
                                            if ($oArticuloVO->getRotulo() > 0) {
                                                $oArticuloVO->setRotulo(3);
                                                $imprimeRotulo++;
                                            }
                                            $oArticuloVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
                                            $this->_date = date('Y-m-d H:i:s');
                                            $oArticuloVO->setFechaAct($this->_date);
                                            $oArticuloModelo->update($oArticuloVO);
                                            $actualiza++;
                                            // Revisa si el artículo tiene condiciones para venta vigentes
                                            // Nota: un artículo puede tener varias condiciones de venta
                                            // Cambia el rótulo para Of/Promo
                                            $oArticuloCondiVO->setIdArticulo($oArticuloVO->getId());
                                            $oArticuloCondiVO->setFechaHasta(date('Y-m-d'));
                                            $this->_aArticulosCondi = $oArticuloCondiModelo->findAllIdArticulo($oArticuloCondiVO);
                                            // Si al artículo tiene condición/es
                                            if ($oArticuloCondiModelo->getCantidad()>0) {
                                                foreach ($this->_aArticulosCondi as $datos) {
                                                    $oArticuloCondiVO->setId($datos['id']);
                                                    // Lee el artículo con condición para tomar datos
                                                    $oArticuloCondiModelo->find($oArticuloCondiVO);
                                                    // Cambia estado del rótulo para descargar en PDF
                                                    $oArticuloCondiVO->setRotulo(3);
                                                    $oArticuloCondiVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
                                                    $this->_date = date('Y-m-d H:i:s');
                                                    $oArticuloCondiVO->setFechaAct($this->_date);
                                                    $oArticuloCondiModelo->update($oArticuloCondiVO);
                                                    $imprimeRotuloPromo++;
                                                    $this->_condicion = 'OFERTA/PROMO';
                                                    //$linea1 .= "<b style='color:green;'> *** en OFERTA/PROMO *** </b>";
                                                }
                                            }
                                            echo " - * Actualizo OK * <br>";
                                        } // fin compara tope agregar
                                        /**
                                         * Arma array para la tabla
                                         */
                                        $this->_aDatosTabla[] = [
                                            'id' => $this->_item['id'],
                                            'codigoB' => $this->_item['codigo_b'],
                                            'nombre' => $this->_item['nombre'],
                                            'presentacion' => $this->_item['presentacion'],
                                            'proveedorRef' => $this->_aProveedores[$this->_item['id_proveedor']],
                                            'rotulo' => $oArticuloVO->getRotulo(),
                                            'precioAnt' => $this->_item['precio'],
                                            'precio' => $this->_precio,
                                            'condicion' => $this->_condicion,
                                            'aumenta' => $this->_aumenta,
                                            'baja' => $this->_baja
                                        ];
                                        // Ordena el array de datos para la tabla por nombre del artículo
                                        $aNombres = array_column($this->_aDatosTabla, 'nombre');
                                        array_multisort($aNombres, SORT_ASC, $this->_aDatosTabla);
                                        // Incrementa cantidad del listado
                                        $this->_cantListado++;
                                    } // fin agrego precios en PLEX
                                } // fin precios diferentes
                            } else { // No encontró el producto para el proveedor
                                /**
                                 * Artículo con proveedor de referencia pero el producto no se encuentra
                                 * en la lista del proveedor.
                                 * Modifica el id del proveedor, la opción y la equivalencia.
                                 */
                                $noLeidos++;
                                //$linea1 = $cont." #".$this->_item['id']." [".$this->_item['codigo_b']."] ".$this->_item['nombre']." - ";
                                if ($this->_item['id_proveedor'] > 1) { // Tenía proveedores de ref lo cambio por 1
                                    // modificar artículo poner id_proveedor en 1, equivalencia en 0, opcion_prov en 0 y actualizar el registro
                                    //$linea1 .= "<b style='color:SlateBlue;'>NO LEI PRODUCTO - NO ACTUALIZA (MODIF. PROVEEDOR REF. = 1)</b>";
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
                                    //$linea1 .= "<b style='color:SlateBlue;'>NO LEI PRODUCTO - NO ACTUALIZA</b>";
                                }
                            } // fin NO encontró el producto en proveedores
                        } // fin sin condición en nombre (PR. ; PU. o PM.)
                        //echo $linea1."<br>";
                        //if ($linea2 != ' ') echo $linea2."<br>";
                    } // fin foreach de articulos x artículo
                    // Cierra conección con DB PLEX
                    DataBasePlex::closeInstance();

                    /*	No muestro totales en actualización
                     echo "------------------------------------- <br>";
                     echo "<b>TOTALES </b><br>";
                     echo "------------------------------------- <br>";
                     echo "<b>Actualiza: ".$actualiza." artículos</b><br>";
                     echo "Aumentos de Precios: ".$aumentos."<br>";
                     echo "---> MAS 30% : ".$masTreinta."<br>";
                     echo "---> MAS 20% : ".$masVeinte."<br>";
                     echo "---> MAS 10% : ".$masDiez."<br>";
                     echo "---> MAS  5% : ".$masCinco."<br>";
                     echo "---> MENOS o IGUAL 5% : ".$menosCinco."<br>";
                     echo "Bajas de Precios: ".$baja."<br>";
                     echo "------------------------------------- <br>";
                     echo "<b>NO ACTUALIZA</b><br>";
                     echo "PROMO en nombre PR.: ".$promoNoActualiza." (NO actualiza)<br>";
                     echo "PRECIOS UNIFICADOS PU.: ".$PUNoActualiza." (NO actualiza)<br>";
                     echo "Diferencia -2%: ".$diferenciaMenor." (NO actualiza)<br>";
                     echo "No actualizados: ".$noActualiza."<br>";
                     echo "No leidos (SIN PROV.): ".$noLeidos."<br>";
                     echo "------------------------------------- <br>";
                     echo "Imprime: ".$imprimeRotulo." Rótulos<br>";
                     echo "Imprime: ".$imprimeRotuloPromo." Rótulos de Art.c/Condiciones<br>";
                     echo "------------------------------------- <br>";
                     */
                } // fin artículos actualizables
                $oDatoVista->setDato(
                    '{evento}',
                    '<h3 style="color:green">- Modificó precios en PLEX -</h3>'
                );
                $oDatoVista->setDato('{cantActualizados}', $actualiza);
                $oDatoVista->setDato('{cantNoActualiza}', $noActualiza);
                $oDatoVista->setDato('{cantAumentos}', $aumentos);
                $oDatoVista->setDato('{cantBajas}', $baja);
                $oDatoVista->setDato('{cantNoLeidos}', $noLeidos);
                $oDatoVista->setDato('{cantPromo}', $promoNoActualiza);
                $oDatoVista->setDato('{cantPrecioUnificado}', $PUNoActualiza);
                $oDatoVista->setDato('{cantPrecioMaximo}', $PMNoActualiza);
                $oDatoVista->setDato('{cantImprimeRotulo}', $imprimeRotulo);
                $oDatoVista->setDato('{cantImprimeRotuloPromo}', $imprimeRotuloPromo);
                $oDatoVista->setDato('{cantInexistentesPlex}', $inexistentesPlex);
                $oDatoVista->setDato('{masTreinta}', $masTreinta);
                $oDatoVista->setDato('{masVeinte}', $masVeinte);
                $oDatoVista->setDato('{masDiez}', $masDiez);
                $oDatoVista->setDato('{masCinco}', $masCinco);
                $oDatoVista->setDato('{menosCinco}', $menosCinco);
                // Pone los iconos necesarios
                if (!isset($this->_aRangos['r1'])) {
                    $oDatoVista->setDato('{img1}', '<img class="img-center" src="/farma/imagenes/varias/times-circle-regular.svg" width="15" height="15">');
                } else {
                    $oDatoVista->setDato('{img1}', ' ');
                }
                if (!isset($this->_aRangos['r2'])) {
                    $oDatoVista->setDato('{img2}', '<img class="img-center" src="/farma/imagenes/varias/times-circle-regular.svg" width="15" height="15">');
                } else {
                    $oDatoVista->setDato('{img2}', ' ');
                }
                if (!isset($this->_aRangos['r3'])) {
                    $oDatoVista->setDato('{img3}', '<img class="img-center" src="/farma/imagenes/varias/times-circle-regular.svg" width="15" height="15">');
                } else {
                    $oDatoVista->setDato('{img3}', ' ');
                }
                if (!isset($this->_aRangos['r4'])) {
                    $oDatoVista->setDato('{img4}', '<img class="img-center" src="/farma/imagenes/varias/times-circle-regular.svg" width="15" height="15">');
                } else {
                    $oDatoVista->setDato('{img4}', ' ');
                }
                if (!isset($this->_aRangos['r5'])) {
                    $oDatoVista->setDato('{img5}', '<img class="img-center" src="/farma/imagenes/varias/times-circle-regular.svg" width="15" height="15">');
                } else {
                    $oDatoVista->setDato('{img5}', ' ');
                }
                if (!isset($this->_aRangos['r6'])) {
                    $oDatoVista->setDato('{img6}', '<img class="img-center" src="/farma/imagenes/varias/times-circle-regular.svg" width="15" height="15">');
                } else {
                    $oDatoVista->setDato('{img6}', ' ');
                }
                break;
                # ----> acción Listar
            case 'Listar':
                // carga el contenido html
                $oCargarVista->setCarga('datos', '/modulos/articulo/vista/actualizarPrecios.html');
                $oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
                $oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
                // ingresa los datos a representar en las alertas de la vista
                $oDatoVista->setDato('{alertaInfo}', 'Puede seleccionar rangos de aumentos a aplicar.');
                $oDatoVista->setDato('{alertaAdvertencia}', '<i>Listado informativo</i>, <b>NO ACTUALIZA</b> los precios.');
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Artículos - Listado Informativo (No actualiza precios)');
                $oDatoVista->setDato('{informacion}', '<p>Confirme para listar los artículos que se actualizan,</p><p>ver botones u opciones del menú.');
                // carga los eventos (botones)
                $this->_aEventos = [
                    "listarConfirmar" => "/includes/vista/botonListarConfirmar.html"
                ];
                $oCargarVista->setCarga('aEventos', $this->_aEventos);

                // arma la tabla de datos a representar
                $oArticuloModelo->count();
                $this->_cantidad = $oArticuloModelo->getCantidad();
                /**
                 * Cuenta los artículos actualizables según los siguientes datos:
                 *
                 * codigo > 9999900000 ($oArticuloVO->setCodigo())
                 * id_proveedor > 1 ($oArticuloVO->setIdProveedor())
                 * codigo_b > 0 (esta la condición en la consulta)
                 * estado = 1 ($oArticuloVO->setEstado())
                 */
                $oArticuloVO->setCodigo(9999900000); // mayor que
                $oArticuloVO->setIdProveedor(1); // mayor que: 1 - SIN IDENTIFICAR
                $oArticuloVO->setEstado(1); // igual que
                // codigo_b > 0
                $oArticuloModelo->countActualizables($oArticuloVO);
                $oDatoVista->setDato('{cantActualizables}', $oArticuloModelo->getCantidad());
                $oDatoVista->setDato('{cantActualizados}', 0);
                $oDatoVista->setDato('{cantNoActualiza}', 0);
                $oDatoVista->setDato('{cantAumentos}', 0);
                $oDatoVista->setDato('{cantBajas}', 0);
                $oDatoVista->setDato('{cantNoLeidos}', 0);
                $oDatoVista->setDato('{cantPromo}', 0);
                $oDatoVista->setDato('{cantPrecioUnificado}', 0);
                $oDatoVista->setDato('{cantPrecioMaximo}', 0);
                $oDatoVista->setDato('{cantImprimeRotulo}', 0);
                $oDatoVista->setDato('{cantImprimeRotuloPromo}', 0);
                $oDatoVista->setDato('{cantInexistentesPlex}', 0);
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                $oDatoVista->setDato('{masTreinta}', 0);
                $oDatoVista->setDato('{masVeinte}', 0);
                $oDatoVista->setDato('{masDiez}', 0);
                $oDatoVista->setDato('{masCinco}', 0);
                $oDatoVista->setDato('{menosCinco}', 0);
                $oDatoVista->setDato('{img1}', ' ');
                $oDatoVista->setDato('{img2}', ' ');
                $oDatoVista->setDato('{img3}', ' ');
                $oDatoVista->setDato('{img4}', ' ');
                $oDatoVista->setDato('{img5}', ' ');
                $oDatoVista->setDato('{img6}', ' ');
                $oDatoVista->setDato(
                    '{evento}',
                    '<h3 style="color:blue">Lista actualización - NO modifica PLEX</h3>'
                );

                break;
                # ----> acción Confirmar Listar
            case "ConfirmarL":
                // Recibe los datos por POST
                $this->_aRangos = $_POST['rangos']; // rangos de % de aumentos
                $topeAgrega = $_POST['cantActualizables']; // tope de artículos a actualizar
                // carga el contenido html
                $oCargarVista->setCarga('datos', '/modulos/articulo/vista/actualizarPrecios.html');
                $oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
                $oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
                // ingresa los datos a representar en las alertas de la vista
                $oDatoVista->setDato('{alertaSuceso}', 'Finalizó la acción solicitada con <b>EXITO !!!</b>.');
                $oDatoVista->setDato('{alertaAdvertencia}', '<i>Listado informativo</i>, <b>NO ACTUALIZÓ</b> los precios.');
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Artículos - Listado Informativo (No actualiza precios)');
                $oDatoVista->setDato('{informacion}', '<p>Finalizó la acción solicitada para los artículos,</p><p>ver botones u opciones del menú.');
                // arma la tabla de datos a representar
                $oDatoVista->setDato('{cantActualizables}', $_POST['cantActualizables']);
                $oDatoVista->setDato('{cantidad}', $_POST['cantidad']);
                // pone en cero los totalizadores y auxiliares varios
                $actualiza = $noActualiza = $promoNoActualiza = $PUNoActualiza = $aumentos = $baja = 0;
                $diferenciaMenor = $imprimeRotulo = $imprimeRotuloPromo = $noLeidos = $inexistentesPlex = 0;
                $cont = $agrega = $condi = $masTreinta = $masVeinte = $masDiez = $masCinco = $menosCinco = 0;
                $PMNoActualiza = 0;
                $linea1 = $linea2 = ''; // Lineas para mostrar datos de los artículo
                // carga array condición IVA
                $oAfipCondicionIvaVO = new AfipCondicionIvaVO();
                $oAfipCondicionIvaModelo = new AfipCondicionIvaModelo();
                $this->_items = $oAfipCondicionIvaModelo->findAll();
                foreach ($this->_items as $this->_item) {
                    $this->_aCondicionIva[$this->_item['codigo']] = $this->_item['alicuota'];
                }
                // carga array proveedores
                $oProveedorVO = new ProveedorVO();
                $oProveedorModelo = new ProveedorModelo();
                $this->_items = $oProveedorModelo->findAllProveedoresRef();
                foreach ($this->_items as $this->_item) {
                    $this->_aProveedores[$this->_item['id']] = $this->_item['inicial'];
                    $this->_aProveedoresLista[$this->_item['id']] = $this->_item['lista'];
                }

                /**
                 * Carga los artículos actualizables según los siguientes datos:
                 *
                 * codigo > 9999900000 ($oArticuloVO->setCodigo())
                 * id_proveedor > 1 ($oArticuloVO->setIdProveedor())
                 * codigo_b > 0 (esta la condición en la consulta)
                 * estado = 1 ($oArticuloVO->setEstado())
                 */
                $oArticuloVO->setCodigo(9999900000); // mayor que
                $oArticuloVO->setIdProveedor(1); // mayor que: 1 - SIN IDENTIFICAR
                $oArticuloVO->setEstado(1); // igual que (activo)
                // codigo_b > 0 // condición esta en la consulta (tiene que tener codigo de barra)
                $this->_items = $oArticuloModelo->findAllActualizables($oArticuloVO);

                /*
                 * Actualizo precios en PLEX y rótulos para la impresión
                 *
                 * 1 - Cambiar el codigo para el rótulo de precio del artículo
                 * 2 - Cambiar el código para el rótulo de of/promo del artículo
                 * 3 - Agregar en PLEX en producto_costo el nuevo precio
                 */


                //echo "**************************************<br>";
                //echo "   <b style='color:blue;'>LISTADO DE -PRUEBA- </b><br>";
                //echo "   <b style='color:blue;'>NO ACTUALIZA PRECIOS NI RÓTULOS </b><br>";
                //echo "**************************************<br>";
                //echo "Articulos actualizables: ".$topeAgrega."<br>";

                $this->_cantListado = 0;
                // verifica si hay artículos actualizables
                if ($oArticuloModelo->getCantidad()>0) { // Si hay artículos actualizables continúa

                    foreach ($this->_items as $this->_item) { // extraigo los datos de artículos x artículo
                        $cont++;
                        $this->_condicion = 'NO';
                        // Muestra datos del registro de artículo
                        //$linea1 = $cont." #".$this->_item['id']." [".$this->_item['codigo_b']."] ".$this->_item['nombre'].
                        //" (Rotulo: ".$this->_item['rotulo'].") ".
                        //" - $ ".$this->_item['costo']." (".$this->_item['margen']."%) Precio $".$this->_item['precio'];
                        //$linea2 = " Prov: ".$this->_item['id_proveedor']." (".$this->_aProveedores[$this->_item['id_proveedor']].") ";
                        /**
                         * SI es PROMO o PRECIO UNIFICADO (en nombre del artículo) no actualizo
                         */
                        if (strpos($this->_item['nombre'], 'PR.') !== false) { // Artículo con PROMO
                            //$linea2 .= "<b style='color:orange;'> <<< PROMO >>>> NO MODIFICA </b>";
                            $this->_condicion = "PROMO";
                            $promoNoActualiza++;
                        } elseif (strpos($this->_item['nombre'], 'PMAX.') !== false) { // Artículo con Precio Máximo
                            //$linea2 .= "<b style='color:orange;'> <<< PR.MAX. >>>> NO MODIFICA </b>";
                            $this->_condicion = "PR.MAX.";
                            $PMNoActualiza++;
                        } elseif (strpos($this->_item['presentacion'], '(PU.') !== false) {	// Artículo con PRECIO UNIFICADO
                            //$linea2 .= "<b style='color:orange;'> <<< PRECIO UNIFICADO >>>> NO MODIFICA </b>";
                            $this->_condicion = "P.UNIF";
                            $PUNoActualiza++;
                        } else {	// Artículo sin condición desde el nombre (PR. o PU.)
                            /**
                             * Calcula el precio con lista del proveedor de referencia
                             */
                            $oProductoVO->setIdArticulo($this->_item['id']);
                            $oProductoVO->setCodigoB(trim($this->_item['codigo_b']));
                            $oProductoVO->setIdProveedor($this->_item['id_proveedor']);
                            $oProductoModelo->findPorIdArticuloCodigoBProveedor($oProductoVO); // consulta producto ACTIVO
                            if ($oProductoModelo->getCantidad()>0) { // tiene producto activo en lista del proveedor de referencia
                                //$linea2 .= " -> lista $ ".$oProductoVO->getPrecio()." (".$this->_aProveedoresLista[$this->_item['id_proveedor']].") ";
                                // verifico que margen no sea CERO
                                $this->_margen = $this->_item['margen'];
                                if ($this->_margen == 0) {
                                    $this->_margen = 37;
                                }  // margen = 0% pongo 37% de prepo
                                // calculo precio con el costo del producto
                                // costo para guardar es "NETO" (sin  IVA)
                                if ($this->_aProveedoresLista[$this->_item['id_proveedor']] == 'F') {
                                    // Lista con precios finales
                                    $this->_costo = round($oProductoVO->getPrecio() / (1 + ($this->_aCondicionIva[$this->_item['codigo_iva']] / 100)), 2);
                                    $this->_precio = round(($oProductoVO->getPrecio() * (1 + ($this->_margen / 100))), 2);
                                } elseif ($this->_aProveedoresLista[$this->_item['id_proveedor']] == 'N') {
                                    // Lista con precios netos
                                    $this->_costo = round($oProductoVO->getPrecio(), 2);
                                    $this->_precio = round((($oProductoVO->getPrecio() * (1 + ($this->_aCondicionIva[$this->_item['codigo_iva']] / 100))) * (1 + ($this->_margen / 100))), 2);
                                } else {
                                    // Proveedor sin tipo de lista
                                    $this->_costo = 0;
                                    $this->_precio = 0;
                                    //$linea2 .= " ** FALTA TIPO DE LISTA ** ";
                                }
                                $this->_precioAct = $this->_precio;
                                /**
                                 * PRECIOS - VEO SI ACTUALIZO
                                 * 1 - Precios iguales sigo sin actualizar
                                 * 2 - Precios diferentes con diferencia menor al 2% sigo sin actualizar
                                 * 3 - Precios diferentes con diferencia mayor al 2%:
                                 *      Actualiza si está seleccionado el rango del % de aumento
                                 */
                                // Pone valores a los parámetros para ver si actualiza
                                $this->_diferenciaPrecio = $this->_porcentajeDiferencia = 0;
                                $this->_modifico = 'N';
                                $this->_precioAnt = $this->_item['precio'];

                                if ($this->_item['precio'] == $this->_precio) { // Precios iguales no actualiza
                                    $noActualiza++;
                                //$linea2 .= " Precio Nuevo $ ".$this->_precio." *** PRECIO IGUAL NO ACTUALIZO ***";
                                } else {
                                    // Precios diferentes hago comparaciones y veo si actualizo
                                    $this->_aumenta = $this->_baja = $this->_porcentaje = 0;
                                    /**
                                     * Con una diferencia igual o menor al 2% no modifico
                                     * los precios de los artículos
                                     */
                                    //$linea2 .= " -> lista $ ".$oProductoVO->getPrecio()." (".$this->_aProveedoresLista[$this->_item['id_proveedor']].") ";
                                    //$linea2 .= " Precio Nuevo $ ".$this->_precio." - ";
                                    // Armo totales para mostrar e indico si actualiza según rangos de % seleccionados
                                    if ($this->_precio > $this->_item['precio']) { // PRECIO CON AUMENTO
                                        // calculo diferencia de precio
                                        $this->_diferenciaPrecio = round(($this->_precio - $this->_item['precio']), 2);
                                        // calculo 2% del precio del artículo
                                        $this->_porcentajeDiferencia = round(($this->_item['precio']*2/100), 2);
                                        // Si diferencia es menor o igual a 2% no actualizo
                                        if ($this->_porcentajeDiferencia > $this->_diferenciaPrecio) {
                                            //$linea2 .= " DIF $".$this->_diferenciaPrecio." < 2% ";
                                            $diferenciaMenor++;
                                        } else {
                                            // Actualiza si el rango de % esta seleccionado - Nuevo precio mayor
                                            // que precio de artículo
                                            $aumentos++;
                                            // Calcula porcentaje de aumento
                                            $this->_porcentaje = round((($this->_precio - $this->_item['precio']) / $this->_item['precio'] * 100), 2);
                                            //$linea2 .= $this->_porcentaje."% ";
                                            $this->_aumenta = $this->_porcentaje;
                                            // Verifica rangos de % de aumentos
                                            if ($this->_porcentaje > 30) {
                                                if (isset($this->_aRangos['r5'])) {
                                                    $this->_modifico = 'S';
                                                }
                                                //$linea2 .= " -> MAS DE 30 % ";
                                                $masTreinta++;
                                            } elseif ($this->_porcentaje > 20) {
                                                if (isset($this->_aRangos['r4'])) {
                                                    $this->_modifico = 'S';
                                                }
                                                //$linea2 .= " -> MAS DE 20 % ";
                                                $masVeinte++;
                                            } elseif ($this->_porcentaje > 10) {
                                                if (isset($this->_aRangos['r3'])) {
                                                    $this->_modifico = 'S';
                                                }
                                                //$linea2 .= " -> MAS DE 10 % ";
                                                $masDiez++;
                                            } elseif ($this->_porcentaje > 5) {
                                                if (isset($this->_aRangos['r2'])) {
                                                    $this->_modifico = 'S';
                                                }
                                                //$linea2 .= " -> MAS DE 5 % ";
                                                $masCinco++;
                                            } else {
                                                if (isset($this->_aRangos['r1'])) {
                                                    $this->_modifico = 'S';
                                                }
                                                //$linea2 .= " -> MENOS O IGUAL A 5 % ";
                                                $menosCinco++;
                                            }
                                        }
                                        // Muestra si actualiza
                                        //if ($this->_modifico == 'S') $linea2 .= " +++ ACTUALIZA +++ ";
                                        //if ($this->_modifico == 'N') $linea2 .= " - NO ACTUALIZA - ";
                                    } else {
                                        // PRECIO CON BAJA
                                        // Actualiza - Nuevo precio menor que precio de artículo
                                        if ($this->_precio < $this->_item['precio']) {
                                            // Calcula porcentaje de baja
                                            $this->_porcentaje = round((($this->_precio - $this->_item['precio']) / $this->_item['precio'] * 100), 2);
                                            $this->_baja = $this->_porcentaje;
                                            // calculo diferencia de precio
                                            $this->_diferenciaPrecio = round(($this->_item['precio'] - $this->_precio), 2);
                                            // calculo 2% del precio del artículo
                                            $this->_porcentajeDiferencia = round(($this->_item['precio']*2/100), 2);
                                            // Si diferencia es menor o igual a 2% no actualizo
                                            if ($this->_porcentajeDiferencia > $this->_diferenciaPrecio) {
                                                //$linea2 .= " DIF $".$this->_diferenciaPrecio." < 2% ";
                                                $diferenciaMenor++;
                                            } else {
                                                //$this->_modifico = 'S';
                                                if (isset($this->_aRangos['r6'])) {
                                                    $this->_modifico = 'S';
                                                }
                                                //$linea2 .= " -BAJA PRECIO- ";
                                                $baja++;
                                            }
                                            // Muestra si actualiza
                                            //if ($this->_modifico == 'S') $linea2 .= " +++ ACTUALIZA +++ ";
                                            //if ($this->_modifico == 'N') $linea2 .= " - NO ACTUALIZA - ";
                                        }
                                    }

                                    // Artículos con modificación de precios agrego registros a PLEX
                                    if ($this->_modifico == 'S') {
                                        if ($agrega < $topeAgrega) {
                                            // Agrego registro a BD PLEX con precio
                                            // ...
                                            $agrega++;
                                            // Modifico articulo con los nuevos datos
                                            $oArticuloVO->setId($this->_item['id']);
                                            $oArticuloModelo->find($oArticuloVO);
                                            // ...
                                            // Cambia el rótulo para imprimir
                                            // pone 3 (imprime el rótulo)
                                            if ($oArticuloVO->getRotulo() > 0) {
                                                // ...
                                                $imprimeRotulo++;
                                            }
                                            // ...
                                            $actualiza++;
                                            // Revisa si el artículo tiene condiciones para venta vigentes
                                            // Nota: un artículo puede tener varias condiciones de venta
                                            // Cambia el rótulo para Of/Promo
                                            $this->_condicion = 'NO';
                                            $oArticuloCondiVO->setIdArticulo($oArticuloVO->getId());
                                            $oArticuloCondiVO->setFechaHasta(date('Y-m-d'));
                                            $this->_aArticulosCondi = $oArticuloCondiModelo->findAllIdArticulo($oArticuloCondiVO);
                                            if ($oArticuloCondiModelo->getCantidad()>0) {
                                                foreach ($this->_aArticulosCondi as $datos) {
                                                    // ...
                                                    $this->_condicion = 'OFERTA/PROMO';
                                                    $imprimeRotuloPromo++;
                                                    //$linea1 .= "<b style='color:green;'> *** en OFERTA/PROMO *** </b>";
                                                }
                                            }
                                        } // fin compara tope agregar
                                        /**
                                         * Arma array para la tabla
                                         */
                                        $this->_aDatosTabla[] = [
                                            'id' => $this->_item['id'],
                                            'codigoB' => $this->_item['codigo_b'],
                                            'nombre' => $this->_item['nombre'],
                                            'presentacion' => $this->_item['presentacion'],
                                            'proveedorRef' => $this->_aProveedores[$this->_item['id_proveedor']],
                                            'rotulo' => $oArticuloVO->getRotulo(),
                                            'precioAnt' => $this->_item['precio'],
                                            'precio' => $this->_precio,
                                            'condicion' => $this->_condicion,
                                            'aumenta' => $this->_aumenta,
                                            'baja' => $this->_baja
                                        ];
                                        // Ordena el array de datos para la tabla por nombre del artículo
                                        $aNombres = array_column($this->_aDatosTabla, 'nombre');
                                        array_multisort($aNombres, SORT_ASC, $this->_aDatosTabla);
                                        // Incrementa cantidad del listado
                                        $this->_cantListado++;
                                    } // fin agrego precios en PLEX
                                } // fin precios diferentes
                            } else { // No encontró el producto para el proveedor
                                /**
                                 * Artículo con proveedor de referencia pero el producto no se encuentra
                                 * en la lista del proveedor.
                                 * Modifica el id del proveedor, la opción y la equivalencia.
                                 */
                                $noLeidos++;
                                //$linea1 = $cont." #".$this->_item['id']." [".$this->_item['codigo_b']."] ".$this->_item['nombre']." - ";
                                //if ($this->_item['id_proveedor'] > 1) { // Tenía proveedores de ref lo cambio por 1
                                    // modificar artículo poner id_proveedor en 1, equivalencia en 0, opcion_prov en 0 y actualizar el registro
                                    //$linea1 .= "<b style='color:SlateBlue;'>NO LEI PRODUCTO - NO ACTUALIZA (MODIF. PROVEEDOR REF. = 1)</b>";
                                    // ...
                                //} else {
                                    //$linea1 .= "<b style='color:SlateBlue;>'NO LEI PRODUCTO - NO ACTUALIZA</b>";
                                //}
                            } // fin NO encontró el producto en proveedores
                        } // fin sin condición en nombre (PR. ; PU. o PM.)
                        //echo $linea1."<br>";
                        //if ($linea2 != ' ') echo $linea2."<br>";
                    } // fin foreach de articulos x artículo
                    // muestra array para tabla
                    //var_dump($this->_aDatosTabla);


                    //DataBasePlex::closeInstance(); // cierra conección con PLEX

                    /*	No muestro totales en actualización
                     echo "------------------------------------- <br>";
                     echo "<b>TOTALES </b><br>";
                     echo "------------------------------------- <br>";
                     echo "<b>Actualiza: ".$actualiza." artículos</b><br>";
                     echo "Aumentos de Precios: ".$aumentos."<br>";
                     echo "---> MAS 30% : ".$masTreinta."<br>";
                     echo "---> MAS 20% : ".$masVeinte."<br>";
                     echo "---> MAS 10% : ".$masDiez."<br>";
                     echo "---> MAS  5% : ".$masCinco."<br>";
                     echo "---> MENOS o IGUAL 5% : ".$menosCinco."<br>";
                     echo "Bajas de Precios: ".$baja."<br>";
                     echo "------------------------------------- <br>";
                     echo "<b>NO ACTUALIZA</b><br>";
                     echo "PROMO en nombre PR.: ".$promoNoActualiza." (NO actualiza)<br>";
                     echo "PRECIOS UNIFICADOS PU.: ".$PUNoActualiza." (NO actualiza)<br>";
                     echo "Diferencia -2%: ".$diferenciaMenor." (NO actualiza)<br>";
                     echo "No actualizados: ".$noActualiza."<br>";
                     echo "No leidos (SIN PROV.): ".$noLeidos."<br>";
                     echo "------------------------------------- <br>";
                     echo "Imprime: ".$imprimeRotulo." Rótulos<br>";
                     echo "Imprime: ".$imprimeRotuloPromo." Rótulos de Art.c/Condiciones<br>";
                     echo "------------------------------------- <br>";
                     */
                } // fin artículos actualizables
                $oDatoVista->setDato(
                    '{evento}',
                    '<h3 style="color:green">Listó actualización - NO modificó PLEX</h3>'
                );
                $oDatoVista->setDato('{cantActualizados}', $actualiza);
                $oDatoVista->setDato('{cantNoActualiza}', $noActualiza);
                $oDatoVista->setDato('{cantAumentos}', $aumentos);
                $oDatoVista->setDato('{cantBajas}', $baja);
                $oDatoVista->setDato('{cantNoLeidos}', $noLeidos);
                $oDatoVista->setDato('{cantPromo}', $promoNoActualiza);
                $oDatoVista->setDato('{cantPrecioUnificado}', $PUNoActualiza);
                $oDatoVista->setDato('{cantPrecioMaximo}', $PMNoActualiza);
                $oDatoVista->setDato('{cantImprimeRotulo}', $imprimeRotulo);
                $oDatoVista->setDato('{cantImprimeRotuloPromo}', $imprimeRotuloPromo);
                $oDatoVista->setDato('{cantInexistentesPlex}', $inexistentesPlex);
                $oDatoVista->setDato('{masTreinta}', $masTreinta);
                $oDatoVista->setDato('{masVeinte}', $masVeinte);
                $oDatoVista->setDato('{masDiez}', $masDiez);
                $oDatoVista->setDato('{masCinco}', $masCinco);
                $oDatoVista->setDato('{menosCinco}', $menosCinco);
                // Pone los iconos necesarios
                if (!isset($this->_aRangos['r1'])) {
                    $oDatoVista->setDato('{img1}', '<img class="img-center" src="/farma/imagenes/varias/times-circle-regular.svg" width="15" height="15">');
                } else {
                    $oDatoVista->setDato('{img1}', ' ');
                }
                if (!isset($this->_aRangos['r2'])) {
                    $oDatoVista->setDato('{img2}', '<img class="img-center" src="/farma/imagenes/varias/times-circle-regular.svg" width="15" height="15">');
                } else {
                    $oDatoVista->setDato('{img2}', ' ');
                }
                if (!isset($this->_aRangos['r3'])) {
                    $oDatoVista->setDato('{img3}', '<img class="img-center" src="/farma/imagenes/varias/times-circle-regular.svg" width="15" height="15">');
                } else {
                    $oDatoVista->setDato('{img3}', ' ');
                }
                if (!isset($this->_aRangos['r4'])) {
                    $oDatoVista->setDato('{img4}', '<img class="img-center" src="/farma/imagenes/varias/times-circle-regular.svg" width="15" height="15">');
                } else {
                    $oDatoVista->setDato('{img4}', ' ');
                }
                if (!isset($this->_aRangos['r5'])) {
                    $oDatoVista->setDato('{img5}', '<img class="img-center" src="/farma/imagenes/varias/times-circle-regular.svg" width="15" height="15">');
                } else {
                    $oDatoVista->setDato('{img5}', ' ');
                }
                if (!isset($this->_aRangos['r6'])) {
                    $oDatoVista->setDato('{img6}', '<img class="img-center" src="/farma/imagenes/varias/times-circle-regular.svg" width="15" height="15">');
                } else {
                    $oDatoVista->setDato('{img6}', ' ');
                }
                ArticuloActTabla::armaTabla($this->_aDatosTabla, $this->_accion, $this->_cantListado);
                $oCargarVista->setCarga('tabla', '/modulos/articulo/tabla.html');
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
        // Verifica si no hay precio para esta fecha
        $query = "SELECT count(*) FROM productoscostos WHERE IDProducto='$IDProducto' AND Fecha='$Fecha' AND TipoLista='P'";
        $result = mysqli_query($this->_con, $query) or die(mysqli_error($this->_con));
        $count = mysqli_fetch_array($result);
        mysqli_free_result($result);
        $this->_count = $count[0];
        // Actualiza, no hay precio para la fecha
        if ($this->_count==0) {
            $query = "INSERT INTO productoscostos
		    (IDProducto, TipoLista, Fecha, IDUsuario, Precio, Origen)
		    VALUES
		    ('$IDProducto', '$TipoLista', '$Fecha', '$IDUsuario', '$Precio', '$Origen')";
            $res = mysqli_query($this->_con, $query) or die(mysqli_error($this->_con));
            mysqli_free_result($res);
        }
    }
}
