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

<form class="form" role="form" id="idForm" enctype="multipart/form-data" action="<?= base_url() ?>gatito/update_post" method="post">
	
	<input type="hidden" name="filter" value="<?=$body['filter']?>" />
	
		
	<input type="hidden" name="id" value="<?= $body['gatito']->id ?>">

	<div class="row form-inline form-group">
		<label for="id-nombre" class="col-2 justify-content-end">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" value="<?=  $body['gatito']->nombre ?>" class="col-6 form-control">
		
	</div>
				
							
	<script>
		 $(window).on("load",(function(){
		 $(function() {
		 $('#id-foto').change(function(e) {addImage(e);});
		function addImage(e){
			var file = e.target.files[0],
			imageType = /image.*/;
			if (!file.type.match(imageType)) return;
			var reader = new FileReader();
			reader.onload = fileOnload;
			reader.readAsDataURL(file);
		}
		function fileOnload(e) {
		var result=e.target.result;
		$('#id-out-foto').attr("src",result);
		}});}));
	</script>
				
				
	<div class="row form-inline form-group">
		<label for="id-foto" class="col-2 justify-content-end">Foto</label>
		<input id="id-foto" type="file" name="foto" value="<?=  $body['gatito']->foto ?>" class="col-6 form-control">
		<img class="offset-1 col-2" id="id-out-foto" width="3%" height="3%" src="<?=base_url().'assets/upload/'.$body['gatito']->foto?>" alt=""/>
	</div>
				
				
	<div class="row form-inline form-group">
		<label for="id-loginname" class="col-2 justify-content-end">Loginname</label>
		<input id="id-loginname" type="text" name="loginname" value="<?=  $body['gatito']->loginname ?>" class="col-6 form-control">
		
	</div>
				
				
	<div class="row form-inline form-group">
		<label for="id-password" class="col-2 justify-content-end">Password</label>
		<input id="id-password" type="text" name="password" value="<?=  $body['gatito']->password ?>" class="col-6 form-control">
		
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

			