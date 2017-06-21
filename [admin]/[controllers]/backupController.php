<?php

$ACTION = $_PARAMS[0];


	

#	запрет на весь контроллер
$_GLOBALS['ADMIN']->checkAndForbid(18);

class BackupController extends MainController{
	
	
	
	function index()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;

		
		Slonne::view('backup/indexView.php');
	}
	
	
	
	
}




?>