

<div class="container">
<h2> Crear ies </h2>

<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>ies/create_post" method="post">

			
	<div class="form-group">
		<label for="id-nombre">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" class="form-control" autofocus="">
	</div>

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Ocurre</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['cursoacademico'] as $cursoacademico ): ?>
				<?php if ( $cursoacademico->ocurre == null ): ?>
					<input class="form-check-input" type="checkbox" id="id-ocurre-<?=$cursoacademico->id?>" name="ocurre[]" value="<?= $cursoacademico->id ?>">
					<label class="form-check-label" for="id-ocurre-<?=$cursoacademico->id?>" ><?= $cursoacademico->anyoini ?></label>
				<?php endif; ?> 
			<?php endforeach; ?>
		</div>
	</fieldset>


	<input type="submit" class="btn btn-primary" value="Crear">

</form>

<div id="idMessage" class="row col-sm-4">
</div>

</div>	