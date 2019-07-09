<?php
//vd(Currency::$items);

switch($_PARAMS[0])
{


    case "v2":
        $ACTION = 'v2index';
        if($_PARAMS[1] == 'submit')
            $ACTION = 'v2formSubmit';
        if($_PARAMS[1] == 'statsAjax')
            $ACTION = 'v2statsAjax';
        if($_PARAMS[1] == 'deleteAjax')
            $ACTION = 'v2deleteAjax';
    break;


	case "v3":
		$ACTION = 'v3index';
		if($_PARAMS[1] == 'submit')
			$ACTION = 'v3formSubmit';
		if($_PARAMS[1] == 'statsAjax')
			$ACTION = 'v3statsAjax';
		if($_PARAMS[1] == 'graphicAjax')
			$ACTION = 'v3graphicAjax';
		if($_PARAMS[1] == 'switchDoneAjax')
			$ACTION = 'v3switchDoneAjax';
		if($_PARAMS[1] == 'graphic2Ajax')
			$ACTION = 'v3graphic2Ajax';
		if($_PARAMS[1] == 'deleteBunch')
			$ACTION = 'v3deleteBunch';
		if($_PARAMS[1] == 'setBunchStatus')
			$ACTION = 'v3setBunchStatus';
		if($_PARAMS[1] == 'saveBunchTitle')
			$ACTION = 'v3saveBunchTitle';
		if($_PARAMS[1] == 'switchShowOnGraphicAjax')
			$ACTION = 'v3switchShowOnGraphicAjax';

		break;



    case "v4":
        $ACTION = 'v4index';
        if($_PARAMS[1] == 'zonesListAjax')
            $ACTION = 'v4zonesListAjax';
        if($_PARAMS[1] == 'submit')
            $ACTION = 'v4formSubmit';
        if($_PARAMS[1] == 'Zones.deleteStrikeAjax')
            $ACTION = 'v4deleteStrikeAjax';

        break;



    case "v5":
        $ACTION = 'v5index';
        if($_PARAMS[1] == 'zonesListAjax')
            $ACTION = 'v5zonesListAjax';
        if($_PARAMS[1] == 'submit')
            $ACTION = 'v5formSubmit';
        if($_PARAMS[1] == 'Zones.deleteStrikeAjax')
            $ACTION = 'v5deleteStrikeAjax';
        if($_PARAMS[1] == 'Zones.deleteBunchAjax')
            $ACTION = 'v5deleteBunchAjax';

        break;



    case "v6":
        $ACTION = 'v6index';
        if($_PARAMS[1] == 'zonesListAjax')
            $ACTION = 'v6zonesListAjax';
        if($_PARAMS[1] == 'submit')
            $ACTION = 'v6formSubmit';
        if($_PARAMS[1] == 'Zones.deleteStrikeAjax')
            $ACTION = 'v6deleteStrikeAjax';
        if($_PARAMS[1] == 'Zones.deleteBunchAjax')
            $ACTION = 'v6deleteBunchAjax';
        if($_PARAMS[1] == 'Zones.savePremAjax')
            $ACTION = 'v6savePremAjax';

        if($_PARAMS[1]=='maxPainIndex')
            $ACTION = 'v6maxPainIndex';
        if($_PARAMS[1]=='maxPainListAjax')
            $ACTION = 'v6maxPainListAjax';
        if($_PARAMS[1]=='maxPainFormSubmit')
            $ACTION = 'v6maxPainFormSubmit';
        if($_PARAMS[1]=='maxPainDeleteBunchAjax')
            $ACTION = 'v6maxPainDeleteBunchAjax';

        //else $ACTION='qweqweqwe';

        break;


}




class optionalAnalysisController extends MainController{
	
	
    function v2index()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['TITLE'] = Slonne::getTitle('Опционный анализ');

        $list = OptionalAnalysisItem2::getList(['dt'=>date('Y-m-d'), 'orderBy'=>'dt DESC', ]);
        $MODEL['list2'] = OptionalAnalysisItem2::arrangeList2($list);
        //vd($MODEL['list2']);

        $MODEL['currencies'] = [
            Currency::code(Currency::CODE_EUR),
            Currency::code(Currency::CODE_GBP),
            Currency::code(Currency::CODE_AUD),
        ];

