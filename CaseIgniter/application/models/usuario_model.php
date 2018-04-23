<?php
/**
* Model code autogenerated by CASE IGNITER
*/
class usuario_model extends CI_Model {


	/**
	* create MODEL action autogenerated by CASE IGNITER
	*/
	public function create( $nombre, $pwd, $rol, $trabaja, $pertenece, $realiza, $desarrolla, $grreferencia ) {

	$bean = R::dispense( 'usuario' );


	// Regular attribute
	$bean -> setMeta("buildcommand.unique" , array(array('nombre')) );
	$bean -> nombre = $nombre;

	// Regular attribute
	$bean -> setMeta("buildcommand.unique" , array(array('pwd')) );
	$bean -> pwd = $pwd;

	// Regular attribute
	$bean -> setMeta("buildcommand.unique" , array(array('rol')) );
	$bean -> rol = $rol;
					
	// "many to many" attribute
	foreach ($trabaja as $id) {
		$another_bean = R::load('cursoacademico', $id);
		$m2m = R::dispense('trabaja');
		R::store($bean);
		$m2m -> usuario = $bean;
		$m2m -> cursoacademico = $another_bean;
		R::store($m2m);
	}
				
									
	// "many to many" attribute
	foreach ($pertenece as $id) {
		$another_bean = R::load('departamento', $id);
		$m2m = R::dispense('pertenece');
		R::store($bean);
		$m2m -> usuario = $bean;
		$m2m -> departamento = $another_bean;
		R::store($m2m);
	}
				
									
	// "one to many" attribute
	foreach ($realiza as $id) {
		$o2m = R::load('encuesta', $id);
		$bean -> alias('realiza') ->ownEncuestaList[] = $o2m;
	}
				
									
	// "one to many" attribute
	foreach ($desarrolla as $id) {
		$o2m = R::load('imparticion', $id);
		$bean -> alias('desarrolla') ->ownImparticionList[] = $o2m;
	}
				
				
	// "many to one" attribute
	if ( $grreferencia != null && $grreferencia != 0) {
		$bean -> grreferencia = R::load('grupo',$grreferencia);
	}
				
				
	R::store($bean);

	return $bean->id;
}


