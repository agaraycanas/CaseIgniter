

<div class="container">
<h2> Crear plantillaencuesta </h2>

<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>plantillaencuesta/create_post" method="post">

			
	<div class="form-group">
		<label for="id-nombre">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" class="form-control" autofocus="">
	</div>

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Asociaa</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['imparticion'] as $imparticion ): ?>
				
					<input class="form-check-input" type="checkbox" id="id-asociaa-<?=$imparticion->id?>" name="asociaa[]" value="<?= $imparticion->id ?>">
					<label class="form-check-label" for="id-asociaa-<?=$imparticion->id?>" ><?= $imparticion->nombre ?></label>
				
			<?php endforeach; ?>
		</div>
	</fieldset>

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Componepc</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['preguntacerrada'] as $preguntacerrada ): ?>
				<?php if ( $preguntacerrada->componepc == null ): ?>
					<input class="form-check-input" type="checkbox" id="id-componepc-<?=$preguntacerrada->id?>" name="componepc[]" value="<?= $preguntacerrada->id ?>">
					<label class="form-check-label" for="id-componepc-<?=$preguntacerrada->id?>" ><?= $preguntacerrada->nombre ?></label>
				<?php endif; ?> 
			<?php endforeach; ?>
		</div>
	</fieldset>

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Componepa</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['preguntaabierta'] as $preguntaabierta ): ?>
				<?php if ( $preguntaabierta->componepa == null ): ?>
					<input class="form-check-input" type="checkbox" id="id-componepa-<?=$preguntaabierta->id?>" name="componepa[]" value="<?= $preguntaabierta->id ?>">
					<label class="form-check-label" for="id-componepa-<?=$preguntaabierta->id?>" ><?= $preguntaabierta->enunciado ?></label>
				<?php endif; ?> 
			<?php endforeach; ?>
		</div>
	</fieldset>

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Acercade</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['encuesta'] as $encuesta ): ?>
				<?php if ( $encuesta->acercade == null ): ?>
					<input class="form-check-input" type="checkbox" id="id-acercade-<?=$encuesta->id?>" name="acercade[]" value="<?= $encuesta->id ?>">
					<label class="form-check-label" for="id-acercade-<?=$encuesta->id?>" ><?= $encuesta->fecha ?></label>
				<?php endif; ?> 
			<?php endforeach; ?>
		</div>
	</fieldset>


	<input type="submit" class="btn btn-primary" value="Crear">

</form>

<div id="idMessage" class="row col-sm-4">
</div>

</div>	