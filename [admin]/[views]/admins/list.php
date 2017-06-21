<?php
$list = $MODEL; 
?>




<?php 
if(count($list) )
{?>

	<table class="t">
		<tr>
			<th>id</th>
			<th>Акт.</th>
			<th></th>
			<th>Администратор</th>
			<th>E-mail</th>
			<th>Группа</th>
			
			<th>Удалить</th>
		</tr>
		<?php 
		foreach($list as $key=>$admin)
		{?>
			<tr class="<?=(!$admin->active ? 'inactive' : '')?>" id="row-<?=$admin->id?>" ondblclick="Slonne.Admins.edit(<?=$admin->id?>)">
				<td><?=$admin->id?></td>
				<td align="center">
					<?php 
					if($admin->active)
					{
						
						if(!$admin->group->active)
							echo '<span style="color: red; ">нет (из-за группы)</span>';
						else 
							echo '<span style="color: green; ">ДА</span>';
					}
					else
						echo '<span style="color: red; ">нет</span>';
					?>
					
				</td>
				<td><a href="#edit" onclick="Slonne.Admins.edit(<?=$admin->id?>); return false;">ред.</a></td>
				<td style="font-weight: bold; "><?=$admin->name?></td>
				<td ><?=$admin->email?></td>

				<td style="font-weight: bold; "><?=$admin->group->name?> <?=(!$admin->group->active ? '<span style="color: red; font-weight: normal; ">(неакт.)</span>' : '')?></td>
				
				<td><a href="#delete" class="" onclick="Slonne.Admins.delete(<?=$admin->id?>); return false;">удалить</a></td>
			</tr>
		<?php 
		}?>
	</table>
	

	
	<input id="add-btn" type="button" onclick="Slonne.Admins.edit(); " value="+ Новый администратор">
<?php
}
else
{?>
	Ничего нет.
<?php 	
} 
?>