	/**
	* update MODEL action autogenerated by CASE IGNITER
	*/
	public function update( $id, $nombre, $pwd, $rol, $trabaja, $pertenece, $realiza, $desarrolla, $grreferencia ) {

	$bean = R::load( 'usuario', $id );


	// Regular attribute
	$bean -> nombre = $nombre;

	// Regular attribute
	$bean -> pwd = $pwd;

	// Regular attribute
	$bean -> rol = $rol;
				
	// "many to many" attribute (M2M)
	
	foreach ($bean->ownTrabajaList as $trabaja_bean ) {
		$key = array_search( $trabaja_bean->cursoacademico->id, $trabaja );
		
		if ($key !== false) { // M2M we keep only the keys to add
			unset($trabaja[$key]);
		}
		else { // M2M Element to be deleted
			R::store($bean);
			R::trash($trabaja_bean);
		}
	}

	// M2M Elements to be added
	foreach ($trabaja as $idf) {
		$another_bean = R::load('cursoacademico', $idf);
		$m2m = R::dispense('trabaja');
		$m2m -> usuario = $bean;
		$m2m -> cursoacademico = $another_bean;
		R::store($m2m);
	}

					
	// "many to many" attribute (M2M)
	
	foreach ($bean->ownPerteneceList as $pertenece_bean ) {
		$key = array_search( $pertenece_bean->departamento->id, $pertenece );
		
		if ($key !== false) { // M2M we keep only the keys to add
			unset($pertenece[$key]);
		}
		else { // M2M Element to be deleted
			R::store($bean);
			R::trash($pertenece_bean);
		}
	}

	// M2M Elements to be added
	foreach ($pertenece as $idf) {
		$another_bean = R::load('departamento', $idf);
		$m2m = R::dispense('pertenece');
		$m2m -> usuario = $bean;
		$m2m -> departamento = $another_bean;
		R::store($m2m);
	}

					
	// "one to many" attribute (O2M)

	foreach ($bean->alias('realiza')->ownEncuestaList as $realiza_bean ) {
		$key = array_search( $realiza_bean->realiza->id, $realiza );
		
		if ($key !== false) { // O2M we keep only the keys to add
			unset($realiza[$key]);
		}
		else { // O2M Element to be deleted
			R::store($bean);
			$realiza_bean -> realiza = null;
			R::store($realiza_bean);
		}
	}

	// O2M Elements to be added
	foreach ($realiza as $idf) {
		$o2m = R::load('encuesta', $idf);
		$o2m -> realiza = $bean;
		R::store($o2m);
	}

				
	// "one to many" attribute (O2M)

	foreach ($bean->alias('desarrolla')->ownImparticionList as $desarrolla_bean ) {
		$key = array_search( $desarrolla_bean->desarrolla->id, $desarrolla );
		
		if ($key !== false) { // O2M we keep only the keys to add
			unset($desarrolla[$key]);
		}
		else { // O2M Element to be deleted
			R::store($bean);
			$desarrolla_bean -> desarrolla = null;
			R::store($desarrolla_bean);
		}
	}

	// O2M Elements to be added
	foreach ($desarrolla as $idf) {
		$o2m = R::load('imparticion', $idf);
		$o2m -> desarrolla = $bean;
		R::store($o2m);
	}

	// "many to one" attribute
	if ( $grreferencia != null ) {
		$bean -> grreferencia = ( $grreferencia != 0 ? R::load('grupo',$grreferencia) : null );
		R::store($bean);
	}


	R::store($bean);
}


	/**
	* get_all MODEL action autogenerated by CASE IGNITER
	*/
	public function get_all() {
		return R::findAll('usuario');
	}

	/**
	* get_filtered MODEL action autogenerated by CASE IGNITER
	*/
	public function get_filtered($filter) {

		$where_clause[] = 'nombre LIKE ?';
		$where_clause[] = 'rol LIKE ?';

		$where_clause[] = 'nombre LIKE ?';
		$where_clause[] = 'rol LIKE ?';
		$where_clause[] = 'nombre LIKE ?';
		$where_clause[] = 'rol LIKE ?';
		$where_clause[] = 'nombre LIKE ?';
		
		/*
		$where_clause[] = '(SELECT anyoini FROM cursoacademico WHERE cursoacademico.id = (SELECT id FROM trabaja WHERE cursoacademico.id = trabaja.id )) LIKE ?'; //trabaja
		$where_clause[] = '(SELECT nombre FROM departamento WHERE departamento.id = (SELECT id FROM pertenece WHERE departamento.id = pertenece.id )) LIKE ?'; // pertenece
		
		
		$where_clause[] = '(SELECT fecha FROM encuesta WHERE encuesta.id = usuario.id) LIKE ?';
		$where_clause[] = '(SELECT nombre FROM imparticion WHERE imparticion.id = usuario.id) LIKE ?';
		$where_clause[] = '(SELECT nombre FROM grupo WHERE grupo.id = usuario.id) LIKE ?';
		*/
		
		$f = "%$filter%";
		
		return R::findAll('usuario', implode(' OR ', $where_clause) , [ $f,$f,$f,$f,$f,$f,$f ] );
		
	}

	/**
	* delete MODEL action autogenerated by CASEIGNITER
	*/
	public function delete( $id ) {
		$bean = R::load('usuario', $id );
		R::trash( $bean );
	}
	
	/**
	* get_by_id MODEL action autogenerated by CASEIGNITER
	*/
	public function get_by_id( $id ) {
		return R::load('usuario', $id ) ;
	}
	
}
?>