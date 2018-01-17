<?php
class Atributo {
	public $nombre;
	public $tipo;
	public $coleccion;
	public $modo;
	
	public function __construct($nombre, $tipo, $coleccion, $modo) {
		$this->nombre = $nombre;
		$this->tipo = $tipo;
		$this->coleccion = $coleccion;
		$this->modo = $modo;
	}
	
	public function esDependiente() {
		$m = $this->modo;
		return $m == 'O2O' || $m == 'M2M' || $m == 'M2Mi' || $m == 'M2O' || $m == 'O2M';
	}
}

// ------------------------------
class Clase {
	public $nombre;
	public $atributos = [ ];
	
	public function __construct($nombre) {
		$this->nombre = $nombre;
	}
	
	public function addAtributo($atributo) {
		array_push ( $this->atributos, $atributo );
	}
	
	public function tieneColecciones() {
		$respuesta = false;
		foreach ( $this->atributos as $a ) {
			$respuesta |= $a->collection;
		}
		return $respuesta;
	}
	
	public function tieneDependientes() {
		$respuesta = false;
		foreach ( $this->atributos as $a ) {
			$respuesta |= $a->is_dependant ();
		}
		return $respuesta;
	}
}

// ===================================================================
// ===================================================================
// ===================================================================

function generate_menus($menuData, $appTitle) {
	return "$appTitle\t$menuData";
}
// ===================================================================

function plural($palabra) {
	$ultimaLetra = substr ( $palabra, - 1, 1 );
	$vocales = [
			'a',
			'e',
			'i',
			'o',
			'u'
	];
	return $palabra . (in_array ( $ultimaLetra, $vocales ) ? 's' : 'es');
}

// ---------------------------------------------
function process_domain_model($fichero = 'model.txt') {
	echo "Abriendo fichero " . $fichero . "\n";
	$lineas = file_get_contents ( $fichero );
	echo "Procesando fichero " . $fichero . "\n";
	
	$clases = [ ];
	$clase = true; // Comenzamos procesando el nombre de una clase
	$claseActual = null;
	
	foreach ( explode ( "\n", $lineas ) as $linea ) {
		$linea = trim ( $linea );
		
		if ($linea == "") {
		} else if ($linea == "--") {
			$clase = ! $clase;
			if ($clase) { // Hemos terminado de procesar atributos
				array_push ( $clases, $claseActual );
				$claseActual = null;
			} else { // Estamos comenzando a procesar atributos
			}
		} else {
			if ($clase) {
				$nombreClase = $linea;
				$claseActual = new Clase ( $nombreClase );
			} else { // ATRIBUTO
				$modo = 'SIN_MODO';
				$tipo = 'String';
				$nombre = 'SIN_NOMBRE';
				$coleccion = false;
				
				if (strlen ( $linea ) > 1) {
					if (substr ( $linea, 0, 2 ) == '**') {
						$coleccion = true;
						$modo = "M2M";
					} else if (substr ( $linea, 0, 2 ) == '*>') {
						$coleccion = true;
						$modo = 'M2Mi';
					} else if (substr ( $linea, 0, 1 ) == '*') {
						$coleccion = true;
						$modo = 'O2M';
					} else if (substr ( $linea, 0, 1 ) == '>') {
						$modo = "M2O";
					} else if (substr ( $linea, 0, 1 ) == '!') {
						$modo = "U";
					} else if (substr ( $linea, 0, 1 ) == '.') {
						$modo = "O2O";
					}
					
					$miLinea = trim ( $linea, '.!>*' );
					$parte = explode ( ':', $miLinea );
					$nombre = $parte [0];
					$tipo = sizeof ( $parte ) == 1 ? 'String' : $parte [1];
				} else { // Un �nico car�cter. El nombre del atributo
					$nombre = substr ( $linea, 0, 1 );
				}
				
				$claseActual->addAtributo ( new Atributo ( $nombre, $tipo, $coleccion, $modo ) );
			}
		}
	}
	
	return $clases;
}

