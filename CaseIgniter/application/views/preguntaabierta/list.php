
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
		<form class="form-inline"  action="<?=base_url()?>preguntaabierta/create"><input type="submit" class="btn btn-primary" value="Crear preguntaabierta" autofocus=""></form>
	</div>

	<div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
		<form class="form-inline" action="<?=base_url()?>preguntaabierta/list" method="post">
			<label for="id-filter">Filtrar</label>
			<input id="id-filter" type="text" name="filter" class="form-control" >
		</form>
	</div>
</div>

<h1>LISTA de  preguntaabierta</h1>

<table id="myTable" class="table table-striped tablesorter">
	<thead>
	<tr>
		<th>enunciado</th>		<th>enunciadofacil</th>
		<th>versaabierta - nombre(asignatura)</th>
		<th>componepa - nombre(plantillaencuesta)</th>
		<th>respuestasra - texto(respuestaabierta)</th>
		<th>contienepa - nombre(categoria)</th>
		<th>Acciones</th>
	</tr>
	</thead>

	<tbody>
	<?php foreach ($body['preguntaabierta'] as $preguntaabierta): ?>
		<tr>
			<td class="alert alert-success"><?= $preguntaabierta -> enunciado ?></td>
			<td><?= $preguntaabierta -> enunciadofacil ?></td>
		<td><?= $preguntaabierta ->  fetchAs('asignatura') -> versaabierta -> nombre ?></td>
		<td><?= $preguntaabierta ->  fetchAs('plantillaencuesta') -> componepa -> nombre ?></td>

				<td>
				<?php foreach ($preguntaabierta -> alias ('respuestasra') -> ownRespuestaabiertaList as $data): ?>
					<span><?= $data -> texto ?> </span>
				<?php endforeach; ?>
				</td>
		<td><?= $preguntaabierta ->  fetchAs('categoria') -> contienepa -> nombre ?></td>

			<td class="form-inline col-md-1">
				<form action="<?= base_url() ?>preguntaabierta/update" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $preguntaabierta -> id ?>">
					<button onclick="submit()">
						<img src="<?= base_url() ?>assets/img/icons/png/pencil-2x.png" height="15" width="15" alt="editar">
					</button>
				</form>

				<form action="<?= base_url() ?>preguntaabierta/delete" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $preguntaabierta -> id ?>">
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