<header class="container">
	<div class="row">
		<div  class="col-4 d-flex justify-content-center offset-4">
			<img src="<?=base_url()?>assets/img/logo.png" class="img-rounded" alt="LOGO" height="80">
		</div>
	
		<div class="col-4 text-right form-inline">
			<?php if (isset ($header['user']) && isset ($header['rol']) ) : ?>
				<form action="<?=base_url().$header['login_bean'].'/update'?>" method="post">
				<input type="hidden" name="id" value="<?= $header['user']->id ?>" />
				<input type="submit" class="btn btn-primary btn-sm" value="<?= $header['user']->loginname ?>" />
			</form>
			<form action="<?=base_url().$header['login_bean'].'/changeRol'?>"
				method="post">
				<input type="hidden" name="id" value="<?= $header['user']->id ?>" />
				<input type="submit" class="btn btn-secondary btn-sm" value="<?= $header['rol']->descripcion ?>" />
			</form>
			<form action="<?=base_url().$header['login_bean'].'/logout'?>">
				<input type="submit" class="btn btn-info btn-sm" value="Cerrar sesión" />
			</form>
			<?php else: ?>
				<?php if (isset ($header['login_bean'])): ?>

					<form  action="<?=base_url().$header['login_bean'].'/login'?>"  >
						<input type="submit" class="btn btn-primary btn-sm" value="ENTRAR" />
					</form>
					<form  action="<?=base_url().$header['login_bean'].'/create'?>"  >
						<input type="submit" class="btn btn-secondary btn-sm" value="Crear cuenta" />
					</form>
				<?php endif; ?>
			<?php endif; ?>

		</div>
	</div>
</header>


