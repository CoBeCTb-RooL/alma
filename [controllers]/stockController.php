<?php 

//vd(Currency::$items);


//vd($_PARAMS[0]);
switch($_PARAMS[0])
{
	case "graphics":
		if($_PARAMS[1] == 'percents')
			$ACTION = 'graphicPercents';
		if($_PARAMS[1] == 'levels')
			$ACTION = 'graphicLevels';
		if($_PARAMS[1] == 'open_interest')
			$ACTION = 'graphicOpenInterest';
		
		break;
		
	case "data":
		$ACTION = 'data';
		break; 
	case "dataSubmit":
		$ACTION = 'dataSubmit';
		break;
		
	
	case "open_interest_data":
		$ACTION = 'openInterestData';
		break;
	case "openInterestDataSubmit":
		$ACTION = 'openInterestDataSubmit';
		break;
		
		
	case "analytics":
		$ACTION = 'analytics';
		break;
	case "analyticsEdit":
		$ACTION = 'analyticsEdit';
		break;
	case "analyticsEditSubmit":
		$ACTION = 'analyticsEditSubmit';
		break;
	case "analyticsDelete":
		$ACTION = 'analyticsDelete';
		break;
	case "analyticsDetails":
		$ACTION = 'analyticsDetails';
		break;

    case "optionalAnalysis":
        $ACTION = 'optionalAnalysis';
        if($_PARAMS[1] == 'submit')
            $ACTION = 'optionalAnalysisFormSubmit';
        if($_PARAMS[1] == 'statsAjax')
            $ACTION = 'optionalAnalysisStatsAjax';
        if($_PARAMS[1] == 'deleteAjax')
            $ACTION = 'deleteAjax';
        break;


    case "optionalAnalysis2":
        $ACTION = 'optionalAnalysis2';
        if($_PARAMS[1] == 'submit')
            $ACTION = 'optionalAnalysisFormSubmit2';
        if($_PARAMS[1] == 'statsAjax')
            $ACTION = 'optionalAnalysisStatsAjax2';
        if($_PARAMS[1] == 'deleteAjax')
            $ACTION = 'optionalAnalysisDeleteAjax2';
        break;

}
	




class StockController extends MainController{
	
	
	function index()
	{
		global $_GLOBALS;
		
		Slonne::view('stock/index.php', $MODEL);
	}
	
	
	
	/*function graphics()
	{
		global $_GLOBALS, $_CONFIG;
		$_GLOBALS['LAYOUT'] = 'stockLayout';
		
		$_GLOBALS['TITLE'] = Slonne::getTitle('Графики');
		 
		
		Slonne::view('stock/graphics.php', $MODEL);
	}*/
	
	
	
	
	
