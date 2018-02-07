<div class="container">
<form action="<?=base_url()?>aficion/create"><input type="submit" class="btn btn-primary" value="Crear aficion"></form>
<h1>LISTA de  aficion</h1>
<table>
	<tr>
		<th>nombre<th>		<th>nombre(aficion)</th>
	</tr>

	<?php foreach ($body['aficion'] as $aficion): ?>
		<tr>		<th>nombre(aficion)</th>
		</tr>
	<?php endforeach; ?>
</table>
</div>