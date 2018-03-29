<?php
/**
* Model code autogenerated by CASE IGNITER
*/
class persona_model extends CI_Model {
public function create( $nombre, $fechanacimiento, $peso, $amo, $paisnacimiento, $aficiones, $expertoen ) {
	$bean = R::dispense( 'persona' );

	// Regular attribute
	$bean -> nombre = $nombre;
	// Regular attribute
	$bean -> fechanacimiento = $fechanacimiento;
	// Regular attribute
	$bean -> peso = $peso;

	// "one to one" attribute
	if ( $amo != null ) {
		$o2o = R::load('mascota',$amo);
		$bean -> amo = $o2o;
		R::store($bean);
		$o2o -> amo = $bean;
		R::store($o2o);
	}

	// "many to one" attribute
	if ( $paisnacimiento != null ) {
		$bean -> paisnacimiento = R::load('pais',$paisnacimiento);
	}

				
	// "many to many" attribute
	foreach ($aficiones as $id) {
		$another_bean = R::load('aficion', $id);
		$m2m = R::dispense('aficiones');
		R::store($bean);
		$m2m -> persona = $bean;
		$m2m -> aficion = $another_bean;
		R::store($m2m);
	}

					
	// "one to many" attribute
	foreach ($expertoen as $id) { 
		$o2m = R::load('aficion', $id);
		$bean -> alias('expertoen') ->ownAficionList[] = $o2m;
	}


	R::store($bean);
}

	public function get_all() {
		return R::findAll('persona');
	}
	public function get_filtered($filter) {
		return [];
	}

	/**
	* model delete action autogenerated by CASEIGNITER
	*/
	public function delete( $id ) {
		$bean = R::load('persona', $id );
		R::trash( $bean );
	}
	
	/**
	* model get_by_id action autogenerated by CASEIGNITER
	*/
	public function get_by_id( $id ) {
		return R::load('persona', $id );
	}
	
}
?>