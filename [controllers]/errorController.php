

<?php


 //vd($_PARAMS);
//echo "!!! АШЫПКА!!!!";
 $error=$_PARAMS[0];
// vd($_REQUEST['id'])

$_GLOBALS['CONTENT'].="";

$model = array();
switch ($error){
	case 404:default:
        $_GLOBALS['TITLE'] = 'Ошибка 404 файл не существует';
		echo  '
            <div class="pad">
			<h1>Ошибка 404 - Страница не найдена</h1>

            <p>
<script type=«text/javascript»>var GOOG_FIXURL_LANG = \'ru\';var GOOG_FIXURL_SITE = '.$_SERVER['SERVER_NAME'].';</script>
<script type=«text/javascript» src=«linkhelp.clients.google.com/tbproxy/lh/wm/fixurl.js»></script>

</p>
            <img src="/images/_error404.gif" alt="Ошибка" width="70" height="68" align="left" style="margin: 15px;" />
			<h2>Что случилось?</h2>
			<p>Возможно что страница была удалена, или вы перешли по неверной ссылке ссылке.</p>
			<h2>Что делать?</h2>
			<p>Вы можете перейти на <a href="/">главную страницу</a> и воспользоваться навигацией. Возможно вы найдете то, что искали...</p>
            </div>';
        break;
	case 403:
        $_GLOBALS['TITLE'] = 'Ошибка 403 доступ запрещён';
		$_GLOBALS['CONTENT'] = '
            <img src="/images/_error404.gif" alt="Ошибка" width="70" height="68" align="left" style="margin: 15px;" />
            <div class="pad"><h1> Ошибка 403 Доступ запрещён</h1>
            <h2>Что делать?</h2>
			<p>Вы можете перейти на <a href="/">главную страницу</a> и воспользоваться навигацией или поиском. Возможно вы найдете то, что искали...</p>
            </div>';
		break;
	case 402:
        $_GLOBALS['TITLE'] = 'Ошибка 402 запрошенный документ является платным ресурсом';
		echo '
            <img src="/images/_error404.gif" alt="Ошибка" width="70" height="68" align="left" style="margin: 15px;" />
            <div class="pad">
			<h1>  Ошибка 402 запрошенный документ является платным ресурсом
            <h2>Что делать?</h2>
			<p>Вы можете перейти на <a href="/">главную страницу</a> и воспользоваться навигацией или поиском. Возможно вы найдете то, что искали...</p>
            </div>';
		break;
	case 401:
        $_GLOBALS['TITLE'] = 'Ошибка 401 у Вас недостаточно прав для просмотра этой страницы';
		echo '
        <img src="/images/_error404.gif" alt="Ошибка" width="70" height="68" align="left" style="margin: 15px;" />  
        <div class="pad"><h1>
        Ошибка 401 У Вас недостаточно прав для просмотра этой страницы
        <h2>Что делать?</h2>
        <p>Вы можете перейти на <a href="/">главную страницу</a> и воспользоваться навигацией или поиском. Возможно вы найдете то, что искали...</p>
        </div>';
		break;
}


?>