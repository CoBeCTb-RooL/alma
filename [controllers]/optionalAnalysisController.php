<?php 

//vd(Currency::$items);


switch($_PARAMS[0])
{


    case "v2":
        $ACTION = 'v2index';
        if($_PARAMS[0] == 'submit')
            $ACTION = 'v2formSubmit';
        if($_PARAMS[0] == 'statsAjax')
            $ACTION = 'v2statsAjax';
        if($_PARAMS[0] == 'deleteAjax')
            $ACTION = 'v2deleteAjax';
    break;


}
	




class optionalAnalysisController extends MainController{
	
	
	function index()
	{
		global $_GLOBALS;
		
		Slonne::view('stock/index.php', $MODEL);
	}
	




    function v2index()
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

        Slonne::view('optionalAnalysis/index.php', $MODEL);
    }


    public function v2formSubmit()
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



    public function v2statsAjax()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['NO_LAYOUT'] = true;

        $list = OptionalAnalysisItem::getList([/*'dt'=>date('Y-m-d'),*/ 'orderBy'=>'currency,  `strikeType` desc', ]);
        //vd($list);
        $MODEL['list'] = OptionalAnalysisItem::arrangeList($list);
        $MODEL['list2'] = OptionalAnalysisItem::arrangeList2($list);
        $MODEL['list3'] = OptionalAnalysisItem::arrangeListByDate($list);

        //vd($_REQUEST);

        Slonne::view('optionalAnalysis/statsAjax.php', $MODEL);
    }



    public function v2deleteAjax()
    {
        global $_GLOBALS, $_CONFIG;
        $_GLOBALS['NO_LAYOUT'] = true;

        OptionalAnalysisItem::deleteByDateAndCurrencyAndType($_REQUEST['date'], $_REQUEST['currency'], $_REQUEST['type']);
    }





}


?>