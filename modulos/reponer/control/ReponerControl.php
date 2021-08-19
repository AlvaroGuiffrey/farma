<?php
/**
 * Archivo de la clase control del módulo reponer.
 *
 * Archivo de la clase control del módulo reponer.
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
 * Clase control del módulo reponer.
 *
 * Clase control del módulo reponer que permite realizar
 * operaciones sobre la tabla reposiciones (CRUD y otras)
 * necesarias para la administración de las reposiciones
 * según la categoría asignada al usuario.
 *
 * @author     Alvaro Guiffrey <alvaroguiffrey@gmail.com>
 * @copyright  Copyright (c) 2015 Alvaro Guiffrey
 * @license    http://www.gnu.org/licenses/   GPL License
 * @version    1.0
 * @link       http://www.alvaroguiffrey.com.ar
 * @since      Class available since Release 1.0
 */
class ReponerControl
{
    #Propiedades
    private $_html;
    private $_accion;
    private $_items;
    private $_item;
    private $_fechaDesde;
    private $_fechaHasta;
    private $_idRubro;
    private $_nombre;
    private $_presentacion;
    private $_codigo;
    private $_aArticulos = array();
    private $_aReposiciones = array();
    private $_aRubros = array();
    private $_estado;
    private $_existeProducto;
    private $_numeroRep;
    private $_cantListados;
    private $_cantArticulos;
    private $_cantUnidades;
    private $_cantAgregados;
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
        Clase::define('ReponerModelo');
        Clase::define('RubroModelo');
        Clase::define('ReponerTabla');
        Clase::define('ReponerNumeroTabla');
        Clase::define('ArrayOrdenadoPor');
        
