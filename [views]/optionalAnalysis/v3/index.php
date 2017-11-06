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
	fieldset{margin: 0 0 20px 0;}
    fieldset legend{font-size: 1.2em; font-weight: bold; }
</style>


<?php Slonne::view('stock/menu.php');?>







<h1>Опционный анализ v3</h1>


<?foreach($currencies as $cur)
{?>
    <form class="form" action="/ru/optionalAnalysis/v3/submit" id="form-<?=$cur->code?>" target="frame7" onsubmit="if(confirm('Сохранить данные?')){return Opt.calc('<?=$cur->code?>')}; return false; ">
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
        <div class="data-input" style="display: ; ">
            <textarea class="global-ta" onkeyup="/*Opt.parseData2('<?=$cur->code?>')*/" style="height: 75px; "></textarea>
        </div>
        <button type="button" style="font-size: 12px; padding: 3px 6px; " onclick="Opt.loadData2('<?=$cur->code?>', Opt.parseData2('<?=$cur->code?>')); Opt.calcAll('<?=$cur->code?>')">внести</button>

        <p>Форвард: <input type="text" class="forward" name="forward[<?=$cur->code?>]" value="<?=$todayData[$cur->code][StrikeType::MAIN][Type::BUY]->forward?>">

            <?
            foreach(StrikeTypeV3::all() as $st)
            {?>
        <fieldset class="strike-<?=$st->code?>">
            <legend><?=$st->name?>: </legend>
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
                        <td><input type="text" class="strike strike-<?=$st->code?>-<?=$t->code?>" name="strike[<?=$cur->code?>][<?=$st->code?>][<?=$t->code?>]" value="<?=$todayData[$cur->code][$st->code][$t->code]->strike?>"></td>
                        <td><input type="text" class="premium-<?=$st->code?>-<?=$t->code?>" name="premium[<?=$cur->code?>][<?=$st->code?>][<?=$t->code?>]" value="<?=$todayData[$cur->code][$st->code][$t->code]->premium?>"></td>
                        <td class="result-<?=$st->code?>-<?=$t->code?>"><?=$todayData[$cur->code][$st->code][$t->code]->result?></td>
                    </tr>
                    <?
                }?>
            </table>

        </fieldset>
        <?
        }?>




        <p>
            <button type="button" onclick="Opt.calcAll('<?=$cur->code?>'); ">посчитать</button>
            <button type="submit" >сохранить</button>
    </form>

    <?
}?>











<hr><hr><hr>
<h1>Опционный анализ</h1>




<?foreach($currencies as $cur)
{?>
	<form class="form" action="/ru/optionalAnalysis/v3/submit" id="form-<?=$cur->code?>" target="frame7" onsubmit="return Opt.calc('<?=$cur->code?>')">
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
            Форвард: <input type="text" class="forward" name="forward[<?=$cur->code?>]" value="<?=$todayData[$cur->code][StrikeType::MAIN][Type::BUY]->forward?>">




        <?
        //foreach(StrikeType2::$items as $st)
        foreach(StrikeTypeV3::all() as $st)
        {?>
            <fieldset class="strike-<?=$st->code?>">
                <legend><?=$st->name?>: </legend>
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
                            <td><input type="text" class="strike strike-<?=$t->code?>" name="strike[<?=$cur->code?>][<?=$st->code?>][<?=$t->code?>]" value="<?=$todayData[$cur->code][$st->code][$t->code]->strike?>"></td>
                            <td><input type="text" class="premium-<?=$t->code?>" name="premium[<?=$cur->code?>][<?=$st->code?>][<?=$t->code?>]" value="<?=$todayData[$cur->code][$st->code][$t->code]->premium?>"></td>
                            <td class="result-<?=$t->code?>"><?=$todayData[$cur->code][$st->code][$t->code]->result?></td>
                        </tr>
                        <?
                    }?>
                </table>
                <button type="button" style="font-size: 12px; padding: 3px 6px; " onclick="$('#form-<?=$cur->code?> .strike-<?=$st->code?> .data-input').slideToggle('fast')">внести данные</button>
                <button type="button" style="font-size: 12px; padding: 3px 6px; " onclick="Opt.calc('<?=$cur->code?>', '<?=$st->code?>');">посчитать</button>
                <div class="data-input" style="display: none; ">
                    <textarea cols="30" rows="1" onkeyup="Opt.loadData('<?=$cur->code?>', '<?=$st->code?>')" style="height: 25px; "></textarea>
                    <!--<br><button type="button" onclick="Opt.loadData('<?=$cur->code?>', '<?=$st->code?>')">загрузить</button>-->
                </div>
            </fieldset>
        <?
        }?>




		<p>
		<button type="submit" >сохранить</button>
	</form>

<?
}?>

<hr>
<?//vd($todayData);?>

<!--<button onclick="Opt.drawStats()">обновить стат.</button>-->
<h1>Статистика</h1>
<div class="stats-loading" style="display: none; ">Ща...</div>
<div class="stats"></div>



<hr>
<iframe src="" frameborder="0" name="frame7" style="border: 1px solid #000; background: #ececec; height: 400px; width: 100%; ">wqe</iframe>




