<?php
Currency::initCurrenciesArr();

class Currency{
	
	var   $code
		, $name
		, $sign
		;
		
	
	const CODE_EUR = 'EUR';	
	const CODE_GBP = 'GBP';
	const CODE_CAD = 'CAD';
	const CODE_AUD = 'AUD';
	const CODE_NZD = 'NZD';
	const CODE_CHF = 'CHF';
	const CODE_JPY = 'JPY';
	
		
	static $items;
	
	
	function  __construct($code, $name, $sign)
	{
		$this->code=$code;
		$this->name=$name;
		$this->sign=$sign;
	}	
	
	
	public  function initCurrenciesArr()
	{
		$arr[self::CODE_EUR] = new Currency(self::CODE_EUR, 'Евро', '€');
		$arr[self::CODE_GBP] = new Currency(self::CODE_GBP, 'Фунт стерлингов', '£');
		$arr[self::CODE_CAD] = new Currency(self::CODE_CAD, 'Канадский доллар', 'CA$');
		$arr[self::CODE_AUD] = new Currency(self::CODE_AUD, 'Австралийский доллар', 'AU$');
		$arr[self::CODE_NZD] = new Currency(self::CODE_NZD, 'Новозеландский доллар', 'NZ$');
		$arr[self::CODE_CHF] = new Currency(self::CODE_CHF, 'Швейцарский франк', '₣');
		$arr[self::CODE_JPY] = new Currency(self::CODE_JPY, 'Йена', '¥');

		self::$items = $arr;
	
	}


	public function code($c)
	{
		return self::$items[$c];
	}
	
	
}

