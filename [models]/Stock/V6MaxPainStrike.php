<?php


class V6MaxPainStrike{
	public $id;
	public $pid;
    public $strike;
    public $currency;
    public $dt;
	public $oiCall;
	public $oiPut;

	public $data;


	const TBL = 'v6__strikes_maxpain';

	function __construct($arr)
	{
		if(count($arr))
		{
			$this->id = $arr['id'];
			$this->pid = $arr['pid'];
			$this->dt = $arr['dt'];

            $this->currency = Currency::code($arr['currency']);

			$this->strike = strikeVal($arr['strike']);
            $this->oiCall = $arr['oiCall'];
            $this->oiPut = $arr['oiPut'];

			if($this->currency->code == Currency::CODE_JPY)
			{
				$this->strike = strikeVal($arr['strike'], 6);
			}

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

        if($next)
        	return new self($next);
    }


	public function getList($params)
	{
		$sql = "SELECT * FROM `".self::TBL."` WHERE 1 ";

		if($params['currency'] )
			$sql.=" AND `currency`= '".strPrepare($params['currency']->code)."' ";
		if($params['pid'] )
			$sql.=" AND `pid`= '".intval($params['pid'])."' ";
		if($params['date'] )
			$sql.=" AND DATE(dt)= DATE('".strPrepare($params['date'])."') ";

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
			$ret[$next['id']] = new self($next);

		return $ret;
	}









    public function delete()
    {
        $sql = "DELETE FROM `".self::TBL."` where id=".$this->id;
//        vd($sql);
//        return;
        DB::query($sql);
        echo mysql_error();
    }





    function insert()
	{
		if($this->strike)
		{
			$sql = "
			INSERT INTO `".self::TBL."` 
			SET   
			 ".$this->innerAlterSql()."";
//			vd($sql);
			DB::query($sql);
			echo mysql_error();
			$this->id = mysql_insert_id();
		}
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
		  dt = '".strPrepare($this->dt)."'
				, currency = '".strPrepare($this->currency->code)."'
				, pid = '".intval($this->pid)."'
				, strike = '".($this->strike)."'
				, oiCall = '".intval($this->oiCall)."'
				, oiPut = '".intval($this->oiPut)."'
				, `data` = '".strPrepare($this->data)."'
				";

        return $str;
    }








}