        Slonne::view('optionalAnalysis/index.php', $MODEL);
    }


    public function v2formSubmit()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['NO_LAYOUT'] = true;

        $error = '';

        $cur = Currency::code($_REQUEST['currency']);
        $date = $_REQUEST['date'][$cur->code];
        $forward = $_REQUEST['forward'][$cur->code];


        $strikes = $_REQUEST['strike'][$cur->code];
        $prems = $_REQUEST['premium'][$cur->code];

        if(!$date && !$error)
            $error = 'Не указана дата!';
        if(!$forward && !$error)
            $error = 'Не указан форвард!';

        $objs = [];


        if(!$error)
        {
            foreach(StrikeType2::$items as $st)
            {
                foreach(Type::$items as $t)
                {
                    $strike = $strikes[$st->code][$t->code];
                    $prem = $prems[$st->code][$t->code];

                    if($strike && $prem)
                    {
                        $item = new OptionalAnalysisItem2();
                        $item->strike = $strike;
                        $item->currency = $cur;
                        $item->dt = $date;
                        $item->forward = $forward;
                        $item->premium = $prem;
                        $item->strikeType = $st;
                        $item->type = $t;

                        $item->calculate();

                        $objs[] = $item;
                    }
                }
            }
        }

        //vd($objs);
        if(!count($objs))
            $error = 'Не введены никакие данные.';


        if(!$error)
        {
            foreach($objs as $item)
            {
                $item->deletePreviousData();
                $item->insert();
            }
            echo '<script>window.top.Opt.drawStats(); </script>';
        } 
        else
            echo '<script>window.top.alert("'.$error.'")</script>';
    }



    public function v2statsAjax()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['NO_LAYOUT'] = true;

        $list = OptionalAnalysisItem2::getList([/*'dt'=>date('Y-m-d'),*/ 'orderBy'=>'dt desc, currency,  `strikeType` desc', ]);
        //vd($list);
        $MODEL['list'] = OptionalAnalysisItem2::arrangeList($list);
        $MODEL['list2'] = OptionalAnalysisItem2::arrangeList2($list);
        $MODEL['list3'] = OptionalAnalysisItem2::arrangeListByDate($list);

        //vd($_REQUEST);

        Slonne::view('optionalAnalysis/statsAjax.php', $MODEL);
    }



    public function v2deleteAjax()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['NO_LAYOUT'] = true;

        OptionalAnalysisItem2::deleteByDateAndCurrencyAndType($_REQUEST['date'], $_REQUEST['currency'], $_REQUEST['type']);
    }



    ########################
    ###   v3   #############
    ########################
    function v3index()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['TITLE'] = Slonne::getTitle('Опционный анализ v3');

        $today = date('Y-m-d');
        $date = $_REQUEST['date'] ? $_REQUEST['date'] : $today;

        $prevDate = date('Y-m-d', strtotime($date . ' - 1 day'));
        $nextDate = $date != $today ? date('Y-m-d', strtotime($date . ' + 1 day')) : null;

        $MODEL['date'] = $date;
        $MODEL['today'] = $today;
        $MODEL['datePrev'] = $prevDate;
        $MODEL['dateNext'] = $nextDate;

        $list = OAItem::getList(['dt'=>$date, 'orderBy'=>'dt DESC', ]);
        //vd($list);
        $MODEL['list2'] = OAItem::arrangeList2($list);
        //vd($MODEL['list2']);

        $MODEL['currencies'] = [
            Currency::code(Currency::CODE_EUR),
            Currency::code(Currency::CODE_GBP),
            Currency::code(Currency::CODE_AUD),
        ];

		$MODEL['currency'] = Currency::code($_REQUEST['currency']) ? Currency::code($_REQUEST['currency']) : Currency::code(Currency::CODE_EUR);

        #   даты ОТ и ДО для графика
        $MODEL['graphicDateFrom'] = date('Y-m-d', strtotime($today . ' - 7 day'));
        $MODEL['graphicDateTo'] = $today;
        $MODEL['graphicChosenCurrency'] = /*Currency::code(Currency::CODE_EUR)*/ $MODEL['currency'];

        Slonne::view('optionalAnalysis/v3/index.php', $MODEL);
    }



    public function v3formSubmit()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['NO_LAYOUT'] = true;

        vd($_REQUEST);
        //return;
        $error = '';

        $cur = Currency::code($_REQUEST['currency']);
        $date = $_REQUEST['date'][$cur->code];
        $data = $_REQUEST['data'][$cur->code];
        $forward = $_REQUEST['forward'][$cur->code];
        $bunchTitle = trim($_REQUEST['bunchTitle'][$cur->code]);


        $strikes = $_REQUEST['strike'][$cur->code];
        $prems = $_REQUEST['premium'][$cur->code];

        if(!$date && !$error)
            $error = 'Не указана дата!';
        if(!$forward && !$error && $forward!=='0')
            $error = 'Не указан форвард!';

        $objs = [];

        if(!$error)
        {
            $bunch = new StrikeBunch();
            $bunch->title = $bunchTitle;
            $bunch->status = Status2::code(Status2::NEUTRAL);
            $bunch->dt = $date;
            $bunch->data = $data;
            $bunch->currency = $cur;
            $bunch->insert();
            //vd($bunch);

            foreach(StrikeTypeV3::$items as $st)
            {

                foreach(Type::$items as $t)
                {
                    $strike = $strikes[$st->code][$t->code];
                    $prem = $prems[$st->code][$t->code];

                    if($strike && $prem)
                    {
                        $item = new OAItem();
                        $item->bunchId = $bunch->id;
                        $item->strike = $strike;
                        $item->currency = $cur;
                        $item->dt = $date;
                        $item->forward = $forward;
                        $item->premium = $prem;
                        $item->strikeType = $st;
                        $item->type = $t;
                        $item->isHistory = 0;

                        $item->calculate();

                        $objs[] = $item;
                    }
                }
            }
        }

        //vd($objs);
        if(!$error && !count($objs))
            $error = 'Не введены никакие данные.';


        if(!$error)
        {
            foreach($objs as $item)
            {
                //$item->deletePreviousData();
                $item->insert();
            }
            echo '<script>window.top.Opt.drawStats(); </script>';
        }
        else
            echo '<script>window.top.alert("'.$error.'")</script>';
    }



    public function v3statsAjax()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['NO_LAYOUT'] = true;

        $list = OAItem::getList([ 'orderBy'=>'dt desc, currency,  `strikeType` desc, `type` asc', ]);
        //vd($list);
        /*$MODEL['list'] = OAItem::arrangeList($list);
        $MODEL['list2'] = OAItem::arrangeList2($list);*/
        $MODEL['list3'] = OAItem::arrangeListByDate($list);

        //vd($_REQUEST);

        Slonne::view('optionalAnalysis/v3/statsAjax.php', $MODEL);
    }



    public function v3graphicAjax()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['NO_LAYOUT'] = true;

        $dateFrom = $_REQUEST['dateFrom'];
        $dateTo = $_REQUEST['dateTo'];
        $currency = Currency::code($_REQUEST['currency']) ? Currency::code($_REQUEST['currency']) : Currency::code(Currency::CODE_AUD);

        $list = OAItem::getList([
                //'dt'=>date('Y-m-d'),
                'dateFrom'=>$dateFrom,
                'dateTo'=>$dateTo,
                'currency'=>$currency,
                'orderBy'=>'dt desc, currency,  `strikeType` desc',
            ]);

        foreach($list as $val)
            $res[substr($val->dt, 0, 10)][$val->currency->code][$val->strikeType->code][$val->type->code] = $val;

        $MODEL['list'] = $list;
        $MODEL['listAssembled'] = $res;
        $MODEL['dateFrom'] = $dateFrom;
        $MODEL['dateTo'] = $dateTo;
        $MODEL['currency'] = $currency;


        Slonne::view('optionalAnalysis/v3/graphicPartial.php', $MODEL);
    }



    public function v3switchDoneAjax()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['NO_LAYOUT'] = true;

        $res = [];
        $error = null;


        //vd($_REQUEST);
        if ($item = OAItem::get($_REQUEST['id']) )
        {
           // vd($item);
            $doneToBe = $item->done ? 0 : 1;
            $item->done = $doneToBe;
            //vd($item);
            $item->update();
        }
        else
            $error = 'Ошибка! Запись не найдена! ['.$_REQUEST['id'].']';


        $res['error'] = $error;
        $res['doneToBe'] = $doneToBe;

        echo json_encode($res);
    }



    public function v3graphic2Ajax()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['NO_LAYOUT'] = true;
        //vd($_REQUEST);

        $dateFrom = $_REQUEST['dateFrom'];
        $dateTo = $_REQUEST['dateTo'];
        $currency = Currency::code($_REQUEST['currency']) ? Currency::code($_REQUEST['currency']) : Currency::code(Currency::CODE_EUR);

        $bunchesList = StrikeBunch::getList([
            //'dt'=>date('Y-m-d'),
            'dateFrom'=>$dateFrom,
            'dateTo'=>$dateTo,
            //'currency'=>$currency,
            'orderBy'=>'dt desc, id asc',
        ]);

        foreach($bunchesList as $b)
            $b->initItems();

        //vd($bunchesList);

        $bunchesListArranged = [];
        foreach($bunchesList as $val)
            if($val->currency->code == $currency->code)
                $bunchesListArranged[substr($val->dt, 0, 10)][] = $val;


        $MODEL['dateFrom'] = $dateFrom;
        $MODEL['dateTo'] = $dateTo;
        $MODEL['currency'] = $currency;
        $MODEL['bunchesList'] = $bunchesList;
        $MODEL['bunchesListArranged'] = $bunchesListArranged;


        Slonne::view('optionalAnalysis/v3/graphic2Partial.php', $MODEL);
    }




    public function v3deleteBunch()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['NO_LAYOUT'] = true;

        $error = null;

        //vd($_REQUEST);
        if ($item = StrikeBunch::get($_REQUEST['id']) )
        {
            $item->delete();
        }
        else
            $error = 'Ошибка! Запись не найдена! ['.$_REQUEST['id'].']';


        $res['error'] = $error;

        echo json_encode($res);
    }




    public function v3setBunchStatus()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['NO_LAYOUT'] = true;

        $res = [];
        $error = null;

        //vd($_REQUEST);
        if ($item = StrikeBunch::get($_REQUEST['id']) )
        {
            // vd($item);
            $statusToBe = Status2::code($_REQUEST['status']);
            if($statusToBe)
            {
                $item->status = $statusToBe;

                #   если статус стал АКТИВ или ДАН - насильно тащим в график
				if(in_array($statusToBe->code, [Status2::ACTIVE, Status2::DONE, ]))
				    $item->showOnGraphic = 1;

				$item->update();
            }
            else
                $error = 'Ошибка! Непонятный статус ['.$_REQUEST['status'].']';
        }
        else
            $error = 'Ошибка! Запись не найдена! ['.$_REQUEST['id'].']';


        $res['error'] = $error;
        $res['status'] = $statusToBe;

        echo json_encode($res);
    }




    public function v3saveBunchTitle()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['NO_LAYOUT'] = true;

        $res = [];
        $error = null;

        if ($item = StrikeBunch::get($_REQUEST['id']) )
        {
            $item->title = trim($_REQUEST['title']);
            $item->update();
        }
        else
            $error = 'Ошибка! Запись не найдена! ['.$_REQUEST['id'].']';

        $res['error'] = $error;
        $res['title'] = $item->title;

        echo json_encode($res);
    }




	public function v3switchShowOnGraphicAjax()
	{
		global $_GLOBALS, $_CONFIG;
		$_GLOBALS['NO_LAYOUT'] = true;

		$res = [];
		$error = null;


		//vd($_REQUEST);
		if ($item = StrikeBunch::get($_REQUEST['id']) )
		{
			// vd($item);
			$valueToBe = $item->showOnGraphic ? 0 : 1;
			$item->showOnGraphic = $valueToBe;
			//vd($item);
			$item->update();
		}
		else
			$error = 'Ошибка! Запись не найдена! ['.$_REQUEST['id'].']';


		$res['error'] = $error;
		$res['valueToBe'] = $valueToBe;

		echo json_encode($res);
	}






    #######################################
    ####    v4.0    #######################
    #######################################

    function v4index()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['TITLE'] = Slonne::getTitle('Опционный анализ v4.0');

        $MODEL['currencies'] = [
            Currency::code(Currency::CODE_EUR),
            Currency::code(Currency::CODE_GBP),
            Currency::code(Currency::CODE_AUD),
            Currency::code(Currency::CODE_JPY),
            Currency::code(Currency::CODE_CAD),
            Currency::code(Currency::CODE_CHF),
        ];


        $today = date('Y-m-d');
        $date = $_REQUEST['date'] ? $_REQUEST['date'] : $today;

        $prevDate = date('Y-m-d', strtotime($date . ' - 1 day'));
        $nextDate = $date != $today ? date('Y-m-d', strtotime($date . ' + 1 day')) : null;

        $MODEL['date'] = $date;
        $MODEL['today'] = $today;
        $MODEL['datePrev'] = $prevDate;
        $MODEL['dateNext'] = $nextDate;

        $MODEL['currency'] = Currency::code($_REQUEST['currency']) ? Currency::code($_REQUEST['currency']) : Currency::code(Currency::CODE_EUR);

        $MODEL['list'] = V4Strike::getList([
            'date' => $date,
            'currency'=>$MODEL['currency'],
            'isZone' => 1,
        ]);

        foreach ($MODEL['list'] as $item)
            $item->initStrikes();

        Slonne::view('optionalAnalysis/v4/index.php', $MODEL);
    }



    public function v4zonesListAjax()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['NO_LAYOUT'] = true;

        $today = date('Y-m-d');
        $date = $_REQUEST['date'] ? $_REQUEST['date'] : $today;
        $MODEL['date'] = $date;
        $MODEL['currency'] = Currency::code($_REQUEST['currency']) ? Currency::code($_REQUEST['currency']) : Currency::code(Currency::CODE_EUR);

        $MODEL['list'] = V4Strike::getList([
            'date' => $date,
            'currency'=>$MODEL['currency'],
            'isZone' => 1,
            'orderBy' => 'id desc',
        ]);

        foreach ($MODEL['list'] as $item)
            $item->initStrikes();

        Slonne::view('optionalAnalysis/v4/zonesListAjax.php', $MODEL);
    }




    public function v4formSubmit()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['NO_LAYOUT'] = true;

        vd($_REQUEST);

        $error = '';

        $cur = Currency::code($_REQUEST['currency']);
        $date = $_REQUEST['date'];
        $zoneData = trim($_REQUEST['zoneData']);
        $data = trim($_REQUEST['data']);
        $forward = $_REQUEST['forward'];
        $comment = trim($_REQUEST['comment']);

        if(!$date && !$error)
            $error = 'Не указана дата!';
        if(!$forward && !$error && $forward!=='0')
            $error = 'Не указан форвард!';
        if(!$error && !$zoneData)
            $error = 'Не введены данные зоны';
        if(!$error && !$data)
            $error = 'Не введены данные';



        if(!$error )
        {
            #   зона
            $cols = explode("\t", $zoneData);

            $valToDivideTo = 10000;     //  на какое значение делим страйк
            if($cur->code == Currency::CODE_JPY)
                $valToDivideTo = 1000000;

            $s = new V4Strike();
            $s->dt = $date;
            $s->pid = 0;
            $s->currency = $cur;
            $s->strike = $cols[1]/$valToDivideTo;
            $s->premiumBuy = $cols[0];
            $s->premiumSell = $cols[2];
            $s->forward = $forward;
            $s->status = Status2::code(Status2::ACTIVE);
            $s->comment = $comment;
            $s->isZone = 1;
            $s->data = json_encode($_REQUEST);

            $s->calculate();
            $s->insert();
            $zone = $s;

            #   страйки
            $rows = explode("\r\n", $data);
            //vd($rows);
            foreach ($rows as $row)
            {
                $cols = explode("\t", $row);

                $s = new V4Strike();
                $s->pid = $zone->id;
                $s->dt = $date;
                $s->currency = $cur;
                $s->strike = $cols[1]/$valToDivideTo;
                $s->premiumBuy = $cols[0];
                $s->premiumSell = $cols[2];
                $s->forward = $forward;
                $s->status = Status2::code(Status2::ACTIVE);
                $s->comment = $comment;
                $s->isZone = 0;

                $s->calculate();
                $s->insert();
            }
            echo '<hr>';
        }



        if(!$error)
        {
            echo '<script>window.top.Zones.list()</script>';
        }
        else
            echo '<script>window.top.alert("'.$error.'")</script>';
    }






    public function v4deleteStrikeAjax()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['NO_LAYOUT'] = true;

        $error = null;

        //vd($_REQUEST);
        if ($item = V4Strike::get($_REQUEST['id']) )
        {
            $item->delete();
        }
        else
            $error = 'Ошибка! Запись не найдена! ['.$_REQUEST['id'].']';


        $res['error'] = $error;

        echo json_encode($res);
    }








    #######################################
    ####    v5.0    #######################
    #######################################

    function v5index()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['TITLE'] = Slonne::getTitle('Опционный анализ v5.0');

        $MODEL['currencies'] = [
            Currency::code(Currency::CODE_EUR),
            Currency::code(Currency::CODE_GBP),
            Currency::code(Currency::CODE_AUD),
            Currency::code(Currency::CODE_JPY),
            Currency::code(Currency::CODE_CAD),
            Currency::code(Currency::CODE_CHF),
        ];

        $today = date('Y-m-d');
        $date = $_REQUEST['date'] ? $_REQUEST['date'] : $today;

        $prevDate = date('Y-m-d', strtotime($date . ' - 1 day'));
        $nextDate = $date != $today ? date('Y-m-d', strtotime($date . ' + 1 day')) : null;

        $MODEL['date'] = $date;
        $MODEL['today'] = $today;
        $MODEL['datePrev'] = $prevDate;
        $MODEL['dateNext'] = $nextDate;

        $MODEL['currency'] = Currency::code($_REQUEST['currency']) ? Currency::code($_REQUEST['currency']) : Currency::code(Currency::CODE_EUR);

        Slonne::view('optionalAnalysis/v5/index.php', $MODEL);
    }



    public function v5zonesListAjax()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['NO_LAYOUT'] = true;

        $today = date('Y-m-d');
        $date = $_REQUEST['date'] ? $_REQUEST['date'] : $today;
        $MODEL['date'] = $date;
        $MODEL['currency'] = Currency::code($_REQUEST['currency']) ? Currency::code($_REQUEST['currency']) : Currency::code(Currency::CODE_EUR);

        $MODEL['list'] = V5Bunch::getList([
            'date' => $date,
            'currency'=>$MODEL['currency'],
            'orderBy' => 'id desc',
        ]);

        foreach ($MODEL['list'] as $item)
        {
            $item->initItems();
            $item->initAdvisor();
        }

        Slonne::view('optionalAnalysis/v5/zonesListAjax.php', $MODEL);
    }




    public function v5formSubmit()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['NO_LAYOUT'] = true;

        $errors = null;

        $cur =Currency::code($_REQUEST['currency']);
