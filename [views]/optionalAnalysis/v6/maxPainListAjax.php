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
    ?>
    <div class="zone" >
        <b><?=$bunch->title?></b> <sup style="font-size: .5em; "><?=$bunch->id?>, <?=Funx::mkDate($bunch->dt)?></sup>
        <p>
        &nbsp;&nbsp;&nbsp;&nbsp;
            <a href=""  onclick='MaxPain.setDataToForm(<?=$bunch->data?>); return false; ' style="font-size: .9em;  ">Внести данные зоны в форму</a>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=""  onclick='MaxPain.deleteBunch(<?=$bunch->id?>); return false; ' style="font-size: .9em;  color: red;  ">&times; удалить</a>
        <div class="table">
            <table border="1" style="" class="t">
                <tr>
                    <th>id</th>
                    <th>cur</th>
                    <th>strike</th>
                    <th>oiCall</th>
                    <th>oiPut</th>
                    <th></th>
                    <th></th>
                </tr>

                <?foreach ($bunch->strikes as $s): ?>
                    <tr style="font-size: .9em; " class="strike-row strike-row-<?=$s->id?> strike-row-<?=$s->color->code?>">
                        <td  style="font-size: .6em;  " class="id"><?=$s->id?>. </td>
                        <td  style="font-weight: normal; font-size: 1.1em; "><?=$bunch->currency->code?> </td>
                        <td  style="font-weight: bold; font-size: 1.1em; "><?=$s->strike?> </td>
                        <td ><?=$s->oiCall?></td>
                        <td ><?=$s->oiPut?></td>

                        <td>
                            <table class="t2" border="1">
                                <?foreach ($s->intrinsicValues['call'] as $sId=>$innerS):?>
                                    <tr style="<?=!($s->totalIntrinsicValues['call'][$sId]) ? 'opacity:  .5; ' : ''?>">
                                        <td><?='intrinsic[call]['.$sId.'] = <b>'.$s->intrinsicValues['call'][$sId].'</b>'?></td>
                                        <td>&times; <b><?=$bunch->strikes[$sId]->oiCall?></b></td>
                                        <td>= <b><?=$s->totalIntrinsicValues['call'][$sId]?></b></td>
                                    </tr>
                                <?endforeach;?>
                                    <tr>
                                        <td colspan="2" style="text-align: right">E</td>
                                        <td  >= <b><?=$s->totalIntrinsicSum['call']?></b></td>
                                    </tr>
                            </table>
                            <?if($s->totalIntrinsicSum['call'] == $bunch->maxPainCall):?>
                            <h1>MIN!</h1>
                            <?endif; ?>
<!--                            <div>E = <b>--><?//=$s->totalIntrinsicSum['call']?><!--</b></div>-->
                        </td>

                        <td>
                            <table class="t2" border="1">
                                <?foreach ($s->intrinsicValues['put'] as $sId=>$innerS):?>
                                    <tr style="<?=!($s->totalIntrinsicValues['put'][$sId]) ? 'opacity:  .5; ' : ''?>">
                                        <td><?='intrinsic[put]['.$sId.'] = <b>'.$s->intrinsicValues['put'][$sId].'</b>'?></td>
                                        <td>&times; <b><?=$bunch->strikes[$sId]->oiPut?></b></td>
                                        <td>= <b><?=$s->totalIntrinsicValues['put'][$sId]?></b></td>
                                    </tr>
                                <?endforeach;?>
                                <tr>
                                    <td colspan="2" style="text-align: right">E</td>
                                    <td  >= <b><?=$s->totalIntrinsicSum['put']?></b></td>
                                </tr>
                            </table>
                            <?if($s->totalIntrinsicSum['put'] == $bunch->maxPainPut):?>
                                <h1>MIN!</h1>
                            <?endif; ?>
                        </td>




<!--                        <td style="text-align: left; ">-->
<!--                            call: --><?//vd($s->intrinsicValues['call'])?>
<!--                            <hr>-->
<!--                            put: --><?//vd($s->intrinsicValues['put'])?>
<!--                        </td>-->
<!--                        <td style="text-align: left; ">-->
<!--                            call: --><?//vd($s->totalIntrinsicValues['call'])?>
<!--                            <hr>-->
<!--                            put: --><?//vd($s->totalIntrinsicValues['put'])?>
<!--                        </td>-->
<!---->





<!--                        <td style="text-align: left; ">-->
<!--                            CALL: --><?//=echoArr($s->intrinsicValues['call'])?>
<!--                            <p></p>-->
<!--                            PUT: --><?//=echoArr($s->intrinsicValues['put'])?>
<!--                        </td>-->
<!--                        <td style="text-align: left; ">-->
<!--                            total CALL: --><?//=echoArr($s->totalIntrinsicValues['call'])?>
<!--                            <p></p>-->
<!--                            total PUT: --><?//=echoArr($s->totalIntrinsicValues['put'])?>
<!--                        </td>-->



                    </tr>
                    <tr style="font-size: .9em; /*border-bottom: 3px solid #000;*/ " class="strike-row strike-row-<?=$s->color->code?>">

                    </tr>
                <?endforeach;?>
            </table>
        </div>




        <div class="clear"></div>
    </div>
    <hr>
<?endforeach;?>



<?
?>