
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
		<form class="form-inline"  action="<?=base_url()?>respuestaabierta/create"><input type="submit" class="btn btn-primary" value="Crear respuestaabierta" autofocus=""></form>
	</div>

	<div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
		<form class="form-inline" action="<?=base_url()?>respuestaabierta/list" method="post">
			<label for="id-filter">Filtrar</label>
			<input id="id-filter" type="text" name="filter" class="form-control" >
		</form>
	</div>
</div>

<h1>LISTA de  respuestaabierta</h1>

<table id="myTable" class="table table-striped tablesorter">
	<thead>
	<tr>
		<th>texto</th>		<th>respondera - fecha(encuesta)</th>
		<th>respuestasra - enunciado(preguntaabierta)</th>
		<th>Acciones</th>
	</tr>
	</thead>

	<tbody>
	<?php foreach ($body['respuestaabierta'] as $respuestaabierta): ?>
		<tr>
			<td class="alert alert-success"><?= $respuestaabierta -> texto ?></td>
		<td><?= $respuestaabierta ->  fetchAs('encuesta') -> respondera -> fecha ?></td>
		<td><?= $respuestaabierta ->  fetchAs('preguntaabierta') -> respuestasra -> enunciado ?></td>

			<td class="form-inline col-md-1">
				<form action="<?= base_url() ?>respuestaabierta/update" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $respuestaabierta -> id ?>">
					<button onclick="submit()">
						<img src="<?= base_url() ?>assets/img/icons/png/pencil-2x.png" height="15" width="15" alt="editar">
					</button>
				</form>

				<form action="<?= base_url() ?>respuestaabierta/delete" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $respuestaabierta -> id ?>">
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