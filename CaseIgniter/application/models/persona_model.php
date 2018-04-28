<?php
/**
* Model code autogenerated by CASE IGNITER
*/
class persona_model extends CI_Model {


	/**
	* create MODEL action autogenerated by CASE IGNITER
	*/
	public function create( $nombre, $fechanacimiento, $peso, $amo, $paisnacimiento, $expertoen, $inutilen, $gusta, $odia ) {

	$bean = R::dispense( 'persona' );


	// Regular attribute
	$bean -> nombre = $nombre;

	// Regular attribute
	$bean -> fechanacimiento = $fechanacimiento;

	// Regular attribute
	$bean -> peso = $peso;
				
	// "one to one" attribute
	if ( $amo != null && $amo != 0 ) {
		$o2o = R::load('mascota',$amo);
		$bean -> amo = $o2o;
		R::store($bean);
		$o2o -> amo = $bean;
		R::store($o2o);
	}
				
				
	// "many to one" attribute
	if ( $paisnacimiento != null && $paisnacimiento != 0) {
		$bean -> paisnacimiento = R::load('pais',$paisnacimiento);
	}
				
									
	// "one to many" attribute
	foreach ($expertoen as $id) {
		$o2m = R::load('aficion', $id);
		$bean -> alias('expertoen') ->ownAficionList[] = $o2m;
		R::store($bean);
	}
				
									
	// "one to many" attribute
	foreach ($inutilen as $id) {
		$o2m = R::load('aficion', $id);
		$bean -> alias('inutilen') ->ownAficionList[] = $o2m;
		R::store($bean);
	}
				
									
	// "many to many" attribute
	foreach ($gusta as $id) {
		$another_bean = R::load('aficion', $id);
		$m2m = R::dispense('gusta');
		R::store($bean);
		$m2m -> persona = $bean;
		$m2m -> aficion = $another_bean;
		R::store($m2m);
	}
				
									
	// "many to many" attribute
	foreach ($odia as $id) {
		$another_bean = R::load('aficion', $id);
		$m2m = R::dispense('odia');
		R::store($bean);
		$m2m -> persona = $bean;
		$m2m -> aficion = $another_bean;
		R::store($m2m);
	}
				
				
	R::store($bean);

	return $bean->id;
}


