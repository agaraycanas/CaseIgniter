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

		$data['body']['filter'] = isset($_REQUEST['filter']) ? $_REQUEST['filter'] : '' ;

		// $data = [];
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
			$id = $this->mascota_model->create( $nombre );
			$this->list_id($id);
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
		$filter = isset($_REQUEST['filter'])?$_REQUEST['filter']:'';
		$data['body']['mascota'] = ($filter == '' ? $this->mascota_model->get_all() : $this->mascota_model->get_filtered( $filter ) );
		$data['body']['filter'] = $filter ;
		enmarcar($this, 'mascota/list', $data);
	}

	/**
	* Controller private function LIST_ID for controller mascota
	* autogenerated by CASE IGNITER
	*/
	private function list_id($id) {
		$this->load->model('mascota_model');
		$data['body']['mascota'] = [ $this->mascota_model->get_by_id($id) ];
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
			$filter = isset ($_REQUEST['filter'] ) ? $_REQUEST['filter'] : '';

			$this -> mascota_model -> delete( $id );
			redirect(base_url().'mascota/list?filter='.$filter);
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

		$data['body']['filter'] = isset($_REQUEST['filter']) ? $_REQUEST['filter'] : '' ;


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

			$filter = isset($_POST['filter']) ? $_POST['filter'] : '' ;
			redirect( base_url() . 'mascota/list?filter='.$filter );
		}
		catch (Exception $e) {
			$data['status'] = 'error';
			$data['message'] = "Error al crear el/la mascota $nombre";
			$this->load->view('mascota/create_message',$data);
		}	
	
	}
			
			}
?>