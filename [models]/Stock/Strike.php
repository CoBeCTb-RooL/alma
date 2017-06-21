<?php
class Strike{
	var   $id
		, $currency
		, $type
		, $dateCreated
		, $strike
		, $previousPrem
		, $currentPrem
		, $volume
		, $openInterest
		, $openInterestDynamics
		, $openInterestChangesQty
		, $openInterestDelta
		, $openInterestContractsCount
		, $level
		
		, $currencyObj
		;
		
	const TBL = 'stock__strikes';
	
	const TYPE_CALL = 'CALL';
	const TYPE_PUT = 'PUT';
	

	function init($arr)
	{
		if(count($arr))
		{
			$a = new Strike();
			$a->id = $arr['id'];
			$a->currency = $arr['currency'];
			$a->type = $arr['type'];
			$a->dateCreated = $arr['dateCreated'];
			$a->strike = $arr['strike'];
			$a->previousPrem = $arr['previousPrem'];
			$a->currentPrem = $arr['currentPrem'];
			$a->volume = $arr['volume'];
			$a->openInterest = $arr['openInterest'];
			$a->openInterestDynamics = $arr['openInterestDynamics'];
			$a->openInterestChangesQty = $arr['openInterestChangesQty'];
			$a->openInterestDelta = $arr['openInterestDelta'];
			$a->openInterestContractsCount = $arr['openInterestContractsCount'];
			
			$a->currencyObj = Currency::$items[$arr['currency']];
			
		}
		
		return $a;
	}
	
	
	
	
	
	function insert()
	{
		if($this->strike)
		{
			$sql = "
			INSERT INTO `".self::TBL."` 
			SET   dateCreated = '".strPrepare($this->dateCreated)."'
				, type = '".strPrepare($this->type)."'
				, currency = '".strPrepare($this->currency)."'
				, strike = '".floatval($this->strike)."'
				, previousPrem = '".floatval($this->previousPrem)."'
				, currentPrem = '".floatval($this->currentPrem)."'
				, openInterest = '".intval($this->openInterest)."'
				, volume = '".strPrepare($this->volume)."'
				, openInterestDynamics = '".strPrepare($this->openInterestDynamics)."'
				, openInterestChangesQty = '".intval($this->openInterestChangesQty)."'
				, openInterestDelta = '".strPrepare($this->openInterestDelta)."'
					";
			//vd($sql);
			DB::query($sql);
			echo mysql_error();
		}
	}
	
	
	
	
	
	function cleanDateByCurrency($date, $currency)
	{
		if(  ($date = strPrepare($date)) && $currency)
		{
			$sql = "DELETE FROM `".self::TBL."` WHERE DATE(dateCreated) = '".$date."' AND currency='".strPrepare($currency)."' ";
			DB::query($sql);
			echo mysql_error();
		}
	}
	
	
	
	
	
	
	function getByCurrencyAndDate($currency, $dateFrom)
	{
		//vd($dateFrom);
		if($dateFrom && Funx::isDateValid($dateFrom))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE 1 ";
			if($dateTo && Funx::isDateValid($dateTo))
			{
				
			}
			else
			{
				$sql.= " AND DATE(dateCreated)='".strPrepare($dateFrom)."' ";
			}
			
			$sql .= " AND currency='".strPrepare($currency)."' ";
			//vd($sql);
			$sql.=" ORDER BY type ASC, strike ASC";
			$qr = DB::query($sql);
			echo mysql_error();
			if(mysql_num_rows($qr))
			{
				while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				{
					$ret[] = self::init($next);
					
					
				}
				
				$ret = self::sortByType($ret);
			}
		}
		//vd($ret);
		
		return $ret; 
	}
	
	
	
	
	
	function sortByType($arr)
	{
		foreach($arr as $key=>$val)
			$tmp[$val->type][] = $val;
		return $tmp; 
	}
	
	
	
	
	

	function calculateLevel()
	{
		# 	ФУНТ и АВСТРАЛИЕЦ
		if(in_array($this->currency, array(
										Currency::$items[Currency::CODE_GBP]->code, 
										Currency::$items[Currency::CODE_AUD]->code,
									)))
		{
			if($this->type == self::TYPE_CALL)
				$this->level = ($this->strike / 1000) + ($this->currentPrem / 100);
			elseif($this->type == self::TYPE_PUT)
				$this->level = ($this->strike / 1000) - ($this->currentPrem / 100);
		}
		# 	ШВЕЙЦАРЕЦ и КАНАДЕЦ
		elseif(in_array($this->currency, array(
										Currency::$items[Currency::CODE_CHF]->code, 
										Currency::$items[Currency::CODE_CAD]->code,
									)))
		{
			if($this->type == self::TYPE_CALL)
				$this->level = 1 / (($this->strike / 1000) + ($this->currentPrem / 100));
			elseif($this->type == self::TYPE_PUT)
				$this->level = 1 / (($this->strike / 1000) - ($this->currentPrem / 100));
		}
		# 	ЯПОНЕЦ
		elseif(in_array($this->currency, array(
										Currency::$items[Currency::CODE_JPY]->code,
								)))
		{
			if($this->type == self::TYPE_CALL)
				$this->level = 100 / (($this->strike / 1000) + ($this->currentPrem / 10000));
			elseif($this->type == self::TYPE_PUT)
				$this->level = 100 / (($this->strike / 1000) - ($this->currentPrem / 10000));
		}
		else
		{
			if($this->type == self::TYPE_CALL)
				$this->level = ($this->strike + $this->currentPrem) / 1000;
			elseif($this->type == self::TYPE_PUT)
				$this->level = ($this->strike - $this->currentPrem) / 1000;
		}
	}
	
	
	
		
}