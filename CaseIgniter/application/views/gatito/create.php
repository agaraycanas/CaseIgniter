

<div class="container">
<h2> Crear gatito </h2>

<form class="form" role="form" id="idForm" enctype="multipart/form-data" action="<?= base_url() ?>gatito/create_post" method="post">

	
	

	<div class="row form-inline form-group">
		<label for="id-nombre" class="col-2 justify-content-end">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" class="col-6 form-control" autofocus="autofocus">
		
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
		<label for="id-loginname" class="col-2 justify-content-end">Loginname</label>
		<input id="id-loginname" type="text" name="loginname" class="col-6 form-control" >
		
	</div>

	
	

	<div class="row form-inline form-group">
		<label for="id-password" class="col-2 justify-content-end">Password</label>
		<input id="id-password" type="password" name="password" class="col-6 form-control" >
		
	</div>


<div class="row offset-2 col-6">
	<input type="submit" class="btn btn-primary" value="Crear">

</form>


<form action="<?=base_url()?>gatito/list" method="post">
	<input type="hidden" name="filter" value="<?=$body['filter']?>" />
	<input type="submit" class="offset-1 btn btn-primary" value="Cancelar">
</form>

</div>

</div>	