	function data()
	{
		global $_GLOBALS, $_CONFIG;
	
		$_GLOBALS['TITLE'] = Slonne::getTitle('Данные');
		
		//vd(date('Y-m-d H:i:s'));
		
		$MODEL['currentCur'] = Currency::$items[$_REQUEST['currency']] ? Currency::$items[$_REQUEST['currency']] : Currency::$items[Currency::CODE_EUR];
		
		$today = date('Y-m-d');
		$dateFromRequest = $_REQUEST['date'];
		
		# 	валидируем дату из РЕКУЭСТА
		if($dateFromRequest && !Funx::isDateValid($dateFromRequest))
			$dateFromRequest = $today;
		
		if($dateFromRequest && $dateFromRequest < $today)
			$date = $dateFromRequest;
		else
			$date = $today;
		
		
		$prevDate = date('Y-m-d', strtotime($date . ' - 1 day'));
		$nextDate = $date != $today ? date('Y-m-d', strtotime($date . ' + 1 day')) : null;
		
		$MODEL['date'] = $date; 
		$MODEL['today'] = $today;
		$MODEL['datePrev'] = $prevDate;
		$MODEL['dateNext'] = $nextDate;
		
		$strikes = Strike::getByCurrencyAndDate($MODEL['currentCur']->code, $date);
		//vd($strikes);
		$MODEL['strikes'] = $strikes;
		
	
		Slonne::view('stock/data.php', $MODEL);
	}
	
	
	
	
	function dataSubmit()
	{
		global $_GLOBALS, $_CONFIG;
	
		$_GLOBALS['NO_LAYOUT'] = true; 
		$error = '';
		
		$date = $_REQUEST['date'];
		
		$currency = Currency::$items[$_REQUEST['currency']];
		//$date = null;
		
		//vd($_FILES);
		
		if($currency)
		{
			if($date)
			{
				if($fileContent = $_FILES['file']['tmp_name'])
				{
					$strikes = DailyReport::parseFile(file_get_contents($fileContent), $dateCreated = $_REQUEST['date']);
					//vd($strikes);
					if(count($strikes))
					{
						# 	проверак на верность ТИПА
						$problem  = false;
						foreach($strikes as $type=>$val)
							if(!in_array($type, array(Strike::TYPE_CALL, Strike::TYPE_PUT)))
								$problem = 'ОШИБКА! Присутствует непонятный тип ('.$type.')...';
								
							if(!$problem )
							{
								$html =  'всё ок!
						<br>---------------
						<br>дата: '.$date.'
						<br>CALL: '.count($strikes[Strike::TYPE_CALL]).'
						<br>PUT: '.count($strikes[Strike::TYPE_PUT]).'
						<p><a href="#" onclick="location.reload(); return false; ">смотреть</a>';
			
								//vd($date);
								# 	вычищаем день
								Strike::cleanDateByCurrency($date, $currency->code);
			
								foreach($strikes as $type=>$items)
								{
									foreach($items as $key=>$item)
									{
										$item->type = $type;
										$item->currency = $currency->code; 
										$item->insert();
									}
								}
							}
							else
								$error = $problem ;
					}
					else
						$error = 'ОШИБКА! Не удалось считать информацию, вероятно, недопустимый формат файла..';
				}
				else
					$error = 'ОШИБКА! Файл пуст!';
			}
			else
				$error = 'ОШИБКА! Не удалось считать дату..';
		}
		else
			$error = 'Ошибка! Не удалось понять валюту ('.$_REQUEST['currency'].')';
		
		
		
		$json['html'] = $html;
		$json['error'] = $error;
		
		?>
		<script>window.top.dataSubmitComplete(<?=json_encode($json)?>)</script>
		<?php 
		
	}
	
	
	
	
	
	
	
