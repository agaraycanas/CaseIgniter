	
	
<div class="container">
<h2> Editar persona </h2>
		
<form class="row col-sm-4" id="idForm">
		
		
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
				
					
	<input type="submit" class="btn btn-primary" value="Actualizar">
			
</form>
			
</div>