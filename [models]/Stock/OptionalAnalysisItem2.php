<?php
class OptionalAnalysisItem2{
	public $dt;
	public $currency;
	public $strikeType;
	public $type;
	public $strike;
	public $premium;
	public $forward;
	public $result;

	/*public $currencyStr;
	public $typeStr;*/


	const TBL = 'optional_analysis';

	function __construct($arr)
	{
		if(count($arr))
		{
			$this->dt = $arr['dt'];
			$this->currency = Currency::code($arr['currency']);
			$this->type = Type::code($arr['type']);
			$this->strikeType = StrikeType2::code($arr['strikeType']);
			$this->strike = $arr['strike'];
			$this->premium = $arr['premium'];
			$this->forward = $arr['forward'];

			$this->calculate();
		}
	}

	public function arrangeDataFromArray($arr, $strikeType, $type)
	{
		$res = [
			'dt' => $_REQUEST['date'][$_REQUEST['currency']],
			'currency' => $_REQUEST['currency'],
			'strikeType' => $strikeType,
			'type' => $type,
			'strike' => $_REQUEST['strike'][$_REQUEST['currency']][$strikeType][$type],
			'premium' => $_REQUEST['premium'][$_REQUEST['currency']][$strikeType][$type],
			'forward' => $_REQUEST['forward'][$_REQUEST['currency']],
		];

		//vd($res);

		return $res;
	}



	public function getList($params)
	{
		$sql = "SELECT * FROM `".self::TBL."` WHERE 1 ";
		if($params['currency'] )
			$sql.=" AND currency= '".strPrepare($params['currency']->code)."' ";
		if($params['type'] )
			$sql.=" AND `type`= '".strPrepare($params['type']->code)."' ";
		if($params['dt'] )
			$sql.=" AND DATE(dt)= DATE('".strPrepare($params['dt'])."') ";

		if($params['orderBy'])
			$sql .= " ORDER BY ".strPrepare($params['orderBy'])." ";
		//vd($sql);
		$qr = DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$ret[] = new self($next);

		return $ret;
	}


	public static function arrangeList($list)
	{
		$res = [];

		foreach($list as $val)
			$res[substr($val->dt, 0, 10)][$val->currency->code][$val->type->code] = $val;

		return $res;
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


	
	
	function insert()
	{
		if($this->strike)
		{
			$sql = "
			INSERT INTO `".self::TBL."` 
			SET   dt = '".strPrepare($this->dt)."'
				, `strikeType` = '".strPrepare($this->strikeType->code)."'
				, `type` = '".strPrepare($this->type->code)."'
				, currency = '".strPrepare($this->currency->code)."'
				, strike = '".floatval($this->strike)."'
				, premium = '".floatval($this->premium)."'
				, `result` = '".floatval($this->result)."'
				, `forward` = '".floatval($this->forward)."'
				";
			//vd($sql);
			DB::query($sql);
			echo mysql_error();
		}
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
	
	

	public function calculate()
	{
		$res = '';

		$res = $this->strike - $this->forward;
		if($this->type->code == Type::BUY)
			$res += $this->premium;
		elseif($this->type->code == Type::SELL)
			$res -= $this->premium;

		$this->result = $res;
	}
	
	
		
}