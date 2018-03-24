<?php error_reporting(0); ?>
<div class="container">
<form action="<?=base_url()?>mascota/create"><input type="submit" class="btn btn-primary" value="Crear mascota"></form>
<h1>LISTA de  mascota</h1>
<table class="table table-striped">
	<tr>
		<th>nombre</th>		<th>nombre(persona)</th>
		<th>Acciones</td>
	</tr>

	<?php foreach ($body['mascota'] as $mascota): ?>
		<tr>
			<td class="alert alert-success"><?= $mascota -> nombre ?></td>
		<td><?= $mascota ->  fetchAs('persona') -> amo -> nombre ?></td>

			<td class="form-inline">
				<form action="<?= base_url() ?>mascota/update" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $mascota -> id ?>">
					<button onclick="submit()">
						<img src="<?= base_url() ?>assets/img/icons/png/pencil-2x.png" heigth="15" width="15">
					</button>
				</form>

				<form action="<?= base_url() ?>mascota/delete" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $mascota -> id ?>">
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