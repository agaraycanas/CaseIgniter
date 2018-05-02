<?php
class Attribute {
	public $name;
	public $type;
	public $collection;
	public $mode;
	public $hidden_create;
	public $hidden_recover;
	public $main;
	public function __construct($name, $type, $collection, $mode, $hidden_create = false, $hidden_recover = false, $main = false) {
		$this->name = $name;
		$this->type = $type;
		$this->collection = $collection;
		$this->mode = $mode;
		$this->hidden_create = $hidden_create;
		$this->hidden_recover = $hidden_recover;
		$this->main = $main;
	}
	public function is_dependant() {
		$m = $this->mode;
		return $m == 'O2O' || $m == 'M2M' || $m == 'M2Mi' || $m == 'M2O' || $m == 'O2M';
	}
}

// ------------------------------
class MyClass {
	public $name;
	public $attributes = [ ];
	public $login_bean = false;
	public function __construct($name) {
		$this->name = $name;
	}
	public function add_attribute($attribute) {
		array_push ( $this->attributes, $attribute );
	}
	public function add_main_attribute($attribute) {
		array_unshift ( $this->attributes, $attribute );
	}
	public function has_collections() {
		$answer = false;
		foreach ( $this->attributes as $a ) {
			$answer |= $a->collection;
		}
		return $answer;
	}
	public function has_images() {
		$answer = false;
		foreach ( $this->attributes as $a ) {
			$answer |= ($a->type == 'file');
		}
		return $answer;
	}
	public function has_dependants() {
		$answer = false;
		foreach ( $this->attributes as $a ) {
			$answer |= $a->is_dependant ();
		}
		return $answer;
	}
	public function setMainAttribute() {
		$exist_main = false;
		foreach ( $this->attributes as $a ) {
			$exist_main |= ($a->main);
		}
		if (! $exist_main) {
			$this->add_main_attribute ( new Attribute ( "nombre", "String", false, "NO_MODE", false, false, true ) ); // TODO LOC
		}
	}
	public function getMainAttribute() {
		$name = 'nombre'; // TODO LOC
		foreach ( $this->attributes as $a ) {
			if ($a->main) {
				$name = $a->name;
			}
		}
		return $name;
	}
}

// ===================================================================
// ===================================================================
// ===================================================================

// ===================================================================
function plural($word) {
	$last_char = substr ( $word, - 1, 1 );
	$vowels = [ 
			'a',
			'e',
			'i',
			'o',
			'u' 
	];
	return $word . (in_array ( $last_char, $vowels ) ? 's' : 'es');
}

// ---------------------------------------------
function classes_have_login_bean($classes) {
	$response = false;
	foreach ( $classes as $class ) {
		$response |= $class->login_bean;
	}
	return $response;
}

// ---------------------------------------------
function create_rol_class($login_bean_name) {
	$rol_class = new MyClass ( 'rol' );
	$rol_class->attributes [] = new Attribute ( 'nombre', 'String', false, 'NO_MODE', false, false, true );
	$rol_class->attributes [] = new Attribute ( 'descripcion', 'String', false, 'NO_MODE', false, false, false );
	$rol_class->attributes [] = new Attribute ( 'roles', $login_bean_name, true, 'M2M', false, false, false );
	
	return $rol_class;
}

// ---------------------------------------------
function generate_admin($classes) {
	$login_bean = null;
	foreach ( $classes as $c ) {
		if ($c->login_bean) {
			$login_bean = $c;
		}
	}
	
	// CREATE DEFAULT ROL IF NOT EXISTS
	if (R::findOne ( 'rol', 'nombre=?', [ 
			'default' 
	] ) == null) {
		$rol = R::dispense ( 'rol' );
		$rol->nombre = 'default';
		$rol->descripcion = 'Default user - SET YOURS';
		R::store ( $rol );
	}
	
	// CREATE ADMIN ROL IF NOT EXISTS
	$rol = null;
	$rol_id = 0;
	if (R::findOne ( 'rol', 'nombre=?', [ 
			'admin' 
	] ) == null) {
		$rol = R::dispense ( 'rol' );
		$rol->nombre = 'admin';
		$rol->descripcion = 'Administrador';
		$rol_id = R::store ( $rol );
	} else {
		$rol = R::findOne ( 'rol', 'nombre = ?', [ 
				'admin' 
		] );
		$rol_id = $rol->id;
	}
	
	// CREATE ADMIN USER IF NOT EXISTS
	$ma = $login_bean->getMainAttribute ();
	$cn = $login_bean->name;
	$admin = null;
	$admin_id = 0;
	if (R::findOne ( $cn, 'loginname = ?', [ 
			'admin' 
	] ) == null) {
		$admin = R::dispense ( $cn );
		$admin->loginname = 'admin';
		$admin->password = password_hash ( 'admin', PASSWORD_DEFAULT );
		$admin->$ma = 'Administrador';
		$admin_id = R::store ( $admin );
	} else {
		$admin = R::findOne ( $cn, 'loginname = ?', [ 
				'admin' 
		] );
		$admin_id = $admin->id;
	}
	
	// ASSIGN ADMIN ROL TO ADMIN USER IF NOT ASSIGNED YET
	try {
		if (R::findOne ( 'roles', $cn . '_id = ? AND rol_id = ?', [ 
				$admin_id,
				$rol_id 
		] ) == null) {
			$roles = R::dispense ( 'roles' );
			$roles->rol = $rol;
			$roles->$cn = $admin;
			$roles->rol = $rol;
			R::store ( $roles );
		}
	} catch ( Exception $e ) {
	}
}

// ---------------------------------------------
function set_bean_class($classes) {
	$rol_class = null;
	if (classes_have_login_bean ( $classes )) {
		foreach ( $classes as $c ) {
			if ($c->login_bean) {
				$rol_class = create_rol_class ( $c->name );
				$c->attributes [] = new Attribute ( 'loginname', 'String', false, 'NO_MODE', false, false, false );
				$c->attributes [] = new Attribute ( 'password', 'String', false, 'NO_MODE', false, false, false );
				$c->attributes [] = new Attribute ( 'roles', 'rol', true, 'M2M', false, false, false );
			}
		}
	}
	return $rol_class;
}