// ------------------------------
function delete_directory($dir) {
	if (! file_exists ( $dir )) {
		return true;
	}
	
	if (! is_dir ( $dir )) {
		return unlink ( $dir );
	}
	
	foreach ( scandir ( $dir ) as $item ) {
		if ($item == '.' || $item == '..') {
			continue;
		}
		
		if (! delete_directory ( $dir . DIRECTORY_SEPARATOR . $item )) {
			return false;
		}
	}
	
	return rmdir ( $dir );
}

// ------------------------------
function addDomainInicio($clase) {
	global $packageApp, $pathApp;
	
	$paquete = $packageApp == '' ? $packageApp : $packageApp . '.';
	$importColeccion = ($clase->tieneColecciones () ? "import java.util.Set;\nimport java.util.HashSet;\n" : '');
	$importDomain = '';
	foreach ( $clase->atributos as $a ) {
		$importDomain .= ($a->collection ? "import {$paquete}domain.$a->tipo;\n" : '');
	}
	
	$codigo = <<<TEXTO
package {$paquete}domain;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import javax.persistence.Id;
import javax.persistence.CascadeType;
import javax.persistence.FetchType;
import javax.persistence.ManyToMany;
import javax.persistence.OneToMany;
import javax.persistence.ManyToOne;
import javax.persistence.OneToOne;
{$importColeccion}
{$importDomain}

@Entity
public class {$clase->nombre} {
		
	@Id
	@GeneratedValue
	private Long id;
		
	public Long getId() {
		return this.id;
	}
		
	public void setId(Long id) {
		this.id = id;
	}
		
		
TEXTO;

return $codigo;
}

// ------------------------------
function is_dependant($atributo) {
	// var_dump ( $atributo ); // TODO DEBUG
	$t = $atributo->modo;
	return $t == "M2M" || $t == "M2Mi" || $t == "M2O" || $t == "O2M" || $t == "O2O";
}

// ------------------------------
function addDomainAtributo($clase, $atributo) {
	$codigo = '';
	
	$anotacion = '';
	$tipo = $atributo->tipo;
	$nombre = $atributo->nombre;
	$coleccion = false;
	
	$fetchLAZY = 'fetch=FetchType.LAZY';
	$fetchEAGER = 'fetch=FetchType.EAGER';
	$cascadeALL = 'cascade = {CascadeType.ALL}';
	
	switch ($atributo->modo) {
		case 'M2M' :
			$coleccion = true;
			$anotacion = "@ManyToMany ( $cascadeALL, $fetchLAZY )";
			break;
		case 'M2Mi' :
			$coleccion = true;
			$vocales = [
					'a',
					'e',
					'i',
					'o',
					'u'
			];
			$ultimaLetra = substr ( $clase->nombre, - 1, 1 );
			// $anotacion = '@ManyToMany ( mappedBy )'; //DEBUG
			
			$anotacion = "@ManyToMany ( $cascadeALL ,  $fetchLAZY, mappedBy = \"" . lcfirst ( $clase->nombre ) . (in_array ( $ultimaLetra, $vocales ) ? 's' : 'es') . '" )';
			break;
		case 'O2M' :
			$coleccion = true;
			$anotacion = "@OneToMany ( $cascadeALL ,  $fetchLAZY, mappedBy = \"" . lcfirst ( $clase->nombre ) . '" )';
			break;
		case 'M2O' :
			$anotacion = "@ManyToOne ( $cascadeALL ,  $fetchLAZY ) ";
			break;
		case 'O2O' :
			$anotacion = "@OneToOne ( $cascadeALL ,  $fetchLAZY )";
			break;
		case 'U' :
			$anotacion = "@Column(unique = true)";
			break;
	}
	
	// ANOTACION
	$codigo .= $anotacion != '' ? "\t$anotacion\n" : '';
	
	// ATRIBUTO
	$codigo .= "\tprivate " . ($coleccion ? 'Set<' : '') . $tipo . ($coleccion ? '>' : '') . " $nombre" . ($coleccion ? " = new HashSet<$tipo>()" : '') . ";\n\n";
	
	// GETTER
	$codigo .= "\tpublic " . ($coleccion ? 'Set<' : '') . $tipo . ($coleccion ? '>' : '') . " get" . ucfirst ( $nombre ) . "() {\n\t\treturn this.$nombre;\n\t}\n\n";
	
	// SETTER
	$codigo .= "\tpublic void set" . ucfirst ( $nombre ) . "(" . ($coleccion ? 'Set<' : '') . $tipo . ($coleccion ? '>' : '') . " $nombre) {\n\t\tthis.$nombre = $nombre;\n\t}\n\n";
	
	// ADDTO
	$codigo .= $coleccion ? "\tpublic void addTo" . ucfirst ( $nombre ) . '(' . $tipo . ' ' . lcfirst ( $tipo ) . ") {\n\t\tthis." . $nombre . ".add(" . lcfirst ( $tipo ) . ");\n\t}\n\n" : '';
	
	return $codigo;
}
// ------------------------------
function addDomainFin() {
	$codigo = <<<TEXTO
}
TEXTO;
	return $codigo;
}

