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
<h2> Editar departamento </h2>
		
<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>departamento/update_post" method="post">
		
		
	<input type="hidden" name="id" value="<?= $body['departamento']->id ?>">
			

	<div class="form-group">
		<label for="id-nombre">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" value="<?=  $body['departamento']->nombre ?>" class="form-control" onkeypress="detect(event);">
	</div>
				
								

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Pertenece</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['usuario'] as $usuario ): ?>
				
				<input class="form-check-input" type="checkbox" id="id-pertenece-<?=$usuario->id ?>" name="pertenece[]" value="<?= $usuario->id ?>" <?= checked($body['departamento']->aggr('ownPerteneceList','usuario'), $usuario->id ) ?>>
				<label class="form-check-label" for="id-pertenece-<?=$usuario->id?>" ><?= $usuario->nombre ?></label>

				
			<?php endforeach; ?>
						
		</div>
	</fieldset>

				

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Imparte</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['asignatura'] as $asignatura ): ?>
				<?php if ( $asignatura -> fetchAs('departamento') -> imparte == null || $asignatura -> fetchAs('departamento') -> imparte -> id == $body['departamento']->id ): ?>
				<input class="form-check-input" type="checkbox" id="id-imparte-<?=$asignatura->id ?>" name="imparte[]" value="<?= $asignatura->id ?>" <?= checked($body['departamento']->alias('imparte')->ownAsignaturaList, $asignatura->id ) ?>>
				<label class="form-check-label" for="id-imparte-<?=$asignatura->id?>" ><?= $asignatura->nombre ?></label>

				<?php endif; ?>
			<?php endforeach; ?>
						
		</div>
	</fieldset>

				

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Gestiona</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['titulacion'] as $titulacion ): ?>
				
				<input class="form-check-input" type="checkbox" id="id-gestiona-<?=$titulacion->id ?>" name="gestiona[]" value="<?= $titulacion->id ?>" <?= checked($body['departamento']->aggr('ownGestionaList','titulacion'), $titulacion->id ) ?>>
				<label class="form-check-label" for="id-gestiona-<?=$titulacion->id?>" ><?= $titulacion->nombre ?></label>

				
			<?php endforeach; ?>
						
		</div>
	</fieldset>


	<input type="submit" class="btn btn-primary" value="Actualizar">
			
</form>
			
</div>