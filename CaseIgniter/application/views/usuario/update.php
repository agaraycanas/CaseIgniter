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
<h2> Editar usuario </h2>
		
<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>usuario/update_post" method="post">
		
		
	<input type="hidden" name="id" value="<?= $body['usuario']->id ?>">
			

	<div class="form-group">
		<label for="id-nombre">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" value="<?=  $body['usuario']->nombre ?>" class="form-control" onkeypress="detect(event);">
	</div>
				
							

	<div class="form-group">
		<label for="id-pwd">Pwd</label>
		<input id="id-pwd" type="text" name="pwd" value="<?=  $body['usuario']->pwd ?>" class="form-control" onkeypress="detect(event);">
	</div>
				
							

	<div class="form-group">
		<label for="id-rol">Rol</label>
		<input id="id-rol" type="text" name="rol" value="<?=  $body['usuario']->rol ?>" class="form-control" onkeypress="detect(event);">
	</div>
				
								

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Trabaja</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['cursoacademico'] as $cursoacademico ): ?>
				
				<input class="form-check-input" type="checkbox" id="id-trabaja-<?=$cursoacademico->id ?>" name="trabaja[]" value="<?= $cursoacademico->id ?>" <?= checked($body['usuario']->aggr('ownTrabajaList','cursoacademico'), $cursoacademico->id ) ?>>
				<label class="form-check-label" for="id-trabaja-<?=$cursoacademico->id?>" ><?= $cursoacademico->anyoini ?></label>

				
			<?php endforeach; ?>
						
		</div>
	</fieldset>

				

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Pertenece</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['departamento'] as $departamento ): ?>
				
				<input class="form-check-input" type="checkbox" id="id-pertenece-<?=$departamento->id ?>" name="pertenece[]" value="<?= $departamento->id ?>" <?= checked($body['usuario']->aggr('ownPerteneceList','departamento'), $departamento->id ) ?>>
				<label class="form-check-label" for="id-pertenece-<?=$departamento->id?>" ><?= $departamento->nombre ?></label>

				
			<?php endforeach; ?>
						
		</div>
	</fieldset>

				

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Realiza</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['encuesta'] as $encuesta ): ?>
				<?php if ( $encuesta -> fetchAs('usuario') -> realiza == null || $encuesta -> fetchAs('usuario') -> realiza -> id == $body['usuario']->id ): ?>
				<input class="form-check-input" type="checkbox" id="id-realiza-<?=$encuesta->id ?>" name="realiza[]" value="<?= $encuesta->id ?>" <?= checked($body['usuario']->alias('realiza')->ownEncuestaList, $encuesta->id ) ?>>
				<label class="form-check-label" for="id-realiza-<?=$encuesta->id?>" ><?= $encuesta->fecha ?></label>

				<?php endif; ?>
			<?php endforeach; ?>
						
		</div>
	</fieldset>

				

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Desarrolla</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['imparticion'] as $imparticion ): ?>
				<?php if ( $imparticion -> fetchAs('usuario') -> desarrolla == null || $imparticion -> fetchAs('usuario') -> desarrolla -> id == $body['usuario']->id ): ?>
				<input class="form-check-input" type="checkbox" id="id-desarrolla-<?=$imparticion->id ?>" name="desarrolla[]" value="<?= $imparticion->id ?>" <?= checked($body['usuario']->alias('desarrolla')->ownImparticionList, $imparticion->id ) ?>>
				<label class="form-check-label" for="id-desarrolla-<?=$imparticion->id?>" ><?= $imparticion->nombre ?></label>

				<?php endif; ?>
			<?php endforeach; ?>
						
		</div>
	</fieldset>

				
	<div class="form-group">
		<label for="id-grreferencia">Grreferencia</label>
		<select id="id-grreferencia" name="grreferencia" class="form-control">
			<option value="0" <?= $body['usuario']->fetchAs('grupo')->grreferencia == null ? 'selected="selected"' : '' ?> > ----- </option> 
		<?php foreach ($body['grupo'] as $grupo ): ?>
			
			<option value="<?= $grupo->id ?>" <?= selected($body['usuario']->fetchAs('grupo')->grreferencia, $grupo->id ) ?>><?= $grupo->nombre ?></option>
			
		<?php endforeach; ?>
					
		</select>
	</div>
					
	<input type="submit" class="btn btn-primary" value="Actualizar">
			
</form>
			
</div>