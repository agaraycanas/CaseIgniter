
<script type="text/javascript" src="<?= base_url() ?>assets/js/serialize.js"></script>

<script type="text/javascript">
var connection;

function detect(e) {
		key = document.all ? e.keyCode : e.which;
		if (key==13) {
			create();
		}
	}

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

<!-- ----------------------------------------- -->



<div class="container">
<h2> Crear persona </h2>

<form class="row col-sm-4" id="idForm">

	<div class="form-group">
		<label for="id-nombre">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" class="form-control" onkeypress="detect(event);">
	</div>

	<div class="form-group">
		<label for="id-fechanacimiento">Fechanacimiento</label>
		<input id="id-fechanacimiento" type="date" name="fechanacimiento" class="form-control" onkeypress="detect(event);">
	</div>

	<div class="form-group">
		<label for="id-peso">Peso</label>
		<input id="id-peso" type="number" name="peso" class="form-control" onkeypress="detect(event);">
	</div>


	<div class="form-group">
		<label for="id-amo">Amo</label>
		<select id="id-amo" name="amo" class="form-control">
		<?php foreach ($body['mascota'] as $mascota ): ?>
			<option value="<?= $mascota->id ?>"><?= $mascota->nombre ?></option>
		<?php endforeach; ?>
		
		</select>
	</div>


	<div class="form-group">
		<label for="id-paisnacimiento">Paisnacimiento</label>
		<select id="id-paisnacimiento" name="paisnacimiento" class="form-control">
		<?php foreach ($body['pais'] as $pais ): ?>
			<option value="<?= $pais->id ?>"><?= $pais->nombre ?></option>
		<?php endforeach; ?>
		
		</select>
	</div>

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Aficiones</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['aficion'] as $aficion ): ?>

				<input class="form-check-input" type="checkbox" id="id-aficiones-<?=$aficion->id?>" name="aficiones[]" value="<?= $aficion->id ?>">
				<label class="form-check-label" for="id-aficiones-<?=$aficion->id?>" ><?= $aficion->nombre ?></label>
			<?php endforeach; ?>

		</div>
	</fieldset>

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Expertoen</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['aficion'] as $aficion ): ?>

				<input class="form-check-input" type="checkbox" id="id-expertoen-<?=$aficion->id?>" name="expertoen[]" value="<?= $aficion->id ?>">
				<label class="form-check-label" for="id-expertoen-<?=$aficion->id?>" ><?= $aficion->nombre ?></label>
			<?php endforeach; ?>

		</div>
	</fieldset>


	<input type="button" class="btn btn-primary" onclick="create()" value="Crear">

</form>

<div id="idMessage" class="row col-sm-4">
</div>

</div>	