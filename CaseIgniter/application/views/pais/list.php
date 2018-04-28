
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
		<form id="id-create" class="form-inline"  action="<?=base_url()?>pais/create">
			<input type="hidden" id="id-createfilter" name="filter" value="" />
			<input type="button" class="btn btn-primary" value="Crear pais" autofocus="autofocus"
				onclick="getElementById('id-createfilter').value  = getElementById('id-filter').value ;getElementById('id-create').submit() ;">
		</form>
	</div>

	<div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
		<form class="form-inline" action="<?=base_url()?>pais/list" method="post">
			<label for="id-filter">Filtrar</label>
			<input id="id-filter" type="search" name="filter" value="<?=$body['filter']?>" class="form-control" >
		</form>
	</div>
</div>

<h1>LISTA de  pais</h1>

<table id="myTable" class="table table-hover table-striped tablesorter">
	<thead>
	<tr>
		<th>nombre</th>		<th>paisnacimiento - nombre(persona)</th>
		<th>Acciones</th>
	</tr>
	</thead>

	<tbody>
	<?php foreach ($body['pais'] as $pais): ?>
		<tr>
			<td class="alert alert-success"><?= str_ireplace($body['filter'], '<kbd>'.$body['filter'].'</kbd>', $pais -> nombre) ?></td>

				<td>
				<?php foreach ($pais -> alias ('paisnacimiento') -> ownPersonaList as $data): ?>
					<span><?= str_ireplace($body['filter'], '<kbd>'.$body['filter'].'</kbd>', $data -> nombre) ?> </span>
				<?php endforeach; ?>
				</td>

			<td class="form-inline text-center">

				<form id="id-update-<?= $pais -> id ?>" action="<?= base_url() ?>pais/update" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $pais -> id ?>">
					<input type="hidden" name="filter" value="" id="id-updatefilter-<?= $pais -> id ?>">
					<button onclick="getElementById('id-updatefilter-<?= $pais -> id ?>').value  = getElementById('id-filter').value ;getElementById('id-update').submit() ;">
						<img src="<?= base_url() ?>assets/img/icons/png/pencil-2x.png" height="15" width="15" alt="editar">
					</button>
				</form>

				<form id="id-delete-<?= $pais -> id ?>" action="<?= base_url() ?>pais/delete" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $pais -> id ?>">
					<input type="hidden" name="filter" value="" id="id-deletefilter-<?= $pais -> id ?>">
					<button onclick="getElementById('id-deletefilter-<?= $pais -> id ?>').value  = getElementById('id-filter').value ;getElementById('id-delete').submit() ;">
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