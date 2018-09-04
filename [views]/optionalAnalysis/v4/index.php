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
</style>


<script>
    var Zones = {
        opts: {
            date: '<?=$date?>',
            currency: '<?=$cur->code?>'
        },

        list: function(){
            $.ajax({
                url: '/ru/optionalAnalysis/v4/zonesListAjax',
                data: this.opts,
                beforeSend: function(){ $('.zones').css('opacity', .6); $('.stat-loading').slideDown('fast');  },
                complete: function(){ $('.zones').css('opacity', 1); $('.stat-loading').slideUp('fast');  },
                success: function(data){
                    $('.zones').html(data)
                },
                error: function(){}
            })
        },


        deleteStrike: function(id){
            if(!confirm('удалить?'))
                return
            $.ajax({
                url: '/ru/optionalAnalysis/v4/Zones.deleteStrikeAjax',
                data: {id: id},
                beforeSend: function(){ $('.stats').css('opacity', .6); $('.stat-loading').slideDown('fast');  },
                complete: function(){ $('.stats').css('opacity', 1); $('.stat-loading').slideUp('fast');  },
                success: function(data){
                    // $('.stats').html(data)
                    //location.href=location.href
                    Zones.list()
                },
                error: function(){}
            })
        },


        setZoneDataToForm: function(obj){
            if(typeof(obj) == 'undefined' || typeof(obj.currency) == 'undefined'){
                alert('Битые данные... С этой зоной не получится')
                return
            }

            var form = $('#addZoneForm')

            form.find('input[name=forward]').val(obj.forward)
            form.find('input[name=zoneData]').val(obj.zoneData)
            form.find('textarea[name=data]').val(obj.data)
        }


    }
</script>






<?php Slonne::view('stock/menu.php');?>






<h1>Опционный анализ v4.0</h1>
Валюта: |&nbsp;

<?
foreach($MODEL['currencies'] as $c)
{?>
    <a href="?currency=<?=$c->code?>&date=<?=$date?>" style="; <?=$c->code == $cur->code ? 'font-weight: bold; font-size: 1.2em;  ' : ''?>"><?=$c->code?></a>
    &nbsp;|&nbsp;
<?
}?>
<hr>
<p></p>


<div class="day-nav" style="margin: 0 0 14px 0; ">
    <h2 class="current-date" style="margin: 0 0 5px 0;  padding: 0;">
		<?=Funx::mkDate($date);?>
		<?=($date == $today ? '<span class="today-lbl">(сегодня)</span>' : '' )?>
    </h2>
    <a href="?date=<?=$datePrev?>&currency=<?=$cur->code?>">&larr; Предыдущий</a>
    <a href="?date=<?=date('Y-m-d')?>&currency=<?=$cur->code?>" style="font-weight: bold; ">Сегодня</a>
	<?php
	if($dateNext)
	{?>
        <a href="?date=<?=$dateNext?>&currency=<?=$cur->code?>">Следующий &rarr;</a>
		<?php
	}?>
</div>



<form class="form" action="/ru/optionalAnalysis/v4/submit" id="addZoneForm" target="frame7" onsubmit="if(confirm('Сохранить данные?')){return true; } return false; " style="position: fixed; top: 200px; right: 100px; background: #efefef; ">
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





<p>
<!--<button onclick="Zones.list(); "></button>-->
<div class="zones">Загрузка...</div>









<iframe src="" frameborder="0" name="frame7" style="display:  ; border: 1px solid #000; background: #ececec; height: 400px; width: 100%; ">wqe</iframe>


<script>

    $(document).ready(function(){
        Zones.list()
    })

</script>