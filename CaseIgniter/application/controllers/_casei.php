<?php
class _casei extends CI_Controller {
	private $model_file = 'assets/doc/model.txt';
	private $menu_file = 'assets/doc/menus.txt';
	private $model_img = 'assets/doc/model_img.txt';
	private $nav_file = APPPATH . 'views/_templates/nav.php';
	
	// -------------------------------------------
	public function index() {
		$this->home ();
	}
	
	// -------------------------------------------
	public function home() {
		$data ['menuData'] = file_exists ( $this->menu_file ) ? file_get_contents ( $this->menu_file ) : null;
		$data ['modelData'] = file_exists ( $this->model_file ) ? file_get_contents ( $this->model_file ) : null;
		$data ['modelImg'] = file_exists ( $this->model_img ) ? file_get_contents ( $this->model_img ) : null;
		frame ( $this, '_casei/home', $data );
	}
	
	// -------------------------------------------
	public function homePOST() {
		if (session_status () == PHP_SESSION_NONE) {
			session_start ();
		}
		session_destroy ();
		
		$debug = isset ( $_POST ['debug'] );
		$app_title = empty ( $_POST ['appTitle'] ) ? 'MyWeb' : $_POST ['appTitle'];
		$menu_data = $_POST ['menuData'];
		$model_data = $_POST ['modelData'];
		
		$classes = $this->csi_process_domain_model ( $debug, $model_data );
		$rol_class = $this->csi_set_login_bean_class ( $debug, $classes );
		$this->csi_generate_application_files ( $debug, $classes );

		$login_bean = get_login_bean ( $classes );
		$this->csi_print_classes_if_debugging ( $debug, $classes );
		$this->csi_generate_frame_helper ( $debug, $login_bean );
		$this->csi_generate_home_controller ( $debug, $login_bean );
		$this->csi_change_title ( $debug, $app_title );
		$this->csi_db_create_set_uniques_and_freeze ( $debug, $classes );
		$this->csi_generate_admin ( $debug, $classes, $rol_class );
		
		$data ['classes'] = $classes;
		$data ['menus'] = $menu_data;
		$data ['model'] = $model_data;
		$data ['modelImg'] = generate_yuml ( $classes );
		
		$this->csi_generate_menus ( $debug, $app_title, $menu_data, $classes );
		$this->csi_update_menu_txt_file ( $debug, $menu_data );
		$this->csi_csi_update_model_txt_file ( $debug, $model_data );
		$this->csi_csi_update_model_img_txt_file ( $debug, $data );
		
		frame ( $this, '_casei/homePOST', $data );
	}
	
	// ===============================================================================
	
	/**
	 *
	 * @param
	 *        	debug
	 * @param
	 *        	data
	 */
	private function csi_csi_update_model_img_txt_file($debug, $data) {
		if ($debug) {
			echo '<h3>Updating <code>assets/doc/model_img.txt</code></h3>';
		}
		file_put_contents ( $this->model_img, $data ['modelImg'] );
	}
	
	/**
	 *
	 * @param
	 *        	debug
	 * @param
	 *        	model_data
	 */
	private function csi_csi_update_model_txt_file($debug, $model_data) {
		if ($debug) {
			echo '<h3>Updating <code>assets/doc/model.txt</code></h3>';
		}
		file_put_contents ( $this->model_file, $model_data );
	}
	
	/**
	 *
	 * @param
	 *        	debug
	 * @param
	 *        	menu_data
	 */
	private function csi_update_menu_txt_file($debug, $menu_data) {
		if ($debug) {
			echo '<h3>Updating <code>assets/doc/menus.txt</code></h3>';
		}
		file_put_contents ( $this->menu_file, $menu_data );
	}
	
	/**
	 *
	 * @param
	 *        	debug
	 * @param
	 *        	app_title
	 * @param
	 *        	menu_data
	 * @param
	 *        	classes
	 */
	private function csi_generate_menus($debug, $app_title, $menu_data, $classes) {
		if ($debug) {
			echo '<h3>Generating menus</h3>';
		}
		file_put_contents ( $this->nav_file, generate_menus ( $menu_data, $app_title, $classes ) );
	}
	
	/**
	 *
	 * @param
	 *        	debug
	 * @param
	 *        	classes
	 * @param
	 *        	rol_class
	 */
	private function csi_generate_admin($debug, $classes, $rol_class) {
		if ($rol_class != null) {
			if ($debug) {
				echo '<h3>Generating admin rol and account</h3>';
			}
			generate_admin ( $classes );
		}
	}
	
	/**
	 *
	 * @param
	 *        	debug
	 * @param
	 *        	classes
	 */
	private function csi_db_create_set_uniques_and_freeze($debug, $classes) {
		if ($debug) {
			echo '<h3>Creating Database. Setting UNIQUE keys and FREEZING RedBeanPHP</h3>';
		}
		db_create_set_uniques_and_freeze ( $classes, $this );
	}
	
	/**
	 *
	 * @param
	 *        	debug
	 * @param
	 *        	app_title
	 */
	private function csi_change_title($debug, $app_title) {
		if ($debug) {
			echo '<h3>Changing title</h3>';
		}
		change_title ( $app_title );
	}
	
	/**
	 *
	 * @param
	 *        	debug
	 * @param
	 *        	login_bean
	 */
	private function csi_generate_home_controller($debug, $login_bean) {
		if ($debug) {
			echo '<h3>Generating home controller</h3>';
		}
		generate_home_controller ( $login_bean );
	}
	
	/**
	 *
	 * @param
	 *        	debug
	 * @param
	 *        	login_bean
	 */
	private function csi_generate_frame_helper($debug, $login_bean) {
		if ($debug) {
			echo '<h3>Generating frame helper</h3>';
		}
		generate_frame_helper ( $login_bean != null ? $login_bean->name : null );
	}
	
	/**
	 *
	 * @param
	 *        	debug
	 * @param
	 *        	classes
	 */
	private function csi_print_classes_if_debugging($debug, $classes) {
		if ($debug) {
			echo '<pre>';
			print_r ( $classes );
			echo '</pre>';
		}
	}
	
	/**
	 *
	 * @param
	 *        	debug
	 * @param
	 *        	classes
	 */
	private function csi_generate_application_files($debug, $classes) {
		if ($debug) {
			echo '<h3>Generating application files (controllers, models and views)</h3>';
		}
		generate_application_files ( $classes );
	}
	
		
	/**
	 *
	 * @param
	 *        	debug
	 * @param
	 *        	classes
	 */
	private function csi_set_login_bean_class($debug, $classes) {
		if ($debug) {
			echo '<h3>Creating rol class and setting login bean class</h3>';
		}
		$rol_class = set_login_bean_class ( $classes );
		if ($rol_class != null) {
			$classes [] = $rol_class;
		}
		return $rol_class;
	}
	
	/**
	 *
	 * @param
	 *        	debug
	 * @param
	 *        	model_data
	 */
	private function csi_process_domain_model($debug, $model_data) {
		if ($debug) {
			echo '<h3>Processing domain model</h3>';
		}
		$classes = process_domain_model ( $model_data );
		return $classes;
	}
}