<?
$currencies = $MODEL['currencies'];
$data = $MODEL['list2'];

$date = $MODEL['date'];
$datePrev = $MODEL['datePrev'];
$dateNext = $MODEL['dateNext'];
$today = $MODEL['today'];

$graphicDateFrom = $MODEL['graphicDateFrom'];
$graphicDateTo = $MODEL['graphicDateTo'];
$graphicChosenCurrency = $MODEL['graphicChosenCurrency'];


//vd($data);

$todayData = $data[$date];

//vd($todayData);
///////////////////////////////
//$currency = Currency::code(Currency::CODE_EUR);
$currency = $MODEL['currency'];
//vd($currency);
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

Валюта:
<?
foreach($currencies as $c)
{?>
    <a href="?currency=<?=$c->code?>" style="; <?=$c->code == $currency->code ? 'font-weight: bold; ' : ''?>"><?=$c->code?></a>
<?
}?>
<p></p>

<!--новая всплывающая форма-->
<div id="form-tmpl" style="display: none; ">
    <form class="form" action="/ru/optionalAnalysis/v3/submit" id="form-<?=$currency->code?>-_FORM_NUM_" formNum="_FORM_NUM_" currency="<?=$currency->code?>" target="frame7" onsubmit="if(confirm('Сохранить данные?')){return Opt.calc('<?=$currency->code?>', '<?=StrikeTypeV3::MAIN?>',  _FORM_NUM_)}; return false; " style="position: relative; ">
        <input type="hidden" name="currency" value="<?=$currency->code?>">

        <button type="button" style="position: absolute; top: 0; right: 0; " onclick="closeForm(_FORM_NUM_)">&times;</button>

        <h3>
			<?=$currency->code?> <sup>(_FORM_NUM_)</sup>
            <input  name="date[<?=$currency->code?>]" id="date-<?=$currency->code?>-_FORM_NUM_" value="<?=$date?>" style="width:70px" type="text">
            <img id="<?=$currency->code?>-_FORM_NUM_-calendar-btn" src="/js/calendar/calendar.jpg" style="border:0px;">
            <script>
                Calendar.setup({
                    inputField     :    "date-<?=$currency->code?>-_FORM_NUM_",      // id of the input field
                    ifFormat       :    "%Y-%m-%d",       // format of the input field
                    showsTime      :    false,            // will display a time selector
                    button         :    "<?=$currency->code?>-_FORM_NUM-calendar-btn",   // trigger for the calendar (button ID)
                    singleClick    :    true,           // double-click mode
                    step           :    1                // show all years in drop-down boxes (instead of every other year as default)
                });
            </script>
        </h3>
        <div class="data-input" style="display: ; ">
            <textarea name="data[<?=$currency->code?>]" class="global-ta" onkeyup="/*Opt.parseData2('<?=$currency->code?>')*/" style="height: 75px; ">0.0150	12300.0
0.0093	12350.0
0.0066	12400.0	0.0040
12450.0	0.0064
12500.0	0.0099</textarea>
        </div>
        <button type="button" style="font-size: 12px; padding: 3px 6px; " onclick="Opt.loadData2('<?=$currency->code?>', Opt.parseData2('<?=$currency->code?>', _FORM_NUM_), _FORM_NUM_); Opt.calcAll('<?=$currency->code?>', _FORM_NUM_)">внести</button>

        <p>Название пучка: <input type="text" name="bunchTitle[<?=$currency->code?>]" style="width: 170px; ">

        <p>Форвард: <input type="text" class="forward" name="forward[<?=$currency->code?>]" >

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
                        <td><input type="text" class="strike strike-<?=$st->code?>-<?=$t->code?>" name="strike[<?=$currency->code?>][<?=$st->code?>][<?=$t->code?>]" ></td>
                        <td><input type="text" class="premium-<?=$st->code?>-<?=$t->code?>" name="premium[<?=$currency->code?>][<?=$st->code?>][<?=$t->code?>]" ></td>
                        <td class="result-<?=$st->code?>-<?=$t->code?>"></td>
                    </tr>
					<?
				}?>
            </table>

        </fieldset>
		<?
		}?>


            <button type="button" onclick="Opt.calcAll('<?=$currency->code?>', _FORM_NUM_); ">посчитать</button>
            <button type="submit" >сохранить</button>
    </form>
