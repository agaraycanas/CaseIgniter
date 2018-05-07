<?php
function frame($controller, $path_to_view, $data = []) {
	if (session_status () == PHP_SESSION_NONE) {
		session_start ();
	}
	if (isset ( $_SESSION ['user'] ) && isset ( $_SESSION ['rol'] )) {
		$data ['header'] ['user'] = $_SESSION ['user'];
		$data ['header'] ['rol'] = $_SESSION ['rol'];
	}
	$data ['header'] ['login_bean'] = 'gatito';
	$controller->load->view ( 'templates/head', $data );
	$controller->load->view ( 'templates/header', $data );
	$controller->load->view ( 'templates/nav', $data );
	$controller->load->view ( $path_to_view, $data );
	$controller->load->view ( 'templates/footer', $data );
	$controller->load->view ( 'templates/end' );
}
?>