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

        #   даты ОТ и ДО для графика
        $MODEL['graphicDateFrom'] = date('Y-m-d', strtotime($today . ' - 7 day'));
        $MODEL['graphicDateTo'] = $today;
        $MODEL['graphicChosenCurrency'] = Currency::code(Currency::CODE_AUD);

        Slonne::view('optionalAnalysis/v3/index.php', $MODEL);
    }



    public function v3formSubmit()
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
            foreach(StrikeTypeV3::$items as $st)
            {
                foreach(Type::$items as $t)
                {
                    $strike = $strikes[$st->code][$t->code];
                    $prem = $prems[$st->code][$t->code];

                    if($strike && $prem)
                    {
                        $item = new OAItem();
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



    public function v3statsAjax()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['NO_LAYOUT'] = true;

        $list = OAItem::getList([/*'dt'=>date('Y-m-d'),*/ 'orderBy'=>'dt desc, currency,  `strikeType` desc', ]);
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


}


?>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     