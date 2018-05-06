<?php
class _casei extends CI_Controller {
	private $model_file = 'assets/doc/model.txt';
	private $menu_file = 'assets/doc/menus.txt';
	private $nav_file = APPPATH . 'views/templates/nav.php';
	public function index() {
		$this->home ();
	}
	public function home() {
		$data ['menuData'] = file_exists ( $this->menu_file ) ? file_get_contents ( $this->menu_file ) : null;
		$data ['modelData'] = file_exists ( $this->model_file ) ? file_get_contents ( $this->model_file ) : null;
		enmarcar ( $this, '_casei/home', $data );
	}
	public function homePOST() {
		if (session_status () == PHP_SESSION_NONE) {session_start ();}
		session_destroy();
		$app_title = empty ( $_POST ['appTitle'] ) ? 'MyWeb' : $_POST ['appTitle'];
		$menu_data = $_POST ['menuData'];
		$model_data = $_POST ['modelData'];
		$classes = process_domain_model ( $model_data );
		
		$login_bean = set_login_bean_class ( $classes );
		if ($login_bean != null) {
			$classes [] = $login_bean;
		}
		
		generate_application_files ( $classes );
		
		generate_home_controller(get_login_bean($classes));	
		change_title ( $app_title );
		db_create_set_uniques_and_freeze ( $classes , $this);

		if ($login_bean != null) {
			generate_admin ( $classes );
		}
		
		file_put_contents ( $this->nav_file, generate_menus ( $menu_data, $app_title, $classes ) );
		file_put_contents ( $this->menu_file, $menu_data );
		file_put_contents ( $this->model_file, $model_data );
		
		$data ['classes'] = $classes;
		$data ['menus'] = $menu_data;
		$data ['model'] = $model_data;
		
		enmarcar ( $this, '_casei/homePOST', $data );
	}
}