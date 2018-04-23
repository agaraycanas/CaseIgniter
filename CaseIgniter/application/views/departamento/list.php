
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
		<form class="form-inline"  action="<?=base_url()?>departamento/create"><input type="submit" class="btn btn-primary" value="Crear departamento" autofocus=""></form>
	</div>

	<div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
		<form class="form-inline" action="<?=base_url()?>departamento/list" method="post">
			<label for="id-filter">Filtrar</label>
			<input id="id-filter" type="text" name="filter" class="form-control" >
		</form>
	</div>
</div>

<h1>LISTA de  departamento</h1>

<table id="myTable" class="table table-striped tablesorter">
	<thead>
	<tr>
		<th>nombre</th>		<th>pertenece - nombre(usuario)</th>
		<th>imparte - nombre(asignatura)</th>
		<th>gestiona - nombre(titulacion)</th>
		<th>Acciones</th>
	</tr>
	</thead>

	<tbody>
	<?php foreach ($body['departamento'] as $departamento): ?>
		<tr>
			<td class="alert alert-success"><?= $departamento -> nombre ?></td>
					
				<td>
				<?php foreach ($departamento -> aggr('ownPerteneceList', 'usuario') as $data): ?>
					<span><?= $data -> nombre ?> </span>
				<?php endforeach; ?>
				</td>
				
				<td>
				<?php foreach ($departamento -> alias ('imparte') -> ownAsignaturaList as $data): ?>
					<span><?= $data -> nombre ?> </span>
				<?php endforeach; ?>
				</td>
					
				<td>
				<?php foreach ($departamento -> aggr('ownGestionaList', 'titulacion') as $data): ?>
					<span><?= $data -> nombre ?> </span>
				<?php endforeach; ?>
				</td>
				
			<td class="form-inline col-md-1">
				<form action="<?= base_url() ?>departamento/update" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $departamento -> id ?>">
					<button onclick="submit()">
						<img src="<?= base_url() ?>assets/img/icons/png/pencil-2x.png" height="15" width="15" alt="editar">
					</button>
				</form>

				<form action="<?= base_url() ?>departamento/delete" method="post" class="form-group">
					<input type="hidden" name="id" value="<?= $departamento -> id ?>">
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