<?php
//vd($GLOBALS['list']); 
$menu = $GLOBALS['list'];
?>

<div class="index">
	Welcome to SLoNNE CMS! Fast, easy, no excess! 
	<div class="modules">
	<?php
	foreach($menu as $key=>$module)
	{
		if(!$_GLOBALS['ADMIN']->hasPrivilege($module->id)) 
			continue;?>
			
		<a href="/<?=ADMIN_URL_SIGN?>/<?=$module->path?>"><span class="icon"><?=($module->icon ? $module->icon : '')?></span><br> <?=$module->name?></a>
	<?php 
	} 
	?>
	</div>
</div>


