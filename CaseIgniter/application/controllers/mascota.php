<?php
/**
* Controller code for mascota autogenerated by CASE IGNITER
*/
class mascota extends CI_Controller {

	/**
	* Controller action CREATE for controller mascota
	* autogenerated by CASE IGNITER
	*/
	public function create() {

		$data = [];

		enmarcar($this, 'mascota/create', $data);
	}

	
	
	/**
	* Controller action CREATE POST for controller mascota
	* autogenerated by CASE IGNITER
	*/
	public function create_post() {
		
		$this->load->model('mascota_model');

		$nombre = ( isset( $_POST['nombre']) ? $_POST['nombre'] : null );

		try {
			$this->mascota_model->create( $nombre );
			$data['status'] = 'ok';
			$data['message'] = "Mascota $nombre creado/a correctamente";
			$this->load->view('mascota/create_message',$data);
		}
		catch (Exception $e) {
			$data['status'] = 'error';
			$data['message'] = "Error al crear el/la mascota $nombre";
			$this->load->view('mascota/create_message',$data);
		}	
	
	}
				
				
	
	/**
	* Controller action LIST for controller mascota
	* autogenerated by CASE IGNITER
	*/
	public function list() {
		$this->load->model('mascota_model');
		$filter = isset($_POST['filter'])?$_POST['filter']:'';
		$data['body']['mascota'] = ($filter == '' ? $this->mascota_model->get_all() : $this->mascota_model->get_filtered( $filter ) );
		enmarcar($this, 'mascota/list', $data);
	}

	
	/**
	* Controller action DELETE for controller mascota
	* autogenerated by CASE IGNITER
	*/
	public function delete() {
		$this -> load -> model ('mascota_model');
		try {
			$id = $_POST['id'];
			$this -> mascota_model -> delete( $id );
			redirect(base_url().'mascota/list');
		}
		catch (Exception $e ) {
			enmarcar($this, 'mascota/deleteERROR');
		}
	}	
	
	
	/**
	* Controller action UPDATE for controller mascota
	* autogenerated by CASE IGNITER
	*/
	public function update() {

		$this -> load -> model ('mascota_model');
		$id = $_POST['id'];
		$data['body']['mascota'] = $this -> mascota_model -> get_by_id($id);
		
		enmarcar($this, 'mascota/update', $data);
	}	
	
	/**
	* Controller action UPDATE POST for controller mascota
	* autogenerated by CASE IGNITER
	*/
	public function update_post() {
	
		$this->load->model('mascota_model');
			
		$id = ( isset( $_POST['id']) ? $_POST['id'] : null );
		$nombre = ( isset( $_POST['nombre']) ? $_POST['nombre'] : null );

		try {
			$this->mascota_model->update( $id, $nombre );


			redirect( base_url() . 'mascota/list' );
		}
		catch (Exception $e) {
			$data['status'] = 'error';
			$data['message'] = "Error al crear el/la mascota $nombre";
			$this->load->view('mascota/create_message',$data);
		}	
	
	}
			
			}
?>