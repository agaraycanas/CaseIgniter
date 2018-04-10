<?php
/**
* Model code autogenerated by CASE IGNITER
*/
class aficion_model extends CI_Model {
public function create( $nombre ) {

	$bean = R::dispense( 'aficion' );

	// Regular attribute
	$bean -> nombre = $nombre;

	R::store($bean);
}

public function update( $id, $nombre ) {

	$bean = R::load( 'aficion', $id );

	// Regular attribute
	$bean -> nombre = $nombre;

	R::store($bean);
}

	public function get_all() {
		return R::findAll('aficion');
	}
	public function get_filtered($filter) {
		return [];
	}

	/**
	* model delete action autogenerated by CASEIGNITER
	*/
	public function delete( $id ) {
		$bean = R::load('aficion', $id );
		R::trash( $bean );
	}
	
	/**
	* model get_by_id action autogenerated by CASEIGNITER
	*/
	public function get_by_id( $id ) {
		return R::load('aficion', $id );
	}
	
}
?>