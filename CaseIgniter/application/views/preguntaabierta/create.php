

<div class="container">
<h2> Crear preguntaabierta </h2>

<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>preguntaabierta/create_post" method="post">

			
	<div class="form-group">
		<label for="id-enunciado">Enunciado</label>
		<input id="id-enunciado" type="text" name="enunciado" class="form-control" autofocus="">
	</div>

			
	<div class="form-group">
		<label for="id-enunciadofacil">Enunciadofacil</label>
		<input id="id-enunciadofacil" type="text" name="enunciadofacil" class="form-control" >
	</div>


	<div class="form-group">
		<label for="id-versaabierta">Versaabierta</label>
		<select id="id-versaabierta" name="versaabierta" class="form-control">
			<option value="0"> ----- </option>
			<?php foreach ($body['asignatura'] as $asignatura ): ?>
				
					<option value="<?= $asignatura->id ?>"><?= $asignatura->nombre ?></option>
				
			<?php endforeach; ?>
		</select>
	</div>


	<div class="form-group">
		<label for="id-componepa">Componepa</label>
		<select id="id-componepa" name="componepa" class="form-control">
			<option value="0"> ----- </option>
			<?php foreach ($body['plantillaencuesta'] as $plantillaencuesta ): ?>
				
					<option value="<?= $plantillaencuesta->id ?>"><?= $plantillaencuesta->nombre ?></option>
				
			<?php endforeach; ?>
		</select>
	</div>

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Respuestasra</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['respuestaabierta'] as $respuestaabierta ): ?>
				<?php if ( $respuestaabierta->respuestasra == null ): ?>
					<input class="form-check-input" type="checkbox" id="id-respuestasra-<?=$respuestaabierta->id?>" name="respuestasra[]" value="<?= $respuestaabierta->id ?>">
					<label class="form-check-label" for="id-respuestasra-<?=$respuestaabierta->id?>" ><?= $respuestaabierta->texto ?></label>
				<?php endif; ?> 
			<?php endforeach; ?>
		</div>
	</fieldset>


	<div class="form-group">
		<label for="id-contienepa">Contienepa</label>
		<select id="id-contienepa" name="contienepa" class="form-control">
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