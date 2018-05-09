

<div class="container">
<h2> Crear rol </h2>

<form class="form" role="form" id="idForm" enctype="multipart/form-data" action="<?= base_url() ?>rol/create_post" method="post">

	
	

	<div class="row form-inline form-group">
		<label for="id-nombre" class="col-2 justify-content-end">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" class="col-6 form-control" autofocus="autofocus">
		
	</div>

	
	

	<div class="row form-inline form-group">
		<label for="id-descripcion" class="col-2 justify-content-end">Descripcion</label>
		<input id="id-descripcion" type="text" name="descripcion" class="col-6 form-control" >
		
	</div>


	<div class="row form-inline form-group">

		<label class="col-2 justify-content-end">Roles</label>
		<div class="col-6 form-check form-check-inline justify-content-start">

			<?php foreach ($body['usuario'] as $usuario ): ?>
				
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="checkbox" id="id-roles-<?=$usuario->id?>" name="roles[]" value="<?= $usuario->id ?>">
						<label class="form-check-label" for="id-roles-<?=$usuario->id?>" ><?= $usuario->nombre ?></label>
					</div>
				
			<?php endforeach; ?>
		</div>
	</div>


<div class="row offset-2 col-6">
	<input type="submit" class="btn btn-primary" value="Crear">

</form>


<form action="<?=base_url()?>rol/list" method="post">
	<input type="hidden" name="filter" value="<?=$body['filter']?>" />
	<input type="submit" class="offset-1 btn btn-primary" value="Cancelar">
</form>

</div>

</div>	