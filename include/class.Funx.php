<?php 
class Funx
{
    static $daysOfWeek = [
        0=>[
            'short'=>'ВС'
        ],
        [
            'short'=>'ПН'
        ],
        [
            'short'=>'ВТ'
        ],
        [
            'short'=>'СР'
        ],
        [
            'short'=>'ЧТ'
        ],
        [
            'short'=>'ПТ'
        ],
        [
            'short'=>'СБ'
        ],
    ];

	static $months=array(
		'ru'=>array(
			1=>array('Январь', 'Января'),
			array('Февраль', 'Февраля'),
			array('Март', 'Марта'),
			array('Апрель', 'Апреля'),
			array('Май', 'Мая'),
			array('Июнь', 'Июня'),
			array('Июль', 'Июля'),
			array('Август', 'Августа'),
			array('Сентябрь', 'Сентября'),
			array('Октябрь', 'Октября'),
			array('Ноябрь', 'Ноября'),
			array('Декабрь', 'Декабря'),
		),
		
		'en'=>array(
			1=>array('January', 'January'),
			array('February', 'February'),
			array('March', 'March'),
			array('April', 'April'),
			array('May', 'May'),
			array('June', 'June'),
			array('July', 'July'),
			array('August', 'August'),
			array('September', 'September'),
			array('October', 'October'),
			array('November', 'November'),
			array('December', 'December'),
		),
		
		'tur'=>array(
			1=>array('Ocak', 'Ocak'),
			array('Şubat', 'Şubat'),
			array('Mart', 'Mart'),
			array('Nisan', 'Nisan'),
			array('Mayıs', 'Mayıs'),
			array('Haziran', 'Haziran'),
			array('Temmuz', 'Temmuz'),
			array('Ağustos', 'Ağustos'),
			array('Eylül', 'Eylül'),
			array('Ekim', 'Ekim'),
			array('Kasım', 'Kasım'),
			array('Aralık', 'Aralık'),
		),
		
		
	);

	function dayOfweek($date, $format='short')
    {
        $ret = self::$daysOfWeek[date('w', strtotime($date))][$format];
        return $ret;
    }

				
				
	function mkDate($dt, $type)
	{
		list($date, $time) = explode(' ', $dt);
		list($year, $month, $day) = explode('-', $date);
		//$str.= $day.'.'.$month.'.'.$year;
		//vd(Funx::$months[$_SESSION['lang']]);
		
		switch($type)
		{	
			default:
				$str.= $day.' '.Funx::$months[LANG][(int)$month][1].' '.$year;
				break;
			
			case 'numeric':
				$str.= $day.'.'.$month.'.'.$year;
				break;
		}
		//vd($str);
		return $str;
	}			
	
	
	

	
	
	
	
	
	
	function sendMail($to, $from, $subject, $msg)
	{
		//http://phpclub.ru/detail/article/mail
		
		//$to="cobectb_rool@list.ru";
		//$subject = 'Заявка на консультацию с сайта Patent Room ';//Тема
		$bound = "qwerty"; // генерируем разделитель 
		
		//$header .= "Return-Path: ".$from."\n";
		
		//$header .= "Envelope-to: $to\n";
		$header  = "From: <".$from.">\n";
		$header .= "To: <".$to.">\n";
		$header .= "Subject: $subject\n";
		//$header .= "Date: ".date('r',time())."\n";
		$header .= "X-Mailer: PHP/".phpversion()."\n";
		$header.=	"Reply-To: $from\n";
		//$header .= "Message-ID: ".md5(uniqid(time()))."@aaa.kz\n";
		$header .= "Mime-Version: 1.0\n";
		$header .= "Content-Type: multipart/mixed; boundary=\"$bound\"\n";
		
		$body  = "--$bound\n";
		$body .= "Content-Type: text/html; charset=\"utf-8\"\n";
		$body .= "Content-Transfer-Encoding: 8bit\n\n";
		$body .= $msg;
		$body .= "\n\n";
		
		
		return mail($to, $subject, $body, $header);
		
	}
				
	
	
	
	
	
	
