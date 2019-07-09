<?php


class V6MaxPainBunch{
	public $id;
    public $title;
    public $dt;
    public $currency;
    private $_strikesData;


	const TBL = 'v6__bunches_maxpain';

	function __construct($arr)
	{
		if(count($arr))
		{
			$this->id = $arr['id'];
			$this->title = $arr['title'];
			$this->dt = $arr['dt'];
            $this->currency = Currency::code($arr['currency']);
            $this->data = $arr['data'];
		}
	}




    public function get($id)
    {
        $sql = "SELECT * FROM `".self::TBL."` WHERE id=".intval($id)." ";

        //vd($sql);
        $qr = DB::query($sql);
        echo mysql_error();
        $next = mysql_fetch_array($qr, MYSQL_ASSOC);

        return new self($next);
    }


	public function getList($params)
	{
		$sql = "SELECT * FROM `".self::TBL."` WHERE 1 ";

        if($params['date'] )
            $sql.=" AND DATE(dt)= DATE('".strPrepare($params['date'])."') ";

        if($params['currency'] )
            $sql.=" AND currency= '".strPrepare($params['currency']->code)."' ";

        if($params['dateFrom'] )
            $sql.=" AND DATE(dt) >= DATE('".strPrepare($params['dateFrom'])."') ";
        if($params['dateTo'] )
            $sql.=" AND DATE(dt) <= DATE('".strPrepare($params['dateTo'])."') ";

		if($params['orderBy'])
			$sql .= " ORDER BY ".strPrepare($params['orderBy'])." ";
		//vd($sql);
		$qr = DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$ret[] = new self($next);

		return $ret;
	}



	function initItems()
    {
        if(!$this->items)
            $this->strikes = V6MaxPainStrike::getList([
                    'pid' =>$this->id,
                    'orderBy' => 'id asc',
                ]);
    }



	
	function insert()
	{
        $sql = "
        INSERT INTO `".self::TBL."` 
        SET dt = ".($this->dt ? "'".$this->dt."'" : "NOW()").", 
        ".$this->innerAlterSql()."   
        ";
        //vd($sql);
        DB::query($sql);
        echo mysql_error();
        $this->id = mysql_insert_id();
	}




    function update()
    {
        $sql = "
			UPDATE `".self::TBL."` 
			SET    
            ".$this->innerAlterSql()."
            WHERE id=".intval($this->id)." ";
        //vd($sql);
        DB::query($sql);
        echo mysql_error();
    }



    function innerAlterSql()
    {
        $str="
		     `title` = '".strPrepare($this->title)."'
            , currency = '".strPrepare($this->currency->code)."'
            , `data` = '".strPrepare($this->data)."'
            ";

        return $str;
    }



    function validate()
    {
        $errors = null;

        if(!$this->dt)
            $errors[] = new Problem('Не указана дата!');
        if(!$this->currency)
            $errors[] = new Problem('Не указана валюта!');

//        if(!$this->data || mb_strlen($this->data) < 10)
//            $errors[] = new Problem('Проблема с инфой! Не передана [data]');

        if(!$this->_strikesData )
            $errors[] = new Problem('Проблема с инфой! Не передана [data]');


        return $errors;
    }





    function getData($arr)
    {
        $this->dt = $arr['date'];
        $this->title = trim($arr['title']);
        $this->currency = Currency::code($arr['currency']);
        $this->data = json_encode($arr, JSON_UNESCAPED_UNICODE);
        $this->_strikesData = $arr['data'];
    }





    public function delete()
    {
        $this->initItems();
        foreach($this->strikes as $val)
            $val->delete();

        $sql = "DELETE FROM `".self::TBL."` where id=".$this->id;
        //vd($sql);
        DB::query($sql);
        echo mysql_error();
    }




    public function calculate()
    {
        #   высчитываем арсеники
        foreach ($this->strikes as $key=>$s)
        {
            foreach ($this->strikes as $key2=>$sInner)
            {
                $this->strikes[$key]->intrinsicValues['call'][$sInner->id] = round(max($s->strike - $sInner->strike, 0), 6);
                $this->strikes[$key]->intrinsicValues['put'][$sInner->id] = round(max($sInner->strike - $s->strike, 0), 6);


            }
        }


        #   высчитываем ТОТАЛ арсеники
        foreach ($this->strikes as $strikeId=>$s)
        {
            $s->totalIntrinsicSum['call'] = 0;
            $s->totalIntrinsicSum['put'] = 0;
            foreach ($this->strikes as $innerStrikeId=>$innerS)
            {
                $s->totalIntrinsicValues['call'][$innerStrikeId] = $s->intrinsicValues['call'][$innerStrikeId] * $innerS->oiCall;
                $s->totalIntrinsicValues['put'][$innerStrikeId] = $s->intrinsicValues['put'][$innerStrikeId] * $innerS->oiPut;

                #   сразу полученное суммируем в сумму арсеников
                $s->totalIntrinsicSum['call'] += $s->totalIntrinsicValues['call'][$innerStrikeId];
                $s->totalIntrinsicSum['put'] += $s->totalIntrinsicValues['put'][$innerStrikeId];
            }
        }



        #   вычисляем максПейн - т.е. минимальные значения
        $this->maxPainCall = 99999999999;
        $this->maxPainPut = 9999999999999;
        foreach ($this->strikes as $strikeId=>$s)
        {
            if($s->totalIntrinsicSum['call'] && $s->totalIntrinsicSum['call'] < $this->maxPainCall )
                $this->maxPainCall = $s->totalIntrinsicSum['call'];
            if($s->totalIntrinsicSum['put'] && $s->totalIntrinsicSum['put'] < $this->maxPainPut )
                $this->maxPainPut = $s->totalIntrinsicSum['put'];
        }



//        vd($this);
    }




//
//    public function assembleDataString()
//    {
//        #   если страйки банча не заинитены
//        if(!$this->strikes)
//            $this->initItems();
//
//        $strikeStringsArr = [];
//        foreach ($this->strikes as $strike)
//            $strikeStringsArr[] = $strike->assembleDataString();
//
//        $strikeString = join("\r\n", $strikeStringsArr);
//
//        $bunchData = json_decode($this->data, true);
//        $bunchData['data'] = $strikeString;
//
//        return  json_encode($bunchData);
//    }


		
}