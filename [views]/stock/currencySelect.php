<?php 
$currentCur = $MODEL['currentCur'];
$isRedirect = $MODEL['isRedirect'];

$locationCurrencyJs = "?currency='+$('#currency option:selected').val()+'";
$locationOther.=($_REQUEST['date']?'&date='.$_REQUEST['date']:'');
$locationOther.=($_REQUEST['dateFrom']?'&dateFrom='.$_REQUEST['dateFrom']:'');
$locationOther.=($_REQUEST['dateTo']?'&dateTo='.$_REQUEST['dateTo']:'');


?>



<!--<select name="currency" id="currency" onchange="location.href='<?=$locationCurrencyJs.$locationOther?>'">
	
	<?php
	foreach(Currency::$items as $code=>$cur)
	{
	//vd($cur)?>
	<option value="<?=$code?>" <?=$code==$currentCur->code ? ' selected="selected" ' : ''?>> (<?=$cur->code?>)  <?=$cur->name?> <?=$cur->sign?></option>
	<?php 	
	} 
	?>
</select>-->





	<div class="currencies-menu">
	<?php
	foreach(Currency::$items as $code=>$cur)
	{?>
	<a href="?currency=<?=$code.$locationOther?>" class="<?=$code==$currentCur->code ? 'active' : ''?>" ><?=$cur->code?>  (<?=$cur->sign?>)</a>
	
	<?php 	
	} 
	?>
	</div>




<?php 
if($currentCur)
{?>
	<h1> (<?=$currentCur->code?>)  <?=$currentCur->name?> <?=$currentCur->sign?></h1>
<?php 	
}?>