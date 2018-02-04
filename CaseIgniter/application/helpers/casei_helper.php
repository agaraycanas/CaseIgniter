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
			$this->add_main_attribute ( new Attribute ( "nombre", "String", false, "NO_MODE", false, false, true ) ); // TODO LOCALIZE
		}
	}
	public function getMainAttribute() {
		$name = 'nombre'; // TODO LOCALIZE
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
		generate_view ( $class, $classes );
	}
}
// ------------------------------
function generate_controller($class) {
	$code = '';
	$code .= generate_controller_header ( $class->name );
	$code .= generate_controller_create ( $class );
	$code .= generate_controller_create_post ( $class );
	$code .= generate_controller_list ( $class );
	$code .= generate_controller_end ();
	file_put_contents ( APPPATH . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $class->name . '.php', $code );
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
	$has_dependants = false;
	$code .= generate_controller_create_header ( $class->name );
	if ($class->has_dependants ()) {
		foreach ( $class->attributes as $a ) {
			if ($a->is_dependant () && ! ($a->hidden_create)) {
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
				$code .= "\t\t\$$a->name = ( isset( \$_POST['$a->name']) ? \$_POST['$a->name'] : null );" . PHP_EOL;
			} else {
				$code .= "\t\t\$$a->name = ( isset( \$_POST['$a->name']) ? \$_POST['$a->name'] : [] );" . PHP_EOL;
			}
		}
	}
	
	$code .= (PHP_EOL . "\t\ttry {" . PHP_EOL);
	$code .= "\t\t\t\$this->{$class->name}_model->create( ";
	
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
	
	// TODO LOCALIZATION
	$code .= <<<CATCH
			\$data['status'] = 'ok';
			\$data['message'] = "$capital \$$main_attribute creado/a correctamente";
			\$this->load->view('{$class->name}/create_message',\$data);
		}
		catch (Exception \$e) {
			\$data['status'] = 'error';
			\$data['message'] = 'Error al crear el/la {$class->name} \$$main_attribute';
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
function generate_controller_create_header($class_name) {
	$code = <<<CODE


	/**
	* Controller action CREATE for controller $class_name
	* autogenerated by CASE IGNITER
	*/
	public function create() {
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
function generate_controller_create_non_dependants() {
	$code = <<<CODE


		\$data = [];
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
function generate_controller_list($class) {
	$cn = $class->name;
	$code = <<<CODE
	public function list() {
		\$this->load->model('{$cn}_model');
		\$filter = isset(\$_POST['filter'])?\$_POST['filter']:'';
		\$data['body']['$cn'] = (\$filter == '' ? \$this->{$cn}_model->get_all() : \$this->{$cn}_model->get_filtered( \$filter ) );
		enmarcar(\$this, '{$cn}/list', \$data);
	}
CODE;
	return $code;
}
// --------------------------------
function generate_model($class) {
	$code = '';
	$code .= generate_model_header ( $class->name );
	$code .= generate_model_create ( $class );
	$code .= generate_model_get_all ( $class );
	$code .= generate_model_get_filtered ();
	$code .= generate_model_end ();
	file_put_contents ( APPPATH . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . $class->name . '_model.php', $code );
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
function generate_model_create($class) {
	$code = '';
	$code .= 'public function create( ';
	$parameters = '';
	foreach ( $class->attributes as $a ) {
		if (! $a->hidden_create) {
			$parameters .= "$$a->name, ";
		}
	}
	$parameters = rtrim ( $parameters, ', ' );
	$code .= $parameters;
	
	$code .= " ) {" . PHP_EOL;
	$code .= "\t\$bean = R::dispense( '{$class->name}' );" . PHP_EOL . PHP_EOL;
	foreach ( $class->attributes as $a ) {
		if (! $a->hidden_create) {
			if ($a->mode == "M2M") {
				$code .= "// TODO M2M";
			} else if ($a->mode == "O2M") {
				$code .= "// TODO O2M";
			} else {
				$code .= "\t\$bean -> {$a->name} = \${$a->name};" . PHP_EOL;
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
	public function get_all() {
		return R::findAll('{$class->name}');
	}

CODE;
	return $code;
}

// --------------------------------
function generate_model_get_filtered() {
	$code = <<<CODE
	public function get_filtered(\$filter) {
		return [];
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
	mkdir ( APPPATH . 'views' . DIRECTORY_SEPARATOR . $class->name );
	generate_view_create ( $class, $classes );
	generate_view_create_post ( $class );
	generate_view_create_message ( $class );
	generate_view_list ( $class, $classes );
}

// ------------------------------
function generate_view_create($class, $classes) {
	$code = '';
	$code .= generate_view_create_ajax ( $class->name );
	$code .= generate_view_create_header ( $class->name );
	$code .= generate_view_create_non_dependants ( $class );
	$code .= generate_view_create_dependants ( $class, $classes );
	$code .= generate_view_create_end ();
	
	file_put_contents ( APPPATH . 'views' . DIRECTORY_SEPARATOR . $class->name . DIRECTORY_SEPARATOR . 'create.php', $code );
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
	$code = <<<JAVASCRIPT

<script type="text/javascript" src="<?= base_url() ?>assets/js/serialize.js"></script>

<script type="text/javascript">
var connection;

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

<!--------------------------------------------->


JAVASCRIPT;
	return $code;
}

// ------------------------------
function generate_view_create_header($class_name) {
	$code = <<<HTML
<div class="container">
<h2> Crear $class_name </h2>

<form class="col-sm-4" id="idForm">


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
			$code .= <<<HTML
	<div class="form-group">
		<label for="id-{$a->name}">$capitalized</label>
		<input id="id-{$a->name}" type="$type" name="{$a->name}" class="form-control">
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
		
		if ($a->is_dependant ()) {
			$capitalized = ucfirst ( explode ( '_', $a->name ) [0] );
			$plural_capitalized = ucfirst ( plural ( $a->type ) );
			$main_attribute = getMainAttributeName ( $classes, $a->type );
			
			if ($a->mode == 'M2O') {
				$code .= <<<SELECT

	<div class="form-group">
		<label for="id-{$a->name}">$capitalized</label>
		<select id="id-{$a->name}" name="{$a->name}" class="form-control">
		<?php foreach (\$body['{$a->type}'] as \${$a->type} ): ?>
			<option value="{$a->type}->id">{$a->type}->\$$main_attribute</option>
		<?php endforeach; ?>
		
		</select>
	</div>


SELECT;
			}
			if ($a->mode == 'O2M' || $a->mode == 'M2M') {
				$code .= <<<CHECKBOX
	<fieldset>
		<legend>$plural_capitalized</legend>
		<div class="form-group">
			<?php foreach (\$body['{$a->type}'] as \${$a->type} ): ?>
				<label for="id-{$a->name}" class="checkbox-inline">$capitalized</label>
				<input type="checkbox" id="id-{$a->name}" name="{$a->name}[]" class="form-control" value="{$a->type}->id">

			<?php endforeach; ?>

		</div>
	</fieldset>


CHECKBOX;
			}
		}
	}
	
	return $code;
}
// ------------------------------
function generate_view_create_end() {
	$code = <<<HTML

	<input type="button" class="btn btn-primary" onclick="create()" value="Crear">

</form>

<div id="idMessage" class="col-sm-4">
</div>

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
	$title = 'LISTA de '; // TODO LOCALIZE
	$cn = $class->name;
	$ma = $class->getMainAttribute ();
	$code = <<<CODE
<div class="container">
<form action="<?=base_url()?>$cn/create"><input type="submit" class="btn btn-primary" value="Crear $cn"></form>
<h1>$title $cn</h1>
<table>
	<tr>
		<th>$ma<th>
CODE;
	foreach ( $class->attributes as $a ) {
		if (! $a->main) {
			if (! ($a->is_dependant ())) {
				$code .= '		<th>' . $a->name . '</th>' . PHP_EOL;
			} else {
				$code .= '		<th>' . getMainAttributeName ( $classes, $a->type ) . '</th>' . PHP_EOL;
			}
		}
	}
	$code .= <<<CODE
	</tr>

	<?php foreach (\$body['$cn'] as \$$cn): ?>
	<?php endforeach; ?>
</table>
</div>
CODE;
	
	file_put_contents ( APPPATH . 'views' . DIRECTORY_SEPARATOR . $class->name . DIRECTORY_SEPARATOR . 'list.php', $code );
}

// ------------------------------

?>