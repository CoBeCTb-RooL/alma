
<?php Slonne::view('catalog/menu.php', $model);?>

<h1><?=$_GLOBALS['CURRENT_MODULE']->icon?> База брендов</h1>

<div id="brands-list" class="brands"> 	
	<div class="inner"></div>
	<div class="loading" style="visibility: hidden; "> <img src="/<?=ADMIN_DIR?>/img/tree-loading.gif" > </div>
</div>

<iframe name="frame1" style="display: none; width: 98%; border: 1px dashed #0e0e0e; height: 400px;">1</iframe>


<!--форма редактирования-->
<div id="float"  style="display: none; min-width: 700px; max-width: 700px;">!!</div>

<script>
$(document).ready(function(){
	Slonne.Adv.Brands.list()
})
</script>