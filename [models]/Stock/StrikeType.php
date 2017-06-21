<?php
StrikeType::initArr();

class StrikeType{
	
	var   $code
		, $name
		;
		
	
	const BARRIER = 'barrier';
	const MAIN = 'main';

		
	static $items;
	
	
	function  __construct($code, $name)
	{
		$this->code=$code;
		$this->name=$name;
	}
	
	
	public  function initArr()
	{
        $arr[self::MAIN] = new self(self::MAIN, 'ГЛАВНЫЙ');
        $arr[self::BARRIER] = new self(self::BARRIER, 'Барьер');

		self::$items = $arr;
	}


	public function code($c)
	{
		return self::$items[$c];
	}
	
	
}

