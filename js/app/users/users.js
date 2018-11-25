function Users()
{
	var pageId;
	this.Init = function()
	{
		$('.impersonate-user').click(function(){
			frm.FormSubmitAction('Impersonate', $(this).data('id'));
		});
		
		if ($('#ddlSideSearchHotel').length > 0)
			initAutocomplete();
	}
	
	function initAutocomplete()
	{
		var dataSource = propertyList;
		_.forEach(dataSource, function(item) {
			item.text = item.value;
			item.id = parseInt(item.id);
			item.value = null;
		});
		
		if ($('#ddlSideSearchHotel').length > 0) {
			pagingPlugin = new Select2PagingPlugin();
			pagingPlugin.init('#ddlSideSearchHotel', dataSource);
		
			$('#ddlSideSearchHotel').on('select2:select', function(item) {
				$('#frmSearch').submit();
			});
		}
	}
}
var ctlUsers = new Users();
ctlUsers.Init();