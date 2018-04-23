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
<h2> Editar cursoacademico </h2>
		
<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>cursoacademico/update_post" method="post">
		
		
	<input type="hidden" name="id" value="<?= $body['cursoacademico']->id ?>">
			

	<div class="form-group">
		<label for="id-anyoini">Anyoini</label>
		<input id="id-anyoini" type="number" name="anyoini" value="<?=  $body['cursoacademico']->anyoini ?>" class="form-control" onkeypress="detect(event);">
	</div>
				
								
	<div class="form-group">
		<label for="id-ocurre">Ocurre</label>
		<select id="id-ocurre" name="ocurre" class="form-control">
			<option value="0" <?= $body['cursoacademico']->fetchAs('ies')->ocurre == null ? 'selected="selected"' : '' ?> > ----- </option> 
		<?php foreach ($body['ies'] as $ies ): ?>
			
			<option value="<?= $ies->id ?>" <?= selected($body['cursoacademico']->fetchAs('ies')->ocurre, $ies->id ) ?>><?= $ies->nombre ?></option>
			
		<?php endforeach; ?>
					
		</select>
	</div>
									

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Tieneim</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['imparticion'] as $imparticion ): ?>
				<?php if ( $imparticion -> fetchAs('cursoacademico') -> tieneim == null || $imparticion -> fetchAs('cursoacademico') -> tieneim -> id == $body['cursoacademico']->id ): ?>
				<input class="form-check-input" type="checkbox" id="id-tieneim-<?=$imparticion->id ?>" name="tieneim[]" value="<?= $imparticion->id ?>" <?= checked($body['cursoacademico']->alias('tieneim')->ownImparticionList, $imparticion->id ) ?>>
				<label class="form-check-label" for="id-tieneim-<?=$imparticion->id?>" ><?= $imparticion->nombre ?></label>

				<?php endif; ?>
			<?php endforeach; ?>
						
		</div>
	</fieldset>

				

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Trabaja</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['usuario'] as $usuario ): ?>
				
				<input class="form-check-input" type="checkbox" id="id-trabaja-<?=$usuario->id ?>" name="trabaja[]" value="<?= $usuario->id ?>" <?= checked($body['cursoacademico']->aggr('ownTrabajaList','usuario'), $usuario->id ) ?>>
				<label class="form-check-label" for="id-trabaja-<?=$usuario->id?>" ><?= $usuario->nombre ?></label>

				
			<?php endforeach; ?>
						
		</div>
	</fieldset>


	<input type="submit" class="btn btn-primary" value="Actualizar">
			
</form>
			
</div>