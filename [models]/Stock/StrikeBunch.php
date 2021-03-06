<?php

/*  for alma v3 */

class StrikeBunch{
	public $id;
    public $title;
    public $dt;
    public $status;
    public $currency;


	
	/*public $currencyStr;
	public $typeStr;*/


	const TBL = 'v3_strike_bunches';

	function __construct($arr)
	{
		if(count($arr))
		{
		    Status::$items;
			$this->id = $arr['id'];
			$this->title = $arr['title'];
			$this->dt = $arr['dt'];
			$this->data = $arr['data'];
			$this->status = Status2::code($arr['status']);
			$this->showOnGraphic = intval($arr['showOnGraphic']);
			$this->currency = Currency::code($arr['currency']);
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

        if($params['dt'] )
            $sql.=" AND DATE(dt)= DATE('".strPrepare($params['dt'])."') ";

        if($params['currency'] )
            $sql.=" AND currency= '".strPrepare($params['currency']->code)."' ";

        if($params['dateFrom'] )
            $sql.=" AND DATE(dt) >= DATE('".strPrepare($params['dateFrom'])."') ";
        if($params['dateTo'] )
            $sql.=" AND DATE(dt) <= DATE('".strPrepare($params['dateTo'])."') ";

		if($params['showOnGraphic'] )
			$sql.=" AND showOnGraphic = ".$params['showOnGraphic'] ? 1 : 0;

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
            $this->items = OAItem::getList([
                    'bunchId' =>$this->id,
                    'orderBy' => 'strikeType, type',
                ]);
    }


    public function row($strikeType, $type)
    {
        if(!$this->items)
            $this->initItems();

        foreach($this->items as $item)
            if($item->strikeType->code == $strikeType && $item->type->code == $type)
                return $item;
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
            , `status` = '".strPrepare($this->status->code)."'
            , currency = '".strPrepare($this->currency->code)."'
            , `data` = '".strPrepare($this->data)."'
            , `showOnGraphic` = '".($this->showOnGraphic ? 1 : 0)."'
            ";

        return $str;
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