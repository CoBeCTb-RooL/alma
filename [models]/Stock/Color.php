<?php
Color::initArr();
class Color{
	
	var   $code
		, $title
        , $color
        , $bgColor
        , $num
		;


    const BLACK     = 'black';
    const RED       = 'red';
    const GREEN     = 'green';

		
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
        $arr[self::RED] = new self(self::RED, 'Красный', 'red', '#F5C1C1',  0);
        $arr[self::BLACK] = new self(self::BLACK, 'Чёрный', 'black', '#CCCCCC', 1);
        $arr[self::GREEN] = new self(self::GREEN, 'Зелёный', 'green', '#C6F0C2',  2);

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

