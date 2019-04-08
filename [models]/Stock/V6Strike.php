<?php

/*  for alma v5.0 */

//МАКСЫ
//[20:53, 10.11.2018] Ерма: Зелёный нижний
//[20:53, 10.11.2018] Ерма: Красный вверх
//[20:54, 10.11.2018] Ерма: И просто Макс черный




class V6Strike{
	public $id;
	public $pid;
	public $dt;
	public $currency;
	public $color;
	public $strike;
	public $premiumBuy;
	public $premiumSell;
	public $forward;
	public $openingPrice;
	public $resultBuy;
	public $resultSell;
	public $status;
	public $comment;
	public $data;
	public $createdAt;
	public $updatedAt;


	const TBL = 'v6__strikes';

	function __construct($arr)
	{
		if(count($arr))
		{
			$this->id = $arr['id'];
			$this->pid = $arr['pid'];
			$this->dt = $arr['dt'];
            $this->currency = Currency::code($arr['currency']);
            $this->color = Color::code($arr['color']);

			$this->strike = strikeVal($arr['strike']);
			$this->premiumBuy = strikeVal($arr['premiumBuy']);
			$this->premiumSell = strikeVal($arr['premiumSell']);
			if($this->currency->code == Currency::CODE_JPY)
			{
				$this->strike = strikeVal($arr['strike'], 6);
				$this->premiumBuy = strikeVal($arr['premiumBuy'], 7);
				$this->premiumSell = strikeVal($arr['premiumSell'], 7);
			}

			$this->forward = $arr['forward'];
            $this->openingPrice = $arr['openingPrice'];
			$this->status = Status2::code($arr['status']);
			$this->comment = $arr['comment'];
			$this->data = $arr['data'];
			$this->createdAt = $arr['createdAt'];
			$this->updatedAt = $arr['updatedAt'];

			$this->calculate();
		}
	}


    public function get($id)
    {
        $sql = "SELECT * FROM `".self::TBL."` WHERE id=".intval($id)." ";

        //vd($sql);
        $qr = DB::query($sql);
        echo mysql_error();
        $next = mysql_fetch_array($qr, MYSQL_ASSOC);

        if($next)
        	return new self($next);
    }


	public function getList($params)
	{
		$sql = "SELECT * FROM `".self::TBL."` WHERE 1 ";

		if($params['currency'] )
			$sql.=" AND `currency`= '".strPrepare($params['currency']->code)."' ";
		if($params['pid'] )
			$sql.=" AND `pid`= '".intval($params['pid'])."' ";
		if($params['date'] )
			$sql.=" AND DATE(dt)= DATE('".strPrepare($params['date'])."') ";

        if($params['dateFrom'] )
            $sql.=" AND DATE(dt) >= DATE('".strPrepare($params['dateFrom'])."') ";
        if($params['dateTo'] )
            $sql.=" AND DATE(dt) <= DATE('".strPrepare($params['dateTo'])."') ";

		if($params['isZone'] )
			$sql.=" AND isZone= ".intval($params['isZone'])." ";

		if($params['orderBy'])
			$sql .= " ORDER BY ".strPrepare($params['orderBy'])." ";
		//vd($sql);
		$qr = DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$ret[] = new self($next);

		return $ret;
	}





	public function max()
    {
        $ret = $this->strike - $this->forward;

        if($this->currency->isIndirect())
            $ret = (1 / $this->strike) + $this->forward;

        return strikeVal($ret);
    }










	public function toArray()
	{
		$tmp = clone $this;
		unset($tmp->strikes);
		unset($tmp->closestBuy);
		unset($tmp->closestSell);

		return (array) $tmp;
	}



//    public function delete()
//    {
//    	$list = self::getList(['pid'=>$this->id]);
//    	foreach ($list as $item)
//		{
//			$sql = "DELETE FROM `".self::TBL."` where id=".$item->id;
//			//vd($sql);
//			DB::query($sql);
//			echo mysql_error();
//		}
//
//		# 	удаляем сам объект
//        $sql = "DELETE FROM `".self::TBL."` where id=".$this->id;
//        //vd($sql);
//        DB::query($sql);
//        echo mysql_error();
//    }




