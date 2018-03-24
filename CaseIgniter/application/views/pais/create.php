
<script type="text/javascript" src="<?= base_url() ?>assets/js/serialize.js"></script>

<script type="text/javascript">
var connection;

function detect(e) {
		key = document.all ? e.keyCode : e.which;
		if (key==13) {
			create();
		}
	}

function create() {
	var createForm = document.getElementById('idForm');
	var serializedData = serialize(createForm);
	
	connection = new XMLHttpRequest();
	connection.open('POST', '<?= base_url() ?>pais/create_post', true);
	connection.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	connection.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	connection.send(serializedData);
	connection.onreadystatechange = function() {
		if (connection.readyState==4 && connection.status==200) {
			actionAJAX();
		}
	}
}

		
function actionAJAX() {
	htmlReceived = connection.responseText;
	document.getElementById("idMessage").innerHTML = htmlReceived;
}	

</script>

<!-- ----------------------------------------- -->



<div class="container">
<h2> Crear pais </h2>

<form class="row col-sm-4" id="idForm">

	<div class="form-group">
		<label for="id-nombre">Nombre</label>
		<input id="id-nombre" type="text" name="nombre" class="form-control" onkeypress="detect(event);">
	</div>


	<input type="button" class="btn btn-primary" onclick="create()" value="Crear">

</form>

<div id="idMessage" class="row col-sm-4">
</div>

</div>	