<nav class="container navbar navbar-inverse">
  <div class="navbar-header">
    <a class="navbar-brand" href="<?=base_url()?>"><span class="glyphicon glyphicon-home"></span></a>
  </div>
  <div class="collapse navbar-collapse" id="myNavbar">
    <ul class="nav navbar-nav">      <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
           BEANS<span class="caret"></span>
        </a>
		<ul class="dropdown-menu">		<li><a href="<?=base_url()?>mascota/list">mascota</a></li>		<li><a href="<?=base_url()?>pais/list">pais</a></li>		<li><a href="<?=base_url()?>aficion/list">aficion</a></li>		<li><a href="<?=base_url()?>persona/list">persona</a></li>	    </ul>
      </li>    </ul>
  </div>
</nav>