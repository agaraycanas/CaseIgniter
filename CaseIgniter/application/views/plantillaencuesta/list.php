
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
		<form class="form-inline"  action="<?=base_url()?>plantillaencuesta/create"><input type="submit" class="btn btn-primary" value="Crear plantillaencuesta" autofocus=""></form>
	</div>

	<div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
		<form class="form-inline" action="<?=base_url()?>plantillaencuesta/list" method="post">
			<label for="id-filter">Filtrar</label>
			<input id="id-filter" type="text" name="filter" class="form-control" >
		</form>
	</div>
</div>

<h1>LISTA de  plantillaencuesta</h1>

<table id="myTable" class="table table-striped tablesorter">
	<thead>
	<tr>
		<th>nombre</th>		<th>asociaa - nombre(imparticion)</th>
		<th>componepc - nombre(preguntacerrada)</th>
		<th>componepa - enunciado(preguntaabierta)</th>
		<th>acercade - fecha(encuesta)</th>
		<th>Acciones</th>
	</tr>
	</thead>

	<tbody>
	<?php foreach ($body['plantillaencuesta'] as $plantillaencuesta): ?>
		<tr>
			<td class="alert alert-success"><?= $plantillaencuesta -> nombre ?></td>
					
				<td>
				<?php foreach ($plantillaencuesta -> aggr('ownAsociaaList', 'imparticion') as $data): ?>
					<span><?= $data -> nombre ?> </span>
				<?php endforeach; ?>
				</td>
				
				<td>
				<?php foreach ($plantillaencuesta -> alias ('componepc') -> ownPreguntacerradaList as $data): ?>
					<span><?= $data -> nombre ?> </span>
				<?php endforeach; ?>
				</td>

				<td>
				<?php foreach ($plantillaencuesta -> alias ('componepa') -> ownPreguntaabiertaList as $data): ?>
					<span><?= $data -> enunciado ?> </span>
				<?php endforeach; ?>
				</td>

				<td>
				<?php foreach ($plantillaencuesta -> alias ('acercade') -> ownEncuestaList as $data): ?>
					<span><?= $data -> fecha ?> </span>
				<?php endforeach; ?>
				</td>

			<td class="form-inline col-md-1">
				<form action="<?= base_url() ?>plantillaencuesta/update" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $plantillaencuesta -> id ?>">
					<button onclick="submit()">
						<img src="<?= base_url() ?>assets/img/icons/png/pencil-2x.png" height="15" width="15" alt="editar">
					</button>
				</form>

				<form action="<?= base_url() ?>plantillaencuesta/delete" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $plantillaencuesta -> id ?>">
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