<?php
class User 
{
	const TBL = 'users';
	
	var   $id
		, $surname
		, $name
		, $fathername
		, $password
		, $birthdate
		, $email
		, $phone
		, $salt
		, $registrationDate
		, $registrationIp
		, $lastActivity
		, $lastIp
		, $sex
		, $active;
		
	
	
	
	
	function init($arr)
	{
		if($arr)
		{
			$u = new User;
			
			$u->id = $arr['id'];
			$u->surname = $arr['surname'];
			$u->name = $arr['name'];
			$u->fathername = $arr['fathername'];
			$u->birthdate = $arr['birthdate'];
			$u->email = $arr['email'];
			$u->sex = $arr['sex'];
			$u->phone= $arr['phone'];
			$u->registrationIp = $arr['ip'];
			$u->lastIp = $arr['ip'];
			$u->password = $arr['password'];
			$u->salt = $arr['salt'];
			$u->active = $arr['active'];
			
			return $u;
		}
	}
	
	
	
	function get($id)
	{
		if($id = intval($id))
		{
			$sql="SELECT * FROM users WHERE id=".$id;
			$qr=DB::query($sql);
			echo mysql_error();
			if($attrs = mysql_fetch_array($qr, MYSQL_ASSOC))
			{
				$user = User::init($attrs);
			}
			
			return $user;
		}
	}
	
	
	function insert()
	{
		$sql = "
		INSERT INTO `".self::TBL."` SET
		".$this->innerAlterSql()."
		, active = 0
		";
		DB::query($sql);
		echo mysql_error();
		//vd($sql);
	}
	
	
	
	
	function update()
	{
		$sql = "
		UPDATE `".self::TBL."` SET
		".$this->innerAlterSql()."
		, active= '".($this->active ? 1 : 0)."'
		WHERE id=".$this->id."
		";
		DB::query($sql);
		echo mysql_error();
		//vd($sql);
	}
	
	
	
	
	function innerAlterSql()
	{
		$str="
		  surname = '".strPrepare($this->surname)."'
		, name = '".strPrepare($this->name)."'
		, fathername = '".strPrepare($this->fathername)."'
		, password = '".strPrepare($this->password)."'
		, birthdate = '".strPrepare($this->birthdate)."'
		, email = '".strPrepare($this->email)."'
		, phone= '".strPrepare($this->phone)."'
		, registrationDate = NOW()
		, registrationIp = '".strPrepare($_SERVER['REMOTE_ADDR'])."'
		, lastActivity = NOW()
		, lastIp = '".strPrepare($_SERVER['REMOTE_ADDR'])."'
		, sex = '".strPrepare($this->sex)."'
		, salt= '".strPrepare($this->salt)."'";
		
		return $str;
	}
	
	
	
	
	/*function authenticate($email, $pass)
	{
		$sql="SELECT * FROM `".self::TBL."` WHERE email='".mysql_real_escape_string($email)."' AND password = '".mysql_real_escape_string($pass)."' AND active=1";
		$qr=DB::query($sql);
		echo mysql_error();
		$usr=mysql_fetch_array($qr, MYSQL_ASSOC);
		if($usr)
		{
			$_SESSION['user'] = array(
									'id'=>$usr['id'],
									'surname'=>$usr['surname'],
									'name'=>$usr['name'],
									'fathername'=>$usr['fathername'],
									'email'=>$usr['email'], 
							);
			return true; 
		}
	}*/
	
	
	
	
	function getByEmailAndPassword($email, $password)
	{
		$sql="SELECT * FROM `".self::TBL."` WHERE email='".mysql_real_escape_string($email)."' AND password = '".mysql_real_escape_string($password)."' AND active=1";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		$usr=mysql_fetch_array($qr, MYSQL_ASSOC);
		//vd($usr);
		return User::init($usr);
	}
	
	
	
	

	
	
	function emailExists($eml, $selfId)
	{
		$sql="SELECT id FROM `".self::TBL."` WHERE email = '".trim(mysql_real_escape_string($eml))."' ".(intval($selfId) ? " AND id != ".intval($selfId)."" : "")."";
		$qr=DB::query($sql);	
		echo mysql_error();
		return mysql_num_rows($qr) > 0;
	}
	
	
	
	
	
	
	
	
	function getBySalt($salt)
	{
		if($salt = strPrepare(trim($salt)))
		{
			$sql="SELECT * FROM `".self::TBL."` WHERE salt='".$salt."'";
			$qr=DB::query($sql);
			echo mysql_error();
			if($attrs = mysql_fetch_array($qr, MYSQL_ASSOC))
			{
				$user = User::init($attrs);
			}
			return $user;
		}
	}
	
	
	
	
	
	
	
} 













?>