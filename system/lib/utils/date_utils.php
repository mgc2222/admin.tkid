<?php 
class DateUtils
{	
	/**
	 * romanian date
	 *
	 * @param string $date - data in format mysql
	 * @return string - data in formar romanesc
	 */
	static function MysqlToRomanianDate($date, $addTime = false)
	{	
		if (isset($date) && $date != '')
		{
			$time = '';
			if (strpos($date, ' ') > 0)
			{
				list($date, $time) = explode(' ', $date);
				$time = ' '.$time;
			}
			
			list($y, $m, $d) = explode("-", $date);

			if (checkdate($m,$d,$y))
			{
				$ret = $d."-".$m."-".$y;
				if ($addTime) $ret = $ret.$time;
				return $ret;
			}
			else
				return null;
		}
		return null;
	}
	
	static function RomanianToMysqlDate($date, $addTime = true)
	{               
		if (isset($date) && $date != '')
		{
			$time = '';
			if (strpos($date, ' ') > 0)
			{
				list($date, $time) = explode(' ', $date);
				$time = ' '.$time;
			}
			
			list($d, $m, $y) = explode("-", $date);
			if (checkdate($m,$d,$y))
			{
				$ret = $y."-".$m."-".$d;
				if ($addTime) $ret = $ret.$time;
				return $ret;
			}
			else
				return null;
		}
		return null;
	}
	
	static function RomanianDate($date, $addTime = false)
	{	
		if (isset($date) && $date != '')
		{
			$time = '';
			if (strpos($date, ' ') > 0)
			{
				list($date, $time) = explode(' ', $date);
				$time = ' '.$time;
			}
			
			list($y, $m, $d) = explode("-", $date);
			$ret = $d.".".$m.".".$y;
			if ($addTime)
				$ret .= $time;
			if (checkdate($m,$d,$y))
				return $ret;
			else
				return null;
		}
		return null;
	}
	
	/**
	* @desc verifica o data on format mysql -- yyyy-mm-dd daca este valida 
	*/
	static function IsValidDate($data)
	{
		if (isset($data) && $data !='')
		{                                  
			list($y,$m,$d) = explode("-",$data);
			
			if (!checkdate($m,$d,$y))
				return false;
				
			return true;            
		}
	}
	
	static function MysqlToRomanianDisplayDate($date, $addTime = false)
	{	
		if (isset($date) && $date != '')
		{
			$time = '';
			if (strpos($date, ' ') > 0)
			{
				list($date, $time) = explode(' ', $date);
				$time = ' '.$time;
			}
			
			list($y, $m, $d) = explode("-", $date);

			if (checkdate($m,$d,$y))
			{
				$ret = $d." ".self::GetRomanianMonth($m)." ".$y;
				if ($addTime) $ret = $ret.$time;
				return $ret;
			}
			else
				return null;
		}
		return null;
	}
	
	static function GetRomanianDay($dayIndex, $getShortName = false)
	{
		$retVal = '';
		switch ($dayIndex)
		{                 
			case 0: $retVal = ($getShortName)?'Dum':'Duminica'; break;
			case 1: $retVal = ($getShortName)?'Lun':'Luni'; break;
			case 2: $retVal = ($getShortName)?'Mar':'Marti'; break;
			case 3: $retVal = ($getShortName)?'Mie':'Miercuri'; break;
			case 4: $retVal = ($getShortName)?'Joi':'Joi'; break;
			case 5: $retVal = ($getShortName)?'Vin':'Vineri'; break;
			case 6: $retVal = ($getShortName)?'Sam':'Sambata'; break;
			case -1: $retVal = 'Toate Zilele'; break;
		}
		
		return $retVal;
	}
	
