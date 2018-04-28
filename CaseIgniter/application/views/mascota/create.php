

<div class="container">
<h2> Crear mascota </h2>

<!-- BOOTSTRAP3
<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>mascota/create_post" method="post">
-->

<form class="form" role="form" id="idForm" action="<?= base_url() ?>mascota/create_post" method="post">

	
	<div class="row form-inline form-group">
		<label for="id-nombre" class="col-2 justify-content-end">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" class="col-6 form-control" autofocus="autofocus">
	</div>


<div class="row offset-2 col-6">
	<input type="submit" class="btn btn-primary" value="Crear">

</form>


<form action="<?=base_url()?>mascota/list" method="post">
	<input type="hidden" name="filter" value="<?=$body['filter']?>" />
	<input type="submit" class="offset-1 btn btn-primary" value="Cancelar">
</form>

</div>

</div>	