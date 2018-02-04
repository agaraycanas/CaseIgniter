<?php $title="MyWeb"; ?>
<!DOCTYPE html >
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	 
	<!-- PARA CUANDO FUNCIONEN LOS GLYPHICONS OFFLINE 
	<link href="<?=base_url()?>assets/css/bootstrap.min.css" rel="stylesheet">
	<script src="<?=base_url()?>assets/js/jquery.min.js"></script>
	<script src="<?=base_url()?>assets/js/bootstrap.min.js"></script>
	-->
	
	<title><?=$title?></title>
</head>
<body onload="<?= (isset($head['onload']) ? $head['onload'] : '') ?>">