    public function delete()
    {
        $sql = "DELETE FROM `".self::TBL."` where id=".$this->id;
//        vd($sql);
//        return;
        DB::query($sql);
        echo mysql_error();
    }



    public function calculate()
    {
//		$this->resultBuy = $this->strike - $this->premiumSell;
//		$this->resultSell = $this->strike + $this->premiumBuy;

        if(in_array($this->currency->code, [Currency::CODE_AUD, Currency::CODE_EUR, Currency::CODE_GBP]))
        {
            $this->resultBuy = $this->strike - $this->premiumSell;
            $this->resultSell = $this->strike + $this->premiumBuy;

            $this->resultBuy -= $this->forward;
            $this->resultSell -= $this->forward;
        }
        elseif(in_array($this->currency->code, [Currency::CODE_CAD, Currency::CODE_JPY, Currency::CODE_CHF]))
        {
//			$this->resultBuy += $this->forward;
//			$this->resultSell += $this->forward;
//
//			# 	оборачиваем
//			$this->resultBuy = 1 / $this->resultBuy;
//			$this->resultSell = 1 / $this->resultSell;
//
//			# 	меняем местами
//			$a = $this->resultBuy;
//			$this->resultBuy = $this->resultSell;
//			$this->resultSell = $a;

            $this->resultSell = 1 / ($this->strike - $this->premiumSell)  + $this->forward;
            $this->resultBuy = 1 / ($this->strike + $this->premiumBuy)  + $this->forward;
        }


        $this->resultBuy = strikeVal($this->resultBuy);
        $this->resultSell = strikeVal($this->resultSell);
    }





    function insert()
	{
		if($this->strike)
		{
			$sql = "
			INSERT INTO `".self::TBL."` 
			SET `createdAt` = NOW(),  
			 ".$this->innerAlterSql()."";
			//vd($sql);
			DB::query($sql);
			echo mysql_error();
			$this->id = mysql_insert_id();
		}
	}




    function update()
    {
        $sql = "
		UPDATE `".self::TBL."` 
		SET   
		".$this->innerAlterSql()."
		WHERE id=".intval($this->id)." ";
        //vd($sql);
        DB::query($sql);
        echo mysql_error();
    }




    function innerAlterSql()
    {
        $str="
		  dt = '".strPrepare($this->dt)."'
				, currency = '".strPrepare($this->currency->code)."'
				, pid = '".intval($this->pid)."'
				, strike = '".floatval($this->strike)."'
				, premiumBuy = '".floatval($this->premiumBuy)."'
				, premiumSell = '".floatval($this->premiumSell)."'
				, `forward` = '".floatval($this->forward)."'
				, `openingPrice` = '".floatval($this->openingPrice)."'
				, `status` = '".strPrepare($this->status->code)."'
				, `comment` = '".strPrepare($this->comment)."'
				, `data` = '".strPrepare($this->data)."'
				, `updatedAt` = '".strPrepare($this->updatedAt)."'
				, `color` = '".strPrepare($this->color->code)."'
				";

        return $str;
    }





    public function lower()
    {
        return $this->resultBuy;
    }
    public function upper()
    {
        return $this->resultSell;
    }





    public function potentialGoal()
    {
        $ret = null;

        $val = 0;
        if(in_array($this->color->code, [Color::LIGHT_RED, Color::RED]))
            $val = $this->resultSell;
        if(in_array($this->color->code, [Color::LIGHT_GREEN, Color::GREEN]))
            $val = $this->resultBuy;

        #   считаем только для ЛАЙТ страйков. Если значение есть - тогда высчитываем
        if($val)
            $ret = strikeVal(abs($this->max() - $val));
        return $ret;
    }




