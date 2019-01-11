<?
$buyColor = '#b2dcff';
$sellColor = '#d3aaff';

$date = $MODEL['date'];
$cur = $MODEL['currency'];
$bunches = $MODEL['list'];



//vd($bunches);
?>


<hr>
<?foreach ($bunches as $bunch): ?>
    <?
    #   выясняем бОльшую цель
    $strikeWithMaxPotentialGoal = $bunch->strikeWithMaxPotentialGoal();
    ?>
    <div class="zone" >
        <b><?=$bunch->title?></b> <sup style="font-size: .5em; "><?=$bunch->id?>, <?=Funx::mkDate($bunch->dt)?></sup>
        <p>
        forward: <b><?=$bunch->forward?></b> &nbsp;&nbsp; открытие: <b><?=$bunch->openingPrice?></b>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <a href=""  onclick='Zones.setZoneDataToForm(<?=$bunch->data?>); return false; ' style="font-size: .9em;  ">Внести данные зоны в форму</a>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=""  onclick='Zones.deleteBunch(<?=$bunch->id?>); return false; ' style="font-size: .9em;  color: red;  ">&times; удалить</a>
        <div class="table">
            <table border="1" style="">
                <tr>
                    <th>id</th>
                    <th>cur</th>
                    <th>strike</th>
                    <th>type</th>
                    <th>prem</th>
                    <th>result</th>
                    <th style="color: blue; ">MAX</th>
                    <th>goal</th>
                    <th>out of range</th>
                    <th>closest max</th>
                    <th style="width: 10px; padding: 0; margin: 0; "></th>
                    <th>closest max2</th>
                    <th>action</th>
                </tr>

                <?foreach ($bunch->strikes as $s): ?>
                    <?
                    $isStrikeWithMaxPotentialGoal = $strikeWithMaxPotentialGoal && $s->id == $strikeWithMaxPotentialGoal->id;
                    ?>
                    <tr style="font-size: .9em; " class="strike-row strike-row-<?=$s->color->code?>">
                        <td rowspan="2" style="font-size: .8em;  " class="id"><?=$s->id?>. </td>
                        <td rowspan="2" style="font-weight: bold; font-size: 1.1em; "><?=$bunch->currency->code?> </td>
                        <td rowspan="2" style="font-weight: bold; font-size: 1.1em; "><?=$s->strike?> </td>
                        <td>to Sell</td>
                        <td><?=$s->premiumBuy?></td>
                        <td ><?=$s->resultSell?></td>
                        <td rowspan="2" style="font-weight: bold; font-size: 1.1em; color: blue;  "><?=$s->max()?> </td>

                        <td rowspan="2" class="potentialGoal <?=$isStrikeWithMaxPotentialGoal ? 'highest' : ''?>">
                            <?if(in_array($s->color->code, [Color::LIGHT_GREEN, Color::LIGHT_RED, Color::GREEN, Color::RED,])):?>
                                <?=$s->potentialGoal()?>
                                <?if($isStrikeWithMaxPotentialGoal):?>
                                    <br><span style="margin: 2px 0 0 0; font-size: .7em;   font-weight: bold; color: #fff; background: #be00be; border-radius: 2px; padding: 1px 2px;  ">max</span>
                                <?endif;?>
                            <?endif;?>
                        </td>

                        <td rowspan="2">
                            <?if($s->isOutOfRange($bunch->black())):?>
                            <span class="outOfRange">out of range!</span>
                            <?endif;?>
                        </td>


                        <td rowspan="2" style="padding: 0;  text-align: left; ">
                            <?if(count($s->minDeltasAgainstMax)):?>
                                <?foreach ($s->minDeltasAgainstMax as $deltaPair):?>
                                    <?//vd($deltaPair)?>
                                    <?//vd($s->minDeltasAgainstMax2)?>
                                    <div style="width: 130px ; height: 55px; padding: 5px ; background: <?=$deltaPair['strike']->color->bgColor?>; border-right: 5px solid <?=$deltaPair['strike']->color->color?>; ">
                                        <span style="font-weight: bold; font-size: 1.2em; "><?=$deltaPair['type']?></span>

                                        <?if($deltaPair['strike']->id == $s->id):?>
                                            <span style="color: #fff; background: #47b73b; font-size: .7em; padding: 1px 2px; border-radius: 3px; ">сам же!</span>
                                        <?else:?>
                                            <sup style="font-size: .7em; ">(<?=$deltaPair['strike']->id?>)</sup>
                                        <?endif;?>
                                        <br>
                                        <div style="padding: 3px; ">
                                            delta = <span style="font-size: 1.3em; font-weight: bold; "><?=$deltaPair['delta']?></span>
                                        </div>
                                        <div style="white-space: nowrap; font-size: .8em;  ">|<?=$deltaPair['resultVal']?> - <?=$deltaPair['strike']->max()?>| = <?=$deltaPair['delta']?></div>
                                        <br>
                                        <!--                                    --><?//=$deltaPair['strike']->id?>
                                    </div>
                                <?endforeach;?>
                            <?endif?>
                        </td>




                        <td rowspan="2" style="background: #fff; padding: 0; margin: 0;  "></td>


                        <!--второстепенный-->
                        <td rowspan="2" style="padding: 0;  text-align: left; ">
                            <?if(count($s->minDeltasAgainstMax)):?>
                                <?foreach ($s->minDeltasAgainstMax as $deltaPair1):?>
                                <?
                                $arr = [];
                                $arr = $s->initMinDeltasAgainstMax($bunch, $deltaPair1['type'] == Type::SELL ? Type::BUY : Type::SELL);
                                ?>
                                    <?foreach ($s->minDeltasAgainstMax as $deltaPair):?>
                                        <?//vd($deltaPair['strike'])?>
                                        <?//vd($s->minDeltasAgainstMax2)?>
                                        <div style="width: 130px ; height: 55px; padding: 5px ; background: <?=$deltaPair['strike']->color->bgColor ? $deltaPair['strike']->color->bgColor : '#fff'?>; border-right: 5px solid <?=$deltaPair['strike']->color->color?>; ">
                                            <span style="font-weight: bold; font-size: 1.2em; "><?=$deltaPair['type']?></span>

                                            <?if($deltaPair['strike']->id == $s->id):?>
                                                <span style="color: #fff; background: #47b73b; font-size: .7em; padding: 1px 2px; border-radius: 3px; ">сам же!</span>
                                            <?else:?>
                                                <sup style="font-size: .7em; ">(<?=$deltaPair['strike']->id?>)</sup>
                                            <?endif;?>
                                            <br>
                                            <div style="padding: 3px; ">
                                                delta = <span style="font-size: 1.3em; font-weight: bold; "><?=$deltaPair['delta']?></span>
                                            </div>
                                            <div style="white-space: nowrap; font-size: .8em;  ">|<?=$deltaPair['resultVal']?> - <?=$deltaPair['strike']->max()?>| = <?=$deltaPair['delta']?></div>
                                            <br>
                                            <!--                                    --><?//=$deltaPair['strike']->id?>
                                        </div>
                                    <?endforeach;?>
                                <?endforeach;?>
                            <?endif?>
                        </td>
                        <!--/второстепенный-->







                        <td rowspan="2">
                            <!--<a href="#" onclick="Zones.deleteStrike(<?=$s->id?>);; return false; ">удалить</a>-->
                        </td>
                    </tr>
                    <tr style="font-size: .9em; border-bottom: 3px solid #000; " class="strike-row strike-row-<?=$s->color->code?>">
                        <td>to Buy</td>
                        <td ><?=$s->premiumSell?></td>
                        <td ><?=$s->resultBuy?></td>
                    </tr>
                <?endforeach;?>
            </table>
        </div>



        <!--ADVISOR-->
        <div class="advisor">
            <?if($bunch->advisor):?>
                <? $i=1;?>
                <?foreach ($bunch->advisor->aspects as $aspectNum=>$aspect):?>
                    <div>
                        <span class="num"><?=$i?>. </span> <span class="question"><?=$aspect->question?> <sup style="font-size: .6em; "><?=$aspectNum?></sup>  <b><?=($aspect->result === true ? '<span style="color: green">ДА</span>' : '<span style="color: red; ">НЕТ</span>')?></b></span>
                    </div>
                    <?$i++;?>
                <?endforeach;?>
                <p>
                <b>советую:</b>
                <div class="advise" ><?=nl2br($bunch->advisor->advise)?></div>
            <?endif?>
        </div>
        <!--/ADVISOR-->


        <div class="clear"></div>
    </div>
    <hr>
<?endforeach;?>
