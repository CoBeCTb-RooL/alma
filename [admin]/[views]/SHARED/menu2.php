<?php
$menu = $MODEL['menu'];
 
//vd($menu);
//vd($class);
//vd($list);
//vd($_GLOBALS['ADMIN']);
//vd($_GLOBALS['ADMIN']->group->privilegesArr);
?>








<div class="top-menu-wrapper">
	<div id="menu">
		<ul class="primary" >
	<?php
	foreach($menu as $key=>$val)
	{
		$module = $val['item'];
		if($_GLOBALS['ADMIN']->hasPrivilege($module->id))
		{?>
			<li>
				<a href="/<?=ADMIN_URL_SIGN?>/<?=$module->path?>" class="<?=($module==$_GLOBALS['CURRENT_MODULE'] ? 'active' : '')?>"><?=($module->icon ? $module->icon : '')?> <?=$module->name?></a>
				<?php
				if($val['subs'])
				{?>
					<ul>
					<?foreach($val['subs'] as $key2=>$val2)
					{
						if($_GLOBALS['ADMIN']->hasPrivilege($val2->id))
						{?>
							<li><a href="/<?=ADMIN_URL_SIGN?>/<?=$val2->path?>" class="<?=($val2==$_GLOBALS['CURRENT_MODULE'] ? 'active' : '')?>"><?=($val2->icon ? $val2->icon : '')?> <?=$val2->name?></a></li>
						<?php 
						}?>
					<?php 
					}?>
					</ul>
				<?php 	
				} 
				?>
			</li>
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
		</ul>
	</div>
	
	<a href="#logout" class="exit2" onclick="if(confirm('Выйти из системы?')){Slonne.Admins.logout(); return false;} else{return false} "><span class="fa fa-road"></span> Выйти</a>
</div>