	/**
	* update MODEL action autogenerated by CASE IGNITER
	*/
	public function update( $id, $nombre, $fechanacimiento, $peso, $amo, $paisnacimiento, $expertoen, $inutilen, $gusta, $odia ) {

	$bean = R::load( 'persona', $id );


	// Regular attribute
	$bean -> nombre = $nombre;

	// Regular attribute
	$bean -> fechanacimiento = $fechanacimiento;

	// Regular attribute
	$bean -> peso = $peso;

	// "one to one" attribute
	if ( $amo != null ) {
		$o2o = ( $amo != 0 ? R::load('mascota',$amo) : null );

		if ($bean->fetchAs('mascota')->amo != null ) {
			$o2o_prev = R::load('mascota',$bean->fetchAs('mascota')->amo->id);
			$o2o_prev -> amo_id = null;
			R::store($o2o_prev);
		}

		$bean -> amo = $o2o;

		R::store($bean);

		if ( $o2o != null ) {
			$o2o -> amo = $bean;
			R::store($o2o);
		}
	}

	// "many to one" attribute
	if ( $paisnacimiento != null ) {
		$bean -> paisnacimiento = ( $paisnacimiento != 0 ? R::load('pais',$paisnacimiento) : null );
		R::store($bean);
	}

				
	// "one to many" attribute (O2M)

	foreach ($bean->alias('expertoen')->ownAficionList as $expertoen_bean ) {
		$key = array_search( $expertoen_bean->expertoen->id, $expertoen );
		
		if ($key !== false) { // O2M we keep only the keys to add
			unset($expertoen[$key]);
		}
		else { // O2M Element to be deleted
			R::store($bean);
			$expertoen_bean -> expertoen = null;
			R::store($expertoen_bean);
		}
	}

	// O2M Elements to be added
	foreach ($expertoen as $idf) {
		$o2m = R::load('aficion', $idf);
		$o2m -> expertoen = $bean;
		R::store($o2m);
	}

				
	// "one to many" attribute (O2M)

	foreach ($bean->alias('inutilen')->ownAficionList as $inutilen_bean ) {
		$key = array_search( $inutilen_bean->inutilen->id, $inutilen );
		
		if ($key !== false) { // O2M we keep only the keys to add
			unset($inutilen[$key]);
		}
		else { // O2M Element to be deleted
			R::store($bean);
			$inutilen_bean -> inutilen = null;
			R::store($inutilen_bean);
		}
	}

	// O2M Elements to be added
	foreach ($inutilen as $idf) {
		$o2m = R::load('aficion', $idf);
		$o2m -> inutilen = $bean;
		R::store($o2m);
	}

				
	// "many to many" attribute (M2M)
	
	foreach ($bean->ownGustaList as $gusta_bean ) {
		$key = array_search( $gusta_bean->aficion->id, $gusta );
		
		if ($key !== false) { // M2M we keep only the keys to add
			unset($gusta[$key]);
		}
		else { // M2M Element to be deleted
			R::store($bean);
			R::trash($gusta_bean);
		}
	}

	// M2M Elements to be added
	foreach ($gusta as $idf) {
		$another_bean = R::load('aficion', $idf);
		$m2m = R::dispense('gusta');
		$m2m -> persona = $bean;
		$m2m -> aficion = $another_bean;
		R::store($m2m);
	}

					
	// "many to many" attribute (M2M)
	
	foreach ($bean->ownOdiaList as $odia_bean ) {
		$key = array_search( $odia_bean->aficion->id, $odia );
		
		if ($key !== false) { // M2M we keep only the keys to add
			unset($odia[$key]);
		}
		else { // M2M Element to be deleted
			R::store($bean);
			R::trash($odia_bean);
		}
	}

	// M2M Elements to be added
	foreach ($odia as $idf) {
		$another_bean = R::load('aficion', $idf);
		$m2m = R::dispense('odia');
		$m2m -> persona = $bean;
		$m2m -> aficion = $another_bean;
		R::store($m2m);
	}

	
	R::store($bean);
}


	/**
	* get_all MODEL action autogenerated by CASE IGNITER
	*/
	public function get_all() {
		return R::findAll('persona');
	}

	/**
	* get_filtered MODEL action autogenerated by CASE IGNITER
	*/
	public function get_filtered($filter) {

		$where_clause = [ ];

		$where_clause[] = 'nombre LIKE ?';
		$where_clause[] = 'fechanacimiento LIKE ?';
		$where_clause[] = 'peso LIKE ?';
		$where_clause[] = '(SELECT nombre FROM mascota WHERE mascota.id = persona.amo_id) LIKE ?';
		$where_clause[] = '(SELECT nombre FROM pais WHERE pais.id = persona.paisnacimiento_id) LIKE ?';
		$where_clause[] = '(SELECT count(*) FROM aficion WHERE nombre LIKE ? AND expertoen_id = persona.id) > 0';
		$where_clause[] = '(SELECT count(*) FROM aficion WHERE nombre LIKE ? AND inutilen_id = persona.id) > 0';
		$where_clause[] = '(SELECT count(*) FROM aficion WHERE nombre LIKE ? AND aficion.id IN (SELECT aficion_id FROM gusta WHERE persona_id = persona.id)) > 0';
		$where_clause[] = '(SELECT count(*) FROM aficion WHERE nombre LIKE ? AND aficion.id IN (SELECT aficion_id FROM odia WHERE persona_id = persona.id)) > 0';
		$f = "%$filter%";
		
		return R::findAll('persona', implode(' OR ', $where_clause) , [ $f,$f,$f,$f,$f,$f,$f,$f,$f ] );
		
	}

	/**
	* delete MODEL action autogenerated by CASEIGNITER
	*/
	public function delete( $id ) {
		$bean = R::load('persona', $id );
		R::trash( $bean );
	}
	
	/**
	* get_by_id MODEL action autogenerated by CASEIGNITER
	*/
	public function get_by_id( $id ) {
		return R::load('persona', $id ) ;
	}
	
}
?>