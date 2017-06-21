<?php

$ACTION = $_PARAMS[0];


	




class IndexController extends MainController{
	
	
	
	function index()
	{
		global $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		
		
		Slonne::view('index/indexView.php', $model);
	}
	
	
	
}




?>