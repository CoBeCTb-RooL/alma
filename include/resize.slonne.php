<?php
//header ("content-type: image/jpeg");
require_once('../include/class.ImageResize.php');

$img = $_GET['img'];
$method = $_GET['method'];

$w = $_GET['width'];
$h= $_GET['height'];
$coef = $_GET['coef'];

$image = new ImageResize($img);
$image->quality_jpg = 100;
$image->quality_png = 100;

switch($method)
{
	case 'resize': 
		if($w && !$h)
			$image->resizeToWidth($w);
		if($h && !$w)	
			$image->resizeToHeight($h);
		if($w && $h)
			$image->resize($w, $h);
		
		break; 
		
	case 'crop':
		if($w && !$h) $h = $w;
		if($h && !$w) $w = $h;
		$image->crop($w, $h);
		break;
		
		
		
		
		
	default: 
		if($w && $h)
			$image->crop($w, $h);
		elseif($w)
		{
			
			$image->resizeToWidth($w);
		}
		elseif($h)
			$image->resizeToHeight($h);	
		break;
}

/*
$image->scale(50);

$image->crop(400, 400);*/
$image->output(); 
?>