<div class="container">
<form action="<?=base_url()?>persona/create"><input type="submit" class="btn btn-primary" value="Crear persona"></form>
<h1>LISTA de  persona</h1>
<table>
	<tr>
		<th>nombre<th>		<th>fecha_nacimiento</th>
		<th>peso</th>
		<th>nombre(persona)</th>
		<th>nombre(persona)</th>
		<th>nombre(persona)</th>
	</tr>

	<?php foreach ($body['persona'] as $persona): ?>
		<tr>		<th>fecha_nacimiento</th>
		<th>peso</th>
		<th>nombre(persona)</th>
		<th>nombre(persona)</th>
		<th>nombre(persona)</th>
		</tr>
	<?php endforeach; ?>
</table>
</div>