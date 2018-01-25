<?
//vd($MODEL);
$dateFrom = $MODEL['dateFrom'];
$dateTo = $MODEL['dateTo'];
$currency = $MODEL['currency'];

$list = $MODEL['bunchesList'];
$listAssembled = $MODEL['bunchesListArranged'];


#   какие именно пучки отображать на графике
$listAssembledForGraphic = $listAssembled;
foreach ($listAssembledForGraphic as $dt=>$bunches)
    foreach($bunches as $key=>$bunch)
        //if($bunch->currency->code != $currency->code || !in_array($bunch->status->code, [Status2::NEUTRAL, Status2::ACTIVE]))
		if(!$bunch->showOnGraphic)
            unset($listAssembledForGraphic[$dt][$key]);

//vd($listAssembledForGraphic);


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
foreach($list as $bunch)
    if($bunch->currency->code == $currency->code)
        foreach($bunch->items as $val)
            if($val->strikeType->code == StrikeTypeV3::MAIN && $val->type->code == Type::BUY)
                $maxStrike = $val->strike >= $maxStrike ? $val->strike : $maxStrike;

#   высчитываем мин значение страйка по мэйну (например, по баю)
$minStrike=0;
foreach($list as $bunch)
    if($bunch->currency->code == $currency->code)
        foreach($bunch->items as $val)
            if($val->strikeType->code == StrikeTypeV3::MAIN && $val->type->code == Type::BUY)
                $minStrike = $val->strike <= $minStrike || !$minStrike ? $val->strike : $minStrike;

/*vd($maxStrike);
vd($minStrike);*/
?>


