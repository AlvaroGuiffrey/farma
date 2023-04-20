<?php
/**
 * Archivo de la clase control del módulo plex/producto.
 *
 * Archivo de la clase control del módulo plex/producto.
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
 * @version    4.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      File available since Release 1.0
 */

/**
 * Clase control del módulo plex/producto.
 *
 * Clase control del módulo plex/producto que permite realizar
 * operaciones sobre la tabla plex/productos (CRUD y otras)
 * según la categoría asignada al usuario.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    4.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 *
 * Síntesis modificaciones:
 * Versión 2.0 - en altas de artículos por productos nuevos solo se pone rótulo a los
 * productos con "código propio > 999990000" y que el rubro sea > 1 (nunca pone a
 * medicamentos).
 *
 * Versión 3.0 - se revisa pasar a inactivo artículos en db farma por productos
 * ocultos o de baja en db plex.
 *
 * Versión 4.0 - se cambia la instancia de la db plex, se abre y cierra la conexión
 * cada vez que se usa; se optimizaron los if; y se utilizan arrays para eliminar
 * los artículos que se dieron de baja en plex.
 */

class ProductoPlexControl
{
    #Propiedades
    private $_html;
    private $_accion;
    private $_items = array();
    private $_aCodigosArt = array();
    private $_itemsPlex = array();
    private $_aCodigosPlex = array();
    private $_aDifCodigos = array();
    private $_cantidad;
    private $_cantProductos;
    private $_cantArticulos;
    private $_cantAgregar;
    private $_cantBajar;
    private $_cantActualizados;
    private $_cantAgregados;
    private $_cantBajados;
    private $_cantRegistros;
    private $_cont;
    private $_act;
    private $_id;
    private $_codigo;
    private $_codigoM;
    private $_codigoB;
    private $_idMarca;
    private $_idRubro;
    private $_nombre;
    private $_presentacion;
    private $_comentario;
    private $_margen;
    private $_costo;
    private $_fechaPrecio;
    private $_precio;
    private $_stock;
    private $_rotulo;
    private $_idProveedor;
    private $_opcionProv;
    private $_equivalencia;
    private $_codigoIva;
    private $_foto;
    private $_estado;
    private $_date;
    private $_con;
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
        Clase::define('DataBasePlex');
        Clase::define('ProductoPlexTabla');

