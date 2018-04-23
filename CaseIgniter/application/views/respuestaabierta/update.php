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
<h2> Editar respuestaabierta </h2>
		
<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>respuestaabierta/update_post" method="post">
		
		
	<input type="hidden" name="id" value="<?= $body['respuestaabierta']->id ?>">
			

	<div class="form-group">
		<label for="id-texto">Texto</label>
		<input id="id-texto" type="text" name="texto" value="<?=  $body['respuestaabierta']->texto ?>" class="form-control" onkeypress="detect(event);">
	</div>
				
								
	<div class="form-group">
		<label for="id-respondera">Respondera</label>
		<select id="id-respondera" name="respondera" class="form-control">
			<option value="0" <?= $body['respuestaabierta']->fetchAs('encuesta')->respondera == null ? 'selected="selected"' : '' ?> > ----- </option> 
		<?php foreach ($body['encuesta'] as $encuesta ): ?>
			
			<option value="<?= $encuesta->id ?>" <?= selected($body['respuestaabierta']->fetchAs('encuesta')->respondera, $encuesta->id ) ?>><?= $encuesta->fecha ?></option>
			
		<?php endforeach; ?>
					
		</select>
	</div>
									
	<div class="form-group">
		<label for="id-respuestasra">Respuestasra</label>
		<select id="id-respuestasra" name="respuestasra" class="form-control">
			<option value="0" <?= $body['respuestaabierta']->fetchAs('preguntaabierta')->respuestasra == null ? 'selected="selected"' : '' ?> > ----- </option> 
		<?php foreach ($body['preguntaabierta'] as $preguntaabierta ): ?>
			
			<option value="<?= $preguntaabierta->id ?>" <?= selected($body['respuestaabierta']->fetchAs('preguntaabierta')->respuestasra, $preguntaabierta->id ) ?>><?= $preguntaabierta->enunciado ?></option>
			
		<?php endforeach; ?>
					
		</select>
	</div>
					
	<input type="submit" class="btn btn-primary" value="Actualizar">
			
</form>
			
</div>