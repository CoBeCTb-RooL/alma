<?
$buyColor = '#b2dcff';
$sellColor = '#d3aaff';


$date = $MODEL['date'];
$datePrev = $MODEL['datePrev'];
$dateNext = $MODEL['dateNext'];
$today = $MODEL['today'];


$cur = $MODEL['currency'];


$list = $MODEL['list'];
$strikes = [];
$zones = [];
foreach ($list as $item)
    if($item->isZone)
        $zones[] = $item;
    else
		$strikes[] = $item;


#   разбираемся со страйками зон
foreach ($zones as $z)
{
	$z->closestBuyId='';
	$z->closestSellId='';

	$deltaBuy = 0;
	$deltaSell = 0;
    $z->strikes = [];
    $i=0;
    foreach ($strikes as $s)
    {
        #   разбираем БАЙи
        if($s->premiumBuy <= $z->premiumBuy)
        {
			if(!$z->closestBuy)
			{
				$deltaBuy = $z->premiumBuy - $s->premiumBuy;
				$z->closestBuy = $s;
			}
			elseif($z->premiumBuy - $s->premiumBuy < $deltaBuy)
            {
                $z->closestBuy = $s;
                $deltaBuy = $z->premiumBuy - $s->premiumBuy;
            }
        }

		#   разбираем СЕЛЛы
		if($s->premiumSell >= $z->premiumSell)
		{
			if(!$z->closestSell)
			{
				$deltaSell = $s->premiumSell - $z->premiumSell;
				$z->closestSell = $s;
			}
            elseif($s->premiumSell - $z->premiumSell < $deltaSell)
			{
				$z->closestSell = $s;
				$deltaSell = $s->premiumSell - $z->premiumSell;
			}
		}

        $z->strikes[] = $s;
//        $i++;
//        if($i >=3)
//            break;
    }
}


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






<h1>Опционный анализ v4.0</h1>

Валюта:
<?
foreach($MODEL['currencies'] as $c)
{?>
    <a href="?currency=<?=$c->code?>" style="; <?=$c->code == $currency->code ? 'font-weight: bold; ' : ''?>"><?=$c->code?></a>
<?
}?>
<p></p>


<div class="day-nav" style="margin: 0 0 14px 0; ">
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



<form class="form" action="/ru/optionalAnalysis/v4/submit" id="form" target="frame7" onsubmit="if(confirm('Сохранить данные?')){return true; } return false; ">
    <input type="hidden" name="currency" value="<?=$cur->code?>">
    <h3><?=$cur->code?></h3>

    <input  name="date" id="date" value="<?=$date?>" style="width:70px" type="text">
    <img id="calendar-btn" src="/js/calendar/calendar.jpg" style="border:0px;">
    <script>
        Calendar.setup({
            inputField     :    "date",      // id of the input field
            ifFormat       :    "%Y-%m-%d",       // format of the input field
            showsTime      :    false,            // will display a time selector
            button         :    "calendar-btn",   // trigger for the calendar (button ID)
            singleClick    :    true,           // double-click mode
            step           :    1                // show all years in drop-down boxes (instead of every other year as default)
        });
    </script>

    Форвард: <input type="text" class="forward" name="forward" value="">

    <p>Коммент: <input type="text" name="comment" style="width: 170px; ">
    <p>

    <p>
    <div class="data-input" style="display: ; ">
        <textarea name="data" class="global-ta" onkeyup="/*Opt.parseData2('<?=$cur->code?>')*/" style="height: 75px; width: 200px;  ">0.0137	11700.0	0.0014
0.0097	11750.0	0.0025
0.0064	11800.0	0.0042
0.0038	11850.0	0.0066
0.0021	11900.0	0.0098
        </textarea>

        <br><label><input type="checkbox" name="isZone">Зона</label>
    </div>

<!--    <fieldset class="strike">-->
<!--        <legend>--><?//=$st->name?><!--213123: </legend>-->
<!--        <table class="t" border="1" style="border-collapse: collapse; ">-->
<!--            <tr>-->
<!--                <th></th>-->
<!--                <th>Страйк</th>-->
<!--                <th >Премия</th>-->
<!--                <th >Результат</th>-->
<!--            </tr>-->
<!--			--><?//
//			foreach(Type::$items as $t)
//			{?>
<!--                <tr>-->
<!--                    <td>--><?//=$t->name?><!--</td>-->
<!--                    <td><input type="text" class="strike strike---><?//=$t->code?><!--" name="strike[--><?//=$cur->code?><!--][--><?//=$t->code?><!--]" value="--><?//=$todayData[$cur->code][$t->code]->strike?><!--"></td>-->
<!--                    <td><input type="text" class="premium---><?//=$t->code?><!--" name="premium[--><?//=$cur->code?><!--][--><?//=$t->code?><!--]" value="--><?//=$todayData[$cur->code][$t->code]->premium?><!--"></td>-->
<!--                    <td class="result---><?//=$t->code?><!--">--><?//=$todayData[$cur->code][$t->code]->result?><!--</td>-->
<!--                </tr>-->
<!--				--><?//
//			}?>
<!--        </table>-->
<!---->
<!--    </fieldset>-->


    <p>
        <button type="submit" >сохранить</button>
</form>


<p></p>



<p>

<?
foreach ($zones as $z)
{?>
    <div class="zone" >
        <b><?=$z->comment?></b>
        <table border="1" style="">
            <tr style="border-top: 3px solid #000; ">
                <td rowspan="2" style="font-size: .8em; border-left: 3px solid #000;  "><?=$z->id?>. </td>
                <td rowspan="2" style="font-weight: bold; font-size: 1.1em; "><?=$z->strike?> </td>
                <td>Buy</td>
                <td><?=$z->premiumBuy?></td>
                <td style="border-right: 3px solid #000; background: <?=$buyColor?>;  "><?=$z->resultBuy?></td>
            </tr>
            <tr style="border-bottom: 3px solid #000; ">
                <td>Sell</td>
                <td><?=$z->premiumSell?></td>
                <td style="border-right: 3px solid #000; background: <?=$sellColor?>;"><?=$z->resultSell?></td>
            </tr>
<!--            <tr><td colspan="5"></td></tr>-->
    <?
    foreach ($z->strikes as $s)
    {
        $isClosestBuy = $z->closestBuy->id == $s->id;
        $isClosestSell = $z->closestSell->id == $s->id;
        ?>
            <tr style="font-size: .9em; ">
                <td rowspan="2" style="font-size: .8em;  "><?=$s->id?>. </td>
                <td rowspan="2" style="font-weight: bold; font-size: 1.1em; "><?=$s->strike?> </td>
                <td>Buy</td>
                <td ><?=$s->premiumBuy?></td>
                <td style="<?=($isClosestBuy ? ' border: 3px solid #2751FF; background: '.$buyColor.'' : '')?>"><?=$s->resultBuy?></td>
            </tr>
        <tr style="font-size: .9em; border-bottom: 2px solid #000; ">
                <td>Sell</td>
                <td><?=$s->premiumSell?></td>
                <td style="<?=($isClosestSell ? ' border: 3px solid #b100ff; background: '.$sellColor.'' : '')?>"><?=$s->resultSell?></td>
            </tr>
    <?
    }?>
        </table>
    </div>
    <hr>
<?
}?>




<iframe src="" frameborder="0" name="frame7" style="display: none ; border: 1px solid #000; background: #ececec; height: 400px; width: 100%; ">wqe</iframe>