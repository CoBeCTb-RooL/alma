<?php
$error = $MODEL['error'];
$catBrands = $MODEL['catBrands'];
$brandsList = $MODEL['brandsList']; 
foreach($catBrands as $b)
	$catBrandIds[] = $b->brandId;
//vd($currentCat); 
//vd($brandIds);

?>
<style>
	label{display: block; margin: 0 0 9px 0; }
	.status-2{color: #ccc; }
</style>


<script>
	function switchBrandCheckbox(id)
	{
		var checked = $("#brand-cb-"+id+"").is(':checked') ? 1 : 0 

		$.ajax({
			url: '/'+Slonne.ADMIN_URL_SIGN+'/'+Slonne.Adv.URL_SECTION+'/cat_brand_combine/check_brand/',
			data: "cat="+CHOSEN_CAT+"&brand="+id+"&checked="+checked,
			dataType: "json",
			beforeSend: function(){$('#brand-wrapper-'+id).css('opacity', '.3'); $('.brands .loading').css('display', 'block')},
			success: function(data){
				if(data.checked > 0) 
					$("#brand-cb-"+id+"").attr('checked', 'checked');
				else
					$("#brand-cb-"+id+"").removeAttr('checked')
			},
			error: function(err){error('Возникла ошибка на сервере...')},
			complete: function(){$('#brand-wrapper-'+id).css('opacity', '1'); $('.brands .loading').css('display', 'none')} 
		});
		
	}
</script>


<?php
if(!$error)
{?>
	<div class="wrapper">
	<?php 
	foreach($brandsList as $key=>$brand)
	{?>
		<label id="brand-wrapper-<?=$brand->id?>" class="status-<?=$brand->status?>"> <input type="checkbox" id="brand-cb-<?=$brand->id?>" <?= in_array($brand->id, $catBrandIds) ? ' checked="checked" ' : "" ?> onclick="switchBrandCheckbox(<?=$brand->id?>)" /> <?=$brand->name?> (<?=$brand->id?>)</label>
	<?php 
	}?>
	</div>
<?php 	
}
else
	echo $error;  
?>