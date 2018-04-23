
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
		<form class="form-inline"  action="<?=base_url()?>grupo/create"><input type="submit" class="btn btn-primary" value="Crear grupo" autofocus=""></form>
	</div>

	<div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
		<form class="form-inline" action="<?=base_url()?>grupo/list" method="post">
			<label for="id-filter">Filtrar</label>
			<input id="id-filter" type="text" name="filter" class="form-control" >
		</form>
	</div>
</div>

<h1>LISTA de  grupo</h1>

<table id="myTable" class="table table-striped tablesorter">
	<thead>
	<tr>
		<th>nombre</th>		<th>grreferencia - nombre(usuario)</th>
		<th>impartida - nombre(imparticion)</th>
		<th>pertenece - nivel(curso)</th>
		<th>Acciones</th>
	</tr>
	</thead>

	<tbody>
	<?php foreach ($body['grupo'] as $grupo): ?>
		<tr>
			<td class="alert alert-success"><?= $grupo -> nombre ?></td>

				<td>
				<?php foreach ($grupo -> alias ('grreferencia') -> ownUsuarioList as $data): ?>
					<span><?= $data -> nombre ?> </span>
				<?php endforeach; ?>
				</td>

				<td>
				<?php foreach ($grupo -> alias ('impartida') -> ownImparticionList as $data): ?>
					<span><?= $data -> nombre ?> </span>
				<?php endforeach; ?>
				</td>
		<td><?= $grupo ->  fetchAs('curso') -> pertenece -> nivel ?></td>

			<td class="form-inline col-md-1">
				<form action="<?= base_url() ?>grupo/update" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $grupo -> id ?>">
					<button onclick="submit()">
						<img src="<?= base_url() ?>assets/img/icons/png/pencil-2x.png" height="15" width="15" alt="editar">
					</button>
				</form>

				<form action="<?= base_url() ?>grupo/delete" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $grupo -> id ?>">
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