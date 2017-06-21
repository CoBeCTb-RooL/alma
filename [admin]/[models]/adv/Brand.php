<?php 
class Brand{
	
	const TBL = 'adv__brands';
	
	var   $id
		, $status
		, $dateCreated
		, $idx
		, $name
		, $pic
		;
		
	
	
	
	function getList($status, $catId)
	{
		$catId = intval($catId);
		
		$sql = "SELECT brands.* FROM `".mysql_real_escape_string(self::TBL)."` AS brands ";
		if($catId) 
			$sql.=" INNER JOIN  `".CatBrandCmb::TBL."` AS cmb  ON cmb.brandId=brands.id ";
		
		$sql.=" WHERE 1 ";
		
		if($catId) 
			$sql.=" AND catId=".$catId." ";
		
		if($status) 
			$sql.=" AND status='".strPrepare($status->num)."' ";
		
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
		{
			$res[] = self::init($next);
		}
		
		return $res;
	}
	
	
	
	function init($arr)
	{
		$m = new self();
		$props = get_object_vars($m);
		
		foreach ($arr as $key => $value)
		{
            if(property_exists($m, $key ))
                $m->{$key} = $value;
        }
        $m->status = intval($m->status);
        return $m;
	}
	
	
	
	
	
	function get($id)
	{
		if($id =intval($id))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE id = ".$id;
			$qr=DB::query($sql);
			echo mysql_error();
			if($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				return self::init($next);
		}
	}
	
	
	
	
	

	function insert()
	{
		$sql = "
		INSERT INTO `".self::TBL."` 
		SET `dateCreated` = NOW(), 
		".$this->alterSql()."
		";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
	}
	

	
	
	function update()
	{
		$sql = "
		UPDATE `".self::TBL."` 
		SET 
		".$this->alterSql()."
		WHERE id=".intval($this->id)."
		";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
	}
	
	
	
	
	function alterSql()
	{
		$str.="
		  `status`='".intval($this->status)."'
		, `idx`='".intval($this->idx)."'
		, `name`='".strPrepare($this->name)."'
		, `pic`='".strPrepare($this->pic)."'
		";
		
		return $str;
	}
	
	
	
	
	
	function delete($id)
	{
		if($id = intval($id))
		{
			$sql = "
			DELETE FROM `".self::TBL."` WHERE id=".$id;
			DB::query($sql);
			echo mysql_error(); 
		}
	}
	
	
	
	function setIdx($id, $val)
	{
		if($id=intval($id))
		{
			$sql = "UPDATE `".self::TBL."` SET idx='".intval($val)."' WHERE id=".$id;
			//vd($sql);
			DB::query($sql);
			echo mysql_error();
		}
	}
	
	
	
	
	
	function validate()
	{
		if(!trim($this->name))
			$problems[] = Slonne::setError('name', 'Заполните все необходимые поля!');
		
		return $problems;
	}
	
	
	
	
	function getNextIdx()
	{
		$sql = "SELECT MAX(idx) as res  FROM `".mysql_real_escape_string(self::TBL)."`";
		$qr = DB::query($sql);
		echo mysql_error();
		
		$next = mysql_fetch_array($qr, MYSQL_ASSOC);
		$res = $next['res'];
		
		$res = $res % 10 ? $res + (10-$res%10) : $res+10;
		
		return $res;
	}
	
	
	
}
?>