<?php
$date = $MODEL['date']; 
$datePrev = $MODEL['datePrev'];
$dateNext = $MODEL['dateNext'];
$today = $MODEL['today'];

$oiStrikes = $MODEL['oiStrikes'];
//vd($oiStrikes);
$currentCur = $MODEL['currentCur'];
?>






<div class="stock">

	<?php Slonne::view('stock/menu.php');?>
	
	
	<h1>График "ОТКРЫТЫЙ ИНТЕРЕС"</h1>
	
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
	
	
	<div class="stock-oi">
	<?php
	//vd($levelsOfDay);
	if($oiStrikes )
	{?>
		<table class="levels" border="0">
		<?php 
		foreach($oiStrikes as $strikeNum=>$item)
		{
			$step = '25'; 	# 	значение в пикселях
			$lineWidthCall = round($item[Strike::TYPE_CALL]->openInterest * $step / 500);
			$lineWidthPut = round($item[Strike::TYPE_PUT]->openInterest * $step / 500);
			//$lineWidthCall = 50;
			$negativeDynCall = false;
			$negativeDynPut = false;
			
			?>
			<tr>
				
				<td class="level-td put">
					<div class="info" ><span class="level"><?=$item[Strike::TYPE_PUT]->level?>;</span> <b><?=$item[Strike::TYPE_PUT]->openInterest ? $item[Strike::TYPE_PUT]->openInterest : '<span class="no-volume">0<span>'?> </b></div>
					<div class="line-wrapper <?=!$item[Strike::TYPE_PUT]->openInterest?'no-stripe':'' ?>" ><div class="line " style="min-width: <?=$lineWidthPut?>px">&nbsp;</div></div>
				</td>
				
				<td class="strike-td"><div class="strike-wrapper"><?=$strikeNum?></div></td>
				
				<td class="level-td call">
					<div class="line-wrapper <?=!$item[Strike::TYPE_CALL]->openInterest?'no-stripe':'' ?>"  ><div class="line " style="min-width: <?=$lineWidthCall?>px">&nbsp;</div></div>
					<div class="info" ><span class="level"><?=$item[Strike::TYPE_CALL]->level?>;</span> <b><?=$item[Strike::TYPE_CALL]->openInterest ? $item[Strike::TYPE_CALL]->openInterest : '<span class="no-volume">0<span>'?></b></div>
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



