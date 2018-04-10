
<script>
	$(document).ready(function() 
	    { 
	        $("#myTable").tablesorter(); 
	    } 
	);
</script>

<?php error_reporting(0); ?>
<div class="container">
<form action="<?=base_url()?>aficion/create"><input type="submit" class="btn btn-primary" value="Crear aficion"></form>
<h1>LISTA de  aficion</h1>
<table id="myTable" class="table table-striped tablesorter">
	<thead>
	<tr>
		<th>nombre</th>		<th>odia - nombre(persona)</th>
		<th>aficiones - nombre(persona)</th>
		<th>expertoen - nombre(persona)</th>
		<th>inutilen - nombre(persona)</th>
		<th>Acciones</th>
	</tr>
	</thead>

	<tbody>
	<?php foreach ($body['aficion'] as $aficion): ?>
		<tr>
			<td class="alert alert-success"><?= $aficion -> nombre ?></td>
					
				<td>
				<?php foreach ($aficion -> aggr('ownOdiaList', 'persona') as $data): ?>
					<span><?= $data -> nombre ?> </span>
				<?php endforeach; ?>
				</td>
									
				<td>
				<?php foreach ($aficion -> aggr('ownAficionesList', 'persona') as $data): ?>
					<span><?= $data -> nombre ?> </span>
				<?php endforeach; ?>
				</td>
						<td><?= $aficion ->  fetchAs('persona') -> expertoen -> nombre ?></td>
		<td><?= $aficion ->  fetchAs('persona') -> inutilen -> nombre ?></td>

			<td class="form-inline col-md-1">
				<form action="<?= base_url() ?>aficion/update" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $aficion -> id ?>">
					<button onclick="submit()">
						<img src="<?= base_url() ?>assets/img/icons/png/pencil-2x.png" height="15" width="15" alt="editar">
					</button>
				</form>

				<form action="<?= base_url() ?>aficion/delete" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $aficion -> id ?>">
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