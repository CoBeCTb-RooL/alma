<div class="essences"> 
	<h1><?=$_GLOBALS['CURRENT_MODULE']->icon?> Сущности</h1>
	<div class="inner"></div>
	<div class="loading" style="visibility: hidden; "> <img src="/<?=ADMIN_DIR?>/img/tree-loading.gif" > </div>
</div>


<div class="fields"> 
	<div class="inner"></div>
	<div class="loading" style="visibility: hidden; "> <img src="/<?=ADMIN_DIR?>/img/tree-loading.gif" > </div>
</div>
<div class="clear"></div>

<!--форма редактирования-->
<div id="float"  style="display: none; min-width: 450px; max-width: 700px;">!!</div>

<script>
$(document).ready(function(){
	Slonne.Essences.list()
})
</script>