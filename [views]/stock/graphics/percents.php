<?php
$dateFrom = $MODEL['dateFrom'];
$dateTo = $MODEL['dateTo'];

$today = $MODEL['today'];

$reports = $MODEL['reports'];

$currentCur = $MODEL['currentCur'];

//vd($reports); 

//vd($MODEL);
?>






<?php Slonne::view('stock/menu.php');?>


<h1>График "ПРОЦЕНТЫ"</h1>
<?php Slonne::view('stock/currencySelect.php', $a=array('currentCur'=>$currentCur, 'isRedirect'=>true))?>

<div class="stock">
	
	<form action="" method="get">
		<input type="hidden" name="currency" value="<?=$currentCur->code?>" />
		Дата : с <input type="text" name="dateFrom" value="<?=$dateFrom?>" size="10" /> &nbsp; по <input type="text" name="dateTo" value="<?=$dateTo?>" size="10" />
		<input type="submit" name="go_btn" value="смотреть" />
	</form>
	
	
	<div style="margin: 20px ; ">Результат с <b><?=Funx::mkDate($dateFrom)?></b> по <b><?=Funx::mkDate($dateTo)?></b>:</div>
	
	
	<div class="report1">
	<?php
	
	$daysOfWeek = array(1=>'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс', );
	
	$prevoiusReport = null;
	$i=0; 
	
	# 	величина в пикселях для 100%
	$height100Percents = 200;
	
	# 	высота гисторгаммы, если прирост больше 100%
	$maxVisualHeightPX = $height100Percents + 40;
	
	
	foreach($reports as $date=>$report)
	{
		
		
		$callPercent = $report->premPercents[Strike::TYPE_CALL];
		$putPercent =  $report->premPercents[Strike::TYPE_PUT];
		
		$heightOfVisualCallPX = intval($height100Percents * $callPercent / 100);
		$heightOfVisualPutPX = intval($height100Percents * $putPercent / 100);
		
		$heightOfVisualCallPX = $callPercent <= 100 ? $heightOfVisualCallPX : $maxVisualHeightPX;  
		$heightOfVisualPutPX = $putPercent <= 100 ? $heightOfVisualPutPX : $maxVisualHeightPX;
		
		
		//vd($prevReport);
		//vd($report);
		
		?>
		<div class="item">
		
			<!--CALL-->
			<div class="call graph <?=!$report->date ? 'no-data' : ''?>" style="height: <?=$maxVisualHeightPX+50?>px">
			<?php 
			if($report->date)
			{?>
				<div class="graphic-wrapper">
					<div class="info <?=$callPercent > 0 ? 'rise' : 'fall'?>">
						<?=$callPercent > 0 ? '&uarr;' : '&darr;' ?><?=($callPercent>0?'+':'') . $callPercent?>%
						<div class="dop-info"><?=$report->premSumsPrev[Strike::TYPE_CALL]?> &rarr; <?=$report->premSums[Strike::TYPE_CALL]?></div>
					</div>
					<div class="graphic-visual" style="height: <?=$heightOfVisualCallPX?>px"></div>
				</div>
			<?php 
			}
			else
			{?>
				<div class="no-data-text">No data</div>
			<?php 
			}?>
			</div>
			<!--//CALL-->
			
			<!--DAY-->
			<div class="date">
				<?=Funx::mkDate($date, 'numeric')?>
				<div class="day-of-week"><?=$daysOfWeek[date('N', strtotime($date))]?></div>
				
				<!--debug-->
				<?php 
				if(0)
				{?>
				<div class="debug-info">
					CALL: <b><?=$prevReport->premSums[Strike::TYPE_CALL]?></b>  &rarr; <b><?=$report->premSums[Strike::TYPE_CALL]?></b>
					<br>
					PUT: <b><?=$prevReport->premSums[Strike::TYPE_PUT]?></b> &rarr; <b><?=$report->premSums[Strike::TYPE_PUT]?></b>
				</div>
				<?php 
				}?>
				<!--debug-->
				
				
			</div>
			<!--//DAY-->
			
			<!--PUT-->
			<div class="put graph <?=!$report->date ? 'no-data' : ''?>" style="height: <?=$maxVisualHeightPX+50?>px">
			<?php 
			if($report->date)
			{?>
				<div class="graphic-wrapper">
					<div class="graphic-visual" style="height: <?=$heightOfVisualPutPX?>px"></div>
					<div class="info <?=$putPercent > 0 ? 'rise' : 'fall'?>" >
						<?=$putPercent > 0 ? '&uarr;' : '&darr;' ?> <?=($putPercent>0?'+':'') . $putPercent?>%
						<div class="dop-info"><?=$report->premSumsPrev[Strike::TYPE_PUT] ?> &rarr; <?=$report->premSums[Strike::TYPE_PUT]?></div>
					</div>
					
				</div>
			<?php 
			}
			else
			{?>
				<div class="no-data-text">No data</div>
			<?php 
			}?>
			</div>
			<!--//PUT-->
			
		</div>
	<?php 	
	} 
	?>
	</div>
	
	
</div>