        // Chequea login
        $oLoginControl = new LoginControl();
        $oLoginControl->chequearLogin($oLoginVO);
        $this->_accion = $accion;
        $this->accionControl($oLoginVO);
    }

    /**
     * Nos permite ejecutar las acciones del módulo
     * plex/producto del sistema, de acuerdo a la categoría del
     * usuario.
     */
    private function accionControl($oLoginVO)
    {
        // Carga acciones del formulario
        if (isset($_POST['bt_listar'])) $this->_accion = "Listar";
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
                "botonListar" => "/includes/vista/botonListar.html",
                "botonActualizar" => "/includes/vista/botonActualizar.html"
        );
        $oCargarVista->setCarga('aAcciones', $this->_aAcciones);
        // Ingresa los datos a representar en el html de la vista
        $oDatoVista = new DatoVista();
        $oDatoVista->setDato('{tituloPagina}', 'Productos (PLEX)');
        $oDatoVista->setDato('{usuario}', $oLoginVO->getAlias());
        $oDatoVista->setDato('{tituloBadge}', 'Productos ');
        // Alertas

        // Carga el contenido html y datos según la acción

        // Instancia las clases del modelo
        $oArticuloVO = new ArticuloVO();
        $oArticuloModelo = new ArticuloModelo();

        // Selector de acciones
        switch ($this->_accion) {
            # ----> acción Iniciar
            case 'Iniciar':
                // carga el contenido html

                // carga las alertas html
                $oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
                // ingresa los datos a representar en las alertas de la vista
                $oDatoVista->setDato('{alertaInfo}', 'Seleccione una acción con los botones.');
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Productos - (PLEX)');
                $oDatoVista->setDato('{informacion}', '<p>Debe seleccionar una acción a realizar</p>
														<p>sobre la tabla plex/productos.</p>');
                // carga los eventos (botones)

                // arma la tabla de datos a representar
                $this->_con = DataBasePlex::getInstance();
                $query = "SELECT count(*) FROM productos";
                $result = mysqli_query($this->_con, $query) or die(mysqli_error($this->_con));
                $count = mysqli_fetch_array($result);
                mysqli_free_result($result);
                DataBasePlex::closeInstance();
                $this->_cantidad = $count[0];
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                break;

                # ----> acción Listar
            case 'Listar':
                // carga el contenido html
                $oCargarVista->setCarga('contenido', '/includes/vista/listar.html');
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Listado de Productos (PLEX)');
                $oDatoVista->setDato('{informacion}', '<p>Listado de todos los productos de la base PLEX.</p>
														<p>También puede seleccionar otras acciones para los</p>
														<p>productos, ver botones.</p>');
                // arma la tabla de datos a representar
                $this->_con = DataBasePlex::getInstance();
                $query = "SELECT count(*) FROM productos";
                $result = mysqli_query($this->_con, $query) or die(mysqli_error($this->_con));
                $count = mysqli_fetch_array($result);
                mysqli_free_result($result);
                $this->_cantidad = $count[0];
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                ProductoPlexTabla::armaTabla($this->_cantidad, $this->_accion, $oLoginVO);
                DataBasePlex::closeInstance();
                $oCargarVista->setCarga('tabla', '/plex/producto/tabla.html');
                break;
            # ----> acción Actualizar
            case 'Actualizar':
                // carga el contenido html
                $oCargarVista->setCarga('datos', '/plex/producto/vista/actualizarDatos.html');

                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Actualiza Artículos con Productos (PLEX)');
                $oDatoVista->setDato('{informacion}', '<p>Actualiza artículos con los productos de la base PLEX.</p>
														<p>También puede seleccionar otras acciones para los</p>
														<p>productos, ver botones.</p>');

                // Ingresa los datos a representar en el html de la vista
                $this->_con = DataBasePlex::getInstance();
                $query = "SELECT count(*) FROM productos";
                $result = mysqli_query($this->_con, $query) or die(mysqli_error($this->_con));
                $count = mysqli_fetch_array($result); // un solo dato
                mysqli_free_result($result);
                $this->_cantidad = $count[0];
                $this->_cantProductos = $count[0];
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                $oDatoVista->setDato('{cantProductos}', $this->_cantProductos);
                DataBasePlex::closeInstance();
                $oArticuloModelo->count();
                $this->_cantArticulos = $oArticuloModelo->getCantidad();
                $oDatoVista->setDato('{cantArticulos}', $this->_cantArticulos);
                $this->_cantRegistros = 0;
                $oDatoVista->setDato('{cantRegistros}', $this->_cantRegistros);
                $this->_cantAgregados = 0;
                $oDatoVista->setDato('{cantAgregados}', $this->_cantAgregados);
                $this->_cantBajados = 0;
                $oDatoVista->setDato('{cantBajados}', $this->_cantBajados);
                $this->_cantActualizados = 0;
                $oDatoVista->setDato('{cantActualizados}', $this->_cantActualizados);
                // carga las alertas html
                $oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
                //$oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
                // ingresa los datos a representar en las alertas de la vista
                $oDatoVista->setDato('{alertaInfo}', 'Actualiza tabla Artículos con tabla Productos de PLEX, confirme la acción.
                                                       <b>Demora unos minutos!!!</b>');
                //$oDatoVista->setDato('{alertaPeligro}',  'La tarea demanda más de <ins>90 minutos</ins>, <b>NO APAGUE</b> el ordenar hasta que finalice.');
                // carga los eventos (botones)
                $this->_aEventos = array(
                            "actualizarConf" => "/includes/vista/botonActualizarConf.html",
                );
                $oCargarVista->setCarga('aEventos', $this->_aEventos);
                break;
            # ----> acción Confirmar Actualizar
            case 'ConfirmarAct':
                // recibe datos por POST
                $this->_cantProductos = $_POST['cantProductos'];
                $this->_cantidad = $_POST['cantProductos'];
                $this->_cantArticulos = $_POST['cantArticulos'];
                // carga el contenido html
                $oCargarVista->setCarga('datos', '/plex/producto/vista/actualizarDatos.html');
                // carga las alertas html

                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Actualizó Articulos con Productos (PLEX)');
                $oDatoVista->setDato('{informacion}', '<p>Actualizó artículos con los productos de la base PLEX.</p>
														<p>También puede seleccionar otras acciones para los</p>
														<p>productos, ver botones.</p>');
                // Ingresa los datos a representar en el html de la vista
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                $oDatoVista->setDato('{cantProductos}', $this->_cantProductos);
                $oDatoVista->setDato('{cantArticulos}', $this->_cantArticulos);

                // Actualiza los datos de artículos con productos
                $this->_cantAgregados = $this->_cantActualizados = $this->_cantBajados = 0;
                $this->_cont = $this->_cantRegistros = 0;

                // lee la tabla productos completa (activos y ocultos)
                $this->_con = DataBasePlex::getInstance();
                $query = "SELECT IDProducto, IDLaboratorio, IDRubro, Codebar, Producto, Presentacion, Activo, Costo, Margen, idTipoIVA, visible
								FROM productos";

                $result = mysqli_query($this->_con, $query) or die(mysqli_error($this->_con));
                $cuenta = mysqli_num_rows($result);
                $this->_cont = 1;
                while ($item = mysqli_fetch_array($result)) {
                    $this->_cantRegistros++;
                    // Carga los datos de PLEX en variables
                    $this->_codigo = trim($item[0]);
                    $this->_codigoM = " ";
                    $this->_codigoB = ($item[3] == ' ' || $item[3] == null) ? 0 : trim($item[3]);
                    $this->_idMarca = (trim($item[1]) == 0) ? 9009 : trim($item[1]);
                    $this->_idRubro = trim($item[2]);
                    $this->_nombre = substr(strtoupper(utf8_encode($item[4])), 0, 40);
                    $this->_presentacion = ($item[5]!=' ') ? substr(strtoupper(utf8_encode($item[5])), 0, 40) : " ";
                    $this->_comentario = " ";
                    $this->_margen = ($item[8] == null) ? 0 : trim($item[8]);
                    $this->_costo = trim($item[7]);
                    $this->_stock = 0;
                    $this->_rotulo = 0;
                    $this->_idProveedor = 0;
                    $this->_opcionProv = 0;
                    $this->_equivalencia = 0;
                    if ($item[9]==1) { // iva 0%
                        $this->_codigoIva = 3;
                    } elseif ($item[9]==3) { // iva 21%
                        $this->_codigoIva = 5;
                    } else {
                            $this->_codigoIva = 0;
                    }
                    $this->_foto = " ";
                    // código de estado "S" Activo - "N" Inactivo
                    $this->_estado = ($item[6]=="S") ? 1 : 0;
                    // producto "oculto" en DB Plex
                    if ($item[10]==0) $this->_estado = 0;

                    // leo tabla productoscostos de la DB Plex para buscar datos precio
                    $query = "SELECT IDProducto, Fecha, Precio FROM productoscostos WHERE IDProducto=".$this->_codigo
                                ." ORDER BY Fecha DESC LIMIT 1";
                    $res = mysqli_query($this->_con, $query) or die(mysqli_error($this->_con));
                    if (mysqli_num_rows($res)> 0) { // si existe precio en tabla productoscostos de la DB Plex lo carga
                        $itemP = mysqli_fetch_array($res);
                        $this->_fechaPrecio = $itemP[1];
                        $this->_precio = $itemP[2];
                        //echo $this->_fechaPrecio." - $ ".$this->_precio."<br>";
                        mysqli_free_result($res);
                    } else { // si no existe precio pone $ 0 y fecha actual
                        $this->_fechaPrecio = date('Y-m-d');
                        $this->_precio = 0;
                    }

                    /**
                    * Consulto artículo por código:
                    * Si existe comparo datos para actualizar y si no existe lo agrego
                    */
                    $oArticuloVO->setCodigo($this->_codigo);
                    $oArticuloModelo->findPorCodigo($oArticuloVO);

                    /**
                     * No existe artículo, se agrega
                     */
                    if ($oArticuloModelo->getCantidad() == 0) {
                        $oArticuloVO->setCodigo($this->_codigo);
                        $oArticuloVO->setCodigoM($this->_codigoM);
                        $oArticuloVO->setCodigoB($this->_codigoB);
                        $oArticuloVO->setIdMarca($this->_idMarca);
                        $oArticuloVO->setIdRubro($this->_idRubro);
                        $oArticuloVO->setNombre($this->_nombre);
                        $oArticuloVO->setPresentacion($this->_presentacion);
                        $oArticuloVO->setComentario($this->_comentario);
                        $oArticuloVO->setMargen($this->_margen);
                        $oArticuloVO->setCosto($this->_costo);
                        $oArticuloVO->setFechaPrecio($this->_fechaPrecio);
                        $oArticuloVO->setPrecio($this->_precio);
                        $oArticuloVO->setStock($this->_stock);
                        // Versión 4.0 - si código es > 999990000 agrego rótulo siempre y pone para imprimir
                        if ($this->_codigo > 9999900000) {
                            $this->_rotulo = ($this->_idRubro > 1) ? 3 : 0;
                        }
                        $oArticuloVO->setRotulo($this->_rotulo);
                        $oArticuloVO->setIdProveedor($this->_idProveedor);
                        $oArticuloVO->setOpcionProv($this->_opcionProv);
                        $oArticuloVO->setEquivalencia($this->_equivalencia);
                        $oArticuloVO->setCodigoIva($this->_codigoIva);
                        $oArticuloVO->setFoto($this->_foto);
                        $oArticuloVO->setEstado($this->_estado);
                        $oArticuloVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
                        $oArticuloVO->setFechaAct(date('Y-m-d H:i:s'));
                        // *** AGREGA ARTICULO NUEVO ***
                        $oArticuloModelo->insert($oArticuloVO);
                        //$obsAgrega = ($oArticuloModelo->getCantidad()==0) ? "-> ERROR INSERT<br>" : "-> Agregó el ARTICULO<br>";
                        //$obsAgrega = " <- *** AGREGA *** <br>";
                        //echo $this->_codigo." ".$obsAgrega;
                        $this->_cantAgregados++;
                        $this->_cont++;
                    } else {
                    /**
                    * Existe artículo verifica y modifica si fuera necesario
                    */
                        $this->_act = "N";
                        // código de barra
                        if ($this->_codigoB != $oArticuloVO->getCodigoB()) {
                            $oArticuloVO->setCodigoB($this->_codigoB);
                            $this->_act = 'S';
                        }
                        // índice de la marca
                        if ($this->_idMarca != $oArticuloVO->getIdMarca()) {
                            $oArticuloVO->setIdMarca($this->_idMarca);
                            $this->_act = 'S';
                        }
                        // índice del rubro
                        if ($this->_idRubro != $oArticuloVO->getIdRubro()) {
                            $oArticuloVO->setIdRubro($this->_idRubro);
                            $this->_act = 'S';
                        }
                        // nombre del artículo
                        if ($this->_nombre != $oArticuloVO->getNombre()) {
                            $oArticuloVO->setNombre($this->_nombre);
                            $this->_act = 'S';
                        }
                        // presentación
                        if ($this->_presentacion != $oArticuloVO->getPresentacion()) {
                            $oArticuloVO->setPresentacion($this->_presentacion);
                            $this->_act = 'S';
                        }
                        // margen
                        if ($oArticuloVO->getCodigo() > 9999900000) {
                            if ($this->_margen != $oArticuloVO->getMargen()) {
                                $oArticuloVO->setMargen($this->_margen);
                                $this->_act = 'S';
                            }
                        }
                        // codigo IVA
                        if ($this->_codigoIva != $oArticuloVO->getCodigoIva()) {
                            $oArticuloVO->setCodigoIva($this->_codigoIva);
                            $this->_act = 'S';
                        }
                        // estado
                        if ($this->_estado != $oArticuloVO->getEstado()) {
                            $oArticuloVO->setEstado($this->_estado);
                            $this->_act = 'S';
                        }
                        // precio del artículo
                        if ($this->_precio != $oArticuloVO->getPrecio()) {
                            $oArticuloVO->setPrecio($this->_precio);
                            $oArticuloVO->setFechaPrecio($this->_fechaPrecio);
                            $this->_act = 'S';
                        }

                        // *** ACTUALIZA EL ARTÍCULO ***
                        if ($this->_act == 'S') {
                            $oArticuloVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
                            $oArticuloVO->setFechaAct(date('Y-m-d H:i:s'));
                            /**
                             * Update del artículo modificado con datos del producto de PLEX
                             */
                            $oArticuloModelo->update($oArticuloVO);
                            //$obsModifica = ($oArticuloModelo->getCantidad()==0) ? "-> ERROR MODI<br>" : " -> Modificó el ARTICULO<br>";
                            //$obsModifica = " <- MODIFICA <br>";
                            //echo $this->_codigo." ".$obsModifica;
                            $this->_cantActualizados++;
                        }
                        $this->_cont++;
                    } // Fin if else artículo
                } // Fin while productos plex
                mysqli_free_result($result);

                /**
                 * Verifica bajas de Productos en DB PLEX
                 */

                $this->_cantBajados = 0;
                $this->_cont = 0;
                // lee todos los artículos de la tabla articulos
                $this->_items = $oArticuloModelo->findAllAlonCodigo();
                // Arma el array de códigos de la tabla artículos db farma
                foreach ($this->_items as $item) {
                    $this->_aCodigosArt[] = $item[0];
                }
                //var_dump($this->_aCodigosArt);
                // leo plex haber si existe producto
                $query = "SELECT IDProducto FROM productos ORDER BY IDProducto ASC";
                $result = mysqli_query($this->_con, $query) or die(mysqli_error($this->_con));
                $this->_itemsPlex = mysqli_fetch_all($result); // todos los registros
                mysqli_free_result($result);
                // Arma el array de códigos de la tabla productos db Plex
                foreach ($this->_itemsPlex as $item) {
                    $this->_aCodigosPlex[] = $item[0];
                }
                //var_dump($this->_aCodigosPlex);
                //echo "-------------------------------------- <br>";
                //echo " ARTICULOS PARA BAJA <br>";
                // Arma array con diferencias para hacer las bajas
                $this->_aDifCodigos = array_diff($this->_aCodigosArt, $this->_aCodigosPlex);
                // Muestra las bajas que se realizarán
                //var_dump($this->_aDifCodigos);
                //echo "<br> -------------------------------------- <br>";
                //echo "Son: ".count($this->_aDifCodigos)."<br>";
                // Si hay artículos para baja continúa
                if (count($this->_aDifCodigos) > 0) {
                    // Recorre el array con los códigos para BAJA de artículos
                    foreach ($this->_aDifCodigos as $clave => $valor) {
                        $this->_cont++;
                        $oArticuloVO->setCodigo($valor);
                        //echo $valor." -> Art: ".$oArticuloVO->getCodigo()."<br>";
                        // Busco el artículo por código
                        $oArticuloModelo->findPorCodigo($oArticuloVO);
                        //echo $this->_cont." -> id:[".$oArticuloVO->getId()."] ".$oArticuloVO->getCodigo()." - ";
                        // Elimino el artículo con el id obtenido
                        $oArticuloModelo->delete($oArticuloVO);
                        /*
                        if ($oArticuloModelo->getCantidad()==0){
                            echo " ---> ERROR BAJA <br>";
                        } else {
                            echo " OK BAJA <br>";
                        }
                        */
                        $this->_cantBajados++;
                    } // Fin foreach del array de artículos para BAJA
                } // Fin si hay artículos para BAJA en la consulta
                // Prueba de Bajas PLEX
                //echo "FINALICE: ".date('Y-m-d H:i:s')."<br>";
                // ------------------------

                $oDatoVista->setDato('{cantRegistros}', $this->_cantRegistros);
                $oDatoVista->setDato('{cantAgregados}', $this->_cantAgregados);
                $oDatoVista->setDato('{cantBajados}', $this->_cantBajados);
                $oDatoVista->setDato('{cantActualizados}', $this->_cantActualizados);
                DataBasePlex::closeInstance();
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