</div>


<script>
    var cur = '<?=$currency->code?>';
    var formNumber = 0
    function openFormsModal(){
        $('#formsModal .inner').html('')
        appendForm(cur)
        //
        $('#formsModal').modal({});
    }

    function appendForm(cur){
        var html = $('#form-tmpl').html()
        html = html.replace(/_FORM_NUM_/g, formNumber)
        $('#formsModal .inner').append(  html  );

        formNumber++;
    }

    function closeForm(formNumb){
        $('form[formNum='+formNumb+']').hide()
    }

</script>
<button onclick="openFormsModal()">+ форма</button>
<div id="formsModal" style="display: none; vertical-align: top; ">
    <span class="inner"></span>
    <button onclick="appendForm('<?=$corrency->form?>')" style="vertical-align: top; ">+form</button>
</div>
<script>//openFormsModal()</script>
<!--//новая всплывающая форма-->





<!--<div class="day-nav" style="margin: 0 0 14px 0; ">
    <h2 class="current-date" style="margin: 0 0 5px 0;  padding: 0;">
        <?=Funx::mkDate($date);?>
        <?=($date == $today ? '<span class="today-lbl">(сегодня)</span>' : '' )?>
    </h2>
    <a href="?date=<?=$datePrev?>">&larr; Предыдущий</a>
    <?php
    if($dateNext)
    {?>
        <a href="?date=<?=$dateNext?>">Следующий &rarr;</a>
        <?php
    }?>
</div>


