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
<h2> Editar persona </h2>

<form class="form" role="form" id="idForm" enctype="multipart/form-data" action="<?= base_url() ?>persona/update_post" method="post">
	
	<input type="hidden" name="filter" value="<?=$body['filter']?>" />
	
		
	<input type="hidden" name="id" value="<?= $body['persona']->id ?>">

	<div class="row form-inline form-group">
		<label for="id-nombre" class="col-2 justify-content-end">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" value="<?=  $body['persona']->nombre ?>" class="col-6 form-control">
		
	</div>
				
				
	<div class="row form-inline form-group">
		<label for="id-fechanacimiento" class="col-2 justify-content-end">Fechanacimiento</label>
		<input id="id-fechanacimiento" type="date" name="fechanacimiento" value="<?=  $body['persona']->fechanacimiento ?>" class="col-3 form-control">
		
	</div>
				
				
	<div class="row form-inline form-group">
		<label for="id-peso" class="col-2 justify-content-end">Peso</label>
		<input id="id-peso" type="number" name="peso" value="<?=  $body['persona']->peso ?>" class="col-6 form-control">
		
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
		<input id="id-foto" type="file" name="foto" value="<?=  $body['persona']->foto ?>" class="col-6 form-control">
		<img class="offset-1 col-2" id="id-out-foto" width="3%" height="3%" src="<?=base_url().'assets/upload/'.$body['persona']->foto?>" alt=""/>
	</div>
				
				
	<div class="row form-inline form-group">
		<label for="id-loginname" class="col-2 justify-content-end">Loginname</label>
		<input id="id-loginname" type="text" name="loginname" value="<?=  $body['persona']->loginname ?>" class="col-6 form-control">
		
	</div>
				
				
	<div class="row form-inline form-group">
		<label for="id-password" class="col-2 justify-content-end">Password</label>
		<input id="id-password" type="text" name="password" value="<?=  $body['persona']->password ?>" class="col-6 form-control">
		
	</div>
				
								
	<div class="row form-inline form-group">
		<label for="id-amo" class="col-2 justify-content-end">Amo</label>
		<select id="id-amo" name="amo" class="col-6 form-control">
			<option value="0" <?= $body['persona']->fetchAs('mascota')->amo == null ? 'selected="selected"' : '' ?> > ----- </option> 
		<?php foreach ($body['mascota'] as $mascota ): ?>
			<?php if ( $mascota -> amo_id == null  || $mascota -> fetchAs('persona') -> amo -> id == $body['persona']->id ): ?>
			<option value="<?= $mascota->id ?>" <?= selected($body['persona']->fetchAs('mascota')->amo, $mascota->id ) ?>><?= $mascota->nombre ?></option>
			<?php endif; ?>
		<?php endforeach; ?>
					
		</select>
	</div>
									
	<div class="row form-inline form-group">
		<label for="id-paisnacimiento" class="col-2 justify-content-end">Paisnacimiento</label>
		<select id="id-paisnacimiento" name="paisnacimiento" class="col-6 form-control">
			<option value="0" <?= $body['persona']->fetchAs('pais')->paisnacimiento == null ? 'selected="selected"' : '' ?> > ----- </option> 
		<?php foreach ($body['pais'] as $pais ): ?>
			
			<option value="<?= $pais->id ?>" <?= selected($body['persona']->fetchAs('pais')->paisnacimiento, $pais->id ) ?>><?= $pais->nombre ?></option>
			
		<?php endforeach; ?>
					
		</select>
	</div>
									

	<div class="row form-inline form-group">
		<label class="col-2 justify-content-end">Expertoen</label>
		<div class="col-6 form-check form-check-inline justify-content-start">

			<?php foreach ($body['aficion'] as $aficion ): ?>
				<?php if ( $aficion -> fetchAs('persona') -> expertoen == null || $aficion -> fetchAs('persona') -> expertoen -> id == $body['persona']->id ): ?>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="checkbox" id="id-expertoen-<?=$aficion->id ?>" name="expertoen[]" value="<?= $aficion->id ?>" <?= checked($body['persona']->alias('expertoen')->ownAficionList, $aficion->id ) ?>>
					<label class="form-check-label" for="id-expertoen-<?=$aficion->id?>" ><?= $aficion->nombre ?></label>
				</div>
				<?php endif; ?>
			<?php endforeach; ?>
						
		</div>
	</div>

				

	<div class="row form-inline form-group">
		<label class="col-2 justify-content-end">Inutilen</label>
		<div class="col-6 form-check form-check-inline justify-content-start">

			<?php foreach ($body['aficion'] as $aficion ): ?>
				<?php if ( $aficion -> fetchAs('persona') -> inutilen == null || $aficion -> fetchAs('persona') -> inutilen -> id == $body['persona']->id ): ?>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="checkbox" id="id-inutilen-<?=$aficion->id ?>" name="inutilen[]" value="<?= $aficion->id ?>" <?= checked($body['persona']->alias('inutilen')->ownAficionList, $aficion->id ) ?>>
					<label class="form-check-label" for="id-inutilen-<?=$aficion->id?>" ><?= $aficion->nombre ?></label>
				</div>
				<?php endif; ?>
			<?php endforeach; ?>
						
		</div>
	</div>

				

	<div class="row form-inline form-group">
		<label class="col-2 justify-content-end">Gusta</label>
		<div class="col-6 form-check form-check-inline justify-content-start">

			<?php foreach ($body['aficion'] as $aficion ): ?>
				
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="checkbox" id="id-gusta-<?=$aficion->id ?>" name="gusta[]" value="<?= $aficion->id ?>" <?= checked($body['persona']->aggr('ownGustaList','aficion'), $aficion->id ) ?>>
					<label class="form-check-label" for="id-gusta-<?=$aficion->id?>" ><?= $aficion->nombre ?></label>
				</div>
				
			<?php endforeach; ?>
						
		</div>
	</div>

				

	<div class="row form-inline form-group">
		<label class="col-2 justify-content-end">Odia</label>
		<div class="col-6 form-check form-check-inline justify-content-start">

			<?php foreach ($body['aficion'] as $aficion ): ?>
				
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="checkbox" id="id-odia-<?=$aficion->id ?>" name="odia[]" value="<?= $aficion->id ?>" <?= checked($body['persona']->aggr('ownOdiaList','aficion'), $aficion->id ) ?>>
					<label class="form-check-label" for="id-odia-<?=$aficion->id?>" ><?= $aficion->nombre ?></label>
				</div>
				
			<?php endforeach; ?>
						
		</div>
	</div>

				

	<div class="row form-inline form-group">
		<label class="col-2 justify-content-end">Roles</label>
		<div class="col-6 form-check form-check-inline justify-content-start">

			<?php foreach ($body['rol'] as $rol ): ?>
				
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="checkbox" id="id-roles-<?=$rol->id ?>" name="roles[]" value="<?= $rol->id ?>" <?= checked($body['persona']->aggr('ownRolesList','rol'), $rol->id ) ?>>
					<label class="form-check-label" for="id-roles-<?=$rol->id?>" ><?= $rol->nombre ?></label>
				</div>
				
			<?php endforeach; ?>
						
		</div>
	</div>

<div class="row offset-2 col-6">
	<input type="submit" class="btn btn-primary" value="Actualizar">
</form>


<form action="<?=base_url()?>persona/list" method="post">
	<input type="hidden" name="filter" value="<?=$body['filter']?>" />
	<input type="submit" class="offset-1 btn btn-primary" value="Cancelar">
</form>

</div>

			