// ---------------------------------------------
function process_domain_model($modelData) {
	
	// ----------------------------------
	
	/**
	 *
	 * @param unknown $line        	
	 * @return $string BEAN_SEPARATOR, ATTRIBUTE_SEPARATOR, BEAN_NAME, ROL_LINE, ATTRIBUTE or UNKNOWN
	 */
	function pdm_line_type($line) {
		$type = 'UNKNOWN';
		
		if (preg_match ( "/[=]+$/", $line )) {
			$type = 'BEAN_SEPARATOR';
		}
		if (preg_match ( "/[\.]+$/", $line )) {
			$type = 'ATTRIBUTE_SEPARATOR';
		}
		if (preg_match ( "/^[A-Z]+(\s\[login\])?$/", $line )) {
			$type = 'BEAN_NAME';
		}
		if (preg_match ( "/^(\s)*[crud]\s\([a-z]+(,[a-z]+)*\)$/", $line )) {
			$type = 'ROL_LINE';
		}
		if (preg_match ( "/^((\*\*|\*>|<>|<\*)\s)?[a-z_]+(:([a-z]+|\@|\%|\#))?(\s\[[a-zA-Z\-]+(,[a-zA-Z\-]+)*\])?$/", $line )) {
			$type = 'ATTRIBUTE';
		}
		
		return $type;
	}
	
	// ----------------------------------
	function pdm_process_bean_name($line, $classes) {
		$class = new MyClass ( strtolower ( explode ( ' ', $line ) [0] ) );
		if (strpos ( $line, ' ' )) {
			if (! classes_have_login_bean ( $classes )) {
				$class->login_bean = true;
			} else {
				throw (new Exception ( "ERROR while parsing model.txt: Only one LOGIN bean allowed" ));
			}
		}
		return $class;
	}
	
	// =============================================================
	function pdm_process_attribute($line) {
		error_reporting ( 0 );
		$name = 'NO_NAME';
		$type = 'String';
		$collection = false;
		$mode = 'NO_MODE';
		$hidden_create = false;
		$hidden_recover = false;
		$main = false;
		
		$pattern = "/^([\*\<\>]+\s)?([a-z_]+)(\:[a-z\%\@\#]+)?(\s\[[a-zA-Z,\-]+\])?$/";
		preg_match ( $pattern, $line, $matches );
		$multiplicity = $matches [1] != '' ? rtrim ( $matches [1] ) : 'REGULAR';
		$name = $matches [2];
		$type = $matches [3] != '' ? ltrim ( $matches [3], "\s\:" ) : 'String';
		$modifiers = trim ( $matches [4], " []" );
		
		$collection = ($multiplicity != 'REGULAR');
		
		switch ($multiplicity) {
			case '<>' :
				$mode = 'O2O';
				break;
			case '*>' :
				$mode = 'M2O';
				break;
			case '<*' :
				$mode = 'O2M';
				break;
			case '**' :
				$mode = 'M2M';
				break;
		}
		
		switch ($type) {
			case '#' :
				$type = 'number';
				break;
			case '%' :
				$type = 'date';
				break;
			case '@' :
				$type = 'file';
				break;
		}
		
		$hidden_create = (strpos ( $modifiers, 'c-' ) !== false);
		$hidden_recover = (strpos ( $modifiers, 'r-' ) !== false);
		$main = (strpos ( $modifiers, 'M' ) !== false);
		$mode = (strpos ( $modifiers, 'U' ) !== false) ? 'UNIQUE' : $mode;
		if ($multiplicity != 'REGULAR' && $mode == 'UNIQUE') {
			throw new Exception ( "ERROR while parsing model.txt: Only REGULAR attributes can be UNIQUE <br/><b>$line</b>" );
		}
		if ($mode == 'M2M' && strpos ( $name, '_' ) !== false) {
			throw new Exception ( "ERROR while parsing model.txt: <i>Many to many</i> attribute name cannot contain underscores <br/><b>$line</b>" );
		}
		
		error_reporting ( E_ALL );
		
		return new Attribute ( $name, $type, $collection, $mode, $hidden_create, $hidden_recover, $main );
	}
	
	// =============================================================
	
	$lines = $modelData;
	$line_number = 0;
	$classes = [ ];
	$current_class = null;
	
	$state = 'idle';
	
	foreach ( explode ( "\n", $lines ) as $line ) {
		$line_number ++;
		$line = trim ( $line );
		if ($line != "") {
			switch ($state) {
				case 'idle' :
					if (pdm_line_type ( $line ) != 'BEAN_SEPARATOR') {
						throw new Exception ( "ERROR while parsing model.txt (line $line_number): Bean separator expected <br/><b>$line</b>" );
					}
					$state = 'bean_name';
					break;
				case 'bean_name' :
					if (pdm_line_type ( $line ) != 'BEAN_NAME') {
						throw new Exception ( "ERROR while parsing model.txt (line $line_number): Bean name expected <br/><b>$line</b>" );
					}
					$current_class = pdm_process_bean_name ( $line, $classes );
					$state = 'rol_line';
					break;
				case 'rol_line' :
					if (! (pdm_line_type ( $line ) == 'ROL_LINE' || pdm_line_type ( $line ) == 'ATTRIBUTE_SEPARATOR')) {
						throw new Exception ( "ERROR while parsing model.txt (line $line_number): Rol line or attribute separator expected <br/><b>$line</b>" );
					}
					if (pdm_line_type ( $line ) == 'ATTRIBUTE_SEPARATOR') {
						$state = 'attribute';
					}
					break;
				case 'attribute' :
					if (! (pdm_line_type ( $line ) == 'ATTRIBUTE' || pdm_line_type ( $line ) == 'BEAN_SEPARATOR')) {
						throw new Exception ( "ERROR while parsing model.txt (line $line_number): Attribute or bean separator expected <br/><b>$line</b>" );
					}
					if (pdm_line_type ( $line ) == 'BEAN_SEPARATOR') {
						$current_class->setMainAttribute ();
						$classes [] = $current_class;
						$current_class = null;
						$state = 'idle';
					} else {
						$current_class->add_attribute ( pdm_process_attribute ( $line ) );
					}
					break;
			}
		}
	}
	
	if ($state != 'idle') {
		throw new Exception ( "MODEL PARSE ERROR ($line_number): Unexpected end of file <br/><b>$line</b>" );
	}
	
	return $classes;
}
// ------------------------------
function delete_directory($path, $ignore_files, $first_level) {
	if (! file_exists ( $path )) { // Name not correct
		return true;
	}
	
	if (! is_dir ( $path )) { // It's a file
		if (! in_array ( basename ( $path ), $ignore_files )) {
			return unlink ( $path );
		} else {
			return true;
		}
	}
	
	foreach ( scandir ( $path ) as $item ) { // It's a directory. Let's process its content
		if ($item == '.' || $item == '..') { // Ignore . and ..
			continue;
		}
		
		if (! in_array ( $item, $ignore_files ) && ! delete_directory ( $path . DIRECTORY_SEPARATOR . $item, $ignore_files, false )) {
			return false;
		}
	}
	
	if (! $first_level) { // It's a directory of deeper levels than first
		if (! in_array ( basename ( $path ), $ignore_files )) {
			return rmdir ( $path );
		} else {
			return true;
		}
	} else { // IT's the first level. We're done
		return true;
	}
}

// ------------------------------
function delete_attribute($classes, $class_name, $attribute_name) {
	foreach ( $classes as $class ) {
		if ($class->name == $class_name) {
			foreach ( $class->attributes as $i => $a ) {
				if ($a->name == $attribute_name) {
					unset ( $class->attributes [$i] );
				}
			}
		}
	}
}
// ------------------------------
function generate_yuml($classes) {
	$colors = [ 
			'yellowgreen',
			'yellow',
			'wheat',
			'violet',
			'turquoise',
			'tomato',
			'thistle',
			'tan',
			'steelblue',
			'springgreen',
			'snow',
			'slategray',
			'slateblue',
			'skyblue',
			'sienna',
			'seashell',
			'seagreen',
			'sandybrown',
			'salmon',
			'saddlebrown',
			'royalblue',
			'rosybrown',
			'red',
			'purple',
			'powderblue',
			'plum',
			'pink',
			'peru',
			'peachpuff' 
	];
	$c = 0;
	$html = '<img src="http://yuml.me/diagram/dir:td;scale:150/class/';
	foreach ( $classes as $class ) {
		$html .= '[' . ucfirst ( $class->name );
		$html .= '|';
		$i = 0;
		foreach ( $class->attributes as $a ) {
			if (! is_dependant ( $a )) {
				$html .= ((($a->main) ? '** ' : '') . $a->name);
				$html .= ($a->type == 'String') ? '' : (':' . $a->type);
				$html .= ';';
				unset ( $class->attributes [$i] );
			}
			$i ++;
		}
		$html .= ('{bg:' . $colors [($c ++)] . '}');
		$html .= '],';
	}
	
	foreach ( $classes as $class ) {
		$alt = true;
		foreach ( $class->attributes as $i => $a ) {
			$rel_name = ($alt ? ' ' : '') . $a->name . ($alt ? '' : ' ');
			// $rel_name = $a->name;
			$alt = ! $alt;
			switch ($a->mode) {
				case 'M2O' :
					$html .= '[' . ($class->name) . ']- ' . $rel_name . '  <>[' . ($a->type) . '],';
					break;
				case 'O2M' :
					$html .= '[' . ($class->name) . ']<>  ' . $rel_name . '-[' . ($a->type) . '],';
					break;
				case 'O2O' :
					$html .= '[' . ($class->name) . ']<>- ' . $rel_name . '  <>[' . ($a->type) . '],';
					break;
				case 'M2M' :
					$html .= '[' . ($class->name) . ']- ' . $rel_name . '  [' . ($a->type) . '],';
					break;
			}
			unset ( $class->attributes [$i] );
			delete_attribute ( $classes, $a->type, $a->name );
		}
	}
	return $html . '">';
}


// ------------------------------

function get_login_bean($classes) {
	$login_bean = null;
	foreach ($classes as $c) {
		if ($c->login_bean) {
			$login_bean = $c;
		}
	}
	return $login_bean;
}

// ------------------------------

function generate_home_controller($login_bean) {
	$lb = ($login_bean==null?'':"\t\t\$_SESSION['login_bean'] = '{$login_bean->name}';");
/*
	$lb = '';
	if  ( $login_bean != null ) {
		$ma = $login_bean->getMainAttribute();
	}
	*/
	$code = <<<CODE
<?php
class _home extends CI_Controller {
	public function index() {
		if (session_status () == PHP_SESSION_NONE) {session_start ();}
		$lb
		enmarcar(\$this, '_home/index');
	}
}
?>
CODE;

	file_put_contents ( APPPATH . 'controllers' . DIRECTORY_SEPARATOR . '_home.php', $code );
	
}

// ------------------------------
function delete_directories($classes) {
	$ignore_files = [ 
			'_casei.php',
			'_casei',
			'_home.php',
			'_home',
			'errors',
			'templates',
			'index.html' 
	];
	
	foreach ( $classes as $class ) {
		$ignore_files [] = $class->name;
		$ignore_files [] = $class->name . '.php';
		$ignore_files [] = $class->name . '_model.php';
	}
	
	delete_directory ( APPPATH . 'controllers', $ignore_files, true );
	delete_directory ( APPPATH . 'models', $ignore_files, true );
	delete_directory ( APPPATH . 'views', $ignore_files, true );
}

// ------------------------------
function change_title($title) {
	$d = DIRECTORY_SEPARATOR;
	$head_file = APPPATH . "views{$d}templates{$d}head.php";
	$html = file_get_contents ( $head_file );
	$pattern = '/<\?php \$title="(.)*"; \?>/';
	$replacement = '<?php \$title="' . $title . '"; ?>';
	
	file_put_contents ( $head_file, preg_replace ( $pattern, $replacement, $html ) );
}

