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
	public function __construct($name) {
		$this->name = $name;
	}
	public function add_attribute($attribute) {
		array_push ( $this->attributes, $attribute );
	}
	public function has_collections() {
		$answer = false;
		foreach ( $this->attributes as $a ) {
			$answer |= $a->collection;
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
			$this->add_attribute ( new Attribute ( "nombre", "String", false, "NO_MODE", false, false, true ) );  // TODO LOCALIZE
		}
	}
	public function getMainAttribute() {
		$name = 'nombre'; //TODO LOCALIZE
		foreach ($this->attributes as $a) {
			if ($a->main) {
				$name = $a -> name;
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
function process_domain_model($modelData) {
	$lines = $modelData;
	
	$classes = [ ];
	$class = true; // Comenzamos procesando el nombre de una clase
	$current_class = null;
	
	foreach ( explode ( "\n", $lines ) as $line ) {
		$line = trim ( $line );
		
		if ($line == "") {
		} else if ($line == "--") {
			$class = ! $class;
			if ($class) { // Done of proccessing attributes
				$current_class->setMainAttribute ();
				array_push ( $classes, $current_class );
				$current_class = null;
			} else { // Starting proccessing attributes
			}
		} else {
			if ($class) { // CLASS PROCESSING
				$class_name = $line;
				$current_class = new MyClass ( $class_name );
			} else { // ATTRIBUTE PROCCESSING
				$mode = 'NO_MODE';
				$type = 'String';
				$name = 'NO_NAME';
				$collection = false;
				$hidden_create = false;
				$hidden_recover = false;
				$main = false;
				
				if (strlen ( $line ) > 1) {
					if (substr ( $line, 0, 1 ) == '_') {
						$hidden_create = true;
					} else if (substr ( $line, 0, 1 ) == '_') {
						$hidden_recover = true;
					} else if (substr ( $line, 0, 1 ) == '&') {
						$main = true;
					}
					$line = trim ( $line, '_-&' );
					
					if (substr ( $line, 0, 2 ) == '**') {
						$collection = true;
						$mode = "M2M";
					} else if (substr ( $line, 0, 2 ) == '*>') {
						$collection = true;
						$mode = 'M2Mi';
					} else if (substr ( $line, 0, 1 ) == '*') {
						$collection = true;
						$mode = 'O2M';
					} else if (substr ( $line, 0, 1 ) == '>') {
						$mode = "M2O";
					} else if (substr ( $line, 0, 1 ) == '!') {
						$mode = "UNIQUE";
					} else if (substr ( $line, 0, 1 ) == '.') {
						$mode = "O2O";
					}
					
					$my_line = trim ( $line, '.!>*' );
					$part = explode ( ':', $my_line );
					$name = $part [0];
					$type = sizeof ( $part ) == 1 ? 'String' : ($part [1] == '#' ? 'number' : ($part [1] == '%' ? 'date' : $part [1]));
				} else { // Unique char. The name of the attribute
					$name = substr ( $line, 0, 1 );
				}
				
				$current_class->add_attribute ( new Attribute ( $name, $type, $collection, $mode, $hidden_create, $hidden_recover, $main ) );
			}
		}
	}
	
	return $classes;
}

// ------------------------------
function delete_directory($path, $ignore_files, $first_level) {
	// echo "<h4>$path</h4>($first_level)"; //TODO DEBUG
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
	$html = '<img src="http://yuml.me/diagram/class/';
	foreach ( $classes as $class ) {
		$html .= '[' . ucfirst ( $class->name );
		$html .= '|';
		$i = 0;
		foreach ( $class->attributes as $a ) {
			if (! is_dependant ( $a )) {
				$html .= $a->name;
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
		foreach ( $class->attributes as $i => $a ) {
			switch ($a->mode) {
				case 'M2O' :
					$html .= '[' . ($class->name) . ']*- ' . $a->name . '  1[' . ($a->type) . '],';
					break;
				case 'O2M' :
					$html .= '[' . ($class->name) . ']1  ' . $a->name . '-*[' . ($a->type) . '],';
					break;
				case 'O2O' :
					$html .= '[' . ($class->name) . ']1- ' . $a->name . '  1[' . ($a->type) . '],';
					break;
				case 'M2M' :
					$html .= '[' . ($class->name) . ']*- ' . $a->name . '  *[' . ($a->type) . '],';
					break;
			}
			unset ( $class->attributes [$i] );
			delete_attribute ( $classes, $a->type, $a->name );
		}
	}
	return $html . '">';
}

// ------------------------------
function delete_directories() {
	$ignore_files = [ 
			'_casei.php',
			'_casei',
			'errors',
			'templates',
			'index.html' 
	];
	
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
function generate_menus($menuData, $appTitle, $clases) {
	$nav = <<<NAVI
<nav class="container navbar navbar-inverse">
  <div class="navbar-header">
    <a class="navbar-brand" href="<?=base_url()?>"><span class="glyphicon glyphicon-home"></span></a>
  </div>
  <div class="collapse navbar-collapse" id="myNavbar">
    <ul class="nav navbar-nav">
NAVI;
	
	foreach ( explode ( "\n", $menuData ) as $menu ) {
		if (trim ( $menu ) == 'CRUD') {
			$nav .= generate_CRUD ( $clases );
		} else {
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
  </div>
</nav>
NAV;
	return $nav;
}

// ------------------------------
function generate_CRUD($classes) {
	$crud = <<<NAV
      <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
           BEANS<span class="caret"></span>
        </a>
		<ul class="dropdown-menu">
NAV;
	foreach ( $classes as $class ) {
		$n = $class->name;
		$crud .= <<<NAV
		<li><a href="<?=base_url()?>$n/list">$n</a></li>
NAV;
	}
	$crud .= <<<NAV
	    </ul>
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
	delete_directories ();
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
		generate_model ( $class );
	}
}

// ------------------------------
function generate_views($classes) {
	foreach ( $classes as $class ) {
		generate_view ( $class , $classes);
	}
}
// ------------------------------
function generate_controller($class) {
	$cn = $class->name;
	$code = <<<CODE
<?php
/**
* Controller code autogenerated by CASE IGNITER
*/
class $cn extends CI_Controller {
	public function create() {
	}

	public function create_post() {
	}

	public function list() {
		\$this->load->model({$cn}_model);
		\$filter = isset(\$_POST['filter'])?\$_POST['filter']:'';
		\$data['body']['$cn'] = (\$filter == '' ? \$this->{$cn}_model->getAll() : \$this->{$cn}_model->getFiltered( \$filter ) );
		enmarcar(\$this, '{$cn}/list', \$data);
	}
}
?>
CODE;
	file_put_contents ( APPPATH . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $class->name . '.php', $code );
}

// ------------------------------
function generate_model($class) {
	$code = <<<CODE
<?php
// CODIGO del MODELO {$class->name}
?>
CODE;
	file_put_contents ( APPPATH . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . $class->name . '_model.php', $code );
}

// ------------------------------
function generate_view($class, $classes) {
	mkdir(APPPATH .  'views' . DIRECTORY_SEPARATOR . $class->name);
	generate_view_create ( $class );
	generate_view_create_post ($class);
	generate_view_list ( $class , $classes);
}
// ------------------------------
function generate_view_create($class) {
	$code = <<<CODE
<?php
// CODIGO de la VISTA {$class->name} CREATE
?>
CODE;
	file_put_contents ( APPPATH .  'views' . DIRECTORY_SEPARATOR . $class->name . DIRECTORY_SEPARATOR . 'create.php', $code );
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
	foreach ($classes as $c ) {
		if ($c->name == $class_name) {
			$name = $c->getMainAttribute();
		}
	}
	return $name;
}

// ------------------------------
function generate_view_list($class, $classes) {
	$title = 'LISTA de '; //TODO LOCALIZE
	$cn = $class -> name;
	$ma = $class -> getMainAttribute();
	$code = <<<CODE
<div class="container">
<h1>$title $cn</h1>
<table>
	<tr>
		<th>$ma<th>
CODE;
foreach ($class->attributes as $a) {
	if (!$a->main) {
		if (!($a->is_dependant())) {
			$code .= '		<th>'.$a->name.'</th>'.PHP_EOL;
		}
		else {
			$code .= '		<th>'.getMainAttributeName($classes, $a->type).'</th>'.PHP_EOL;
		}
	}
}
	$code .= <<<CODE
	</tr>

	<?php foreach (\$body['$cn'] as \$$cn): ?>
	<?php endforeach; ?>
</div>
CODE;

	file_put_contents ( APPPATH .  'views' . DIRECTORY_SEPARATOR . $class->name . DIRECTORY_SEPARATOR . 'list.php', $code );
}


// ------------------------------

?>