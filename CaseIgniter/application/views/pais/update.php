	
	
<div class="container">
<h2> Editar pais </h2>
		
<form class="row col-sm-4" id="idForm">
		
		
	<div class="form-group">
		<label for="id-nombre">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" value="<?=  $body['pais']->nombre ?>" class="form-control" onkeypress="detect(event);">
	</div>
				
					
	<input type="submit" class="btn btn-primary" value="Actualizar">
			
</form>
			
</div>