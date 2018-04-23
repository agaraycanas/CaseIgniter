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
<h2> Editar categoria </h2>
		
<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>categoria/update_post" method="post">
		
		
	<input type="hidden" name="id" value="<?= $body['categoria']->id ?>">
			

	<div class="form-group">
		<label for="id-nombre">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" value="<?=  $body['categoria']->nombre ?>" class="form-control" onkeypress="detect(event);">
	</div>
				
								

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Contienepc</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['preguntacerrada'] as $preguntacerrada ): ?>
				<?php if ( $preguntacerrada -> fetchAs('categoria') -> contienepc == null || $preguntacerrada -> fetchAs('categoria') -> contienepc -> id == $body['categoria']->id ): ?>
				<input class="form-check-input" type="checkbox" id="id-contienepc-<?=$preguntacerrada->id ?>" name="contienepc[]" value="<?= $preguntacerrada->id ?>" <?= checked($body['categoria']->alias('contienepc')->ownPreguntacerradaList, $preguntacerrada->id ) ?>>
				<label class="form-check-label" for="id-contienepc-<?=$preguntacerrada->id?>" ><?= $preguntacerrada->nombre ?></label>

				<?php endif; ?>
			<?php endforeach; ?>
						
		</div>
	</fieldset>

				

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Contienepa</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['preguntaabierta'] as $preguntaabierta ): ?>
				<?php if ( $preguntaabierta -> fetchAs('categoria') -> contienepa == null || $preguntaabierta -> fetchAs('categoria') -> contienepa -> id == $body['categoria']->id ): ?>
				<input class="form-check-input" type="checkbox" id="id-contienepa-<?=$preguntaabierta->id ?>" name="contienepa[]" value="<?= $preguntaabierta->id ?>" <?= checked($body['categoria']->alias('contienepa')->ownPreguntaabiertaList, $preguntaabierta->id ) ?>>
				<label class="form-check-label" for="id-contienepa-<?=$preguntaabierta->id?>" ><?= $preguntaabierta->enunciado ?></label>

				<?php endif; ?>
			<?php endforeach; ?>
						
		</div>
	</fieldset>


	<input type="submit" class="btn btn-primary" value="Actualizar">
			
</form>
			
</div>