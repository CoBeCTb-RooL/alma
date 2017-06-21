<?php


$titlePrefix = 'Сущность';
$titlePostfix = ' : добавление';

?>


<div class="view" >
	<form id="edit-form" method="post" action="/<?=ADMIN_URL_SIGN?>/essence/editSubmit" target="frame1" onsubmit="Slonne.Essences.editSubmitStart();" >	
		<input type="hidden" name="id" value="<?=$essence->id?>">
			<h1><?=$titlePrefix?><span class="title-gray"><?=$titlePostfix?></span></h1>
				
			
				<div class="field-wrapper">
					<span class="label">Название<span class="required">*</span>: </span>
					<span class="value">
						<input type="text" name="name" >
					</span>
					<div class="clear"></div>
				</div>
				
				<div class="field-wrapper">
					<span class="label">Код<span class="required">*</span>: </span>
					<span class="value">
						<input type="text" name="code" >
					</span>
					<div class="clear"></div>
				</div>
				
				
				<div class="field-wrapper">
					<span class="label">Обьединённые поля: </span>
					<span class="value">
						<input type="checkbox" name="jointFields" >
					</span>
					<div class="clear"></div>
				</div>	
				
				
						
				<div class="field-wrapper">
					<span class="label">Линейный: </span>
					<span class="value">
						<input type="checkbox" name="linear" >
					</span>
					<div class="clear"></div>
				</div>	
				
			
			
		
		<input type="submit" value="Сохранить">
			
		<div class="loading" style="display: none;">Секунду...</div>
		<div class="info"></div>
	</form>
</div>