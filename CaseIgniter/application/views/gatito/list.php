
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
		<form id="id-create" class="form-inline"  action="<?=base_url()?>gatito/create">
			<input type="hidden" id="id-createfilter" name="filter" value="" />
			<input type="button" class="btn btn-primary" value="Crear gatito" autofocus="autofocus"
				onclick="getElementById('id-createfilter').value  = getElementById('id-filter').value ;getElementById('id-create').submit() ;">
		</form>
	</div>

	<div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
		<form class="form-inline" action="<?=base_url()?>gatito/list" method="post">
			<label for="id-filter">Filtrar</label>
			<input id="id-filter" type="search" name="filter" value="<?=$body['filter']?>" class="form-control" >
		</form>
	</div>
</div>

<h1>LISTA de  gatito</h1>

<table id="myTable" class="table table-hover table-striped tablesorter">
	<thead>
	<tr>
		<th>nombre</th>		<th>piba</th>
		<th>loginname</th>
		<th>roles - nombre(rol)</th>
		<th>Acciones</th>
	</tr>
	</thead>

	<tbody>
	<?php foreach ($body['gatito'] as $gatito): ?>
		<tr>
			<td class="alert alert-success"><?= str_ireplace($body['filter'], '<kbd>'.$body['filter'].'</kbd>', $gatito -> nombre) ?></td>

			<td><?= str_ireplace($body['filter'], '<kbd>'.$body['filter'].'</kbd>',$gatito -> piba) ?></td>

			<td><?= str_ireplace($body['filter'], '<kbd>'.$body['filter'].'</kbd>',$gatito -> loginname) ?></td>
					
			<td>
			<?php foreach ($gatito -> aggr('ownRolesList', 'rol') as $data): ?>
				<span><?= str_ireplace($body['filter'], '<kbd>'.$body['filter'].'</kbd>', $data -> nombre ) ?> </span>
			<?php endforeach; ?>
			</td>
				
			<td class="form-inline text-center">

				<form id="id-update-<?= $gatito -> id ?>" action="<?= base_url() ?>gatito/update" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $gatito -> id ?>">
					<input type="hidden" name="filter" value="" id="id-updatefilter-<?= $gatito -> id ?>">
					<button onclick="getElementById('id-updatefilter-<?= $gatito -> id ?>').value  = getElementById('id-filter').value ;getElementById('id-update').submit() ;">
						<img src="<?= base_url() ?>assets/img/icons/png/pencil-2x.png" height="15" width="15" alt="editar">
					</button>
				</form>

				<form id="id-delete-<?= $gatito -> id ?>" action="<?= base_url() ?>gatito/delete" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $gatito -> id ?>">
					<input type="hidden" name="filter" value="" id="id-deletefilter-<?= $gatito -> id ?>">
					<button onclick="getElementById('id-deletefilter-<?= $gatito -> id ?>').value  = getElementById('id-filter').value ;getElementById('id-delete').submit() ;">
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