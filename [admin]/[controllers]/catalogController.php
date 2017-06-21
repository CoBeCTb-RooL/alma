<?php

$ACTION = $_PARAMS[1] ? $_PARAMS[1] : $_PARAMS[0];


if($ACTION == 'list')
	$ACTION = 'list1';

#	ТИПЫ КАТАЛОГОВ
if($_PARAMS[0] == 'types')
{
	if($_PARAMS[1] == 'list')
		$ACTION = 'typesListAjax';
	if($_PARAMS[1] == 'edit')
		$ACTION = 'typesEdit';
	if($_PARAMS[1] == 'editSubmit')
		$ACTION = 'typesEditSubmit';
	if($_PARAMS[1] == 'delete')
		$ACTION = 'typeDelete';
}


#	СВОЙСТВА
if($_PARAMS[0] == 'props')
{
	if($_PARAMS[1] == 'list')
		$ACTION = 'propsListAjax';
	if($_PARAMS[1] == 'edit')
		$ACTION = 'propsEdit';
	if($_PARAMS[1] == 'editSubmit')
		$ACTION = 'propsEditSubmit';
	if($_PARAMS[1] == 'delete')
		$ACTION = 'propsDelete';
	if($_PARAMS[1] == 'optionValueSubmit')
		$ACTION = 'optionValueSubmit';
	if($_PARAMS[1] == 'optionDelete')
		$ACTION = 'optionDelete';
}


#	КЛАССЫ
if($_PARAMS[0] == 'classes' )
{
	if($_PARAMS[1] == 'list' )
		$ACTION = 'classesListAjax';
	if($_PARAMS[1] == 'edit')
		$ACTION = 'classesEdit';
	if($_PARAMS[1] == 'editSubmit')
		$ACTION = 'classesEditSubmit';
	if($_PARAMS[1] == 'delete')
		$ACTION = 'classesDelete';	
			
}



#	ИНТЕРФЕЙС
if($_PARAMS[0] == 'interface' )
{
	$ACTION = 'interface1';
	
	if($_PARAMS[1] == 'catsListJson' )
		$ACTION = 'catsListJson';	
	if($_PARAMS[1] == 'cat_view' )
		$ACTION = 'catView';
	if($_PARAMS[1] == 'catEdit' )
		$ACTION = 'catEdit';	
	if($_PARAMS[1] == 'catEditSubmit' )
		$ACTION = 'catEditSubmit';
	if($_PARAMS[1] == 'catClassEdit' )
		$ACTION = 'catClassEdit';
	if($_PARAMS[1] == 'catClassEditSubmit' )
		$ACTION = 'catClassEditSubmit';		
	if($_PARAMS[1] == 'itemsList' )
		$ACTION = 'itemsList';		
	if($_PARAMS[1] == 'itemEdit' )
		$ACTION = 'itemEdit';	
	if($_PARAMS[1] == 'itemEditSubmit' )
		$ACTION = 'itemEditSubmit';		
	
}

	
	



#	запрет на весь контроллер
$_GLOBALS['ADMIN']->checkAndForbid(24);


class CatalogController extends MainController{
	
	
	
	function index()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		
		Slonne::view('catalog/indexView.php', $model);
	}
	
	
	
	
