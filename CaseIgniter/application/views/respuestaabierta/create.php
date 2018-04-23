

<div class="container">
<h2> Crear respuestaabierta </h2>

<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>respuestaabierta/create_post" method="post">

			
	<div class="form-group">
		<label for="id-texto">Texto</label>
		<input id="id-texto" type="text" name="texto" class="form-control" autofocus="">
	</div>


	<div class="form-group">
		<label for="id-respondera">Respondera</label>
		<select id="id-respondera" name="respondera" class="form-control">
			<option value="0"> ----- </option>
			<?php foreach ($body['encuesta'] as $encuesta ): ?>
				
					<option value="<?= $encuesta->id ?>"><?= $encuesta->fecha ?></option>
				
			<?php endforeach; ?>
		</select>
	</div>


	<div class="form-group">
		<label for="id-respuestasra">Respuestasra</label>
		<select id="id-respuestasra" name="respuestasra" class="form-control">
			<option value="0"> ----- </option>
			<?php foreach ($body['preguntaabierta'] as $preguntaabierta ): ?>
				
					<option value="<?= $preguntaabierta->id ?>"><?= $preguntaabierta->enunciado ?></option>
				
			<?php endforeach; ?>
		</select>
	</div>


	<input type="submit" class="btn btn-primary" value="Crear">

</form>

<div id="idMessage" class="row col-sm-4">
</div>

</div>	