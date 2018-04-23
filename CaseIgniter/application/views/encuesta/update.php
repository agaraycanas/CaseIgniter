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
<h2> Editar encuesta </h2>
		
<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>encuesta/update_post" method="post">
		
		
	<input type="hidden" name="id" value="<?= $body['encuesta']->id ?>">
			

	<div class="form-group">
		<label for="id-fecha">Fecha</label>
		<input id="id-fecha" type="date" name="fecha" value="<?=  $body['encuesta']->fecha ?>" class="form-control" onkeypress="detect(event);">
	</div>
				
								
	<div class="form-group">
		<label for="id-realiza">Realiza</label>
		<select id="id-realiza" name="realiza" class="form-control">
			<option value="0" <?= $body['encuesta']->fetchAs('usuario')->realiza == null ? 'selected="selected"' : '' ?> > ----- </option> 
		<?php foreach ($body['usuario'] as $usuario ): ?>
			
			<option value="<?= $usuario->id ?>" <?= selected($body['encuesta']->fetchAs('usuario')->realiza, $usuario->id ) ?>><?= $usuario->nombre ?></option>
			
		<?php endforeach; ?>
					
		</select>
	</div>
									
	<div class="form-group">
		<label for="id-realizada">Realizada</label>
		<select id="id-realizada" name="realizada" class="form-control">
			<option value="0" <?= $body['encuesta']->fetchAs('imparticion')->realizada == null ? 'selected="selected"' : '' ?> > ----- </option> 
		<?php foreach ($body['imparticion'] as $imparticion ): ?>
			
			<option value="<?= $imparticion->id ?>" <?= selected($body['encuesta']->fetchAs('imparticion')->realizada, $imparticion->id ) ?>><?= $imparticion->nombre ?></option>
			
		<?php endforeach; ?>
					
		</select>
	</div>
									
	<div class="form-group">
		<label for="id-acercade">Acercade</label>
		<select id="id-acercade" name="acercade" class="form-control">
			<option value="0" <?= $body['encuesta']->fetchAs('plantillaencuesta')->acercade == null ? 'selected="selected"' : '' ?> > ----- </option> 
		<?php foreach ($body['plantillaencuesta'] as $plantillaencuesta ): ?>
			
			<option value="<?= $plantillaencuesta->id ?>" <?= selected($body['encuesta']->fetchAs('plantillaencuesta')->acercade, $plantillaencuesta->id ) ?>><?= $plantillaencuesta->nombre ?></option>
			
		<?php endforeach; ?>
					
		</select>
	</div>
									
	<div class="form-group">
		<label for="id-responderc">Responderc</label>
		<select id="id-responderc" name="responderc" class="form-control">
			<option value="0" <?= $body['encuesta']->fetchAs('respuestacerrada')->responderc == null ? 'selected="selected"' : '' ?> > ----- </option> 
		<?php foreach ($body['respuestacerrada'] as $respuestacerrada ): ?>
			
			<option value="<?= $respuestacerrada->id ?>" <?= selected($body['encuesta']->fetchAs('respuestacerrada')->responderc, $respuestacerrada->id ) ?>><?= $respuestacerrada->numero ?></option>
			
		<?php endforeach; ?>
					
		</select>
	</div>
									

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Respondera</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['respuestaabierta'] as $respuestaabierta ): ?>
				<?php if ( $respuestaabierta -> fetchAs('encuesta') -> respondera == null || $respuestaabierta -> fetchAs('encuesta') -> respondera -> id == $body['encuesta']->id ): ?>
				<input class="form-check-input" type="checkbox" id="id-respondera-<?=$respuestaabierta->id ?>" name="respondera[]" value="<?= $respuestaabierta->id ?>" <?= checked($body['encuesta']->alias('respondera')->ownRespuestaabiertaList, $respuestaabierta->id ) ?>>
				<label class="form-check-label" for="id-respondera-<?=$respuestaabierta->id?>" ><?= $respuestaabierta->texto ?></label>

				<?php endif; ?>
			<?php endforeach; ?>
						
		</div>
	</fieldset>


	<input type="submit" class="btn btn-primary" value="Actualizar">
			
</form>
			
</div>