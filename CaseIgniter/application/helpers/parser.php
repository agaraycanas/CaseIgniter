<?php
function process_domain_model($modelData) {
	
	// ----------------------------------
	
	/**
	 *
	 * @param unknown $line        	
	 * @return $string BEAN_SEPARATOR, ATTRIBUTE_SEPARATOR, BEAN_NAME, ROL_LINE, ATTRIBUTE or UNKNOWN
	 */
	function line_type($line) {
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
		if (preg_match ( "/^((\*\*|\*>|<>|<\*)\s)?[a-z]+(:([a-z]+|\@|\%|\#))?(\s\[[a-zA-Z\-]+(,[a-zA-Z\-]+)*\])?$/", $line )) {
			$type = 'ATTRIBUTE';
		}
		
		return $type;
	}
	
	// ----------------------------------
	function process_bean_name($line) {
		$class = new MyClass ( strtolower ( explode ( ' ', $line ) [0] ) );
		if (strpos ( $line, ' ' )) {
			$class->login_bean = true;
		}
		return $class;
	}
	
	// =============================================================
	function process_attribute($line) {
		error_reporting(0);
		$name = 'NO_NAME';
		$type = 'String';
		$collection = false;
		$mode = 'NO_MODE';
		$hidden_create = false;
		$hidden_recover = false;
		$main = false;
		
		$pattern = "/^([\*\<\>]+\s)?([a-z]+)(\:[a-z\%\@\#]+)?(\s\[[a-zA-Z,\-]+\])?$/";
		preg_match ( $pattern, $line, $matches );
		$multiplicity = $matches[1] != '' ? rtrim($matches[1]) : 'REGULAR';
		$name = $matches [2] ;
		$type = $matches[3] != '' ? ltrim($matches[3],"\s\:") : 'String' ;
		$modifiers = trim($matches[4]," []");
		
		$collection = ($multiplicity != 'REGULAR');

		switch ($multiplicity) {
			case '<>':$mode='O2O';break;
			case '*>':$mode='M2O';break;
			case '<*':$mode='O2M';break;
			case '**':$mode='M2M';break;
		}

		switch ($type) {
			case '#':$type='number';break;
			case '%':$type='date';break;
			case '@':$type='file';break;
		}

		$hidden_create = (strpos($modifiers, 'c-') !== false );
		$hidden_recover = (strpos($modifiers, 'r-') !== false );
		$main = (strpos($modifiers, 'M') !== false ); 
		$mode = (strpos($modifiers, 'U') !== false ) ? 'UNIQUE' : $mode ;
		if ($multiplicity != 'REGULAR' && $mode == 'UNIQUE') {
			throw new Exception ( "ERROR while parsing model.txt: Only REGULAR attributes can be UNIQUE" );
		}
		
		//echo "<pre>$line..$multiplicity||$name||$type||$modifiers</pre>"; //TODO DEBUG
		
		error_reporting(E_ALL);
		
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
					if (line_type ( $line ) != 'BEAN_SEPARATOR') {
						throw new Exception ( "ERROR while parsing model.txt (line $line_number): Bean separator expected" );
					}
					$state = 'bean_name';
					break;
				case 'bean_name' :
					if (line_type ( $line ) != 'BEAN_NAME') {
						throw new Exception ( "ERROR while parsing model.txt (line $line_number): Bean name expected" );
					}
					$current_class = process_bean_name ( $line );
					$state = 'rol_line';
					break;
				case 'rol_line' :
					if (! (line_type ( $line ) == 'ROL_LINE' || line_type ( $line ) == 'ATTRIBUTE_SEPARATOR')) {
						throw new Exception ( "ERROR while parsing model.txt (line $line_number): Rol line or attribute separator expected" );
					}
					if (line_type ( $line ) == 'ATTRIBUTE_SEPARATOR') {
						$state = 'attribute';
					}
					break;
				case 'attribute' :
					if (! (line_type ( $line ) == 'ATTRIBUTE' || line_type ( $line ) == 'BEAN_SEPARATOR')) {
						throw new Exception ( "ERROR while parsing model.txt (line $line_number): Attribute or bean separator expected" );
					}
					if (line_type ( $line ) == 'BEAN_SEPARATOR') {
						$current_class->setMainAttribute();
						$classes [] = $current_class;
						$current_class = null;
						$state = 'idle';
					} else {
						$current_class->add_attribute ( process_attribute ( $line ) );
					}
					break;
			}
		}
	}
	
	if ($state != 'idle') {
		throw new Exception ( "MODEL PARSE ERROR ($line_number): Unexpected end of file " );
	}
	
	return $classes;
}
?>		
		