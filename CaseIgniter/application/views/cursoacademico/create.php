

<div class="container">
<h2> Crear cursoacademico </h2>

<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>cursoacademico/create_post" method="post">

			
	<div class="form-group">
		<label for="id-anyoini">Anyoini</label>
		<input id="id-anyoini" type="number" name="anyoini" class="form-control" autofocus="">
	</div>


	<div class="form-group">
		<label for="id-ocurre">Ocurre</label>
		<select id="id-ocurre" name="ocurre" class="form-control">
			<option value="0"> ----- </option>
			<?php foreach ($body['ies'] as $ies ): ?>
				
					<option value="<?= $ies->id ?>"><?= $ies->nombre ?></option>
				
			<?php endforeach; ?>
		</select>
	</div>

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Tieneim</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['imparticion'] as $imparticion ): ?>
				<?php if ( $imparticion->tieneim == null ): ?>
					<input class="form-check-input" type="checkbox" id="id-tieneim-<?=$imparticion->id?>" name="tieneim[]" value="<?= $imparticion->id ?>">
					<label class="form-check-label" for="id-tieneim-<?=$imparticion->id?>" ><?= $imparticion->nombre ?></label>
				<?php endif; ?> 
			<?php endforeach; ?>
		</div>
	</fieldset>

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Trabaja</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['usuario'] as $usuario ): ?>
				
					<input class="form-check-input" type="checkbox" id="id-trabaja-<?=$usuario->id?>" name="trabaja[]" value="<?= $usuario->id ?>">
					<label class="form-check-label" for="id-trabaja-<?=$usuario->id?>" ><?= $usuario->nombre ?></label>
				
			<?php endforeach; ?>
		</div>
	</fieldset>


	<input type="submit" class="btn btn-primary" value="Crear">

</form>

<div id="idMessage" class="row col-sm-4">
</div>

</div>	