

<div class="container">
<h2> Crear respuestacerrada </h2>

<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>respuestacerrada/create_post" method="post">

			
	<div class="form-group">
		<label for="id-numero">Numero</label>
		<input id="id-numero" type="number" name="numero" class="form-control" autofocus="">
	</div>


	<div class="form-group">
		<label for="id-responderc">Responderc</label>
		<select id="id-responderc" name="responderc" class="form-control">
			<option value="0"> ----- </option>
			<?php foreach ($body['encuesta'] as $encuesta ): ?>
				
					<option value="<?= $encuesta->id ?>"><?= $encuesta->fecha ?></option>
				
			<?php endforeach; ?>
		</select>
	</div>


	<div class="form-group">
		<label for="id-respuestasrc">Respuestasrc</label>
		<select id="id-respuestasrc" name="respuestasrc" class="form-control">
			<option value="0"> ----- </option>
			<?php foreach ($body['preguntacerrada'] as $preguntacerrada ): ?>
				
					<option value="<?= $preguntacerrada->id ?>"><?= $preguntacerrada->nombre ?></option>
				
			<?php endforeach; ?>
		</select>
	</div>


	<input type="submit" class="btn btn-primary" value="Crear">

</form>

<div id="idMessage" class="row col-sm-4">
</div>

</div>	