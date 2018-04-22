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
<h2> Editar aficion </h2>
		
<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>aficion/update_post" method="post">
		
		
	<input type="hidden" name="id" value="<?= $body['aficion']->id ?>">
			

	<div class="form-group">
		<label for="id-nombre">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" value="<?=  $body['aficion']->nombre ?>" class="form-control" onkeypress="detect(event);">
	</div>
				
				
	<input type="submit" class="btn btn-primary" value="Actualizar">
			
</form>
			
</div>