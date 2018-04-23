

<div class="container">
<h2> Crear departamento </h2>

<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>departamento/create_post" method="post">

			
	<div class="form-group">
		<label for="id-nombre">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" class="form-control" autofocus="">
	</div>

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Pertenece</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['usuario'] as $usuario ): ?>
				
					<input class="form-check-input" type="checkbox" id="id-pertenece-<?=$usuario->id?>" name="pertenece[]" value="<?= $usuario->id ?>">
					<label class="form-check-label" for="id-pertenece-<?=$usuario->id?>" ><?= $usuario->nombre ?></label>
				
			<?php endforeach; ?>
		</div>
	</fieldset>

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Imparte</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['asignatura'] as $asignatura ): ?>
				<?php if ( $asignatura->imparte == null ): ?>
					<input class="form-check-input" type="checkbox" id="id-imparte-<?=$asignatura->id?>" name="imparte[]" value="<?= $asignatura->id ?>">
					<label class="form-check-label" for="id-imparte-<?=$asignatura->id?>" ><?= $asignatura->nombre ?></label>
				<?php endif; ?> 
			<?php endforeach; ?>
		</div>
	</fieldset>

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Gestiona</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['titulacion'] as $titulacion ): ?>
				
					<input class="form-check-input" type="checkbox" id="id-gestiona-<?=$titulacion->id?>" name="gestiona[]" value="<?= $titulacion->id ?>">
					<label class="form-check-label" for="id-gestiona-<?=$titulacion->id?>" ><?= $titulacion->nombre ?></label>
				
			<?php endforeach; ?>
		</div>
	</fieldset>


	<input type="submit" class="btn btn-primary" value="Crear">

</form>

<div id="idMessage" class="row col-sm-4">
</div>

</div>	