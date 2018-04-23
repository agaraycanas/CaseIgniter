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
<h2> Editar asignatura </h2>
		
<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>asignatura/update_post" method="post">
		
		
	<input type="hidden" name="id" value="<?= $body['asignatura']->id ?>">
			

	<div class="form-group">
		<label for="id-nombre">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" value="<?=  $body['asignatura']->nombre ?>" class="form-control" onkeypress="detect(event);">
	</div>
				
								
	<div class="form-group">
		<label for="id-imparte">Imparte</label>
		<select id="id-imparte" name="imparte" class="form-control">
			<option value="0" <?= $body['asignatura']->fetchAs('departamento')->imparte == null ? 'selected="selected"' : '' ?> > ----- </option> 
		<?php foreach ($body['departamento'] as $departamento ): ?>
			
			<option value="<?= $departamento->id ?>" <?= selected($body['asignatura']->fetchAs('departamento')->imparte, $departamento->id ) ?>><?= $departamento->nombre ?></option>
			
		<?php endforeach; ?>
					
		</select>
	</div>
									

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Refierea</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['imparticion'] as $imparticion ): ?>
				<?php if ( $imparticion -> fetchAs('asignatura') -> refierea == null || $imparticion -> fetchAs('asignatura') -> refierea -> id == $body['asignatura']->id ): ?>
				<input class="form-check-input" type="checkbox" id="id-refierea-<?=$imparticion->id ?>" name="refierea[]" value="<?= $imparticion->id ?>" <?= checked($body['asignatura']->alias('refierea')->ownImparticionList, $imparticion->id ) ?>>
				<label class="form-check-label" for="id-refierea-<?=$imparticion->id?>" ><?= $imparticion->nombre ?></label>

				<?php endif; ?>
			<?php endforeach; ?>
						
		</div>
	</fieldset>


	<input type="submit" class="btn btn-primary" value="Actualizar">
			
</form>
			
</div>