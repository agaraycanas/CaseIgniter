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
<h2> Editar curso </h2>
		
<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>curso/update_post" method="post">
		
		
	<input type="hidden" name="id" value="<?= $body['curso']->id ?>">
			

	<div class="form-group">
		<label for="id-nivel">Nivel</label>
		<input id="id-nivel" type="number" name="nivel" value="<?=  $body['curso']->nivel ?>" class="form-control" onkeypress="detect(event);">
	</div>
				
								

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Pertenece</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['grupo'] as $grupo ): ?>
				<?php if ( $grupo -> fetchAs('curso') -> pertenece == null || $grupo -> fetchAs('curso') -> pertenece -> id == $body['curso']->id ): ?>
				<input class="form-check-input" type="checkbox" id="id-pertenece-<?=$grupo->id ?>" name="pertenece[]" value="<?= $grupo->id ?>" <?= checked($body['curso']->alias('pertenece')->ownGrupoList, $grupo->id ) ?>>
				<label class="form-check-label" for="id-pertenece-<?=$grupo->id?>" ><?= $grupo->nombre ?></label>

				<?php endif; ?>
			<?php endforeach; ?>
						
		</div>
	</fieldset>

				
	<div class="form-group">
		<label for="id-tienetit">Tienetit</label>
		<select id="id-tienetit" name="tienetit" class="form-control">
			<option value="0" <?= $body['curso']->fetchAs('titulacion')->tienetit == null ? 'selected="selected"' : '' ?> > ----- </option> 
		<?php foreach ($body['titulacion'] as $titulacion ): ?>
			
			<option value="<?= $titulacion->id ?>" <?= selected($body['curso']->fetchAs('titulacion')->tienetit, $titulacion->id ) ?>><?= $titulacion->nombre ?></option>
			
		<?php endforeach; ?>
					
		</select>
	</div>
					
	<input type="submit" class="btn btn-primary" value="Actualizar">
			
</form>
			
</div>