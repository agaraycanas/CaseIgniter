

<div class="container">
<h2> Crear preguntacerrada </h2>

<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>preguntacerrada/create_post" method="post">

			
	<div class="form-group">
		<label for="id-nombre">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" class="form-control" autofocus="">
	</div>

			
	<div class="form-group">
		<label for="id-enunciado">Enunciado</label>
		<input id="id-enunciado" type="text" name="enunciado" class="form-control" >
	</div>

			
	<div class="form-group">
		<label for="id-enunciadofacil">Enunciadofacil</label>
		<input id="id-enunciadofacil" type="text" name="enunciadofacil" class="form-control" >
	</div>

			
	<div class="form-group">
		<label for="id-min">Min</label>
		<input id="id-min" type="number" name="min" class="form-control" >
	</div>

			
	<div class="form-group">
		<label for="id-max">Max</label>
		<input id="id-max" type="number" name="max" class="form-control" >
	</div>


	<div class="form-group">
		<label for="id-versacerrada">Versacerrada</label>
		<select id="id-versacerrada" name="versacerrada" class="form-control">
			<option value="0"> ----- </option>
			<?php foreach ($body['asignatura'] as $asignatura ): ?>
				
					<option value="<?= $asignatura->id ?>"><?= $asignatura->nombre ?></option>
				
			<?php endforeach; ?>
		</select>
	</div>


	<div class="form-group">
		<label for="id-componepc">Componepc</label>
		<select id="id-componepc" name="componepc" class="form-control">
			<option value="0"> ----- </option>
			<?php foreach ($body['plantillaencuesta'] as $plantillaencuesta ): ?>
				
					<option value="<?= $plantillaencuesta->id ?>"><?= $plantillaencuesta->nombre ?></option>
				
			<?php endforeach; ?>
		</select>
	</div>


	<div class="form-group">
		<label for="id-respuestasrc">Respuestasrc</label>
		<select id="id-respuestasrc" name="respuestasrc" class="form-control">
			<option value="0"> ----- </option>
			<?php foreach ($body['respuestacerrada'] as $respuestacerrada ): ?>
				
					<option value="<?= $respuestacerrada->id ?>"><?= $respuestacerrada->numero ?></option>
				
			<?php endforeach; ?>
		</select>
	</div>


	<div class="form-group">
		<label for="id-contienepc">Contienepc</label>
		<select id="id-contienepc" name="contienepc" class="form-control">
			<option value="0"> ----- </option>
			<?php foreach ($body['categoria'] as $categoria ): ?>
				
					<option value="<?= $categoria->id ?>"><?= $categoria->nombre ?></option>
				
			<?php endforeach; ?>
		</select>
	</div>


	<input type="submit" class="btn btn-primary" value="Crear">

</form>

<div id="idMessage" class="row col-sm-4">
</div>

</div>	