

<div class="container">
<h2> Crear encuesta </h2>

<form class="row col-sm-4" id="idForm" action="<?= base_url() ?>encuesta/create_post" method="post">

			
	<div class="form-group">
		<label for="id-fecha">Fecha</label>
		<input id="id-fecha" type="date" name="fecha" class="form-control" autofocus="">
	</div>


	<div class="form-group">
		<label for="id-realiza">Realiza</label>
		<select id="id-realiza" name="realiza" class="form-control">
			<option value="0"> ----- </option>
			<?php foreach ($body['usuario'] as $usuario ): ?>
				
					<option value="<?= $usuario->id ?>"><?= $usuario->nombre ?></option>
				
			<?php endforeach; ?>
		</select>
	</div>


	<div class="form-group">
		<label for="id-realizada">Realizada</label>
		<select id="id-realizada" name="realizada" class="form-control">
			<option value="0"> ----- </option>
			<?php foreach ($body['imparticion'] as $imparticion ): ?>
				
					<option value="<?= $imparticion->id ?>"><?= $imparticion->nombre ?></option>
				
			<?php endforeach; ?>
		</select>
	</div>


	<div class="form-group">
		<label for="id-acercade">Acercade</label>
		<select id="id-acercade" name="acercade" class="form-control">
			<option value="0"> ----- </option>
			<?php foreach ($body['plantillaencuesta'] as $plantillaencuesta ): ?>
				
					<option value="<?= $plantillaencuesta->id ?>"><?= $plantillaencuesta->nombre ?></option>
				
			<?php endforeach; ?>
		</select>
	</div>


	<div class="form-group">
		<label for="id-responderc">Responderc</label>
		<select id="id-responderc" name="responderc" class="form-control">
			<option value="0"> ----- </option>
			<?php foreach ($body['respuestacerrada'] as $respuestacerrada ): ?>
				
					<option value="<?= $respuestacerrada->id ?>"><?= $respuestacerrada->numero ?></option>
				
			<?php endforeach; ?>
		</select>
	</div>

	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Respondera</legend>
		<div class="form-check form-check-inline">
			<?php foreach ($body['respuestaabierta'] as $respuestaabierta ): ?>
				<?php if ( $respuestaabierta->respondera == null ): ?>
					<input class="form-check-input" type="checkbox" id="id-respondera-<?=$respuestaabierta->id?>" name="respondera[]" value="<?= $respuestaabierta->id ?>">
					<label class="form-check-label" for="id-respondera-<?=$respuestaabierta->id?>" ><?= $respuestaabierta->texto ?></label>
				<?php endif; ?> 
			<?php endforeach; ?>
		</div>
	</fieldset>


	<input type="submit" class="btn btn-primary" value="Crear">

</form>

<div id="idMessage" class="row col-sm-4">
</div>

</div>	