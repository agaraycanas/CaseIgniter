<?php error_reporting(0); ?>
<div class="container">
<form action="<?=base_url()?>persona/create"><input type="submit" class="btn btn-primary" value="Crear persona"></form>
<h1>LISTA de  persona</h1>
<table class="table table-striped">
	<tr>
		<th>nombre</th>		<th>fechanacimiento</th>
		<th>peso</th>
		<th>amo - nombre(mascota)</th>
		<th>paisnacimiento - nombre(pais)</th>
		<th>aficiones - nombre(aficion)</th>
		<th>odia - nombre(aficion)</th>
		<th>expertoen - nombre(aficion)</th>
		<th>inutilen - nombre(aficion)</th>
		<th>Acciones</th>
	</tr>

	<?php foreach ($body['persona'] as $persona): ?>
		<tr>
			<td class="alert alert-success"><?= $persona -> nombre ?></td>
			<td><?= $persona -> fechanacimiento ?></td>
			<td><?= $persona -> peso ?></td>
		<td><?= $persona ->  fetchAs('mascota') -> amo -> nombre ?></td>
		<td><?= $persona ->  fetchAs('pais') -> paisnacimiento -> nombre ?></td>
					
				<td>
				<?php foreach ($persona -> aggr('ownAficionesList', 'aficion') as $data): ?>
					<span><?= $data -> nombre ?> </span>
				<?php endforeach; ?>
				</td>
									
				<td>
				<?php foreach ($persona -> aggr('ownOdiaList', 'aficion') as $data): ?>
					<span><?= $data -> nombre ?> </span>
				<?php endforeach; ?>
				</td>
				
				<td>
				<?php foreach ($persona -> alias ('expertoen') -> ownAficionList as $data): ?>
					<span><?= $data -> nombre ?> </span>
				<?php endforeach; ?>
				</td>

				<td>
				<?php foreach ($persona -> alias ('inutilen') -> ownAficionList as $data): ?>
					<span><?= $data -> nombre ?> </span>
				<?php endforeach; ?>
				</td>

			<td class="form-inline col-md-1">
				<form action="<?= base_url() ?>persona/update" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $persona -> id ?>">
					<button onclick="submit()">
						<img src="<?= base_url() ?>assets/img/icons/png/pencil-2x.png" height="15" width="15">
					</button>
				</form>

				<form action="<?= base_url() ?>persona/delete" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $persona -> id ?>">
					<button onclick="submit()">
						<img src="<?= base_url() ?>assets/img/icons/png/trash-2x.png" height="15" width="15">
					</button>
				</form>
			</td>

		</tr>
	<?php endforeach; ?>
</table>
</div>
<?php error_reporting(E_ALL); ?>