
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
		<form class="form-inline"  action="<?=base_url()?>usuario/create"><input type="submit" class="btn btn-primary" value="Crear usuario" autofocus=""></form>
	</div>

	<div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
		<form class="form-inline" action="<?=base_url()?>usuario/list" method="post">
			<label for="id-filter">Filtrar</label>
			<input id="id-filter" type="text" name="filter" class="form-control" >
		</form>
	</div>
</div>

<h1>LISTA de  usuario</h1>

<table id="myTable" class="table table-striped tablesorter">
	<thead>
	<tr>
		<th>nombre</th>		<th>rol</th>
		<th>trabaja - anyoini(cursoacademico)</th>
		<th>pertenece - nombre(departamento)</th>
		<th>realiza - fecha(encuesta)</th>
		<th>desarrolla - nombre(imparticion)</th>
		<th>grreferencia - nombre(grupo)</th>
		<th>Acciones</th>
	</tr>
	</thead>

	<tbody>
	<?php foreach ($body['usuario'] as $usuario): ?>
		<tr>
			<td class="alert alert-success"><?= $usuario -> nombre ?></td>
			<td><?= $usuario -> rol ?></td>
					
				<td>
				<?php foreach ($usuario -> aggr('ownTrabajaList', 'cursoacademico') as $data): ?>
					<span><?= $data -> anyoini ?> </span>
				<?php endforeach; ?>
				</td>
									
				<td>
				<?php foreach ($usuario -> aggr('ownPerteneceList', 'departamento') as $data): ?>
					<span><?= $data -> nombre ?> </span>
				<?php endforeach; ?>
				</td>
				
				<td>
				<?php foreach ($usuario -> alias ('realiza') -> ownEncuestaList as $data): ?>
					<span><?= $data -> fecha ?> </span>
				<?php endforeach; ?>
				</td>

				<td>
				<?php foreach ($usuario -> alias ('desarrolla') -> ownImparticionList as $data): ?>
					<span><?= $data -> nombre ?> </span>
				<?php endforeach; ?>
				</td>
		<td><?= $usuario ->  fetchAs('grupo') -> grreferencia -> nombre ?></td>

			<td class="form-inline col-md-1">
				<form action="<?= base_url() ?>usuario/update" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $usuario -> id ?>">
					<button onclick="submit()">
						<img src="<?= base_url() ?>assets/img/icons/png/pencil-2x.png" height="15" width="15" alt="editar">
					</button>
				</form>

				<form action="<?= base_url() ?>usuario/delete" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $usuario -> id ?>">
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