// ------------------------------
function generate_menus($menuData, $appTitle, $classes) {
	$nav = <<<NAV
<nav class="container navbar navbar-expand-sm bg-dark navbar-dark rounded">

	<a class="navbar-brand" href="<?=base_url()?>">
		<img src="<?=base_url()?>assets/img/icons/png/home-alt.png" alt="INICIO" style="width:40px;">
	</a>

	<ul class="navbar-nav">
NAV;
	
	$nav .= generate_menus_CRUD ( $classes );
	$nav .= generate_menus_CSI();
	foreach ( explode ( "\n", $menuData ) as $menu ) {
		if (trim ( $menu ) != '') {
			$menuTitle = explode ( ":", $menu ) [0];
			$nav .= <<<NAV
      <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
           $menuTitle<span class="caret"></span>
        </a>
NAV;
			$subMenus = strpos ( $menu, ':' ) !== FALSE ? explode ( ":", $menu ) [1] : [ ];
			if (sizeof ( $subMenus ) > 0) {
				$nav .= <<<NAV
		<ul class="dropdown-menu">
NAV;
				foreach ( explode ( ",", $subMenus ) as $subMenu ) {
					$subMenuTitle = explode ( "(", $subMenu ) [0];
					$subMenuAction = explode ( "(", $subMenu ) [1];
					$subMenuAction = explode ( ")", $subMenuAction ) [0];
					$nav .= <<<NAV
		  <li><a href="<?=base_url()?>$subMenuAction">$subMenuTitle</a></li>
NAV;
				}
				$nav .= <<<NAV
		</ul>
	</li>
NAV;
			} else { // NO SUBMENUS
			}
		}
	}
	$nav .= <<<NAV
 
   </ul>
</nav>
NAV;
	return $nav;
}

// ------------------------------

function generate_menus_CSI() {
	$crud = <<<NAV

		<li class="nav-item">
			<a class="nav-link" href="<?=base_url()?>_casei">
				CSI
			</a>
		</li>

NAV;
	return $crud;
}

// ------------------------------

function generate_menus_CRUD($classes) {
	$crud = <<<NAV

		<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
				BEANS 
			</a>

			<div class="dropdown-menu">

NAV;
	foreach ( $classes as $class ) {
		$n = $class->name;
		$crud .= <<<NAV
				<a class="dropdown-item" href="<?=base_url()?>$n/list">$n</a>
NAV;
	}
	$crud .= <<<NAV
			</div>

		</li>
NAV;
	return $crud;
}

// ------------------------------

function is_dependant($attribute) {
	$t = $attribute->mode;
	return $t == "M2M" || $t == "M2Mi" || $t == "M2O" || $t == "O2M" || $t == "O2O";
}

// ------------------------------
function generate_application_files($classes) {
	delete_directories ( $classes );
	generate_controllers ( $classes );
	generate_models ( $classes );
	generate_views ( $classes );
}

// ------------------------------
function generate_controllers($classes) {
	foreach ( $classes as $class ) {
		generate_controller ( $class );
	}
}

// ------------------------------
function generate_models($classes) {
	foreach ( $classes as $class ) {
		generate_model ( $class, $classes );
	}
}

// ------------------------------
function generate_views($classes) {
	foreach ( $classes as $class ) {
		generate_view ( $class, $classes );
	}
}

// ------------------------------
function backup_and_save($filename, $code) {
	if (file_exists ( $filename )) {
		file_put_contents ( substr ( $filename, 0, - 4 ) . '_bak.php', file_get_contents ( $filename ) );
	}
	file_put_contents ( $filename, $code );
}

// ------------------------------
function generate_controller($class) {
	$code = '';
	$code .= generate_controller_header ( $class->name );
	$code .= generate_controller_create ( $class );
	$code .= generate_controller_create_post ( $class );
	$code .= generate_controller_list ( $class );
	$code .= generate_controller_list_id ( $class );
	$code .= generate_controller_delete ( $class );
	$code .= generate_controller_update ( $class );
	$code .= generate_controller_update_post ( $class );
	if ($class->login_bean) {
		$code .= generate_controller_login ( $class );
		$code .= generate_controller_login_post ( $class );
		$code .= generate_controller_logout ( $class );
	}
	$code .= generate_controller_end ();
	$filename = APPPATH . 'controllers' . DIRECTORY_SEPARATOR . $class->name . '.php';
	backup_and_save ( $filename, $code );
}

// ------------------------------

function generate_controller_login ( $class ) {

	$code = <<<CODE
	
	
	/**
	* Controller action LOGIN for controller {$class->name}
	* autogenerated by CASE IGNITER
	*/
	public function login() {
		\$this->load->model('rol_model');
		\$data['body']['rol'] = \$this->rol_model->get_all();
		
		enmarcar(\$this,'{$class->name}/login',\$data);
	}

CODE;
	
	return $code;

}

// ------------------------------

function generate_controller_logout ( $class ) {
	
	$code = <<<CODE
	
	
	/**
	* Controller action LOGOUT for controller {$class->name}
	* autogenerated by CASE IGNITER
	*/
	public function logout() {
		if (session_status () == PHP_SESSION_NONE) {
			session_start ();
		}
		session_destroy();
		redirect(base_url());
	}
	
CODE;
	
	return $code;
	
}


// ------------------------------

function generate_controller_login_post ( $class ) {
	
	//TODO Cambiar excepciones por mensajes de error
	$code = <<<CODE
	
	/**
	* Controller action LOGIN POST for controller {$class->name}
	* autogenerated by CASE IGNITER
	*/
	public function loginPost() {
		\$loginname = isset(\$_POST['loginname'])?\$_POST['loginname']:null;
		\$password = isset(\$_POST['password'])?\$_POST['password']:null;
		\$rol = isset(\$_POST['rol'])?\$_POST['rol']:null;

		\$this->load->model('{$class->name}_model');
		\$user = \$this->{$class->name}_model->get_by_loginname(\$loginname);
		
		if (\$user != null) {
			if ( password_verify(\$password, \$user->password) ) {
				\$rol_ok = false;
				\$rol_id = 0;
				foreach (\$user->aggr('ownRolesList', 'rol' )  as \$r ) {
					if (\$r->id == \$rol) {
						\$rol_ok = true;
						\$rol_id = \$r->id;
					}
				}
				if (\$rol_ok ) {
					if (session_status () == PHP_SESSION_NONE) {session_start ();}
					\$_SESSION['user'] = \$user;
					\$this->load->model('rol_model');
					\$rol_used = \$this->rol_model->get_by_id(\$rol_id);

					\$_SESSION['rol'] = \$rol_used;
					enmarcar(\$this,'_home/index');
				}
				else {
					throw new Exception("Rol incorrecta");
				}
			} 
			else {
				throw new Exception("Password incorrecta");
			}
		}
		else {
			throw new Exception("Usuario inexistente");
		}
		
	}
	
CODE;
	return $code;
	
	
}

// ------------------------------

function generate_controller_list_id($class) {
	$code = <<<CODE


	/**
	* Controller private function LIST_ID for controller {$class->name}
	* autogenerated by CASE IGNITER
	*/
	private function list_id(\$id) {
		\$this->load->model('{$class->name}_model');
		\$data['body']['{$class->name}'] = [ \$this->{$class->name}_model->get_by_id(\$id) ];
		enmarcar(\$this, '{$class->name}/list', \$data);
	}

CODE;
	
	return $code;
}

// ------------------------------
function generate_controller_delete($class) {
	$code = <<<CODE


	
	/**
	* Controller action DELETE for controller {$class->name}
	* autogenerated by CASE IGNITER
	*/
	public function delete() {
		\$this -> load -> model ('{$class->name}_model');
		try {
			\$id = \$_POST['id'];
			\$filter = isset (\$_REQUEST['filter'] ) ? \$_REQUEST['filter'] : '';

			\$this -> {$class->name}_model -> delete( \$id );
			redirect(base_url().'{$class->name}/list?filter='.\$filter);
		}
		catch (Exception \$e ) {
			enmarcar(\$this, '{$class->name}/deleteERROR');
		}
	}
CODE;
	
	return $code;
}

// ------------------------------
function generate_controller_update($class) {
	$code = <<<CODE
	
	
	
	/**
	* Controller action UPDATE for controller {$class->name}
	* autogenerated by CASE IGNITER
	*/
	public function update() {

		\$data['body']['filter'] = isset(\$_REQUEST['filter']) ? \$_REQUEST['filter'] : '' ;

CODE;
	
	if ($class->has_dependants ()) {
		$types_loaded = [ ];
		foreach ( $class->attributes as $a ) {
			if ($a->is_dependant () && ! ($a->hidden_create) && ! in_array ( $a->type, $types_loaded )) {
				$types_loaded [] = $a->type;
				$code .= generate_controller_update_dependants ( $a );
			}
		}
	}
	
	$code .= <<<CODE


		\$this -> load -> model ('{$class->name}_model');
		\$id = \$_POST['id'];
		\$data['body']['{$class->name}'] = \$this -> {$class->name}_model -> get_by_id(\$id);
		
		enmarcar(\$this, '{$class->name}/update', \$data);
	}
CODE;
	
	return $code;
}

// ------------------------------
function generate_controller_update_post($class) {
	$code = '';
	$code .= generate_controller_update_post_header ( $class->name );
	$code .= generate_controller_update_post_middle ( $class );
	$code .= generate_controller_update_post_end ( $class->name );
	return $code;
}

// ------------------------------
function generate_controller_end() {
	return <<<CODE
}
?>
CODE;
}

