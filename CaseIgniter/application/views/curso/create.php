

<div class="container">
<h2> Crear curso </h2>

<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>curso/create_post" method="post">

			
	<div class="form-group">
		<label for="id-nivel">Nivel</label>
		<input id="id-nivel" type="number" name="nivel" class="form-control" autofocus="">
	</div>

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Pertenece</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['grupo'] as $grupo ): ?>
				<?php if ( $grupo->pertenece == null ): ?>
					<input class="form-check-input" type="checkbox" id="id-pertenece-<?=$grupo->id?>" name="pertenece[]" value="<?= $grupo->id ?>">
					<label class="form-check-label" for="id-pertenece-<?=$grupo->id?>" ><?= $grupo->nombre ?></label>
				<?php endif; ?> 
			<?php endforeach; ?>
		</div>
	</fieldset>


	<div class="form-group">
		<label for="id-tienetit">Tienetit</label>
		<select id="id-tienetit" name="tienetit" class="form-control">
			<option value="0"> ----- </option>
			<?php foreach ($body['titulacion'] as $titulacion ): ?>
				
					<option value="<?= $titulacion->id ?>"><?= $titulacion->nombre ?></option>
				
			<?php endforeach; ?>
		</select>
	</div>


	<input type="submit" class="btn btn-primary" value="Crear">

</form>

<div id="idMessage" class="row col-sm-4">
</div>

</div>	