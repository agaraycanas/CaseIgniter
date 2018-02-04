
<script type="text/javascript" src="<?= base_url() ?>assets/js/serialize.js"></script>

<script type="text/javascript">
var connection;

function create() {
	var createForm = document.getElementById('idForm');
	var serializedData = serialize(createForm);
	
	connection = new XMLHttpRequest();
	connection.open('POST', '<?= base_url() ?>persona/create_post', true);
	connection.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	connection.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	connection.send(serializedData);
	connection.onreadystatechange = function() {
		if (connection.readyState==4 && connection.status==200) {
			actionAJAX();
		}
	}
}

		
function actionAJAX() {
	htmlReceived = connection.responseText;
	document.getElementById("idMessage").innerHTML = htmlReceived;
}	

</script>

<!--------------------------------------------->

<div class="container">
<h2> Crear persona </h2>

<form class="col-sm-4" id="idForm">

	<div class="form-group">
		<label for="id-nombre">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" class="form-control">
	</div>

	<div class="form-group">
		<label for="id-fecha_nac">Fecha_nac</label>
		<input id="id-fecha_nac" type="date" name="fecha_nac" class="form-control">
	</div>

	<div class="form-group">
		<label for="id-peso">Peso</label>
		<input id="id-peso" type="number" name="peso" class="form-control">
	</div>


	<div class="form-group">
		<label for="id-pais_nacimiento">Pais</label>
		<select id="id-pais_nacimiento" name="pais_nacimiento" class="form-control">
		<?php foreach ($body['pais'] as $pais ): ?>
			<option value="pais->id">pais->$nombre</option>
		<?php endforeach; ?>
		
		</select>
	</div>

	<fieldset>
		<legend>Aficiones</legend>
		<div class="form-group">
			<?php foreach ($body['aficion'] as $aficion ): ?>
				<label for="id-tiene_pa" class="checkbox-inline">Tiene</label>
				<input type="checkbox" id="id-tiene_pa" name="tiene_pa[]" class="form-control" value="aficion->id">

			<?php endforeach; ?>

		</div>
	</fieldset>


	<input type="button" class="btn btn-primary" onclick="create()" value="Crear">

</form>

<div id="idMessage" class="col-sm-4">
</div>

</div>	