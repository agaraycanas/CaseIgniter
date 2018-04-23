

<div class="container">
<h2> Crear categoria </h2>

<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>categoria/create_post" method="post">

			
	<div class="form-group">
		<label for="id-nombre">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" class="form-control" autofocus="">
	</div>

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Contienepc</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['preguntacerrada'] as $preguntacerrada ): ?>
				<?php if ( $preguntacerrada->contienepc == null ): ?>
					<input class="form-check-input" type="checkbox" id="id-contienepc-<?=$preguntacerrada->id?>" name="contienepc[]" value="<?= $preguntacerrada->id ?>">
					<label class="form-check-label" for="id-contienepc-<?=$preguntacerrada->id?>" ><?= $preguntacerrada->nombre ?></label>
				<?php endif; ?> 
			<?php endforeach; ?>
		</div>
	</fieldset>

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Contienepa</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['preguntaabierta'] as $preguntaabierta ): ?>
				<?php if ( $preguntaabierta->contienepa == null ): ?>
					<input class="form-check-input" type="checkbox" id="id-contienepa-<?=$preguntaabierta->id?>" name="contienepa[]" value="<?= $preguntaabierta->id ?>">
					<label class="form-check-label" for="id-contienepa-<?=$preguntaabierta->id?>" ><?= $preguntaabierta->enunciado ?></label>
				<?php endif; ?> 
			<?php endforeach; ?>
		</div>
	</fieldset>


	<input type="submit" class="btn btn-primary" value="Crear">

</form>

<div id="idMessage" class="row col-sm-4">
</div>

</div>	