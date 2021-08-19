<?php
/**
 * Archivo de la clase control del módulo plex/factura.
 *
 * Archivo de la clase control del módulo plex/factura.
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
 * Clase control del módulo plex/factura.
 *
 * Clase control del módulo plex/factura que permite realizar
 * operaciones sobre la tabla plex/factlineas (CRUD y otras)
 * según la categoría asignada al usuario.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class FacturaPlexControl
{    #Propiedades
    private $_html;
    private $_accion;
    private $_item;
    private $_items;
    private $_cantidad;
    private $_cantFacturas;
    private $_cantFacLineas;
    private $_cantAgregar;
    private $_cantActualizar;
    private $_cantActualizados;
    private $_cantActReponer;
    private $_cantAgregados;
    private $_cantAgregReponer;
    private $_cantRegistros;
    private $_cantPedido;
    private $_cantReponer;
    private $_cont;
    private $_id;
    private $_idFactura;
    private $_fecha;
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
        Clase::define('PendienteModelo');
        Clase::define('PendienteActModelo');
        Clase::define('ReponerModelo');
        Clase::define('DataBasePlex');
 
        
        // Chequea login
        $oLoginControl = new LoginControl();
        $oLoginControl->chequearLogin($oLoginVO);
        $this->_accion = $accion;
        $this->accionControl($oLoginVO);
    }
    
    /**
     * Nos permite ejecutar las acciones del módulo
     * plex/factura del sistema, de acuerdo a la categoría del
     * usuario.
     */
    private function accionControl($oLoginVO)
    {        // Carga acciones del formulario
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
            "botonActualizar" => "/includes/vista/botonActualizar.html"
        );
        $oCargarVista->setCarga('aAcciones', $this->_aAcciones);
        // Ingresa los datos a representar en el html de la vista
        $oDatoVista = new DatoVista();
        $oDatoVista->setDato('{tituloPagina}', 'Comprobantes (PLEX)');
        $oDatoVista->setDato('{usuario}', $oLoginVO->getAlias());
        $oDatoVista->setDato('{tituloBadge}', 'Comprobantes ');
        // Alertas
        
        // Carga el contenido html y datos según la acción
        
        // Instancia las clases del modelo
        $oArticuloVO = new ArticuloVO();
        $oArticuloModelo = new ArticuloModelo();
        $oPendienteVO = new PendienteVO();
        $oPendienteModelo = new PendienteModelo();
        $oPendienteActVO = new PendienteActVO();
        $oPendienteActModelo = new PendienteActModelo();
        $oReponerVO = new ReponerVO();
        $oReponerModelo = new ReponerModelo();
        
        // Selector de acciones
        switch ($this->_accion){
            # ----> acción Iniciar
            case 'Iniciar':
                // carga el contenido html
                
                // carga las alertas html
                $oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
                // ingresa los datos a representar en las alertas de la vista
                $oDatoVista->setDato('{alertaInfo}',  'Seleccione una acción con los botones.');
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Comprobantes - (PLEX)');
                $oDatoVista->setDato('{informacion}', '<p>Debe seleccionar una acción a realizar,</p>');
				
                // carga los eventos (botones)
                
                // agrega  mensajes si hay facturas para actualizar
                // busca la última factura actualizada
                $oPendienteActModelo->lastId();
                $this->_id = $oPendienteActModelo->getLastId();
                $oPendienteActVO->setId($this->_id);
                $oPendienteActModelo->find($oPendienteActVO);
                $this->_idFactura = $oPendienteActVO->getIdFactura();
                // cuenta cantidad de renglones de las facturas sin actualizar
                $this->_con = DataBasePlex::getInstance();
                $query = "SELECT count(*) FROM factcabecera WHERE IDComprobante > ".$this->_idFactura;
                $result = mysqli_query($this->_con, $query) or die(mysqli_error($this->_con));
                $count = mysqli_fetch_array($result);
                mysqli_free_result($result);
                DataBasePlex::closeInstance();
                $this->_cantidad = $count[0];
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                // si cantidad hay facturas para actualizar agrego mensaje
                if ($this->_cantidad > 0 ){

                    // agrega las alertas html
                    $oCargarVista->setCarga('alertaPeligro', '/includes/vista/alertaPeligro.html');
                    // ingresa los datos a representar en las alertas agregadas de la vista
                    $oDatoVista->setDato('{alertaPeligro}',  'Actualiza -Pedidos- pendientes con los comprobantes de PLEX.');
                } else {
                    // agrega las alertas html
                    $oCargarVista->setCarga('alertaAdvertencia', '/includes/vista/alertaAdvertencia.html');
                    // ingresa los datos a representar en las alertas de la vista
                    $oDatoVista->setDato('{alertaAdvertencia}',  'No hay Comprobantes de PLEX para actualizar.');
                }
                break;
                
                # ----> acción Listar
            case 'Listar':

                break;
                # ----> acción Actualizar
            case 'Actualizar':
                // carga el contenido html
                $oCargarVista->setCarga('datos', '/plex/factura/vista/actualizarDatos.html');
      
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Actualiza Pedidos Pendientes con Comprobantes (PLEX)');
                $oDatoVista->setDato('{informacion}', '<p>Actualiza pedidos pendientes con los comprobantes de la base PLEX.</p>
														<p>También puede seleccionar otras acciones para los</p>
														<p>pedidos pendientes, ver botones.</p>');
                
                // Ingresa los datos a representar en el html de la vista
                // busca la última factura actualizada
                $oPendienteActModelo->lastId();
                $this->_id = $oPendienteActModelo->getLastId();
                $oPendienteActVO->setId($this->_id);
                $oPendienteActModelo->find($oPendienteActVO);
                $this->_idFactura = $oPendienteActVO->getIdFactura();
                // Conecta a DB PLEX
                $this->_con = DataBasePlex::getInstance();
                // busca cantidad de facturas a actualizar en PLEX
                $query = "SELECT count(*) FROM factcabecera WHERE IDComprobante > ".$this->_idFactura;
                $result = mysqli_query($this->_con, $query) or die(mysqli_error($this->_con));
                $count = mysqli_fetch_array($result);
                mysqli_free_result($result);
                $this->_cantidad = $this->_cantFacturas = $count[0];
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                $oDatoVista->setDato('{cantFacturas}', $this->_cantFacturas);
                DataBasePlex::closeInstance();
                
                $this->_cantFacLineas = $this->_cantAgregados = $this->_cantActualizados = 0;
                $oDatoVista->setDato('{cantFacLineas}', $this->_cantFacLineas);
                $oDatoVista->setDato('{cantAgregados}', $this->_cantAgregados);
                $oDatoVista->setDato('{cantActualizados}', $this->_cantActualizados);
                // carga las alertas html
                $oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
                // ingresa los datos a representar en las alertas de la vista
                $oDatoVista->setDato('{alertaInfo}',  'Actualiza -Pedidos- y -Reposiciones- pendientes con los comprobantes de PLEX. Confirme la acción.');
                // carga los eventos (botones)
                $this->_aEventos = array(
                    "actualizarConf" => "/includes/vista/botonActualizarConf.html",
                );
                $oCargarVista->setCarga('aEventos', $this->_aEventos);
                break;
                # ----> acción Confirmar Actualizar
            case 'ConfirmarAct':
                // recibe datos por POST
                $this->_cantidad = $_POST['cantidad'];
                $this->_cantFacturas = $_POST['cantFacturas'];
                // carga el contenido html
                $oCargarVista->setCarga('datos', '/plex/factura/vista/actualizarDatos.html');
                // carga las alertas html
                
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Actualiza Pedidos y Reposiciones con Comprobantes (PLEX)');
                $oDatoVista->setDato('{informacion}', '<p>Actualiza pedidos y reposiciones pendientes con los comprobantes de la base PLEX.</p>
														<p>También puede seleccionar otras acciones para los</p>
														<p>pedidos pendientes, ver botones.</p>');
                // Ingresa los datos a representar en el html de la vista
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                $oDatoVista->setDato('{cantFacturas}', $this->_cantFacturas);
                
                // Actualiza los datos de artículos con productos
                $this->_cantAgregados = $this->_cantActualizados = $this->_cantAgregReponer = $this->_cantActReponer = 0;
                $this->_cont = $this->_cantRegistros = 0;
                
                /*
                 *  Busco los renglones de facturas para actualizar pedidos pendientes
                 */
                // busco la última actualizada
                $oPendienteActModelo->lastId();
                $this->_id = $oPendienteActModelo->getLastId();
                $oPendienteActVO->setId($this->_id);
                $oPendienteActModelo->find($oPendienteActVO);
                $this->_idFactura = $oPendienteActVO->getIdFactura();
                // Conecta a DB PLEX
                $this->_con = DataBasePlex::getInstance();
                // lee la tabla de factcabecera desde la última actualizada.
                $query = "SELECT IDComprobante, Emision, Tipo 
                                 FROM factcabecera
                                 WHERE IDComprobante > ".$this->_idFactura;
                $result = mysqli_query($this->_con, $query) or die(mysqli_error($this->_con));
                while ($factura = mysqli_fetch_array($result)){
                    // guardo datos para el último comprobante actualizado
                    $this->_idFactura = $factura[0];
                    $this->_fecha = $factura[1];
                    // pregunta si los tipos de comprobantes son actualizables
                    if ($factura[2]=="NC" || $factura[2]=="TF" || $factura[2]=="TK"){
                        echo "Comprobante ID --> ".$factura[0]." - Tipo: ".$factura[2]." del ".$factura[1]."<br>";
                        // lee la tabla de factlineas
                        $query1 = "SELECT IDComprobante, Orden, IdProducto, TipoCantidad, Cantidad
                                          FROM factlineas
                                          WHERE IDComprobante =".$factura[0];
                        $result1 = mysqli_query($this->_con, $query1) or die(mysqli_error($this->_con));
                        while ($renglon = mysqli_fetch_array($result1)){
                            if (trim($renglon[2]) != NULL){
                                if ($renglon[3] == "C"){
                                // ------------------------------------------------    
                                // PENDIENTE - veo si actualizo o agrego
          
                                    //var_dump($renglon[2]);
                                    echo "---> Renglon: ".$renglon[1]." - Producto ID: ".$renglon[2]." (".$renglon[3].") ".$renglon[4]."<br>";
                                    $this->_cantPedido = 0;
                                    $oPendienteVO->setCodigo($renglon[2]);
                                    $oPendienteModelo->findPorCodigo($oPendienteVO);
                                    if ($oPendienteModelo->getCantidad() > 0){ // existe el articulo pendiente
                                        echo "EXISTE el pendiente -> Id: ".$oPendienteVO->getId()." - Cod. ".$oPendienteVO->getCodigo()." - Cantidad: ".$oPendienteVO->getCantidad()."<br>"; 
                                        if ($factura[2]=="NC"){
                                            $this->_cantPedido = $oPendienteVO->getCantidad() - $renglon[4];
                                        } else {
                                            $this->_cantPedido = $oPendienteVO->getCantidad() + $renglon[4];
                                        }
                                        $oPendienteVO->setCantidad($this->_cantPedido);
                                        $oPendienteVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
                                        $this->_date = date('Y-m-d H:i:s');
                                        $oPendienteVO->setFechaAct($this->_date);
                                        $oPendienteModelo->update($oPendienteVO);
                                        if($oPendienteModelo->getCantidad() > 0){
                                            $this->_cantActualizados++;
                                            echo "Actualización Nro: ".$this->_cantActualizados."<br>";
                                        } else {
                                            echo "Fallo actualización - VER ERROR <br>";
                                        }
                                        
                                    } else { // no existe el artículo pendiente
                                        $oArticuloVO->setCodigo($renglon[2]);
                                        $oArticuloModelo->findPorCodigo($oArticuloVO);
                                        $oPendienteVO->setIdArticulo($oArticuloVO->getId());
                                        $oPendienteVO->setCodigo($oArticuloVO->getCodigo());
                                        $oPendienteVO->setCodigoB($oArticuloVO->getCodigoB());
                                        $oPendienteVO->setIdRubro($oArticuloVO->getIdRubro());
                                        $oPendienteVO->setIdProveedor(0); // Id del proveedor en 0 (CERO)
                                        if ($factura[2]=="NC"){
                                            $this->_cantPedido = $this->_cantPedido - $renglon[4];
                                        } else {
                                            $this->_cantPedido = $this->_cantPedido + $renglon[4];
                                        }
                                        $oPendienteVO->setCantidad($this->_cantPedido);
                                        $oPendienteVO->setIdPedido(0); // Id del pedido en 0 (CERO)
                                        $oPendienteVO->setEstado(1); // Estado en 1 = Activo
                                        $oPendienteVO->setCantidadRec(0); // en CERO
                                        $oPendienteVO->setFechaRec("0000-00-00");
                                        $oPendienteVO->setComprobante('');
                                        $oPendienteVO->setComentario(''); // en blanco
                                        $oPendienteVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
                                        $this->_date = date('Y-m-d H:i:s');
                                        $oPendienteVO->setFechaAct($this->_date);
                                        $oPendienteModelo->insert($oPendienteVO);
                                        if($oPendienteModelo->getCantidad() > 0){
                                            $this->_cantAgregados++;
                                            echo "Agregado Nro: ".$this->_cantAgregados."<br>";
                                            
                                        } else {
                                            echo "Fallo agregado - VER ERROR <br>";
                                        }
                                         
                                    } 
                               // Fin pendientes
                               // --------------------------------------------------     
                               // REPONER - veo si actualizo o agrego
                                    $this->_cantReponer = 0;
                                    $oReponerVO->setCodigo($renglon[2]);
                                    $oReponerModelo->findPorCodigoSinNumero($oReponerVO);
                                    if ($oReponerModelo->getCantidad() > 0){ // existe el articulo a reponer
                                        echo "EXISTE el articulo a reponer -> Id: ".$oReponerVO->getId()." - Cod. ".$oReponerVO->getCodigo()." - Cantidad: ".$oReponerVO->getCantidad()."<br>";
                                        if ($factura[2]=="NC"){
                                            $this->_cantReponer = $oReponerVO->getCantidad() - $renglon[4];
                                        } else {
                                            $this->_cantReponer = $oReponerVO->getCantidad() + $renglon[4];
                                        }
                                        $oReponerVO->setCantidad($this->_cantReponer);
                                        $oReponerVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
                                        $this->_date = date('Y-m-d H:i:s');
                                        $oReponerVO->setFechaAct($this->_date);
                                        $oReponerModelo->update($oReponerVO);
                                        if($oReponerModelo->getCantidad() > 0){
                                            $this->_cantActReponer++;
                                            echo "Actualización Reponer Nro: ".$this->_cantActReponer."<br>";
                                        } else {
                                            echo "Fallo actualización - VER ERROR <br>";
                                        }
                                     
                                    } else { // no existe el artículo a reponer
                                        $oArticuloVO->setCodigo($renglon[2]);
                                        $oArticuloModelo->findPorCodigo($oArticuloVO);
                                        $oReponerVO->setCodigo($oArticuloVO->getCodigo());
                                        $oReponerVO->setIdRubro($oArticuloVO->getIdRubro());
                                        if ($factura[2]=="NC"){
                                            $this->_cantReponer = $this->_cantReponer - $renglon[4];
                                        } else {
                                            $this->_cantReponer = $this->_cantReponer + $renglon[4];
                                        }
                                        $oReponerVO->setCantidad($this->_cantReponer);
                                        $oReponerVO->setNumeroRep(0); // número de reposición en 0 (CERO)
                                        $oReponerVO->setEstado(1); // Estado en 1 = Activo
                                        $oReponerVO->setFechaRep(NULL);
                                        $oReponerVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
                                        $this->_date = date('Y-m-d H:i:s');
                                        $oReponerVO->setFechaAct($this->_date);
                                        $oReponerModelo->insert($oReponerVO);
                                        if($oReponerModelo->getCantidad() > 0){
                                            $this->_cantAgregReponer++;
                                            echo "Agregado Reponer Nro: ".$this->_cantAgregReponer."<br>";
                                            
                                        } else {
                                            echo "Fallo agregado - VER ERROR <br>";
                                        }
                                        
                                    } // Fin Reponer
                                    
                                    
                                } else {
                                    echo "NO ACT es UNIDAD: ---> Renglon: ".$renglon[1]." - Producto ID: ".$renglon[2]." (".$renglon[3].") ".$renglon[4]."<br>";
                                }
                            } else {
                                echo "NO ACT: ---> Renglon: ".$renglon[1]." - Producto ID: ".$renglon[2]." (".$renglon[3].") ".$renglon[4]."<br>";
                            }
                        }
                        mysqli_free_result($result1);
                    } else { // Fin comprobantes actualizables
                        
                        echo "***** Comprobante ID --> ".$factura[0]." es una ".$factura[2]." NO AGREGA <br>";
                        
                    }
                    
                }
                
                echo "***** GRABO EL ULTIMO COMPROBANTE ******<br>";
                echo "Comprobante ID --> ".$this->_idFactura." - del : ".$this->_fecha."<br>";
                echo "****************************************<br>";
                $oPendienteActVO->setIdFactura($this->_idFactura);
                $oPendienteActVO->setFecha($this->_fecha);
                $oPendienteActVO->setEstado(1); // 1 = Activo
                $oPendienteActVO->setIdUsuarioAct($oLoginVO->getIdUsuario());
                $this->_date = date('Y-m-d H:i:s');
                $oPendienteActVO->setFechaAct($this->_date);
                $oPendienteActModelo->insert($oPendienteActVO);
                
                mysqli_free_result($result);
               
                DataBasePlex::closeInstance(); // cierra conección con PLEX
                $oDatoVista->setDato('{cantAgregados}', $this->_cantAgregados);
                $oDatoVista->setDato('{cantActualizados}', $this->_cantActualizados);

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