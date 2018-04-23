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
<h2> Editar ies </h2>
		
<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>ies/update_post" method="post">
		
		
	<input type="hidden" name="id" value="<?= $body['ies']->id ?>">
			

	<div class="form-group">
		<label for="id-nombre">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" value="<?=  $body['ies']->nombre ?>" class="form-control" onkeypress="detect(event);">
	</div>
				
								

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Ocurre</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['cursoacademico'] as $cursoacademico ): ?>
				<?php if ( $cursoacademico -> fetchAs('ies') -> ocurre == null || $cursoacademico -> fetchAs('ies') -> ocurre -> id == $body['ies']->id ): ?>
				<input class="form-check-input" type="checkbox" id="id-ocurre-<?=$cursoacademico->id ?>" name="ocurre[]" value="<?= $cursoacademico->id ?>" <?= checked($body['ies']->alias('ocurre')->ownCursoacademicoList, $cursoacademico->id ) ?>>
				<label class="form-check-label" for="id-ocurre-<?=$cursoacademico->id?>" ><?= $cursoacademico->anyoini ?></label>

				<?php endif; ?>
			<?php endforeach; ?>
						
		</div>
	</fieldset>


	<input type="submit" class="btn btn-primary" value="Actualizar">
			
</form>
			
</div>