<?php 
$list = $MODEL['list'];



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
</style>



<a href="/ru/stock/analyticsEdit">+ добавить</a>

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
		<th></th>
		<th></th>
		<th></th>
	</tr>
<?php 
foreach($list as $item)
{
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
		<td><a href="/ru/stock/analyticsDetails?id=<?=$item->id?>">подробнее</a></td>
		<td><a href="/ru/stock/analyticsEdit/?id=<?=$item->id?>">ред. </a></td>
		<td><a href="/ru/stock/analyticsDelete/?id=<?=$item->id?>" style="color: red; " onclick="if(!confirm('Удалить?')){return false; }">удалить</a></td>
	</tr>
<?php 	
}?>
</table>


<a href="/ru/stock/analyticsEdit">+ добавить</a>



