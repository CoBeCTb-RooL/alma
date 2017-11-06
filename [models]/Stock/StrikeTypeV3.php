<?php
StrikeTypeV3::initArr();
class StrikeTypeV3{
	
	var   $code
		, $name
		;


    const MAIN = 'main';
    const INNER = 'inner';
    const OUTER = 'outer';

		
	static $items;
	
	
	function  __construct($code, $name)
	{
		$this->code=$code;
		$this->name=$name;
	}
	
	
	public  function initArr()
	{
        $arr[self::MAIN] = new self(self::MAIN, 'ГЛАВНЫЙ');
        $arr[self::INNER] = new self(self::INNER, 'Inner');
        $arr[self::OUTER] = new self(self::OUTER, 'Outer');

		self::$items = $arr;
	}


	public function code($c)
	{
	    if($q = self::$items[$c])
	        return new self($q->code, $q->name);

		//return self::$items[$c];
	}


	public function all()
    {
        return self::$items;
    }
	
	
}

