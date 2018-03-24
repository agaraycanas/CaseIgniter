<?php error_reporting(0); ?>
<div class="container">
<form action="<?=base_url()?>pais/create"><input type="submit" class="btn btn-primary" value="Crear pais"></form>
<h1>LISTA de  pais</h1>
<table class="table table-striped">
	<tr>
		<th>nombre</th>		<th>nombre(persona)</th>
		<th>Acciones</td>
	</tr>

	<?php foreach ($body['pais'] as $pais): ?>
		<tr>
			<td class="alert alert-success"><?= $pais -> nombre ?></td>

				<td>
				<?php foreach ($pais -> alias ('paisnacimiento') -> ownPersonaList as $data): ?>
					<span><?= $data -> nombre ?> </span>
				<?php endforeach; ?>
				</td>

			<td class="form-inline">
				<form action="<?= base_url() ?>pais/update" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $pais -> id ?>">
					<button onclick="submit()">
						<img src="<?= base_url() ?>assets/img/icons/png/pencil-2x.png" heigth="15" width="15">
					</button>
				</form>

				<form action="<?= base_url() ?>pais/delete" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $pais -> id ?>">
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