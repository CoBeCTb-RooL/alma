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
.info{display: none; position: absolute; background: oldlace; border: 1px solid #ccc; border-radius: 2px;  font-size: .9em; z-index: 10; font-size: .8em; padding: 3px 6px 3px 3px ; width: 150px; }
.day:hover{background: #eee; }
.day:hover .info{display: inline-block; }

    .row{text-align: left; border: 0px solid red; padding: 1px ;  cursor: pointer; }
    .row:hover{background: #dadbff; }
    .lbl{display: inline-block; font-weight: bold; width:35px; text-align: right; border: 0px solid red; }
    .val{display: inline-block; width: 62px;  border: 0px solid red;   }

    .m{font-size: 1.3em;  font-weight: bold;  }
    .done-wrapper{display: inline-block; border: 0px solid red; }
    .done{font-size: 14px; padding: 0 0 0 2px;  display: none !important; }
    .m .done{padding: 0; }
    .done-1 .lbl, .done-1 .val {text-decoration: line-through ; opacity: .4; }
    .done-0 .done-btn-0{display: none;}
    .done-1 .done-btn-1{display: none;}
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

                    <?
                    $rows = [
                            'OS'=>$data[StrikeTypeV3::OUTER][Type::SELL],
                            'IS'=>$data[StrikeTypeV3::INNER][Type::SELL],
                            'MB'=>$data[StrikeTypeV3::MAIN][Type::BUY],
                            'MS'=>$data[StrikeTypeV3::MAIN][Type::SELL],
                            'IB'=>$data[StrikeTypeV3::INNER][Type::BUY],
                            'OB'=>$data[StrikeTypeV3::OUTER][Type::BUY],
                    ];
                    ?>
                    <div class="info">
                        <?
                        foreach($rows as $lbl=>$item)
                        {?>
                            <div id="oa-row-<?=$item->id?>" class="row done-<?=$item->done ? 1 : 0?> <?=$item->strikeType->code==StrikeTypeV3::MAIN?'m':''?> " onclick="Graphic.switchDone(<?=$item->id?>); " title="Нажми, чтобы done/не done">
                                <div class="lbl"><?=$lbl?>: </div>
                                <div class="val"><?=$item->result?></div>
                                <!--<div class="done-wrapper">
                                    <a href="#" class="done done-btn-1" onclick="Graphic.switchDone(<?=$item->id?>); return false; "><i class="fa fa-square-o" aria-hidden="true"></i></a>
                                    <a href="#" class="done-btn-0" onclick="Graphic.switchDone(<?=$item->id?>); return false; "><i class="fa fa-check-square-o" aria-hidden="true"></i></a>
                                    </a>
                                </div>-->
                            </div>
                        <?
                        }?>

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
