<?php
class _casei extends CI_Controller {
	public function index() {
		$this->home ();
	}
	public function home() {
		$model_file = 'assets/doc/model.txt';
		$menu_file = 'assets/doc/menus.txt';

		$data ['menuData'] = file_exists ( $menu_file ) ? file_get_contents ( $menu_file ) : null;
		$data ['modelData'] = file_exists ( $model_file ) ? file_get_contents ( $model_file ) : null;
		enmarcar ( $this, '_casei/home', $data );
	}
	public function homePOST() {
		$model_file = 'assets/doc/model.txt';
		$menu_file = 'assets/doc/menus.txt';
		
		$app_title = empty($_POST['appTitle'])?'MyWeb':$_POST['appTitle']; 
		$menu_data = $_POST ['menuData'];
		$model_data = $_POST ['modelData'];
		$classes = process_domain_model($model_data);
		generate_application_files($classes);
		
		change_title($app_title);
		file_put_contents ( APPPATH . 'views/templates/nav.php', generate_menus ( $menu_data, $app_title, $classes ) );
		file_put_contents ( $menu_file, $menu_data );
		file_put_contents ( $model_file, $model_data );

		$data['classes'] = $classes;
		$data['menus'] = $menu_data;
		$data['model'] = $model_data;
		enmarcar($this, '_casei/homePOST',$data);
		
	}
}