<?php
$error = $MODEL['error'];
$brands = $MODEL['brandsList'];
?>
<style>
	label{display: block; margin: 0 0 9px 0; }
	.status-2{color: #ccc; }
	.brand a{ text-decoration: none; font-size: 13px; }
	.brand a.active{font-weight: bold; text-decoration: underline;  }
	.brand {margin: 0 0 6px 0; }
</style>


<script>
function changeBrand(id)
{
	CHOSEN_BRAND = id 
	//alert(id)
	$('.brand a').removeClass('active')
	$('#brand-'+id).addClass('active')
	
	
	$.ajax({
		url: '/'+Slonne.ADMIN_URL_SIGN+'/'+Slonne.Adv.URL_SECTION+'/cat_brand_artnum_combine/artnums_list_ajax/',
		data: "brand="+id+"&cat="+CHOSEN_CAT+"",
		beforeSend: function(){$('.artnums .inner').css('opacity', '.3'); $('.artnums .loading').css('display', 'block')},
		success: function(data){$('.artnums .inner').html(data)},
		error: function(err){error('Возникла ошибка на сервере...')},
		complete: function(){$('.artnums .inner').css('opacity', '1'); $('.artnums .loading').css('display', 'none')} 
	});
}
</script>
</script>


<?php
if(!$error)
{?>
	<div class="wrapper" >
	<?php 
	foreach($brands as $key=>$brand)
	{?>
		<div class="brand" ><a href="#" id="brand-<?=$brand->id?>" class="primary-cat" onclick="changeBrand(<?=$brand->id?>); return false; "> <span style="font-size: 10px;">(<?=$brand->id ?>)</span> <?=$brand->name?> </span> <span style="font-size: 10px; font-weight: normal; ">| арт.номеров: <b><?=count($brand->brandArtnumCombines)?></b></span> </a></div>
		
		<!--<label id="brand-wrapper-<?=$brand->id?>" class="status-<?=$brand->status?>"> <input type="checkbox" id="brand-cb-<?=$brand->id?>" <?= in_array($brand->id, $catBrandIds) ? ' checked="checked" ' : "" ?> onclick="switchBrandCheckbox(<?=$brand->id?>)" /> <?=$brand->name?> (<?=$brand->id?>)</label>-->
	<?php 
	}?>
	</div>
<?php 	
}
else
	echo $error;  
?>