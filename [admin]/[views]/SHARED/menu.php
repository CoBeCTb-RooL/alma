<?php
$list = $MODEL['menu'];
$class=$MODEL['class']; 

//vd($class);
//vd($list);
//vd($_GLOBALS['ADMIN']);
//vd($_GLOBALS['ADMIN']->group->privilegesArr);
?>




<div class="<?=$class?>">
	<?php
	foreach($list as $key=>$module)
	{
		if($_GLOBALS['ADMIN']->hasPrivilege($module->id))
		{?>
			<a href="/<?=ADMIN_URL_SIGN?>/<?=$module->path?>" class="<?=($module==$_GLOBALS['CURRENT_MODULE'] ? 'active' : '')?>"><?=($module->icon ? $module->icon : '')?> <?=$module->name?></a>
		<?php 
		}
		else
		{
			continue; ?>
			<a href="/<?=ADMIN_URL_SIGN?>/<?=$module->path?>" class="inactive <?=($module==$_GLOBALS['CURRENT_MODULE'] ? 'active' : '')?>"><?=($module->icon ? $module->icon : '')?> <?=$module->name?></a>
		<?php 	
		} 	
	} 
	?>
	
	
	
	<a href="#logout2" class="exit" onclick="if(confirm('Выйти из системы?')){Slonne.Admins.logout(); return false;} else{return false} "><span class="fa fa-road"></span> Выйти</a>
</div>