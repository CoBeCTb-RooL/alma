<?php
class Admin
{
	var 	  $id
			, $email
			, $password
			, $name
			, $regTime
			, $lastAuth
			, $active
			, $groupId
			, $group;
			
	const TBL = 'slonne__admins';
	
	#	число попыток, после которых будет delay
	const AUTH_TRIES_LIMIT = 5;
	
	#	шаг в секундах между попытками авторизации 
	const SECONDS_DELAY_STEP = 3;
	
	#	клёч
	const PASS_KEY = 'vn9n(^%$VNY73nv7t(^B*vn38yv48vwh7t^&';
	
	
	
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
		
		$m->id = $arr['id'];
		$m->email = $arr['email'];
		$m->password = $arr['password'];
		$m->name = $arr['name'];
		$m->regTime = $arr['regTime'];
		$m->lastAuth = $arr['lastAuth'];
		$m->active = $arr['active'];
		$m->groupId = $arr['groupId'];
		
        return $m;
	}
	
	
	
	function get($id, $active)
	{
		if($id =intval($id))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE id = ".$id." ".($active ? " AND active=1" : "")."";
			$qr=DB::query($sql);
			echo mysql_error();
			if($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				return self::init($next);
		}
	}
	
	
	function getByEmail($email)
	{
		if($email = strPrepare($email))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE email = '".$email."'";
			$qr=DB::query($sql);
			echo mysql_error();
			if($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				return self::init($next);
		}
	}
	
	
	
	function getByEmailAndPassword($email, $password)
	{
		if(($email = strPrepare($email)) && ($password = strPrepare($password)) )
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE email = '".$email."' AND password='".strPrepare(self::encryptPassword($password))."' AND active=1";
			$qr=DB::query($sql);
			echo mysql_error();
			if($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				return self::init($next);
		}
	}
	
	
	
	
	function encryptPassword($a)
	{
		//return $a;
		return md5($a.self::PASS_KEY);
	}
	
	
	function initGroup()
	{
		$this->group = AdminGroup::get($this->groupId);
	}
	
	
	
	
	
	function add($admin)
	{
		if($admin)
		{
			$sql = "
			INSERT INTO `".self::TBL."` 
			SET 
			  active='".$admin->active."'
			, name='".$admin->name."'
			, email='".$admin->email."'
			, groupId = '".$admin->groupId."'
			
			";
			
			$qr=DB::query($sql);
			echo mysql_error();
			return mysql_insert_id();
		}
	}
	
	
	
	function update($admin)
	{
		if($admin)
		{
			$sql = "
			UPDATE `".self::TBL."` 
			SET 
			  active='".$admin->active."'
			, name='".$admin->name."'
			, email='".$admin->email."'
			, groupId = '".$admin->groupId."'
			
			WHERE id=".intval($admin->id)."
			";
			//vd($sql);
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
	
	
	
	
	
	
	
	
	
	
	function isAdmin()
	{
		return $_SESSION['admin']['id'] ? true : false;
	}
	
	
	
	
	
	
	function setLastAuth()
	{
		$sql = "UPDATE `".strPrepare(self::TBL)."` SET lastAuth = NOW() WHERE id=".intval($this->id)." ";
		$qr=DB::query($sql);
		echo mysql_error();
	}
	
	
	
	function setPassword($password)
	{
		$sql = "UPDATE `".strPrepare(self::TBL)."` SET password = '".self::encryptPassword($password)."' WHERE id=".intval($this->id)." ";
		$qr=DB::query($sql);
		echo mysql_error();
	}
	
	
	
	
	
	function hasPrivilege($moduleId, $action)
	{
		//vd($this->group->privilegesArr);
		if($action)
		{
			if($this->group->privilegesArr[$moduleId][$action])
				return true;
		}
		else
			if($this->group->privilegesArr[$moduleId])
				return true;
		return false;
	}
	
	
	
	
	
	function checkAndForbid($moduleId, $action)
	{
		//vd($action);
		if(!$this->hasPrivilege($moduleId, $action))
		{
			header('HTTP/1.0 403 Forbidden');
			die;
		}
	}
	
	
	
	
	
	
} 
?>