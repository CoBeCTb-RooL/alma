<?php
$date = $MODEL['date']; 
$datePrev = $MODEL['datePrev'];
$dateNext = $MODEL['dateNext'];
$today = $MODEL['today'];

$levelsOfDay = $MODEL['levelsOfDay'];

$currentCur = $MODEL['currentCur'];
?>






<div class="stock">

	<?php Slonne::view('stock/menu.php');?>
	
	
	<h1>График "Уровни"</h1>
	
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
		
		
		
		
	</div>
	
	
	<div class="stock-levels">
	<?php
	//vd($levelsOfDay);
	if($levelsOfDay )
	{?>
		<table class="levels" border="0">
		<?php 
		foreach($levelsOfDay as $strikeNum=>$item)
		{
			if(!$item[Strike::TYPE_CALL]->volume && !$item[Strike::TYPE_PUT]->volume)
				continue;
			
			$step = '25'; 	# 	значение в пикселях
			$lineWidthCall = round($item[Strike::TYPE_CALL]->volume * $step / 500);
			$lineWidthPut = round($item[Strike::TYPE_PUT]->volume * $step / 500);
			//$lineWidthCall = 50;
			$negativeDynCall = false;
			$negativeDynPut = false;
			
			/*if($strikeNum == 1110)
				vd($item);*/
			
			if($item[Strike::TYPE_CALL]->openInterestDynamics == '-')
				$negativeDynCall = true;
			if($item[Strike::TYPE_PUT]->openInterestDynamics == '-')
				$negativeDynPut = true;
				
			
			?>
			<tr>
				<td class="open-interest <?=$item[Strike::TYPE_PUT]->openInterestDynamics == '+' ?  'pos' : ($item[Strike::TYPE_PUT]->openInterestDynamics == '-' ? 'neg' : '') ?>">
					<span class="current-val"><?=intval($item[Strike::TYPE_PUT]->openInterest)?></span> <span class="dynamics-val"><?=$item[Strike::TYPE_PUT]->openInterestChangesQty ? '('.$item[Strike::TYPE_PUT]->openInterestDynamics . $item[Strike::TYPE_PUT]->openInterestChangesQty.')' : ''?></span>
				</td>
				<td class="delta"><?=floatval($item[Strike::TYPE_PUT]->openInterestDelta)?>%</td>
				<td class="level-td put">
					<div class="info" style="<?=!$negativeDynPut ? 'color: #03AB09; text-shadow: 1px 0px 0px #333; ' : ''?>"><?=$item[Strike::TYPE_PUT]->volume ? number_format($item[Strike::TYPE_PUT]->level, 4).'; ' : ''?> <b><?=($item[Strike::TYPE_PUT]->volume ? '-'.$item[Strike::TYPE_PUT]->volume : '<span class="no-volume">0<span>')?></b></div>
					<div class="line-wrapper <?=!$item[Strike::TYPE_PUT]->volume ? 'no-stripe' : ''?>"  ><div class="line <?=$negativeDynPut  ? 'negative-dyn' : ''?>" style="min-width: <?=$lineWidthPut?>px">&nbsp;</div></div>
				</td>
				
				<td class="strike-td"><div class="strike-wrapper"><?=$strikeNum?></div></td>
				
				<td class="level-td call">
					<div class="line-wrapper <?=!$item[Strike::TYPE_CALL]->volume ? 'no-stripe ' : ''?>" ><div class="line <?=$negativeDynCall ? 'negative-dyn' : ''?>"  style="width: <?=$lineWidthCall?>px">&nbsp;</div></div>
					<div class="info" style="<?=!$negativeDynCall ? 'color: #03AB09; text-shadow: 1px 0px 0px #333;  ' : ''?>"><?=$item[Strike::TYPE_CALL]->volume ? number_format($item[Strike::TYPE_CALL]->level, 4).'; ' : ''?> <b><?=($item[Strike::TYPE_CALL]->volume ? $item[Strike::TYPE_CALL]->volume : '<span class="no-volume">0<span>')?></b></div>
				</td>
				<td class="delta"><?=floatval($item[Strike::TYPE_CALL]->openInterestDelta)?>%</td>
				<td class="open-interest <?=$item[Strike::TYPE_CALL]->openInterestDynamics == '+' ?  'pos' : ($item[Strike::TYPE_CALL]->openInterestDynamics == '-' ? 'neg' : '') ?>">
					<span class="current-val"><?=intval($item[Strike::TYPE_CALL]->openInterest)?></span> <span class="dynamics-val"><?=$item[Strike::TYPE_CALL]->openInterestChangesQty ? '('.$item[Strike::TYPE_CALL]->openInterestDynamics . $item[Strike::TYPE_CALL]->openInterestChangesQty.')' : ''?></span>
				</td>
			</tr>
		<?php 	
		} ?>
		</table>
	<?php 	
	} 
	else
	{?>
		Данных нет.
	<?php 
	}?>
	</div>

</div>



