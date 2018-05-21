<?
$buyColor = '#b2dcff';
$sellColor = '#d3aaff';


$date = $MODEL['date'];
$datePrev = $MODEL['datePrev'];
$dateNext = $MODEL['dateNext'];
$today = $MODEL['today'];


$cur = $MODEL['currency'];


$zones = $MODEL['list'];



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
    <a href="?date=<?=$datePrev?>&currency=<?=$cur->code?>">&larr; Предыдущий</a>
	<?php
	if($dateNext)
	{?>
        <a href="?date=<?=$dateNext?>&currency=<?=$cur->code?>">Следующий &rarr;</a>
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
    <p>Данные зоны: <br><input type="text" name="zoneData" style="width: 204px; ">
    <p>
    <div class="data-input" style="display: ; ">
        Страйки:<br>
        <textarea name="data" class="global-ta" onkeyup="/*Opt.parseData2('<?=$cur->code?>')*/" style="height: 75px; width: 200px;  "></textarea>

<!--        <br><label><input type="checkbox" name="isZone">Зона</label>-->
    </div>
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
	<?
	if(!$z->closestBuy)
	{?>
        <div style="color: red; ">- Нет <b>BUY</b>, у которого <b>resultBuy <= <?=$z->resultBuy?></b></div>
		<?
	}?>
	<?
	if(!$z->closestSell)
	{?>
        <div style="color: red; ">- Нет <b>SELL</b>, у которого <b>resultSell >= <?=$z->resultSell?></b>!!!</div>
		<?
	}?>
    <?
    //vd($z->closestBuy)?>
        <table border="1" style="">
            <tr>
                <th>id</th>
                <th>cur</th>
                <th>strike</th>
                <th>type</th>
                <th>prem</th>
                <th>result</th>
                <th>delta</th>
                <th>action</th>
            </tr>
            <tr style="border-top: 3px solid #000; background: #eeeaed;  ">
                <td rowspan="2" style="font-size: .8em; border-left: 3px solid #000;  "><?=$z->id?>. </td>
                <td rowspan="2" style="font-weight: bold; font-size: 1.1em; "><?=$z->currency->code?> </td>
                <td rowspan="2" style="font-weight: bold; font-size: 1.1em; "><?=$z->strike?> </td>
                <td>Sell</td>
                <td><?=$z->premiumSell?></td>
                <td style="background: <?=$sellColor?>;"><?=$z->resultSell?></td>
                <td></td>
                <td rowspan="2" style="border-right: 3px solid #000;"><a href="#" onclick="deleteStrike(<?=$z->id?>); return false; ">удалить</a></td>
            </tr>
            <tr style="border-bottom: 3px solid #000; background: #eeeaed">
                <td>Buy</td>
                <td><?=$z->premiumBuy?></td>
                <td style=" background: <?=$buyColor?>;  "><?=$z->resultBuy?></td>

                <td></td>
            </tr>
    <?
    foreach ($z->strikes as $s)
    {
        $isClosestBuy = $z->closestBuy->id == $s->id;
        $isClosestSell = $z->closestSell->id == $s->id;
        ?>
            <tr style="font-size: .9em; ">
                <td rowspan="2" style="font-size: .8em;  "><?=$s->id?>. </td>
                <td rowspan="2" style="font-weight: bold; font-size: 1.1em; "><?=$z->currency->code?> </td>
                <td rowspan="2" style="font-weight: bold; font-size: 1.1em; "><?=$s->strike?> </td>
                <td>Sell</td>
                <td><?=$s->premiumSell?></td>
                <td style="<?=($isClosestSell ? ' border: 3px solid #b100ff; background: '.$sellColor.'' : '')?>"><?=$s->resultSell?></td>
                <td style="font-size: .8em; text-align: left;  ">
                    дельта: <?=strikeVal($s->deltaSell)?>
                </td>
                <td rowspan="2"><a href="#" onclick="deleteStrike(<?=$s->id?>);; return false; ">удалить</a></td>
            </tr>
            <tr style="font-size: .9em; border-bottom: 3px solid #000; ">
                <td>Buy</td>
                <td ><?=$s->premiumBuy?></td>
                <td style="<?=($isClosestBuy ? ' border: 3px solid #2751FF; background: '.$buyColor.'' : '')?>"><?=$s->resultBuy?></td>
                <td style="font-size: .8em; text-align: left;  ">
                    дельта: <?=strikeVal($s->deltaBuy)?>
                </td>

            </tr>
    <?
    }?>
        </table>

<!--    ---------------------->
<!--    ---------------------->
   <!-- <table border="1" style="">
        <tr style="border-top: 3px solid #000;  border-bottom: 3px solid #000; ">
            <td style="font-size: .8em; border-left: 3px solid #000;  "><?=$z->id?>. </td>
            <td  style="font-weight: bold; font-size: 1.1em; "><?=$z->currency->code?> </td>
            <td style="font-weight: bold; font-size: 1.1em; "><?=$z->strike?> </td>
            <td>Buy</td>
            <td><?=$z->premiumBuy?></td>
            <td style="background: <?=$buyColor?>;  "><?=$z->resultBuy?></td>
            <td></td>


            <td>Sell</td>
            <td><?=$z->premiumSell?></td>
            <td style="background: <?=$sellColor?>;"><?=$z->resultSell?></td>
            <td></td>
            <td style="border-right: 3px solid #000; "><a href="#"  onclick="deleteStrike(<?=$z->id?>); return false; ">удалить</a></td>
        </tr>
		<?
		foreach ($z->strikes as $s)
		{
			$isClosestBuy = $z->closestBuy->id == $s->id;
			$isClosestSell = $z->closestSell->id == $s->id;
			?>
            <tr style="font-size: .9em; ">
                <td style="font-size: .8em;  "><?=$s->id?>. </td>
                <td  style="font-weight: bold; font-size: 1.1em; "><?=$z->currency->code?> </td>
                <td style="font-weight: bold; font-size: 1.1em; "><?=$s->strike?> </td>
                <td>Buy</td>
                <td ><?=$s->premiumBuy?></td>
                <td style="<?=($isClosestBuy ? ' border: 3px solid #2751FF; background: '.$buyColor.'' : '')?>"><?=$s->resultBuy?></td>
                <td style="font-size: .8em; text-align: left;  ">дельта: <?=strikeVal($z->resultBuy-$s->resultBuy)?></td>


                <td>Sell</td>
                <td><?=$s->premiumSell?></td>
                <td style="<?=($isClosestSell ? ' border: 3px solid #b100ff; background: '.$sellColor.'' : '')?>"><?=$s->resultSell?></td>
                <td style="font-size: .8em; text-align: left;  ">дельта: <?=strikeVal($s->resultSell-$z->resultSell)?></td>
                <td ><a href="#"  onclick="deleteStrike(<?=$s->id?>); return false; ">удалить</a></td>
            </tr>
			<?
		}?>
    </table>-->
<!--    ---------------------->
<!--    ---------------------->


    </div>
    <hr>
<?
}?>










<iframe src="" frameborder="0" name="frame7" style="display: none ; border: 1px solid #000; background: #ececec; height: 400px; width: 100%; ">wqe</iframe>


<script>
    var deleteStrike = function(id){
        if(!confirm('удалить?'))
            return

        $.ajax({
            url: '/ru/optionalAnalysis/v4/deleteStrikeAjax',
            data: {id: id},
            beforeSend: function(){ $('.stats').css('opacity', .6); $('.stat-loading').slideDown('fast');  },
            complete: function(){ $('.stats').css('opacity', 1); $('.stat-loading').slideUp('fast');  },
            success: function(data){
                // $('.stats').html(data)
                location.href=location.href
            },
            error: function(){}
        })
    }

</script>