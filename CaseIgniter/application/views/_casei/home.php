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
				<textarea name="menuData" class="form-control" cols="10" rows="10" id="idMenu"
					placeholder="
Menu1>submenu1-1(action_1-1),submenu1-2(action_1-2)
Menu2>submenu2-1(action_2-1),submenu2-2(action_1-2),submenu2-3(action2-3)
Menu3>submenu3-1(action_3-1)
[rol1]Menu_rol1>submenu_rol1(..),[rol2,rol3]submenu_rol1_rol2_rol3(..)
CI_to_keep_Case_Igniter_Menu
CRUD_to_generate_a_menu_for_all_the_beans
			" 
			><?php if (isset($menuData)):?><?= $menuData ?><?php endif;?></textarea>
			</div>
		
		
			<div class="form-group col-lg-6">
				<label for="idModel">MODELO</label>
				<textarea name="modelData" class="form-control" cols="15" rows="30" id="idModel" style="overflow:scroll;"
					placeholder="
================================================
BEANNAME [login]
	c (all)
	r (auth,anon)
	u (auth)
	d (auth)
................................................

regularstringattribute
regularnumberattribute:#
regulardateattribute:%
regularimageattribute:@

<> onetooneattribute:beanrelated
<* onetomanyattribute:beanrelated
*> manytooneattribute:beanrelated
** manytomanyattribute:beanrelated

regularattributewithmodifiers [M,U]
** manytomanyattributewithmodifiers:beanrelated [c-,r-]

================================================





================================================
ANOTHERBEAN
.......
moreattributes
================================================

================================================
ABEANWITHNAMEATTRIBUTEONLY
.......
================================================			"
			><?php if (isset($modelData)):?><?= $modelData ?><?php endif;?></textarea>
			</div>
		</div>
	</div>
</form>