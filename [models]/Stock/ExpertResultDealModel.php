<?php
class ExpertResultDeal
{
	const TBL = 'expert_results_details';
	
	
	public    $resultId
			, $num
			, $dt
			, $dtModify
			, $type
			, $order
			, $volume
			, $price
			, $sl
			, $tp
			, $profit
			, $balance
		;
	
		
	function __construct($rowStr)
	{
		list($this->num, $this->dt, $this->type, $this->order, $this->volume, $this->price, $this->sl, $this->tp, $this->profit, $this->balance) = explode("\t", $rowStr);
		
		$this->dt = str_replace('.', '-', $this->dt).':00';
	}	
		
	
	function init($arr)
	{
		if($arr)
		{
			$u = new self;
			
			$u->resultId = $arr['resultId'];
			$u->num = $arr['num'];
			$u->dt = $arr['dt'];
			$u->dtModify = $arr['dtModify'];
			$u->type = $arr['type'];
			$u->order = $arr['order'];
			$u->volume = $arr['volume'];
			$u->price = $arr['price'];
			$u->sl = $arr['sl'];
			$u->tp = $arr['tp'];
			$u->profit = $arr['profit'];
			$u->balance = $arr['balance'];
			
			
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
	
	
	
	function getList($resultId, $params)
	{
		$sql="SELECT * FROM `".strPrepare(self::TBL)."` WHERE 1 AND resultId='".intval($resultId)."'";
		
		if($params['orderBy'])
			$sql.=" ORDER BY ".(mysql_real_escape_string($params['orderBy']) ? mysql_real_escape_string($params['orderBy']) : ' dt ')." ".($params['desc'] ? ' DESC ' : '')." ";
			
		if( ($params['from'] = intval($params['from']))>=0 && ($params['count'] = intval($params['count']))>0)
			$sql.=" LIMIT ".$params['from'].", ".$params['count']." ";
			
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$ret[] = self::init($next);
				
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
	
	
	/*function getListInnerSql($params)
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
	}*/
	
	
	
	function insert()
	{
		$sql = "
		INSERT INTO `".self::TBL."` SET
		
		".$this->innerAlterSql()."
		";
		//vd($sql);
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
		//vd($sql);
		DB::query($sql);
		echo mysql_error();
		
	}
	
	
	
	
	function innerAlterSql()
	{
		$str="		
		  resultId = '".strPrepare($this->resultId)."'
		, num = '".strPrepare($this->num)."'
		, dt = '".strPrepare($this->dt)."'
		, dtModify = '".strPrepare($this->dtModify)."'
		, `type` = '".strPrepare($this->type)."'
		, `order` = '".strPrepare($this->order)."'
		, volume = '".strPrepare($this->volume)."'
		, price = '".strPrepare($this->price)."'
		, sl = '".strPrepare($this->sl)."'
		, tp = '".strPrepare($this->tp)."'
		, profit = '".strPrepare($this->profit)."'
		, balance = '".strPrepare($this->balance)."'
		
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
	
	
	function deleteByResultId($id)
	{
		$sql = "DELETE FROM `".self::TBL."` WHERE resultId=".intval($id);
		//vd($sql);
		DB::query($sql);
		echo mysql_error();
	}
	
	
} 













?>