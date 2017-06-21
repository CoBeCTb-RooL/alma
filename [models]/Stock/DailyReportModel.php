<?php
class DailyReport{
	
	var   $date
		, $premSums
		, $premSumsPrev
		, $premPercents
		, $strikes
		;
	
	
	
	
	
	function getReport($currency, $date)
	{
		if(Funx::isDateValid($date))
		{
			if($strikes = Strike::getByCurrencyAndDate($currency->code, $date))
			{
				$ret = new self();
				$ret->date = $date;
				$ret->strikes = $strikes; 
				//$ret->initSums();
				//$ret->premSums = self::initSumsByStrikes($strikes);
				$ret->initSums();
				$ret->calculatePercents();
			}
		}
		
		return $ret;
	}
		
		
	
	function initSums()
	{
		foreach($this->strikes as $type=>$items)
			foreach($items as $item)
			{
				$this->premSums[$type] += ($item->currentPrem);
				$this->premSumsPrev[$type] += $item->previousPrem;
			}
	}
	
	
	function initSumsByStrikes($strikes)
	{
		foreach($strikes as $type=>$items)
			foreach($items as $item)
				$premSums[$type] += ($item->currentPrem - $item->previousPrem);
			
		return $premSums; 
	}
	
	
	
	function calculatePercents()
	{
		$this->premPercents[Strike::TYPE_CALL] = self::countPercents($valWas = $this->premSumsPrev[Strike::TYPE_CALL], $valIs = $this->premSums[Strike::TYPE_CALL]);
				
			$this->premPercents[Strike::TYPE_PUT] = self::countPercents($valWas = $this->premSumsPrev[Strike::TYPE_PUT], $valIs = $this->premSums[Strike::TYPE_PUT]);
		
	}
	
	
	
	function countPercents($valWas, $valIs)
	{
		
		$ret=null;
		$ret = ($valIs*100/$valWas);
		$ret-=100;
		
		/*vd($valWas);
		vd($valIs);
		vd($ret);
		
			
		echo '<hr>';*/
		return round($ret, 2);
		
		
		/*if($valWas < $valIs)	# 	РОСТ
		{
			//echo 'РОСТ';
			$raznica = $valIs - $valWas;
			
			
		}
		else 	# 	СПАД
		{
			//echo 'СПАД';
			$raznica = $valWas - $valIs;
			$spad = true;
		}
		
		
		$percent = 100 * $raznica / $valWas;
		
		if(!$spad && $percent < 0)
			$percent *= -1;
		
		if($spad && $percent > 0)
			$percent *= -1;*/
		
		//return 15; 

		/*vd($valWas);
		vd($valIs);
		vd($valWas < $valIs);
		vd($raznica);
		echo "( spad=".$spad." )  ".($spad ? '-' : '').$percent."%<hr>";*/
		
		
	}
	
		
		
	
	function parseFile($str, $date)
	{
		$ret = null;
		
		$optionsType='';
		foreach($lines = explode("\r\n", $str) as $key=>$line)
		{
			//$cols[$key] = explode(";", $line);
			
			$tmp = explode(";", $line);
			$arr = array(
					'dateCreated'=>$date,
					'strike'=>$tmp[0],
					//'junk1'=>$tmp[1],
					//'junk2'=>$tmp[2],
					//'optionsType'=>$tmp[3],
					//'junk3'=>$tmp[4],
					//'junk4'=>$tmp[5],
					'previousPrem'=>$tmp[6] == '----' ? 0 : $tmp[6],
					'currentPrem'=>$tmp[7],
					//'junk5'=>$tmp[8],
					'openInterestDelta'=>$tmp[9]*100,
					//'junk7'=>$tmp[10],
					'volume'=>$tmp[11],
					'openInterest'=>$tmp[12],
					'openInterestDynamics'=>$tmp[13],
					'openInterestChangesQty'=>$tmp[14],
					//'junk9'=>$tmp[15],
					//'junk10'=>$tmp[16],
			);
			//vd($arr['junk6']*100);
			if($tmp[3]) 	# 	тип опциона
				$optionsType = $tmp[3];
			
			if($optionsType && !$tmp[3])
			{
				//$cols[$optionsType][] = $arr;
				$ret[$optionsType][] = Strike::init($arr);
			}
		}
		
		
		
		
		
		
		//vd($cols);
		
		//$ret = $lines;
		return $ret;
	}
	
	
}