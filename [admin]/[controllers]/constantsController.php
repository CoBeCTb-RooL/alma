<?php

$ACTION = $_PARAMS[0];


if($ACTION == 'list')
	$ACTION = 'list1';


#	запрет на весь контроллер
$_GLOBALS['ADMIN']->checkAndForbid(18);

class ConstantsController extends MainController{
	
	
	
	function index()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		
		Slonne::view('constants/indexView.php', $model);
	}
	
	
	
	
	function list1()
	{
		//Slonne::redirect('adminGroup/edit?id=2');
		
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		$model = Constants::getList();
		
		
		Slonne::view('constants/list.php', $model);
	}
	
	
	
	function edit()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		$model['const'] = Constants::get($_REQUEST['id']);
		
		Slonne::view('constants/edit.php', $model);
	}
	
	
	
	
	function editSubmit()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		if($id = intval($_REQUEST['id']))
		{
			$const = Constants::get($_REQUEST['id']);
			$edit = true; 
		}
		//vd($_REQUEST);
		
		
		$name = strPrepare(trim($_REQUEST['name']));
		 		
		$error = 'Заполните все необходимые поля!';
				
		if(!$name) $problems[] = 'name';
		foreach($_CONFIG['LANGS'] as $lang=>$val)
		{
			if(!trim($_REQUEST['value_'.$lang]))	
				$problems[] = 'value_'.$lang;
		} 
		
		if(!count($problems))
		{
			
			if($tmp = Constants::getByName($name, $const->id))
			{
				$problems[] = 'name';
				$error = 'Константа с таким именем уже существует';
			}
		}
		
			
		if(count($problems))
		{
			$str.='
			<script>';
			foreach($problems as $key=>$val)
			{
				$str.='
				window.top.highlight("edit-form *[name='.$val.']")
				window.top.$("#edit-form *[name='.$val.']").addClass("field-error")';
			}
			
			$str.='
				//window.top.$("#edit-form .info").html("'.$error.'")
				window.top.error("'.$error.'")
				window.top.Slonne.Admins.editSubmitComplete()';
			$str.='
			</script>';
			die($str);
		}
		else
		{
			if(!$edit)
				$const = new Constants();
			
			$const->name = $name;
			foreach($_CONFIG['LANGS'] as $lang=>$val)
				$const->value[$lang] = ($_REQUEST['value_'.$lang]); 
			
			if($edit)
				$const->update();
			else
				$const->insert();
				
			
			$str.='
			<script>
				window.top.$.fancybox.close();
				window.top.Slonne.Constants.list();
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
			Constants::delete($id);
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
				Constants::setIdx($key, $val);
		}
		
		
		$str.='
		<script>
		window.top.Slonne.Admins.listSubmitComplete()
		window.top.notice("Сохранено!")
		</script>';
		
		echo $str;
	}
	
	
	
	
	
	
	
	
	
}




?>