// ------------------------------
function addMain() {
	global $packageApp, $pathApp;
	$nombreApp = ucfirst ( array_pop ( explode ( '.', $packageApp ) ) );
	
	$texto = <<<TEXTO
package $packageApp;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;

@SpringBootApplication
public class $nombreApp {

	public static void main(String[] args) {
		SpringApplication.run($nombreApp.class, args);
	}
}
TEXTO;
	return $texto;
}

// ------------------------------
function write_main($codigo) {
	global $packageApp, $pathApp;
	$nombreApp = ucfirst ( array_pop ( explode ( '.', $packageApp ) ) );
	
	echo "Creando {$pathApp}/" . $nombreApp . ".java\n";
	if (! file_exists ( $pathApp )) {
		mkdir ( $pathApp, 0777, true );
	}
	file_put_contents ( $pathApp . '/' . $nombreApp . '.java', $codigo );
}

// ------------------------------
function process_main() {
	write_main ( addMain () );
}

// ------------------------------
function escribirTemplatesFooter($codigo) {
	global $packageApp, $pathApp;
	
	echo "Creando {$pathApp}/view/_templates/footer.html\n";
	if (! file_exists ( $pathApp.'/view/_templates' )) {
		mkdir ( $pathApp.'/view/_templates', 0777, true );
	}
	file_put_contents ( $pathApp . '/view/_templates/footer.html', $codigo );
}

// ------------------------------
function escribirTemplatesHead($codigo) {
	global $packageApp, $pathApp;
	
	echo "Creando {$pathApp}/view/_templates/head.html\n";
	if (! file_exists ( $pathApp.'/view/_templates' )) {
		mkdir ( $pathApp.'/view/_templates', 0777, true );
	}
	file_put_contents ( $pathApp . '/view/_templates/head.html', $codigo );
}

// ------------------------------
function escribirTemplatesHeader($codigo) {
	global $packageApp, $pathApp;
	
	echo "Creando {$pathApp}/view/_templates/header.html\n";
	if (! file_exists ( $pathApp.'/view/_templates' )) {
		mkdir ( $pathApp.'/view/_templates', 0777, true );
	}
	file_put_contents ( $pathApp . '/view/_templates/header.html', $codigo );
}

// ------------------------------
function escribirTemplatesMASTER($codigo) {
	global $packageApp, $pathApp;
	
	echo "Creando {$pathApp}/view/_templates/_MASTER.html\n";
	if (! file_exists ( $pathApp.'/view/_templates' )) {
		mkdir ( $pathApp.'/view/_templates', 0777, true );
	}
	file_put_contents ( $pathApp . '/view/_templates/_MASTER.html', $codigo );
}

// ------------------------------
function escribirTemplatesNav($codigo) {
	global $packageApp, $pathApp;
	
	echo "Creando {$pathApp}/view/_templates/nav.html\n";
	if (! file_exists ( $pathApp.'/view/_templates' )) {
		mkdir ( $pathApp.'/view/_templates', 0777, true );
	}
	file_put_contents ( $pathApp . '/view/_templates/nav.html', $codigo );
}


