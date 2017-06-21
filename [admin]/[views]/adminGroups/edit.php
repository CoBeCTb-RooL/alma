<?php
$group = $MODEL['group'];
$modules = $MODEL['modules'];

//vd($modules);


$new = $group ? false : true;

if($new)	
{
	$titlePrefix = 'Группа';
	$titlePostfix = ' : добавление';
}
else
{
	$titlePrefix = $group->name;
	$titlePostfix = ' : редактирование';
}
?>



<?php
if($group || 1)
{?>
	<div class="view" >
		<form id="edit-form" method="post" action="/<?=ADMIN_URL_SIGN?>/adminGroup/editSubmit" target="frame1" onsubmit="Slonne.AdminGroups.editSubmitStart();" >	
			<input type="hidden" name="id" value="<?=$group->id?>">
				<h1><?=$titlePrefix?><span class="title-gray"><?=$titlePostfix?></span></h1>
					<div class="field-wrapper">
						<span class="label">Активен: </span>
						<span class="value" >
							<input type="checkbox" name="active" <?=($group->active || $new ? ' checked="checked" ' : '')?>>
						</span>
						<div class="clear"></div>
					</div>
				
					<div class="field-wrapper">
						<span class="label">Название<span class="required">*</span>: </span>
						<span class="value">
							<input type="text" name="name" value="<?=htmlspecialchars($group->name)?>">
						</span>
						<div class="clear"></div>
					</div>
					
					
					<div class="field-wrapper">
						<span class="label">Полномочия: </span>
						<span class="value privileges">
							<?php
							//vd($group->privilegesArr);
							foreach($modules as $key=>$module)
							{?>
								<div class="item" id="module-<?=$module->id?>" >
									<label ><input type="checkbox" name="priv[<?=$module->id?>]" <?=(isset($group->privilegesArr[$module->id]) ? ' checked="checked" ' : '')?>  onclick="Slonne.AdminGroups.rootPrivilegeClick(<?=$module->id?>)" > <?=$module->icon?> <?=$module->name?></label>
									<div class="sub">
										<?php
										//vd($module->actionsArr);
										foreach($module->actionsArr as $actionCode=>$actionName)
										{?>
											<label><input type="checkbox" name="priv[<?=$module->id?>][<?=$actionCode?>]"  <?=($group->privilegesArr[$module->id][$actionCode] ? ' checked="checked" ' : '')?>  > <?=$actionName?></label>
										<?php 
										} 
										?>
									</div>
								</div>
							<?php 
							} 
							?>
						</span>
						<div class="clear"></div>
					</div>
					
					
			
			<input type="submit" value="Сохранить">
				
			<div class="loading" style="display: none;">Секунду...</div>
			<div class="info"></div>
		</form>
	</div>
	
<?php 	
}
else 
{
	echo 'Группа не найдена! ['.$_REQUEST['id'].']';
}
?>

