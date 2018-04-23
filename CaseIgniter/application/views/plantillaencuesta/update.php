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
<h2> Editar plantillaencuesta </h2>
		
<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>plantillaencuesta/update_post" method="post">
		
		
	<input type="hidden" name="id" value="<?= $body['plantillaencuesta']->id ?>">
			

	<div class="form-group">
		<label for="id-nombre">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" value="<?=  $body['plantillaencuesta']->nombre ?>" class="form-control" onkeypress="detect(event);">
	</div>
				
								

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Asociaa</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['imparticion'] as $imparticion ): ?>
				
				<input class="form-check-input" type="checkbox" id="id-asociaa-<?=$imparticion->id ?>" name="asociaa[]" value="<?= $imparticion->id ?>" <?= checked($body['plantillaencuesta']->aggr('ownAsociaaList','imparticion'), $imparticion->id ) ?>>
				<label class="form-check-label" for="id-asociaa-<?=$imparticion->id?>" ><?= $imparticion->nombre ?></label>

				
			<?php endforeach; ?>
						
		</div>
	</fieldset>

				

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Componepc</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['preguntacerrada'] as $preguntacerrada ): ?>
				<?php if ( $preguntacerrada -> fetchAs('plantillaencuesta') -> componepc == null || $preguntacerrada -> fetchAs('plantillaencuesta') -> componepc -> id == $body['plantillaencuesta']->id ): ?>
				<input class="form-check-input" type="checkbox" id="id-componepc-<?=$preguntacerrada->id ?>" name="componepc[]" value="<?= $preguntacerrada->id ?>" <?= checked($body['plantillaencuesta']->alias('componepc')->ownPreguntacerradaList, $preguntacerrada->id ) ?>>
				<label class="form-check-label" for="id-componepc-<?=$preguntacerrada->id?>" ><?= $preguntacerrada->nombre ?></label>

				<?php endif; ?>
			<?php endforeach; ?>
						
		</div>
	</fieldset>

				

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Componepa</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['preguntaabierta'] as $preguntaabierta ): ?>
				<?php if ( $preguntaabierta -> fetchAs('plantillaencuesta') -> componepa == null || $preguntaabierta -> fetchAs('plantillaencuesta') -> componepa -> id == $body['plantillaencuesta']->id ): ?>
				<input class="form-check-input" type="checkbox" id="id-componepa-<?=$preguntaabierta->id ?>" name="componepa[]" value="<?= $preguntaabierta->id ?>" <?= checked($body['plantillaencuesta']->alias('componepa')->ownPreguntaabiertaList, $preguntaabierta->id ) ?>>
				<label class="form-check-label" for="id-componepa-<?=$preguntaabierta->id?>" ><?= $preguntaabierta->enunciado ?></label>

				<?php endif; ?>
			<?php endforeach; ?>
						
		</div>
	</fieldset>

				

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Acercade</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['encuesta'] as $encuesta ): ?>
				<?php if ( $encuesta -> fetchAs('plantillaencuesta') -> acercade == null || $encuesta -> fetchAs('plantillaencuesta') -> acercade -> id == $body['plantillaencuesta']->id ): ?>
				<input class="form-check-input" type="checkbox" id="id-acercade-<?=$encuesta->id ?>" name="acercade[]" value="<?= $encuesta->id ?>" <?= checked($body['plantillaencuesta']->alias('acercade')->ownEncuestaList, $encuesta->id ) ?>>
				<label class="form-check-label" for="id-acercade-<?=$encuesta->id?>" ><?= $encuesta->fecha ?></label>

				<?php endif; ?>
			<?php endforeach; ?>
						
		</div>
	</fieldset>


	<input type="submit" class="btn btn-primary" value="Actualizar">
			
</form>
			
</div>