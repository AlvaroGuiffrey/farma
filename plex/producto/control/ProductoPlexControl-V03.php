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
 * @version    3.0
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
 * @version    3.0
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
 */

class ProductoPlexControl
{
    #Propiedades
    private $_html;
    private $_accion;
    private $_items;
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
    private $_date;
    private $_estado;
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
        if (isset($_POST['bt_listar'])) {
            $this->_accion = "Listar";
        }
        if (isset($_POST['bt_actualizar'])) {
            $this->_accion = "Actualizar";
        }
        if (isset($_POST['bt_actualizar_conf'])) {
            $this->_accion = "ConfirmarAct";
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
        $this->_con = DataBasePlex::getInstance();
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
                $query = "SELECT count(*) FROM productos";
                $result = mysqli_query($this->_con, $query) or die(mysqli_error($this->_con));
                $count = mysqli_fetch_array($result);
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
                $query = "SELECT IDProducto, IDLaboratorio, IDRubro, Codebar, Producto, Presentacion, Activo, Costo, Margen, idTipoIVA, visible
								FROM productos";

                $result = mysqli_query($this->_con, $query) or die(mysqli_error($this->_con));
                $cuenta = mysqli_num_rows($result);
                $this->_cont = 1;
                while ($item = mysqli_fetch_array($result)) {
                    $this->_cantRegistros++;
                    $oArticuloVO->setCodigo(trim($item[0]));
                    $oArticuloModelo->findPorCodigo($oArticuloVO);

                    /**
                     * No existe artículo, se agrega
                     */
                    if ($oArticuloModelo->getCantidad()==0) { // No existe artículo se AGREGA
                        $codigo = trim($item[0]);
                        //echo $this->_cantRegistros." NO EXISTE --> Cod: ".$codigo;
                        $oArticuloVO->setCodigo($codigo);
                        $codigoM = " ";
                        //echo " - ".$codigoM;
                        $oArticuloVO->setCodigoM($codigoM);
                        if ($item[3] == ' ') {
                            $codigoB = 0;
                        } else {
                            $codigoB = trim($item[3]);
                        }
                        if ($item[3] == null) {
                            $codigoB=0;
                        }
                        //echo " - ".$codigoB;
                        $oArticuloVO->setCodigoB($codigoB);
                        $idMarca = trim($item[1]);
                        if ($idMarca == 0) {
                            $idMarca = 9009;
                        }
                        //echo " - ".$idMarca;
                        $oArticuloVO->setIdMarca($idMarca);
                        $idRubro = trim($item[2]);
                        //echo " - ".$idRubro;
                        $oArticuloVO->setIdRubro($idRubro);
                        $nombre = strtoupper(utf8_encode($item[4]));
                        //echo " - ".$nombre;
                        $oArticuloVO->setNombre($nombre);
                        if ($item[5]!=' ') {
                            $presentacion = strtoupper(utf8_encode($item[5]));
                        } else {
                            $presentacion = " ";
                        }
                        //echo " - ".$presentacion;
                        $oArticuloVO->setPresentacion($presentacion);
                        $comentario = " ";
                        //echo " - ".$comentario;
                        $oArticuloVO->setComentario($comentario);
                        // margen
                        if ($item[8]==null) {
                            $margen=0;
                        } else {
                            $margen = trim($item[8]);
                        }
                        $oArticuloVO->setMargen($margen);
                        //echo " - ".$margen;
                        $costo = trim($item[7]);
                        //echo " - ".$costo;
                        $oArticuloVO->setCosto($costo);
                        // leo tabla productoscostos de la DB Plex para buscar precio
                        $query = "SELECT IDProducto, Fecha, Precio FROM productoscostos WHERE IDProducto=".$oArticuloVO->getCodigo()." ORDER BY Fecha DESC LIMIT 1";
                        $res = mysqli_query($this->_con, $query) or die(mysqli_error($this->_con));
                        if (mysqli_num_rows($res)>0) { // si existe precio en tabla productoscostos de la DB Plex lo carga
                            $item = mysqli_fetch_array($res);
                            $oArticuloVO->setFechaPrecio($item[1]);
                            $oArticuloVO->setPrecio($item[2]);
                            mysqli_free_result($res);
                        } else { // si no existe precio pone $ 0 y fecha actual
                            $oArticuloVO->setPrecio(0);
                            $oArticuloVO->setFechaPrecio(date('Y-m-d'));
                        }

                        $stock = 0;
                        //echo " - ".$stock;
                        $oArticuloVO->setStock($stock);
                        // modificado en versión 2.0
                        if ($codigo > 9999900000) {
                            if ($idRubro>1) {
                                $rotulo = 1;
                            } else {
                                $rotulo = 0;
                            }
                        } else {
                            $rotulo = 0;
                        }
                        // fin modificación versión 2.0
                        //echo " - ".$rotulo;
                        $oArticuloVO->setRotulo($rotulo);
                        // cambia el rótulo para imprimir
                        if ($oArticuloVO->getRotulo() > 0) {
                            $oArticuloVO->setRotulo(3);
                        }
                        $idProveedor = 0;
                        $oArticuloVO->setIdProveedor($idProveedor);
                        $opcionProv = 0;
                        $oArticuloVO->setOpcionProv($opcionProv);
                        $equivalencia = 0;
                        $oArticuloVO->setEquivalencia($equivalencia);
                        // codigo de iva según tabla AFIP
                        if ($item[9]==1) { // iva 0%
                            $codigoIva = 3;
                        } else {
                            if ($item[9]==3) { // iva 21%
                                $codigoIva = 5;
                            } else {
                                $codigoIva = 0;
                            }
                        }
                        //echo " - ".$codigoIva;
                        $oArticuloVO->setCodigoIva($codigoIva);
                        $foto = " ";
                        $oArticuloVO->setFoto($foto);
                        // *** ESTADO *********
                        // código de estado "S" Activo - "N" Inactivo
                        if ($item[6]=="S") {
                            $estado=1;  // Activo = S
                        } else {
                            $estado=0;  // Activo = N u otra letra
                        }
                        // producto "oculto" en DB Plex
                        if ($item[10]==0) {
                            $estado = 0;
                        }
                        // ***** Fin estado ********
                        //echo " - ".$estado;
                        $oArticuloVO->setEstado($estado);
                        $idUsuarioAct = $oLoginVO->getIdUsuario();
                        //echo " - ".$idUsuarioAct;
                        $oArticuloVO->setIdUsuarioAct($idUsuarioAct);
                        $fechaAct = date('Y-m-d H:i:s');
                        //echo " - ".$fechaAct;
                        $oArticuloVO->setFechaAct($fechaAct);
                        // AGREGA UN ARTICULO NUEVO
                        $oArticuloModelo->insert($oArticuloVO);
                        //if ($oArticuloModelo->getCantidad()==0){
                        //echo "-> ERROR INSERT<br>";
                        //}
                        //echo "Agregó el ARTICULO<br>";
                        $this->_cantAgregados++;
                        $this->_cont++;
                    } else {

                /**
                 * Existe artículo verifica y modifica si fuera necesario
                 */

                        $this->_act = "N";
                        //echo $this->_cantRegistros." - Codigo:".$item[0];
                        // código de barra
                        if ($item[3] == ' ') {
                            $codigoB = 0;
                        } else {
                            $codigoB = trim($item[3]);
                        }
                        if ($item[3] == null) {
                            $codigoB=0;
                        }
                        $codB = $oArticuloVO->getCodigoB();
                        if ($codigoB != $codB) {
                            //echo " - ".$codigoB;
                            $oArticuloVO->setCodigoB($codigoB);
                            $this->_act = 'S';
                        }
                        // indice de la marca
                        $idMarca = $oArticuloVO->getIdMarca();
                        if (trim($item[1]) == 0) {
                            $marcaId = 9009;
                        } else {
                            $marcaId = trim($item[1]);
                        }
                        if ($idMarca != $marcaId) {
                            //echo "Marca: ".$idMarca."/".$marcaId;
                            $idMarca = $marcaId;
                            $oArticuloVO->setIdMarca($idMarca);
                            $this->_act = 'S';
                        }
                        // índice del rubro
                        $idRubro = $oArticuloVO->getIdRubro();
                        if ($idRubro != trim($item[2])) {
                            //echo "Rubro: ".$idRubro."/".$item[2];
                            $idRubro = trim($item[2]);
                            $oArticuloVO->setIdRubro($idRubro);
                            $this->_act = 'S';
                        }
                        // nombre del artículo
                        $nombre = $oArticuloVO->getNombre();
                        $nombreP = substr(strtoupper(utf8_encode($item[4])), 0, 40);
                        if ($nombre != $nombreP) {
                            //echo "Nombre: ".$nombre." (".$nombreP.") ";
                            $nombre = $nombreP;
                            $oArticuloVO->setNombre($nombre);
                            $this->_act = 'S';
                        }
                        // presentación
                        $presentacion = $oArticuloVO->getPresentacion();
                        if ($item[5]!=' ') {
                            $presentacionP = substr(strtoupper(utf8_encode($item[5])), 0, 40);
                        } else {
                            $presentacionP = " ";
                        }
                        if ($presentacion != $presentacionP) {
                            //echo "Pres: ".$presentacion." (".$presentacionP.") ";
                            $presentacion = $presentacionP;
                            $oArticuloVO->setPresentacion($presentacion);
                            $this->_act = 'S';
                        }
                        // margen
                        if ($oArticuloVO->getCodigo()>9999900000) {
                            $margen = $oArticuloVO->getMargen();
                            if (trim($item[8])==null) {
                                $item[8]=0;
                            }
                            if ($margen != trim($item[8])) {
                                $margen = trim($item[8]);
                                $oArticuloVO->setMargen($margen);
                                //echo "Margen".$margen."/".$item[8];
                                $margen = trim($item[8]);
                                $oArticuloVO->setMargen($margen);
                                $this->_act = 'S';
                            }
                        }
                        // codigo IVA
                        $codigoIva = $oArticuloVO->getCodigoIva();
                        if ($item[9]==1) { // iva 0%
                            $codigoIvaP = 3;
                        } else {
                            if ($item[9]==3) { // iva 21%
                                $codigoIvaP = 5;
                            } else {
                                $codigoIvaP = 0;
                            }
                        }
                        if ($codigoIva != $codigoIvaP) {
                            $codigoIva = $codigoIvaP;
                            $oArticuloVO->setCodigoIva($codigoIva);
                            $this->_act = 'S';
                        }
                        // estado
                        $estado = $oArticuloVO->getEstado();

                        if ($item[6]=="S") {
                            $estadoP=1;  // Activo = S
                        } else {
                            $estadoP=0;  // Activo = N u otra letra
                        }
                        // producto con visibilidad oculta en DB Plex
                        if ($item[10]==0) {
                            $estadoP = 0;
                        } // 0 oculto, 1 visible
                        if ($estado != $estadoP) {
                            $estado = $estadoP;
                            $oArticuloVO->setEstado($estado);
                            $this->_act = 'S';
                        }

                        // precio del artículo
                        // busco el último precio del artículo en PLEX
                        $query = "SELECT IDProducto, Precio, Fecha FROM productoscostos WHERE IDProducto=".$oArticuloVO->getCodigo()." ORDER BY Fecha DESC LIMIT 1";
                        $res1 = mysqli_query($this->_con, $query) or die(mysqli_error($this->_con));
                        $count = mysqli_fetch_array($res1);
                        mysqli_free_result($res1);
                        $precio = $count[1];
                        $codigo = $count[0];
                        if ($codigo == $oArticuloVO->getCodigo()) { // si es el mismo código verifica precios
                            if ($precio != $oArticuloVO->getPrecio()) {
                                //echo $codigo." -> Precio PLEX $ ".$precio." de fecha: ".$count[2]."<br>";
                                //echo "       Articulo Precio $ ".$oArticuloVO->getPrecio()." de fecha: ".$oArticuloVO->getFechaPrecio()."<br>";
                                $oArticuloVO->setPrecio($precio);
                                $oArticuloVO->setFechaPrecio($count[2]);
                                $this->_act = 'S';
                            }
                        }

                        // datos de la actualización
                        if ($this->_act == 'S') {
                            $idUsuarioAct = $oLoginVO->getIdUsuario();
                            $oArticuloVO->setIdUsuarioAct($idUsuarioAct);
                            $fechaAct = date('Y-m-d H:i:s');
                            $oArticuloVO->setFechaAct($fechaAct);
                            /**
                             * Update del artículo modificado con datos del producto
                             * de PLEX
                             */

                            $oArticuloModelo->update($oArticuloVO);
                            //echo " - ACTUALIZADO ---> ".$oArticuloVO->getCodigo()."<br>";
                            //echo " ----------------------------------------------<br>";
                            $this->_cantActualizados++;
                        }

                        //echo "EXISTE y actualizo: ".$this->_act."<br>";
                        $this->_cont++;
                    }
                }

                mysqli_free_result($result);

                /**
                 * Verifica bajas de Productos en DB PLEX
                 */

                $this->_cantBajados = 0;
                $this->_cont = 0;
                //$conx = DataBasePlex::getInstance(); // conecto a DB PLEX

                // lee todos los artículos de la tabla articulos
                $this->_items = $oArticuloModelo->findAll();
                //echo "Controlo PLEX para borrar artículos<br>";
                if ($oArticuloModelo->getCantidad()>0) { // Si hay artículos continúa
                    //echo "Son: ".$oArticuloModelo->getCantidad()."<br>";
                    foreach ($this->_items as $this->_item) { // extraigo los datos de artículos x artículo
                        $this->_cont++;
                        //echo $this->_item['id']." - ".$this->_item['codigo']." - ".$this->_item['nombre']."- ";
                        // leo plex haber si existe producto
                        $query = "SELECT COUNT(*)
						               FROM productos
                                       WHERE IDProducto=".trim($this->_item['codigo']);
                        $result = mysqli_query($this->_con, $query) or die(mysqli_error($this->_con));
                        $count = mysqli_fetch_array($result);
                        //echo " Leo: ".$count[0]." ";
                        if ($count[0] == 0) { // No hay productos en PLEX
                            $oArticuloVO->setId($this->_item['id']);

                            $oArticuloModelo->delete($oArticuloVO);
                            if ($oArticuloModelo->getCantidad()==0){
                                //echo "---------------> ERROR BAJA<br>";
                            } else {
                                //echo "OK<br>";
                            }
                            $this->_cantBajados++;
                        //} else {
                            //echo " ** EXISTE ***<br>";
                        }
                    } // Fin foreach del array de artículos
                } // Fin si hay artículos en la consulta

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
