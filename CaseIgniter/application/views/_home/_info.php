<div class="container jumbotron">
	<div class="alert alert-<?=$status?>">
		<h4><?=$message?></h4>
	</div>
	<a href="<?= base_url().($link!=null?$link:'') ?>">
		<input type="button" class="offset-1 btn btn-primary" value="Volver">
	</a>
	
</div>