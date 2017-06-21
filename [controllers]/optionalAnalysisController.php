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

        $list = OptionalAnalysisItem2::getList([/*'dt'=>date('Y-m-d'),*/ 'orderBy'=>'currency,  `strikeType` desc', ]);
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





}


?>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     