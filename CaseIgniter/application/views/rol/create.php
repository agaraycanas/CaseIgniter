

<div class="container">

	
		<script type="text/javascript">
		$(document).ready(function(){
	 		$("#id-form").submit(function(e){
                e.preventDefault();
                $('#id-modal').on('shown.bs.modal', function() {
                	  $(this).find('[autofocus]').focus();
                });
                $.ajax({
                    url: 'http://[::1]/CaseIgniter/rol/create_post',
                    type: 'POST',
                    data: $("#id-form").serialize(),
                    dataType:"json",
                    success: function(data){
                     	$("#id-modal-message").html(data.message);
                     	if (data.severity == "ERROR" ) {
                         	$("#id-modal-header").html("ERROR");
                     		$("#id-modal-message").attr('class', 'modal-title text-center bg-danger');
            				$("#id-modal").modal('show');
                 	}
                     	else if (data.severity == "WARNING" ) {
                         	$("#id-modal-header").html("Atención");
                     		$("#id-modal-message").attr('class', 'modal-title text-center bg-warning');
	        				$("#id-modal").modal('show');
                     	}
                     	else { //SUCCESS
                     		$(location).attr("href", data.message);

                         }
			
	              	}
              	})
            });
        });
			
		</script>
	

<div class="modal fade" id="id-modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header text-center" id="id-modal-header">ERROR</div>
			<h4 class="modal-title text-center bg-success" id="id-modal-message">SIN AJAX</h4>
			<div class="modal-footer">
				<button type="button" class="btn " data-dismiss="modal" id="id-modal-button" autofocus="autofocus">Aceptar</button>
			</div>
		</div>
	</div>
</div>

	
<h2> Crear rol </h2>

<form class="form" role="form" id="idForm" enctype="multipart/form-data" action="<?= base_url() ?>rol/create_post" method="post">

	
	
	
	<div class="row form-inline form-group">
		<label for="id-nombre" class="col-2 justify-content-end">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" class="col-6 form-control" autofocus="autofocus" >
		
	</div>

	
	
	
	<div class="row form-inline form-group">
		<label for="id-descripcion" class="col-2 justify-content-end">Descripcion</label>
		<input id="id-descripcion" type="text" name="descripcion" class="col-6 form-control"  >
		
	</div>


	<div class="row form-inline form-group">

		<label class="col-2 justify-content-end">Roles</label>
		<div class="col-6 form-check form-check-inline justify-content-start">

			<?php foreach ($body['persona'] as $persona ): ?>
				
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="checkbox" id="id-roles-<?=$persona->id?>" name="roles[]" value="<?= $persona->id ?>">
						<label class="form-check-label" for="id-roles-<?=$persona->id?>" ><?= $persona->nombre ?></label>
					</div>
				
			<?php endforeach; ?>
		</div>
	</div>


<div class="row offset-2 col-6">
	<input type="submit" class="btn btn-primary" value="Crear">

</form>


<form action="<?=base_url()?>rol/list" method="post">
	<input type="hidden" name="filter" value="<?=$body['filter']?>" />
	<input type="submit" class="offset-1 btn btn-primary" value="Cancelar">
</form>

</div>

</div>	