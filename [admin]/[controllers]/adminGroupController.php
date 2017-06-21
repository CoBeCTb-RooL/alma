<?php

$ACTION = $_PARAMS[0];


if($ACTION == 'list')
	$ACTION = 'list1';


#	запрет на весь контроллер
$_GLOBALS['ADMIN']->checkAndForbid(21); //	всё равно права - от модуля АДМИНЫ

class AdminGroupController extends MainController{
	
	
	
	function index()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		
		Slonne::view('adminGroups/indexView.php', $model);
	}
	
	
	
	
	function list1()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		$model = AdminGroup::getList();
		
		Slonne::view('adminGroups/list.php', $model);
	}
	
	
	
	
	
	function edit()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		if($_REQUEST['id'])
		{
			$model['group'] = AdminGroup::get($_REQUEST['id']);
			$model['group']->initPrivilegesArr();
		}
		
		$model['modules'] = Module::getList();
		foreach($model['modules'] as $key=>$val)
			$val->initActionsArr();
		
		Slonne::view('adminGroups/edit.php', $model);
	}
	
	
	
	
	function editSubmit()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		if($id = intval($_REQUEST['id']))
		{
			$group = AdminGroup::get($_REQUEST['id']);
			$edit = true; 
		}
		
		$active = ($_REQUEST['active'] ? 1 : 0);
		$name = strPrepare(trim($_REQUEST['name']));
		
		//vd($name);
		 		
		$error = 'Заполните все необходимые поля!';
				
		if(!$name) $problems[] = 'name';
		
		
		
		
		if(count($problems))
		{
			$str.='
			<script>';
			foreach($problems as $key=>$val)
			{
				$str.='
				window.top.highlight("edit-form input[name='.$val.']")
				window.top.$("#edit-form input[name='.$val.']").addClass("field-error")';
			}
			
			$str.='
				//window.top.$("#edit-form .info").html("'.$error.'")
				window.top.error("'.$error.'")
				window.top.Slonne.AdminGroups.editSubmitComplete()';
			$str.='
			</script>';
			die($str);
		}
		else
		{
			if(!$edit)
				$group = new AdminGroup();
			
			$group->active = $active;
			$group->name = $name;

			$priv = $_REQUEST['priv'];
			//vd($priv);
			$tmp = array();
			foreach($priv as $key=>$val)
				$tmp[] = $key.':'.join(',', array_keys($val));
				
			//vd($priv); return; 
			
			$group->privileges = join('|', $tmp);
			
			if($edit)
				AdminGroup::update($group);
			else
				AdminGroup::add($group);
				
				
			
			
			
			
			
			$str.='
			<script>
				window.top.$.fancybox.close();
				window.top.Slonne.AdminGroups.list();
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
			AdminGroup::delete($id);
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
				AdminGroup::setIdx($key, $val);
		}
		
		
		$str.='
		<script>
		window.top.Slonne.AdminGroups.listSubmitComplete()
		window.top.notice("Сохранено!")
		</script>';
		
		echo $str;
	}
	
	
	
}




?>