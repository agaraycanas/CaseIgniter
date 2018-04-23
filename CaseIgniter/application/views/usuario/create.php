

<div class="container">
<h2> Crear usuario </h2>

<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>usuario/create_post" method="post">

			
	<div class="form-group">
		<label for="id-nombre">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" class="form-control" autofocus="">
	</div>

			
	<div class="form-group">
		<label for="id-pwd">Pwd</label>
		<input id="id-pwd" type="text" name="pwd" class="form-control" >
	</div>

			
	<div class="form-group">
		<label for="id-rol">Rol</label>
		<input id="id-rol" type="text" name="rol" class="form-control" >
	</div>

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Trabaja</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['cursoacademico'] as $cursoacademico ): ?>
				
					<input class="form-check-input" type="checkbox" id="id-trabaja-<?=$cursoacademico->id?>" name="trabaja[]" value="<?= $cursoacademico->id ?>">
					<label class="form-check-label" for="id-trabaja-<?=$cursoacademico->id?>" ><?= $cursoacademico->anyoini ?></label>
				
			<?php endforeach; ?>
		</div>
	</fieldset>

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Pertenece</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['departamento'] as $departamento ): ?>
				
					<input class="form-check-input" type="checkbox" id="id-pertenece-<?=$departamento->id?>" name="pertenece[]" value="<?= $departamento->id ?>">
					<label class="form-check-label" for="id-pertenece-<?=$departamento->id?>" ><?= $departamento->nombre ?></label>
				
			<?php endforeach; ?>
		</div>
	</fieldset>

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Realiza</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['encuesta'] as $encuesta ): ?>
				<?php if ( $encuesta->realiza == null ): ?>
					<input class="form-check-input" type="checkbox" id="id-realiza-<?=$encuesta->id?>" name="realiza[]" value="<?= $encuesta->id ?>">
					<label class="form-check-label" for="id-realiza-<?=$encuesta->id?>" ><?= $encuesta->fecha ?></label>
				<?php endif; ?> 
			<?php endforeach; ?>
		</div>
	</fieldset>

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Desarrolla</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['imparticion'] as $imparticion ): ?>
				<?php if ( $imparticion->desarrolla == null ): ?>
					<input class="form-check-input" type="checkbox" id="id-desarrolla-<?=$imparticion->id?>" name="desarrolla[]" value="<?= $imparticion->id ?>">
					<label class="form-check-label" for="id-desarrolla-<?=$imparticion->id?>" ><?= $imparticion->nombre ?></label>
				<?php endif; ?> 
			<?php endforeach; ?>
		</div>
	</fieldset>


	<div class="form-group">
		<label for="id-grreferencia">Grreferencia</label>
		<select id="id-grreferencia" name="grreferencia" class="form-control">
			<option value="0"> ----- </option>
			<?php foreach ($body['grupo'] as $grupo ): ?>
				
					<option value="<?= $grupo->id ?>"><?= $grupo->nombre ?></option>
				
			<?php endforeach; ?>
		</select>
	</div>


	<input type="submit" class="btn btn-primary" value="Crear">

</form>

<div id="idMessage" class="row col-sm-4">
</div>

</div>	