// ------------------------------
function procesarTemplates() {
	escribirTemplatesFooter( addViewTemplatesFooter() );
	escribirTemplatesHead( addViewTemplatesHead() );
	escribirTemplatesHeader( addViewTemplatesHeader() );
	escribirTemplatesMASTER( addViewTemplatesMASTER() );
	escribirTemplatesNav( addViewTemplatesNav() );
}

// ------------------------------
function procesarClases($clases) {
	borrarEstructuraApp ();
	process_main ();
	procesarTemplates();
	procesarDomain ( $clases );
	procesarRepository ( $clases );
	procesarController ( $clases );
	procesarView ( $clases );
}

// ------------------------------
function procesarRepository($clases) {
	foreach ( $clases as $clase ) {
		escribirRepository ( $clase->nombre );
	}
}

// ------------------------------
function addControllerInicio($clase) {
	global $packageApp, $pathApp;
	
	$paquete = $packageApp == '' ? $packageApp : $packageApp . '.';
	$importDomain = "import {$paquete}domain.{$clase->nombre};\n";
	$importRepo = "import {$paquete}repository.{$clase->nombre}Repository;\n";
	foreach ( $clase->atributos as $a ) {
		$importDomain .= ($a->is_dependant () ? "import {$paquete}domain.$a->tipo;\n" : '');
		$importRepo .= ($a->is_dependant () ? "import {$paquete}repository.{$a->tipo}Repository;\n" : '');
	}
	
	$codigo = <<<TEXTO
package {$paquete}controller;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.ModelMap;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.RequestParam;
import java.util.List;

{$importDomain}
{$importRepo}

@Controller
public class {$clase->nombre}Controller {
	@Autowired
	private {$clase->nombre}Repository repo{$clase->nombre};
			
			
TEXTO;
foreach ( $clase->atributos as $atributo ) {
	$mayuscula = ucfirst ( $atributo->tipo );
	if (is_dependant ( $atributo )) {
		$codigo .= <<<TEXTO
	@Autowired
	private {$mayuscula}Repository repo{$mayuscula};
			
			
TEXTO;
	}
}
return $codigo;
}

// ------------------------------
function addControllerCrearGet($clase) {
	$minuscula = lcfirst ( $clase->nombre );
	$codigo = <<<TEXTO
	@RequestMapping(value = "/{$minuscula}/crear", method = RequestMethod.GET)
	public String crearGet(
			
TEXTO;
	$codigo .= $clase->tieneDependientes () ? "\t\t\tModelMap model\n" : '';
	$codigo .= <<<TEXTO
			) {
			
TEXTO;
	foreach ( $clase->atributos as $atributo ) {
		$nombrePlural = $atributo->collection ? $atributo->nombre : plural ( $atributo->nombre );
		$codigo .= (($atributo->is_dependant () ? "\t\tList<{$atributo->tipo}> {$nombrePlural} = this.repo{$atributo->tipo}.findAll();\n" : ''));
		$codigo .= ($atributo->is_dependant () ? "\t\tmodel.put(\"{$nombrePlural}\", {$nombrePlural});\n" : '');
	}
	$codigo .= <<<TEXTO
		return "view/{$minuscula}/crearGet";
	}
				
				
TEXTO;
	return $codigo;
}

// ------------------------------
function addControllerCrearPost($clase) {
	$minuscula = lcfirst ( $clase->nombre );
	$codigo = <<<TEXTO
	@RequestMapping(value = "/{$minuscula}/crear", method = RequestMethod.POST)
	public String crearPost() {
		return "view/{$minuscula}/crearPost";
	}
				
				
TEXTO;
	return $codigo;
}

// ------------------------------
function addControllerListarGet($clase) {
	$minuscula = lcfirst ( $clase->nombre );
	$codigo = <<<TEXTO
	@RequestMapping(value = "/{$minuscula}/listar", method = RequestMethod.GET)
	public String listarGet() {
		return "view/{$minuscula}/listarGet";
	}
				
				
TEXTO;
	return $codigo;
}

// ------------------------------
function addControllerEditarGet($clase) {
	$minuscula = lcfirst ( $clase->nombre );
	$codigo = <<<TEXTO
	@RequestMapping(value = "/{$minuscula}/editar", method = RequestMethod.GET)
	public String editarGet() {
		return "view/{$minuscula}/editarGet";
	}
				
				
TEXTO;
	return $codigo;
}

// ------------------------------
function addControllerEditarPost($clase) {
	$minuscula = lcfirst ( $clase->nombre );
	$codigo = <<<TEXTO
	@RequestMapping(value = "/{$minuscula}/editar", method = RequestMethod.POST)
	public String editarPost() {
		return "view/{$minuscula}/editarPost";
	}
				
				
TEXTO;
	return $codigo;
}

// ------------------------------
function addControllerBorrarGet($clase) {
	$minuscula = lcfirst ( $clase->nombre );
	$codigo = <<<TEXTO
	@RequestMapping(value = "/{$minuscula}/borrar", method = RequestMethod.GET)
	public String borrarGet() {
		return "view/{$minuscula}/borrarGet";
	}
				
				
TEXTO;
	return $codigo;
}

// ------------------------------
function addControllerBorrarPost($clase) {
	$minuscula = lcfirst ( $clase->nombre );
	$codigo = <<<TEXTO
	@RequestMapping(value = "/{$minuscula}/borrar", method = RequestMethod.POST)
	public String borrarPost() {
		return "view/{$minuscula}/borrarPost";
	}
				
				
TEXTO;
	return $codigo;
}

// ------------------------------
function addControllerFin() {
	$codigo = <<<TEXTO
}
TEXTO;
	return $codigo;
}

