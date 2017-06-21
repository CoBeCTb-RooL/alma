<h1><?=$_GLOBALS['CURRENT_MODULE']->icon?> Администраторы</h1>



<div id="admins-list" class="admins"> 
	<div class="switcher">
		<a href="#admins" onclick="Slonne.Admins.list(); return false; ">Администраторы</a>
		<a href="#admin_groups" onclick="Slonne.AdminGroups.list();return false; ">Группы</a>
	</div>
		
	<div class="inner"></div>
	<div class="loading" style="visibility: hidden; "> <img src="/<?=ADMIN_DIR?>/img/tree-loading.gif" > </div>
</div>

<!--форма редактирования-->
<div id="float"  style="display: none; min-width: 700px; max-width: 700px;">!!</div>

<script>
$(document).ready(function(){
	Slonne.Admins.list()
})
</script>