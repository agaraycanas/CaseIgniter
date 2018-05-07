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
<h2> Editar gatito </h2>
		
<form action="<?=base_url()?>gatito/changepwd" method="post">
	<input type="hidden" name="id" value="<?= $body['gatito']->id ?>">
	<input type="submit" class="offset-1 btn btn-primary" value="Cambiar contraseña">
</form>
				
<form class="form" role="form" id="idForm" enctype="multipart/form-data" action="<?= base_url() ?>gatito/updatePost" method="post">
	
	<input type="hidden" name="filter" value="<?=$body['filter']?>" />
	
		
	<input type="hidden" name="id" value="<?= $body['gatito']->id ?>">

	<div class="row form-inline form-group">
		<label for="id-nombre" class="col-2 justify-content-end">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" value="<?=  $body['gatito']->nombre ?>" class="col-6 form-control">
		
	</div>
				
				
	<div class="row form-inline form-group">
		<label for="id-expuesto" class="col-2 justify-content-end">Expuesto</label>
		<input id="id-expuesto" type="text" name="expuesto" value="<?=  $body['gatito']->expuesto ?>" class="col-6 form-control">
		
	</div>
				
				
	<div class="row form-inline form-group">
		<label for="id-oculto" class="col-2 justify-content-end">Oculto</label>
		<input id="id-oculto" type="text" name="oculto" value="<?=  $body['gatito']->oculto ?>" class="col-6 form-control">
		
	</div>
				
				
	<div class="row form-inline form-group">
		<label for="id-loginname" class="col-2 justify-content-end">Loginname</label>
		<input id="id-loginname" type="text" name="loginname" value="<?=  $body['gatito']->loginname ?>" class="col-6 form-control">
		
	</div>
				
								
	<?php if ($body['is_admin']): ?>
	<div class="row form-inline form-group">
		<label class="col-2 justify-content-end">Roles</label>
		<div class="col-6 form-check form-check-inline justify-content-start">

			<?php foreach ($body['rol'] as $rol ): ?>
				
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="checkbox" id="id-roles-<?=$rol->id ?>" name="roles[]" value="<?= $rol->id ?>" <?= checked($body['gatito']->aggr('ownRolesList','rol'), $rol->id ) ?>>
					<label class="form-check-label" for="id-roles-<?=$rol->id?>" ><?= $rol->nombre ?></label>
				</div>
				
			<?php endforeach; ?>
						
		</div>
	</div>
	<?php endif; ?>

<div class="row offset-2 col-6">
	<input type="submit" class="btn btn-primary" value="Actualizar">
</form>


<form action="<?=base_url()?>gatito/list" method="post">
	<input type="hidden" name="filter" value="<?=$body['filter']?>" />
	<input type="submit" class="offset-1 btn btn-primary" value="Cancelar">
</form>

</div>

			