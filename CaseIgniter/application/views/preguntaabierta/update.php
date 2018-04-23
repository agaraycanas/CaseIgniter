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
<h2> Editar preguntaabierta </h2>
		
<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>preguntaabierta/update_post" method="post">
		
		
	<input type="hidden" name="id" value="<?= $body['preguntaabierta']->id ?>">
			

	<div class="form-group">
		<label for="id-enunciado">Enunciado</label>
		<input id="id-enunciado" type="text" name="enunciado" value="<?=  $body['preguntaabierta']->enunciado ?>" class="form-control" onkeypress="detect(event);">
	</div>
				
							

	<div class="form-group">
		<label for="id-enunciadofacil">Enunciadofacil</label>
		<input id="id-enunciadofacil" type="text" name="enunciadofacil" value="<?=  $body['preguntaabierta']->enunciadofacil ?>" class="form-control" onkeypress="detect(event);">
	</div>
				
								
	<div class="form-group">
		<label for="id-versaabierta">Versaabierta</label>
		<select id="id-versaabierta" name="versaabierta" class="form-control">
			<option value="0" <?= $body['preguntaabierta']->fetchAs('asignatura')->versaabierta == null ? 'selected="selected"' : '' ?> > ----- </option> 
		<?php foreach ($body['asignatura'] as $asignatura ): ?>
			
			<option value="<?= $asignatura->id ?>" <?= selected($body['preguntaabierta']->fetchAs('asignatura')->versaabierta, $asignatura->id ) ?>><?= $asignatura->nombre ?></option>
			
		<?php endforeach; ?>
					
		</select>
	</div>
									
	<div class="form-group">
		<label for="id-componepa">Componepa</label>
		<select id="id-componepa" name="componepa" class="form-control">
			<option value="0" <?= $body['preguntaabierta']->fetchAs('plantillaencuesta')->componepa == null ? 'selected="selected"' : '' ?> > ----- </option> 
		<?php foreach ($body['plantillaencuesta'] as $plantillaencuesta ): ?>
			
			<option value="<?= $plantillaencuesta->id ?>" <?= selected($body['preguntaabierta']->fetchAs('plantillaencuesta')->componepa, $plantillaencuesta->id ) ?>><?= $plantillaencuesta->nombre ?></option>
			
		<?php endforeach; ?>
					
		</select>
	</div>
									

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Respuestasra</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['respuestaabierta'] as $respuestaabierta ): ?>
				<?php if ( $respuestaabierta -> fetchAs('preguntaabierta') -> respuestasra == null || $respuestaabierta -> fetchAs('preguntaabierta') -> respuestasra -> id == $body['preguntaabierta']->id ): ?>
				<input class="form-check-input" type="checkbox" id="id-respuestasra-<?=$respuestaabierta->id ?>" name="respuestasra[]" value="<?= $respuestaabierta->id ?>" <?= checked($body['preguntaabierta']->alias('respuestasra')->ownRespuestaabiertaList, $respuestaabierta->id ) ?>>
				<label class="form-check-label" for="id-respuestasra-<?=$respuestaabierta->id?>" ><?= $respuestaabierta->texto ?></label>

				<?php endif; ?>
			<?php endforeach; ?>
						
		</div>
	</fieldset>

				
	<div class="form-group">
		<label for="id-contienepa">Contienepa</label>
		<select id="id-contienepa" name="contienepa" class="form-control">
			<option value="0" <?= $body['preguntaabierta']->fetchAs('categoria')->contienepa == null ? 'selected="selected"' : '' ?> > ----- </option> 
		<?php foreach ($body['categoria'] as $categoria ): ?>
			
			<option value="<?= $categoria->id ?>" <?= selected($body['preguntaabierta']->fetchAs('categoria')->contienepa, $categoria->id ) ?>><?= $categoria->nombre ?></option>
			
		<?php endforeach; ?>
					
		</select>
	</div>
					
	<input type="submit" class="btn btn-primary" value="Actualizar">
			
</form>
			
</div>