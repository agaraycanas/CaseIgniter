
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
		<form class="form-inline"  action="<?=base_url()?>preguntacerrada/create"><input type="submit" class="btn btn-primary" value="Crear preguntacerrada" autofocus=""></form>
	</div>

	<div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
		<form class="form-inline" action="<?=base_url()?>preguntacerrada/list" method="post">
			<label for="id-filter">Filtrar</label>
			<input id="id-filter" type="text" name="filter" class="form-control" >
		</form>
	</div>
</div>

<h1>LISTA de  preguntacerrada</h1>

<table id="myTable" class="table table-striped tablesorter">
	<thead>
	<tr>
		<th>nombre</th>		<th>enunciado</th>
		<th>enunciadofacil</th>
		<th>min</th>
		<th>max</th>
		<th>versacerrada - nombre(asignatura)</th>
		<th>componepc - nombre(plantillaencuesta)</th>
		<th>respuestasrc - numero(respuestacerrada)</th>
		<th>contienepc - nombre(categoria)</th>
		<th>Acciones</th>
	</tr>
	</thead>

	<tbody>
	<?php foreach ($body['preguntacerrada'] as $preguntacerrada): ?>
		<tr>
			<td class="alert alert-success"><?= $preguntacerrada -> nombre ?></td>
			<td><?= $preguntacerrada -> enunciado ?></td>
			<td><?= $preguntacerrada -> enunciadofacil ?></td>
			<td><?= $preguntacerrada -> min ?></td>
			<td><?= $preguntacerrada -> max ?></td>
		<td><?= $preguntacerrada ->  fetchAs('asignatura') -> versacerrada -> nombre ?></td>
		<td><?= $preguntacerrada ->  fetchAs('plantillaencuesta') -> componepc -> nombre ?></td>
		<td><?= $preguntacerrada ->  fetchAs('respuestacerrada') -> respuestasrc -> numero ?></td>
		<td><?= $preguntacerrada ->  fetchAs('categoria') -> contienepc -> nombre ?></td>

			<td class="form-inline col-md-1">
				<form action="<?= base_url() ?>preguntacerrada/update" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $preguntacerrada -> id ?>">
					<button onclick="submit()">
						<img src="<?= base_url() ?>assets/img/icons/png/pencil-2x.png" height="15" width="15" alt="editar">
					</button>
				</form>

				<form action="<?= base_url() ?>preguntacerrada/delete" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $preguntacerrada -> id ?>">
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