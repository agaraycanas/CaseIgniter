<?php
function frame($controller, $path_to_view, $data = []) {
	if (session_status () == PHP_SESSION_NONE) {
		session_start ();
	}
	if (isset ( $_SESSION ['user'] ) && isset ( $_SESSION ['rol'] )) {
		$data ['header'] ['user'] = $_SESSION ['user'];
		$data ['header'] ['rol'] = $_SESSION ['rol'];
		$data ['nav'] ['rol'] = $_SESSION ['rol'];
	}
	$data ['header'] ['login_bean'] = 'usuario';
	$controller->load->view ( '_templates/head', $data );
	$controller->load->view ( '_templates/header', $data );
	$controller->load->view ( '_templates/nav', $data );
	$controller->load->view ( $path_to_view, $data );
	$controller->load->view ( '_templates/footer', $data );
	$controller->load->view ( '_templates/end' );
}
?>