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
<h2> Editar mascota </h2>

<form class="form" role="form" id="idForm" action="<?= base_url() ?>mascota/update_post" method="post">
	
	<input type="hidden" name="filter" value="<?=$body['filter']?>" />
	
		
	<input type="hidden" name="id" value="<?= $body['mascota']->id ?>">
	
	<div class="row form-inline form-group">
		<label for="id-nombre" class="col-2 justify-content-end">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" class="col-6 form-control" autofocus="autofocus">
	</div>
		

	<div class="row form-inline form-group">
		<label for="id-nombre" class="col-2 justify-content-end">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" value="<?=  $body['mascota']->nombre ?>" class="col-6 form-control">
	</div>
				
				<div class="row offset-2 col-6">
	<input type="submit" class="btn btn-primary" value="Actualizar">
</form>


<form action="<?=base_url()?>mascota/list" method="post">
	<input type="hidden" name="filter" value="<?=$body['filter']?>" />
	<input type="submit" class="offset-1 btn btn-primary" value="Cancelar">
</form>

</div>

			