	static function GetRomanianMonth($monthIndex)
	{
		$retVal = '';
		switch ($monthIndex)
		{                 
			case 1: $retVal = 'Ianuarie'; break;
			case 2: $retVal = 'Februarie'; break;
			case 3: $retVal = 'Martie'; break;
			case 4: $retVal = 'Aprilie'; break;
			case 5: $retVal = 'Mai'; break;
			case 6: $retVal = 'Iunie'; break;
			case 7: $retVal = 'Iulie'; break;
			case 8: $retVal = 'August'; break;
			case 9: $retVal = 'Septembrie'; break;
			case 10: $retVal = 'Octombrie'; break;
			case 11: $retVal = 'Noiembrie'; break;
			case 12: $retVal = 'Decembrie'; break;
		}
		
		return $retVal;
	} 
	
	static function DateDiff($dateStart, $dateEnd) 
	{
        $timeStart = strtotime($dateStart);
        $timeEnd = strtotime($dateEnd);
        $diff = $timeEnd - $timeStart;
        return round($diff / 86400);
    }
	
	static function DateAdd($dateStart, $units) 
	{
        $timeStart = strtotime($dateStart);
		$second = isset($units['second'])?$units['second']:0;
		$minute = isset($units['minute'])?$units['minute']:0;
		$hour = isset($units['hour'])?$units['hour']:0;
		$day = isset($units['day'])?$units['day']:0;
		$month = isset($units['month'])?$units['month']:0;
		$year = isset($units['year'])?$units['year']:0;
		
		return date('Y-m-d H:i:s', mktime($hour,$minute,$second,date('m',$timeStart)+$month,date('d',$timeStart) + $day,date('Y',$timeStart) + $year)); 
    }
	
