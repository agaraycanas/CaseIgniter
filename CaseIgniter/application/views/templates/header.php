<header class="container">

	<div class="col d-flex justify-content-center">
		<img src="<?=base_url()?>assets/img/logo.png" class="img-rounded "
			alt="LOGO" height="80">
	</div>

	<div class="col text-right">
		<?php if (isset ($header['user']) && isset ($header['rol']) ) : ?>
			<?= $header['user']->loginname ?> (<?= $header['rol']->descripcion ?>)
			 <a href="<?=base_url().$header['login_bean']?>/logout">LOGOUT</a>
		<?php else: ?>
			<?php if (isset ($header['login_bean'])): ?>
				<a href="<?=base_url().$header['login_bean']?>/login">ENTRAR</a>
			<?php endif; ?>
		<?php endif; ?>
	</div>

</header>