// ------------------------------
function generate_controller_header($class_name) {
	return <<<CODE
<?php
/**
* Controller code for $class_name autogenerated by CASE IGNITER
*/
class $class_name extends CI_Controller {
CODE;
}
// ------------------------------
function generate_controller_create($class) {
	$code = '';
	$code .= generate_controller_create_header ( $class->name );
	
	$has_dependants = false;
	
	if ($class->has_dependants ()) {
		$models_loaded = [ ];
		foreach ( $class->attributes as $a ) {
			if ($a->is_dependant () && ! ($a->hidden_create) && ! in_array ( $a->type, $models_loaded )) {
				$models_loaded [] = $a->type;
				$has_dependants = true;
				$code .= generate_controller_create_dependants ( $a );
			}
		}
	}
	
	if (! $has_dependants) {
		$code .= generate_controller_create_non_dependants ();
	}
	
	$code .= generate_controller_create_end ( $class->name );
	return $code;
}

// ------------------------------
function generate_controller_create_post($class) {
	$code = '';
	$code .= generate_controller_create_post_header ( $class->name );
	$code .= generate_controller_create_post_middle ( $class );
	$code .= generate_controller_create_post_end ( $class->name );
	return $code;
}

// ------------------------------
function generate_controller_create_post_middle($class) {
	$code = '';
	
	foreach ( $class->attributes as $a ) {
		if (! $a->hidden_create) {
			if (! $a->collection) {
				if ($a->type == 'file') {
					$code .= "\t\t\$$a->name = ( isset( \$_FILES['$a->name']) ? \$_FILES['$a->name'] : null );" . PHP_EOL;
				} else {
					$code .= "\t\t\$$a->name = ( isset( \$_POST['$a->name']) ? \$_POST['$a->name'] : null );" . PHP_EOL;
				}
			} else {
				$code .= "\t\t\$$a->name = ( isset( \$_POST['$a->name']) ? \$_POST['$a->name'] : [] );" . PHP_EOL;
			}
		}
	}
	
	$code .= (PHP_EOL . "\t\ttry {" . PHP_EOL);
	$code .= "\t\t\t\$id = \$this->{$class->name}_model->create( ";
	
	$parameters = '';
	foreach ( $class->attributes as $a ) {
		if (! $a->hidden_create) {
			$parameters .= "$$a->name, ";
		}
	}
	$parameters = rtrim ( $parameters, ', ' );
	$code .= $parameters;
	
	$code .= " );" . PHP_EOL;
	
	$main_attribute = $class->getMainAttribute ();
	$capital = ucfirst ( $class->name );
	
	// TODO LOC
	$code .= <<<CATCH
			\$this->list_id(\$id);
		}
		catch (Exception \$e) {
			\$data['status'] = 'error';
			\$data['message'] = "Error al crear el/la {$class->name} \$$main_attribute";
			\$this->load->view('{$class->name}/create_message',\$data);
		}
CATCH;
	
	return $code;
}

// ------------------------------
function generate_controller_update_post_middle($class) {
	$code = '';
	
	$code .= "\t\t\$id = ( isset( \$_POST['id']) ? \$_POST['id'] : null );" . PHP_EOL;
	
	foreach ( $class->attributes as $a ) {
		if (! $a->hidden_create) {
			if (! $a->collection) {
				if ($a->type == 'file') {
					$code .= "\t\t\$$a->name = ( isset( \$_FILES['$a->name']) ? \$_FILES['$a->name'] : null );" . PHP_EOL;
				} else {
					$code .= "\t\t\$$a->name = ( isset( \$_POST['$a->name']) ? \$_POST['$a->name'] : null );" . PHP_EOL;
				}
			} else {
				$code .= "\t\t\$$a->name = ( isset( \$_POST['$a->name']) ? \$_POST['$a->name'] : [] );" . PHP_EOL;
			}
		}
	}
	
	$code .= (PHP_EOL . "\t\ttry {" . PHP_EOL);
	$code .= "\t\t\t\$this->{$class->name}_model->update( \$id, ";
	
	$parameters = '';
	foreach ( $class->attributes as $a ) {
		if (! $a->hidden_create) {
			$parameters .= "$$a->name, ";
		}
	}
	$parameters = rtrim ( $parameters, ', ' );
	$code .= $parameters;
	
	$code .= " );" . PHP_EOL;
	
	$main_attribute = $class->getMainAttribute ();
	$capital = ucfirst ( $class->name );
	
	// TODO LOC
	$code .= <<<CATCH

			\$filter = isset(\$_POST['filter']) ? \$_POST['filter'] : '' ;
			redirect( base_url() . '{$class->name}/list?filter='.\$filter );
		}
		catch (Exception \$e) {
			\$data['status'] = 'error';
			\$data['message'] = "Error al crear el/la {$class->name} \$$main_attribute";
			\$this->load->view('{$class->name}/create_message',\$data);
		}
CATCH;
	
	return $code;
}

// ------------------------------
function generate_controller_create_post_header($class_name) {
	$code = <<<CODE
	
	
	/**
	* Controller action CREATE POST for controller $class_name
	* autogenerated by CASE IGNITER
	*/
	public function create_post() {
		
		\$this->load->model('{$class_name}_model');


CODE;
	return $code;
}

// ------------------------------
function generate_controller_update_post_header($class_name) {
	$code = <<<CODE
	
	
	/**
	* Controller action UPDATE POST for controller $class_name
	* autogenerated by CASE IGNITER
	*/
	public function update_post() {
	
		\$this->load->model('{$class_name}_model');
			

CODE;
	return $code;
}

// ------------------------------
function generate_controller_create_header($class_name) {
	$code = <<<CODE


	/**
	* Controller action CREATE for controller $class_name
	* autogenerated by CASE IGNITER
	*/
	public function create() {

		\$data['body']['filter'] = isset(\$_REQUEST['filter']) ? \$_REQUEST['filter'] : '' ;
CODE;
	return $code;
}

// ------------------------------
function generate_controller_create_dependants($attribute) {
	$code = <<<CODE


		\$this->load->model('{$attribute->type}_model');
		\$data['body']['{$attribute->type}'] = \$this->{$attribute->type}_model->get_all();
CODE;
	return $code;
}

// ------------------------------
function generate_controller_update_dependants($attribute) {
	$code = <<<CODE
	
	
		\$this->load->model('{$attribute->type}_model');
		\$data['body']['{$attribute->type}'] = \$this->{$attribute->type}_model->get_all();
CODE;
	return $code;
}

// ------------------------------
function generate_controller_create_non_dependants() {
	$code = <<<CODE


		// \$data = [];
CODE;
	return $code;
}

// ------------------------------
function generate_controller_create_end($class_name) {
	$code = <<<CODE

		enmarcar(\$this, '$class_name/create', \$data);
	}


CODE;
	return $code;
}

// ------------------------------
function generate_controller_create_post_end($class_name) {
	$code = <<<CODE
	
	
	}
				
				
CODE;
	return $code;
}

// ------------------------------
function generate_controller_update_post_end($class_name) {
	$code = <<<CODE
	
	
	}
			
			
CODE;
	return $code;
}

// ------------------------------
function generate_controller_list($class) {
	$cn = $class->name;
	$code = <<<CODE

	
	/**
	* Controller action LIST for controller {$class->name}
	* autogenerated by CASE IGNITER
	*/
	public function list() {
		\$this->load->model('{$cn}_model');
		\$filter = isset(\$_REQUEST['filter'])?\$_REQUEST['filter']:'';
		\$data['body']['$cn'] = (\$filter == '' ? \$this->{$cn}_model->get_all() : \$this->{$cn}_model->get_filtered( \$filter ) );
		\$data['body']['filter'] = \$filter ;
		enmarcar(\$this, '{$cn}/list', \$data);
	}
CODE;
	return $code;
}

// --------------------------------

function generate_model_get_by_loginname ( $class ) {
	$code = <<<CODE
	/**
	 * create MODEL action autogenerated by CASE IGNITER
	 */
	public function get_by_loginname( \$loginname ) {
		return R::findOne( '{$class->name}', ' loginname = ? ', [ \$loginname ] );
	}
			
CODE;
	return $code;
}

// --------------------------------

function generate_model($class, $classes) {
	$code = '';
	$code .= generate_model_header ( $class->name );
	$code .= generate_model_create ( $class );
	$code .= generate_model_update ( $class );
	$code .= generate_model_get_all ( $class );
	$code .= generate_model_get_filtered ( $class, $classes );
	$code .= generate_model_delete ( $class );
	$code .= generate_model_get_by_id ( $class );
	if ($class->login_bean) {
		$code .= generate_model_get_by_loginname ( $class );
	}
	$code .= generate_model_end ();
	
	$filename = APPPATH . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . $class->name . '_model.php';
	backup_and_save ( $filename, $code );
}

