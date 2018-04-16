<?php

	function get_ids($beans) {
		$sol = [];
		foreach ($beans as $bean) {
			$sol[] = $bean -> id;
		}
		return $sol;
	}

	function selected($id_selected, $id_to_be_tested) {
		return $id_selected == $id_to_be_tested ? 'selected="selected"' : '';
	}

	function checked($list, $id_to_be_tested) {
		return in_array($id_to_be_tested, get_ids($list) ) ? 'checked="checked"' : '';
	}
?>	
	
<div class="container">
<h2> Editar persona </h2>
		
<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>persona/update_post" method="post">
		
		
	<input type="hidden" name="id" value="<?= $body['persona']->id ?>">

	<div class="form-group">
		<label for="id-nombre">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" value="<?=  $body['persona']->nombre ?>" class="form-control" onkeypress="detect(event);">
	</div>
				
				
	<div class="form-group">
		<label for="id-fechanacimiento">Fechanacimiento</label>
		<input id="id-fechanacimiento" type="date" name="fechanacimiento" value="<?=  $body['persona']->fechanacimiento ?>" class="form-control" onkeypress="detect(event);">
	</div>
				
				
	<div class="form-group">
		<label for="id-peso">Peso</label>
		<input id="id-peso" type="number" name="peso" value="<?=  $body['persona']->peso ?>" class="form-control" onkeypress="detect(event);">
	</div>
				
								
	<div class="form-group">
		<label for="id-amo">Amo</label>
		<select id="id-amo" name="amo" class="form-control">
		<?php foreach ($body['mascota'] as $mascota ): ?>
			<option value="<?= $mascota->id ?>" <?= selected($body['persona']->fetchAs('mascota')->amo->id, $mascota->id ) ?>><?= $mascota->nombre ?></option>
		<?php endforeach; ?>
					
		</select>
	</div>
									
	<div class="form-group">
		<label for="id-paisnacimiento">Paisnacimiento</label>
		<select id="id-paisnacimiento" name="paisnacimiento" class="form-control">
		<?php foreach ($body['pais'] as $pais ): ?>
			<option value="<?= $pais->id ?>" <?= selected($body['persona']->fetchAs('pais')->paisnacimiento->id, $pais->id ) ?>><?= $pais->nombre ?></option>
		<?php endforeach; ?>
					
		</select>
	</div>
									

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Gusta</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['aficion'] as $aficion ): ?>
				<input class="form-check-input" type="checkbox" id="id-gusta-<?=$aficion->id ?>" name="gusta[]" value="<?= $aficion->id ?>" <?= checked($body['persona']->aggr('ownGustaList','aficion'), $aficion->id ) ?>>
				<label class="form-check-label" for="id-gusta-<?=$aficion->id?>" ><?= $aficion->nombre ?></label>
			<?php endforeach; ?>
						
		</div>
	</fieldset>

				

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Odia</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['aficion'] as $aficion ): ?>
				<input class="form-check-input" type="checkbox" id="id-odia-<?=$aficion->id ?>" name="odia[]" value="<?= $aficion->id ?>" <?= checked($body['persona']->aggr('ownOdiaList','aficion'), $aficion->id ) ?>>
				<label class="form-check-label" for="id-odia-<?=$aficion->id?>" ><?= $aficion->nombre ?></label>
			<?php endforeach; ?>
						
		</div>
	</fieldset>

				

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Expertoen</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['aficion'] as $aficion ): ?>
				<input class="form-check-input" type="checkbox" id="id-expertoen-<?=$aficion->id ?>" name="expertoen[]" value="<?= $aficion->id ?>" <?= checked($body['persona']->alias('expertoen')->ownAficionList, $aficion->id ) ?>>
				<label class="form-check-label" for="id-expertoen-<?=$aficion->id?>" ><?= $aficion->nombre ?></label>
			<?php endforeach; ?>
						
		</div>
	</fieldset>

				

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Inutilen</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['aficion'] as $aficion ): ?>
				<input class="form-check-input" type="checkbox" id="id-inutilen-<?=$aficion->id ?>" name="inutilen[]" value="<?= $aficion->id ?>" <?= checked($body['persona']->alias('inutilen')->ownAficionList, $aficion->id ) ?>>
				<label class="form-check-label" for="id-inutilen-<?=$aficion->id?>" ><?= $aficion->nombre ?></label>
			<?php endforeach; ?>
						
		</div>
	</fieldset>


	<input type="submit" class="btn btn-primary" value="Actualizar">
			
</form>
			
</div>