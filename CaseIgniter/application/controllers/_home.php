<?php
class _home extends CI_Controller {
	public function index() {
		if (session_status () == PHP_SESSION_NONE) {session_start ();}
				$_SESSION['login_bean'] = 'perrito';
		enmarcar($this, '_home/index');
	}
}
?>