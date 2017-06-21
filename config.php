<?
if($_SERVER['SERVER_ADDR'] == '127.0.0.1')
{
	define('DB_USER', 'root');
	define('DB_PASSWORD', '');
	define('DB_HOST', '127.0.0.1');
	define('DB_NAME', 'alma');
}
else
{
	define('DB_USER', 'v_93999_alma');
	define('DB_PASSWORD', 'Wvks%204ld88$D0l');
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'v_93999_alma');
}
//////d

#	лэйаут по умолчанию
$_CONFIG['DEFAULT_LAYOUT'] = 'mainLayout';

#	лэйаут админки по умолчанию
$_CONFIG['DEFAULT_ADMIN_LAYOUT'] = 'adminLayout';

#	дефолтовые ящики, на которые будут уходить все фидбеки, формы и тд
$_CONFIG['DEFAULT_DELIVERY_EMAILS'] = array('A@mail.ru', 'B@yandex.com');


$_CONFIG['LANGS']=array(
	'ru'=>array('title'=>'Русская', 'postfix'=>'', 'siteTitle'=>'Rus',  ),
	'en'=>array('title'=>'Engish',  'postfix'=>'_en', 'siteTitle'=>'Eng', ),
//	'kz'=>array('title'=>'Қазақ',  'postfix'=>'_kz', 'siteTitle'=>'Каз', ),
//	'tur'=>array('title'=>'Türk',  'postfix'=>'_tur', 'siteTitle'=>'Tur', ),

);


#	ЯЗЫК ПО УМОЛЧАНИЮ
$_CONFIG['DEFAULT_LANG'] = 'ru'; 
$_CONFIG['default_admin_lang'] = 'ru'; 



define('CONTROLLERS_DIR', 	'[controllers]');
define('VIEWS_DIR', 		'[views]');
define('MODELS_DIR', 		'[models]');


#	папка с общими вьюхами
define('SHARED_VIEWS_DIR', 	'SHARED');

#	папка с лэйаутами
define('LAYOUTS_DIR', 	'[layouts]');

#	
define('ABS_PATH_TO_RESIZER_SCRIPT', '/resize.php');
//define('ABS_PATH_TO_RESIZER_SCRIPT', '/imgresize.php');

#	корень (ну там если придётся сделать псевдо-относительный путь)            
define('ROOT', $_SERVER['DOCUMENT_ROOT']);

#	относительный путь к папке со всеми инклудами
define('INCLUDE_DIR', 'include');

#	Папки с медиа
define('UPLOAD_IMAGES_REL_DIR', 'upload/images/');

#	Значение 1-го кусочка урла, говорящего что это админка
define('ADMIN_URL_SIGN', 'admin');

#	папка - админка
define('ADMIN_DIR', '[admin]');

#	разделитель для параметров и значений в гете (метод Slonne::getParams )
define('PARAMS_INNER_SEPARATOR', '_');


?>