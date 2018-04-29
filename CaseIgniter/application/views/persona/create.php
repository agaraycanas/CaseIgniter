

<div class="container">
<h2> Crear persona </h2>

<form class="form" role="form" id="idForm" enctype="multipart/form-data" action="<?= base_url() ?>persona/create_post" method="post">

	
	<div class="row form-inline form-group">
		<label for="id-nombre" class="col-2 justify-content-end">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" class="col-6 form-control" autofocus="autofocus">
		
	</div>

	
	<div class="row form-inline form-group">
		<label for="id-fechanacimiento" class="col-2 justify-content-end">Fechanacimiento</label>
		<input id="id-fechanacimiento" type="date" name="fechanacimiento" class="col-3 form-control" >
		
	</div>

	
	<div class="row form-inline form-group">
		<label for="id-peso" class="col-2 justify-content-end">Peso</label>
		<input id="id-peso" type="number" name="peso" class="col-6 form-control" >
		
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
		<input id="id-foto" type="file" name="foto" class="col-6 form-control" >
		<img class="offset-1 col-2" id="id-out-foto" width="3%" height="3%" src="" alt=""/>
	</div>


	<div class="row form-inline form-group">
		<label for="id-amo" class="col-2 justify-content-end">Amo</label>
		<select id="id-amo" name="amo" class="col-6 form-control">
			<option value="0"> ----- </option>
			<?php foreach ($body['mascota'] as $mascota ): ?>
				<?php if ( $mascota->amo_id == null ): ?>
					<option value="<?= $mascota->id ?>"><?= $mascota->nombre ?></option>
				<?php endif; ?> 
			<?php endforeach; ?>
		</select>
	</div>


	<div class="row form-inline form-group">
		<label for="id-paisnacimiento" class="col-2 justify-content-end">Paisnacimiento</label>
		<select id="id-paisnacimiento" name="paisnacimiento" class="col-6 form-control">
			<option value="0"> ----- </option>
			<?php foreach ($body['pais'] as $pais ): ?>
				
					<option value="<?= $pais->id ?>"><?= $pais->nombre ?></option>
				
			<?php endforeach; ?>
		</select>
	</div>


	<div class="row form-inline form-group">

		<label class="col-2 justify-content-end">Expertoen</label>
		<div class="col-6 form-check form-check-inline justify-content-start">

			<?php foreach ($body['aficion'] as $aficion ): ?>
				<?php if ( $aficion->expertoen_id == null ): ?>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="checkbox" id="id-expertoen-<?=$aficion->id?>" name="expertoen[]" value="<?= $aficion->id ?>">
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
				<?php if ( $aficion->inutilen_id == null ): ?>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="checkbox" id="id-inutilen-<?=$aficion->id?>" name="inutilen[]" value="<?= $aficion->id ?>">
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
						<input class="form-check-input" type="checkbox" id="id-gusta-<?=$aficion->id?>" name="gusta[]" value="<?= $aficion->id ?>">
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
						<input class="form-check-input" type="checkbox" id="id-odia-<?=$aficion->id?>" name="odia[]" value="<?= $aficion->id ?>">
						<label class="form-check-label" for="id-odia-<?=$aficion->id?>" ><?= $aficion->nombre ?></label>
					</div>
				
			<?php endforeach; ?>
		</div>
	</div>


<div class="row offset-2 col-6">
	<input type="submit" class="btn btn-primary" value="Crear">

</form>


<form action="<?=base_url()?>persona/list" method="post">
	<input type="hidden" name="filter" value="<?=$body['filter']?>" />
	<input type="submit" class="offset-1 btn btn-primary" value="Cancelar">
</form>

</div>

</div>	