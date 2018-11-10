<?php

/*  for alma v3 */

class V5Advisor{

    public $aspects;
    public $result;





    function __construct($bunch, $currency = null)
    {
        $aspects = [];
        $currency = $currency ? $currency : $bunch->currency;


        #   EUR
        if($currency->code == Currency::CODE_EUR)
        {
            $advise = null;
            $aspects[0] = new V5AdvisorAspect('открытие ниже MAX?');
            if($bunch->openingPrice < $bunch->black()->max() )
            {
                $aspects[0]->result = true;

                $aspects[1] = new V5AdvisorAspect('открытие ниже НИЖНЕГО ЗЕЛЁНОГО?');
                if($bunch->openingPrice < $bunch->green()->lower())
                {
                    $aspects[1]->result = true;

                    $aspects[2] = new V5AdvisorAspect('открытие ниже ЧЁРНОГО?');
                    if($bunch->openingPrice < $bunch->black()->strike)
                    {
                        $aspects[2]->result = true;
                        $advise = 'BUY ниже чёрного';
                    }
                    else
                    {
                        $aspects[2]->result = false;
                        $advise = 'BUY от "где есть"';
                    }
                }
                else
                {
                    $aspects[1]->result = false;

                    $aspects[3] = new V5AdvisorAspect('верхний КРАСНЫЙ ниже MAX?');
                    if($bunch->red()->upper() < $bunch->black()->max())
                    {
                        $aspects[3]->result = true;

                        $advise = 'SELL от MAX (возможно с заходом за MAX)
                                    затем
                                    BUY от SELL-MAXa (с заходом)';
                    }
                    else
                    {
                        $aspects[3]->result = false;

                        $advise = ' (undone...)
 
                        Если касание к ЧЁРНОМУ НИЖНЕМУ уровню - то BUY-вход от нижнего ЧЁРНОГО (пипка в пипку)
                        или
                        нижний СИНИЙ (пипка-в пипку)
                        а затем -
                        SELL от верхнего СИНЕГО <hr>если нет, то
                        если касание к нижнему ЗЕЛЁНОМУ - 
                        то SELL с заходом за верхний ЧЁРНЫЙ
                        или
                        SELL с недоходом до BUY-СИНЕГО
                        <hr>иначе - SELL от чёрного  ПИПКА В ПИПКУ';
                    }
                }
            }
            else
            {
                $aspects[0]->result = false;

                $aspects[4] = new V5AdvisorAspect('нижний ЗЕЛЁНЫЙ ниже MAX?');
                if($bunch->green()->lower() < $bunch->black()->max())
                {
                    $aspects[4]->result = true;

                    $advise = '(undone...)
                    Если цена была ниже НИЖНЕГО ЗЕЛЁНОГО - 
                    SELL от верхнего КРАСНОГО (с заходом)
                    или
                    от верхнего ЧЁРНОГО (с недоходом)
                    ВОЗМОЖНО -
                    разворот от красного MAX 
<hr>иначе - 
SELL от верхнего ЧЁРНОГО(с заходом )
или
от ЗЕЛЁНОГО MAX (пипка в пипку)';
                }
                else
                {
                    $aspects[4]->result = false;

                    $aspects[5] = new V5AdvisorAspect('открытие ниже нижнего ЗЕЛЁНОГО?');
                    if($bunch->openingPrice < $bunch->green()->lower())
                    {
                        $aspects[5]->result = true;

                        $advise = '(undone...)
                        если было касание верхнего КРАСНОГО, то
                        ПОКА ХЗ
                        <hr>иначе - 
                        BUY от MAXa (с заходом)
                        или
                        BUY от нижнего ЧЁРНОГО';

                    }
                    else
                    {
                        $aspects[5]->result = false;

                        $advise = 'BUY от MAXa (с заходом)
или
недоходя до нижнего ЧЁРНОГО
затем
SELL от верхнего КРАСНОГО (с заходом)
';
                    }
                }
            }

            $this->aspects = $aspects;
            $this->advise = $advise;
        }



//        switch($currency->code )
//        {
//            case Currency::CODE_EUR:
//                $this->processEur();
//                break;
//        }

    }


}