<?php

/*  for alma v3 */

class V5Bunch{
	public $id;
    public $title;
    public $dt;
    public $forward;
    public $openingPrice;
    public $status;
    public $currency;
    private $_strikesData;



	const TBL = 'v5__bunches';

	function __construct($arr)
	{
		if(count($arr))
		{
			$this->id = $arr['id'];
			$this->title = $arr['title'];
			$this->dt = $arr['dt'];
            $this->currency = Currency::code($arr['currency']);
            $this->forward = $arr['forward'] ? $arr['forward'] : 0;
            $this->status = Status2::code($arr['status']);
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
            $this->strikes = V5Strike::getList([
                    'pid' =>$this->id,
                    'orderBy' => 'id asc',
                ]);
    }


//    public function row($strikeType, $type)
//    {
//        if(!$this->items)
//            $this->initItems();
//
//        foreach($this->items as $item)
//            if($item->strikeType->code == $strikeType && $item->type->code == $type)
//                return $item;
//    }


	
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
            , `status` = '".strPrepare($this->status->code)."'
            , currency = '".strPrepare($this->currency->code)."'
            , `forward` = '".floatval($this->forward)."'
            , `openingPrice` = '".floatval($this->openingPrice)."'
            , `data` = '".strPrepare($this->data)."'
            ";

        return $str;
    }



    function validate()
    {
        $errors = null;

        if(!$this->dt)
            $errors[] = new Problem('Не указана дата!');
        if(!$this->forward && $this->forward!=='0')
            $errors[] = new Problem('Не указан форвард!');
        if(!$this->openingPrice)
            $errors[] = new Problem('Не указана цена открытия!');
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
        $this->forward =  $arr['forward'];
        $this->openingPrice =  $arr['openingPrice'];
        $this->title = trim($arr['title']);
        $this->currency = Currency::code($arr['currency']);
        $this->data = json_encode($arr, JSON_UNESCAPED_UNICODE);
        $this->_strikesData = $arr['data'];
    }





    public function delete()
    {
        $this->initItems();
        foreach($this->items as $val)
            $val->delete();

        $sql = "DELETE FROM `".self::TBL."` where id=".$this->id;
        //vd($sql);
        DB::query($sql);
        echo mysql_error();
    }
	
		
}