<?php
Status::initArr();

class Status{
	
	var   $num
		, $code
		, $name
		;
		
	const UNSPECIFIED = '';
	const ACTIVE = 'active';
	const INACTIVE = 'inactive';

	
	static $items;
	
	
	function  __construct($num, $code, $name)
	{
		$this->num=$num;
		$this->code=$code;
		$this->name=$name;
	}	
	
	
	public  function initArr()
	{
		$arr[self::ACTIVE] = new Status(1, 'active', 'Активен');
		$arr[self::INACTIVE] = new Status(2, 'inactive', 'Неактивен');

		self::$items = $arr;
	}
	
	
	
}

