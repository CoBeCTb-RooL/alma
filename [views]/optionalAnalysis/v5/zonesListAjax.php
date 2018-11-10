<?
$buyColor = '#b2dcff';
$sellColor = '#d3aaff';

$date = $MODEL['date'];
$cur = $MODEL['currency'];
$bunches = $MODEL['list'];
?>


<hr>
<?
foreach ($bunches as $bunch)
{?>
    <div class="zone" >
        <b><?=$bunch->title?></b> <sup style="font-size: .5em; "><?=$bunch->id?>, <?=Funx::mkDate($bunch->dt)?></sup>
   
        <p>
        forward: <b><?=$bunch->forward?></b> &nbsp;&nbsp; открытие: <b><?=$bunch->openingPrice?></b>
        <!--<button onclick='Zones.setZoneDataToForm(<?=$bunch->data?>)' style="font-size: .9em; padding: 3px; ">Внести данные зоны в форму</button>-->
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href=""  onclick='Zones.setZoneDataToForm(<?=$bunch->data?>); return false; ' style="font-size: .9em;  ">Внести данные зоны в форму</a>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=""  onclick='Zones.deleteBunch(<?=$bunch->id?>); return false; ' style="font-size: .9em;  color: red;  ">&times; удалить</a>
        <table border="1" style="">
            <tr>
                <th>id</th>
                <th>cur</th>
                <th>strike</th>
                <th>type</th>
                <th>prem</th>
                <th>result</th>
                <th style="color: blue; ">MAX</th>
                <th>action</th>
            </tr>

            <?
            foreach ($bunch->strikes as $s)
            {?>
                <tr style="font-size: .9em; " class="strike-row strike-row-<?=$s->color->code?>">
                    <td rowspan="2" style="font-size: .8em;  " class="id"><?=$s->id?>. </td>
                    <td rowspan="2" style="font-weight: bold; font-size: 1.1em; "><?=$bunch->currency->code?> </td>
                    <td rowspan="2" style="font-weight: bold; font-size: 1.1em; "><?=$s->strike?> </td>
                    <td>to Sell</td>
                    <td><?=$s->premiumBuy?></td>
                    <td ><?=$s->resultSell?></td>
                    <td rowspan="2" style="font-weight: bold; font-size: 1.1em; color: blue;  "><?=$s->max()?> </td>

                    <td rowspan="2">
                        <!--<a href="#" onclick="Zones.deleteStrike(<?=$s->id?>);; return false; ">удалить</a>-->
                    </td>
                </tr>
                <tr style="font-size: .9em; border-bottom: 3px solid #000; " class="strike-row strike-row-<?=$s->color->code?>">
                    <td>to Buy</td>
                    <td ><?=$s->premiumSell?></td>
                    <td ><?=$s->resultBuy?></td>


                </tr>
                <?
            }?>
        </table>

    </div>
    <hr>
    <?
}?>
