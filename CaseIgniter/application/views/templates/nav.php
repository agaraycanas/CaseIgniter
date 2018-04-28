<nav class="container navbar navbar-expand-sm bg-dark navbar-dark rounded">

	<a class="navbar-brand" href="<?=base_url()?>">
		<img src="<?=base_url()?>assets/img/icons/png/home-alt.png" alt="INICIO" style="width:40px;">
	</a>

	<ul class="navbar-nav">
		<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
				BEANS 
			</a>

			<div class="dropdown-menu">
				<a class="dropdown-item" href="<?=base_url()?>persona/list">persona</a>				<a class="dropdown-item" href="<?=base_url()?>aficion/list">aficion</a>				<a class="dropdown-item" href="<?=base_url()?>mascota/list">mascota</a>				<a class="dropdown-item" href="<?=base_url()?>pais/list">pais</a>			</div>

		</li> 
   </ul>
</nav>