//       vd($_REQUEST);
//        return;

        $bunch = new V5Bunch();
        $bunch->getData($_REQUEST);
        $bunch->status = Status2::code(Status2::ACTIVE);

        $errors = $bunch->validate();

        if(!$errors)
        {
            $bunch->insert();

            $valToDivideTo = 10000;     //  на какое значение делим страйк
            if($cur->code == Currency::CODE_JPY)
                $valToDivideTo = 1000000;

            $rows = explode("\r\n", $_REQUEST['data']);

            #   для перевёртышей - ряды берём снизу вверх
            if($cur->isIndirect())
                $rows = array_reverse($rows);

            #   формируем страйки
            foreach ($rows as $rowNum=>$row)
            {
                $cols = explode("\t", $row);

                $s = new v5Strike();
                $s->pid = $bunch->id;
                $s->dt = $bunch->dt;
                $s->currency = $bunch->currency;
                $s->strike = $cols[1]/$valToDivideTo;
                $s->premiumBuy = $cols[0];
                $s->premiumSell = $cols[2];
                $s->forward = $bunch->forward;
                $s->openingPrice = $bunch->openingPrice;
                $s->status = Status2::code(Status2::ACTIVE);
                $s->data = $row;
                $s->comment = $bunch->title;

                #   в старой версии участвуют только 3 цвета
                $colorsArr = [
                    0=>Color::code(Color::RED),
                    1=>Color::code(Color::BLACK),
                    2=>Color::code(Color::GREEN),
                ];
                $s->color = $colorsArr[$rowNum] ? $colorsArr[$rowNum] : Color::none();

                $s->calculate();
                $s->insert();
            }
        }


        if(!$errors)
            echo '<script>window.top.Zones.list()</script>';
        else
            echo '<script>window.top.alert("'.$errors[0]->msg.'")</script>';
    }



    public function v5deleteBunchAjax()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['NO_LAYOUT'] = true;

        $error = null;

        if ($item = V5Bunch::get($_REQUEST['id']) )
            $item->delete();
        else
            $error = 'Ошибка! Пучок не найден! ['.$_REQUEST['id'].']';


        $res['error'] = $error;

        echo json_encode($res);
    }









    #######################################
    ####    v6.0    #######################
    #######################################
    function v6index()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['TITLE'] = Slonne::getTitle('Опционный анализ v5.0');

        $MODEL['currencies'] = [
            Currency::code(Currency::CODE_EUR),
            Currency::code(Currency::CODE_GBP),
            Currency::code(Currency::CODE_AUD),
            Currency::code(Currency::CODE_JPY),
            Currency::code(Currency::CODE_CAD),
            Currency::code(Currency::CODE_CHF),
        ];

        $today = date('Y-m-d');
        $date = $_REQUEST['date'] ? $_REQUEST['date'] : $today;

        $prevDate = date('Y-m-d', strtotime($date . ' - 1 day'));
        $nextDate = $date != $today ? date('Y-m-d', strtotime($date . ' + 1 day')) : null;

        $MODEL['date'] = $date;
        $MODEL['today'] = $today;
        $MODEL['datePrev'] = $prevDate;
        $MODEL['dateNext'] = $nextDate;

        $MODEL['currency'] = Currency::code($_REQUEST['currency']) ? Currency::code($_REQUEST['currency']) : Currency::code(Currency::CODE_EUR);

        Slonne::view('optionalAnalysis/v6/index.php', $MODEL);
    }



    public function v6zonesListAjax()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['NO_LAYOUT'] = true;

        $today = date('Y-m-d');
        $date = $_REQUEST['date'] ? $_REQUEST['date'] : $today;
        $MODEL['date'] = $date;
        $MODEL['currency'] = Currency::code($_REQUEST['currency']) ? Currency::code($_REQUEST['currency']) : Currency::code(Currency::CODE_EUR);

        $MODEL['list'] = V6Bunch::getList([
            'date' => $date,
            'currency'=>$MODEL['currency'],
            'orderBy' => 'id desc',
        ]);

        foreach ($MODEL['list'] as $item)
        {
            $item->initItems();
            $item->initAdvisor();
            $item->initMinDeltasAgainstMax();
        }

        Slonne::view('optionalAnalysis/v6/zonesListAjax.php', $MODEL);
    }




    public function v6formSubmit()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['NO_LAYOUT'] = true;

        $errors = null;

        $cur =Currency::code($_REQUEST['currency']);
