<?php
class AdminGroup
{
	var 	  $id
			, $name
			, $active
			, $privileges
			, $privilegesArr;

			
	const TBL = 'slonne__admin_groups';
	
	
	
	function getList()
	{
		$sql="SELECT * FROM `".self::TBL."` ORDER BY id";
		$qr = DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$ret[] = self::init($next);
		
		return $ret;
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
	
	
	
	function add($group)
	{
		if($group)
		{
			$sql = "
			INSERT INTO `".self::TBL."` 
			SET active='".$group->active."'
			, name='".$group->name."'
			, privileges = '".$group->privileges."'
			";
			
			$qr=DB::query($sql);
			echo mysql_error();
		}
	}
	
	
	
	function update($group)
	{
		if($group)
		{
			$sql = "
			UPDATE `".self::TBL."` 
			SET active='".$group->active."'
			, name='".$group->name."'
			, privileges = '".$group->privileges."'
			WHERE id=".intval($group->id)."
			";
			vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
		}
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
	
	
	
	
	
	function initPrivilegesArr()
	{
		//vd($this->privileges);
		if($this->privileges)
		{
			$tmp = explode('|', $this->privileges);
			//vd($tmp);
			foreach($tmp as $key=>$val)
			{
				$tmp2 = explode(':', $val);
				//$result[$tmp2[0]] = array();
				//vd($tmp2);
				$tmp3 = explode(',', $tmp2[1]);
				foreach($tmp3 as $key2=>$val2)
					$result[$tmp2[0]][$val2] = 1;
				//vd($tmp3);
				//$result[$tmp2[0]] = explode(',', $tmp2[1]);
				
			}
		}
		//echo '<hr>';
		//vd($result);
		$this->privilegesArr = $result;
	}
	
	
	
	
	
} 
?>