    public function isOutOfRange($centralStrike)
    {
        $ret = false;

        if($this->color->code == Color::LIGHT_RED)
            if($this->resultSell > $centralStrike->max())
                $ret = true;
        if($this->color->code == Color::LIGHT_GREEN)
            if($this->resultBuy < $centralStrike->max())
                $ret = true;

        return $ret;
    }




    public function initMinDeltasAgainstMax($bunch, $type=null)
    {
        $ret = [];
        $minDelta = 99999;


        #   высчитываем минимальную дельту
        foreach ($bunch->strikes as $strike)
        {
            #   бай
            if(!$type || $type == Type::BUY)
            {
                $delta = abs($this->resultBuy - $strike->max());
                $minDelta = $delta < $minDelta ? $delta : $minDelta;
            }

            #   селл
            if(!$type || $type == Type::SELL)
            {
                $delta = abs($this->resultSell - $strike->max());
                $minDelta = $delta < $minDelta ? $delta : $minDelta;
            }
        }

        #   теперь, зная минДельту - заново высчитываем все дельты, и те, у кого результат равен ей - нас интересуют!
        foreach ($bunch->strikes as $strike)
        {
//            echo $delta.' == '.$minDelta.' ??'.($delta == $minDelta ? 'da' : 'net').'<br>';

            #   байи
            if(!$type || $type == Type::BUY)
            {
                $delta = abs($this->resultBuy - $strike->max());
                if($delta == $minDelta)
                {
                    $ret[] = [
                        'type'=>Type::BUY,
                        'resultVal'=>$this->resultBuy,
                        'delta' => strikeVal($delta),
                        'strike'=>$strike
                    ];
                }
            }

            #   селлы
            if(!$type || $type == Type::SELL)
            {
                $delta = abs($this->resultSell - $strike->max());
                if($delta == $minDelta)
                {
                    $ret[] = [
                        'type'=>Type::SELL,
                        'resultVal'=>$this->resultSell,
                        'delta' => strikeVal($delta),
                        'strike'=>$strike,
                    ];
                }
            }
        }

//        if($this->id == 654)
//        {
////            vd($bunch->strikes);
//            $ret[] = ['type'=>Type::SELL,
//                'resultVal'=>$this->resultSell,
//                'delta' => strikeVal($delta),
//                'strike'=>$bunch->strikes[0],];
//        }

        $this->minDeltasAgainstMax = $ret;
    }



    //  коэффициент, на который делим значение страйка
    public static function divisionCoefStatic($cur)
    {
        $ret = 10000;     //  на какое значение делим страйк
        if($cur->code == Currency::CODE_JPY)
            $ret = 1000000;

        return $ret;
    }

    public function divisionCoef()
    {
        return $this->divisionCoefStatic($this->currency);
    }



    public function assembleDataString()
    {
        return join("\t", [$this->premiumBuy, $this->strike*$this->divisionCoef(), $this->premiumSell, ]);
    }







//    public function getMinDeltasAgainstMaxByType($bunch, $type)
//    {
//        $ret = [];
//        $minDelta = 99999;
//
//        $valToCompare = 0;
//        if($type == Type::SELL)
//            $valToCompare = $this->resultSell;
//        if($type == Type::BUY)
//            $valToCompare = $this->resultBuy;
//
//        #   высчитываем минимальную дельту
//        foreach ($bunch->strikes as $strike)
//        {
//            $delta = abs($valToCompare - $strike->max());
//            $minDelta = $delta < $minDelta ? $delta : $minDelta;
//        }
//
//        #   теперь, зная минДельту - заново высчитываем все дельты, и те, у кого результат равен ей - нас интересуют!
//        foreach ($bunch->strikes as $strike)
//        {
//            $delta = abs($valToCompare - $strike->max());
//            if($delta == $minDelta)
//            {
//                $ret[] = [
//                    'type'=>Type::BUY,
//                    'resultVal'=>$this->resultBuy,
//                    'delta' => strikeVal($delta),
//                    'strike'=>$strike
//                ];
//            }
//        }
//    }


	
		
}