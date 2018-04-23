

<div class="container">
<h2> Crear grupo </h2>

<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>grupo/create_post" method="post">

			
	<div class="form-group">
		<label for="id-nombre">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" class="form-control" autofocus="">
	</div>

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Grreferencia</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['usuario'] as $usuario ): ?>
				<?php if ( $usuario->grreferencia == null ): ?>
					<input class="form-check-input" type="checkbox" id="id-grreferencia-<?=$usuario->id?>" name="grreferencia[]" value="<?= $usuario->id ?>">
					<label class="form-check-label" for="id-grreferencia-<?=$usuario->id?>" ><?= $usuario->nombre ?></label>
				<?php endif; ?> 
			<?php endforeach; ?>
		</div>
	</fieldset>

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Impartida</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['imparticion'] as $imparticion ): ?>
				<?php if ( $imparticion->impartida == null ): ?>
					<input class="form-check-input" type="checkbox" id="id-impartida-<?=$imparticion->id?>" name="impartida[]" value="<?= $imparticion->id ?>">
					<label class="form-check-label" for="id-impartida-<?=$imparticion->id?>" ><?= $imparticion->nombre ?></label>
				<?php endif; ?> 
			<?php endforeach; ?>
		</div>
	</fieldset>


	<div class="form-group">
		<label for="id-pertenece">Pertenece</label>
		<select id="id-pertenece" name="pertenece" class="form-control">
			<option value="0"> ----- </option>
			<?php foreach ($body['curso'] as $curso ): ?>
				
					<option value="<?= $curso->id ?>"><?= $curso->nivel ?></option>
				
			<?php endforeach; ?>
		</select>
	</div>


	<input type="submit" class="btn btn-primary" value="Crear">

</form>

<div id="idMessage" class="row col-sm-4">
</div>

</div>	