// --------------------------------
function generate_model_header($class_name) {
	return <<<CODE
<?php
/**
* Model code autogenerated by CASE IGNITER
*/
class {$class_name}_model extends CI_Model {

CODE;
}

// --------------------------------
function generate_model_delete($class) {
	$code = <<<CODE

	/**
	* delete MODEL action autogenerated by CASEIGNITER
	*/
	public function delete( \$id ) {
		\$bean = R::load('{$class -> name}', \$id );

CODE;
	foreach ( $class->attributes as $a ) {
		if ($a->type == 'file') {
			$code .= <<<CODE
		\$file_path = 'assets/upload/'.(\$bean->$a->name);
		if (file_exists( \$file_path )) {
			unlink( \$file_path );
		} 

CODE;
		}
	}
	$code .= "\n\t\tR::trash( \$bean );";
	$code .= "\n\t}";
	return $code;
}

// --------------------------------
function generate_model_get_by_id($class) {
	$code = <<<CODE
	
	/**
	* get_by_id MODEL action autogenerated by CASEIGNITER
	*/
	public function get_by_id( \$id ) {
		return R::load('{$class -> name}', \$id ) ;
	}
	
CODE;
	return $code;
}

// --------------------------------
function generate_model_create($class) {
	$code = <<<CODE


	/**
	* create MODEL action autogenerated by CASE IGNITER
	*/
	public function create( 
CODE;
	
	$parameters = '';
	
	foreach ( $class->attributes as $a ) {
		if (! $a->hidden_create) {
			$parameters .= "$$a->name, ";
		}
	}
	$parameters = rtrim ( $parameters, ', ' );
	$code .= $parameters;
	
	$code .= " ) {" . PHP_EOL;
	$code .= "\n\t\$bean = R::dispense( '{$class->name}' );";
	$code .= "\n\t\$id_bean = R::store( \$bean );" . PHP_EOL . PHP_EOL;
	
	foreach ( $class->attributes as $a ) {
		if (! $a->hidden_create) {
			$type_capitalized = ucfirst ( $a->type );
			if ($a->mode == "O2O") { // =========== ONE TO ONE RELATIONSHIP ======================
				$code .= <<<O2O
				
	// "one to one" attribute
	if ( \${$a->name} != null && \${$a->name} != 0 ) {
		\$o2o = R::load('{$a->type}',\${$a->name});
		\$bean -> {$a->name} = \$o2o;
		R::store(\$bean);
		\$o2o -> {$a->name} = \$bean;
		R::store(\$o2o);
	}
				
				
O2O;
			} else if ($a->mode == "M2O") { // =========== MANY TO ONE RELATIONSHIP ======================
				$code .= <<<M2O

	// "many to one" attribute
	if ( \${$a->name} != null && \${$a->name} != 0) {
		\$bean -> {$a->name} = R::load('{$a->type}',\${$a->name});
	}
				
				
M2O;
			} else if ($a->mode == "O2M") { // =========== ONE TO MANY RELATIONSHIP ======================
				$code .= <<<O2M
					
	// "one to many" attribute
	foreach (\${$a->name} as \$id) {
		\$o2m = R::load('{$a->type}', \$id);
		\$bean -> alias('{$a->name}') ->own{$type_capitalized}List[] = \$o2m;
		R::store(\$bean);
	}
				
				
O2M;
			} else if ($a->mode == "M2M") { // =========== MANY TO MANY RELATIONSHIP ======================
				$code .= <<<M2M
					
	// "many to many" attribute
	foreach (\${$a->name} as \$id) {
		\$another_bean = R::load('{$a->type}', \$id);
		\$m2m = R::dispense('{$a->name}');
		R::store(\$bean);
		\$m2m -> {$class->name} = \$bean;
		\$m2m -> {$a->type} = \$another_bean;
		R::store(\$m2m);
	}
				
				
M2M;
			} else {
				if ($a->type == 'file') { // ============ REGULAR FILE ATTRIBUTE ======================
					$code .= <<<REGULARFILE
					
	// Regular FILE attribute
	if ( \${$a->name} != NULL && \${$a->name}['error'] == UPLOAD_ERR_OK) {
		\$name_and_ext = explode ( '.', \${$a->name}['name'] );
		\$ext = \$name_and_ext[sizeof ( \$name_and_ext ) - 1 ];
		\$file_name = '{$class->name}' . '-' . '{$a->name}' . '-' . \$id_bean . '.' .\$ext ;
		copy ( \${$a->name}['tmp_name'], 'assets/upload/' .  \$file_name );
		\$bean -> {$a->name} = \$file_name;	
	}

REGULARFILE;
				} else { // ================================ REGULAR ATTRIBUTE ===========================
					$code .= <<<REGULAR

	// Regular attribute
	\$bean -> {$a->name} = \${$a->name};

REGULAR;
				}
			}
		}
	}
	$code .= (PHP_EOL . "\tR::store(\$bean);" . PHP_EOL);
	$code .= (PHP_EOL . "\treturn \$bean->id;" . PHP_EOL);
	$code .= '}' . PHP_EOL . PHP_EOL;
	
	return $code;
}

// --------------------------------
function generate_model_update($class) {
	$code = <<<CODE

	/**
	* update MODEL action autogenerated by CASE IGNITER
	*/
	public function update( \$id, 
CODE;
	
	$parameters = '';
	foreach ( $class->attributes as $a ) {
		if (! $a->hidden_create) {
			$parameters .= "$$a->name, ";
		}
	}
	$parameters = rtrim ( $parameters, ', ' );
	$code .= $parameters;
	
	$code .= " ) {" . PHP_EOL;
	$code .= "\n\t\$bean = R::load( '{$class->name}', \$id );" . PHP_EOL . PHP_EOL;
	
	foreach ( $class->attributes as $a ) {
		if (! $a->hidden_create) {
			$type_capitalized = ucfirst ( $a->type );
			$name_capitalized = ucfirst ( $a->name );
			
			if ($a->mode == "O2O") { // =========== ONE TO ONE RELATIONSHIP ======================
				$code .= <<<O2O

	// "one to one" attribute
	if ( \${$a->name} != null ) {
		\$o2o = ( \${$a->name} != 0 ? R::load('{$a->type}',\${$a->name}) : null );

		if (\$bean->fetchAs('{$a->type}')->{$a->name} != null ) {
			\$o2o_prev = R::load('{$a->type}',\$bean->fetchAs('{$a->type}')->{$a->name}->id);
			\$o2o_prev -> {$a->name}_id = null;
			R::store(\$o2o_prev);
		}

		\$bean -> {$a->name} = \$o2o;

		R::store(\$bean);

		if ( \$o2o != null ) {
			\$o2o -> {$a->name} = \$bean;
			R::store(\$o2o);
		}
	}


O2O;
			} else if ($a->mode == "M2O") { // =========== MANY TO ONE RELATIONSHIP ======================
				$code .= <<<M2O
	// "many to one" attribute
	if ( \${$a->name} != null ) {
		\$bean -> {$a->name} = ( \${$a->name} != 0 ? R::load('{$a->type}',\${$a->name}) : null );
		R::store(\$bean);
	}


M2O;
			} else if ($a->mode == "O2M") { // =========== ONE TO MANY RELATIONSHIP ======================
				$code .= <<<O2M
				
	// "one to many" attribute (O2M)

	foreach (\$bean->alias('{$a->name}')->own{$type_capitalized}List as \${$a->name}_bean ) {
		\$key = array_search( \${$a->name}_bean->{$a->name}->id, \${$a->name} );
		
		if (\$key !== false) { // O2M we keep only the keys to add
			unset(\${$a->name}[\$key]);
		}
		else { // O2M Element to be deleted
			R::store(\$bean);
			\${$a->name}_bean -> {$a->name} = null;
			R::store(\${$a->name}_bean);
		}
	}

	// O2M Elements to be added
	foreach (\${$a->name} as \$idf) {
		\$o2m = R::load('{$a->type}', \$idf);
		\$o2m -> {$a->name} = \$bean;
		R::store(\$o2m);
	}


O2M;
			} else if ($a->mode == "M2M") { // =========== MANY TO MANY RELATIONSHIP ======================
				$code .= <<<M2M
				
	// "many to many" attribute (M2M)
	
	foreach (\$bean->own{$name_capitalized}List as \${$a->name}_bean ) {
		\$key = array_search( \${$a->name}_bean->{$a->type}->id, \${$a->name} );
		
		if (\$key !== false) { // M2M we keep only the keys to add
			unset(\${$a->name}[\$key]);
		}
		else { // M2M Element to be deleted
			R::store(\$bean);
			R::trash(\${$a->name}_bean);
		}
	}

	// M2M Elements to be added
	foreach (\${$a->name} as \$idf) {
		\$another_bean = R::load('{$a->type}', \$idf);
		\$m2m = R::dispense('{$a->name}');
		\$m2m -> {$class->name} = \$bean;
		\$m2m -> {$a->type} = \$another_bean;
		R::store(\$m2m);
	}

	
M2M;
			} else {
				if ($a->type == 'file') { // ============ REGULAR FILE ATTRIBUTE ======================
					$code .= <<<REGULARFILE
					
	// Regular FILE attribute
	if ( \${$a->name} != NULL && \${$a->name}['error'] == UPLOAD_ERR_OK) {
		\$name_and_ext = explode ( '.', \${$a->name}['name'] );
		\$ext = \$name_and_ext[sizeof ( \$name_and_ext ) - 1 ];
		\$file_name = '{$class->name}' . '-' . '{$a->name}' . '-' . \$id . '.' .\$ext ;
		copy ( \${$a->name}['tmp_name'], 'assets/upload/' .  \$file_name );
		if (\$bean->{$a->name} != null && \$bean->{$a->name} != '' ) {
			unlink( 'assets/upload/'.\$bean->{$a->name} );
		}
		\$bean -> {$a->name} = \$file_name;
	}
	
REGULARFILE;
				} else { // ================================ REGULAR ATTRIBUTE ===========================
					$code .= <<<REGULAR
					
	// Regular attribute
	\$bean -> {$a->name} = \${$a->name};
	
REGULAR;
				}
			}
		}
	}
	$code .= (PHP_EOL . "\tR::store(\$bean);" . PHP_EOL);
	$code .= '}' . PHP_EOL . PHP_EOL;
	
	return $code;
}

// --------------------------------
function generate_model_get_all($class) {
	$code = <<<CODE

	/**
	* get_all MODEL action autogenerated by CASE IGNITER
	*/
	public function get_all() {
		return R::findAll('{$class->name}');
	}

CODE;
	return $code;
}

// --------------------------------
function generate_model_get_filtered($class, $classes) {
	$code = <<<CODE

	/**
	* get_filtered MODEL action autogenerated by CASE IGNITER
	*/
	public function get_filtered(\$filter) {

		\$where_clause = [ ];

CODE;
	$param_count = 0;
	foreach ( $class->attributes as $a ) {
		if (! $a->hidden_recover) {
			$param_count ++;
			if (! $a->is_dependant ()) { // REGULAR ATTRIBUTE
				$code .= "\n\t\t\$where_clause[] = '{$a->name} LIKE ?';";
			} else {
				if ($a->mode == 'O2O' || $a->mode == 'M2O') { // O2O or M2O
					$foreign_name = getMainAttributeName ( $classes, $a->type );
					$code .= "\n\t\t\$where_clause[] = " . "'(SELECT $foreign_name FROM {$a->type} WHERE {$a->type}.id = {$class->name}.{$a->name}_id) LIKE ?';";
				} else if ($a->mode == 'O2M') { // O2M
					$foreign_name = getMainAttributeName ( $classes, $a->type );
					$code .= "\n\t\t\$where_clause[] = " . "'(SELECT count(*) FROM {$a->type} WHERE $foreign_name LIKE ? AND {$a->name}_id = {$class->name}.id) > 0';";
				} else if ($a->mode == 'M2M') {
					$foreign_name = getMainAttributeName ( $classes, $a->type );
					$code .= "\n\t\t\$where_clause[] = " . "'(SELECT count(*) FROM {$a->type} WHERE $foreign_name LIKE ? AND {$a->type}.id IN (SELECT {$a->type}_id FROM {$a->name} WHERE {$class->name}_id = {$class->name}.id)) > 0';";
				}
			}
		}
	}
	
	$params = [ ];
	for($i = 0; $i < $param_count; $i ++) {
		$params [] = '$f';
	}
	$params = implode ( ',', $params );
	
	$code .= <<<CODE

		\$f = "%\$filter%";
		
		return R::findAll('{$class->name}', implode(' OR ', \$where_clause) , [ $params ] );
		
	}

CODE;
	return $code;
}

// --------------------------------
function generate_model_end() {
	$code = <<<CODE

}
?>
CODE;
	return $code;
}

// ------------------------------
function generate_view($class, $classes) {
	$dirname = APPPATH . 'views' . DIRECTORY_SEPARATOR . $class->name;
	
	if (! file_exists ( $dirname )) {
		mkdir ( $dirname );
	}
	
	generate_view_create ( $class, $classes );
	generate_view_create_post ( $class );
	generate_view_create_message ( $class );
	generate_view_update ( $class, $classes );
	generate_view_list ( $class, $classes );
	generate_view_login ($class, $classes );
}

// ------------------------------

function generate_view_login ($class, $classes ) {
	$code = <<<CODE


<div class="container">

<h2> Bienvenido </h2>
<h5> Introduce tus credenciales</h5>

<form class="form" role="form" id="idForm" action="<?= base_url() ?>{$class->name}/loginPost" method="post">

	<div class="row form-inline form-group">
		<label for="id-loginname" class="col-2 justify-content-end">Usuario</label>
		<input id="id-loginname" type="text" name="loginname" class="col-6 form-control" autofocus="autofocus">
	</div>

	<div class="row form-inline form-group">
		<label for="id-password" class="col-2 justify-content-end">Contraseña</label>
		<input id="id-password" type="password" name="password" class="col-6 form-control" >
	</div>

	<div class="row form-inline form-group">
		<label for="id-rol" class="col-2 justify-content-end">Rol</label>
		<select id="id-rol" name="rol" class="col-6 form-control">
		<?php foreach (\$body['rol'] as \$rol):?>
			<option value="<?=\$rol->id?>"><?=\$rol->descripcion?></option>
		<?php endforeach; ?>				
		</select>
	</div>

	<div class="row offset-2 col-6">
		<input type="submit" class="btn btn-primary" value="Crear">
		<a href="<?=base_url()?>">
			<input type="button" class="offset-1 btn btn-primary" value="Cancelar">
		</a>
	</div>
	
</form>

</div>

CODE;
	file_put_contents ( APPPATH . 'views' . DIRECTORY_SEPARATOR . $class->name . DIRECTORY_SEPARATOR . 'login.php', $code );
	
}


// ------------------------------

function generate_view_create($class, $classes) {
	$code = '';
	// $code .= generate_view_create_ajax ( $class->name );
	$code .= generate_view_create_header ( $class->name );
	$code .= generate_view_create_non_dependants ( $class );
	$code .= generate_view_create_dependants ( $class, $classes );
	$code .= generate_view_create_end ( $class->name );
	
	file_put_contents ( APPPATH . 'views' . DIRECTORY_SEPARATOR . $class->name . DIRECTORY_SEPARATOR . 'create.php', $code );
}

// ------------------------------
function generate_view_update($class, $classes) {
	$code = '';
	$code .= generate_view_update_header ( $class->name );
	$code .= generate_view_update_non_dependants ( $class, $classes );
	$code .= generate_view_update_dependants ( $class, $classes );
	$code .= generate_view_update_end ( $class->name );
	
	file_put_contents ( APPPATH . 'views' . DIRECTORY_SEPARATOR . $class->name . DIRECTORY_SEPARATOR . 'update.php', $code );
}

// ------------------------------
function generate_view_create_message($class) {
	$code = '';
	$code .= generate_view_create_message_all ( $class );
	
	file_put_contents ( APPPATH . 'views' . DIRECTORY_SEPARATOR . $class->name . DIRECTORY_SEPARATOR . 'create_message.php', $code );
}

// ------------------------------
function generate_view_create_message_all($class) {
	$code = <<<CODE
<?php if (\$status == 'ok' ): ?>
<div class="alert alert-success"><?= \$message ?></div>
<?php else: ?>
<div class="alert alert-danger"><?= \$message ?></div>
<?php endif; ?>
CODE;
	
	return $code;
}

// ------------------------------
function generate_view_create_ajax($class_name) {
	$code = <<<JS

<script type="text/javascript" src="<?= base_url() ?>assets/js/serialize.js"></script>

<script type="text/javascript">
var connection;

function detect(e) {
		key = document.all ? e.keyCode : e.which;
		if (key==13) {
			create();
		}
	}

function create() {
	var createForm = document.getElementById('idForm');
	var serializedData = serialize(createForm);
	
	connection = new XMLHttpRequest();
	connection.open('POST', '<?= base_url() ?>$class_name/create_post', true);
	connection.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	connection.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	connection.send(serializedData);
	connection.onreadystatechange = function() {
		if (connection.readyState==4 && connection.status==200) {
			actionAJAX();
		}
	}
}

		
function actionAJAX() {
	htmlReceived = connection.responseText;
	document.getElementById("idMessage").innerHTML = htmlReceived;
}	

</script>

<!-- ----------------------------------------- -->


JS;
	return $code;
}

// ------------------------------
function generate_view_create_header($class_name) {
	$code = <<<HTML


<div class="container">
<h2> Crear $class_name </h2>

<form class="form" role="form" id="idForm" enctype="multipart/form-data" action="<?= base_url() ?>$class_name/create_post" method="post">


HTML;
	
	return $code;
}

// ------------------------------
function generate_view_update_header($class_name) {
	$code = "<?php\n";
	
	$code .= code_get_ids ();
	
	$code .= <<<HTML

	function selected(\$bean_selected, \$id_to_be_tested) {
		return \$bean_selected != null && \$bean_selected->id == \$id_to_be_tested ? 'selected="selected"' : '';
	}

	function checked(\$list, \$id_to_be_tested) {
		return in_array(\$id_to_be_tested, get_ids(\$list) ) ? 'checked="checked"' : '';
	}
?>	
	
<div class="container">
<h2> Editar $class_name </h2>

<form class="form" role="form" id="idForm" enctype="multipart/form-data" action="<?= base_url() ?>$class_name/update_post" method="post">
	
	<input type="hidden" name="filter" value="<?=\$body['filter']?>" />
	
		
HTML;
	
	return $code;
}

// ------------------------------
function generate_view_create_non_dependants($class) {
	$code = '';
	foreach ( $class->attributes as $a ) {
		if (! $a->is_dependant ()) {
			$capitalized = ucfirst ( $a->name );
			$type = ($a->type == 'String' ? 'text' : $a->type);
			$autofocus = $a->main ? 'autofocus="autofocus"' : '';
			$size = $a->type == 'date' ? '3' : '6';
			$jquery_file_code = <<<CODE

	<script>
		 $(window).on("load",(function(){
		 $(function() {
		 $('#id-{$a->name}').change(function(e) {addImage(e);});
		function addImage(e){
			var file = e.target.files[0],
			imageType = /image.*/;
			if (!file.type.match(imageType)) return;
			var reader = new FileReader();
			reader.onload = fileOnload;
			reader.readAsDataURL(file);
		}
		function fileOnload(e) {
		var result=e.target.result;
		$('#id-out-{$a->name}').attr("src",result);
		}});}));
	</script>


CODE;
			$code .= ($a->type == 'file' ? $jquery_file_code : '');
			$preview = ($a->type == 'file' ? "<img class=\"offset-1 col-2\" id=\"id-out-{$a->name}\" width=\"3%\" height=\"3%\" src=\"\" alt=\"\"/>" : '');
			$code .= <<<HTML
	
	<div class="row form-inline form-group">
		<label for="id-{$a->name}" class="col-2 justify-content-end">$capitalized</label>
		<input id="id-{$a->name}" type="$type" name="{$a->name}" class="col-$size form-control" $autofocus>
		$preview
	</div>


HTML;
		}
	}
	return $code;
}

// ------------------------------
function generate_view_update_non_dependants($class, $classes) {
	$code = <<<CODE

	<input type="hidden" name="id" value="<?= \$body['{$class->name}']->id ?>">

CODE;
	foreach ( $class->attributes as $a ) {
		if (! $a->is_dependant ()) {
			$capitalized = ucfirst ( $a->name );
			$type = ($a->type == 'String' ? 'text' : $a->type);
			$size = $a->type == 'date' ? '3' : '6';
			$jquery_file_code = <<<CODE
			
	<script>
		 $(window).on("load",(function(){
		 $(function() {
		 $('#id-{$a->name}').change(function(e) {addImage(e);});
		function addImage(e){
			var file = e.target.files[0],
			imageType = /image.*/;
			if (!file.type.match(imageType)) return;
			var reader = new FileReader();
			reader.onload = fileOnload;
			reader.readAsDataURL(file);
		}
		function fileOnload(e) {
		var result=e.target.result;
		$('#id-out-{$a->name}').attr("src",result);
		}});}));
	</script>
				
				
CODE;
			$code .= ($a->type == 'file' ? $jquery_file_code : '');
			$src = "<?=base_url().'assets/upload/'.\$body['{$class->name}']->{$a->name}?>";
			$preview = ($a->type == 'file' ? "<img class=\"offset-1 col-2\" id=\"id-out-{$a->name}\" width=\"3%\" height=\"3%\" src=\"$src\" alt=\"\"/>" : '');
			
			$code .= <<<HTML

	<div class="row form-inline form-group">
		<label for="id-{$a->name}" class="col-2 justify-content-end">$capitalized</label>
		<input id="id-{$a->name}" type="$type" name="{$a->name}" value="<?=  \$body['{$class->name}']->{$a->name} ?>" class="col-$size form-control">
		$preview
	</div>
				
				
HTML;
		}
	}
	return $code;
}

// ------------------------------
function generate_view_create_dependants($class, $classes) {
	$code = '';
	
	foreach ( $class->attributes as $a ) {
		
		if ($a->is_dependant () && ! $a->hidden_create) {
			$name_capitalized = ucfirst ( explode ( '_', $a->name ) [0] );
			$type_plural_capitalized = ucfirst ( plural ( $a->type ) );
			$main_attribute = getMainAttributeName ( $classes, $a->type );
			$legend_2m = ($a->mode == 'M2M' ? $type_plural_capitalized : ($a->mode == 'O2M') ? $name_capitalized : 'dontCare');
			
			if ($a->mode == 'M2O' || $a->mode == 'O2O') {
				$if_O2O_begin = ($a->mode != 'O2O' ? '' : "<?php if ( \${$a->type}->{$a->name}_id == null ): ?>");
				$if_O2O_end = ($a->mode != 'O2O' ? '' : '<?php endif; ?> ');
				$code .= <<<SELECT_CODE

	<div class="row form-inline form-group">
		<label for="id-{$a->name}" class="col-2 justify-content-end">$name_capitalized</label>
		<select id="id-{$a->name}" name="{$a->name}" class="col-6 form-control">
			<option value="0"> ----- </option>
			<?php foreach (\$body['{$a->type}'] as \${$a->type} ): ?>
				$if_O2O_begin
					<option value="<?= \${$a->type}->id ?>"><?= \${$a->type}->$main_attribute ?></option>
				$if_O2O_end
			<?php endforeach; ?>
		</select>
	</div>


SELECT_CODE;
			}
			if ($a->mode == 'O2M' || $a->mode == 'M2M') {
				$if_O2M_begin = ($a->mode != 'O2M' ? '' : "<?php if ( \${$a->type}->{$a->name}_id == null ): ?>");
				$if_O2M_end = ($a->mode != 'O2M' ? '' : '<?php endif; ?> ');
				$code .= <<<CHECKBOX_CODE

	<div class="row form-inline form-group">

		<label class="col-2 justify-content-end">$legend_2m</label>
		<div class="col-6 form-check form-check-inline justify-content-start">

			<?php foreach (\$body['{$a->type}'] as \${$a->type} ): ?>
				$if_O2M_begin
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="checkbox" id="id-{$a->name}-<?=\${$a->type}->id?>" name="{$a->name}[]" value="<?= \${$a->type}->id ?>">
						<label class="form-check-label" for="id-{$a->name}-<?=\${$a->type}->id?>" ><?= \${$a->type}->$main_attribute ?></label>
					</div>
				$if_O2M_end
			<?php endforeach; ?>
		</div>
	</div>


CHECKBOX_CODE;
			}
		}
	}
	
	return $code;
}

// ------------------------------
function code_get_ids() {
	$code = <<<CODE


	function get_ids(\$beans) {
		\$sol = [];
		foreach (\$beans as \$bean) {
			\$sol[] = \$bean -> id;
		}
		return \$sol;
	}

CODE;
	
	return $code;
}

// ------------------------------
function generate_view_update_dependants($class, $classes) {
	$code = '';
	
	foreach ( $class->attributes as $a ) {
		
		if ($a->is_dependant () && ! $a->hidden_create) {
			$name_capitalized = ucfirst ( explode ( '_', $a->name ) [0] );
			$type_capitalized = ucfirst ( $a->type );
			$type_plural_capitalized = ucfirst ( plural ( $a->type ) );
			$main_attribute = getMainAttributeName ( $classes, $a->type );
			$legend_2m = ($a->mode == 'M2M' ? $type_plural_capitalized : ($a->mode == 'O2M') ? $name_capitalized : 'dontCare');
			
			if ($a->mode == 'M2O' || $a->mode == 'O2O') {
				$if_O2O_start = $a->mode == 'O2O' ? "<?php if ( \${$a->type} -> {$a->name}_id == null  || \${$a->type} -> fetchAs('{$class->name}') -> {$a->name} -> id == \$body['{$class->name}']->id ): ?>" : '';
				$if_O2O_end = $a->mode == 'O2O' ? '<?php endif; ?>' : '';
				$code .= <<<SELECT_CODE
				
	<div class="row form-inline form-group">
		<label for="id-{$a->name}" class="col-2 justify-content-end">$name_capitalized</label>
		<select id="id-{$a->name}" name="{$a->name}" class="col-6 form-control">
			<option value="0" <?= \$body['{$class->name}']->fetchAs('{$a->type}')->{$a->name} == null ? 'selected="selected"' : '' ?> > ----- </option> 
		<?php foreach (\$body['{$a->type}'] as \${$a->type} ): ?>
			$if_O2O_start
			<option value="<?= \${$a->type}->id ?>" <?= selected(\$body['{$class->name}']->fetchAs('{$a->type}')->{$a->name}, \${$a->type}->id ) ?>><?= \${$a->type}->$main_attribute ?></option>
			$if_O2O_end
		<?php endforeach; ?>
					
		</select>
	</div>
					
SELECT_CODE;
			}
			if ($a->mode == 'O2M' || $a->mode == 'M2M') {
				$checked_string = ($a->mode == 'M2M' ? "<?= checked(\$body['{$class->name}']->aggr('own{$name_capitalized}List','$a->type'), \${$a->type}->id ) ?>" : "<?= checked(\$body['{$class->name}']->alias('{$a->name}')->own{$type_capitalized}List, \${$a->type}->id ) ?>");
				$if_O2M_start = $a->mode == 'O2M' ? "<?php if ( \${$a->type} -> fetchAs('{$class->name}') -> {$a->name} == null || \${$a->type} -> fetchAs('{$class->name}') -> {$a->name} -> id == \$body['{$class->name}']->id ): ?>" : '';
				$if_O2M_end = $a->mode == 'O2M' ? '<?php endif; ?>' : '';
				$code .= <<<CHECKBOX_CODE
				

	<div class="row form-inline form-group">
		<label class="col-2 justify-content-end">$legend_2m</label>
		<div class="col-6 form-check form-check-inline justify-content-start">

			<?php foreach (\$body['{$a->type}'] as \${$a->type} ): ?>
				$if_O2M_start
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="checkbox" id="id-{$a->name}-<?=\${$a->type}->id ?>" name="{$a->name}[]" value="<?= \${$a->type}->id ?>" $checked_string>
					<label class="form-check-label" for="id-{$a->name}-<?=\${$a->type}->id?>" ><?= \${$a->type}->$main_attribute ?></label>
				</div>
				$if_O2M_end
			<?php endforeach; ?>
						
		</div>
	</div>


CHECKBOX_CODE;
			}
		}
	}
	
	return $code;
}

// ------------------------------
function generate_view_create_end($class_name) {
	$code = <<<HTML

<div class="row offset-2 col-6">
	<input type="submit" class="btn btn-primary" value="Crear">

</form>


<form action="<?=base_url()?>$class_name/list" method="post">
	<input type="hidden" name="filter" value="<?=\$body['filter']?>" />
	<input type="submit" class="offset-1 btn btn-primary" value="Cancelar">
</form>

</div>

</div>	
HTML;
	return $code;
}

// ------------------------------
function generate_view_update_end($class_name) {
	$code = <<<HTML
<div class="row offset-2 col-6">
	<input type="submit" class="btn btn-primary" value="Actualizar">
</form>


<form action="<?=base_url()?>$class_name/list" method="post">
	<input type="hidden" name="filter" value="<?=\$body['filter']?>" />
	<input type="submit" class="offset-1 btn btn-primary" value="Cancelar">
</form>

</div>

			
HTML;
	
	return $code;
}

// ------------------------------
function generate_view_create_post($class) {
	$code = <<<CODE
<?php
// CODIGO de la VISTA {$class->name} CREATE POST AJAX
?>
CODE;
	file_put_contents ( APPPATH . 'views' . DIRECTORY_SEPARATOR . $class->name . DIRECTORY_SEPARATOR . 'create_post.php', $code );
}

// ------------------------------
function getMainAttributeName($classes, $class_name) {
	$name = 'unknown';
	foreach ( $classes as $c ) {
		if ($c->name == $class_name) {
			$name = $c->getMainAttribute ();
		}
	}
	return $name;
}

// ------------------------------
function generate_view_list($class, $classes) {
	$title = 'LISTA de '; // TODO LOC
	$cn = $class->name;
	$ma = $class->getMainAttribute ();
	$code = <<<CODE

<script>
	$(document).ready(function() 
	    { 
	        $("#myTable").tablesorter(); 
	    } 
	);
</script>

<?php error_reporting(0); ?>
<div class="container">
<div class="row">
	<div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
		<form id="id-create" class="form-inline"  action="<?=base_url()?>$cn/create">
			<input type="hidden" id="id-createfilter" name="filter" value="" />
			<input type="button" class="btn btn-primary" value="Crear $cn" autofocus="autofocus"
				onclick="getElementById('id-createfilter').value  = getElementById('id-filter').value ;getElementById('id-create').submit() ;">
		</form>
	</div>

	<div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
		<form class="form-inline" action="<?=base_url()?>$cn/list" method="post">
			<label for="id-filter">Filtrar</label>
			<input id="id-filter" type="search" name="filter" value="<?=\$body['filter']?>" class="form-control" >
		</form>
	</div>
</div>

<h1>$title $cn</h1>

<table id="myTable" class="table table-hover table-striped tablesorter">
	<thead>
	<tr>
		<th>$ma</th>
CODE;
	foreach ( $class->attributes as $a ) {
		if (! $a->hidden_recover) {
			if (! $a->main) {
				if (! ($a->is_dependant ())) {
					$code .= '		<th>' . $a->name . '</th>' . PHP_EOL;
				} else {
					$code .= '		<th>' . $a->name . ' - ' . getMainAttributeName ( $classes, $a->type ) . "({$a->type})</th>" . PHP_EOL;
				}
			}
		}
	}
	$code .= <<<CODE
		<th>Acciones</th>
	</tr>
	</thead>

	<tbody>
	<?php foreach (\$body['$cn'] as \$$cn): ?>
		<tr>
			<td class="alert alert-success"><?= str_ireplace(\$body['filter'], '<kbd>'.\$body['filter'].'</kbd>', \$$cn -> $ma) ?></td>

CODE;
	foreach ( $class->attributes as $a ) {
		if (! $a->hidden_recover) {
			if (! $a->main) {
				if (! ($a->is_dependant ())) {
					if ($a->type == 'file') { // ============ REGULAR FILE ATTRIBUTE ===============
						$img_path = "( ( \$$cn -> {$a->name} == null || \$$cn -> {$a->name} == '' ) ? 'assets/img/icons/png/ban-4x.png' : 'assets/upload/'.\$$cn -> {$a->name})";
						$img_size = "<?=( \$$cn -> {$a->name} == null || \$$cn -> {$a->name} == '' ) ? 15 : 30?>";
						$code .= "\n\t\t\t<td><img src=\"<?=base_url().$img_path?>\" alt=\"IMG\" width=\"$img_size\" height=\"$img_size\" /></td>" . PHP_EOL;
					} else { // ================================= REGULAR ATTRIBUTE ====================
						$td_content = "\$$cn -> {$a->name}";
						$code .= "\n\t\t\t<td><?= str_ireplace(\$body['filter'], '<kbd>'.\$body['filter'].'</kbd>',$td_content) ?></td>" . PHP_EOL;
					}
				} else {
					$main_attribute_name = getMainAttributeName ( $classes, $a->type );
					if ($a->mode == 'M2O' || $a->mode == 'O2O') { // ===== SOMETHING TO ONE RELATIONSHIPS =======
						$td_content = "\$$cn ->  fetchAs('{$a->type}') -> {$a->name} -> {$main_attribute_name}";
						$code .= "\n\t\t\t<td><?= str_ireplace(\$body['filter'], '<kbd>'.\$body['filter'].'</kbd>',$td_content) ?></td>" . PHP_EOL;
					} else if ($a->mode == 'O2M') { // ============ ONE TO MANY RELATIONSHIP ====================
						$capital_type = ucfirst ( $a->type );
						
						$code .= <<<CODE

			<td>
			<?php foreach (\$$cn -> alias ('{$a->name}') -> own{$capital_type}List as \$data): ?>
				<span><?= str_ireplace(\$body['filter'], '<kbd>'.\$body['filter'].'</kbd>', \$data -> $main_attribute_name) ?> </span>
			<?php endforeach; ?>
			</td>

CODE;
					} else if ($a->mode == 'M2M') { // ============ MANY TO MANY RELATIONSHIP ===============
						$capital_name = ucfirst ( $a->name );
						$code .= <<<CODE
					
			<td>
			<?php foreach (\$$cn -> aggr('own{$capital_name}List', '{$a->type}') as \$data): ?>
				<span><?= str_ireplace(\$body['filter'], '<kbd>'.\$body['filter'].'</kbd>', \$data -> $main_attribute_name ) ?> </span>
			<?php endforeach; ?>
			</td>
				
CODE;
					}
				}
			}
		}
	}
	$code .= <<<CODE

			<td class="form-inline text-center">

				<form id="id-update-<?= \$$cn -> id ?>" action="<?= base_url() ?>$cn/update" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= \$$cn -> id ?>">
					<input type="hidden" name="filter" value="" id="id-updatefilter-<?= \$$cn -> id ?>">
					<button onclick="getElementById('id-updatefilter-<?= \$$cn -> id ?>').value  = getElementById('id-filter').value ;getElementById('id-update').submit() ;">
						<img src="<?= base_url() ?>assets/img/icons/png/pencil-2x.png" height="15" width="15" alt="editar">
					</button>
				</form>

				<form id="id-delete-<?= \$$cn -> id ?>" action="<?= base_url() ?>$cn/delete" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= \$$cn -> id ?>">
					<input type="hidden" name="filter" value="" id="id-deletefilter-<?= \$$cn -> id ?>">
					<button onclick="getElementById('id-deletefilter-<?= \$$cn -> id ?>').value  = getElementById('id-filter').value ;getElementById('id-delete').submit() ;">
						<img src="<?= base_url() ?>assets/img/icons/png/trash-2x.png" height="15" width="15" alt="borrar">
					</button>
				</form>

			</td>

		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
</div>
<?php error_reporting(E_ALL); ?>
CODE;
	
	file_put_contents ( APPPATH . 'views' . DIRECTORY_SEPARATOR . $class->name . DIRECTORY_SEPARATOR . 'list.php', $code );
}

// ------------------------------
function db_create_and_freeze($classes) {
	R::freeze ( false );
	foreach ( $classes as $class ) {
		db_bean_test_create ( $class );
	}
	
	R::freeze ( true );
}

// ------------------------------

/**
 *
 * @param string $path
 *        	the path to file from application folder without the first slash
 */
function file_application_replace($path, $pattern, $replacement) {
	$app_file = APPPATH . implode ( DIRECTORY_SEPARATOR, explode ( '/', $path ) );
	$content = file_get_contents ( $app_file );
	file_put_contents ( $app_file, preg_replace ( $pattern, $replacement, $content ) );
}

// ------------------------------
function db_bean_test_create($class) {
	$cn = $class->name;
	
	foreach ( $class->attributes as $a ) {
		$bean = R::dispense ( $cn );
		$name = $a->name;
		if (! $a->collection) { // REGULAR ATTRIBUTE
			switch ($a->type) {
				case "String" :
					;
				case "file" :
					$bean->$name = "TEST";
					break;
				case "number" :
					$bean->$name = "0";
					break;
				case "date" :
					$bean->$name = "1900-12-31";
					break;
			}
			R::store ( $bean );
			R::trash ( $bean );
		} else {
			$type = $a->type;
			
			if ($a->mode == 'O2O') { // ONE TO ONE
				
				$o2o = R::dispense ( $type );
				
				$bean->$name = $o2o;
				R::store ( $bean );
				
				$o2o->$name = $bean;
				R::store ( $o2o );
				
				R::trash ( $bean );
				/*
				 * $name .= '_id';
				 * $o2o -> $name = NULL;
				 * R::store( $o2o );
				 */
				R::trash ( $o2o );
			}
			
			if ($a->mode == 'O2M') { // ONE TO MANY
				R::store ( $bean );
				$o2m = R::dispense ( $type );
				$o2m->$name = $bean;
				R::store ( $o2m );
				
				R::trash ( $bean );
				R::trash ( $o2m );
			}
			
			if ($a->mode == 'M2M') { // MANY TO MANY
				R::store ( $bean );
				$another_bean = R::dispense ( $type );
				R::store ( $another_bean );
				$m2m = R::dispense ( $name );
				$m2m->$cn = $bean;
				$m2m->$type = $another_bean;
				R::store ( $m2m );
				
				R::trash ( $m2m );
				R::trash ( $bean );
				R::trash ( $another_bean );
			}
		}
	}
}

// ------------------------------

?>