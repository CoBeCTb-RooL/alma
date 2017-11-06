<?php 
class MainController{
	
	
	
	function action($action, $controller, $params = array())
	{
		global $_GLOBALS;
		//vd($controller);
		
		if($controller)
		{
			if(class_exists($controller))
			{
				if($action)
				{
					if(method_exists($controller, $action))
					{
						//eval($controller.'::'.$action.'();');
						
						call_user_func_array(''.$controller.'::'.$action.'', $params);
					}
					else
						echo 'uNDeFiNeD aCTioN <b>"'.$action.'"</b> iN <b>"'.$controller.'"</b>';
				}
				else
					eval($controller.'::index();');
			}
			else
				echo 'uNDeFiNeD CoNTRoLLeR <b>"'.$controller.'"</b>';
		}
		else
			echo 'No CoNTRoLLeR iNiTiaLiZeD';
			
		
	}
	
	
	
	
	function index()
	{
		global $_GLOBALS;

	}
	
}
?>