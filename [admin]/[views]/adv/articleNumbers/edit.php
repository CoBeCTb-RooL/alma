<?php
$artNum = $MODEL;
$new = $artNum ? false : true;

if($new)	
{
	$titlePrefix = 'Арт. номер';
	$titlePostfix = ' : добавление';
}
else
{
	$titlePrefix = $artNum->name;
	$titlePostfix = ' : редактирование';
}
?>



<?php
if($artNum || 1)
{?>
	<div class="view" >
		<form id="edit-form" method="post" action="/<?=ADMIN_URL_SIGN?>/adv/article_numbers/editSubmit" target="frame1" onsubmit="Slonne.Adv.ArtNums.editSubmitStart();" >	
			<input type="hidden" name="id" value="<?=$artNum->id?>">
				<h1><?=$titlePrefix?><span class="title-gray"><?=$titlePostfix?></span></h1>
					<div class="field-wrapper">
						<span class="label">Активен: </span>
						<span class="value" >
							<input type="checkbox" name="active" <?=($artNum->status==Status::$items[Status::ACTIVE] || $new ? ' checked="checked" ' : '')?>>
						</span>
						<div class="clear"></div>
					</div>
				
					<div class="field-wrapper">
						<span class="label">Название<span class="required">*</span>: </span>
						<span class="value">
							<input type="text" name="name" value="<?=htmlspecialchars($artNum->name)?>">
						</span>
						<div class="clear"></div>
					</div>
					
					<div class="field-wrapper">
						<span class="label">Иконка: </span>
						<span class="value">
							<input type="text" name="pic" value="<?=htmlspecialchars($artNum->pic)?>">
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
	echo 'АРтикульный номер не найден! ['.$_REQUEST['id'].']';
}
?>

