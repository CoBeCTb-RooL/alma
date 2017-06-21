<?php
$item = $MODEL['item'];
?>


<?php Slonne::view('stock/menu.php');?>


<style>
.row{margin: 0 0 5px 0; }
.label{display: inline-block; width: 120px; text-align: right; padding: 0 6px 0 0; }
.input{display: inline-block; }
</style>


<a href="javascript:history.go(-1)">&larr; назад</a>
<form action="/ru/stock/analyticsEditSubmit" method="post">
	<input type="hidden" name="id" value="<?=$item->id?>" />
	<div class="row">
		<div class="label">Символ: </div>
		<div class="input"><input type="text" name="symbol" value="<?=$item->symbolCode?>" /></div>
	</div>
	
	<div class="row">
		<div class="label">Спред: </div>
		<div class="input"><input type="text" name="spread" value="<?=$item->spread?>" /></div>
	</div>
	<div class="row">
		<div class="label">tp: </div>
		<div class="input"><input type="text" name="tp" value="<?=$item->tp?>" /></div>
	</div>
	<div class="row">
		<div class="label">sl: </div>
		<div class="input"><input type="text" name="sl" value="<?=$item->sl?>" /></div>
	</div>
	<hr />
	<div class="row">
		<div class="label">Прибыль: </div>
		<div class="input"><input type="text" name="totalProfit" value="<?=$item->totalProfit?>" /></div>
	</div>
	<div class="row">
		<div class="label">Убыток: </div>
		<div class="input"><input type="text" name="totalLoss" value="<?=$item->totalLoss?>" /></div>
	</div>
	<hr />
	
	<div class="row">
		<div class="label">Месяцев: </div>
		<div class="input"><input type="text" name="months" value="<?=$item ? $item->months : 12 ?>" /></div>
	</div>
	<div class="row">
		<div class="label">Непр. прибыль: </div>
		<div class="input"><input type="text" name="continuousProfit" value="<?=$item->continuousProfit?>" /></div>
	</div>
	<div class="row">
		<div class="label">Непр. убыток: </div>
		<div class="input"><input type="text" name="continuousLoss" value="<?=$item->continuousLoss?>" /></div>
	</div>
	<div class="row">
		<div class="label">Период: </div>
		<div class="input"><input type="text" name="period" value="<?=$item ? $item->period : 'm5'?>" /></div>
	</div>
	<hr />
	<div class="row">
		<div class="label">Сделок прибыль: </div>
		<div class="input"><input type="text" name="dealsProfit" value="<?=$item->dealsProfit?>" /></div>
	</div>
	<div class="row">
		<div class="label">Сделок убыток: </div>
		<div class="input"><input type="text" name="dealsLoss" value="<?=$item->dealsLoss?>" /></div>
	</div>
	<hr />
	<div class="row">
		<div class="label">коммент: </div>
		<div class="input"><textarea name="comment" style="width: 300px; height: 40px; "><?=$item->comment?></textarea></div>
	</div>
	<hr />
	
	<input type="submit" name="go_btn" value="Сохранить" />
</form>