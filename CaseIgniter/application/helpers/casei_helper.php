<?php
class Attribute {
	public $name;
	public $type;
	public $collection;
	public $mode;
	public $hidden_create;
	public $hidden_recover;
	public function __construct($name, $type, $collection, $mode, $hidden_create = false, $hidden_recover = false) {
		$this->name = $name;
		$this->type = $type;
		$this->collection = $collection;
		$this->mode = $mode;
		$this->hidden_create = $hidden_create;
		$this->hidden_recover = $hidden_recover;
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
				
				if (strlen ( $line ) > 1) {
					if (substr ( $line, 0, 1 ) == '_') {
						$hidden_create = true;
					} else if (substr ( $line, 0, 1 ) == '_') {
						$hidden_recover = true;
					}
					$line = trim ( $line, '_-' );
					
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
				
				$current_class->add_attribute ( new Attribute ( $name, $type, $collection, $mode, $hidden_create, $hidden_recover ) );
			}
		}
	}
	
	return $classes;
}

// ------------------------------
function delete_directory($dir, $ignore_files) {
	if (! file_exists ( $dir )) {
		return true;
	}
	
	if (! is_dir ( $dir ) && ! in_array ( basename ( $dir ), $ignore_files )) {
		return unlink ( $dir );
	}
	
	foreach ( scandir ( $dir ) as $item ) {
		if ($item == '.' || $item == '..') {
			continue;
		}
		
		if (! delete_directory ( $dir . DIRECTORY_SEPARATOR . $item, $ignore_files )) {
			return false;
		}
	}
	
	return rmdir ( $dir );
}
// ------------------------------
function delete_attribute($classes, $class_name, $attribute_name) {
	foreach ( $classes as $class ) {
		if ($class->name == $class_name) {
			foreach ($class->attributes as $i => $a) {
				if ($a->name == $attribute_name) {
					unset($class->attributes[$i]);
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
		$html .= ('{bg:'.$colors[($c++)].'}');
		$html .= '],';
	}
	
	foreach ( $classes as $class ) {
		foreach ( $class->attributes as $i => $a ) {
			switch ($a->mode) {
				case 'M2O' :
					$html .= '[' . ($class->name) . ']*- '.$a->name.'  1[' . ($a->type) . '],';
					break;
				case 'O2M' :
					$html .= '[' . ($class->name) . ']1  '.$a->name.'-*[' . ($a->type) . '],';
					break;
				case 'O2O' :
					$html .= '[' . ($class->name) . ']1- '.$a->name.'  1[' . ($a->type) . '],';
					break;
				case 'M2M' :
					$html .= '[' . ($class->name) . ']*- '.$a->name.'  *[' . ($a->type) . '],';
					break;
			}
			unset ( $class->attributes [$i] );
			delete_attribute($classes, $a->type, $a->name);
		}
	}
	return $html . '">';
}

// ------------------------------
function delete_directories() {
	$ignore_files = [ 
			'controllers',
			'_casei.php',
			'index.html' 
	];
	delete_directory ( APPPATH . 'controllers', $ignore_files );
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
function write_main($code) {
	global $packageApp, $pathApp;
	$nombreApp = ucfirst ( array_pop ( explode ( '.', $packageApp ) ) );
	
	// echo "Creando {$pathApp}/" . $nombreApp . ".java\n";
	if (! file_exists ( $pathApp )) {
		mkdir ( $pathApp, 0777, true );
	}
	file_put_contents ( $pathApp . '/' . $nombreApp . '.java', $code );
}

// ------------------------------
function process_main() {
	write_main ( addMain () );
}

// ------------------------------
function generate_application_files($classes) {
	generate_controllers ( $classes );
	generate_models ( $classes );
	generate_views ( $classes );
}

// ------------------------------
function generate_controllers($classes) {
}

// ------------------------------
function generate_models($classes) {
}

// ------------------------------
function generate_views($classes) {
}

?>