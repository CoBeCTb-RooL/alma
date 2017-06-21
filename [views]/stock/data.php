<?php
$date = $MODEL['date']; 
$datePrev = $MODEL['datePrev'];
$dateNext = $MODEL['dateNext'];
$today = $MODEL['today'];

$strikes = $MODEL['strikes'];

$currentCur = $MODEL['currentCur'];

?>






<div class="stock">

	<?php Slonne::view('stock/menu.php');?>
	
	
	<h1>Данные</h1>
	
	<?php Slonne::view('stock/currencySelect.php', $a=array('currentCur'=>$currentCur, 'isRedirect'=>true))?>
	
	<div class="top-wrapper">
	
		<div class="day-nav">
			<h2 class="current-date">
			<?=Funx::mkDate($date);?>
			<?=($date == $today ? '<span class="today-lbl">(сегодня)</span>' : '' )?>
			</h2>
			<a href="?date=<?=$datePrev?>&currency=<?=$_REQUEST['currency']?>">&larr; Предыдущий</a>
			<?php
			if($dateNext)
			{?>
			<a href="?date=<?=$dateNext?>&currency=<?=$_REQUEST['currency']?>">Следующий &rarr;</a>
			<?php
			}?>
		</div>
		
		
		
		<form class="upload-file-form" action="/ru/stock/dataSubmit/" method="post" enctype="multipart/form-data" target="frame2">
			<input type="hidden" name="date" value="<?=$date?>" />	
			<input type="hidden" name="currency" value="<?=$currentCur->code?>" />	
			<div class="row">
				<div class="label">Загрузить файл (csv с "точка с запятой"): </div>
				<input type="file" name="file" />
			</div>
			
		
			<input type="submit" name="go_btn" value="сохранить"/>
			<div class="info"></div>
			<iframe name="frame2" frameborder="0" style="display: none; width: 100%; height: 400px; border: 1px solid black; "></iframe>
		</form>
		
	</div>
	
	
	<div class="strikes-wrapper">
	<?php
	//vd($strikes);
	if($strikes )
	{
		foreach($strikes as $type=>$items)
		{?>
			<h2><?=$type?></h2>
			<table class="strikes-for-day" border="1">
			<?php
				ob_start();
				?>
				<tr>
					<th class="sep-right">#</th>
					<th>Strike</th>
					<th>Премия "вчера"</th>
					<th>Премия "сегодня"</th>
					<th class="sep-right">Разница премий</th>
					<th>Объём</th>
					<th>Открытый интерес</th>
					<th>Динамика ОИ</th>
					<th>Кол-во изменений ОИ</th>
					<th>Дельта ОИ</th>
				</tr>
				<?php
				$tableHeadingTmpl = ob_get_clean();
				echo $tableHeadingTmpl;
			?>
			<?php 
			foreach($items as $key=>$item)
			{
				//vd($item);
				$premDiff = $item->currentPrem - $item->previousPrem;
				$premDiffFieldClass = $premDiff > 0 ? 'positive' : ($premDiff < 0 ? 'negative' : '');
				?>
				<tr class="<?=$premDiffFieldClass?>">
					<td class="num sep-right"><?=$key+1?>.</td>
					<td class="strike-num"><?=$item->strike?></td>
					<td>
						<?php
						if($item->previousPrem)
						{
							# 	для GBP
							if($item->currency == Currency::$items[Currency::CODE_GBP]->code)
								echo $item->previousPrem * 100;
							else 
								echo $item->previousPrem * 10;
						} 
						else
						{ echo '----'; }
						?>
					</td>
					<td>
						<?php
						if($item->currentPrem)
						{
							# 	для GBP
							if($item->currency == Currency::$items[Currency::CODE_GBP]->code)
								echo $item->currentPrem * 100;
							else 
								echo $item->currentPrem * 10;
						} 
						else
						{ echo 'CAB'; }
						?>
					</td>
					<td class="sep-right prem-diff <?=$premDiffFieldClass?>">
						<?=($premDiff > 0 ? '+' : '' )?>
						<?php
						if($item->previousPrem)
						{
							# 	для GBP
							if($item->currency == Currency::$items[Currency::CODE_GBP]->code)
								echo round($premDiff * 100, 2);
							else 
								echo $premDiff * 10;
						} 
						else
						{ echo '----'; }
						?>
					</td>
					<td><?=($item->volume ? $item->volume : '----')?></td>
					<td><?=$item->openInterest?></td>
					<td class="dynamics <?=($item->openInterestDynamics == '+' ? 'positive' : 'negative')?>"><?=$item->openInterestDynamics?></td>
					<td><?=$item->openInterestChangesQty ? $item->openInterestChangesQty : '----'?></td>
					<td><?=$item->openInterestDelta ?></td>
					
				</tr>
			<?php 	
				if(!(($key+1)%20) && $key!=0)
					echo $tableHeadingTmpl;
			}?>
			</table>
		<?php 	
		} 
	} 
	else
	{?>
		Данных нет.
	<?php 
	}?>
	</div>

</div>





<script>
	function dataSubmitComplete(res)
	{
		var str = '';
		if(res.error!='')
			str = ''+res.error+'';
		else
			str = ''+res.html+'';
		$('.upload-file-form .info').html(str)
	}
</script>