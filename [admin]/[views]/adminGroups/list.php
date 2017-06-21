<?php
$list = $MODEL; 
?>




<?php 
if(count($list) )
{?>
<form id="list-form" method="post" action="/<?=ADMIN_URL_SIGN?>/module/listSubmit" target="frame1" onsubmit="Slonne.AdminGroups.listSubmitStart();" >
	<table class="t">
		<tr>
			<th>id</th>
			<th>Акт.</th>
			<th></th>
			<th>Группа</th>
			
			<th>Удалить</th>
		</tr>
		<?php 
		foreach($list as $key=>$module)
		{?>
			<tr class="<?=(!$module->active ? 'inactive' : '')?>" id="row-<?=$module->id?>" ondblclick="Slonne.AdminGroups.edit(<?=$module->id?>)">
				<td><?=$module->id?></td>
				<td><?=($module->active ? '<span style="color: green; ">ДА</span>' : '<span style="color: red; ">нет</span>')?></td>
				<td><a href="#edit" onclick="Slonne.AdminGroups.edit(<?=$module->id?>); return false;">ред.</a></td>
				<td style="font-weight: bold; "><?=$module->icon?> <?=$module->name?></td>
				<td><a href="#delete" class="" onclick="Slonne.AdminGroups.delete(<?=$module->id?>); return false;">удалить</a></td>
			</tr>
		<?php 
		}?>
	</table>
	
</form>
	
	<input id="add-btn" type="button" onclick="Slonne.AdminGroups.edit(); " value="+ группа">
<?php
}
else
{?>
	Ничего нет.
<?php 	
} 
?>