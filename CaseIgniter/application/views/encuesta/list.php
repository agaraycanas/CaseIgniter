
<script>
	$(document).ready(function() 
	    { 
	        $("#myTable").tablesorter(); 
	    } 
	);
</script>

<?php error_reporting(0); ?>
<div class="container">
<div class="row">
	<div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
		<form class="form-inline"  action="<?=base_url()?>encuesta/create"><input type="submit" class="btn btn-primary" value="Crear encuesta" autofocus=""></form>
	</div>

	<div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
		<form class="form-inline" action="<?=base_url()?>encuesta/list" method="post">
			<label for="id-filter">Filtrar</label>
			<input id="id-filter" type="text" name="filter" class="form-control" >
		</form>
	</div>
</div>

<h1>LISTA de  encuesta</h1>

<table id="myTable" class="table table-striped tablesorter">
	<thead>
	<tr>
		<th>fecha</th>		<th>realiza - nombre(usuario)</th>
		<th>realizada - nombre(imparticion)</th>
		<th>acercade - nombre(plantillaencuesta)</th>
		<th>responderc - numero(respuestacerrada)</th>
		<th>respondera - texto(respuestaabierta)</th>
		<th>Acciones</th>
	</tr>
	</thead>

	<tbody>
	<?php foreach ($body['encuesta'] as $encuesta): ?>
		<tr>
			<td class="alert alert-success"><?= $encuesta -> fecha ?></td>
		<td><?= $encuesta ->  fetchAs('usuario') -> realiza -> nombre ?></td>
		<td><?= $encuesta ->  fetchAs('imparticion') -> realizada -> nombre ?></td>
		<td><?= $encuesta ->  fetchAs('plantillaencuesta') -> acercade -> nombre ?></td>
		<td><?= $encuesta ->  fetchAs('respuestacerrada') -> responderc -> numero ?></td>

				<td>
				<?php foreach ($encuesta -> alias ('respondera') -> ownRespuestaabiertaList as $data): ?>
					<span><?= $data -> texto ?> </span>
				<?php endforeach; ?>
				</td>

			<td class="form-inline col-md-1">
				<form action="<?= base_url() ?>encuesta/update" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $encuesta -> id ?>">
					<button onclick="submit()">
						<img src="<?= base_url() ?>assets/img/icons/png/pencil-2x.png" height="15" width="15" alt="editar">
					</button>
				</form>

				<form action="<?= base_url() ?>encuesta/delete" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $encuesta -> id ?>">
					<button onclick="submit()">
						<img src="<?= base_url() ?>assets/img/icons/png/trash-2x.png" height="15" width="15" alt="borrar">
					</button>
				</form>
			</td>

		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
</div>
<?php error_reporting(E_ALL); ?>