<?php
$brands = $MODEL['brands'];

//vd($catsTree);
?>

<style>
	.section{border-right: 1px solid #ccc; display: table-cell; padding: 0 40px ; vertical-align: top;  }
	.brand{margin: 0 0 4px 0; }
	.brand a{ text-decoration: none; }
	.brand a.active{font-weight: bold; text-decoration: underline;  }
	.subs{margin: 0 0 11px 25px; }
	.subs a{display: block; margin: 0 0 3px 0;  }
	h3{margin: 10px 0 10px 0; padding: 0; font-size: 18px; }
</style>



<script>
var CHOSEN_BRAND = '';

function changeBrand(id)
{
	CHOSEN_BRAND = id 
	//alert(id)
	$('.brand a').removeClass('active')
	$('#brand-'+id).addClass('active')
	
	
	$.ajax({
		url: '/'+Slonne.ADMIN_URL_SIGN+'/'+Slonne.Adv.URL_SECTION+'/brand_artnum_combine/artnums_list_ajax/',
		data: "brand="+id+"",
		beforeSend: function(){$('.artnums .inner').css('opacity', '.3'); $('.artnums .loading').css('display', 'block')},
		success: function(data){$('.artnums .inner').html(data)},
		error: function(err){error('Возникла ошибка на сервере...')},
		complete: function(){$('.artnums .inner').css('opacity', '1'); $('.artnums .loading').css('display', 'none')} 
	});
}
</script>




<?php Slonne::view('catalog/menu.php', $model);?>



<h1>Бренд + Арт.номер</h1>



<div class="section brands">
	<h3>Бренды</h3>
	<?php 
	foreach($brands as $key=>$item)
	{?>
		<div class="brand">
			<a href="#" id="brand-<?=$item->id?>" onclick="changeBrand(<?=$item->id?>); return false; "> <span style="font-size: 12px;">(<?=$item->id ?>)</span> <?=$item->name?> <span style="font-size: 10px; font-weight: normal; ">| арт.номеров: <b><?=count($item->brandArtnumCombines)?></b></span> </a>
		</div>
	<?php 	
	}?>
</div>


<div class="section artnums">
	<h3>Арт. номера</h3>
	<div class="inner">&larr; Выберите бренд сперва</div>
	<div class="loading" style="display: none;">заргузка...</div>
</div>