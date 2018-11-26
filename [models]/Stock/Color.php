<?php
Color::initArr();
class Color{
	
	var   $code
		, $title
        , $color
        , $bgColor
        , $num
		;


    const BLACK       = 'black';
    const RED         = 'red';
    const GREEN       = 'green';
    const LIGHT_RED   = 'light_red';
    const LIGHT_GREEN = 'light_green';

		
	static $items;
	
	
	function  __construct($code, $title, $color, $bgColor, $num)
	{
		$this->code = $code;
		$this->title = $title;
		$this->color = $color;
		$this->bgColor = $bgColor;
		$this->num = $num;
	}

	
	public  function initArr()
	{
        $arr[self::LIGHT_RED] = new self(self::LIGHT_RED, 'Дельта-Красный', '#FA7575', '#FCF0F0',  0);
        $arr[self::RED] = new self(self::RED, 'Красный', 'red', '#F5C1C1',  1);
        $arr[self::BLACK] = new self(self::BLACK, 'Чёрный', 'black', '#CCCCCC', 2);
        $arr[self::GREEN] = new self(self::GREEN, 'Зелёный', 'green', '#C6F0C2',  3);
        $arr[self::LIGHT_GREEN] = new self(self::LIGHT_GREEN, 'Дельта-Зелёный', '#87F57D', '#ECFFEB',  4);

		self::$items = $arr;
	}


    public function code($c)
    {
        return $c && self::$items[$c] ? self::$items[$c] : self::none();
    }


    public function num($n)
    {
        $ret = self::none();
        foreach (self::$items as $item)
            if($item->num == $n)
                $ret = $item;
        return $ret;
    }


	public function all()
    {
        return self::$items;
    }


    public static function none()
    {
        return new self(null, null, null, null);
    }
	
}

