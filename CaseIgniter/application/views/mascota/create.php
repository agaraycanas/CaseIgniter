

<div class="container">
<h2> Crear mascota </h2>

<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>mascota/create_post" method="post">

	<div class="form-group">
		<label for="id-nombre">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" class="form-control" autofocus="">
	</div>


	<input type="submit" class="btn btn-primary" value="Crear">

</form>

<div id="idMessage" class="row col-sm-4">
</div>

</div>	