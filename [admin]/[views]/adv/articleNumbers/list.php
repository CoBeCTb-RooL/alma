<?php
$list = $MODEL; 
?>




<?php 
if(count($list) )
{?>
Всего: <b><?=count($list)?></b>
<form id="list-form" method="post" action="/<?=ADMIN_URL_SIGN?>/adv/article_numbers/listSubmit" target="frame1" onsubmit="Slonne.Adv.ArtNums.listSubmitStart();" >
	<table class="t">
		<tr>
			<th>id</th>
			<th>Акт.</th>
			<th></th>
			<th>Название</th>
			<th>Картинка</th>
			<th>Сорт.</th>
			<th>Удалить</th>
		</tr>
		<?php 
		foreach($list as $key=>$artNum)
		{?>
			<tr class="<?=($artNum->status!=Status::$items[Status::ACTIVE] ? 'inactive' : '')?>" id="row-<?=$artNum->id?>" ondblclick="Slonne.Adv.ArtNums.edit(<?=$artNum->id?>)">
				<td><?=$artNum->id?></td>
				<td><?=($artNum->status == Status::$items[Status::ACTIVE]->num ? '<span style="color: green; ">ДА</span>' : '<span style="color: red; ">нет</span>')?></td>
				<td><a href="#edit" onclick="Slonne.Adv.ArtNums.edit(<?=$artNum->id?>); return false;">ред.</a></td>
				<td style="font-weight: bold; "><?=$artNum->icon?> <?=$artNum->name?></td>
				
				<td><?=$artNum->pic?></td>
				
				<td><input size="2" style="width: 25px; font-size: 9px;" id="idx-<?=$artNum->id?>" name="idx[<?=$artNum->id?>]" value="<?=$artNum->idx?>" type="text"></td>
				<td>
				<?php 
				if($artNum->status!=Status::$items[Status::ACTIVE])
				{?>
					<a href="#delete" class="" onclick="Slonne.Adv.ArtNums.delete(<?=$artNum->id?>); return false;">удалить</a>
				<?php 
				}?>
				</td>
			</tr>
		<?php 
		}?>
	</table>
	<input type="submit" id="list-submit-btn" value="Сохранить изменения">
</form>
	
	
<?php
}
else
{?>
	Ничего нет.
<?php 	
} 
?>

<p><input id="add-btn" type="button" onclick="Slonne.Adv.ArtNums.edit(); " value="+ артикульный номер">