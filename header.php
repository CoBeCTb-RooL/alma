<?php
error_reporting(E_ERROR  | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING | E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE);
session_start();

#	необходимые подключения
require_once('config.php');
require_once(INCLUDE_DIR.'/funx.php');

#	необходимые классы
//require_once(INCLUDE_DIR.'/class.Slonne.php');
require_once(INCLUDE_DIR.'/class.Slonne2.php');
require_once(INCLUDE_DIR.'/class.Funx.php');
require_once(INCLUDE_DIR.'/class.DB.php');


/*
require_once(INCLUDE_DIR.'/class.Essence.php');
require_once(INCLUDE_DIR.'/class.Field.php');
require_once(INCLUDE_DIR.'/class.Entity.php');
require_once(INCLUDE_DIR.'/class.Slonne.php');*/

require_once(INCLUDE_DIR.'/class.ReferalTail.php');
//require_once(INCLUDE_DIR.'/class.Constant.php'); 
require_once(INCLUDE_DIR.'/class.MainController.php');
require_once(INCLUDE_DIR.'/class.Form.php');

#	подгружаем модели админа
Slonne::loadModels(ADMIN_DIR.'/'.MODELS_DIR);
Slonne::loadModels(ADMIN_DIR.'/'.MODELS_DIR.'/catalog');
Slonne::loadModels(ADMIN_DIR.'/'.MODELS_DIR.'/adv');

#	подгружаем модели
Slonne::loadModels(MODELS_DIR);
Slonne::loadModels(MODELS_DIR.'/Stock');



#	подключение к базе
DB::connect();

#	считываем рефералки
ReferalTail::init();

#	инициализация констант
Constants::assemble();

#	инициализация настроек
$_CONFIG['SETTINGS'] = Settings::get();

$tmp = null;

?>