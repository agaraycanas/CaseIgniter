<div class="container">
<form action="<?=base_url()?>mascota/create"><input type="submit" class="btn btn-primary" value="Crear mascota"></form>
<h1>LISTA de  mascota</h1>
<table>
	<tr>
		<th>nombre<th>		<th>nombre(mascota)</th>
	</tr>

	<?php foreach ($body['mascota'] as $mascota): ?>
		<tr>		<th>nombre(mascota)</th>
		</tr>
	<?php endforeach; ?>
</table>
</div>