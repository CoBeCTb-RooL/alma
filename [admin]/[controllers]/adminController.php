<?php

$ACTION = $_PARAMS[0];


if($ACTION == 'list')
	$ACTION = 'list1';


#	запрет на весь контроллер
$_GLOBALS['ADMIN']->checkAndForbid(21);

class AdminController extends MainController{
	
	
	
	function index()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		
		Slonne::view('admins/indexView.php', $model);
	}
	
	
	
	
	function list1()
	{
		//Slonne::redirect('adminGroup/edit?id=2');
		
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		$model = Admin::getList();
		foreach($model as $key=>$val)
			$val->initGroup();
		
		Slonne::view('admins/list.php', $model);
	}
	
	
	
	function edit()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		$model['admin'] = Admin::get($_REQUEST['id']);
		$model['groups'] = AdminGroup::getList();
		
		Slonne::view('admins/edit.php', $model);
	}
	
	
	
	
	function editSubmit()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		if($id = intval($_REQUEST['id']))
		{
			$admin = Admin::get($_REQUEST['id']);
			$edit = true; 
		}
		vd($_REQUEST);
		$active = ($_REQUEST['active'] ? 1 : 0);
		$name = strPrepare(trim($_REQUEST['name']));
		$email = strPrepare(trim($_REQUEST['email']));
		$groupId = strPrepare(trim($_REQUEST['groupId']));
		$password = strPrepare(trim($_REQUEST['password']));
		$password2 = strPrepare(trim($_REQUEST['password2']));
		 		
		$error = 'Заполните все необходимые поля!';
				
		if(!$name) $problems[] = 'name';
		if(!$email) $problems[] = 'email';
		
		if(!count($problems))
		{
			$tmp = Admin::getByEmail($email);
			if($tmp && $tmp->id != $admin->id)
			{
				$problems[] = 'email';
				$error = 'Email занят!';
			}
		}
		if(!$groupId)
			$problems[] = 'groupId';
		
		if( !$edit ||  ($edit && ($password || $password2) )  )
		{
			if(!$password)
			{
				$problems[] = 'password';
				$error = 'Введите пароль!';
			}
			elseif(!$password2)
			{
				$problems[] = 'password2';
				$error = 'Подтвердите пароль!';
			}
			elseif($password != $password2)
			{
				$problems[] = 'password';
				$problems[] = 'password2';
				$error = 'Пароли не совпадают!';
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
				window.top.Slonne.Admins.editSubmitComplete()';
			$str.='
			</script>';
			die($str);
		}
		else
		{
			if(!$edit)
				$admin = new Admin();
			
			$admin->active = $active;
			$admin->name = $name;
			$admin->email = $email;
			$admin->groupId = $groupId;
			
			if($edit)
				Admin::update($admin);
			else
				$admin->id = Admin::add($admin);
				
			if($password)
				$admin->setPassword($password);
				
			$str.='
			<script>
				window.top.$.fancybox.close();
				window.top.Slonne.Admins.list();
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
			Admin::delete($id);
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
				Admin::setIdx($key, $val);
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