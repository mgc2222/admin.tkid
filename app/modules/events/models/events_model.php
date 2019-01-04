<?php 
class EventsModel extends AbstractModel
{	
	function __construct()
	{
		parent::__construct();
		$this->table = 'events';
		$this->tableEventTypes = 'event_types';
		$this->tableEventCssClasses = 'event_css_classes';
		$this->verifiedTableField = 'id';
		$this->primaryKey = 'id';
	}
	
	function SetMapping()
	{
		$this->mapping = array(
		    'title'=>'textTitle',
            'file'=>'txtFile',
            'url_key'=>'txtUrlKey',
            'description'=>'txtDescription',
            'short_description'=>'txtShortDescription',
            'status'=>'chkStatus',
            'event_type_id'=>'txtEventTypeId',
            'event_css_class_id'=>'txtEventCssClassId',
            'event_start_unix_milliseconds'=>'txtEventDateStartInMilliseconds',
            'event_end_unix_milliseconds'=>'txtEventDateEndInMilliseconds');
	}
	
	function GetSqlCondition(&$dataSearch)
	{
		if ($dataSearch == null) return '';
		
		$cond = '';
		if (isset($dataSearch->search) && $dataSearch->search != '') {
			$cond = " WHERE name LIKE '%{$dataSearch->search}%'";
		}

        if (isset($dataSearch->eventType) && $dataSearch->eventType != '') {
            $cond = " WHERE et.name LIKE '%{$dataSearch->eventType}%'";
        }
		
		return $cond;
	}
	
	function GetRecordsList($dataSearch, $orderBy)
	{
		$cond = $this->GetSqlCondition($dataSearch);
		$sql = "SELECT e.*, et.name as event_type, ec.name as event_css_class 
                FROM {$this->table} e 
                LEFT JOIN $this->tableEventTypes et ON e.event_type_id=et.id 
                LEFT JOIN $this->tableEventCssClasses ec ON e.event_css_class_id = ec.id 
                {$cond}";

        $sql .= ($orderBy != null) ? ' ORDER BY '.$orderBy : ' ORDER BY e.event_start_unix_milliseconds';
		
		return $this->dbo->GetRows($sql);
	}
	
	function GetRecordsListCount($dataSearch)
	{
		$cond = $this->GetSqlCondition($dataSearch);
		$sql = "SELECT COUNT(*) FROM {$this->table}	{$cond}";		
		return $this->dbo->GetFieldValue($sql);
	}
	
	function GetRecordsForDropdown($dataSearch = null)
	{
		$cond = $this->GetSqlCondition($dataSearch);
		$orderBy = ' ORDER BY name';
		$sql = "SELECT * FROM {$this->table}	{$cond} {$orderBy}";
		
		return $this->dbo->GetRows($sql);
	}
	
	function GetRecordsIds($dataSearch = null)
	{
		$cond = $this->GetSqlCondition($dataSearch);
		$orderBy = ' ORDER BY id';
		$sql = "SELECT id FROM {$this->table} {$cond} {$orderBy}";
		
		return $this->dbo->GetRows($sql);
	}

	function GetEventsByIds($eventsIds)
	{
		$sql = "SELECT * FROM {$this->table} e WHERE  e.id IN ({$eventsIds})";
		return $this->dbo->GetRows($sql);
	}

    function GetEventById($eventId)
    {
        $sql = "SELECT id FROM {$this->table} e WHERE e.id = {$eventId}";

        return $this->dbo->GetFirstRow($sql);
    }

    function DeleteEventsById($ids)
    {
        $rowsAffected = $this->DeleteSelectedRecords($ids);
        return $rowsAffected;
    }

    function DeleteEventById($id)
    {
        $rowsAffected = $this->DeleteRecord($id);
        return $rowsAffected;
    }

    function GetEventsTypes($dataSearch=null)
    {
        $cond = $this->GetSqlCondition($dataSearch);
        $sql = "SELECT * FROM {$this->tableEventTypes} et {$cond}";
        return $this->dbo->GetRows($sql);
    }

    function GetEventsCssClassesByIds($eventsCssClassesIds)
	{
		$sql = "SELECT * FROM {$this->tableEventCssClasses} ec WHERE  ec.id IN ({$eventsCssClassesIds})";
		return $this->dbo->GetRows($sql);
	}

    function SaveEvent($data)
    {
        $id = $this->InsertOrUpdateById($data, true, false);
        return $id;
    }
	
	function ExtendGetFormData(&$data, &$row)
	{
		$data->chkStatus = ($data->chkStatus > 0) ? 'checked="checked"':'';
		$data->chkDisplaySeparateStatus = ($data->chkDisplaySeparateStatus > 0) ? 'checked="checked"':'';
		$data->txtFile = $row->file;
	}
	
	function ExtendGetFormDataEmpty(&$data)
	{
		$data->chkStatus = 'checked="checked"';
		$data->chkDisplaySeparateStatus = '';
		$data->txtFile = '';
	}
	
	function BeforeSaveData(&$data, &$row)
	{
		//echo'<pre>';print_r($data);echo'</pre>';die; 
		//$row->status = isset($data->chkStatus)? 1: 0;
		//$row->display_separate_status = isset($data->chkDisplaySeparateStatus)? 1: 0;
	}
	
	function UpdateFileName($id, $fileName)
	{
		$this->dbo->UpdateRow($this->table, array('file'=>$fileName), array($this->primaryKey=>$id));
	}
	
	function DeleteFile($recordId, $filePath)
	{
		$this->dbo->DeleteFile($this->table, 'file', $filePath, array(_THUMBS_PATH), array($this->primaryKey=>$recordId));
	}
	
	function AddNew($name, $urlKey)
	{
		return $this->dbo->InsertRow($this->table, array('name'=>$name, 'url_key'=>$urlKey));
	}
    // make additional changes before inserting the data
    function BeforeInsertData(&$data)
    {
        //echo '<pre>'; print_r($data);die();
        if(!isset($data['event_type_id'])){
            $data['event_type_id'] = 1; // means that new event is created from local source event_type_id = 1
        }
    }

    function BeforeUpdateData(&$data)
    {
       //
    }
}
?>