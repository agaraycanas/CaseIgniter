<?php
$popup = false;
$severity_style = isset($_GET['severity']) ? '' : null;
$severity = isset($_GET['severity']) ? $_GET['severity'] : null;
$message = isset($_GET['message']) ? $_GET['message'] : null;
if ($severity != null && $message != null) {
    $popup = true;
    switch ($severity) {
        case 'ERROR':$severity_style='bg-danger';break;
        case 'WARNING':$severity_style='bg-warning';break;
        case 'OK':$severity_style='bg-success';break;
    }
}
?>

<?php if ($popup): ?>
<script type="text/javascript">
    $(window).on('load',function(){
        $('#id-modal').modal('show');
    });
</script>

<div class="modal fade" id="id-modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header text-center" id="id-modal-header"><?= $severity ?></div>
			<h4 class="modal-title text-center <?= $severity_style ?>"
				id="id-modal-message"><?= $message ?></h4>
			<div class="modal-footer">
				<button type="button" class="btn " data-dismiss="modal"
					id="id-modal-button" autofocus="autofocus">Aceptar</button>
			</div>
		</div>
	</div>
</div>

<?php endif; ?>


<div class="container jumbotron">
	<h1>Bienvenido DEFAULT</h1>
	<h3>Esta es tu home page que deberás personalizar</h3>

	<p>
		Para personalizar esta home page, edita el fichero
		<code>views/_home/default.php</code>

</div>

