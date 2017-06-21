<?php

$ACTION = $_PARAMS[1] ? $_PARAMS[1] : $_PARAMS[0];


#	БРЕНДЫ
if($_PARAMS[0] == 'brands' )
{
	$ACTION = 'brandsIndex';	# 	по умлочанию
	
	if($_PARAMS[1] == 'list')
		$ACTION = 'brandsList';
	if($_PARAMS[1] == 'edit')
		$ACTION = 'brandsEdit';
	if($_PARAMS[1] == 'editSubmit')
		$ACTION = 'brandsEditSubmit';
	if($_PARAMS[1] == 'listSubmit')
		$ACTION = 'brandsListSubmit';
	if($_PARAMS[1] == 'delete' )
		$ACTION = 'brandsDelete';
}



#	АРТИКУЛЬНЫЕ НОМЕРА
if($_PARAMS[0] == 'article_numbers' )
{
	$ACTION = 'articleNumbersIndex';	# 	по умлочанию

	if($_PARAMS[1] == 'list')
		$ACTION = 'articleNumbersList';
	if($_PARAMS[1] == 'edit')
		$ACTION = 'articleNumbersEdit';
	if($_PARAMS[1] == 'editSubmit')
		$ACTION = 'articleNumbersEditSubmit';
	if($_PARAMS[1] == 'listSubmit')
		$ACTION = 'articleNumbersListSubmit';
	if($_PARAMS[1] == 'delete' )
		$ACTION = 'articleNumbersDelete';
}






#	КАТЕГОРИЯ + БРЕНД
if($_PARAMS[0] == 'cat_brand_combine' )
{
	$ACTION = 'CatBrandIndex';	# 	по умлочанию

	if($_PARAMS[1] == 'brands_list_ajax')
		$ACTION = 'CatBrandBrandsListAjax';
	if($_PARAMS[1] == 'check_brand')
		$ACTION = 'CatBrandCheckBrand';
}



#	БРЕНД + АРТ.НОМЕР
if($_PARAMS[0] == 'brand_artnum_combine' )
{
	$ACTION = 'BrandArtnumIndex';	# 	по умлочанию

	if($_PARAMS[1] == 'artnums_list_ajax')
		$ACTION = 'BrandArtnumArtnumsListAjax';
	if($_PARAMS[1] == 'check_artnum')
		$ACTION = 'BrandArtnumCheckArtnum';
}




#	КАТЕГОРИЯ + БРЕНД + АРТ.НОМЕР
if($_PARAMS[0] == 'cat_brand_artnum_combine' )
{
	$ACTION = 'CatBrandArtnumIndex';	# 	по умлочанию

	if($_PARAMS[1] == 'brands_list_ajax')
		$ACTION = 'CatBrandArtnumBrandsListAjax';
	if($_PARAMS[1] == 'artnums_list_ajax')
		$ACTION = 'CatBrandArtnumArtnumsListAjax';
	if($_PARAMS[1] == 'check_artnum')
		$ACTION = 'CatBrandArtnumCheckArtnum';
}


		
	



#	запрет на весь контроллер
$_GLOBALS['ADMIN']->checkAndForbid(24);


