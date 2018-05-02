<?php
function enmarcar($controlador, $rutaVista, $datos = []) {
	if (session_status () == PHP_SESSION_NONE) {
		session_start ();
	}
	if (isset ( $_SESSION ['user'] ) && isset ( $_SESSION ['rol'] )) {
		$datos ['header'] ['user'] = $_SESSION ['user'];
		$datos ['header'] ['rol'] = $_SESSION ['rol'];
	}
	if (isset ( $_SESSION ['login_bean'] )) {
		$datos ['header'] ['login_bean'] = $_SESSION ['login_bean'];
	}
	
	$controlador->load->view ( 'templates/head', $datos );
	$controlador->load->view ( 'templates/header', $datos );
	$controlador->load->view ( 'templates/nav', $datos );
	$controlador->load->view ( $rutaVista, $datos );
	$controlador->load->view ( 'templates/footer', $datos );
	$controlador->load->view ( 'templates/end' );
}
?>