<?
$tangent = $MODEL['tangent'];
$formData = $MODEL['formData'];
//vd($tangent);
?>

<script src="https://unpkg.com/d3@3/d3.min.js"></script>
<script src="https://unpkg.com/function-plot@1/dist/function-plot.js"></script>


<?php Slonne::view('stock/menu.php');?>|

<form action="?" style="background: #f7f7f7; border: 1px solid #ccc; padding: 10px; ">

    SigmaATM=<input type="text" name="sigmaAtm" value="<?=$formData['sigmaAtm']?>"><br>
    S=<input type="text" name="S" value="<?=$formData['S']?>"><br>
    T=<input type="text" name="T" value="<?=$formData['T']?>"><br>
    Улыбка (в формате K=V, через [ENTER]. Центральный страйк обозначить восклиц знаком вконце):<br>
    <div style="display: table-cell; width: 200px; border: 0px solid red; vertical-align: top; ">
        <textarea name="strikes" id="" cols="20" rows="7"><?=$formData['strikes']?></textarea>
        <br>
    </div>
    <div style="display: table-cell; width: 400px; border: 0px solid green; vertical-align: top;  padding-top: 10px; ">
        Пример:
        <pre>1.1000 = 8.32
1.1025 = 8.28
1.1050 = 8.41!
1.1075 = 8.51
1.1250 = 11.42</pre>
    </div>
    <input type="submit" name="goBtn" value="go">
</form>



<div class="graphic1"></div>


<?if($tangent):?>

    <?
    $yMin = $yMax= 0;
    $graphicXOffset = ($tangent->x[1]-$tangent->x[0])/2;


    $dotsArr = [];
    foreach ($tangent->dots as $i=>$val)
        $dotsArr[] = [$val[0], $val[1]];
    ?>





    <table class="t" border="1">
        <tr>
            <td>STRIKE: </td>
            <?foreach ($tangent->dots as $val):?>
                <td><?=strikeVal($val[0])?></td>
            <?endforeach;?>
        </tr>
        <tr>
            <td>VOLATILITY (bug): </td>
            <?foreach ($tangent->dots as $val):?>
                <td><?=$val[1]?></td>
            <?endforeach;?>
        </tr>
        <tr>
            <td>KSI: </td>
            <?foreach ($tangent->dots as $val):?>
                <td><?=$tangent->ksi($val[0])?></td>
            <?endforeach;?>
        </tr>
        <tr>
            <td>SIGMA (correct): </td>
            <?foreach ($tangent->dots as $val):?>
                <td><?=$tangent->sigmaCorrect($val[0])?></td>
            <?endforeach;?>
        </tr>
    </table>
    <div>sigmaAtm: <b><?=$tangent->sigmaAtm?></b></div>
    <div>S: <b><?=$tangent->S?></b></div>
    <div>T: <b><?=$tangent->T?></b></div>
    <div>beta: <b><?=$tangent->scewAngle?></b></div>
    <div>lambda: <b><?=$tangent->lambda?></b></div>
    <div>formula: <b><?=$tangent->formula?></b></div>


    <div style="height: 100px; "></div>




    <script>
        functionPlot({
            target: '.graphic1',
            xAxis: {domain: [<?=$tangent->dots[0][0]-( $graphicXOffset )?>, <?=$tangent->dots[count($tangent->dots)-1][0] + $graphicXOffset?>]},
            yAxis: {domain: [<?=$tangent->yMin-0.1?>, <?=$tangent->yMax+0.1?>]},
            data: [
                {
                    fn: 'x^2'
                },
                {
                    fn: 'x+11113',
                    skipTip: true,
                    color: 'green'
                },
                {

                    points:
                        <?=json_encode($dotsArr)?>
                        // [1, 1],
                        // [2, 1],
                        // [2, 2],
                        // [1, 2],
                        // [1, 1]
                    ,
                    fnType: 'points',
                    graphType: 'scatter',
                    color: 'red',
                },

            ]
        })
    </script>


<?endif;?>