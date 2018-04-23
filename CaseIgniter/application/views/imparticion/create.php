

<div class="container">
<h2> Crear imparticion </h2>

<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>imparticion/create_post" method="post">

			
	<div class="form-group">
		<label for="id-nombre">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" class="form-control" autofocus="">
	</div>


	<div class="form-group">
		<label for="id-desarrolla">Desarrolla</label>
		<select id="id-desarrolla" name="desarrolla" class="form-control">
			<option value="0"> ----- </option>
			<?php foreach ($body['usuario'] as $usuario ): ?>
				
					<option value="<?= $usuario->id ?>"><?= $usuario->nombre ?></option>
				
			<?php endforeach; ?>
		</select>
	</div>


	<div class="form-group">
		<label for="id-impartida">Impartida</label>
		<select id="id-impartida" name="impartida" class="form-control">
			<option value="0"> ----- </option>
			<?php foreach ($body['grupo'] as $grupo ): ?>
				
					<option value="<?= $grupo->id ?>"><?= $grupo->nombre ?></option>
				
			<?php endforeach; ?>
		</select>
	</div>


	<div class="form-group">
		<label for="id-refierea">Refierea</label>
		<select id="id-refierea" name="refierea" class="form-control">
			<option value="0"> ----- </option>
			<?php foreach ($body['asignatura'] as $asignatura ): ?>
				
					<option value="<?= $asignatura->id ?>"><?= $asignatura->nombre ?></option>
				
			<?php endforeach; ?>
		</select>
	</div>

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Asociaa</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['plantillaencuesta'] as $plantillaencuesta ): ?>
				
					<input class="form-check-input" type="checkbox" id="id-asociaa-<?=$plantillaencuesta->id?>" name="asociaa[]" value="<?= $plantillaencuesta->id ?>">
					<label class="form-check-label" for="id-asociaa-<?=$plantillaencuesta->id?>" ><?= $plantillaencuesta->nombre ?></label>
				
			<?php endforeach; ?>
		</div>
	</fieldset>

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Realizada</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['encuesta'] as $encuesta ): ?>
				<?php if ( $encuesta->realizada == null ): ?>
					<input class="form-check-input" type="checkbox" id="id-realizada-<?=$encuesta->id?>" name="realizada[]" value="<?= $encuesta->id ?>">
					<label class="form-check-label" for="id-realizada-<?=$encuesta->id?>" ><?= $encuesta->fecha ?></label>
				<?php endif; ?> 
			<?php endforeach; ?>
		</div>
	</fieldset>


	<div class="form-group">
		<label for="id-tieneim">Tieneim</label>
		<select id="id-tieneim" name="tieneim" class="form-control">
			<option value="0"> ----- </option>
			<?php foreach ($body['cursoacademico'] as $cursoacademico ): ?>
				
					<option value="<?= $cursoacademico->id ?>"><?= $cursoacademico->anyoini ?></option>
				
			<?php endforeach; ?>
		</select>
	</div>


	<input type="submit" class="btn btn-primary" value="Crear">

</form>

<div id="idMessage" class="row col-sm-4">
</div>

</div>	