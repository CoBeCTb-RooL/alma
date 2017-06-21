<?php

$ACTION = $_PARAMS[0];


if($ACTION == 'list')
	$ACTION = 'list1';


#	запрет на весь контроллер
$_GLOBALS['ADMIN']->checkAndForbid(27);


class SettingsController extends MainController{
	
	
	
	function index()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;

		if($_REQUEST['go_btn'])
		{
			//vd($_REQUEST);
			Settings::save($_REQUEST);
			
			echo'Сохранено!';
		}
		
		$_CONFIG['SETTINGS'] = Settings::get();
		
		Slonne::view('settings/index.php', $model);
	}
	
	
	
	
	
	
	
	
	
	
	
}




?>