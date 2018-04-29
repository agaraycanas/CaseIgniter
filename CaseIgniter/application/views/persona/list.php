
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
		<form id="id-create" class="form-inline"  action="<?=base_url()?>persona/create">
			<input type="hidden" id="id-createfilter" name="filter" value="" />
			<input type="button" class="btn btn-primary" value="Crear persona" autofocus="autofocus"
				onclick="getElementById('id-createfilter').value  = getElementById('id-filter').value ;getElementById('id-create').submit() ;">
		</form>
	</div>

	<div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
		<form class="form-inline" action="<?=base_url()?>persona/list" method="post">
			<label for="id-filter">Filtrar</label>
			<input id="id-filter" type="search" name="filter" value="<?=$body['filter']?>" class="form-control" >
		</form>
	</div>
</div>

<h1>LISTA de  persona</h1>

<table id="myTable" class="table table-hover table-striped tablesorter">
	<thead>
	<tr>
		<th>nombre</th>		<th>fechanacimiento</th>
		<th>peso</th>
		<th>foto</th>
		<th>amo - nombre(mascota)</th>
		<th>paisnacimiento - nombre(pais)</th>
		<th>expertoen - nombre(aficion)</th>
		<th>inutilen - nombre(aficion)</th>
		<th>gusta - nombre(aficion)</th>
		<th>odia - nombre(aficion)</th>
		<th>Acciones</th>
	</tr>
	</thead>

	<tbody>
	<?php foreach ($body['persona'] as $persona): ?>
		<tr>
			<td class="alert alert-success"><?= str_ireplace($body['filter'], '<kbd>'.$body['filter'].'</kbd>', $persona -> nombre) ?></td>

			<td><?= str_ireplace($body['filter'], '<kbd>'.$body['filter'].'</kbd>',$persona -> fechanacimiento) ?></td>

			<td><?= str_ireplace($body['filter'], '<kbd>'.$body['filter'].'</kbd>',$persona -> peso) ?></td>

			<td><img src="<?=base_url().( ( $persona -> foto == null || $persona -> foto == '' ) ? 'assets/img/icons/png/ban-4x.png' : 'assets/upload/'.$persona -> foto)?>" alt="IMG" width="<?=( $persona -> foto == null || $persona -> foto == '' ) ? 15 : 30?>" height="<?=( $persona -> foto == null || $persona -> foto == '' ) ? 15 : 30?>" /></td>

			<td><?= str_ireplace($body['filter'], '<kbd>'.$body['filter'].'</kbd>',$persona ->  fetchAs('mascota') -> amo -> nombre) ?></td>

			<td><?= str_ireplace($body['filter'], '<kbd>'.$body['filter'].'</kbd>',$persona ->  fetchAs('pais') -> paisnacimiento -> nombre) ?></td>

			<td>
			<?php foreach ($persona -> alias ('expertoen') -> ownAficionList as $data): ?>
				<span><?= str_ireplace($body['filter'], '<kbd>'.$body['filter'].'</kbd>', $data -> nombre) ?> </span>
			<?php endforeach; ?>
			</td>

			<td>
			<?php foreach ($persona -> alias ('inutilen') -> ownAficionList as $data): ?>
				<span><?= str_ireplace($body['filter'], '<kbd>'.$body['filter'].'</kbd>', $data -> nombre) ?> </span>
			<?php endforeach; ?>
			</td>
					
			<td>
			<?php foreach ($persona -> aggr('ownGustaList', 'aficion') as $data): ?>
				<span><?= str_ireplace($body['filter'], '<kbd>'.$body['filter'].'</kbd>', $data -> nombre ) ?> </span>
			<?php endforeach; ?>
			</td>
									
			<td>
			<?php foreach ($persona -> aggr('ownOdiaList', 'aficion') as $data): ?>
				<span><?= str_ireplace($body['filter'], '<kbd>'.$body['filter'].'</kbd>', $data -> nombre ) ?> </span>
			<?php endforeach; ?>
			</td>
				
			<td class="form-inline text-center">

				<form id="id-update-<?= $persona -> id ?>" action="<?= base_url() ?>persona/update" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $persona -> id ?>">
					<input type="hidden" name="filter" value="" id="id-updatefilter-<?= $persona -> id ?>">
					<button onclick="getElementById('id-updatefilter-<?= $persona -> id ?>').value  = getElementById('id-filter').value ;getElementById('id-update').submit() ;">
						<img src="<?= base_url() ?>assets/img/icons/png/pencil-2x.png" height="15" width="15" alt="editar">
					</button>
				</form>

				<form id="id-delete-<?= $persona -> id ?>" action="<?= base_url() ?>persona/delete" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $persona -> id ?>">
					<input type="hidden" name="filter" value="" id="id-deletefilter-<?= $persona -> id ?>">
					<button onclick="getElementById('id-deletefilter-<?= $persona -> id ?>').value  = getElementById('id-filter').value ;getElementById('id-delete').submit() ;">
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