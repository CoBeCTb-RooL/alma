<?
//vd($MODEL);
$dateFrom = $MODEL['dateFrom'];
$dateTo = $MODEL['dateTo'];
$currency = $MODEL['currency'];

$list = $MODEL['list'];
$listAssembled = $MODEL['listAssembled'];


#   рассчитываем массив дат (диапазон)
$dates = [];
$d = $dateFrom;
while($d <= $dateTo)
{
    $dates[] = $d;
    $d = date('Y-m-d', strtotime($d . ' + 1 day'));
}


#   высчитываем макс значение страйка по мэйну (например, по баю)
$maxStrike=0;
foreach($list as $val)
    if($val->strikeType->code == StrikeTypeV3::MAIN && $val->type->code == Type::BUY)
        $maxStrike = $val->strike >= $maxStrike ? $val->strike : $maxStrike;
?>


<style>
.stolbec{width: 10px; background: lightsalmon; border: 0px solid #1d2b3a; display: inline-block; position: relative; }
.strike-lbl{ position: absolute; top: -20px; left: -10px; font-size: .8em; font-weight: bold; }
.day{}
.info{display: none; position: absolute; background: oldlace; border: 1px solid #ccc; border-radius: 2px;  font-size: .9em; z-index: 10; font-size: .8em; padding: 3px 6px 3px 3px ; }
.day:hover{background: #eee; }
.day:hover .info{display: inline-block; }

    .row{text-align: left; }
    .lbl{display: inline-block; font-weight: bold; width: 30px; text-align: right; }
    .val{display: inline-block; }

    .m{font-size: 1.3em; }
</style>


<div style="margin: 40px 0; ">
    <table style="border-collapse: collapse; ">
        <tr>
            <?
            foreach($dates as $dt)
            {
                $data = $listAssembled[$dt][$currency->code];
                $main = $data[StrikeTypeV3::MAIN][Type::BUY];
                $heightPercent = $main->strike * 100 / $maxStrike;
                ?>
                <td class="day" style="width: 80px; height: 300px;  border: 1px solid #aaa; text-align: center; padding: 0; margin: 0;  vertical-align: bottom; border-top: none; ">

                    <div class="info">
                        <div class="row">
                            <div class="lbl">OS: </div>
                            <div class="val"><?=$data[StrikeTypeV3::OUTER][Type::SELL]->premium?></div>
                        </div>
                        <div class="row">
                            <div class="lbl">IS: </div>
                            <div class="val"><?=$data[StrikeTypeV3::INNER][Type::SELL]->premium?></div>
                        </div>

                        <div class="row m">
                            <div class="lbl">MB: </div>
                            <div class="val"><?=$data[StrikeTypeV3::MAIN][Type::BUY]->premium?></div>
                        </div>
                        <div class="row m">
                            <div class="lbl">MS: </div>
                            <div class="val"><?=$data[StrikeTypeV3::MAIN][Type::SELL]->premium?></div>
                        </div>

                        <div class="row">
                            <div class="lbl">IB: </div>
                            <div class="val"><?=$data[StrikeTypeV3::INNER][Type::BUY]->premium?></div>
                        </div>
                        <div class="row">
                            <div class="lbl">OB: </div>
                            <div class="val"><?=$data[StrikeTypeV3::OUTER][Type::BUY]->premium?></div>
                        </div>
                    </div>

                    <div class="stolbec" style="height: <?=$heightPercent?>%; ">
                        <div class="strike-lbl"><?=$main->strike?></div>

                    </div>
                </td>
                <?
            }?>
        </tr>

        <tr>
            <?
            foreach($dates as $dt)
            {?>
                <td style="font-size: .7em; "><?=Funx::mkDate($dt)?></td>
            <?
            }?>
        </tr>
    </table>
</div>
