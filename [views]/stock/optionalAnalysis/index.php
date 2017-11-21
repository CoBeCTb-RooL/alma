<?
$currencies = $MODEL['currencies'];
$data = $MODEL['list2'];


//vd($data);

$todayData = $data[date('Y-m-d')];

//vd($todayData);
?>


<style>
	.form{display: inline-block; border: 1px solid #000; padding: 3px 20px;   }
	.form input[type=text]{width: auto; width: 60px; }
	.t th, .t td{padding: 3px 5px; }

	table{border-collapse: collapse; }
	table td, table th{padding: 4px 8px; text-align: center; }


	.cell-<?=Type::BUY?>{background: #dedfff; }
	.cell-<?=Type::SELL?>{background: #e8ffe5; }
	fieldset{margin: 10px 0;}
</style>


<?php Slonne::view('stock/menu.php');?>


<h1>Опционный анализ</h1>




<?foreach($currencies as $cur)
{?>
	<form class="form" action="/ru/stock/optionalAnalysis/submit" id="form-<?=$cur->code?>" target="frame7" onsubmit="return Opt.calc('<?=$cur->code?>')">
		<input type="hidden" name="currency" value="<?=$cur->code?>">
		<h3><?=$cur->code?></h3>

		<input  name="date[<?=$cur->code?>]" id="date-<?=$cur->code?>" value="<?=date('Y-m-d')?>" style="width:70px" type="text">
		<img id="<?=$cur->code?>-calendar-btn" src="/js/calendar/calendar.jpg" style="border:0px;">
		<script>
			Calendar.setup({
				inputField     :    "date-<?=$cur->code?>",      // id of the input field
				ifFormat       :    "%Y-%m-%d",       // format of the input field
				showsTime      :    false,            // will display a time selector
				button         :    "<?=$cur->code?>-calendar-btn",   // trigger for the calendar (button ID)
				singleClick    :    true,           // double-click mode
				step           :    1                // show all years in drop-down boxes (instead of every other year as default)
			});
		</script>


		<p>
		<button type="button" onclick="$('#data-<?=$cur->code?>').slideToggle('fast')">внести данные</button>
		<br>
		<div id="data-<?=$cur->code?>" style="display: none; ">
			<textarea cols="30" rows="3"></textarea>
			<br><button type="button" onclick="Opt.loadData('<?=$cur->code?>')">загрузить</button>
		</div>

		<fieldset>
			<legend>Главный страйк: </legend>
			<table class="t" border="1" style="border-collapse: collapse; ">
				<tr>
					<th></th>
					<th>Страйк</th>
					<th >Премия</th>
					<th >Результат</th>
				</tr>
				<?
				foreach(Type::$items as $t)
				{?>
					<tr>
						<td><?=$t->name?></td>
						<td><input type="text" class="strike-<?=StrikeType::MAIN?>-<?=$t->code?>" name="strike[<?=$cur->code?>][<?=StrikeType::MAIN?>][<?=$t->code?>]" value="<?=$todayData[$cur->code][StrikeType::MAIN][$t->code]->strike?>"></td>
						<td><input type="text" class="premium-<?=StrikeType::MAIN?>-<?=$t->code?>" name="premium[<?=$cur->code?>][<?=StrikeType::MAIN?>][<?=$t->code?>]" value="<?=$todayData[$cur->code][StrikeType::MAIN][$t->code]->premium?>"></td>
						<td class="result-<?=StrikeType::MAIN?>-<?=$t->code?>"></td>
					</tr>
					<?
				}?>
			</table>
		</fieldset>

		<fieldset>
			<legend>Барьеры: </legend>
			<table class="t" border="1" style="border-collapse: collapse; ">
				<tr>
					<th></th>
					<th >Страйк</th>
					<th >Премия</th>
					<th >Результат</th>
				</tr>
				<?
				foreach(Type::$items as $t)
				{?>
					<tr>
						<td><?=$t->name?></td>
						<td><input type="text" class="strike-<?=$t->code?>" name="strike[<?=$cur->code?>][<?=StrikeType::BARRIER?>][<?=$t->code?>]" value="<?=$todayData[$cur->code][StrikeType::BARRIER][$t->code]->strike?>"></td>
						<td><input type="text" class="premium-<?=StrikeType::BARRIER?>-<?=$t->code?>" name="premium[<?=$cur->code?>][<?=StrikeType::BARRIER?>][<?=$t->code?>]" value="<?=$todayData[$cur->code][StrikeType::BARRIER][$t->code]->premium?>"></td>
						<td class="result-<?=StrikeType::BARRIER?>-<?=$t->code?>"></td>
					</tr>
					<?
				}?>
			</table>
		</fieldset>


		<p>
		Форвард: <input type="text" class="forward" name="forward[<?=$cur->code?>]" value="<?=$todayData[$cur->code][StrikeType::MAIN][Type::BUY]->forward?>">
		<p>
		<button type="submit" >сохранить</button>
		<button type="button" onclick="Opt.calc('<?=$cur->code?>')" >посчитать</button>
	</form>

<?
}?>

<hr>
<?//vd($todayData);?>

<button onclick="Opt.drawStats()">обновить стат.</button>
<h1>Статистика</h1>
<div class="stats-loading" style="display: none; ">Ща...</div>
<div class="stats"></div>



<hr>
<iframe src="" frameborder="0" name="frame7" style="border: 1px solid #000; background: #ececec; height: 400px; width: 100%; ">wqe</iframe>




<script>
	var Opt = {
		calc: function(cur){
			var data = Opt.getDataFromForm(cur)

			var errors = Opt.getErrors(data)

			if(errors.length){
				Opt.showErrors(errors, cur)
				return false;
			}
			else{
				result_buy = parseFloat(data.strike_buy) + parseFloat(data.premium_buy) - parseFloat(data.forward)
				result_sell = parseFloat(data.strike_sell) - parseFloat(data.premium_sell) - parseFloat(data.forward)

				resultMainBuy = parseFloat(data.mainStrikeBuy) + parseFloat(data.mainPremiumBuy) - parseFloat(data.forward)
				resultMainSell = parseFloat(data.mainStrikeSell) - parseFloat(data.mainPremiumSell) - parseFloat(data.forward)

				var w = $('#form-'+cur)

				w.find('.result-main-buy').html(resultMainBuy.toFixed(4))
				w.find('.result-main-sell').html(resultMainSell.toFixed(4))

				w.find('.result-barrier-buy').html(result_buy.toFixed(4))
				w.find('.result-barrier-sell').html(result_sell.toFixed(4))
			}
			return true

		},

		getErrors: function(data){
			var errors = []

			if(data.mainStrikeBuy == '') errors.push('Введи главный страйк (buy)')
			if(data.mainStrikeSell == '') errors.push('Введи главный страйк (sell)')

			if(data.mainPremiumBuy == '') errors.push('Введи главную премию (buy)')
			if(data.mainPremiumSell == '') errors.push('Введи главную премию (sell)')


			if(data.strike_buy == '') errors.push('Введи страйк (buy)')
			if(data.strike_sell == '') errors.push('Введи страйк (sell)')

			if(data.premium_buy == '') errors.push('Введи премию (buy)')
			if(data.premium_sell == '') errors.push('Введи премию (sell)')

			if(data.forward == '') errors.push('Введи форвард')

			return errors
		},


		getDataFromForm: function(cur){
			//alert(cur)
			var w = $('#form-'+cur)
			return {
				strike_buy: w.find('.strike-buy').val(),
				premium_buy: w.find('.premium-barrier-buy').val(),

				strike_sell: w.find('.strike-sell').val(),
				premium_sell: w.find('.premium-barrier-sell').val(),

				mainStrikeBuy: w.find('.strike-main-buy').val(),
				mainStrikeSell: w.find('.strike-main-sell').val(),

				mainPremiumBuy: w.find('.premium-main-buy').val(),
				mainPremiumSell: w.find('.premium-main-sell').val(),

				forward: w.find('.forward').val(),
			}

		},


		showErrors: function(errors, cur){
			var msg="Ошибки: \n"
			for(var i in errors)
				msg+="\n - "+errors[i];
			alert(msg)
		},



		drawStats: function(){
			$.ajax({
				url: '/ru/stock/optionalAnalysis/statsAjax',
				data: {},
				beforeSend: function(){ $('.stats').css('opacity', .6); $('.stat-loading').slideDown('fast');  },
				complete: function(){ $('.stats').css('opacity', 1); $('.stat-loading').slideUp('fast');  },
				success: function(data){
					$('.stats').html(data)
				},
				error: function(){}
			})
		},


		delete: function(date, cur, type){
			$.ajax({
				url: '/ru/stock/optionalAnalysis/deleteAjax',
				data: {date: date, currency: cur, type: type},
				beforeSend: function(){ $('.stats').css('opacity', .6); $('.stat-loading').slideDown('fast');  },
				complete: function(){ $('.stats').css('opacity', 1); $('.stat-loading').slideUp('fast');  },
				success: function(data){
					Opt.drawStats()
				},
				error: function(){}
			})
		},


		loadData: function(cur){
			var data = Opt.parseData(cur)
			var f = $('#form-'+cur)
			f.find('.strike-main-buy').val(data.strikeMain/10000)
			f.find('.strike-main-sell').val(data.strikeMain/10000)

			f.find('.premium-main-buy').val(data.mainBuyPrem)
			f.find('.premium-main-sell').val(data.mainSellPrem)

			f.find('.strike-buy').val(data.strikeBuy/10000)
			f.find('.strike-sell').val(data.strikeSell/10000)

			f.find('.premium-barrier-buy').val(data.strikeBuyPrem)
			f.find('.premium-barrier-sell').val(data.strikeSellPrem)

			Opt.calc(cur)
			$('#data-'+cur).slideToggle('fast')
		},

		parseData: function(cur){
			var str = $('#data-'+cur+' textarea').val()

			var data = {}
			var arr = str.split("\n")
			var a=[]
			for(var i in arr){
				//alert(arr[i])
				a.push(arr[i].split("\t"))

			}

			data = {
				strikeMain: a[1][1],
				mainBuyPrem: a[1][0],
				mainSellPrem: a[1][2],

				strikeBuy: a[0][1],
				strikeSell: a[2][1],
				strikeBuyPrem: a[0][0],
				strikeSellPrem: a[2][2],
			}
			return data;
		},

	}




	$(document).ready(function(){
		Opt.drawStats();
	})
</script>
