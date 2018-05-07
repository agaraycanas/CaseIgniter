

<div class="container">
<h2> Crear gatito </h2>

<form class="form" role="form" id="idForm" enctype="multipart/form-data" action="<?= base_url() ?>gatito/create_post" method="post">

	
	

	<div class="row form-inline form-group">
		<label for="id-nombre" class="col-2 justify-content-end">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" class="col-6 form-control" autofocus="autofocus">
		
	</div>

	
	

	<div class="row form-inline form-group">
		<label for="id-expuesto" class="col-2 justify-content-end">Expuesto</label>
		<input id="id-expuesto" type="text" name="expuesto" class="col-6 form-control" >
		
	</div>

	
	

	<div class="row form-inline form-group">
		<label for="id-oculto" class="col-2 justify-content-end">Oculto</label>
		<input id="id-oculto" type="text" name="oculto" class="col-6 form-control" >
		
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