//       vd($_REQUEST);
//        return;

        $bunch = new V6Bunch();
        $bunch->getData($_REQUEST);
        $bunch->status = Status2::code(Status2::ACTIVE);

        $errors = $bunch->validate();

        if(!$errors)
        {
            $bunch->insert();

            $valToDivideTo = 10000;     //  на какое значение делим страйк
            if($cur->code == Currency::CODE_JPY)
                $valToDivideTo = 1000000;


            $rows = explode("\r\n", $_REQUEST['data']);

            #   для перевёртышей - ряды берём снизу вверх
            if($cur->isIndirect())
                $rows = array_reverse($rows);

            #   формируем страйки
            foreach ($rows as $rowNum=>$row)
            {
                $cols = explode("\t", $row);

                $s = new V6Strike();
                $s->pid = $bunch->id;
                $s->dt = $bunch->dt;
                $s->currency = $bunch->currency;
                $s->strike = $cols[1]/$valToDivideTo;
                $s->premiumBuy = $cols[0];
                $s->premiumSell = $cols[2];
                $s->forward = $bunch->forward;
                $s->openingPrice = $bunch->openingPrice;
                $s->status = Status2::code(Status2::ACTIVE);
                $s->data = $row;
                $s->comment = $bunch->title;

                #   в старой версии участвуют только 3 цвета
                $colorsArr = [
                    0=>Color::code(Color::LIGHT_RED),
                    1=>Color::code(Color::RED),
                    2=>Color::code(Color::BLACK),
                    3=>Color::code(Color::GREEN),
                    4=>Color::code(Color::LIGHT_GREEN),
                ];
                $s->color = $colorsArr[$rowNum] ? $colorsArr[$rowNum] : Color::none();

                $s->calculate();
                $s->insert();
            }
        }


        if(!$errors)
            echo '<script>window.top.Zones.list()</script>';
        else
            echo '<script>window.top.alert("'.$errors[0]->msg.'")</script>';
    }



    public function v6deleteBunchAjax()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['NO_LAYOUT'] = true;

        $error = null;

        if ($item = V6Bunch::get($_REQUEST['id']) )
            $item->delete();
        else
            $error = 'Ошибка! Пучок не найден! ['.$_REQUEST['id'].']';


        $res['error'] = $error;

        echo json_encode($res);
    }



    public function v6savePremAjax()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['NO_LAYOUT'] = true;

        $errors = null;

        $strike = V6Strike::get($_REQUEST['strikeId']);
        $premType = $_REQUEST['premType'] == 'buy' || $_REQUEST['premType'] == 'sell' ? $_REQUEST['premType']  : null;
        $val = floatval($_REQUEST['val']);

        if(!$strike)
            $errors[] = new Problem('Страйк не найден! ['.$_REQUEST['strikeId'].']');
        if(!$premType)
            $errors[] = new Problem('Непонятный тип премии! ['.$_REQUEST['premType'].']');
        if(!$val)
            $errors[] = new Problem('Левое значение! ['.$_REQUEST['val'].']');


        if(!$errors)
        {
            if($premType == 'buy')
                $strike->premiumBuy = $val;
            if($premType == 'sell')
                $strike->premiumSell = $val;

            #   подправляем исходную строку с данными
            $strike->data = $strike->assembleDataString();
            $strike->update();

            #   то же самое для БАНЧА
            $bunch = V6Bunch::get($strike->pid);
            $bunch->initItems();
            $bunch->data = $bunch->assembleDataString();
            $bunch->update();
        }

        $res['errors'] = $errors;

        echo json_encode($res);

    }


    //////////////////////////////////////////////
    function v6maxPainIndex()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['TITLE'] = Slonne::getTitle('MaxPain 6.0');

        $MODEL['currencies'] = [
            Currency::code(Currency::CODE_EUR),
            Currency::code(Currency::CODE_GBP),
            Currency::code(Currency::CODE_AUD),
            Currency::code(Currency::CODE_JPY),
            Currency::code(Currency::CODE_CAD),
            Currency::code(Currency::CODE_CHF),
        ];

        $today = date('Y-m-d');
        $date = $_REQUEST['date'] ? $_REQUEST['date'] : $today;

        $prevDate = date('Y-m-d', strtotime($date . ' - 1 day'));
        $nextDate = $date != $today ? date('Y-m-d', strtotime($date . ' + 1 day')) : null;

        $MODEL['date'] = $date;
        $MODEL['today'] = $today;
        $MODEL['datePrev'] = $prevDate;
        $MODEL['dateNext'] = $nextDate;

        $MODEL['currency'] = Currency::code($_REQUEST['currency']) ? Currency::code($_REQUEST['currency']) : Currency::code(Currency::CODE_EUR);

        Slonne::view('optionalAnalysis/v6/maxPainIndex.php', $MODEL);
    }





    public function v6maxPainListAjax()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['NO_LAYOUT'] = true;

        $today = date('Y-m-d');
        $date = $_REQUEST['date'] ? $_REQUEST['date'] : $today;
        $MODEL['date'] = $date;
        $MODEL['currency'] = Currency::code($_REQUEST['currency']) ? Currency::code($_REQUEST['currency']) : Currency::code(Currency::CODE_EUR);

        $MODEL['list'] = V6MaxPainBunch::getList([
            'date' => $date,
            'currency'=>$MODEL['currency'],
            'orderBy' => 'id desc',
        ]);

        foreach ($MODEL['list'] as $item)
        {
            $item->initItems();
            $item->calculate();
        }

        Slonne::view('optionalAnalysis/v6/maxPainListAjax.php', $MODEL);
    }



    public function v6maxPainFormSubmit()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['NO_LAYOUT'] = true;

        $errors = null;

        $cur =Currency::code($_REQUEST['currency']);
