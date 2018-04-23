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
<h2> Editar respuestacerrada </h2>
		
<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>respuestacerrada/update_post" method="post">
		
		
	<input type="hidden" name="id" value="<?= $body['respuestacerrada']->id ?>">
			

	<div class="form-group">
		<label for="id-numero">Numero</label>
		<input id="id-numero" type="number" name="numero" value="<?=  $body['respuestacerrada']->numero ?>" class="form-control" onkeypress="detect(event);">
	</div>
				
								
	<div class="form-group">
		<label for="id-responderc">Responderc</label>
		<select id="id-responderc" name="responderc" class="form-control">
			<option value="0" <?= $body['respuestacerrada']->fetchAs('encuesta')->responderc == null ? 'selected="selected"' : '' ?> > ----- </option> 
		<?php foreach ($body['encuesta'] as $encuesta ): ?>
			
			<option value="<?= $encuesta->id ?>" <?= selected($body['respuestacerrada']->fetchAs('encuesta')->responderc, $encuesta->id ) ?>><?= $encuesta->fecha ?></option>
			
		<?php endforeach; ?>
					
		</select>
	</div>
									
	<div class="form-group">
		<label for="id-respuestasrc">Respuestasrc</label>
		<select id="id-respuestasrc" name="respuestasrc" class="form-control">
			<option value="0" <?= $body['respuestacerrada']->fetchAs('preguntacerrada')->respuestasrc == null ? 'selected="selected"' : '' ?> > ----- </option> 
		<?php foreach ($body['preguntacerrada'] as $preguntacerrada ): ?>
			
			<option value="<?= $preguntacerrada->id ?>" <?= selected($body['respuestacerrada']->fetchAs('preguntacerrada')->respuestasrc, $preguntacerrada->id ) ?>><?= $preguntacerrada->nombre ?></option>
			
		<?php endforeach; ?>
					
		</select>
	</div>
					
	<input type="submit" class="btn btn-primary" value="Actualizar">
			
</form>
			
</div>