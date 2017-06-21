<?php
class Slonne
{
	
	
	
	function loadModels($path)
	{
		$models = scandir($path);
		
		foreach($models as $key=>$val)
			if(!is_dir($path.'/'.$val))
				require_once($path.'/'.$val);
	}
	
	
	
	
	#	перекидывает модель во вью
	function view($viewName, &$model, $buffer = false)
	{
		global $_GLOBALS;
		if($buffer)
			ob_start();

		$view = realpath(ROOT.'/'.(IS_ADMINKA ? ADMIN_DIR.'/' : '').VIEWS_DIR.'/'.$viewName);
		if(file_exists($view))
		{
			foreach($GLOBALS as $key=>$val){$$key = $val;}
			${'MODEL'} = $model;
			require($view);
			//$model=NULL;
		}
		else
			echo 'eRRoR: VieW <b>"'.$viewName.'"</b> NoT FouND.';
		
		if($buffer)
			return ob_get_clean();
	}
	
	
	

	function layoutRender($layout)
	{
		global $_GLOBALS, $_CONFIG;
		
		//vd($layout);
		if($layout)
		{
			$layoutPath = (IS_ADMINKA ? ADMIN_DIR.'/' : '').VIEWS_DIR.'/'.LAYOUTS_DIR.'/'.$layout.'.php';
			
			if(file_exists($layoutPath))
				require_once($layoutPath);
			else
			{
				echo 'LaYouT <b>"'.$layout.'"</b> NoT FouND!';
				echo $_GLOBALS['CONTENT'];
			}
		}
			
		else echo $_GLOBALS['CONTENT'];
	}
	
	
	
	
	
	
	function redirect($url)
	{
		$a = '/'.(IS_ADMINKA ? ADMIN_URL_SIGN.'/' : '').$url. htmlspecialchars($_SERVER['QUERY_STRING']);
		header("Location: ".$a."");
	}
	
	
	
	
	
	#	тайтл для сайта
	function getTitle($str)
	{
		global $_GLOBALS;
		if($str = trim($str))
			return $str.$_GLOBALS['TITLE_SEPARATOR'].$_GLOBALS['MAIN_TITLE'];
		else return $_GLOBALS['MAIN_TITLE'];
	}
	
	
	
	
	static $paramsInnerSeparator = '_';
	
	
	#	возвращает массив параметров, у кторых задан ключ (типа "year_2013")
	#	ключ - 'year', значение - '2014'
	function getParams()
	{
		global $_PARAMS;
		
		$params = array();
		foreach($_PARAMS as $key=>$val)
		{
			list($param, $value) = explode(PARAMS_INNER_SEPARATOR, $val);
			if($param)
			{
				$params[$param] = $value;
			}
		}
		
		return $params;
	}
	
	
	
	function setError($fieldCode, $err)
	{
		return array('field'=>$fieldCode, 'error'=>$err);
	}
	
	
} 
?>