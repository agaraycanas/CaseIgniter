
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
		<form class="form-inline"  action="<?=base_url()?>imparticion/create"><input type="submit" class="btn btn-primary" value="Crear imparticion" autofocus=""></form>
	</div>

	<div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
		<form class="form-inline" action="<?=base_url()?>imparticion/list" method="post">
			<label for="id-filter">Filtrar</label>
			<input id="id-filter" type="text" name="filter" class="form-control" >
		</form>
	</div>
</div>

<h1>LISTA de  imparticion</h1>

<table id="myTable" class="table table-striped tablesorter">
	<thead>
	<tr>
		<th>nombre</th>		<th>desarrolla - nombre(usuario)</th>
		<th>impartida - nombre(grupo)</th>
		<th>refierea - nombre(asignatura)</th>
		<th>asociaa - nombre(plantillaencuesta)</th>
		<th>realizada - fecha(encuesta)</th>
		<th>tieneim - anyoini(cursoacademico)</th>
		<th>Acciones</th>
	</tr>
	</thead>

	<tbody>
	<?php foreach ($body['imparticion'] as $imparticion): ?>
		<tr>
			<td class="alert alert-success"><?= $imparticion -> nombre ?></td>
		<td><?= $imparticion ->  fetchAs('usuario') -> desarrolla -> nombre ?></td>
		<td><?= $imparticion ->  fetchAs('grupo') -> impartida -> nombre ?></td>
		<td><?= $imparticion ->  fetchAs('asignatura') -> refierea -> nombre ?></td>
					
				<td>
				<?php foreach ($imparticion -> aggr('ownAsociaaList', 'plantillaencuesta') as $data): ?>
					<span><?= $data -> nombre ?> </span>
				<?php endforeach; ?>
				</td>
				
				<td>
				<?php foreach ($imparticion -> alias ('realizada') -> ownEncuestaList as $data): ?>
					<span><?= $data -> fecha ?> </span>
				<?php endforeach; ?>
				</td>
		<td><?= $imparticion ->  fetchAs('cursoacademico') -> tieneim -> anyoini ?></td>

			<td class="form-inline col-md-1">
				<form action="<?= base_url() ?>imparticion/update" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $imparticion -> id ?>">
					<button onclick="submit()">
						<img src="<?= base_url() ?>assets/img/icons/png/pencil-2x.png" height="15" width="15" alt="editar">
					</button>
				</form>

				<form action="<?= base_url() ?>imparticion/delete" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $imparticion -> id ?>">
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