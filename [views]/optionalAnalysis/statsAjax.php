<?
$data = $MODEL['list'];
//vd($data);

$data2 = $MODEL['list2'];
$data3 = $MODEL['list3'];

//vd($data3);
?>



<?
foreach($data3 as $date=>$val)
{?>
	<h2><?=Funx::mkDate($date)?></h2>

	<table border="1" class="t">
		<tr style="border-bottom: 2px solid #000; ">
			<th>Валюта</th>
			<th>форвард</th>
			<th>Тип страйка</th>
			<th>Тип сделки</th>
			<th>Страйк</th>
			<th>Премия</th>
			<th>Результат</th>
			<th></th>
		</tr>
		<?
		$a=0;
		$b=0;
		$krat = 4;
		foreach($val as $item)
		{
			if($cur!=$item->currency->code && $b)
			{?>
				<tr>
					<td colspan="8"></td>
				</tr>
			<?
			}

			$cur = $item->currency->code;

			?>
			<tr>
				<?
				if(!$a)
				{?>
					<td rowspan="<?=$krat?>"  style="font-weight: bold; font-size: 1.2em;  "><?=$item->currency->code?></td>
					<td rowspan="<?=$krat?>"><?=$item->forward?></td>
				<?
				}?>


				<?
				if(!($a%($krat/2)))
				{?>
					<td rowspan="2" style="font-weight: bold; " ><?=$item->strikeType->name?></td>
				<?
				}?>


				<td class="cell-<?=$item->type->code?>" style="font-weight: bold; "><?=$item->type->code?></td>
				<td class="cell-<?=$item->type->code?>"><?=$item->strike?></td>
				<td class="cell-<?=$item->type->code?>"><?=$item->premium?></td>
				<td class="cell-<?=$item->type->code?>"><?=$item->result?></td>
				<td><!--<a href="#" onclick="if(confirm('УДалить?')){Opt.delete('<?=$date?>', '<?=$cur?>', '<?=$type?>')} return false; ">&times; удалить</a>--></td>
			</tr>

		<?
			$a++;
			if(!($a%$krat))
				$a=0;
			$b++;
		}?>
	</table>
	<hr>
	<?
}?>





<!--
<hr>
<hr>
<hr>
<hr>
<hr>

<?
foreach($data2 as $date=>$val)
{?>
	<h2><?=Funx::mkDate($date)?></h2>

	<table border="1" class="t">
		<tr style="border-bottom: 2px solid #000; ">
			<th>Валюта</th>
			<th>форвард</th>
			<th>Тип страйка</th>
			<th>Тип сделки</th>
			<th>Страйк</th>
			<th>Премия</th>
			<th>Результат</th>
			<th></th>
		</tr>
		<?
		foreach($val as $cur=>$strikeTypes)
		{?>
			<?
			foreach($strikeTypes as $strikeType=>$types)
			{?>
				<?
				$a=0;
				foreach($types as $type=>$val2)
				{?>
					<tr>

						<td  style="font-weight: bold; font-size: 1.2em;  "><?=Currency::code($cur)->code?></td>
						<td ><?=$val2->forward?></td>

						<td style="font-weight: bold; " ><?=$val2->strikeType->name?></td>
						<td style="font-weight: bold; " class="cell-<?=$type?>"><?=$type?></td>
						<td class="cell-<?=$type?>"><?=$val2->strike?></td>
						<td class="cell-<?=$type?>"><?=$val2->premium?></td>
						<td class="cell-<?=$type?>"><?=$val2->result?></td>
						<td><a href="#" onclick="if(confirm('УДалить?')){Opt.delete('<?=$date?>', '<?=$cur?>', '<?=$type?>')} return false; ">&times; удалить</a></td>
					</tr>

				<?
					$a++;
				}?>
			<?
			}?>
		<?
		}?>
	</table>
	<hr>
	<?
}?>
-->





<!--
	<hr>
	<hr>
	<hr>
	<hr>
	<hr>
<?
foreach($data as $date=>$val)
{?>
	<h2><?=Funx::mkDate($date)?></h2>

	<table border="1" class="t">
		<tr style="border-bottom: 2px solid #000; ">
			<th>Валюта</th>

			<th>форвард</th>
			<th>Тип сделки</th>
			<th>Страйк</th>
			<th>Премия</th>
			<th>Результат</th>
			<th></th>
		</tr>
		<?
		foreach($val as $cur=>$types)
		{?>
			<?
			$a=0;
			foreach($types as $type=>$val2)
			{?>
				<tr style="<?=($a? ' border-bottom: 2px solid #000;  ' : '')?>">
					<?
					$rowspan = 2;
					if(count($types) == 1)
						$rowspan = 1;
					if(!$a)
					{?>
					<td rowspan="<?=$rowspan?>" style="font-weight: bold; font-size: 1.2em;  "><?=Currency::code($cur)->code?></td>

					<td rowspan="<?=$rowspan?>"><?=$val2->forward?></td>
					<?
					}?>
					<td style="font-weight: bold; " class="cell-<?=$type?>"><?=$type?></td>
					<td class="cell-<?=$type?>"><?=$val2->strike?></td>
					<td class="cell-<?=$type?>"><?=$val2->premium?></td>
					<td class="cell-<?=$type?>"><?=$val2->result?></td>
					<td><a href="#" onclick="if(confirm('УДалить?')){Opt.delete('<?=$date?>', '<?=$cur?>', '<?=$type?>')} return false; ">&times; удалить</a></td>
				</tr>
				<?
				if($a)
				{?>
					<tr>
						<td colspan="8"></td>
					</tr>
				<?
				}?>
			<?
				$a++;
			}?>
		<?
		}?>
	</table>
	<hr>
<?
}?>-->