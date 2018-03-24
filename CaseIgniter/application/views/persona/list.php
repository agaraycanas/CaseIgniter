<?php error_reporting(0); ?>
<div class="container">
<form action="<?=base_url()?>persona/create"><input type="submit" class="btn btn-primary" value="Crear persona"></form>
<h1>LISTA de  persona</h1>
<table class="table table-striped">
	<tr>
		<th>nombre</th>		<th>fechanacimiento</th>
		<th>peso</th>
		<th>nombre(mascota)</th>
		<th>nombre(pais)</th>
		<th>nombre(aficion)</th>
		<th>nombre(aficion)</th>
		<th>Acciones</td>
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
				<?php foreach ($persona -> alias ('expertoen') -> ownAficionList as $data): ?>
					<span><?= $data -> nombre ?> </span>
				<?php endforeach; ?>
				</td>

			<td class="form-inline">
				<form action="<?= base_url() ?>persona/update" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $persona -> id ?>">
					<button onclick="submit()">
						<img src="<?= base_url() ?>assets/img/icons/png/pencil-2x.png" heigth="15" width="15">
					</button>
				</form>

				<form action="<?= base_url() ?>persona/delete" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $persona -> id ?>">
					<button onclick="submit()">
						<img src="<?= base_url() ?>assets/img/icons/png/trash-2x.png" heigth="15" width="15">
					</button>
				</form>
			</td>

		</tr>
	<?php endforeach; ?>
</table>
</div>
<?php error_reporting(E_ALL); ?>