<?php

$ACTION = $_PARAMS[0];


if($ACTION == 'list')
	$ACTION = 'list1';




class AuthController extends MainController{
	
	
	
	function index()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		
		$_GLOBALS['LAYOUT'] = 'authLayout';
		
		//Slonne::view('auth/indexView.php', $model);
	}
	
	
	function auth()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		$error = '';
		$state = '';
		
		
		if(time() < $_SESSION['admin']['nextTryTS'])
		{
			$result['delay'] = ''.($_SESSION['admin']['nextTryTS'] - time()).' сек.';
			$state = 'tries_limit';
		}
		else
		{
			$email = trim($_REQUEST['email']);
			$password = trim($_REQUEST['password']);
			if(!$email || !$password)
				$error = 'Заполните все поля!';
			else
			{
				$admin = Admin::getByEmailAndPassword($email, $password);
				#	проверка группы
				if($admin)	
				{
					$admin->initGroup();
					//vd($admin);
					if(!$admin->group->active)	
						$admin = null;
				}
				
				if(!$admin)
				{
					$state = 'not_found';
					$_SESSION['admin']['authTries'] ++ ;
					$result['triesRemain'] = Admin::AUTH_TRIES_LIMIT - $_SESSION['admin']['authTries'];
					$_SESSION['admin']['nextTryTS'] = time() + Admin::SECONDS_DELAY_STEP * ($_SESSION['admin']['authTries'] - Admin::AUTH_TRIES_LIMIT) ;
				}
				else
				{
					$_SESSION['admin']['id'] = $admin->id;
					$state = 'ok';
					$admin->setLastAuth();
				}
			}
		}
		
		$result['error'] = $error;
		$result['result'] = $state;
		
		
		
		echo json_encode($result);
	}
	
	
	
	function logout()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		$_GLOBALS['NO_LAYOUT'] = true;
		
		unset($_SESSION['admin']);
	}
	
	
}




?>