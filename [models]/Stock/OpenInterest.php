<?php
class OpenInterest{
	var   $id
		, $currency
		, $type
		, $dateCreated
		, $strike
		, $settle
		, $openInterest
		, $currencyObj
		
		, $level
		;
		
	const TBL = 'stock__open_interest';
	
	

	function init($arr)
	{
		if(count($arr))
		{
			$a = new self();
			$a->id = $arr['id'];
			$a->currency = $arr['currency'];
			$a->type = $arr['type'];
			$a->dateCreated = $arr['dateCreated'];
			$a->strike = $arr['strike'];
			$a->settle = $arr['settle'];
			$a->openInterest = $arr['openInterest'];
			$a->currencyObj = Currency::$items[$arr['currency']];
			
			$a->calculateLevel();
		}
		//vd($a);
		
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
				, settle = '".floatval($this->settle)."'
				, openInterest = '".intval($this->openInterest)."'
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
				
				//$ret = self::sortByType($ret);
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
	
	
	
	
	function sortByStrikeAndType($arr)
	{
		foreach($arr as $key=>$val)
			$tmp[$val->strike][$val->type] = $val;
		return $tmp;
	}
	
	

	function parseFile($str)
	{
		$ret = null;
	
		$optionsType='';
		foreach($lines = explode("\r\n", $str) as $key=>$line)
		{
			//$cols[$key] = explode(";", $line);
				
			$tmp = explode(";", $line);
			$arr = array(
					'strike'=>$tmp[0],
					'type'=>$tmp[1]=='Call'?Strike::TYPE_CALL : ($tmp[1]=='Put' ? Strike::TYPE_PUT : '') ,
					'settle'=>round(floatval($tmp[7]), 4),
					'openInterest'=>str_replace(',', '', $tmp[9]),
					
			);
			
			//vd($arr);

			if(intval($arr['strike']))
				$ret[$arr['type']][] = OpenInterest::init($arr);
			
		}
	
		//vd($ret);
		return $ret;
	}
	
	
	
	
	
	function calculateLevel()
	{
		//vd($this->curren)
		# 	для GBP
		if($this->currency==Currency::$items[Currency::CODE_GBP]->code)
		{
			if($this->type == Strike::TYPE_CALL)
				$this->level = ($this->strike / 1000) + ($this->currentPrem / 100);
			elseif($this->type == Strike::TYPE_PUT)
				$this->level = ($this->strike / 1000) - ($this->currentPrem / 100);
		}
		else
		{
			if($this->type == Strike::TYPE_CALL)
				$this->level = ($this->strike / 1000) + $this->settle;
			elseif($this->type == Strike::TYPE_PUT)
				$this->level = ($this->strike / 1000) - $this->settle;
		}
	}
	
	
	
	
		
}