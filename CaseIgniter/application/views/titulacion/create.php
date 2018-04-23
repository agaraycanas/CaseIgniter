

<div class="container">
<h2> Crear titulacion </h2>

<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>titulacion/create_post" method="post">

			
	<div class="form-group">
		<label for="id-nombre">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" class="form-control" autofocus="">
	</div>

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Tienetit</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['curso'] as $curso ): ?>
				<?php if ( $curso->tienetit == null ): ?>
					<input class="form-check-input" type="checkbox" id="id-tienetit-<?=$curso->id?>" name="tienetit[]" value="<?= $curso->id ?>">
					<label class="form-check-label" for="id-tienetit-<?=$curso->id?>" ><?= $curso->nivel ?></label>
				<?php endif; ?> 
			<?php endforeach; ?>
		</div>
	</fieldset>


	<input type="submit" class="btn btn-primary" value="Crear">

</form>

<div id="idMessage" class="row col-sm-4">
</div>

</div>	