<?php

/*  for alma v4.0 */

class V4Strike{
	public $id;
	public $pid;
	public $dt;
	public $currency;
	public $strike;
	public $premiumBuy;
	public $premiumSell;
	public $forward;
	public $resultBuy;
	public $resultSell;
	public $status;
	public $comment;
	public $isZone;
	public $data;
	public $createdAt;
	public $updatedAt;


	const TBL = 'v4_strikes';

	function __construct($arr)
	{
		if(count($arr))
		{
			$this->id = $arr['id'];
			$this->pid = $arr['pid'];
			$this->dt = $arr['dt'];
			$this->currency = Currency::code($arr['currency']);
			$this->strike = strikeVal($arr['strike']);
			$this->premiumBuy = strikeVal($arr['premiumBuy'], 7);
			$this->premiumSell = strikeVal($arr['premiumSell'], 7);
			$this->resultBuy = strikeVal($arr['resultBuy']);
			$this->resultSell = strikeVal($arr['resultSell']);
			$this->forward = $arr['forward'];
			$this->status = Status2::code($arr['status']);
			$this->comment = $arr['comment'];
			$this->isZone = $arr['isZone'];
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



	public function initStrikes()
	{
		$this->strikes = self::getList(['pid'=>$this->id]);

		$this->initClosestStrikes();
	}


	public function initClosestStrikes()
	{
		foreach ($this->strikes as $s)
			$s->countDeltas($this);

		# 	теперь ищем клоусесты
		$closestBuy = null;
		$closestSell = null;

		$i=0;
		foreach ($this->strikes as $s)
		{
			if(!$i) 	# 	первый
			{
				if($s->deltaBuy >=0)
					$closestBuy = $s;

				if($s->deltaSell >=0)
					$closestSell = $s;
			}
			else
			{
				if( $s->deltaBuy >= 0 && (!$closestBuy || $s->deltaBuy < $closestBuy->deltaBuy)  )
					$closestBuy = $s;
				if( $s->deltaSell >= 0 && (!$closestSell || $s->deltaSell < $closestSell->deltaSell)  )
					$closestSell = $s;
			}

			$i++;
		}
		$this->closestBuy = $closestBuy;
		$this->closestSell = $closestSell;


		# 	ищем клоусесты по модулю
		foreach ($this->strikes as $s)
		{
			# 	buy
			if(!$this->closestAbsBuy)
				$this->closestAbsBuy = $s;
			elseif($s->deltaAbsBuy < $this->closestAbsBuy->deltaAbsBuy)
				$this->closestAbsBuy = $s;

			# 	buy
			if(!$this->closestAbsSell)
				$this->closestAbsSell = $s;
			elseif($s->deltaAbsSell < $this->closestAbsSell->deltaAbsSell)
				$this->closestAbsSell = $s;
		}

	}


	public function countDeltas($zone)
	{
		$this->deltaBuy = strikeVal($zone->resultBuy - $this->resultBuy);
		$this->deltaSell = strikeVal($this->resultSell - $zone->resultSell);

//		$this->deltaAbsBuy = strikeVal(abs($zone->resultBuy - $this->resultBuy));
//		$this->deltaAbsSell = strikeVal(abs($zone->resultSell - $this->resultSell));
		$this->deltaAbsBuy = abs($this->deltaBuy);
		$this->deltaAbsSell = abs($this->deltaSell);
	}



	public function toArray()
	{
		$tmp = clone $this;
		unset($tmp->strikes);
		unset($tmp->closestBuy);
		unset($tmp->closestSell);

		return (array) $tmp;
	}



    public function delete()
    {
    	$list = self::getList(['pid'=>$this->id]);
    	foreach ($list as $item)
		{
			$sql = "DELETE FROM `".self::TBL."` where id=".$item->id;
			//vd($sql);
			DB::query($sql);
			echo mysql_error();
		}

		# 	удаляем сам объект
        $sql = "DELETE FROM `".self::TBL."` where id=".$this->id;
        //vd($sql);
        DB::query($sql);
        echo mysql_error();
    }
	
	

	public function calculate()
	{
		$this->resultBuy = $this->strike - $this->premiumSell;
		$this->resultSell = $this->strike + $this->premiumBuy;

//		if($this->currency->code == Currency::CODE_AUD)
		if(in_array($this->currency->code, [Currency::CODE_AUD, Currency::CODE_EUR, Currency::CODE_GBP]))
		{
			$this->resultBuy -= $this->forward;
			$this->resultSell -= $this->forward;
		}
		elseif(in_array($this->currency->code, [Currency::CODE_CAD, Currency::CODE_JPY, Currency::CODE_CHF]))
		{
			$this->resultBuy += $this->forward;
			$this->resultSell += $this->forward;

			# 	оборачиваем
			$this->resultBuy = 1 / $this->resultBuy;
			$this->resultSell = 1 / $this->resultSell;

			# 	меняем местами
			$a = $this->resultBuy;
			$this->resultBuy = $this->resultSell;
			$this->resultSell = $a;
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
				, `resultBuy` = '".floatval($this->resultBuy)."'
				, `resultSell` = '".floatval($this->resultSell)."'
				, `forward` = '".floatval($this->forward)."'
				, `status` = '".strPrepare($this->status->code)."'
				, `comment` = '".strPrepare($this->comment)."'
				, `isZone` = '".($this->isZone ? 1 : 0)."'
				, `data` = '".strPrepare($this->data)."'
				, `updatedAt` = '".strPrepare($this->updatedAt)."'
				";

        return $str;
    }





	
		
}