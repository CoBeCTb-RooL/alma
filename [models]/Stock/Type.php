<?php
Type::initArr();

class Type{
	
	var   $code
		, $name
		;
		
	
	const BUY = 'buy';
	const SELL = 'sell';

		
	static $items;
	
	
	function  __construct($code, $name)
	{
		$this->code=$code;
		$this->name=$name;
	}
	
	
	public  function initArr()
	{
		$arr[self::BUY] = new Type(self::BUY, 'Buy');
		$arr[self::SELL] = new Type(self::SELL, 'Sell');

		self::$items = $arr;
	
	}


	public function code($c)
	{
		return self::$items[$c];
	}
	
	
}

