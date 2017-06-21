<?php
$admin = $MODEL['admin'];
$groups = $MODEL['groups'];
//vd($admin);
$new = $admin ? false : true;

if($new)	
{
	$titlePrefix = 'Администратор';
	$titlePostfix = ' : добавление';
}
else
{
	$titlePrefix = $admin->name;
	$titlePostfix = ' : редактирование';
}
?>



<?php
if($admin || 1)
{?>
	<div class="view" >
		<form id="edit-form" method="post" action="/<?=ADMIN_URL_SIGN?>/admin/editSubmit" target="frame1" onsubmit="Slonne.Admins.editSubmitStart();" >	
			<input type="hidden" name="id" value="<?=$admin->id?>">
				<h1><?=$titlePrefix?><span class="title-gray"><?=$titlePostfix?></span></h1>
					<div class="field-wrapper">
						<span class="label">Активен: </span>
						<span class="value" >
							<input type="checkbox" name="active" <?=($admin->active || $new ? ' checked="checked" ' : '')?>>
						</span>
						<div class="clear"></div>
					</div>
				
					<div class="field-wrapper">
						<span class="label">ФИО<span class="required">*</span>: </span>
						<span class="value">
							<input type="text" name="name" value="<?=htmlspecialchars($admin->name)?>">
						</span>
						<div class="clear"></div>
					</div>
					
					<div class="field-wrapper">
						<span class="label">E-mail<span class="required">*</span>: </span>
						<span class="value">
							<input type="text" name="email" value="<?=htmlspecialchars($admin->email)?>">
						</span>
						<div class="clear"></div>
					</div>
					
					
					<div class="field-wrapper">
						<span class="label">Группа<span class="required">*</span>: </span>
						<span class="value">
							<select name="groupId">
								<option value="">-выберите группу-</option>
								<?
								foreach($groups as $key=>$group)
								{?>
								<option value="<?=$group->id?>" <?=($group->id == $admin->groupId ? ' selected="selected" ' : '')?>   ><?=$group->name?> <?=(!$group->active ? '(неакт.)' : '')?></option>
								<?php 
								}?>
							</select>
						</span>
						<div class="clear"></div>
					</div>
					
					
					
					<hr>
					
					
					<div class="field-wrapper">
						<span class="label">Пароль: </span>
						<span class="value">
							<input type="password" name="password" autocomplete="off">
						</span>
						<div class="clear"></div>
						<br>
						<span class="label">Ещё раз: </span>
						<span class="value">
							<input type="password" name="password2" autocomplete="off">
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
	echo 'Модуль не найден! ['.$_REQUEST['id'].']';
}
?>

