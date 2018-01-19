<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<meta charset="utf-8">
	
	<title><?=$_GLOBALS['TITLE']?></title>
	
	<meta name="description" content="<?=$_CONFIG['SETTINGS']['description']?>" />
	<meta name="keywords" content="<?=$_CONFIG['SETTINGS']['keywords']?>" />

	<script type="text/javascript" src="/js/libs/jquery-1.11.0.min.js"></script>
	<? require_once(INCLUDE_DIR.'/constants_js.php');?>
	
	<!--LESS-->
	<link rel="stylesheet/less" type="text/css" href="/css/style.less" />
	<link rel="stylesheet/less" type="text/css" href="/css/slonne.less" />
	<script src="/js/libs/less/less-1.7.3.min.js" type="text/javascript"></script>
	
	<script type="text/javascript" src="/js/libs/highslide-4.1.13/highslide-full.packed.js"></script>
	<link rel="stylesheet" type="text/css" href="/js/libs/highslide-4.1.13/highslide.css" />

    <link rel="stylesheet" href="/js/font-awesome-4.7.0/css/font-awesome.min.css">


    <!--стандартные js Slonne-->
	<script type="text/javascript" src="/js/common.js"></script>
	<!--кабинет-->
	<script src="/js/slonne.cabinet.js" type="text/javascript"></script>
	<!--формы-->
	<script src="/js/slonne.forms.js" type="text/javascript"></script>
	
	<!--Модальное окно-->
	<script type='text/javascript' src='/js/plugins/jquery.simplemodal/jquery.simplemodal.js'></script>
	<link rel="stylesheet" type="text/css" href="/js/plugins/jquery.simplemodal/simplemodal.css" />
	
	<!--Карусель (для клиентов)-->
	<script type='text/javascript' src='/js/plugins/jquery.jcarousellite.min.js'></script>
	
	<!--Слайдер (для индекса)-->
	<script type='text/javascript' src='/js/plugins/jquery.superslides/jquery.superslides.min.js'></script>
	<link rel="stylesheet" type="text/css" href="/js/plugins/jquery.superslides/superslides.css" />

	<!--Calendar-->
	<script type="text/javascript" language="javascript" src="/js/calendar/calendar.js"></script>
	<script type="text/javascript" language="javascript" src="/js/calendar/calendar-setup.js"></script>
	<script type="text/javascript" language="javascript" src="/js/calendar/lang/calendar-ru.js"></script>
	<link rel="StyleSheet" href="/js/calendar/calendar.css" type="text/css">
	
	<script type="text/javascript">
		hs.graphicsDir = '/js/libs/highslide-4.1.13/graphics/';
		hs.align = 'center';
		hs.transitions = ['expand', 'crossfade'];
		hs.wrapperClassName = 'dark borderless floating-caption';
		hs.fadeInOut = true;
		hs.dimmingOpacity = .65;
		hs.showCredits = false;
	
		// Add the controlbar
		if (hs.addSlideshow) hs.addSlideshow({
			//slideshowGroup: 'group1',
			interval: 5000,
			repeat: false,
			useControls: true,
			fixedControls: 'fit',
			overlayOptions: {
				opacity: .6,
				position: 'bottom center',
				hideOnMouseOut: true
			}
		});
	</script>
</head>

111111111111

<body style="background: none; padding: 15px 40px;">

			<div style="position: absolute; top: 0; left: 0; font-size: .7em;">ПаНКТаР ХоЙ!</div>

			<!--КОНТЕНТ-->
			<div class="content">
				<?=$_GLOBALS['CONTENT']?>
			</div>
			<!--//КОНТЕНТ-->
			
		

	<iframe name="iframe1" style="width: 700px; height: 400px;  background: #fff; display: none;  ">asdasd</iframe>
</body>
</html> 




<script>
jQuery(function ($) {
	// Load dialog on page load
	//$('#basic-modal-content').modal();

	// Load dialog on click
	$('.modal-opener').click(function (e) {
		$('#float-form-wrapper').modal();
		return false;
	});
});
</script>
