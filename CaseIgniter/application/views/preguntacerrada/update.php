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
<h2> Editar preguntacerrada </h2>
		
<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>preguntacerrada/update_post" method="post">
		
		
	<input type="hidden" name="id" value="<?= $body['preguntacerrada']->id ?>">
			

	<div class="form-group">
		<label for="id-nombre">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" value="<?=  $body['preguntacerrada']->nombre ?>" class="form-control" onkeypress="detect(event);">
	</div>
				
							

	<div class="form-group">
		<label for="id-enunciado">Enunciado</label>
		<input id="id-enunciado" type="text" name="enunciado" value="<?=  $body['preguntacerrada']->enunciado ?>" class="form-control" onkeypress="detect(event);">
	</div>
				
							

	<div class="form-group">
		<label for="id-enunciadofacil">Enunciadofacil</label>
		<input id="id-enunciadofacil" type="text" name="enunciadofacil" value="<?=  $body['preguntacerrada']->enunciadofacil ?>" class="form-control" onkeypress="detect(event);">
	</div>
				
							

	<div class="form-group">
		<label for="id-min">Min</label>
		<input id="id-min" type="number" name="min" value="<?=  $body['preguntacerrada']->min ?>" class="form-control" onkeypress="detect(event);">
	</div>
				
							

	<div class="form-group">
		<label for="id-max">Max</label>
		<input id="id-max" type="number" name="max" value="<?=  $body['preguntacerrada']->max ?>" class="form-control" onkeypress="detect(event);">
	</div>
				
								
	<div class="form-group">
		<label for="id-versacerrada">Versacerrada</label>
		<select id="id-versacerrada" name="versacerrada" class="form-control">
			<option value="0" <?= $body['preguntacerrada']->fetchAs('asignatura')->versacerrada == null ? 'selected="selected"' : '' ?> > ----- </option> 
		<?php foreach ($body['asignatura'] as $asignatura ): ?>
			
			<option value="<?= $asignatura->id ?>" <?= selected($body['preguntacerrada']->fetchAs('asignatura')->versacerrada, $asignatura->id ) ?>><?= $asignatura->nombre ?></option>
			
		<?php endforeach; ?>
					
		</select>
	</div>
									
	<div class="form-group">
		<label for="id-componepc">Componepc</label>
		<select id="id-componepc" name="componepc" class="form-control">
			<option value="0" <?= $body['preguntacerrada']->fetchAs('plantillaencuesta')->componepc == null ? 'selected="selected"' : '' ?> > ----- </option> 
		<?php foreach ($body['plantillaencuesta'] as $plantillaencuesta ): ?>
			
			<option value="<?= $plantillaencuesta->id ?>" <?= selected($body['preguntacerrada']->fetchAs('plantillaencuesta')->componepc, $plantillaencuesta->id ) ?>><?= $plantillaencuesta->nombre ?></option>
			
		<?php endforeach; ?>
					
		</select>
	</div>
									
	<div class="form-group">
		<label for="id-respuestasrc">Respuestasrc</label>
		<select id="id-respuestasrc" name="respuestasrc" class="form-control">
			<option value="0" <?= $body['preguntacerrada']->fetchAs('respuestacerrada')->respuestasrc == null ? 'selected="selected"' : '' ?> > ----- </option> 
		<?php foreach ($body['respuestacerrada'] as $respuestacerrada ): ?>
			
			<option value="<?= $respuestacerrada->id ?>" <?= selected($body['preguntacerrada']->fetchAs('respuestacerrada')->respuestasrc, $respuestacerrada->id ) ?>><?= $respuestacerrada->numero ?></option>
			
		<?php endforeach; ?>
					
		</select>
	</div>
									
	<div class="form-group">
		<label for="id-contienepc">Contienepc</label>
		<select id="id-contienepc" name="contienepc" class="form-control">
			<option value="0" <?= $body['preguntacerrada']->fetchAs('categoria')->contienepc == null ? 'selected="selected"' : '' ?> > ----- </option> 
		<?php foreach ($body['categoria'] as $categoria ): ?>
			
			<option value="<?= $categoria->id ?>" <?= selected($body['preguntacerrada']->fetchAs('categoria')->contienepc, $categoria->id ) ?>><?= $categoria->nombre ?></option>
			
		<?php endforeach; ?>
					
		</select>
	</div>
					
	<input type="submit" class="btn btn-primary" value="Actualizar">
			
</form>
			
</div>