//        vd($_REQUEST);
//        return;

        $bunch = new V6MaxPainBunch();
        $bunch->getData($_REQUEST);
        $bunch->status = Status2::code(Status2::ACTIVE);

        $errors = $bunch->validate();

        if(!$errors)
        {
            $bunch->insert();

            $_REQUEST['data'] = str_replace(',', '.', $_REQUEST['data']);

            $rows = explode("\r\n", $_REQUEST['data']);

            #   формируем страйки
            foreach ($rows as $rowNum=>$row)
            {
                if(trim($row))
                {
                    $cols = explode(" ", $row);

                    $s = new V6MaxPainStrike();
                    $s->pid = $bunch->id;
                    $s->dt = $bunch->dt;
                    $s->currency = $bunch->currency;
                    $s->strike = (float)$cols[0];
                    $s->oiCall = $cols[1];
                    $s->oiPut = $cols[2];
                    $s->data = $row;

                    $s->insert();
                }
            }
        }


        if(!$errors)
            echo '<script>window.top.MaxPain.list()</script>';
        else
            echo '<script>window.top.alert("'.$errors[0]->msg.'")</script>';
    }




    public function v6maxPainDeleteBunchAjax()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['NO_LAYOUT'] = true;

        $error = null;

        if ($item = V6MaxPainBunch::get($_REQUEST['id']) )
            $item->delete();
        else
            $error = 'Ошибка! Пучок не найден! ['.$_REQUEST['id'].']';


        $res['error'] = $error;

        echo json_encode($res);
    }


}


?>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     