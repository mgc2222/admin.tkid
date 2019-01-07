<?php
/**
 * Created by PhpStorm.
 * User: MGC
 * Date: 1/4/2019
 * Time: 1:57 AM
 */
class GetAndSaveFBEvents extends AbstractController
{
    private $eventsModel;


    function __construct()
    {
        parent::__construct();

        $this->module = 'cron';
        $this->eventsModel = $this->LoadModel('events', 'events');

    }

    function GetFaceBookEvents(){
        //$facebookevents = _FACEBOOK_GRAPH_API_PATH._FACEBOOK_PAGE_ID."/events/created/?is_draft=true&since=2018&access_token="._FACEBOOK_USER_ACCESS_TOKEN_NEVER_EXPIRE;
        $facebookevents = _FACEBOOK_GRAPH_API_PATH._FACEBOOK_PAGE_ID."/events/created/?is_draft=true&since=".strtotime(' - 1 month')."&until=".strtotime('now')."&access_token="._FACEBOOK_USER_ACCESS_TOKEN_NEVER_EXPIRE;
        $calendarData = json_decode($this->GetContentOutsideDomain($facebookevents), true);
        //echo'<pre>'; print_r($facebookevents);die();
        $data = $this->FormatFacebookJsonResponce($calendarData);
        return $this->SaveFacebookEvents($data);
        //echo'<pre>'; print_r($this->FormatFacebookJsonResponce($calendarData)); die();
    }
    function FormatFacebookJsonResponce($response){
        if(!isset($response['data'])) return;
        $ret = [];
        foreach ($response['data'] as $key=>$val){
            (isset($val['description'])) ? $ret[$key]['description'] = $val['description'] : '';
            (isset($val['start_time'])) ? $ret[$key]['event_start_unix_milliseconds'] = $this->GetTimestampInMilliseconds($val['start_time']) : '';
            (isset($val['end_time'])) ? $ret[$key]['event_end_unix_milliseconds'] = $this->GetTimestampInMilliseconds($val['end_time']) : '';
            (isset($val['name'])) ? $ret[$key]['title'] = $val['name'] : '';
            (isset($val['id'])) ? $ret[$key]['event_external_id'] = $val['id'] : '';
            $ret[$key]['event_type_id'] = 2; // event_type_id = 2 is facebook in event_types table
            $ret[$key]['status'] = 1; // set default status to published status = 1
            $ret[$key]['event_css_class_id'] = 4; // set default css color to event-info event_css_class_id = 4
        }
        return $ret;
    }
    function GetTimestampInMilliseconds($dateString){
        $date = new DateTime($dateString);
        //$date = new DateTime(DateTime::createFromFormat('Y-m-d H:i:s', $dateString));
        //print_r($date);die;
        return strval($date->format('Uu')/1000);//die;
        //return strval($date->getTimestamp()*1000);
    }

    private function GetContentOutsideDomain($URL){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $URL);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    private function SaveFacebookEvents($data){
        $res = $this->eventsModel->InsertOrUpdateFacebookEvents($data);
        echo '<pre>';echo json_encode($res);echo'</pre>';//die();
    }
}