	function drawPages($total_elements, $pg, $onepage)
	{
		$sym['beginning']='<<';
		$sym['prev']='<';
		
		
		$sym['end']='>>';
		$sym['next']='>';

		//vd($total_elements);
	//$total_elements = mysql_num_rows(mysql_query($search_sql));
		$print_pages = '';
		$totalPages=ceil($total_elements/$onepage);
	    if($totalPages>1)
	      {
	        $print_pages .= '<div align="center" class="pages-div">';
	        if($pg>0) 
	        {
	        	$print_pages .= '
	        	<a  class="arrow beginning " title="начало"  href="'.Funx::getHref(1).'" >'.$sym['beginning'].'</a>
	        	<a class="arrow prev" title="предыдущая" href="'.Funx::getHref($pg).'">'.$sym['prev'].'</a>
	        	';
	        }
	        else
	        {
	        	$print_pages.='
	        	<div class=" arrow pages-inactive beginning">'.$sym['beginning'].'</div>
	        	<div  class="arrow pages-inactive prev">'.$sym['prev'].'</div>
	        	';
	        }
	        
	        $index = $pg>=6 ? ($pg+1<ceil($total_elements/$onepage)-5 ? $pg-5 : (ceil($total_elements/$onepage)>11 ? ceil($total_elements/$onepage)-11 : 0)) : 0;
	        for($i=1; $i<=(ceil($total_elements/$onepage)<11 ? ceil($total_elements/$onepage) : 11); $i++)
	           {
	             $index++;
	             if($index>ceil($total_elements/$onepage)) break;
	             if($index==$pg+1)
	               {
	                 $print_pages .= "<div>".$index."</div> ";
	               }else{
	                 $print_pages .= '<a href="'.Funx::getHref($index).'" >'.$index.'</a> ';
	               }
	
	           }
	        if($pg+1<ceil($total_elements/$onepage)) 
	        {
	        	$print_pages .= '
	        	<a class="arrow next" title="следующая"  href="'.Funx::getHref($pg+2).'" >'.$sym['next'].'</a>
	        	<a  class="arrow end" title="конец"  href="'.Funx::getHref($totalPages).'" >'.$sym['end'].'</a>
	        	 ';
	        }
	        else
	        {
	        	$print_pages.='
	        	<div class="arrow pages-inactive next">'.$sym['next'].'</div>
	        	<div class="arrow pages-inactive end">'.$sym['end'].'</div>
	        	';
	        }
	        $print_pages .= '
	        </div>
	        
	        <div class="clear" style="clear:both;"></div>';
	      }
	      

		return  '<table cellpadding="0" cellspacing="0" style="width: 100%" border="0"><tr><td align="center" style="text-align: center;">'.$print_pages.'</td></tr></table>';
	}
	
	
	
	
	function getHref($p)
	{
		$str = '';
		$str = $_SERVER['REQUEST_URI'];
		//vd($str);
		$tmp = explode("/", $str);
		//vd($tmp);
		$href = '/'.$tmp[1].'/'.$tmp[2].'/';
		//vd($href);
		
		foreach($tmp as $key=>$val)
		{
			//vd($val);
			if($val = trim($val))
			{
				//echo '<br>'.$key.' = '.$val;
				if($key <=2)
					continue;
					//echo "??";
				if(strpos( $val, 'p_') === 0)
					continue;
				$arr[] = $val;
				//echo "!";
				
			}
		}
		//vd($arr);
		
		$href .= join('/', $arr).(count($arr) ? '/' : '').'p_'.$p;
		//$href = str_replace('//', '/', $href );
		
		return $href;
	}
	
	
	
	

	
	
	function getSalt($length = 8)
	{
		$chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
		//$chars = 'QWERTYUPASDFGHKLZXCVBNM23456789';
		$numChars = strlen($chars);
		$string = '';
		for ($i = 0; $i < $length; $i++) 
			$string .= substr($chars, rand(1, $numChars) - 1, 1);
		
		return $string; 
	}
	
	
	
	
	
	function okon($num, $arr)
	{
		$res = $num%10;
		
		switch($res)
		{
			case 0: default: 
				$str = $arr[0];
				break;
				
			case 1: 
				$str = $arr[1];
				break;
			case 2: case 3: case 4:
				$str = $arr[2]; 
				break;
		}
		return $str;
	}
	
	
	
	
	
	function isDateValid($date)
	{
		return date('Y-m-d', strtotime($date)) == $date; 
	}
	
	
	
	function daysBetween($d1, $d2)
	{
		$start = strtotime($d1);
		$end = strtotime($d2);
		
		$daysBetween = ceil(abs($end - $start) / 86400);
		
		return $daysBetween;
	}
	
	
	
	
	
	function numberFormat($a)
	{
		return number_format($a, 0, '', ' ');
	}
	
	
	
}










?>