	function graphicPercents()
	{
		global $_GLOBALS, $_CONFIG;
		
		$_GLOBALS['TITLE'] = Slonne::getTitle('График "ПРОЦЕНТЫ"');
		
		$MODEL['currentCur'] = Currency::$items[$_REQUEST['currency']] ? Currency::$items[$_REQUEST['currency']] : Currency::$items[Currency::CODE_EUR];
		
		$dateFrom = $_REQUEST['dateFrom'];
		$dateTo = $_REQUEST['dateTo'];
		
		$today = date('Y-m-d');
		
		if(!$dateFrom || !Funx::isDateValid($dateFrom))
			$dateFrom = date('Y-m-d', strtotime($today. ' - 6 day'));
			
		if(!$dateTo || !Funx::isDateValid($dateTo))
			$dateTo = $today;
		
		if($dateFrom > $dateTo) 
		{
			$tmp = $dateFrom;
			$dateFrom = $dateTo;
			$dateTo = $tmp;
		}
		
		
		# 	берём по доп дню с обеих сторон, так как последний день - это собственно dateTo, а самый первый мы неотображаем, но относительно него будет рисоваться график

		for($i = 1; $i<=Funx::daysBetween($dateFrom, $dateTo)+1; $i++)
		{
			$d = date('Y-m-d', strtotime($dateFrom)+(3600*24)*($i-1));
			$reports[$d] = DailyReport::getReport($MODEL['currentCur'], $d);
			$reports[$d]->strikes = 'cleared';
		}
		//vd($reports);
		
		
		$MODEL['dateFrom'] = $dateFrom;
		$MODEL['dateTo'] = $dateTo;
		$MODEL['today'] = $today;
		
		$MODEL['reports'] = $reports;
		
		
		Slonne::view('stock/graphics/percents.php', $MODEL);
	}
	
	
	
	
	
	
	
	
	function graphicLevels()
	{
		global $_GLOBALS, $_CONFIG;
	
		$_GLOBALS['TITLE'] = Slonne::getTitle('График "УРОВНИ"');
	
		$MODEL['currentCur'] = Currency::$items[$_REQUEST['currency']] ? Currency::$items[$_REQUEST['currency']] : Currency::$items[Currency::CODE_EUR];
		//vd(date('Y-m-d H:i:s'));
	
		$today = date('Y-m-d');
		$dateFromRequest = $_REQUEST['date'];
	
		# 	валидируем дату из РЕКУЭСТА
		if($dateFromRequest && !Funx::isDateValid($dateFromRequest))
			$dateFromRequest = $today;
	
		if($dateFromRequest && $dateFromRequest < $today)
			$date = $dateFromRequest;
		else
			$date = $today;


		$prevDate = date('Y-m-d', strtotime($date . ' - 1 day'));
		$nextDate = $date != $today ? date('Y-m-d', strtotime($date . ' + 1 day')) : null;

		$MODEL['date'] = $date;
		$MODEL['today'] = $today;
		$MODEL['datePrev'] = $prevDate;
		$MODEL['dateNext'] = $nextDate;

		
		$levelsOfDay = LevelsOfDay::getByCurrencyAndDate($MODEL['currentCur']->code, $date);
		//vd($strikes);
		$MODEL['levelsOfDay'] = $levelsOfDay;
			

		Slonne::view('stock/graphics/levels.php', $MODEL);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	function openInterestData()
	{
		global $_GLOBALS, $_CONFIG;
	
		$_GLOBALS['TITLE'] = Slonne::getTitle('Открытый интерес::ДАННЫЕ');
	
		//vd(date('Y-m-d H:i:s'));
	
		$MODEL['currentCur'] = Currency::$items[$_REQUEST['currency']] ? Currency::$items[$_REQUEST['currency']] : Currency::$items[Currency::CODE_EUR];
	
		$today = date('Y-m-d');
		$dateFromRequest = $_REQUEST['date'];
	
		# 	валидируем дату из РЕКУЭСТА
		if($dateFromRequest && !Funx::isDateValid($dateFromRequest))
			$dateFromRequest = $today;
	
			if($dateFromRequest && $dateFromRequest < $today)
				$date = $dateFromRequest;
			else
				$date = $today;
	
	
			$prevDate = date('Y-m-d', strtotime($date . ' - 1 day'));
			$nextDate = $date != $today ? date('Y-m-d', strtotime($date . ' + 1 day')) : null;
	
			$MODEL['date'] = $date;
			$MODEL['today'] = $today;
			$MODEL['datePrev'] = $prevDate;
			$MODEL['dateNext'] = $nextDate;
	
			$oiStrikes = OpenInterest::sortByType(OpenInterest::getByCurrencyAndDate($MODEL['currentCur']->code, $date));
			//vd($oiStrikes);
			$MODEL['oiStrikes'] = $oiStrikes;
	
	
			Slonne::view('stock/openInterestData.php', $MODEL);
	}
	
	
	
	
	
	
	function openInterestDataSubmit()
	{
		global $_GLOBALS, $_CONFIG;
	
		$_GLOBALS['NO_LAYOUT'] = true;
		$error = '';
	
		$date = $_REQUEST['date'];
	
		$currency = Currency::$items[$_REQUEST['currency']];
		//$date = null;
	
		//vd($_FILES);
	
		if($currency)
		{
			if($date)
			{
				if($fileContent = $_FILES['file']['tmp_name'])
				{
					$strikes = OpenInterest::parseFile(file_get_contents($fileContent));
					//vd($strikes);
					//return; 
					if(count($strikes))
					{
						# 	проверак на верность ТИПА
						$problem  = false;
						foreach($strikes as $type=>$val)
							if(!in_array($type, array(Strike::TYPE_CALL, Strike::TYPE_PUT)))
								$problem = 'ОШИБКА! Присутствует непонятный тип ('.$type.')...';
	
							if(!$problem )
							{
								$html =  'всё ок!
						<br>---------------
						<br>дата: '.$date.'
						<br>CALL: '.count($strikes[Strike::TYPE_CALL]).'
						<br>PUT: '.count($strikes[Strike::TYPE_PUT]).'
						<p><a href="#" onclick="location.reload(); return false; ">смотреть</a>';
									
								//vd($date);
								# 	вычищаем день
								OpenInterest::cleanDateByCurrency($date, $currency->code);
									
								foreach($strikes as $type=>$items)
								{
									foreach($items as $key=>$item)
									{
										$item->type = $type;
										$item->currency = $currency->code;
										$item->dateCreated = $date;
										$item->insert();
									}
								}
							}
							else
								$error = $problem ;
					}
					else
						$error = 'ОШИБКА! Не удалось считать информацию, вероятно, недопустимый формат файла..';
				}
				else
					$error = 'ОШИБКА! Файл пуст!';
			}
			else
				$error = 'ОШИБКА! Не удалось считать дату..';
		}
		else
			$error = 'Ошибка! Не удалось понять валюту ('.$_REQUEST['currency'].')';
	
	
	
		$json['html'] = $html;
		$json['error'] = $error;
	
		?>
		<script>window.top.dataSubmitComplete(<?=json_encode($json)?>)</script>
		<?php 
			
	}
	
	
	
	
	
	
	
	
	function graphicOpenInterest()
	{
		global $_GLOBALS, $_CONFIG;
	
		$_GLOBALS['TITLE'] = Slonne::getTitle('График "ОТКРЫТЫЙ ИНТЕРЕС"');
	
		$MODEL['currentCur'] = Currency::$items[$_REQUEST['currency']] ? Currency::$items[$_REQUEST['currency']] : Currency::$items[Currency::CODE_EUR];
		//vd(date('Y-m-d H:i:s'));
	
		$today = date('Y-m-d');
		$dateFromRequest = $_REQUEST['date'];
	
		# 	валидируем дату из РЕКУЭСТА
		if($dateFromRequest && !Funx::isDateValid($dateFromRequest))
			$dateFromRequest = $today;
	
			if($dateFromRequest && $dateFromRequest < $today)
				$date = $dateFromRequest;
			else
				$date = $today;
	
	
			$prevDate = date('Y-m-d', strtotime($date . ' - 1 day'));
			$nextDate = $date != $today ? date('Y-m-d', strtotime($date . ' + 1 day')) : null;
	
			$MODEL['date'] = $date;
			$MODEL['today'] = $today;
			$MODEL['datePrev'] = $prevDate;
			$MODEL['dateNext'] = $nextDate;
	
			$oiStrikes = OpenInterest::sortByStrikeAndType(OpenInterest::getByCurrencyAndDate($MODEL['currentCur']->code, $date));
			
			$MODEL['oiStrikes'] = $oiStrikes;
	
	
			Slonne::view('stock/graphics/openInterest.php', $MODEL);
	}
	
	
	
	
	
	
	
	
	
	
	function analytics()
	{
		global $_GLOBALS, $_CONFIG;
	
		$_GLOBALS['TITLE'] = Slonne::getTitle('Аналитика');
		
		$MODEL['list'] = ExpertResult::getList(array(
											'orderBy'=>' id DESC ',
		));
		
		Slonne::view('stock/analytics.php', $MODEL);
	}
	
	
	
	
	
	function analyticsEdit()
	{
		global $_GLOBALS, $_CONFIG;
	
		$_GLOBALS['TITLE'] = Slonne::getTitle('Аналитика');
	
		$MODEL['item'] = ExpertResult::get($_REQUEST['id']);
	
		Slonne::view('stock/analyticsEdit.php', $MODEL);
	}
	
	
	
	function analyticsEditSubmit()
	{
		global $_GLOBALS, $_CONFIG;
	
		$_GLOBALS['TITLE'] = Slonne::getTitle('Аналитика');
	
		//vd($_REQUEST);
		$e = ExpertResult::get($_REQUEST['id']);
		if(!$e)
			$e = new ExpertResult();
		
		$e->symbolCode = strPrepare($_REQUEST['symbol']);
		$e->spread = strPrepare($_REQUEST['spread']);
		$e->tp = strPrepare($_REQUEST['tp']);
		$e->sl = strPrepare($_REQUEST['sl']);
		$e->totalProfit = strPrepare($_REQUEST['totalProfit']);
		$e->totalLoss = strPrepare($_REQUEST['totalLoss']);
		$e->months = strPrepare($_REQUEST['months']);
		$e->continuousProfit = strPrepare($_REQUEST['continuousProfit']);
		$e->continuousLoss = strPrepare($_REQUEST['continuousLoss']);
		$e->period = strPrepare($_REQUEST['period']);
		$e->dealsProfit = strPrepare($_REQUEST['dealsProfit']);
		$e->dealsLoss = strPrepare($_REQUEST['dealsLoss']);
		$e->comment = strPrepare($_REQUEST['comment']);
		
		
		if($e->id)
			$e->update();
		else 
			$e->insert();
		
		echo 'Сохранено!';	
		echo '<script>location.href="/ru/stock/analytics"</script>';
		//Slonne::view('stock/analyticsEdit.php', $MODEL);
	}
	
	
	
	
	function analyticsDelete()
	{
		global $_GLOBALS, $_CONFIG;
	
		$_GLOBALS['TITLE'] = Slonne::getTitle('Аналитика');
	
		//vd($_REQUEST);
		$e = ExpertResult::get($_REQUEST['id']);
		if($e)
			$e->delete();
	
		echo 'Сохранено!';
		echo '<script>location.href="/ru/stock/analytics"</script>';
		//Slonne::view('stock/analyticsEdit.php', $MODEL);
	}
	
	
	
	
	function analyticsDetails()
	{
		global $_GLOBALS, $_CONFIG;
	
		$_GLOBALS['TITLE'] = Slonne::getTitle('Аналитика ДЕТАЛИ');
		
		
		if($_REQUEST['go_btn'])
		{
			$MODEL['item'] = ExpertResult::get($_REQUEST['id']);
			//vd($_REQUEST);
			$stat = $_REQUEST['stat'];
			//vd($stat);
			$rows = explode("\r\n", $stat);
			//vd($rows);
			
			
			
			ExpertResultDeal::deleteByResultId($MODEL['item']->id);
			foreach($rows as $rowStr)
			{
				$erd = new ExpertResultDeal($rowStr);
				
				# 	фиксируем ближайший modify
				if($erd->type == 'modify')
					$lastModify = $erd;
				//vd($lastModify);
				
				if($erd->type !='buy' && $erd->type !='sell' && $erd->type !='modify' )
				{
					$erd->resultId = intval($MODEL['item']->id);
					$erd->dtModify = $lastModify->dt;
					$erd->insert();
				}
			}
		}
		
		
		$MODEL['item'] = ExpertResult::get($_REQUEST['id']);
		$MODEL['item']->initDetails();
		
	
		Slonne::view('stock/analyticsDetails.php', $MODEL);
	}




    function optionalAnalysis()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['TITLE'] = Slonne::getTitle('Опционный анализ');

        $list = OptionalAnalysisItem::getList(['dt'=>date('Y-m-d'), 'orderBy'=>'dt DESC', ]);
        //vd($list);
        $MODEL['list'] = OptionalAnalysisItem::arrangeList($list);
        $MODEL['list2'] = OptionalAnalysisItem::arrangeList2($list);
        //vd($MODEL['list2']);

        $MODEL['currencies'] = [
            Currency::code(Currency::CODE_EUR),
            Currency::code(Currency::CODE_GBP),
        ];

        Slonne::view('stock/optionalAnalysis/index.php', $MODEL);
    }


