<?
//vd($MODEL);
$dateFrom = $MODEL['dateFrom'];
$dateTo = $MODEL['dateTo'];
$currency = $MODEL['currency'];

$list = $MODEL['bunchesList'];
$listAssembled = $MODEL['bunchesListArranged'];


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

#   высчитываем мин значение страйка по мэйну (например, по баю)
$minStrike=0;
foreach($list as $val)
    if($val->strikeType->code == StrikeTypeV3::MAIN && $val->type->code == Type::BUY)
        $minStrike = $val->strike <= $minStrike || !$minStrike ? $val->strike : $minStrike;

/*vd($maxStrike);
vd($minStrike);*/
?>


<style>
.graphic-tbl{border-collapse: collapse; 100% }
.graphic-tbl td{border: 1px solid #ccc ; }
td.stolb{width: 80px; /*height: 300px;*/ height: 200px;  border: 1px solid #aaa;  padding: 0; margin: 0;   border-top: none; }

.stolbec-wrapper{height: 100%; border: 0px solid green; display:block; position: relative;  vertical-align: bottom; }
.stolbec-wrapper .inner2{display: inline-block; border: 0px solid red; vertical-align: bottom;  width: 10px; background: #88b0bf; position: absolute; bottom: 0;  padding: 0 0  20px 0; box-sizing: border-box;    }
.strike-lbl{ position: absolute; top: -20px; left: -10px; font-size: .8em; font-weight: bold; }

.info{display: none; position: absolute; left: 30px; top: -30px;  background: oldlace; border: 1px solid #ccc; border-radius: 2px;  font-size: .9em; z-index: 10; font-size: .8em; padding: 3px 6px 3px 3px ; width: 150px; }
.stolb:hover{background: #eee; }
.stolb:hover .info{display: inline-block; }
.day-of-week-lbl{font-size: 1.3em; font-weight: bold; }
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
    <table class="graphic-tbl" >
        <tr>
            <?
            foreach($dates as $dt)
            {
                $data = $listAssembled[$dt][$currency->code];
                $main = $data[StrikeTypeV3::MAIN][Type::BUY];
                $heightPercent = $main->strike * 100 / $maxStrike;

                $pseudoMax = ($maxStrike-$minStrike)*1000;
                $pseudoCurrent = ($main->strike - $minStrike)*1000;
                $heightPercent2 = $pseudoCurrent * 100 / $pseudoMax;
                    //vd($heightPercent2);

                //echo '<hr>';
                ?>
                <td class="stolb">

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


                    <?
                    if($main)
                    {?>
                        <div class="stolbec-wrapper">
                            <div class="inner2" style="height: <?= $heightPercent2 ?>%;   ">

                                <div class="strike-lbl"><?= $main->strike ?></div>
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
                            </div>
                        </div>
                    <?
                    }?>

                </td>
                <?
            }?>
        </tr>

        <tr>
            <?
            foreach($dates as $dt)
            {?>
                <td style="font-size: .7em; ">
                    <?=Funx::mkDate($dt)?>
                    <div class="day-of-week-lbl"><?=Funx::dayOfWeek($dt)?></div>
                </td>
            <?
            }?>
        </tr>
    </table>
</div>


<hr><hr><hr><hr><hr><hr>

<div class="stats2">
    <h1>Статистика 2.0</h1>

    <?

    foreach($listAssembled as $date=>$bunches)
    {?>
        <h1><?=Funx::mkDate($date)?></h1>
        <?
        foreach($bunches as $key=>$bunch)
        {
            $rows = count($bunch->items);
            ?>
            <div class="bunch bunch-<?=$bunch->id?>">
                <div class="title">[<?=$bunch->id?>] <?=$bunch->title?></div>

                <table border="1" class="t">
                    <tr style="border-bottom: 2px solid #000; ">
                        <th>Валюта</th>
                        <th>форвард</th>
                        <th>Тип страйка</th>
                        <th>Тип сделки</th>
                        <th>Страйк</th>
                        <th>Премия</th>
                        <th>Результат</th>
                        <th></th>
                    </tr>
                    <?
                    $i=0;
                    foreach($bunch->items as $item)
                    {?>
                        <tr>
                            <?if(!$i)
                            {?>
                            <td rowspan="<?=$rows?>" style="font-weight: bold; font-size: 1.2em; "><?=$item->currency->code?></td>
                            <td rowspan="<?=$rows?>"><?=$item->forward?></td>
                            <?
                            }?>



                            <?if(!($i%2))
                            {?>
                            <td rowspan="2" style="font-weight: bold; "><?=$item->strikeType->name?></td>
                            <?
                            }?>



                            <td class="cell-<?=$item->type->code?>" style="font-weight: bold; "><?=$item->type->code?></td>
                            <td class="cell-<?=$item->type->code?>"><?=$item->strike?></td>
                            <td class="cell-<?=$item->type->code?>"><?=$item->premium?></td>
                            <td class="cell-<?=$item->type->code?>"><?=$item->result?></td>

                            <td style="font-size: .7em; "><?=$item->id?></td>
                        </tr>
                    <?
                        $i++;
                    }?>
                </table>
            </div>
            <p>
        <?
        }?>
    <?
    }?>
</div>