// ------------------------------
function addViewInicio() {
}

// ------------------------------
function addViewClase($clase) {
}

// ------------------------------
function addViewCrearGet($clase) {
}

// ------------------------------
function addViewCrearPost($clase) {
}

// ------------------------------
function addViewListarGet($clase) {
}

// ------------------------------
function addViewEditarGet($clase) {
}

// ------------------------------
function addViewEditarPost($clase) {
}

// ------------------------------
function addViewBorrarGet($clase) {
}

// ------------------------------
function addViewBorrarPost($clase) {
}

// ------------------------------
function addViewFin() {
}

// ------------------------------
function addViewTemplatesMASTER() {
	$texto = <<<'TEXTO'
	<!DOCTYPE html>
	<html xmlns:th="http://thymeleaf.org">
	<div th:include="view/_templates/head :: head"></div>
	<body onload="">
	<header th:replace="view/_templates/header :: header"></header>
	<nav th:replace="view/_templates/nav :: nav"></nav>
	<div th:replace="${view} :: div"></div>
	<footer th:replace="view/_templates/footer:: footer"></footer>
	</body>
	</html>
TEXTO;
	
	return $texto;
}


// ------------------------------
function addViewTemplatesFooter() {
	$texto = <<<TEXTO
<footer>
			
</footer>
TEXTO;
	
	return $texto;
}

// ------------------------------
function addViewTemplatesHeader() {
	$texto = <<<'TEXTO'
	<header class="container">
	<div class="row">
	<img src="assets/img/emple.jpg" class="img-rounded  center-block" alt="Empleados trabajando" height="100"/>
	<div class="pull-right">
	<!--
	<c:choose>
	  <c:when test="${empty empleadoNombre}">
	  		<a href="/empleado/login">LOGIN</a>
	  </c:when>
	  <c:otherwise>
	  		${empleadoNombre} ${empleadoApe1} <a href="${baseURL}t8/ej07/empleado/logout">LOGOUT</a>
	  </c:otherwise>
	</c:choose>
	 -->
	</div>
	</div>
	</header>
TEXTO;
	
	return $texto;
}

