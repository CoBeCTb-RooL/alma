<?php
class LevelsOfDay{
	
	var   $date
		, $strikeLines
		;
	
	
	
	
		function getByCurrencyAndDate($currency, $date)
		{
			//vd($dateFrom);
			if($date && Funx::isDateValid($date) && $currency)
			{
				$sql = "SELECT * FROM `".Strike::TBL."` WHERE DATE(dateCreated)='".strPrepare($date)."' AND currency='".strPrepare($currency)."' ";
				$sql.=" ORDER BY strike ASC";
				//vd($sql);
				$qr = DB::query($sql);
				echo mysql_error();
				if(mysql_num_rows($qr))
				{
					while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
						$arr[] = Strike::init($next);
					foreach($arr as $key=>$strike)
					{
						
						$strike->calculateLevel();
						//vd($strike);
							
						$ret[$strike->strike][$strike->type] = $strike;
					}
				}
			}
		
			return $ret;
		}
	
		
		
		
		
		
		
		
		
	
}