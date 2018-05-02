

<div class="container">

<h2> Bienvenido </h2>
<h5> Introduce tus credenciales</h5>

<form class="form" role="form" id="idForm" action="<?= base_url() ?>rol/loginPost" method="post">

	<div class="row form-inline form-group">
		<label for="id-loginname" class="col-2 justify-content-end">Usuario</label>
		<input id="id-loginname" type="text" name="loginname" class="col-6 form-control" autofocus="autofocus">
	</div>

	<div class="row form-inline form-group">
		<label for="id-password" class="col-2 justify-content-end">Contraseña</label>
		<input id="id-password" type="password" name="password" class="col-6 form-control" >
	</div>

	<div class="row form-inline form-group">
		<label for="id-rol" class="col-2 justify-content-end">Rol</label>
		<select id="id-rol" name="rol" class="col-6 form-control">
		<?php foreach ($body['rol'] as $rol):?>
			<option value="<?=$rol->id?>"><?=$rol->descripcion?></option>
		<?php endforeach; ?>				
		</select>
	</div>

	<div class="row offset-2 col-6">
		<input type="submit" class="btn btn-primary" value="Crear">
		<a href="<?=base_url()?>">
			<input type="button" class="offset-1 btn btn-primary" value="Cancelar">
		</a>
	</div>
	
</form>

</div>
