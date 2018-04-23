
<script>
	$(document).ready(function() 
	    { 
	        $("#myTable").tablesorter(); 
	    } 
	);
</script>

<?php error_reporting(0); ?>
<div class="container">
<div class="row">
	<div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
		<form class="form-inline"  action="<?=base_url()?>categoria/create"><input type="submit" class="btn btn-primary" value="Crear categoria" autofocus=""></form>
	</div>

	<div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
		<form class="form-inline" action="<?=base_url()?>categoria/list" method="post">
			<label for="id-filter">Filtrar</label>
			<input id="id-filter" type="text" name="filter" class="form-control" >
		</form>
	</div>
</div>

<h1>LISTA de  categoria</h1>

<table id="myTable" class="table table-striped tablesorter">
	<thead>
	<tr>
		<th>nombre</th>		<th>contienepc - nombre(preguntacerrada)</th>
		<th>contienepa - enunciado(preguntaabierta)</th>
		<th>Acciones</th>
	</tr>
	</thead>

	<tbody>
	<?php foreach ($body['categoria'] as $categoria): ?>
		<tr>
			<td class="alert alert-success"><?= $categoria -> nombre ?></td>

				<td>
				<?php foreach ($categoria -> alias ('contienepc') -> ownPreguntacerradaList as $data): ?>
					<span><?= $data -> nombre ?> </span>
				<?php endforeach; ?>
				</td>

				<td>
				<?php foreach ($categoria -> alias ('contienepa') -> ownPreguntaabiertaList as $data): ?>
					<span><?= $data -> enunciado ?> </span>
				<?php endforeach; ?>
				</td>

			<td class="form-inline col-md-1">
				<form action="<?= base_url() ?>categoria/update" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $categoria -> id ?>">
					<button onclick="submit()">
						<img src="<?= base_url() ?>assets/img/icons/png/pencil-2x.png" height="15" width="15" alt="editar">
					</button>
				</form>

				<form action="<?= base_url() ?>categoria/delete" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $categoria -> id ?>">
					<button onclick="submit()">
						<img src="<?= base_url() ?>assets/img/icons/png/trash-2x.png" height="15" width="15" alt="borrar">
					</button>
				</form>
			</td>

		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
</div>
<?php error_reporting(E_ALL); ?>