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
<h2> Editar grupo </h2>
		
<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>grupo/update_post" method="post">
		
		
	<input type="hidden" name="id" value="<?= $body['grupo']->id ?>">
			

	<div class="form-group">
		<label for="id-nombre">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" value="<?=  $body['grupo']->nombre ?>" class="form-control" onkeypress="detect(event);">
	</div>
				
								

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Grreferencia</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['usuario'] as $usuario ): ?>
				<?php if ( $usuario -> fetchAs('grupo') -> grreferencia == null || $usuario -> fetchAs('grupo') -> grreferencia -> id == $body['grupo']->id ): ?>
				<input class="form-check-input" type="checkbox" id="id-grreferencia-<?=$usuario->id ?>" name="grreferencia[]" value="<?= $usuario->id ?>" <?= checked($body['grupo']->alias('grreferencia')->ownUsuarioList, $usuario->id ) ?>>
				<label class="form-check-label" for="id-grreferencia-<?=$usuario->id?>" ><?= $usuario->nombre ?></label>

				<?php endif; ?>
			<?php endforeach; ?>
						
		</div>
	</fieldset>

				

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Impartida</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['imparticion'] as $imparticion ): ?>
				<?php if ( $imparticion -> fetchAs('grupo') -> impartida == null || $imparticion -> fetchAs('grupo') -> impartida -> id == $body['grupo']->id ): ?>
				<input class="form-check-input" type="checkbox" id="id-impartida-<?=$imparticion->id ?>" name="impartida[]" value="<?= $imparticion->id ?>" <?= checked($body['grupo']->alias('impartida')->ownImparticionList, $imparticion->id ) ?>>
				<label class="form-check-label" for="id-impartida-<?=$imparticion->id?>" ><?= $imparticion->nombre ?></label>

				<?php endif; ?>
			<?php endforeach; ?>
						
		</div>
	</fieldset>

				
	<div class="form-group">
		<label for="id-pertenece">Pertenece</label>
		<select id="id-pertenece" name="pertenece" class="form-control">
			<option value="0" <?= $body['grupo']->fetchAs('curso')->pertenece == null ? 'selected="selected"' : '' ?> > ----- </option> 
		<?php foreach ($body['curso'] as $curso ): ?>
			
			<option value="<?= $curso->id ?>" <?= selected($body['grupo']->fetchAs('curso')->pertenece, $curso->id ) ?>><?= $curso->nivel ?></option>
			
		<?php endforeach; ?>
					
		</select>
	</div>
					
	<input type="submit" class="btn btn-primary" value="Actualizar">
			
</form>
			
</div>