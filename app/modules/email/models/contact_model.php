<?php 
class ContactModel extends AbstractModel
{	
	function __construct()
	{
		parent::__construct();
		$this->table = 'site_contact';
		$this->primaryKey = 'id';
	}
	
	function AddContact(&$data)
	{
		$this->dbo->InsertRow($this->table, $data);
	}
}
?>