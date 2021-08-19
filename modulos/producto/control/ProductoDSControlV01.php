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
 * @version    3.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      File available since Release 1.0
 */

/**
 * Clase control del módulo producto.
 *
 * Clase control del módulo producto que permite realizar
 * operaciones sobre la tabla productos (CRUD y otras)
 * según la categoría asignada al usuario.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    3.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
/**
 * Modificación para tomar más de un código de barra por producto,
 * los carga como otros productos en nuestra DB
 * @author     Alvaro Guiffrey
 * @date       17/10/2017
 *
 */
class ProductoDSControl
{
    #Propiedades
    private $_html;
    private $_accion;
    private $_items;
    private $_item;
    private $_aProductos;
    private $_productos;
    private $_producto;
    private $_cantidad;
    private $_cantAgregados;
    private $_cantActualizados;
    private $_cantEliminados;
    private $_cantDuplicados;
    private $_cantDupliCodigoP;
    private $_cantCodigoBRep;
    private $_cantCodigoPRep;
    private $_cantUpdate;
    private $_renglonDesde;
    private $_id;
    private $_idProveedor;
    private $_codigoP;
    private $_razonSocial;
    private $_estado;
    private $_precio;
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
        Clase::define('ProductoModelo');
        Clase::define('ProductoProvModelo');
        Clase::define('ArticuloModelo');
        Clase::define('ProveedorModelo');
        
        
        // Chequea login
        $oLoginControl = new LoginControl();
        $oLoginControl->chequearLogin($oLoginVO);
        $this->_accion = $accion;
        $this->accionControl($oLoginVO);
    }
    
    /**
     * Nos permite ejecutar las acciones del módulo
     * producto del sistema, de acuerdo a la categoría del
     * usuario.
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
        $oDatoVista->setDato('{tituloPagina}', 'Productos ');
        $oDatoVista->setDato('{usuario}', $oLoginVO->getAlias());
        $oDatoVista->setDato('{tituloBadge}', 'Productos ');
        // Alertas
        
        // Carga el contenido html y datos según la acción
        $oProductoVO = new ProductoVO();
        $oProductoModelo = new ProductoModelo();
        $oProductoProvVO = new ProductoProvVO();
        $oProductoProvModelo = new ProductoProvModelo();
        // Carga Lista del proveedor
        $archivoTXT = $_SERVER['DOCUMENT_ROOT'].$_SESSION['dir']."/archivos/delsud.txt";
        //echo $archivoTXT."<br>";
        $contenido = file ( $archivoTXT );
        $numero_registros = sizeof( $contenido );
        
        // Selector de acciones
        switch ($this->_accion){
            # ----> acción Iniciar
            case 'Iniciar':
                // carga el contenido html
                $oCargarVista->setCarga('datos', '/modulos/producto/vista/actualizarProductos.html');
                $oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
                // ingresa los datos a representar en las alertas de la vista
                $oDatoVista->setDato('{alertaInfo}',  'Actualiza productos con lista del proveedor.');
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Actualiza Productos - Selección de acciones');
                $oDatoVista->setDato('{informacion}', '<p>Puede seleccionar acciones para los productos de las listas</p>
														<p>de precios de los proveedores, ver botones.');
                // arma la tabla de datos a representar
                /*
                 * DROGUERIA DEL SUD SA
                 * PRECAUCION: Tiene "codigo de proveedor" en 0
                 * Tipo Registro: "T" final de tabla
                 */
                // carga los datos del proveedor
                $this->_idProveedor = 2;
                $oProveedorVO = new ProveedorVO();
                $oProveedorModelo = new ProveedorModelo();
                $oProveedorVO->setId($this->_idProveedor);
                $oProveedorModelo->find($oProveedorVO);
                $oDatoVista->setDato('{idProveedor}', $oProveedorVO->getId());
                $oDatoVista->setDato('{razonSocial}', $oProveedorVO->getRazonSocial());
                $oDatoVista->setDato('{inicial}', $oProveedorVO->getInicial());
                // cuenta la cantidad de productos activos del proveedor
                $this->_items = $oProductoModelo->countPorProveedor($oProveedorVO->getId());
                $this->_cantidad = $oProductoModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                $oDatoVista->setDato('{cantProductos}', $this->_cantidad);
                $oDatoVista->setDato('{cantLista}', sizeof( $contenido ));
                // pone en 0 los contadores
                $this->_cantAgregados = $this->_cantActualizados = $this->_cantEliminados = 0;
                $oDatoVista->setDato('{cantAgregados}', $this->_cantAgregados);
                $oDatoVista->setDato('{cantActualizados}', $this->_cantActualizados);
                $oDatoVista->setDato('{cantEliminados}', $this->_cantEliminados);
                
                break;
                # ----> acción Actualizar
            case 'Actualizar':
                // carga el contenido html
                $oCargarVista->setCarga('datos', '/modulos/producto/vista/actualizarProductos.html');
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Actualización de Productos');
                $oDatoVista->setDato('{informacion}', '<p>Actualiza todos los productos del proveedor de referencia</p>
														<p>con la lista del proveedor.</p>');
                // recibe los datos por POST
                $this->_idProveedor = $_POST['idProveedor'];
                $oDatoVista->setDato('{idProveedor}', $this->_idProveedor);
                $oDatoVista->setDato('{razonSocial}', $_POST['razonSocial']);
                $oDatoVista->setDato('{inicial}', $_POST['inicial']);
                $oDatoVista->setDato('{cantidad}', $_POST['cantProductos']);
                $oDatoVista->setDato('{cantProductos}', $_POST['cantProductos']);
                $oDatoVista->setDato('{cantLista}', sizeof( $contenido ));
                // pone en 0 los totales
                $this->_cantAgregados = $this->_cantActualizados = $this->_cantEliminados = 0;
                
                /**
                 * PASO 1
                 * Borra la tabla productos_prov para limpìar datos del proceso anterior
                 * y arma tabla nueva para el proveedor
                 */
                $oProductoProvModelo->truncate();
                // Pone en CERO control registros
                $registroD = $registroNoD = $registroNoMedicinal = $registroMedicinal = $registroEspecial = 0;
                $registroOtro = $registroCodCero = $registroInsert = $registroUpdate = 0;
                // Arma la nueva tabla productos_prov para el proveedor
                for( $i = 0; $i < sizeof( $contenido ); $i++) {
                    $linea = trim( $contenido[ $i ] );
                    $tipoReg = substr($linea, 0, 1);
                    if ($tipoReg == "D"){ // Si tipo de registro es igual a "D" arma tabla
                        $registroD++;
                        // Windows lee TXT sin problemas en caracteres especiales
                        $tipo = substr($linea, 60, 1);
                        if ($tipo==2){ // si no es "no medicinal" sigue
                            $registroNoMedicinal++;
                            // Codigo de Producto del Proveedor
                            $codigoP = substr($linea, 1, 18);
                            $codigoP = trim($codigoP);
                            if ($codigoP != '000000000000000000'){ // si codigo del proveedor > 0 continua para insertar en producto_prov
                                // Codigo de Producto - Sustituye los 0 por +
                                $this->_codigoP = "+".ltrim($codigoP, 0);
                                // Nombre para guardar
                                $nombre = substr($linea, 19, 40);
                                $nombre = trim($nombre);
                                $nombreUTF8 = utf8_decode($nombre);
                                // Nombre largo para buscar caracteres especiales
                                $nombreL = substr($linea, 19, 50);
                                $nombreL = trim($nombreL);
                                $nombreLUTF8 = utf8_decode($nombreL);
                                /*
                                 // Para LINUX con problemas de caracteres especiales
                                 if (stristr($nombreLUTF8, '?')==TRUE){ // nombre con caracteres especiales
                                 $cantidad = substr_count($nombreLUTF8, '?');
                                 $nombre = $nombreUTF8;
                                 //$nombre = str_replace('?', 'Ñ', $nombreUTF8);
                                 switch ($cantidad){
                                 case 1:
                                 $tipo = substr($linea, 62, 1);
                                 $codigoB = substr($linea, 87, 18);
                                 $precio = substr($linea, 165, 13);
                                 $precio = trim($precio);
                                 $precio = substr_replace($precio,'.',-2,0);
                                 $estadoPro = substr($linea, 84, 1);
                                 break;
                                 case 2:
                                 $tipo = substr($linea, 64, 1);
                                 $codigoB = substr($linea, 89, 18);
                                 $precio = substr($linea, 167, 13);
                                 $precio = trim($precio);
                                 $precio = substr_replace($precio,'.',-2,0);
                                 $estadoPro = substr($linea, 86, 1);
                                 break;
                                 case 3:
                                 $tipo = substr($linea, 66, 1);
                                 $codigoB = substr($linea, 91, 18);
                                 $precio = substr($linea, 169, 13);
                                 $precio = trim($precio);
                                 $precio = substr_replace($precio,'.',-2,0);
                                 $estadoPro = substr($linea, 88, 1);
                                 break;
                                 case 4:
                                 $tipo = substr($linea, 68, 1);
                                 $codigoB = substr($linea, 93, 18);
                                 $precio = substr($linea, 171, 13);
                                 $precio = trim($precio);
                                 $precio = substr_replace($precio,'.',-2,0);
                                 $estadoPro = substr($linea, 90, 1);
                                 break;
                                 case 5:
                                 $tipo = substr($linea, 70, 1);
                                 $codigoB = substr($linea, 95, 18);
                                 $precio = substr($linea, 173, 13);
                                 $precio = trim($precio);
                                 $precio = substr_replace($precio,'.',-2,0);
                                 $estadoPro = substr($linea, 92, 1);
                                 break;
                                 }
                                 } else { // nombre sin caracteres especiales
                                 $tipo = substr($linea, 60, 1);
                                 $codigoB = substr($linea, 85, 18);
                                 $precio = substr($linea, 163, 13);
                                 $precio = trim($precio);
                                 $precio = substr_replace($precio,'.',-2,0);
                                 $estadoPro = substr($linea, 82, 1);
                                 }
                                 // Fin para Linux con problemas de caracteres especiales
                                 */
                                
                                // Para varios códigos de barras por producto
                                $aCodigoB = array();
                                // Primer código de barra
                                $codigoB = 0;
                                $codigoB = substr($linea, 85, 18);
                                if (ctype_space($codigoB) == false){
                                    $aCodigoB[] = $codigoB;
                                }
                                // Segundo código de barra
                                $codigoB = 0;
                                $codigoB = substr($linea, 105, 18);
                                if (ctype_space($codigoB) == false){
                                    $aCodigoB[] = $codigoB;
                                }
                                // Tercer código de barra
                                $codigoB = 0;
                                $codigoB = substr($linea, 125, 18);
                                if (ctype_space($codigoB) == false){
                                    $aCodigoB[] = $codigoB;
                                }
                                // Cuarto código de barra
                                $codigoB = 0;
                                $codigoB = substr($linea, 145, 18);
                                if (ctype_space($codigoB) == false){
                                    $aCodigoB[] = $codigoB;
                                }
                                // Precio del producto
                                $precio = substr($linea, 163, 13);
                                $precio = trim($precio);
                                $precio = substr_replace($precio,'.',-2,0);
                                // Estado del producto
                                $estadoPro = substr($linea, 82, 1);
                                // Fin para Windows sin problemas
                                $estado = 1;
                                if ($estadoPro == 'B'){
                                    $estado = 0;
                                }
                                if ($estadoPro == 'S'){
                                    $estado = 2;
                                }
                                // Cargo los datos del producto en ProductoProvVO
                                $idArticulo = 0;
                                $oProductoProvVO->setIdProveedor($this->_idProveedor);
                                $oProductoProvVO->setCodigoP($this->_codigoP);
                                $oProductoProvVO->setNombre($nombre);
                                $oProductoProvVO->setPrecio($precio);
                                $oProductoProvVO->setEstado($estado);
                                $oProductoProvVO->setIdArticulo($idArticulo);
                                $oProductoProvVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
                                // Inserta en la tabla producto_prov tantas veces como codigo de barras diferente tenga
                                foreach ($aCodigoB as $codigoB){
                                    $oProductoProvVO->setCodigoB($codigoB);
                                    $this->_date = date('Y-m-d H:i:s');
                                    $oProductoProvVO->setFechaAct($this->_date);
                                    $oProductoProvModelo->insert($oProductoProvVO);
                                    $registroInsert++;
                                }
                                
                            }else{
                                $registroCodCero++;
                            }
                        } else { // fin tipo no medicinal
                            if ($tipo==1){ // tipo medicinal
                                $registroMedicinal++;
                            }else{
                                if ($tipo=='E'){
                                    $registroEspecial++;
                                } else {
                                    $registroOtro++;
                                    // habilitar echo para pruebas
                                    //echo "Otro --> ".$i." - ".$codigoP." - ".$nombre." (".$tipo.") ".$codigoB." - $ ".$precio."<br>";
                                }
                            }
                        }
                    } else { // fin tipo de registro "D", si no es "D" suma al contador
                        $registroNoD++;
                    }
                    unset($aCodigoB); // borra el array de Códigos de Barra que armamos para el producto
                } // finaliza carga nuevos datos tabla productos_prov para el proveedor
                
                // Muestras los totalizadores para realizar pruebas
                /*
                 echo "Registros D: ".$registroD."<br>";
                 echo "-------------------------------------<br>";
                 echo "Registros No Medicinal: ".$registroNoMedicinal."<br>";
                 echo "Registros Insert: ".$registroInsert."<br>";
                 echo "Registros Cod. CERO: ".$registroCodCero."<br>";
                 echo "--------------------------------------<br>";
                 echo "Registros Medicinales: ".$registroMedicinal."<br>";
                 echo "Registro Especial: ".$registroEspecial."<br>";
                 echo "Registros Otros: ".$registroOtro."<br>";
                 echo "--------------------------------------<br>";
                 echo "Registro No D: ".$registroNoD."<br>";
                 */
                
                /**
                 *
                 * PASO 2:
                 * Verifico si existen codigos de barra repetidos y cambio de
                 * estado al producto_prov
                 *
                 *  estado = 9
                 *
                 */
                $this->_cantDuplicados = $this->_cantCodigoBRep = 0;
                $this->_aProductos = $oProductoProvModelo->findAllCodigoBRepetidos();
                //echo "CODIGOS DE BARRAS REPETIDOS<br>";
                //var_dump($this->_aProductos);
                //echo "<br> Cantidad Leidos repetidos: ".$oProductoProvModelo->getCantidad()."<br>";
                if ($oProductoProvModelo->getCantidad()>0){ // hay productos_prov con código de barra repetidos
                    // cambiar estado a los productos_prov con codigo de barra repetidos
                    foreach ($this->_aProductos as $this->_producto){
                        //echo "Producto: - Cod. Bar. ".$this->_producto['codigo_b']."<br>";
                        if ($this->_producto['codigo_b']>0){
                            $this->_cantCodigoBRep++;
                            $oProductoProvVO->setCodigoB($this->_producto['codigo_b']);
                            $this->_items = $oProductoProvModelo->findAllPorCodigoB($oProductoProvVO);
                            if ($oProductoProvModelo->getCantidad()>0){
                                foreach ($this->_items as $this->_item){
                                    $oProductoProvVO->setId($this->_item['id']);
                                    $oProductoProvModelo->find($oProductoProvVO);
                                    $oProductoProvVO->setEstado(9);
                                    $oProductoProvVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
                                    $this->_date = date('Y-m-d H:i:s');
                                    $oProductoProvVO->setFechaAct($this->_date);
                                    $oProductoProvModelo->update($oProductoProvVO);
                                    $this->_cantDuplicados++;
                                }
                            }
                        }
                    }
                    if ($this->_cantCodigoBRep > 0) {
                        // Avisa que hay productos con codigo de barra repetidos
                        $oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
                        // ingresa los datos a representar en las alertas de la vista
                        $oDatoVista->setDato('{alertaPeligro}',  'Lista del proveedor con <b>CODIGOS de BARRA repetidos</b>, CONSULTE...
                                                                <p>Son '.$this->_cantCodigoBRep.' códigos de barras repetidos en '.$this->_cantDuplicados.' productos.</p>');
                    }
                } // Fin codigos de barras repetidos
                
                
                /**
                 *
                 * PASO 3:
                 * Verifico si existen codigos del proveedor repetidos y cambio de
                 * estado al producto_prov
                 *
                 *  estado = 9
                 *
                 * COMENTARIO: tenemos repetidos los códigos de productos pero tienen
                 * diferentes códigos de barra.
                 */
                
                /**
                 * PASO 4
                 * Actualiza en tabla productos los registros con cambio de estado
                 * o eliminados en la lista del proveedor
                 *
                 * COMENTARIO: tenemos repetidos los códigos de productos pero tienen
                 * diferentes códigos de barra.
                 */
                
                // verifico si el proveedor no elimino algún producto de la lista
                // comparo todos los productos del proveedor con productos_prov
                $oProductoVO->setIdProveedor($this->_idProveedor);
                // cargo todos los productos activos del proveedor
                $this->_items = $oProductoModelo->findAllPorIdProveedor($oProductoVO); // Busca los productos activos por el id del proveedor
                if ($oProductoModelo->getCantidad()>0){ // Existen productos activos para el proveedor
                    foreach ($this->_items as $this->_item){
                        $oProductoProvVO->setCodigoP($this->_item['codigo_p']);
                        $oProductoProvVO->setCodigoB($this->_item['codigo_b']);
                        $this->_productos = $oProductoProvModelo->findAllPorCodigoPCodigoB($oProductoProvVO); // Estado activos
                        // No existe en ProductoProv un producto con codigo_p igual al de tabla Producto
                        if ($oProductoProvModelo->getCantidad()==0){ // paso a inactivo el producto en tabla productos para el proveedor
                            $oProductoVO->setId($this->_item['id']);
                            $oProductoModelo->find($oProductoVO);
                            $oProductoVO->setIdArticulo(0); // pongo 0 a id_articulo de prepo
                            $oProductoVO->setEstado(0); // producto con estado inactivo
                            $oProductoVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
                            $this->_date = date('Y-m-d H:i:s');
                            $oProductoVO->setFechaAct($this->_date);
                            $oProductoModelo->update($oProductoVO);
                            $this->_cantEliminados = $this->_cantEliminados + 1;
                            // Existe uno o mas productos en ProductoProv con codigo_p y codigo_b igual al de tabla Productos
                        } else { // Hay dos o mas productos con igual codigo_p y codigo_b
                            // pregunto si hay más de un producto para mandar error
                            // e igualar precios al mayor de ellos
                            if ($oProductoProvModelo->getCantidad()>1){ // más de un producto
                                // PRECIO DE EMERGENCIA: busco precio mayor para igualar
                                $this->_precio = 0;
                                foreach ($this->_productos as $this->_producto){
                                    //echo "Id: ".$this->_producto['id']." - Cod Prod Prov: ".$this->_producto['codigo_p']." (".$this->_producto['codigo_b'].") $ ".$this->_producto['precio']."<br>";
                                    if ($this->_precio < $this->_producto['precio']){ // carga el precio mayor
                                        $this->_precio = $this->_producto['precio'];
                                    }
                                }
                                reset($this->_productos); // posiciono el puntero en primer registro
                                foreach ($this->_productos as $this->_producto){
                                    $oProductoProvVO->setId($this->_producto['id']);
                                    $oProductoProvModelo->find($oProductoProvVO);
                                    if ($oProductoProvModelo->getCantidad() == 1){
                                        $oProductoProvVO->setPrecio($this->_precio); // precio mayor de productos iguales
                                        $oProductoProvVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
                                        $this->_date = date('Y-m-d H:i:s');
                                        $oProductoProvVO->setFechaAct($this->_date);
                                        $oProductoProvModelo->update($oProductoProvVO);
                                        $registroUpdate++;
                                    }
                                }
                                // Avisa la cantidad de precios que cambio al mayor
                                $oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
                                // ingresa los datos a representar en las alertas de la vista
                                $oDatoVista->setDato('{alertaAdvertencia}',  'PRECAUCIÓN, modificamos <b>'.$registroUpdate.' precios </b>del proveedor por el MAYOR.');
                                // Avisa que hay productos con codigo duplicado
                                $oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
                                // ingresa los datos a representar en las alertas de la vista
                                $oDatoVista->setDato('{alertaPeligro}',  'Lista del proveedor con <b>CODIGOS de productos iguales</b>, CONSULTE...');
                            } // Fin igualar precios a productos con codigo_p y codigo_b iguales
                        } // Fin hay mas de un producto con codigo_p y codigo_b iguales
                    } // Fin foreach
                } // Fin existen productos activos para el proveedor
                
                /**
                 * PASO 5
                 * Actualiza los registros de productos con los de productos_prov
                 *
                 */
                // ------------------------------------------------------------------------
                // Nota: tiene diferentes codigos de barras y el mismo codigo de productos,
                //       armo un producto nuevo por combinación (codigo_p / codigo_b)
                // ------------------------------------------------------------------------
                
                // Actualiza los datos de productos con productos_prov
                $idArticulo = 0;
                $this->_items = $oProductoProvModelo->findAll(); // Carga todos, activos e inactivos
                foreach ($this->_items as $this->_item){
                    $oProductoVO->setIdProveedor($this->_idProveedor);
                    $oProductoVO->setCodigoP($this->_item['codigo_p']);
                    $oProductoVO->setCodigoB($this->_item['codigo_b']);
                    $oProductoModelo->findPorCodigoPCodigoBProveedor($oProductoVO);
                    if ($oProductoModelo->getCantidad() == 0){ // Agrega un producto
                        $oProductoVO->setIdProveedor($this->_idProveedor);
                        $oProductoVO->setCodigoB($this->_item['codigo_b']);
                        $oProductoVO->setCodigoP($this->_item['codigo_p']);
                        $oProductoVO->setNombre($this->_item['nombre']);
                        $oProductoVO->setPrecio($this->_item['precio']);
                        $oProductoVO->setEstado($this->_item['estado']);
                        $oProductoVO->setIdArticulo($idArticulo);  // es cero - todavia no etiquetó artículos
                        $oProductoVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
                        $this->_date = date('Y-m-d H:i:s');
                        $oProductoVO->setFechaAct($this->_date);
                        $oProductoModelo->insert($oProductoVO);
                        $this->_cantAgregados++;
                    }else{ // verifica si actualiza un producto (Precio y Estado)
                        if ($oProductoVO->getPrecio() != $this->_item['precio'] OR $oProductoVO->getEstado() != $this->_item['estado']){
                            //echo "Act-> (".$tipoReg.") ".$oProductoVO->getCodigoP()." [".$oProductoVO->getCodigoB()."] ".$oProductoVO->getNombre()."<br>";
                            //echo "-----------> Precio Prod $ ".$oProductoVO->getPrecio()." / Lista $ ".$precio." - Estado Prod: ".$oProductoVO->getEstado()." / Lista: ".$estado." <--- FIN <br>";
                            $oProductoVO->setPrecio($this->_item['precio']);
                            $oProductoVO->setEstado($this->_item['estado']);
                            $oProductoVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
                            $this->_date = date('Y-m-d H:i:s');
                            $oProductoVO->setFechaAct($this->_date);
                            $oProductoModelo->update($oProductoVO);
                            $this->_cantActualizados++;
                        }
                    }
                }
                
                
                $oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
                // ingresa los datos a representar en las alertas de la vista
                $oDatoVista->setDato('{alertaSuceso}',  'Se ejecutó la acción con <b>EXITO !!!</b>.');
                $oDatoVista->setDato('{cantAgregados}', $this->_cantAgregados);
                $oDatoVista->setDato('{cantActualizados}', $this->_cantActualizados);
                $oDatoVista->setDato('{cantEliminados}', $this->_cantEliminados);
                break;
                # ----> acción Confirmar Actualizar
            case 'ConfirmarAct':
                
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
    
}
?>