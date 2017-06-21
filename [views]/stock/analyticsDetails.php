<?php
$item = $MODEL['item'];
//vd($MODEL);

$continuousLoss = 6;
?>


<?php Slonne::view('stock/menu.php');?>
<style>
table{border-collapse: collapse; }
table td, table th{padding: 4px 8px; text-align: center; }
td.spread{font-size: 1.0em; font-weight: bold; }
.important{padding: 5px 10px; font-size: 1.1em; }
.mini{/*font-size: .8em;*/}
.bold{font-weight: bold; }
.green{color: blue;}

.details{font-size: 12px; }
.details .profit{background: #E1F5CE;}
.details .loss{background: #F5DFCE; }

.col{display: inline-block; }

</style>

<a href="javascript:history:go(-1)">&larr; назад</a>
<table border="1">
	<tr>
		<th>#</th>
		<th>symbol</th>
		<th>спред</th>
		<th>tp</th>
		<th>sl</th>
		<th>сделки +</th>
		<th>сделки -</th>
		<th>прибыль</th>
		<th>убыток</th>
		<th>разница</th>
		<th>месяцев</th>
		<th>в месяц</th>
		<th>неприр. прибыль</th>
		<th>неприр. убыток</th>
		<th>период</th>
		<th>коммент</th>

	</tr>
<?php 
	$delta = $item->totalProfit - $item->totalLoss;
	$spreadTPSum = ($item->tp + $item->spread)*$item->dealsProfit;
	$spreadSLSum = ($item->sl - $item->spread)*$item->dealsLoss;
	?>
	<tr>
		<td><?=$item->id?></td>
		<td><?=$item->symbolCode?></td>
		<td class="spread"><?=$item->spread?></td>
		<td><?=$item->tp?></td>
		<td><?=$item->sl?></td>
		<td><?=$item->dealsProfit?></td>
		<td><?=$item->dealsLoss?></td>
		<td class="important profit"><!-- <?=$item->totalProfit?><br> --><span class="mini green"><?=$spreadTPSum?></span></td>
		<td class="important loss"><!-- <?=$item->totalLoss?><br> --><span class="mini green"><?=$spreadSLSum?></span></td>
		
		<td class="important delta bold"><!-- <?=$delta?><br> --><span class="mini green"><?=$spreadTPSum - $spreadSLSum?></span></td>
		<td><?=$item->months?></td>
		<td class="important bold"><!-- <?=round($delta/($item->months ? $item->months : 1), 2)?><br> --><span class="mini green"><?=round(($spreadTPSum - $spreadSLSum) /  ($item->months ? $item->months : 1), 2)?></span></td>
		<td><?=$item->continuousProfit?></td>
		<td><?=$item->continuousLoss?></td>
		<td><?=$item->period?></td>
		<td><?=$item->comment?></td>

	</tr>

</table>



<hr />

<!-- <h1>ВСЕ СДЕЛКИ!</h1>
 <table border="1" class="details">
	<tr>
		<th>№</th>
		<th>Время</th>
		<th>Тип</th>
		<th>Ордер</th>
		<th>Объём</th>
		<th>Цена</th>
		<th>sl</th>
		<th>tp</th>
		<th>Прибыль</th>
		<th>Баланс</th>
	</tr>
<?foreach($item->details as $d)
{
	$class="";
	if($d->profit>0)
		$class = 'profit';
	if($d->profit<0)
		$class = 'loss';
	
	
	
	if($d->profit < 0)
	{
		$lossyDeals[$index][] = $d;
	}
	 
	
	?>
	<tr class="<?=$class?>">
		<td><?=$d->num?></td>
		<td><?=$d->dt?></td>
		<td><?=$d->type?></td>
		<td><?=$d->order?></td>
		<td><?=$d->volume?></td>
		<td><?=$d->price?></td>
		<td><?=$d->sl?></td>
		<td><?=$d->tp?></td>
		<td><?=$d->profit?></td>
		<td><?=$d->balance?></td>
	</tr>
<?php 	
	if($d->profit > 0)
		$index++;
}?>
</table>-->
<hr />
<hr />
<hr />


<hr />
	<div class="col">
	<?php
	//vd($item->details->dt);
	foreach($item->details as $d )
	{
		$a = new DateTime($d->dtModify);
		$h = intval($a->format('H'));
		//vd($d->dt);
		//vd($h);
		if($d->profit>0)
		{
			$arr[$h]['profit']['count'] ++;
			$arr[$h]['profit']['sum'] +=$d->profit;
		}
		if($d->profit<0)
		{
			$arr[$h]['loss']['count'] ++;
			$arr[$h]['loss']['sum'] +=$d->profit;
		}
	}
	//vd($arr);
	?>
	<h1>По часам(по Modify)</h1>
	<table border="1" class="details">
		<tr>
			<th>Время</th>
			<th>Кол-во сделок</th>
			<th>Сумма сделок</th>
		</tr>
		<?php 
		for($h=0; $h<24; $h++)
		{?>
		<tr>
			<td><?=$h?>:00 - <?=$h+1?>:00</td>
			<td>
				<span class="profit"><?=$arr[$h]['profit']['count'] ? $arr[$h]['profit']['count'] : 0?></span>
				/ <span class="loss"><?= $arr[$h]['loss']['count'] ? $arr[$h]['loss']['count'] : 0?></span>
			</td>
			<td>
				<span class="profit"><?=$arr[$h]['profit']['sum'] ? $arr[$h]['profit']['sum'] : 0?></span>
				/ <span class="loss"><?=$arr[$h]['loss']['sum'] ? $arr[$h]['loss']['sum'] : 0?></span>
				
			</td>
			<td>= <b><?=round($arr[$h]['profit']['sum'] + $arr[$h]['loss']['sum'], 2)?></b></td>
		</tr>
		<?php 	
		}?>
	</table>
</div>



<hr />
<div></div>
<hr />
	<div class="col">
	<?php
	//vd($item->details->dt);
	foreach($item->details as $d )
	{
		$a = new DateTime($d->dtModify);
		$h = intval($a->format('H'));
		$m = intval($a->format('i'));
		//vd($d->dt);
		//vd($h);
		if($d->profit>0)
		{
			$arr[$h][$m]['profit']['count'] ++;
			$arr[$h][$m]['profit']['sum'] +=$d->profit;
		}
		if($d->profit<0)
		{
			$arr[$h][$m]['loss']['count'] ++;
			$arr[$h][$m]['loss']['sum'] +=$d->profit;
		}
	}
	//vd($arr);
	?>
	<style>
		.suck{background: #F7D3B5; }
		.gray{color: #ccc; }
	</style>
	<h1>По ПЯТИМИНУТКАМ(по Modify)</h1>
	<table border="1" class="details">
		<tr>
			<th>Время</th>
			<th>Кол-во сделок</th>
			<th>Сумма сделок</th>
		</tr>
		<?php 
		for($h=0; $h<24; $h++)
		{?>
		
			<?php 
			for($min=0; $min<60; $min+=5)
			{
				$overall = round($arr[$h][$min]['profit']['sum'] + $arr[$h][$min]['loss']['sum'], 2);
				$class='';
				if($overall > -80)
					$class="loss";
				else
					$class="profit";
				if($overall > 0)
					$class = 'gray';
				?>	
			<tr>
				<td><?=$h?>:<?=$min?></td>
				<td>
					<span class="profit"><?=$arr[$h][$min]['profit']['count'] ? $arr[$h][$min]['profit']['count'] : 0?></span>
					/ <span class="loss"><?= $arr[$h][$min]['loss']['count'] ? $arr[$h][$min]['loss']['count'] : 0?></span>
				</td>
				<td>
					<span class="profit"><?=$arr[$h][$min]['profit']['sum'] ? $arr[$h][$min]['profit']['sum'] : 0?></span>
					/ <span class="loss"><?=$arr[$h][$min]['loss']['sum'] ? $arr[$h][$min]['loss']['sum'] : 0?></span>
					
				</td>
				<td class="<?=$class?>">= <b><?=$overall?></b></td>
			</tr>	
			<?php 	
			}?>
		
		<?php 	
		}?>
	</table>
</div>



<h1>Периоды убыльных сделок (не менее <?=$continuousLoss?> подряд)</h1>
<table border="1" class="details">
<?php 
$i=0;
foreach($lossyDeals as $index=>$deals)
{
	$profitSum = 0;
	if(count($deals) < $continuousLoss)
		continue; 
	$i++;?>
	<tr>
		<td colspan="10" style="background: #ccc; ">
			<h3 style="margin: 0; padding: 0 ; text-align: left; ">
				Череда сливов #<?=$i?>
				<span style="font-size: 11px; font-weight: normal ">(сделки делались с <b><?=substr($deals[0]->dt, -8, 5)?></b> до <b><?=substr($deals[count($deals)-1]->dt, -8, 5)?></b> )</span>
			</h3>
		</td>
	</tr>
	<?php 
	foreach($deals as $d)
	{?>
		<?php 	
		$class="";
		if($d->profit>0)
			$class = 'profit';
		if($d->profit<0)
			$class = 'loss';
		
		$profitSum += $d->profit;
		?>
	<tr class="<?=$class?>">
		<td><?=$d->num?></td>
		<td><?=$d->dt?></td>
		<td><?=$d->type?></td>
		<td><?=$d->order?></td>
		<td><?=$d->volume?></td>
		<td><?=$d->price?></td>
		<td><?=$d->sl?></td>
		<td><?=$d->tp?></td>
		<td><?=$d->profit?></td>
		<td><?=$d->balance?></td>
	</tr>
	
	<?php 	
	}?>
	<tr>
		<td colspan="8" style="text-align: right; "><b>итого:</b></td>
		<td><?=$profitSum?></td>
	</tr>
<?php 	
}?>
</table>



<hr />




<hr />
Внести статистику
<form action="" method="post">	
	<input type="hidden" name="id" value="<?=$item->id?>" />
	<textarea name="stat" style="width: 100%; height: 200px;"></textarea>
	<input type="submit" name="go_btn" value="GO" />
</form>
