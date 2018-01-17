<div class="container">
	<div class="row">
		<div class="form-group col-lg-12">
			<label for="idModel">MODELO</label>
			<?= generate_yuml($classes)?>
		</div>
		
		<pre><code>
		<?php print_r($classes); ?>
		</code></pre>
	</div>
	<a href="<?=base_url()?>"> KEL </a>
</div>