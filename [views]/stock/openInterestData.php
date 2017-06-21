<?php
$date = $MODEL['date']; 
$datePrev = $MODEL['datePrev'];
$dateNext = $MODEL['dateNext'];
$today = $MODEL['today'];

$oiStrikes = $MODEL['oiStrikes'];

$currentCur = $MODEL['currentCur'];

?>






<div class="stock">

	<?php Slonne::view('stock/menu.php');?>
	
	
	<h1>Открытый интерес :: ДАННЫЕ</h1>
	
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
		
		
		
		<form class="upload-file-form" action="/ru/stock/openInterestDataSubmit/" method="post" enctype="multipart/form-data" target="frame2">
			<input type="hidden" name="date" value="<?=$date?>" />	
			<input type="hidden" name="currency" value="<?=$currentCur->code?>" />	
			<div class="row">
				<div class="label">Загрузить файл (csv с "точка с запятой"): </div>
				<input type="file" name="file" />
			</div>
			
		
			<input type="submit" name="go_btn" value="сохранить"/>
			<div class="info"></div>
			<iframe name="frame2" frameborder="0" style="display: none; width: 100%; height: 700px; border: 1px solid black; "></iframe>
		</form>
		
	</div>
	
	
	<div class="strikes-wrapper">
	<?php
	//vd($strikes);
	if($oiStrikes )
	{
		foreach($oiStrikes as $type=>$items)
		{?>
			<h2><?=$type?></h2>
			<table class="strikes-for-day" border="1">
			<?php
				ob_start();
				?>
				<tr>
					<th class="sep-right">#</th>
					<th>Strike</th>
					<th>Settle</th>
					<th>ОИ</th>
				</tr>
				<?php
				$tableHeadingTmpl = ob_get_clean();
				echo $tableHeadingTmpl;
			?>
			<?php 
			foreach($items as $key=>$item)
			{?>
				<tr>
					<td class="num sep-right"><?=$key+1?>.</td>
					<td class="strike-num"><?=$item->strike?></td>
					<td><?=$item->settle?></td>
					<td><?=$item->openInterest?></td>
					
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