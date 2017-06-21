<?php
StrikeType2::initArr();

class StrikeType2{
	
	var   $code
		, $name
		;


    const MAIN = 'main';
    const BARRIER_UP = 'barrier_up';
    const BARRIER_DOWN = 'barrier_down';

		
	static $items;
	
	
	function  __construct($code, $name)
	{
		$this->code=$code;
		$this->name=$name;
	}
	
	
	public  function initArr()
	{
        $arr[self::MAIN] = new self(self::MAIN, 'ГЛАВНЫЙ');
        $arr[self::BARRIER_UP] = new self(self::BARRIER_UP, 'Барьер ВВЕРХ');
        $arr[self::BARRIER_DOWN] = new self(self::BARRIER_DOWN, 'Барьер ВНИЗ');

		self::$items = $arr;
	}


	public function code($c)
	{
		return self::$items[$c];
	}
	
	
}

