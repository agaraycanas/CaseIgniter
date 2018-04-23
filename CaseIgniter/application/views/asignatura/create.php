

<div class="container">
<h2> Crear asignatura </h2>

<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>asignatura/create_post" method="post">

			
	<div class="form-group">
		<label for="id-nombre">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" class="form-control" autofocus="">
	</div>


	<div class="form-group">
		<label for="id-imparte">Imparte</label>
		<select id="id-imparte" name="imparte" class="form-control">
			<option value="0"> ----- </option>
			<?php foreach ($body['departamento'] as $departamento ): ?>
				
					<option value="<?= $departamento->id ?>"><?= $departamento->nombre ?></option>
				
			<?php endforeach; ?>
		</select>
	</div>

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Refierea</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['imparticion'] as $imparticion ): ?>
				<?php if ( $imparticion->refierea == null ): ?>
					<input class="form-check-input" type="checkbox" id="id-refierea-<?=$imparticion->id?>" name="refierea[]" value="<?= $imparticion->id ?>">
					<label class="form-check-label" for="id-refierea-<?=$imparticion->id?>" ><?= $imparticion->nombre ?></label>
				<?php endif; ?> 
			<?php endforeach; ?>
		</div>
	</fieldset>


	<input type="submit" class="btn btn-primary" value="Crear">

</form>

<div id="idMessage" class="row col-sm-4">
</div>

</div>	