// ------------------------------
function addViewTemplatesNav() {
	$texto = <<<'TEXTO'
	<nav class="container navbar navbar-inverse">
  <div class="navbar-header">
    <a class="navbar-brand" href="view/home">CRUD-emple</a>
  </div>
  <div class="collapse navbar-collapse" id="myNavbar">
    <ul class="nav navbar-nav">
			
      <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
           Empleado<span class="caret"></span>
        </a>
		<ul class="dropdown-menu">
			
		  <?php if (isset($header['empleado']['nombre'])): ?>
		  <li><a href="/empleado/crear">Crear</a></li>
		  <li><a href="/empleado/modificar">Modificar</a></li>
		  <li><a href="/empleado/borrar">Borrar</a></li>
		  <?php endif;?>
			
		  <li><a href="${baseURL}t8/ej07/empleado/listar">Listar</a></li>
			
	     </ul>
      </li>
			
      <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
           Ciudad<span class="caret"></span>
        </a>
		<ul class="dropdown-menu">
		  <?php if (isset($header['empleado']['nombre'])): ?>
		  <li><a href="/ciudad/crear">Crear</a></li>
		  <li><a href="/ciudad/modificar">Modificar</a></li>
		  <li><a href="/ciudad/borrar">Borrar</a></li>
		  <?php endif;?>
			
		  <li><a href="/ciudad/listar">Listar</a></li>
	     </ul>
      </li>
			
      <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
           Lenguaje de programaci�n<span class="caret"></span>
        </a>
		<ul class="dropdown-menu">
		  <?php if (isset($header['empleado']['nombre'])): ?>
		  <li><a href="/lp/crear">Crear</a></li>
		  <li><a href="/lp/modificar">Modificar</a></li>
		  <li><a href="/lp/borrar">Borrar</a></li>
		  <?php endif;?>
		  <li><a href="/lp/listar">Listar</a></li>
	     </ul>
      </li>
			
			
			
    </ul>
  </div>
</nav>
TEXTO;
	
	return $texto;
}

// ------------------------------
function addViewTemplatesHead() {
	$texto = <<<TEXTO
	<head>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<title>TITULO APP</title>
	</head>
TEXTO;
	
	return $texto;
}

//------------------------------
/*
function enmarcar($vista) {
	$texto = <<<TEXTO
		model.put("view","view/$vista");
		return "view/_templates/_MASTER";
TEXTO;
	return $texto;
}
*/

// ------------------------------
function addControllerHome() {
	global $packageApp;
	$paquete = $packageApp == '' ? $packageApp : $packageApp . '.';
	$despliegueVista= enmarcar("home/index");
	$codigo = <<<HTML
package {$paquete}controller;

import org.springframework.stereotype.Controller;
import org.springframework.ui.ModelMap;
import org.springframework.web.bind.annotation.RequestMapping;

@Controller
public class HomeController {
	@RequestMapping(value="/")
	public String index(ModelMap model) {
$despliegueVista
	}
}
HTML;
	return $codigo;
}
// ------------------------------
function procesarController($clases) {
	$codigo = addControllerHome ();
	escribirController ( "Home", $codigo );
	
	foreach ( $clases as $clase ) {
		$codigo = addControllerInicio ( $clase );
		$codigo .= addControllerCrearGet ( $clase );
		$codigo .= addControllerCrearPost ( $clase );
		$codigo .= addControllerListarGet ( $clase );
		$codigo .= addControllerEditarGet ( $clase );
		$codigo .= addControllerEditarPost ( $clase );
		$codigo .= addControllerBorrarGet ( $clase );
		$codigo .= addControllerBorrarPost ( $clase );
		$codigo .= addControllerFin ();
		escribirController ( $clase->nombre, $codigo );
	}
}
// ------------------------------
function addViewHome() {
	$texto = <<< TEXTO
<div class="container">
<h1>
Bienvenido a la aplicaci�n APP
</h1>
</div>
TEXTO;
	return $texto;
}
// ------------------------------
function procesarView($clases) {
	escribirView ( 'Home', addViewHome (), 'index' );
	foreach ( $clases as $clase ) {
		$codigo = addViewCrearGet ( $clase );
		escribirView ( $clase->nombre, $codigo, 'crearGet' );
		
		$codigo = addViewCrearPost ( $clase );
		escribirView ( $clase->nombre, $codigo, 'crearPost' );
		
		$codigo = addViewListarGet ( $clase );
		escribirView ( $clase->nombre, $codigo, 'listarGet' );
		
		$codigo = addViewEditarGet ( $clase );
		escribirView ( $clase->nombre, $codigo, 'editarGet' );
		
		$codigo = addViewEditarPost ( $clase );
		escribirView ( $clase->nombre, $codigo, 'editarPost' );
		
		$codigo = addViewBorrarGet ( $clase );
		escribirView ( $clase->nombre, $codigo, 'borrarGet' );
		
		$codigo = addViewBorrarPost ( $clase );
		escribirView ( $clase->nombre, $codigo, 'borrarPost' );
	}
}

