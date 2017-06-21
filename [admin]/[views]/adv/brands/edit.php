<?php
$brand = $MODEL;
$new = $brand ? false : true;

if($new)	
{
	$titlePrefix = 'Бренд';
	$titlePostfix = ' : добавление';
}
else
{
	$titlePrefix = $brand->name;
	$titlePostfix = ' : редактирование';
}
?>



<?php
if($brand || 1)
{?>
	<div class="view" >
		<form id="edit-form" method="post" action="/<?=ADMIN_URL_SIGN?>/adv/brands/editSubmit" target="frame1" onsubmit="Slonne.Adv.Brands.editSubmitStart();" >	
			<input type="hidden" name="id" value="<?=$brand->id?>">
				<h1><?=$titlePrefix?><span class="title-gray"><?=$titlePostfix?></span></h1>
					<div class="field-wrapper">
						<span class="label">Активен: </span>
						<span class="value" >
							<input type="checkbox" name="active" <?=($brand->status==Status::$items[Status::ACTIVE] || $new ? ' checked="checked" ' : '')?>>
						</span>
						<div class="clear"></div>
					</div>
				
					<div class="field-wrapper">
						<span class="label">Название<span class="required">*</span>: </span>
						<span class="value">
							<input type="text" name="name" value="<?=htmlspecialchars($brand->name)?>">
						</span>
						<div class="clear"></div>
					</div>
					
					<div class="field-wrapper">
						<span class="label">Иконка: </span>
						<span class="value">
							<input type="text" name="pic" value="<?=htmlspecialchars($brand->pic)?>">
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
	echo 'Бренд не найден! ['.$_REQUEST['id'].']';
}
?>

