<form action="<?=base_url() ?>_casei/homePOST" method="post">
	<div class="container">
		
		<div class="row">
			<div class="col-lg-6 ">
				<input type="submit" class="btn btn-primary" value="Pulsa para generar tu
					aplicación"/>
			</div>
		</div>
		
		<div class="row">
			<label for="idTitle">Título de la aplicación</label>
			<input type="text" name="appTitle" id="idTitle" class="form-control">
		</div>		
		
		<div class="row">
			<div class="form-group col-lg-6">
				<label for="idMenu">MENÚS</label>
				<textarea name="menuData" class="form-control" cols="10" rows="15" id="idMenu"
					placeholder="m1>sub-1.1(uri-1.1),sub1-2(uri-1.2)
[rol1]m2>sub-2.1(uri-2.1)
[rol2,rol3]m3>[rol3]sub3.1(uri-3.1),[rol2]sub3.2(uri-3.2)
			" 
			><?php if (isset($menuData)):?><?= $menuData ?><?php endif;?></textarea>
			DEBUG<input type="checkbox" name="debug"/>
			</div>
		
		
			<div class="form-group col-lg-6">
				<label for="idModel">MODELO</label>
				<textarea name="modelData" class="form-control" cols="15" rows="15" id="idModel" style="overflow:scroll;"
					placeholder="============================
BEANNAME [login]
	c [rol1]
	r [auth,anon]
	u [auth]
	d [auth]
............................
textattribute
numberattribute:#
dateattribute:%
imageattribute:@

atwithmodifiers [M,U]

<> onetooneattribute:beanrelated
<* onetomanyattribute:beanrelated
*> manytooneattribute:beanrelated
** manytomanyattribute:beanrelated

** m2matwithmodifiers [c-,r-]

		MODIFIERS
		M   main reg.attribute
		U	unique reg.attribute
		c-	hidden in create/update forms
		r-	hidden in list view
............................
[rol1] use_case_just_for_rol1()
[rol2,rol3] use_case_just_for_rol2_rol3()
===========================
"
			><?php if (isset($modelData)):?><?= $modelData ?><?php endif;?></textarea>
			</div>
		</div>
		<div class="row">
			<?= $modelImg ?>
		</div>
	</div>
</form>