<?php
Status2::initArr();
class Status2{
	
	var   $code
		, $title
		;


    const NEUTRAL   = 'neutral';
    const ACTIVE    = 'active';
    const DONE      = 'done';

		
	static $items;
	
	
	function  __construct($code, $title)
	{
		$this->code = $code;
		$this->title = $title;
	}
	
	
	public  function initArr()
	{
        $arr[self::NEUTRAL] = new self(self::NEUTRAL, 'Нейтрален');
        $arr[self::ACTIVE] = new self(self::ACTIVE, 'Активен');
        $arr[self::DONE] = new self(self::DONE, 'Выполнен');

		self::$items = $arr;
	}


	public function code($c)
	{
	    /*if($q = self::$items[$c])
	        return new self($q->code, $q->name);*/

		return self::$items[$c];
	}


	public function all()
    {
        return self::$items;
    }
	
	
}

