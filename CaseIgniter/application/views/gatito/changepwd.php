	
	
<div class="container">

<h2> Cambio de contraseña </h2>

<form class="form" role="form" id="idForm" action="<?= base_url() ?>gatito/changepwdPost" method="post">
		
	<input type="hidden" name="id" value="<?=$body['id']?>"/>

	<div class="row form-inline form-group">
		<label for="id-loginname" class="col-2 justify-content-end">Antigua contraseña</label>
		<input id="id-loginname" type="password" name="oldPwd" class="col-6 form-control" autofocus="autofocus">
	</div>
		
	<div class="row form-inline form-group">
		<label for="id-password" class="col-2 justify-content-end">Nueva contraseña</label>
		<input id="id-password" type="password" name="newPwd" class="col-6 form-control" >
	</div>
		
	<div class="row offset-2 col-6">
		<input type="submit" class="btn btn-primary" value="Entrar">
		<a href="<?=base_url()?>">
			<input type="button" class="offset-1 btn btn-primary" value="Cancelar">
		</a>
	</div>
		
</form>
		
</div>
