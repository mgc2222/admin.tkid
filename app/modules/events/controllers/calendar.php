<?php
class Calendar extends AdminController
{

	function GetJsonData()
	{
	    /*$facebookevents = _FACEBOOK_GRAPH_API_PATH._FACEBOOK_PAGE_ID."/events/created/?is_draft=true&since=2018&access_token="._FACEBOOK_USER_ACCESS_TOKEN_NEVER_EXPIRE;
		$calendarData = json_decode($this->get_content($facebookevents), true);
        echo json_encode($this->FormatFacebookJsonResponce($calendarData));die();*/
		$content = file_get_contents('bootstrap_calendar/events.json');
		echo $content; die();

	}
	function FormatFacebookJsonResponce($response){
	    if(!isset($response['data'])) return;
	    $ret = [];
	    $ret['success'] = 0;
	    $cssClasses = array("event-important", "event-info", "event-warning", "event-inverse", "event-success", "event-special");
	    foreach ($response['data'] as $key=>$val){
	        (isset($val['description'])) ? $ret['result'][$key]['description'] = $val['description'] : '';
	        (isset($val['start_time'])) ? $ret['result'][$key]['start'] = $this->GetTimestampInMilliseconds($val['start_time']) : '';
	        (isset($val['end_time'])) ? $ret['result'][$key]['end'] = $this->GetTimestampInMilliseconds($val['end_time']) : '';
	        (isset($val['name'])) ? $ret['result'][$key]['title'] = $val['name'] : '';
	        (isset($val['id'])) ? $ret['result'][$key]['id'] = $val['id'] : '';
	        $ret['result'][$key]['class'] = $cssClasses[array_rand($cssClasses, 1)];
	        $ret['result'][$key]['url'] = '';
        }
        (isset($ret['result']))? $ret['success'] = 1 : '';
        return $ret;
    }
    function GetTimestampInMilliseconds($dateString){
	    $date = new DateTime($dateString);
	    //$date = new DateTime(DateTime::createFromFormat('Y-m-d H:i:s', $dateString));
	    //print_r($date);die;
	    return strval($date->format('Uu')/1000);//die;
	    //return strval($date->getTimestamp()*1000);
    }

	private function get_content($URL){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $URL);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    function GetMonthTemplate()
    {
        $calendarMonthData = file_get_contents('bootstrap_calendar/tmpls/month.html');
        //die($calendarMonthData);
        echo $calendarMonthData; die();
    }

    function GetDayTemplate()
    {
        $calendarDayData = file_get_contents('bootstrap_calendar/tmpls/day.html');
        //die($calendarDayData);
        echo $calendarDayData; die();
    }

    function GetModalTemplate()
    {
        $calendarModalData = file_get_contents('bootstrap_calendar/tmpls/modal.html');
        echo $calendarModalData; die();
    }
    function GetModalTitleTemplate()
    {
        $calendarModalData = file_get_contents('bootstrap_calendar/tmpls/modal-title.html');
        echo $calendarModalData; die();
    }

    function GetMonthDayTemplate()
    {
        $calendarMonthDayData = file_get_contents('bootstrap_calendar/tmpls/month-day.html');
        echo $calendarMonthDayData; die();
    }

    function GetWeekTemplate()
    {
        $calendarWeekData = file_get_contents('bootstrap_calendar/tmpls/week.html');
        echo $calendarWeekData; die();
    }

    function GetWeekDaysTemplate()
    {
        $calendarWeekDaysData = file_get_contents('bootstrap_calendar/tmpls/week-days.html');
        echo $calendarWeekDaysData; die();
    }

    function GetYearTemplate()
    {
        $calendarYearData = file_get_contents('bootstrap_calendar/tmpls/year.html');
        echo $calendarYearData; die();
    }

    function GetYearMonthTemplate()
    {
        $calendarYearMonthData = file_get_contents('bootstrap_calendar/tmpls/year-month.html');
        echo $calendarYearMonthData; die();
    }

    function GetEventsListTemplate()
    {
        $calendarEventsListData = file_get_contents('bootstrap_calendar/tmpls/events-list.html');
        echo $calendarEventsListData; die();
    }

}
?>