        // Chequea login
        $oLoginControl = new LoginControl();
        $oLoginControl->chequearLogin($oLoginVO);
        $this->_accion = $accion;
        $this->accionControl($oLoginVO);
    }
    
    /**
     * Nos permite ejecutar las acciones del módulo
     * reponer del sistema, de acuerdo a la categoría del
     * usuario.
     */
    private function accionControl($oLoginVO)
    {
        
        // Carga acciones del formulario
        if (isset($_POST['bt_listar'])) $this->_accion = "Listar";
        if (isset($_POST['bt_listar_conf'])) $this->_accion = "ConfirmarL";
        if (isset($_POST['bt_reponer'])) $this->_accion = "Reponer";
        if (isset($_POST['bt_reponer_conf'])) $this->_accion = "ConfirmarR";
        if (isset($_POST['bt_descargar'])) $this->_accion = "Descargar";
   
        
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
            "reponer" => "/modulos/reponer/vista/botonReponer.html",
            "descargar" => "/modulos/reponer/vista/botonDescargar.html"
        );
        $oCargarVista->setCarga('aAcciones', $this->_aAcciones);
        
        // Ingresa los datos a representar en el html de la vista
        $oDatoVista = new DatoVista();
        $oDatoVista->setDato('{tituloPagina}', 'Reponer');
        $oDatoVista->setDato('{usuario}', $oLoginVO->getAlias());
        $oDatoVista->setDato('{tituloBadge}', 'Reponer ');
        
        // Alertas
        
        // Carga el contenido html y datos según la acción
        $oArticuloVO = new ArticuloVO();
        $oArticuloModelo = new ArticuloModelo();
        $oReponerVO = new ReponerVO();
        $oReponerModelo = new ReponerModelo();
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
                $oDatoVista->setDato('{tituloPanel}', 'Reponer - Selección de acciones');
                $oDatoVista->setDato('{informacion}', '<p>Puede seleccionar acciones para reponer, ver botones.</p>');
                // arma la tabla de datos a representar
                $this->_items = $oReponerModelo->countReponer(); // todos los artículos a reponer
                $this->_cantidad = $oReponerModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                break;
            # ----> acción Listar
            case 'Listar':
                // carga el contenido html
                $oCargarVista->setCarga('datos', '/modulos/reponer/vista/listarOpcion.html');
                
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Listado de Reposiciones');
                $oDatoVista->setDato('{informacion}', '<p>Listado de las reposiciones realizadas en un período.</p><p>También puede seleccionar otras acciones</p><p>para las reposiciones, ver botones.');
                // carga los eventos (botones)
                $this->_aEventos = [
                    "listarConfirmar" => "/includes/vista/botonListarConfirmar.html",
                    "borrar" => "/includes/vista/botonBorrar.html"
                ];
                $oCargarVista->setCarga('aEventos', $this->_aEventos);
                // ingresa los datos a representar en las alertas de la vista

                // arma los datos a representar
                $oReponerModelo->countReponer(); // todos los artículos a reponer
                $this->_cantidad = $oReponerModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                $oReponerModelo->countReposicionesListadas();
                $oDatoVista->setDato('{cantidadReposiciones}', $oReponerModelo->getCantidad());
                break;
            # ----> acción Confirmar Listar
            case "ConfirmarL":
                // recibe datos por Post
                $this->_fechaDesde = trim($_POST['fechaDesde']);
                $this->_fechaHasta = trim($_POST['fechaHasta']);
                
                // carga el contenido html
                
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Listado de Reposiciones');
                $oDatoVista->setDato('{informacion}', '<p>Listado de todas las reposiciones según opciones elegidas.</p>
												<p>También puede seleccionar otras acciones</p>
												<p>para las reposiciones, ver botones.'
                    );
                // ingresa los datos a representar en las alertas de la vista
 
                // arma la tabla de datos a representar
                
                // realiza la consulta segun las opciones elegidas
                //echo "Desde: ".$this->_fechaDesde." - Hasta: ".$this->_fechaHasta."<br>";
                $this->_items = $oReponerModelo->findAllOpcionRepListadas($this->_fechaDesde, $this->_fechaHasta);
                $this->_cantidad = $oReponerModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad); 
                
                foreach ($this->_items as $this->_item){
                    //echo "Numero Rep: ".$this->_item['numero_rep']."<br>";
                    $oReponerVO->setNumeroRep($this->_item['numero_rep']);
                    $oReponerModelo->findFechaReposicion($oReponerVO);
                    $oReponerModelo->countArticulosReposicion($this->_item['numero_rep']);
                    $this->_cantArticulos = $oReponerModelo->getCantidad();
                    $oReponerModelo->sumaUnidadesReposicion($this->_item['numero_rep']);
                    $this->_cantUnidades = $oReponerModelo->getCantidad();
                    $this->_aReposiciones[] = array(
                        'numero_rep' => $this->_item['numero_rep'],
                        'fecha_rep' => $oReponerVO->getFechaRep(),
                        'cant_articulos' => $this->_cantArticulos,
                        'cant_unidades' => $this->_cantUnidades
                    );
                }
                // ordena el array
                $this->_items = ArrayOrdenadoPor::ordenaArray($this->_aReposiciones, 'numero_rep', SORT_ASC);
                ReponerNumeroTabla::armaTabla($this->_cantidad, $this->_items, $this->_accion, $this->_fechaDesde, $this->_fechaHasta);
                $oCargarVista->setCarga('tabla', '/modulos/reponer/tabla.html');
                break;

            # ----> acción Reponer
            case 'Reponer':
                // carga el contenido html
                
                // ingresa los datos a representar en el panel de la vista
                $oCargarVista->setCarga('datos', '/modulos/reponer/vista/checkRubros.html');
                $oDatoVista->setDato('{tituloPanel}', 'Reponer Artículos');
                $oDatoVista->setDato('{informacion}', '<p>Genera una nueva reposición de artículos.</p><p>También puede seleccionar otras acciones</p><p>para las reposiciones, ver botones.');
                // carga los eventos (botones)
                
                // arma los datos a representar
                $this->_aRubros = $oRubroModelo->findAll();
                foreach ($this->_aRubros as $rubro){
                    $oDatoVista->setDato('{Rubro'.$rubro['id'].'Id}', $rubro['id']);
                    $oDatoVista->setDato('{Rubro'.$rubro['id'].'Nombre}', $rubro['nombre']);
                }
                $oReponerModelo->countAgregarReponer(); // cuenta todos los artículos a reponer estado=1 numeroRep = 0
                $this->_cantidad = $oReponerModelo->getCantidad();
                $this->_cantArticulos = $this->_cantUnidades = $this->_cantAgregados = $this->_numeroRep = 0;
                // si no hay artículos para reponer manda mensaje
                if ($this->_cantidad == 0){ 
                    // carga alerta 
                    $oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
                    // ingresa los datos a representar en las alertas de la vista
                    $oDatoVista->setDato('{alertaInfo}',  'No hay artículos para reponer.');
                } else {
                    // carga alerta
                    $oCargarVista->setCarga('alertaInfo', '/includes/vista/alertaInfo.html');
                    // ingresa los datos a representar en las alertas de la vista
                    $oDatoVista->setDato('{alertaInfo}',  'Seleccione los rubros para reponer.');
                    // carga los eventos (botones)
                    $this->_aEventos = [
                        "reponerConfirmar" => "/modulos/reponer/vista/botonReponerConf.html",
                        "borrar" => "/includes/vista/botonBorrar.html"
                    ];
                    $oCargarVista->setCarga('aEventos', $this->_aEventos);
                }
                $oDatoVista->setDato('{cantidad}', $this->_cantidad); 
                break;
            # ----> acción Confirmar Reponer
            case 'ConfirmarR':
                // recibe datos por POST
                if(isset($_POST['rubro'])){
                    $this->_aRubros = $_POST['rubro'];
                }    
                // carga el contenido html
                
                // ingresa los datos a representar en el panel de la vista
                $oCargarVista->setCarga('datos', '/modulos/reponer/vista/reponerDatos.html');
                $oDatoVista->setDato('{tituloPanel}', 'Reponer Artículos');
                $oDatoVista->setDato('{informacion}', '<p>Genera una nueva reposición de artículos.</p><p>También puede seleccionar otras acciones</p><p>para las reposiciones, ver botones.');
                // carga los eventos (botones)
                
                // arma los datos a representar
                $this->_cantArticulos = $this->_cantUnidades = $this->_cantAgregados = $this->_numeroRep = 0;
                $oReponerModelo->countAgregarReponer(); // cuenta todos los artículos a reponer estado=1 numeroRep = 0
                $this->_cantidad = $oReponerModelo->getCantidad();
                
                $this->_items = $oReponerModelo->findAllPorRubros($this->_aRubros);
                foreach ($this->_items as $this->_item){
                    array_push($this->_aReposiciones, $this->_item['id']);
                }

                $this->_cantArticulos = $oReponerModelo->getCantidad();
                $oReponerModelo->sumaUnidadesReposicion($this->_numeroRep);
                $this->_cantUnidades = $oReponerModelo->getCantidad();
                $this->_numeroRep = $oReponerModelo->findNumeroRepAsignado();
                $this->_numeroRep = $this->_numeroRep + 1;
                $oReponerModelo->updateNumeroRep($this->_aReposiciones, $this->_numeroRep);
                $this->_cantAgregados = $oReponerModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad); 
                $oDatoVista->setDato('{numeroRep}', $this->_numeroRep); 
                $oDatoVista->setDato('{cantArticulos}', $this->_cantArticulos);
                $oDatoVista->setDato('{cantUnidades}', $this->_cantUnidades);
                $oDatoVista->setDato('{cantAgregados}', $this->_cantAgregados); 
                // carga alerta
                $oCargarVista->setCarga('alertaSuceso', '/includes/vista/alertaSuceso.html');
                // ingresa los datos a representar en las alertas de la vista
                $oDatoVista->setDato('{alertaSuceso}',  'Finalizó la tarea con exito!!!.');
                break;
            # ----> acción Descargar 
            case 'Descargar':
                // carga el contenido html
                
                // ingresa los datos a representar en el panel de la vista
                $oDatoVista->setDato('{tituloPanel}', 'Descarga de Reposiciones sin listar');
                $oDatoVista->setDato('{informacion}', '<p>Descarga las reposiciones realizadas sin listar.</p>
												<p>También puede seleccionar otras acciones</p>
												<p>para las reposiciones, ver botones.'
                    );
                // ingresa los datos a representar en las alertas de la vista
                
                // arma la tabla de datos a representar
                $this->_items = $oReponerModelo->findAllRepDescarga();
                $this->_cantidad = $oReponerModelo->getCantidad();
                $oDatoVista->setDato('{cantidad}', $this->_cantidad);
                
                foreach ($this->_items as $this->_item){
                    //echo "Numero Rep: ".$this->_item['numero_rep']."<br>";
                    $oReponerVO->setNumeroRep($this->_item['numero_rep']);
                    $oReponerModelo->findFechaReposicion($oReponerVO);
                    $oReponerModelo->countArticulosReposicion($this->_item['numero_rep']);
                    $this->_cantArticulos = $oReponerModelo->getCantidad();
                    $oReponerModelo->sumaUnidadesReposicion($this->_item['numero_rep']);
                    $this->_cantUnidades = $oReponerModelo->getCantidad();
                    $this->_aReposiciones[] = array(
                        'numero_rep' => $this->_item['numero_rep'],
                        'fecha_rep' => $oReponerVO->getFechaRep(),
                        'cant_articulos' => $this->_cantArticulos,
                        'cant_unidades' => $this->_cantUnidades
                    );
                }
                $this->_fechaDesde = $this->_fechaHasta = "";
                // ordena el array
                $this->_items = ArrayOrdenadoPor::ordenaArray($this->_aReposiciones, 'numero_rep', SORT_ASC);
                ReponerNumeroTabla::armaTabla($this->_cantidad, $this->_items, $this->_accion, $this->_fechaDesde, $this->_fechaHasta);
                $oCargarVista->setCarga('tabla', '/modulos/reponer/tabla.html');
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
