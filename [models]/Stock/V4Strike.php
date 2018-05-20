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


	const TBL = 'v4_strikes';

	function __construct($arr)
	{
		if(count($arr))
		{
			$this->id = $arr['id'];
			$this->pid = $arr['pid'];
			$this->dt = $arr['dt'];
			$this->currency = Currency::code($arr['currency']);
			$this->type = Type::code($arr['type']);
			$this->strike = strikeVal($arr['strike']);
			$this->premiumBuy = strikeVal($arr['premiumBuy']);
			$this->premiumSell = strikeVal($arr['premiumSell']);
			$this->resultBuy = strikeVal($arr['resultBuy']);
			$this->resultSell = strikeVal($arr['resultSell']);
			$this->forward = $arr['forward'];
			$this->status = Status2::code($arr['status']);
			$this->comment = $arr['comment'];
			$this->isZone = $arr['isZone'];

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



	public static function arrangeList2($list)
	{
		$res = [];

		foreach($list as $val)
			$res[substr($val->dt, 0, 10)][$val->currency->code][$val->strikeType->code][$val->type->code] = $val;

		return $res;
	}

	public static function arrangeListByDate($list)
	{
		$res = [];

		foreach($list as $val)
			$res[substr($val->dt, 0, 10)][] = $val;

		return $res;
	}




	public function deletePreviousData()
	{
		self::deleteByDateAndCurrencyAndStrikeTypeAndType($this->dt, $this->currency->code, $this->strikeType->code,  $this->type->code);
	}



	public static function deleteByDateAndCurrencyAndStrikeTypeAndType($date, $cur, $strikeType, $type)
	{
		$sql = "DELETE FROM `".self::TBL."` where 
		DATE(dt) = DATE('".strPrepare($date)."')
		 AND currency='".$cur."'
		 AND `strikeType`='".$strikeType."'
		 AND `type`='".$type."'
		";
		vd($sql);
		DB::query($sql);
		echo mysql_error();
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
		$this->resultBuy = $this->strike - $this->premiumBuy;
		$this->resultSell = $this->strike + $this->premiumSell;

		if($this->currency->code == Currency::CODE_AUD)
		{
			$this->resultBuy += $this->forward;
			$this->resultSell += $this->forward;
		}
		else
		{
			$this->resultBuy -= $this->forward;
			$this->resultSell -= $this->forward;
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
			SET   ".$this->innerAlterSql()."
				";
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
				";

        return $str;
    }





	
		
}