<style>
.graphic-tbl{border-collapse: collapse; 100% }
.graphic-tbl td{border: 1px solid #ccc ; }
td.stolb{width: 140px; min-width: 140px;   /*height: 300px;*/ height: 200px;  border: 1px solid #aaa;  padding: 0; margin: 0;   border-top: none; }

.stolbec-wrapper{height: 100%; width: 41px; border: 0px solid green !important; display:inline-block; position: relative;  vertical-align: bottom; border: 0px solid red;  }
.stolbec-wrapper .inner2{display: inline-block; border: 0px solid green; vertical-align: bottom;  width: 10px; background: #88b0bf; position: absolute; bottom: 0;  padding: 0 0  20px 0; box-sizing: border-box;    }
.stolbec-wrapper:hover .inner2{background: #55bf64;  }
.stolbec-wrapper .inner2.stolbec-status-<?=Status2::NEUTRAL?>{background: #999; }
.stolbec-wrapper .inner2.stolbec-status-<?=Status2::ACTIVE?>{background: #00b600; }
.info{display: none; position: absolute; left: 15px; bottom: 0px;  background: oldlace; border: 1px solid #ccc; border-radius: 2px;  font-size: .9em; z-index: 10; font-size: .8em; padding: 3px 6px 3px 3px ; width: 150px; }
.stolbec-wrapper:hover .inner2 .info{display: inline-block; }
.strike-lbl{ position: absolute; top: -34px; left: -10px; font-size: .8em; font-weight: bold; }
.strike-lbl .cur-lbl{ color: #aaa; font-size: .9em;  }
.strike-lbl .strike-itself{}


.stolb:hover{/*background: #eee;*/ }
/*.stolb:hover .info{display: inline-block; }*/
.day-of-week-lbl{font-size: 1.5em; font-weight: bold; }
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


    .bunch-title{ font-weight: bold; }
    .btns{margin: 5px 0 0 0; text-align: left; }
    .btns .btn{font-size: .8em; text-decoration: none !important; }

.status-lbl{border-radius: 3px; padding: 3px 5px; color: #fff; margin: 2px 0 0 0; }

.bunch{display: inline-block; }

.bunch-status-<?=Status2::NEUTRAL?> {}
.bunch-status-<?=Status2::NEUTRAL?> .status-lbl{background: #999; }
.bunch-status-<?=Status2::NEUTRAL?> table{border-left: 6px solid #999; }

.bunch-status-<?=Status2::DONE?> {/*text-decoration: line-through;*/ opacity: 1; background: #eee1ff;  }
.bunch-status-<?=Status2::DONE?> .status-lbl{background: #840084; }
.bunch-status-<?=Status2::DONE?> table{border-left: 6px solid #840084; }


.bunch-status-<?=Status2::ACTIVE?> .status-lbl{background: green; }
.bunch-status-<?=Status2::ACTIVE?> table{border-left: 6px solid #3cae27; }


.weekend{background: #fddeff; }

    <?
    foreach(Status2::$items as $s)
    {?>
    .bunch-status-<?=$s->code?> {}
    .bunch-status-<?=$s->code?> .bunch-status-btn-<?=$s->code?>{font-weight: bold;  }
    <?
    }?>

.row-done-1 .to-be-line-throughed{ text-decoration: line-through; color: #999;  }

.row-done-0 .row-done-0-btn{display: none;}
.row-done-1 .row-done-1-btn{display: none;}


.bunch-showOnGraphic-btn{font-size: 28px; }
.bunch-showOnGraphic-0 .bunch-showOnGraphic-1-btn{display: none;}
.bunch-showOnGraphic-1 .bunch-showOnGraphic-0-btn{display: none;}

.bunch-showOnGraphic-0 .bunch-showOnGraphic-0-btn{opacity: .7; }

</style>


<script>
    function deleteItem(id){
        if(!confirm('Удалить пучок?'))
            return

        var w = $('#bunch-'+id)

        $.ajax({
            url: '/ru/optionalAnalysis/v3/deleteBunch',
            data: {id: id},
            beforeSend: function(){w.css('opacity', .7)},
            complete: function(){w.css('opacity', 1)},
            success: function(data){
                if(!data.error){
                    w.fadeOut()
                }
                else alert(data.error)
            },
            error: function(){alert('Ошибка какая-то.. хмм. Звоните Лахматому')},
        })
    }



    function setBunchStatus(id, status){
        var w = $('#bunch-'+id)

        $.ajax({
            url: '/ru/optionalAnalysis/v3/setBunchStatus',
            data: {id: id, status: status},
            dataType: 'json',
            beforeSend: function(){w.css('opacity', .7)},
            complete: function(){w.css('opacity', 1)},
            success: function(data){
                if(!data.error){
                    //w.fadeOut()
                    //alert(data.status.code)
                    w.removeAttr('class')
                    w.addClass('bunch').addClass('bunch-status-'+data.status.code)

                    w.find('.status-lbl').html(data.status.title)
                }
                else alert(data.error)
            },
            error: function(){alert('Ошибка какая-то.. хмм. Звоните Лахматому')},
        })
    }


    function saveBunchTitle(id, title){
        var w = $('#bunch-'+id)
        $.ajax({
            url: '/ru/optionalAnalysis/v3/saveBunchTitle',
            data: {id: id, title: title},
            dataType: 'json',
            beforeSend: function(){w.css('opacity', .7)},
            complete: function(){w.css('opacity', 1)},
            success: function(data){
                if(!data.error){
                    w.find('.bunch-title').html(title!='' ? title : 'нет названия')
                    w.find('.bunch-title-input-wrapper').slideUp('fast')
                }
                else alert(data.error)
            },
            error: function(){alert('Ошибка какая-то.. хмм. Звоните Лахматому')},
        })
    }

</script>


<div style="margin: 40px 0; ">
    <table class="graphic-tbl" >
        <tr>
            <?
			foreach($dates as $dt)
            {
                $isWeekend = date('N', strtotime($dt)) > 5;
                $columnsCount = count($listAssembledForGraphic[$dt]);
                ?>
                <td class="stolb <?=$isWeekend ? 'weekend' : ''?>" style="min-width: <?=55*($columnsCount ? $columnsCount : 1) ?>px">
                <?
                foreach($listAssembledForGraphic[$dt] as $bunch)
                {
                    $main = $bunch->row(StrikeTypeV3::MAIN, Type::BUY);

                    $pseudoMax = ($maxStrike-$minStrike)*1000;
                    $pseudoCurrent = ($main->strike - $minStrike)*1000;
                    $heightPercent2 = $pseudoCurrent * 100 / $pseudoMax;

                    $rows = [
                        'OS'=>$bunch->row(StrikeTypeV3::OUTER, Type::SELL),
                        'IS'=>$bunch->row(StrikeTypeV3::INNER, Type::SELL),
                        'MB'=>$bunch->row(StrikeTypeV3::MAIN, Type::BUY),
                        'MS'=>$bunch->row(StrikeTypeV3::MAIN, Type::SELL),
                        'IB'=>$bunch->row(StrikeTypeV3::INNER, Type::BUY),
                        'OB'=>$bunch->row(StrikeTypeV3::OUTER, Type::BUY),
                    ];
                    ?>


                    <?
                    if($main)
                    {?>
                        <div class="stolbec-wrapper">
                            <div class="inner2 stolbec-status-<?=$bunch->status->code?>" style="height: <?= $heightPercent2 ?>%;   ">

                                <div class="strike-lbl">
                                    <div class="cur-lbl"><?=$bunch->currency->code?></div>
                                    <div class="strike-itself"><?= strikeVal($main->strike) ?></div>
                                </div>
                                <div class="info">
                                    <div class="title" style="font-size: 1.3em; text-align: left; margin: 0 0 6px 0; ">
                                        [<?=$bunch->currency->code?>][<?=$bunch->id?>] <?=$bunch->title?>
                                        <div class="bunch-status"><?=$bunch->status->code?></div>
                                    </div>
                                    <?
                                    foreach($rows as $lbl=>$item)
                                    {?>
                                        <div id="oa-row-<?=$item->id?>" class="row done-<?=$item->done ? 1 : 0?> <?=$item->strikeType->code==StrikeTypeV3::MAIN?'m':''?> " onclick="Graphic.switchDone(<?=$item->id?>); " title="Нажми, чтобы done/не done">
                                            <div class="lbl"><?=$lbl?>: </div>
                                            <div class="val"><?=$item->result?></div>

                                        </div>
                                        <?
                                    }?>

                                </div>
                            </div>
                        </div>
                        <?
                        }?>
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
                <td style="font-size: .8em; " class="<?=date('N', strtotime($dt)) > 5 ? 'weekend' : ''?>">
                    <div style="white-space: nowrap; "><?=Funx::mkDate($dt)?></div>
                    <div class="day-of-week-lbl"><?=Funx::dayOfWeek($dt)?></div>
                </td>
            <?
            }?>
        </tr>
    </table>
</div>


<hr>





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
            <div class="bunch bunch-status-<?=$bunch->status->code?> bunch-showOnGraphic-<?=$bunch->showOnGraphic?>" id="bunch-<?=$bunch->id?>" style="margin: 0 0 3px 0; ">

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
                        <th></th>
                        <th></th>
                    </tr>
                    <?
                    $i=0;
                    foreach($bunch->items as $item)
                    {?>
                        <tr class="roww row-done-<?=$item->done?>" id="row-<?=$item->id?>">
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

                            <td class="cell-<?=$item->type->code?> to-be-line-throughed" style="font-weight: bold; "><?=$item->type->code?></td>
                            <td class="cell-<?=$item->type->code?> to-be-line-throughed"><?=strikeVal($item->strike)?></td>
                            <td class="cell-<?=$item->type->code?> to-be-line-throughed"><?=strikeVal($item->premium)?></td>
                            <td class="cell-<?=$item->type->code?> to-be-line-throughed"><?=strikeVal($item->result)?></td>

                            <td style="font-size: .7em; "><?=$item->id?></td>
                            <td class="done-wrapper">
                                <a href="#" class="row-done-btn row-done-1-btn" onclick="Graphic.switchDone(<?=$item->id?>); return false; "><i class="fa fa-square-o" aria-hidden="true"></i>
                                </a>
                                <a href="#" class="row-done-btn row-done-0-btn" onclick="Graphic.switchDone(<?=$item->id?>); return false; "><i class="fa fa-check-square-o" aria-hidden="true"></i>
                                </a>
                            </td>

                            <?if(!$i)
                            {?>
                            <td rowspan="<?=$rows?>" class="bunch-info" style="width: 400px; ">
                                <div class="bunch-title-wrapper" style="text-align: left; ">
                                    [<?=$bunch->id?>]
                                    <form action="" onsubmit="saveBunchTitle(<?=$bunch->id?>, $('#bunch-<?=$bunch->id?> .bunch-title-input-wrapper input').val()); return false; " style="display: inline-block; ">
                                        <span class="bunch-title" style="cursor: default; " ondblclick="$('#bunch-<?=$bunch->id?> .bunch-title-input-wrapper').slideToggle('fast')"><?=$bunch->title?$bunch->title:'нет названия'?></span>
                                        <span class="bunch-title-input-wrapper" style="display: none; ">
                                            <input type="text" value="<?=htmlspecialchars($bunch->title)?>">
                                            <button type="submit">ok</button>
                                            <a href="#" onclick="$('#bunch-<?=$bunch->id?> .bunch-title-input-wrapper').slideUp('fast'); return false; ">отмена</a>
                                        </span>
                                    </form>
                                    <br>
                                    <div style="font-size: .7em; display: inline-block; " class="status-lbl"><?=$bunch->status->title?></div>

                                    <!--кнопка ОТОБРАЖАТЬ-->
                                    <span class="showOnGraphic-wrapper" style="margin: 0 0 0 15px; ">
                                        <a href="#" class="bunch-showOnGraphic-btn bunch-showOnGraphic-1-btn" onclick="Graphic.switchShowOnGraphic(<?=$bunch->id?>); return false; "><i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>
                                        <a href="#" class="bunch-showOnGraphic-btn bunch-showOnGraphic-0-btn" onclick="Graphic.switchShowOnGraphic(<?=$bunch->id?>); return false; "><i class="fa fa-eye-slash" aria-hidden="true"></i>
                                        </a>
                                    </span>
                                    <!--//кнопка ОТОБРАЖАТЬ-->

                                </div>
                                <div class="btns">
                                    <a href="#" onclick="deleteItem(<?=$bunch->id?>); return false; " class="btn" style="color: red; "><i class="fa fa-times" aria-hidden="true"></i> удалить</a>
                                    <p>

                                    <?
                                    foreach(Status2::$items as $s)
                                    {?>
                                    <div><a href="#" class="btn bunch-status-btn-<?=$s->code?>" onclick="setBunchStatus(<?=$bunch->id?>, '<?=$s->code?>'); return false; " ><?=$s->title?></a></div>
                                    <?
                                    }?>



                                </div>
                            </td>
                            <?
                            }?>

                        </tr>
                    <?
                        $i++;
                    }?>
                </table>
            </div>
        <?
        }?>
    <?
    }?>
</div>