<script>
	var Opt = {
		calc: function(cur, st){
			var data = Opt.getDataFromForm(cur, st)

			var errors = Opt.getErrors(data)

			if(errors.length){
				Opt.showErrors(errors, cur, st)
				return false;
			}
			else{

                var w = $('#form-'+cur)

                /*resultBuy = parseFloat(data.strikeBuy) - parseFloat(data.premBuy) - parseFloat(data.forward)
                resultSell = parseFloat(data.strikeSell) + parseFloat(data.premSell) - parseFloat(data.forward)*/

                resultBuy = parseFloat(data.strikeBuy) - parseFloat(data.premBuy)
                resultSell = parseFloat(data.strikeSell) + parseFloat(data.premSell)

                if(cur == '<?=Currency::CODE_AUD?>')
                {
                    resultBuy += parseFloat(data.forward)
                    resultSell += parseFloat(data.forward)
                }
                else{
                    resultBuy -= parseFloat(data.forward)
                    resultSell -= parseFloat(data.forward)
                }


                w.find('.strike-'+st+' .result-'+st+'-buy').html(resultBuy.toFixed(4))
                w.find('.strike-'+st+' .result-'+st+'-sell').html(resultSell.toFixed(4))
			}
			return true

		},

        calcAll: function(cur){
            Opt.calc(cur, 'main')
            Opt.calc(cur, 'inner')
            Opt.calc(cur, 'outer')
        },


		getErrors: function(data){
			var errors = []

            if(data.forward == '') errors.push('Введи форвард')
            if(data.strikeBuy == '') errors.push('Введи страйк (buy)')
            if(data.strikeSell == '' ) errors.push('Введи страйк (sell)')

            if(data.premBuy == '') errors.push('Введи премию (buy)')
            if(data.premSell == '') errors.push('Введи премию (sell)')

			return errors
		},


		getDataFromForm: function(cur, st){
			//alert(cur)
			var w = $('#form-'+cur)
			return {
                forward: w.find('.forward').val(),

                strikeBuy: w.find('.strike-'+st+'-buy ').val(),
                strikeSell: w.find('.strike-'+st+'-sell ').val(),
                premBuy: w.find('.premium-'+st+'-buy ').val(),
                premSell: w.find('.premium-'+st+'-sell ').val()
			}

		},


		showErrors: function(errors, cur){
			var msg="Ошибки: "
			for(var i in errors)
				msg+="\n - "+errors[i];
			alert(msg)
		},



		drawStats: function(){
			$.ajax({
				url: '/ru/optionalAnalysis/v2/statsAjax',
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


		/*loadData: function(cur, strikeType){
			//var data = Opt.parseData(cur, strikeType)

            var str = $('#form-'+cur+' .strike-'+strikeType+' textarea').val()

            var arr = str.split("\t")
            var data = {
                strike: arr[1],
                premBuy: arr[0],
                premSell: arr[2]
            }

            var w = $('#form-'+cur+' .strike-'+strikeType+' ')

            w.find('.strike').val(data.strike/10000)
            w.find('.premium-buy').val(data.premBuy)
            w.find('.premium-sell').val(data.premSell)

            $('#form-'+cur+' .strike-'+strikeType+' .data-input').slideUp('fast')
            Opt.calc(cur, strikeType)
		},*/


        //  свастик
        parseData2: function(cur){
            var str = $('#form-'+cur+'  textarea.global-ta').val()

            var data = {}   //  конечный объект с инфой в ассоциативных ключах
            var a = []      //  временный массив просто с данными

            var arr = str.split("\n")
            for(var i in arr){
                //alert(arr[i])
                a.push(arr[i].split("\t"))
            }

            data.outerStrS = a[0][1]
            data.outerS = a[0][0]
            data.outerStrB = a[4][0]
            data.outerB = a[4][1]

            data.innerStrS = a[1][1]
            data.innerS = a[1][0]
            data.innerStrB = a[3][0]
            data.innerB = a[3][1]

            data.mainStrS = a[2][1]
            data.mainS = a[2][0]
            data.mainStrB = a[2][1]
            data.mainB = a[2][2]

            return data;

        },



        loadData2: function(cur, data){
            $('#form-'+cur+' .strike-main-sell').val((data.mainStrS/10000).toFixed(4))
            $('#form-'+cur+' .strike-main-buy').val((data.mainStrB/10000).toFixed(4))
            $('#form-'+cur+' .premium-main-sell').val(parseFloat(data.mainS).toFixed(4))
            $('#form-'+cur+' .premium-main-buy').val(parseFloat(data.mainB).toFixed(4))

            $('#form-'+cur+' .strike-inner-sell').val((data.innerStrS/10000).toFixed(4))
            $('#form-'+cur+' .strike-inner-buy').val((data.innerStrB/10000).toFixed(4))
            $('#form-'+cur+' .premium-inner-sell').val(parseFloat(data.innerS).toFixed(4))
            $('#form-'+cur+' .premium-inner-buy').val(parseFloat(data.innerB).toFixed(4))

            $('#form-'+cur+' .strike-outer-sell').val((data.outerStrS/10000).toFixed(4))
            $('#form-'+cur+' .strike-outer-buy').val((data.outerStrB/10000).toFixed(4))
            $('#form-'+cur+' .premium-outer-sell').val(parseFloat(data.outerS).toFixed(4))
            $('#form-'+cur+' .premium-outer-buy').val(parseFloat(data.outerB).toFixed(4))
        }




	}




	$(document).ready(function(){
		Opt.drawStats();
	})
</script>
