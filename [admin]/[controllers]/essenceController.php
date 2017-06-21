<?php

$ACTION = $_PARAMS[0];


if($ACTION == 'list')
	$ACTION = 'list1';


#	запрет на весь контроллер
$_GLOBALS['ADMIN']->checkAndForbid(22);


class EssenceController extends MainController{
	
		
	
	function index()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		
		Slonne::view('essences/indexView.php', $model);
	}
	
	
	
	
	function list1()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		
		$model = Essence2::getList();
		
		
		Slonne::view('essences/list.php', $model);
	}
	
	
	
	function edit()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		Slonne::view('essences/edit.php', $model);
	}
	
	
	
	
	function editSubmit()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		$name = strPrepare(trim($_REQUEST['name']));
		$code = strPrepare(trim($_REQUEST['code']));
		$jointFields = $_REQUEST['jointFields'] ? 1 : 0;
		$linear = $_REQUEST['linear'] ? 1 : 0;
		 		
		$error = 'Заполните все необходимые поля!';
				
		if(!$name) $problems[] = 'name';
		if(!$code) $problems[] = 'code';
		
		
		$tmp=preg_match('/^[a-zA-Z][a-zA-Z0-9_]+$/', $code);
		if(!$tmp)	
		{
			$problems[]='code';
			$error='Некорректный код!';
		}
		
		if(!count($problems))
		{
			$tmp = Essence2::getByCode($code);
			if($tmp && $tmp->id != $essence->id)
			{
				$problems[] = 'code';
				$error = 'Код уже занят!';
			}
		}
		
		
		if(count($problems))
		{
			$str.='
			<script>';
			foreach($problems as $key=>$val)
			{
				$str.='
				window.top.highlight("edit-form input[name='.$val.']")
				window.top.$("#edit-form *[name='.$val.']").addClass("field-error")';
			}
			
			$str.='
				//window.top.$("#edit-form .info").html("'.$error.'")
				window.top.error("'.$error.'")
				window.top.Slonne.Essences.editSubmitComplete()';
			$str.='
			</script>';
			die($str);
		}
		else
		{
			$essence = new Essence2();
			
			$essence->name = $name;
			$essence->code = $code;
			$essence->jointFields = $jointFields;
			$essence->linear = $linear;
			
			#	создаём сущность
			$essence->id = Essence2::add($essence);

			#	Создаём таблицы с обьектами
			$essence->createTables();
			
			
			$str.='
			<script>
				window.top.$.fancybox.close();
				window.top.Slonne.Essences.list();
				window.top.notice("Сохранено")
			</script>';
			echo $str;
		}
		
	}
	
	
	
	
	
	
	function delete()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		$error = '';
		if($id = intval($_REQUEST['id']) )
		{
			$essence = Essence2::get($id);
			if($essence)
			{
				Essence2::delete($id);
				
				$essence->dropTables();
				
			}
		}
		else 
			$error = 'Ошибка! Не передан id!';

		$result['error'] = $error;
		
		echo json_encode($result);
	}
	
	
	
	
	
	
	function listSubmit()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		//vd($_REQUEST);
		foreach($_REQUEST['idx'] as $key=>$val)
		{
			if($val = intval($val))
				Essence2::setIdx($key, $val);
		}
		
		
		$str.='
		<script>
		window.top.Slonne.Essences.listSubmitComplete()
		window.top.notice("Сохранено!")
		</script>';
		
		echo $str;
	}
	
	
	
}




?>