class AdvController extends MainController{
	
	
	# 	БРЕНДЫ
	function brandsIndex()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		
		Slonne::view('adv/brands/index.php', $model);
	}
	
	
	
	
	function brandsList()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
	
		$model = Brand::getList();
	
		Slonne::view('adv/brands/list.php', $model);
	}
	
	
	
	function brandsEdit()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
	
		$model = Brand::get($_REQUEST['id']);
	
		Slonne::view('adv/brands/edit.php', $model);
	}
	
	
	
	function brandsEditSubmit()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
	
		if($id = intval($_REQUEST['id']))
		{
			$brand = Brand::get($_REQUEST['id']);
			$edit = true;
		}
		else
		{
			$brand = new Brand(); 
		}
	
		$brand->status = ($_REQUEST['active'] ? Status::$items[Status::ACTIVE]->num : Status::$items[Status::INACTIVE]->num);
		$brand->name = strPrepare(trim($_REQUEST['name']));
		$brand->pic = strPrepare(trim($_REQUEST['pic']));
		
		//vd($name);
		$error = 'Заполните все необходимые поля!';
	
			
		$problems = $brand->validate();
		//vd($problems);
		if(count($problems))
		{
			$str.='
			<script>';
			foreach($problems as $key=>$err)
			{
				$str.='
				window.top.highlight("edit-form input[name='.$err['field'].']")
				window.top.$("#edit-form input[name='.$err['field'].']").addClass("field-error")';
			}
	
			$str.='
				//window.top.$("#edit-form .info").html("'.$error.'")
				window.top.error("'.$error.'")
				window.top.Slonne.Adv.Brands.editSubmitComplete()';
			$str.='
			</script>';
			die($str);
		}
		else
		{	
			if($edit)
				$brand->update();
			else
			{
				$brand->idx = Brand::getNextIdx();
				$brand->insert();
			}
	
			$str.='
			<script>
				window.top.$.fancybox.close();
				window.top.Slonne.Adv.Brands.list();
				window.top.notice("Сохранено")
			</script>';
			echo $str;
		}
	}
	
	
	function brandsDelete()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
	
		$error = '';
		if($id = intval($_REQUEST['id']) )
		{
			Brand::delete($id);
		}
		else
			$error = 'Ошибка! Не передан id!';
	
		$result['error'] = $error;
	
		echo json_encode($result);
	}
	
	
	
	function brandsListSubmit()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
	
		//vd($_REQUEST);
		foreach($_REQUEST['idx'] as $key=>$val)
		{
			if($val = intval($val))
				Brand::setIdx($key, $val);
		}
	
		$str.='
		<script>
		window.top.Slonne.Adv.Brands.listSubmitComplete()
		window.top.notice("Сохранено!")
		</script>';
	
		echo $str;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	# 	АРТИКУЛЬНЫЕ НОМЕРА
	function articleNumbersIndex()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		
		
		if(isset($_REQUEST['from_file']))
		{
			$file = ROOT.'/'.INCLUDE_DIR.'/content/artNumbers.txt';
			$str = file_get_contents($file);
			$tmp = explode("\r\n", $str);
			foreach($tmp as $key=>$val)
			{
				if(trim($val))
					$artNums[] = mb_strtoupper(mb_substr(trim($val), 0, 1)) .  mb_substr(trim($val), 1);
			}
			vd($artNums);
			
			if($_REQUEST['grab_from_file'])
			{
				foreach($artNums as $key=>$artNum)
				{
					if(!ArtNum::getByName($artNum))
					{
						$a = new ArtNum();
						$a->status = Status::$items[Status::ACTIVE];
						$a->name = $artNum;
						$a->insert();
					}
				}
			}
				
		}
		
	
		Slonne::view('adv/articleNumbers/index.php', $model);
	}
	
	
	
	
	function articleNumbersList()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
	
		$model = ArtNum::getList();
	
		Slonne::view('adv/articleNumbers/list.php', $model);
	}
	
	
	
	function articleNumbersEdit()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
	
		$model = ArtNum::get($_REQUEST['id']);
	
		Slonne::view('adv/articleNumbers/edit.php', $model);
	}
	
	
	
	function articleNumbersEditSubmit()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
	
		if($id = intval($_REQUEST['id']))
		{
			$artNum = ArtNum::get($_REQUEST['id']);
			$edit = true;
		}
		else
		{
			$artNum = new ArtNum();
		}
	
		$artNum->status = ($_REQUEST['active'] ? Status::$items[Status::ACTIVE]->num : Status::$items[Status::INACTIVE]->num);
		$artNum->name = strPrepare(trim($_REQUEST['name']));
		$artNum->pic = strPrepare(trim($_REQUEST['pic']));
	
		//vd($name);
		$error = 'Заполните все необходимые поля!';
	
			
		$problems = $artNum->validate();
		//vd($problems);
		if(count($problems))
		{
			$str.='
			<script>';
			foreach($problems as $key=>$err)
			{
				$str.='
				window.top.highlight("edit-form input[name='.$err['field'].']")
				window.top.$("#edit-form input[name='.$err['field'].']").addClass("field-error")';
			}
	
			$str.='
				//window.top.$("#edit-form .info").html("'.$error.'")
				window.top.error("'.$error.'")
				window.top.Slonne.Adv.ArtNums.editSubmitComplete()';
			$str.='
			</script>';
			die($str);
		}
		else
		{
			if($edit)
				$artNum->update();
			else
			{
				$artNum->idx = ArtNum::getNextIdx();
				$artNum->insert();
			}
	
			$str.='
			<script>
				window.top.$.fancybox.close();
				window.top.Slonne.Adv.ArtNums.list();
				window.top.notice("Сохранено")
			</script>';
			echo $str;
		}
	}
	
	
	function articleNumbersDelete()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
	
		$error = '';
		if($id = intval($_REQUEST['id']) )
		{
			ArtNum::delete($id);
		}
		else
			$error = 'Ошибка! Не передан id!';
	
		$result['error'] = $error;
	
		echo json_encode($result);
	}
	
	
	
	function articleNumbersListSubmit()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
	
		//vd($_REQUEST);
		foreach($_REQUEST['idx'] as $key=>$val)
		{
			if($val = intval($val))
				ArtNum::setIdx($key, $val);
		}
	
		$str.='
		<script>
		window.top.Slonne.Adv.ArtNums.listSubmitComplete()
		window.top.notice("Сохранено!")
		</script>';
	
		echo $str;
	}
	
	
	
	
	
	
	# 	КАТЕГОРИЯ + БРЕНД
	function CatBrandIndex()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		
		$MODEL['catsTree'] = Category::getListTree(array('pid'=>0, 'catType'=>'adv')); 
		
		$allCatBrandCmbs = CatBrandCmb::getList();
		foreach($MODEL['catsTree'] as $key=>$cat)
		{
			foreach($allCatBrandCmbs as $cmb)
				if($cat->id == $cmb->catId)
					$cat->catBrandCombines[] = $cmb;
			# 	для сабс
			foreach($cat->subs as $sub)
				foreach($allCatBrandCmbs as $cmb)
					if($sub->id == $cmb->catId)
						$sub->catBrandCombines[] = $cmb;
		}
		
		Slonne::view('adv/cmbCatBrand/index.php', $MODEL);
	}
	
	
	
	function CatBrandBrandsListAjax()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		
		$_GLOBALS['NO_LAYOUT'] = true;
		
		if($catId = $_REQUEST['cat'])
		{
			if($cat = Category::get($catId))
			{
				$MODEL['catBrands'] = CatBrandCmb::getByCatId($cat->id);
				$MODEL['brandsList'] = Brand::getList();				
			}
			else $MODEL['error'] = 'Ошибка! Категория не найдена! '.$catId;		
		}
		else $MODEL['error'] = 'Ошибка! не передан cat id.';
		
		Slonne::view('adv/cmbCatBrand/brandsList.php', $MODEL);
	}
	
	
	
	
	
	function CatBrandCheckBrand()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		
		$_GLOBALS['NO_LAYOUT'] = true;
		
		$cat = Category::get($_REQUEST['cat']);
		$brand = Brand::get($_REQUEST['brand']);
		$checked = $_REQUEST['checked'];

		if($cat && $brand )
		{
			$catBrandCmb = new CatBrandCmb();
			$catBrandCmb->catId = $cat->id;
			$catBrandCmb->brandId = $brand->id;
			if($checked)
			{
				if(!CatBrandCmb::get($cat->id, $brand->id))
					$catBrandCmb->insert();
			}
			else 
				$catBrandCmb->delete();
		}
		
		echo json_encode(array('checked'=>$checked)); 
	}
	
	
	
	
	
	
	
	# 	БРЕНД + АРТ.НОМЕР
	function BrandArtnumIndex()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
	
		$MODEL['brands'] = Brand::getList();
	
		$allbrandArtnumsCmbs = BrandArtnumCmb::getList();
		foreach($MODEL['brands'] as $key=>$brand)
		{
			foreach($allbrandArtnumsCmbs as $cmb)
				if($brand->id == $cmb->brandId)
					$brand->brandArtnumCombines[] = $cmb;
		}
	
		Slonne::view('adv/cmbBrandArtnum/index.php', $MODEL);
	}
	
	
	
	function BrandArtnumArtnumsListAjax()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
	
		$_GLOBALS['NO_LAYOUT'] = true;
	
		if($brandId = $_REQUEST['brand'])
		{
			if($brand = Brand::get($brandId))
			{
				$MODEL['brandArtnums'] = BrandArtnumCmb::getByBrandId($brand->id);
				$MODEL['artnumsList'] = ArtNum::getList();
			}
			else $MODEL['error'] = 'Ошибка! Категория не найдена! '.$catId;
		}
		else $MODEL['error'] = 'Ошибка! не передан cat id.';
	
		Slonne::view('adv/cmbBrandArtnum/artnumsList.php', $MODEL);
	}
	
	
	
	
	
	function BrandArtnumCheckArtnum()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
	
		$_GLOBALS['NO_LAYOUT'] = true;
	
		$brand = Brand::get($_REQUEST['brand']);
		$artnum = ArtNum::get($_REQUEST['artnum']);
		$checked = $_REQUEST['checked'];
	
		if($brand && $artnum )
		{
			$brandArtnumCmb = new BrandArtnumCmb();
			$brandArtnumCmb->brandId = $brand->id;
			$brandArtnumCmb->artnumId = $artnum->id;
			if($checked)
			{
				if(!BrandArtnumCmb::get($brand->id, $artnum->id))
					$brandArtnumCmb->insert();
			}
			else
				$brandArtnumCmb->delete();
		}
	
		echo json_encode(array('checked'=>$checked));
	}
	
	
	
	
	
	
	
	# 	КАТЕГОРИЯ + БРЕНД + АРТ.НОМЕР
	function CatBrandArtnumIndex()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
	
		$MODEL['catsTree'] = Category::getListTree(array('pid'=>0, 'catType'=>'adv'));
		
		# 	кол-во брендов
		$allCatBrandCmbs = CatBrandCmb::getList();
		foreach($MODEL['catsTree'] as $key=>$cat)
		{
			foreach($allCatBrandCmbs as $cmb)
				if($cat->id == $cmb->catId)
					$cat->catBrandCombines[] = $cmb;
				# 	для сабс
				foreach($cat->subs as $sub)
					foreach($allCatBrandCmbs as $cmb)
						if($sub->id == $cmb->catId)
							$sub->catBrandCombines[] = $cmb;
		}
		
		
	
		Slonne::view('adv/cmbCatBrandArtnum/index.php', $MODEL);
	}
	
	
	
	
	function CatBrandArtnumBrandsListAjax()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
	
		$_GLOBALS['NO_LAYOUT'] = true;

		if($catId = $_REQUEST['cat'])
		{
			if($cat = Category::get($catId))
			{
				# 	требуются только определённые бренды, подвязанные к выбранной категории
				$catBrandCmbs = CatBrandCmb::getByCatId($cat->id);
				foreach($catBrandCmbs as $cmb)
					$tmp[] = $cmb->brandId;
				$allBrands = Brand::getList();
				foreach($allBrands as $brand)
					if(in_array($brand->id, $tmp))
						$MODEL['brandsList'][] = $brand;
					
				$allbrandArtnumCmbs = BrandArtnumCmb::getList();
				foreach($MODEL['brandsList'] as $key=>$brand)
				{
					foreach($allbrandArtnumCmbs as $cmb)
						if($brand->id == $cmb->brandId)
							$brand->brandArtnumCombines[] = $cmb;
					//vd($brand);
				}
			}
			else $MODEL['error'] = 'Ошибка! Категория не найдена! '.$catId;
		}
		else $MODEL['error'] = 'Ошибка! не передан cat id.';
		
		Slonne::view('adv/cmbCatBrandArtnum/brandsList.php', $MODEL);
	}
	
	
	
	
	
	function CatBrandArtnumArtnumsListAjax()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
	
		$_GLOBALS['NO_LAYOUT'] = true;
		
		$MODEL['chosenCat'] = Category::get($_REQUEST['cat']);

		if($brandId = $_REQUEST['brand'])
		{
			if($brand = Brand::get($brandId))
			{
				$MODEL['brandArtnums'] = BrandArtnumCmb::getByBrandId($brand->id);
				
				$brandArtnumCmb = BrandArtnumCmb::getByBrandId($brand->id);
				foreach($brandArtnumCmb as $cmb)
					$requiredArtnumIds[] = $cmb->artnumId;
				
				if($requiredArtnumIds)
					$MODEL['artnumsList'] = ArtNum::getListByIds($requiredArtnumIds);
				
				# 	выделенные арт номера
				$tmp = CatBrandArtnumCmb::getByCatIdAndBrandId($MODEL['chosenCat']->id, $brand->id);
				foreach($tmp as $cmb)
					$MODEL['chosenArtnumIds'][] = $cmb->artnumId;
			}
			else $MODEL['error'] = 'Ошибка! Категория не найдена! '.$catId;
		}
		else $MODEL['error'] = 'Ошибка! не передан cat id.';
	
		Slonne::view('adv/cmbCatBrandArtnum/artnumsList.php', $MODEL);
	}
	
	

	function CatBrandArtnumCheckArtnum()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
	
		$_GLOBALS['NO_LAYOUT'] = true;
	
		$cat = Category::get($_REQUEST['cat']);
		$brand = Brand::get($_REQUEST['brand']);
		$artnum = ArtNum::get($_REQUEST['artnum']);
		$checked = $_REQUEST['checked'];
	
		if($cat && $brand && $artnum )
		{
			$catBrandArtnumCmb = new CatBrandArtnumCmb();
			$catBrandArtnumCmb->catId = $cat->id;
			$catBrandArtnumCmb->brandId = $brand->id;
			$catBrandArtnumCmb->artnumId = $artnum->id;
			if($checked)
			{
				if(!CatBrandArtnumCmb::get($cat->id, $brand->id, $artnum->id))
					$catBrandArtnumCmb->insert();
			}
			else
				$catBrandArtnumCmb->delete();
		}
	
		echo json_encode(array('checked'=>$checked));
	}
	
	
	
	
	
	
	
	
	
	
	
	
}




?>