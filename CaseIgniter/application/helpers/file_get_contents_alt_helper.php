<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
define ( 'PHP_COMPAT_FILE_GET_CONTENTS_MAX_REDIRECTS', 5 );
function php_compat_file_get_contents($filename, $incpath = false, $resource_context = null) {
	if (is_resource ( $resource_context ) && function_exists ( 'stream_context_get_options' )) {
		$opts = stream_context_get_options ( $resource_context );
	}
	$colon_pos = strpos ( $filename, '://' );
	$wrapper = $colon_pos === false ? 'file' : substr ( $filename, 0, $colon_pos );
	$opts = (empty ( $opts ) || empty ( $opts [$wrapper] )) ? array () : $opts [$wrapper];
	switch ($wrapper) {
		case 'http' :
			$max_redirects = (isset ( $opts [$wrapper] ['max_redirects'] ) ? $opts [$proto] ['max_redirects'] : PHP_COMPAT_FILE_GET_CONTENTS_MAX_REDIRECTS);
			for($i = 0; $i < $max_redirects; $i ++) {
				$contents = php_compat_http_get_contents_helper ( $filename, $opts );
				if (is_array ( $contents )) {
					$filename = rtrim ( $contents [1] );
					$contents = '';
					continue;
				}
				return $contents;
			}
			user_error ( 'redirect limit exceeded', E_USER_WARNING );
			return;
		case 'ftp' :
		case 'https' :
		case 'ftps' :
		case 'socket' :
	}
	if (false === $fh = fopen ( $filename, 'rb', $incpath )) {
		user_error ( 'failed to open stream: No such file or directory', E_USER_WARNING );
		return false;
	}
	clearstatcache ();
	if ($fsize = @filesize ( $filename )) {
		$data = fread ( $fh, $fsize );
	} else {
		$data = '';
		while ( ! feof ( $fh ) ) {
			$data .= fread ( $fh, 8192 );
		}
	}
	fclose ( $fh );
	return $data;
}
function php_compat_http_get_contents_helper($filename, $opts) {
	$path = parse_url ( $filename );
	if (! isset ( $path ['host'] )) {
		return '';
	}
	$fp = fsockopen ( $path ['host'], 80, $errno, $errstr, 4 );
	if (! $fp) {
		return '';
	}
	if (! isset ( $path ['path'] )) {
		$path ['path'] = '/';
	}
	$headers = array (
			'Host' => $path ['host'],
			'Conection' => 'close' 
	);
	$opts_defaults = array (
			'method' => 'GET',
			'header' => null,
			'user_agent' => ini_get ( 'user_agent' ),
			'content' => null,
			'request_fulluri' => false 
	);
	foreach ( $opts_defaults as $key => $value ) {
		if (! isset ( $opts [$key] )) {
			$opts [$key] = $value;
		}
	}
	$opts ['path'] = $opts ['request_fulluri'] ? $filename : $path ['path'];
	$request = $opts ['method'] . ' ' . $opts ['path'] . " HTTP/1.0\r\n";
	if (isset ( $opts ['header'] )) {
		$optheaders = explode ( "\r\n", $opts ['header'] );
		for($i = count ( $optheaders ); $i --;) {
			$sep_pos = strpos ( $optheaders [$i], ': ' );
			$headers [substr ( $optheaders [$i], 0, $sep_pos )] = substr ( $optheaders [$i], $sep_pos + 2 );
		}
	}
	foreach ( $headers as $key => $value ) {
		$request .= "$key: $value\r\n";
	}
	$request .= "\r\n" . $opts ['content'];
	fputs ( $fp, $request );
	$response = '';
	while ( ! feof ( $fp ) ) {
		$response .= fgets ( $fp, 8192 );
	}
	fclose ( $fp );
	$content_pos = strpos ( $response, "\r\n\r\n" );
	if (preg_match ( '/^Location: (.*)$/mi', $response, $matches )) {
		return $matches;
	}
	return ($content_pos != - 1 ? substr ( $response, $content_pos + 4 ) : $response);
}
function php_compat_ftp_get_contents_helper($filename, $opts) {
}
if (! function_exists ( 'file_get_contents_alt' )) {
	function file_get_contents_alt($filename, $incpath = false, $resource_context = null) {
		return php_compat_file_get_contents ( $filename, $incpath, $resource_context );
	}
}
/* End of file file_get_contents_alt_helper.php */
/* Location: ./application/helpers/file_get_contents_alt_helper.php */