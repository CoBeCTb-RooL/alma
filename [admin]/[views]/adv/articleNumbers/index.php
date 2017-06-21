
<?php Slonne::view('catalog/menu.php', $model);?>

<h1><?=$_GLOBALS['CURRENT_MODULE']->icon?> База артикульных номеров</h1>

<div id="brands-list" class="article-numbers"> 	
	<div class="inner"></div>
	<div class="loading" style="visibility: hidden; "> <img src="/<?=ADMIN_DIR?>/img/tree-loading.gif" > </div>
</div>

<iframe name="frame1" style="display: none; width: 98%; border: 1px dashed #0e0e0e; height: 400px;">1</iframe>


<!--форма редактирования-->
<div id="float"  style="display: none; min-width: 700px; max-width: 700px;">!!</div>

<?php 
if(!isset($_REQUEST['from_file']))
{?>
<script>
$(document).ready(function(){
	Slonne.Adv.ArtNums.list()
})
</script>
<?php 
}?>



<hr /><a href="?from_file">Из файла</a><p>


<?php 
if(isset($_REQUEST['from_file']))
{?>
 sdf sdf sdf 
 <p>
 <a href="?from_file&grab_from_file=1">СПАРСИТЬ НОВЫЕ</a>
<?php 
}?>