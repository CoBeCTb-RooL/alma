<?php
function vd($a)
{
	echo '<pre>';
	var_dump($a);
	echo '</pre>';
}

error_reporting(E_ERROR  | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING | E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE);
session_start();
//$_SESSION = null;
//vd($_SESSION); return;
require_once('config.php');

$url = $_SERVER['PATH_INFO'];
$urlParts=explode("/", $url);
$tmp = array();
foreach($urlParts as $key=>$val)
	if($val = trim($val))
		$tmp[] = $val;
$urlParts = $tmp;


if($_CONFIG['LANGS'][$urlParts[0]])		#	1-й кусок урла - язык
{
	define('IS_ADMINKA', false);
	define('LANG', $urlParts[0]);
	$_SESSION['lang'] = LANG;	#	для того, чтобы в аджаксовыхзапросах помнить язык
	$moduleIndexInUrl = 1;
}	
elseif($urlParts[0] == ADMIN_URL_SIGN)	#	админка
{
	define('IS_ADMINKA', true);
	define('LANG', $_CONFIG['DEFAULT_LANG']);
	$moduleIndexInUrl = 1;
} 	
else	#	Безъязыковый запрос (скорее всего AJAX)
{
	define('IS_ADMINKA', false);	
	define('LANG', $_SESSION['lang']);
	$moduleIndexInUrl = 0;
}
$module = $urlParts[$moduleIndexInUrl];
if(!$module) 
	$module='index';
$module .= 'Controller';


#	наполняем $_PARAMS
$urlSectionsCount=count($urlParts);
for ($i = ($moduleIndexInUrl+1); $i < $urlSectionsCount; $i++) 
	$_PARAMS[$i-($moduleIndexInUrl+1)] = $urlParts[$i];


require_once('header.php'); 




#	наполнение глобальных переменных фронтэнда (ЕСЛИ ЕСТЬ ЛЭЙАУТ)
if(!IS_ADMINKA)
{
	if(!$_GLOBALS['NO_LAYOUT'])
		require_once('startup.php');
}
else	#	в любом случае для админки
{
	require_once(ADMIN_DIR.'/startup.php');
	
	#	редирект к авторизации
	$_GLOBALS['ADMIN'] = Admin::get($_SESSION['admin']['id'], $active=true);
	if($module!='authController' )
		if(!$_GLOBALS['ADMIN'])
			Slonne::redirect("auth");	
		
	if($_GLOBALS['ADMIN'])
	{
		$_GLOBALS['ADMIN']->initGroup();
		$_GLOBALS['ADMIN']->group->initPrivilegesArr();
		$ADMIN = $_GLOBALS['ADMIN'];
	}
}
	


/*echo "!";
$sql="select * from slonne__constants";
$qr=mysql_query($sql);
echo mysql_error();
while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
{
	//vd($next);
	$arr = array('ru'=>$next['value'], 'en'=>$next['value_en'], 'tur'=>$next['value_tur']);
	
	$sql="insert into slonne__constants2 set name='".strPrepare($next['name'])."', value='".strPrepare(json_encode($arr))."'";
	mysql_query($sql);
	echo mysql_error();
	vd($sql);
}*/

/*$a = Constants::get(1);
vd($a);*/



ob_start();
$arr = NULL;




$controllerPath = (IS_ADMINKA ? ADMIN_DIR.'/' : '').CONTROLLERS_DIR.'/'.$module.'.php'; 

if(!file_exists($controllerPath))
	$module = 'errorController';

define('CURRENT_CONTROLLER', $module);	
$controllerPath = (IS_ADMINKA && $module != 'errorController' ? ADMIN_DIR.'/' : '').CONTROLLERS_DIR.'/'.CURRENT_CONTROLLER.'.php';

require_once($controllerPath);


#	задача контроллеров - наполнить переменные $ACTION и $CONTROLLER(при желании)
#	дальще просто вызывается соответствующий экшн соотв. контроллера:
MainController::action($ACTION, $CONTROLLER ? $CONTROLLER : CURRENT_CONTROLLER);	

$_GLOBALS['CONTENT']=ob_get_clean();


$LAYOUT = '';
$defaultLayout = IS_ADMINKA ? $_CONFIG['DEFAULT_ADMIN_LAYOUT'] : $_CONFIG['DEFAULT_LAYOUT'];	

if(!$_GLOBALS['NO_LAYOUT'])
	$LAYOUT = $_GLOBALS['LAYOUT'] ? $_GLOBALS['LAYOUT'] : $defaultLayout;

if(IS_ADMINKA)
	$_GLOBALS['TITLE'] = 'SLoNNe CMS';

Slonne::layoutRender($LAYOUT); 

?>