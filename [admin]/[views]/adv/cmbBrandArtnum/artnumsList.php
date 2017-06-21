<?php
$error = $MODEL['error'];
$brandArtnums = $MODEL['brandArtnums'];
$artnumsList = $MODEL['artnumsList']; 
foreach($brandArtnums as $a)
	$brandArtnumIds[] = $a->artnumId;
//vd($currentCat); 
//vd($brandIds);

?>
<style>
	label{display: block; margin: 0 0 9px 0; }
	.status-2{color: #ccc; }
</style>


<script>
	function switchArtnumCheckbox(id)
	{
		var checked = $("#artnum-cb-"+id+"").is(':checked') ? 1 : 0 

		$.ajax({
			url: '/'+Slonne.ADMIN_URL_SIGN+'/'+Slonne.Adv.URL_SECTION+'/brand_artnum_combine/check_artnum/',
			data: "brand="+CHOSEN_BRAND+"&artnum="+id+"&checked="+checked,
			dataType: "json",
			beforeSend: function(){$('#artnum-wrapper-'+id).css('opacity', '.3'); $('.artnums .loading').css('display', 'block')},
			success: function(data){
				if(data.checked > 0) 
					$("#artnum-cb-"+id+"").attr('checked', 'checked');
				else
					$("#artnum-cb-"+id+"").removeAttr('checked')
			},
			error: function(err){error('Возникла ошибка на сервере...')},
			complete: function(){$('#artnum-wrapper-'+id).css('opacity', '1'); $('.artnums .loading').css('display', 'none')} 
		});
		
	}
</script>


<?php
if(!$error)
{?>
	<div class="wrapper">
	<?php 
	foreach($artnumsList as $key=>$artnum)
	{?>
		<label id="artnum-wrapper-<?=$artnum->id?>" class="status-<?=$artnum->status?>"> <input type="checkbox" id="artnum-cb-<?=$artnum->id?>" <?= in_array($artnum->id, $brandArtnumIds) ? ' checked="checked" ' : "" ?> onclick="switchArtnumCheckbox(<?=$artnum->id?>)" /> <?=$artnum->name?> (<?=$artnum->id?>)</label>
	<?php 
	}?>
	</div>
<?php 	
}
else
	echo $error;  
?>