	static function DateDiffUnitFull($datefrom, $dateto, $unit, $using_timestamps = false)
	{
		/*
		$unit can be:
		Y - Number of full years
		q - Number of full quarters
		m - Number of full months
		z - Difference between day numbers
			(eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
		d - Number of full days
		w - Number of full weekdays
		W - Number of full weeks
		h - Number of full hours
		n - Number of full minutes
		s - Number of full seconds (default)
		*/
			
		if (!$using_timestamps) 
		{
			$datefrom = strtotime($datefrom, 0);
			$dateto = strtotime($dateto, 0);
		}
		
		$difference = $dateto - $datefrom; // Difference in seconds
		 
		switch($unit) 
		{
			case 'Y': // Number of full years
				$years_difference = floor($difference / 31536000);
				if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom)+$years_difference) > $dateto) {
					$years_difference--;
				}
				if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto)-($years_difference+1)) > $datefrom) {
					$years_difference++;
				}
				$datediff = $years_difference;
			break;
			case "q": // Number of full quarters
				$quarters_difference = floor($difference / 8035200);
				while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($quarters_difference*3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
					$months_difference++;
				}
				$quarters_difference--;
				$datediff = $quarters_difference;
			break;
			case "m": // Number of full months
				$months_difference = floor($difference / 2678400);
				while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
					$months_difference++;
				}
				$months_difference--;
				$datediff = $months_difference;
			break;
			case "W2": // Number of weeks
				$weeks_difference = floor($difference / 604800);
				$daysDiff = 7;
				while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("d", $dateto)+$daysDiff, date("Y", $datefrom)) < $dateto) {
					$weeks_difference++;
					$daysDiff += 7;
				}
				$weeks_difference--;
				$datediff = $weeks_difference;
			break;
			case 'z': // Difference between day numbers
				$datediff = date("z", $dateto) - date("z", $datefrom);
			break;
			case "d": // Number of full days
				$datediff = floor($difference / 86400);
			break;
			case "w": // Number of full weekdays
				$days_difference = floor($difference / 86400);
				$weeks_difference = floor($days_difference / 7); // Complete weeks
				$first_day = date("w", $datefrom);
				$days_remainder = floor($days_difference % 7);
				$odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?
				if ($odd_days > 7) { // Sunday
					$days_remainder--;
				}
				if ($odd_days > 6) { // Saturday
					$days_remainder--;
				}
				$datediff = ($weeks_difference * 5) + $days_remainder;
			break;
			case "W": // Number of full weeks
				$datediff = floor($difference / 604800);
			break;
			case "h": // Number of full hours
				$datediff = floor($difference / 3600);
			break;
			case "n": // Number of full minutes
				$datediff = floor($difference / 60);
			break;
			default: // Number of full seconds (default)
				$datediff = $difference;
			break;
		}    
		return $datediff;
	}
	
	/*
	$unit can be:
	Y - Number of years 
	m - Number of months
	WS - Number of weeks starting sunday
	WM - Number of weeks starting monday
	*/
	// return how many dates units are between the two dates, including start and end date
	static function DatesUnits($datefrom, $dateto, $unit, $using_timestamps = false)
	{
		if (!$using_timestamps) 
		{
			$datefrom = strtotime($datefrom, 0);
			$dateto = strtotime($dateto, 0);
		}
		$difference = $dateto - $datefrom;
		
		$unitsCount = 0;
		
		switch($unit) 
		{
			case 'Y': // Number of years
				$startYear = date('Y', $datefrom);
				$endYear = date('Y', $dateto);
				$unitsCount = 1 + $endYear - $startYear;
			break;
			case "m": // Number of months across the dates
				$startYear = date('Y', $datefrom);
				$endYear = date('Y', $dateto);
				$startMonth = date('m', $datefrom);
				$endMonth = date('m', $dateto);
				$yearsDiff = $endYear - $startYear;
				$unitsCount = 1 + $endMonth + 12 * $yearsDiff - $startMonth;
			break;
			case "WS": // Number of week across the dates, starting sunday
				$startYear = date('Y', $datefrom);
				$endYear = date('Y', $dateto);
				
				$startWeek = strftime('%U', $datefrom);
				$endWeek = strftime('%U', $dateto);
				
				$yearsDiff = $endYear - $startYear;
				$unitsCount = 1 + $endWeek + 53 * $yearsDiff - $startWeek;
			break;
			case "WM": // Number of week across the dates, starting monday
				$startYear = date('Y', $datefrom);
				$endYear = date('Y', $dateto);
				
				$startWeek = date('W', $datefrom);
				$endWeek = date('W', $dateto);
				
				$yearsDiff = $endYear - $startYear;
				$unitsCount = 1 + $endWeek + 53 * $yearsDiff - $startWeek;
			break;
			case 'd': // number of days 
				$unitsCount = 1 + floor($difference / 86400);
			break;
		}
		return $unitsCount;
	}
	
	static function RomanianDateTime($timeStamp)
	{
		return strftime("%d-%m-%Y %H:%M:%S", $timeStamp);
	}
	
	
	static function TimeAgo($time)
	{
		$periods = array("secunde", "minute", "ore", "zile", "saptamani", "luni", "ani", "mai mult de ");
		$lengths = array("60","60","24","7","4.35","12","10");

		$now = time();
		$difference = $now - $time;
		
		if ($difference / 2592000 > 1) // if more than 1 month, don't show anymore
			return ''; 

		for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) 
		{
			$difference /= $lengths[$j];
		}

		$difference = round($difference);

		if($difference == 1) 
		{
			switch ($periods[$j])
			{
				case 'secunde': $difference = 'acum o secunda'; break;
				case 'minute': $difference = 'acum un minut'; break;
				case 'ore': $difference = 'acum o ora'; break;
				case 'zile': $difference = 'ieri'; break;
				case 'saptamani': $difference = 'acum o saptamana'; break;
				case 'luni': $difference = 'acum o luna'; break;
			}
		}
		else $difference = 'acum '.$difference.' '.$periods[$j];

		return "{$difference}";
	}
	
	static function TimeUntil($time)
	{
		$periods = array("secunde", "minute", "ore", "zile", "saptamani", "luni", "ani");
		$lengths = array("60","60","24","7","4.35","12");
		$rests = array();

		$now = time();
		$difference = $time - $now;
		
		if ($difference / 2592000 > 1) // if more than 1 month, don't show anymore
			return ''; 

		$ret = '';
		
		$periodsCount = count($lengths) - 1;
		
		for($pIndex = 0; $pIndex < $periodsCount; $pIndex++) 
		{
			$rest = floor($difference % $lengths[$pIndex]);
			
			$difference /= $lengths[$pIndex];
			
			if ($difference < 0)
				array_push($rests, 0);
			else array_push($rests, $rest);
		}
		
		
		// don't take seconds
		for ($pIndex = $periodsCount - 1; $pIndex >= 1; $pIndex--)
		{
			if ($rests[$pIndex] == 0) continue;
			if ($rests[$pIndex] == 1) 
			{
				switch ($periods[$pIndex])
				{
					case 'secunde': $diffWord = ', o secunda'; break;
					case 'minute': $diffWord = ', un minut'; break;
					case 'ore': $diffWord = ', o ora'; break;
					case 'zile': $diffWord = ', o zi'; break;
					case 'saptamani': $diffWord = ', o saptamana'; break;
					case 'luni': $diffWord = ', o luna'; break;
					case 'ani': $diffWord = ', un an'; break;
				}
				$ret .= $diffWord.' ';
			}
			else $ret .= ', '.$rests[$pIndex].' '.$periods[$pIndex];
		}
		
		$ret = substr($ret, 2);
		
		return $ret;
	}
	
	// $date format: Ymd   (20130412)
	static function AnalyticsDateToShortDay($date)
	{	
		$ret = null;
		if ($date != '' && strlen($date) == 8)
		{
			$y = substr($date, 0, 4);
			$m = substr($date, 4, 2);
			$d = substr($date, 6);

			if (checkdate($m,$d,$y))
			{
				$dayOfWeek = date('w', mktime(0, 0, 0, $m, $d, $y));
				$ret = self::GetRomanianDay($dayOfWeek, true).' '.$d.'.'.$m;
			}
		}
		return $ret;
	}
	
	static function DateToShortDay($date)
	{	
		$ret = null;
		if ($date != '' && strlen($date) >6)
		{
			list($y, $m, $d) = explode('-', $date);

			if (checkdate($m,$d,$y))
			{
				$dayOfWeek = date('w', mktime(0, 0, 0, $m, $d, $y));
				$ret = self::GetRomanianDay($dayOfWeek, true).' '.$d.'.'.$m;
			}
		}
		return $ret;
	}
	
	// get a date by add the $difference to the given startdate
	// difference can be negative, in which case, will substract
	static function GetDateAdd($startDate, $difference, $unit)
	{
		$startTime = strtotime($startDate);
		$month= date("m", $startTime); // Month value
		$day= date("d", $startTime); //today's date
		$year= date("Y", $startTime); // Year value
		
		switch ($unit)
		{
			case 'day': $day += $difference; break;
			case 'month': $month += $difference; break;
			case 'year': $year += $difference; break;
		}
		
		$ret = date('Y-m-d', mktime(0,0,0,$month, $day, $year)); 
		return $ret;
	}
	
	// get a date by add the $difference to the given startdate
	// difference can be negative, in which case, will substract
	static function GetTimeAdd($startDate, $difference, $unit)
	{
		$startTime = strtotime($startDate);
		$month= date("m", $startTime); // Month value
		$day= date("d", $startTime); //today's date
		$year= date("Y", $startTime); // Year value
		
		switch ($unit)
		{
			case 'day': $day += $difference; break;
			case 'month': $month += $difference; break;
			case 'year': $year += $difference; break;
		}
		
		$ret = mktime(0,0,0,$month, $day, $year); 
		return $ret;
	}
	
	static function GetTimeZoneDiffHours($timeZone1 = 'Europe/Bucharest', $timeZone2 = 'UTC')
	{
		$dateTimeZone1 = new DateTimeZone($timeZone1);
		$dateTimeZone2  = new DateTimeZone($timeZone2);
		$dateTime1 = new DateTime("now", $dateTimeZone1);
		
		$timeOffset = $dateTimeZone1->getOffset($dateTime1);
		$timeOffsetHours = $timeOffset/3600;
		
		return $timeOffsetHours;
	}
	
}
?>