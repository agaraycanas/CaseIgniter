<?php


	function get_ids($beans) {
		$sol = [];
		foreach ($beans as $bean) {
			$sol[] = $bean -> id;
		}
		return $sol;
	}

	function selected($bean_selected, $id_to_be_tested) {
		return $bean_selected != null && $bean_selected->id == $id_to_be_tested ? 'selected="selected"' : '';
	}

	function checked($list, $id_to_be_tested) {
		return in_array($id_to_be_tested, get_ids($list) ) ? 'checked="checked"' : '';
	}
?>	
	
<div class="container">
<h2> Editar imparticion </h2>
		
<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>imparticion/update_post" method="post">
		
		
	<input type="hidden" name="id" value="<?= $body['imparticion']->id ?>">
			

	<div class="form-group">
		<label for="id-nombre">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" value="<?=  $body['imparticion']->nombre ?>" class="form-control" onkeypress="detect(event);">
	</div>
				
								
	<div class="form-group">
		<label for="id-desarrolla">Desarrolla</label>
		<select id="id-desarrolla" name="desarrolla" class="form-control">
			<option value="0" <?= $body['imparticion']->fetchAs('usuario')->desarrolla == null ? 'selected="selected"' : '' ?> > ----- </option> 
		<?php foreach ($body['usuario'] as $usuario ): ?>
			
			<option value="<?= $usuario->id ?>" <?= selected($body['imparticion']->fetchAs('usuario')->desarrolla, $usuario->id ) ?>><?= $usuario->nombre ?></option>
			
		<?php endforeach; ?>
					
		</select>
	</div>
									
	<div class="form-group">
		<label for="id-impartida">Impartida</label>
		<select id="id-impartida" name="impartida" class="form-control">
			<option value="0" <?= $body['imparticion']->fetchAs('grupo')->impartida == null ? 'selected="selected"' : '' ?> > ----- </option> 
		<?php foreach ($body['grupo'] as $grupo ): ?>
			
			<option value="<?= $grupo->id ?>" <?= selected($body['imparticion']->fetchAs('grupo')->impartida, $grupo->id ) ?>><?= $grupo->nombre ?></option>
			
		<?php endforeach; ?>
					
		</select>
	</div>
									
	<div class="form-group">
		<label for="id-refierea">Refierea</label>
		<select id="id-refierea" name="refierea" class="form-control">
			<option value="0" <?= $body['imparticion']->fetchAs('asignatura')->refierea == null ? 'selected="selected"' : '' ?> > ----- </option> 
		<?php foreach ($body['asignatura'] as $asignatura ): ?>
			
			<option value="<?= $asignatura->id ?>" <?= selected($body['imparticion']->fetchAs('asignatura')->refierea, $asignatura->id ) ?>><?= $asignatura->nombre ?></option>
			
		<?php endforeach; ?>
					
		</select>
	</div>
									

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Asociaa</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['plantillaencuesta'] as $plantillaencuesta ): ?>
				
				<input class="form-check-input" type="checkbox" id="id-asociaa-<?=$plantillaencuesta->id ?>" name="asociaa[]" value="<?= $plantillaencuesta->id ?>" <?= checked($body['imparticion']->aggr('ownAsociaaList','plantillaencuesta'), $plantillaencuesta->id ) ?>>
				<label class="form-check-label" for="id-asociaa-<?=$plantillaencuesta->id?>" ><?= $plantillaencuesta->nombre ?></label>

				
			<?php endforeach; ?>
						
		</div>
	</fieldset>

				

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Realizada</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['encuesta'] as $encuesta ): ?>
				<?php if ( $encuesta -> fetchAs('imparticion') -> realizada == null || $encuesta -> fetchAs('imparticion') -> realizada -> id == $body['imparticion']->id ): ?>
				<input class="form-check-input" type="checkbox" id="id-realizada-<?=$encuesta->id ?>" name="realizada[]" value="<?= $encuesta->id ?>" <?= checked($body['imparticion']->alias('realizada')->ownEncuestaList, $encuesta->id ) ?>>
				<label class="form-check-label" for="id-realizada-<?=$encuesta->id?>" ><?= $encuesta->fecha ?></label>

				<?php endif; ?>
			<?php endforeach; ?>
						
		</div>
	</fieldset>

				
	<div class="form-group">
		<label for="id-tieneim">Tieneim</label>
		<select id="id-tieneim" name="tieneim" class="form-control">
			<option value="0" <?= $body['imparticion']->fetchAs('cursoacademico')->tieneim == null ? 'selected="selected"' : '' ?> > ----- </option> 
		<?php foreach ($body['cursoacademico'] as $cursoacademico ): ?>
			
			<option value="<?= $cursoacademico->id ?>" <?= selected($body['imparticion']->fetchAs('cursoacademico')->tieneim, $cursoacademico->id ) ?>><?= $cursoacademico->anyoini ?></option>
			
		<?php endforeach; ?>
					
		</select>
	</div>
					
	<input type="submit" class="btn btn-primary" value="Actualizar">
			
</form>
			
</div>