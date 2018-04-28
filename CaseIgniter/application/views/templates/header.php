<header class="container">

	<div class="col d-flex justify-content-center">
		<img src="<?=base_url()?>assets/img/logo.png" class="img-rounded "
			alt="LOGO de la aplicación" height="100">
	</div>

	<div class="col text-right">
		<?php if (isset ($header['empleado']['nombre'])) : ?>
			<?= $header['empleado']['nombre'] ?> <?= $header['empleado']['ape1'] ?> <a
			href="<?=base_url()?>empleado/logout">LOGOUT</a>
		<?php else: ?>
			<a href="<?=base_url()?>empleado/login">LOGIN</a>
		<?php endif;?>
	</div>

</header>
