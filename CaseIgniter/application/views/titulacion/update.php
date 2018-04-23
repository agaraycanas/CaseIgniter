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
<h2> Editar titulacion </h2>
		
<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>titulacion/update_post" method="post">
		
		
	<input type="hidden" name="id" value="<?= $body['titulacion']->id ?>">
			

	<div class="form-group">
		<label for="id-nombre">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" value="<?=  $body['titulacion']->nombre ?>" class="form-control" onkeypress="detect(event);">
	</div>
				
								

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Tienetit</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['curso'] as $curso ): ?>
				<?php if ( $curso -> fetchAs('titulacion') -> tienetit == null || $curso -> fetchAs('titulacion') -> tienetit -> id == $body['titulacion']->id ): ?>
				<input class="form-check-input" type="checkbox" id="id-tienetit-<?=$curso->id ?>" name="tienetit[]" value="<?= $curso->id ?>" <?= checked($body['titulacion']->alias('tienetit')->ownCursoList, $curso->id ) ?>>
				<label class="form-check-label" for="id-tienetit-<?=$curso->id?>" ><?= $curso->nivel ?></label>

				<?php endif; ?>
			<?php endforeach; ?>
						
		</div>
	</fieldset>


	<input type="submit" class="btn btn-primary" value="Actualizar">
			
</form>
			
</div>