    public function optionalAnalysisFormSubmit()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['NO_LAYOUT'] = true;

        $error = '';

        /*vd($_REQUEST);
        die;*/

        /*if(!$_REQUEST['globalStrike'])
            $error = 'Введи глобальный страйк';*/

        if(!$error)
        {
            $cur = Currency::code($_REQUEST['currency']);

            //vd($_REQUEST);

            $buy = new OptionalAnalysisItem(OptionalAnalysisItem::arrangeDataFromArray($_REQUEST, StrikeType::BARRIER,Type::BUY));
            $sell = new OptionalAnalysisItem(OptionalAnalysisItem::arrangeDataFromArray($_REQUEST,StrikeType::BARRIER,Type::SELL));
            $buyMain = new OptionalAnalysisItem(OptionalAnalysisItem::arrangeDataFromArray($_REQUEST, StrikeType::MAIN, Type::BUY));
            $sellMain = new OptionalAnalysisItem(OptionalAnalysisItem::arrangeDataFromArray($_REQUEST,StrikeType::MAIN,Type::SELL));

            //vd($buy);
            //die;
            $buy->deletePreviousData();
            $buy->insert();

            //vd($sell);
            $sell->deletePreviousData();
            $sell->insert();

            //vd($sell);
            $buyMain->deletePreviousData();
            $buyMain->insert();

            $sellMain->deletePreviousData();
            $sellMain->insert();

            echo '<script>window.top.Opt.drawStats(); </script>';
        }
        else
            echo '<script>window.top.alert("'.$error.'")</script>';

    }



    public function optionalAnalysisStatsAjax()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['NO_LAYOUT'] = true;

        $list = OptionalAnalysisItem::getList([/*'dt'=>date('Y-m-d'),*/ 'orderBy'=>'currency,  `strikeType` desc', ]);
        //vd($list);
        $MODEL['list'] = OptionalAnalysisItem::arrangeList($list);
        $MODEL['list2'] = OptionalAnalysisItem::arrangeList2($list);
        $MODEL['list3'] = OptionalAnalysisItem::arrangeListByDate($list);

        //vd($_REQUEST);

        Slonne::view('stock/optionalAnalysis/statsAjax.php', $MODEL);
    }



    public function deleteAjax()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['NO_LAYOUT'] = true;

        OptionalAnalysisItem::deleteByDateAndCurrencyAndType($_REQUEST['date'], $_REQUEST['currency'], $_REQUEST['type']);
    }





}


?>