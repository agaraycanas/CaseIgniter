<?php
/**
* Model code autogenerated by CASE IGNITER
*/
class grupo_model extends CI_Model {


	/**
	* create MODEL action autogenerated by CASE IGNITER
	*/
	public function create( $nombre, $grreferencia, $impartida, $pertenece ) {

	$bean = R::dispense( 'grupo' );


	// Regular attribute
	$bean -> setMeta("buildcommand.unique" , array(array('nombre')) );
	$bean -> nombre = $nombre;
					
	// "one to many" attribute
	foreach ($grreferencia as $id) {
		$o2m = R::load('usuario', $id);
		$bean -> alias('grreferencia') ->ownUsuarioList[] = $o2m;
	}
				
									
	// "one to many" attribute
	foreach ($impartida as $id) {
		$o2m = R::load('imparticion', $id);
		$bean -> alias('impartida') ->ownImparticionList[] = $o2m;
	}
				
				
	// "many to one" attribute
	if ( $pertenece != null && $pertenece != 0) {
		$bean -> pertenece = R::load('curso',$pertenece);
	}
				
				
	R::store($bean);

	return $bean->id;
}


	/**
	* update MODEL action autogenerated by CASE IGNITER
	*/
	public function update( $id, $nombre, $grreferencia, $impartida, $pertenece ) {

	$bean = R::load( 'grupo', $id );


	// Regular attribute
	$bean -> nombre = $nombre;
				
	// "one to many" attribute (O2M)

	foreach ($bean->alias('grreferencia')->ownUsuarioList as $grreferencia_bean ) {
		$key = array_search( $grreferencia_bean->grreferencia->id, $grreferencia );
		
		if ($key !== false) { // O2M we keep only the keys to add
			unset($grreferencia[$key]);
		}
		else { // O2M Element to be deleted
			R::store($bean);
			$grreferencia_bean -> grreferencia = null;
			R::store($grreferencia_bean);
		}
	}

	// O2M Elements to be added
	foreach ($grreferencia as $idf) {
		$o2m = R::load('usuario', $idf);
		$o2m -> grreferencia = $bean;
		R::store($o2m);
	}

				
	// "one to many" attribute (O2M)

	foreach ($bean->alias('impartida')->ownImparticionList as $impartida_bean ) {
		$key = array_search( $impartida_bean->impartida->id, $impartida );
		
		if ($key !== false) { // O2M we keep only the keys to add
			unset($impartida[$key]);
		}
		else { // O2M Element to be deleted
			R::store($bean);
			$impartida_bean -> impartida = null;
			R::store($impartida_bean);
		}
	}

	// O2M Elements to be added
	foreach ($impartida as $idf) {
		$o2m = R::load('imparticion', $idf);
		$o2m -> impartida = $bean;
		R::store($o2m);
	}

	// "many to one" attribute
	if ( $pertenece != null ) {
		$bean -> pertenece = ( $pertenece != 0 ? R::load('curso',$pertenece) : null );
		R::store($bean);
	}


	R::store($bean);
}


	/**
	* get_all MODEL action autogenerated by CASE IGNITER
	*/
	public function get_all() {
		return R::findAll('grupo');
	}

	/**
	* get_filtered MODEL action autogenerated by CASE IGNITER
	*/
	public function get_filtered($filter) {

		$where_clause[] = 'nombre LIKE ?';
		$where_clause[] = '(SELECT nombre FROM usuario WHERE usuario.id = grupo.id) LIKE ?';
		$where_clause[] = '(SELECT nombre FROM imparticion WHERE imparticion.id = grupo.id) LIKE ?';
		$where_clause[] = '(SELECT nivel FROM curso WHERE curso.id = grupo.id) LIKE ?';
		$f = "%$filter%";
		
		return R::findAll('ies', implode(' OR ', $where_clause) , [ $f,$f,$f,$f ] );
		
	}

	/**
	* delete MODEL action autogenerated by CASEIGNITER
	*/
	public function delete( $id ) {
		$bean = R::load('grupo', $id );
		R::trash( $bean );
	}
	
	/**
	* get_by_id MODEL action autogenerated by CASEIGNITER
	*/
	public function get_by_id( $id ) {
		return R::load('grupo', $id ) ;
	}
	
}
?>