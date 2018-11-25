function UserPermissions()
{
	this.Init = function()
	{
		$('#ddlUser').change( function() { changeUser($(this).val()); });
		$('#chkAll').click( function() { htmlCtl.ToggleCheckboxes('chkAll', 'chk_page'); });
	}
	
	function changeUser()
	{
		frm.FormSubmitAction('ChangeUser');
	}
}
var ctlUserPermissions = new UserPermissions();
	ctlUserPermissions.Init();
