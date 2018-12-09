<?php
class _home extends CI_Controller {

	public function index() {
		if (session_status () == PHP_SESSION_NONE) {session_start ();}
				$_SESSION['login_bean'] = 'persona';
		frame($this, '_home/index');
	}

    public function info() {
        if ( session_status () == PHP_SESSION_NONE) { session_start (); }
        if ( !isset($_SESSION['info']['status']) || !isset($_SESSION['info']['message']) ) {
            $_SESSION['info']['status'] = 'info';
            $_SESSION['info']['message'] = 'Pulsa el botón para volver a home';
            $_SESSION['info']['link'] = null;
        }

        $data['status'] = $_SESSION['info']['status'];
        $data['message'] = $_SESSION['info']['message'];
        $data['link'] = isset($_SESSION['info']['link']) ? $_SESSION['info']['link'] : null;

        $_SESSION['info']['status'] = 'info';
        $_SESSION['info']['message'] = 'Pulsa el botón para volver a home';
        $_SESSION['info']['link'] = null;

        frame($this,'_home/_info',$data);
    }
}
?>