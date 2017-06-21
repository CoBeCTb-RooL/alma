<?php 





$id = intval($_PARAMS[0]);
if($id)
	$ACTION = 'pagesItem';
else
	$ACTION = 'pagesList';
	




class PagesController extends MainController{
	
	
	
	function pagesList()
	{
		global $_GLOBALS, $_CONFIG;
		
		$_GLOBALS['TITLE'] = Slonne::getTitle('Разделы');
		 
		$pages = Page::getChildren(113);
		$MODEL['pages'] = $pages;
		
		Slonne::view('pages/pagesList.php', $MODEL);
	}
	
	
	
	
	function pagesItem($id)
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		
		$id = intval($_PARAMS[0]);
		
		$p = Page::get($id);
		//vd($p);
		//Page::a();
		$_GLOBALS['TITLE'] = Slonne::getTitle($p->attrs['name']);
		$MODEL['p'] = $p;
		
		#	крошки
		$tree = Page::getTree($p->id);
		foreach($tree as $key=>$page)
			$treeIds[] = $page->id;
	
		$crumbs = array();
		$crumbs[] = '<a href="/'.LANG.'/">'.$_CONST['ГЛАВНАЯ'].'</a>';
		if(in_array(1, $treeIds))	#	если открыт раздел ГЛАВНОГО МЕНЮ, а не левый
		{
			$i = 0;
			foreach($tree as $key=>$page)
			{
				$i++;
				if($key == 1)	#	главное меню
					continue;
	
				if($i < count($tree))
				{
					$crumbs[] = '<a href="#'.$page->attrs['name'].'">'.$page->attrs['name'].'</a>';
				}
				else 	
					$crumbs[] = ''.$page->attrs['name'].'';
			}
		}
		else	#	открыт левый
		{
			$crumbs[] = ''.$p->attrs['name'].'';
		}
		
		$MODEL['crumbs'] = $crumbs;

		Slonne::view('pages/page.php', $MODEL);
	}
	
	
	
	
}


?>