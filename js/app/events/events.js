function Events()
{
	this.Init = function()
	{
		initSortable();
		initControls();
	}
	
	function initSortable()
	{
		var objSortableWrapper = new SortableWrapper();
		var categoryId = objSortableWrapper.GetParameterByName('id');
		var queryParams = { id: categoryId }
		var options = { tablename:'sortable', urlServer: SITE_RELATIVE_URL + 'events', colorRowOdd:'eeeeee', colorRowEven:'FEFEFE', queryParams:queryParams};
		objSortableWrapper.SetOptions(options);
		objSortableWrapper.Init();
		
		$('.grid_view tr').addClass('allow_drag'); // class to show hand cursor
	}
	
	function initControls()
	{
		$('#chkAll').click(function() {
			htmlCtl.ToggleCheckboxes('chkAll','multi_checkbox');
		});
	}
}
var objCategories = new Events();
objCategories.Init();