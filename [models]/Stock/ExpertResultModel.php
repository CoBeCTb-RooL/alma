<?php
class ExpertResult
{
	const TBL = 'expert_results';
	
	
	public    $id
			, $user
			, $symbolCode
			, $totalProfit
			, $totalLoss
			, $dealsProfit
			, $dealsLoss
			, $continuousProfit
			, $continuousLoss
			, $spread
			, $tp
			, $sl
			, $closeBySignal
			, $period
			, $months
			, $comment
			
			, $dateCreated
		;
	
		
	function init($arr)
	{
		if($arr)
		{
			$u = new self;
			
			$u->id = $arr['id'];
			$u->user = $arr['user'];
			$u->symbolCode = $arr['symbol'];
			$u->dateCreated = $arr['dateCreated'];
			$u->totalProfit = $arr['totalProfit'];
			$u->totalLoss = $arr['totalLoss'];
			$u->dealsProfit = $arr['dealsProfit'];
			$u->dealsLoss = $arr['dealsLoss'];
			$u->continuousProfit = $arr['continuousProfit'];
			$u->continuousLoss = $arr['continuousLoss'];
			$u->spread = $arr['spread'];
			
			$u->tp = $arr['tp'];
			$u->sl = $arr['sl'];
			$u->closeBySignal = $arr['closeBySignal'];
			$u->period 	= $arr['period'];
			$u->months 	= $arr['months'];
			$u->comment 	= $arr['comment'];
			
			
			return $u;
		}
	}
	
	
	function get($id, $status)
	{
		if($id = intval($id))
		{
			$sql="SELECT * FROM `".strPrepare(self::TBL)."` WHERE id=".$id." ";
			$qr=DB::query($sql);
			echo mysql_error();
			if($attrs = mysql_fetch_array($qr, MYSQL_ASSOC))
				$item = self::init($attrs);
			
			return $item;
		}
	}
	
	
	
	function getList($params)
	{
		$sql="SELECT * FROM `".strPrepare(self::TBL)."` WHERE 1 ".self::getListInnerSql($params);
		
		if($params['orderBy'])
			$sql.=" ORDER BY ".(mysql_real_escape_string($params['orderBy']) ? mysql_real_escape_string($params['orderBy']) : ' idx ')." ".($params['desc'] ? ' DESC ' : '')." ";
			
		if( ($params['from'] = intval($params['from']))>=0 && ($params['count'] = intval($params['count']))>0)
			$sql.=" LIMIT ".$params['from'].", ".$params['count']." ";
			
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$ret[$next['id']] = self::init($next);
				
		return $ret;
	}
	
	
	
	function getCount($params)
	{
		$sql="SELECT COUNT(*) FROM `".strPrepare(self::TBL)."` WHERE 1 ".self::getListInnerSql($params);
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		$next = mysql_fetch_array($qr);
		return $next[0];
	}
	
	
	function getListInnerSql($params)
	{
		//vd($params);
		$sql = "";
		
		if($params['id'])
			$sql .= " AND id=".intval($params['id'])." ";
		if(array_key_exists('ids', $params) )
		{
			$sql .= " AND id IN(-1";
			foreach($params['ids'] as $s)
				$sql .= ", '".intval($s)."'";
			$sql.=") ";
		}
		if(array_key_exists('idsNotIn', $params) )
		{
			$sql .= " AND id NOT IN(-1";
			foreach($params['idsNotIn'] as $s)
				$sql .= ", '".intval($s)."'";
			$sql.=") ";
		}
		
		//vd($sql);
		return $sql;
	}
	
	
	
	function insert()
	{
		$sql = "
		INSERT INTO `".self::TBL."` SET
		dateCreated = NOW(),
		".$this->innerAlterSql()."
		";
		vd($sql);
		DB::query($sql);
		echo mysql_error();
		return mysql_insert_id();
		//vd($sql);
	}
	
	
	
	
	function update()
	{
		$sql = "
		UPDATE `".self::TBL."` SET
		".$this->innerAlterSql()."
		WHERE id=".$this->id."
		";
		vd($sql);
		DB::query($sql);
		echo mysql_error();
		
	}
	
	
	
	
	function innerAlterSql()
	{
		$str="		
		  user = '".strPrepare($this->user)."'
		, symbol = '".strPrepare($this->symbolCode)."'
		, totalProfit = '".strPrepare($this->totalProfit)."'
		, totalLoss = '".strPrepare($this->totalLoss)."'
		, dealsProfit = '".strPrepare($this->dealsProfit)."'
		, dealsLoss = '".strPrepare($this->dealsLoss)."'
		, continuousProfit = '".strPrepare($this->continuousProfit)."'
		, continuousLoss = '".strPrepare($this->continuousLoss)."'
		, spread = '".strPrepare($this->spread)."'
				
		, tp = '".strPrepare($this->tp)."'
		, sl = '".strPrepare($this->sl)."'
		, closeBySignal = '".strPrepare($this->closeBySignal)."'
		, period = '".strPrepare($this->period)."'
		, months = '".strPrepare($this->months)."'
		, comment = '".strPrepare($this->comment)."'
		
		";
		
		
		return $str;
	}
	
	
	
	
	
	function validate()
	{
		$errors = null;
		
		if(!$this->name)
			$errors[] = new Error('Не указано название', 'name');
		/*if(!$this->descr)
			$errors[] = new Error('Не введено описание товара', 'descr');*/
		
		
		return $errors;
	}
	
	
	
	
	function setStatus($status)
	{
		if($status)
		{
			$sql = "UPDATE `".self::TBL."` SET status='".intval($status->num)."' WHERE id=".$this->id;
			//vd($sql);
			DB::query($sql);
			echo mysql_error();
		}
	}
	
	
	
	function delete()
	{
		$sql = "DELETE FROM `".self::TBL."` WHERE id=".$this->id;
		//vd($sql);
		DB::query($sql);
		echo mysql_error();
	}
	
	
	
	
	function initDetails()
	{
		$this->details = ExpertResultDeal::getList($this->id);
	}
	
	
	
	
	
} 













?>