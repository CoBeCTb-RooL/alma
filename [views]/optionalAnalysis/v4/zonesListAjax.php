<?
$buyColor = '#b2dcff';
$sellColor = '#d3aaff';

$date = $MODEL['date'];
$cur = $MODEL['currency'];
$zones = $MODEL['list'];
?>


<hr>
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
        <p>
        forward: <b><?=$z->forward?></b>
        <!--<button onclick='Zones.setZoneDataToForm(<?=$z->data?>)' style="font-size: .9em; padding: 3px; ">Внести данные зоны в форму</button>-->
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=""  onclick='Zones.setZoneDataToForm(<?=$z->data?>); return false; ' style="font-size: .9em;  ">Внести данные зоны в форму</a>
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
                <td>to Sell</td>
                <td><?=$z->premiumBuy?></td>
                <td style="background: <?=$sellColor?>;"><?=$z->resultSell?></td>
                <td></td>
                <td rowspan="2" style="border-right: 3px solid #000;"><a href="#" onclick="Zones.deleteStrike(<?=$z->id?>); return false; ">удалить</a></td>
            </tr>
            <tr style="border-bottom: 3px solid #000; background: #eeeaed">
                <td>to Buy</td>
                <td><?=$z->premiumSell?></td>
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
                    <td>to Sell</td>
                    <td><?=$s->premiumBuy?></td>
                    <td style="<?=($isClosestSell ? ' border: 3px solid #b100ff; background: '.$sellColor.'' : '')?>"><?=$s->resultSell?></td>
                    <td style="font-size: .8em; text-align: left;  ">
                        дельта: <?=strikeVal($s->deltaSell)?>
                        <br>
                        <?
                        if($z->closestAbsSell->id == $s->id)
                        {?>
                            <div style="display:inline-block; margin: 2px 0 0 0;  font-weight: bold; color: #fff; background: #b100ff; border-radius: 2px; padding: 1px 2px;  ">по модулю</span>
                        <?
                        }?>
                    </td>
                    <td rowspan="2"><a href="#" onclick="Zones.deleteStrike(<?=$s->id?>);; return false; ">удалить</a></td>
                </tr>
                <tr style="font-size: .9em; border-bottom: 3px solid #000; ">
                    <td>to Buy</td>
                    <td ><?=$s->premiumSell?></td>
                    <td style="<?=($isClosestBuy ? ' border: 3px solid #2751FF; background: '.$buyColor.'' : '')?>"><?=$s->resultBuy?></td>
                    <td style="font-size: .8em; text-align: left;  ">
                        дельта: <?=strikeVal($s->deltaBuy)?>
                        <br>
						<?
						if($z->closestAbsBuy->id == $s->id)
						{?>
                        <div style="display:inline-block; margin: 2px 0 0 0;  font-weight: bold; color: #fff; background: #2751FF; border-radius: 2px; padding: 1px 2px;  ">по модулю</span>
                        <?
                        }?>
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
        <td style="border-right: 3px solid #000; "><a href="#"  onclick="Zones.deleteStrike(<?=$z->id?>); return false; ">удалить</a></td>
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
            <td ><a href="#"  onclick="Zones.deleteStrike(<?=$s->id?>); return false; ">удалить</a></td>
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
