<?php
/**
* Controller code for aficion autogenerated by CASE IGNITER
*/
class aficion extends CI_Controller {

	/**
	* Controller action CREATE for controller aficion
	* autogenerated by CASE IGNITER
	*/
	public function create() {

		$data = [];

		enmarcar($this, 'aficion/create', $data);
	}

	
	
	/**
	* Controller action CREATE POST for controller aficion
	* autogenerated by CASE IGNITER
	*/
	public function create_post() {
		
		$this->load->model('aficion_model');

		$nombre = ( isset( $_POST['nombre']) ? $_POST['nombre'] : null );

		try {
			$this->aficion_model->create( $nombre );
			$data['status'] = 'ok';
			$data['message'] = "Aficion $nombre creado/a correctamente";
			$this->load->view('aficion/create_message',$data);
		}
		catch (Exception $e) {
			$data['status'] = 'error';
			$data['message'] = "Error al crear el/la aficion $nombre";
			$this->load->view('aficion/create_message',$data);
		}	
	
	}
				
				
	public function list() {
		$this->load->model('aficion_model');
		$filter = isset($_POST['filter'])?$_POST['filter']:'';
		$data['body']['aficion'] = ($filter == '' ? $this->aficion_model->get_all() : $this->aficion_model->get_filtered( $filter ) );
		enmarcar($this, 'aficion/list', $data);
	}

	
	/**
	* Controller action DELETE for controller aficion
	* autogenerated by CASE IGNITER
	*/
	public function delete() {
		$this -> load -> model ('aficion_model');
		try {
			$id = $_POST['id'];
			$this -> aficion_model -> delete( $id );
			redirect(base_url().'aficion/list');
		}
		catch (Exception $e ) {
			enmarcar($this, 'aficion/deleteERROR');
		}
	}	
	
	
	/**
	* Controller action UPDATE for controller aficion
	* autogenerated by CASE IGNITER
	*/
	public function update() {
		$this -> load -> model ('aficion_model');
		$id = $_POST['id'];
		$data['body']['aficion'] = $this -> aficion_model -> get_by_id($id);
		
		enmarcar($this, 'aficion/update', $data);
	}	
	
	/**
	* Controller action UPDATE POST for controller aficion
	* autogenerated by CASE IGNITER
	*/
	public function update_post() {
	
		$this->load->model('aficion_model');
			
		$id = ( isset( $_POST['id']) ? $_POST['id'] : null );
		$nombre = ( isset( $_POST['nombre']) ? $_POST['nombre'] : null );

		try {
			$this->aficion_model->update( $id, $nombre );


			redirect( base_url() . 'aficion/list' );
		}
		catch (Exception $e) {
			$data['status'] = 'error';
			$data['message'] = "Error al crear el/la aficion $nombre";
			$this->load->view('aficion/create_message',$data);
		}	
	
	}
			
			}
?>