<?foreach($currencies as $cur)
{?>
    <form class="form" action="/ru/optionalAnalysis/v3/submit" id="form-<?=$cur->code?>" target="frame7" onsubmit="if(confirm('Сохранить данные?')){return Opt.calc('<?=$cur->code?>')}; return false; ">
        <input type="hidden" name="currency" value="<?=$cur->code?>">
        <h3><?=$cur->code?></h3>

        <input  name="date[<?=$cur->code?>]" id="date-<?=$cur->code?>" value="<?=$date?>" style="width:70px" type="text">
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
            <textarea name="data[<?=$cur->code?>]" class="global-ta" onkeyup="/*Opt.parseData2('<?=$cur->code?>')*/" style="height: 75px; "></textarea>
        </div>
        <button type="button" style="font-size: 12px; padding: 3px 6px; " onclick="Opt.loadData2('<?=$cur->code?>', Opt.parseData2('<?=$cur->code?>')); Opt.calcAll('<?=$cur->code?>')">внести</button>

        <p>Название пучка: <input type="text" name="bunchTitle[<?=$cur->code?>]" style="width: 170px; ">

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
-->

<hr>


<script>
    var Graphic = {
        opts: {},

        takeData: function(){
            this.opts.dateFrom = $('#graphic-date-from').val()
            this.opts.dateTo = $('#graphic-date-to').val()
            this.opts.currency = $('#graphicCurrency').val()
        },

        /*draw: function(){
            //alert(this.opts.currency)
            var w = $('#graphic')

            $.ajax({
                url: '/ru/optionalAnalysis/v3/graphicAjax',
                data: this.opts,
                beforeSend: function(){w.css('opacity', .7)},
                complete: function(){w.css('opacity', 1)},
                success: function(data){
                    w.find('.inner').html(data)
                },
                error: function(){alert('Ошибка какая-то.. хмм. Звоните Лахматому')},
            })
        },*/

        draw2: function(){
            //alert(this.opts.currency)
            var w = $('#graphic')

            $.ajax({
                url: '/ru/optionalAnalysis/v3/graphic2Ajax',
                data: this.opts,
                beforeSend: function(){w.css('opacity', .7)},
                complete: function(){w.css('opacity', 1)},
                success: function(data){
                    w.find('.inner').html(data)
                },
                error: function(){alert('Ошибка какая-то.. хмм. Звоните Лахматому')},
            })
        },

        switchDone: function(id){
            var w = $('#graphic')
            var w2 = $('#row-'+id+'')
            $.ajax({
                url: '/ru/optionalAnalysis/v3/switchDoneAjax',
                data: {id: id},
                dataType: 'json',
                beforeSend: function(){w.css('opacity', .7); w2.css('opacity', .7)},
                complete: function(){w.css('opacity', 1); w2.css('opacity', 1)},
                success: function(data){
                    //w.find('.inner').html(data)
                    if(!data.error)
                    {
                        $('#oa-row-'+id+'').removeClass('done-0').removeClass('done-1').addClass('done-'+data.doneToBe)

                        $('#row-'+id+'').removeClass('row-done-0').removeClass('row-done-1').addClass('roww').addClass('row-done-'+data.doneToBe)
                    }
                    else alert(data.error)
                },
                error: function(){alert('Ошибка какая-то.. хмм. Звоните Лахматому')},
            })
        },

        switchShowOnGraphic: function(id){
            var w = $('#graphic')
            var w2 = $('#bunch-'+id+'')
            $.ajax({
                url: '/ru/optionalAnalysis/v3/switchShowOnGraphicAjax',
                data: {id: id},
                dataType: 'json',
                beforeSend: function(){w.css('opacity', .7); w2.css('opacity', .7)},
                complete: function(){/*w.css('opacity', 1); w2.css('opacity', 1)*/},
                success: function(data){
                    //w.find('.inner').html(data)
                    if(!data.error)
                    {
                        //$('#oa-row-'+id+'').removeClass('showOnGraphic-0').removeClass('showOnGraphic-1').addClass('showOnGraphic-'+data.valueToBe)

                        //$('#showOnGraphic-'+id+'').removeClass('bunch-showOnGraphic-0').removeClass('bunch-showOnGraphic-1').addClass('bunch-showOnGraphic-'+data.valueToBe)
                        Graphic.draw2()
                    }
                    else alert(data.error)
                },
                error: function(){alert('Ошибка какая-то.. хмм. Звоните Лахматому')},
            })
        }


    }


    $(document).ready(function(){
        Graphic.takeData()
        Graphic.draw2()
    })
</script>


<div id="graphic">
    <form class="filter" onsubmit="Graphic.takeData(); Graphic.draw2(); return false; ">
        <select id="graphicCurrency" onchange="Graphic.takeData(); Graphic.draw2();">
            <?
            foreach($currencies as $cur)
            {?>
                <option value="<?=$cur->code?>" <?=$cur->code==$graphicChosenCurrency->code ? ' selected ' : ''?>><?=$cur->code?></option>
            <?    
            }?>
        </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        
        from: <input  id="graphic-date-from" value="<?=$graphicDateFrom?>" style="width:70px" type="text">
        <img id="graphic-date-from-btn" src="/js/calendar/calendar.jpg" style="border:0px;">
        <script>
            Calendar.setup({
                inputField     :    "graphic-date-from",      // id of the input field
                ifFormat       :    "%Y-%m-%d",       // format of the input field
                showsTime      :    false,            // will display a time selector
                button         :    "graphic-date-from-btn",   // trigger for the calendar (button ID)
                singleClick    :    true,           // double-click mode
                step           :    1                // show all years in drop-down boxes (instead of every other year as default)
            });
        </script>
        &nbsp;&nbsp;&nbsp;
        to: <input  id="graphic-date-to" value="<?=$graphicDateTo?>" style="width:70px" type="text">
        <img id="graphic-date-to-btn" src="/js/calendar/calendar.jpg" style="border:0px;">
        <script>
            Calendar.setup({
                inputField     :    "graphic-date-to",      // id of the input field
                ifFormat       :    "%Y-%m-%d",       // format of the input field
                showsTime      :    false,            // will display a time selector
                button         :    "graphic-date-to-btn",   // trigger for the calendar (button ID)
                singleClick    :    true,           // double-click mode
                step           :    1                // show all years in drop-down boxes (instead of every other year as default)
            });
        </script>
        <input type="submit" value="go">
    </form>
    <div class="inner"></div>
</div>




<?//vd($todayData);?>

<!--<button onclick="Opt.drawStats()">обновить стат.</button>-->
<!--<h1>Статистика</h1>
<div class="stats-loading" style="display: none; ">Ща...</div>
<div class="stats"></div>-->



<hr>
<iframe src="" frameborder="0" name="frame7" style="display: none; border: 1px solid #000; background: #ececec; height: 400px; width: 100%; ">wqe</iframe>




<script>
	var Opt = {
		calc: function(cur, st, formNumb){
            var w;
            if(typeof formNumb == 'undefined')
                w = $('#form-'+cur)
            else
                w = $('#form-'+cur+'-'+formNumb)

            //alert(w.attr('id'))
			var data = Opt.getDataFromForm(cur, st, formNumb)
            /*var a=''
            for(var i in data)
                a+="\n"+i+"="+data[i]
            alert(a)*/

            //alert(data)
			var errors = Opt.getErrors(data)

			if(errors.length){
				Opt.showErrors(errors, cur, st)
				return false;
			}
			else{

               // var w = $('#form-'+cur)

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


                //alert(resultBuy.toFixed(4))
                //alert('.strike-'+st+' .result-'+st+'-buy')
                w.find('.strike-'+st+' .result-'+st+'-buy').html(resultBuy.toFixed(4))
                w.find('.strike-'+st+' .result-'+st+'-sell').html(resultSell.toFixed(4))
                //alert(w.attr('id'))
			}
			return true

		},

        calcAll: function(cur, formNumb){
            /*var w;
            if(typeof formNumb == 'undefined')
                w = $('#form-'+cur)
            else
                w = $('#form-'+cur+'-'+formNumb)*/

            Opt.calc(cur, 'main', formNumb)
            Opt.calc(cur, 'inner', formNumb)
            Opt.calc(cur, 'outer', formNumb)
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


		getDataFromForm: function(cur, st, formNumb){
		    //alert(formNumb)
            var w;
            if(typeof formNumb == 'undefined')
                w = $('#form-'+cur)
            else
                w = $('#form-'+cur+'-'+formNumb)

            //alert(w.attr('id'))
			//alert(cur)
			//var w = $('#form-'+cur)
            //alert('.strike-'+st+'-buy')
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
				url: '/ru/optionalAnalysis/v3/statsAjax',
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
        parseData2: function(cur, formNumb){
            var w;
            if(typeof formNumb == 'undefined')
                w = $('#form-'+cur)
            else
                w = $('#form-'+cur+'-'+formNumb)

            var str = w.find('textarea.global-ta').val()

            //alert(str)

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

            /*var a=''
            for(var i in data)
                a+="\n"+i+"="+data[i]
            alert(a)*/

            return data;

        },



        loadData2: function(cur, data, formNumb){
            var w;
            if(typeof formNumb == 'undefined')
                w = $('#form-'+cur)
            else
                w = $('#form-'+cur+'-'+formNumb)


            //alert(w.attr('id'))
            /*var a=''
            for(var i in data)
                a+="\n"+i+"="+data[i]
            alert(a)*/

            //alert(data)
            w.find('.strike-main-sell').val((data.mainStrS/10000).toFixed(4))
            w.find('.strike-main-buy').val((data.mainStrB/10000).toFixed(4))
            w.find('.premium-main-sell').val(parseFloat(data.mainS).toFixed(4))
            w.find('.premium-main-buy').val(parseFloat(data.mainB).toFixed(4))

            w.find('.strike-inner-sell').val((data.innerStrS/10000).toFixed(4))
            w.find('.strike-inner-buy').val((data.innerStrB/10000).toFixed(4))
            w.find('.premium-inner-sell').val(parseFloat(data.innerS).toFixed(4))
            w.find('.premium-inner-buy').val(parseFloat(data.innerB).toFixed(4))

            w.find('.strike-outer-sell').val((data.outerStrS/10000).toFixed(4))
            w.find('.strike-outer-buy').val((data.outerStrB/10000).toFixed(4))
            w.find('.premium-outer-sell').val(parseFloat(data.outerS).toFixed(4))
            w.find('.premium-outer-buy').val(parseFloat(data.outerB).toFixed(4))
        }




	}




	$(document).ready(function(){
		Opt.drawStats();
	})
</script>