#	индекс типов
	function types()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		 
		
		Slonne::view('catalog/types/typesIndex.php', $model);
	}

	#	список типов
	function typesListAjax()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		$list = CatType::getList();
		$model['list'] = $list;
		
		Slonne::view('catalog/types/typesList.php', $model);
	}
	
	
	#	редактирование типа (добавление)
	function typesEdit()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		if($id = intval($_REQUEST['id']))
			$type = CatType::get($id);
		
		$model['type'] = $type;	
		
		Slonne::view('catalog/types/typesEdit.php', $model);
	}
	
	
	#	сабмит редактирования типов каталога
	function typesEditSubmit()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		
		$type = CatType::get($_REQUEST['id']);
		$edit = $type ? true : false;
		
		
		$error = 0;
		$problems = array();
		
		$name = strPrepare(trim($_REQUEST['name']));
		$code = strPrepare(trim($_REQUEST['code']));

		if(!$name) 
		{
			$problems[] = 'name';
			$error = "Введите название типа!";
		}
		if(!$code && !$type)
		{
			$problems[] = 'code';
			$error = $error ? $error : "Введите код типа!";
		}
		
		if(!$type)
		{
			$tmp=preg_match('/^[a-zA-Z][a-zA-Z0-9_]+$/', $code);
			if(!$tmp)	
			{
				$problems[]='code';
				$error = $error ? $error : 'Некорректный код!';
			}
			
			if(!count($problems))
			{
				#	на существование имени
				$tmp = CatType::getByName($name);
				if($tmp && $tmp->id != $type->id)
				{
					$problems[] = 'name';
					$error = $error ? $error : 'Тип с таким именем уже есть!';
				}
			}
		}
		if(!count($problems))
		{
		#	на существование кода
			$tmp = CatType::getByCode($code);
			if($tmp && $tmp->id != $type->id)
			{
				$problems[] = 'code';
				$error = $error ? $error : 'Код уже занят!';
			}
		}
		
		
		if(!count($problems))
		{
			if(!$edit)
				$type = new CatType();
			
			$type->name = $name;
			if(!$edit)
				$type->code = $code;
			
			if(!$edit)
				$type->insert();
			else 
				$type->update();
			$error = mysql_error();
		}
		
		$result['error'] = $error;
		$result['problems'] = $problems;
		echo '<script>window.top.Slonne.Catalog.Types.typeEditSubmitComplete('.json_encode($result).')</script>';
		
		
	}
	
	
	
	function typeDelete()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		$error = '';
		if($id = intval($_REQUEST['id']) )
		{
			$essence = CatType::get($id);
			if($essence)
			{
				CatType::delete($id);
			}
		}
		else 
			$error = 'Ошибка! Не передан id!';

		$result['error'] = $error;
		
		echo json_encode($result);
	}
	
	
	
	
	
	
	
	
	#	СВОЙСТВА
	#	индекс 
	function props()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		 
		
		Slonne::view('catalog/props/propsIndex.php', $model);
	}

	#	список типов
	function propsListAjax()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		$list = Prop::getList();
		foreach($list as $key=>$prop)
			if($prop)
				$prop->initOptions($activeOnly=false);
				
		$model['list'] = $list;
		
		Slonne::view('catalog/props/propsList.php', $model);
	}
	
	
	#	редактирование типа (добавление)
	function propsEdit()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		if($id = intval($_REQUEST['id']))
		{
			$prop = Prop::get($id);
			if($prop)
				$prop->initOptions($activeOnly=false);
		}
		
		$model['prop'] = $prop;	
		
		Slonne::view('catalog/props/propsEdit.php', $model);
	}
	
	
	#	сабмит редактирования типов каталога
	function propsEditSubmit()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		$error = '';
		$warnings = array();
		$problems = array();
		vd($_REQUEST);
		
		if($id = intval($_REQUEST['id']))
		{
			$prop = Prop::get($id);
			$edit = true; 
		}
		
		if(!$edit)
			$prop = new Prop();
		
		if(!$prop->id)
		{
			$prop->code = $_REQUEST['code'];
			$prop->type = $_REQUEST['type'];
			$prop->active = 1;
		}
		else
		{
			$prop->active = $_REQUEST['active'] ? 1 : 0;
		}
		
		$prop->name = $_REQUEST['name'];
		$prop->nameOnSite = $_REQUEST['nameOnSite'];
		
		$size = $_REQUEST['size'];
		if($_REQUEST['width'] && $_REQUEST['height'])
			$size = $_REQUEST['width'].'x'.$_REQUEST['height'];
		$prop->size = $size;
		
		//$prop->options = $_REQUEST['options'];
		$prop->multiple = ($_REQUEST['pic_multiple'] || $_REQUEST['select_multiple']) ? 1 : 0;
		$prop->required = $_REQUEST['required'] ? 1 : 0;
		
		
		$problems = $prop->validate();
		//vd($problems);
		//vd($error);
		if(!count($problems))
		{
			if($edit)
				$prop->update();
			else
				$prop->id = $prop->insert();
				

			#	заводим новые опции	
			if($prop->type == 'select')
			{
				$newOptions = trim($_REQUEST['options']);
				if($newOptions)
				{
					$arr = explode("\r\n", $newOptions);
					foreach($arr as $key=>$val)
					{
						if(trim($val))
						{
							$opt = new CatSelectOption();
							$opt->propId = $prop->id;
							$opt->value = strPrepare($val);
							
							$optSimilar = $opt->getSimilarByValue();
							//vd($optSimilar);
							if(!$optSimilar)
								$opt->insert();
							else
							{
								$warnings[] = 'Опция <b>"'.$opt->value.'"</b> уже есть в этом списке! НЕ ДОБАВЛЕНА!';
							}
						}
					}
				}
			}
			
		}
		else $error = $problems[0]['error'];
		//vd($warnings);
		
		$result['error'] = $error;
		$result['problems'] = $problems;
		$result['warnings'] = $warnings;
		vd($result);
		echo '<script>window.top.Slonne.Catalog.Props.propsEditSubmitComplete('.json_encode($result).')</script>';
	}
	
	
	function propsDelete()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		$error = '';
		if($id = intval($_REQUEST['id']) )
		{
			Prop::delete($id);
		}
		else 
			$error = 'Ошибка! Не передан id!';

		$result['error'] = $error;
		
		echo json_encode($result);
	}
	
	
	
	function optionValueSubmit()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		$error = '';
		
		//vd($_REQUEST);
		$opt = CatSelectOption::get($_REQUEST['id']);
		if($opt)
		{
			if($val = trim($_REQUEST['val']))
			{
				$opt->value = $val;
				#	проверка, существует ли такое значение уже в этом списке
				$similarByValue = $opt->getSimilarByValue();
				if($similarByValue)
					$error = "Опция с таким значением уже существует в этом списке!";
				else
				{
					
					$opt->update();
				} 
			}
			else
				$error = 'Ошибка! Не передано значение! ';
		}
		else
			$error = 'Ошибка! Не удалось найти option id='.$_REQUEST['id'].' ';

		$result['error'] = $error;
		
		echo json_encode($result);
	}
	
	
	
	function optionDelete()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		$error = '';
		if($id = intval($_REQUEST['id']) )
		{
			CatSelectOption::delete($id);
		}
		else 
			$error = 'Ошибка! Не передан id!';

		$result['error'] = $error;
		
		echo json_encode($result);
	}
	
	
	
	
	
	
	
	
	#	КЛАССЫ
	#	индекс
	function classes()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		 
		
		Slonne::view('catalog/classes/classesIndex.php', $model);
	}

	#	список классов
	function classesListAjax()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		$list = CatClass::getList();
		foreach($list as $key=>$class)
			$class->initProps($activeOnly=false);
			
		$model['list'] = $list;
		
		Slonne::view('catalog/classes/classesList.php', $model);
	}
	
	
	#	редактирование типа (добавление)
	function classesEdit()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		if($id = intval($_REQUEST['id']))
		{
			$class = CatClass::get($id);
			if($class)
				$class->initProps($activeOnly=false);
		}
		
		$model['class'] = $class;
		$model['props'] = Prop::getList($activeOnly=false);	
		
		Slonne::view('catalog/classes/classesEdit.php', $model);
	}
	
	
	function classesEditSubmit()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		$error = '';
		$warnings = array();
		$problems = array();
		
		if($id = intval($_REQUEST['id']))
		{
			$class = CatClass::get($id);
			$edit = true; 
		}
		
		if(!$edit)
			$class = new CatClass();
		
		if(!$class->id)
		{
			$class->active = 1;
		}
		else
		{
			$class->active = $_REQUEST['active'] ? 1 : 0;
		}
		
		$class->name = $_REQUEST['name'];
		
		$problems = $class->validate();
		//vd($problems);
		
		if(!count($problems))
		{
			if($edit)
				$class->update();
			else
				$class->id = $class->insert();
				
				
			#	сохранение связей 
			ClassPropRelation::deleteRelationsOfClass($class->id);
			
			foreach($_REQUEST['props'] as $propId=>$val)
			{
				$rel = new ClassPropRelation();
				$rel->classId = $class->id;
				$rel->propId = $propId;
				
				$rel->insert(); 
			}	
			
		}
		else $error = $problems[0]['error'];
		
		$result['error'] = $error;
		$result['problems'] = $problems;
		$result['warnings'] = $warnings;
		//vd($result);
		echo '<script>window.top.Slonne.Catalog.Classes.classesEditSubmitComplete('.json_encode($result).')</script>';
	}
	
	function classesDelete()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		$error = '';
		if($id = intval($_REQUEST['id']) )
		{
			$class = CatClass::get($id);
			if($class)
			{
				CatClass::delete($id);
				
				ClassPropRelation::deleteRelationsOfClass($id);
			}
		}
		else 
			$error = 'Ошибка! Не передан id!';

		$result['error'] = $error;
		
		echo json_encode($result);
	}
	
	
	
	
	
	
	
	
	#	ИНТЕРФЕЙС
	function interface1()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		
		//vd($_REQUEST);
		$type=$_REQUEST['type'];
		$model['type'] = $type;
		$catType = CatType::getByCode($type);
		$model['catType'] = $catType;
		//vd($catType);
		
		
		Slonne::view('catalog/interface/indexView.php', $model);
	}
	
	

	
	
	
	
	
	
	function catsListJson()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		
		$_GLOBALS['NO_LAYOUT'] = true; 
		
		$catType = CatType::getByCode($_GET['catType']);
		$pid = $_GET['pid'];
		$lang = $_GET['lang'];
		$lang = $_CONFIG['LANGS'][$lang] ? $lang : LANG;
		
		
		$p=intval($_REQUEST['p']) ? intval($_REQUEST['p'])-1 : 0;
		$elPP=Category::TREE_ELEMENTS_PER_PAGE;
		
		if($catType) 
		{			
			$result = array();

			$params = array(
							'catType'=>$catType->code,
							'pid'=>$pid,
							'limit'=>"LIMIT ".($p)*$elPP.", ".$elPP."",
							'order'=>'', 
							'lang'=>$lang, 
							'additionalClauses'=>'and 1',
							'activeOnly'=>false,
						);
						
			$tmp = Category::getList($params); 
			
			#	сколько всего 
			$params['limit'] = '';
			$result['totalCount'] = Category::getCount($params);
			$result['elPP'] = $elPP;
			$result['p'] = $p;
			
			foreach($tmp as $key=>$val)
			{
				$tmp[$key]->childBlocksCount = Category::getChildBlocksCount($catType->code, $val->id);
				$tmp[$key]->childElementsCount = Category::getChildElementsCount($val->id);
				if($val->classId)
					$classIds[$val->classId]++; 
			}
			
			
			$result['treeItems'] = $tmp;
			$result['pagesHTML'] = drawPages($result['totalCount'], $p, $elPP, $onclick="Slonne.Entities.getEntities(Slonne.Entities.TREE_SETTINGS.essenceCode, ".$pid.", Slonne.Entities.TREE_SETTINGS.type, Slonne.Entities.TREE_SETTINGS.lang, ###)", $class="pg", $symbols = array('prev'=>'&larr;', 'next'=>'&rarr;'));
			
			# 	инициализируем классы объектов
			$classIds = array_keys($classIds);
			$additionalClause = " AND id IN(".join(", ", $classIds).")";
			$classesList = CatClass::getList($activeOnly=false, $params = array("additionalClause"=>$additionalClause));
			
			foreach($tmp as $key=>$item)
				foreach($classesList as $class)
					if($item->classId == $class->id)
					{
						$item->class=$class;
						break;
					}
			
			$result['essence'] = $essence;
				
		}
		else 	
			$result['error'] = 'Передан непонятный тип! ['.$_GET['catType'].']';	
			
		echo json_encode($result);
	}
	
	
	
	function catView()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		
		$_GLOBALS['NO_LAYOUT'] = true; 
		
		//vd($_REQUEST);
		$id = $_REQUEST['id'];
		$lang = $_REQUEST['lang'];
		$lang = $_CONFIG['LANGS'][$lang] ? $lang : LANG;
		$catType = CatType::getByCode($_REQUEST['catType']);
			
		$cat = Category::get($id);
		if($cat)
		{
			$model['cat'] = $cat;
			$model['catType'] = $catType;
			$model['lang'] = $lang;
		}
		else
			$model['error'] = 'Объект не найден! ['.$essenceCode.', '.$type.', '.$id.']';
		
		Slonne::view('catalog/interface/catView.php', $model);
	}
	
	
	
	function catEdit()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		
		$_GLOBALS['NO_LAYOUT'] = true; 
		
		$catType = CatType::getByCode($_REQUEST['catType']);
		$id = $_REQUEST['id'];
		$pid = $_REQUEST['pid'];

		if($catType)
		{
			$cat = Category::get($id);
			
			$model['id'] = $id; 
			$model['cat'] = $cat;
			$model['catType'] = $catType;
			$model['pid'] = $pid;
		}
		else
			$model['error'] = 'Непонятный catType='.$_REQUEST['catType'].'';
	
			
		Slonne::view('catalog/interface/catEdit.php', $model);
	}
	
	
	
	
	
	function catEditSubmit()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		
		$_GLOBALS['NO_LAYOUT'] = true;

		$error = '';
		$problems = array();
		$warnings = array();
		$pidWas = null;
		$edit = false;
		
		$pid = $_REQUEST['pid'];
		
		$catType = CatType::getByCode($_REQUEST['catType']);
		
		if($catType)
		{
			$cat = Category::get($_REQUEST['id']);
			if($cat)
			{
				$edit = true;
				
				if($pid != $cat->pid && $pid!='')
				{
					$pidWas = $cat->pid;
				}
			}
			
			
			if(!$edit)
			{
				$cat = new Category();
				$cat->catTypeCode = $catType->code;
			}

			//vd($_REQUEST);
			
			if(!$name = strPrepare($_REQUEST['name']))
				$problems[] = array('field'=>'name');
				
				
			if(count($problems))
				$error = 'Пожалуйста, заполните все необходимые поля!';
			else
			{
				$cat->active = $_REQUEST['active'] ? 1 : 0;
				$cat->name = strPrepare($_REQUEST['name']);
				$cat->pid = intval($_REQUEST['pid']);
				vd($cat);
				if($edit)	
					$cat->update();
				else 
					$cat->id = $cat->insert();
			}
			
		}
		else
			$error = 'Передан непонятный CatType = '.$_REQUEST['catType'].'';	
		
		
			
		#	результат
		$result = array(
					'edit'=>$edit,
					'cat'=>$cat,
					'error'=>$error, #	основной текст ошибки, который будет выведен
					'problems'=>$problems,	#	это большие ошибки, не позволяющие завершить транзакцию
					'warnings'=>$warnings,	#	небольшие варнинги
					'pidWas'=>$pidWas,	#	чтобы перерисовать в дереве
				);	
		//vd($e);
		//vd($result); 
		echo '<script>window.top.Slonne.Catalog.Interface.catEditComplete('.json_encode($result).')</script>';	
		return;
		
	}	
	

	
	function catClassEdit()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true; 
		
		$model['cat'] = Category::get($_REQUEST['id']);
		$model['classes'] = CatClass::getList($activeOnly=true);
		//vd($model['classes']);
			
		Slonne::view('catalog/interface/catClassEdit.php', $model);
	}
	
	function catClassEditSubmit()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		$error = '';
		
		//vd($_REQUEST);
		if($cat = Category::get($_REQUEST['id']))
		{
			$class = CatClass::get($_REQUEST['class']);
			if(!($_REQUEST['class'] && !$class))
			{
				$cat->classId = $class->id;
				$cat->update(); 
			}
			else 
				$error = 'Ошибка! Класс не найден! ['.$_REQUEST['class'].']';
		}
		else 
			$error = 'Ошибка! Категория не найдена! ['.$_REQUEST['id'].']';
		
			
		$result['error'] = $error;
		
		echo '<script>window.top.Slonne.Catalog.Interface.catClassEditSubmitComplete('.json_encode($result).')</script>';	
		return;
	}
	
	
	
	
	
	function itemsList()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		
		$_GLOBALS['NO_LAYOUT'] = true; 
		
		//usleep(300000);
		//vd($_GET);
		//vd($_REQUEST);
		
		$pid = $_REQUEST['pid'];
		$lang = $_REQUEST['lang'];
		$lang = $_CONFIG['LANGS'][$lang] ? $lang : LANG;
		
		//$page = $_REQUEST['p'];
		$p=intval($_REQUEST['p']) ? intval($_REQUEST['p'])-1 : 0;

		$model['cat'] = Category::get($pid);
		if($model['cat'])
			$model['cat']->initClass();
		if($model['cat']->class)
			$model['cat']->class->initProps($activeOnly=true);
		$model['catType'] = CatType::getByCode($model['cat']->catTypeCode);
		
		$model['order'] = strPrepare($_REQUEST['order']);
		$model['order'] = $model['order'];
		$model['desc'] = intval($_REQUEST['desc']);
		
		$elPP=Entity2::LIST_ELEMENTS_PER_PAGE;
		
		$params = array(
					'pid'=>$pid,
					'limit'=>"LIMIT ".($p)*$elPP.", ".$elPP."", 
					'order'=>$model['order'].($model['desc'] ? ' DESC' : ''), 
					'lang'=>$lang, 
					'additionalClauses'=>'and 1',
					'activeOnly'=>false,
				);
				//vd($params);
		$list = CatItem::getList($params);
		//vd($list);
		foreach($list as $item)
		{
			$item->cat = $model['cat'];
			$item->initValues();
		}
		$model['list'] = $list;
		

		$params['limit'] = '';
		$model['totalCount'] = CatItem::getCount($params);
		$model['elPP'] = $elPP;
		$model['p'] = $p;

			
			
			
		Slonne::view('catalog/interface/itemsList.php', $model);
	}
	
	
	
	function itemEdit()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true; 
		
		$model['cat'] = Category::get($_REQUEST['pid']);
		$model['cat']->initClass();
		$model['cat']->class->initProps($activeOnly=true);
		$model['item'] = CatItem::get($_REQUEST['id']);
		
		//vd($model['item']);
		if($model['item']->id)
		{
			$model['item']->cat = $model['cat'];
			$model['item']->initValues();
		}
		$model['catType'] = CatType::getByCode($model['cat']->catTypeCode);
		
		
		$model['id'] = $_REQUEST['id'];
		$model['pid'] = $_REQUEST['pid'];
		$model['lang'] = $_REQUEST['lang'] ? $_REQUEST['lang'] : LANG;
		
			
		Slonne::view('catalog/interface/itemEdit.php', $model);
	}
	
	function itemEditSubmit()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		$error = '';
		$lang = $_REQUEST['lang'] ? $_REQUEST['lang'] : LANG;
		
		//vd($_REQUEST);
		
		$id = intval($_REQUEST['id']);
		if($cat = Category::get($_REQUEST['pid']))
		{
			$cat->initClass();
			$cat->class->initProps();
			
			$edit = false; 
			if($item = CatItem::get($id, $lang))
				$edit = true;
			else
			{
				$item=new CatItem();
			}	
			$item->active = $_REQUEST['active'] ? 1 : 0;
			$item->pid = $cat->id;
			$item->catTypeCode = $cat->catTypeCode;
			$item->name = $_REQUEST['name'];
			$item->descr = $_REQUEST['descr'];
			
			$item->cat = $cat;
			
			
			foreach($item->cat->class->props as $key=>$prop)
			{
				$item->propValues[$prop->code] = $_REQUEST[$prop->code];
			}
			
			$problems = $item->validate();
			if(!count($problems))
			{
				#	всё ок, сохраняем!
				DB::transactionStart(); 
				if(!$edit)
				{
					$item->id = $item->insert();
				}
				else
				{
					$item->update();
				}
				
				
				if(!$e = mysql_error())
				{
					#	работа с пропсами
					foreach($item->cat->class->props as $propId=>$prop)
					{
						$value = $item->propValues[$prop->code];
						
						#	обработка МУЛЬТИСЕЛЕКТА
						if($prop->type=='select' && $prop->multiple)
						{
							//vd($value);
							CatPropValue::saveMultipleSelectOptionsByItemAndProp($item, $prop, $value, $lang);
						}
						else 	#	ВСЕ ОСТАЛЬНЫЕ
						{
							$newProp = false; 
							$propValue = CatPropValue::getByItemIdAndPropId($item->id, $prop->id);
							
							if(!$propValue)
							{
								$propValue = new CatPropValue();
								$newProp = true; 
							}
							
							$propValue->propId = $prop->id;
							$propValue->propCode = $prop->code;
							$propValue->itemId = $item->id;
							$propValue->catTypeCode = $item->cat->catTypeCode; 
							$propValue->lang = $lang;
							$propValue->value = $value;
							//vd($prop);
							if($newProp)
								$propValue->insert();
							else
								$propValue->update();
						}
							
					}
					
					if(!$e = mysql_error())
						DB::commit();
					else
					{
						DB::rollback();
						$error = $e;	
					}
				}
				else
				{
					DB::rollback();
					$error = $e;
				}
			}
			else
				$error = $problems[0]['error'];
		}
		else 
			$error = 'Ошибка! Категория не найдена! ['.$_REQUEST['pid'].']';
		
			
		$result['error'] = $error;
		$result['problems'] = $problems;
		//$result['edit'] = $edit;
		//vd($result);
		echo '<script>window.top.Slonne.Catalog.Interface.itemEditSubmitComplete('.json_encode($result).')</script>';	
		return;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}




?>