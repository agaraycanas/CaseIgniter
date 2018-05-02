

<div class="container">
<h2> Crear perrito </h2>

<form class="form" role="form" id="idForm" enctype="multipart/form-data" action="<?= base_url() ?>perrito/create_post" method="post">

	
	<div class="row form-inline form-group">
		<label for="id-nombre" class="col-2 justify-content-end">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" class="col-6 form-control" autofocus="autofocus">
		
	</div>

	
	<div class="row form-inline form-group">
		<label for="id-loginname" class="col-2 justify-content-end">Loginname</label>
		<input id="id-loginname" type="text" name="loginname" class="col-6 form-control" >
		
	</div>

	
	<div class="row form-inline form-group">
		<label for="id-password" class="col-2 justify-content-end">Password</label>
		<input id="id-password" type="text" name="password" class="col-6 form-control" >
		
	</div>


	<div class="row form-inline form-group">

		<label class="col-2 justify-content-end">Roles</label>
		<div class="col-6 form-check form-check-inline justify-content-start">

			<?php foreach ($body['rol'] as $rol ): ?>
				
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="checkbox" id="id-roles-<?=$rol->id?>" name="roles[]" value="<?= $rol->id ?>">
						<label class="form-check-label" for="id-roles-<?=$rol->id?>" ><?= $rol->nombre ?></label>
					</div>
				
			<?php endforeach; ?>
		</div>
	</div>


<div class="row offset-2 col-6">
	<input type="submit" class="btn btn-primary" value="Crear">

</form>


<form action="<?=base_url()?>perrito/list" method="post">
	<input type="hidden" name="filter" value="<?=$body['filter']?>" />
	<input type="submit" class="offset-1 btn btn-primary" value="Cancelar">
</form>

</div>

</div>	