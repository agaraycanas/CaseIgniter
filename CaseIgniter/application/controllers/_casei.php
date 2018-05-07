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
		frame ( $this, '_casei/home', $data );
	}
	public function homePOST() {
		if (session_status () == PHP_SESSION_NONE) {session_start ();}
		session_destroy();
		$app_title = empty ( $_POST ['appTitle'] ) ? 'MyWeb' : $_POST ['appTitle'];
		$menu_data = $_POST ['menuData'];
		$model_data = $_POST ['modelData'];
		$classes = process_domain_model ( $model_data );
		
		$rol_class = set_login_bean_class ( $classes );
		if ($rol_class != null) {
			$classes [] = $rol_class;
		}
		
		generate_application_files ( $classes );
		
		$login_bean = get_login_bean($classes);
		generate_frame_helper($login_bean!=null ? $login_bean->name : null);
		generate_home_controller($login_bean);	
		change_title ( $app_title );
		db_create_set_uniques_and_freeze ( $classes , $this);

		if ($rol_class != null) {
			generate_admin ( $classes );
		}
		
		file_put_contents ( $this->nav_file, generate_menus ( $menu_data, $app_title, $classes ) );
		file_put_contents ( $this->menu_file, $menu_data );
		file_put_contents ( $this->model_file, $model_data );
		
		$data ['classes'] = $classes;
		$data ['menus'] = $menu_data;
		$data ['model'] = $model_data;
		
		frame ( $this, '_casei/homePOST', $data );
	}
}