// ------------------------------
function procesarDomain($clases) {
	foreach ( $clases as $clase ) {
		$codigo = addDomainInicio ( $clase );
		foreach ( $clase->atributos as $atributo ) {
			$codigo .= addDomainAtributo ( $clase, $atributo );
		}
		$codigo .= addDomainFin ();
		escribirDomain ( $clase->nombre, $codigo );
	}
}

// ------------------------------
function borrarEstructuraApp() {
	global $packageApp, $pathApp;
	if ($pathApp != '.') {
		$dirRaiz = explode ( '/', $pathApp ) [0];
		echo "Borrando directorio {$dirRaiz}\n";
		delete_directory ( $dirRaiz );
	} else {
		echo "Borrando directorio controller\n";
		delete_directory ( 'controller' );
		
		echo "Borrando directorio domain\n";
		delete_directory ( 'domain' );
		
		echo "Borrando directorio repository\n";
		delete_directory ( 'repository' );
		
		echo "Borrando directorio view\n";
		delete_directory ( 'view' );
	}
}

// ------------------------------
function escribirDomain($nombreClase, $codigo) {
	global $packageApp, $pathApp;
	
	echo "Creando {$pathApp}/domain/" . ucfirst ( $nombreClase ) . ".java\n";
	if (! file_exists ( $pathApp . '/domain' )) {
		mkdir ( $pathApp . '/domain', 0777, true );
	}
	file_put_contents ( $pathApp . '/domain/' . ucfirst ( $nombreClase ) . '.java', $codigo );
}

// ------------------------------
function escribirController($nombreClase, $codigo) {
	global $packageApp, $pathApp;
	
	echo "Creando {$pathApp}/controller/" . ucfirst ( $nombreClase ) . "Controller.java\n";
	if (! file_exists ( $pathApp . '/controller' )) {
		mkdir ( $pathApp . '/controller', 0777, true );
	}
	file_put_contents ( $pathApp . '/controller/' . ucfirst ( $nombreClase ) . 'Controller.java', $codigo );
}

// ------------------------------
function escribirView($nombreClase, $codigo, $accion) {
	global $packageApp, $pathApp;
	
	echo "Creando {$pathApp}/view/" . lcfirst ( $nombreClase ) . '/' . $accion . ".html\n";
	if (! file_exists ( $pathApp . '/view/' . lcfirst ( $nombreClase ) )) {
		mkdir ( $pathApp . '/view/' . lcfirst ( $nombreClase ), 0777, true );
	}
	file_put_contents ( $pathApp . '/view/' . lcfirst ( $nombreClase ) . '/' . $accion . '.html', $codigo );
}

// ------------------------------
function escribirRepository($nombreClase) {
	global $packageApp, $pathApp;
	
	echo "Creando {$pathApp}/repository/" . ucfirst ( $nombreClase ) . "Repository.java\n";
	$codigo = <<< TEXTO
	package $packageApp.repository;
	
	import org.springframework.data.jpa.repository.JpaRepository;
	import org.springframework.stereotype.Repository;
	
	import $packageApp.domain.{$nombreClase};
	
	@Repository
	public interface {$nombreClase}Repository extends JpaRepository<{$nombreClase}, Long> {
	}
TEXTO;
	
	if (! file_exists ( $pathApp . '/repository' )) {
		mkdir ( $pathApp . '/repository', 0777, true );
	}
	file_put_contents ( $pathApp . '/repository/' . ucfirst ( $nombreClase ) . 'Repository.java', $codigo );
}

// ===================================================================
// ========================== M A I N ================================
// ===================================================================
/*
$packageApp = sizeof ( $argv ) > 1 ? $argv [1] : '';
$pathApp = $packageApp == '' ? '.' : str_replace ( '.', '/', $packageApp );
$clases = procesarModeloDeDominio ();
procesarClases ( $clases );
*/
?>