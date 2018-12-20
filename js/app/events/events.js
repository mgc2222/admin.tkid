function Events()
{
	this.Init = function()
	{
		//initSortable();
		initControls();
		//initPopovers();
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
        $('#add-events-modal').on('open.bs.modal', function (e) {
            $(this)
                .find("input,textarea,select")
                .val('')
                .end()
                .find("input[type=checkbox], input[type=radio]")
                .prop("checked", "")
                .end();
        })
	}
	function initPopovers()
	{
        var popOverSettings = {
            placement: 'top',
            container: 'body',
            //html: true,
            //trigger: 'manual',
            selector: '[data-toggle="popover"]', //Specify the selector here
			/*content: function () {
			 return $('#popover-content').html();
			 }*/
        };

        $('#calendar').popover(popOverSettings);
		$('body').on('click', function (e) {
            var calendarCell = $('.calendar-event');
            var popovers = $('.popover');
            var tooltips = $('.tooltip');
            if (calendarCell.has(e.target).length === 0 && !calendarCell.is(e.target) && popovers.length) {
                $('[data-toggle="popover"]').popover('destroy');
                popovers.remove();
            }
            if(tooltips.length){
                tooltips.remove();
            }
        });
	}
}
var objCategories = new Events();
objCategories.Init();