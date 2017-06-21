<?php
$catsTree = $MODEL['catsTree'];

//vd($catsTree);
?>

<style>
	.section{border-right: 1px solid #ccc; display: table-cell; padding: 0 40px ; vertical-align: top;  }
	.cat a{ text-decoration: none; }
	.cat a.active{font-weight: bold; text-decoration: underline;  }
	.primary-cat{font-size: 15px; }
	.subs{margin: 0 0 11px 25px; }
	.subs a{display: block; margin: 0 0 3px 0;  }
	h3{margin: 10px 0 10px 0; padding: 0; font-size: 18px; }
</style>



<script>
var CHOSEN_CAT = '';

function changeCat(id)
{
	CHOSEN_CAT = id 
	//alert(id)
	$('.cat a').removeClass('active')
	$('#cat-'+id).addClass('active')
	
	
	$.ajax({
		url: '/'+Slonne.ADMIN_URL_SIGN+'/'+Slonne.Adv.URL_SECTION+'/cat_brand_combine/brands_list_ajax/',
		data: "cat="+id+"",
		beforeSend: function(){$('.brands .inner').css('opacity', '.3'); $('.brands .loading').css('display', 'block')},
		success: function(data){$('.brands .inner').html(data)},
		error: function(err){error('Возникла ошибка на сервере...')},
		complete: function(){$('.brands .inner').css('opacity', '1'); $('.brands .loading').css('display', 'none')} 
	});
}
</script>




<?php Slonne::view('catalog/menu.php', $model);?>



<h1>Категория + бренд</h1>






<form action="/admin/adv/cat_brand_combine_submit" target="frame3">
	<input type="hidden" name="cat" value="" />
	<input type="hidden" name="brand" value="" />
</form>
<iframe name="frame3" style="display: none; width: 98%; border: 1px dashed #0e0e0e; height: 400px;">1</iframe>

<div class="section cat">
	<h3>Категории</h3>
	<?php 
	foreach($catsTree as $key=>$cat)
	{?>
		<div class="cat">
			<a href="#" id="cat-<?=$cat->id?>" class="primary-cat" onclick="changeCat(<?=$cat->id?>); return false; "> <span style="font-size: 12px;">(<?=$cat->id ?>)</span> <?=$cat->name?> <span style="font-size: 10px; font-weight: normal; ">| брендов: <b><?=count($cat->catBrandCombines)?></b></span> </a>
			<?php
			if($cat->subs)
			{?>
			<div class="subs">
				<?php 
				foreach($cat->subs as $key=>$sub)
				{?>
					<a href="#" id="cat-<?=$sub->id?>" onclick="changeCat(<?=$sub->id?>); return false; "><span style="font-size: 9px;">(<?=$sub->id ?>)</span> <?=$sub->name?> <span style="font-size: 10px; font-weight: normal; ">| брендов: <b><?=count($sub->catBrandCombines)?></b></span> </a>
				<?php 
				}?>
			</div>
			<?php 
			}?>
		</div>
	<?php 	
	}?>
</div>


<div class="section brands">
	<h3>Бренды</h3>
	<div class="inner">&larr; Выберите категорию сперва</div>
	<div class="loading" style="display: none;">заргузка...</div>
</div>