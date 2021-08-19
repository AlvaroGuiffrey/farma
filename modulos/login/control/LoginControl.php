<?php
// Se definen las clases necesarias
Clase::define('LoginModelo');

Class LoginControl 
{

	#Propiedades
	private $_rutaVistaModulo = "/modulos/login/vista/";
	private $_usuario;
	private $_clave;
	private $_mensajeLogin;
	
	#Métodos
	function __construct()
	{
		$this->_mensajeLogin = '';		
	}
	
	/**
	 * Permite ingresar al sistema a un usuario habilitado
	 */
	public function  login()
	{
		// Si viene boton Entrar
		if (isset($_POST['bt_entrar'])){
			$this->_mensajeLogin = '';
			if ($this->chequearDatos() == true){
				$oLoginVO = new LoginVO();
				$oLoginVO->setUsuario($this->_usuario);
				$oLoginVO->setClave($this->_clave);
				$this->nuevoLogin($oLoginVO);
			} 
		} 
		
		// Si viene boton Salir
		if (isset($_POST['bt_salir'])){
			logout();
		}
		
		// Ingreso de datos y vista
		$vista = $_SERVER['DOCUMENT_ROOT'].$_SESSION['dir'].$this->_rutaVistaModulo."login.html";
		$html = file_get_contents($vista);
		$html = str_replace('{dir}', $_SESSION['dir'], $html);
		if ($this->_mensajeLogin == ''){
			$html = str_replace('{mensaje}', '', $html);
		} else {
			$vista = $_SERVER['DOCUMENT_ROOT'].$_SESSION['dir'].$this->_rutaVista."loginMensaje.html";
			$htmlMensaje = file_get_contents($vista);
			$htmlMensaje = str_replace('{mensajeLogin}', $this->_mensajeLogin, $htmlMensaje);
			$html = str_replace('{mensaje}', $htmlMensaje, $html); 
		}
		echo $html;
	}
	
	/**
	 * Permite salir del sistema borrando los datos de la sesión
	 * y destruye la misma
	 */
	public function logout()
	{
		unset($_SESSION['token']);
		session_destroy();
		$vista = $_SERVER['DOCUMENT_ROOT'].$_SESSION['dir'].$this->_rutaVistaModulo."logout.html";
		$html = file_get_contents($vista);
		$html = str_replace('{dir}', $_SESSION['dir'], $html);
		echo $html;	
		exit();	
	}
	
	/**
	 * Permite crear un buen login del usuario.
	 */
	private function nuevoLogin($oLoginVO)
	{
		$this->_mensajeLogin = '';
		$oLoginModelo = new LoginModelo();
		$oLoginModelo->findPorUsuario($oLoginVO);
		if ($oLoginModelo->getCantidad() == 1 && $oLoginVO->getCategoria() > 0){
			if ($this->_clave==$oLoginVO->getClave()){
				// Registra variables de sesión
				$_SESSION['usuarioRegistrado'] = true;
				$_SESSION['usuarioHabilitado'] = $oLoginVO->getCategoria();
				$_SESSION['idUsuario'] = $oLoginVO->getIdUsuario();
				$_SESSION['alias'] = $oLoginVO->getAlias();
				$_SESSION['http-user-agent'] = md5($_SERVER['HTTP_USER_AGENT'].$oLoginVO->getIdUsuario().'alfonsin1983');
				// Regenera el identificador de sesión después de un buen login
				session_regenerate_id();
				// Direcciona a la página de aplicaciones
				header("Location: ".$_SESSION['dir']."/aplicaciones/index.php");
			}else{
				// Mensaje por un mal login
				$this->_mensajeLogin = "Contraseña erronea.";
				return false;
			}
		} else {
		$this->_mensajeLogin = "Usuario no habilitado.";
		return false;
		}
	}
	
	/**
	 * Permite chequear los datos para el login.
	 */
	private function chequearDatos()
	{
		$this->_usuario = htmlentities(trim($_POST['usuario']));
		$this->_clave = htmlentities(trim($_POST['clave']));
		if (ctype_alnum($this->_usuario) && strlen($this->_usuario) > 5){ 
			if (ctype_alnum($this->_clave) && strlen($this->_clave) > 5){
				return true;
			} else {
				$this->_mensajeLogin = "Datos de clave incorrectos.";
				return false;
			}
		} else {
			$this->_mensajeLogin = "Datos de usuario incorrectos.";
			return false;
		}
	}
	
	/**
	 * Permite cargar datos del usuario al LoginVO
	 */
	public function cargarUsuario($oLoginVO)
	{
		$oLoginVO->setIdUsuario($_SESSION['idUsuario']);
		$oLoginModelo = new LoginModelo();
		$oLoginModelo->find($oLoginVO);
		return $oLoginVO;
	}
	
	/**
	 * Permite chequear si es un buen login.
	 * Compara los datos de LoginVO con los de la sesión,
	 * si hay inconsistencias sale del sistema (logout). 
	 */
	public function chequearLogin($oLoginVO)
	{
		// Chequea el login del usuario
		//if ($oLoginModelo->getCantidad() == 0) $this->logout();
		// Verifica que el usuario se encuentra registrado
		if ($_SESSION['usuarioRegistrado']==false) $this->logout();
				
		// Verifica si el usuario se encuentra habilitado
		if ($_SESSION['usuarioHabilitado']=='' or $_SESSION['usuarioHabilitado'] != $oLoginVO->getCategoria()) $this->logout();
		
		// Verificar si el usuario posee un id de usuario asociado
		if ($_SESSION['idUsuario']=='' or $_SESSION['idUsuario'] != $oLoginVO->getIdUsuario()) $this->logout();
		
		//Verificar si el usuario posee un alias
		if ($_SESSION['alias']=='' or $_SESSION['alias'] != $oLoginVO->getAlias()) $this->logout();
		
		// Verificar que el user-agent + la palabra secreta sean iguales
		if ($_SESSION['http-user-agent'] != md5($_SERVER['HTTP_USER_AGENT'].$oLoginVO->getIdUsuario().'alfonsin1983')) $this->logout();
	}
}