<?php
$list = $MODEL; 
?>




<?php 
if(count($list) )
{?>
<form id="list-form" method="post" action="/<?=ADMIN_URL_SIGN?>/adv/brands/listSubmit" target="frame1" onsubmit="Slonne.Adv.Brands.listSubmitStart();" >
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
		foreach($list as $key=>$brand)
		{?>
			<tr class="<?=($brand->status!=Status::$items[Status::ACTIVE] ? 'inactive' : '')?>" id="row-<?=$brand->id?>" ondblclick="Slonne.Adv.Brands.edit(<?=$brand->id?>)">
				<td><?=$brand->id?></td>
				<td><?=($brand->status == Status::$items[Status::ACTIVE]->num ? '<span style="color: green; ">ДА</span>' : '<span style="color: red; ">нет</span>')?></td>
				<td><a href="#edit" onclick="Slonne.Adv.Brands.edit(<?=$brand->id?>); return false;">ред.</a></td>
				<td style="font-weight: bold; "><?=$brand->icon?> <?=$brand->name?></td>
				
				<td><?=$brand->pic?></td>
				
				<td><input size="2" style="width: 25px; font-size: 9px;" id="idx-<?=$brand->id?>" name="idx[<?=$brand->id?>]" value="<?=$brand->idx?>" type="text"></td>
				<td>
				<?php
				if($brand->status!=Status::$items[Status::ACTIVE]) 
				{?>
					<a href="#delete" class="" onclick="Slonne.Adv.Brands.delete(<?=$brand->id?>); return false;">удалить</a>
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

<p><input id="add-btn" type="button" onclick="Slonne.Adv.Brands.edit(); " value="+ бренд">